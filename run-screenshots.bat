@echo off
echo ========================================
echo   Screenshot Automation Tool
echo   College Sports Management System
echo ========================================
echo.

REM Check if Node.js is installed
where node >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] Node.js is not installed!
    echo Please install Node.js from https://nodejs.org/
    pause
    exit /b 1
)

REM Check if node_modules exists
if not exist "node_modules" (
    echo [INFO] Installing dependencies...
    call npm install
    echo.
)

REM Check if XAMPP is running
echo [INFO] Checking if XAMPP is running...
powershell -Command "try { $response = Invoke-WebRequest -Uri 'http://localhost/COLLEGE-SPORTS-MANAGEMENT-SYSTEM/' -UseBasicParsing -TimeoutSec 5; Write-Host '[OK] XAMPP is running' } catch { Write-Host '[ERROR] XAMPP is not running!'; exit 1 }"
if %ERRORLEVEL% NEQ 0 (
    echo.
    echo Please start XAMPP (Apache + MySQL) and try again.
    pause
    exit /b 1
)

echo.
echo ========================================
echo   Starting Screenshot Capture...
echo ========================================
echo.

REM Run the screenshot script
node screenshot-automation.js

echo.
echo ========================================
echo   Process Complete!
echo ========================================
echo.
echo Screenshots saved in:
echo   - assets\screenshots\viewport\
echo   - assets\screenshots\full-height\
echo.
pause
