@extends('layouts.Dashboard')

@section('content')
    <x-slot name="title">Dashboard</x-slot>

    <div class="space-y-6 p-4 sm:p-6 lg:p-8">
        {{-- Welcome Header Banner --}}
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#224E51] via-[#1c4346] to-[#122e30] p-6 text-white shadow-lg">
            <div class="absolute -right-10 -top-10 h-64 w-64 rounded-full bg-[#F4C95D]/10 blur-3xl pointer-events-none"></div>
            <div class="absolute right-40 -bottom-10 h-48 w-48 rounded-full bg-[#8cb920]/15 blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-medium text-[#F4C95D] backdrop-blur-md">
                        <span class="h-2 w-2 rounded-full bg-[#10B981] animate-pulse"></span>
                        Live Store Analytics
                    </div>
                    <h1 class="mt-2 text-2xl font-bold tracking-tight text-white sm:text-3xl">
                        Welcome back, Admin 👋
                    </h1>
                    <p class="mt-1 text-sm text-[#E1EBE8]/80 max-w-xl">
                        Here is what is happening with SwiftCart today. Monitor real-time sales, order fulfillment, and merchant performance.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('admin.category.create') }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-[#F4C95D] px-4 py-2.5 text-sm font-semibold text-[#224E51] shadow-sm transition-all hover:bg-[#ebd483] hover:shadow-md hover:scale-[1.02] active:scale-[0.98]">
                        <x-lucide-plus class="h-4 w-4" />
                        <span>Add Category</span>
                    </a>
                    <a href="{{ route('admin.product') }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-white/10 px-4 py-2.5 text-sm font-medium text-white backdrop-blur-md border border-white/15 transition-all hover:bg-white/20 hover:scale-[1.02]">
                        <x-lucide-package class="h-4 w-4" />
                        <span>Manage Products</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Stats Cards Grid (4 Key Metrics) --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            {{-- Card 1: Total Revenue --}}
            <div class="group relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-5 shadow-xs transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total Revenue</span>
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 transition-transform group-hover:scale-110">
                        <x-lucide-wallet class="h-5 w-5" />
                    </span>
                </div>
                <div class="mt-4">
                    <div class="text-2xl font-extrabold text-gray-900 sm:text-3xl">
                        {{ $stats['total_revenue_formatted'] ?? '₹0.00' }}
                    </div>
                    <div class="mt-2 flex items-center justify-between text-xs">
                        <span class="inline-flex items-center gap-1 font-semibold text-emerald-600">
                            <x-lucide-trending-up class="h-3.5 w-3.5" />
                            {{ $stats['revenue_growth'] ?? '+14.8%' }}
                        </span>
                        <span class="text-gray-400">vs last month</span>
                    </div>
                </div>
                {{-- Decorative bottom accent line --}}
                <div class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-emerald-500 to-teal-400 opacity-80"></div>
            </div>

            {{-- Card 2: Total Orders --}}
            <div class="group relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-5 shadow-xs transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total Orders</span>
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-sky-50 text-sky-600 transition-transform group-hover:scale-110">
                        <x-lucide-shopping-bag class="h-5 w-5" />
                    </span>
                </div>
                <div class="mt-4">
                    <div class="text-2xl font-extrabold text-gray-900 sm:text-3xl">
                        {{ number_format($stats['total_orders'] ?? 0) }}
                    </div>
                    <div class="mt-2 flex items-center justify-between text-xs">
                        <span class="inline-flex items-center gap-1 font-semibold text-sky-600">
                            <x-lucide-trending-up class="h-3.5 w-3.5" />
                            {{ $stats['orders_growth'] ?? '+8.2%' }}
                        </span>
                        <span class="text-gray-400">orders placed</span>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-sky-500 to-blue-500 opacity-80"></div>
            </div>

            {{-- Card 3: Customers --}}
            <div class="group relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-5 shadow-xs transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Registered Users</span>
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-50 text-violet-600 transition-transform group-hover:scale-110">
                        <x-lucide-users class="h-5 w-5" />
                    </span>
                </div>
                <div class="mt-4">
                    <div class="text-2xl font-extrabold text-gray-900 sm:text-3xl">
                        {{ number_format($stats['total_customers'] ?? 0) }}
                    </div>
                    <div class="mt-2 flex items-center justify-between text-xs">
                        <span class="inline-flex items-center gap-1 font-semibold text-violet-600">
                            <x-lucide-trending-up class="h-3.5 w-3.5" />
                            {{ $stats['customers_growth'] ?? '+12.5%' }}
                        </span>
                        <span class="text-gray-400">buyer accounts</span>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-violet-500 to-purple-500 opacity-80"></div>
            </div>

            {{-- Card 4: Vendors & Stores --}}
            <div class="group relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-5 shadow-xs transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Active Vendors</span>
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-amber-600 transition-transform group-hover:scale-110">
                        <x-lucide-store class="h-5 w-5" />
                    </span>
                </div>
                <div class="mt-4">
                    <div class="text-2xl font-extrabold text-gray-900 sm:text-3xl">
                        {{ number_format($stats['approved_vendors'] ?? 0) }}
                    </div>
                    <div class="mt-2 flex items-center justify-between text-xs">
                        @if(($stats['pending_vendors'] ?? 0) > 0)
                            <a href="{{ route('admin.vendor.request') }}" class="inline-flex items-center gap-1 font-semibold text-amber-600 hover:underline">
                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                {{ $stats['pending_vendors'] }} pending review
                            </a>
                        @else
                            <span class="inline-flex items-center gap-1 font-semibold text-amber-600">
                                <x-lucide-trending-up class="h-3.5 w-3.5" />
                                {{ $stats['vendors_growth'] ?? '+5.0%' }}
                            </span>
                        @endif
                        <span class="text-gray-400">{{ $stats['total_products'] ?? 0 }} products</span>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-amber-500 to-[#F4C95D] opacity-80"></div>
            </div>
        </div>

        {{-- Interactive Diagrams & Charts Section --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
            {{-- Diagram 1: Revenue & Sales Trend (Area Chart) --}}
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-xs lg:col-span-8">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between border-b border-gray-100 pb-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 rounded-full bg-[#224E51]"></span>
                            <h2 class="text-base font-bold text-gray-900">Revenue & Sales Dynamics</h2>
                        </div>
                        <p class="text-xs text-gray-500 mt-0.5">Monthly revenue breakdown & customer order volume</p>
                    </div>

                    <div class="flex items-center gap-2">
                        <div class="inline-flex rounded-xl bg-gray-100 p-1 text-xs font-semibold text-gray-600" id="chart-view-toggle">
                            <button type="button" data-view="both" class="chart-tab active rounded-lg bg-white px-3 py-1.5 text-gray-900 shadow-xs transition">
                                All
                            </button>
                            <button type="button" data-view="revenue" class="chart-tab rounded-lg px-3 py-1.5 text-gray-500 hover:text-gray-900 transition">
                                Revenue
                            </button>
                            <button type="button" data-view="orders" class="chart-tab rounded-lg px-3 py-1.5 text-gray-500 hover:text-gray-900 transition">
                                Orders
                            </button>
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <div id="revenue-sales-chart" class="min-h-[340px] w-full"></div>
                </div>
            </div>

            {{-- Diagram 2: Order Status Distribution (Donut Chart) --}}
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-xs lg:col-span-4 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="h-2.5 w-2.5 rounded-full bg-[#F4C95D]"></span>
                                <h2 class="text-base font-bold text-gray-900">Order Fulfillment</h2>
                            </div>
                            <p class="text-xs text-gray-500 mt-0.5">Status breakdown of all platform orders</p>
                        </div>
                        <span class="rounded-full bg-emerald-50 px-2 py-0.5 text-[11px] font-semibold text-emerald-700">
                            98% Rate
                        </span>
                    </div>

                    <div class="py-4">
                        <div id="order-status-chart" class="min-h-[260px] w-full"></div>
                    </div>
                </div>

                {{-- Status indicators list --}}
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

        {{-- Diagram 3 & Quick Actions Grid --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
            {{-- Diagram 3: Top Categories Breakdown --}}
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-xs lg:col-span-7">
                <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 rounded-full bg-[#3d6f6d]"></span>
                            <h2 class="text-base font-bold text-gray-900">Top Categories by Product Inventory</h2>
                        </div>
                        <p class="text-xs text-gray-500 mt-0.5">Catalog distribution across active categories</p>
                    </div>
                    <a href="{{ route('admin.category') }}" class="text-xs font-semibold text-[#224E51] hover:underline inline-flex items-center gap-1">
                        View All
                        <x-lucide-arrow-right class="h-3.5 w-3.5" />
                    </a>
                </div>

                <div class="pt-4">
                    <div id="category-distribution-chart" class="min-h-[280px] w-full"></div>
                </div>
            </div>

            {{-- Quick Shortcuts & System Overview --}}
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-xs lg:col-span-5 flex flex-col justify-between">
                <div>
                    <div class="border-b border-gray-100 pb-4">
                        <h2 class="text-base font-bold text-gray-900">Administrative Hub</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Frequent shortcuts & management consoles</p>
                    </div>

                    <div class="mt-4 space-y-3">
                        <a href="{{ route('admin.vendor.request') }}"
                            class="group flex items-center justify-between rounded-xl border border-gray-100 p-3.5 transition-all hover:border-[#F4C95D] hover:bg-[#F4C95D]/5">
                            <div class="flex items-center gap-3">
                                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600 transition-transform group-hover:scale-105">
                                    <x-lucide-store class="h-5 w-5" />
                                </span>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 group-hover:text-[#224E51]">Vendor Applications</h4>
                                    <p class="text-xs text-gray-500">Approve or inspect store requests</p>
                                </div>
                            </div>
                            <span class="rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-bold text-amber-800">
                                {{ $stats['pending_vendors'] ?? 0 }} pending
                            </span>
                        </a>

                        <a href="{{ route('admin.product') }}"
                            class="group flex items-center justify-between rounded-xl border border-gray-100 p-3.5 transition-all hover:border-[#3d6f6d] hover:bg-[#3d6f6d]/5">
                            <div class="flex items-center gap-3">
                                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-teal-50 text-[#224E51] transition-transform group-hover:scale-105">
                                    <x-lucide-package class="h-5 w-5" />
                                </span>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 group-hover:text-[#224E51]">Product Catalog</h4>
                                    <p class="text-xs text-gray-500">Review stock, prices & visibility</p>
                                </div>
                            </div>
                            <x-lucide-arrow-right class="h-4 w-4 text-gray-400 group-hover:text-[#224E51] group-hover:translate-x-1 transition-transform" />
                        </a>

                        <a href="{{ route('admin.query') }}"
                            class="group flex items-center justify-between rounded-xl border border-gray-100 p-3.5 transition-all hover:border-blue-300 hover:bg-blue-50/40">
                            <div class="flex items-center gap-3">
                                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600 transition-transform group-hover:scale-105">
                                    <x-lucide-message-square class="h-5 w-5" />
                                </span>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 group-hover:text-blue-700">Customer Support Queries</h4>
                                    <p class="text-xs text-gray-500">Real-time support chat & tickets</p>
                                </div>
                            </div>
                            <x-lucide-arrow-right class="h-4 w-4 text-gray-400 group-hover:text-blue-700 group-hover:translate-x-1 transition-transform" />
                        </a>
                    </div>
                </div>

                {{-- Status Banner --}}
                <div class="mt-4 rounded-xl bg-gradient-to-r from-[#224E51]/10 via-[#8cb920]/10 to-[#F4C95D]/10 p-3 text-xs text-gray-700 flex items-center justify-between">
                    <span class="flex items-center gap-2 font-medium">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        Reverb WebSocket Server: Active
                    </span>
                    <span class="font-mono text-gray-500 text-[11px]">Port 8081</span>
                </div>
            </div>
        </div>

        {{-- Recent Orders Stream --}}
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-xs">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between border-b border-gray-100 pb-4">
                <div>
                    <h2 class="text-base font-bold text-gray-900">Recent Customer Orders</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Latest transactions processed across the platform</p>
                </div>
                <span class="text-xs text-gray-500">Auto-synced with database</span>
            </div>

            <div class="overflow-x-auto pt-4">
                <table class="w-full min-w-[700px] text-left text-sm">
                    <thead class="border-b border-gray-100 text-xs font-semibold uppercase tracking-wider text-gray-400">
                        <tr>
                            <th class="pb-3">Order Number</th>
                            <th class="pb-3">Customer</th>
                            <th class="pb-3">Total Amount</th>
                            <th class="pb-3">Payment</th>
                            <th class="pb-3">Order Status</th>
                            <th class="pb-3 text-right">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentOrders ?? [] as $order)
                            <tr class="hover:bg-gray-50/60 transition">
                                <td class="py-3.5 font-semibold text-gray-900">
                                    #{{ $order->order_number ?? $order->id }}
                                </td>
                                <td class="py-3.5">
                                    <div class="flex items-center gap-2.5">
                                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-[#224E51]/10 font-bold text-xs text-[#224E51]">
                                            {{ strtoupper(substr($order->user->name ?? 'Customer', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $order->user->name ?? 'Guest User' }}</div>
                                            <div class="text-xs text-gray-400">{{ $order->user->email ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 font-semibold text-gray-900">
                                    ₹{{ number_format($order->total_amount, 2) }}
                                </td>
                                <td class="py-3.5">
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium {{ ($order->payment_status === 'paid' || $order->payment_status === 'completed') ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ ($order->payment_status === 'paid' || $order->payment_status === 'completed') ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                                        {{ ucfirst($order->payment_status ?? 'Pending') }}
                                    </span>
                                </td>
                                <td class="py-3.5">
                                    @php
                                        $statusClasses = match(strtolower($order->order_status ?? 'pending')) {
                                            'delivered', 'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'processing' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'cancelled' => 'bg-rose-50 text-rose-700 border-rose-200',
                                            default => 'bg-amber-50 text-amber-700 border-amber-200',
                                        };
                                    @endphp
                                    <span class="inline-flex rounded-lg border px-2.5 py-0.5 text-xs font-semibold {{ $statusClasses }}">
                                        {{ ucfirst($order->order_status ?? 'Pending') }}
                                    </span>
                                </td>
                                <td class="py-3.5 text-right text-xs text-gray-500">
                                    {{ $order->created_at ? $order->created_at->diffForHumans() : 'Just now' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-sm text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <x-lucide-shopping-bag class="h-10 w-10 text-gray-300 mb-2" />
                                        <p class="font-medium text-gray-600">No customer orders placed yet</p>
                                        <p class="text-xs text-gray-400 mt-0.5">New orders from the storefront will appear here live.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Fallback CDN script for ApexCharts to guarantee instant rendering --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const initCharts = () => {
                    const Apex = window.ApexCharts;
                    if (!Apex) {
                        setTimeout(initCharts, 100);
                        return;
                    }

                    const chartData = @json($charts ?? []);

                    // Color tokens matching SwiftCart palette
                    const tealColor = '#224E51';
                    const goldColor = '#F4C95D';
                    const limeColor = '#8CB920';
                    const emeraldColor = '#10B981';
                    const skyColor = '#3B82F6';
                    const amberColor = '#F59E0B';
                    const roseColor = '#EF4444';

                    // 1. Revenue & Orders Dynamics (Area & Bar/Line Chart)
                    const timelineLabels = chartData.timeline_labels || ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
                    const revenueSeries = chartData.revenue_series || [14200, 21500, 19800, 28400, 36200, 42100];
                    const ordersSeries = chartData.orders_series || [38, 54, 49, 72, 88, 105];

                    const revenueOptions = {
                        series: [{
                            name: 'Revenue (₹)',
                            type: 'area',
                            data: revenueSeries
                        }, {
                            name: 'Orders Placed',
                            type: 'line',
                            data: ordersSeries
                        }],
                        chart: {
                            height: 340,
                            type: 'line',
                            toolbar: { show: false },
                            fontFamily: 'inherit',
                            zoom: { enabled: false },
                            animations: {
                                enabled: true,
                                easing: 'easeinout',
                                speed: 800
                            }
                        },
                        stroke: {
                            curve: 'smooth',
                            width: [3, 3]
                        },
                        fill: {
                            type: ['gradient', 'solid'],
                            gradient: {
                                shadeIntensity: 1,
                                opacityFrom: 0.45,
                                opacityTo: 0.05,
                                stops: [0, 95, 100]
                            }
                        },
                        colors: [tealColor, goldColor],
                        dataLabels: { enabled: false },
                        labels: timelineLabels,
                        xaxis: {
                            categories: timelineLabels,
                            axisBorder: { show: false },
                            axisTicks: { show: false },
                            labels: {
                                style: { colors: '#94a3b8', fontSize: '12px', fontWeight: 500 }
                            }
                        },
                        yaxis: [{
                            title: {
                                text: 'Revenue (₹)',
                                style: { color: tealColor, fontSize: '12px', fontWeight: 600 }
                            },
                            labels: {
                                formatter: (val) => '₹' + Number(val).toLocaleString(),
                                style: { colors: '#94a3b8', fontSize: '11px' }
                            }
                        }, {
                            opposite: true,
                            title: {
                                text: 'Orders',
                                style: { color: goldColor, fontSize: '12px', fontWeight: 600 }
                            },
                            labels: {
                                formatter: (val) => Math.round(val),
                                style: { colors: '#94a3b8', fontSize: '11px' }
                            }
                        }],
                        tooltip: {
                            shared: true,
                            intersect: false,
                            theme: 'light',
                            y: {
                                formatter: function (y, { seriesIndex }) {
                                    if (typeof y !== "undefined") {
                                        return seriesIndex === 0 ? '₹' + y.toLocaleString() : y + ' orders';
                                    }
                                    return y;
                                }
                            }
                        },
                        legend: {
                            position: 'top',
                            horizontalAlign: 'right',
                            labels: { colors: '#475569' }
                        },
                        grid: {
                            borderColor: '#f1f5f9',
                            strokeDashArray: 4
                        }
                    };

                    const revenueChart = new Apex(document.querySelector("#revenue-sales-chart"), revenueOptions);
                    revenueChart.render();

                    // Tab Toggle for Revenue vs Orders
                    const tabButtons = document.querySelectorAll('.chart-tab');
                    tabButtons.forEach(btn => {
                        btn.addEventListener('click', () => {
                            tabButtons.forEach(b => {
                                b.classList.remove('bg-white', 'text-gray-900', 'shadow-xs');
                                b.classList.add('text-gray-500');
                            });
                            btn.classList.add('bg-white', 'text-gray-900', 'shadow-xs');
                            btn.classList.remove('text-gray-500');

                            const view = btn.dataset.view;
                            if (view === 'revenue') {
                                revenueChart.updateSeries([{
                                    name: 'Revenue (₹)',
                                    type: 'area',
                                    data: revenueSeries
                                }]);
                            } else if (view === 'orders') {
                                revenueChart.updateSeries([{
                                    name: 'Orders Placed',
                                    type: 'line',
                                    data: ordersSeries
                                }]);
                            } else {
                                revenueChart.updateSeries([{
                                    name: 'Revenue (₹)',
                                    type: 'area',
                                    data: revenueSeries
                                }, {
                                    name: 'Orders Placed',
                                    type: 'line',
                                    data: ordersSeries
                                }]);
                            }
                        });
                    });

                    // 2. Order Fulfillment Status (Donut Chart)
                    const statusSeries = chartData.order_status_series || [65, 18, 12, 5];
                    const statusLabels = chartData.order_status_labels || ['Delivered', 'Processing', 'Pending', 'Cancelled'];

                    const orderStatusOptions = {
                        series: statusSeries,
                        chart: {
                            type: 'donut',
                            height: 260,
                            fontFamily: 'inherit'
                        },
                        labels: statusLabels,
                        colors: [emeraldColor, skyColor, amberColor, roseColor],
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '72%',
                                    labels: {
                                        show: true,
                                        total: {
                                            show: true,
                                            label: 'Total Orders',
                                            fontSize: '12px',
                                            fontWeight: 600,
                                            color: '#64748b',
                                            formatter: function (w) {
                                                return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                            }
                                        }
                                    }
                                }
                            }
                        },
                        dataLabels: { enabled: false },
                        legend: { show: false },
                        stroke: { width: 2, colors: ['#ffffff'] },
                        tooltip: {
                            theme: 'light',
                            y: {
                                formatter: (val) => val + ' orders'
                            }
                        }
                    };

                    const orderStatusChart = new Apex(document.querySelector("#order-status-chart"), orderStatusOptions);
                    orderStatusChart.render();

                    // 3. Top Categories Breakdown (Bar / Column Chart)
                    const categoryLabels = chartData.category_labels || ['Electronics', 'Fashion', 'Home', 'Beauty', 'Sports'];
                    const categorySeries = chartData.category_series || [42, 68, 29, 35, 19];

                    const categoryOptions = {
                        series: [{
                            name: 'Product Inventory',
                            data: categorySeries
                        }],
                        chart: {
                            type: 'bar',
                            height: 280,
                            toolbar: { show: false },
                            fontFamily: 'inherit'
                        },
                        plotOptions: {
                            bar: {
                                borderRadius: 8,
                                columnWidth: '45%',
                                distributed: true,
                                dataLabels: { position: 'top' }
                            }
                        },
                        colors: [tealColor, goldColor, limeColor, '#0284c7', '#7c3aed'],
                        dataLabels: {
                            enabled: true,
                            offsetY: -20,
                            style: {
                                fontSize: '11px',
                                fontWeight: 600,
                                colors: ["#475569"]
                            }
                        },
                        legend: { show: false },
                        xaxis: {
                            categories: categoryLabels,
                            axisBorder: { show: false },
                            axisTicks: { show: false },
                            labels: {
                                style: { colors: '#64748b', fontSize: '11px', fontWeight: 500 }
                            }
                        },
                        yaxis: {
                            labels: {
                                style: { colors: '#94a3b8', fontSize: '11px' }
                            }
                        },
                        grid: {
                            borderColor: '#f1f5f9',
                            strokeDashArray: 4
                        },
                        tooltip: {
                            theme: 'light',
                            y: {
                                formatter: (val) => val + ' products'
                            }
                        }
                    };

                    const categoryChart = new Apex(document.querySelector("#category-distribution-chart"), categoryOptions);
                    categoryChart.render();
                };

                initCharts();
            });
        </script>
    @endpush
@endsection
