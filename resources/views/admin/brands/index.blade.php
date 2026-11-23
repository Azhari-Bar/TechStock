@extends('layouts.admin')

@section('title', 'Merek')
@section('heading', 'Merek')

@section('content')
<div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-5">
    <form method="GET" class="flex gap-2">
        <input name="q" value="{{ request('q') }}" placeholder="Cari merek..." class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 transition">
        <button class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm transition-all duration-200 hover:bg-indigo-50 hover:border-indigo-300">Cari</button>
    </form>
    <a href="{{ route('admin.brands.create') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">+ Tambah Merek</a>
</div>

<div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-200">
    <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Nama</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Deskripsi</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Status</th>
                <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-slate-500">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($brands as $brand)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 text-sm font-medium">{{ $brand->name }}</td>
                    <td class="px-4 py-3 text-sm text-slate-600">{{ $brand->description ?? '-' }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="rounded-full px-2.5 py-0.5 text-xs font-medium {{ $brand->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">{{ $brand->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                    </td>
                    <td class="px-4 py-3 text-right text-sm space-x-2">
                        <a href="{{ route('admin.brands.show', $brand) }}" class="text-indigo-600 hover:text-indigo-800">Lihat</a>
                        <a href="{{ route('admin.brands.edit', $brand) }}" class="text-slate-600 hover:text-slate-900">Edit</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-4 py-12 text-center text-sm text-slate-500">Belum ada merek.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="border-t border-slate-200 px-4 py-3">{{ $brands->links() }}</div>
</div>
@endsection