# Step-by-Step VPS Deployment Guide

## Prerequisites

Before starting, make sure you have:
- ✅ Virtual server access (SSH)
- ✅ Domain name (optional, can use IP address initially)
- ✅ Root or sudo access to the server
- ✅ Basic knowledge of Linux commands

---

## Step 1: Connect to Your Server

### Using SSH (Windows PowerShell or Command Prompt)

```bash
ssh root@your_server_ip
# or
ssh username@your_server_ip
```

**Replace:**
- `your_server_ip` with your actual server IP address
- `root` or `username` with your server username

**Example:**
```bash
ssh root@192.168.1.100
```

---

## Step 2: Check Server Operating System

```bash
cat /etc/os-release
```

**Expected output:** Ubuntu 20.04/22.04 or Debian 11/12

**Note:** This guide assumes Ubuntu/Debian. If you have a different OS, let me know!

---

## Step 3: Update System Packages

```bash
sudo apt update && sudo apt upgrade -y
```

This may take a few minutes.

---

## Step 4: Install Required Software

### Install PHP 8.2 and Extensions

```bash
sudo apt install -y software-properties-common
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip php8.2-gd php8.2-bcmath php8.2-dom php8.2-cli
```

### Install MySQL

```bash
sudo apt install -y mysql-server
```

### Install Nginx

```bash
sudo apt install -y nginx
```

### Install Composer

```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer
```

### Install Node.js and npm

```bash
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
```

### Verify Installations

```bash
php -v          # Should show PHP 8.2.x
mysql --version # Should show MySQL version
nginx -v        # Should show Nginx version
composer -v     # Should show Composer version
node -v         # Should show Node.js version
npm -v          # Should show npm version
```

---

## Step 5: Configure MySQL Database

### Secure MySQL Installation

```bash
sudo mysql_secure_installation
```

**Follow prompts:**
- Set root password? **Yes** (choose a strong password)
- Remove anonymous users? **Yes**
- Disallow root login remotely? **Yes**
- Remove test database? **Yes**
- Reload privilege tables? **Yes**

### Create Database and User

```bash
sudo mysql -u root -p
```

**Enter your MySQL root password when prompted.**

Then run these SQL commands (replace passwords with your own):

