@extends('layouts.public')

@section('title', 'Ajukan Peminjaman')
@section('heading', 'Ajukan Peminjaman')

@section('content')
<div class="max-w-2xl mx-auto py-8 px-4">
    <form action="{{ route('rentals.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="product_id" value="{{ $product->id }}">

        <div class="bg-white rounded-xl shadow-sm p-6 space-y-6">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">Data Peminjaman: {{ $product->name }}</h2>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Nama Lengkap</label>
                    <input type="text" name="customer_name" required
                           class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">No. HP</label>
                    <input type="text" name="customer_phone" required
                           class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Email (Opsional)</label>
                <input type="email" name="customer_email"
                       class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Alamat (Opsional)</label>
                <textarea name="customer_address" rows="2"
                          class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500"></textarea>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Mulai</label>
                    <input type="date" name="start_date" required @today
                           class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Jatuh Tempo</label>
                    <input type="date" name="due_date" required @today
                           class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Tipe Peminjaman</label>
                    <select name="rental_period_type" required
                            class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                        <option value="">Pilih...</option>
                        <option value="daily">Harian</option>
                        <option value="weekly">Mingguan</option>
                        <option value="monthly">Bulanan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Tipe Pinjaman</label>
                    <select name="loan_type" required
                            class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                        <option value="internal">Internal</option>
                        <option value="external">Eksternal</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Keperluan</label>
                <textarea name="purpose" rows="3"
                          class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500"></textarea>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Bukti Identitas Pengaju</label>
                    <div class="mt-1">
                        <input type="file" name="identity_document" accept="jpg,jpeg,png" required>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">Klik untuk upload foto kartu identitas (JPG/PNG)</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Bukti Pembayaran</label>
                    <div class="mt-1">
                        <input type="file" name="proof" accept="jpg,jpeg,png,pdf" required>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">Upload bukti transfer/DOMPET Digital</p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Catatan (Opsional)</label>
                <textarea name="notes" rows="2"
                          class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500"></textarea>
            </div>
        </div>

        <div class="mt-6">
            <button type="submit"
                    class="w-full inline-flex justify-center items-center gap-2 rounded-md bg-indigo-600 px-5 py-3 text-white font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 16v1a3 3 0 013 3h10a3 3 0 013-3V4a3 3 0 00-3-3H7a3 3 0 00-3 3z"/>
                </svg>
                Ajukan Peminjaman
            </button>
        </div>
    </form>
</div>
@endsection