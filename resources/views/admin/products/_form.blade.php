@php($value = fn ($key, $default = null) => old($key, data_get($product ?? null, $key, $default)))
<div class="grid gap-5 md:grid-cols-2">
    <div class="md:col-span-2">
        <label class="block text-sm font-medium mb-1">Nama Produk</label>
        <input name="name" value="{{ $value('name') }}" required class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
        @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Kategori</label>
        <select name="category_id" required class="w-full rounded-lg border-slate-300">
            <option value="">Pilih kategori</option>
            @foreach($categories as $category)<option value="{{ $category->id }}" @selected($value('category_id') == $category->id)>{{ $category->name }} ({{ $category->type->label() }})</option>@endforeach
        </select>
        @error('category_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Merek</label>
        <select name="brand_id" class="w-full rounded-lg border-slate-300"><option value="">Tanpa merek</option>@foreach($brands as $brand)<option value="{{ $brand->id }}" @selected($value('brand_id') == $brand->id)>{{ $brand->name }}</option>@endforeach</select>
    </div>
    <div><label class="block text-sm font-medium mb-1">Model</label><input name="model" value="{{ $value('model') }}" class="w-full rounded-lg border-slate-300"></div>
    <div><label class="block text-sm font-medium mb-1">Kondisi</label><select name="condition" required class="w-full rounded-lg border-slate-300">@foreach($conditions as $condition)<option value="{{ $condition->value }}" @selected($value('condition', 'new') === $condition->value)>{{ $condition->label() }}</option>@endforeach</select></div>

    <div class="md:col-span-2 mt-2 border-t border-slate-100 pt-4">
        <h3 class="text-sm font-semibold text-slate-700 mb-1">Spesifikasi Laptop / Desktop</h3>
        <p class="text-xs text-slate-500 mb-3">Isi bila produk berupa laptop atau komputer.</p>
    </div>
    <div><label class="block text-sm font-medium mb-1">Prosesor</label><input name="processor" value="{{ $value('processor') }}" class="w-full rounded-lg border-slate-300"></div>
    <div><label class="block text-sm font-medium mb-1">RAM (GB)</label><input type="number" min="0" step="1" name="ram_gb" value="{{ $value('ram_gb') }}" class="w-full rounded-lg border-slate-300"></div>
    <div><label class="block text-sm font-medium mb-1">Penyimpanan (GB)</label><input type="number" min="0" step="1" name="storage_capacity" value="{{ $value('storage_capacity') }}" class="w-full rounded-lg border-slate-300"></div>
    <div><label class="block text-sm font-medium mb-1">Tipe Penyimpanan</label><input name="storage_type" value="{{ $value('storage_type') }}" placeholder="SSD / HDD" class="w-full rounded-lg border-slate-300"></div>
    <div><label class="block text-sm font-medium mb-1">GPU</label><input name="gpu" value="{{ $value('gpu') }}" class="w-full rounded-lg border-slate-300"></div>
    <div><label class="block text-sm font-medium mb-1">Ukuran Layar (inch)</label><input type="number" min="0" step="0.1" name="screen_size" value="{{ $value('screen_size') }}" class="w-full rounded-lg border-slate-300"></div>
    <div><label class="block text-sm font-medium mb-1">Sistem Operasi</label><input name="operating_system" value="{{ $value('operating_system') }}" class="w-full rounded-lg border-slate-300"></div>

    <div class="md:col-span-2 mt-2 border-t border-slate-100 pt-4">
        <h3 class="text-sm font-semibold text-slate-700 mb-1">Spesifikasi Aksesoris / Komponen</h3>
        <p class="text-xs text-slate-500 mb-3">Isi bila produk berupa SSD, RAM, mouse, keyboard, monitor, kabel, dan sejenisnya.</p>
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Jenis Komponen</label>
        <select name="component_type" class="w-full rounded-lg border-slate-300">
            <option value="">— Bukan aksesoris —</option>
            @foreach(['SSD','RAM','Mouse','Keyboard','Monitor','Headset','Webcam','Kabel','Adapter','Charger','Docking','Printer','Scanner','Lainnya'] as $ct)
                <option value="{{ $ct }}" @selected($value('component_type') === $ct)>{{ $ct }}</option>
            @endforeach
        </select>
    </div>
    <div><label class="block text-sm font-medium mb-1">Kapasitas</label><input name="capacity_gb" value="{{ $value('capacity_gb') }}" placeholder="512 GB / 16 GB" class="w-full rounded-lg border-slate-300"></div>
    <div><label class="block text-sm font-medium mb-1">Konektivitas</label><input name="connectivity" value="{{ $value('connectivity') }}" placeholder="NVMe / USB-C / Bluetooth" class="w-full rounded-lg border-slate-300"></div>
    <div><label class="block text-sm font-medium mb-1">Kompatibilitas</label><input name="compatibility" value="{{ $value('compatibility') }}" placeholder="M.2 2280 / DDR4" class="w-full rounded-lg border-slate-300"></div>

    <div class="md:col-span-2 mt-2 border-t border-slate-100 pt-4">
        <h3 class="text-sm font-semibold text-slate-700 mb-1">Nilai Inventaris & Stok</h3>
    </div>
    <div><label class="block text-sm font-medium mb-1">Harga Beli</label><input type="number" min="0" step="0.01" name="purchase_price" value="{{ $value('purchase_price') }}" class="w-full rounded-lg border-slate-300"></div>
    <div><label class="block text-sm font-medium mb-1">Tarif Peminjaman / Hari</label><input type="number" min="0" step="0.01" name="rental_daily_rate" value="{{ $value('rental_daily_rate') }}" class="w-full rounded-lg border-slate-300"></div>
    <div><label class="block text-sm font-medium mb-1">Tarif Peminjaman / Minggu</label><input type="number" min="0" step="0.01" name="rental_weekly_rate" value="{{ $value('rental_weekly_rate') }}" class="w-full rounded-lg border-slate-300"></div>
    <div><label class="block text-sm font-medium mb-1">Tarif Peminjaman / Bulan</label><input type="number" min="0" step="0.01" name="rental_monthly_rate" value="{{ $value('rental_monthly_rate') }}" class="w-full rounded-lg border-slate-300"></div>
    <div><label class="block text-sm font-medium mb-1">Garansi</label><input name="warranty_period" value="{{ $value('warranty_period') }}" placeholder="12 months" class="w-full rounded-lg border-slate-300"></div>
    <div><label class="block text-sm font-medium mb-1">Stok Minimum</label><input type="number" min="0" name="minimum_stock" value="{{ $value('minimum_stock', 0) }}" class="w-full rounded-lg border-slate-300"></div>
    <div class="md:col-span-2"><label class="block text-sm font-medium mb-1">Deskripsi</label><textarea name="description" rows="4" class="w-full rounded-lg border-slate-300">{{ $value('description') }}</textarea></div>
    <div class="md:col-span-2"><label class="inline-flex items-center gap-2"><input type="checkbox" name="is_active" value="1" @checked($value('is_active', true)) class="rounded border-slate-300 text-indigo-600"> <span class="text-sm">Produk aktif</span></label></div>
    <div class="md:col-span-2"><label class="inline-flex items-center gap-2"><input type="checkbox" name="is_borrowable" value="1" @checked($value('is_borrowable', true)) class="rounded border-slate-300 text-indigo-600"> <span class="text-sm">Barang ini dapat dipinjam (tampil di katalog publik)</span></label></div>
    <div class="md:col-span-2 rounded-lg border border-dashed border-indigo-200 bg-indigo-50 p-4">
        <h3 class="mb-2 text-sm font-medium text-indigo-800">Foto Inventaris</h3>
        @if(!empty($product?->image_path))
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="mb-3 h-40 w-full rounded-lg object-contain bg-white">
        @endif
        <input type="file" name="image" accept="image/*" class="w-full rounded-lg border-slate-300 px-3 py-2 text-sm">
        <p class="mt-1 text-xs text-slate-500">JPG, JPEG, PNG, WEBP. Maksimal 2MB.</p>
        @error('image')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
</div>
