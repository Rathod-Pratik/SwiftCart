@extends('layouts.Vendor')

@section('content')
    <x-slot name="title">Orders</x-slot>

    <div class="p-4 sm:p-6">
        <nav class="mb-6 flex items-center gap-2 text-xs font-semibold text-gray-400" aria-label="Breadcrumb">
            <a href="{{ route('vendor.dashboard') }}" class="transition hover:text-gray-600">Dashboard</a>
            <span>/</span>
            <span class="text-gray-800">Orders</span>
        </nav>
        <div class="mb-6">
            <p class="text-sm font-medium text-[#3d6f6d]">Order management</p>
            <h1 class="text-2xl font-bold text-gray-900">Orders</h1>
            <p class="mt-1 text-sm text-gray-600">Track orders containing your products.</p>
        </div>

        <div class="mb-6 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
            @foreach ([['Total Orders', 'order-stat-total', 'shopping-bag', 'bg-teal-50', 'text-[#3d6f6d]', 'text-[#3d6f6d]'], ['Pending', 'order-stat-pending', 'clock', 'bg-amber-50', 'text-amber-600', 'text-amber-600'], ['Processing', 'order-stat-processing', 'package', 'bg-blue-50', 'text-blue-600', 'text-blue-600'], ['Delivered', 'order-stat-delivered', 'circle-check', 'bg-emerald-50', 'text-emerald-600', 'text-emerald-600'], ['Cancelled', 'order-stat-cancelled', 'alert-triangle', 'bg-rose-50', 'text-rose-600', 'text-rose-600']] as [$label, $id, $icon, $iconBackground, $iconColor, $valueColor])
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">{{ $label }}</p>
                            <p id="{{ $id }}" class="mt-2 text-2xl font-bold {{ $valueColor }}">—</p>
                        </div>
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ $iconBackground }} {{ $iconColor }}">
                            <x-dynamic-component :component="'lucide-'.$icon" class="h-5 w-5" />
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="rounded-xl bg-white p-4 shadow-sm sm:p-6">
            <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <label class="block w-full sm:max-w-sm" for="order-search">
                    <span class="sr-only">Search orders</span>
                    <input id="order-search" type="search" placeholder="Search order number or customer..." class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                </label>
                <p id="order-count" class="text-sm text-gray-500"></p>
            </div>
            <div id="order-status" class="mb-4 hidden rounded-lg px-4 py-3 text-sm" role="alert"></div>

            <div class="min-h-[260px] overflow-x-auto">
                <table class="w-full min-w-[780px] text-left text-sm">
                    <thead class="border-b border-gray-200 text-xs uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Order</th>
                            <th class="px-4 py-3 font-semibold">Customer</th>
                            <th class="px-4 py-3 font-semibold">Items</th>
                            <th class="px-4 py-3 font-semibold">Total</th>
                            <th class="px-4 py-3 font-semibold">Status</th>
                            <th class="px-4 py-3 text-right font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="order-list" class="divide-y divide-gray-100"></tbody>
                </table>
            </div>
            <template id="order-loading-template"><x-loading /></template>
            <div id="order-empty" class="hidden py-12 text-center text-sm text-gray-500">No orders found.</div>
            <div class="mt-5 flex flex-col gap-3 border-t border-gray-100 pt-4 sm:flex-row sm:items-center sm:justify-between">
                <p id="pagination-summary" class="text-sm text-gray-500"></p>
                <div id="pagination" class="flex flex-wrap gap-2"></div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (() => {
                const apiUrl = @json(url('/api/vendor/orders'));
                const detailsUrl = @json(route('vendor.order.details'));
                const list = document.querySelector('#order-list');
                const empty = document.querySelector('#order-empty');
                const status = document.querySelector('#order-status');
                const search = document.querySelector('#order-search');
                const loadingTemplate = document.querySelector('#order-loading-template');
                const count = document.querySelector('#order-count');
                const pagination = document.querySelector('#pagination');
                const summary = document.querySelector('#pagination-summary');
                let timer;

                fetch(@json(url('/api/vendor/orders/stats')), { headers: { Accept: 'application/json' } })
                    .then((response) => response.json())
                    .then((payload) => {
                        if (!payload.success) return;
                        document.querySelector('#order-stat-total').textContent = payload.data.total;
                        document.querySelector('#order-stat-pending').textContent = payload.data.pending;
                        document.querySelector('#order-stat-processing').textContent = payload.data.processing;
                        document.querySelector('#order-stat-delivered').textContent = payload.data.delivered;
                        document.querySelector('#order-stat-cancelled').textContent = payload.data.cancelled;
                    })
                    .catch(() => {});

                const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (character) => ({
                    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;',
                }[character]));
                const showError = (message) => {
                    window.showToast?.(message, 'error');
                };
                const renderPagination = (meta) => {
                    pagination.innerHTML = '';
                    for (let page = 1; page <= meta.last_page; page += 1) {
                        const button = document.createElement('button');
                        button.type = 'button';
                        button.textContent = page;
                        button.disabled = page === meta.current_page;
                        button.className = `rounded-lg px-3 py-2 text-sm font-semibold ${button.disabled ? 'bg-[#3d6f6d] text-white' : 'border border-gray-300 text-gray-700 hover:bg-gray-50'}`;
                        button.addEventListener('click', () => loadOrders(page));
                        pagination.append(button);
                    }
                    summary.textContent = meta.total ? `Showing ${meta.from}-${meta.to} of ${meta.total}` : '';
                };
                const renderOrders = (orders, meta) => {
                    list.innerHTML = orders.map((order) => `
                        <tr class="text-gray-700">
                            <td class="px-4 py-4"><p class="font-semibold text-gray-900">${escapeHtml(order.order_number)}</p><p class="text-xs text-gray-500">${escapeHtml(new Date(order.created_at).toLocaleDateString())}</p></td>
                            <td class="px-4 py-4"><p class="font-medium text-gray-900">${escapeHtml(order.shipping_name)}</p><p class="text-xs text-gray-500">${escapeHtml(order.user?.email || order.shipping_phone)}</p></td>
                            <td class="px-4 py-4">${order.items.length}</td>
                            <td class="px-4 py-4 font-semibold">₹${escapeHtml(order.total_amount)}</td>
                            <td class="px-4 py-4"><span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold capitalize text-blue-700">${escapeHtml(order.order_status)}</span></td>
                            <td class="px-4 py-4 text-right"><a href="${detailsUrl}?_id=${order.id}" class="inline-flex rounded-lg border border-[#3d6f6d] px-3 py-1.5 text-xs font-semibold text-[#3d6f6d] hover:bg-[#3d6f6d] hover:text-white">Details</a></td>
                        </tr>
                    `).join('');
                    empty.classList.toggle('hidden', orders.length > 0);
                    count.textContent = `${meta.total} order${meta.total === 1 ? '' : 's'}`;
                    renderPagination(meta);
                };
                const loadOrders = async (page = 1) => {
                    status.classList.add('hidden');
                    list.innerHTML = `<tr><td colspan="6" class="px-4 py-12">${loadingTemplate.innerHTML}</td></tr>`;
                    const params = new URLSearchParams({ page, per_page: 10 });
                    if (search.value.trim()) params.set('search', search.value.trim());
                    try {
                        const response = await fetch(`${apiUrl}?${params}`, { headers: { Accept: 'application/json' } });
                        const payload = await response.json();
                        if (!response.ok || !payload.success) throw new Error(payload.message || 'Unable to load orders.');
                        renderOrders(payload.data.data, payload.data);
                    } catch (error) {
                        list.innerHTML = '';
                        empty.classList.add('hidden');
                        showError(error.message);
                    }
                };
                search.addEventListener('input', () => {
                    clearTimeout(timer);
                    timer = setTimeout(() => loadOrders(1), 300);
                });
                loadOrders();
            })();
        </script>
    @endpush
@endsection
