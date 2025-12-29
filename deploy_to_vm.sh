#!/bin/bash

# UTB HR Central - Linux VM Deployment Script
# Run this script on your Linux VM

set -e

echo "=========================================="
echo "UTB HR Central - VM Deployment Script"
echo "=========================================="
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

print_info() {
    echo -e "${BLUE}→ $1${NC}"
}

# Check if running as root
if [ "$EUID" -ne 0 ]; then 
    print_error "Please run as root or with sudo"
    exit 1
fi

# Project directory
PROJECT_DIR="/var/www/utb-hr-central"

print_info "Project will be deployed to: $PROJECT_DIR"
echo ""

# Check prerequisites
print_info "Checking prerequisites..."

if ! command -v git &> /dev/null; then
    print_error "Git is not installed. Installing..."
    apt update
    apt install -y git
fi
print_success "Git is installed"

if ! command -v php &> /dev/null; then
    print_error "PHP is not installed. Please install PHP 8.2+ first."
    exit 1
fi
print_success "PHP is installed: $(php -v | head -n 1)"

if ! command -v composer &> /dev/null; then
    print_error "Composer is not installed. Installing..."
    curl -sS https://getcomposer.org/installer | php
    mv composer.phar /usr/local/bin/composer
    chmod +x /usr/local/bin/composer
fi
print_success "Composer is installed"

if ! command -v node &> /dev/null; then
    print_error "Node.js is not installed. Installing..."
    curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
    apt install -y nodejs
fi
print_success "Node.js is installed: $(node -v)"

echo ""

# Clone or update repository
if [ -d "$PROJECT_DIR" ]; then
    print_info "Project directory exists. Updating..."
    cd $PROJECT_DIR
    git pull origin main || print_error "Git pull failed"
else
    print_info "Cloning repository..."
    mkdir -p /var/www
    cd /var/www
    git clone https://github.com/MatinShmli/UTB-HR-CENTRAL-PROJECT-HANDOVER.git utb-hr-central
    cd utb-hr-central
fi

# Install PHP dependencies
print_info "Installing PHP dependencies..."
composer install --optimize-autoloader --no-dev --no-interaction

# Install Node dependencies
print_info "Installing Node dependencies..."
npm install

# Build frontend
print_info "Building frontend assets..."
npm run build

# Configure environment
print_info "Configuring environment..."
if [ ! -f .env ]; then
    cp .env.example .env
    print_success "Created .env file"
fi

print_info "Please configure .env file with your database settings"
echo "  - DB_DATABASE=utb_hr_central"
echo "  - DB_USERNAME=utb_user"
echo "  - DB_PASSWORD=your_password"
echo "  - APP_URL=http://your-vm-ip"
echo ""
read -p "Press Enter after configuring .env file..."

# Generate application key
print_info "Generating application key..."
php artisan key:generate

# Ask about migrations
echo ""
read -p "Run database migrations? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    print_info "Running migrations..."
    php artisan migrate --force
    
    read -p "Add dummy data? (y/n) " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        php artisan db:seed --class=ProfileDataSeeder
        php artisan db:seed --class=JobPostingSeeder
        php artisan db:seed --class=CpdApplicationSeeder
    fi
fi

# Set permissions
print_info "Setting file permissions..."
chown -R www-data:www-data $PROJECT_DIR
chmod -R 755 $PROJECT_DIR
chmod -R 775 $PROJECT_DIR/storage
chmod -R 775 $PROJECT_DIR/bootstrap/cache

# Optimize for production
print_info "Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ""
print_success "Deployment complete!"
echo ""
print_info "Next steps:"
echo "  1. Configure your web server (Nginx/Apache)"
echo "  2. Point web root to: $PROJECT_DIR/public"
echo "  3. Visit: http://your-vm-ip"
echo ""
print_info "To start development server:"
echo "  php artisan serve --host=0.0.0.0 --port=8000"
echo ""

