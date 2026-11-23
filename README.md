# TECHSTOCK

**Sistem inventaris dan peminjaman barang berbasis web** — dibuat untuk
menggantikan pencatatan stok dan peminjaman barang yang masih manual
(spreadsheet/buku), sehingga stok, unit barang, dan riwayat peminjaman tercatat
rapi di satu tempat.

## Deskripsi

TECHSTOCK dipakai oleh dua jenis pengguna:

- **Pengunjung** — membuka katalog, mencari barang, lalu mengajukan peminjaman
  tanpa perlu membuat akun. Setelah mengirim pengajuan, mereka mendapat nomor
  referensi untuk memantau status dan mengunggah bukti pembayaran.
- **Admin** — mengelola data barang (kategori, merek, produk, unit), mencatat
  barang masuk dari supplier, menyetujui atau menolak pengajuan, mencatat serah
  terima dan pengembalian unit, menghitung denda keterlambatan, serta mengunduh
  laporan dalam format Excel.

Alur utamanya: **barang masuk → stok tercatat → pengajuan peminjaman → disetujui
→ unit diserahkan → dikembalikan → stok kembali**, dan setiap perpindahan unit
tercatat sebagai riwayat pergerakan stok.

## Teknologi yang Digunakan

| Bagian | Teknologi |
|---|---|
| Bahasa pemrograman | PHP 8.2+ |
| Framework backend | Laravel 11 (MVC, Eloquent ORM, Blade) |
| Database | MariaDB 10.4+ / MySQL 8+ (SQL, diakses lewat PDO) |
| Tampilan | Blade + Tailwind CSS 3 |
| Interaksi di browser | Alpine.js, Chart.js (grafik dashboard) |
| Build asset | Vite 5 |
| Laporan Excel | maatwebsite/excel (PhpSpreadsheet) |
| Laporan PDF | barryvdh/laravel-dompdf |
| Autentikasi | Laravel Breeze |
| Pengujian | PHPUnit 10 |
| Manajemen dependency | Composer (PHP) dan npm (JavaScript) |
| Version control | Git / GitHub |

## Fitur

**Publik (tanpa login)**
- Katalog produk dengan pencarian dan filter kategori/merek
- Form pengajuan peminjaman
- Cek status pengajuan lewat nomor referensi
- Unggah bukti pembayaran

**Admin**
- Dashboard ringkasan stok, peminjaman aktif, dan pergerakan terbaru
- CRUD kategori, merek, produk, unit barang, dan supplier
- Persetujuan peminjaman, serah terima unit, dan pencatatan pengembalian
- Pencatatan denda keterlambatan dan deposit
- Pergerakan stok (barang masuk/keluar, penyesuaian) beserta riwayatnya
- Laporan Excel: peminjaman, inventaris gudang, unit inventaris, dan
  pergerakan stok — masing-masing bisa difilter per tahun/bulan/minggu
- Penandaan peminjaman terlambat otomatis lewat scheduler harian

## Prasyarat

- PHP 8.2+ dengan ekstensi `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`,
  `zip`, `gd`, `dom`
- Composer 2
- Node.js 20+ dan npm
- MariaDB 10.4+ atau MySQL 8+

## Instalasi

