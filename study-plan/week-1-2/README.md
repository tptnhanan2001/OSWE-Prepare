# Week 1-2: Web Security Fundamentals

## Mục tiêu học tập

Nắm vững kiến thức cơ bản về bảo mật web application và các lỗ hổng phổ biến.

## Nội dung

### Day 1-3: OWASP Top 10 (2021)

1. **A01:2021 – Broken Access Control**
   - IDOR (Insecure Direct Object Reference)
   - Privilege escalation
   - JWT vulnerabilities

2. **A02:2021 – Cryptographic Failures**
   - Weak encryption
   - Sensitive data exposure
   - Insecure random number generation

3. **A03:2021 – Injection**
   - SQL Injection
   - NoSQL Injection
   - Command Injection
   - LDAP Injection

4. **A04:2021 – Insecure Design**
   - Business logic flaws
   - Missing security controls

5. **A05:2021 – Security Misconfiguration**
   - Default credentials
   - Exposed debug information
   - Insecure headers

6. **A06:2021 – Vulnerable and Outdated Components**
   - Dependency vulnerabilities
   - Known CVEs

7. **A07:2021 – Identification and Authentication Failures**
   - Weak passwords
   - Session fixation
   - Brute force vulnerabilities

8. **A08:2021 – Software and Data Integrity Failures**
   - Insecure deserialization
   - CI/CD pipeline vulnerabilities

9. **A09:2021 – Security Logging and Monitoring Failures**
   - Insufficient logging
   - Missing security events

10. **A10:2021 – Server-Side Request Forgery (SSRF)**
    - Internal network access
    - Cloud metadata access

### Day 4-7: HTTP Protocol Deep Dive

- HTTP methods (GET, POST, PUT, DELETE, etc.)
- Headers và security implications
- Cookies và session management
- CORS và CSRF
- HTTP/2 và HTTP/3

### Day 8-10: Authentication & Authorization

- Authentication mechanisms
- Session management
- JWT (JSON Web Tokens)
- OAuth 2.0
- SAML

### Day 11-14: Practice

- PortSwigger Web Security Academy labs
- PentesterLab exercises
- CTF challenges

## Tài liệu

- OWASP Top 10: https://owasp.org/Top10/
- PortSwigger Academy: https://portswigger.net/web-security
- Web Security Testing Guide: https://owasp.org/www-project-web-security-testing-guide/

## Bài tập

Hoàn thành ít nhất 20 labs trên PortSwigger Web Security Academy, tập trung vào:
- SQL Injection
- Authentication bypass
- Access control
- SSRF
- XXE

