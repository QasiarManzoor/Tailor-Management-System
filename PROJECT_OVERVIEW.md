# Tailor Shop Management System - Project Overview

## Purpose

This is a Laravel tailor shop management system for replacing paper-based shop workflows with a shop-aware digital workspace. It manages customers, measurements, orders, payments, workers, production tasks, cashbook entries, printable slips, and super-admin administration.

The current default shop identity is generic and editable:

- Shop name: `XYZ Tailor Shop`
- Shop code: `default-tailor-shop`
- Active database: `tailor-system-double-road`

## Local Environment

### Runtime

- PHP requirement: `^8.3`
- Current working PHP used for Artisan: `C:\Users\Qaisar\AppData\Local\Programs\PHP\8.5.4\nts\x64\php.exe`
- Framework: Laravel `^13.0`
- Frontend tooling: Vite, Bootstrap CDN in Blade layouts, Tailwind dependency present
- Database: MariaDB/MySQL through XAMPP

### XAMPP Services

The intended active XAMPP installation is:

```text
C:\Program Files\Xampp
```

The Windows services were switched to use Program Files XAMPP:

```text
Apache2.4 -> C:\Program Files\Xampp\apache\bin\httpd.exe
mysql     -> C:\Program Files\Xampp\mysql\bin\mysqld.exe
```

The active MySQL data directory should report:

```sql
SELECT @@datadir;
-- C:\Program Files\Xampp\mysql\data\
```

Laravel `.env` database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tailor-system-double-road
DB_USERNAME=root
DB_PASSWORD=
```

## Default Logins

```text
Super Admin
Email: admin@shaqtechnologies.com
Password: password

