@extends('layouts.admin')

@section('title', 'Catat Pergerakan Stok')
@section('heading', 'Catat Pergerakan Stok')

@section('content')
<div class="max-w-2xl">
    <a href="{{ route('admin.stock-movements.index') }}" class="mb-4 inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4l6 6m0 0l-6 6m6-6H6"/></svg>
        Kembali ke Riwayat Stok
    </a>

    <div class="bg-white/95 rounded-2xl shadow-lg ring-1 ring-slate-200/60 p-6 backdrop-blur-md space-y-6">

        {{-- Warning Box --}}
        <div class="rounded-xl bg-gradient-to-r from-amber-50 via-orange-50 to-amber-50 border border-amber-200/70 p-5 text-sm">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg bg-amber-100/80 flex items-center justify-center shrink-0 ring-1 ring-amber-200/50">
                    <svg class="w-4.5 h-4.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M3 6h18v12H3V6z"/></svg>
                </div>
                <div class="space-y-2">
                    <p class="font-semibold text-amber-900">Pilih jenis yang tepat</p>
                    <ul class="space-y-1.5 text-xs leading-relaxed text-amber-800/90">
                        <li class="flex items-start gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 mt-1 shrink-0"></span>
                            <span><b>Barang Masuk:</b> untuk stok baru dari supplier / pembelian eksternal.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="w-2 h-2 rounded-full bg-blue-500 mt-1 shrink-0"></span>
                            <span><b>Keluar / Rusak / Hilang / Penyesuaian:</b> untuk koreksi stok fisik yang tidak terkait peminjaman.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="w-2 h-2 rounded-full bg-purple-500 mt-1 shrink-0"></span>
                            <span><b>Jangan pakai form ini untuk peminjaman/pengembalian.</b> <a href="{{ route('admin.rentals.index') }}" class="underline font-semibold text-indigo-700 hover:text-indigo-900">Buka menu Peminjaman</a> agar serah terima, deposit, dan denda tercatat otomatis.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.stock-movements.store') }}">
            @csrf

            <div class="grid gap-5">
                {{-- Jenis Pergerakan --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Jenis Pergerakan *</label>
                    <div class="grid gap-2 sm:grid-cols-3">
                        @foreach([
                            ['value' => 'stock_in',    'label' => 'Barang Masuk',   'desc' => 'Tambah stok baru'],
                            ['value' => 'stock_out',   'label' => 'Barang Keluar',  'desc' => 'Keluar dari inventaris'],
                            ['value' => 'damaged',      'label' => 'Rusak',          'desc' => 'Unit tidak layak'],
                            ['value' => 'lost',         'label' => 'Hilang',         'desc' => 'Unit hilang'],
                            ['value' => 'adjustment',   'label' => 'Penyesuaian',    'desc' => 'Koreksi stok'],
                        ] as $opt)
                            <label class="flex items-start gap-2.5 rounded-lg border border-slate-200 bg-slate-50/50 p-3 cursor-pointer hover:border-indigo-300 hover:bg-indigo-50/50 transition-all group has-[:checked]:border-indigo-400 has-[:checked]:bg-indigo-50/80 has-[:checked]:ring-1 has-[:checked]:ring-indigo-300">
                                <input type="radio" name="movement_type" value="{{ $opt['value'] }}" @checked(old('movement_type', 'stock_in') === $opt['value'])
                                    class="mt-0.5 h-4 w-4 border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="flex-1 min-w-0">
                                    <span class="block text-sm font-semibold text-slate-800 group-[[&>input:checked~span]:text-indigo-800]">{{ $opt['label'] }}</span>
                                    <span class="block text-xs text-slate-500 mt-0.5">{{ $opt['desc'] }}</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                    @error('movement_type')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                {{-- Produk --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Produk *</label>
                    <select name="product_id" required class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition">
                        <option value="">Pilih produk…</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>{{ $product->name }} — {{ $product->available_units_count }} unit tersedia</option>
                        @endforeach
                    </select>
                    @error('product_id')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="grid sm:grid-cols-2 gap-5">
                    {{-- Supplier --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Supplier (opsional)</label>
                        <select name="supplier_id" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition">
                            <option value="">Tanpa supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" @selected(old('supplier_id') == $supplier->id)>{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                        @error('supplier_id')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Harga Satuan --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Harga Satuan (Rp)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-400 pointer-events-none">Rp</span>
                            <input type="number" name="unit_price" min="0" step="0.01" value="{{ old('unit_price') }}"
                                class="w-full rounded-lg border-slate-300 pl-8 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition">
                        </div>
                        @error('unit_price')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Serial Numbers --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Serial Number / Kode Unit</label>
                    <p class="text-xs text-slate-500 mb-2">
                        <span class="font-medium text-slate-600">Barang Masuk:</span> satu baris = satu unit baru. Kosongkan untuk generate serial otomatis.
                        <br>
                        <span class="font-medium text-slate-600">Jenis lain:</span> tulis serial unit yang sudah ada (dipisah baris atau koma).
                    </p>
                    <textarea name="serial_numbers_text" rows="3" placeholder="SN-001&#10;SN-002&#10;SN-003"
                        class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition font-mono text-sm leading-relaxed">{{ old('serial_numbers_text') }}</textarea>
                    @error('serial_numbers_text')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                {{-- Jumlah (hanya saat barang masuk tanpa serial) --}}
                <div class="max-w-[200px]">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Jumlah (tanpa serial)</label>
                    <input type="number" name="quantity" min="1" value="{{ old('quantity', 1) }}"
                        class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition">
                    @error('quantity')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                {{-- Catatan --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Catatan</label>
                    <textarea name="notes" rows="2" placeholder="Keterangan tambahan…"
                        class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition">{{ old('notes') }}</textarea>
                    @error('notes')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                {{-- Buttons --}}
                <div class="flex items-center gap-3 pt-2 border-t border-slate-100">
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 hover:shadow-md active:scale-[0.98] transition-all focus:outline-none focus:ring-4 focus:ring-indigo-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan
                    </button>
                    <a href="{{ route('admin.stock-movements.index') }}" class="text-sm text-slate-500 hover:text-slate-700 transition-colors">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
