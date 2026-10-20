@extends('layouts.Dashboard')

@section('content')
    <x-slot name="title">Queries</x-slot>

    <div class="p-4 sm:p-6">
        <div class="rounded-xl bg-white p-4 shadow-sm sm:p-6">
            <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex flex-1 flex-col sm:flex-row gap-3">
                    <label class="block w-full sm:max-w-sm" for="query-search">
                        <span class="sr-only">Search queries</span>
                        <input id="query-search"
                               type="search"
                               placeholder="Search customer, vendor, product, or message..."
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                    </label>

                    <select id="query-type"
                            class="rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-700 outline-none focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20 bg-white">
                        <option value="">All Types</option>
                        <option value="customer_vendor">Customer ↔ Vendor</option>
                        <option value="customer_admin">Customer ↔ Admin</option>
                    </select>
                </div>
                <p id="query-count" class="text-sm text-gray-500" aria-live="polite"></p>
            </div>

            <div id="query-status" class="mb-4 hidden rounded-lg px-4 py-3 text-sm" role="alert"></div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[920px] text-left text-sm">
                    <thead class="border-b border-gray-200 text-xs uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Customer</th>
                            <th class="px-4 py-3 font-semibold">Vendor / Store</th>
                            <th class="px-4 py-3 font-semibold">Product Details</th>
                            <th class="px-4 py-3 font-semibold">Latest Message</th>
                            <th class="px-4 py-3 font-semibold">Last Active</th>
                            <th class="px-4 py-3 text-right font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="query-list" class="divide-y divide-gray-100"></tbody>
                </table>
            </div>

            <div id="query-empty" class="hidden py-12 text-center text-sm text-gray-500">No queries found.</div>

            <div class="mt-5 flex flex-col gap-3 border-t border-gray-100 pt-4 sm:flex-row sm:items-center sm:justify-between">
                <p id="pagination-summary" class="text-sm text-gray-500"></p>
                <div id="pagination" class="flex flex-wrap gap-2"></div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (() => {
                const apiUrl = @json(url('/api/admin/queries'));
                const detailsUrl = @json(route('admin.query.details'));
                const list = document.querySelector('#query-list');
                const emptyState = document.querySelector('#query-empty');
                const pagination = document.querySelector('#pagination');
                const status = document.querySelector('#query-status');
                const count = document.querySelector('#query-count');
                const summary = document.querySelector('#pagination-summary');
                const searchInput = document.querySelector('#query-search');
                const typeSelect = document.querySelector('#query-type');
                let searchTimer;

                const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (character) => ({
                    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;',
                }[character]));

                const formatDate = (value) => {
                    if (!value) return 'N/A';
                    return new Intl.DateTimeFormat(undefined, {
                        dateStyle: 'short',
                        timeStyle: 'short',
                    }).format(new Date(value));
                };

                const showError = (message) => {
                    status.textContent = message;
                    status.className = 'mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700';
                };

                const renderPagination = (meta) => {
                    pagination.innerHTML = '';
                    for (let page = 1; page <= meta.last_page; page += 1) {
                        const button = document.createElement('button');
                        button.type = 'button';
                        button.textContent = page;
                        button.disabled = page === meta.current_page;
                        button.className = `rounded-lg px-3 py-2 text-sm font-semibold ${button.disabled ? 'bg-[#3d6f6d] text-white' : 'border border-gray-300 text-gray-700 hover:bg-gray-50'}`;
                        button.addEventListener('click', () => loadQueries(page));
                        pagination.append(button);
                    }
                    summary.textContent = meta.total ? `Showing ${meta.from}-${meta.to} of ${meta.total}` : '';
                };

                const renderQueries = (queries, meta) => {
                    list.innerHTML = queries.map((query) => {
                        const customer = query.customer || {};
                        const vendor = query.vendor || {};
                        const vendorProfile = vendor.vendor || {};
                        const product = query.product;
                        const latest = query.latest_message;

                        const vendorDisplay = vendorProfile.store_name
                            ? `<p class="font-semibold text-gray-900">${escapeHtml(vendorProfile.store_name)}</p><p class="text-xs text-gray-500">${escapeHtml(vendor.name || 'Vendor')}</p>`
                            : (vendor.name ? `<p class="font-semibold text-gray-900">${escapeHtml(vendor.name)}</p><p class="text-xs text-gray-500">${escapeHtml(vendor.email || '')}</p>` : '<span class="text-xs text-gray-400">Admin Support</span>');

                        const productDisplay = product
                            ? `<div class="max-w-xs">
                                    <p class="font-medium text-[#204d4f] line-clamp-1">${escapeHtml(product.name)}</p>
                                    <p class="text-xs text-gray-500">₹${Number(product.price).toFixed(2)} ${product.category?.name ? '• ' + escapeHtml(product.category.name) : ''}</p>
                               </div>`
                            : '<span class="text-xs text-gray-400">General Inquiry</span>';

                        const latestBody = latest?.body
                            ? `<p class="text-sm text-gray-700 line-clamp-1">${escapeHtml(latest.body)}</p><p class="text-xs text-gray-400">By: ${escapeHtml(latest.sender?.name || 'User')}</p>`
                            : '<span class="text-xs italic text-gray-400">No messages yet</span>';

                        return `
                            <tr class="text-gray-700">
                                <td class="px-4 py-4">
                                    <p class="font-semibold text-gray-900">${escapeHtml(customer.name || 'Customer')}</p>
                                    <p class="text-xs text-gray-500">${escapeHtml(customer.email || '')}</p>
                                </td>
                                <td class="px-4 py-4">${vendorDisplay}</td>
                                <td class="px-4 py-4">${productDisplay}</td>
                                <td class="px-4 py-4">${latestBody}</td>
                                <td class="px-4 py-4 text-xs text-gray-500 whitespace-nowrap">${formatDate(query.last_message_at || query.created_at)}</td>
                                <td class="px-4 py-4 text-right">
                                    <a href="${detailsUrl}?_id=${encodeURIComponent(query.id)}"
                                       class="inline-flex rounded-lg border border-[#3d6f6d] px-3 py-1.5 text-xs font-semibold text-[#3d6f6d] hover:bg-[#3d6f6d] hover:text-white transition">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        `;
                    }).join('');

                    emptyState.classList.toggle('hidden', queries.length > 0);
                    count.textContent = `${meta.total} quer${meta.total === 1 ? 'y' : 'ies'}`;
                    renderPagination(meta);
                };

                const loadQueries = async (page = 1) => {
                    status.classList.add('hidden');
                    list.innerHTML = '<tr><td colspan="6" class="px-4 py-12 text-center text-sm text-gray-500">Loading queries...</td></tr>';
                    const params = new URLSearchParams({ page, per_page: 10 });
                    if (searchInput.value.trim()) params.set('search', searchInput.value.trim());
                    if (typeSelect.value) params.set('type', typeSelect.value);

                    try {
                        const response = await fetch(`${apiUrl}?${params}`, { headers: { Accept: 'application/json' } });
                        const payload = await response.json();
                        if (!response.ok || !payload.success) throw new Error(payload.message || 'Unable to load queries.');
                        renderQueries(payload.data.data, payload.data);
                    } catch (error) {
                        list.innerHTML = '';
                        emptyState.classList.add('hidden');
                        showError(error.message);
                    }
                };

                searchInput.addEventListener('input', () => {
                    clearTimeout(searchTimer);
                    searchTimer = setTimeout(() => loadQueries(1), 300);
                });

                typeSelect.addEventListener('change', () => loadQueries(1));

                loadQueries();
            })();
        </script>
    @endpush
@endsection
