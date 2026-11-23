@extends('layouts.admin')

@section('title', 'Kategori')
@section('heading', 'Kategori')

@section('content')
<div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-5">
    <form method="GET" class="flex gap-2">
        <input name="q" value="{{ request('q') }}" placeholder="Cari kategori..." class="rounded-lg border-slate-300 text-sm">
        <button class="rounded-lg border border-slate-300 px-4 py-2 text-sm hover:bg-white">Cari</button>
    </form>
    <a href="{{ route('admin.categories.create') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-center text-sm font-medium text-white hover:bg-indigo-700">+ Tambah Kategori</a>
</div>

<div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-200">
    <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Nama</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Tipe</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Produk</th>
                <th class="px-4 py-3 text-center text-xs font-semibold uppercase text-slate-500">Status</th>
                <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-slate-500">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($categories as $category)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 text-sm">
                        <a href="{{ route('admin.categories.show', $category) }}" class="font-medium text-slate-900 hover:text-indigo-600">{{ $category->name }}</a>
                        <div class="text-xs text-slate-400">{{ $category->slug }}</div>
                    </td>
                    <td class="px-4 py-3 text-sm text-slate-600">{{ $category->type->label() }}</td>
                    <td class="px-4 py-3 text-sm text-slate-600">{{ $category->products_count }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium
                            {{ $category->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                            {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right text-sm space-x-2">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="text-indigo-600 hover:text-indigo-800">Ubah</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-4 py-12 text-center text-sm text-slate-500">Belum ada kategori.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="border-t border-slate-200 px-4 py-3">{{ $categories->links() }}</div>
</div>
@endsection