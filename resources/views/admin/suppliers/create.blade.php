@extends('layouts.admin')

@section('title', 'Tambah Supplier')
@section('heading', 'Tambah Supplier')

@section('content')
<div class="max-w-xl">
    <a href="{{ route('admin.suppliers.index') }}" class="mb-4 inline-block text-sm font-medium text-indigo-600 hover:text-indigo-800">← Kembali</a>

    <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 p-6">
        <form method="POST" action="{{ route('admin.suppliers.store') }}">
            @csrf

            <div class="grid gap-5 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">Nama Supplier *</label>
                    <input name="name" value="{{ old('name') }}" required class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Nama Kontak</label>
                    <input name="contact_person" value="{{ old('contact_person') }}" placeholder="Contoh: Budi Santoso" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('contact_person')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Nomor Telepon</label>
                    <input name="phone" type="tel" value="{{ old('phone') }}" placeholder="08123456789" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input name="email" type="email" value="{{ old('email') }}" placeholder="supplier@example.com" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">Alamat</label>
                    <textarea name="address" rows="3" placeholder="Alamat lengkap supplier" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('address') }}</textarea>
                    @error('address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">Catatan</label>
                    <textarea name="notes" rows="3" placeholder="Catatan tambahan mengenai supplier" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                    @error('notes')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label class="inline-flex items-center gap-2 mt-1"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', true)) class="rounded border-slate-300 text-indigo-600"> <span class="text-sm">Supplier aktif</span></label>
                </div>
                <div class="md:col-span-2">
                    <button type="submit" class="rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 transition">Simpan Supplier</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
