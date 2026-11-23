@extends('layouts.admin')

@section('title', 'Tambah Unit')
@section('heading', 'Tambah Unit Inventaris')

@section('content')
<div class="mx-auto max-w-4xl">
    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h2 class="mb-1 text-xl font-bold">Form Tambah Unit</h2>
        <p class="mb-5 text-sm text-slate-500">Satu unit = satu barang fisik dengan serial number dan foto sendiri.</p>
        <form method="POST" action="{{ route('admin.product-units.store') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.product-units.form', ['unit' => null, 'selectedProduct' => $selectedProduct])
            <div class="mt-6 flex justify-between">
                <a href="{{ route('admin.product-units.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Batal</a>
                <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">Simpan Unit</button>
            </div>
        </form>
    </div>
</div>
@endsection
