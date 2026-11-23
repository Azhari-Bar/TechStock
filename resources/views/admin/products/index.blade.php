@extends('layouts.admin')

@section('title', 'Produk')
@section('heading', 'Produk')

@section('content')
<div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-5">
    <form method="GET" class="flex items-center gap-3">
        <div class="relative group">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.711-4.711M11.293 3.293a7.5 7.5 0 1110.607 10.607L16 16H8V8l3.293-3.293z"/>
            </svg>
            <input name="q" value="{{ request('q') }}" placeholder="Cari..." class="w-48 rounded-xl border border-slate-300 py-2 pl-10 pr-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
        </div>
        <select name="category" class="rounded-xl border border-slate-300 py-2 px-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white">
            <option value="">Semua kategori</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        <button class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 transition">
            Filter
        </button>
    </form>
    <a href="{{ route('admin.products.create') }}" class="rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 px-4 py-2 text-sm font-medium text-white hover:from-indigo-700 hover:to-purple-700 transition">
        + Tambah Produk
    </a>
</div>

<div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-200">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Inventaris</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Kategori / Merek</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Stok</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Harga Beli</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-slate-500"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($products as $product)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3">
                            <a class="font-medium text-slate-900 hover:text-indigo-600" href="{{ route('admin.products.show', $product) }}">
                                {{ $product->name }}
                            </a>
                            <div class="text-xs text-slate-500">{{ $product->model ?? '—' }}</div>
                        </td>
                        <td class="px-4 py-3 text-sm text-slate-600">
                            {{ $product->category?->name ?? '—' }}
                            <div class="text-xs text-slate-400">{{ $product->brand?->name ?? '—' }}</div>
                        </td>
                        <td class="px-4 py-3 text-sm">{{ $product->available_units_count }}/{{ $product->total_units_count }}</td>
                        <td class="px-4 py-3 text-sm">Rp {{ number_format($product->purchase_price ?? 0, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-1 text-xs {{ $product->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.products.edit', $product) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Ubah</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-sm text-slate-500">
                            Produk tidak ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="border-t border-slate-200 px-4 py-3">
        {{ $products->links() }}
    </div>
</div>
@endsection
