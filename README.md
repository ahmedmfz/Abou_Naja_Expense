# Expenses API (Laravel 12)

A modular Laravel 12 API for managing expenses with:
- **nwidart/laravel-modules** (Expense module)
- **Events & Listeners** (store DB notification + send email on create)
- **Centralized exceptions** via `bootstrap/app.php -> withExceptions(...)`
- **OpenAPI/Swagger** docs
- **Queued** notifications & mail

## Requirements

- PHP 8.2+
- MySQL 8+ or PostgreSQL 14+
- Composer 2+
- Redis (recommended for queues)
- Mail driver configured (SMTP, Mailtrap(local Env), or similar)

## Quick Start

```bash
# 1) Clone
git clone https://github.com/ahmedmfz/Abou_Naja_Expense.git
cd Abou_Naja_Expense

# 2) Install deps
composer install

# 3) Env
cp .env.example .env
php artisan key:generate

# 4) DB & migrations
# set DB_* in .env first
php artisan migrate --seed
php artisan module:seed Expense   # seed the Expense module too

# 5) Queue (required for email/notifications)
php artisan queue:work

# 6) Serve API
php artisan serve
```

### Swagger / OpenAPI
- Visit: `/api/documentation` (Here you can Show and Test Apis)
- Rebuild docs:
```bash
php artisan l5-swagger:generate
```

## Environment

Example `.env` essentials:
```dotenv
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=expenses
DB_USERNAME=root
DB_PASSWORD=secret

QUEUE_CONNECTION=database
CACHE_DRIVER=file
SESSION_DRIVER=file

MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="no-reply@example.com"
MAIL_FROM_NAME="Expenses API"
```


## Project Structure (high level)

```
bootstrap/app.php               # Laravel 12 app config (exceptions wired here)
Modules/Expense/
├─ App/
│  ├─ Emails/
│  │  └─ ExpenseCreatedMail.php
│  ├─ Enums/
│  │  └─ CategoryEnum.php
│  ├─ Events/
│  │  └─ ExpenseCreated.php
│  ├─ Http/
│  │  ├─ Controllers/
│  │  │  ├─ .gitkeep
│  │  │  └─ ExpenseController.php
│  │  └─ Requests/
│  │     ├─ BaseApiRequest.php
│  │     └─ Expense/
│  │        ├─ StoreExpenseRequest.php
│  │        ├─ UpdateExpenseRequest.php
│  │        └─ ViewExpenseRequest.php
│  ├─ Interfaces/
│  │  ├─ ExpenseRepositoryInterface.php
│  │  └─ ExpenseServiceInterface.php
│  ├─ Listeners/
│  │  ├─ SendExpenseEmail.php
│  │  └─ StoreExpenseNotification.php
│  ├─ Models/
│  │  └─ Expense.php
│  ├─ Notifications/
│  │  └─ ExpenseCreatedNotification.php
│  ├─ Providers/
│  ├─ Repositories/
│  │  └─ ExpenseRepository.php
│  ├─ Services/
│  │  └─ ExpenseService.php
│  └─ Transformers/
│     └─ Expense/
│        ├─ ExpenseCollection.php
│        └─ ExpenseResource.php
```

## Architecture & Decisions

- **Modules (nwidart)** to isolate the Expense domain (models, requests, service/repo, listeners).
- **Service + Repository** to keep controllers thin and business logic testable.
- **Events/Listeners**
  - `ExpenseCreated` fired after create.
  - `StoreExpenseNotification` (DB notifications table).
  - `SendExpenseEmail` (queued mailable).
- **Exception handling** in `bootstrap/app.php` using `->withExceptions()`:
  - JSON for API via `shouldRenderJsonWhen()`
  - 404 (route/model), 405, 401, 403, 422 unified responses.
- **OpenAPI** annotations on CRUD endpoints for auto docs.
- **Pagination** to limit results and avoid timeouts or oversized responses, improving overall performance.
- **An `ApiResponseHelper` is used to unify all backend API responses** (success and error) into a consistent JSON shape across the application.
- **All API FormRequests extend an abstract `BaseApiRequest`** that overrides Laravel’s default validation response shape for consistency across endpoints.

## API Overview

- `POST /api/expenses` — create  
- `GET /api/expenses` — view all (query: `page`, `per_page`, `category`, `from`, `to`)  
  - `category`: filter by category id/enum  
  - `from` / `to`: ISO date (YYYY-MM-DD) range filter on `expense_date`
- `PUT /api/expenses/{id}` — update  
- `DELETE /api/expenses/{id}` — delete  

On successful create:
- Event `ExpenseCreated` dispatched  
- Listener 1: stores DB notification (queued)
- Listener 2: sends email (queued)

## Assumptions
- You have a mail catcher or SMTP for local (e.g., Mailtrap on port 587).
- Notifications use the **database** channel only (no broadcast).
- IDs are **UUIDs** for expenses.
- `category` is an **enum backed by int** in the DB and transformed in resources.
- **Notifications and emails are processed via the queue** (both listeners implement `ShouldQueue`, and a queue worker is running).

## Time Spent : 10 Hours
- Setup & scaffolding: **10 mins**
- Module wiring & CRUD: **2 – 4 hours**
- Events/Listeners/Notifications/Mail: **2 hours**
- Exception strategy & tests: **2 hours**
- Swagger docs: **2 hours**
