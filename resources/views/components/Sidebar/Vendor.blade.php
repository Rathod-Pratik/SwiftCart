@php
    $active = 'bg-[#fee21f]/60 text-[#64883e] shadow-lg shadow-[#64883e]/10';
    $inactive = 'text-[#64883e] hover:bg-[#8cb920]/15 hover:text-[#64883e] hover:translate-x-1';
    $items = [
        ['route' => 'vendor.dashboard', 'label' => 'Dashboard', 'icon' => 'layout-dashboard'],
        ['route' => 'vendor.product', 'label' => 'Products', 'icon' => 'package'],
        ['route' => 'vendor.order', 'label' => 'Orders', 'icon' => 'shopping-bag'],
        ['route' => 'vendor.rating*', 'label' => 'Ratings', 'icon' => 'star'],
        ['route' => 'vendor.query*', 'label' => 'Queries', 'icon' => 'message-square'],
        ['route' => 'vendor.profile', 'label' => 'Profile', 'icon' => 'user-round'],
    ];
@endphp

<aside id="sidebar"
    class="fixed left-0 top-0 z-40 h-screen w-64 -translate-x-full border-r border-[#8cb920]/20 bg-[#f7fbe9] pt-20 text-[#64883e] shadow-xl transition-transform duration-300 lg:translate-x-0"
    aria-label="Vendor sidebar">
    <div class="px-4 py-6">
        <p class="mb-4 px-3 text-xs font-semibold uppercase tracking-[0.2em] text-[#64883e]">Seller workspace</p>
        <ul class="space-y-2 text-sm font-medium">
            @foreach ($items as $item)
                @php($isActive = request()->routeIs($item['route']))
                <li>
                    <a href="{{ route(str_replace('*', '', $item['route'])) }}"
                        class="group relative flex items-center gap-3 rounded-xl px-3 py-3 transition duration-300 {{ $isActive ? $active : $inactive }}">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg transition duration-300 {{ $isActive ? 'bg-[#64883e] text-[#fee21f]' : 'bg-[#8cb920]/15 text-[#64883e] group-hover:bg-[#fee21f] group-hover:text-[#64883e] group-hover:scale-105' }}">
                            <x-dynamic-component :component="'lucide-' . $item['icon']" class="h-5 w-5" />
                        </span>
                        <span>{{ $item['label'] }}</span>
                        @if ($isActive)
                            <span class="absolute -right-4 h-8 w-1 rounded-l-full bg-[#fee21f]"></span>
                        @endif
                    </a>
                </li>
            @endforeach
            <li class="pt-6">
                <button onclick="Logout()" type="button"
                    class="group flex w-full items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-[#f6caca] transition duration-300 hover:translate-x-1 hover:bg-red-400/15 hover:text-white">
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-red-400/15 text-[#f6caca] transition duration-300 group-hover:bg-red-400 group-hover:text-white">
                        <x-lucide-log-out class="h-5 w-5" />
                    </span>
                    <span>Logout</span>
                </button>
            </li>
        </ul>
    </div>
</aside>
