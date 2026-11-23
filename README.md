# TECHSTOCK

Sistem inventaris & rental barang (Laravel 11 + MariaDB/MySQL + Tailwind + Alpine + Chart.js).

## Prasyarat

- PHP 8.2+ (extension: `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `gd`)
- Composer 2
- Node.js 20+ & npm
- MariaDB 10.4+ / MySQL 8+ (XAMPP/Laragon juga boleh)

## Setup (sekali saja)

```bash
composer install
npm install
cp .env.example .env        # Windows: copy .env.example .env
php artisan key:generate
```

Sesuaikan bagian `DB_*` di `.env` dengan MySQL di komputer masing-masing
(port default XAMPP/Laragon biasanya **3306**, proyek ini aslinya **3307**):

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=techstock
DB_USERNAME=root
DB_PASSWORD=
```

Buat database kosong, lalu isi struktur + data contoh:

```bash
mysql -u root -e "CREATE DATABASE techstock"
php artisan migrate --seed
php artisan storage:link
npm run build
```

## Menjalankan

```bash
php artisan serve
```

Buka http://localhost:8000

Akun demo: `admin@techstock.test` / `password`

Saat mengembangkan tampilan, jalankan `npm run dev` di terminal terpisah.
**Jangan** bagikan link ke orang lain selama `npm run dev` menyala — file
`public/hot` membuat CSS/JS diambil dari `localhost:5173` dan tampilan jadi
berantakan di komputer lain. Tutup dev server lalu `npm run build`.

## Yang TIDAK boleh dikirim

`.env`, `vendor/`, `node_modules/`, `public/hot` — semuanya sudah ada di
`.gitignore`, jadi aman kalau dibagikan lewat Git. Jangan pernah commit `.env`.

Detail deploy ke server ada di [DEPLOYMENT.md](DEPLOYMENT.md).
