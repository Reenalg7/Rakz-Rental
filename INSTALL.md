# Rakz Rental — Installation Guide (XAMPP + MySQL)

This is the complete, standalone project. It does **not** depend on Emergent, SQLite, or any cloud service.

---

## Prerequisites
- **XAMPP** (or any stack providing PHP 8.1+ and MySQL/MariaDB)
- **Composer** (only required if the `vendor/` folder is missing — it is included in this ZIP so you can skip this)
- A text editor / IDE (VS Code recommended)

> No Node.js / npm is required. Styling uses the Tailwind CDN.

---

## Quick Start (5 steps)

### 1. Extract
Extract `Rakz-Rental-Final-Complete.zip` to your XAMPP `htdocs` folder, e.g.
```
C:\xampp\htdocs\rakz-rental\
```

### 2. Start XAMPP
Open the XAMPP Control Panel and start **Apache** and **MySQL**.

### 3. Create the database and import the backup
Open phpMyAdmin → http://localhost/phpmyadmin

**Option A — via phpMyAdmin UI**
1. Click **New** → database name: `rakz_rental` → Collation: `utf8mb4_unicode_ci` → **Create**.
2. Click the `rakz_rental` database → **Import** tab → choose file:
   `database/backup/rakz_rental.sql` → **Go**.

**Option B — via command line (Windows)**
```bat
C:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE rakz_rental CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
C:\xampp\mysql\bin\mysql.exe -u root rakz_rental < database\backup\rakz_rental.sql
```

### 4. (Only if `vendor/` is missing) install PHP dependencies
```bat
cd C:\xampp\htdocs\rakz-rental
composer install
```

### 5. Start Laravel's dev server
```bat
cd C:\xampp\htdocs\rakz-rental
php artisan serve
```
Open **http://localhost:8000**.

---

## Demo credentials
| Role | Email | Password |
|---|---|---|
| Admin | `admin@rakzrental.com` | `admin123` |
| Customer | `customer@rakzrental.com` | `customer123` |

---

## The included `.env`
Already configured for XAMPP defaults:

```env
APP_NAME="Rakz Rental"
APP_ENV=local
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rakz_rental
DB_USERNAME=root
DB_PASSWORD=
```

If your MySQL root user has a password, set `DB_PASSWORD=yourpassword` and save.

> **Do NOT commit `.env` to GitHub.** It is already listed in `.gitignore`. Use `.env.example` as the template for teammates.

---

## Verifying the install
1. Visit `http://localhost:8000` → landing page shows 8 vehicles grouped into 5 categories.
2. Login as customer → `/vehicles` lists the fleet by **registration number** (LR3180, LR3181 …).
3. Book `LR3180` for any 3-day window → booking appears in **My Bookings** with an **invoice PDF**.
4. Try booking the same vehicle on the same dates again → you get *"Sorry, this vehicle is already booked…"* (overlap prevention working).
5. Login as admin → `/admin` dashboard → **Replace Vehicle** on the booking → swap LR3180 → LR3181 → admin **Reports**, **CSV Exports**, **Audit Logs** all populate.

---

## Database structure (quick reference)

**Vehicles table — primary key is the registration number (string), as required by the lecturer.**

```
vehicles (registration_no VARCHAR(20) PK, name, make, model, year, category_id FK, daily_rate, …)
bookings (id PK, user_id FK, vehicle_registration_no FK → vehicles.registration_no,
          original_vehicle_registration_no FK → vehicles.registration_no,
          pickup_date, return_date, total_days, daily_rate_snapshot, total_amount, status, …)
vehicle_replacements (id PK, booking_id FK, original_registration_no FK, replacement_registration_no FK, reason, replaced_by_user_id FK)
favorites (id PK, user_id FK, vehicle_registration_no FK) — UNIQUE(user_id, vehicle_registration_no)
billings (id PK, booking_id FK, amount, status, …)
audit_logs (id PK, user_id FK, action, target_type, target_id, ip_address, user_agent, …)
users (id PK, name, email UNIQUE, password, role ENUM('admin','customer'))
categories (id PK, name UNIQUE)
notifications (id PK, user_id FK, title, message, type, read_at)
```

All vehicle references across the schema use `registration_no` as a true foreign key (InnoDB `FOREIGN KEY` constraints) — **not** a numeric `vehicle_id`. Verified via `SHOW CREATE TABLE`.

