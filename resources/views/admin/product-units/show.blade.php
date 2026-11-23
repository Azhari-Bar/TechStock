@extends('layouts.admin')

@section('title', 'Detail Unit')
@section('heading', 'Detail Unit Inventaris')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <div class="flex justify-end gap-2">
        <a href="{{ route('admin.product-units.edit', $unit) }}" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Ubah Unit</a>
        <a href="{{ route('admin.product-units.index') }}" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Kembali</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 p-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4">
            <div>
                <h2 class="text-xl font-bold text-slate-900">{{ $unit->serial_number }}</h2>
                <p class="text-sm text-slate-500">{{ $unit->product?->name ?? '-' }}</p>
            </div>
            <span class="rounded-full bg-{{ $unit->status->color() }}-100 px-3 py-1 text-xs font-semibold text-{{ $unit->status->color() }}-800 ring-1 ring-inset ring-{{ $unit->status->color() }}-200">{{ $unit->status->label() }}</span>
        </div>

        <dl class="grid grid-cols-2 gap-4 text-sm">
            <div><dt class="text-slate-500">Produk</dt><dd class="font-medium">{{ $unit->product?->name ?? '-' }}</dd></div>
            <div><dt class="text-slate-500">Kategori</dt><dd>{{ $unit->product?->category?->name ?? '-' }}</dd></div>
            <div><dt class="text-slate-500">Merek</dt><dd>{{ $unit->product?->brand?->name ?? '-' }}</dd></div>
            <div><dt class="text-slate-500">Lokasi Simpan</dt><dd class="font-bold text-indigo-600">{{ $unit->room ?? '—' }} ({{ collect([$unit->rack, $unit->shelf, $unit->bin])->filter()->join(' / ') ?: 'Belum diatur' }})</dd></div>
            <div><dt class="text-slate-500">Kondisi Fisik</dt><dd>{{ $unit->physical_condition }}</dd></div>
            <div><dt class="text-slate-500">Grade</dt><dd>{{ $unit->grade ?? '—' }}</dd></div>
            <div><dt class="text-slate-500">Harga Beli</dt><dd>Rp {{ number_format((float) $unit->acquisition_price, 0, ',', '.') }}</dd></div>
            <div><dt class="text-slate-500">Diterima</dt><dd>{{ optional($unit->received_at)->format('d M Y') ?? '—' }}</dd></div>
            <div><dt class="text-slate-500">Garansi Hingga</dt><dd>{{ optional($unit->warranty_until)->format('d M Y') ?? '—' }}</dd></div>
            <div><dt class="text-slate-500">Dibuat</dt><dd>{{ $unit->created_at?->format('d M Y H:i') }}</dd></div>
        </dl>

        @if($unit->notes)
            <div class="mt-4 rounded-lg bg-slate-50 p-3 text-sm text-slate-700">{{ $unit->notes }}</div>
        @endif
    </div>

    @if($unit->rental)
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 p-6">
            <h3 class="font-semibold text-slate-800 mb-3">Riwayat Peminjaman Terkait</h3>
            <p class="text-sm text-slate-600">{{ $unit->rental->reference_number }} — {{ $unit->rental->customer_name }} — {{ $unit->rental->status->label() }}</p>
        </div>
    @endif
</div>
@endsection