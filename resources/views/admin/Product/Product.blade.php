@extends('layouts.Dashboard')

@section('content')
    <x-slot name="title">Products</x-slot>

    <div class="p-4 sm:p-6">
        <div class="mb-6">
            <p class="text-sm font-medium text-[#3d6f6d]">Catalog management</p>
            <h1 class="text-2xl font-bold text-gray-900">Products</h1>
            <p class="mt-1 text-sm text-gray-600">Review and manage all products listed in the marketplace.</p>
        </div>

        <div class="mb-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
            @foreach ([['Total Products', 'product-stat-total', 'package', 'bg-teal-50', 'text-[#3d6f6d]', 'text-[#3d6f6d]'], ['Active', 'product-stat-active', 'circle-check', 'bg-emerald-50', 'text-emerald-600', 'text-emerald-600'], ['Inactive', 'product-stat-inactive', 'pause-circle', 'bg-gray-100', 'text-gray-500', 'text-gray-600'], ['Out of Stock', 'product-stat-out', 'alert-triangle', 'bg-amber-50', 'text-amber-600', 'text-amber-600']] as [$label, $id, $icon, $iconBackground, $iconColor, $valueColor])
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
                <label class="block w-full sm:max-w-sm" for="product-search">
                    <span class="sr-only">Search products</span>
                    <input id="product-search" type="search" placeholder="Search name or description..."
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                </label>
                <p id="product-count" class="text-sm text-gray-500"></p>
            </div>
            <div id="product-status-message" class="mb-4 hidden rounded-lg px-4 py-3 text-sm" role="alert"></div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] text-left text-sm">
                    <thead class="border-b border-gray-200 text-xs uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Product</th>
                            <th class="px-4 py-3 font-semibold">Category</th>
                            <th class="px-4 py-3 font-semibold">Vendor</th>
                            <th class="px-4 py-3 font-semibold">Price</th>
                            <th class="px-4 py-3 font-semibold">Stock</th>
                            <th class="px-4 py-3 font-semibold">Status</th>
                            <th class="px-4 py-3 text-right font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="product-list" class="divide-y divide-gray-100">
                        <tr id="product-loading">
                            <td colspan="7" class="px-4 py-12 text-center">
                                <x-Loading />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="product-empty" class="hidden py-12 text-center text-sm text-gray-500">No products found.</div>
            <div
                class="mt-5 flex flex-col gap-3 border-t border-gray-100 pt-4 sm:flex-row sm:items-center sm:justify-between">
                <p id="pagination-summary" class="text-sm text-gray-500"></p>
                <div id="pagination" class="flex flex-wrap gap-2"></div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (() => {
                const apiUrl = @json(url('/api/products'));
                const detailsUrl = @json(route('admin.product.details'));
                const list = document.querySelector('#product-list');
                const empty = document.querySelector('#product-empty');
                const loading = document.querySelector('#product-loading');
                const status = document.querySelector('#product-status-message');
                const search = document.querySelector('#product-search');
                const count = document.querySelector('#product-count');
                const pagination = document.querySelector('#pagination');
                const summary = document.querySelector('#pagination-summary');
                let timer;

                const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (character) => ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;',
                } [character]));
                const money = (value) => `₹${Number(value ?? 0).toFixed(2)}`;
                const showError = (text) => {
                    status.textContent = text;
                    status.className = 'mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700';
                };

                fetch(@json(url('/api/admin/dashboard/stats')), { headers: { Accept: 'application/json' } })
                    .then((response) => {
                        if (!response.ok) throw new Error('Unable to load product statistics.');
                        return response.json();
                    })
                    .then((payload) => {
                        if (!payload.stats) throw new Error(payload.message || 'Unable to load product statistics.');
                        document.querySelector('#product-stat-total').textContent = payload.stats.total_products;
                        document.querySelector('#product-stat-active').textContent = payload.stats.active_products;
                        document.querySelector('#product-stat-inactive').textContent = payload.stats.inactive_products;
                        document.querySelector('#product-stat-out').textContent = payload.stats.out_of_stock_products;
                    })
                    .catch((error) => showError(error.message));

                const renderPagination = (meta) => {
                    pagination.innerHTML = '';
                    for (let page = 1; page <= meta.last_page; page += 1) {
                        const button = document.createElement('button');
                        button.type = 'button';
                        button.textContent = page;
                        button.disabled = page === meta.current_page;
                        button.className =
                            `rounded-lg px-3 py-2 text-sm font-semibold ${button.disabled ? 'bg-[#3d6f6d] text-white' : 'border border-gray-300 text-gray-700 hover:bg-gray-50'}`;
                        button.addEventListener('click', () => loadProducts(page));
                        pagination.append(button);
                    }
                    summary.textContent = meta.total ? `Showing ${meta.from}-${meta.to} of ${meta.total}` : '';
                };
                const renderProducts = (products, meta) => {
                    list.innerHTML = products.map((product) => `
                        <tr class="text-gray-700">
                            <td class="px-4 py-4"><p class="font-semibold text-gray-900">${escapeHtml(product.name)}</p><p class="text-xs text-gray-500">${escapeHtml(product.visibility || 'public')}</p></td>
                            <td class="px-4 py-4">${escapeHtml(product.category?.name || 'Uncategorized')}</td>
                            <td class="px-4 py-4"><p class="font-medium text-gray-900">${escapeHtml(product.vendor?.name || 'No vendor')}</p><p class="text-xs text-gray-500">${escapeHtml(product.vendor?.email || '')}</p></td>
                            <td class="px-4 py-4 font-semibold">${money(product.price)}</td>
                            <td class="px-4 py-4">${escapeHtml(product.stock)}</td>
                            <td class="px-4 py-4"><span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold capitalize text-gray-700">${escapeHtml(product.status)}</span></td>
                            <td class="px-4 py-4">
                                <div class="flex flex-wrap justify-end gap-2">
                                    <a href="${detailsUrl}?_id=${product.id}" class="inline-flex rounded-lg border border-[#3d6f6d] px-3 py-1.5 text-xs font-semibold text-[#3d6f6d] hover:bg-[#3d6f6d] hover:text-white">Details</a>
                                </div>
                            </td>
                        </tr>
                    `).join('');
                    empty.classList.toggle('hidden', products.length > 0);
                    count.textContent = `${meta.total} product${meta.total === 1 ? '' : 's'}`;
                    renderPagination(meta);
                };
                const showLoading = () => {
                    list.innerHTML = '<tr id="product-loading"><td colspan="7" class="px-4 py-12 text-center"><div class="flex items-center justify-center py-4"><div class="w-12 h-12 border-[3px] border-[#64883e] border-t-transparent rounded-full animate-spin"></div></div></td></tr>';
                };
                const loadProducts = async (page = 1) => {
                    status.classList.add('hidden');
                    empty.classList.add('hidden');
                    showLoading();

                    const params = new URLSearchParams({
                        page,
                        per_page: 10
                    });
                    if (search.value.trim()) params.set('search', search.value.trim());
                    try {
                        const response = await fetch(`${apiUrl}?${params}`, {
                            headers: {
                                Accept: 'application/json'
                            }
                        });
                        const payload = await response.json();
                        if (!response.ok || !payload.success) throw new Error(payload.message ||
                            'Unable to load products.');
                        const items = payload.data?.data ?? [];
                        const meta = payload.data ?? { total: items.length, current_page: 1, last_page: 1 };
                        renderProducts(items, meta);
                    } catch (error) {
                        list.innerHTML = '';
                        empty.classList.add('hidden');
                        showError(error.message);
                    }
                };
                search.addEventListener('input', () => {
                    clearTimeout(timer);
                    timer = setTimeout(() => loadProducts(1), 300);
                });
                loadProducts();
            })();
        </script>
    @endpush
    </div>
@endsection
