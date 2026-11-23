@extends('layouts.public')

@section('title', 'Administrasi Peminjaman')
@section('heading', 'Administrasi Peminjaman')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 p-6 mb-6">
        <h2 class="text-xl font-bold text-slate-800">Kirim Dokumen Administrasi</h2>
        <p class="mt-1 text-sm text-slate-600">Peminjaman <span class="font-mono font-semibold text-indigo-600">{{ $rental->reference_number }}</span></p>
        <p class="mt-2 text-sm text-slate-500">Estimasi biaya: <span class="font-bold text-indigo-600">Rp {{ number_format($rental->estimated_amount, 0, ',', '.') }}</span></p>
        <p class="mt-2 text-sm text-slate-500">Status: <span class="font-medium">{{ $rental->status->label() }}</span></p>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 p-6">
        <form method="POST" action="{{ route('rentals.payment.submit', ['rental' => $rental->id, 'token' => request('token')]) }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1 text-slate-700">Jumlah (Rp) *</label>
                <input type="number" name="amount" min="1" step="0.01" value="{{ old('amount', $rental->estimated_amount) }}" required class="w-full rounded-lg border border-slate-300 focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1 text-slate-700">Metode Pembayaran *</label>
                <select name="payment_method" required class="w-full rounded-lg border border-slate-300 focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3">
                    <option value="transfer">Transfer Bank</option>
                    <option value="qris">QRIS</option>
                    <option value="cash">Tunai</option>
                    <option value="other">Lainnya</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1 text-slate-700">Bukti Pembayaran (Foto/Nota) *</label>
                <input type="file" name="proof" required class="block w-full rounded-lg border border-slate-300 focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 text-sm">
                <p class="mt-1 text-xs text-slate-500">File maksimal 5MB (JPG, PNG, PDF)</p>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium mb-1 text-slate-700">Catatan (Opsional)</label>
                <textarea name="notes" rows="2" class="w-full rounded-lg border border-slate-300 focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3">{{ old('notes') }}</textarea>
            </div>
            <button type="submit" class="w-full rounded-lg bg-indigo-600 py-3 font-semibold text-white hover:bg-indigo-700">Kirim Dokumen Administrasi</button>
            <p class="mt-3 text-xs text-slate-500">Petugas akan memverifikasi dokumen administrasi sebelum peminjaman disetujui.</p>
        </form>
    </div>
</div>
@endsection