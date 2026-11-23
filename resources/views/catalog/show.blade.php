@extends('layouts.public')

@section('title', $product->name)
@section('heading', 'Detail Inventaris')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6 text-sm text-slate-600 flex items-center gap-2">
        <a href="{{ route('catalog.index') }}" class="hover:text-indigo-600 transition">Inventaris Gudang</a>
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
        <span class="font-medium text-slate-800">{{ $product->name }}</span>
    </nav>

    <div class="grid gap-8 lg:grid-cols-2">
        <!-- Product Image -->
        <div class="relative group">
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-2xl opacity-10 blur-xl group-hover:opacity-20 transition-opacity"></div>
            <div class="relative bg-white rounded-2xl shadow-lg overflow-hidden ring-1 ring-slate-200">
                <div class="aspect-[4/3] overflow-hidden bg-gradient-to-br from-slate-100 to-slate-200">
                    <img src="{{ $product->getImageUrlAttribute() }}" alt="{{ $product->name }}" class="w-full h-full object-contain p-6 group-hover:scale-105 transition-transform duration-300">
                </div>
                <div class="absolute top-4 left-4">
                    @if($product->condition === 'new')
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            Baru
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">Bekas</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Product Info -->
        <div class="space-y-6">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">{{ $product->name }}</h1>
                <p class="mt-2 text-slate-600 leading-relaxed">{{ $product->description ?? 'Unit inventaris gudang Techstock. Ketersediaan dapat ditanyakan langsung via WhatsApp atau diajukan melalui formulir peminjaman.' }}</p>
            </div>

            <!-- Key Stats Grid -->
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl p-5 border border-indigo-100">
                    <dt class="text-sm text-slate-600 font-semibold">Kategori</dt>
                    <dd class="mt-1.5 font-bold text-indigo-700 text-lg">{{ $product->category?->name }}</dd>
                </div>
                <div class="bg-gradient-to-br from-sky-50 to-blue-50 rounded-xl p-5 border border-sky-100">
                    <dt class="text-sm text-slate-600 font-semibold">Merek</dt>
                    <dd class="mt-1.5 font-bold text-sky-700 text-lg">{{ $product->brand?->name ?? '-' }}</dd>
                </div>
                <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-xl p-5 border border-emerald-100">
                    <dt class="text-sm text-slate-600 font-semibold">Stok Tersedia</dt>
                    <dd class="mt-1.5 font-bold text-emerald-700 text-lg">{{ $product->units_count ?? 0 }} unit</dd>
                </div>
                <div class="bg-gradient-to-br from-violet-50 to-fuchsia-50 rounded-xl p-5 border border-violet-100">
                    <dt class="text-sm text-slate-600 font-semibold">Kesiapan Unit</dt>
                    <dd class="mt-1.5 font-extrabold text-violet-700 text-xl">{{ ($product->units_count ?? 0) > 0 ? 'Tersedia' : 'Tidak tersedia' }}</dd>
                </div>
            </div>

            <!-- Specifications -->
            <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
                    <h3 class="text-white font-semibold flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Spesifikasi Teknis
                    </h3>
                </div>
                <div class="p-6 grid grid-cols-2 gap-4 text-sm">
                    @php($specs = [
                        'Processor' => $product->processor,
                        'RAM' => $product->ram_gb ? $product->ram_gb . 'GB' : null,
                        'Storage' => $product->storage_capacity ? $product->storage_capacity . 'GB ' . ($product->storage_type ?? '') : null,
                        'GPU' => $product->gpu,
                        'OS' => $product->operating_system,
                        'Screen' => $product->screen_size ? $product->screen_size . ' inch' : null,
                        'Tipe Komponen' => $product->component_type,
                        'Kapasitas' => $product->capacity_gb ? $product->capacity_gb . 'GB' : null,
                        'Konektivitas' => $product->connectivity,
                        'Kompatibilitas' => $product->compatibility,
                    ])
                    @foreach($specs as $k => $v)
                        @if($v)
                            <div class="flex flex-col">
                                <dt class="text-slate-500 text-xs uppercase tracking-wide">{{ $k }}</dt>
                                <dd class="font-medium text-slate-800 mt-0.5">{{ $v }}</dd>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Lokasi Unit Tersedia -->
            @if($product->units && $product->units->count())
                <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-4">
                        <h3 class="text-white font-semibold flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Lokasi Unit di Gudang
                        </h3>
                    </div>
                    <div class="p-4 divide-y divide-slate-100">
                        @foreach($product->units->take(5) as $unit)
                            <div class="py-2 flex justify-between items-center text-sm">
                                <div>
                                    <span class="font-mono font-bold text-slate-800">{{ $unit->serial_number }}</span>
                                    <div class="text-xs text-slate-500">{{ $unit->physical_condition }}</div>
                                </div>
                                <div class="text-right">
                                    <span class="text-indigo-600 font-bold">{{ $unit->room ?? 'Gudang' }}</span>
                                    <div class="text-xs text-slate-500">{{ collect([$unit->rack, $unit->shelf, $unit->bin])->filter()->join(' · ') ?: '-' }}</div>
                                </div>
                            </div>
                        @endforeach
                        @if($product->units->count() > 5)
                            <p class="pt-2 text-xs text-slate-400 italic">+{{ $product->units->count() - 5 }} unit lain tersedia di lokasi berbeda</p>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Rental Rates -->
            @if($product->rental_daily_rate || $product->rental_weekly_rate || $product->rental_monthly_rate)
                <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200 p-5 border border-slate-200">
                    <h3 class="text-base font-semibold text-slate-800 mb-3">Informasi Biaya Peminjaman</h3>
                    <div class="grid grid-cols-3 gap-3 text-center">
                        <div class="bg-white rounded-lg p-3 shadow-sm">
                            <p class="text-xs text-slate-500">Harian</p>
                            <p class="font-bold text-indigo-600">Rp {{ number_format($product->rental_daily_rate, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-3 shadow-sm">
                            <p class="text-xs text-slate-500">Mingguan</p>
                            <p class="font-bold text-purple-600">Rp {{ number_format($product->rental_weekly_rate, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-3 shadow-sm">
                            <p class="text-xs text-slate-500">Bulanan</p>
                            <p class="font-bold text-violet-600">Rp {{ number_format($product->rental_monthly_rate, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex flex-col gap-3">
                <a href="https://wa.me/+6281xxxxxxxxxx?text=Halo, saya ingin menanyakan ketersediaan {{ $product->name }}" 
                   target="_blank"
                   class="flex items-center justify-center gap-2 w-full rounded-xl bg-gradient-to-r from-emerald-600 to-green-600 px-6 py-4 font-semibold text-white hover:from-emerald-700 hover:to-green-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Hubungi Petugas tentang Ketersediaan
                </a>
                
                @if($product->is_borrowable && ($product->rental_daily_rate || $product->rental_weekly_rate || $product->rental_monthly_rate))
                    <a href="{{ route('rentals.create', ['product' => $product->id]) }}" 
                       class="flex items-center justify-center gap-2 w-full rounded-xl border-2 border-indigo-600 px-6 py-4 font-semibold text-indigo-600 hover:bg-indigo-50 transition-all hover:border-indigo-700 hover:text-indigo-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        Ajukan Peminjaman Unit
                    </a>
                @elseif(!$product->is_borrowable)
                    <p class="rounded-xl bg-slate-50 border border-slate-200 px-4 py-3 text-sm font-medium text-slate-500 text-center">
                        Barang ini tercatat di gudang tetapi tidak dapat dipinjam.
                    </p>
                @endif
            </div>

            <a href="{{ route('catalog.index') }}" class="block text-center text-sm font-medium text-slate-500 hover:text-slate-700 transition">← Kembali ke Inventaris</a>
        </div>
    </div>
</div>
@endsection