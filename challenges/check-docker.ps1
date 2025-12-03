# Script kiem tra Docker setup tren Windows

Write-Host "=== Docker Setup Check ===" -ForegroundColor Cyan
Write-Host ""

# Check Docker version
Write-Host "1. Checking Docker version..." -ForegroundColor Yellow
try {
    $dockerVersion = docker --version 2>&1
    if ($LASTEXITCODE -eq 0) {
        Write-Host "   [OK] Docker installed: $dockerVersion" -ForegroundColor Green
    } else {
        Write-Host "   [ERROR] Docker not found. Please install Docker Desktop." -ForegroundColor Red
        exit 1
    }
} catch {
    Write-Host "   [ERROR] Docker not found. Please install Docker Desktop." -ForegroundColor Red
    exit 1
}

# Check Docker daemon
Write-Host ""
Write-Host "2. Checking Docker daemon..." -ForegroundColor Yellow
try {
    $null = docker info 2>&1 | Out-Null
    if ($LASTEXITCODE -eq 0) {
        Write-Host "   [OK] Docker daemon is running" -ForegroundColor Green
    } else {
        Write-Host "   [ERROR] Docker daemon is not running" -ForegroundColor Red
        Write-Host "   -> Please start Docker Desktop and wait for it to fully start" -ForegroundColor Yellow
        Write-Host "   -> Look for Docker icon in system tray (should be green)" -ForegroundColor Yellow
        exit 1
    }
} catch {
    Write-Host "   [ERROR] Cannot connect to Docker daemon" -ForegroundColor Red
    Write-Host "   -> Please start Docker Desktop" -ForegroundColor Yellow
    exit 1
}

# Check Docker Compose
Write-Host ""
Write-Host "3. Checking Docker Compose..." -ForegroundColor Yellow
try {
    $composeVersion = docker compose version 2>&1
    if ($LASTEXITCODE -eq 0) {
        Write-Host "   [OK] Docker Compose: $composeVersion" -ForegroundColor Green
    } else {
        Write-Host "   [WARNING] Docker Compose not available" -ForegroundColor Yellow
    }
} catch {
    Write-Host "   [WARNING] Docker Compose not found" -ForegroundColor Yellow
}

# Check running containers
Write-Host ""
Write-Host "4. Checking running containers..." -ForegroundColor Yellow
try {
    $containers = docker ps 2>&1
    if ($LASTEXITCODE -eq 0) {
        Write-Host "   [OK] Docker is working correctly" -ForegroundColor Green
        $containerCount = ($containers | Measure-Object -Line).Lines - 1
        if ($containerCount -gt 0) {
            Write-Host "   -> Found $containerCount running container(s)" -ForegroundColor Cyan
        }
    }
} catch {
    Write-Host "   [ERROR] Cannot list containers" -ForegroundColor Red
}

# Check ports
Write-Host ""
Write-Host "5. Checking required ports..." -ForegroundColor Yellow
$ports = @(8080, 5000, 3000, 3001, 8081, 3306, 5432, 6379, 27017)
$usedPorts = @()

foreach ($port in $ports) {
    $connection = Get-NetTCPConnection -LocalPort $port -ErrorAction SilentlyContinue
    if ($connection) {
        $usedPorts += $port
        Write-Host "   [WARNING] Port $port is already in use" -ForegroundColor Yellow
    }
}

if ($usedPorts.Count -eq 0) {
    Write-Host "   [OK] All required ports are available" -ForegroundColor Green
} else {
    Write-Host "   -> You may need to stop services using these ports or change port mappings" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "=== Summary ===" -ForegroundColor Cyan
Write-Host "Docker setup looks good! You can now run:" -ForegroundColor Green
Write-Host "  cd challenges\challenge-01-php-cms" -ForegroundColor White
Write-Host "  docker-compose up -d" -ForegroundColor White
Write-Host ""
