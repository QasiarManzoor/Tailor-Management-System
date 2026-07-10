# Tailor Shop Management System

A Laravel 13 tailor shop management application for digitizing customer records, measurements, order booking, payments, printable slips, and multi-shop administration.

This project replaces paper-based tailor workflows with a role-based, shop-aware system built with Blade, Bootstrap 5, and Laravel.

## Highlights

- Multi-shop architecture using `shop_id` tenant isolation
- Role-based access with exactly two roles:
  - `super_admin`
  - `owner`
- Customer, measurement, order, and payment management
- Printable receipt and invoice/delivery slip
- Shop-specific slip header editing
- Super admin user management, shop management, system settings, and activity logs
- Light/dark theme toggle with persistence
- Production hardening:
  - login throttling
  - owner cross-shop isolation
  - shop-scoped business queries
  - focused feature tests for production-critical flows

## Tech Stack

- PHP `^8.3`
- Laravel `^13.0`
- Blade templates
- Bootstrap `5.3`
- MySQL recommended for production
- Laravel Tinker for admin/developer maintenance

## Deploying on Vercel

This repository includes Vercel configuration for Laravel:

- `vercel.json` routes static files from `public/` and all app requests through `api/index.php`.
- `api/index.php` boots the normal Laravel front controller.
- `.vercelignore` keeps local and development-only files out of deployments.

Set these environment variables in Vercel before deploying:

```text
APP_NAME="Tailor Management System"
APP_ENV=production
APP_KEY=base64:your-generated-key
APP_DEBUG=false
APP_URL=https://your-vercel-domain.vercel.app

DB_CONNECTION=mysql
DB_HOST=your-external-db-host
DB_PORT=3306
DB_DATABASE=your-db-name
DB_USERNAME=your-db-user
DB_PASSWORD=your-db-password

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=sync
LOG_CHANNEL=stderr
```

Vercel does not provide persistent local disk storage. For uploaded order attachment images, use an external disk such as S3-compatible storage:

```text
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=...
AWS_SECRET_ACCESS_KEY=...
AWS_DEFAULT_REGION=...
AWS_BUCKET=...
AWS_URL=...
AWS_ENDPOINT=...
AWS_USE_PATH_STYLE_ENDPOINT=false
```

Generate an app key locally with:

```bash
php artisan key:generate --show
```

Run migrations against the production database after environment variables are configured:

```bash
php artisan migrate --force
```

## Core Modules

### Owner Business Modules

- Dashboard
- Customers
- Measurements
- Orders
- Payments
- Printable receipt
- Printable invoice / delivery slip
- Shop slip-header settings

### Super Admin Modules

- Super Admin Dashboard
- User Management
- Shop Management
- System Settings
- Activity Logs
- Manage Shop Data context switching

## Roles

The application uses exactly two roles.

### `super_admin`

Can:

- access global system data
- manage all shops
- manage all users
- manage system settings
- review activity logs
- enter a selected shop context and manage that shop’s business data

### `owner`

Can:

- access only their own shop’s data
- manage customers, measurements, orders, and payments for their shop
- print receipts/invoices for their shop
- edit their own shop slip header

## Multi-Shop Architecture

This project uses a shop-based tenant model.

- each owner belongs to one shop
- all business data belongs to a shop
- owners are restricted to their own shop
- super admins can see everything globally or work inside a selected shop context

Tables scoped by `shop_id`:

- `users`
- `customers`
- `measurements`
- `orders`
- `payments`
- `activity_logs`

Primary shop model:

- `shops`

## Main Features

### 1. Authentication

- login page at `/`
- email/password login
- logout
- rate-limited login attempts

### 2. Customer Management

- create, update, delete customers
- customer number generation like `202600001`
- search by number, name, and phone
- customer profile with linked measurements and orders

### 3. Measurement Management

- bilingual measurement form
- saved measurement profiles per customer
- print view
- measurement-to-order linking

### 4. Order Management

- auto-generated order numbers like `ORD-2026-0001`
- customer + saved measurement selection
- total, advance, and balance handling
- order status and priority tracking
- trial/delivery workflow
- receipt and invoice print views

### 5. Payment Management

- add payments against an order
- prevents overpayment
- prevents past payment dates
- updates order advance and balance

### 6. Printable Slips

Existing print layouts are reused, not duplicated.

Supported print outputs:

- printable receipt
- printable invoice / delivery slip

Slip behavior includes:

- shop-specific header values
- compact print layout
- footer banner support
- print-safe light output

### 7. Shop Header Editing

Owners can update the shop information shown on slips:

- shop name
- tagline
- primary phone
- secondary phone
- address line 1
- address line 2
- logo path

### 8. Super Admin User Management

Super admin can:

