# Rakz Rental — Viva / Demo Script

> One-page click path, SQL proofs, and anticipated Q&A for the live presentation.
> **Keep this file open on your second monitor during the demo.**

---

## 0. Before you start

```bash
cd rakz-rental
composer install
cp .env.example .env     # if not already done
php artisan key:generate
php artisan migrate:fresh --seed    # fresh demo data
php artisan serve                   # http://localhost:8000
```

Open two browser windows side by side:
- **Window A**: incognito — for the Customer account
- **Window B**: normal — for the Admin account

Demo accounts (seeded):

| Role     | Email                       | Password     |
| -------- | --------------------------- | ------------ |
| Admin    | `admin@rakzrental.com`      | `admin123`   |
| Customer | `customer@rakzrental.com`   | `customer123`|

---

## 1. Customer flow — "Find a car and book it" (3–4 min)

**Window A** (incognito):

1. Go to **`/`** → point out the hero image and that the system is built for Sri Lankan car rental.
2. Click **Browse Fleet** → show the catalog.
3. In the search box, type **`LR3180`** → press **Search**.
   - **Talking point:** *"Notice I can search by the real registration number, because registration number is literally the primary key of the vehicle table — not a hidden numeric ID."*
4. Enter **Pickup date = tomorrow**, **Return date = tomorrow + 2 days**, press **Search** again.
   - **Talking point:** *"The list is now date-aware. Any vehicle with an overlapping booking disappears."*
5. Click **View** on **Toyota Corolla (LR3180)**.
6. Click **Log in to book** (or Sign Up) → log in as `customer@rakzrental.com`.
7. Back on the vehicle page, click **Book this vehicle**.
8. Keep the same two dates, click **Check availability & price**.
   - Show the AJAX response: `Days`, `Total` populate instantly, status says "available".
9. Click **Confirm Booking**.
10. Open **My Bookings** → the new booking is there with status **Pending**.
11. (Optional) Open your terminal: `tail -n 50 storage/logs/laravel.log` → the booking-received email is in the log (since `MAIL_MAILER=log`).

---

## 2. Admin flow — "Confirm, replace, download" (4–5 min)

**Window B** (admin):

1. Log in as `admin@rakzrental.com` → **Dashboard** shows the booking you just made under *Recent bookings*.
2. Click **Bookings** → open the booking you just created.
3. Change status dropdown to **Confirmed** → **Update**.
   - **Talking point:** *"The customer gets both an in-app notification and a real email (log driver in dev; SMTP in production — switchable in `.env`)."*
4. Click **Mark as paid** → billing status becomes Paid.
5. Click **Replace vehicle**.
   - **Talking point:** *"The system only shows vehicles that are free for THIS booking's date range."*
6. Select **Honda Civic (LR3181)**, type reason **"Original vehicle sent for routine maintenance"**, click **Confirm replacement**.
7. Back on booking page: show the amber notice "Vehicle replaced LR3180 → LR3181" and the Replacement audit trail entry.
8. Click **Download Invoice (PDF)** → open the PDF → point out:
   - Brand header
   - Current registration (LR3181) *and* the "note" showing it was replaced from LR3180
   - Payment status badge = PAID
9. **Window A** (customer): refresh **My Bookings** → the booking shows the "Replaced" badge. Click it → the replacement audit trail is visible to the customer. Click the bell icon → notification is there.

---

## 3. The double-booking proof (2 min) — this is the one that wins points

**Window A** (customer, still logged in):

1. Back on **Browse Fleet**, click LR3181 (the Honda Civic you now hold).
2. **Book this vehicle** → use dates that **overlap** with what Window B just assigned (same tomorrow → +2 days).
3. Click **Check availability & price**.
   - System replies: *"Sorry, this vehicle is already booked for part of your selected period."*
   - **Confirm Booking** button stays **disabled**.
4. **Talking point:** *"That check runs on the server, not just the UI. And at submit time there's an additional DB transaction with row lock — I'll show you the SQL."*

---

## 4. SQL overlap proof — show the lecturer the actual query

Open MySQL CLI / Workbench on the demo DB and run:

```sql
-- Show the raw booking row
SELECT id, user_id, vehicle_registration_no, pickup_date, return_date, status, total_amount
FROM bookings
WHERE vehicle_registration_no = 'LR3181'
  AND status IN ('pending','confirmed');

-- The exact overlap predicate used in the backend
-- Try to insert a booking from TODAY+1 to TODAY+2 for the same vehicle
SELECT COUNT(*) AS overlapping_bookings
FROM bookings
WHERE vehicle_registration_no = 'LR3181'
  AND status IN ('pending','confirmed')
  AND pickup_date  <= DATE_ADD(CURDATE(), INTERVAL 2 DAY)
  AND return_date  >= DATE_ADD(CURDATE(), INTERVAL 1 DAY);
```

Point to the two WHERE lines:
```
pickup_date <= :requested_return
AND return_date >= :requested_pickup
```
That's the complete, correct interval-overlap predicate (also called *Allen's "intersects" relation*). The row-level lock (`SELECT ... FOR UPDATE`) wraps this inside `DB::transaction()` so two users submitting at the exact same millisecond cannot both win.

Code reference to open:
`app/Http/Controllers/Customer/BookingController.php` → `store()` method.

