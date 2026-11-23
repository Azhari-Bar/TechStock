@extends('layouts.admin')

@section('title', 'Riwayat Stok')
@section('heading', 'Riwayat Pergerakan Stok')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards with Gradients -->
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl stat-card emerald">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-emerald-800">Barang Masuk</p>
                    <p class="mt-2 text-3xl font-bold text-emerald-700">{{ $stats['stock_in'] }}</p>
                    <p class="mt-1 text-xs text-emerald-700/70">Total unit ditambahkan</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl stat-card amber">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-amber-800">Barang Keluar</p>
                    <p class="mt-2 text-3xl font-bold text-amber-700">{{ $stats['stock_out'] }}</p>
                    <p class="mt-1 text-xs text-amber-700/70">Total unit dikeluarkan</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl stat-card indigo">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-indigo-800">Sedang Dipinjam</p>
                    <p class="mt-2 text-3xl font-bold text-indigo-700">{{ $stats['rental'] }}</p>
                    <p class="mt-1 text-xs text-indigo-700/70">Unit dalam proses peminjaman</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl stat-card sky">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-sky-800">Dikembalikan</p>
                    <p class="mt-2 text-3xl font-bold text-sky-700">{{ $stats['return'] }}</p>
                    <p class="mt-1 text-xs text-sky-700/70">Unit selesai dipinjam</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-sky-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters + Add -->
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <form method="GET" class="flex flex-wrap items-center gap-2">
            <input name="q" value="{{ request('q') }}" placeholder="Cari produk / serial..." class="rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
            <select name="type" class="rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                <option value="">Semua tipe</option>
                @foreach(\App\Enums\MovementType::cases() as $mt)
                    <option value="{{ $mt->value }}" @selected(request('type') === $mt->value)>{{ $mt->label() }}</option>
                @endforeach
            </select>
            <select name="product" class="rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                <option value="">Semua produk</option>
                @foreach($products as $p)
                    <option value="{{ $p->id }}" @selected(request('product') == $p->id)>{{ $p->name }}</option>
                @endforeach
            </select>
            <button class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition">Filter</button>
        </form>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.reports.export.stock-movements') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-emerald-300 bg-white px-4 py-2 text-sm font-medium text-emerald-700 hover:bg-emerald-50 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Ekspor Excel
            </a>
            <a href="{{ route('admin.stock-movements.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Catat Pergerakan
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto rounded-2xl admin-card">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50/80">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500 tracking-wider">Jenis</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500 tracking-wider">Produk</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500 tracking-wider">Unit</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500 tracking-wider">Jml</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500 tracking-wider">Supplier</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500 tracking-wider">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500 tracking-wider">Oleh</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500 tracking-wider">Tanggal</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-slate-500 tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200/60 bg-white/60">
                @forelse($movements as $m)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-{{ $m->movement_type->color() }}-100 px-2.5 py-1 text-xs font-semibold text-{{ $m->movement_type->color() }}-800 ring-1 ring-inset ring-{{ $m->movement_type->color() }}-200">
                                {{ $m->movement_type->label() }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm font-medium text-slate-900">{{ $m->product?->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm font-mono text-slate-600">{{ $m->unit?->serial_number ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm font-semibold text-slate-900">{{ $m->quantity }}</td>
                        <td class="px-4 py-3 text-sm text-slate-600">{{ $m->supplier?->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-xs text-slate-600">{{ $m->status_before ? $m->status_before.' → '.$m->status_after : '-' }}</td>
                        <td class="px-4 py-3 text-sm text-slate-600">{{ $m->user?->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-slate-500">{{ optional($m->created_at)->format('d M Y H:i') }}</td>
                        <td class="px-4 py-3 text-right text-sm">
                            <a href="{{ route('admin.stock-movements.show', $m) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-16 text-center">
                            <div class="flex flex-col items-center gap-3 text-slate-500">
                                <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                <p class="text-sm font-medium">Belum ada riwayat pergerakan stok.</p>
                                <p class="text-xs">Catat pergerakan pertama Anda dengan tombol di atas.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="border-t border-slate-200 px-4 py-3 bg-white/80">
            {{ $movements->links() }}
        </div>
    </div>
</div>
@endsection
