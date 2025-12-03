# Challenge 4: Next.js Social Media App - Multi-Vulnerability Chain

## Mô tả

Đây là một ứng dụng Social Media được viết bằng Next.js 14 với TypeScript. Ứng dụng cho phép người dùng đăng bài, follow users, và quản lý profile.

## Mục tiêu

Chain các lỗ hổng để đạt được Remote Code Execution (RCE) và truy cập admin functionality.

## Learning Objectives

- SSRF (Server-Side Request Forgery)
- XXE (XML External Entity)
- GraphQL Injection
- Server-Side Template Injection
- Authentication Bypass (JWT manipulation)

## Setup Instructions

### Yêu cầu
- Docker và Docker Compose
- Hoặc Node.js 18+, npm/yarn

### Cách chạy với Docker

```bash
cd challenge-04-nextjs-app
docker-compose up -d
```

Ứng dụng sẽ chạy tại: http://localhost:3000

### Cách setup thủ công

1. Install dependencies:
```bash
npm install
```

2. Setup database:
```bash
npm run init-db
```

3. Run application:
```bash
npm run dev
```

## Default Credentials

- Admin: `admin:admin123`
- User: `user:user123`

## Vulnerabilities

Ứng dụng có các lỗ hổng sau (cần chain để exploit):

1. **SSRF** - Internal API access
2. **XXE** - XML External Entity injection
3. **GraphQL Injection** - GraphQL query injection
4. **Server-Side Template Injection** - Template injection
5. **Authentication Bypass** - JWT manipulation

## Hints

<details>
<summary>Hint 1</summary>
Có endpoint nào cho phép fetch URL không? Có thể access internal services?
</details>

<details>
<summary>Hint 2</summary>
Có chức năng upload XML không? Có thể exploit XXE?
</details>

<details>
<summary>Hint 3</summary>
GraphQL endpoint có validate input không?
</details>

## Flag

Flag được lưu trong PostgreSQL database, table `flags`, column `value`.

## Solution

Xem `solutions/solution.md` sau khi đã thử exploit.

