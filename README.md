# TECHSTOCK

Sistem inventaris dan peminjaman barang berbasis web. Admin mengelola stok,
unit barang, supplier, serta pengajuan peminjaman; pengunjung dapat melihat
katalog dan mengajukan peminjaman tanpa akun.

Dibuat dengan **Laravel 11**, **MariaDB/MySQL**, **Tailwind CSS**, **Alpine.js**,
dan **Chart.js**. Laporan dapat diunduh sebagai Excel (`.xlsx`).

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

Buka http://localhost:8000

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

`DB_PASSWORD` diisi kalau MySQL-mu memakai password. Jangan pakai `.env` milik
teman — nilainya berbeda di tiap komputer, dan `APP_KEY` harus milik sendiri.

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

Buka http://localhost:8000. Login admin memakai akun dari seeder (lihat
`database/seeders/UserSeeder.php`), lalu ganti password.

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
git status --short    # pastikan .env TIDAK muncul
git commit -m "fitur: ..."
git push
```

Saat mengubah tampilan, jalankan `npm run dev` di terminal kedua.

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

Akun admin awal dibuat oleh seeder. Lihat `database/seeders/UserSeeder.php`
untuk emailnya, lalu **segera ganti password** setelah login pertama.

Saat mengembangkan tampilan, jalankan `npm run dev` di terminal terpisah.
Jangan bagikan link aplikasi selama `npm run dev` menyala: file `public/hot`
membuat browser mengambil CSS/JS dari `localhost:5173`, sehingga tampilan
rusak di komputer lain. Tutup dev server lalu jalankan `npm run build`.

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

## Keamanan

- File `.env` berisi kredensial dan **tidak boleh** di-commit. Gunakan
  `.env.example` sebagai template.
- Di server produksi: `APP_ENV=production`, `APP_DEBUG=false`, dan `APP_KEY`
  hasil generate sendiri.
- Ganti password admin bawaan sebelum aplikasi dipakai.
- Dokumen identitas dan bukti pembayaran disimpan pada disk `local`
  (tidak dapat diakses langsung dari browser).
- Akses file dokumen pengguna dilindungi middleware admin.

## Deployment

Panduan lengkap untuk VPS/Nginx ada di [DEPLOYMENT.md](DEPLOYMENT.md).

## Troubleshooting

| Gejala | Penyebab umum |
|---|---|
| Halaman tampil tanpa CSS/JS | `public/hot` masih ada, atau `npm run build` belum dijalankan |
| Error 500 setelah upload | `storage/` dan `bootstrap/cache/` tidak writable |
| `Connection refused` / SQLSTATE[HY000] | MySQL belum jalan atau `DB_PORT` salah |
| Error ekstensi saat `composer install` | `zip`, `gd`, atau `dom` belum aktif di PHP |