- create users
- edit users
- assign/create shops during owner creation
- activate/deactivate users
- delete users safely

### 9. Super Admin Shop Management

Super admin can:

- browse all shops
- archive/activate a shop
- enter a shop management context
- delete a shop only when no users are assigned to it

Current shop delete rule:

- if a shop still has assigned users, it cannot be deleted
- if there are no users left, deleting the shop removes related business data

## Business Rules

### Money

- money fields are integer-only in user-facing validation
- negative values are blocked
- payment amount must be at least `1`
- overpayment is blocked

### Dates

- payment date cannot be in the past
- booking date cannot be in the past
- trial/delivery dates cannot be before booking date

### Tenant Safety

- owner cannot access another shop’s records by URL
- owner cannot print another shop’s slips
- owner-created business records are forced into the owner’s own shop

## Important Routes

### Public / Auth

- `GET /` login page
- `GET /login`
- `POST /login`
- `POST /logout`

### Owner + Super Admin Business Routes

- `GET /dashboard`
- `GET /shop-header`
- `PUT /shop-header`
- resource routes for:
  - `/customers`
  - `/measurements`
  - `/orders`
- payment routes:
  - `GET /orders/{order}/payments/create`
  - `POST /orders/{order}/payments`
- print routes:
  - `GET /measurements/{measurement}/print`
  - `GET /orders/{order}/receipt`
  - `GET /orders/{order}/invoice`

### Super Admin Routes

- `GET /super-admin/dashboard`
- `GET /super-admin/users`
- `GET /super-admin/shops`
- `GET /super-admin/settings`
- `GET /super-admin/activity-logs`

## Database Structure

Main business tables:

- `shops`
- `users`
- `customers`
- `measurements`
- `orders`
- `payments`
- `system_settings`
- `activity_logs`

Useful indexes already added for tenant-aware filtering, including combinations such as:

- `users(shop_id, role)`
- `customers(shop_id, name)`
- `customers(shop_id, phone)`
- `measurements(shop_id, customer_id)`
- `orders(shop_id, customer_id)`
- `orders(shop_id, status, delivery_date)`
- `payments(shop_id, order_id)`
- `activity_logs(shop_id, action)`

## Local Setup

### 1. Install dependencies

```bash
composer install
```

### 2. Create environment file

```bash
copy .env.example .env
```

### 3. Configure database

Recommended local MySQL example:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rashid_tailor_shop
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate application key

```bash
php artisan key:generate
```

### 5. Run migrations

```bash
php artisan migrate
```

### 6. Seed starter data

```bash
php artisan db:seed
```

### 7. Start the application

```bash
php artisan serve
```

## Seeder Data

Default seeders:

- `TailorShopSeeder`
- `SuperAdminSeeder`

Seeded users:

### Super Admin

- email: `admin@shaqtechnologies.com`
- password: `password`

### Owner

- email: `owner@shop.com`
- password: `password`

Change these credentials immediately in any real environment.

## Testing

Run tests:

```bash
php artisan test
```

Focused production-readiness coverage exists in:

- [tests/Feature/ProductionReadinessTest.php](./tests/Feature/ProductionReadinessTest.php)

Covered scenarios include:

- login throttling
- super admin route protection
- owner cross-shop access blocking
- print route isolation
- owner shop-forced customer creation
- payment validation against overpayment and past dates

## Production Deployment Notes

Before production, make sure:

- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_KEY` is set
- database credentials are correct
- migrations are run
- seeders are run only if needed

Recommended production command:

```bash
php artisan migrate --force
```

Optional admin seeding:

```bash
php artisan db:seed --class=SuperAdminSeeder --force
```

## Laravel Cloud / Railway Notes

If deploying to Laravel Cloud, Railway, or another managed host:

- attach a real database
- do not rely on default SQLite unless intentionally configured
- ensure cache/session drivers are appropriate for the environment
- run migrations during deploy or immediately after deploy

## Security Notes

Implemented:

- role middleware
- owner shop isolation
- login throttling
- active/inactive user enforcement
- request validation for business forms

Recommended additional operational practices:

- change seeded passwords immediately
- use HTTPS in production
- use strong admin passwords
- back up the database regularly
- restrict who can access the super admin account

## Logo Handling

The app uses PNG logo assets.

Safer current setup:

- a web-safe public logo is used by default for general UI display
- print footer banner remains separate
- high-value master design assets should not be placed in `public/`

## Project Status

Current status:

- suitable for a careful production launch
- multi-shop role behavior is in place
- core validations and tenant isolation are covered

Still recommended over time:

- expand automated tests further
- add backup/recovery procedures
- add more deployment automation
- add audit/reporting improvements as needed

## License

This project is currently maintained as a custom business application. Confirm your intended license before public redistribution.
