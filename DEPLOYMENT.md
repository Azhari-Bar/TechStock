# Deployment TECHSTOCK

## Prasyarat

- PHP 8.2+ dengan extension: `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `xml`, `zip`, `gd` (opsional untuk image processing)
- Composer 2
- Node.js 20+ dan npm
- MariaDB 10.4+ atau MySQL 8+
- Web server Nginx atau Apache
- Cron scheduler

## Konfigurasi Production

1. Salin proyek ke server.
2. Salin dan isi environment file:

```bash
cp .env.example .env
```

3. Isi nilai production minimal:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-anda.example
APP_TIMEZONE=Asia/Jakarta

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3307
DB_DATABASE=techstock
DB_USERNAME=techstock_user
DB_PASSWORD=password-kuat
DB_CHARSET=utf8mb4
DB_COLLATION=utf8mb4_unicode_ci
```

4. Install dependency dan build asset:

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan key:generate
php artisan migrate --force
php artisan storage:link
php artisan optimize
```

> Jangan jalankan `migrate:fresh` pada production. Command itu menghapus seluruh data database.

5. Atur akses folder:

```bash
chmod -R ug+rwx storage bootstrap/cache
```

6. Document root web server harus menunjuk ke folder `public/`, bukan root repository.

## Nginx Minimum

```nginx
server {
    listen 80;
    server_name domain-anda.example;
    root /var/www/techstock/public;

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Sesuaikan socket PHP-FPM (`fastcgi_pass`) dengan versi server.

## Scheduler Rental Terlambat

Tambahkan cron server:

```cron
* * * * * cd /var/www/techstock && php artisan schedule:run >> /dev/null 2>&1
```

Scheduler akan menjalankan `rentals:mark-overdue` harian.

Uji manual:

```bash
php artisan rentals:mark-overdue --dry-run
php artisan rentals:mark-overdue
```

## Queue Worker

Saat fitur queue aktif dipakai, jalankan worker via Supervisor/systemd:

```bash
php artisan queue:work --sleep=3 --tries=3 --max-time=3600
```

## Final Checklist

- [ ] `APP_DEBUG=false`
- [ ] Password admin default diganti
- [ ] User database production bukan `root`
- [ ] HTTPS aktif
- [ ] `php artisan test` sukses di staging
- [ ] `php artisan migrate --force` sukses
- [ ] `php artisan storage:link` sukses
- [ ] `npm run build` sukses
- [ ] Cron scheduler aktif
- [ ] Backup MariaDB dijadwalkan
- [ ] Cek upload identitas dan bukti pembayaran tersimpan non-public pada disk `local`