```sql
CREATE DATABASE utb_hr_central CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'utb_user'@'localhost' IDENTIFIED BY 'YourStrongPassword123!';
GRANT ALL PRIVILEGES ON utb_hr_central.* TO 'utb_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

**Important:** Remember the database name, username, and password - you'll need them later!

---

## Step 6: Clone Your Project

```bash
cd /var/www
sudo git clone https://github.com/MatinShmli/UTB-HR-CENTRAL-PROJECT-HANDOVER.git utb-hr-central
cd utb-hr-central
```

---

## Step 7: Install Project Dependencies

### Install PHP Dependencies

```bash
composer install --optimize-autoloader --no-dev
```

This may take 2-5 minutes.

### Install Node Dependencies

```bash
npm install
```

### Build Frontend Assets

```bash
npm run build
```

---

## Step 8: Configure Environment File

```bash
cp .env.example .env
nano .env
```

**Edit the following important settings:**

```env
APP_NAME="UTB HR Central"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://your_server_ip
# or if you have a domain:
# APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=utb_hr_central
DB_USERNAME=utb_user
DB_PASSWORD=YourStrongPassword123!
```

**To save in nano:** Press `Ctrl+X`, then `Y`, then `Enter`

### Generate Application Key

```bash
php artisan key:generate
```

---

## Step 9: Import Database or Run Migrations

### Option A: If you have a database backup file

```bash
# Upload your database_backup.sql to the server first, then:
mysql -u utb_user -p utb_hr_central < database_backup.sql
```

### Option B: Run migrations (fresh database)

```bash
php artisan migrate --force
```

### (Optional) Add Dummy Data

```bash
php artisan db:seed --class=ProfileDataSeeder
php artisan db:seed --class=JobPostingSeeder
php artisan db:seed --class=CpdApplicationSeeder
```

---

## Step 10: Set File Permissions

```bash
sudo chown -R www-data:www-data /var/www/utb-hr-central
sudo chmod -R 755 /var/www/utb-hr-central
sudo chmod -R 775 /var/www/utb-hr-central/storage
sudo chmod -R 775 /var/www/utb-hr-central/bootstrap/cache
```

---

## Step 11: Configure Nginx

### Create Nginx Configuration File

```bash
sudo nano /etc/nginx/sites-available/utb-hr-central
```

**Paste this configuration** (replace `your_server_ip` or `yourdomain.com`):

```nginx
server {
    listen 80;
    server_name your_server_ip yourdomain.com www.yourdomain.com;
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
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

**Save:** `Ctrl+X`, then `Y`, then `Enter`

### Enable the Site

```bash
sudo ln -s /etc/nginx/sites-available/utb-hr-central /etc/nginx/sites-enabled/
```

### Remove Default Site (Optional)

```bash
sudo rm /etc/nginx/sites-enabled/default
```

### Test Nginx Configuration

```bash
sudo nginx -t
```

**Expected output:** `nginx: configuration file /etc/nginx/nginx.conf test is successful`

### Reload Nginx

```bash
sudo systemctl reload nginx
```

---

## Step 12: Optimize Laravel for Production

```bash
cd /var/www/utb-hr-central
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Step 13: Test Your Website

Open your web browser and visit:
- `http://your_server_ip` (if using IP)
- `http://yourdomain.com` (if using domain)

**You should see the UTB HR Central landing page!**

---

## Step 14: Set Up SSL Certificate (HTTPS) - Optional but Recommended

### Install Certbot

```bash
sudo apt install -y certbot python3-certbot-nginx
```

### Get SSL Certificate

```bash
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

**Follow the prompts:**
- Enter your email address
- Agree to terms
- Choose whether to redirect HTTP to HTTPS (recommended: Yes)

**Note:** SSL certificates require a domain name. If you only have an IP address, skip this step.

---

## Step 15: Set Up Automatic Backups (Optional but Recommended)

```bash
sudo mkdir -p /backups/utb-hr-central
sudo nano /usr/local/bin/backup-utb.sh
```

**Paste this script:**

```bash
#!/bin/bash
BACKUP_DIR="/backups/utb-hr-central"
DATE=$(date +%Y%m%d_%H%M%S)
mkdir -p $BACKUP_DIR

# Backup database
mysqldump -u utb_user -p'YourStrongPassword123!' utb_hr_central > $BACKUP_DIR/db_$DATE.sql

# Backup files
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/utb-hr-central

# Keep only last 7 days
find $BACKUP_DIR -type f -mtime +7 -delete

echo "Backup completed: $DATE"
```

**Make it executable:**

```bash
sudo chmod +x /usr/local/bin/backup-utb.sh
```

**Add to crontab (daily at 2 AM):**

```bash
sudo crontab -e
```

**Add this line:**

```
0 2 * * * /usr/local/bin/backup-utb.sh
```

---

## Step 16: Set Up Scheduled Tasks (Cron)

Laravel needs a cron job to run scheduled tasks:

```bash
sudo crontab -e
```

**Add this line:**

```
* * * * * cd /var/www/utb-hr-central && php artisan schedule:run >> /dev/null 2>&1
```

---

## Troubleshooting

### Website Not Loading?

1. **Check Nginx status:**
   ```bash
   sudo systemctl status nginx
   ```

2. **Check PHP-FPM status:**
   ```bash
   sudo systemctl status php8.2-fpm
   ```

3. **Check Laravel logs:**
   ```bash
   tail -f /var/www/utb-hr-central/storage/logs/laravel.log
   ```

4. **Check Nginx error logs:**
   ```bash
   sudo tail -f /var/log/nginx/error.log
   ```

### Permission Errors?

```bash
sudo chown -R www-data:www-data /var/www/utb-hr-central
sudo chmod -R 775 /var/www/utb-hr-central/storage
sudo chmod -R 775 /var/www/utb-hr-central/bootstrap/cache
```

### Database Connection Error?

1. **Check MySQL is running:**
   ```bash
   sudo systemctl status mysql
   ```

2. **Test database connection:**
   ```bash
   mysql -u utb_user -p utb_hr_central
   ```

3. **Verify .env file has correct credentials**

### 500 Internal Server Error?

1. **Clear all caches:**
   ```bash
   cd /var/www/utb-hr-central
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   php artisan route:clear
   ```

2. **Regenerate caches:**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

---

## Next Steps After Deployment

1. ✅ Test login functionality
2. ✅ Test file uploads
3. ✅ Test PDF generation
4. ✅ Configure email settings (if needed)
5. ✅ Set up monitoring (optional)
6. ✅ Document your server details securely

---

## Quick Reference Commands

```bash
# Restart services
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm
sudo systemctl restart mysql

# View logs
tail -f /var/www/utb-hr-central/storage/logs/laravel.log
sudo tail -f /var/log/nginx/error.log

# Clear Laravel cache
cd /var/www/utb-hr-central
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Update application (after git pull)
cd /var/www/utb-hr-central
composer install --optimize-autoloader --no-dev
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

**Need Help?** Check the main `DEPLOYMENT_GUIDE.md` for more detailed information.

