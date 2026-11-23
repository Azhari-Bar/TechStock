@extends('layouts.admin')

@section('title', 'Unit Inventaris')
@section('heading', 'Unit Inventaris')

@section('content')
<div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-5">
    <form method="GET" class="flex flex-wrap gap-2">
         <input name="q" value="{{ request('q') }}" placeholder="Cari serial, barang, ruangan, atau rak" class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
         <select name="status" class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
             <option value="">Semua status</option>
             @foreach(['available', 'rented', 'sold', 'service', 'damaged', 'lost'] as $st)
                 <option value="{{ $st }}" @selected(request('status') == $st)>{{ 
                     match($st) {
                         'available' => 'Tersedia',
                         'rented' => 'Dipinjam',
                         'sold' => 'Terjual',
                         'service' => 'Servis',
                         'damaged' => 'Rusak',
                         'lost' => 'Hilang',
                     }
                 }}</option>
             @endforeach
         </select>
         <button class="rounded-lg border border-slate-300 px-4 py-2 text-sm hover:bg-white">Filter</button>
     </form>
    <a href="{{ route('admin.product-units.create') }}" class="rounded-lg bg-emerald-600 px-4 py-2 text-center text-sm font-medium text-white hover:bg-emerald-700">+ Tambah Unit</a>
</div>

<div class="rounded-xl bg-white shadow-sm ring-1 ring-slate-200">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Serial Number</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Inventaris</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Lokasi Penyimpanan</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Kondisi</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-slate-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($units as $unit)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-sm font-mono font-semibold">{{ $unit->serial_number }}</td>
                        <td class="px-4 py-3 text-sm">
                            {{ $unit->product?->name ?? '-' }}
                            <div class="text-xs text-slate-400">{{ $unit->product?->brand?->name ?? '-' }}</div>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            @if($unit->room || $unit->rack || $unit->shelf || $unit->bin)
                                <span class="font-medium">{{ $unit->room ?? '—' }}</span>
                                <div class="text-xs text-slate-500">{{ collect([$unit->rack, $unit->shelf, $unit->bin])->filter()->join(' · ') ?: 'Lokasi belum lengkap' }}</div>
                            @else
                                <span class="text-slate-400">Belum diatur</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm capitalize">{{ $unit->physical_condition ?? 'baik' }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="rounded-full bg-{{ $unit->status->color() }}-100 px-2.5 py-1 text-xs font-medium text-{{ $unit->status->color() }}-800">{{ $unit->status->label() }}</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.product-units.show', $unit) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-12 text-center text-sm text-slate-500">Belum ada unit inventaris.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="border-t border-slate-200 px-4 py-3">{{ $units->links() }}</div>
</div>
@endsection