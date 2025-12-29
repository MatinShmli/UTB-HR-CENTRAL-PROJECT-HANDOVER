# UTB HR Central - Windows VM Deployment Script
# Run this script in PowerShell on your Windows VM

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "UTB HR Central - VM Deployment Script" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""

# Check if running as Administrator
$isAdmin = ([Security.Principal.WindowsPrincipal] [Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)

if (-not $isAdmin) {
    Write-Host "⚠ Warning: Not running as Administrator. Some steps may require admin rights." -ForegroundColor Yellow
    Write-Host ""
}

# Check if Git is installed
Write-Host "Checking prerequisites..." -ForegroundColor Yellow
$gitInstalled = Get-Command git -ErrorAction SilentlyContinue
$phpInstalled = Get-Command php -ErrorAction SilentlyContinue
$composerInstalled = Get-Command composer -ErrorAction SilentlyContinue
$nodeInstalled = Get-Command node -ErrorAction SilentlyContinue

if (-not $gitInstalled) {
    Write-Host "✗ Git is not installed. Please install Git first." -ForegroundColor Red
    Write-Host "  Download from: https://git-scm.com/download/win" -ForegroundColor Yellow
    exit 1
} else {
    Write-Host "✓ Git is installed" -ForegroundColor Green
}

if (-not $phpInstalled) {
    Write-Host "✗ PHP is not installed." -ForegroundColor Red
    Write-Host "  Please install PHP 8.2+ or use Laragon/XAMPP" -ForegroundColor Yellow
    exit 1
} else {
    Write-Host "✓ PHP is installed" -ForegroundColor Green
    php -v
}

if (-not $composerInstalled) {
    Write-Host "✗ Composer is not installed." -ForegroundColor Red
    Write-Host "  Download from: https://getcomposer.org/download/" -ForegroundColor Yellow
    exit 1
} else {
    Write-Host "✓ Composer is installed" -ForegroundColor Green
}

if (-not $nodeInstalled) {
    Write-Host "✗ Node.js is not installed." -ForegroundColor Red
    Write-Host "  Download from: https://nodejs.org/" -ForegroundColor Yellow
    exit 1
} else {
    Write-Host "✓ Node.js is installed" -ForegroundColor Green
    node -v
    npm -v
}

Write-Host ""
Write-Host "All prerequisites are installed!" -ForegroundColor Green
Write-Host ""

# Ask for project directory
$projectPath = Read-Host "Enter project directory path (or press Enter for C:\inetpub\wwwroot\utb-hr-central)"
if ([string]::IsNullOrWhiteSpace($projectPath)) {
    $projectPath = "C:\inetpub\wwwroot\utb-hr-central"
}

# Create directory if it doesn't exist
if (-not (Test-Path $projectPath)) {
    Write-Host "Creating directory: $projectPath" -ForegroundColor Yellow
    New-Item -ItemType Directory -Path $projectPath -Force | Out-Null
}

# Clone or update repository
Set-Location $projectPath

if (Test-Path ".git") {
    Write-Host "Repository already exists. Pulling latest changes..." -ForegroundColor Yellow
    git pull origin main
} else {
    Write-Host "Cloning repository..." -ForegroundColor Yellow
    git clone https://github.com/MatinShmli/UTB-HR-CENTRAL-PROJECT-HANDOVER.git .
}

# Install PHP dependencies
Write-Host ""
Write-Host "Installing PHP dependencies..." -ForegroundColor Yellow
composer install --optimize-autoloader --no-dev --no-interaction

# Install Node dependencies
Write-Host ""
Write-Host "Installing Node dependencies..." -ForegroundColor Yellow
npm install

# Build frontend
Write-Host ""
Write-Host "Building frontend assets..." -ForegroundColor Yellow
npm run build

# Configure environment
Write-Host ""
Write-Host "Configuring environment..." -ForegroundColor Yellow

if (-not (Test-Path ".env")) {
    Copy-Item ".env.example" ".env"
    Write-Host "Created .env file from .env.example" -ForegroundColor Green
}

Write-Host ""
Write-Host "Please edit .env file with your database settings:" -ForegroundColor Cyan
Write-Host "  - DB_DATABASE=utb_hr_central" -ForegroundColor White
Write-Host "  - DB_USERNAME=root (or your MySQL username)" -ForegroundColor White
Write-Host "  - DB_PASSWORD= (your MySQL password)" -ForegroundColor White
Write-Host "  - APP_URL=http://localhost (or your VM IP)" -ForegroundColor White
Write-Host ""

$continue = Read-Host "Press Enter after you've configured .env file, or type 'skip' to continue"

if ($continue -ne "skip") {
    Write-Host "Opening .env file in Notepad..." -ForegroundColor Yellow
    notepad .env
    Read-Host "Press Enter after saving .env file"
}

# Generate application key
Write-Host ""
Write-Host "Generating application key..." -ForegroundColor Yellow
php artisan key:generate

# Run migrations
Write-Host ""
$runMigrations = Read-Host "Run database migrations? (y/n)"
if ($runMigrations -eq "y" -or $runMigrations -eq "Y") {
    Write-Host "Running migrations..." -ForegroundColor Yellow
    php artisan migrate --force
    
    $seedData = Read-Host "Add dummy data? (y/n)"
    if ($seedData -eq "y" -or $seedData -eq "Y") {
        php artisan db:seed --class=ProfileDataSeeder
        php artisan db:seed --class=JobPostingSeeder
        php artisan db:seed --class=CpdApplicationSeeder
    }
}

# Optimize for production
Write-Host ""
Write-Host "Optimizing for production..." -ForegroundColor Yellow
php artisan config:cache
php artisan route:cache
php artisan view:cache

Write-Host ""
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "Deployment Complete!" -ForegroundColor Green
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Next steps:" -ForegroundColor Yellow
Write-Host "1. Make sure MySQL/MariaDB is running" -ForegroundColor White
Write-Host "2. Configure your web server (IIS/Apache/Nginx)" -ForegroundColor White
Write-Host "3. Point web root to: $projectPath\public" -ForegroundColor White
Write-Host "4. Visit: http://localhost or http://your-vm-ip" -ForegroundColor White
Write-Host ""
Write-Host "To start development server:" -ForegroundColor Yellow
Write-Host "  php artisan serve --host=0.0.0.0 --port=8000" -ForegroundColor White
Write-Host ""

