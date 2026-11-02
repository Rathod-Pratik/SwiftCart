@php
    $type = collect(['success', 'error', 'warning', 'info'])
        ->first(fn ($key) => session()->has($key));
@endphp

<div
    id="toast"
    role="status"
    aria-live="polite"
    class="fixed bottom-5 right-5 z-[100] hidden max-w-sm items-center gap-3 rounded-xl border-l-4 px-4 py-3 shadow-xl transition-all duration-300"
>
    <span id="toast-icon" class="shrink-0"></span>
    <p id="toast-message" class="flex-1 text-sm font-medium"></p>
    <button
        type="button"
        onclick="window.hideToast?.()"
        class="cursor-pointer rounded-md p-1 opacity-80 transition hover:bg-black/10 hover:opacity-100"
        aria-label="Close notification"
    >
        <x-lucide-x class="h-4 w-4" />
    </button>
</div>

<script>
    (() => {
        const toast = document.getElementById('toast');
        const message = document.getElementById('toast-message');
        const icon = document.getElementById('toast-icon');
        const styles = {
            success: ['bg-[#224E51]', 'text-white', 'border-[#F4C95D]'],
            error: ['bg-red-600', 'text-white', 'border-red-300'],
            warning: ['bg-[#F4C95D]', 'text-[#224E51]', 'border-[#224E51]/20'],
            info: ['bg-[#E8F0EF]', 'text-[#224E51]', 'border-[#224E51]/20'],
        };
        const icons = {
            success: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 4 4L19 6"/></svg>',
            error: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6m0-6 6 6"/></svg>',
            warning: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z"/><path d="M12 9v4m0 4h.01"/></svg>',
            info: '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4m0-4h.01"/></svg>',
        };
        let timeout;

        window.hideToast = () => {
            if (!toast) return;
            toast.classList.add('hidden');
            toast.classList.remove('flex', 'translate-x-4', 'opacity-0');
        };

        window.showToast = (text, toastType = 'success') => {
            if (!toast || !message || !icon) return;
            const type = styles[toastType] ? toastType : 'info';
            clearTimeout(timeout);
            toast.classList.remove('hidden', 'translate-x-4', 'opacity-0');
            toast.classList.add('flex');
            toast.classList.remove(...Object.values(styles).flat());
            toast.classList.add(...styles[type]);
            message.textContent = text;
            icon.innerHTML = icons[type];
            timeout = setTimeout(window.hideToast, 3500);
        };

        @if ($type)
            window.showToast(@json(session($type)), @json($type));
        @endif
    })();
</script>