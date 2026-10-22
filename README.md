# Rakz Rental — Car Rental Booking System

A university-project car rental booking system built with **Laravel 10, PHP 8.1+, and MySQL**.
---

## 1. Highlights (addresses the lecturer's feedback)

| Feedback | How it is addressed |
|---|---|
| Use **registration number** instead of a numeric vehicle id | `vehicles.registration_no` is a **string primary key** (e.g. `LR3180`). All FKs (`bookings.vehicle_registration_no`, `vehicle_replacements.original_registration_no`, `vehicle_replacements.replacement_registration_no`) reference it. See `database/migrations/*_create_vehicles_table.php`. |
| **Search** improvement | Keyword search covers vehicle name, make, model AND registration number. Filters: category, transmission, price range, and date-aware availability. See `app/Http/Controllers/Customer/VehicleController.php`. |
| **Availability** check | Overlap check runs in the backend via SQL (`pickup_date <= return_date AND return_date >= pickup_date`) + a **row lock** inside a DB transaction to prevent race conditions. See `BookingController@store`. |
| **Booking confirmation** | Dedicated step-by-step flow: Pick vehicle → Pick dates → Live availability + price check (AJAX) → Confirm → Store → Show confirmation page. |
| **Vehicle replacement** | Admin can swap a vehicle on a booking. The replacement is checked against the booking's dates; original vehicle is preserved in `bookings.original_vehicle_registration_no` and audited in `vehicle_replacements`. Customer gets a notification. |

---

## 2. Setup instructions

### Requirements
- PHP 8.1 or higher
- Composer
- MySQL 5.7 / 8.0 (or MariaDB)
- Node not required (we use the Tailwind CDN to keep the demo simple)

### Install
```bash
cd rakz-rental

# 1. Install PHP dependencies
composer install

# 2. Prepare env
cp .env.example .env
php artisan key:generate

# 3. Create MySQL database (name must match DB_DATABASE in .env)
mysql -u root -p -e "CREATE DATABASE rakz_rental CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 4. Run migrations + seed demo data
php artisan migrate --seed

# 5. Serve
php artisan serve
```
App runs at **http://localhost:8000**.

### Demo credentials
| Role | Email | Password |
|---|---|---|
| Admin | `admin@rakzrental.com` | `admin123` |
| Customer | `customer@rakzrental.com` | `customer123` |

---

## 3. Main modules

```
app/
  Models/
    User.php                  Customer or Admin (role column)
    Vehicle.php               PRIMARY KEY = registration_no (string)
    Category.php              Sedan / SUV / Luxury / Van / Compact
    Booking.php               Customer + Vehicle (by reg_no) + dates + status
    VehicleReplacement.php    Audit of every admin vehicle swap
    Billing.php               Internal billing (no gateway)
    Notification.php          In-app notifications (replacement etc.)

  Http/Controllers/
    Auth/{Login,Register}Controller.php
    HomeController.php
    Customer/VehicleController.php     Public browse + search + date-aware filter
    Customer/BookingController.php     Create / check-availability / store / cancel
    Customer/ProfileController.php
    Customer/NotificationController.php
    Admin/DashboardController.php
    Admin/VehicleController.php        CRUD + retire/restore (reg_no is PK)
    Admin/CategoryController.php
    Admin/BookingController.php        Status management + mark paid
    Admin/ReplacementController.php    Vehicle replacement (with availability recheck)
    Admin/CustomerController.php
```

---

## 4. Database relationships

```
users (id)
  └── 1:N bookings (user_id)

categories (id)
  └── 1:N vehicles (category_id)

vehicles (registration_no)   ← PK is a STRING plate number
  └── 1:N bookings           (vehicle_registration_no FK → vehicles.registration_no)
  └── 1:N vehicle_replacements (original_registration_no, replacement_registration_no)

bookings (id)
  ├── 1:1 billings (booking_id)
  └── 1:N vehicle_replacements (booking_id)

users (id)
  └── 1:N notifications (user_id)
```

