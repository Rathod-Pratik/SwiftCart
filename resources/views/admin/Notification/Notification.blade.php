@extends('layouts.Dashboard')

@section('content')
    <x-slot name="title">Notifications</x-slot>

    <div class="p-4 sm:p-6 lg:p-8">
        {{-- Page Header --}}
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#224e51]/10 text-[#224e51]">
                        <x-lucide-bell class="h-5 w-5" />
                    </span>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">Notification Center</h1>
                </div>
                <p class="mt-1 text-sm text-gray-500">Monitor store activity, vendor requests, stock warnings, and system alerts.</p>
            </div>

            {{-- Action Buttons (Reusing Button Component) --}}
            <div class="flex flex-wrap items-center gap-3">
                <button type="button" onclick="clearReadNotifications()"
                        class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-xs sm:text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none">
                    <x-lucide-trash-2 class="h-4 w-4 text-gray-500" />
                    <span>Clear Read</span>
                </button>

                <x-Button type="button" onclick="markAllAsRead()" class="!bg-[#224e51] hover:!bg-[#1a3d3f] !px-4 !py-2.5 !text-xs sm:!text-sm shadow-sm">
                    <x-lucide-check-check class="mr-2 h-4 w-4" />
                    <span>Mark All as Read</span>
                </x-Button>
            </div>
        </div>

        {{-- Metrics Overview Cards (Reusing Card & Badge Components) --}}
        <div class="mb-8 grid grid-cols-2 gap-4 lg:grid-cols-4">
            <x-Card class="!p-4 sm:!p-5 border-l-4 !border-l-[#224e51]">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Alerts</p>
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-600">
                        <x-lucide-bell class="h-4 w-4" />
                    </span>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span id="stat-total" class="text-2xl font-extrabold text-gray-900">0</span>
                    <span class="text-xs text-gray-400">received</span>
                </div>
            </x-Card>

            <x-Card class="!p-4 sm:!p-5 border-l-4 !border-l-red-500">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Unread</p>
                    <x-Badge variant="danger" size="sm">Requires Action</x-Badge>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span id="stat-unread" class="text-2xl font-extrabold text-red-600">0</span>
                    <span class="text-xs text-gray-400">new alerts</span>
                </div>
            </x-Card>

            <x-Card class="!p-4 sm:!p-5 border-l-4 !border-l-[#F4C95D]">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Vendor Approvals</p>
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#F4C95D]/20 text-[#224e51]">
                        <x-lucide-store class="h-4 w-4" />
                    </span>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span id="stat-vendor" class="text-2xl font-extrabold text-gray-900">0</span>
                    <span class="text-xs text-gray-400">pending</span>
                </div>
            </x-Card>

            <x-Card class="!p-4 sm:!p-5 border-l-4 !border-l-amber-500">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Inventory & Stock</p>
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                        <x-lucide-package class="h-4 w-4" />
                    </span>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span id="stat-stock" class="text-2xl font-extrabold text-gray-900">0</span>
                    <span class="text-xs text-gray-400">warnings</span>
                </div>
            </x-Card>
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
                           placeholder="Search alerts by title, order #, vendor name..."
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
                            <option value="low">Low Priority</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Category Filter Pills --}}
            <div class="mt-4 flex flex-wrap items-center gap-2 border-t border-gray-100 pt-3">
                <button type="button" onclick="selectCategory('all')" data-category="all"
                        class="category-pill active inline-flex items-center gap-1.5 rounded-xl px-3.5 py-1.5 text-xs font-semibold transition bg-[#224e51] text-white">
                    <x-lucide-inbox class="h-3.5 w-3.5" />
                    <span>All</span>
                    <span id="pill-count-all" class="ml-1 rounded-full bg-white/20 px-1.5 py-0.2 text-[10px]">0</span>
                </button>

                <button type="button" onclick="selectCategory('vendor')" data-category="vendor"
                        class="category-pill inline-flex items-center gap-1.5 rounded-xl px-3.5 py-1.5 text-xs font-semibold transition bg-gray-100 text-gray-600 hover:bg-gray-200">
                    <x-lucide-store class="h-3.5 w-3.5 text-[#224e51]" />
                    <span>Vendors</span>
                    <span id="pill-count-vendor" class="ml-1 rounded-full bg-gray-200 px-1.5 py-0.2 text-[10px] text-gray-700">0</span>
                </button>

                <button type="button" onclick="selectCategory('order')" data-category="order"
                        class="category-pill inline-flex items-center gap-1.5 rounded-xl px-3.5 py-1.5 text-xs font-semibold transition bg-gray-100 text-gray-600 hover:bg-gray-200">
                    <x-lucide-shopping-bag class="h-3.5 w-3.5 text-emerald-600" />
                    <span>Orders</span>
                    <span id="pill-count-order" class="ml-1 rounded-full bg-gray-200 px-1.5 py-0.2 text-[10px] text-gray-700">0</span>
                </button>

                <button type="button" onclick="selectCategory('inventory')" data-category="inventory"
                        class="category-pill inline-flex items-center gap-1.5 rounded-xl px-3.5 py-1.5 text-xs font-semibold transition bg-gray-100 text-gray-600 hover:bg-gray-200">
                    <x-lucide-package class="h-3.5 w-3.5 text-amber-600" />
                    <span>Products & Stock</span>
                    <span id="pill-count-inventory" class="ml-1 rounded-full bg-gray-200 px-1.5 py-0.2 text-[10px] text-gray-700">0</span>
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
            {{-- Injected dynamically via JavaScript --}}
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
                const STORAGE_KEY = 'swiftcart_admin_notifications_v1';

                // Initial Seed Notifications for SwiftCart Administration
                const initialSeed = [
                    {
                        id: 'notif-1',
                        title: 'New Vendor Registration Application',
                        description: 'EcoStyle Apparel submitted their business license and KYC documentation for seller approval.',
                        category: 'vendor',
                        priority: 'high',
                        read: false,
                        time: '10 minutes ago',
                        actionUrl: @json(route('admin.vendor')),
                        actionLabel: 'Review Application',
                        icon: 'store',
                        iconBg: 'bg-[#F4C95D]/20 text-[#224e51]',
                    },
                    {
                        id: 'notif-2',
                        title: 'Critical Low Stock Warning',
                        description: 'Ultra Wireless Noise-Cancelling Headphones has reached 2 units remaining in warehouse.',
                        category: 'inventory',
                        priority: 'high',
                        read: false,
                        time: '25 minutes ago',
                        actionUrl: @json(route('admin.product')),
                        actionLabel: 'View Product',
                        icon: 'package',
                        iconBg: 'bg-amber-100 text-amber-700',
                    },
                    {
                        id: 'notif-3',
                        title: 'High-Value Order Received #ORD-9842',
                        description: 'Customer order placed with total value of $1,420.00 via Stripe card checkout.',
                        category: 'order',
                        priority: 'medium',
                        read: false,
                        time: '1 hour ago',
                        actionUrl: @json(route('admin.dashboard')),
                        actionLabel: 'Order Overview',
                        icon: 'shopping-bag',
                        iconBg: 'bg-emerald-100 text-emerald-700',
                    },
                    {
                        id: 'notif-4',
                        title: 'New Customer Support Inquiry',
                        description: 'Sarah Connor opened query "Delivery delay regarding package #SC-5021".',
                        category: 'query',
                        priority: 'medium',
                        read: false,
                        time: '2 hours ago',
                        actionUrl: @json(route('admin.query')),
                        actionLabel: 'Open Ticket',
                        icon: 'message-square',
                        iconBg: 'bg-sky-100 text-sky-700',
                    },
                    {
                        id: 'notif-5',
                        title: 'Vendor Bank Account Update Request',
                        description: 'Urban Trends submitted an IFSC and account number update for weekly disbursement.',
                        category: 'vendor',
                        priority: 'medium',
                        read: false,
                        time: '4 hours ago',
                        actionUrl: @json(route('admin.vendor')),
                        actionLabel: 'Verify Details',
                        icon: 'store',
                        iconBg: 'bg-[#F4C95D]/20 text-[#224e51]',
                    },
                    {
                        id: 'notif-6',
                        title: 'New User Registrations Milestone',
                        description: '50 new customer accounts were created in the past 24 hours across mobile and desktop.',
                        category: 'system',
                        priority: 'low',
                        read: false,
                        time: '5 hours ago',
                        actionUrl: @json(route('admin.user')),
                        actionLabel: 'View Users',
                        icon: 'users',
                        iconBg: 'bg-purple-100 text-purple-700',
                    },
                    {
                        id: 'notif-7',
                        title: 'Automated Database Backup Completed',
                        description: 'Nightly incremental database snapshot was archived successfully to encrypted S3 storage.',
                        category: 'system',
                        priority: 'low',
                        read: true,
                        time: 'Yesterday at 3:00 AM',
                        actionUrl: null,
                        actionLabel: null,
                        icon: 'shield',
                        iconBg: 'bg-gray-100 text-gray-700',
                    },
                    {
                        id: 'notif-8',
                        title: 'New 1-Star Review Flagged for Moderation',
                        description: 'Product "Smart Bluetooth Tracker" received a flagged review referencing shipping disputes.',
                        category: 'query',
                        priority: 'medium',
                        read: true,
                        time: 'Yesterday at 11:20 AM',
                        actionUrl: @json(route('admin.rating')),
                        actionLabel: 'Moderate Review',
                        icon: 'star',
                        iconBg: 'bg-amber-100 text-amber-700',
                    }
                ];

                // Load State
                let notifications = [];
                try {
                    const stored = localStorage.getItem(STORAGE_KEY);
                    notifications = stored ? JSON.parse(stored) : initialSeed;
                } catch (e) {
                    notifications = initialSeed;
                }

                let currentCategory = 'all';
                let currentSearch = '';

                const container = document.getElementById('notifications-container');
                const emptyState = document.getElementById('empty-state');
                const searchInput = document.getElementById('notification-search');
                const filterStatus = document.getElementById('filter-status');
                const filterPriority = document.getElementById('filter-priority');

                const saveState = () => {
                    try {
                        localStorage.setItem(STORAGE_KEY, JSON.stringify(notifications));
                    } catch (e) {
                        console.error('Failed to save notification state:', e);
                    }
                };

                const updateCounters = () => {
                    const total = notifications.length;
                    const unread = notifications.filter(n => !n.read).length;
                    const vendor = notifications.filter(n => n.category === 'vendor').length;
                    const stock = notifications.filter(n => n.category === 'inventory').length;

                    document.getElementById('stat-total').textContent = total;
                    document.getElementById('stat-unread').textContent = unread;
                    document.getElementById('stat-vendor').textContent = vendor;
                    document.getElementById('stat-stock').textContent = stock;

                    document.getElementById('pill-count-all').textContent = total;
                    document.getElementById('pill-count-vendor').textContent = vendor;
                    document.getElementById('pill-count-order').textContent = notifications.filter(n => n.category === 'order').length;
                    document.getElementById('pill-count-inventory').textContent = stock;
                    document.getElementById('pill-count-query').textContent = notifications.filter(n => n.category === 'query').length;
                    document.getElementById('pill-count-system').textContent = notifications.filter(n => n.category === 'system').length;

                    // Update Top Navbar Badge
                    const navBadge = document.getElementById('nav-unread-badge');
                    if (navBadge) {
                        navBadge.textContent = unread;
                        navBadge.style.display = unread > 0 ? 'flex' : 'none';
                    }
                };

                const renderNotifications = () => {
                    const status = filterStatus.value;
                    const priority = filterPriority.value;
                    const search = currentSearch.toLowerCase().trim();

                    const filtered = notifications.filter(item => {
                        // Category filter
                        if (currentCategory !== 'all' && item.category !== currentCategory) return false;

                        // Status filter
                        if (status === 'unread' && item.read) return false;
                        if (status === 'read' && !item.read) return false;

                        // Priority filter
                        if (priority !== 'all' && item.priority !== priority) return false;

                        // Search
                        if (search) {
                            const matchTitle = item.title.toLowerCase().includes(search);
                            const matchDesc = item.description.toLowerCase().includes(search);
                            const matchCat = item.category.toLowerCase().includes(search);
                            if (!matchTitle && !matchDesc && !matchCat) return false;
                        }

                        return true;
                    });

                    updateCounters();

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
                            vendor: 'Vendor',
                            order: 'Order',
                            inventory: 'Inventory',
                            query: 'Support',
                            system: 'System'
                        }[item.category] || 'Alert';

                        return `
                            <div id="${item.id}"
                                 class="group relative flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl border p-4 sm:p-5 transition-all duration-200 ${
                                     item.read
                                         ? 'bg-white border-gray-100 hover:border-gray-200 hover:shadow-sm'
                                         : 'bg-[#F9FCFB] border-[#224e51]/20 shadow-sm ring-1 ring-[#224e51]/5'
                                 }">
                                <div class="flex items-start gap-4">
                                    {{-- Unread Dot Indicator --}}
                                    <div class="mt-1 flex items-center justify-center">
                                        ${
                                            item.read
                                                ? `<span class="h-2.5 w-2.5 rounded-full bg-gray-200"></span>`
                                                : `<span class="h-2.5 w-2.5 rounded-full bg-[#224e51] ring-4 ring-[#224e51]/20 animate-pulse"></span>`
                                        }
                                    </div>

                                    {{-- Icon Container --}}
                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl ${item.iconBg} font-bold shadow-xs">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            ${
                                                item.category === 'vendor'
                                                    ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>'
                                                    : item.category === 'order'
                                                    ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>'
                                                    : item.category === 'inventory'
                                                    ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>'
                                                    : item.category === 'query'
                                                    ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>'
                                                    : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>'
                                            }
                                        </svg>
                                    </div>

                                    {{-- Content Block --}}
                                    <div class="flex-1">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <h3 class="text-sm font-bold text-gray-900 ${item.read ? 'text-gray-700' : 'text-gray-950'}">${item.title}</h3>
                                            <span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider text-gray-600">${categoryName}</span>
                                            ${priorityBadge}
                                        </div>
                                        <p class="mt-1 text-xs sm:text-sm text-gray-600 leading-relaxed">${item.description}</p>
                                        <div class="mt-2 flex items-center gap-3 text-xs text-gray-400">
                                            <span class="flex items-center gap-1">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                ${item.time}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Action Buttons --}}
                                <div class="flex items-center justify-end gap-2 border-t border-gray-100 pt-3 sm:border-0 sm:pt-0 shrink-0">
                                    ${
                                        item.actionUrl
                                            ? `
                                            <a href="${item.actionUrl}"
                                               class="inline-flex items-center gap-1.5 rounded-xl bg-[#224e51]/10 px-3 py-1.5 text-xs font-semibold text-[#224e51] transition hover:bg-[#224e51] hover:text-white">
                                                <span>${item.actionLabel || 'View'}</span>
                                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                            </a>
                                            `
                                            : ''
                                    }

                                    <button type="button" onclick="toggleReadStatus('${item.id}')"
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

                // Filter & Actions Handlers
                window.selectCategory = (category) => {
                    currentCategory = category;
                    document.querySelectorAll('.category-pill').forEach(pill => {
                        const isMatch = pill.dataset.category === category;
                        pill.className = isMatch
                            ? 'category-pill active inline-flex items-center gap-1.5 rounded-xl px-3.5 py-1.5 text-xs font-semibold transition bg-[#224e51] text-white'
                            : 'category-pill inline-flex items-center gap-1.5 rounded-xl px-3.5 py-1.5 text-xs font-semibold transition bg-gray-100 text-gray-600 hover:bg-gray-200';
                    });
                    renderNotifications();
                };

                window.filterNotifications = () => {
                    renderNotifications();
                };

                window.toggleReadStatus = (id) => {
                    const item = notifications.find(n => n.id === id);
                    if (item) {
                        item.read = !item.read;
                        saveState();
                        renderNotifications();
                    }
                };

                window.deleteNotification = (id) => {
                    notifications = notifications.filter(n => n.id !== id);
                    saveState();
                    renderNotifications();
                };

                window.markAllAsRead = () => {
                    notifications.forEach(n => n.read = true);
                    saveState();
                    renderNotifications();
                };

                window.clearReadNotifications = () => {
                    notifications = notifications.filter(n => !n.read);
                    saveState();
                    renderNotifications();
                };

                window.resetFilters = () => {
                    searchInput.value = '';
                    currentSearch = '';
                    filterStatus.value = 'all';
                    filterPriority.value = 'all';
                    selectCategory('all');
                };

                if (searchInput) {
                    searchInput.addEventListener('input', (e) => {
                        currentSearch = e.target.value;
                        renderNotifications();
                    });
                }

                // Initial render
                renderNotifications();
            })();
        </script>
    @endpush
@endsection
