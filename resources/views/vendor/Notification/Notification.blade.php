@extends('layouts.Vendor')

@section('content')
    <x-slot name="title">Notifications</x-slot>

    <div class="p-4 sm:p-6 lg:p-8">
        {{-- Breadcrumb --}}
        <nav class="mb-4 flex items-center gap-2 text-xs font-semibold text-gray-400" aria-label="Breadcrumb">
            <a href="{{ route('vendor.dashboard') }}" class="text-gray-600 transition hover:text-gray-900">Dashboard</a>
            <span>/</span>
            <span class="text-gray-800">Notifications</span>
        </nav>

        {{-- Page Header --}}
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#224e51]/10 text-[#224e51]">
                        <x-lucide-bell class="h-5 w-5" />
                    </span>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">Vendor Notification Center</h1>
                </div>
                <p class="mt-1 text-sm text-gray-500">Monitor store activity, orders, admin notices, and inventory updates in real time.</p>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-wrap items-center gap-3">
                <button type="button" onclick="clearReadNotifications()"
                        class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-xs sm:text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none">
                    <x-lucide-trash-2 class="h-4 w-4 text-gray-500" />
                    <span>Clear Read</span>
                </button>

                <button type="button" onclick="markAllAsRead()"
                        class="inline-flex items-center gap-2 rounded-xl bg-[#224e51] px-4 py-2.5 text-xs sm:text-sm font-semibold text-white shadow-sm transition hover:bg-[#1a3d3f] focus:outline-none">
                    <x-lucide-check-check class="h-4 w-4 text-[#F4C95D]" />
                    <span>Mark All as Read</span>
                </button>
            </div>
        </div>

        {{-- Metrics Overview Cards --}}
        <div class="mb-8 grid grid-cols-2 gap-4 lg:grid-cols-4">
            <div class="rounded-2xl border border-gray-100 border-l-4 border-l-[#224e51] bg-white p-4 shadow-sm sm:p-5">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total Alerts</p>
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-600">
                        <x-lucide-bell class="h-4 w-4" />
                    </span>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span id="stat-total" class="text-2xl font-extrabold text-gray-900">0</span>
                    <span class="text-xs text-gray-400">received</span>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-100 border-l-4 border-l-red-500 bg-white p-4 shadow-sm sm:p-5">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Unread</p>
                    <span class="inline-flex items-center rounded-full bg-red-100 border border-red-200 px-2 py-0.5 text-xs font-semibold text-red-700">Requires Action</span>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span id="stat-unread" class="text-2xl font-extrabold text-red-600">0</span>
                    <span class="text-xs text-gray-400">new alerts</span>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-100 border-l-4 border-l-emerald-500 bg-white p-4 shadow-sm sm:p-5">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Orders & Sales</p>
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                        <x-lucide-shopping-bag class="h-4 w-4" />
                    </span>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span id="stat-orders" class="text-2xl font-extrabold text-gray-900">0</span>
                    <span class="text-xs text-gray-400">updates</span>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-100 border-l-4 border-l-[#F4C95D] bg-white p-4 shadow-sm sm:p-5">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Products & Notices</p>
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#F4C95D]/20 text-[#224e51]">
                        <x-lucide-package class="h-4 w-4" />
                    </span>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span id="stat-products" class="text-2xl font-extrabold text-gray-900">0</span>
                    <span class="text-xs text-gray-400">notices</span>
                </div>
            </div>
        </div>

        {{-- Filter & Search Toolbar --}}
        <div class="mb-6 rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                {{-- Search Box --}}
                <div class="relative w-full lg:max-w-md">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                        <x-lucide-search class="h-4 w-4" />
                    </div>
                    <input id="notification-search" type="search"
                           placeholder="Search alerts by title, order #, product name, or reason..."
                           class="w-full rounded-xl border border-gray-200 bg-gray-50/50 py-2.5 pl-10 pr-4 text-sm text-gray-900 outline-none transition focus:border-[#224e51] focus:bg-white focus:ring-2 focus:ring-[#224e51]/20">
                </div>

                {{-- Status & Priority Filters --}}
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex items-center gap-2">
                        <label for="filter-status" class="text-xs font-semibold text-gray-500">Status:</label>
                        <select id="filter-status" onchange="filterNotifications()"
                                class="rounded-xl border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-700 outline-none transition focus:border-[#224e51] focus:ring-2 focus:ring-[#224e51]/20">
                            <option value="all">All Statuses</option>
                            <option value="unread" selected>Unread Only</option>
                            <option value="read">Read Only</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <label for="filter-priority" class="text-xs font-semibold text-gray-500">Priority:</label>
                        <select id="filter-priority" onchange="filterNotifications()"
                                class="rounded-xl border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-700 outline-none transition focus:border-[#224e51] focus:ring-2 focus:ring-[#224e51]/20">
                            <option value="all">All Priorities</option>
                            <option value="high">High Priority</option>
                            <option value="medium">Medium Priority</option>
                            <option value="normal">Normal</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Category Filter Pills --}}
            <div class="mt-4 flex flex-wrap items-center gap-2 border-t border-gray-100 pt-3">
                <button type="button" onclick="selectCategory('all')" data-category="all"
                        class="category-pill inline-flex items-center gap-1.5 rounded-xl px-3.5 py-1.5 text-xs font-semibold transition bg-[#224e51] text-white">
                    <x-lucide-layers class="h-3.5 w-3.5" />
                    <span>All</span>
                    <span id="pill-count-all" class="ml-1 rounded-full bg-white/20 px-1.5 py-0.2 text-[10px] text-white">0</span>
                </button>

                <button type="button" onclick="selectCategory('order')" data-category="order"
                        class="category-pill inline-flex items-center gap-1.5 rounded-xl px-3.5 py-1.5 text-xs font-semibold transition bg-gray-100 text-gray-600 hover:bg-gray-200">
                    <x-lucide-shopping-bag class="h-3.5 w-3.5 text-emerald-600" />
                    <span>Orders</span>
                    <span id="pill-count-order" class="ml-1 rounded-full bg-gray-200 px-1.5 py-0.2 text-[10px] text-gray-700">0</span>
                </button>

                <button type="button" onclick="selectCategory('product')" data-category="product"
                        class="category-pill inline-flex items-center gap-1.5 rounded-xl px-3.5 py-1.5 text-xs font-semibold transition bg-gray-100 text-gray-600 hover:bg-gray-200">
                    <x-lucide-package class="h-3.5 w-3.5 text-amber-600" />
                    <span>Products & Catalog</span>
                    <span id="pill-count-product" class="ml-1 rounded-full bg-gray-200 px-1.5 py-0.2 text-[10px] text-gray-700">0</span>
                </button>

                <button type="button" onclick="selectCategory('service')" data-category="service"
                        class="category-pill inline-flex items-center gap-1.5 rounded-xl px-3.5 py-1.5 text-xs font-semibold transition bg-gray-100 text-gray-600 hover:bg-gray-200">
                    <x-lucide-truck class="h-3.5 w-3.5 text-teal-600" />
                    <span>Perks & Services</span>
                    <span id="pill-count-service" class="ml-1 rounded-full bg-gray-200 px-1.5 py-0.2 text-[10px] text-gray-700">0</span>
                </button>

                <button type="button" onclick="selectCategory('coupon')" data-category="coupon"
                        class="category-pill inline-flex items-center gap-1.5 rounded-xl px-3.5 py-1.5 text-xs font-semibold transition bg-gray-100 text-gray-600 hover:bg-gray-200">
                    <x-lucide-ticket-percent class="h-3.5 w-3.5 text-indigo-600" />
                    <span>Coupons</span>
                    <span id="pill-count-coupon" class="ml-1 rounded-full bg-gray-200 px-1.5 py-0.2 text-[10px] text-gray-700">0</span>
                </button>

                <button type="button" onclick="selectCategory('query')" data-category="query"
                        class="category-pill inline-flex items-center gap-1.5 rounded-xl px-3.5 py-1.5 text-xs font-semibold transition bg-gray-100 text-gray-600 hover:bg-gray-200">
                    <x-lucide-message-square class="h-3.5 w-3.5 text-sky-600" />
                    <span>Customer Queries</span>
                    <span id="pill-count-query" class="ml-1 rounded-full bg-gray-200 px-1.5 py-0.2 text-[10px] text-gray-700">0</span>
                </button>

                <button type="button" onclick="selectCategory('system')" data-category="system"
                        class="category-pill inline-flex items-center gap-1.5 rounded-xl px-3.5 py-1.5 text-xs font-semibold transition bg-gray-100 text-gray-600 hover:bg-gray-200">
                    <x-lucide-shield class="h-3.5 w-3.5 text-purple-600" />
                    <span>System & Security</span>
                    <span id="pill-count-system" class="ml-1 rounded-full bg-gray-200 px-1.5 py-0.2 text-[10px] text-gray-700">0</span>
                </button>
            </div>
        </div>

        {{-- Dynamic Notifications List --}}
        <div id="notifications-container" class="space-y-3">
            {{-- Injected dynamically --}}
        </div>

        {{-- Empty State --}}
        <div id="empty-state" class="hidden rounded-2xl border border-gray-100 bg-white py-16 px-6 text-center shadow-sm">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[#224e51]/10 text-[#224e51]">
                <x-lucide-bell-off class="h-8 w-8" />
            </div>
            <h3 class="mt-4 text-lg font-bold text-gray-900">No notifications found</h3>
            <p id="empty-state-text" class="mt-1 text-sm text-gray-500">You're all caught up! No alerts match your current filter criteria.</p>
            <button type="button" onclick="resetFilters()"
                    class="mt-5 inline-flex items-center gap-2 rounded-xl bg-gray-100 px-4 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-200">
                <x-lucide-rotate-ccw class="h-3.5 w-3.5" />
                <span>Reset Filters</span>
            </button>
        </div>
    </div>

    @push('scripts')
        <script>
            (() => {
                const STORAGE_KEY = 'swiftcart_vendor_notifications_v1';
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

                const initialSeed = [
                    {
                        id: 'v-seed-1',
                        title: 'Welcome to SwiftCart Vendor Hub!',
                        description: 'Your vendor account is active. Start adding your product catalog and reach thousands of customers.',
                        category: 'system',
                        priority: 'normal',
                        read: false,
                        time_ago: '10 minutes ago',
                        created_at: new Date(Date.now() - 10 * 60 * 1000).toISOString(),
                        action_url: @json(route('vendor.product.create')),
                        action_label: 'Add First Product',
                        iconBg: 'bg-[#224e51]/10 text-[#224e51]'
                    },
                    {
                        id: 'v-seed-2',
                        title: 'Store Perks Configured',
                        description: 'Offer Cash on Delivery and Express Shipping to boost conversions on your products.',
                        category: 'service',
                        priority: 'normal',
                        read: false,
                        time_ago: '1 hour ago',
                        created_at: new Date(Date.now() - 60 * 60 * 1000).toISOString(),
                        action_url: @json(route('vendor.service')),
                        action_label: 'Manage Perks',
                        iconBg: 'bg-teal-100 text-teal-700'
                    },
                    {
                        id: 'v-seed-3',
                        title: 'Customer Inquiry Received',
                        description: 'A buyer asked questions regarding product warranty and delivery estimates.',
                        category: 'query',
                        priority: 'normal',
                        read: true,
                        time_ago: 'Yesterday at 4:30 PM',
                        created_at: new Date(Date.now() - 24 * 60 * 60 * 1000).toISOString(),
                        action_url: @json(route('vendor.query')),
                        action_label: 'View Queries',
                        iconBg: 'bg-sky-100 text-sky-700'
                    }
                ];

                let notifications = [];
                let currentCategory = 'all';

                const container = document.getElementById('notifications-container');
                const searchInput = document.getElementById('notification-search');
                const statusFilter = document.getElementById('filter-status');
                const priorityFilter = document.getElementById('filter-priority');
                const emptyState = document.getElementById('empty-state');

                const getLocalStored = () => {
                    try {
                        const stored = localStorage.getItem(STORAGE_KEY);
                        return stored ? JSON.parse(stored) : null;
                    } catch (e) {
                        return null;
                    }
                };

                const saveLocalStored = () => {
                    try {
                        localStorage.setItem(STORAGE_KEY, JSON.stringify(notifications));
                        window.dispatchEvent(new CustomEvent('vendor-notifications-updated', { detail: notifications }));
                    } catch (e) {
                        console.error('Failed to save notification state:', e);
                    }
                };

                const updateMetrics = () => {
                    const total = notifications.length;
                    const unread = notifications.filter(n => !n.read).length;
                    const orders = notifications.filter(n => n.category === 'order').length;
                    const products = notifications.filter(n => n.category === 'product' || n.category === 'admin').length;

                    document.getElementById('stat-total').textContent = total;
                    document.getElementById('stat-unread').textContent = unread;
                    document.getElementById('stat-orders').textContent = orders;
                    document.getElementById('stat-products').textContent = products;

                    document.getElementById('pill-count-all').textContent = total;
                    document.getElementById('pill-count-order').textContent = orders;
                    document.getElementById('pill-count-product').textContent = products;
                    document.getElementById('pill-count-service').textContent = notifications.filter(n => n.category === 'service').length;
                    document.getElementById('pill-count-coupon').textContent = notifications.filter(n => n.category === 'coupon').length;
                    document.getElementById('pill-count-query').textContent = notifications.filter(n => n.category === 'query').length;
                    document.getElementById('pill-count-system').textContent = notifications.filter(n => n.category === 'system').length;
                };

                const getCategorySvg = (category) => {
                    switch (category) {
                        case 'order':
                            return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>';
                        case 'product':
                        case 'inventory':
                            return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>';
                        case 'service':
                            return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>';
                        case 'coupon':
                            return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>';
                        case 'query':
                            return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>';
                        default:
                            return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>';
                    }
                };

                const renderNotifications = () => {
                    updateMetrics();

                    const query = (searchInput.value || '').toLowerCase().trim();
                    const status = statusFilter.value;
                    const priority = priorityFilter.value;

                    const filtered = notifications.filter(item => {
                        const matchesCategory = currentCategory === 'all' || item.category === currentCategory;
                        const matchesStatus = status === 'all' ||
                            (status === 'unread' && !item.read) ||
                            (status === 'read' && item.read);
                        const matchesPriority = priority === 'all' || item.priority === priority;
                        const matchesSearch = !query ||
                            (item.title && item.title.toLowerCase().includes(query)) ||
                            (item.description && item.description.toLowerCase().includes(query));

                        return matchesCategory && matchesStatus && matchesPriority && matchesSearch;
                    });

                    if (filtered.length === 0) {
                        container.innerHTML = '';
                        emptyState.classList.remove('hidden');
                        return;
                    }

                    emptyState.classList.add('hidden');

                    container.innerHTML = filtered.map(item => {
                        const priorityBadge = item.priority === 'high'
                            ? `<span class="inline-flex items-center gap-1 rounded-full bg-red-50 border border-red-200 px-2 py-0.5 text-[11px] font-semibold text-red-700">Urgent</span>`
                            : item.priority === 'medium'
                            ? `<span class="inline-flex items-center gap-1 rounded-full bg-amber-50 border border-amber-200 px-2 py-0.5 text-[11px] font-semibold text-amber-700">Medium</span>`
                            : `<span class="inline-flex items-center gap-1 rounded-full bg-gray-100 border border-gray-200 px-2 py-0.5 text-[11px] font-semibold text-gray-600">Info</span>`;

                        const categoryName = {
                            order: 'Order',
                            product: 'Product',
                            service: 'Perks',
                            coupon: 'Coupon',
                            query: 'Support',
                            system: 'System',
                            admin: 'Admin'
                        }[item.category] || 'Alert';

                        const iconBg = item.iconBg || (
                            item.priority === 'high' ? 'bg-red-100 text-red-700' :
                            item.category === 'order' ? 'bg-emerald-100 text-emerald-700' :
                            item.category === 'product' ? 'bg-amber-100 text-amber-700' :
                            item.category === 'coupon' ? 'bg-indigo-100 text-indigo-700' :
                            item.category === 'query' ? 'bg-sky-100 text-sky-700' :
                            item.category === 'service' ? 'bg-teal-100 text-teal-700' :
                            'bg-[#224e51]/10 text-[#224e51]'
                        );

                        return `
                            <div id="${item.id}"
                                 class="group relative flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl border p-4 sm:p-5 transition-all duration-200 ${
                                     item.read
                                         ? 'bg-white border-gray-100 hover:border-gray-200 hover:shadow-sm'
                                         : 'bg-[#F9FCFB] border-[#224e51]/20 shadow-sm ring-1 ring-[#224e51]/5'
                                 }">
                                <div class="flex items-start gap-4">
                                    {{-- Unread Dot Indicator (Static, No Pulse) --}}
                                    <div class="mt-1 flex items-center justify-center">
                                        ${
                                            item.read
                                                ? `<span class="h-2.5 w-2.5 rounded-full bg-gray-200"></span>`
                                                : `<span class="h-2.5 w-2.5 rounded-full bg-[#224e51] ring-4 ring-[#224e51]/20"></span>`
                                        }
                                    </div>

                                    {{-- Icon Container --}}
                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl ${iconBg} font-bold shadow-xs">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            ${getCategorySvg(item.category)}
                                        </svg>
                                    </div>

                                    {{-- Content Block --}}
                                    <div class="flex-1">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <h3 class="text-sm font-bold text-gray-900 ${item.read ? 'text-gray-700' : 'text-gray-950'}">${escapeHtml(item.title)}</h3>
                                            <span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider text-gray-600">${categoryName}</span>
                                            ${priorityBadge}
                                        </div>
                                        <p class="mt-1 text-xs sm:text-sm text-gray-600 leading-relaxed">${escapeHtml(item.description || '')}</p>
                                        <div class="mt-2 flex items-center gap-3 text-xs text-gray-400">
                                            <span class="flex items-center gap-1">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                ${item.time_ago || item.time || 'Recently'}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Action Buttons --}}
                                <div class="flex items-center justify-end gap-2 border-t border-gray-100 pt-3 sm:border-0 sm:pt-0 shrink-0">
                                    ${
                                        item.action_url
                                            ? `
                                            <a href="${item.action_url}"
                                               class="inline-flex items-center gap-1.5 rounded-xl bg-[#224e51]/10 px-3 py-1.5 text-xs font-semibold text-[#224e51] transition hover:bg-[#224e51] hover:text-white">
                                                <span>${item.action_label || 'View'}</span>
                                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                            </a>
                                            `
                                            : ''
                                    }

                                    <button type="button" onclick="toggleRead('${item.id}')"
                                            title="${item.read ? 'Mark as unread' : 'Mark as read'}"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-xl border border-gray-200 text-gray-500 transition hover:bg-gray-100 hover:text-gray-900 focus:outline-none">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            ${
                                                item.read
                                                    ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"/>'
                                                    : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>'
                                            }
                                        </svg>
                                    </button>

                                    <button type="button" onclick="deleteNotification('${item.id}')"
                                            title="Delete notification"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-xl border border-transparent text-gray-400 transition hover:bg-red-50 hover:text-red-600 focus:outline-none">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </div>
                        `;
                    }).join('');
                };

                const escapeHtml = (text) => {
                    if (!text) return '';
                    const div = document.createElement('div');
                    div.textContent = text;
                    return div.innerHTML;
                };

                window.selectCategory = (cat) => {
                    currentCategory = cat;
                    document.querySelectorAll('.category-pill').forEach(btn => {
                        const isSelected = btn.dataset.category === cat;
                        if (isSelected) {
                            btn.className = 'category-pill inline-flex items-center gap-1.5 rounded-xl px-3.5 py-1.5 text-xs font-semibold transition bg-[#224e51] text-white';
                            const badge = btn.querySelector('span:last-child');
                            if (badge) badge.className = 'ml-1 rounded-full bg-white/20 px-1.5 py-0.2 text-[10px] text-white';
                        } else {
                            btn.className = 'category-pill inline-flex items-center gap-1.5 rounded-xl px-3.5 py-1.5 text-xs font-semibold transition bg-gray-100 text-gray-600 hover:bg-gray-200';
                            const badge = btn.querySelector('span:last-child');
                            if (badge) badge.className = 'ml-1 rounded-full bg-gray-200 px-1.5 py-0.2 text-[10px] text-gray-700';
                        }
                    });
                    renderNotifications();
                };

                window.filterNotifications = () => {
                    renderNotifications();
                };

                window.toggleRead = async (id) => {
                    const item = notifications.find(n => n.id === id);
                    if (item) {
                        item.read = !item.read;
                        saveLocalStored();
                        renderNotifications();

                        if (item.read) {
                            try {
                                await fetch(`/api/notifications/${id}/read`, {
                                    method: 'PATCH',
                                    headers: {
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': csrfToken
                                    }
                                });
                            } catch (e) {}
                        }
                    }
                };

                window.deleteNotification = async (id) => {
                    notifications = notifications.filter(n => n.id !== id);
                    saveLocalStored();
                    renderNotifications();

                    try {
                        await fetch(`/api/notifications/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            }
                        });
                    } catch (e) {}
                };

                window.markAllAsRead = async () => {
                    notifications.forEach(n => n.read = true);
                    saveLocalStored();
                    renderNotifications();

                    try {
                        await fetch('/api/notifications/mark-all-read', {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            }
                        });
                    } catch (e) {}
                };

                window.clearReadNotifications = () => {
                    notifications = notifications.filter(n => !n.read);
                    saveLocalStored();
                    renderNotifications();
                };

                window.resetFilters = () => {
                    searchInput.value = '';
                    statusFilter.value = 'all';
                    priorityFilter.value = 'all';
                    selectCategory('all');
                };

                searchInput.addEventListener('input', renderNotifications);

                // Initialize from API / local storage
                const loadPageNotifications = async () => {
                    const local = getLocalStored();

                    try {
                        const res = await fetch('/api/notifications?per_page=30', {
                            headers: { 'Accept': 'application/json' }
                        });

                        if (res.ok) {
                            const payload = await res.json();
                            const dbItems = payload.data?.data || payload.data || [];

                            if (dbItems.length > 0) {
                                const dbIds = new Set(dbItems.map(i => i.id));
                                const localCustom = (local || []).filter(i => !dbIds.has(i.id) && String(i.id).startsWith('v-seed'));
                                notifications = [...dbItems, ...localCustom];
                            } else if (local && local.length > 0) {
                                notifications = local;
                            } else {
                                notifications = initialSeed;
                            }
                        } else {
                            notifications = local || initialSeed;
                        }
                    } catch (e) {
                        notifications = local || initialSeed;
                    }

                    saveLocalStored();
                    renderNotifications();
                };

                loadPageNotifications();
            })();
        </script>
    @endpush
@endsection
