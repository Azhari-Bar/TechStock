<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Inventaris Gudang') &middot; TECHSTOCK</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="tech-shell text-slate-900 antialiased flex flex-col min-h-screen">

<!-- Navigation -->
<nav class="bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-40 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <a href="{{ route('catalog.index') }}" class="flex items-center gap-3 group">
                <x-logo-mark class="w-9 h-9 drop-shadow-md transition duration-300 group-hover:drop-shadow-lg group-hover:-rotate-3" />
                <div class="flex flex-col">
                    <span class="font-black text-lg tracking-tight bg-gradient-to-r from-indigo-700 to-purple-700 bg-clip-text text-transparent leading-none">TECHSTOCK</span>
                    <span class="text-[9px] font-semibold text-slate-400 uppercase tracking-widest leading-none mt-0.5">Gudang Laptop & Komputer</span>
                </div>
            </a>

            <!-- Nav Links -->
            <div class="flex items-center gap-2">
                <a href="{{ route('catalog.index') }}"
                   class="px-4 py-2 text-sm font-medium text-slate-700 rounded-xl hover:bg-indigo-50 hover:text-indigo-700 transition-all">
                    Inventaris Gudang
                </a>
                <a href="https://wa.me/+6281xxxxxxxxxx" target="_blank"
                   class="px-4 py-2 text-sm font-medium text-emerald-700 rounded-xl hover:bg-emerald-50 border border-emerald-200 transition-all flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Chat WA
                </a>

                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                           class="ml-2 px-4 py-2 rounded-xl text-sm font-semibold bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 transition-all shadow-md hover:shadow-lg">
                            Dashboard
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}"
                       class="ml-2 px-4 py-2 rounded-xl text-sm font-semibold bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 transition-all shadow-md hover:shadow-lg">
                        Masuk Admin
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<!-- Main Content -->
<main class="flex-1 min-h-[70vh]">
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
            <div class="rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700 flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
            <div class="rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700 flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                {{ session('error') }}</div>
        </div>
    @endif
    @yield('content')
</main>

<!-- Footer -->
<footer class="mt-auto border-t border-slate-800 bg-slate-900 text-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid gap-8 md:grid-cols-3">
            <!-- Brand -->
            <div class="md:col-span-1">
                <div class="mb-4 flex items-center gap-3">
                    <x-logo-mark class="h-10 w-10" />
                    <div>
                        <span class="block text-xl font-black text-white">TECHSTOCK</span>
                        <span class="block text-xs uppercase tracking-wider text-indigo-300">Gudang Laptop & Komputer</span>
                    </div>
                </div>
                <p class="max-w-md text-sm leading-relaxed text-slate-300">
                    Sistem manajemen inventaris untuk mengelola stok laptop, komputer, dan aksesoris gudang penuh lengkapnya.
                </p>
            </div>

            <!-- Navigation & Contact -->
            <div class="md:col-span-2">
                <div class="grid gap-8 sm:grid-cols-2">
                    <div>
                        <h3 class="mb-4 text-sm font-bold uppercase tracking-wider text-white">Navigasi</h3>
                        <ul class="space-y-3 text-sm text-slate-300">
                            <li><a href="{{ route('catalog.index') }}" class="transition hover:text-white">Inventaris Gudang</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="mb-4 text-sm font-bold uppercase tracking-wider text-white">Media Sosial</h3>
                        <div class="flex flex-col gap-4">
                            <div class="flex gap-3">
                                <a href="https://instagram.com/techstock" target="_blank" aria-label="Instagram TECHSTOCK" class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/10 transition hover:bg-pink-500 cursor-pointer hover:scale-105"><svg class="h-5 w-5 text-pink-400 group-hover:text-pink-500 transition" fill="currentColor" viewBox="0 0 24 24"><path d="M7 2C4.23858 2 2 4.23858 2 7V17C2 19.7614 4.23858 22 7 22H17C19.7614 22 22 19.7614 22 17V7C22 4.23858 19.7614 2 17 2H7ZM7 4H17C18.6569 4 20 5.34315 20 7V17C20 18.6569 18.6569 20 17 20H7C5.34315 20 4 18.6569 4 17V7C4 5.34315 5.34315 4 7 4ZM14 11.5V15.5H10.5V15H14V11H10.5V9.5H14V8H9.5V15H14V15.5Z"/></svg></a>
                                <a href="https://twitter.com/techstock" target="_blank" aria-label="Twitter TECHSTOCK" class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/10 transition hover:bg-blue-500 cursor-pointer hover:scale-105"><svg class="h-5 w-5 text-blue-400 group-hover:text-blue-500 transition" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.945 13.945 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.93 9.93 0 0024 4.59z"/></svg></a>
                                <a href="mailto:techstock@ac.id" aria-label="Email TECHSTOCK" class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/10 transition hover:bg-emerald-500 cursor-pointer hover:scale-105"><svg class="h-5 w-5 text-emerald-400 group-hover:text-emerald-500 transition" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4C2.89543 4 2 4.89543 2 6V18C2 19.1046 2.89543 20 4 20H20C21.1046 20 22 19.1046 22 18V6C22 4.89543 21.1046 4 20 4ZM20 6L12 12L4 6H20Z"/></svg></a>
                            </div>
                            <div class="text-sm text-slate-300">
                                <p class="font-semibold text-white">Instagram: @techstock</p>
                                <p class="font-semibold text-white">Twitter: @techstock</p>
                                <p class="font-semibold text-white">Email: techstock@ac.id</p>
                            </div>
                            <div class="rounded-lg bg-slate-800 px-4 py-3 text-sm text-slate-300">
                                <p class="font-semibold text-white">Jam Operasional</p>
                                <p class="mt-1">Senin – Jumat | 08:00 – 17:00 WIB</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-8 border-t border-slate-800 pt-6 text-center text-xs text-slate-500">
            &copy; {{ date('Y') }} TECHSTOCK. Semua hak dilindungi. Sistem Manajemen Inventaris Gudang Toko Laptop & Komputer.
        </div>
    </div>
</footer>
</body>
</html>