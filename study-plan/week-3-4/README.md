# Week 3-4: Code Review Techniques

## Mục tiêu học tập

Phát triển kỹ năng đọc và phân tích mã nguồn để tìm lỗ hổng bảo mật.

## Nội dung

### Day 1-4: Static Code Analysis

1. **Code Review Methodology**
   - Đọc code từ entry points
   - Trace data flow
   - Identify security controls
   - Find bypass techniques

2. **Common Vulnerable Patterns**
   ```php
   // SQL Injection
   $query = "SELECT * FROM users WHERE id = " . $_GET['id'];
   
   // Command Injection
   exec("ping " . $_POST['host']);
   
   // File Upload
   move_uploaded_file($_FILES['file']['tmp_name'], $_FILES['file']['name']);
   ```

3. **Tools for Code Review**
   - grep/ripgrep
   - Semgrep
   - CodeQL
   - SonarQube

### Day 5-8: Application Architecture

1. **MVC Pattern**
   - Model-View-Controller
   - Routing mechanisms
   - Middleware

2. **Database Interactions**
   - ORM vulnerabilities
   - Raw SQL queries
   - NoSQL injection

3. **File Handling**
   - Upload mechanisms
   - Path traversal
   - File inclusion

### Day 9-12: Language-Specific Vulnerabilities

1. **PHP**
   - Type juggling
   - Magic methods
   - Serialization

2. **Python**
   - Pickle deserialization
   - Template injection
   - YAML parsing

3. **Node.js**
   - Prototype pollution
   - eval() usage
   - Deserialization

4. **Java**
   - Java deserialization
   - Expression Language (EL)
   - Reflection

### Day 13-14: Practice

- Review source code của các ứng dụng mã nguồn mở
- Tìm lỗ hổng trong các CTF challenges
- Practice với challenges trong thư mục `../challenges/`

## Bài tập

1. Review code của một CMS đơn giản và tìm ít nhất 5 lỗ hổng
2. Viết exploit cho các lỗ hổng tìm được
3. Document quá trình code review

## Tài liệu

- OWASP Code Review Guide
- Secure Coding Practices
- Language-specific security guides

