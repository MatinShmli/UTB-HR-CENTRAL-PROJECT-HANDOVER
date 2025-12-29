# Windows Server Hosting Guide - UTB HR Central

This guide will help you set up your Windows VM as a web server so others can access your website.

---

## Overview

We'll set up:
- ✅ Web server (IIS or Apache/Nginx via Laragon)
- ✅ PHP and Laravel application
- ✅ MySQL database
- ✅ Firewall configuration
- ✅ Network access
- ✅ (Optional) SSL/HTTPS

---

## Step 1: Connect to Your Windows VM Server

1. Open **Remote Desktop Connection**
2. Connect to your server
3. Make sure you have **Administrator** access

---

## Step 2: Install Required Software

### Option A: Laragon (Easiest - Recommended)

1. **Download Laragon Full:**
   - Go to: https://laragon.org/download/
   - Download **Full** version
   - Install to `C:\laragon` (default)

2. **Start Laragon:**
   - Open Laragon
   - Click **Start All**
   - Wait for all services to start (green indicators)

### Option B: IIS (Windows Server)

If you're using Windows Server, you can use IIS:

1. **Install IIS:**
   - Open **Server Manager**
   - **Add Roles and Features**
   - Select **Web Server (IIS)**
   - Install with these features:
     - CGI
     - URL Rewrite Module
     - PHP Manager

2. **Install PHP:**
   - Download PHP 8.2 from: https://windows.php.net/download/
   - Extract to `C:\php`
   - Add to PATH

3. **Install MySQL:**
   - Download MySQL from: https://dev.mysql.com/downloads/installer/
   - Install MySQL Server

---

## Step 3: Deploy Your Application

### Using Laragon:

1. **Open Terminal in Laragon** (or PowerShell as Administrator)

2. **Clone your project:**
```powershell
cd C:\laragon\www
git clone https://github.com/MatinShmli/UTB-HR-CENTRAL-PROJECT-HANDOVER.git utb-hr-central
cd utb-hr-central
```

3. **Install dependencies:**
```powershell
composer install --optimize-autoloader --no-dev
npm install
npm run build
```

4. **Create database:**
   - In Laragon, click **Database**
   - Click **Create Database**
   - Name: `utb_hr_central`

5. **Configure environment:**
```powershell
copy .env.example .env
notepad .env
```

Update `.env` file:
```env
APP_NAME="UTB HR Central"
APP_ENV=production
APP_DEBUG=false
APP_URL=http://YOUR_SERVER_IP
# or if you have a domain:
# APP_URL=http://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=utb_hr_central
DB_USERNAME=root
DB_PASSWORD=
```

**Important:** Replace `YOUR_SERVER_IP` with your actual server IP address!

6. **Generate key and migrate:**
```powershell
php artisan key:generate
php artisan migrate --force
```

7. **Optimize:**
```powershell
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Step 4: Configure Web Server for Network Access

### If Using Laragon:

Laragon uses Nginx or Apache. We need to configure it for network access:

1. **Find your server's IP address:**
```powershell
ipconfig
```
Note your **IPv4 Address** (e.g., 192.168.1.100 or a public IP)

2. **Configure Laragon to listen on all interfaces:**
   - In Laragon, click **Menu** → **Preferences**
   - Under **Nginx** or **Apache**, make sure it's configured to listen on `0.0.0.0:80` (all interfaces)
   - Laragon usually does this by default

3. **Test locally first:**
   - On the server, open browser
   - Visit: `http://localhost` or `http://utb-hr-central.test`
   - Should see your website

### If Using IIS:

1. **Add Website:**
   - Open **IIS Manager**
   - Right-click **Sites** → **Add Website**
   - Site name: `UTB HR Central`
   - Physical path: `C:\laragon\www\utb-hr-central\public`
   - Binding: 
     - Type: `http`
     - IP address: `All Unassigned` (or your specific IP)
     - Port: `80`
     - Host name: (leave empty for IP access)

