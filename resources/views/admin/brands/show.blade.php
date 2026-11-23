@extends('layouts.admin')

@section('title', $brand->name)
@section('heading', 'Detail Merek')

@section('content')
<div class="grid gap-6 lg:grid-cols-3">
    <section class="lg:col-span-2 rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <div class="flex justify-between">
            <div>
                <h2 class="text-xl font-bold">{{ $brand->name }}</h2>
                <a href="{{ route('admin.brands.edit', $brand) }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white">Edit</a>
            </div>
        </div>
        <dl class="mt-5 grid grid-cols-2 gap-4 text-sm">
            <div><dt class="text-slate-500">Merek</dt><dd class="font-medium">{{ $brand->name }}</dd></div>
            <div><dt class="text-slate-500">Deskripsi</dt><dd class="font-medium">{{ $brand->description ?? '-' }}</dd></dd>
            <div><dt class="text-slate-500">Status</dt><dd><span class="rounded-full px-2.5 py-0.5 text-xs {{ $brand->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">{{ $brand->is_active ? 'Aktif' : 'Nonaktif' }}</span></dd></div>
            @if($brand->description)<div class="col-span-2"><dt class="text-slate-500">Deskripsi</dt><dd class="font-medium">{{ $brand->description }}</dd></div>@endif
        </dl>
    </section>
    <section class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h3 class="text-sm font-semibold mb-3">Riwayat Inventaris</h3>
        @forelse($brand->products as $product)
            <div class="border-b py-2 last:border-0 text-sm">
                <a href="{{ route('admin.products.show', $product) }}" class="font-medium text-indigo-600 hover:text-indigo-800">{{ $product->name }}</a>
                <div class="text-xs text-slate-500">{{ $product->units->count() }} unit</div>
            </div>
        @empty
            <p class="text-sm text-slate-500">Belum ada produk.</p>
        @endforelse
    </section>
</div>
@endsection