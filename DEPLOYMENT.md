# ZEMS Laravel Deployment Guide
## Deploy to growmusicians.com (Shared Hosting / cPanel)

---

## Prerequisites
- cPanel access for growmusicians.com
- PHP 8.2+ enabled on the server
- MySQL database access
- SSH access (recommended) or File Manager

---

## STEP 1: Prepare the Build Locally

Before uploading, build the production assets on your local machine:

```powershell
# In your project directory
Set-ExecutionPolicy -Scope Process -ExecutionPolicy Bypass
npm run build
```

This creates the `public/build/` folder with compiled CSS/JS.

---

## STEP 2: Create the Database on Server

1. Log in to **cPanel** → **MySQL Databases**
2. Create a new database: `growmusi_zems_db` (cPanel prefixes your username)
3. Create a database user: `growmusi_zems_user`
4. Set a strong password and **note it down**
5. Add the user to the database with **ALL PRIVILEGES**
6. Go to **phpMyAdmin** → Select your new database → **Import** tab
7. Upload and import `database/zems_db.sql`

---

## STEP 3: Upload Files to Server

### Option A: Using File Manager (cPanel)

1. **Zip your entire project** (exclude `node_modules` and `.git`):
   - Select all files/folders EXCEPT `node_modules/` and `.git/`
   - Create a ZIP archive

2. **Upload the ZIP** to your server:
   - In cPanel File Manager, navigate to `/home/yourusername/`
   - Create a folder called `zems-laravel` (outside public_html)
   - Upload the ZIP file into `zems-laravel/`
   - Extract it there

3. **Final server structure should look like**:
   ```
   /home/yourusername/
   ├── zems-laravel/          ← Your Laravel app (NOT in public_html)
   │   ├── app/
   │   ├── bootstrap/
   │   ├── config/
   │   ├── database/
   │   ├── public/             ← Only THIS folder's contents go to public_html
   │   ├── resources/
   │   ├── routes/
   │   ├── storage/
   │   ├── vendor/
   │   ├── .env
   │   ├── artisan
   │   └── composer.json
   └── public_html/            ← Domain root (growmusicians.com points here)
   ```

### Option B: Using SSH (Recommended)

```bash
# Connect via SSH
ssh yourusername@growmusicians.com

# Navigate to home
cd ~

# Create project directory
mkdir zems-laravel

# Upload via SCP from your local machine (run this locally):
# scp -r ./* yourusername@growmusicians.com:~/zems-laravel/
# (exclude node_modules - it's not needed on server)
```

---

## STEP 4: Link public_html to Laravel's public Folder

This is the **most critical step**. Laravel's `public/` folder must serve as the document root.

### Method 1: Symlink (SSH Required — Recommended)

```bash
# SSH into the server
# Backup existing public_html contents if any
mv ~/public_html ~/public_html_backup

# Create a symlink
ln -s ~/zems-laravel/public ~/public_html
```

### Method 2: Copy + Modify index.php (If No SSH)

If you can't use SSH, copy the **contents** of `public/` into `public_html/` and modify `index.php`:

1. Copy ALL files from `zems-laravel/public/` into `public_html/`:
   - `index.php`
   - `.htaccess`
   - `build/` folder
   - `favicon.ico`
   - `robots.txt`
   - `images/` folder

2. Edit `public_html/index.php` to point to the correct paths:

```php
<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Check If The Application Is Under Maintenance
if (file_exists($maintenance = __DIR__.'/../zems-laravel/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register The Auto Loader
require __DIR__.'/../zems-laravel/vendor/autoload.php';

// Run The Application
$app = require_once __DIR__.'/../zems-laravel/bootstrap/app.php';

$app->handleRequest(Request::capture());
```

3. Also create/edit `public_html/.htaccess`:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

---

## STEP 5: Configure the .env File on Server

Edit the `.env` file on the server (`zems-laravel/.env`):