2. **Install URL Rewrite:**
   - Download from: https://www.iis.net/downloads/microsoft/url-rewrite
   - Install the module

3. **Configure web.config:**
   - In `C:\laragon\www\utb-hr-central\public`, create `web.config`:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<configuration>
    <system.webServer>
        <rewrite>
            <rules>
                <rule name="Imported Rule 1" stopProcessing="true">
                    <match url="^(.*)/$" ignoreCase="false" />
                    <conditions>
                        <add input="{REQUEST_FILENAME}" matchType="IsDirectory" ignoreCase="false" negate="true" />
                    </conditions>
                    <action type="Redirect" redirectType="Permanent" url="/{R:1}" />
                </rule>
                <rule name="Imported Rule 2" stopProcessing="true">
                    <match url="^" ignoreCase="false" />
                    <conditions>
                        <add input="{REQUEST_FILENAME}" matchType="IsDirectory" ignoreCase="false" negate="true" />
                        <add input="{REQUEST_FILENAME}" matchType="IsFile" ignoreCase="false" negate="true" />
                    </conditions>
                    <action type="Rewrite" url="index.php" />
                </rule>
            </rules>
        </rewrite>
    </system.webServer>
</configuration>
```

---

## Step 5: Configure Windows Firewall

**This is critical** - without this, others can't access your server!

1. **Open Windows Defender Firewall:**
   - Press `Win + R`
   - Type: `wf.msc`
   - Press Enter

2. **Create Inbound Rule for HTTP (Port 80):**
   - Click **Inbound Rules** → **New Rule**
   - Select **Port** → Next
   - Select **TCP**
   - Enter **80** in "Specific local ports"
   - Click **Next**
   - Select **Allow the connection**
   - Check all profiles (Domain, Private, Public)
   - Name: `Web Server HTTP (Port 80)`
   - Click **Finish**

3. **Create Inbound Rule for HTTPS (Port 443) - Optional but Recommended:**
   - Same steps as above, but use port **443**
   - Name: `Web Server HTTPS (Port 443)`

---

## Step 6: Get Your Server's IP Address

### For Local Network Access:

```powershell
ipconfig
```

Look for **IPv4 Address** under your network adapter (e.g., 192.168.1.100)

**Access from other computers on same network:**
- `http://192.168.1.100` (replace with your IP)

### For Internet Access:

You need a **public IP address**:

1. **Check if you have a public IP:**
   - Visit: https://whatismyipaddress.com/ from your server
   - This shows your public IP

2. **Configure Router (if behind NAT):**
   - Log into your router
   - Set up **Port Forwarding**:
     - External Port: 80
     - Internal IP: Your server's local IP (e.g., 192.168.1.100)
     - Internal Port: 80
     - Protocol: TCP

3. **Update .env file:**
```powershell
notepad .env
```

Change:
```env
APP_URL=http://YOUR_PUBLIC_IP
# or if you have a domain:
# APP_URL=http://yourdomain.com
```

Then:
```powershell
php artisan config:clear
php artisan config:cache
```

---

## Step 7: Test Access

### Test from Server:

1. Open browser on server
2. Visit: `http://localhost` or `http://YOUR_SERVER_IP`
3. Should see your website

### Test from Another Computer:

1. On another computer (same network or internet)
2. Open browser
3. Visit: `http://YOUR_SERVER_IP` or `http://YOUR_PUBLIC_IP`
4. Should see your website

---

## Step 8: Configure Domain Name (Optional)

If you have a domain name:

1. **Point DNS to your server:**
   - In your domain registrar's DNS settings
   - Add **A Record**:
     - Name: `@` (or `www`)
     - Value: Your public IP address
     - TTL: 3600

2. **Update .env:**
```env
APP_URL=http://yourdomain.com
```

