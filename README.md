# Tailor Shop Management System

Laravel 13.1 MVP for a tailor shop to replace paper measurement and order slips with a digital workflow.

## What Was Built

- Dashboard with totals for customers, orders, pending work, ready orders, deliveries today, overdue orders, and pending payments.
- Customer management with search, profile page, measurement history, and order history.
- Measurement management using a tailor-style slip with saved body sizes and style notes.
- Order booking with auto-generated order numbers, customer-linked saved measurements, status tracking, priority, dates, and automatic balance calculation.
- Payment recording against orders with balance protection so remaining balance never goes negative.
- Demo seed data for a few customers, measurements, and orders.

## Main Pages

- `/` dashboard
- `/customers` customer list
- `/customers/create` add customer
- `/measurements` measurement list
- `/measurements/create` add measurement
- `/orders` order list
- `/orders/create` book order
- `/orders/{order}` order details
- `/orders/{order}/payments/create` add payment

## Data Model

- `customers`
- `measurements`
- `orders`
- `payments`

Relationships:

- Customer has many measurements
- Customer has many orders
- Measurement belongs to customer
- Order belongs to customer
- Order belongs to measurement optionally
- Order has many payments
- Payment belongs to order

## Run The Project

1. Copy `.env.example` to `.env` if needed.
2. Set your database credentials in `.env`.
   MySQL example:
   - `DB_CONNECTION=mysql`
   - `DB_HOST=127.0.0.1`
   - `DB_PORT=3306`
   - `DB_DATABASE=tailor_shop`
   - `DB_USERNAME=root`
   - `DB_PASSWORD=`
3. Generate the app key:
   - `php artisan key:generate`
4. Run migrations and seed demo data:
   - `php artisan migrate --seed`
5. Start the app:
   - `php artisan serve`

## Notes

- Order numbers are generated automatically in the format `ORD-YYYYMMDD-0001`.
- Booking advance is stored on the order form, and later receipts can be added from the order details page.
- The UI uses Blade templates with Bootstrap 5 and keeps the workflow close to a tailor slip instead of a generic CRM.
