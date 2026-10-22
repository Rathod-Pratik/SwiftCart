@props([
    'title' => null,
    'subtitle' => null,
])

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-gray-100 bg-white p-5 sm:p-6 shadow-sm']) }}>
    @if ($title || $subtitle || isset($headerAction))
        <div class="mb-5 flex flex-col gap-2 border-b border-gray-100 pb-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                @if ($title)
                    <h3 class="text-base sm:text-lg font-bold text-gray-900">{{ $title }}</h3>
                @endif
                @if ($subtitle)
                    <p class="mt-0.5 text-xs sm:text-sm text-gray-500">{{ $subtitle }}</p>
                @endif
            </div>
            @if (isset($headerAction))
                <div class="shrink-0">
                    {{ $headerAction }}
                </div>
            @endif
        </div>
    @endif

    {{ $slot }}

    @if (isset($footer))
        <div class="mt-5 border-t border-gray-100 pt-4">
            {{ $footer }}
        </div>
    @endif
</div>
