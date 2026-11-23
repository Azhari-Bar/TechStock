@extends('layouts.admin')

@section('title', 'Edit Unit')
@section('heading', 'Edit Unit Inventaris')

@section('content')
<div class="mx-auto max-w-4xl">
    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h2 class="mb-1 text-xl font-bold">Edit Unit: {{ $unit->serial_number }}</h2>
        <p class="mb-5 text-sm text-slate-500">Perbarui identitas fisik, kondisi, status, dan foto unit.</p>
        <form method="POST" action="{{ route('admin.product-units.update', $unit) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            @include('admin.product-units.form', ['selectedProduct' => $unit->product_id])
            <div class="mt-6 flex justify-between">
                <a href="{{ route('admin.product-units.show', $unit) }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Batal</a>
                <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
