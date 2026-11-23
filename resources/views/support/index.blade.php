@extends('layouts.public')

@section('title', 'Helpdesk')
@section('heading', 'Helpdesk Support')

@section('content')
<div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 rounded-2xl bg-gradient-to-r from-indigo-700 via-purple-600 to-indigo-800 p-6 text-white">
        <h1 class="text-2xl font-bold">Helpdesk TECHSTOCK</h1>
        <p class="mt-2 text-indigo-100">Ada pertanyaan atau masalah? Tim kami siap membantu!</p>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <!-- Contact Form -->
        <div class="rounded-2xl bg-white shadow-xl ring-1 ring-slate-200">
            <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                <h2 class="text-lg font-semibold text-slate-800">Kirim Pesan</h2>
            </div>
            <div class="p-6">
                <form action="#" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama</label>
                        <input type="text" name="name" required class="w-full rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 py-2.5 px-4" placeholder="Nama lengkap Anda">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
                        <input type="email" name="email" required class="w-full rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 py-2.5 px-4" placeholder="email@contoh.com">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">No. HP</label>
                        <input type="tel" name="phone" required class="w-full rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 py-2.5 px-4" placeholder="08xxxxxxxxxx">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Subjek</label>
                        <select name="subject" required class="w-full rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 py-2.5 px-4 bg-white">
                            <option value="">Pilih subjek</option>
                            <option value="pinjaman">Masalah Peminjaman</option>
                            <option value="inventaris">Pertanyaan Inventaris</option>
                            <option value="retur">Pengembalian Unit</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Pesan</label>
                        <textarea name="message" rows="4" required class="w-full rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 py-2.5 px-4" placeholder="Jelaskan masalah Anda dengan detail..."></textarea>
                    </div>
                    <button type="submit" class="w-full rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 py-3 font-semibold text-white hover:from-indigo-700 hover:to-purple-700 transition">
                        Kirim Pesan
                    </button>
                </form>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="space-y-6">
            <div class="rounded-2xl bg-white shadow-xl ring-1 ring-slate-200 p-6">
                <h3 class="mb-4 text-lg font-semibold text-slate-800">Informasi Kontak</h3>
                <div class="space-y-3 text-slate-600">
                    <p class="flex items-center gap-3">
                        <svg class="h-5 w-5 text-indigo-600" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4C2.89543 4 2 4.89543 2 6V18C2 19.1046 2.89543 20 4 20H20C21.1046 20 22 19.1046 22 18V6C22 4.89543 21.1046 4 20 4ZM20 6L12 12L4 6H20ZM12 14C14.6583 14 18 12.8379 18 10V6L12 10L6 6V10C6 12.8379 9.34172 14 12 14Z"/></svg>
                        techstock@gmail.com
                    </p>
                    <p class="flex items-center gap-3">
                        <svg class="h-5 w-5 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        08:00 - 17:00 WIB
                    </p>
                </div>
            </div>
            
            <div class="rounded-2xl bg-gradient-to-r from-slate-50 to-slate-100 p-6">
                <h4 class="mb-3 font-semibold text-slate-800">FAQ Cepat</h4>
                <div class="space-y-3">
                    <details class="group">
                        <summary class="cursor-pointer font-medium text-slate-700 hover:text-indigo-600 transition">Bagaimana cara memesan unit penunjang?</summary>
                        <p class="mt-2 text-sm text-slate-600">Anda dapat mengklik tombol "Ajukan Peminjaman Unit" pada halaman detail produk.</p>
                    </details>
                    <details class="group">
                        <summary class="cursor-pointer font-medium text-slate-700 hover:text-indigo-600 transition transition">Apakah semua produk bisa dipinjam?</summary>
                        <p class="mt-2 text-sm text-slate-600">Hanya produk dengan label "Dapat Dipinjam" yang dapat Anda pinjamkan.</p>
                    </details>
                    <details class="group">
                        <summary class="cursor-pointer font-medium text-slate-700 hover:text-indigo-600 transition">Bagaimana jika ada masalah saat pengembalian?</summary>
                        <p class="mt-2 text-sm text-slate-600">Silahkan hubungi helpdesk kami untuk laporan masalah pengembalian.</p>
                    </details>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection