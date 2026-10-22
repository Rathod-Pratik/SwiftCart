@php
    $active = 'bg-[#F4C95D]/15 text-[#F4C95D] shadow-lg shadow-black/10';
    $inactive = 'text-[#E1EBE8] hover:bg-[#F5F7F4]/10 hover:text-[#F4C95D] hover:translate-x-1';
@endphp

<aside
    class="fixed left-0 top-0 z-40 h-screen w-64 -translate-x-full bg-[#224e51] pt-20 text-[#E1EBE8] shadow-xl transition-transform duration-300 lg:translate-x-0"
    aria-label="Admin sidebar">
    <div class="px-4 py-6">
        <ul class="space-y-2">
            @php
                $items = [
                    ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'layout-dashboard'],
                    ['route' => 'admin.category', 'label' => 'Categories', 'icon' => 'layout-grid'],
                    ['route' => 'admin.product', 'label' => 'Products', 'icon' => 'package'],
                    ['route' => 'admin.user', 'label' => 'Users', 'icon' => 'users'],
                    ['route' => 'admin.vendor', 'label' => 'Vendors', 'icon' => 'store'],
                    ['route' => 'admin.rating', 'label' => 'Ratings', 'icon' => 'star'],
                    ['route' => 'admin.query*', 'label' => 'Queries', 'icon' => 'message-square'],
                    // ['route' => 'admin.notification', 'label' => 'Notifications', 'icon' => 'bell'],
                    // ['route' => 'admin.profile', 'label' => 'Profile', 'icon' => 'user-round'],
                ];
            @endphp

            @foreach ($items as $item)
                @php($isActive = request()->routeIs($item['route']))
                <li>
                    <a href="{{ route(str_replace('*', '', $item['route'])) }}"
                        class="group relative flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition-all duration-300 {{ $isActive ? $active : $inactive }}">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg transition-all duration-300 {{ $isActive ? 'bg-[#F4C95D] text-[#224E51]' : 'bg-white/10 text-[#E1EBE8] group-hover:bg-[#F4C95D] group-hover:text-[#224E51] group-hover:scale-105' }}">
                            <x-dynamic-component :component="'lucide-' . $item['icon']" class="h-5 w-5" />
                        </span>

                        <span>{{ $item['label'] }}</span>

                        @if ($isActive)
                            <span class="absolute -right-4 h-8 w-1 rounded-l-full bg-[#F4C95D]"></span>
                        @endif
                    </a>
                </li>
            @endforeach

            <li class="pt-6">
                <button onclick="Logout()" type="button"
                    class="group flex w-full items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-[#F6CACA] transition-all duration-300 hover:translate-x-1 hover:bg-[#DC2626]/15 hover:text-white">
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#DC2626]/15 text-[#F6CACA] transition-all duration-300 group-hover:bg-[#DC2626] group-hover:text-white">
                        <x-lucide-log-out class="h-5 w-5" />
                    </span>

                    <span>Logout</span>
                </button>
            </li>
        </ul>
    </div>
</aside>