Key points:
- `vehicles.registration_no` is **declared as `primary()` on a string column**, not an auto-increment integer. Laravel's model declares `public $incrementing = false;` and `protected $keyType = 'string';`.
- Route-model binding uses `{vehicle:registration_no}` so URLs look like `/vehicles/LR3180`.

---

## 5. How vehicle availability works

Backend overlap rule (two date ranges overlap iff):

```
existing.pickup_date  <= requested.return_date
AND existing.return_date >= requested.pickup_date
```

Where it runs:
1. **Browse screen (date-aware search)** — `Customer\VehicleController@index` uses `whereDoesntHave('bookings', …)` so unavailable vehicles disappear from the list.
2. **AJAX check** — `Customer\BookingController@checkAvailability` returns JSON `{available, total_days, total_amount}` as the user picks dates.
3. **Final submit** — inside `DB::transaction()` we execute `lockForUpdate()` on overlapping bookings for that vehicle. If the lock reveals an overlap, we throw a `ValidationException`. This stops two users submitting simultaneously.

Statuses considered as "blocking": `pending` and `confirmed`. `cancelled` and `completed` bookings are **ignored** for overlap.

---

## 6. How vehicle replacement works

Admin flow:
1. Open a booking → "Replace vehicle".
2. The system shows **only vehicles that are available for the booking's exact date range** (same overlap rule, inverted).
3. Admin picks one, enters a reason, submits.
4. Inside a transaction + row lock:
   - A `vehicle_replacements` row is inserted (from → to, who, why, when).
   - `bookings.vehicle_registration_no` is updated to the new plate.
   - `bookings.original_vehicle_registration_no` is set (only on the **first** replacement, so the true original is never lost even after multiple replacements).
   - A `notifications` row is created for the customer.
5. Customer sees a highlighted "Replaced" badge on their booking detail page.

---

## 7. Security

- Passwords hashed with bcrypt via Laravel's hashing facade.
- CSRF tokens on every POST/PUT/DELETE form.
- Role-based middleware: `admin` and `customer`.
- Authorization checks ensure a customer can only view/cancel their own bookings (`abort_if($booking->user_id !== Auth::id(), 403)`).
- Input validation on every controller (`$request->validate`).

---

## 8. Demo / testing checklist

### Customer flow
- [ ] Register a new customer (`/register`)
- [ ] Log in as the demo customer
- [ ] Browse vehicles, search by `LR3180`
- [ ] Filter by category + enter future pickup/return dates → unavailable vehicles disappear
- [ ] Click a vehicle → see detail with the registration number badge
- [ ] Click "Book this vehicle" → pick dates → "Check availability & price" → confirm
- [ ] Open "My Bookings" → see booking and status
- [ ] (Second booking) try the same vehicle for overlapping dates → system blocks it
- [ ] Cancel a pending booking