```env
APP_NAME="ZEMS"
APP_ENV=production
APP_KEY=base64:y8xWEODbQtG9E9mLYTXbW0jcF1jB6nHXn/fUyOUeaCo=
APP_DEBUG=false
APP_URL=https://growmusicians.com

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=growmusi_zems_db
DB_USERNAME=growmusi_zems_user
DB_PASSWORD=YOUR_DATABASE_PASSWORD_HERE

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync

CACHE_STORE=file

MAIL_MAILER=log

VITE_APP_NAME="ZEMS"
```

### ⚠️ IMPORTANT .env changes for production:
| Setting | Local | Production |
|---------|-------|------------|
| `APP_ENV` | `local` | `production` |
| `APP_DEBUG` | `true` | `false` |
| `APP_URL` | `http://localhost:8000` | `https://growmusicians.com` |
| `DB_DATABASE` | `zems_db` | `growmusi_zems_db` |
| `DB_USERNAME` | `root` | `growmusi_zems_user` |
| `DB_PASSWORD` | (empty) | `your_password` |
| `QUEUE_CONNECTION` | `database` | `sync` |
| `LOG_LEVEL` | `debug` | `error` |

---

## STEP 6: Set Permissions (SSH)

```bash
cd ~/zems-laravel

# Storage and cache must be writable
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Ensure storage directories exist
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
```

If using **File Manager** instead of SSH:
- Right-click `storage/` → Change Permissions → Set to `0775` recursively
- Right-click `bootstrap/cache/` → Change Permissions → Set to `0775`

---

## STEP 7: Run Artisan Commands (SSH)

```bash
cd ~/zems-laravel

# Install PHP dependencies (if vendor/ was not uploaded)
composer install --no-dev --optimize-autoloader

# Clear and cache config
php artisan config:clear
php artisan config:cache

# Clear and cache routes
php artisan route:clear
php artisan route:cache

# Clear and cache views
php artisan view:clear
php artisan view:cache

# Create storage symlink (for file uploads)
php artisan storage:link
```

If you **don't have SSH**, most of these can be skipped if you uploaded the `vendor/` folder. But try to access the cPanel **Terminal** (some hosts provide it).

---

## STEP 8: Verify Deployment

1. Visit `https://growmusicians.com` — You should see the login page
2. Login with:
   - **Email:** `admin@wilgamuwa.com`
   - **Password:** `password123`
3. Test all sections: Dashboard, Schools, Teachers, Subjects

---

## Troubleshooting

### 500 Internal Server Error
- Check `storage/logs/laravel.log` for error details
- Ensure `storage/` and `bootstrap/cache/` are writable (775)
- Ensure `.env` has correct database credentials
- Run `php artisan config:clear` via SSH or Terminal

### Blank White Page
- Set `APP_DEBUG=true` temporarily to see the error
- Check PHP version is 8.2+ (cPanel → Select PHP Version)

### CSS/JS Not Loading (Unstyled Page)
- Make sure `public/build/` folder was uploaded
- Check if `.htaccess` is present in the document root
- Ensure `APP_URL` in `.env` matches your domain exactly

### Database Connection Error
- Verify DB credentials in `.env`
- Make sure the DB user is added to the database with all privileges
- Try `127.0.0.1` instead of `localhost` for `DB_HOST`

### "Class not found" errors
- Run `composer dump-autoload` via SSH
- Or re-upload the `vendor/` folder

---

## Quick Checklist

- [ ] `npm run build` done locally (public/build/ exists)
- [ ] Database created on server
- [ ] `zems_db.sql` imported via phpMyAdmin
- [ ] Files uploaded to `~/zems-laravel/`
- [ ] `public_html` linked to `public/` folder
- [ ] `.env` updated with production values
- [ ] `storage/` permissions set to 775
- [ ] `bootstrap/cache/` permissions set to 775
- [ ] Site loads at growmusicians.com
- [ ] Login works with admin credentials
