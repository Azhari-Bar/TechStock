@extends('layouts.admin')

@section('title', $category->name)
@section('heading', 'Detail Kategori')

@section('content')
<div class="grid gap-6 lg:grid-cols-3">
    <section class="lg:col-span-2 rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <div class="flex justify-between"><h2 class="text-xl font-bold">{{ $category->name }}</h2><a href="{{ route('admin.categories.edit', $category) }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white">Ubah</a></div>
        <dl class="mt-5 grid grid-cols-2 gap-4 text-sm">
            <div><dt class="text-slate-500">Tipe</dt><dd class="font-medium">{{ $category->type->label() }}</dd></div>
            <div><dt class="text-slate-500">Slug</dt><dd class="font-medium font-mono text-sm">{{ $category->slug }}</dd></div>
            <div><dt class="text-slate-500">Status</dt><dd><span class="rounded-full px-2.5 py-0.5 text-xs {{ $category->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">{{ $category->is_active ? 'Aktif' : 'Nonaktif' }}</span></dd></div>
            @if($category->description)<div class="col-span-2"><dt class="text-slate-500">Deskripsi</dt><dd class="font-medium">{{ $category->description }}</dd></div>@endif
        </dl>
    </section>
    <section class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h3 class="text-sm font-semibold mb-3">Ketersediaan Stok</h3>
        <div class="grid grid-cols-2 gap-3 text-sm text-center">
            <div class="bg-slate-50 rounded-lg p-3"><p class="text-xs text-slate-500">Total</p><p class="text-lg font-bold text-slate-700">{{ $category->products_count }}</p></div>
            <div class="bg-emerald-50 rounded-lg p-3"><p class="text-xs text-emerald-600">Tersedia</p><p class="text-lg font-bold text-emerald-700">{{
                $category->products->sum(fn($p) => $p->units()->where('status','available')->count())
            }}</p></div>
        </div>
        <div class="mt-4 space-y-1 text-sm">
            @foreach($category->products->take(3) as $product)
                <a href="{{ route('admin.products.edit', $product) }}" class="block rounded-lg p-2 hover:bg-slate-50">{{ $product->name }} ({{ $product->units->where('status','available')->count() }}/{{ $product->units->count() }})</a>
            @endforeach
            @if($category->products->count() > 3)
                <div class="text-xs text-slate-500">+{{ $category->products->count() - 3 }} lainnya</div>
            @endif
        </div>
    </section>
</div>
@endsection
