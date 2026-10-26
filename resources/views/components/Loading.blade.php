@props([
    'fullscreen' => false,
])

<div {{ $attributes->merge([
    'id' => $fullscreen ? 'global-preloader' : null,
    'class' => $fullscreen
        ? 'fixed inset-0 z-50 flex items-center justify-center bg-white transition-opacity duration-500'
        : 'flex items-center justify-center py-4 bg-transparent',
]) }}>
    <div class="flex items-center justify-center">
        <div class="w-12 h-12 border-[3px] border-[#64883e] border-t-transparent rounded-full animate-spin"></div>
    </div>
</div>
