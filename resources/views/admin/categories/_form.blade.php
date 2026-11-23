@php($value = fn ($key, $default = null) => old($key, data_get($category ?? null, $key, $default)))
<div class="grid gap-5 md:grid-cols-2">
    <div class="md:col-span-2">
        <label class="block text-sm font-medium mb-1">Nama Kategori *</label>
        <input name="name" value="{{ $value('name') }}" required class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
        @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Tipe Produk *</label>
        <select name="type" required class="w-full rounded-lg border-slate-300">
            @foreach(\App\Enums\ProductType::cases() as $type)
                <option value="{{ $type->value }}" @selected($value('type', 'laptop') === $type->value)>{{ $type->label() }}</option>
            @endforeach
        </select>
        @error('type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Status</label>
        <label class="inline-flex items-center gap-2 mt-1"><input type="checkbox" name="is_active" value="1" @checked($value('is_active', true)) class="rounded border-slate-300 text-indigo-600"><span class="text-sm">Kategori aktif</span></label>
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-medium mb-1">Deskripsi</label>
        <textarea name="description" rows="3" class="w-full rounded-lg border-slate-300">{{ $value('description') }}</textarea>
        @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
</div>