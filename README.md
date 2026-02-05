# Hakim Clinics

<p align="center">
  <img src="public/images/logo.svg" alt="Hakim Clinics" width="120">
</p>

<p align="center">
  <strong>Clinic booking & management platform</strong>
</p>

---

## 🏥 About

**Hakim Clinics** is a clinic booking and management system for patients and doctors. It provides public clinic discovery, appointment booking, and a clinic workspace for managing patients and examinations.

## ✨ Features

- Public clinic listing and search
- Appointment booking (guest and authenticated)
- Patient dashboards and appointment history
- Clinic workspace for doctors
- Admin clinic approvals and specialty management

## 🛠 Technical Stack

| Component | Technology |
|-----------|------------|
| Framework | Laravel 11.x |
| Frontend | Bootstrap 5, Vite, Blade |
| Database | MySQL 8.0 |
| Authentication | Laravel Fortify + Sanctum |
| Permissions | Spatie Laravel Permission |

## 📦 Installation

```bash
composer install
npm install

cp .env.example .env
php artisan key:generate

npm run build
php artisan migrate --seed
php artisan serve
```

## 📄 License

Proprietary Software - All Rights Reserved © 2026
