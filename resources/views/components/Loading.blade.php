<div {{ $attributes->merge([
    'id' => 'global-preloader', 
    'class' => 'fixed inset-0 z-50 flex items-center justify-center bg-white transition-opacity duration-500'
]) }}>
    <div class="flex items-center justify-center">
        <div class="w-12 h-12 border-[3px] border-[#64883e] border-t-transparent rounded-full animate-spin"></div>
    </div>
</div>
