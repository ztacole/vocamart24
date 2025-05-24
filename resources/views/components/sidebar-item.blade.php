@props(['link', 'label', 'icon'])

@php
    $isActive = request()->url() === $link || request()->is(ltrim(parse_url($link, PHP_URL_PATH), '/'));
@endphp

<a href="{{ $link }}" class="block mb-3 px-3 py-2 rounded flex items-center gap-2 duration-300 hover:bg-yellow-600 {{ $isActive ? 'bg-yellow-600 text-white font-bold' : 'hover:bg-yellow-500 text-white' }}">
    <img src="{{ asset('images/icons/' . $icon . '.png') }}" alt="" class="w-6 h-6">
    <h3 class="text-lg">{{ $label }}</h3>
</a>
