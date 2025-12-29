# UTB HR Central - Server Deployment Guide

**Date**: November 2025  
**Project**: UTB HR Central  
**Framework**: Laravel 12 (PHP 8.2+)

---

## Table of Contents

1. [Deployment Options](#deployment-options)
2. [Server Requirements](#server-requirements)
3. [Pre-Deployment Checklist](#pre-deployment-checklist)
4. [Deployment Methods](#deployment-methods)
   - [Option 1: Shared Hosting (cPanel)](#option-1-shared-hosting-cpanel)
   - [Option 2: VPS Server](#option-2-vps-server)
   - [Option 3: Cloud Platforms](#option-3-cloud-platforms)
   - [Option 4: Laravel Forge](#option-4-laravel-forge)
5. [Post-Deployment Steps](#post-deployment-steps)
6. [Troubleshooting](#troubleshooting)

---

## Deployment Options

### 1. **Shared Hosting** (Easiest, Limited Control)
- **Best for**: Small organizations, low budget
- **Providers**: Hostinger, SiteGround, Bluehost, etc.
- **Cost**: $5-15/month
- **Pros**: Easy setup, managed hosting
- **Cons**: Limited customization, shared resources

### 2. **VPS Server** (Full Control, More Technical)
- **Best for**: Medium organizations, need customization
- **Providers**: DigitalOcean, Linode, Vultr, AWS EC2
- **Cost**: $10-40/month
- **Pros**: Full control, scalable, better performance
- **Cons**: Requires server management knowledge

### 3. **Cloud Platforms** (Scalable, Professional)
- **Best for**: Large organizations, high traffic
- **Providers**: AWS, Google Cloud, Azure, DigitalOcean App Platform
- **Cost**: $20-100+/month
- **Pros**: Highly scalable, managed services, auto-scaling
- **Cons**: More complex, higher cost

### 4. **Laravel Forge** (Managed Laravel Hosting)
- **Best for**: Laravel-specific hosting, automated deployments
- **Cost**: $12-39/month
- **Pros**: Laravel-optimized, automated deployments, SSL management
- **Cons**: Additional cost, vendor lock-in

---

## Server Requirements

### Minimum Requirements
- **PHP**: 8.2 or higher
- **MySQL**: 5.7+ or MariaDB 10.3+
- **RAM**: 512 MB (minimum), 1-2 GB (recommended)
- **Storage**: 5 GB (minimum), 20 GB (recommended)
- **Extensions**: 
  - OpenSSL
  - PDO
  - Mbstring
  - Tokenizer
  - XML
  - Ctype
  - JSON
  - BCMath
  - Fileinfo
  - GD or Imagick (for image processing)
  - DOM (for PDF generation)

### Recommended Configuration
- **PHP**: 8.3
- **MySQL**: 8.0+
- **RAM**: 2-4 GB
- **Storage**: 50 GB SSD
- **Web Server**: Nginx (recommended) or Apache
- **PHP-FPM**: Enabled
- **OPcache**: Enabled
- **SSL Certificate**: Required (Let's Encrypt free)

---

## Pre-Deployment Checklist

### 1. Code Preparation
- [ ] All code committed to Git
- [ ] `.env.example` file is up to date
- [ ] Frontend assets built (`npm run build`)
- [ ] All migrations tested locally
- [ ] Database backup created

### 2. Environment Configuration
- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_URL` set to production domain
- [ ] Database credentials configured
- [ ] Mail configuration set up
- [ ] Storage permissions configured

### 3. Security
- [ ] `APP_KEY` generated (unique for production)
- [ ] Strong database passwords
- [ ] File permissions set correctly
- [ ] `.env` file not accessible publicly
- [ ] SSL certificate ready

---

## Deployment Methods

## Option 1: Shared Hosting (cPanel)

### Step 1: Prepare Files for Upload

```bash
# On your local machine, build production assets
npm run build

# Create a deployment package (exclude unnecessary files)
# Files to upload:
# - All files EXCEPT: node_modules, vendor, .git, .env
```

### Step 2: Upload Files via FTP/cPanel File Manager

1. Connect to your hosting via FTP (FileZilla, WinSCP) or cPanel File Manager
2. Navigate to `public_html` or your domain's root directory
3. Upload all project files (except `node_modules`, `vendor`, `.git`, `.env`)

**Important**: The `public` folder contents should be in the web root, and other files should be one level up.

**Correct Structure on Server:**
```
/home/username/public_html/          (or your domain root)
├── index.php                        (from public/index.php)
├── .htaccess                        (from public/.htaccess)
├── assets/                          (from public/build/)
├── images/                          (from public/images/)
└── ../                              (parent directory)
    ├── app/
    ├── bootstrap/
    ├── config/
    ├── database/
    ├── resources/
    ├── routes/
    ├── storage/
    ├── vendor/                      (will install via Composer)
    └── .env
```

### Step 3: Install Dependencies

**Via cPanel Terminal or SSH:**

```bash
# Navigate to project root (parent of public_html)
cd ~/public_html/../

# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install Node dependencies (if needed for future builds)
npm install --production
```

### Step 4: Configure Environment

1. Create `.env` file in project root (parent of `public_html`)
2. Copy from `.env.example` and configure:

```env
APP_NAME="UTB HR Central"
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

# Mail configuration
MAIL_MAILER=smtp
MAIL_HOST=mail.yourdomain.com
MAIL_PORT=587
MAIL_USERNAME=your_email@yourdomain.com
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

3. Generate application key:
```bash
php artisan key:generate
```

### Step 5: Set Permissions

```bash
# Set storage and cache permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Step 6: Run Migrations

```bash
# Run database migrations
php artisan migrate --force

# (Optional) Run seeders for initial data
php artisan db:seed --class=ProfileDataSeeder
```

### Step 7: Configure Web Server

**For Apache (most shared hosting):**

Create or update `.htaccess` in `public_html`:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ ../public/$1 [L]
</IfModule>
```

**Or configure Document Root in cPanel:**
- Set Document Root to `public_html/public` (if possible)

---

## Option 2: VPS Server

### Step 1: Server Setup (Ubuntu/Debian)

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install required software
sudo apt install -y nginx mysql-server php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip php8.2-gd php8.2-bcmath php8.2-dom

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js and npm
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
```

### Step 2: Configure MySQL

```bash
# Secure MySQL installation
sudo mysql_secure_installation

# Create database and user
sudo mysql -u root -p
```

```sql
CREATE DATABASE utb_hr_central CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'utb_user'@'localhost' IDENTIFIED BY 'strong_password_here';
GRANT ALL PRIVILEGES ON utb_hr_central.* TO 'utb_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### Step 3: Deploy Application

```bash
# Clone repository (or upload files)
cd /var/www
sudo git clone https://github.com/MatinShmli/UTB-HR-CENTRAL-PROJECT-HANDOVER.git utb-hr-central
cd utb-hr-central

# Install dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Set permissions
sudo chown -R www-data:www-data /var/www/utb-hr-central
sudo chmod -R 775 /var/www/utb-hr-central/storage
sudo chmod -R 775 /var/www/utb-hr-central/bootstrap/cache
```

### Step 4: Configure Environment

```bash
# Copy environment file
cp .env.example .env

# Edit .env file
nano .env
```

Configure `.env`:
```env
APP_NAME="UTB HR Central"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=utb_hr_central
DB_USERNAME=utb_user
DB_PASSWORD=strong_password_here
```

```bash
# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate --force

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 5: Configure Nginx

Create Nginx configuration:

```bash
sudo nano /etc/nginx/sites-available/utb-hr-central
```

```nginx
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/utb-hr-central/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Enable site:
```bash
sudo ln -s /etc/nginx/sites-available/utb-hr-central /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### Step 6: Install SSL Certificate (Let's Encrypt)

```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx

# Get SSL certificate
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com

# Auto-renewal is set up automatically
```

### Step 7: Set Up Queue Worker (Optional but Recommended)

```bash
# Create systemd service
sudo nano /etc/systemd/system/utb-queue.service
```

```ini
[Unit]
Description=UTB HR Central Queue Worker
After=network.target

[Service]
User=www-data
Group=www-data
Restart=always
ExecStart=/usr/bin/php /var/www/utb-hr-central/artisan queue:work --sleep=3 --tries=3 --max-time=3600

[Install]
WantedBy=multi-user.target
```

```bash
# Enable and start service
sudo systemctl enable utb-queue
sudo systemctl start utb-queue
```

---

## Option 3: Cloud Platforms

### DigitalOcean App Platform

1. **Connect Repository**
   - Go to DigitalOcean App Platform
   - Connect your GitHub repository
   - Select branch (main/master)

2. **Configure Build Settings**
   - **Build Command**: `npm run build`
   - **Run Command**: `php artisan serve --host=0.0.0.0 --port=8080`

3. **Add Database**
   - Add MySQL database component
   - Note connection details

4. **Environment Variables**
   - Add all variables from `.env.example`
   - Set `APP_ENV=production`
   - Set `APP_DEBUG=false`

5. **Deploy**
   - Click "Deploy"
   - Wait for build to complete

### AWS Elastic Beanstalk

1. **Install EB CLI**
```bash
pip install awsebcli
```

2. **Initialize EB**
```bash
eb init -p "PHP 8.2 running on 64bit Amazon Linux 2" utb-hr-central
```

3. **Create Environment**
```bash
eb create utb-hr-central-env
```

4. **Configure Environment Variables**
```bash
eb setenv APP_ENV=production APP_DEBUG=false APP_URL=https://yourdomain.com
```

5. **Deploy**
```bash
eb deploy
```

---

## Option 4: Laravel Forge

1. **Sign Up**: Create account at https://forge.laravel.com
2. **Connect Server**: Add your VPS or create new server
3. **Create Site**: Add your domain
4. **Deploy**: Connect GitHub repository and deploy
5. **Configure**: Set environment variables in Forge dashboard
6. **SSL**: Forge automatically sets up SSL certificates

---

## Post-Deployment Steps

### 1. Verify Installation

```bash
# Check application status
php artisan about

# Test database connection
php artisan tinker
>>> DB::connection()->getPdo();
```

### 2. Optimize Performance

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev
```

### 3. Set Up Scheduled Tasks (Cron)

```bash
# Edit crontab
crontab -e

# Add Laravel scheduler (runs every minute)
* * * * * cd /var/www/utb-hr-central && php artisan schedule:run >> /dev/null 2>&1
```

### 4. Set Up Backups

```bash
# Create backup script
nano /usr/local/bin/backup-utb.sh
```

```bash
#!/bin/bash
BACKUP_DIR="/backups/utb-hr-central"
DATE=$(date +%Y%m%d_%H%M%S)
mkdir -p $BACKUP_DIR

# Backup database
mysqldump -u utb_user -p'password' utb_hr_central > $BACKUP_DIR/db_$DATE.sql

# Backup files
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/utb-hr-central

# Keep only last 7 days
find $BACKUP_DIR -type f -mtime +7 -delete
```

```bash
# Make executable
chmod +x /usr/local/bin/backup-utb.sh

# Add to crontab (daily at 2 AM)
0 2 * * * /usr/local/bin/backup-utb.sh
```

### 5. Monitor Logs

```bash
# View Laravel logs
tail -f storage/logs/laravel.log

# View Nginx logs
tail -f /var/log/nginx/error.log
tail -f /var/log/nginx/access.log
```

### 6. Test Application

- [ ] Visit homepage
- [ ] Test login
- [ ] Test file uploads
- [ ] Test PDF generation
- [ ] Test email sending
- [ ] Check SSL certificate

---

## Troubleshooting

### Common Issues

#### 1. 500 Internal Server Error

**Check:**
```bash
# View Laravel logs
tail -f storage/logs/laravel.log

# Check file permissions
ls -la storage bootstrap/cache

# Fix permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

#### 2. Database Connection Error

**Check:**
- Database credentials in `.env`
- MySQL service running: `sudo systemctl status mysql`
- Database exists: `mysql -u root -p -e "SHOW DATABASES;"`

#### 3. Permission Denied Errors

**Fix:**
```bash
sudo chown -R www-data:www-data /var/www/utb-hr-central
sudo chmod -R 775 storage bootstrap/cache
```

#### 4. Assets Not Loading (CSS/JS)

**Fix:**
```bash
# Rebuild assets
npm run build

# Clear cache
php artisan cache:clear
php artisan view:clear
```

#### 5. Session/Cache Issues

**Fix:**
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## Security Checklist

- [ ] `APP_DEBUG=false` in production
- [ ] Strong `APP_KEY` generated
- [ ] Database passwords are strong
- [ ] `.env` file not accessible publicly
- [ ] SSL certificate installed
- [ ] File permissions set correctly
- [ ] Regular security updates applied
- [ ] Firewall configured (UFW, iptables)
- [ ] Regular backups scheduled

---

## Maintenance

### Regular Tasks

1. **Weekly**: Check logs for errors
2. **Monthly**: Update dependencies (`composer update`, `npm update`)
3. **Quarterly**: Review and optimize database
4. **As needed**: Apply security patches

### Update Application

```bash
# Pull latest code
git pull origin main

# Update dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Run migrations
php artisan migrate --force

# Clear and cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Support Resources

- **Laravel Documentation**: https://laravel.com/docs
- **Laravel Deployment**: https://laravel.com/docs/deployment
- **Nginx Configuration**: https://nginx.org/en/docs/
- **DigitalOcean Tutorials**: https://www.digitalocean.com/community/tags/laravel

---

**Last Updated**: November 2025  
**Project**: UTB HR Central  
**Version**: 1.0

