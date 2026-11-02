@extends('layouts.Vendor')

@section('content')
    <x-slot name="title">Vendor Dashboard</x-slot>

    @php
        /** @var \App\Models\User $vendorAuth */
        $vendorAuth = auth()->user();
        $storeName  = $vendorAuth->vendor?->store_name ?? $vendorAuth->name;
    @endphp

    <div class="space-y-6 p-4 sm:p-6 lg:p-8">
        <nav class="flex items-center gap-2 text-xs font-semibold text-gray-400" aria-label="Breadcrumb">
            <a href="{{ route('vendor.dashboard') }}" class="text-gray-800 transition hover:text-gray-600">Dashboard</a>
        </nav>

        {{-- ── Welcome Hero Banner ── --}}
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#224E51] via-[#1c4346] to-[#122e30] p-6 text-white shadow-lg">
            <div class="absolute -right-10 -top-10 h-64 w-64 rounded-full bg-[#F4C95D]/10 blur-3xl pointer-events-none"></div>
            <div class="absolute right-40 -bottom-10 h-48 w-48 rounded-full bg-[#8cb920]/15 blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-medium text-[#F4C95D] backdrop-blur-md">
                        <span class="h-2 w-2 rounded-full bg-[#10B981] animate-pulse"></span>
                        Vendor Store
                    </div>
                    <h1 class="mt-2 text-2xl font-bold tracking-tight text-white sm:text-3xl">
                        Welcome back, {{ $vendorAuth->name }} 👋
                    </h1>
                    <p class="mt-1 max-w-xl text-sm text-[#E1EBE8]/80">
                        Here is what is happening with <span class="font-semibold text-[#F4C95D]">{{ $storeName }}</span> today. Track your products, orders, and customer ratings.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('vendor.product.create') }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-[#F4C95D] px-4 py-2.5 text-sm font-semibold text-[#224E51] shadow-sm transition-all hover:bg-[#ebd483] hover:shadow-md hover:scale-[1.02] active:scale-[0.98]">
                        <x-lucide-plus class="h-4 w-4" />
                        <span>Add Product</span>
                    </a>
                    <a href="{{ route('vendor.order') }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-white/10 px-4 py-2.5 text-sm font-medium text-white backdrop-blur-md border border-white/15 transition-all hover:bg-white/20 hover:scale-[1.02]">
                        <x-lucide-clipboard-list class="h-4 w-4" />
                        <span>Manage Orders</span>
                    </a>
                    <a href="{{ route('vendor.coupon') }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-white/10 px-4 py-2.5 text-sm font-medium text-white backdrop-blur-md border border-white/15 transition-all hover:bg-white/20 hover:scale-[1.02]">
                        <x-lucide-ticket-percent class="h-4 w-4" />
                        <span>Manage Coupons</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- ── Stat Cards ── --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            {{-- Total Products --}}
            <div class="group relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-5 shadow-xs transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">My Products</span>
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-teal-50 text-[#224E51] transition-transform group-hover:scale-110">
                        <x-lucide-package class="h-5 w-5" />
                    </span>
                </div>
                <div class="mt-4">
                    <div id="stat-products" class="text-2xl font-extrabold text-gray-900 sm:text-3xl">—</div>
                    <div class="mt-2 flex items-center justify-between text-xs">
                        <span class="inline-flex items-center gap-1 font-semibold text-[#224E51]">
                            <x-lucide-trending-up class="h-3.5 w-3.5" />
                            <span id="stat-active-products">0 active</span>
                        </span>
                        <span class="text-gray-400">in your store</span>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-[#224E51] to-teal-400 opacity-80"></div>
            </div>

            {{-- Pending Orders --}}
            <div class="group relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-5 shadow-xs transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Pending Orders</span>
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-amber-600 transition-transform group-hover:scale-110">
                        <x-lucide-clock class="h-5 w-5" />
                    </span>
                </div>
                <div class="mt-4">
                    <div id="stat-pending" class="text-2xl font-extrabold text-gray-900 sm:text-3xl">—</div>
                    <div class="mt-2 flex items-center justify-between text-xs">
                        <span class="inline-flex items-center gap-1 font-semibold text-amber-600">
                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                            Awaiting action
                        </span>
                        <span class="text-gray-400">orders</span>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-amber-500 to-[#F4C95D] opacity-80"></div>
            </div>

            {{-- Completed Orders --}}
            <div class="group relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-5 shadow-xs transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Completed Orders</span>
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 transition-transform group-hover:scale-110">
                        <x-lucide-circle-check class="h-5 w-5" />
                    </span>
                </div>
                <div class="mt-4">
                    <div id="stat-completed" class="text-2xl font-extrabold text-gray-900 sm:text-3xl">—</div>
                    <div class="mt-2 flex items-center justify-between text-xs">
                        <span class="inline-flex items-center gap-1 font-semibold text-emerald-600">
                            <x-lucide-trending-up class="h-3.5 w-3.5" />
                            Successfully fulfilled
                        </span>
                        <span class="text-gray-400">delivered</span>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-emerald-500 to-teal-400 opacity-80"></div>
            </div>

            {{-- Store Rating --}}
            <div class="group relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-5 shadow-xs transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Store Rating</span>
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-50 text-violet-600 transition-transform group-hover:scale-110">
                        <x-lucide-star class="h-5 w-5" />
                    </span>
                </div>
                <div class="mt-4">
                    <div id="stat-rating" class="text-2xl font-extrabold text-gray-900 sm:text-3xl">—</div>
                    <div class="mt-2 flex items-center justify-between text-xs">
                        <span class="inline-flex items-center gap-1 font-semibold text-violet-600">
                            <span id="stat-reviews">0 reviews</span>
                        </span>
                        <span class="text-gray-400">avg. score</span>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-violet-500 to-purple-400 opacity-80"></div>
            </div>
        </div>

        {{-- ── Charts Row ── --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">

            {{-- Order Status Trend (Area Chart) --}}
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-xs lg:col-span-8">
                <div class="flex flex-col gap-3 border-b border-gray-100 pb-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 rounded-full bg-[#224E51]"></span>
                            <h2 class="text-base font-bold text-gray-900">Orders Overview</h2>
                        </div>
                        <p class="mt-0.5 text-xs text-gray-500">Monthly order volume by status</p>
                    </div>
                    <div class="inline-flex rounded-xl bg-gray-100 p-1 text-xs font-semibold text-gray-600" id="chart-toggle">
                        <button type="button" data-view="all"
                                class="chart-tab active rounded-lg bg-white px-3 py-1.5 text-gray-900 shadow-xs transition">All</button>
                        <button type="button" data-view="completed"
                                class="chart-tab rounded-lg px-3 py-1.5 text-gray-500 hover:text-gray-900 transition">Completed</button>
                        <button type="button" data-view="pending"
                                class="chart-tab rounded-lg px-3 py-1.5 text-gray-500 hover:text-gray-900 transition">Pending</button>
                    </div>
                </div>
                <div class="pt-4">
                    <div id="vendor-orders-chart" class="min-h-[300px] w-full"></div>
                </div>
            </div>

            {{-- Order Status Donut --}}
            <div class="flex flex-col justify-between rounded-2xl border border-gray-100 bg-white p-5 shadow-xs lg:col-span-4">
                <div>
                    <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="h-2.5 w-2.5 rounded-full bg-[#F4C95D]"></span>
                                <h2 class="text-base font-bold text-gray-900">Order Status</h2>
                            </div>
                            <p class="mt-0.5 text-xs text-gray-500">Breakdown of your store orders</p>
                        </div>
                        <span id="fulfillment-rate" class="rounded-full bg-emerald-50 px-2 py-0.5 text-[11px] font-semibold text-emerald-700">— %</span>
                    </div>
                    <div class="py-4">
                        <div id="vendor-status-chart" class="min-h-[220px] w-full"></div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2 border-t border-gray-100 pt-4 text-xs">
                    <div class="flex items-center gap-2 rounded-lg bg-gray-50 p-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-[#10B981]"></span>
                        <span class="text-gray-600">Delivered</span>
                    </div>
                    <div class="flex items-center gap-2 rounded-lg bg-gray-50 p-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-[#3B82F6]"></span>
                        <span class="text-gray-600">Processing</span>
                    </div>
                    <div class="flex items-center gap-2 rounded-lg bg-gray-50 p-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-[#F59E0B]"></span>
                        <span class="text-gray-600">Pending</span>
                    </div>
                    <div class="flex items-center gap-2 rounded-lg bg-gray-50 p-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-[#EF4444]"></span>
                        <span class="text-gray-600">Cancelled</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Bottom Row: Quick Actions + Recent Orders ── --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">

            {{-- Quick Actions Hub --}}
            <div class="flex flex-col justify-between rounded-2xl border border-gray-100 bg-white p-5 shadow-xs lg:col-span-4">
                <div>
                    <div class="border-b border-gray-100 pb-4">
                        <h2 class="text-base font-bold text-gray-900">Vendor Hub</h2>
                        <p class="mt-0.5 text-xs text-gray-500">Frequently used actions & shortcuts</p>
                    </div>

                    <div class="mt-4 space-y-3">
                        <a href="{{ route('vendor.product.create') }}"
                           class="group flex items-center justify-between rounded-xl border border-gray-100 p-3.5 transition-all hover:border-[#F4C95D] hover:bg-[#F4C95D]/5">
                            <div class="flex items-center gap-3">
                                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-teal-50 text-[#224E51] transition-transform group-hover:scale-105">
                                    <x-lucide-package-plus class="h-5 w-5" />
                                </span>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 group-hover:text-[#224E51]">Add Product</h4>
                                    <p class="text-xs text-gray-500">List a new item in your store</p>
                                </div>
                            </div>
                            <x-lucide-arrow-right class="h-4 w-4 text-gray-400 transition-transform group-hover:translate-x-1 group-hover:text-[#224E51]" />
                        </a>

                        <a href="{{ route('vendor.order') }}"
                           class="group flex items-center justify-between rounded-xl border border-gray-100 p-3.5 transition-all hover:border-amber-300 hover:bg-amber-50/40">
                            <div class="flex items-center gap-3">
                                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600 transition-transform group-hover:scale-105">
                                    <x-lucide-clipboard-list class="h-5 w-5" />
                                </span>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 group-hover:text-amber-700">View Orders</h4>
                                    <p class="text-xs text-gray-500">Review & manage recent orders</p>
                                </div>
                            </div>
                            <x-lucide-arrow-right class="h-4 w-4 text-gray-400 transition-transform group-hover:translate-x-1 group-hover:text-amber-600" />
                        </a>

                        <a href="{{ route('vendor.query') }}"
                           class="group flex items-center justify-between rounded-xl border border-gray-100 p-3.5 transition-all hover:border-blue-300 hover:bg-blue-50/40">
                            <div class="flex items-center gap-3">
                                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600 transition-transform group-hover:scale-105">
                                    <x-lucide-message-square class="h-5 w-5" />
                                </span>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 group-hover:text-blue-700">Customer Queries</h4>
                                    <p class="text-xs text-gray-500">Respond to customer messages</p>
                                </div>
                            </div>
                            <x-lucide-arrow-right class="h-4 w-4 text-gray-400 transition-transform group-hover:translate-x-1 group-hover:text-blue-600" />
                        </a>
                    </div>
                </div>

                {{-- Store status badge --}}
                <div class="mt-4 rounded-xl bg-gradient-to-r from-[#224E51]/10 via-[#8cb920]/10 to-[#F4C95D]/10 p-3 text-xs text-gray-700 flex items-center justify-between">
                    <span class="flex items-center gap-2 font-medium">
                        <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Store Status: Active
                    </span>
                    <a href="{{ route('home') }}" class="font-semibold text-[#224E51] hover:underline">View Store →</a>
                </div>
            </div>

            {{-- Recent Orders Table --}}
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-xs lg:col-span-8">
                <div class="flex flex-col gap-2 border-b border-gray-100 pb-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-base font-bold text-gray-900">Recent Orders</h2>
                        <p class="mt-0.5 text-xs text-gray-500">Latest orders from your store</p>
                    </div>
                    <a href="{{ route('vendor.order') }}" class="inline-flex items-center gap-1 text-xs font-semibold text-[#224E51] hover:underline">
                        View all <x-lucide-arrow-right class="h-3.5 w-3.5" />
                    </a>
                </div>

                <div class="overflow-x-auto pt-4">
                    <table class="w-full min-w-[560px] text-left text-sm">
                        <thead class="border-b border-gray-100 text-xs font-semibold uppercase tracking-wider text-gray-400">
                            <tr>
                                <th class="pb-3">Order</th>
                                <th class="pb-3">Customer</th>
                                <th class="pb-3">Amount</th>
                                <th class="pb-3">Status</th>
                                <th class="pb-3 text-right">Date</th>
                            </tr>
                        </thead>
                        <tbody id="recent-orders-body" class="divide-y divide-gray-100">
                            <tr>
                                <td colspan="5" class="py-8 text-center text-sm text-gray-400">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="h-6 w-6 animate-spin text-gray-300" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                        </svg>
                                        Loading orders…
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    @push('scripts')
        <script>
            (() => {
                const ordersApi   = @json(url('/api/vendor/orders'));
                const productsApi = @json(url('/api/products'));
                const ratingsApi  = @json(url('/api/vendor/ratings/products'));

                // ── Helpers ──────────────────────────────────────────
                const $ = (sel) => document.querySelector(sel);
                const setText = (sel, val) => { const el = $(sel); if (el) el.textContent = val; };
                const fetchJson = async (url) => {
                    const response = await fetch(url, { headers: { Accept: 'application/json' } });

                    if (!response.ok) {
                        throw new Error(`Request failed with status ${response.status}`);
                    }

                    return response.json();
                };

                const statusConfig = {
                    delivered : { bg: 'bg-emerald-50', text: 'text-emerald-700', border: 'border-emerald-200', dot: 'bg-emerald-500' },
                    completed : { bg: 'bg-emerald-50', text: 'text-emerald-700', border: 'border-emerald-200', dot: 'bg-emerald-500' },
                    processing: { bg: 'bg-blue-50',    text: 'text-blue-700',    border: 'border-blue-200',   dot: 'bg-blue-500'    },
                    cancelled : { bg: 'bg-rose-50',    text: 'text-rose-700',    border: 'border-rose-200',   dot: 'bg-rose-500'    },
                    pending   : { bg: 'bg-amber-50',   text: 'text-amber-700',   border: 'border-amber-200',  dot: 'bg-amber-500'   },
                };
                const getStatusCfg = (s) => statusConfig[s?.toLowerCase()] ?? statusConfig.pending;

                const fmt = new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR', minimumFractionDigits: 2 });

                // ── Fetch vendor data in parallel ────────────────────
                Promise.all([
                    fetchJson(ordersApi),
                    fetchJson(productsApi),
                    fetchJson(ratingsApi),
                ]).then(([ordersData, productsData, ratingsData]) => {
                    const orders   = ordersData.data?.data ?? ordersData.data ?? [];
                    const products = productsData.data?.data ?? productsData.data ?? [];
                    const ratings  = ratingsData.data?.data ?? ratingsData.data ?? [];

                    // ── Stat cards ──────────────────────────────────
                    const totalProducts  = productsData.data?.total ?? products.length;
                    const activeProducts = products.filter(p => p.status === 'active' || p.is_active).length;

                    const pendingOrders   = orders.filter(o => ['pending'].includes(o.order_status?.toLowerCase())).length;
                    const completedOrders = orders.filter(o => ['delivered','completed'].includes(o.order_status?.toLowerCase())).length;

                    const totalReviews = ratings.reduce((acc, p) => acc + (p.reviews_count ?? 0), 0);
                    const avgRating    = ratings.length
                        ? (ratings.reduce((acc, p) => acc + parseFloat(p.average_rating ?? 0), 0) / ratings.length).toFixed(1)
                        : null;

                    setText('#stat-products',        totalProducts);
                    setText('#stat-active-products', `${activeProducts} active`);
                    setText('#stat-pending',         pendingOrders);
                    setText('#stat-completed',       completedOrders);
                    setText('#stat-rating',          avgRating ? `${avgRating} ★` : '—');
                    setText('#stat-reviews',         `${totalReviews} review${totalReviews !== 1 ? 's' : ''}`);

                    // ── Recent orders table ──────────────────────────
                    const tbody = $('#recent-orders-body');
                    if (tbody) {
                        const recent = [...orders].slice(0, 8);
                        if (recent.length === 0) {
                            tbody.innerHTML = `<tr><td colspan="5" class="py-8 text-center text-sm text-gray-400">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="h-10 w-10 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007Z" />
                                    </svg>
                                    <p class="font-medium text-gray-500">No orders yet</p>
                                    <p class="text-xs text-gray-400">Orders from your store will appear here.</p>
                                </div></td></tr>`;
                        } else {
                            tbody.innerHTML = recent.map(order => {
                                const cfg     = getStatusCfg(order.order_status);
                                const initials = (order.user?.name ?? 'C').split(' ').map(p => p[0]).slice(0, 2).join('').toUpperCase();
                                const date    = order.created_at ? new Date(order.created_at).toLocaleDateString('en-IN', { day: '2-digit', month: 'short' }) : '—';
                                return `<tr class="hover:bg-gray-50/60 transition">
                                    <td class="py-3.5 font-semibold text-gray-900">#${order.order_number ?? order.id}</td>
                                    <td class="py-3.5">
                                        <div class="flex items-center gap-2.5">
                                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-[#224E51]/10 text-xs font-bold text-[#224E51]">${initials}</div>
                                            <span class="font-medium text-gray-900">${order.user?.name ?? 'Customer'}</span>
                                        </div>
                                    </td>
                                    <td class="py-3.5 font-semibold text-gray-900">${fmt.format(order.total_amount ?? 0)}</td>
                                    <td class="py-3.5">
                                        <span class="inline-flex items-center gap-1.5 rounded-lg border px-2.5 py-0.5 text-xs font-semibold ${cfg.bg} ${cfg.text} ${cfg.border}">
                                            <span class="h-1.5 w-1.5 rounded-full ${cfg.dot}"></span>
                                            ${(order.order_status ?? 'Pending').charAt(0).toUpperCase() + (order.order_status ?? 'Pending').slice(1)}
                                        </span>
                                    </td>
                                    <td class="py-3.5 text-right text-xs text-gray-500">${date}</td>
                                </tr>`;
                            }).join('');
                        }
                    }

                    // ── Charts ───────────────────────────────────────
                    const initCharts = () => {
                        const Apex = window.ApexCharts;
                        if (!Apex) { setTimeout(initCharts, 150); return; }

                        // Build monthly buckets (last 6 months)
                        const months = [];
                        const now = new Date();
                        for (let i = 5; i >= 0; i--) {
                            const d = new Date(now.getFullYear(), now.getMonth() - i, 1);
                            months.push({ label: d.toLocaleString('en-IN', { month: 'short' }), year: d.getFullYear(), month: d.getMonth() });
                        }

                        const countByMonth = (arr, statusList) => months.map(m =>
                            arr.filter(o => {
                                const d = new Date(o.created_at);
                                const statusMatch = statusList ? statusList.includes(o.order_status?.toLowerCase()) : true;
                                return d.getFullYear() === m.year && d.getMonth() === m.month && statusMatch;
                            }).length
                        );

                        const allSeries      = countByMonth(orders, null);
                        const completedSeries = countByMonth(orders, ['delivered','completed']);
                        const pendingSeries  = countByMonth(orders, ['pending']);
                        const monthLabels    = months.map(m => m.label);

                        // ── Orders trend (area chart) ──
                        const ordersChart = new Apex(document.querySelector('#vendor-orders-chart'), {
                            series: [
                                { name: 'All Orders',   data: allSeries },
                                { name: 'Completed',    data: completedSeries },
                                { name: 'Pending',      data: pendingSeries },
                            ],
                            chart: {
                                height: 300, type: 'area', toolbar: { show: false },
                                fontFamily: 'inherit',
                                animations: { enabled: true, easing: 'easeinout', speed: 800 },
                            },
                            stroke: { curve: 'smooth', width: [2.5, 2.5, 2.5] },
                            fill: {
                                type: 'gradient',
                                gradient: { shadeIntensity: 1, opacityFrom: 0.3, opacityTo: 0.02, stops: [0, 90, 100] },
                            },
                            colors: ['#224E51', '#10B981', '#F59E0B'],
                            xaxis: { categories: monthLabels, axisBorder: { show: false }, axisTicks: { show: false }, labels: { style: { fontSize: '11px', colors: '#94a3b8' } } },
                            yaxis: { labels: { formatter: v => Math.round(v), style: { fontSize: '11px', colors: '#94a3b8' } }, min: 0 },
                            grid: { strokeDashArray: 4, borderColor: '#f1f5f9' },
                            legend: { position: 'top', fontSize: '12px' },
                            tooltip: { y: { formatter: v => `${v} orders` } },
                            dataLabels: { enabled: false },
                        });
                        ordersChart.render();

                        // Chart toggle
                        document.querySelectorAll('#chart-toggle .chart-tab').forEach(btn => {
                            btn.addEventListener('click', () => {
                                document.querySelectorAll('#chart-toggle .chart-tab').forEach(b => {
                                    b.classList.remove('bg-white', 'text-gray-900', 'shadow-xs');
                                    b.classList.add('text-gray-500');
                                });
                                btn.classList.add('bg-white', 'text-gray-900', 'shadow-xs');
                                btn.classList.remove('text-gray-500');
                                const view = btn.dataset.view;
                                const seriesMap = {
                                    all:       [{ name: 'All Orders', data: allSeries }, { name: 'Completed', data: completedSeries }, { name: 'Pending', data: pendingSeries }],
                                    completed: [{ name: 'Completed', data: completedSeries }],
                                    pending:   [{ name: 'Pending',   data: pendingSeries }],
                                };
                                ordersChart.updateSeries(seriesMap[view] ?? seriesMap.all);
                            });
                        });

                        // ── Status donut chart ──
                        const processingCount = orders.filter(o => o.order_status?.toLowerCase() === 'processing').length;
                        const cancelledCount  = orders.filter(o => o.order_status?.toLowerCase() === 'cancelled').length;
                        const deliveredCount  = orders.filter(o => ['delivered','completed'].includes(o.order_status?.toLowerCase())).length;
                        const pendingCount    = orders.filter(o => o.order_status?.toLowerCase() === 'pending').length;

                        const total = deliveredCount + processingCount + pendingCount + cancelledCount;
                        const fulfillRate = total > 0 ? Math.round((deliveredCount / total) * 100) : 0;
                        const rateEl = $('#fulfillment-rate');
                        if (rateEl) rateEl.textContent = `${fulfillRate}% Fulfilled`;

                        const statusChart = new Apex(document.querySelector('#vendor-status-chart'), {
                            series: [deliveredCount, processingCount, pendingCount, cancelledCount],
                            chart: { height: 220, type: 'donut', fontFamily: 'inherit', animations: { enabled: true, easing: 'easeinout', speed: 800 } },
                            labels: ['Delivered', 'Processing', 'Pending', 'Cancelled'],
                            colors: ['#10B981', '#3B82F6', '#F59E0B', '#EF4444'],
                            plotOptions: { pie: { donut: { size: '65%', labels: { show: true, total: { show: true, label: 'Total', fontSize: '13px', color: '#374151', formatter: () => total } } } } },
                            legend: { show: false },
                            dataLabels: { enabled: false },
                            tooltip: { y: { formatter: v => `${v} orders` } },
                        });
                        statusChart.render();
                    initCharts();
                    window.showToast?.('Dashboard data loaded successfully.', 'success');
                }).catch((error) => {
                    console.error('Vendor dashboard data loading failed:', error);
                    window.showToast?.('Unable to load dashboard data. Please refresh and try again.', 'error');
                });
            })();
        </script>
    @endpush
@endsection
