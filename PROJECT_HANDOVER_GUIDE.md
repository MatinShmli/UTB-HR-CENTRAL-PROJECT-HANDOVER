# UTB HR Central - Project Handover Guide

**Purpose**: Complete guide for transferring the UTB HR Central project to another developer/team member.

---

## Table of Contents

1. [Overview](#overview)
2. [Pre-Handover Checklist](#pre-handover-checklist)
3. [Step 1: Initialize Git Repository](#step-1-initialize-git-repository)
4. [Step 2: Prepare Project Files](#step-2-prepare-project-files)
5. [Step 3: Export Database](#step-3-export-database)
6. [Step 4: Document Environment Setup](#step-4-document-environment-setup)
7. [Step 5: Create Handover Documentation](#step-5-create-handover-documentation)
8. [Step 6: Transfer to New Developer](#step-6-transfer-to-new-developer)
9. [Step 7: New Developer Setup](#step-7-new-developer-setup)
10. [Important Files and Credentials](#important-files-and-credentials)
11. [Troubleshooting Common Issues](#troubleshooting-common-issues)

---

## Overview

This guide will help you transfer the UTB HR Central Laravel project from your local machine to another developer. The handover process involves:

1. **Code Transfer**: Using Git/GitHub for version control
2. **Database Export**: Exporting current database data
3. **Environment Configuration**: Documenting setup requirements
4. **Documentation**: Providing all necessary documentation
5. **Access Transfer**: Sharing credentials and access information

**Note**: GitHub alone is NOT enough. You need to transfer code, database, environment setup, and documentation.

---

## Pre-Handover Checklist

Before starting the handover process, ensure you have:

- [ ] All code changes committed
- [ ] Database exported
- [ ] Environment variables documented
- [ ] All credentials documented
- [ ] Server/hosting information (if applicable)
- [ ] Third-party service API keys documented
- [ ] User manual and technical documentation ready

---

## Step 1: Initialize Git Repository

### 1.1 Initialize Git (if not already done)

```bash
# Navigate to project directory
cd C:\Users\ampma\Herd\internutb

# Initialize git repository
git init

# Add all files
git add .

# Create initial commit
git commit -m "Initial commit - UTB HR Central project"
```

### 1.2 Create GitHub Repository

1. Go to GitHub.com and sign in
2. Click "New Repository"
3. Repository name: `utb-hr-central` (or your preferred name)
4. Description: "UTB HR Central - Human Resources Management System"
5. Set to **Private** (recommended for organizational projects)
6. **DO NOT** initialize with README, .gitignore, or license (you already have these)
7. Click "Create repository"

### 1.3 Connect Local Repository to GitHub

```bash
# Add remote repository (replace YOUR_USERNAME with your GitHub username)
git remote add origin https://github.com/YOUR_USERNAME/utb-hr-central.git

# Rename branch to main (if needed)
git branch -M main

# Push code to GitHub
git push -u origin main
```

### 1.4 Verify Upload

- Go to your GitHub repository
- Verify all files are uploaded
- Check that sensitive files (.env) are NOT included (they should be in .gitignore)

---

## Step 2: Prepare Project Files

### 2.1 Files to Include in Git

✅ **Include these files:**
- All PHP files (app/, routes/, config/, etc.)
- All Blade templates (resources/views/)
- Database migrations (database/migrations/)
- Database seeders (database/seeders/)
- Package files (composer.json, package.json)
- Configuration files (config/)
- Public assets (public/images/, public/css/, etc.)
- .gitignore file
- README.md (create if doesn't exist)

### 2.2 Files to EXCLUDE (Already in .gitignore)

❌ **These should NOT be in Git:**
- `.env` file (contains sensitive credentials)
- `vendor/` directory (can be regenerated)
- `node_modules/` directory (can be regenerated)
- `storage/logs/` (log files)
- Database files (if using SQLite)

### 2.3 Create .env.example File

Create a template file for environment variables:

```bash
# Copy .env to .env.example
copy .env .env.example
```

Then edit `.env.example` and:
- Remove actual passwords and keys
- Replace with placeholder values
- Add comments explaining each variable

Example `.env.example`:
```env
APP_NAME=UTB_HR_Central
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=utb_hr_central
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

# Add other environment variables with placeholders
```

**Important**: Make sure `.env.example` is committed to Git, but `.env` is not.

---

## Step 3: Export Database

### 3.1 Export Database Structure and Data

#### Option A: Using MySQL Command Line

```bash
# Export database (replace with your actual database name and credentials)
mysqldump -u root -p utb_hr_central > database_backup.sql
```

#### Option B: Using phpMyAdmin

1. Open phpMyAdmin
2. Select your database (`utb_hr_central`)
3. Click "Export" tab
4. Select "Quick" or "Custom" export method
5. Choose format: **SQL**
6. Click "Go" to download
7. Save as `database_backup.sql`

#### Option C: Using Laravel Herd (if using Herd)

```bash
# Herd uses MySQL, find the database name and export
# Check database name in .env file
mysqldump -u root -p utb_hr_central > database_backup.sql
```

### 3.2 Store Database Backup

- Save `database_backup.sql` in a secure location
- Upload to GitHub as a release asset (if repository is private)
- OR share via secure file transfer (Google Drive, OneDrive with password)
- **DO NOT** commit database backup to Git repository (too large and contains sensitive data)

### 3.3 Document Database Information

Create a file `DATABASE_INFO.md` with:
- Database name
- Database host
- Database user (without password)
- Database structure overview
- Important tables and their purposes

---

## Step 4: Document Environment Setup

### 4.1 Create SETUP_INSTRUCTIONS.md

Create a comprehensive setup guide:

```markdown
# UTB HR Central - Setup Instructions

## System Requirements
- PHP 8.2 or higher
- Composer
- Node.js and NPM
- MySQL 5.7+ or MariaDB
- Laravel Herd (recommended) or XAMPP/WAMP

## Installation Steps

1. Clone repository
2. Install PHP dependencies
3. Install Node dependencies
4. Setup environment file
5. Generate application key
6. Run migrations
7. Seed database (optional)
8. Start development server

## Detailed Steps
[Add detailed instructions]
```

### 4.2 Document Required Software

List all software needed:
- Laravel Herd / XAMPP / WAMP
- Composer
- Node.js
- Git
- Code editor (VS Code, PHPStorm, etc.)
- MySQL client (if needed)

### 4.3 Document Server/Hosting Information (if applicable)

If the project is deployed:
- Server IP address
- Hosting provider
- SSH access details (share securely)
- Domain name
- SSL certificate information
- Backup procedures

---

## Step 5: Create Handover Documentation

### 5.1 Create HANDOVER_NOTES.md

Document important information:

```markdown
# Handover Notes - UTB HR Central

## Project Overview
[Brief description]

## Key Features
[List main features]

## Important Notes
- [Any special configurations]
- [Known issues]
- [Future improvements needed]

## Contact Information
- Previous Developer: [Your contact]
- Organization Contact: [HR/IT contact]
```

### 5.2 Document Credentials (Securely)

Create a **PASSWORD_PROTECTED** document with:

**DO NOT commit this to Git!** Share separately via secure method.

- Database credentials
- Admin account credentials (if any)
- Third-party API keys
- Email service credentials
- Any other sensitive information

**Recommendation**: Use a password manager (LastPass, 1Password) or encrypted file.

### 5.3 Create README.md

If it doesn't exist, create a comprehensive README:

```markdown
# UTB HR Central

Human Resources Management System for Universiti Teknologi Brunei

## Installation
[Installation steps]

## Configuration
[Configuration details]

## Usage
[Basic usage instructions]

## Documentation
- User Manual: UTB_HR_Central_User_Manual.docx
- Technical Documentation: [Link or file]
```

---

## Step 6: Transfer to New Developer

### 6.1 Share Repository Access

1. **Add Collaborator to GitHub Repository:**
   - Go to repository Settings → Collaborators
   - Add new developer's GitHub username
   - Grant appropriate access level

2. **OR Transfer Repository Ownership:**
   - Settings → General → Transfer ownership
   - Enter new owner's GitHub username

### 6.2 Share Database Backup

- Upload `database_backup.sql` to secure cloud storage
- Share link with new developer (password-protected if possible)
- OR transfer via USB drive
- OR use secure file transfer service

### 6.3 Share Credentials Securely

- Use encrypted file or password manager
- Share via secure communication channel
- **Never** share via email or unencrypted channels

### 6.4 Provide Documentation

Share these documents:
- User Manual (UTB_HR_Central_User_Manual.docx)
- Setup Instructions
- Handover Notes
- Database Information
- Any other relevant documentation

---

## Step 7: New Developer Setup

### 7.1 Clone Repository

```bash
# Clone the repository
git clone https://github.com/YOUR_USERNAME/utb-hr-central.git

# Navigate to project directory
cd utb-hr-central
```

### 7.2 Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Build frontend assets
npm run build
```

### 7.3 Setup Environment

```bash
# Copy environment file
copy .env.example .env

# Generate application key
php artisan key:generate

# Edit .env file with actual database credentials
# [Edit .env file manually]
```

### 7.4 Setup Database

```bash
# Create database (if not exists)
# [Create database in MySQL]

# Import database backup
mysql -u root -p utb_hr_central < database_backup.sql

# OR run migrations (if starting fresh)
php artisan migrate

# Run seeders (optional, for dummy data)
php artisan db:seed
```

### 7.5 Start Development Server

```bash
# Start Laravel development server
php artisan serve

# OR if using Laravel Herd, the site should be accessible automatically
```

### 7.6 Verify Installation

- Access the application in browser
- Test login functionality
- Verify database connection
- Check all features are working

---

## Important Files and Credentials

### Files to Share (Securely)

1. **Database Backup** (`database_backup.sql`)
   - Contains all current data
   - Share via secure method

2. **Environment Variables** (`.env` file contents)
   - Database credentials
   - Application key
   - Mail configuration
   - Any API keys

3. **Admin Credentials**
   - Default admin account (if exists)
   - Test user accounts

4. **Server Access** (if deployed)
   - SSH keys
   - FTP credentials
   - Hosting panel access

### Files Already in Repository

- All source code
- Database migrations
- Configuration templates
- Documentation files

---

## Troubleshooting Common Issues

### Issue: Database Connection Error

**Solution:**
- Verify database credentials in `.env`
- Ensure MySQL service is running
- Check database exists
- Verify user has proper permissions

### Issue: Missing Vendor Files

**Solution:**
```bash
composer install
```

### Issue: Missing Node Modules

**Solution:**
```bash
npm install
npm run build
```

### Issue: Application Key Error

**Solution:**
```bash
php artisan key:generate
```

### Issue: Permission Errors (Linux/Mac)

**Solution:**
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Issue: Migration Errors

**Solution:**
```bash
# Reset database (WARNING: Deletes all data)
php artisan migrate:fresh

# OR import backup
mysql -u root -p database_name < database_backup.sql
```

---

## Complete Handover Checklist

### For You (Current Developer)

- [ ] Initialize Git repository
- [ ] Push code to GitHub
- [ ] Create .env.example file
- [ ] Export database backup
- [ ] Document environment setup
- [ ] Create setup instructions
- [ ] Document all credentials (securely)
- [ ] Create handover notes
- [ ] Share repository access
- [ ] Share database backup
- [ ] Share credentials securely
- [ ] Provide all documentation
- [ ] Test that new developer can setup project

### For New Developer

- [ ] Clone repository from GitHub
- [ ] Install PHP dependencies (composer install)
- [ ] Install Node dependencies (npm install)
- [ ] Copy .env.example to .env
- [ ] Configure .env file with credentials
- [ ] Generate application key
- [ ] Import database backup
- [ ] Run migrations (if needed)
- [ ] Build frontend assets
- [ ] Start development server
- [ ] Verify application works
- [ ] Review documentation
- [ ] Understand project structure

---

## Additional Recommendations

### 1. Use GitHub Issues for Known Problems

Create GitHub issues for:
- Known bugs
- Future improvements
- Technical debt
- Documentation needs

### 2. Create Development Branch

```bash
# Create and switch to development branch
git checkout -b development

# Push development branch
git push -u origin development
```

### 3. Document API Endpoints

If the system has APIs, document them:
- Endpoint URLs
- Request/Response formats
- Authentication requirements

### 4. Set Up CI/CD (Optional)

Consider setting up:
- GitHub Actions for automated testing
- Automated deployment
- Code quality checks

### 5. Regular Backups

Ensure new developer understands:
- Database backup procedures
- Code backup (Git handles this)
- File storage backups (if applicable)

---

## Quick Start Commands Summary

### For Current Developer (Handover)

```bash
# Initialize Git
git init
git add .
git commit -m "Initial commit"

# Connect to GitHub
git remote add origin https://github.com/USERNAME/utb-hr-central.git
git push -u origin main

# Export database
mysqldump -u root -p utb_hr_central > database_backup.sql

# Create .env.example
copy .env .env.example
# [Edit .env.example to remove sensitive data]
```

### For New Developer (Setup)

```bash
# Clone repository
git clone https://github.com/USERNAME/utb-hr-central.git
cd utb-hr-central

# Install dependencies
composer install
npm install
npm run build

# Setup environment
copy .env.example .env
# [Edit .env with actual credentials]
php artisan key:generate

# Import database
mysql -u root -p utb_hr_central < database_backup.sql

# Start server
php artisan serve
```

---

## Security Reminders

⚠️ **IMPORTANT SECURITY NOTES:**

1. **Never commit sensitive files to Git:**
   - `.env` file
   - Database backups with real data
   - API keys and passwords
   - Private keys

2. **Always use .env.example** for templates

3. **Share credentials securely:**
   - Use password-protected files
   - Use encrypted communication
   - Use password managers

4. **Change all passwords** after handover:
   - Database passwords
   - Admin account passwords
   - API keys (if possible)

5. **Review access permissions:**
   - GitHub repository access
   - Server access
   - Database access

---

## Contact and Support

If you encounter issues during handover:

1. Review this guide
2. Check Laravel documentation: https://laravel.com/docs
3. Check project documentation
4. Contact previous developer (if available)
5. Contact IT support

---

**End of Handover Guide**

