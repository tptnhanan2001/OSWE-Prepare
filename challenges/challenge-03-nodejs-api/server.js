const express = require('express');
const jwt = require('jsonwebtoken');
const { MongoClient } = require('mongodb');
const bodyParser = require('body-parser');
const cookieParser = require('cookie-parser');
const { exec } = require('child_process');

const app = express();
app.use(bodyParser.json());
app.use(cookieParser());

const JWT_SECRET = process.env.JWT_SECRET || 'secret_key_change_in_production';
const MONGODB_URI = process.env.MONGODB_URI || 'mongodb://mongo:27017/challenge_db';

let db;

// Connect to MongoDB
MongoClient.connect(MONGODB_URI, (err, client) => {
    if (err) {
        console.error('MongoDB connection error:', err);
        return;
    }
    db = client.db('challenge_db');
    console.log('Connected to MongoDB');
});

// Helper function to merge objects (VULNERABLE to Prototype Pollution)
function merge(target, source) {
    for (let key in source) {
        if (source.hasOwnProperty(key)) {
            if (typeof source[key] === 'object' && source[key] !== null && !Array.isArray(source[key])) {
                if (!target[key]) target[key] = {};
                merge(target[key], source[key]);
            } else {
                target[key] = source[key];
            }
        }
    }
    return target;
}

// VULNERABILITY: JWT Algorithm Confusion
// Verifies JWT but doesn't properly check algorithm
function verifyToken(req, res, next) {
    const token = req.headers.authorization?.split(' ')[1] || req.cookies.token;
    
    if (!token) {
        return res.status(401).json({ error: 'No token provided' });
    }
    
    try {
        // VULNERABILITY: Doesn't specify algorithm, allows algorithm confusion
        const decoded = jwt.verify(token, JWT_SECRET);
        req.user = decoded;
        next();
    } catch (err) {
        res.status(401).json({ error: 'Invalid token' });
    }
}

function requireAdmin(req, res, next) {
    if (req.user.role !== 'admin') {
        return res.status(403).json({ error: 'Admin access required' });
    }
    next();
}

// Auth endpoints
app.post('/api/auth/login', async (req, res) => {
    const { username, password } = req.body;
    
    if (!username || !password) {
        return res.status(400).json({ error: 'Username and password required' });
    }
    
    // VULNERABILITY: NoSQL Injection
    // Direct user input in MongoDB query
    const user = await db.collection('users').findOne({
        username: username,
        password: password
    });
    
    if (user) {
        const token = jwt.sign(
            { id: user._id, username: user.username, role: user.role },
            JWT_SECRET,
            { algorithm: 'HS256' }
        );
        res.cookie('token', token, { httpOnly: true });
        res.json({ token, user: { id: user._id, username: user.username, role: user.role } });
    } else {
        res.status(401).json({ error: 'Invalid credentials' });
    }
});

// User endpoints
app.get('/api/users', verifyToken, requireAdmin, async (req, res) => {
    const users = await db.collection('users').find({}).toArray();
    res.json(users.map(u => ({ id: u._id, username: u.username, role: u.role })));
});

app.get('/api/users/:id', verifyToken, async (req, res) => {
    const userId = req.params.id;
    const user = await db.collection('users').findOne({ _id: userId });
    
    if (!user) {
        return res.status(404).json({ error: 'User not found' });
    }
    
    // VULNERABILITY: Authorization Bypass
    // Only checks if user exists, not if requester has permission
    if (req.user.id !== user._id && req.user.role !== 'admin') {
        return res.status(403).json({ error: 'Access denied' });
    }
    
    res.json({ id: user._id, username: user.username, role: user.role });
});

// Product endpoints
app.get('/api/products', async (req, res) => {
    // VULNERABILITY: NoSQL Injection in query parameters
    const query = {};
    if (req.query.name) {
        query.name = req.query.name;
    }
    if (req.query.price) {
        query.price = { $gte: parseFloat(req.query.price) };
    }
    
    const products = await db.collection('products').find(query).toArray();
    res.json(products);
});

app.post('/api/products', verifyToken, requireAdmin, async (req, res) => {
    const { name, description, price } = req.body;
    
    // VULNERABILITY: Prototype Pollution
    // Merges user input without proper sanitization
    const product = { name, description, price };
    const additionalData = req.body.additionalData || {};
    const merged = merge(product, additionalData);
    
    const result = await db.collection('products').insertOne(merged);
    res.json({ id: result.insertedId, ...merged });
});

// Order endpoints
app.post('/api/orders', verifyToken, async (req, res) => {
    const { productId, quantity, shippingAddress } = req.body;
    
    // VULNERABILITY: Command Injection
    // Uses user input in system command without sanitization
    if (shippingAddress) {
        // Simulate address validation
        const command = `echo "Validating address: ${shippingAddress}"`;
        exec(command, (error, stdout, stderr) => {
            // Command executed
        });
    }
    
    const order = {
        userId: req.user.id,
        productId,
        quantity,
        shippingAddress,
        status: 'pending',
        createdAt: new Date()
    };
    
    const result = await db.collection('orders').insertOne(order);
    res.json({ id: result.insertedId, ...order });
});

// Admin endpoints
app.get('/api/admin/flag', verifyToken, requireAdmin, async (req, res) => {
    const flag = await db.collection('flags').findOne({ type: 'admin_flag' });
    res.json({ flag: flag.value });
});

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
    console.log(`Server running on port ${PORT}`);
});

