```blade
@if (session('success') || session('error') || session('warning') || session('info'))
    @php
        $type = collect(['success', 'error', 'warning', 'info'])
            ->first(fn ($key) => session()->has($key));

        $styles = [
            'success' => 'bg-[#224E51] text-white border-[#F4C95D]',
            'error' => 'bg-red-600 text-white border-red-300',
            'warning' => 'bg-[#F4C95D] text-[#224E51] border-[#224E51]/20',
            'info' => 'bg-[#E8F0EF] text-[#224E51] border-[#224E51]/20',
        ];

        $icons = [
            'success' => 'check-circle',
            'error' => 'x-circle',
            'warning' => 'alert-triangle',
            'info' => 'info',
        ];
    @endphp

    <div
        id="toast"
        role="status"
        aria-live="polite"
        class="fixed right-5 top-5 z-[100] flex max-w-sm items-center gap-3 rounded-xl border-l-4 px-4 py-3 shadow-xl transition-all duration-300 {{ $styles[$type] }}"
    >
        <x-dynamic-component :component="'lucide-' . $icons[$type]" class="h-5 w-5 shrink-0" />

        <p class="flex-1 text-sm font-medium">
            {{ session($type) }}
        </p>

        <button
            type="button"
            onclick="document.getElementById('toast')?.remove()"
            class="rounded-md p-1 opacity-80 transition hover:bg-black/10 hover:opacity-100"
            aria-label="Close notification"
        >
            <x-lucide-x class="h-4 w-4" />
        </button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toast = document.getElementById('toast');

            if (!toast) return;

            setTimeout(() => {
                toast.classList.add('translate-x-4', 'opacity-0');

                setTimeout(() => toast.remove(), 300);
            }, 3500);
        });
    </script>
@endif
```