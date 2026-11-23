@extends('layouts.public')

@section('title', 'Detail Pengajuan Peminjaman')
@section('heading', 'Detail Pengajuan Peminjaman')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 p-6 mb-6">
        <div class="text-center">
            <div class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-indigo-100">
                <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-3-3v6m5 5H7a2 2 0 01-2-2V7a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2z"/></svg>
            </div>
            <h2 class="mt-4 text-2xl font-bold text-slate-800">Status: {{ $rental->status->label() ?? 'Menunggu Persetujuan' }}</h2>
            <p class="mt-2 text-slate-600">No. Referensi: <span class="font-mono font-semibold text-indigo-600">{{ $rental->reference_number }}</span></p>
        </div>

        <dl class="mt-6 grid grid-cols-2 gap-4 text-sm border-t border-slate-100 pt-6">
            <div><dt class="text-slate-500">Produk</dt><dd class="font-medium">{{ $rental->product->name }}</dd></div>
            <div><dt class="text-slate-500">Penyewa</dt><dd class="font-medium">{{ $rental->customer_name }}</dd></div>
            <div><dt class="text-slate-500">Mulai</dt><dd>{{ $rental->start_date?->format('d M Y') }}</dd></div>
            <div><dt class="text-slate-500">Jatuh Tempo</dt><dd>{{ $rental->due_date?->format('d M Y') }}</dd></div>
            <div class="col-span-2"><dt class="text-slate-500">Perkiraan Biaya</dt><dd class="font-bold text-indigo-600">Rp {{ number_format($rental->estimated_amount, 0, ',', '.') }}</dd></div>
        </dl>
    </div>

    <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 p-6">
        <h3 class="text-lg font-semibold text-slate-800 mb-4">Informasi Pengajuan</h3>
        <div class="space-y-4 text-sm">
            <div class="flex items-start gap-3">
                <span class="text-slate-500 w-32">Identitas:</span>
                <span class="text-slate-800">{{ $rental->identity_document ? 'Sudah diupload' : 'Belum diupload' }}</span>
            </div>
            <div class="flex items-start gap-3">
                <span class="text-slate-500 w-32">Keperluan:</span>
                <span class="text-slate-800">{{ $rental->purpose }}</span>
            </div>
            <div class="flex items-start gap-3">
                <span class="text-slate-500 w-32">Catatan:</span>
                <span class="text-slate-800">{{ $rental->notes ?? 'Tidak ada' }}</span>
            </div>
        </div>

        @if($rental->status->value === 'menunggu')
            <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <p class="text-sm text-yellow-800">Pengajuan peminjaman ini menunggu persetujuan admin. Silakan hubungi admin untuk mempercepat proses.</p>
            </div>
        @endif

        @if($rental->status->value === 'disetujui')
            <div class="mt-6 bg-emerald-50 border border-emerald-200 rounded-lg p-4">
                <p class="text-sm text-emerald-800">Peminjaman disetujui. Silakan atur serah terima unit dan administrasi.</p>
            </div>
        @endif
    </div>
</div>
@endsection