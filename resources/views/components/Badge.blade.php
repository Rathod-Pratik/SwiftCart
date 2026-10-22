@props([
    'variant' => 'default',
    'size' => 'md',
])

@php
    $variantClasses = match($variant) {
        'primary' => 'bg-[#224e51]/10 text-[#224e51] border border-[#224e51]/20',
        'success' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
        'warning' => 'bg-amber-50 text-amber-700 border border-amber-200',
        'danger', 'error' => 'bg-red-50 text-red-700 border border-red-200',
        'info' => 'bg-sky-50 text-sky-700 border border-sky-200',
        'accent' => 'bg-[#F4C95D]/25 text-[#224e51] border border-[#F4C95D]/40',
        default => 'bg-gray-100 text-gray-700 border border-gray-200',
    };

    $sizeClasses = match($size) {
        'sm' => 'text-[11px] px-2 py-0.5',
        'lg' => 'text-sm px-3.5 py-1.5',
        default => 'text-xs px-2.5 py-1',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 font-semibold rounded-full {$variantClasses} {$sizeClasses}"]) }}>
    {{ $slot }}
</span>
