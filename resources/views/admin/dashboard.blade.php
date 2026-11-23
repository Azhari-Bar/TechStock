@extends('layouts.admin')

@section('title', 'Dashboard')
@section('heading', 'Dashboard Inventaris Gudang')

@section('content')
<div class="mx-auto max-w-7xl space-y-8">
    <section class="rounded-3xl bg-gradient-to-r from-indigo-700 via-indigo-600 to-violet-600 p-7 text-white shadow-lg sm:p-9">
        <p class="text-sm font-semibold uppercase tracking-wider text-indigo-100">TECHSTOCK · DASHBOARD</p>
        <h1 class="mt-2 text-3xl font-bold tracking-tight">Ringkasan Inventaris & Peminjaman</h1>
        <p class="mt-2 max-w-2xl text-indigo-100">
            Pantau stok, kondisi unit, dan aktivitas peminjaman secara real-time.
        </p>
    </section>

    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-6 animate-slide-up" style="animation-delay: 0.1s">
        <div class="rounded-2xl border border-indigo-100 bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <p class="text-sm font-medium text-slate-500">Produk Aktif</p>
            <p class="mt-2 text-3xl font-bold text-indigo-700">{{ $activeProducts }}</p>
            <p class="mt-1 text-xs text-slate-400">dari {{ $totalProducts }} produk terdaftar</p>
        </div>
        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <p class="text-sm font-medium text-emerald-800">Unit Tersedia</p>
            <p class="mt-2 text-3xl font-bold text-emerald-700">{{ $availableUnits }}</p>
            <p class="mt-1 text-xs text-emerald-700/70">dari {{ $totalUnits }} unit total</p>
        </div>
        <div class="rounded-2xl border border-sky-100 bg-sky-50 p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <p class="text-sm font-medium text-sky-800">Sedang Dipinjam</p>
            <p class="mt-2 text-3xl font-bold text-sky-700">{{ $rentedUnits }}</p>
            <p class="mt-1 text-xs text-sky-700/70">unit keluar gudang</p>
        </div>
        <div class="rounded-2xl border border-amber-100 bg-amber-50 p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <p class="text-sm font-medium text-amber-800">Menunggu Tinjauan</p>
            <p class="mt-2 text-3xl font-bold text-amber-700">{{ $pendingRentals }}</p>
            <p class="mt-1 text-xs text-amber-700/70">pengajuan perlu diproses</p>
        </div>
        <div class="rounded-2xl border border-rose-100 bg-rose-50 p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <p class="text-sm font-medium text-rose-700">Perlu Perhatian</p>
            <p class="mt-2 text-3xl font-bold text-rose-700">{{ $overdueRentals + $serviceUnits + $damagedUnits + $lostUnits }}</p>
            <p class="mt-1 text-xs text-rose-700/70">terlambat / servis / rusak / hilang</p>
        </div>
        <div class="rounded-2xl border border-violet-100 bg-violet-50 p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <p class="text-sm font-medium text-violet-800">Peminjaman Selesai</p>
            <p class="mt-2 text-3xl font-bold text-violet-700">{{ $completedRentals }}</p>
            <p class="mt-1 text-xs text-violet-700/70">transaksi lengkap</p>
        </div>
    </section>

    <section class="grid gap-6 xl:grid-cols-3 animate-slide-up" style="animation-delay: 0.2s">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm xl:col-span-1">
            <h2 class="text-lg font-bold text-slate-900">Status Unit Inventaris</h2>
            <p class="text-sm text-slate-500 mb-4">Distribusi kondisi unit di gudang.</p>
            <div class="h-72"><canvas id="unitStatusChart"></canvas></div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm xl:col-span-2">
            <h2 class="text-lg font-bold text-slate-900">Tren Peminjaman 6 Bulan</h2>
            <p class="text-sm text-slate-500 mb-4">Jumlah pengajuan peminjaman per bulan.</p>
            <div class="h-72"><canvas id="rentalTrendChart"></canvas></div>
        </div>
    </section>

    <section class="grid gap-6 xl:grid-cols-3 animate-slide-up" style="animation-delay: 0.25s">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm xl:col-span-2">
            <h2 class="text-lg font-bold text-slate-900">Inventaris per Kategori</h2>
            <p class="text-sm text-slate-500 mb-4">Jumlah produk dan unit fisik per kategori.</p>
            <div class="h-80"><canvas id="categoryChart"></canvas></div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-bold text-slate-900">Ringkasan Cepat</h2>
            <div class="mt-4 space-y-3 text-sm">
                <div class="flex justify-between rounded-lg bg-slate-50 px-3 py-2"><span>Total Produk</span><span class="font-bold">{{ $totalProducts }}</span></div>
                <div class="flex justify-between rounded-lg bg-emerald-50 px-3 py-2"><span>Total Unit</span><span class="font-bold text-emerald-700">{{ $totalUnits }}</span></div>
                <div class="flex justify-between rounded-lg bg-indigo-50 px-3 py-2"><span>Tersedia</span><span class="font-bold text-indigo-700">{{ $availableUnits }}</span></div>
                <div class="flex justify-between rounded-lg bg-sky-50 px-3 py-2"><span>Dipinjam</span><span class="font-bold text-sky-700">{{ $rentedUnits }}</span></div>
                <div class="flex justify-between rounded-lg bg-amber-50 px-3 py-2"><span>Dipesan</span><span class="font-bold text-amber-700">{{ $reservedUnits }}</span></div>
                <div class="flex justify-between rounded-lg bg-orange-50 px-3 py-2"><span>Servis</span><span class="font-bold text-orange-700">{{ $serviceUnits }}</span></div>
                <div class="flex justify-between rounded-lg bg-red-50 px-3 py-2"><span>Rusak/Hilang</span><span class="font-bold text-red-700">{{ $damagedUnits + $lostUnits }}</span></div>
                <div class="flex justify-between rounded-lg bg-purple-50 px-3 py-2"><span>Peminjaman Aktif</span><span class="font-bold text-purple-700">{{ $activeRentals }}</span></div>
                <div class="flex justify-between rounded-lg bg-teal-50 px-3 py-2"><span>Menunggu Tinjauan</span><span class="font-bold text-teal-700">{{ $pendingRentals }}</span></div>
                <div class="flex justify-between rounded-lg bg-rose-50 px-3 py-2"><span>Terlambat</span><span class="font-bold text-rose-700">{{ $overdueRentals }}</span></div>
            </div>
        </div>
    </section>

    <section class="grid gap-6 lg:grid-cols-2 animate-slide-up" style="animation-delay: 0.3s">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-5 flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Aktivitas Peminjaman Terbaru</h2>
                    <p class="text-sm text-slate-500">Pengajuan terbaru yang masuk.</p>
                </div>
                <a href="{{ route('admin.rentals.index') }}" class="rounded-xl bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-700 hover:bg-indigo-100">Lihat semua</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($recentRentals as $rental)
                    <a href="{{ route('admin.rentals.show', $rental) }}" class="block py-3 hover:bg-slate-50 -mx-2 px-2 rounded-lg transition">
                        <div class="flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <p class="truncate font-semibold text-slate-800">{{ $rental->product?->name ?? 'Produk dihapus' }}</p>
                                <p class="text-xs text-slate-500">{{ $rental->customer_name }} · {{ $rental->reference_number }}</p>
                            </div>
                            <span class="shrink-0 rounded-full bg-{{ $rental->status->color() }}-100 px-3 py-1 text-xs font-semibold text-{{ $rental->status->color() }}-800">{{ $rental->status->label() }}</span>
                        </div>
                    </a>
                @empty
                    <p class="py-8 text-center text-sm text-slate-500">Belum ada pengajuan peminjaman.</p>
                @endforelse
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-5 flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Riwayat Pergerakan Terbaru</h2>
                    <p class="text-sm text-slate-500">Aktivitas stok/unit terbaru.</p>
                </div>
                <a href="{{ route('admin.stock-movements.index') }}" class="rounded-xl bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700 hover:bg-emerald-100">Lihat semua</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($recentMovements as $movement)
                    <div class="py-3">
                        <p class="font-semibold text-slate-800">{{ $movement->product?->name ?? '-' }}</p>
                        <p class="text-xs text-slate-500">{{ $movement->movement_type?->label() ?? $movement->movement_type }} · {{ $movement->unit?->serial_number ?? 'Tanpa unit' }} · {{ optional($movement->created_at)->format('d M Y H:i') }}</p>
                    </div>
                @empty
                    <p class="py-8 text-center text-sm text-slate-500">Belum ada pergerakan stok.</p>
                @endforelse
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const unitStatusChart = document.getElementById('unitStatusChart');
    if (unitStatusChart) {
        new Chart(unitStatusChart, {
            type: 'doughnut',
            data: {
                labels: ['Tersedia', 'Dipinjam', 'Dipesan', 'Servis', 'Rusak', 'Hilang'],
                datasets: [{
                    data: [{{ $availableUnits }}, {{ $rentedUnits }}, {{ $reservedUnits }}, {{ $serviceUnits }}, {{ $damagedUnits }}, {{ $lostUnits }}],
                    backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#f97316', '#ef4444', '#64748b'],
                    borderWidth: 0
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
        });
    }

    const rentalTrendChart = document.getElementById('rentalTrendChart');
    if (rentalTrendChart) {
        new Chart(rentalTrendChart, {
            type: 'line',
            data: {
                labels: @json($monthlyRentals->pluck('label')),
                datasets: [{
                    label: 'Pengajuan Peminjaman',
                    data: @json($monthlyRentals->pluck('total')),
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99,102,241,0.15)',
                    fill: true,
                    tension: 0.35
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
        });
    }

    const categoryChart = document.getElementById('categoryChart');
    if (categoryChart) {
        new Chart(categoryChart, {
            type: 'bar',
            data: {
                labels: @json($categoryStats->pluck('name')),
                datasets: [
                    { label: 'Produk', data: @json($categoryStats->pluck('products_count')), backgroundColor: '#8b5cf6' },
                    { label: 'Unit', data: @json($categoryStats->pluck('units_count')), backgroundColor: '#06b6d4' }
                ]
            },
            options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
        });
    }
});
</script>
@endsection
