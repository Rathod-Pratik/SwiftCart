@extends('layouts.app')

@section('title', 'My Profile - SwiftCart')

@section('content')
    @php
        /** @var \App\Models\User $user */
        $user = $user ?? auth()->user();
        $orders = $orders ?? \App\Models\Order::query()->where('user_id', $user->id)->with('items.product')->latest()->get();
        $stats = $stats ?? [
            'orders_count' => $orders->count(),
            'wishlist_count' => \App\Models\Wishlist::where('user_id', $user->id)->count(),
            'cart_count' => \App\Models\Cart::where('user_id', $user->id)->count(),
            'reviews_count' => \App\Models\Review::where('user_id', $user->id)->count(),
        ];

        $initials = collect(explode(' ', $user->name ?? 'User'))
            ->filter()
            ->take(2)
            ->map(fn($part) => mb_substr($part, 0, 1))
            ->join('');
        $initials = strtoupper($initials ?: 'U');

        $firstName = explode(' ', $user->name ?? 'Customer')[0];
    @endphp

    <div class="min-h-screen bg-[#f8fafb] py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Breadcrumbs Navigation --}}
            <nav class="flex items-center gap-2 text-xs sm:text-sm text-gray-500 mb-6" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-[#204d4f] transition flex items-center gap-1">
                    <x-lucide-home class="h-3.5 w-3.5" />
                    <span>Home</span>
                </a>
                <span class="text-gray-400">/</span>
                <span class="text-gray-400">My Account</span>
                <span class="text-gray-400">/</span>
                <span class="font-semibold text-[#204d4f]">Customer Profile</span>
            </nav>

            {{-- Global Dynamic Notification Alert --}}
            <div id="profile-alert" class="mb-6 hidden items-center justify-between rounded-2xl px-5 py-4 text-sm font-medium shadow-sm transition-all duration-300" role="alert">
                <div class="flex items-center gap-3">
                    <span id="alert-icon" class="shrink-0"></span>
                    <span id="alert-message"></span>
                </div>
                <button type="button" onclick="dismissAlert()" class="rounded-lg p-1.5 opacity-70 transition hover:opacity-100 hover:bg-black/5 focus:outline-none" aria-label="Dismiss alert">
                    <x-lucide-x class="h-4 w-4" />
                </button>
            </div>

            {{-- Profile Header / Hero Card --}}
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#18393b] via-[#204d4f] to-[#2c6568] p-6 sm:p-8 md:p-10 text-white shadow-xl mb-8">
                {{-- Decorative Ambient Glow Orbs --}}
                <div class="absolute -right-12 -top-12 h-64 w-64 rounded-full bg-white/5 blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-16 right-48 h-56 w-56 rounded-full bg-[#d09523]/15 blur-2xl pointer-events-none"></div>

                <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
                    {{-- Avatar & Basic Information --}}
                    <div class="flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-6">
                        {{-- Interactive Avatar with Upload Trigger --}}
                        <div class="relative group shrink-0">
                            <div id="header-avatar" class="flex h-24 w-24 sm:h-28 sm:w-28 items-center justify-center overflow-hidden rounded-2xl bg-[#d09523] text-3xl font-extrabold text-[#204d4f] shadow-lg ring-4 ring-white/20 transition duration-300 group-hover:ring-[#d09523]">
                                @if (!empty($user->image))
                                    <img id="header-avatar-img"
                                         src="{{ str_starts_with($user->image, 'http') ? $user->image : asset('storage/' . $user->image) }}"
                                         alt="{{ $user->name }}"
                                         onerror="this.classList.add('hidden'); document.getElementById('header-avatar-initials').classList.remove('hidden');"
                                         class="h-full w-full object-cover">
                                    <span id="header-avatar-initials" class="hidden">{{ $initials }}</span>
                                @else
                                    <span id="header-avatar-initials">{{ $initials }}</span>
                                @endif
                            </div>

                            <label for="avatar-input" title="Change Profile Picture"
                                   class="absolute -bottom-2 -right-2 flex h-9 w-9 cursor-pointer items-center justify-center rounded-xl bg-white text-[#204d4f] shadow-md transition duration-200 hover:scale-110 hover:bg-[#d09523] hover:text-white focus:outline-none">
                                <x-lucide-camera class="h-4 w-4" />
                                <span class="sr-only">Change profile photo</span>
                            </label>
                        </div>

                        {{-- Identity Text & Metadata --}}
                        <div>
                            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2.5">
                                <h1 id="header-name" class="text-2xl sm:text-3xl font-bold tracking-tight text-white">
                                    {{ $user->name }}
                                </h1>
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-[#d09523] px-3 py-0.5 text-xs font-bold text-[#204d4f] shadow-xs">
                                    <x-lucide-sparkles class="h-3.5 w-3.5" />
                                    SwiftCart Customer
                                </span>
                            </div>

                            <div class="mt-2 flex flex-wrap items-center justify-center sm:justify-start gap-4 text-sm text-gray-200">
                                <p id="header-email" class="flex items-center gap-1.5">
                                    <x-lucide-mail class="h-4 w-4 text-[#d09523] shrink-0" />
                                    <span>{{ $user->email }}</span>
                                </p>
                                @if (!empty($user->phone))
                                    <p id="header-phone" class="flex items-center gap-1.5">
                                        <x-lucide-phone class="h-4 w-4 text-[#d09523] shrink-0" />
                                        <span>{{ $user->phone }}</span>
                                    </p>
                                @endif
                            </div>

                            {{-- Verification & Membership Badges --}}
                            <div class="mt-4 flex flex-wrap items-center justify-center sm:justify-start gap-2 text-xs">
                                <span class="inline-flex items-center gap-1.5 rounded-xl bg-white/10 px-3 py-1 text-white backdrop-blur-sm border border-white/10">
                                    <x-lucide-calendar class="h-3.5 w-3.5 text-[#d09523]" />
                                    Member since {{ $user->created_at ? $user->created_at->format('M Y') : 'Recently' }}
                                </span>

                                @if ($user->hasVerifiedEmail())
                                    <span class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-500/20 px-3 py-1 text-emerald-200 border border-emerald-400/30 font-medium">
                                        <x-lucide-check-circle class="h-3.5 w-3.5 text-emerald-300" />
                                        Email Verified
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-xl bg-amber-500/20 px-3 py-1 text-amber-200 border border-amber-400/30 font-medium">
                                        <x-lucide-alert-circle class="h-3.5 w-3.5 text-amber-300" />
                                        Email Unverified
                                    </span>
                                    <button type="button" onclick="resendVerificationEmail()" id="btn-header-resend"
                                            class="inline-flex items-center gap-1 rounded-xl bg-[#d09523] px-2.5 py-1 text-[11px] font-bold text-[#204d4f] hover:bg-[#f4b942] transition">
                                        Verify Now
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Quick Metrics / Stat Tiles --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 border-t border-white/10 pt-6 lg:border-t-0 lg:pt-0 shrink-0">
                        <button type="button" onclick="switchTab('orders')" class="group flex flex-col items-center justify-center rounded-2xl bg-white/10 p-3.5 text-center backdrop-blur-sm border border-white/10 hover:bg-white/20 transition">
                            <span class="text-xl sm:text-2xl font-extrabold text-[#d09523] group-hover:scale-105 transition-transform">{{ $stats['orders_count'] }}</span>
                            <span class="text-xs font-medium text-gray-200 mt-1 flex items-center gap-1">
                                <x-lucide-package class="h-3 w-3" /> Orders
                            </span>
                        </button>

                        <a href="{{ url('/wishlist') }}" class="group flex flex-col items-center justify-center rounded-2xl bg-white/10 p-3.5 text-center backdrop-blur-sm border border-white/10 hover:bg-white/20 transition">
                            <span class="text-xl sm:text-2xl font-extrabold text-[#d09523] group-hover:scale-105 transition-transform">{{ $stats['wishlist_count'] }}</span>
                            <span class="text-xs font-medium text-gray-200 mt-1 flex items-center gap-1">
                                <x-lucide-heart class="h-3 w-3" /> Wishlist
                            </span>
                        </a>

                        <a href="{{ url('/cart') }}" class="group flex flex-col items-center justify-center rounded-2xl bg-white/10 p-3.5 text-center backdrop-blur-sm border border-white/10 hover:bg-white/20 transition">
                            <span class="text-xl sm:text-2xl font-extrabold text-[#d09523] group-hover:scale-105 transition-transform">{{ $stats['cart_count'] }}</span>
                            <span class="text-xs font-medium text-gray-200 mt-1 flex items-center gap-1">
                                <x-lucide-shopping-cart class="h-3 w-3" /> Cart
                            </span>
                        </a>

                        <div class="flex flex-col items-center justify-center rounded-2xl bg-white/10 p-3.5 text-center backdrop-blur-sm border border-white/10">
                            <span class="text-xl sm:text-2xl font-extrabold text-[#d09523]">{{ $stats['reviews_count'] }}</span>
                            <span class="text-xs font-medium text-gray-200 mt-1 flex items-center gap-1">
                                <x-lucide-star class="h-3 w-3" /> Reviews
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Layout: Navigation Sidebar + Tab Panels --}}
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

                {{-- Left Navigation Sidebar --}}
                <div class="lg:col-span-1">
                    <div class="sticky top-24 rounded-2xl border border-gray-200/80 bg-white p-3 shadow-xs">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Customer Menu</p>
                            <p class="text-sm font-semibold text-gray-900 mt-0.5">Manage Your Account</p>
                        </div>

                        <nav class="mt-2 space-y-1" aria-label="Customer Profile Sections">
                            <button type="button" onclick="switchTab('personal')" id="nav-btn-personal"
                                    class="tab-nav-btn flex w-full items-center justify-between rounded-xl px-4 py-3 text-sm font-semibold transition bg-[#204d4f] text-white shadow-xs">
                                <div class="flex items-center gap-3">
                                    <x-lucide-user class="h-4 w-4 text-[#d09523]" />
                                    <span>Personal Information</span>
                                </div>
                                <x-lucide-chevron-right class="h-4 w-4 opacity-70" />
                            </button>

                            <button type="button" onclick="switchTab('orders')" id="nav-btn-orders"
                                    class="tab-nav-btn flex w-full items-center justify-between rounded-xl px-4 py-3 text-sm font-medium transition text-gray-700 hover:bg-gray-100/70">
                                <div class="flex items-center gap-3">
                                    <x-lucide-package class="h-4 w-4 text-gray-500" />
                                    <span>My Orders</span>
                                </div>
                                <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-bold text-gray-700">
                                    {{ $stats['orders_count'] }}
                                </span>
                            </button>

                            <button type="button" onclick="switchTab('address')" id="nav-btn-address"
                                    class="tab-nav-btn flex w-full items-center justify-between rounded-xl px-4 py-3 text-sm font-medium transition text-gray-700 hover:bg-gray-100/70">
                                <div class="flex items-center gap-3">
                                    <x-lucide-map-pin class="h-4 w-4 text-gray-500" />
                                    <span>Delivery Address</span>
                                </div>
                                <x-lucide-chevron-right class="h-4 w-4 opacity-40" />
                            </button>

                            <button type="button" onclick="switchTab('security')" id="nav-btn-security"
                                    class="tab-nav-btn flex w-full items-center justify-between rounded-xl px-4 py-3 text-sm font-medium transition text-gray-700 hover:bg-gray-100/70">
                                <div class="flex items-center gap-3">
                                    <x-lucide-shield-check class="h-4 w-4 text-gray-500" />
                                    <span>Password &amp; Security</span>
                                </div>
                                <x-lucide-chevron-right class="h-4 w-4 opacity-40" />
                            </button>

                            <button type="button" onclick="switchTab('settings')" id="nav-btn-settings"
                                    class="tab-nav-btn flex w-full items-center justify-between rounded-xl px-4 py-3 text-sm font-medium transition text-gray-700 hover:bg-gray-100/70">
                                <div class="flex items-center gap-3">
                                    <x-lucide-settings class="h-4 w-4 text-gray-500" />
                                    <span>Account Settings</span>
                                </div>
                                <x-lucide-chevron-right class="h-4 w-4 opacity-40" />
                            </button>
                        </nav>

                        {{-- Sign Out Trigger in Sidebar --}}
                        <div class="mt-4 border-t border-gray-100 pt-3">
                            <button type="button" onclick="handleCustomerLogout()"
                                    class="flex w-full items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-semibold text-red-600 transition hover:bg-red-50 focus:outline-none">
                                <x-lucide-log-out class="h-4 w-4" />
                                <span>Sign Out</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Right Content: Tab Panels --}}
                <div class="lg:col-span-3">

                    {{-- ==================== TAB 1: PERSONAL INFORMATION ==================== --}}
                    <div id="tab-panel-personal" class="tab-panel">
                        <div class="rounded-3xl border border-gray-200/80 bg-white p-6 sm:p-8 shadow-xs">
                            <div class="mb-6 border-b border-gray-100 pb-5">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900">Personal Information</h2>
                                        <p class="text-sm text-gray-500 mt-1">Manage your official contact details and profile preferences.</p>
                                    </div>
                                </div>
                            </div>

                            <form id="customer-profile-form" enctype="multipart/form-data" novalidate class="space-y-6">
                                @csrf

                                {{-- Hidden Avatar File Input --}}
                                <input id="avatar-input" name="image" type="file" accept="image/jpeg,image/png,image/webp,image/jpg" class="hidden">

                                {{-- Avatar Change Banner Preview --}}
                                <div id="avatar-preview-box" class="hidden items-center gap-4 rounded-2xl bg-amber-50/60 p-4 border border-amber-200/60">
                                    <img id="avatar-preview-img" class="h-16 w-16 rounded-xl object-cover border-2 border-[#d09523]" alt="Avatar Preview">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate" id="avatar-preview-name"></p>
                                        <p class="text-xs text-gray-600">New photo selected. Click 'Save Changes' to update your avatar.</p>
                                    </div>
                                    <button type="button" onclick="cancelAvatarSelection()" class="rounded-lg p-2 text-gray-500 hover:bg-amber-100 hover:text-gray-900 transition" title="Cancel photo">
                                        <x-lucide-x class="h-4 w-4" />
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                    {{-- Full Name --}}
                                    <div>
                                        <label for="cust-name" class="block text-sm font-semibold text-gray-700">
                                            Full Name <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative mt-2">
                                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                                <x-lucide-user class="h-4 w-4" />
                                            </div>
                                            <input id="cust-name" name="name" type="text" required minlength="2" maxlength="50"
                                                   value="{{ old('name', $user->name) }}"
                                                   placeholder="John Doe"
                                                   class="w-full rounded-xl border border-gray-300 py-3 pl-10 pr-4 text-sm text-gray-900 outline-none transition focus:border-[#204d4f] focus:ring-2 focus:ring-[#204d4f]/20">
                                        </div>
                                        <p id="error-name" class="mt-1.5 hidden text-xs text-red-600 font-medium"></p>
                                    </div>

                                    {{-- Email Address --}}
                                    <div>
                                        <label for="cust-email" class="block text-sm font-semibold text-gray-700">
                                            Email Address <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative mt-2">
                                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                                <x-lucide-mail class="h-4 w-4" />
                                            </div>
                                            <input id="cust-email" name="email" type="email" required maxlength="255"
                                                   value="{{ old('email', $user->email) }}"
                                                   placeholder="john@example.com"
                                                   class="w-full rounded-xl border border-gray-300 py-3 pl-10 pr-4 text-sm text-gray-900 outline-none transition focus:border-[#204d4f] focus:ring-2 focus:ring-[#204d4f]/20">
                                        </div>
                                        <p id="error-email" class="mt-1.5 hidden text-xs text-red-600 font-medium"></p>
                                    </div>

                                    {{-- Phone Number --}}
                                    <div>
                                        <label for="cust-phone" class="block text-sm font-semibold text-gray-700">
                                            Phone Number
                                        </label>
                                        <div class="relative mt-2">
                                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                                <x-lucide-phone class="h-4 w-4" />
                                            </div>
                                            <input id="cust-phone" name="phone" type="tel" minlength="10" maxlength="15"
                                                   value="{{ old('phone', $user->phone) }}"
                                                   placeholder="+1 (555) 234-5678"
                                                   class="w-full rounded-xl border border-gray-300 py-3 pl-10 pr-4 text-sm text-gray-900 outline-none transition focus:border-[#204d4f] focus:ring-2 focus:ring-[#204d4f]/20">
                                        </div>
                                        <p id="error-phone" class="mt-1.5 hidden text-xs text-red-600 font-medium"></p>
                                    </div>

                                    {{-- Account Status Badge --}}
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">
                                            Account Status
                                        </label>
                                        <div class="relative mt-2">
                                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                                <x-lucide-check-circle class="h-4 w-4 text-emerald-500" />
                                            </div>
                                            <input type="text" readonly disabled
                                                   value="Active Customer Account"
                                                   class="w-full cursor-not-allowed rounded-xl border border-gray-200 bg-gray-50 py-3 pl-10 pr-4 text-sm font-medium text-gray-600">
                                        </div>
                                    </div>
                                </div>

                                {{-- Default Shipping Address --}}
                                <div>
                                    <label for="cust-address" class="block text-sm font-semibold text-gray-700">
                                        Primary Shipping / Delivery Address
                                    </label>
                                    <div class="relative mt-2">
                                        <div class="pointer-events-none absolute top-3.5 left-3.5 text-gray-400">
                                            <x-lucide-map-pin class="h-4 w-4" />
                                        </div>
                                        <textarea id="cust-address" name="address" rows="3" minlength="5" maxlength="255"
                                                  placeholder="Apartment, Street address, City, State, ZIP code"
                                                  class="w-full rounded-xl border border-gray-300 py-3 pl-10 pr-4 text-sm text-gray-900 outline-none transition focus:border-[#204d4f] focus:ring-2 focus:ring-[#204d4f]/20">{{ old('address', $user->address) }}</textarea>
                                    </div>
                                    <p id="error-address" class="mt-1.5 hidden text-xs text-red-600 font-medium"></p>
                                    <p class="mt-1.5 text-xs text-gray-400">This address will be automatically populated during your checkout.</p>
                                </div>

                                {{-- Buttons --}}
                                <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-5">
                                    <button type="reset" class="rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                                        Reset
                                    </button>
                                    <button type="submit" id="btn-save-personal"
                                            class="inline-flex items-center justify-center rounded-xl bg-[#204d4f] px-6 py-2.5 text-sm font-semibold text-white shadow-xs transition hover:bg-[#18393b] focus:outline-none">
                                        <x-lucide-save class="mr-2 h-4 w-4" />
                                        <span id="btn-save-personal-text">Save Changes</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- ==================== TAB 2: MY ORDERS ==================== --}}
                    <div id="tab-panel-orders" class="tab-panel hidden">
                        <div class="rounded-3xl border border-gray-200/80 bg-white p-6 sm:p-8 shadow-xs">
                            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-gray-100 pb-5">
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900">My Orders</h2>
                                    <p class="text-sm text-gray-500 mt-1">Track current shipments and view previous purchase receipts.</p>
                                </div>

                                {{-- Filter Tabs --}}
                                <div class="flex items-center gap-1.5 overflow-x-auto rounded-xl bg-gray-100 p-1 text-xs font-semibold">
                                    <button type="button" onclick="filterOrders('all', this)" class="order-filter-btn rounded-lg bg-white px-3 py-1.5 text-[#204d4f] shadow-xs">
                                        All ({{ $orders->count() }})
                                    </button>
                                    <button type="button" onclick="filterOrders('pending', this)" class="order-filter-btn rounded-lg px-3 py-1.5 text-gray-600 hover:text-gray-900">
                                        Pending
                                    </button>
                                    <button type="button" onclick="filterOrders('processing', this)" class="order-filter-btn rounded-lg px-3 py-1.5 text-gray-600 hover:text-gray-900">
                                        Processing
                                    </button>
                                    <button type="button" onclick="filterOrders('completed', this)" class="order-filter-btn rounded-lg px-3 py-1.5 text-gray-600 hover:text-gray-900">
                                        Completed
                                    </button>
                                </div>
                            </div>

                            @if ($orders->isEmpty())
                                {{-- Empty Orders State --}}
                                <div class="py-12 text-center">
                                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl text-[#204d4f]">
                                        <x-lucide-shopping-bag class="h-10 w-10 text-[#204d4f]" />
                                    </div>
                                    <h3 class="mt-4 text-lg font-bold text-gray-900">No orders placed yet</h3>
                                    <p class="mt-1 text-sm text-gray-500 max-w-sm mx-auto">
                                        When you purchase items on SwiftCart, their delivery progress and order receipts will be stored here.
                                    </p>
                                    <div class="mt-6">
                                        <a href="{{ route('product') }}"
                                           class="inline-flex items-center gap-2 rounded-xl bg-[#d09523] px-6 py-3 text-sm font-semibold text-white shadow-xs hover:bg-[#f4b942] transition">
                                            <x-lucide-sparkles class="h-4 w-4" />
                                            <span>Explore Products</span>
                                        </a>
                                    </div>
                                </div>
                            @else
                                {{-- Order Cards List --}}
                                <div class="space-y-4" id="orders-list-container">
                                    @foreach ($orders as $order)
                                        @php
                                            $statusColors = [
                                                'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                'delivered' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                'processing' => 'bg-blue-50 text-blue-700 border-blue-200',
                                                'shipped' => 'bg-sky-50 text-sky-700 border-sky-200',
                                                'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                'cancelled' => 'bg-red-50 text-red-700 border-red-200',
                                            ];
                                            $orderStatus = strtolower($order->order_status ?? 'pending');
                                            $statusBadgeClass = $statusColors[$orderStatus] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                                            $orderItemsCount = $order->items ? $order->items->count() : 0;
                                        @endphp

                                        <div class="order-card rounded-2xl border border-gray-200/80 p-5 transition hover:border-[#204d4f]/40 hover:shadow-xs"
                                             data-status="{{ $orderStatus }}">
                                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-gray-100 pb-4">
                                                <div>
                                                    <div class="flex items-center gap-3">
                                                        <span class="font-bold text-gray-900 text-base">
                                                            {{ $order->order_number }}
                                                        </span>
                                                        <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold capitalize {{ $statusBadgeClass }}">
                                                            {{ $order->order_status }}
                                                        </span>
                                                    </div>
                                                    <p class="text-xs text-gray-500 mt-1 flex items-center gap-1.5">
                                                        <x-lucide-calendar class="h-3.5 w-3.5" />
                                                        Placed on {{ $order->created_at ? $order->created_at->format('M d, Y \a\t h:i A') : 'N/A' }}
                                                    </p>
                                                </div>

                                                <div class="text-left sm:text-right">
                                                    <p class="text-xs text-gray-500">Total Amount</p>
                                                    <p class="text-lg font-extrabold text-[#204d4f]">
                                                        ${{ number_format($order->total_amount ?? 0, 2) }}
                                                    </p>
                                                </div>
                                            </div>

                                            {{-- Items Preview --}}
                                            <div class="py-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                                <div class="flex items-center gap-3">
                                                    <div class="flex -space-x-2 overflow-hidden">
                                                        @foreach ($order->items->take(3) as $item)
                                                            <div class="inline-block h-10 w-10 rounded-xl bg-gray-100 border-2 border-white overflow-hidden shadow-xs shrink-0" title="{{ $item->product_name }}">
                                                                @php
                                                                    $productImage = $item->product?->image;
                                                                    if (is_array($productImage)) {
                                                                        $productImage = $productImage[0] ?? null;
                                                                    }
                                                                @endphp
                                                                @if (!empty($productImage) && is_string($productImage))
                                                                    <img src="{{ str_starts_with($productImage, 'http') ? $productImage : asset('storage/' . $productImage) }}"
                                                                         alt="{{ $item->product_name }}"
                                                                         class="h-full w-full object-cover">
                                                                @else
                                                                    <div class="flex h-full w-full items-center justify-center bg-[#204d4f]/10 text-xs font-bold text-[#204d4f]">
                                                                        <x-lucide-package class="h-4 w-4" />
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <div class="text-xs text-gray-600">
                                                        <span class="font-semibold text-gray-900">{{ $orderItemsCount }} {{ Str::plural('item', $orderItemsCount) }}</span>:
                                                        <span class="truncate block max-w-xs sm:max-w-md">
                                                            {{ $order->items->pluck('product_name')->take(2)->join(', ') }}{{ $orderItemsCount > 2 ? '...' : '' }}
                                                        </span>
                                                    </div>
                                                </div>

                                                <button type="button" onclick="openOrderModal({{ json_encode($order) }})"
                                                        class="self-start sm:self-auto inline-flex items-center gap-1.5 rounded-xl border border-gray-300 px-4 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-50 hover:border-gray-400">
                                                    <span>View Details</span>
                                                    <x-lucide-chevron-right class="h-3.5 w-3.5" />
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- ==================== TAB 3: DELIVERY & SHIPPING ADDRESS ==================== --}}
                    <div id="tab-panel-address" class="tab-panel hidden">
                        <div class="rounded-3xl border border-gray-200/80 bg-white p-6 sm:p-8 shadow-xs">
                            <div class="mb-6 border-b border-gray-100 pb-5">
                                <h2 class="text-xl font-bold text-gray-900">Delivery Address & Shipping Preferences</h2>
                                <p class="text-sm text-gray-500 mt-1">Set your default dispatch destination for 1-click orders and swift deliveries.</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Current Address Card --}}
                                <div class="rounded-2xl border-2 border-[#204d4f]/30 bg-[#204d4f]/5 p-6 relative">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center gap-2.5">
                                            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#204d4f] text-white">
                                                <x-lucide-home class="h-4 w-4" />
                                            </span>
                                            <span class="font-bold text-gray-900 text-sm">Default Home Address</span>
                                        </div>
                                        <span class="inline-flex rounded-full bg-[#204d4f] px-2.5 py-0.5 text-[11px] font-bold text-white">
                                            Primary
                                        </span>
                                    </div>

                                    <div class="space-y-2 text-sm text-gray-700">
                                        <p class="font-bold text-gray-900" id="address-display-name">{{ $user->name }}</p>
                                        <p id="address-display-body" class="text-gray-600">
                                            {{ $user->address ?: 'No address specified yet. Please update your address below.' }}
                                        </p>
                                        <p class="text-xs text-gray-500 flex items-center gap-1.5 pt-2">
                                            <x-lucide-phone class="h-4 w-4 text-[#204d4f]" />
                                            <span id="address-display-phone">{{ $user->phone ?: 'No phone added' }}</span>
                                        </p>
                                    </div>

                                    <div class="mt-6 pt-4 border-t border-gray-200/60 flex items-center justify-end">
                                     <x-Button title="Save"  />
                                        <button type="button" onclick="focusAddressInput()"
                                                class="inline-flex items-center gap-1.5 text-xs font-bold text-[#204d4f] hover:text-[#d09523] transition">
                                            <x-lucide-edit-3 class="h-3.5 w-3.5" />
                                            <span>Update Address</span>
                                        </button>
                                    </div>
                                </div>

                                {{-- Shipping Perks & Policy Card --}}
                                <div class="rounded-2xl border border-gray-200 bg-gray-50/70 p-6 space-y-4">
                                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                                        <x-lucide-truck class="h-4 w-4 text-[#d09523]" />
                                        SwiftCart Delivery Assurance
                                    </h3>
                                    <ul class="space-y-3 text-xs text-gray-600">
                                        <li class="flex items-start gap-2.5">
                                            <x-lucide-check-circle class="h-4 w-4 text-emerald-600 shrink-0 mt-0.5" />
                                            <span><strong>Fast Dispatch:</strong> Orders are verified and dispatched within 24-48 business hours.</span>
                                        </li>
                                        <li class="flex items-start gap-2.5">
                                            <x-lucide-check-circle class="h-4 w-4 text-emerald-600 shrink-0 mt-0.5" />
                                            <span><strong>Real-time Tracking:</strong> Every shipment includes live parcel telemetry directly to your inbox.</span>
                                        </li>
                                        <li class="flex items-start gap-2.5">
                                            <x-lucide-check-circle class="h-4 w-4 text-emerald-600 shrink-0 mt-0.5" />
                                            <span><strong>Discreet Packaging:</strong> Sealed tamper-proof protection on all delivered parcels.</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ==================== TAB 4: PASSWORD & SECURITY ==================== --}}
                    <div id="tab-panel-security" class="tab-panel hidden">
                        <div class="rounded-3xl border border-gray-200/80 bg-white p-6 sm:p-8 shadow-xs">
                            <div class="mb-6 border-b border-gray-100 pb-5">
                                <h2 class="text-xl font-bold text-gray-900">Change Password</h2>
                                <p class="text-sm text-gray-500 mt-1">Keep your customer account secure by choosing a unique, strong password.</p>
                            </div>

                            <form id="customer-password-form" novalidate class="space-y-6 max-w-2xl">
                                @csrf

                                {{-- New Password --}}
                                <div>
                                    <label for="new_password" class="block text-sm font-semibold text-gray-700">
                                        New Password <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative mt-2">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                            <x-lucide-key class="h-4 w-4" />
                                        </div>
                                        <input id="new_password" name="password" type="password" required minlength="8" maxlength="64"
                                               placeholder="Enter at least 8 characters"
                                               class="w-full rounded-xl border border-gray-300 py-3 pl-10 pr-10 text-sm text-gray-900 outline-none transition focus:border-[#204d4f] focus:ring-2 focus:ring-[#204d4f]/20">
                                        <button type="button" onclick="togglePasswordVisibility('new_password', this)"
                                                class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-gray-400 hover:text-gray-600 focus:outline-none" aria-label="Toggle password visibility">
                                            <x-lucide-eye class="h-4 w-4" />
                                        </button>
                                    </div>
                                    <p id="error-password" class="mt-1.5 hidden text-xs text-red-600 font-medium"></p>
                                </div>

                                {{-- Confirm New Password --}}
                                <div>
                                    <label for="new_password_confirmation" class="block text-sm font-semibold text-gray-700">
                                        Confirm New Password <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative mt-2">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                            <x-lucide-check-circle class="h-4 w-4" />
                                        </div>
                                        <input id="new_password_confirmation" name="password_confirmation" type="password" required minlength="8" maxlength="64"
                                               placeholder="Re-type your new password"
                                               class="w-full rounded-xl border border-gray-300 py-3 pl-10 pr-10 text-sm text-gray-900 outline-none transition focus:border-[#204d4f] focus:ring-2 focus:ring-[#204d4f]/20">
                                        <button type="button" onclick="togglePasswordVisibility('new_password_confirmation', this)"
                                                class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-gray-400 hover:text-gray-600 focus:outline-none" aria-label="Toggle password visibility">
                                            <x-lucide-eye class="h-4 w-4" />
                                        </button>
                                    </div>
                                </div>

                                {{-- Password Tips --}}
                                <div class="rounded-2xl bg-gray-50 p-4 border border-gray-100">
                                    <p class="text-xs font-bold text-gray-800 mb-2">Password Guidelines:</p>
                                    <ul class="space-y-1.5 text-xs text-gray-600">
                                        <li class="flex items-center gap-2">
                                            <x-lucide-check class="h-3.5 w-3.5 text-[#204d4f]" />
                                            Must be at least 8 characters long (up to 64)
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <x-lucide-check class="h-3.5 w-3.5 text-[#204d4f]" />
                                            Both new passwords must match identically
                                        </li>
                                    </ul>
                                </div>

                                <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-5">
                                    <button type="submit" id="btn-save-password"
                                            class="inline-flex items-center justify-center rounded-xl bg-[#204d4f] px-6 py-2.5 text-sm font-semibold text-white shadow-xs transition hover:bg-[#18393b] focus:outline-none">
                                        <x-lucide-lock class="mr-2 h-4 w-4" />
                                        <span id="btn-save-password-text">Update Password</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- ==================== TAB 5: ACCOUNT SETTINGS & DANGER ZONE ==================== --}}
                    <div id="tab-panel-settings" class="tab-panel hidden">
                        <div class="space-y-6">
                            {{-- End Session Card --}}
                            <div class="rounded-3xl border border-gray-200/80 bg-white p-6 sm:p-8 shadow-xs">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                    <div>
                                        <h3 class="text-base font-bold text-gray-900">Sign Out</h3>
                                        <p class="mt-1 text-sm text-gray-500">End your current session on this device safely.</p>
                                    </div>
                                    <button type="button" onclick="handleCustomerLogout()"
                                            class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 shrink-0">
                                        <x-lucide-log-out class="h-4 w-4" />
                                        <span>Sign Out</span>
                                    </button>
                                </div>
                            </div>

                            {{-- Danger Zone: Account Deletion --}}
                            <div class="rounded-3xl border border-red-200 bg-red-50/40 p-6 sm:p-8 shadow-xs">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                    <div>
                                        <h3 class="text-base font-bold text-red-900 flex items-center gap-2">
                                            <x-lucide-alert-triangle class="h-5 w-5 text-red-600" />
                                            <span>Delete Account</span>
                                        </h3>
                                        <p class="mt-1 text-sm text-red-700 max-w-xl">
                                            Permanently erase your SwiftCart customer account, active orders, saved cart items, and wishlist. This action is irreversible.
                                        </p>
                                    </div>
                                    <button type="button" onclick="openDeleteModal()"
                                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-red-600 px-5 py-2.5 text-sm font-semibold text-white shadow-xs transition hover:bg-red-700 shrink-0">
                                        <x-lucide-trash-2 class="h-4 w-4" />
                                        <span>Delete Account</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- ==================== ORDER DETAILS MODAL ==================== --}}
    <div id="order-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-xs p-4 overflow-y-auto">
        <div class="w-full max-w-2xl rounded-3xl bg-white p-6 sm:p-8 shadow-2xl transition-all my-8 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <x-lucide-package class="h-5 w-5 text-[#204d4f]" />
                        <span>Order Details:</span>
                        <span id="modal-order-number" class="text-[#204d4f]"></span>
                    </h3>
                    <p id="modal-order-date" class="text-xs text-gray-500 mt-1"></p>
                </div>
                <button type="button" onclick="closeOrderModal()" class="rounded-xl p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-700 transition" aria-label="Close modal">
                    <x-lucide-x class="h-5 w-5" />
                </button>
            </div>

            <div class="mt-5 space-y-6">
                {{-- Order Status Banner --}}
                <div class="flex items-center justify-between rounded-2xl bg-gray-50 p-4 border border-gray-100">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</p>
                        <span id="modal-order-status" class="inline-block mt-1 font-bold capitalize text-sm"></span>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Payment Method</p>
                        <p id="modal-payment-method" class="text-sm font-bold text-gray-900 mt-1"></p>
                    </div>
                </div>

                {{-- Purchased Items List --}}
                <div>
                    <h4 class="text-sm font-bold text-gray-900 mb-3">Purchased Items</h4>
                    <div id="modal-items-container" class="space-y-2 border border-gray-100 rounded-2xl p-3 bg-gray-50/50">
                        {{-- Items injected dynamically via JS --}}
                    </div>
                </div>

                {{-- Delivery Destination --}}
                <div class="rounded-2xl border border-gray-100 bg-gray-50/50 p-4">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Shipping Recipient</h4>
                    <p id="modal-shipping-name" class="font-bold text-gray-900 text-sm"></p>
                    <p id="modal-shipping-address" class="text-xs text-gray-600 mt-1"></p>
                    <p id="modal-shipping-phone" class="text-xs text-gray-500 mt-1"></p>
                </div>

                {{-- Totals Breakdown --}}
                <div class="border-t border-gray-100 pt-4 space-y-2 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span>
                        <span id="modal-subtotal" class="font-medium">$0.00</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Shipping Cost</span>
                        <span id="modal-shipping-cost" class="font-medium">$0.00</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Tax</span>
                        <span id="modal-tax" class="font-medium">$0.00</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Discount</span>
                        <span id="modal-discount" class="font-medium text-emerald-600">-$0.00</span>
                    </div>
                    <div class="flex justify-between border-t border-gray-200 pt-3 text-base font-extrabold text-[#204d4f]">
                        <span>Grand Total</span>
                        <span id="modal-total-amount">$0.00</span>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="button" onclick="closeOrderModal()"
                        class="rounded-xl bg-gray-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-gray-800">
                    Close Details
                </button>
            </div>
        </div>
    </div>

    {{-- ==================== DELETE ACCOUNT CONFIRMATION MODAL ==================== --}}
    <div id="delete-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-xs p-4">
        <div class="w-full max-w-md rounded-3xl bg-white p-6 sm:p-8 shadow-2xl transition-all">
            <div class="flex items-center gap-3 border-b border-gray-100 pb-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-red-100 text-red-600">
                    <x-lucide-alert-triangle class="h-6 w-6" />
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Confirm Deletion</h3>
                    <p class="text-xs text-gray-500">This action is permanent and cannot be undone.</p>
                </div>
            </div>

            <p class="mt-4 text-sm text-gray-600 leading-relaxed">
                Please enter your current account password to verify your identity and permanently delete your SwiftCart profile.
            </p>

            <form id="delete-account-form" class="mt-5 space-y-4">
                @csrf
                <div>
                    <label for="delete-password" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                        Current Password <span class="text-red-500">*</span>
                    </label>
                    <input id="delete-password" name="password" type="password" required
                           placeholder="Enter your current password"
                           class="mt-1.5 w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20">
                    <p id="delete-error" class="mt-1.5 hidden text-xs text-red-600 font-medium"></p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3">
                    <button type="button" onclick="closeDeleteModal()"
                            class="rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" id="btn-confirm-delete"
                            class="rounded-xl bg-red-600 px-5 py-2.5 text-sm font-semibold text-white shadow-xs transition hover:bg-red-700 focus:outline-none">
                        Permanently Delete
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ==================== CLIENT SCRIPTS & INTERACTIONS ==================== --}}
    @push('scripts')
        <script>
            (() => {
                const profileApiUrl = @json(url('/api/profile'));
                const resendEmailUrl = @json(url('/api/email/verification-notification'));
                const logoutUrl = @json(url('/api/logout'));
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || @json(csrf_token());

                // DOM Elements
                const alertBox = document.getElementById('profile-alert');
                const alertMessage = document.getElementById('alert-message');
                const alertIcon = document.getElementById('alert-icon');

                const personalForm = document.getElementById('customer-profile-form');
                const passwordForm = document.getElementById('customer-password-form');
                const avatarInput = document.getElementById('avatar-input');
                const avatarPreviewBox = document.getElementById('avatar-preview-box');
                const avatarPreviewImg = document.getElementById('avatar-preview-img');
                const avatarPreviewName = document.getElementById('avatar-preview-name');

                const headerAvatarImg = document.getElementById('header-avatar-img');
                const headerAvatarInitials = document.getElementById('header-avatar-initials');
                const headerName = document.getElementById('header-name');
                const headerEmail = document.getElementById('header-email');
                const headerPhone = document.getElementById('header-phone');

                const addressDisplayName = document.getElementById('address-display-name');
                const addressDisplayBody = document.getElementById('address-display-body');
                const addressDisplayPhone = document.getElementById('address-display-phone');

                // Notification Alert display
                window.showAlert = (message, type = 'success') => {
                    if (!alertBox || !alertMessage || !alertIcon) return;
                    alertBox.classList.remove('hidden', 'bg-emerald-50', 'text-emerald-800', 'border-emerald-200', 'bg-red-50', 'text-red-800', 'border-red-200');

                    if (type === 'success') {
                        alertBox.classList.add('flex', 'bg-emerald-50', 'text-emerald-800', 'border', 'border-emerald-200');
                        alertIcon.innerHTML = `<svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`;
                    } else {
                        alertBox.classList.add('flex', 'bg-red-50', 'text-red-800', 'border', 'border-red-200');
                        alertIcon.innerHTML = `<svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>`;
                    }
                    alertMessage.textContent = message;
                    alertBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                };

                window.dismissAlert = () => {
                    if (alertBox) alertBox.classList.add('hidden');
                };

                // Tab Switcher
                window.switchTab = (tabName) => {
                    document.querySelectorAll('.tab-panel').forEach(panel => panel.classList.add('hidden'));
                    document.querySelectorAll('.tab-nav-btn').forEach(btn => {
                        btn.classList.remove('bg-[#204d4f]', 'text-white', 'shadow-xs', 'font-semibold');
                        btn.classList.add('text-gray-700', 'font-medium');
                    });

                    const targetPanel = document.getElementById(`tab-panel-${tabName}`);
                    const targetBtn = document.getElementById(`nav-btn-${tabName}`);

                    if (targetPanel && targetBtn) {
                        targetPanel.classList.remove('hidden');
                        targetBtn.classList.remove('text-gray-700', 'font-medium');
                        targetBtn.classList.add('bg-[#204d4f]', 'text-white', 'shadow-xs', 'font-semibold');
                    }

                    if (window.location.hash !== `#${tabName}`) {
                        history.replaceState(null, '', `#${tabName}`);
                    }
                };

                // Focus Address textarea
                window.focusAddressInput = () => {
                    switchTab('personal');
                    const addressField = document.getElementById('cust-address');
                    if (addressField) {
                        addressField.focus();
                        addressField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                };

                // Password show/hide toggle
                window.togglePasswordVisibility = (inputId, button) => {
                    const input = document.getElementById(inputId);
                    if (!input) return;
                    const isPassword = input.type === 'password';
                    input.type = isPassword ? 'text' : 'password';
                    button.classList.toggle('text-[#204d4f]', isPassword);
                };

                // Avatar Selection Preview
                if (avatarInput) {
                    avatarInput.addEventListener('change', (e) => {
                        const file = e.target.files?.[0];
                        if (file) {
                            if (file.size > 2048 * 1024) {
                                showAlert('The avatar image may not be greater than 2048 kilobytes.', 'error');
                                avatarInput.value = '';
                                return;
                            }
                            const reader = new FileReader();
                            reader.onload = (event) => {
                                if (avatarPreviewImg) avatarPreviewImg.src = event.target.result;
                                if (avatarPreviewName) avatarPreviewName.textContent = file.name;
                                if (avatarPreviewBox) {
                                    avatarPreviewBox.classList.remove('hidden');
                                    avatarPreviewBox.classList.add('flex');
                                }
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                }

                window.cancelAvatarSelection = () => {
                    if (avatarInput) avatarInput.value = '';
                    if (avatarPreviewBox) {
                        avatarPreviewBox.classList.add('hidden');
                        avatarPreviewBox.classList.remove('flex');
                    }
                };

                // Inline Form Error Helpers
                const clearErrors = (form) => {
                    form.querySelectorAll('[id^="error-"]').forEach(el => {
                        el.textContent = '';
                        el.classList.add('hidden');
                    });
                };

                const showErrors = (errors) => {
                    for (const [key, messages] of Object.entries(errors)) {
                        const errorEl = document.getElementById(`error-${key}`);
                        if (errorEl) {
                            errorEl.textContent = Array.isArray(messages) ? messages[0] : messages;
                            errorEl.classList.remove('hidden');
                        }
                    }
                };

                // Personal Details AJAX Form Submit
                if (personalForm) {
                    personalForm.addEventListener('submit', async (e) => {
                        e.preventDefault();
                        clearErrors(personalForm);

                        const submitBtn = document.getElementById('btn-save-personal');
                        const submitText = document.getElementById('btn-save-personal-text');
                        submitBtn.disabled = true;
                        submitText.textContent = 'Saving...';

                        try {
                            const formData = new FormData(personalForm);
                            formData.append('_method', 'PATCH');

                            const response = await fetch(profileApiUrl, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: formData
                            });

                            const data = await response.json();

                            if (!response.ok) {
                                if (response.status === 422 && data.errors) {
                                    showErrors(data.errors);
                                    showAlert('Please check the highlighted fields and try again.', 'error');
                                } else {
                                    showAlert(data.message || 'Unable to update profile. Please try again.', 'error');
                                }
                                return;
                            }

                            showAlert(data.message || 'Personal details updated successfully!', 'success');

                            // Update live DOM views
                            const updated = data.user;
                            if (updated) {
                                if (headerName) headerName.textContent = updated.name;
                                if (addressDisplayName) addressDisplayName.textContent = updated.name;
                                if (headerEmail) {
                                    const span = headerEmail.querySelector('span');
                                    if (span) span.textContent = updated.email;
                                }
                                if (headerPhone) {
                                    const span = headerPhone.querySelector('span');
                                    if (span) span.textContent = updated.phone || '';
                                }
                                if (addressDisplayBody) {
                                    addressDisplayBody.textContent = updated.address || 'No address specified yet. Please update your address below.';
                                }
                                if (addressDisplayPhone) {
                                    addressDisplayPhone.textContent = updated.phone || 'No phone added';
                                }

                                if (updated.image) {
                                    const imgUrl = updated.image.startsWith('http') ? updated.image : `/storage/${updated.image}`;
                                    if (headerAvatarImg) {
                                        headerAvatarImg.src = imgUrl;
                                        headerAvatarImg.classList.remove('hidden');
                                        if (headerAvatarInitials) headerAvatarInitials.classList.add('hidden');
                                    }
                                }
                            }

                            cancelAvatarSelection();
                        } catch (err) {
                            showAlert('A network error occurred. Please try again.', 'error');
                        } finally {
                            submitBtn.disabled = false;
                            submitText.textContent = 'Save Changes';
                        }
                    });
                }

                // Password AJAX Form Submit
                if (passwordForm) {
                    passwordForm.addEventListener('submit', async (e) => {
                        e.preventDefault();
                        clearErrors(passwordForm);

                        const submitBtn = document.getElementById('btn-save-password');
                        const submitText = document.getElementById('btn-save-password-text');
                        submitBtn.disabled = true;
                        submitText.textContent = 'Updating...';

                        try {
                            const formData = new FormData(passwordForm);
                            formData.append('_method', 'PATCH');

                            const response = await fetch(profileApiUrl, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: formData
                            });

                            const data = await response.json();

                            if (!response.ok) {
                                if (response.status === 422 && data.errors) {
                                    showErrors(data.errors);
                                    showAlert('Password validation failed. Please check requirements.', 'error');
                                } else {
                                    showAlert(data.message || 'Unable to update password.', 'error');
                                }
                                return;
                            }

                            showAlert(data.message || 'Password changed successfully!', 'success');
                            passwordForm.reset();
                        } catch (err) {
                            showAlert('A network error occurred. Please try again.', 'error');
                        } finally {
                            submitBtn.disabled = false;
                            submitText.textContent = 'Update Password';
                        }
                    });
                }

                // Resend Email Verification
                window.resendVerificationEmail = async () => {
                    const btn = document.getElementById('btn-header-resend');
                    if (btn) {
                        btn.disabled = true;
                        btn.textContent = 'Sending...';
                    }

                    try {
                        const response = await fetch(resendEmailUrl, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        const data = await response.json();
                        showAlert(data.message || 'Verification link has been sent to your email address.', 'success');
                    } catch (err) {
                        showAlert('Unable to send verification email. Please try again later.', 'error');
                    } finally {
                        if (btn) {
                            btn.disabled = false;
                            btn.textContent = 'Verify Now';
                        }
                    }
                };

                // Order Filters
                window.filterOrders = (status, button) => {
                    document.querySelectorAll('.order-filter-btn').forEach(btn => {
                        btn.classList.remove('bg-white', 'text-[#204d4f]', 'shadow-xs');
                        btn.classList.add('text-gray-600');
                    });
                    button.classList.add('bg-white', 'text-[#204d4f]', 'shadow-xs');
                    button.classList.remove('text-gray-600');

                    const cards = document.querySelectorAll('.order-card');
                    cards.forEach(card => {
                        const cardStatus = card.getAttribute('data-status');
                        if (status === 'all' || cardStatus === status) {
                            card.classList.remove('hidden');
                        } else {
                            card.classList.add('hidden');
                        }
                    });
                };

                // Order Details Modal
                const orderModal = document.getElementById('order-modal');

                window.openOrderModal = (order) => {
                    if (!orderModal || !order) return;

                    document.getElementById('modal-order-number').textContent = order.order_number || '';
                    document.getElementById('modal-order-date').textContent = order.created_at
                        ? `Placed on ${new Date(order.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}`
                        : '';
                    document.getElementById('modal-order-status').textContent = order.order_status || 'Pending';
                    document.getElementById('modal-payment-method').textContent = (order.payment_method || 'Online').toUpperCase();

                    document.getElementById('modal-shipping-name').textContent = order.shipping_name || @json($user->name);
                    document.getElementById('modal-shipping-address').textContent = [
                        order.shipping_address,
                        order.shipping_city,
                        order.shipping_state,
                        order.shipping_postal_code,
                        order.shipping_country
                    ].filter(Boolean).join(', ') || 'No shipping address recorded';
                    document.getElementById('modal-shipping-phone').textContent = order.shipping_phone ? `Phone: ${order.shipping_phone}` : '';

                    document.getElementById('modal-subtotal').textContent = `$${parseFloat(order.subtotal || 0).toFixed(2)}`;
                    document.getElementById('modal-shipping-cost').textContent = `$${parseFloat(order.shipping_cost || 0).toFixed(2)}`;
                    document.getElementById('modal-tax').textContent = `$${parseFloat(order.tax || 0).toFixed(2)}`;
                    document.getElementById('modal-discount').textContent = `-$${parseFloat(order.discount || 0).toFixed(2)}`;
                    document.getElementById('modal-total-amount').textContent = `$${parseFloat(order.total_amount || 0).toFixed(2)}`;

                    // Inject items
                    const itemsContainer = document.getElementById('modal-items-container');
                    itemsContainer.innerHTML = '';

                    if (order.items && order.items.length > 0) {
                        order.items.forEach(item => {
                            const itemRow = document.createElement('div');
                            itemRow.className = 'flex items-center justify-between py-2 border-b border-gray-100 last:border-0 text-xs';
                            itemRow.innerHTML = `
                                <div>
                                    <p class="font-semibold text-gray-900">${item.product_name || 'Product'}</p>
                                    <p class="text-gray-500">Qty: ${item.quantity} × $${parseFloat(item.price || 0).toFixed(2)}</p>
                                </div>
                                <span class="font-bold text-gray-900">$${parseFloat(item.total_price || (item.quantity * item.price) || 0).toFixed(2)}</span>
                            `;
                            itemsContainer.appendChild(itemRow);
                        });
                    } else {
                        itemsContainer.innerHTML = '<p class="text-xs text-gray-400 py-2">No item lines available.</p>';
                    }

                    orderModal.classList.remove('hidden');
                    orderModal.classList.add('flex');
                };

                window.closeOrderModal = () => {
                    if (orderModal) {
                        orderModal.classList.add('hidden');
                        orderModal.classList.remove('flex');
                    }
                };

                // Delete Account Modal
                const deleteModal = document.getElementById('delete-modal');
                const deleteForm = document.getElementById('delete-account-form');
                const deleteError = document.getElementById('delete-error');

                window.openDeleteModal = () => {
                    if (deleteModal) {
                        deleteModal.classList.remove('hidden');
                        deleteModal.classList.add('flex');
                    }
                };

                window.closeDeleteModal = () => {
                    if (deleteModal) {
                        deleteModal.classList.add('hidden');
                        deleteModal.classList.remove('flex');
                    }
                    if (deleteForm) deleteForm.reset();
                    if (deleteError) {
                        deleteError.textContent = '';
                        deleteError.classList.add('hidden');
                    }
                };

                if (deleteForm) {
                    deleteForm.addEventListener('submit', async (e) => {
                        e.preventDefault();
                        if (deleteError) {
                            deleteError.textContent = '';
                            deleteError.classList.add('hidden');
                        }

                        const submitBtn = document.getElementById('btn-confirm-delete');
                        submitBtn.disabled = true;
                        submitBtn.textContent = 'Deleting...';

                        try {
                            const passwordInput = document.getElementById('delete-password');
                            const response = await fetch(profileApiUrl, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: JSON.stringify({ password: passwordInput.value })
                            });

                            const data = await response.json();

                            if (!response.ok) {
                                if (response.status === 422 && data.errors && data.errors.password) {
                                    deleteError.textContent = data.errors.password[0];
                                } else {
                                    deleteError.textContent = data.message || 'Unable to delete account.';
                                }
                                deleteError.classList.remove('hidden');
                                return;
                            }

                            window.location.href = @json(url('/'));
                        } catch (err) {
                            if (deleteError) {
                                deleteError.textContent = 'A network error occurred. Please try again.';
                                deleteError.classList.remove('hidden');
                            }
                        } finally {
                            submitBtn.disabled = false;
                            submitBtn.textContent = 'Permanently Delete';
                        }
                    });
                }

                // Global Logout
                window.handleCustomerLogout = async () => {
                    try {
                        await fetch(logoutUrl, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                    } finally {
                        window.location.href = @json(url('/'));
                    }
                };

                // Check initial URL hash on load (e.g. #orders)
                const currentHash = window.location.hash.replace('#', '');
                if (['personal', 'orders', 'address', 'security', 'settings'].includes(currentHash)) {
                    switchTab(currentHash);
                }
            })();
        </script>
    @endpush
@endsection
