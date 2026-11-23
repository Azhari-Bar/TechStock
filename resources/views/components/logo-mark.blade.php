@php
    $gid = 'ts-mark-' . substr(md5(uniqid('', true)), 0, 8);
@endphp
<svg viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" {{ $attributes }}>
    <defs>
        <linearGradient id="{{ $gid }}-bg" x1="4" y1="0" x2="44" y2="48" gradientUnits="userSpaceOnUse">
            <stop offset="0" stop-color="#6366F1"/>
            <stop offset=".55" stop-color="#7C3AED"/>
            <stop offset="1" stop-color="#3730A3"/>
        </linearGradient>
    </defs>
    <path fill-rule="evenodd" clip-rule="evenodd" fill="url(#{{ $gid }}-bg)"
          d="M13 0h22a13 13 0 0 1 13 13v22a13 13 0 0 1-13 13H13A13 13 0 0 1 0 35V13A13 13 0 0 1 13 0ZM12 13h24l-3 7h-5.5v15.5L20.5 33V20H15l-3-7Z"/>
    <rect x=".75" y=".75" width="46.5" height="46.5" rx="12.25" fill="none" stroke="#FFFFFF" stroke-opacity=".18" stroke-width="1.5"/>
    <path d="M33 36.5l4-4" stroke="#22D3EE" stroke-width="2.5" stroke-linecap="round"/>
</svg>
