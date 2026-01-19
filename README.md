# My Health Notebook

A lightweight PHP/MySQL web app to keep personal health records in one place: vaccination history, reminders, medical reports, and a health profile. Includes PDF export via Dompdf and simple auth.

## Features
- User registration/login with hashed passwords
- Health profile: blood type, chronic diseases, allergies, medications, emergency contact
- Vaccination tracking with next-dose dates and notes
- Reminders with pending/completed status
- Medical report uploads (PDF/images, 2MB limit) with listing and deletion
- One-click PDF export of all data (uses Dompdf)

## Tech Stack
- PHP 8+ (tested with built-in PHP server)
- MySQL/MariaDB
- Composer for dependencies (`dompdf/dompdf`)
- HTML/CSS/JS
- Bootstrap 5 for UI

## Prerequisites
- PHP 8+ with `pdo_mysql`, `fileinfo`, and `openssl` extensions enabled
- MySQL/MariaDB
- Composer
- Write access to `uploads/files/` for storing uploaded reports

## Setup
1) Clone the repo:
```bash
git clone https://github.com/sbaamoha/health-notebook
cd my-health-notebook
```

2) Install PHP dependencies:
```bash
composer install
```

3) Configure the database connection in `config/db.php` (host, port, db name, user, password).

4) Create the database and user (fresh MySQL install, using root):
```bash
mysql -u root -p -e "CREATE DATABASE my_health_notebook CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci; CREATE USER IF NOT EXISTS 'appuser'@'%' IDENTIFIED BY 'StrongPass123'; GRANT ALL PRIVILEGES ON my_health_notebook.* TO 'appuser'@'%'; FLUSH PRIVILEGES;"
```
   - If you prefer to keep using root, you can skip creating `appuser`; just remember to use `-u root -p` in the next step.

5) Create the database schema automatically:
```bash
mysql -u appuser -p my_health_notebook < scripts/schema.sql
# or, if using root:
mysql -u root -p my_health_notebook < scripts/schema.sql
```

5) Make sure `uploads/files/` exists and is writable by PHP:
```bash
mkdir -p uploads/files
chmod 755 uploads/files   # adjust as needed on your system
```

## Running the app (local)
```bash
php -S localhost:8000
```
Then visit http://localhost:8000 in your browser, register a user, and start adding data.

## PDF Export
The `Export PDF` button uses Dompdf. Ensure `vendor/autoload.php` exists (run `composer install`). If missing, install with:
```bash
composer require dompdf/dompdf
```

## File Upload Notes
- Allowed types: PDF, JPG, PNG, GIF
- Max size: 2MB
- Files are stored under `uploads/files/` and referenced in the database.

## Development Tips
- Auth/session helpers live in `includes/functions.php`
- Main pages: `dashboard.php`, `vaccines/*`, `uploads/*`, `user/*`
- Navbar and layout: `includes/header.php` / `includes/footer.php`