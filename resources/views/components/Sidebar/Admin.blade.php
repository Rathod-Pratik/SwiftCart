@php
    $active = 'font-semibold';
    $inactive = 'text-gray-500';
@endphp

<aside
    class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 bg-white overflow-visible">
    <div class="px-5 py-6">

        <ul class="space-y-2">
            {{-- Dashboard --}}
            <li>
                <a href="{{ route('admin.dashboard') }}"
                   class="relative flex items-center gap-3 px-2 py-3 rounded-2xl transition-all duration-300
                   {{ request()->routeIs('admin.dashboard') ? $active : $inactive }}">

                    <x-lucide-layout-dashboard class="w-5 h-5"/>

                    <span>Dashboard</span>

                    @if(request()->routeIs('admin.dashboard'))
                        <div class="absolute -left-5 w-1.5 h-9 bg-[#7ED957] rounded-r-2xl"></div>
                    @endif
                </a>
            </li>

            {{-- Categories --}}
            <li>
                <a href="{{ route('admin.category') }}"
                   class="relative flex items-center gap-3 px-2  py-3 rounded-2xl transition-all duration-300
                   {{ request()->routeIs('admin.category') ? $active : $inactive }}">

                    <x-lucide-layout-grid class="w-5 h-5"/>

                    <span>Categories</span>

                    @if(request()->routeIs('admin.category'))
                        <div class="absolute -left-5 w-1.5 h-9 bg-[#7ED957] rounded-r-2xl"></div>
                    @endif
                </a>
            </li>

            {{-- Products --}}
            <li>
                <a href="{{ route('admin.product') }}"
                   class="relative flex items-center gap-3 px-2 py-3 rounded-2xl transition-all duration-300
                   {{ request()->routeIs('admin.product') ? $active : $inactive }}">

                    <x-lucide-package class="w-5 h-5"/>

                    <span>Products</span>

                    @if(request()->routeIs('admin.product'))
                        <div class="absolute -left-5   w-1.5 h-9 bg-[#7ED957] rounded-r-2xl"></div>
                    @endif
                </a>
            </li>

            {{-- Users --}}
            <li>
                <a href="{{ route('admin.user') }}"
                   class="relative flex items-center gap-3 px-2 py-3 rounded-2xl transition-all duration-300
                   {{ request()->routeIs('admin.user') ? $active : $inactive }}">

                    <x-lucide-users class="w-5 h-5"/>

                    <span>Users</span>

                    @if(request()->routeIs('admin.user'))
                        <div class="absolute -left-5   w-1.5 h-9 bg-[#7ED957] rounded-r-2xl"></div>
                    @endif
                </a>
            </li>

            {{-- Vendors --}}
            <li>
                <a href="{{ route('admin.vendor') }}"
                   class="relative flex items-center gap-3 px-2 py-3 rounded-2xl transition-all duration-300
                   {{ request()->routeIs('admin.vendor') ? $active : $inactive }}">

                    <x-lucide-store class="w-5 h-5"/>

                    <span>Vendors</span>

                    @if(request()->routeIs('admin.vendor'))
                        <div class="absolute -left-5   w-1.5 h-9 bg-[#7ED957] rounded-r-2xl"></div>
                    @endif
                </a>
            </li>

            {{-- Ratings --}}
            <li>
                <a href="{{ route('admin.rating') }}"
                   class="relative flex items-center gap-3 px-2 py-3 rounded-2xl transition-all duration-300
                   {{ request()->routeIs('admin.rating') ? $active : $inactive }}">

                    <x-lucide-star class="w-5 h-5"/>

                    <span>Ratings</span>

                    @if(request()->routeIs('admin.rating'))
                        <div class="absolute -left-5   w-1.5 h-9 bg-[#7ED957] rounded-r-2xl"></div>
                    @endif
                </a>
            </li>

            {{-- Contact --}}
            <li>
                <a href="{{ route('admin.contact') }}"
                   class="relative flex items-center gap-3 px-2 py-3 rounded-2xl transition-all duration-300
                   {{ request()->routeIs('admin.contact') ? $active : $inactive }}">

                    <x-lucide-mail class="w-5 h-5"/>

                    <span>Contact</span>

                    @if(request()->routeIs('admin.contact'))
                        <div class="absolute -left-5   w-1.5 h-9 bg-[#7ED957] rounded-r-2xl"></div>
                    @endif
                </a>
            </li>

            {{-- Logout --}}
            <li class="pt-6">
                <button
                    onclick="Logout()"
                    class="flex items-center gap-3 w-full px-2 py-3 rounded-2xl text-red-500 hover:bg-red-50 transition">

                    <x-lucide-log-out class="w-5 h-5"/>

                    <span>Logout</span>

                </button>
            </li>

        </ul>

    </div>

</aside>