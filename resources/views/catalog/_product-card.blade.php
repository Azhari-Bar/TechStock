@php($firstUnit = $product->units->first())
<a href="{{ route('catalog.show', $product) }}" class="group block animate-slide-up" style="animation-delay: {{ $delay ?? 0 }}s; animation-fill-mode: both">
    <div class="tech-card overflow-hidden h-full flex flex-col group-hover:-translate-y-2 group-hover:ring-2 group-hover:ring-indigo-300 group-hover:shadow-2xl transition-all duration-300">
        <div class="aspect-[4/3] overflow-hidden bg-slate-50 relative p-8 group-hover:bg-indigo-50 transition-colors duration-300">
            <img src="{{ $product->getImageUrlAttribute() }}" alt="{{ $product->name }}" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-700 ease-out">
            @if($product->condition === 'new')
                <div class="absolute top-4 left-4">
                    <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-[10px] font-bold uppercase tracking-widest bg-amber-400 text-amber-950 shadow-lg">
                        Unit Baru
                    </span>
                </div>
            @endif
            @if(!$product->is_borrowable && request('mode') !== 'borrow')
                <div class="absolute top-4 right-4">
                    <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-[10px] font-bold uppercase tracking-widest bg-slate-900/85 text-white shadow-lg">
                        Tidak Dipinjamkan
                    </span>
                </div>
            @endif
        </div>
        <div class="p-6 flex-1 flex flex-col bg-white">
            <p class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.2em] mb-2">{{ $product->brand?->name ?? '—' }}</p>
            <h3 class="font-bold text-slate-900 group-hover:text-indigo-600 transition-colors line-clamp-2 text-lg mb-4 leading-snug">{{ $product->name }}</h3>

            @if($firstUnit && $firstUnit->room)
                <div class="mb-3 flex items-center gap-1.5 text-xs text-indigo-600 bg-indigo-50 px-2.5 py-1.5 rounded-lg">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span class="font-medium">{{ $firstUnit->room }}</span>
                    @if($firstUnit->rack)<span class="text-slate-400">/</span><span class="font-medium">{{ $firstUnit->rack }}</span>@endif
                </div>
            @endif

            <div class="mt-auto pt-5 flex items-end justify-between border-t border-slate-100">
                <div>
                    <p class="text-[10px] text-slate-400 font-bold uppercase mb-1">Unit Tersedia</p>
                    <p class="text-sm font-extrabold {{ $product->is_borrowable ? 'text-emerald-700' : 'text-slate-600' }}">{{ $product->available_units_count ?? 0 }} unit tersedia</p>
                </div>
                <div class="w-10 h-10 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-300 group-hover:bg-indigo-600 group-hover:text-white group-hover:rotate-45 transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25"/></svg>
                </div>
            </div>
        </div>
    </div>
</a>
