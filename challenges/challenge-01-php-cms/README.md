# Challenge 1: PHP CMS - Multi-Vulnerability Chain

## Mô tả

Đây là một Content Management System (CMS) đơn giản được viết bằng PHP. Ứng dụng cho phép người dùng đăng nhập, quản lý bài viết, và upload/download files.

## Mục tiêu

Chain các lỗ hổng để đạt được Remote Code Execution (RCE) và đọc flag từ database.

## Learning Objectives

- SQL Injection trong authentication
- Insecure File Upload
- Path Traversal
- IDOR (Insecure Direct Object Reference)
- Business Logic Flaws
- Vulnerability Chaining

## Setup Instructions

### Yêu cầu
- Docker và Docker Compose
- Hoặc PHP 8.x, MySQL 8.x, Apache

### Cách chạy với Docker

```bash
cd challenge-01-php-cms
docker-compose up -d
```

Ứng dụng sẽ chạy tại: http://localhost:8080

### Cách setup thủ công

1. Import database:
```bash
mysql -u root -p < database/schema.sql
mysql -u root -p < database/data.sql
```

2. Cấu hình database trong `config.php`

3. Chạy Apache với PHP 8.x

## Default Credentials

- Admin: `admin:admin123`
- User: `user:user123`

## Vulnerabilities

Ứng dụng có các lỗ hổng sau (cần chain để exploit):

1. **SQL Injection** trong authentication
2. **Insecure File Upload** - không validate file type đúng cách
3. **Path Traversal** trong file download
4. **IDOR** - có thể truy cập files của user khác
5. **Business Logic Flaw** - privilege escalation

## Hints

<details>
<summary>Hint 1</summary>
Bắt đầu với authentication. Có cách nào bypass login không?
</details>

<details>
<summary>Hint 2</summary>
Sau khi login, hãy xem xét chức năng upload file. File được lưu ở đâu?
</details>

<details>
<summary>Hint 3</summary>
Có thể kết hợp SQLi để đọc thông tin từ database và sử dụng file upload để đạt RCE không?
</details>

## Flag

Flag được lưu trong database, table `flags`, column `secret_flag`.

## Solution

Xem `solutions/solution.md` sau khi đã thử exploit.

