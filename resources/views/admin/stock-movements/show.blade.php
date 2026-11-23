@extends('layouts.admin')

@section('title', 'Detail Pergerakan Stok')
@section('heading', 'Detail Pergerakan Stok')

@section('content')
<div class="max-w-3xl">
    <a href="{{ route('admin.stock-movements.index') }}" class="mb-4 inline-block text-sm font-medium text-indigo-600 hover:text-indigo-800">← Kembali ke Riwayat Stok</a>

    <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 p-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-5">
            <div>
                <span class="rounded-full bg-{{ $movement->movement_type->color() }}-100 px-3 py-1 text-sm font-bold text-{{ $movement->movement_type->color() }}-800">{{ $movement->movement_type->label() }}</span>
                <p class="text-xs text-slate-500 mt-2">Dicatat {{ optional($movement->created_at)->format('d M Y H:i') }}</p>
            </div>
            <p class="text-xs text-slate-500">Oleh {{ $movement->user?->name ?? '-' }}</p>
        </div>

        <dl class="grid grid-cols-2 gap-5 text-sm">
            <div><dt class="text-slate-500">Produk</dt><dd class="font-semibold">{{ $movement->product?->name ?? '-' }}</dd></div>
            <div><dt class="text-slate-500">Serial Unit</dt><dd class="font-mono font-semibold">{{ $movement->unit?->serial_number ?? '-' }}</dd></div>
            <div><dt class="text-slate-500">Jumlah</dt><dd class="font-semibold">{{ $movement->quantity }}</dd></div>
            <div><dt class="text-slate-500">Supplier</dt><dd class="font-semibold">{{ $movement->supplier?->name ?? '-' }}</dd></div>
            <div><dt class="text-slate-500">Status Sebelum</dt><dd class="font-semibold">{{ $movement->status_before ?? '-' }}</dd></div>
            <div><dt class="text-slate-500">Status Sesudah</dt><dd class="font-semibold">{{ $movement->status_after ?? '-' }}</dd></div>
            <div><dt class="text-slate-500">Harga Satuan</dt><dd class="font-semibold">{{ $movement->unit_price ? 'Rp '.number_format((float)$movement->unit_price, 0, ',', '.') : '-' }}</dd></div>
            <div><dt class="text-slate-500">Total</dt><dd class="font-semibold">{{ $movement->total_amount ? 'Rp '.number_format((float)$movement->total_amount, 0, ',', '.') : '-' }}</dd></div>
        </dl>

        @if($movement->notes)
            <div class="mt-5 rounded-lg bg-slate-50 p-4 text-sm text-slate-700">{{ $movement->notes }}</div>
        @endif
    </div>
</div>
@endsection
