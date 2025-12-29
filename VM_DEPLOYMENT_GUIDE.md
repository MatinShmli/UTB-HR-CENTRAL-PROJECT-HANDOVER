# Virtual Machine Deployment Guide - UTB HR Central

This guide will help you deploy the UTB HR Central website on your virtual machine, whether it's Windows or Linux.

---

## Quick Questions First

Before we start, please let me know:

1. **What operating system is your VM?**
   - Windows Server (2016/2019/2022)
   - Windows 10/11
   - Linux (Ubuntu/Debian/CentOS)
   - Other?

2. **How do you access your VM?**
   - Remote Desktop (RDP)
   - SSH
   - Direct access
   - VNC

3. **Do you have administrator/root access?**

---

## Option A: Linux VM (Ubuntu/Debian) - Recommended

If your VM is running Linux, follow these steps:

### Step 1: Connect to Your VM

```bash
# Via SSH (if remote)
ssh username@vm_ip_address

# Or access directly if it's local
```

### Step 2: Run Automated Setup

```bash
# Download the setup script
curl -O https://raw.githubusercontent.com/MatinShmli/UTB-HR-CENTRAL-PROJECT-HANDOVER/main/setup_server.sh

# Make it executable
chmod +x setup_server.sh

# Run with sudo
sudo bash setup_server.sh
```

This will install all required software automatically.

### Step 3: Clone Your Project

```bash
cd /var/www
sudo git clone https://github.com/MatinShmli/UTB-HR-CENTRAL-PROJECT-HANDOVER.git utb-hr-central
cd utb-hr-central
```

### Step 4: Install Dependencies

```bash
# Install PHP packages
composer install --optimize-autoloader --no-dev

# Install Node packages
npm install

# Build frontend
npm run build
```

### Step 5: Configure Database

```bash
# Secure MySQL
sudo mysql_secure_installation

# Create database
sudo mysql -u root -p
```

In MySQL, run:
```sql
CREATE DATABASE utb_hr_central CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'utb_user'@'localhost' IDENTIFIED BY 'YourPassword123!';
GRANT ALL PRIVILEGES ON utb_hr_central.* TO 'utb_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### Step 6: Configure Environment

```bash
cp .env.example .env
nano .env
```

Update these settings:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=http://vm_ip_address
# or http://localhost if accessing locally

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=utb_hr_central
DB_USERNAME=utb_user
DB_PASSWORD=YourPassword123!
```

Save (Ctrl+X, Y, Enter)

```bash
php artisan key:generate
```

### Step 7: Set Up Database

```bash
# Run migrations
php artisan migrate --force

# (Optional) Add dummy data
php artisan db:seed --class=ProfileDataSeeder
php artisan db:seed --class=JobPostingSeeder
php artisan db:seed --class=CpdApplicationSeeder
```

### Step 8: Set Permissions

```bash
sudo chown -R www-data:www-data /var/www/utb-hr-central
sudo chmod -R 775 /var/www/utb-hr-central/storage
sudo chmod -R 775 /var/www/utb-hr-central/bootstrap/cache
```

### Step 9: Configure Nginx

```bash
sudo nano /etc/nginx/sites-available/utb-hr-central
```

Paste this configuration:
```nginx
server {
    listen 80;
    server_name _;
    root /var/www/utb-hr-central/public;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

Enable site:
```bash
sudo ln -s /etc/nginx/sites-available/utb-hr-central /etc/nginx/sites-enabled/
sudo rm /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl reload nginx
```

### Step 10: Optimize and Test

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**Test:** Open browser and go to `http://vm_ip_address` or `http://localhost`

---

## Option B: Windows VM

If your VM is running Windows, we'll use Laragon, XAMPP, or IIS.

### Method 1: Using Laragon (Easiest for Windows)

#### Step 1: Download and Install Laragon

1. Download Laragon from: https://laragon.org/download/
2. Install it (includes PHP, MySQL, Nginx/Apache)

#### Step 2: Clone Your Project

```powershell
# Open Laragon terminal or PowerShell
cd C:\laragon\www
git clone https://github.com/MatinShmli/UTB-HR-CENTRAL-PROJECT-HANDOVER.git utb-hr-central
cd utb-hr-central
```

#### Step 3: Install Dependencies

```powershell
# Install Composer (if not installed)
# Download from: https://getcomposer.org/download/

# Install PHP packages
composer install --optimize-autoloader --no-dev

# Install Node packages
npm install

# Build frontend
npm run build
```

#### Step 4: Configure Database in Laragon

1. Open Laragon
2. Click "Database" → "Create Database"
3. Name: `utb_hr_central`
4. Note the MySQL root password (usually empty or "root")

#### Step 5: Configure Environment

```powershell
copy .env.example .env
notepad .env
```

Update:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=utb_hr_central
DB_USERNAME=root
DB_PASSWORD=
```

```powershell
php artisan key:generate
```

#### Step 6: Run Migrations

```powershell
php artisan migrate --force
```

#### Step 7: Start Laragon

1. Click "Start All" in Laragon
2. Visit: `http://utb-hr-central.test` or `http://localhost`

---

### Method 2: Using XAMPP (Alternative for Windows)

#### Step 1: Install XAMPP

1. Download from: https://www.apachefriends.org/
2. Install to `C:\xampp`

#### Step 2: Clone Project

```powershell
cd C:\xampp\htdocs
git clone https://github.com/MatinShmli/UTB-HR-CENTRAL-PROJECT-HANDOVER.git utb-hr-central
cd utb-hr-central
```

#### Step 3: Install Dependencies

```powershell
composer install --optimize-autoloader --no-dev
npm install
npm run build
```

#### Step 4: Configure Database

1. Open XAMPP Control Panel
2. Start Apache and MySQL
3. Open phpMyAdmin: http://localhost/phpmyadmin
4. Create database: `utb_hr_central`

#### Step 5: Configure Environment

```powershell
copy .env.example .env
notepad .env
```

Update:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=utb_hr_central
DB_USERNAME=root
DB_PASSWORD=
```

```powershell
php artisan key:generate
php artisan migrate --force
```

#### Step 6: Configure Apache

Edit `C:\xampp\apache\conf\httpd.conf`:

```apache
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/utb-hr-central/public"
    ServerName localhost
    <Directory "C:/xampp/htdocs/utb-hr-central/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Restart Apache in XAMPP Control Panel.

**Test:** Visit `http://localhost`

---

## Option C: Simple PHP Built-in Server (Quick Test)

If you just want to test quickly on any OS:

```bash
# Navigate to project
cd /path/to/utb-hr-central

# Start server
php artisan serve --host=0.0.0.0 --port=8000
```

Then access: `http://vm_ip_address:8000` or `http://localhost:8000`

**Note:** This is for testing only, not production!

---

## Troubleshooting

### Can't Connect to Database

- Check MySQL/MariaDB is running
- Verify credentials in `.env`
- Check firewall settings

### Permission Errors (Linux)

```bash
sudo chown -R www-data:www-data /var/www/utb-hr-central
sudo chmod -R 775 storage bootstrap/cache
```

### 500 Error

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

Then rebuild:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Assets Not Loading

```bash
npm run build
php artisan view:clear
```

---

## Next Steps

Once your website is running:

1. ✅ Test login functionality
2. ✅ Test file uploads
3. ✅ Configure email (if needed)
4. ✅ Set up SSL (if accessing remotely)
5. ✅ Configure firewall rules

---

**Need Help?** Let me know:
- Your VM's operating system
- How you access it
- Any error messages you're seeing

