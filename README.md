# Hakim Clinics

<p align="center">
  <img src="public/images/logo.svg" alt="Hakim Clinics" width="120">
</p>

<p align="center">
  <strong>Professional Clinic Management & Booking Platform</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/Bootstrap-5-7952B3?style=flat&logo=bootstrap&logoColor=white" alt="Bootstrap">
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat&logo=mysql&logoColor=white" alt="MySQL">
</p>

---

## 🏥 About

**Hakim Clinics** is a comprehensive clinic booking and management system designed for healthcare providers and patients. The platform enables clinics to manage appointments, patient records, and medical examinations while providing patients with easy access to clinic discovery and appointment booking.

### Key Highlights

- ✅ **Multi-language**: Full support for English and Arabic (RTL)
- 🔒 **Secure**: OTP authentication, Google OAuth, role-based permissions
- ⚡ **Performant**: Queue system, caching layer, optimized database queries
- 📋 **Medical Standards**: ICD-11 code support for diagnoses
- 📱 **Responsive**: Mobile-friendly design with Bootstrap 5
- 🧪 **Tested**: Comprehensive test suite with PHPUnit

## ✨ Features

### For Patients
- 🏥 Browse and search clinics by specialty and location
- 📅 Book appointments online
- 👤 Personal dashboard with appointment history
- 🔐 Secure authentication (OTP, Google OAuth)

### For Doctors/Clinics
- 📊 Clinic workspace dashboard
- 👥 Patient management with unique file numbers
- 🩺 Complete examination records with:
  - Chief complaints and medical history
  - Vital signs tracking
  - Physical examination notes
  - Diagnosis with ICD-11 codes
  - Treatment plans and prescriptions
  - Lab tests and imaging orders
  - Follow-up scheduling
- 📄 Printable examination reports
- 📎 File attachments for medical records
- 📆 Appointment management

### For Administrators
- ✅ Clinic approval workflow
- 🏥 Specialty management
- 👨‍⚕️ User and role management
- 📊 System configuration

## 🛠 Technical Stack

| Component | Technology |
|-----------|------------|
| Framework | Laravel 11.x |
| PHP Version | 8.2+ |
| Frontend | Bootstrap 5, Vite, Blade Templates |
| Database | MySQL 8.0 |
| Authentication | Laravel Fortify + Sanctum |
| Permissions | Spatie Laravel Permission |
| DataTables | Yajra Laravel DataTables |
| Queue | Database/Redis |
| Cache | File/Redis |

### Performance Features
- ⚡ **Queue System**: Asynchronous email sending
- 🗄️ **Caching**: Multi-layer caching for frequently accessed data
- 🔍 **Eager Loading**: Optimized database queries (N+1 prevention)
- 📦 **Asset Bundling**: Vite for fast builds

## 📦 Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MySQL 8.0 or higher
- Redis (optional, for production)

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd hakim
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure database** (edit `.env`)
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=hakim_clinics
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Configure queue** (edit `.env`)
   ```env
   QUEUE_CONNECTION=database  # or redis for production
   ```

6. **Run migrations and seeders**
   ```bash
   php artisan migrate --seed
   ```

7. **Build assets**
   ```bash
   npm run build
   ```

8. **Start queue worker** (new terminal)
   ```bash
   php artisan queue:work
   ```

9. **Start development server**
   ```bash
   php artisan serve
   ```

10. **Access the application**
    - Frontend: http://localhost:8000
    - Admin: http://localhost:8000/en/dashboard

### Default Credentials

After seeding, use these credentials:

**Admin:**
- Email: admin@hakim.com
- Password: password

**Doctor:**
- Email: doctor@hakim.com
- Password: password

## 🧪 Testing

Run the test suite:

```bash
# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run specific suite
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit
```

See [TESTING.md](docs/TESTING.md) for detailed testing guide.

## 📚 Documentation

- [API Documentation](docs/API.md) - Complete API reference
- [Testing Guide](docs/TESTING.md) - Testing best practices
- [CHANGELOG](CHANGELOG.md) - Version history and updates

## 🚀 Deployment

### Production Checklist

- [ ] Set `APP_ENV=production` and `APP_DEBUG=false`
- [ ] Configure Redis for cache and queue
- [ ] Set up HTTPS/SSL certificate
- [ ] Configure email provider (SMTP/SendGrid/etc.)
- [ ] Set up cron job for scheduled tasks:
  ```bash
  * * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
  ```
- [ ] Run queue worker as a service:
  ```bash
  php artisan queue:work --tries=3 --timeout=90
  ```
- [ ] Optimize for production:
  ```bash
  composer install --optimize-autoloader --no-dev
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  npm run build
  ```

## 📊 Project Structure

```
hakim/
├── app/
│   ├── Http/
│   │   ├── Controllers/    # Application controllers
│   │   ├── Middleware/     # Custom middleware
│   │   └── Requests/       # Form validation requests
│   ├── Models/             # Eloquent models
│   ├── Services/           # Business logic services
│   ├── Observers/          # Model observers
│   └── Enums/              # Enum classes
├── database/
│   ├── migrations/         # Database migrations
│   ├── seeders/           # Database seeders
│   └── factories/         # Model factories
├── resources/
│   ├── views/             # Blade templates
│   ├── js/                # JavaScript files
│   ├── css/               # Stylesheets
│   └── lang/              # Translations (en, ar)
├── routes/
│   ├── web.php            # Web routes
│   ├── api.php            # API routes
│   └── dashboard.php      # Admin routes
├── tests/
│   ├── Feature/           # Feature tests
│   └── Unit/              # Unit tests
└── docs/                  # Documentation
```

## 🤝 Contributing

This is a private project. If you have access and want to contribute:

1. Create a feature branch (`git checkout -b feature/amazing-feature`)
2. Commit your changes (`git commit -m 'Add amazing feature'`)
3. Push to the branch (`git push origin feature/amazing-feature`)
4. Open a Pull Request

## 📝 Code Quality Standards

- **PSR-12**: PHP coding standard
- **Testing**: Minimum 70% coverage
- **Documentation**: All public methods must be documented
- **Type Hints**: Use strict types where possible

## 📄 License

Proprietary Software - All Rights Reserved © 2026
