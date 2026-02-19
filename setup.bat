@echo off
:: ======================================================
:: COLLEGE SPORTS MANAGEMENT SYSTEM - RELIABLE SETUP
:: ======================================================

set XAMPP_MYSQL=C:\xampp\mysql\bin\mysql.exe
set XAMPP_PHP=C:\xampp\php\php.exe
set DB_NAME=sports_management
set PROJECT_DIR=%~dp0
set SQL_FILE=%PROJECT_DIR%database\sports_management.sql
set SEED_FILE=%PROJECT_DIR%database\seed_massive_data.php

echo.
echo [SYSTEM] Initiating Environmental Convergence...
echo.

:: 1. PREREQUISITES
if not exist "%XAMPP_MYSQL%" (
    echo [CRITICAL] MySQL Binary Not Detected at %XAMPP_MYSQL%
    pause
    exit /b
)

:: 2. DATABASE INITIALIZATION
echo [1/3] Creating Database Structure...
"%XAMPP_MYSQL%" -u root -e "CREATE DATABASE IF NOT EXISTS %DB_NAME% CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

echo [2/3] Importing Schema Evolution...
:: Use type piping for better path handling in Windows
type "%SQL_FILE%" | "%XAMPP_MYSQL%" -u root %DB_NAME%

:: 3. DATA SEEDING
echo [3/3] Executing Massive Synthetic Injection...
"%XAMPP_PHP%" "%SEED_FILE%"

echo.
echo --------------------------------------------------
echo FUSION COMPLETE: SYSTEM IS OPERATIONAL
echo --------------------------------------------------
echo Admin: admin / password
echo Staff: staff / password
echo --------------------------------------------------
echo.

:: 4. LAUNCH BROWSER
echo Launching High-Fidelity Interface...
:: Get the current folder name to build the URL dynamically
for %%I in ("%~dp0.") do set "FOLDER_NAME=%%~nxI"
start "" "http://localhost/%FOLDER_NAME%/"

echo.
echo Setup cycle finished. Press any key to close this terminal.
pause
