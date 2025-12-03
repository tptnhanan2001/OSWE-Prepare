# Troubleshooting Guide - Docker trên Windows

## Lỗi thường gặp và cách khắc phục

### 1. Lỗi: "docker client must be run with elevated privileges"

**Nguyên nhân:** Docker Desktop chưa chạy hoặc Docker daemon chưa khởi động.

**Giải pháp:**

1. **Kiểm tra Docker Desktop:**
   - Mở Docker Desktop từ Start Menu
   - Đợi cho đến khi Docker Desktop hiển thị "Docker Desktop is running"
   - Icon Docker trong system tray phải màu xanh

2. **Khởi động Docker Desktop:**
   ```powershell
   # Tìm Docker Desktop trong Start Menu và chạy
   # Hoặc chạy từ command line (cần quyền admin):
   Start-Process "C:\Program Files\Docker\Docker\Docker Desktop.exe"
   ```

3. **Kiểm tra Docker đã chạy:**
   ```powershell
   docker ps
   # Nếu thành công, sẽ hiển thị danh sách containers (có thể rỗng)
   ```

4. **Nếu vẫn lỗi, thử restart Docker Desktop:**
   - Right-click vào Docker icon trong system tray
   - Chọn "Restart Docker Desktop"
   - Đợi 1-2 phút để Docker khởi động lại

### 2. Warning: "the attribute `version` is obsolete"

**Đã fix:** Đã remove `version: '3.8'` khỏi tất cả docker-compose.yml files.

### 3. Lỗi về volume paths trên Windows

**Nguyên nhân:** Windows paths có thể gây vấn đề với Docker volumes.

**Giải pháp:**

1. **Sử dụng WSL2 backend (khuyến nghị):**
   - Docker Desktop Settings → General → Use WSL 2 based engine
   - Enable integration với WSL distro của bạn

2. **Nếu dùng Windows paths, đảm bảo:**
   - Paths là relative paths (bắt đầu với `./`)
   - Không có spaces trong paths
   - Sử dụng forward slashes `/` thay vì backslashes `\`

### 4. Lỗi port đã được sử dụng

**Nguyên nhân:** Port đã được sử dụng bởi service khác.

**Giải pháp:**

1. **Kiểm tra port đang được sử dụng:**
   ```powershell
   netstat -ano | findstr :8080
   ```

2. **Thay đổi port trong docker-compose.yml:**
   ```yaml
   ports:
     - "8081:80"  # Thay đổi port host từ 8080 sang 8081
   ```

3. **Hoặc stop service đang dùng port:**
   ```powershell
   # Tìm PID từ netstat
   taskkill /PID <PID> /F
   ```

### 5. Lỗi build Docker image

**Nguyên nhân:** Dockerfile có vấn đề hoặc network issues.

**Giải pháp:**

1. **Kiểm tra Dockerfile syntax:**
   ```powershell
   docker build -t test .
   ```

2. **Clear Docker cache:**
   ```powershell
   docker system prune -a
   ```

3. **Build lại từ đầu:**
   ```powershell
   docker-compose build --no-cache
   ```

### 6. Lỗi database connection

**Nguyên nhân:** Database container chưa sẵn sàng khi app container start.

**Giải pháp:**

1. **Đảm bảo `depends_on` trong docker-compose.yml:**
   ```yaml
   services:
     web:
       depends_on:
         - mysql
   ```

2. **Hoặc thêm healthcheck và wait script**

3. **Kiểm tra logs:**
   ```powershell
   docker-compose logs mysql
   ```

## Quick Fix Checklist

- [ ] Docker Desktop đang chạy (icon xanh trong system tray)
- [ ] Đã đợi Docker Desktop khởi động hoàn toàn (1-2 phút)
- [ ] Đang ở đúng thư mục challenge khi chạy `docker-compose up`
- [ ] Ports không bị conflict với services khác
- [ ] Có đủ disk space (Docker cần ~10GB)
- [ ] Windows đã enable virtualization (WSL2 hoặc Hyper-V)

## Test Docker Setup

Chạy lệnh sau để test:

```powershell
# Test Docker
docker --version
docker ps

# Test Docker Compose
docker-compose --version

# Test build một challenge
cd challenges\challenge-01-php-cms
docker-compose up -d
docker-compose ps
docker-compose logs
```

## Liên hệ hỗ trợ

Nếu vẫn gặp vấn đề:
1. Kiểm tra Docker Desktop logs: Settings → Troubleshoot → View logs
2. Xem chi tiết lỗi trong `docker-compose logs`
3. Đảm bảo Windows đã update và có đủ resources

