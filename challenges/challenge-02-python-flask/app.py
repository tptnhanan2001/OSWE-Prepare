from flask import Flask, render_template, request, redirect, url_for, session, flash, jsonify
import sqlite3
import pickle
import base64
import os
import redis
import requests
from functools import wraps

app = Flask(__name__)
app.secret_key = os.urandom(32)

# Redis connection
redis_client = redis.Redis(host='redis', port=6379, db=0, decode_responses=True)

# Initialize flag in Redis
if not redis_client.exists('admin_flag'):
    redis_client.set('admin_flag', 'OSWE{Pickle_SSRF_SSTI_Chain_Success!}')

def get_db():
    conn = sqlite3.connect('ecommerce.db')
    conn.row_factory = sqlite3.Row
    return conn

def init_db():
    db = get_db()
    db.execute('''
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT UNIQUE NOT NULL,
            password TEXT NOT NULL,
            role TEXT DEFAULT 'user'
        )
    ''')
    
    db.execute('''
        CREATE TABLE IF NOT EXISTS products (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            description TEXT,
            price REAL NOT NULL
        )
    ''')
    
    db.execute('''
        CREATE TABLE IF NOT EXISTS cart (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER,
            product_id INTEGER,
            quantity INTEGER DEFAULT 1,
            FOREIGN KEY (user_id) REFERENCES users(id),
            FOREIGN KEY (product_id) REFERENCES products(id)
        )
    ''')
    
    # Insert default users
    try:
        db.execute("INSERT INTO users (username, password, role) VALUES (?, ?, ?)",
                  ('admin', 'admin123', 'admin'))
        db.execute("INSERT INTO users (username, password, role) VALUES (?, ?, ?)",
                  ('user', 'user123', 'user'))
    except:
        pass
    
    # Insert sample products
    try:
        db.execute("INSERT INTO products (name, description, price) VALUES (?, ?, ?)",
                  ('Laptop', 'High-performance laptop', 999.99))
        db.execute("INSERT INTO products (name, description, price) VALUES (?, ?, ?)",
                  ('Phone', 'Smartphone', 599.99))
    except:
        pass
    
    db.commit()
    db.close()

def require_login(f):
    @wraps(f)
    def decorated_function(*args, **kwargs):
        if 'user_id' not in session:
            return redirect(url_for('login'))
        return f(*args, **kwargs)
    return decorated_function

def require_admin(f):
    @wraps(f)
    def decorated_function(*args, **kwargs):
        if 'user_id' not in session or session.get('role') != 'admin':
            flash('Admin access required')
            return redirect(url_for('index'))
        return f(*args, **kwargs)
    return decorated_function

@app.route('/')
def index():
    db = get_db()
    products = db.execute('SELECT * FROM products').fetchall()
    db.close()
    return render_template('index.html', products=products)

@app.route('/login', methods=['GET', 'POST'])
def login():
    if request.method == 'POST':
        username = request.form.get('username')
        password = request.form.get('password')
        
        db = get_db()
        user = db.execute(
            'SELECT * FROM users WHERE username = ? AND password = ?',
            (username, password)
        ).fetchone()
        db.close()
        
        if user:
            # VULNERABILITY: Insecure Deserialization
            # Session data is pickled and base64 encoded
            # If we can control the session cookie, we can achieve RCE
            session['user_id'] = user['id']
            session['username'] = user['username']
            session['role'] = user['role']
            
            # VULNERABILITY: Session Fixation
            # Session ID is not regenerated after login
            flash('Logged in successfully')
            return redirect(url_for('index'))
        else:
            flash('Invalid credentials')
    
    return render_template('login.html')

@app.route('/logout')
def logout():
    session.clear()
    return redirect(url_for('index'))

@app.route('/product/<int:product_id>')
def product_detail(product_id):
    db = get_db()
    product = db.execute('SELECT * FROM products WHERE id = ?', (product_id,)).fetchone()
    db.close()
    
    if not product:
        flash('Product not found')
        return redirect(url_for('index'))
    
    # VULNERABILITY: Server-Side Template Injection (SSTI)
    # Product description is rendered directly in template
    # If we can control product description, we can inject template code
    return render_template('product.html', product=product)

@app.route('/fetch', methods=['POST'])
@require_login
def fetch_url():
    """Fetch content from a URL"""
    url = request.form.get('url')
    
    if not url:
        return jsonify({'error': 'URL required'}), 400
    
    # VULNERABILITY: SSRF
    # No validation of URL, can access internal services
    # Can be used to access Redis or other internal services
    try:
        # Allow file:// and internal URLs
        response = requests.get(url, timeout=5, allow_redirects=False)
        return jsonify({
            'url': url,
            'status_code': response.status_code,
            'content': response.text[:1000]  # Limit response
        })
    except Exception as e:
        return jsonify({'error': str(e)}), 500

@app.route('/cart')
@require_login
def cart():
    db = get_db()
    cart_items = db.execute('''
        SELECT c.*, p.name, p.price 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?
    ''', (session['user_id'],)).fetchall()
    
    total = sum(item['price'] * item['quantity'] for item in cart_items)
    db.close()
    
    return render_template('cart.html', cart_items=cart_items, total=total)

@app.route('/add_to_cart', methods=['POST'])
@require_login
def add_to_cart():
    product_id = request.form.get('product_id')
    quantity = int(request.form.get('quantity', 1))
    
    # VULNERABILITY: Business Logic Flaw
    # Price can be manipulated via hidden form fields or parameter tampering
    # No server-side validation of price
    
    db = get_db()
    db.execute(
        'INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)',
        (session['user_id'], product_id, quantity)
    )
    db.commit()
    db.close()
    
    flash('Item added to cart')
    return redirect(url_for('cart'))

@app.route('/checkout', methods=['POST'])
@require_login
def checkout():
    # VULNERABILITY: Business Logic - Price Manipulation
    # Price is sent from client, not verified from database
    total = float(request.form.get('total', 0))
    
    # In real app, should recalculate from database
    # Here we trust the client value
    
    flash(f'Order placed! Total: ${total:.2f}')
    
    # Clear cart
    db = get_db()
    db.execute('DELETE FROM cart WHERE user_id = ?', (session['user_id'],))
    db.commit()
    db.close()
    
    return redirect(url_for('index'))

@app.route('/admin')
@require_admin
def admin():
    # Admin panel - contains flag
    flag = redis_client.get('admin_flag')
    return render_template('admin.html', flag=flag)

@app.route('/admin/products')
@require_admin
def admin_products():
    db = get_db()
    products = db.execute('SELECT * FROM products').fetchall()
    db.close()
    return render_template('admin_products.html', products=products)

@app.route('/admin/products/add', methods=['POST'])
@require_admin
def admin_add_product():
    name = request.form.get('name')
    description = request.form.get('description')
    price = float(request.form.get('price'))
    
    db = get_db()
    db.execute(
        'INSERT INTO products (name, description, price) VALUES (?, ?, ?)',
        (name, description, price)
    )
    db.commit()
    db.close()
    
    flash('Product added')
    return redirect(url_for('admin_products'))

if __name__ == '__main__':
    init_db()
    app.run(host='0.0.0.0', port=5000, debug=True)

