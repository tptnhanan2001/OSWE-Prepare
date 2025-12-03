# Challenge 2: Python Flask E-Commerce - Multi-Vulnerability Chain

## Mô tả

Đây là một ứng dụng E-Commerce đơn giản được viết bằng Flask. Ứng dụng cho phép người dùng mua sắm, quản lý giỏ hàng, và quản lý sản phẩm (admin).

## Mục tiêu

Chain các lỗ hổng để đạt được Remote Code Execution (RCE) và truy cập admin panel để đọc flag.

## Learning Objectives

- Insecure Deserialization (Pickle RCE)
- SSRF (Server-Side Request Forgery)
- Template Injection (SSTI)
- Authentication Bypass (Session Fixation)
- Business Logic Flaws (Price Manipulation)

## Setup Instructions

### Yêu cầu
- Docker và Docker Compose
- Hoặc Python 3.9+, Flask, SQLite, Redis

### Cách chạy với Docker

```bash
cd challenge-02-python-flask
docker-compose up -d
```

Ứng dụng sẽ chạy tại: http://localhost:5000

### Cách setup thủ công

1. Install dependencies:
```bash
pip install -r requirements.txt
```

2. Initialize database:
```bash
python init_db.py
```

3. Run application:
```bash
python app.py
```

## Default Credentials

- Admin: `admin:admin123`
- User: `user:user123`

## Vulnerabilities

Ứng dụng có các lỗ hổng sau (cần chain để exploit):

1. **Insecure Deserialization** - Pickle RCE trong session/cookie
2. **SSRF** - Internal service access
3. **Template Injection** - SSTI trong product description
4. **Authentication Bypass** - Session fixation
5. **Business Logic Flaw** - Price manipulation

## Hints

<details>
<summary>Hint 1</summary>
Kiểm tra cách ứng dụng xử lý session/cookies. Có sử dụng serialization không?
</details>

<details>
<summary>Hint 2</summary>
Có chức năng nào cho phép fetch URL từ server không? Có thể access internal services?
</details>

<details>
<summary>Hint 3</summary>
Template engine nào được sử dụng? Có thể inject template code không?
</details>

## Flag

Flag được lưu trong Redis, key `admin_flag`.

## Solution

Xem `solutions/solution.md` sau khi đã thử exploit.