Owner
Email: owner@shop.com
Password: password
```

Change these before production use.

## Main Concepts

### Roles

The app currently has these roles:

- `super_admin`
- `owner`
- `cashier`
- `receptionist`
- `cutter`
- `stitcher`

Super admins manage global data and can enter a selected shop context. Owners operate inside their own shop and can edit shop profile details. Staff roles can access business workflow pages for their assigned shop but cannot access super-admin screens.

### Tenant Isolation

Shop isolation is centralized in:

- `app/Models/Concerns/BelongsToShop.php`
- `app/Support/CurrentShop.php`

Models using `BelongsToShop` automatically scope business queries to the current owner shop or the super-admin selected shop context.

## Main Modules

### Authentication

Files:

- `app/Http/Controllers/AuthController.php`
- `resources/views/auth/login.blade.php`

Features:

- Login at `/` and `/login`
- Logout
- Login throttling
- Inactive user blocking
- Super admins redirect to super admin dashboard
- Owners redirect to business dashboard

### Dashboard

Files:

- `app/Http/Controllers/DashboardController.php`
- `app/Services/BusinessDashboardService.php`
- `resources/views/dashboard/index.blade.php`

Shows:

- Total customers
- Total orders
- Pending orders
- Ready orders
- Deliveries today
- Overdue orders
- Pending payments
- Latest orders
- Urgent orders

### Customers

Files:

- `app/Models/Customer.php`
- `app/Http/Controllers/CustomerController.php`
- `app/Http/Requests/CustomerRequest.php`
- `resources/views/customers/*`

Features:

- Customer CRUD
- Auto-generated customer numbers like `202600001`
- Phone/address/gender/notes
- Customer detail page with measurements and orders

### Measurements

Files:

- `app/Models/Measurement.php`
- `app/Http/Controllers/MeasurementController.php`
- `app/Http/Requests/MeasurementRequest.php`
- `resources/views/measurements/*`

Features:

- Measurement CRUD
- Bilingual labels
- Copy measurement
- Print measurement
- Link measurements to customers and orders

### Orders

Files:

- `app/Models/Order.php`
- `app/Http/Controllers/OrderController.php`
- `app/Http/Controllers/OrderKanbanController.php`
- `app/Http/Requests/OrderRequest.php`
- `resources/views/orders/*`

Features:

- Auto-generated order numbers like `ORD-2026-0001`
- Customer and measurement selection
- Worker assignment
- Work category
- Trial status
- Status history
- Production checklist
- Photo attachments
- WhatsApp receipt/reminder URLs
- WhatsApp template actions for receipt, trial, ready, delivery, payment, and thank-you messages
- Receipt and invoice print views
- Kanban board for active production workflow

Order statuses:

- `booked`
- `cutting`
- `stitching`
- `trial`
- `ready`
- `delivered`
- `cancelled`

### Payments

Files:

- `app/Models/Payment.php`
- `app/Http/Controllers/PaymentController.php`
- `app/Http/Requests/PaymentRequest.php`
- `resources/views/payments/create.blade.php`

Features:

- Payment recording against an order
- Overpayment blocking
- Past payment date blocking
- Order advance and balance update

Payment methods:

- `cash`
- `bank_transfer`
- `easypaisa`
- `jazzcash`
- `card`

### Workers

Files:

- `app/Models/Worker.php`
- `app/Models/WorkerPayment.php`
- `app/Http/Controllers/WorkerController.php`
- `app/Http/Controllers/WorkerPaymentController.php`
- `resources/views/workers/index.blade.php`

Features:

- Worker list
- Create/update worker
- Active/inactive worker flag
- Assign active workers to orders
- Record worker wage payments
- Optionally link wage payments to assigned orders
- Add worker payments to cashbook expenses under `worker_payment`

### Cashbook

Files:

- `app/Models/CashbookEntry.php`
- `app/Http/Controllers/CashbookController.php`
- `resources/views/cashbook/index.blade.php`

Features:

- Daily cashbook view
- Payment income from order payments
- Manual income entries
- Manual expense entries
- Income, expense, and net summary

### Inventory

Files:

- `app/Models/InventoryItem.php`
- `app/Models/InventoryMovement.php`
- `app/Http/Controllers/InventoryController.php`
- `resources/views/inventory/index.blade.php`

Features:

- Inventory item catalog
- Fabric/accessory categories
- Stock quantity and reorder level
- Low-stock flags
- Stock movements for in, out, and adjustment

### Reports

Files:

- `app/Http/Controllers/ReportController.php`
- `resources/views/reports/index.blade.php`

Features:

- Today, week, month, and custom date-range filters
- Gross income, expenses, net income, and booked order value
- Payment method breakdown
- Expense category breakdown
- Order status breakdown
- Top customers for the selected period
- Recent payments
- Current pending balance and overdue order risk

### Delivery Calendar

Files:

- `app/Http/Controllers/DeliveryCalendarController.php`
- `resources/views/calendar/index.blade.php`

Features:

- Monthly calendar grid
- Delivery dates
- Trial dates
- Urgent and overdue visual markers
- Quick links to order detail pages

### Customer Ledger

Files:

- `app/Http/Controllers/CustomerLedgerController.php`
- `resources/views/customers/ledger.blade.php`

Features:

- Customer statement page
- Total orders, total amount, paid amount, and balance
- Order-by-order balance table
- Payment history across all customer orders
- Print action

### Shop Profile

Files:

- `app/Models/Shop.php`
- `app/Http/Controllers/ShopHeaderController.php`
- `resources/views/shop-header/edit.blade.php`

Features:

- Editable shop title/name
- Editable tagline
- Editable phone numbers
- Editable address lines
- Editable logo path
- Editable receipt footer fallback details

Printed documents keep shop owner details in the header. The footer uses the system company footer/banner.

### Super Admin

Files:

- `app/Http/Controllers/SuperAdmin/*`
- `resources/views/superadmin/*`

Features:

- Super admin dashboard
- User management
- Shop management
- System settings
- Activity logs
- Manage selected shop data through session context
- Backup download and restore

### Backup And Restore

Files:

- `app/Http/Controllers/SuperAdmin/BackupController.php`
- `resources/views/superadmin/backups/index.blade.php`

Features:

- Super-admin-only JSON database backup download
- JSON backup restore into the current migrated schema
- Included table listing

### Search

Files:

- `app/Http/Controllers/GlobalSearchController.php`
- `resources/views/search/index.blade.php`

Global search supports business data lookup from the app header.

## Important Routes

Public/auth:

- `GET /`
- `GET /login`
- `POST /login`
- `POST /logout`

Business routes:

- `GET /dashboard`
- `GET /search`
- `GET /reports`
- `GET /calendar`
- `GET /inventory`
- `POST /inventory`
- `POST /inventory/{item}/movements`
- `GET /cashbook`
- `POST /cashbook`
- `GET /shop-header`
- `PUT /shop-header`
- `resources /customers`
- `resources /measurements`
- `resources /orders`
- `GET /orders/kanban`
- `PATCH /orders/{order}/status`
- `resources /workers` limited to index, store, update
- `POST /workers/{worker}/payments`
- `GET /customers/{customer}/ledger`

Order operations:

- `GET /orders/{order}/receipt`
- `GET /orders/{order}/invoice`
- `PATCH /orders/{order}/checklist`
- `POST /orders/{order}/attachments`
- `DELETE /orders/{order}/attachments/{attachment}`
- `GET /orders/{order}/payments/create`
- `POST /orders/{order}/payments`

Super admin:

- `GET /super-admin/dashboard`
- `GET /super-admin/shops`
- `POST /super-admin/shops/{shop}/manage`
- `DELETE /super-admin/shops/manage`
- `resources /super-admin/users`
- `GET /super-admin/settings`
- `PUT /super-admin/settings`
- `GET /super-admin/activity-logs`
- `GET /super-admin/backups`
- `GET /super-admin/backups/download`
- `POST /super-admin/backups/restore`

## Database Tables

Core tables:

- `shops`
- `users`
- `customers`
- `measurements`
- `orders`
- `payments`
- `workers`
- `worker_payments`
- `inventory_items`
- `inventory_movements`
- `cashbook_entries`
- `order_checklist_items`
- `order_attachments`
- `order_status_histories`
- `system_settings`
- `activity_logs`

Laravel infrastructure:

- `cache`
- `cache_locks`
- `jobs`
- `job_batches`
- `failed_jobs`
- `sessions`
- `password_reset_tokens`
- `migrations`

## Printing

Shared print layout:

- `resources/views/layouts/print.blade.php`

Print views:

- `resources/views/orders/receipt.blade.php`
- `resources/views/orders/invoice.blade.php`
- `resources/views/measurements/print.blade.php`

Current behavior:

- Header shows shop owner details from the related shop.
- Footer shows the existing company footer banner if available.
- If the banner is missing, footer falls back to system settings company text.

## Seeders

Files:

- `database/seeders/DatabaseSeeder.php`
- `database/seeders/TailorShopSeeder.php`
- `database/seeders/SuperAdminSeeder.php`

Seeded data includes:

- Default editable shop
- Sample customers
- Sample measurements
- Sample orders
- Super admin user
- Owner user

## Testing

Feature tests:

- `tests/Feature/ProductionReadinessTest.php`

Covered behavior:

- Login throttling
- Super admin route protection
- Owner cross-shop access blocking
- Print route isolation
- Owner shop-forced customer creation
- Payment validation

## Recommended Feature Roadmap

The next implementation sequence:

1. Reports
2. Worker wages
3. Delivery calendar
4. Customer ledger
5. WhatsApp templates

This order prioritizes daily owner value: financial visibility, production accountability, delivery control, customer balance clarity, and communication speed.

## Operational Commands

Use PHP 8.5.4 explicitly:

```powershell
& 'C:\Users\Qaisar\AppData\Local\Programs\PHP\8.5.4\nts\x64\php.exe' artisan migrate
& 'C:\Users\Qaisar\AppData\Local\Programs\PHP\8.5.4\nts\x64\php.exe' artisan db:seed
& 'C:\Users\Qaisar\AppData\Local\Programs\PHP\8.5.4\nts\x64\php.exe' artisan cache:clear
& 'C:\Users\Qaisar\AppData\Local\Programs\PHP\8.5.4\nts\x64\php.exe' artisan view:clear
```

Check MySQL data directory:

```powershell
& 'C:\Program Files\Xampp\mysql\bin\mysql.exe' -uroot -e "SELECT @@datadir;"
```

Expected:

```text
C:\Program Files\Xampp\mysql\data\
```
