# Windows VM Deployment Guide - Step by Step

## Prerequisites
- ✅ Windows VM (accessed via Remote Desktop)
- ✅ Administrator access
- ✅ Internet connection on the VM

---

## Step 1: Connect to Your VM

1. Open **Remote Desktop Connection** on your local machine
2. Enter your VM's IP address or hostname
3. Enter your credentials
4. Click **Connect**

You should now be connected to your Windows VM!

---

## Step 2: Install Required Software

### Option A: Laragon (Recommended - Easiest)

Laragon includes everything you need: PHP, MySQL, Nginx/Apache, Composer, and Node.js.

1. **Download Laragon:**
   - Go to: https://laragon.org/download/
   - Download the **Full** version (includes everything)
   - Run the installer

2. **Install Laragon:**
   - Choose installation path (default: `C:\laragon`)
   - Select **Nginx** or **Apache** (both work)
   - Complete the installation

3. **Start Laragon:**
   - Open Laragon
   - Click **Start All** button
   - Wait for all services to start (green indicators)

### Option B: Manual Installation (If not using Laragon)

If you prefer to install manually:

1. **Install PHP 8.2:**
   - Download from: https://windows.php.net/download/
   - Extract to `C:\php`
   - Add to PATH

2. **Install MySQL:**
   - Download from: https://dev.mysql.com/downloads/installer/
   - Install MySQL Server

3. **Install Composer:**
   - Download from: https://getcomposer.org/download/
   - Run the installer

4. **Install Node.js:**
   - Download from: https://nodejs.org/
   - Install LTS version

5. **Install Git:**
   - Download from: https://git-scm.com/download/win
   - Install with default options

---

## Step 3: Clone Your Project

### Using Laragon Terminal:

1. In Laragon, click **Terminal** button (or right-click → Terminal)
2. Run these commands:

```powershell
cd C:\laragon\www
git clone https://github.com/MatinShmli/UTB-HR-CENTRAL-PROJECT-HANDOVER.git utb-hr-central
cd utb-hr-central
```

### Using PowerShell or Command Prompt:

1. Open **PowerShell** or **Command Prompt** as Administrator
2. Run:

```powershell
cd C:\laragon\www
git clone https://github.com/MatinShmli/UTB-HR-CENTRAL-PROJECT-HANDOVER.git utb-hr-central
cd utb-hr-central
```

**Note:** If you're not using Laragon, use a different directory like `C:\inetpub\wwwroot` or `C:\xampp\htdocs`

---

## Step 4: Install PHP Dependencies

In the terminal (still in the project directory):

```powershell
composer install --optimize-autoloader --no-dev
```

This will take a few minutes. Wait for it to complete.

---

## Step 5: Install Node Dependencies and Build

```powershell
npm install
npm run build
```

Wait for both commands to complete.

---

## Step 6: Configure Database

### If Using Laragon:

1. In Laragon, click **Database** button
2. Click **Create Database**
3. Name: `utb_hr_central`
4. Click **Create**
5. Note: MySQL root password is usually **empty** (blank) in Laragon

### If Using MySQL Separately:

1. Open **MySQL Command Line Client** or **phpMyAdmin**
2. Run:

```sql
CREATE DATABASE utb_hr_central CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

---

## Step 7: Configure Environment File

1. In the project directory, copy the example file:

```powershell
copy .env.example .env
```

2. Open `.env` file in Notepad:

```powershell
notepad .env
```

3. **Update these important settings:**

```env
APP_NAME="UTB HR Central"
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

**Important:**
- If using Laragon: `DB_PASSWORD=` (leave empty)
- If you set a MySQL password: Enter it in `DB_PASSWORD`
- If accessing from another computer: Change `APP_URL` to `http://your-vm-ip-address`

4. **Save and close** Notepad (Ctrl+S, then close)

---

## Step 8: Generate Application Key

In the terminal:

```powershell
php artisan key:generate
```

You should see: `Application key set successfully.`

---

## Step 9: Run Database Migrations

```powershell
php artisan migrate --force
```

This will create all the database tables.

