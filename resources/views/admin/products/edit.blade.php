@extends('layouts.admin')

@section('title', 'Ubah Produk')
@section('heading', 'Ubah Produk')

@section('content')
<div class="max-w-4xl rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('admin.products._form')
        <div class="mt-6 flex justify-between gap-3">
            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini?')">@csrf @method('DELETE')<button class="rounded-lg px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50">Hapus</button></form>
            <div class="flex gap-3"><a href="{{ route('admin.products.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium hover:bg-slate-50">Batal</a><button class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Simpan Perubahan</button></div>
        </div>
    </form>
</div>
@endsection