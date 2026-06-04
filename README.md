# CarsRental — Enterprise Car Rental Management System

A production-ready, full-stack car rental management system built with **Laravel 11** (REST API) and **React 18** (Vite + Tailwind CSS).

---

## Table of Contents

1. [System Architecture](#system-architecture)
2. [Database Schema](#database-schema)
3. [API Documentation](#api-documentation)
4. [Installation](#installation)
5. [Environment Setup](#environment-setup)
6. [PayPal Configuration](#paypal-configuration)
7. [Default Accounts](#default-accounts)
8. [Security](#security)
9. [Scalability](#scalability)
10. [Deployment Guide](#deployment-guide)

---

## System Architecture

```
CarsRental/
├── backend/          # Laravel 11 REST API
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/   # Thin controllers, delegate to services
│   │   │   ├── Middleware/    # JWT auth + Role authorization
│   │   │   └── Requests/      # Form Request validators (all validation here)
│   │   ├── Models/            # Eloquent models with relationships
│   │   ├── Repositories/      # Data access layer (Repository Pattern)
│   │   ├── Services/          # Business logic (Service Layer Pattern)
│   │   ├── Traits/            # ApiResponse trait (consistent JSON format)
│   │   └── Providers/         # RepositoryServiceProvider (DI bindings)
│   ├── database/
│   │   ├── migrations/        # Version-controlled schema
│   │   ├── seeders/           # Demo data + roles/permissions
│   │   └── factories/         # Test data factories
│   ├── routes/api.php         # All API routes with middleware groups
│   └── tests/Feature/        # PHPUnit feature tests
│
└── frontend/         # React 18 + Vite + Tailwind CSS
    └── src/
        ├── context/           # AuthContext + ToastContext (React Context API)
        ├── services/          # Axios-based API service layer
        ├── layouts/           # AuthLayout, AdminLayout, ClientLayout
        ├── pages/             # auth/, admin/, client/ page components
        ├── components/        # common/, vehicles/, reservations/ components
        └── utils/             # helpers.js, constants.js
```

### Design Patterns Used

| Pattern              | Location                       | Purpose                              |
|----------------------|--------------------------------|--------------------------------------|
| Repository Pattern   | `app/Repositories/`            | Decouple data access from business   |
| Service Layer        | `app/Services/`                | Centralise business logic            |
| Form Request         | `app/Http/Requests/`           | Validation at the boundary           |
| Middleware Guards    | `JwtMiddleware`, `RoleMiddleware` | Auth + RBAC enforcement             |
| Trait Mixin          | `ApiResponse`                  | Consistent JSON responses            |
| Context API          | `AuthContext`, `ToastContext`   | Global React state                   |

---

## Database Schema

### `users`
| Column          | Type         | Notes                          |
|-----------------|--------------|--------------------------------|
| id              | bigint PK    |                                |
| name            | varchar      |                                |
| email           | varchar      | unique                         |
| password        | varchar      | bcrypt hashed                  |
| phone           | varchar      | nullable                       |
| address         | varchar      | nullable                       |
| driver_license  | varchar      | file path, nullable            |
| avatar          | varchar      | file path, nullable            |
| is_active       | boolean      | default true                   |
| deleted_at      | timestamp    | soft delete                    |

### `roles` / `permissions` (Spatie)
Standard Spatie `laravel-permission` tables.  
Roles: `super-admin`, `admin`, `manager`, `client`

### `vehicles`
| Column          | Type         | Notes                          |
|-----------------|--------------|--------------------------------|
| brand, model    | varchar      | required                       |
| category        | varchar      | sedan/suv/truck/van/luxury/economy |
| transmission    | enum         | automatic/manual               |
| fuel_type       | enum         | petrol/diesel/electric/hybrid  |
| price_per_day   | decimal(10,2)|                                |
| is_available    | boolean      |                                |
| image           | varchar      | storage path                   |
| created_by      | FK→users     |                                |
| deleted_at      | timestamp    | soft delete                    |

### `reservations`
| Column              | Type         | Notes                       |
|---------------------|--------------|-----------------------------|
| user_id             | FK→users     |                             |
| vehicle_id          | FK→vehicles  |                             |
| start_date, end_date | date        | overlap prevention enforced |
| total_days          | int          | computed on creation        |
| total_price         | decimal      | price_per_day × total_days  |
| status              | enum         | pending/confirmed/cancelled/paid |
| payment_status      | enum         | unpaid/paid/refunded        |

### `payments`
| Column          | Type         | Notes                           |
|-----------------|--------------|---------------------------------|
| reservation_id  | FK           |                                 |
| method          | enum         | paypal/mock                     |
| status          | enum         | pending/completed/failed/refunded |
| transaction_id  | varchar      | unique                          |
| paypal_order_id | varchar      | for PayPal captures             |
| gateway_response | json        |                                 |

### `activity_logs`
Tracks every significant user action with `model_type`, `model_id`, `old_values`, `new_values`.

---

## API Documentation

### Base URL
```
http://localhost:8000/api
```

### Authentication
All protected endpoints require: `Authorization: Bearer <token>`

### Response Format
```json
{
  "success": true,
  "message": "Human readable message",
  "data": { ... }
}
```
Paginated:
```json
{
  "success": true,
  "data": [...],
  "meta": { "current_page": 1, "last_page": 5, "per_page": 15, "total": 72 }
}
```

### Auth Endpoints

| Method | Endpoint              | Auth  | Description         |
|--------|-----------------------|-------|---------------------|
| POST   | `/auth/register`      | No    | Register new client |
| POST   | `/auth/login`         | No    | Login, returns token|
| POST   | `/auth/logout`        | Yes   | Invalidate token    |
| POST   | `/auth/refresh`       | Yes   | Refresh JWT token   |
| GET    | `/auth/me`            | Yes   | Get current user    |
| PUT    | `/auth/profile`       | Yes   | Update profile      |

### Vehicle Endpoints

| Method | Endpoint            | Auth  | Roles               |
|--------|---------------------|-------|---------------------|
| GET    | `/vehicles`         | No    | Public              |
| GET    | `/vehicles/{id}`    | No    | Public              |
| POST   | `/vehicles`         | Yes   | admin/manager       |
| PUT    | `/vehicles/{id}`    | Yes   | admin/manager       |
| DELETE | `/vehicles/{id}`    | Yes   | admin/manager       |

**Query parameters for GET /vehicles:**
- `search`, `category`, `brand`, `transmission`, `fuel_type`, `min_price`, `max_price`, `is_available`
- `start_date` + `end_date` — returns only available vehicles for dates

### Reservation Endpoints

| Method | Endpoint                              | Auth | Roles          |
|--------|---------------------------------------|------|----------------|
| GET    | `/reservations`                       | Yes  | role-aware     |
| POST   | `/reservations`                       | Yes  | all            |
| GET    | `/reservations/{id}`                  | Yes  | owner or admin |
| POST   | `/reservations/check-availability`    | Yes  | all            |
| POST   | `/reservations/{id}/confirm`          | Yes  | admin/manager  |
| POST   | `/reservations/{id}/cancel`           | Yes  | owner or admin |

### Payment Endpoints

| Method | Endpoint                     | Auth | Description               |
|--------|------------------------------|------|---------------------------|
| POST   | `/payments/mock`             | Yes  | Instant mock payment      |
| POST   | `/payments/paypal/create`    | Yes  | Create PayPal order       |
| GET    | `/payments/paypal/success`   | Yes  | Capture PayPal payment    |
| GET    | `/payments/paypal/cancel`    | Yes  | Handle PayPal cancel      |

### Dashboard Endpoints

| Method | Endpoint               | Roles              |
|--------|------------------------|--------------------|
| GET    | `/dashboard/admin`     | super-admin, admin |
| GET    | `/dashboard/manager`   | manager+           |
| GET    | `/dashboard/client`    | client+            |

### Upload Endpoints

| Method | Endpoint                    | Description           |
|--------|-----------------------------|-----------------------|
| POST   | `/upload/driver-license`    | Upload driver license |
| POST   | `/upload/avatar`            | Upload profile avatar |

### User Management (Admin only)

| Method | Endpoint                          | Description    |
|--------|-----------------------------------|----------------|
| GET    | `/admin/users`                    | List users     |
| POST   | `/admin/users`                    | Create user    |
| PUT    | `/admin/users/{id}`               | Update user    |
| DELETE | `/admin/users/{id}`               | Delete user    |
| POST   | `/admin/users/{id}/toggle-status` | Activate/deactivate |

---

## Installation

### Prerequisites
- PHP 8.2+
- Composer 2+
- Node.js 18+ and npm
- MySQL 8+
- XAMPP / Laragon / Herd (or any PHP server)

### Step 1 — Clone / Download
```bash
cd C:\Users\YourName\Desktop
# project is already at CarsRental/
```

### Step 2 — Backend Setup
```bash
cd CarsRental/backend

# Install PHP dependencies (already done)
composer install

# Create the database in MySQL
# mysql -u root -p -e "CREATE DATABASE cars_rental CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Configure environment
cp .env.example .env           # Already configured for you
php artisan key:generate       # Already done
php artisan jwt:secret         # Already done

# Run migrations + seed demo data
php artisan migrate --seed

# Create storage symlink (for file uploads)
php artisan storage:link

# Start the API server
php artisan serve --port=8000
```

### Step 3 — Frontend Setup
```bash
cd CarsRental/frontend

# Install JS dependencies (already done)
npm install

# Start dev server
npm run dev
# Opens at http://localhost:5173
```

### Step 4 — Verify
- API:      http://localhost:8000/api/vehicles
- Frontend: http://localhost:5173

---

## Environment Setup

### Backend `.env` (key settings)
```env
DB_CONNECTION=mysql
DB_DATABASE=cars_rental
DB_USERNAME=root
DB_PASSWORD=

PAYPAL_CLIENT_ID=your_client_id
PAYPAL_CLIENT_SECRET=your_secret
PAYPAL_MODE=sandbox

FRONTEND_URL=http://localhost:5173
```

### Frontend `.env`
```env
VITE_API_URL=http://localhost:8000/api
VITE_STORAGE_URL=http://localhost:8000/storage
```

---

## PayPal Configuration

1. Go to [developer.paypal.com](https://developer.paypal.com)
2. Create an App in the **Sandbox** environment
3. Copy **Client ID** and **Secret**
4. Update `backend/.env`:
   ```env
   PAYPAL_CLIENT_ID=AXxx...
   PAYPAL_CLIENT_SECRET=EXxx...
   PAYPAL_MODE=sandbox
   ```
5. For production, change `PAYPAL_MODE=live` and use live credentials

---

## Default Accounts

After running `php artisan migrate --seed`:

| Role        | Email                        | Password        |
|-------------|------------------------------|-----------------|
| Super Admin | superadmin@carsrental.com    | SuperAdmin@123  |
| Admin       | admin@carsrental.com         | Admin@123456    |
| Manager     | manager@carsrental.com       | Manager@123     |
| Client      | client@carsrental.com        | Client@123456   |

---

## Security

- **JWT Authentication** — Stateless tokens via `tymon/jwt-auth`. Tokens are validated on every request via `JwtMiddleware`.
- **Role-Based Access Control** — `Spatie\Permission` with `RoleMiddleware` enforcing roles on every protected route group.
- **Password Hashing** — bcrypt via Laravel's `hashed` cast (cost factor 12).
- **Input Validation** — All user input validated via Form Requests before reaching controllers.
- **File Upload Security** — MIME-type whitelist, max size limits, UUID-based filenames, stored outside `public/` root.
- **SQL Injection Prevention** — Eloquent ORM / query builder with parameterised queries.
- **CORS** — Restricted to `FRONTEND_URL` origin only.
- **Rate Limiting** — Laravel's built-in throttle middleware (configurable in `routes/api.php`).
- **Soft Deletes** — Users and vehicles are never hard deleted, preserving referential integrity.
- **Environment Variables** — All secrets in `.env`, never committed to VCS.

---

## Scalability

- **Repository Pattern** — Swapping the data layer (e.g., from Eloquent to raw PDO or cache layer) requires only changing the repository implementation, not business logic.
- **Service Layer** — Complex workflows (booking, payment) are isolated; easy to unit-test independently.
- **Database Indexing** — Composite indexes on `(vehicle_id, start_date, end_date)` and `(user_id, status)` for fast availability and listing queries.
- **Queue-Ready** — Payment and email notifications can be moved to Laravel queues with zero service-layer changes.
- **Stateless API** — JWT tokens enable horizontal scaling (no server-side sessions).
- **Soft Deletes** — No cascading hard deletes, safer data migration.
- **Paginated Responses** — All list endpoints paginate to prevent unbounded queries.

---

## Deployment Guide

### Backend (Laravel on VPS / Shared Hosting)

```bash
# 1. Upload files, set document root to public/
# 2. Configure .env for production
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# 3. Install dependencies
composer install --no-dev --optimize-autoloader

# 4. Optimise
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Migrate
php artisan migrate --force

# 6. Storage link
php artisan storage:link
```

### Frontend (Vercel / Netlify / nginx)

```bash
cd frontend
npm run build           # Outputs to dist/
# Upload dist/ to your hosting
# Set environment variable VITE_API_URL=https://api.yourdomain.com/api
```

#### nginx SPA config
```nginx
location / {
    try_files $uri $uri/ /index.html;
}
```

---

## Running Tests

```bash
cd backend
php artisan test
# or
./vendor/bin/phpunit
```

Tests cover:
- User registration & login
- JWT authentication
- Role-based access control
- Reservation creation with price calculation
- Overlap prevention
- Vehicle CRUD with soft delete
- File upload with validation
