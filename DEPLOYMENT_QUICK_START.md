# Quick Deployment Guide - UTB HR Central

## Fastest Deployment Options

### 🚀 Option 1: Laravel Forge (Easiest - Recommended for Beginners)

1. **Sign up** at https://forge.laravel.com ($12/month)
2. **Add server** (DigitalOcean, AWS, etc.) or use existing
3. **Create site** with your domain
4. **Connect GitHub** repository
5. **Deploy** - Forge handles everything automatically!

**Time**: 15-30 minutes  
**Difficulty**: ⭐ Easy

---

### 🌐 Option 2: Shared Hosting (cPanel)

1. **Buy hosting** (Hostinger, SiteGround, etc.)
2. **Upload files** via FTP/cPanel File Manager
3. **Install dependencies** via cPanel Terminal:
   ```bash
   composer install --optimize-autoloader --no-dev
   npm run build
   ```
4. **Configure** `.env` file
5. **Run migrations**: `php artisan migrate --force`

**Time**: 1-2 hours  
**Difficulty**: ⭐⭐ Medium

---

### 💻 Option 3: VPS Server (Full Control)

**Prerequisites**: Basic Linux knowledge

1. **Get VPS** (DigitalOcean, Linode, Vultr - $10-20/month)
2. **Follow VPS setup** in `DEPLOYMENT_GUIDE.md` (Option 2)
3. **Use deployment script**: `bash deploy.sh`

**Time**: 2-3 hours  
**Difficulty**: ⭐⭐⭐ Advanced

---

## Minimum Requirements

- **PHP**: 8.2+
- **MySQL**: 5.7+
- **RAM**: 1 GB (minimum), 2 GB (recommended)
- **Storage**: 10 GB
- **Domain**: Your domain name
- **SSL**: Free from Let's Encrypt

---

## Quick Checklist

Before deploying:

- [ ] Code is in GitHub
- [ ] `.env.example` is updated
- [ ] Frontend assets built (`npm run build`)
- [ ] Database backup created
- [ ] Domain DNS configured

After deploying:

- [ ] Application loads correctly
- [ ] Login works
- [ ] File uploads work
- [ ] Database connected
- [ ] SSL certificate installed
- [ ] Emails sending (if configured)

---

## Need Help?

1. **Read full guide**: `DEPLOYMENT_GUIDE.md`
2. **Check logs**: `storage/logs/laravel.log`
3. **Common issues**: See Troubleshooting section in `DEPLOYMENT_GUIDE.md`

---

## Recommended Providers

### For Beginners
- **Laravel Forge** - Managed Laravel hosting
- **Shared Hosting** - cPanel-based hosting

### For Advanced Users
- **DigitalOcean** - VPS ($10/month)
- **Linode** - VPS ($12/month)
- **Vultr** - VPS ($6/month)

### For Enterprise
- **AWS** - Cloud platform
- **Google Cloud** - Cloud platform
- **Azure** - Cloud platform

---

**Quick Start**: If you're new to server deployment, start with **Laravel Forge** - it's the easiest option!

