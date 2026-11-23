@extends('layouts.admin')

@section('title', 'Detail Supplier')
@section('heading', 'Detail Supplier')

@section('content')
<div class="max-w-4xl">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-5">
        <div>
            <a href="{{ route('admin.suppliers.index') }}" class="mb-2 inline-block text-sm font-medium text-indigo-600 hover:text-indigo-800">← Kembali ke Daftar</a>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.suppliers.edit', $supplier) }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm hover:bg-slate-50">Edit</a>
            <form method="POST" action="{{ route('admin.suppliers.destroy', $supplier) }}" onsubmit="return confirm('Hapus supplier ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="rounded-lg border border-red-300 px-4 py-2 text-sm text-red-600 hover:bg-red-50">Hapus</button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 p-6">
        <div class="grid grid-cols-2 gap-6 mb-6">
            <div class="rounded-lg border border-slate-200 bg-white p-4">
                <p class="text-xs font-bold text-slate-500 uppercase">Nama</p>
                <p class="text-lg font-bold text-slate-900 mt-1">{{ $supplier->name }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-white p-4">
                <p class="text-xs font-bold text-slate-500 uppercase">Kontak</p>
                <p class="text-lg font-bold text-slate-900 mt-1">{{ $supplier->contact_person ?? '-' }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-white p-4">
                <p class="text-xs font-bold text-slate-500 uppercase">Telepon</p>
                <p class="text-lg font-bold text-slate-900 mt-1">{{ $supplier->phone ?? '-' }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-white p-4">
                <p class="text-xs font-bold text-slate-500 uppercase">Email</p>
                <p class="text-lg font-bold text-slate-900 mt-1">{{ $supplier->email ?? '-' }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-white p-4">
                <p class="text-xs font-bold text-slate-500 uppercase">Status</p>
                <p class="text-lg font-bold text-slate-900 mt-1">{{ $supplier->is_active ? 'Aktif' : 'Nonaktif' }}</p>
            </div>
            <div class="col-span-2 rounded-lg border border-slate-200 bg-white p-4">
                <p class="text-xs font-bold text-slate-500 uppercase">Alamat</p>
                <p class="text-slate-700 mt-1 whitespace-pre-wrap">{{ $supplier->address ?? '-' }}</p>
            </div>
        </div>

        @if($supplier->notes)
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 mb-6">
                <p class="text-xs font-bold text-slate-500 uppercase mb-1">Catatan</p>
                <p class="text-slate-700 whitespace-pre-wrap">{{ $supplier->notes }}</p>
            </div>
        @endif

        <h3 class="text-lg font-bold text-slate-900 mb-3">Riwayat Stok</h3>
        <div class="overflow-hidden rounded-xl border border-slate-200">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Jenis</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Produk / Unit</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Jumlah</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($supplier->stockMovements as $movement)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 text-sm text-slate-600">{{ optional($movement->created_at)->format('d M Y H:i') }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="rounded-full px-2 py-0.5 text-xs font-bold {{ $movement->movement_type->color() }}-100 text-{{ $movement->movement_type->color() }}-800">{{ $movement->movement_type->label() }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-700">{{ $movement->product?->name ?? '-' }} ({{ $movement->unit?->serial_number ?? '-' }})</td>
                            <td class="px-4 py-3 text-sm font-medium">{{ $movement->quantity }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $movement->notes ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-12 text-center text-sm text-slate-500">Belum ada riwayat stok.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
