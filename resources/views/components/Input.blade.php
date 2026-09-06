@props([
    'type' => 'text',
    'variant' => 'default',
])

@php
    $classes = match($variant) {
        'contact' => 'w-full px-4 py-2 rounded-md bg-[#3d6f6d] text-white placeholder-white/70 focus:outline-none',
        default => 'w-full px-8 py-4 rounded-lg font-medium bg-gray-100 border border-gray-200 placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white',
    };
@endphp

<input
    type="{{ $type }}"
    {{ $attributes->merge(['class' => $classes]) }}
>