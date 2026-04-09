# BRIMS Deployment Guide

This guide covers deploying the BRIMS (Bio-medical Research Information Management System) Laravel application on an Ubuntu/Debian server running **Nginx** and **PHP-FPM**.

---

## Table of Contents

1. [Server Requirements](#server-requirements)
2. [Installing Dependencies](#installing-dependencies)
3. [Application Setup](#application-setup)
4. [Nginx Configuration](#nginx-configuration)
5. [PHP-FPM Configuration](#php-fpm-configuration)
6. [Database Setup](#database-setup)
7. [Environment Configuration](#environment-configuration)
8. [File Permissions](#file-permissions)
9. [Optimization](#optimization)
10. [Queue Worker](#queue-worker)
11. [Scheduled Tasks](#scheduled-tasks)
12. [Deployment Checklist](#deployment-checklist)
13. [Updating the Application](#updating-the-application)

---

## Server Requirements

| Requirement | Version |
|---|---|
| PHP | 8.5+ |
| MariaDB | 11+ |
| Nginx | 1.18+ |
| Node.js | 22+ (build only) |
| Composer | 2.x |

### Required PHP Extensions

The Laravel framework requires the following PHP extensions as a minimum (extension names, not package names):

```
Ctype, cURL, DOM, Fileinfo, Filter, Hash, Mbstring, OpenSSL, PCRE, PDO, Session, Tokenizer, XML
```

The following additional Ubuntu/Debian packages are needed for BRIMS:

```
php8.5-fpm
php8.5-cli
php8.5-mysql       (MariaDB/MySQL database driver)
php8.5-mbstring
php8.5-xml
php8.5-zip
php8.5-bcmath
php8.5-curl
php8.5-gd
php8.5-intl
php8.5-sqlite3
php8.5-soap
php8.5-redis        (if using Redis for cache/queues)
php8.5-imagick
```

---

## Installing Dependencies

### 1. Add the Ondřej Surý PHP PPA

```bash
sudo apt update && sudo apt install -y software-properties-common curl gnupg
curl -sS 'https://keyserver.ubuntu.com/pks/lookup?op=get&search=0xb8dc7e53946656efbce4c1dd71daeaab4ad4cab6' \
  | sudo gpg --dearmor \
  | sudo tee /etc/apt/keyrings/ppa_ondrej_php.gpg > /dev/null
echo "deb [signed-by=/etc/apt/keyrings/ppa_ondrej_php.gpg] https://ppa.launchpadcontent.net/ondrej/php/ubuntu $(lsb_release -cs) main" \
  | sudo tee /etc/apt/sources.list.d/ppa_ondrej_php.list
sudo apt update
```

### 2. Install Nginx and PHP-FPM

```bash
sudo apt install -y nginx \
    php8.5-fpm \
    php8.5-cli \
    php8.5-mysql \
    php8.5-mbstring \
    php8.5-xml \
    php8.5-zip \
    php8.5-bcmath \
    php8.5-curl \
    php8.5-gd \
    php8.5-intl \
    php8.5-sqlite3 \
    php8.5-soap \
    php8.5-imagick
```

### 3. Install MariaDB

```bash
sudo apt install -y mariadb-server
sudo mysql_secure_installation
```

### 4. Install Composer

```bash
curl -sLS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer
```

### 5. Install Node.js (for building frontend assets)

```bash
curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key \
  | sudo gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg
echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_22.x nodistro main" \
  | sudo tee /etc/apt/sources.list.d/nodesource.list
sudo apt update && sudo apt install -y nodejs
```

---

## Application Setup

### 1. Clone the Repository

```bash
sudo mkdir -p /var/www/brims
sudo chown $USER:$USER /var/www/brims
git clone https://github.com/gvds/brims.git /var/www/brims
cd /var/www/brims
```

### 2. Install PHP Dependencies

```bash
composer install --optimize-autoloader --no-dev
```

### 3. Install and Build Frontend Assets

```bash
npm install
npm run build
```

### 4. Create and Configure the Environment File

Copy the provided template and generate the application key:

```bash
cp .env.template .env
php artisan key:generate
```

Edit `.env` and fill in all required values (see [Environment Configuration](#environment-configuration) below).

### 5. Run Database Migrations

```bash
php artisan migrate --force
```

### 6. Create the Storage Symlink

```bash
php artisan storage:link
```

### 7. Optimize the Application

Cache all configuration, events, routes, and views for production (see [Optimization](#optimization) for details):

```bash
php artisan optimize
```

---

## Nginx Configuration

Create an Nginx server block for the application. Replace `brims.example.com` with your actual domain name.

```bash
sudo nano /etc/nginx/sites-available/brims
```

Paste the following configuration:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name brims.example.com;

    # Redirect all HTTP to HTTPS
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl;
    listen [::]:443 ssl;
    http2 on;

    server_name brims.example.com;

    # SSL — update paths to your actual certificate files
    ssl_certificate     /etc/ssl/certs/brims.example.com.crt;
    ssl_certificate_key /etc/ssl/private/brims.example.com.key;
    ssl_protocols       TLSv1.2 TLSv1.3;
    ssl_ciphers         HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;
    ssl_session_cache   shared:SSL:10m;

    root /var/www/brims/public;
    index index.php;

    charset utf-8;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";
    add_header Referrer-Policy "strict-origin-when-cross-origin";

    # Increase upload limit for study/assay file uploads
    client_max_body_size 100M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    # Only allow execution of index.php (recommended by Laravel docs)
    location ~ ^/index\.php(/|$) {
        fastcgi_pass unix:/var/run/php/php8.5-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    # Deny access to hidden files (e.g. .env, .git)
    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Cache static assets
    location ~* \.(css|js|gif|ico|jpeg|jpg|png|svg|webp|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
    }

    access_log /var/log/nginx/brims_access.log;
    error_log  /var/log/nginx/brims_error.log;
}
```

Enable the site and reload Nginx:

```bash
sudo ln -s /etc/nginx/sites-available/brims /etc/nginx/sites-enabled/brims
sudo nginx -t
sudo systemctl reload nginx
```

> **SSL Certificates:** For free, automatically-renewing certificates use [Certbot](https://certbot.eff.org/):
> ```bash
> sudo apt install -y certbot python3-certbot-nginx
> sudo certbot --nginx -d brims.example.com
> ```

---

## PHP-FPM Configuration

### Recommended `php.ini` settings

Edit `/etc/php/8.5/fpm/php.ini` (or create a drop-in at `/etc/php/8.5/fpm/conf.d/99-brims.ini`):

```ini
upload_max_filesize = 100M
post_max_size       = 100M
max_execution_time  = 120
memory_limit        = 256M
opcache.enable      = 1
opcache.memory_consumption = 128
opcache.interned_strings_buffer = 16
opcache.max_accelerated_files = 10000
opcache.validate_timestamps = 0   ; set to 1 during development
```

Restart PHP-FPM after changes:

```bash
sudo systemctl restart php8.5-fpm
```

---

## Database Setup

### Create the Database and User

```sql
sudo mariadb -u root

CREATE DATABASE brims CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'brims'@'localhost' IDENTIFIED BY 'strong_password_here';
GRANT ALL PRIVILEGES ON brims.* TO 'brims'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

---

## Environment Configuration

The repository includes an `.env.template` file with all supported variables, grouped by feature and annotated with descriptions. Copy it to `.env` and fill in the values for your environment:

```bash
cp .env.template .env
```

The key variables to set for a production deployment are:

| Variable | Description |
|---|---|
| `APP_KEY` | Generated by `php artisan key:generate` |
| `APP_URL` | Full HTTPS URL of the application |
| `APP_DEBUG` | Must be `false` in production |
| `DB_HOST` / `DB_DATABASE` / `DB_USERNAME` / `DB_PASSWORD` | Primary database connection |
| `REDCAP_DB_*` / `REDCAP_URL` | REDCap integration — leave commented out if not used |
| `MAIL_HOST` / `MAIL_USERNAME` / `MAIL_PASSWORD` | Outgoing mail server |
| `TUS_ENDPOINT` | TUS server URL for resumable uploads — leave blank if not used |

> **Never commit `.env` to version control.** It contains secrets.

> **Warning:** In your production environment, `APP_DEBUG` must always be `false`. If it is set to `true` in production, you risk exposing sensitive configuration values and stack traces to end users.

---

## File Permissions

The web server process (`www-data` on Ubuntu/Debian) must be able to write to the `storage` and `bootstrap/cache` directories.

```bash
sudo chown -R www-data:www-data /var/www/brims/storage
sudo chown -R www-data:www-data /var/www/brims/bootstrap/cache
sudo chmod -R 775 /var/www/brims/storage
sudo chmod -R 775 /var/www/brims/bootstrap/cache
```

Add your deploy user to the `www-data` group so you can still write files:

```bash
sudo usermod -aG www-data $USER
```

---

## Optimization

When deploying to production, several files should be cached to improve performance. Laravel provides a single command that handles all of them:

```bash
php artisan optimize
```

This is equivalent to running the following individual commands:

```bash
# Cache all configuration values into a single file
php artisan config:cache

# Cache event-to-listener mappings
php artisan event:cache

# Cache all route registrations
php artisan route:cache

# Pre-compile all Blade views
php artisan view:cache
```

> **Warning:** After running `config:cache`, the `.env` file is no longer loaded at runtime. All calls to `env()` from outside of configuration files will return `null`. Always access environment values through `config()` helpers in application code — for example use `config('app.name')` instead of `env('APP_NAME')`.

To clear all caches (e.g. for debugging):

```bash
php artisan optimize:clear
```

---

## Queue Worker

BRIMS uses the **database** queue driver by default. A persistent queue worker is required to process queued jobs (e.g. notifications, exports).

### Supervisor Configuration

Install Supervisor:

```bash
sudo apt install -y supervisor
```

Create a Supervisor configuration file:

```bash
sudo nano /etc/supervisor/conf.d/brims-worker.conf
```

```ini
[program:brims-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/brims/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/log/supervisor/brims-worker.log
stopwaitsecs=3600
```

Enable and start the worker:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start brims-worker:*
```

Check worker status:

```bash
sudo supervisorctl status
```

---

## Scheduled Tasks

BRIMS registers the following scheduled commands:

| Command | Frequency | Purpose |
|---|---|---|
| `app:delete-old-exports` | Hourly | Clean up stale export files |
| `app:deactivate-inactive-users` | Daily | Deactivate users who have not logged in for 3 months |

Add a single cron entry for the Laravel scheduler:

```bash
sudo crontab -u www-data -e
```

Add the following line:

```cron
* * * * * php /var/www/brims/artisan schedule:run >> /dev/null 2>&1
```

---

---

## Deployment Checklist

Use this checklist for every deployment:

- [ ] Pull latest code: `git pull origin main`
- [ ] Install/update PHP dependencies: `composer install --optimize-autoloader --no-dev`
- [ ] Install/update Node dependencies and rebuild assets: `npm install && npm run build`
- [ ] Run pending migrations: `php artisan migrate --force`
- [ ] Clear and rebuild the optimization cache: `php artisan optimize`
- [ ] Restart queue workers: `php artisan queue:restart`
- [ ] Reload PHP-FPM: `sudo systemctl reload php8.5-fpm`

---

## Updating the Application

A typical update deployment from the application directory (`/var/www/brims`):

```bash
cd /var/www/brims

# 1. Put the application into maintenance mode
php artisan down

# 2. Pull latest code
git pull origin main

# 3. Install/update dependencies
composer install --optimize-autoloader --no-dev
npm install && npm run build

# 4. Run any new database migrations
php artisan migrate --force

# 5. Clear old caches and rebuild optimized caches
php artisan optimize

# 6. Restart queue workers so they pick up the new code
php artisan queue:restart

# 7. Reload PHP-FPM to pick up any new OPcache files
sudo systemctl reload php8.5-fpm

# 8. Bring the application back online
php artisan up
```