### Admin flow
- [ ] Log in as admin → dashboard shows live counts & revenue
- [ ] Add a new vehicle (type the plate as the PK, e.g. `LR9999`)
- [ ] Edit an existing vehicle (plate field is read-only – it's the PK)
- [ ] "Retire" a vehicle → status changes to `retired`; past bookings still visible
- [ ] View bookings → change status (pending → confirmed) → customer gets a notification
- [ ] Mark a bill as paid
- [ ] Open a booking → "Replace vehicle" → pick a candidate → submit
- [ ] Open the Replacements page → audit row shows `LR3180 → LR3181`
- [ ] Log in as the affected customer → their booking shows "Replaced" badge + notification

---

## 9. Technology summary

- **Framework**: Laravel 10 (MVC)
- **Language**: PHP 8.1+
- **Database**: MySQL
- **Templating**: Blade
- **Styling**: Tailwind CSS (CDN)
- **Icons**: Font Awesome (CDN)
- **Fonts**: Inter + Space Grotesk (Google Fonts)

---

## 10. Where to look during the viva

If the examiner asks:

- **"Where is the registration number used as a primary key?"** → `database/migrations/*_create_vehicles_table.php` line with `$table->string('registration_no', 20)->primary();` and `app/Models/Vehicle.php` (`$primaryKey = 'registration_no'`).
- **"How do you prevent double booking?"** → `app/Http/Controllers/Customer/BookingController.php` → `store()` method inside `DB::transaction` with `lockForUpdate()`.
- **"Where do you implement replacement?"** → `app/Http/Controllers/Admin/ReplacementController.php`.
- **"How did you improve search?"** → `app/Http/Controllers/Customer/VehicleController.php@index` — searches `registration_no` + name + make + model; date filter triggers an overlap-aware availability query.

---

## 11. Note about the Emergent preview

This project was authored inside Emergent, which only runs React/FastAPI/MongoDB in the live preview. **The Laravel source will not run inside the Emergent preview**. To run it locally you need PHP, Composer, and MySQL (XAMPP, Laravel Herd, or Laravel Sail all work).

---

## 12. Email notifications

The app sends real emails via Laravel's built-in Mail system. Two events are mailed:

| Event | Mailable | Template |
|---|---|---|
| Customer creates a booking | `App\Mail\BookingConfirmed` (stage=`created`) | `resources/views/emails/booking-confirmed.blade.php` |
| Admin confirms a booking | `App\Mail\BookingConfirmed` (stage=`confirmed`) | same template, different wording |
| Admin replaces a vehicle | `App\Mail\VehicleReplaced` | `resources/views/emails/vehicle-replaced.blade.php` |

### Local testing (no SMTP needed)
Leave `MAIL_MAILER=log` in `.env`. Every email is written to `storage/logs/laravel.log` where you can read the rendered HTML. Great for the viva demo.

### Real SMTP (e.g. Gmail)
In `.env`:
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=yourgmail@gmail.com
MAIL_PASSWORD=your_16_char_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=yourgmail@gmail.com
MAIL_FROM_NAME="Rakz Rental"
```
(For Gmail you need an "App Password" from your Google account, not your main password.)

Mail failures are caught with `try/catch` + `Log::warning` so a down SMTP server never blocks a booking or replacement.

---

## 13. Reports dashboard

Admin → sidebar → **Reports** (`/admin/reports`). Powered by one controller + Chart.js (CDN).

What it shows:
1. **6 summary cards** — 12-month revenue, 12-month bookings, fleet size, average utilization, replacements, customer count.
2. **Revenue line chart** — month-by-month revenue over the last 12 months (confirmed + completed only). Missing months are filled with zero so the line is continuous.
3. **Fleet utilization table** — per-vehicle `% of days booked` within an admin-selectable date window (defaults to last 90 days). Uses MySQL's `DATEDIFF(LEAST(return_date, :to), GREATEST(pickup_date, :from)) + 1` to count only the overlap with the window.
4. **Top 10 customers** by total spend (confirmed + completed).
5. **Bookings by category** — doughnut chart + revenue table.

All queries live in `app/Http/Controllers/Admin/ReportController.php`. Pure SQL + Eloquent, no extra packages required.

---

## 14. PDF invoices

Both customers and admins can download a branded A4 PDF invoice from the booking detail page.

- Route (customer): `GET /my/bookings/{booking}/invoice` — restricted to the owner
- Route (admin): `GET /admin/bookings/{booking}/invoice` — any admin
- Controller: `App\Http\Controllers\InvoiceController`
- Template: `resources/views/invoices/booking.blade.php`
- Package: `barryvdh/laravel-dompdf` (added to `composer.json`)

The invoice includes customer info, billing address, line item (vehicle, registration no, period, days, daily rate), subtotal/total, payment status badge, replacement audit trail (if any), and customer notes.

If `composer install` has not been run yet, you'll see a "Class not found" error — simply run:
```bash
composer require barryvdh/laravel-dompdf
```

---

## 15. Scheduled auto-complete

Confirmed bookings whose return date has passed are automatically marked as `completed` once a day. The command also creates an in-app notification for the customer.

- Command: `php artisan bookings:mark-completed`
- Dry run (safe preview): `php artisan bookings:mark-completed --dry`
- Registered in `app/Console/Kernel.php` to run **daily at 00:30**.

### Enabling the scheduler in production
Add this single cron entry on your server (Linux) and Laravel takes care of the rest:
```
* * * * * cd /path-to-your-rakz-rental && php artisan schedule:run >> /dev/null 2>&1
```

### Trying it immediately
Just run the command manually:
```bash
php artisan bookings:mark-completed
```
Any confirmed booking with `return_date < today` will be marked completed and the customer will see a success notification.

---

## 16. CSV exports

For your lecturer's inspection, every major dataset can be exported as a UTF-8 CSV (with BOM, so Excel reads it correctly even with Sinhala/Tamil names).

| Button | Route | Columns |
|---|---|---|
| All Bookings CSV (on `/admin/bookings`) | `/admin/export/bookings.csv` | 17 columns: id, status, customer, vehicle reg, dates, days, rate, total, payment, replaced flag |
| Revenue CSV (on `/admin/reports`) | `/admin/export/revenue.csv` | Month, bookings count, revenue |
| Utilization CSV (on `/admin/reports`) | `/admin/export/utilization.csv` | Reg no, vehicle, rate, bookings in window, days booked, window size, utilization % |
| Top Customers CSV (on `/admin/reports`) | `/admin/export/customers.csv` | Id, name, email, phone, bookings, total spent |
| Audit Log CSV (on `/admin/audit-logs`) | `/admin/export/audit-logs.csv` | Timestamp, actor, role, action, target type:id, description, IP, user-agent — respects current page filters |

Implemented in `app/Http/Controllers/Admin/CsvExportController.php` using Laravel's `streamDownload` (memory-efficient — chunks of 500 rows for the bookings table).

---

## 17. Viva / demo script

See **[`docs/demo.md`](docs/demo.md)** for the exact click-paths, SQL overlap proofs, lecturer Q&A, and reset commands to run during your presentation.

---

## 18. QR code on PDF invoice

Each invoice's top-right corner now carries a QR code encoding the booking's canonical URL (customer or admin version depending on who downloaded the PDF).

- Package: `endroid/qr-code` v5 (pure PHP + GD, works inside DomPDF)
- Helper: `App\Support\QrCodeGenerator::dataUri($url)` returns a base64 PNG data URI, which embeds safely in the Blade template.
- Integrated in `InvoiceController@download` and rendered at the top of `resources/views/invoices/booking.blade.php`.
- Also mentioned in the invoice footer so the recipient knows what the code does.

Install step (first run only):
```bash
composer require endroid/qr-code
```

---

## 19. Admin Audit Log viewer

Every tracked administrator (and some customer) actions are written to `audit_logs`. Admin → sidebar → **Audit Log** shows a filterable table.

Tracked events (built-in):

| Action key | When it fires |
|---|---|
| `login` / `login.failed` / `logout` | Any auth attempt |
| `register` | New customer sign-up |
| `booking.create` | Customer creates a booking |
| `booking.cancel` | Customer cancels a booking |
| `booking.status.update` | Admin changes booking status |
| `booking.paid` | Admin marks billing as paid |
| `vehicle.create` / `.update` / `.retire` / `.restore` | Admin fleet changes |
| `vehicle.replace` | Admin swaps a vehicle on a booking |
| `scheduler.mark_completed` | Nightly auto-complete scheduler run |

Filters: keyword, action, actor role, date range. Table shows timestamp, actor name + role, action badge (colour-coded), target type:id, free-text description and IP address.

Code map:
- Table: `database/migrations/*_create_audit_logs_table.php`
- Model: `app/Models/AuditLog.php`
- Writer helper: `app/Support/AuditLogger.php` (`AuditLogger::log($action, $description, $targetType, $targetId)`) — guarded with try/catch so audit failures never block the main action
- Controller / view: `app/Http/Controllers/Admin/AuditLogController.php` · `resources/views/admin/audit_logs/index.blade.php`

---

## 20. Dark / Light theme toggle

A moon/sun button in the **customer navbar** (and also in the **admin topbar**) switches between the signature **dark luxury** theme and a **light accessible** theme. The choice is saved in `localStorage` and applied *before paint* via a tiny inline script, so there is no flash of dark mode when a light-theme user reloads.

- All custom `.rz-*` classes read from CSS variables (dark + light variable blocks).
- Admin and customer themes use **separate** localStorage keys (`rz-theme` vs `rz-admin-theme`) so each portal remembers its own preference — handy for an admin who prefers dark ops dashboards but a light customer view.
- **First-visit default:** when nothing is stored yet, the pre-paint script reads `window.matchMedia('(prefers-color-scheme: light)')` so new visitors land on the same theme their OS is already using. A manual toggle still wins and is remembered thereafter.
- **Keyboard shortcut:** `Shift + T` toggles the theme on either portal without touching the mouse. The handler ignores the shortcut while the focus is in a text input, textarea, select or contentEditable element, so typing an uppercase "T" in a form field never flips the theme by accident.

---

## 21. Printable Vehicle Datasheet

From **Admin → Vehicles**, each row has a printer-icon button that downloads a one-page A4 PDF for that vehicle: brand header with the registration number badge, photo, specifications table, description, daily-rate call-out box, and a scannable QR code that opens the vehicle's public page. Counter staff clip this to the physical key so the plate on the tag is always verified before release.

- Route: `GET /admin/vehicles/{reg_no}/datasheet`
- Controller: `Admin\VehicleController::datasheet()`
- Template: `resources/views/vehicles/datasheet.blade.php`
- Uses the same `endroid/qr-code` + `barryvdh/laravel-dompdf` packages already pulled in by the invoice feature.

---

## 22. Favorite vehicles

Logged-in customers see a heart button on every vehicle card and detail page. Tapping it toggles the vehicle in/out of their personal favorites list.

- Table: `favorites` (migration #9) — `user_id` + `vehicle_registration_no` with a composite UNIQUE index so a vehicle can only be favorited once per user. FK to `vehicles.registration_no` keeps the schema's reg-no-first policy consistent.
- Nav shows a **Favorites** link; `/my/favorites` lists saved vehicles with a one-click **Book again** button (hidden when the vehicle is in maintenance/retired).
- Controller: `Customer\FavoriteController@toggle` is idempotent — tap once to add, tap again to remove.
- Code: `app/Models/Favorite.php`, `app/Http/Controllers/Customer/FavoriteController.php`, `resources/views/customer/favorites/index.blade.php`.

---

## 23. Return date reminder email (scheduler)

A second scheduled command runs at **08:00 daily** and emails every customer whose booking's `return_date` is tomorrow, plus creates an in-app notification. Overdue returns drop because customers get a nudge the day before.

- Command: `php artisan bookings:send-return-reminders` (supports `--dry` for a safe preview)
- Mailable: `App\Mail\ReturnDateReminder` · Template: `resources/views/emails/return-reminder.blade.php`
- Registered in `app/Console/Kernel.php` alongside the auto-complete command
- Writes an audit log entry (`scheduler.return_reminders`) when it runs

Same cron entry on the server covers both scheduled jobs:
```
* * * * * cd /path-to-rakz-rental && php artisan schedule:run >> /dev/null 2>&1
```

---

## 24. Compare vehicles side-by-side

Customers can tick a **Compare** checkbox on vehicle cards from either place:
- **Browse grid** (`/vehicles`) — sticky toolbar appears at the top with a **Compare selected** button
- **Favorites page** (`/my/favorites`) — same checkbox + sticky toolbar

Tick 2 or 3 and submit → `/my/compare?v[]=LR3180&v[]=LR3181` renders a dense side-by-side table (photo, reg-no badge, category, daily rate, seats, transmission, fuel, status, description). The cheapest row and the vehicle with the most seats are highlighted in gold. Each column carries a direct **Book this** button.

- Controller: `App\Http\Controllers\Customer\CompareController` (silently caps at 3, de-duplicates, preserves click order)
- Template: `resources/views/customer/compare/show.blade.php`
- Shared card partial `_card.blade.php` accepts an optional `$showCompare` flag so the home-page featured grid stays checkbox-free
- JS: a tiny inline guard on both grids prevents the user from ticking more than 3

