@extends('layouts.admin')

@section('title', $product->name)
@section('heading', 'Detail Produk')

@section('content')
<div class="mx-auto max-w-6xl space-y-6 animate-slide-up">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <a href="{{ route('admin.products.index') }}" class="text-sm font-medium text-slate-500 transition hover:text-indigo-600">← Kembali ke Produk</a>
        <div class="flex gap-2">
            <a href="{{ route('admin.products.edit', $product) }}" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 active:scale-95">Ubah Produk</a>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 transition hover:shadow-md lg:col-span-2">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">{{ $product->name }}
                        @if($product->model)<span class="font-normal text-slate-500">{{ $product->model }}</span>@endif
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">{{ $product->category?->name }} · {{ $product->brand?->name ?? 'Tanpa merek' }}</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $product->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">{{ $product->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $product->is_borrowable ? 'bg-indigo-100 text-indigo-700' : 'bg-amber-100 text-amber-700' }}">{{ $product->is_borrowable ? 'Dapat Dipinjam' : 'Tidak Dipinjamkan' }}</span>
                </div>
            </div>

            <dl class="mt-6 grid grid-cols-2 gap-4 text-sm md:grid-cols-3">
                <div><dt class="text-slate-500">Kondisi</dt><dd class="font-medium">{{ $product->condition->label() }}</dd></div>
                <div><dt class="text-slate-500">Harga Beli</dt><dd class="font-medium">Rp {{ number_format($product->purchase_price ?? 0, 0, ',', '.') }}</dd></div>
                <div><dt class="text-slate-500">Garansi</dt><dd class="font-medium">{{ $product->warranty_period ?? '-' }}</dd></div>
                <div><dt class="text-slate-500">Sewa Harian</dt><dd class="font-medium">Rp {{ number_format($product->rental_daily_rate ?? 0, 0, ',', '.') }}</dd></div>
                <div><dt class="text-slate-500">Sewa Mingguan</dt><dd class="font-medium">Rp {{ number_format($product->rental_weekly_rate ?? 0, 0, ',', '.') }}</dd></div>
                <div><dt class="text-slate-500">Sewa Bulanan</dt><dd class="font-medium">Rp {{ number_format($product->rental_monthly_rate ?? 0, 0, ',', '.') }}</dd></div>
                <div class="md:col-span-3"><dt class="text-slate-500">Spesifikasi Laptop / Desktop</dt><dd class="font-medium">{{ $product->processor ?? '-' }} | {{ $product->ram_gb ?? '-' }} GB RAM | {{ $product->storage_capacity ?? '-' }} GB {{ $product->storage_type ?? '-' }} | {{ $product->gpu ?? '-' }}</dd></div>
                <div class="md:col-span-3"><dt class="text-slate-500">Spesifikasi Aksesoris</dt><dd class="font-medium">{{ $product->component_type ?? '-' }} | {{ $product->capacity_gb ?? '-' }} GB {{ $product->connectivity ?? '-' }}</dd></div>
            </dl>

            @if($product->description)
                <p class="mt-5 border-t border-slate-100 pt-4 text-sm leading-relaxed text-slate-600">{{ $product->description }}</p>
            @endif
        </section>

        <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 transition hover:shadow-md">
            <p class="text-sm font-medium text-slate-500">Total Unit</p>
            <p class="mt-2 text-3xl font-bold text-indigo-700">{{ $product->units->count() }}</p>
            <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
                <div class="rounded-xl bg-emerald-50 px-3 py-2"><p class="text-xs text-emerald-800">Tersedia</p><p class="font-bold text-emerald-700">{{ $product->units->where('status', \App\Enums\UnitStatus::AVAILABLE)->count() }}</p></div>
                <div class="rounded-xl bg-sky-50 px-3 py-2"><p class="text-xs text-sky-800">Dipinjam</p><p class="font-bold text-sky-700">{{ $product->units->where('status', \App\Enums\UnitStatus::RENTED)->count() }}</p></div>
            </div>
            <div class="mt-5 border-t border-slate-100 pt-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Lokasi & Status Unit</p>
                <ul class="mt-2 max-h-96 space-y-2 overflow-y-auto pr-1 text-sm">
                    @forelse($product->units as $unit)
                        <li class="rounded-lg bg-slate-50 px-3 py-2 transition hover:bg-indigo-50">
                            <div class="flex items-center justify-between gap-2">
                                <span class="font-mono font-semibold text-slate-800">{{ $unit->serial_number }}</span>
                                <span class="rounded-full bg-{{ $unit->status->color() }}-100 px-2 py-0.5 text-[10px] font-bold text-{{ $unit->status->color() }}-800">{{ $unit->status->label() }}</span>
                            </div>
                            <span class="mt-0.5 block text-xs text-indigo-700">
                                {{ collect([$unit->room, $unit->rack, $unit->shelf, $unit->bin])->filter()->join(' · ') ?: 'Lokasi belum diatur' }}
                            </span>
                        </li>
                    @empty
                        <li class="text-sm text-slate-400">Belum ada unit inventaris.</li>
                    @endforelse
                </ul>
            </div>
        </section>
    </div>
</div>
@endsection
