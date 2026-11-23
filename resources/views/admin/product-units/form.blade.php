@php($unit = $unit ?? null)
<div class="grid grid-cols-1 gap-4 md:grid-cols-2">
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Inventaris *</label>
        <select name="product_id" required class="w-full rounded-lg border-slate-300">
            <option value="">Pilih inventaris</option>
            @foreach($products as $prod)
                <option value="{{ $prod->id }}" @selected(old('product_id', $selectedProduct ?? $unit?->product_id) == $prod->id)>{{ $prod->name }}</option>
            @endforeach
        </select>
        @error('product_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Nomor Seri *</label>
        <input name="serial_number" required value="{{ old('serial_number', $unit?->serial_number) }}" class="w-full rounded-lg border-slate-300">
        @error('serial_number')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Ruangan / Area</label>
        <input name="room" value="{{ old('room', $unit?->room) }}" placeholder="Contoh: Gudang Utama" class="w-full rounded-lg border-slate-300">
        @error('room')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Rak</label>
        <input name="rack" value="{{ old('rack', $unit?->rack) }}" placeholder="Contoh: Rak A" class="w-full rounded-lg border-slate-300">
        @error('rack')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Tingkat Rak</label>
        <input name="shelf" value="{{ old('shelf', $unit?->shelf) }}" placeholder="Contoh: Tingkat 2" class="w-full rounded-lg border-slate-300">
        @error('shelf')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Kotak / Posisi</label>
        <input name="bin" value="{{ old('bin', $unit?->bin) }}" placeholder="Contoh: Box 03" class="w-full rounded-lg border-slate-300">
        @error('bin')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Status *</label>
        <select name="status" required class="w-full rounded-lg border-slate-300">
            @foreach(\App\Enums\UnitStatus::cases() as $status)
                <option value="{{ $status->value }}" @selected(old('status', $unit?->status?->value ?? 'available') === $status->value)>{{ $status->label() }}</option>
            @endforeach
        </select>
        @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Kondisi Fisik *</label>
        <input name="physical_condition" required value="{{ old('physical_condition', $unit?->physical_condition ?? 'baik') }}" class="w-full rounded-lg border-slate-300">
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Grade</label>
        <input name="grade" value="{{ old('grade', $unit?->grade) }}" placeholder="A / B / C" class="w-full rounded-lg border-slate-300">
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Harga Beli (Rp)</label>
        <input type="number" min="0" step="0.01" name="acquisition_price" value="{{ old('acquisition_price', $unit?->acquisition_price ?? 0) }}" class="w-full rounded-lg border-slate-300">
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Tanggal Diterima</label>
        <input type="date" name="received_at" value="{{ old('received_at', $unit?->received_at?->format('Y-m-d')) }}" class="w-full rounded-lg border-slate-300">
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Garansi Hingga</label>
        <input type="date" name="warranty_until" value="{{ old('warranty_until', $unit?->warranty_until?->format('Y-m-d')) }}" class="w-full rounded-lg border-slate-300">
    </div>
    <div class="md:col-span-2">
        <label class="mb-1 block text-sm font-medium text-slate-700">Catatan Kondisi</label>
        <textarea name="notes" rows="3" class="w-full rounded-lg border-slate-300">{{ old('notes', $unit?->notes) }}</textarea>
    </div>
    <div class="rounded-lg border border-dashed border-indigo-200 bg-indigo-50 p-4 md:col-span-2">
        <h3 class="mb-2 text-sm font-medium text-indigo-800">Foto Unit</h3>
        @if($unit?->image_path)
            <img src="{{ asset('storage/'.$unit->image_path) }}" alt="Unit {{ $unit->serial_number }}" class="mb-3 h-40 w-full rounded-lg bg-white object-contain">
        @endif
        <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="w-full rounded-lg border-slate-300 bg-white px-3 py-2 text-sm">
        <p class="mt-1 text-xs text-slate-500">Opsional. JPG, PNG, WEBP, maksimal 2MB.</p>
        @error('image')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
</div>
