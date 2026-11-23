@extends('layouts.admin')

@section('title', 'Peminjaman Admin')
@section('heading', 'Daftar Peminjaman')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="interactive-panel bg-white rounded-xl shadow-sm ring-1 ring-slate-200 p-6 mb-6 animate-slide-up">
        <h2 class="text-xl font-semibold text-slate-800">Daftar Peminjaman</h2>
        <p class="text-sm text-slate-600">Kelola pengajuan dan proses peminjaman unit inventaris.</p>

        <div class="mt-6 flex gap-3 flex-wrap">
            <form method="GET" class="flex items-center gap-3">
                <div class="relative group">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.711-4.711M11.293 3.293a7.5 7.5 0 1110.607 10.607L16 16H8V8l3.293-3.293z"/>
                    </svg>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nomor / customer..." class="w-48 rounded-xl border border-slate-300 px-3 py-2 pl-10 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
                <select name="status" class="rounded-xl border border-slate-300 py-2 px-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white">
                    <option value="">Semua status</option>
                    <option value="menunggu" @selected(request('status') == 'menunggu')>Menunggu</option>
                    <option value="disetujui" @selected(request('status') == 'disetujui')>Disetujui</option>
                    <option value="dipinjam" @selected(request('status') == 'dipinjam')>Dipinjam</option>
                    <option value="menunggu_pengembalian" @selected(request('status') == 'menunggu_pengembalian')>Menunggu Pengembalian</option>
                    <option value="terlambat" @selected(request('status') == 'terlambat')>Terlambat</option>
                    <option value="selesai" @selected(request('status') == 'selesai')>Selesai</option>
                    <option value="ditolak" @selected(request('status') == 'ditolak')>Ditolak</option>
                    <option value="dibatalkan" @selected(request('status') == 'dibatalkan')>Dibatalkan</option>
                </select>
                <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 transition">Filter</button>
            </form>
        </div>

        @if($rentals->count())
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">No. Ref</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Customer</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Produk</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Periode</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Estimasi</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase text-slate-500">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-slate-500"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($rentals as $rental)
                    <tr class="hover:bg-indigo-50/50 transition-colors duration-200">
                        <td class="px-4 py-3 text-sm font-mono">{{ $rental->reference_number }}</td>
                        <td class="px-4 py-3 text-sm font-medium">{{ $rental->customer_name }}</td>
                        <td class="px-4 py-3 text-sm">{{ $rental->product?->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-slate-600">{{ $rental->start_date?->format('d M Y') }} - {{ $rental->due_date?->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-sm font-medium">Rp {{ number_format($rental->estimated_amount, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-center"><span class="rounded-full px-2.5 py-0.5 text-xs font-medium bg-{{ $rental->status->color() }}-100 text-{{ $rental->status->color() }}-800">{{ $rental->status->label() }}</span></td>
                        <td class="px-4 py-3 text-right text-sm"><a href="{{ route('admin.rentals.show', $rental) }}" class="text-indigo-600 hover:text-indigo-900 hover:underline transition">Detail</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{!! $rentals->links() !!}</div>
        @else
            <div class="rounded-xl bg-white p-8 text-center shadow-sm ring-1 ring-slate-200 mt-6 animate-fade-in">
                <p class="text-sm text-slate-600">Belum ada data peminjaman.</p>
            </div>
        @endif
    </div>
</div>
@endsection