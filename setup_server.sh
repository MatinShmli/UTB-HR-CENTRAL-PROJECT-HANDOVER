#!/bin/bash

# UTB HR Central - Initial Server Setup Script
# This script sets up a fresh server with all required software

set -e

echo "=========================================="
echo "UTB HR Central - Server Setup Script"
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

print_warning() {
    echo -e "${YELLOW}⚠ $1${NC}"
}

# Check if running as root
if [ "$EUID" -ne 0 ]; then 
    print_error "Please run as root or with sudo"
    exit 1
fi

# Detect OS
if [ -f /etc/os-release ]; then
    . /etc/os-release
    OS=$ID
    VER=$VERSION_ID
else
    print_error "Cannot detect OS. This script supports Ubuntu/Debian only."
    exit 1
fi

print_info "Detected OS: $OS $VER"

# Update system
print_info "Updating system packages..."
apt update && apt upgrade -y
print_success "System updated"

# Install basic tools
print_info "Installing basic tools..."
apt install -y software-properties-common curl wget git unzip
print_success "Basic tools installed"

# Install PHP 8.2
print_info "Installing PHP 8.2 and extensions..."
add-apt-repository ppa:ondrej/php -y
apt update
apt install -y php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip php8.2-gd php8.2-bcmath php8.2-dom php8.2-cli
print_success "PHP 8.2 installed"

# Install MySQL
print_info "Installing MySQL..."
apt install -y mysql-server
print_success "MySQL installed"
print_warning "Remember to run: sudo mysql_secure_installation"

# Install Nginx
print_info "Installing Nginx..."
apt install -y nginx
print_success "Nginx installed"

# Install Composer
print_info "Installing Composer..."
if [ ! -f /usr/local/bin/composer ]; then
    curl -sS https://getcomposer.org/installer | php
    mv composer.phar /usr/local/bin/composer
    chmod +x /usr/local/bin/composer
    print_success "Composer installed"
else
    print_info "Composer already installed"
fi

# Install Node.js
print_info "Installing Node.js and npm..."
if ! command -v node &> /dev/null; then
    curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
    apt install -y nodejs
    print_success "Node.js installed"
else
    print_info "Node.js already installed"
fi

# Enable services
print_info "Enabling services..."
systemctl enable nginx
systemctl enable mysql
systemctl enable php8.2-fpm
print_success "Services enabled"

# Start services
print_info "Starting services..."
systemctl start nginx
systemctl start mysql
systemctl start php8.2-fpm
print_success "Services started"

# Display versions
echo ""
echo "=========================================="
echo "Installation Summary"
echo "=========================================="
php -v | head -n 1
mysql --version
nginx -v
composer --version 2>/dev/null | head -n 1
node -v
npm -v
echo ""

print_success "Server setup completed!"
echo ""
print_info "Next steps:"
echo "  1. Run: sudo mysql_secure_installation"
echo "  2. Create database and user (see VPS_DEPLOYMENT_STEPS.md)"
echo "  3. Clone your project: cd /var/www && git clone [your-repo]"
echo "  4. Follow the deployment steps in VPS_DEPLOYMENT_STEPS.md"
echo ""

