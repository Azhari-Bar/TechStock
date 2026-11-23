@extends('layouts.admin')

@section('title', 'Tambah Produk')
@section('heading', 'Tambah Produk')

@section('content')
<div class="max-w-4xl rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.products._form')
        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('admin.products.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium hover:bg-slate-50">Batal</a>
            <button class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Simpan Produk</button>
        </div>
    </form>
</div>
@endsection