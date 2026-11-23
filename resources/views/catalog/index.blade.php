@extends('layouts.public')

@section('title', 'Inventaris Gudang')
@section('heading', 'Inventaris Gudang')

@section('content')
<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Hero Section -->
    <div class="tech-hero relative overflow-hidden rounded-3xl shadow-2xl mb-12">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-10 rounded-full -mr-20 -mt-20 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-amber-400 opacity-20 rounded-full -ml-10 -mb-10 blur-2xl"></div>
        <div class="absolute top-1/4 left-1/4 w-2 h-2 bg-indigo-400 rounded-full opacity-30"></div>
        <div class="absolute top-1/3 right-1/4 w-3 h-3 bg-emerald-400 rounded-full opacity-20"></div>
        <div class="relative px-8 py-16 md:px-14 md:py-20 text-center md:text-left motion-safe:animate-[fadeIn_.5s_ease-out]">
            <span class="inline-block px-4 py-1.5 rounded-full bg-white/10 border border-white/20 text-indigo-100 text-xs font-bold uppercase tracking-widest mb-6">Sistem Inventaris Gudang</span>
            <h1 class="text-4xl md:text-6xl font-black text-white mb-6 tracking-tight leading-[1.1]">
                Inventaris <span class="text-amber-400 italic">Laptop & Komputer</span><br class="hidden md:block"> dalam Satu Sistem
            </h1>
            <p class="text-indigo-100 text-lg md:text-xl max-w-2xl font-medium leading-relaxed opacity-90">
                Cek stok, kondisi, lokasi, dan status peminjaman dalam satu tempat.
            </p>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="relative -mt-20 z-10 mb-8">
        <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-xl ring-1 ring-slate-200/60 p-6 md:p-8 max-w-5xl mx-auto">
            <form method="GET" action="{{ route('catalog.index') }}" class="flex flex-col lg-flex-row gap-4">
                <div class="relative flex-1 group">
                    <svg class="absolute left-5 top-4 h-5 w-5 text-slate-400 group-focus-within:text-indigo-500 transition-colors" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari laptop, SSD, RAM, mouse, keyboard..." class="w-full pl-14 pr-6 py-4 rounded-xl border-slate-200 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all font-medium">
                </div>
                <div class="flex flex-wrap sm:flex-nowrap gap-4">
                    <select name="category" class="flex-1 sm:flex-none px-6 py-4 rounded-xl border-slate-200 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all bg-white font-medium min-w-[170px]">
                        <option value="">Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <select name="condition" class="flex-1 sm:flex-none px-6 py-4 rounded-xl border-slate-200 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all bg-white font-medium min-w-[150px]">
                        <option value="">Kondisi</option>
                        <option value="new" @selected(request('condition') == 'new')>Baru</option>
                        <option value="used" @selected(request('condition') == 'used')>Bekas</option>
                    </select>
                    <select name="location" class="flex-1 sm:flex-none px-6 py-4 rounded-xl border-slate-200 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all bg-white font-medium min-w-[170px]">
                        <option value="">Lokasi Gudang</option>
                        @foreach($rooms ?? [] as $room)
                            <option value="{{ $room }}" @selected(request('location') == $room)>{{ $room }}</option>
                        @endforeach
                    </select>
                    <button class="tech-button w-full sm:w-auto px-10 py-4">
                        Cari Inventaris
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Mode Toggle -->
    <div class="flex justify-center mb-8">
        <div class="inline-flex w-full max-w-xl rounded-2xl bg-slate-100 p-1.5 shadow-inner ring-1 ring-slate-200">
            <a href="{{ route('catalog.index', array_merge(request()->except(['page', 'mode']), ['mode' => 'all'])) }}" 
               class="flex min-h-14 flex-1 items-center justify-center rounded-xl px-4 py-3 text-center transition-all {{ $filterMode === 'all' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 hover:bg-white hover:text-indigo-700' }}">
                <span class="flex flex-col leading-tight">
                    <span class="text-sm font-bold">Semua Inventaris</span>
                    <span class="mt-0.5 text-xs opacity-80">{{ $totalAll }} barang</span>
                </span>
            </a>
            <a href="{{ route('catalog.index', array_merge(request()->except(['page', 'mode']), ['mode' => 'borrow'])) }}" 
               class="flex min-h-14 flex-1 items-center justify-center rounded-xl px-4 py-3 text-center transition-all {{ $filterMode === 'borrow' ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-600 hover:bg-white hover:text-emerald-700' }}">
                <span class="flex flex-col leading-tight">
                    <span class="text-sm font-bold">Dapat Dipinjam</span>
                    <span class="mt-0.5 text-xs opacity-80">{{ $totalBorrow }} barang</span>
                </span>
            </a>
        </div>
    </div>

    <!-- Products Section -->
    @php
        $sectionTitle = $filterMode === 'borrow' ? 'Inventaris yang Dapat Dipinjam' : 'Daftar Inventaris Gudang';
        $sectionDesc = $filterMode === 'borrow' 
            ? 'Barang dengan status "dapat dipinjam" yang dapat diajukan peminjamannya.' 
            : 'Barang serta jumlah unit yang tercatat dalam sistem. Status "Tersedia" menandakan unit dapat diajukan untuk dipinjam.';
    @endphp
    
    <section class="mb-8" id="products-section">
        <div class="mb-6 rounded-2xl bg-white/70 p-5 ring-1 ring-slate-200 animate-slide-up">
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">{{ $sectionTitle }}</h2>
            <p class="text-slate-500 font-medium mt-1">{{ $sectionDesc }}</p>
        </div>

        @if($products->count())
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach($products as $product)
                    @include('catalog._product-card', ['product' => $product, 'delay' => $loop->index * 0.05])
                @endforeach
            </div>
            <div class="mt-12 flex justify-center">
                {{ $products->links() }}
            </div>
        @else
            <div class="rounded-3xl bg-white p-20 text-center shadow-xl ring-1 ring-slate-200">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-200">
                    <svg class="h-12 w-12" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 1010.607 10.607z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-slate-900 mb-2">Inventaris Tidak Ditemukan</h3>
                <p class="text-slate-500 max-w-sm mx-auto mb-8 font-medium">
                    Coba gunakan kata kunci lain atau ubah filter untuk melihat seluruh inventaris gudang.
                </p>
                <a href="{{ route('catalog.index') }}" class="tech-button">Lihat Semua Inventaris</a>
            </div>
        @endif
    </section>
</div>
@endsection