3. **Clear and rebuild cache:**
```powershell
php artisan config:clear
php artisan config:cache
```

---

## Step 9: Set Up SSL/HTTPS (Recommended)

### Using Let's Encrypt with Laragon:

1. **Install Certbot for Windows:**
   - Download: https://github.com/certbot/certbot/releases
   - Or use Win-ACME: https://www.win-acme.com/

2. **Generate SSL Certificate:**
   - Follow Win-ACME wizard
   - Select your website
   - It will automatically configure IIS/Nginx

### Manual SSL Setup:

1. **Obtain SSL certificate** (from Let's Encrypt or paid provider)
2. **Configure in IIS/Nginx** to use the certificate
3. **Update .env:**
```env
APP_URL=https://yourdomain.com
```

4. **Force HTTPS** (in Laravel):
   - Add to `app/Providers/AppServiceProvider.php`:
```php
use Illuminate\Support\Facades\URL;

public function boot()
{
    if (env('APP_ENV') === 'production') {
        URL::forceScheme('https');
    }
}
```

---

## Step 10: Security Hardening

### 1. Change Default Passwords

- MySQL root password
- Windows Administrator password (if possible)

### 2. Disable Unnecessary Services

- Only keep essential services running

### 3. Regular Updates

- Keep Windows updated
- Keep PHP, MySQL, and Laravel updated

### 4. Firewall Rules

- Only allow ports 80 (HTTP) and 443 (HTTPS)
- Block unnecessary ports

### 5. File Permissions

- Ensure proper file permissions
- Don't expose sensitive files

---

## Step 11: Set Up Automatic Startup

### Make Services Start Automatically:

1. **Laragon Auto-Start:**
   - In Laragon: **Menu** → **Preferences** → **Auto Start**
   - Enable auto-start for services

2. **Windows Services:**
   - Open `services.msc`
   - Set MySQL, Nginx/Apache to **Automatic** startup

---

## Troubleshooting

### Can't Access from Other Computers

1. **Check Firewall:**
   ```powershell
   # Test if port 80 is open
   netstat -an | findstr :80
   ```

2. **Check Windows Firewall:**
   - Make sure rule for port 80 is enabled
   - Try temporarily disabling firewall to test

3. **Check Web Server:**
   - Make sure Nginx/Apache/IIS is running
   - Check if it's listening on `0.0.0.0:80` (all interfaces)

4. **Check Router:**
   - If accessing from internet, verify port forwarding

### 500 Internal Server Error

```powershell
# Check Laravel logs
notepad storage\logs\laravel.log

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Rebuild
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Database Connection Error

1. **Check MySQL is running:**
   - In Laragon: Check MySQL status
   - Or: `services.msc` → MySQL

2. **Verify database exists:**
   - In Laragon: Database → Check `utb_hr_central`

3. **Check .env credentials**

### Assets Not Loading

```powershell
npm run build
php artisan view:clear
```

---

## Quick Reference Commands

```powershell
# Navigate to project
cd C:\laragon\www\utb-hr-central

# Check server IP
ipconfig

# Check if port 80 is listening
netstat -an | findstr :80

# Restart services (Laragon)
# Click "Stop All" then "Start All"

# Clear Laravel cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Rebuild cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Check Laravel logs
notepad storage\logs\laravel.log
```

---

## Network Access Summary

**For Local Network:**
- Server IP: `http://192.168.1.100` (your local IP)
- Accessible from computers on same network

**For Internet:**
- Public IP: `http://YOUR_PUBLIC_IP`
- Or Domain: `http://yourdomain.com`
- Requires port forwarding if behind router

---

## Next Steps

1. ✅ Test website from server
2. ✅ Test from another computer
3. ✅ Configure domain name (if available)
4. ✅ Set up SSL/HTTPS
5. ✅ Set up automatic backups
6. ✅ Monitor server performance

---

**Your website should now be accessible from other computers!**

If you need help with any step, let me know!

