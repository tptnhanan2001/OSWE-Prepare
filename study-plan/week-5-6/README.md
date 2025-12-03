# Week 5-6: Advanced Exploitation

## Mục tiêu học tập

Học cách kết hợp nhiều lỗ hổng để đạt mục tiêu khai thác (vulnerability chaining).

## Nội dung

### Day 1-4: Vulnerability Chaining

1. **Chaining Concepts**
   - Authentication bypass → Authorization bypass → RCE
   - Information disclosure → Authentication bypass → Data exfiltration
   - SSRF → Internal service access → RCE

2. **Common Chaining Patterns**
   - SQLi → File read → Credential disclosure → Admin access
   - File upload → Path traversal → RCE
   - Deserialization → SSRF → Internal access

### Day 5-8: Bypassing Security Controls

1. **Input Validation Bypass**
   - Encoding techniques
   - Filter bypass
   - WAF evasion

2. **Authentication Bypass**
   - SQL injection in login
   - JWT manipulation
   - Session fixation

3. **Authorization Bypass**
   - IDOR
   - Parameter tampering
   - Privilege escalation

### Day 9-12: Advanced Attack Techniques

1. **Deserialization Attacks**
   - PHP object injection
   - Python pickle RCE
   - Java deserialization (Commons Collections)
   - Node.js deserialization


2. **Template Injection**
   - Server-Side Template Injection (SSTI)
   - Jinja2, Twig, Smarty
   - Template injection to RCE

3. **XXE (XML External Entity)**
   - File read
   - SSRF via XXE
   - XXE to RCE

### Day 13-14: Practice

- Hoàn thành các challenges trong thư mục `../challenges/`
- Mỗi challenge yêu cầu chaining ít nhất 2-3 lỗ hổng
- Viết exploit scripts hoàn chỉnh

## Bài tập

1. Hoàn thành Challenge 1-3 (PHP, Python, Node.js)
2. Viết exploit script cho mỗi challenge
3. Document quá trình chaining vulnerabilities

## Tài liệu

- OWASP Deserialization Cheat Sheet
- PortSwigger Template Injection
- PayloadsAllTheThings

