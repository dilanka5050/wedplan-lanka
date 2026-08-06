# 💍 WedPlan Lanka

A modern SaaS wedding planning web application for Sri Lankan couples built with Laravel 12, TailwindCSS, and Alpine.js.

## Requirements

- PHP 8.2+ with extensions: `pdo_mysql`, `intl`, `mbstring`, `openssl`
- MySQL 8.0+
- Composer
- Node.js (optional, for Vite asset compilation)

## Quick Setup

### 1. Configure Database

Edit `.env` and set your MySQL credentials:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wedplan_lanka
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 2. Create Database

```sql
CREATE DATABASE wedplan_lanka;
```

### 3. Run Migrations & Seed

```bash
php artisan migrate
php artisan db:seed
php artisan storage:link
```

### 4. Start Server

```bash
php artisan serve
```

### 5. Access the Application

| URL | Credentials |
|-----|-------------|
| http://localhost:8000 | Landing page |
| http://localhost:8000/login | Demo: `demo@wedplanlanka.com` / `password` |
| http://localhost:8000/admin | Admin: `admin@wedplanlanka.com` / `password` |

## Features

### For Couples (Free Plan)
- ✅ Wedding workspace setup (Sinhala, Tamil, Muslim, Christian)
- ✅ Smart checklist with pre-built Sri Lankan wedding templates
- ✅ Budget tracker with estimated vs actual costs
- ✅ Guest management with RSVP tracking
- ✅ Wedding countdown
- ✅ Progress dashboard

### Pro Plan (Admin-upgraded)
- 📅 Wedding timeline with Nekath time support
- 📁 File uploads (inspiration, contracts)
- 👥 Unlimited guests & tasks
- 🎨 Full wedding templates
- 🤝 Multi-user collaboration

### Admin Panel
- Manage users & upgrade to Pro
- Manage weddings
- Manage subscription plans
- View analytics

## Architecture

```
app/
├── Enums/          # UserRole, WeddingType, TaskStatus, Priority, RsvpStatus
├── Http/
│   ├── Controllers/    # Web controllers
│   ├── Middleware/     # CheckPlanFeature, CheckPlanLimit
│   └── Requests/      # Form request validation
├── Models/         # Eloquent models
├── Policies/       # Authorization policies
└── Services/       # Business logic (WeddingService)
```

## Tech Stack

- **Backend:** Laravel 12, PHP 8.2
- **Frontend:** TailwindCSS (CDN), Alpine.js
- **Database:** MySQL
- **Auth:** Custom (Laravel-based)

## Sri Lankan Wedding Templates

Pre-built checklists for:
- **Sinhala** - Poruwa ceremony, Jayamangala Gatha, Nekath time
- **Tamil** - Thali ceremony, Nadaswaram, Muhurtham
- **Muslim** - Nikah, Walima, Mahar
- **Christian** - Church ceremony, Choir, Priest meeting
