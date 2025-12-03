# OSWE Study Plan - Offensive Security Web Expert

## Tổng quan về OSWE

OSWE (Offensive Security Web Expert) là chứng chỉ chuyên sâu về white-box web application security testing. Kỳ thi tập trung vào:

- **Code Review**: Phân tích mã nguồn để tìm lỗ hổng
- **Vulnerability Chaining**: Kết hợp nhiều lỗ hổng để đạt mục tiêu
- **Custom Exploit Development**: Viết exploit tùy chỉnh
- **Report Writing**: Viết báo cáo chi tiết về quá trình khai thác

## Roadmap 8 tuần

### Week 1-2: Web Security Fundamentals
- OWASP Top 10 (2021)
- Common web vulnerabilities
- HTTP protocol deep dive
- Authentication & Authorization mechanisms
- Session management

### Week 3-4: Code Review Techniques
- Static code analysis
- Identifying vulnerable code patterns
- Understanding application architecture
- Database interactions (SQL, NoSQL)
- File handling vulnerabilities

### Week 5-6: Advanced Exploitation
- Vulnerability chaining
- Bypassing security controls
- Custom payload development
- Deserialization attacks
- Template injection

### Week 7-8: Exam Preparation
- Practice challenges
- Time management
- Report writing practice
- Exam simulation

## Công nghệ cần nắm vững

- **PHP**: Code review, common pitfalls
- **Python**: Flask/Django, deserialization
- **Node.js**: Express, prototype pollution
- **Next.js**: Server-side rendering, API routes
- **Java**: Spring Boot, deserialization

## Tài liệu tham khảo

- OWASP Web Security Testing Guide
- PortSwigger Web Security Academy
- Offensive Security WEB-300 Course
- GitHub OSWE-Prep repositories

## Practice Challenges

Hoàn thành tất cả 5 challenges trong thư mục `../challenges/`:
1. PHP CMS - SQLi + File Upload chain
2. Python Flask - Deserialization + SSRF chain
3. Node.js API - JWT + Prototype Pollution chain
4. Next.js App - SSRF + XXE chain
5. Java Spring Boot - Deserialization + SQLi chain

## Tips cho kỳ thi

1. **Quản lý thời gian**: 48 giờ cho kỳ thi, lập kế hoạch rõ ràng
2. **Documentation**: Ghi chép mọi bước trong quá trình khai thác
3. **Code Review**: Đọc kỹ source code trước khi exploit
4. **Chaining**: Tìm cách kết hợp các lỗ hổng
5. **Report**: Viết báo cáo chi tiết, có screenshots và code snippets

