@props([
    'href' => null,
    'type' => 'button',
])

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => 'bg-[#d09523] hover:bg-[#f4b942] cursor-pointer font-semibold text-white px-4 py-2 rounded-lg transition-all duration-300 ease-in-out inline-flex items-center justify-center focus:shadow-outline focus:outline-none']) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => 'bg-[#d09523] hover:bg-[#f4b942] cursor-pointer font-semibold text-white px-4 py-2 rounded-lg transition-all duration-300 ease-in-out flex items-center justify-center focus:shadow-outline focus:outline-none']) }}>
        {{ $slot }}
    </button>
@endif