### (Optional) Add Dummy Data

If you want to populate the database with sample data:

```powershell
php artisan db:seed --class=ProfileDataSeeder
php artisan db:seed --class=JobPostingSeeder
php artisan db:seed --class=CpdApplicationSeeder
```

---

## Step 10: Optimize for Production

```powershell
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Step 11: Configure Web Server

### If Using Laragon:

Laragon automatically creates a virtual host! Just:

1. Make sure Laragon is running (click **Start All** if needed)
2. Visit: **http://utb-hr-central.test** in your browser

**That's it!** Laragon handles everything automatically.

### If Using IIS (Windows Server):

1. Open **IIS Manager**
2. Add a new website:
   - Site name: `UTB HR Central`
   - Physical path: `C:\laragon\www\utb-hr-central\public`
   - Binding: Port 80
3. Install **URL Rewrite Module** (if not installed)
4. Visit: **http://localhost**

### If Using Apache (XAMPP):

1. Edit `C:\xampp\apache\conf\httpd.conf`
2. Add at the end:

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

3. Restart Apache in XAMPP Control Panel
4. Visit: **http://localhost**

---

## Step 12: Test Your Website

1. Open a web browser on your VM
2. Visit:
   - **http://localhost** (if using Laragon: **http://utb-hr-central.test**)
   - Or **http://your-vm-ip-address** (if accessing from another computer)

You should see the UTB HR Central landing page!

---

## Step 13: Access from Other Computers

To access the website from other computers on the network:

1. **Find your VM's IP address:**
   ```powershell
   ipconfig
   ```
   Look for **IPv4 Address** (e.g., 192.168.1.100)

2. **Configure Windows Firewall:**
   - Open **Windows Defender Firewall**
   - Click **Advanced settings**
   - Click **Inbound Rules** → **New Rule**
   - Select **Port** → **TCP** → **80**
   - Allow the connection
   - Apply to all profiles
   - Name it: "Web Server HTTP"

3. **Update APP_URL in .env:**
   ```env
   APP_URL=http://your-vm-ip-address
   ```

4. **Clear cache:**
   ```powershell
   php artisan config:clear
   php artisan config:cache
   ```

5. **Access from another computer:**
   - Open browser
   - Visit: **http://your-vm-ip-address**

---

## Troubleshooting

### Website Shows 500 Error

```powershell
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Database Connection Error

1. **Check MySQL is running:**
   - In Laragon: Check if MySQL shows green (running)
   - Or check Services: `services.msc` → MySQL

2. **Verify database exists:**
   - In Laragon: Click **Database** → Check if `utb_hr_central` exists
   - Or in MySQL: `SHOW DATABASES;`

3. **Check .env file:**
   - Make sure `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` are correct

### Assets (CSS/JS) Not Loading

```powershell
npm run build
php artisan view:clear
```

### Permission Errors

If you see permission errors:

1. Right-click project folder → **Properties**
2. **Security** tab → **Edit**
3. Add **Everyone** with **Full control**
4. Apply to all subfolders

### Port 80 Already in Use

If port 80 is already used:

1. **Find what's using it:**
   ```powershell
   netstat -ano | findstr :80
   ```

2. **Stop the service** or change Laragon to use a different port

---

## Quick Reference Commands

```powershell
# Navigate to project
cd C:\laragon\www\utb-hr-central

# Start Laragon services
# (Click "Start All" in Laragon)

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Start development server (alternative to web server)
php artisan serve --host=0.0.0.0 --port=8000
```

---

## Next Steps After Deployment

1. ✅ Test login functionality
2. ✅ Test file uploads
3. ✅ Test PDF generation
4. ✅ Configure email settings (if needed)
5. ✅ Set up SSL certificate (for HTTPS)
6. ✅ Configure automatic backups

---

## Need Help?

If you encounter any issues:
1. Check Laravel logs: `storage\logs\laravel.log`
2. Check web server error logs
3. Make sure all services are running
4. Verify file permissions

---

**Ready to start?** Follow the steps above, and let me know if you need help with any specific step!

