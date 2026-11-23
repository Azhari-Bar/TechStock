@extends('layouts.admin')

@section('title', 'Ringkasan & Laporan')
@section('heading', 'Ringkasan & Laporan')

@section('content')
<div class="space-y-8">
    <section class="rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-900 to-violet-800 px-6 py-8 text-white shadow-xl sm:px-10 sm:py-10">
        <div class="max-w-3xl">
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-indigo-200">TECHSTOCK Admin</p>
            <h2 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">Ringkasan Operasional & Laporan</h2>
            <p class="mt-3 text-sm leading-relaxed text-indigo-100 sm:text-base">
                Pantau inventaris stok gudang, pergerakan barang, serta peminjaman unit. Seluruh data laporan diambil dari aktivitas inventaris yang tercatat di sistem.
            </p>
        </div>
    </section>

    <section>
        <div class="mb-4 flex items-end justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Statistik Singkat</h3>
                <p class="text-sm text-slate-500">Gambaran kondisi inventaris dan peminjaman saat ini.</p>
            </div>
        </div>
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-2xl border border-emerald-100 bg-emerald-50/70 p-5 shadow-sm ring-1 ring-emerald-100">
                    <p class="text-sm font-medium text-emerald-900">Total Barang Masuk</p>
                    <p class="mt-3 text-3xl font-extrabold text-emerald-700">{{ $stats['total_stock_in'] ?? 0 }}</p>
                    <p class="mt-1 text-xs text-emerald-800/70">unit diterima dari supplier</p>
                </div>
                <div class="rounded-2xl border border-indigo-100 bg-white p-5 shadow-sm ring-1 ring-indigo-50">
                    <p class="text-sm font-medium text-slate-500">Jenis Inventaris</p>
                    <p class="mt-3 text-3xl font-extrabold text-indigo-700">{{ $stats['active_products'] }}</p>
                    <p class="mt-1 text-xs text-slate-400">dari {{ $stats['total_products'] }} produk terdaftar</p>
                </div>
                <div class="rounded-2xl border border-amber-100 bg-amber-50/70 p-5 shadow-sm ring-1 ring-amber-100">
                    <p class="text-sm font-medium text-amber-900">Menunggu Tinjauan</p>
                    <p class="mt-3 text-3xl font-extrabold text-amber-700">{{ $stats['pending_rentals'] }}</p>
                    <p class="mt-1 text-xs text-amber-800/70">pengajuan perlu diproses</p>
                </div>
                <div class="rounded-2xl border border-sky-100 bg-sky-50/70 p-5 shadow-sm ring-1 ring-sky-100">
                    <p class="text-sm font-medium text-sky-900">Peminjaman Berjalan</p>
                    <p class="mt-3 text-3xl font-extrabold text-sky-700">{{ $stats['active_rentals'] }}</p>
                    <p class="mt-1 text-xs text-sky-800/70">dipinjam / proses pengembalian</p>
                </div>
                <div class="rounded-2xl border border-rose-100 bg-rose-50/70 p-5 shadow-sm ring-1 ring-rose-100">
                    <p class="text-sm font-medium text-rose-900">Peminjaman Selesai</p>
                    <p class="mt-3 text-3xl font-extrabold text-rose-700">{{ $stats['completed_rentals'] }}</p>
                    <p class="mt-1 text-xs text-rose-800/70">transaksi peminjaman lengkap</p>
                </div>
                <div class="rounded-2xl border border-violet-100 bg-violet-50/70 p-5 shadow-sm ring-1 ring-violet-100">
                    <p class="text-sm font-medium text-violet-900">Total Pergerakan</p>
                    <p class="mt-3 text-3xl font-extrabold text-violet-700">{{ $stats['total_movements'] }}</p>
                    <p class="mt-1 text-xs text-violet-800/70">aktivitas stok tercatat</p>
                </div>
                <div class="rounded-2xl border border-teal-100 bg-teal-50/70 p-5 shadow-sm ring-1 ring-teal-100">
                    <p class="text-sm font-medium text-teal-900">Barang Keluar</p>
                    <p class="mt-3 text-3xl font-extrabold text-teal-700">{{ $stats['total_stock_out'] ?? 0 }}</p>
                    <p class="mt-1 text-xs text-teal-800/70">unit keluar gudang</p>
                </div>
            </div>
    </section>

    <section class="grid gap-6 xl:grid-cols-[1.15fr_0.85fr]">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Pengajuan Peminjaman Terbaru</h3>
                    <p class="text-sm text-slate-500">Daftar 8 pengajuan terakhir yang masuk ke sistem.</p>
                </div>
                <a href="{{ route('admin.rentals.index') }}" class="inline-flex items-center rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-700 transition hover:bg-indigo-100">
                    Kelola Peminjaman
                </a>
            </div>
            <div class="overflow-hidden rounded-xl border border-slate-100">
                <div class="divide-y divide-slate-100">
                    @forelse($recentRentals as $rental)
                        <a href="{{ route('admin.rentals.show', $rental) }}" class="flex items-center justify-between gap-4 px-4 py-4 transition hover:bg-slate-50 sm:px-5">
                            <div class="min-w-0 flex-1">
                                <p class="truncate font-semibold text-slate-800">{{ $rental->product?->name ?? 'Produk' }}</p>
                                <p class="mt-1 text-xs text-slate-500 sm:text-sm">
                                    <span class="font-medium text-slate-600">{{ $rental->customer_name }}</span>
                                    <span class="mx-1.5 text-slate-300">&middot;</span>
                                    {{ $rental->reference_number }}
                                    <span class="mx-1.5 text-slate-300">&middot;</span>
                                    {{ optional($rental->start_date)->format('d M Y') }} – {{ optional($rental->due_date)->format('d M Y') }}
                                </p>
                            </div>
                    <span class="shrink-0 rounded-full bg-{{ $rental->status->color() }}-100 px-3 py-1.5 text-xs font-semibold text-{{ $rental->status->color() }}-800 ring-1 ring-inset ring-{{ $rental->status->color() }}-200">
                                {{ $rental->status->label() }}
                            </span>
                        </a>
                    @empty
                        <div class="px-5 py-12 text-center">
                            <p class="text-sm font-medium text-slate-600">Belum ada pengajuan peminjaman.</p>
                            <p class="mt-1 text-xs text-slate-400">Data akan muncul setelah pengaju mengirim formulir peminjaman.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-bold text-slate-900">Cetak / Ekspor Laporan Excel</h3>
                <p class="mt-1 text-sm leading-relaxed text-slate-500">
                    Unduh laporan operasional dalam format Excel (.xlsx) untuk arsip dan rekap internal.
                </p>

                <div class="mt-5 space-y-4">

                    <div class="rounded-xl border border-emerald-100 bg-gradient-to-br from-emerald-50 to-white p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="font-semibold text-slate-900">Laporan Peminjaman</p>
                                <p class="mt-1 text-sm text-slate-600">Semua data pengajuan peminjaman: penyewa, produk, periode, tanggal, status, estimasi biaya, dan deposit.</p>
                            </div>
                            <span class="shrink-0 rounded-lg bg-emerald-100 px-2.5 py-1 text-xs font-bold text-emerald-700">.XLSX</span>
                        </div>
                        <div class="mt-4 flex flex-wrap items-center gap-2">
                            <a href="{{ route('admin.reports.export.rentals') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Unduh Laporan Peminjaman
                            </a>
                            <select data-export="{{ route('admin.reports.export.rentals') }}" class="rounded-lg border-slate-300 text-sm">
                                <option value="">Semua Periode</option>
                                <option value="year">Tahun Ini</option>
                                <option value="month">Bulan Ini</option>
                                <option value="week">Minggu Ini</option>
                            </select>
                        </div>
                        <p class="mt-3 text-xs text-slate-500">Filter berdasarkan tanggal pengajuan peminjaman dibuat.</p>
                    </div>

                    <div class="rounded-xl border border-indigo-100 bg-gradient-to-br from-indigo-50 to-white p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="font-semibold text-slate-900">Laporan Inventaris Gudang</p>
                                <p class="mt-1 text-sm text-slate-600">Daftar produk, kategori, merek, kondisi, harga beli/jual, tarif sewa, dan status peminjaman.</p>
                            </div>
                            <span class="shrink-0 rounded-lg bg-indigo-100 px-2.5 py-1 text-xs font-bold text-indigo-700">.XLSX</span>
                        </div>
                        
                        <div class="mt-4 flex flex-wrap gap-2 items-center">
                            <a href="{{ route('admin.reports.export.products') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Unduh Laporan Inventaris
                            </a>
                            <select data-export="{{ route('admin.reports.export.products') }}" class="rounded-lg border-slate-300 text-sm">
                                <option value="">Semua Periode</option>
                                <option value="year">Tahun Ini</option>
                                <option value="month">Bulan Ini</option>
                                <option value="week">Minggu Ini</option>
                            </select>
                        </div>
                        <p class="mt-3 text-xs text-slate-500">Filter berdasarkan waktu pembuatan produk. Pilih periode untuk melihat rekap sesuai rentang tanggal.</p>
                    </div>

                    <div class="rounded-xl border border-amber-100 bg-gradient-to-br from-amber-50 to-white p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="font-semibold text-slate-900">Laporan Unit Inventaris</p>
                                <p class="mt-1 text-sm text-slate-600">Rekap unit barang: lokasi penyimpanan (ruangan/rak/tingkat/kotak), kondisi fisik, status (tersedia/kerusakan/service), dan harga beli.</p>
                            </div>
                            <span class="shrink-0 rounded-lg bg-amber-100 px-2.5 py-1 text-xs font-bold text-amber-700">.XLSX</span>
                        </div>
                        <div class="mt-4 flex flex-wrap gap-2 items-center">
                            <a href="{{ route('admin.reports.export.units') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-amber-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-700">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Unduh Laporan Unit
                            </a>
                            <select data-export="{{ route('admin.reports.export.units') }}" class="rounded-lg border-slate-300 text-sm">
                                <option value="">Semua Periode</option>
                                <option value="year">Tahun Ini</option>
                                <option value="month">Bulan Ini</option>
                                <option value="week">Minggu Ini</option>
                            </select>
                        </div>
                        <p class="mt-3 text-xs text-slate-500">Filter berdasarkan waktu unit ditambahkan ke sistem.</p>
                    </div>

                    <div class="rounded-xl border border-sky-100 bg-gradient-to-br from-sky-50 to-white p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="font-semibold text-slate-900">Laporan Pergerakan Stok</p>
                                <p class="mt-1 text-sm text-slate-600">Riwayat barang masuk/keluar, peminjaman, pengembalian, penyesuaian, supplier, dan biaya satuan per unit.</p>
                            </div>
                            <span class="shrink-0 rounded-lg bg-sky-100 px-2.5 py-1 text-xs font-bold text-sky-700">.XLSX</span>
                        </div>
                        <div class="mt-4 flex flex-wrap items-center gap-2">
                            <a href="{{ route('admin.reports.export.stock-movements') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-sky-700">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Unduh Laporan Stok
                            </a>
                            <select data-export="{{ route('admin.reports.export.stock-movements') }}" class="rounded-lg border-slate-300 text-sm">
                                <option value="">Semua Periode</option>
                                <option value="year">Tahun Ini</option>
                                <option value="month">Bulan Ini</option>
                                <option value="week">Minggu Ini</option>
                            </select>
                        </div>
                        <p class="mt-3 text-xs text-slate-500">Filter berdasarkan tanggal pergerakan stok dicatat.</p>
                    </div>
                </div>

                <script>
                    document.querySelectorAll('select[data-export]').forEach(function (select) {
                        select.addEventListener('change', function () {
                            if (!this.value) return;
                            // toLocaleDateString('en-CA') = YYYY-MM-DD waktu lokal, bukan UTC
                            const today = new Date().toLocaleDateString('en-CA');
                            window.location.href = this.dataset.export + '/' + this.value + '/' + today;
                        });
                    });
                </script>

                <div class="mt-4 rounded-xl border border-amber-100 bg-amber-50 px-4 py-3 text-sm text-amber-900">
                    <strong>Catatan:</strong> Sistem ini fokus pada manajemen inventaris dan peminjaman. Laporan stok, pergerakan barang, dan peminjaman merupakan rekap operasional utama.
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-bold text-slate-900">Alur Layanan</h3>
                <ol class="mt-4 space-y-3 text-sm text-slate-600">
                    <li class="flex gap-3"><span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-xs font-bold text-emerald-700">1</span><span>Admin mencatat barang masuk dari supplier dan mengelola stok gudang.</span></li>
                    <li class="flex gap-3"><span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-xs font-bold text-indigo-700">2</span><span>Pengaju mengirim permohonan peminjaman melalui katalog publik.</span></li>
                    <li class="flex gap-3"><span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-amber-100 text-xs font-bold text-amber-700">3</span><span>Admin meninjau, menyerahkan unit, dan mencatat pengembalian.</span></li>
                </ol>
            </div>
        </div>
    </section>

    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Pergerakan Stok Terbaru</h3>
                <p class="text-sm text-slate-500">Aktivitas barang masuk, keluar, dan penyesuaian terakhir.</p>
            </div>
            <a href="{{ route('admin.stock-movements.index') }}" class="inline-flex items-center rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-100">
                Lihat Semua Pergerakan
            </a>
        </div>
        <div class="overflow-x-auto rounded-xl border border-slate-100">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Produk</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Unit</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Jenis</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentMovements ?? [] as $movement)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 text-slate-500">{{ optional($movement->created_at)->format('d M Y H:i') }}</td>
                            <td class="px-4 py-3 font-medium text-slate-800">{{ $movement->product?->name ?? '-' }}</td>
                            <td class="px-4 py-3 font-mono text-slate-600">{{ $movement->unit?->serial_number ?? '-' }}</td>
                            <td class="px-4 py-3"><span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">{{ $movement->movement_type?->label() ?? $movement->movement_type }}</span></td>
                            <td class="px-4 py-3">{{ $movement->quantity }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-10 text-center text-slate-500">Belum ada pergerakan stok tercatat.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