---

## 5. Vehicle-registration-as-PK proof

Run in MySQL CLI:

```sql
-- Prove registration_no is the PRIMARY KEY (not a numeric id)
SHOW CREATE TABLE vehicles\G
-- Expect:  PRIMARY KEY (`registration_no`)

-- Prove bookings references it as a STRING foreign key, not a numeric vehicle_id
SHOW CREATE TABLE bookings\G
-- Expect:  CONSTRAINT bookings_vehicle_registration_no_foreign
--          FOREIGN KEY (vehicle_registration_no) REFERENCES vehicles (registration_no)

SHOW CREATE TABLE vehicle_replacements\G
-- Expect:  two FKs: original_registration_no, replacement_registration_no
--          each referencing vehicles.registration_no
```

Code references to open:
- `database/migrations/*_create_vehicles_table.php` → `$table->string('registration_no', 20)->primary();`
- `app/Models/Vehicle.php` → `$primaryKey = 'registration_no'`, `$incrementing = false`, `$keyType = 'string'`.
- `routes/web.php` → `{vehicle:registration_no}` route-model binding.

---

## 6. Reports + CSV export (2 min)

**Window B** (admin):

1. Sidebar → **Reports**.
2. Show:
   - 6 summary cards (revenue, bookings, utilization...)
   - Revenue line chart (12 months)
   - Fleet utilization bar table — change the from/to dates and click **Apply**
   - Top-customers list
   - Bookings-by-category doughnut
3. Click **Revenue CSV**, **Utilization CSV**, **All Bookings CSV**.
4. Open the downloaded files in Excel — point out that they open with UTF-8 BOM so Sinhala/Tamil names won't break.

---

## 7. Scheduled auto-complete (1 min)

Open terminal:

```bash
php artisan bookings:mark-completed --dry
```
Shows what *would* change but makes no DB write.

```bash
php artisan bookings:mark-completed
```
Any `confirmed` booking whose return date is already in the past becomes `completed` and the customer gets a notification.

In production this runs daily at 00:30 — see `app/Console/Kernel.php` and the single cron entry noted in `README.md` section 15.

---

## 8. Anticipated lecturer questions

### Q1. "Why registration number as the primary key?"
A: Business entities should use their natural, real-world identifier when it is unique, stable, and already known to the users. A plate number ticks all three. Surrogate numeric IDs would only add indirection — booking confirmations, invoices and audit logs would then always have to display two IDs ("Booking #42 for vehicle #7 (LR3180)") instead of one.

### Q2. "What if a plate ever changes?"
A: In Sri Lanka the registration is bound to the chassis, not the owner — it does not change. If it did we would issue the vehicle a new row and `retire` the old row (status change, FK integrity preserved).

### Q3. "How is availability guaranteed?"
A: Two layers:
1. Browse page excludes any vehicle whose existing bookings overlap the requested window.
2. The actual `store` method runs the exact same overlap predicate inside a `DB::transaction` + `lockForUpdate` — so a concurrent submission cannot sneak in between check and insert.

### Q4. "Can a customer modify someone else's booking?"
A: No. Every customer controller action calls `abort_if($booking->user_id !== Auth::id(), 403)`. Admin-only routes are protected by the `admin` middleware.

### Q5. "What happens to a booking after you replace the vehicle?"
A: `bookings.vehicle_registration_no` updates to the new plate. `bookings.original_vehicle_registration_no` is set the first time (and never overwritten) so the original is preserved even across multiple replacements. Every swap also inserts a row in `vehicle_replacements` with the full audit trail (from, to, reason, who, when).

### Q6. "Where is password security handled?"
A: Laravel's `Hash::make()` (bcrypt). The `User` model has `'password' => 'hashed'` cast so plaintext is never stored. Login uses `Auth::attempt()` which runs the constant-time bcrypt check.

### Q7. "Why do you keep retired vehicles instead of deleting them?"
A: Deleting a vehicle would violate the foreign keys on past bookings and replacement rows. Setting `status = 'retired'` keeps full history but blocks new bookings. The admin UI exposes a Restore button.

---

## 9. Fast reset between demos

```bash
php artisan migrate:fresh --seed
```
Fresh database with the 8 demo vehicles and the admin + customer accounts, zero bookings. Takes ~1 second.

---

## 10. Where to look if something is asked about

| Topic | Open this file |
|---|---|
| Registration no as PK | `database/migrations/*_create_vehicles_table.php` · `app/Models/Vehicle.php` |
| Overlap check | `app/Http/Controllers/Customer/BookingController.php` → `store()` |
| Date-aware search | `app/Http/Controllers/Customer/VehicleController.php` → `index()` |
| Vehicle replacement | `app/Http/Controllers/Admin/ReplacementController.php` |
| Role-based security | `app/Http/Middleware/EnsureUserIsAdmin.php` · `EnsureUserIsCustomer.php` |
| Email sending | `app/Mail/*` · `resources/views/emails/*` |
| PDF invoice | `app/Http/Controllers/InvoiceController.php` · `resources/views/invoices/booking.blade.php` |
| Scheduler | `app/Console/Commands/MarkBookingsCompleted.php` · `app/Console/Kernel.php` |
| Reports queries | `app/Http/Controllers/Admin/ReportController.php` |
