@echo off
echo ========================================
echo UTB HR Central - Handover Setup Script
echo ========================================
echo.

echo Step 1: Initializing Git Repository...
git init
if %errorlevel% neq 0 (
    echo ERROR: Git is not installed or not in PATH
    echo Please install Git from https://git-scm.com/
    pause
    exit /b 1
)

echo.
echo Step 2: Creating .env.example from .env...
if exist .env (
    copy .env .env.example >nul
    echo .env.example created. Please edit it to remove sensitive data.
) else (
    echo WARNING: .env file not found. Please create .env.example manually.
)

echo.
echo Step 3: Checking for database backup...
if exist database_backup.sql (
    echo Database backup found: database_backup.sql
) else (
    echo WARNING: Database backup not found.
    echo Please export database using: mysqldump -u root -p utb_hr_central ^> database_backup.sql
)

echo.
echo Step 4: Adding files to Git...
git add .

echo.
echo Step 5: Creating initial commit...
git commit -m "Initial commit - UTB HR Central project handover"

echo.
echo ========================================
echo Setup Complete!
echo ========================================
echo.
echo Next Steps:
echo 1. Create a GitHub repository
echo 2. Add remote: git remote add origin https://github.com/USERNAME/REPO.git
echo 3. Push code: git push -u origin main
echo 4. Export database: mysqldump -u root -p utb_hr_central ^> database_backup.sql
echo 5. Share repository access and database backup with new developer
echo.
echo See PROJECT_HANDOVER_GUIDE.md for complete instructions.
echo.
pause

