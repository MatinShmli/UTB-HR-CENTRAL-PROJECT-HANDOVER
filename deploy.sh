#!/bin/bash

# UTB HR Central - Deployment Script
# This script automates the deployment process for a VPS/server

set -e

echo "=== UTB HR Central Deployment Script ==="
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration
PROJECT_DIR="/var/www/utb-hr-central"
BACKUP_DIR="/backups/utb-hr-central"

# Functions
print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

print_info() {
    echo -e "${YELLOW}→ $1${NC}"
}

# Check if running as root
if [ "$EUID" -ne 0 ]; then 
    print_error "Please run as root or with sudo"
    exit 1
fi

# Step 1: Backup existing installation
print_info "Creating backup..."
if [ -d "$PROJECT_DIR" ]; then
    mkdir -p $BACKUP_DIR
    DATE=$(date +%Y%m%d_%H%M%S)
    tar -czf $BACKUP_DIR/backup_$DATE.tar.gz $PROJECT_DIR
    print_success "Backup created at $BACKUP_DIR/backup_$DATE.tar.gz"
else
    print_info "No existing installation found, skipping backup"
fi

# Step 2: Navigate to project directory
print_info "Navigating to project directory..."
cd $PROJECT_DIR || exit 1

# Step 3: Pull latest code
print_info "Pulling latest code from Git..."
git pull origin main || print_error "Git pull failed. Make sure repository is set up correctly."

# Step 4: Install/Update dependencies
print_info "Installing PHP dependencies..."
composer install --optimize-autoloader --no-dev --no-interaction

print_info "Installing Node dependencies..."
npm install --production

# Step 5: Build frontend assets
print_info "Building frontend assets..."
npm run build

# Step 6: Run migrations
print_info "Running database migrations..."
php artisan migrate --force

# Step 7: Clear and cache
print_info "Clearing old cache..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

print_info "Caching configuration for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Step 8: Set permissions
print_info "Setting file permissions..."
chown -R www-data:www-data $PROJECT_DIR
chmod -R 755 $PROJECT_DIR
chmod -R 775 $PROJECT_DIR/storage
chmod -R 775 $PROJECT_DIR/bootstrap/cache

# Step 9: Restart services
print_info "Restarting PHP-FPM..."
systemctl restart php8.2-fpm || systemctl restart php-fpm

print_info "Reloading Nginx..."
nginx -t && systemctl reload nginx

# Step 10: Check queue worker
print_info "Checking queue worker..."
if systemctl is-active --quiet utb-queue; then
    print_info "Restarting queue worker..."
    systemctl restart utb-queue
else
    print_info "Queue worker not running (optional service)"
fi

echo ""
print_success "Deployment completed successfully!"
echo ""
print_info "Next steps:"
echo "  1. Verify the application is running: https://yourdomain.com"
echo "  2. Check logs: tail -f $PROJECT_DIR/storage/logs/laravel.log"
echo "  3. Test all major features"
echo ""

