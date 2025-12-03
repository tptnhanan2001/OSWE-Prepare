# Challenge 5: Java Spring Boot Enterprise App - Multi-Vulnerability Chain

## Mô tả

Đây là một ứng dụng Enterprise được viết bằng Java Spring Boot. Ứng dụng cung cấp REST API để quản lý employees, departments, và reports.

## Mục tiêu

Chain các lỗ hổng để đạt được Remote Code Execution (RCE) và đọc sensitive configuration.

## Learning Objectives

- Java Deserialization (RCE via Commons Collections)
- SQL Injection (Hibernate HQL)
- Path Traversal
- XXE trong XML processing
- Authorization Bypass (Spring Security misconfiguration)

## Setup Instructions

### Yêu cầu
- Docker và Docker Compose
- Hoặc Java 17+, Maven, MySQL

### Cách chạy với Docker

```bash
cd challenge-05-java-spring
docker-compose up -d
```

API sẽ chạy tại: http://localhost:8080

### Cách setup thủ công

1. Build application:
```bash
mvn clean package
```

2. Setup database:
```bash
mysql -u root -p < src/main/resources/schema.sql
```

3. Run application:
```bash
java -jar target/challenge-0.0.1-SNAPSHOT.jar
```

## Default Credentials

- Admin: `admin:admin123`
- User: `user:user123`

## API Endpoints

- `POST /api/auth/login` - Login
- `GET /api/employees` - List employees
- `GET /api/employees/{id}` - Get employee by ID
- `POST /api/reports` - Create report (XML upload)
- `GET /api/admin/config` - Get config (admin only)

## Vulnerabilities

Ứng dụng có các lỗ hổng sau (cần chain để exploit):

1. **Java Deserialization** - RCE via Commons Collections
2. **SQL Injection** - Hibernate HQL injection
3. **Path Traversal** - File access
4. **XXE** - XML External Entity
5. **Authorization Bypass** - Spring Security misconfiguration

## Hints

<details>
<summary>Hint 1</summary>
Có endpoint nào deserialize Java objects không? Có thể exploit Commons Collections?
</details>

<details>
<summary>Hint 2</summary>
Hibernate queries có được parameterized không?
</details>

<summary>Hint 3</summary>
XML processing có disable external entities không?
</details>

## Flag

Flag được lưu trong application.properties hoặc database.

## Solution

Xem `solutions/solution.md` sau khi đã thử exploit.

