# Challenge 3: Node.js REST API - Multi-Vulnerability Chain

## Mô tả

Đây là một REST API được viết bằng Node.js và Express. API cung cấp các endpoints để quản lý users, products, và orders. Sử dụng MongoDB để lưu trữ dữ liệu và JWT cho authentication.

## Mục tiêu

Chain các lỗ hổng để đạt được Remote Code Execution (RCE) và đọc sensitive data từ MongoDB.

## Learning Objectives

- JWT Algorithm Confusion
- Prototype Pollution
- NoSQL Injection
- Command Injection
- Authorization Bypass

## Setup Instructions

### Yêu cầu
- Docker và Docker Compose
- Hoặc Node.js 18+, MongoDB, npm

### Cách chạy với Docker

```bash
cd challenge-03-nodejs-api
docker-compose up -d
```

API sẽ chạy tại: http://localhost:3000

### Cách setup thủ công

1. Install dependencies:
```bash
npm install
```

2. Start MongoDB:
```bash
mongod
```

3. Initialize database:
```bash
node init_db.js
```

4. Run application:
```bash
node server.js
```

## Default Credentials

- Admin: `admin:admin123`
- User: `user:user123`

## API Endpoints

- `POST /api/auth/login` - Login
- `GET /api/users` - List users (admin only)
- `GET /api/users/:id` - Get user by ID
- `POST /api/products` - Create product (admin only)
- `GET /api/products` - List products
- `POST /api/orders` - Create order
- `GET /api/admin/flag` - Get flag (admin only)

## Vulnerabilities

Ứng dụng có các lỗ hổng sau (cần chain để exploit):

1. **JWT Algorithm Confusion** - Bypass authentication
2. **Prototype Pollution** - Modify object prototypes
3. **NoSQL Injection** - MongoDB injection
4. **Command Injection** - RCE via user input
5. **Authorization Bypass** - Access admin endpoints

## Hints

<details>
<summary>Hint 1</summary>
Kiểm tra cách JWT được verify. Có sử dụng algorithm nào?
</details>

<details>
<summary>Hint 2</summary>
Có endpoint nào merge objects từ user input không? Có thể pollute prototype?
</details>

<summary>Hint 3</summary>
MongoDB queries có được sanitize không? Có thể inject NoSQL?
</details>

## Flag

Flag được lưu trong MongoDB collection `flags`, document với `type: 'admin_flag'`.

## Solution

Xem `solutions/solution.md` sau khi đã thử exploit.

