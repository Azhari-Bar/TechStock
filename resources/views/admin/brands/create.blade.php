@php($value = fn ($key, $default = null) => old($key, data_get($brand ?? null, $key, $default)))
<div class="grid gap-5 md:grid-cols-2">
    <div class="md:col-span-2">
        <label class="block text-sm font-medium mb-1">Nama Merek *</label><input name="name" value="{{ $value('name') }}" required class="w-full rounded-lg border-slate-300 focus:ring-indigo-500 focus:border-indigo-500">@error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div><label class="block text-sm font-medium mb-1">Deskripsi</label><textarea name="description" rows="2" class="w-full rounded-lg border-slate-300">{{ $value('description', $data?->description) }}</textarea></div>
    <div><label class="block text-sm font-medium mb-1">Status</label><label class="inline-flex items-center gap-2"><input type="checkbox" name="is_active" value="1" @checked($value('is_active', $data?->is_active)) class="rounded border-slate-300 text-indigo-600"><span class="text-sm">Merek aktif</span></label></div>
    <div class="md:col-span-2"><label class="block text-sm font-medium mb-1">Catatan</label><textarea name="notes" rows="3" class="w-full rounded-lg border-slate-300">{{ $value('notes', $data?->notes) }}</textarea></div>
</div>