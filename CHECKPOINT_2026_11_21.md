# Techstock — Checkpoint 2026-11-21 (Supplier + Stock Movement)

## Yang Baru Ditambahkan
1. **Tabel `suppliers`** (`database/migrations/2026_11_21_000004_create_suppliers_and_stock_tracking.php`)
   - Kolom: id, name, slug(unique), contact_person, phone, email, address, notes, is_active, timestamps, soft_deletes.
2. **Kolom tambahan `stock_movements`**: supplier_id, reference_number, unit_price, total_amount.
3. **Model baru** `app/Models/Supplier.php`.
4. **Enum `MovementType` diperluas** jadi: `StockIn, StockOut, Sale, Rental, RentalReturn, Adjustment, Damaged, Lost`.
5. **Controller & view Supplier CRUD** lengkap (index/create/store/show/edit/update/destroy).
6. **Controller & view Stock Movement** lengkap (index/create/store/show) dengan catatan penting membedakan barang masuk vs alur peminjaman.
7. **Menu admin** ditambahkan "Supplier" dan "Riwayat Stok".
8. **Seeder** `SupplierSeeder` + dicatatkan di `DatabaseSeeder`.
9. **UI**: background motif SVG + gradasi radial pada `app.css`, gradient stat cards, warning box di form stok.

## Status DB
```
php artisan migrate:fresh --seed
```
Lulus + seed berhasil: user, supplier(5 record), produk(unit) contoh.

## Status Test
```
php artisan test
```
Hasil: **50 deprecated, 1 passed (135 assertions), 0 failed**.
Deprecation hanya dari PDO PHP 8.5, bukan error bisnis.

## Hal yang Masih Perlu Dilakukan Sebelum Dinyatakan Siap Produksi
### A. Integrasi Peminjaman ↔ Stock Movement — SELESAI
- Workflow rental tetap mencatat movement sendiri ketika approve/handover/return/cancel.
- Form manual hanya menerima `stock_in`, `stock_out`, `damaged`, `lost`, dan `adjustment`.
- `rental` dan `rental_return` tidak dapat dikirim lewat form manual.
- Unit pada rental aktif (`disetujui`, `dipinjam`, `menunggu_pengembalian`, `terlambat`) dilewati oleh pencatatan manual agar status tidak tertimpa.

### B. Export Excel Laporan Stok — SELESAI
- `app/Exports/StockMovementExport.php` dibuat.
- Route `admin.reports.export.stock-movements` dan tombol export di halaman Riwayat Stok + Laporan dibuat.

### C. Validasi Quantity vs Serial Count
- Di `store()`: jika user isi `quantity=N` tapi juga kosongkan `serial_numbers_text`, sistem membuat N unit dengan serial auto.
- Sebaliknya jika isi serial count=M tapi quantity berbeda, yang dipakai adalah count serial (kode sekarang). Ini perlu dokumentasi/form helper text lebih jelas, atau dikunci satu-satunya input dominan.

### D. Role & Permission Detail
- Supplier CRUD opsinya open untuk semua auth admin. Jika nanti ada role level (kasir/gudang/admin), perlu middleware permission tambahan.

### E. Smoke Test Manual yang Belum Diverifikasi
Karena environment test CLI terbatas, urutannya harus dicek manual via browser sekali lagi:
1. Login admin → Dashboard
2. Buka **Supplier** → tambah baru → edit → lihat detail → hapus
3. Buka **Riwayat Stok** → klik **Catat Pergerakan**
4. Pilih produk "SSD Samsung 980", supplier "PT Sumber Komputer Jaya", isi harga satuan, simpan
5. Cek tabel `product_units` apakah unit baru masuk `status = available`
6. Cek `stock_movements` apakah entri tercatat
7. Coba catat ulang "Barang Keluar / Rusak / Hilang" → pastikan status berubah
8. Buka menu **Peminjaman** lalu buat rental baru sampai selesai (approve → handover → return)
9. Bandingkan: status unit dari alur rental tetap berjalan normal tanpa konflik dengan riwayat stok manual.

## Catatan Penting
- Jangan gunakan form manual untuk operasional peminjaman/pengembalian rutin — gunakan menu Rental.
- Field `slug` supplier dibuat random append agar unik, tapi tidak tampil di UI publik.
- CSS terbaru (gradasi + SVG pattern) sudah di-compile lewat `npm run build`.
