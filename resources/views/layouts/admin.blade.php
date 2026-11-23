@php
    $items = [
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'active' => 'admin.dashboard'],
        ['label' => 'Ringkasan', 'route' => 'admin.reports.index', 'active' => 'admin.reports.*'],
        ['label' => 'Peminjaman', 'route' => 'admin.rentals.index', 'active' => 'admin.rentals.*'],
        ['label' => 'Produk', 'route' => 'admin.products.index', 'active' => 'admin.products.*'],
        ['label' => 'Unit Inventaris', 'route' => 'admin.product-units.index', 'active' => 'admin.product-units.*'],
        ['label' => 'Riwayat Stok', 'route' => 'admin.stock-movements.index', 'active' => 'admin.stock-movements.*'],
        ['label' => 'Supplier', 'route' => 'admin.suppliers.index', 'active' => 'admin.suppliers.*'],
        ['label' => 'Kategori', 'route' => 'admin.categories.index', 'active' => 'admin.categories.*'],
        ['label' => 'Merek', 'route' => 'admin.brands.index', 'active' => 'admin.brands.*'],
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') &middot; TECHSTOCK</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-slate-100 text-slate-900 antialiased min-h-screen" style="background: radial-gradient(circle at 15% 20%, rgba(99,102,241,.04), transparent 25rem), radial-gradient(circle at 85% 80%, rgba(14,165,233,.03), transparent 25rem), radial-gradient(circle at 50% 100%, rgba(124,58,237,.02), transparent 35rem)">

<div class="flex min-h-screen">
    <aside class="w-64 shrink-0 bg-slate-900 text-slate-300 flex flex-col relative overflow-hidden">
        <div class="absolute inset-0 opacity-[0.04] pointer-events-none" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; stroke=&quot;%23ffffff&quot; stroke-width=&quot;1&quot;%3E%3Cpath d=&quot;M30 0v60M0 30h60&quot;/%3E%3C/g%3E%3C/svg%3E');"></div>
        <div class="h-16 flex items-center gap-3 px-5 border-b border-slate-800 relative bg-gradient-to-r from-indigo-900 via-purple-900 to-indigo-800">
            <x-logo-mark class="h-9 w-9 drop-shadow-lg" />
            <div class="flex flex-col leading-none">
                <span class="text-base font-black tracking-tight text-white">TECHSTOCK</span>
                <span class="text-[8px] font-semibold text-indigo-300 uppercase tracking-widest leading-none mt-0.5">Gudang Laptop & Komputer</span>
            </div>
        </div>
        <nav class="flex-1 overflow-y-auto p-4 space-y-1 relative">
            @foreach($items as $item)
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs($item['active']) ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20' : 'hover:bg-slate-800 hover:text-white' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>
        <div class="border-t border-slate-800 p-4 relative">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium hover:bg-red-500/10 hover:text-red-400 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 overflow-y-auto">
        <header class="h-16 border-b border-slate-200 bg-white/80 backdrop-blur-md px-8 flex items-center justify-between sticky top-0 z-10">
            <div>
                <h1 class="text-xl font-bold text-slate-800">@yield('heading')</h1>
                <p class="text-xs text-slate-400">TECHSTOCK Admin Panel</p>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ url('/') }}" class="text-sm font-medium text-slate-500 hover:text-indigo-600">Lihat Inventaris Gudang</a>
            </div>
        </header>
        <div class="p-8">
            @if(session('success'))
                <div class="mb-6 rounded-xl bg-emerald-50 border border-emerald-100 px-4 py-3 text-sm text-emerald-700 flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 rounded-xl bg-red-50 border border-red-100 px-4 py-3 text-sm text-red-700 flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    {{ session('error') }}</div>
            @endif
            @yield('content')
        </div>
    </main>
</div>
</body>
</html>