---

## Scheduled tasks (optional — only matters for daily auto-completion)
The project ships two scheduled commands:
- `php artisan bookings:mark-completed` (daily 00:30) — marks bookings whose `return_date` has passed as `completed`.
- `php artisan bookings:send-return-reminders` (daily 08:00) — emails customers one day before return.

On Windows, create a Task Scheduler entry that runs `php C:\xampp\htdocs\rakz-rental\artisan schedule:run` every minute, or run the scheduler worker:
```bat
php artisan schedule:work
```

---

## Running the regression test suite
With Laravel serving on `http://localhost:8000`, update the `BASE` variable at the top of `tests_e2e.sh` to `http://localhost:8000` and run it from Git Bash / WSL:
```bash
bash tests_e2e.sh
```
Expected: `PASSED: 43  FAILED: 0`.

---

## Troubleshooting

| Problem | Fix |
|---|---|
| `SQLSTATE[HY000] [1045] Access denied for user 'root'@'localhost'` | Your MySQL root has a password → set `DB_PASSWORD=` in `.env` |
| `Class 'DOMDocument' not found` | Enable `php_xml` and `php_mbstring` in XAMPP's `php.ini` |
| `could not find driver` | Enable `extension=pdo_mysql` in `php.ini` |
| Blank page / 500 error | Check `storage/logs/laravel.log`. Make sure `storage/` and `bootstrap/cache/` are writable |
| APP_KEY empty | Run `php artisan key:generate` |

---

## Project structure (quick map)
```
rakz-rental/
├── app/
│   ├── Console/Commands/         # Scheduled task commands
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/            # 9 admin controllers (vehicles, bookings, reports, replacements, csv, audit, customers, categories, dashboard)
│   │   │   ├── Auth/             # Login/Register/Logout
│   │   │   └── Customer/         # Vehicle browse, booking, favorites, profile
│   │   └── Middleware/           # TrustProxies, EnsureUserIsAdmin
│   ├── Mail/                     # Booking confirmation / replacement / return reminder mailables
│   ├── Models/                   # Vehicle (string PK), Booking, VehicleReplacement, Billing, Favorite, AuditLog, Notification, User, Category
│   └── Support/                  # AuditLogger helper
├── database/
│   ├── migrations/               # 9 migrations
│   ├── seeders/                  # Users, Categories, Vehicles
│   └── backup/
│       └── rakz_rental.sql       # ← full MySQL dump, schema + data
├── docs/
│   └── demo.md                   # Viva presentation script
├── public/                       # Laravel public root (index.php)
├── resources/views/              # Blade templates
│   ├── admin/                    # Admin portal
│   ├── auth/                     # Login / Register
│   ├── customer/                 # Customer portal
│   ├── emails/                   # Email templates
│   ├── invoices/                 # PDF invoice templates
│   └── layouts/                  # Base layouts, nav
├── routes/web.php                # All application routes
├── storage/                      # Logs, cache, PDFs (make writable)
├── vendor/                       # Composer dependencies (included)
├── .env                          # Local XAMPP config (DO NOT COMMIT)
├── .env.example                  # Teammate-safe template
├── composer.json / composer.lock # PHP dependencies
├── README.md                     # Project overview
├── INSTALL.md                    # ← this file
└── tests_e2e.sh                  # 43-assertion regression suite
```

---

## Lecturer requirements — proof of implementation

| Requirement | Where to see it |
|---|---|
| Vehicle **registration number** is the real primary key / FK | `database/migrations/*_create_vehicles_table.php` → `$table->string('registration_no', 20)->primary()`. Confirm with: `SHOW CREATE TABLE bookings;` — the FK is `vehicle_registration_no REFERENCES vehicles(registration_no)`. |
| **Overlap prevention** on bookings | `app/Models/Vehicle.php@isAvailableBetween` + `app/Http/Controllers/Customer/BookingController@store` (row lock inside DB transaction). |
| **Vehicle replacement** | `app/Http/Controllers/Admin/ReplacementController` → `/admin/bookings/{id}/replace`. |
| **Improved search** | `app/Http/Controllers/Customer/VehicleController@index` — keyword covers name/make/model/registration + category/transmission/price/date filters. |
| **Audit log** | `app/Support/AuditLogger` + `app/Http/Controllers/Admin/AuditLogController`. |

Enjoy the demo — good luck with the viva.
