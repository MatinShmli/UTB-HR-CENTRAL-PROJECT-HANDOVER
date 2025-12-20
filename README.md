# UTB HR Central

Human Resources Management System for Universiti Teknologi Brunei (UTB)

## Overview

UTB HR Central is a comprehensive web-based Human Resources Management System designed to streamline HR operations for Universiti Teknologi Brunei. The system provides a centralized platform for managing staff profiles, processing CPD applications, handling memo requests, managing job postings, and various administrative tasks.

## Features

- **User Profile Management**: Complete staff profile with personal, employment, and spouse information
- **CPD Applications**: Submit and manage Continuing Professional Development applications
- **Memo Requests**: Create and track memo requests for various purposes
- **Career at UTB**: View and apply for academic, non-academic, and Tabung staff positions
- **Service Application**: Download forms for visa, dependent pass, and residence permit applications
- **Insurance Information**: Access insurance plans and application information
- **Administrative Functions**: User management, approval workflows, and system administration

## Technology Stack

- **Backend**: PHP 8.2+ with Laravel Framework 12.0
- **Frontend**: HTML5, CSS3, JavaScript, Tailwind CSS 4.0
- **Database**: MySQL
- **Build Tool**: Vite 6.2.4
- **PDF Generation**: DomPDF (barryvdh/laravel-dompdf)

## System Requirements

- PHP 8.2 or higher
- Composer
- Node.js and NPM
- MySQL 5.7+ or MariaDB
- Laravel Herd (recommended) or XAMPP/WAMP

## Installation

### 1. Clone Repository

```bash
git clone https://github.com/YOUR_USERNAME/utb-hr-central.git
cd utb-hr-central
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node Dependencies

```bash
npm install
```

### 4. Setup Environment

```bash
# Copy environment file
copy .env.example .env

# Generate application key
php artisan key:generate

# Edit .env file with your database credentials
# [Edit .env file manually]
```

### 5. Setup Database

```bash
# Create database (if not exists)
# [Create database in MySQL]

# Import database backup (if available)
mysql -u root -p utb_hr_central < database_backup.sql

# OR run migrations (if starting fresh)
php artisan migrate

# Run seeders (optional, for dummy data)
php artisan db:seed --class=ProfileDataSeeder
php artisan db:seed --class=JobPostingSeeder
php artisan db:seed --class=CpdApplicationSeeder
```

### 6. Build Frontend Assets

```bash
npm run build
```

### 7. Start Development Server

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## Default Accounts

After running seeders, you can use:
- **Admin**: admin@utb.edu.bn / admin123
- **Test User**: test@example.com / password

## User Roles

- **Administrator**: Full system access
- **Head of Section**: Review and approve applications
- **Regular Staff**: Submit applications and manage profile

## Documentation

- **User Manual**: `UTB_HR_Central_User_Manual.docx`
- **Handover Guide**: `PROJECT_HANDOVER_GUIDE.md`
- **Technical Documentation**: See Laravel documentation at https://laravel.com/docs

## Project Structure

```
internutb/
├── app/
│   ├── Http/Controllers/     # Application controllers
│   ├── Models/               # Eloquent models
│   └── Middleware/           # Custom middleware
├── database/
│   ├── migrations/           # Database schema migrations
│   └── seeders/              # Database seeders
├── resources/
│   ├── views/                # Blade templates
│   └── css/                  # Stylesheets
├── routes/
│   └── web.php               # Web routes
└── public/                    # Public assets
```

## Important Notes

- The `.env` file contains sensitive information and should never be committed to Git
- Always use `.env.example` as a template for environment configuration
- Database backups should be stored securely and not committed to Git
- Regular backups of the database are recommended

## Support

For issues or questions:
- Review the User Manual
- Check the Handover Guide
- Contact the system administrator
- Contact IT support

## License

[Specify license if applicable]

## Contributors

[Add contributor information]

---

**For complete setup instructions, see PROJECT_HANDOVER_GUIDE.md**
"# UTB-HR-CENTRAL-PROJECT-HANDOVER" 