> Baru pertama kali menjalankan proyek ini di komputer sendiri? Langsung ke
> bagian [Menjalankan di Komputer Lain](#menjalankan-di-komputer-lain-anggota-kelompok)
> — langkahnya lebih lengkap dan berurutan.

```bash
composer install
npm install
cp .env.example .env          # Windows: copy .env.example .env
php artisan key:generate
```

Sesuaikan koneksi database di `.env` (nama database, user, password, dan port
sesuai MySQL di komputer masing-masing — XAMPP/Laragon umumnya `3306`):

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=techstock
DB_USERNAME=...
DB_PASSWORD=...
```

Buat database kosong terlebih dahulu, lalu isi struktur tabel dan data contoh:

```bash
php artisan migrate --seed
php artisan storage:link
npm run build
```

## Menjalankan

```bash
php artisan serve
```

Buka http://localhost:8000. Akun admin awal dibuat oleh seeder (lihat
`database/seeders/UserSeeder.php`), lalu ganti password setelah login pertama.

Saat mengembangkan tampilan, jalankan `npm run dev` di terminal terpisah.
Setelah selesai mengubah tampilan, jalankan `npm run build` supaya hasilnya
tersimpan di `public/build`.

## Menjalankan di Komputer Lain (Anggota Kelompok)

Bagian ini untuk anggota yang baru pertama kali menjalankan proyek ini di
komputernya sendiri. Ikuti berurutan; jangan lewati langkah database.

### 1. Pasang aplikasi pendukung

| Kebutuhan | Cara termudah (Windows) |
|---|---|
| PHP 8.2+ | Ikut di XAMPP/Laragon, atau unduh dari php.net lalu tambahkan ke `PATH` |
| MySQL/MariaDB | XAMPP atau Laragon (Start → MySQL) |
| Composer | https://getcomposer.org/download/ |
| Node.js 20+ | https://nodejs.org (pilih LTS) |
| Git | https://git-scm.com/download/win |

Cek semuanya sudah terbaca:

```bash
php -v
composer -V
node -v
npm -v
git --version
```

Kalau muncul "not recognized", tutup terminal, buka lagi. Kalau masih, tambahkan
folder `bin` PHP/Composer ke Environment Variables → `Path`.

### 2. Ambil kode proyek

```bash
git clone https://github.com/Azhari-Bar/TechStock.git
cd TechStock
```

Butuh akses: minta pemilik repo mengundangmu lewat
**Settings → Collaborators**, lalu terima email undangannya.

### 3. Siapkan database

1. Jalankan MySQL (XAMPP/Laragon → Start MySQL)
2. Buka http://localhost/phpmyadmin
3. Tab **Databases** → nama: `techstock` → **Create**
4. Catat user & password MySQL-mu (default XAMPP: user `root`, password kosong)

### 4. Konfigurasi `.env`

```bash
copy .env.example .env
php artisan key:generate
```

Buka `.env`, sesuaikan bagian ini dengan MySQL di komputermu:

```dotenv
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=techstock
DB_USERNAME=root
DB_PASSWORD=
```

`DB_PASSWORD` diisi kalau MySQL-mu memakai password. Setiap komputer memakai
`.env` sendiri dengan `APP_KEY` hasil generate sendiri, karena nilainya berbeda
di tiap instalasi.

### 5. Pasang dependency dan isi data

```bash
composer install
npm install
php artisan migrate --seed
php artisan storage:link
npm run build
```

### 6. Jalankan

```bash
php artisan serve
```

Buka http://localhost:8000, lalu login memakai akun admin dari seeder.

### 7. Rutinitas kerja harian

Sebelum mulai mengerjakan apa pun:

```bash
git pull
composer install      # kalau composer.json berubah
npm install           # kalau package.json berubah
php artisan migrate   # kalau ada migration baru
```

Setelah selesai mengubah kode:

```bash
git add .
git status --short    # cek file yang akan di-commit
git commit -m "fitur: ..."
git push
```

### Masalah yang sering muncul

| Gejala | Solusi |
|---|---|
| `php`/`composer` tidak dikenali | Buka terminal baru, atau tambahkan ke `Path` |
| `SQLSTATE[HY000] [2002]` | MySQL belum di-start, atau `DB_PORT` salah |
| `Access denied for user` | `DB_USERNAME`/`DB_PASSWORD` tidak cocok |
| `Unknown database 'techstock'` | Database belum dibuat di phpMyAdmin |
| Halaman tanpa CSS/JS | Jalankan `npm run build`, hapus `public/hot` |
| Error 500 / blank | Cek `storage/logs/laravel.log`, pastikan `php artisan key:generate` sudah dijalankan |
| `Class "..." not found` | `composer install` belum dijalankan |
| `Vite manifest not found` | `npm run build` belum dijalankan |

## Pengujian

```bash
php artisan test
```

## Struktur Singkat

```
app/Http/Controllers/Admin/   controller area admin
app/Exports/                  laporan Excel
app/Models/                   model Eloquent
database/migrations/          struktur tabel
database/seeders/             data contoh
resources/views/              tampilan Blade
routes/web.php                rute publik
routes/admin.php              rute admin
```

## Deployment

Panduan lengkap untuk VPS/Nginx ada di [DEPLOYMENT.md](DEPLOYMENT.md).
