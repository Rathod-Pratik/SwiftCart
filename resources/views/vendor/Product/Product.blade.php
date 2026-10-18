@extends('layouts.Vendor')

@section('content')
    <x-slot name="title">My Products</x-slot>

    <div class="p-4 sm:p-6">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-[#3d6f6d]">Product management</p>
                <h1 class="text-2xl font-bold text-gray-900">My Products</h1>
                <p class="mt-1 text-sm text-gray-600">Manage the products listed in your store.</p>
            </div>
            <a href="{{ route('vendor.product.create') }}" class="inline-flex items-center justify-center rounded-lg bg-[#3d6f6d] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#315b59]">
                Add Product
            </a>
        </div>

        <div class="rounded-xl bg-white p-4 shadow-sm sm:p-6">
            <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <label class="block w-full sm:max-w-sm" for="product-search">
                    <span class="sr-only">Search products</span>
                    <input id="product-search" type="search" placeholder="Search products..." class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                </label>
                <p id="product-count" class="text-sm text-gray-500"></p>
            </div>
            <div id="product-status" class="mb-4 hidden rounded-lg px-4 py-3 text-sm" role="alert"></div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[850px] text-left text-sm">
                    <thead class="border-b border-gray-200 text-xs uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Product</th>
                            <th class="px-4 py-3 font-semibold">Category</th>
                            <th class="px-4 py-3 font-semibold">Price</th>
                            <th class="px-4 py-3 font-semibold">Stock</th>
                            <th class="px-4 py-3 font-semibold">Status</th>
                            <th class="px-4 py-3 text-right font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="product-list" class="divide-y divide-gray-100"></tbody>
                </table>
            </div>
            <div id="product-empty" class="hidden py-12 text-center text-sm text-gray-500">No products found.</div>
            <div class="mt-5 flex flex-col gap-3 border-t border-gray-100 pt-4 sm:flex-row sm:items-center sm:justify-between">
                <p id="pagination-summary" class="text-sm text-gray-500"></p>
                <div id="pagination" class="flex flex-wrap gap-2"></div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (() => {
                const apiUrl = @json(url('/api/products'));
                const detailsUrl = @json(route('vendor.product.details'));
                const editUrl = @json(route('vendor.product.create'));
                const list = document.querySelector('#product-list');
                const empty = document.querySelector('#product-empty');
                const status = document.querySelector('#product-status');
                const pagination = document.querySelector('#pagination');
                const summary = document.querySelector('#pagination-summary');
                const count = document.querySelector('#product-count');
                const search = document.querySelector('#product-search');
                let timer;

                const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (character) => ({
                    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;',
                }[character]));
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
                        button.addEventListener('click', () => loadProducts(page));
                        pagination.append(button);
                    }
                    summary.textContent = meta.total ? `Showing ${meta.from}-${meta.to} of ${meta.total}` : '';
                };
                const render = (products, meta) => {
                    list.innerHTML = products.map((product) => `
                        <tr class="text-gray-700">
                            <td class="px-4 py-4"><p class="font-semibold text-gray-900">${escapeHtml(product.name)}</p><p class="text-xs text-gray-500">ID: ${product.id}</p></td>
                            <td class="px-4 py-4">${escapeHtml(product.category?.name || 'Unknown')}</td>
                            <td class="px-4 py-4">₹${escapeHtml(product.price)}</td>
                            <td class="px-4 py-4">${escapeHtml(product.stock)}</td>
                            <td class="px-4 py-4"><span class="rounded-full px-3 py-1 text-xs font-semibold ${product.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'}">${escapeHtml(product.status)}</span></td>
                            <td class="px-4 py-4"><div class="flex justify-end gap-2">
                                <a href="${detailsUrl}?_id=${product.id}" class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50">Details</a>
                                <a href="${editUrl}?_id=${product.id}" class="rounded-lg border border-[#3d6f6d] px-3 py-1.5 text-xs font-semibold text-[#3d6f6d] hover:bg-[#3d6f6d] hover:text-white">Edit</a>
                            </div></td>
                        </tr>
                    `).join('');
                    empty.classList.toggle('hidden', products.length > 0);
                    count.textContent = `${meta.total} product${meta.total === 1 ? '' : 's'}`;
                    renderPagination(meta);
                };
                const loadProducts = async (page = 1) => {
                    status.classList.add('hidden');
                    list.innerHTML = '<tr><td colspan="6" class="px-4 py-12 text-center text-sm text-gray-500">Loading products...</td></tr>';
                    const params = new URLSearchParams({ page, per_page: 10, my_products: '1' });
                    if (search.value.trim()) params.set('search', search.value.trim());
                    try {
                        const response = await fetch(`${apiUrl}?${params}`, { headers: { Accept: 'application/json' } });
                        const payload = await response.json();
                        if (!response.ok || !payload.success) throw new Error(payload.message || 'Unable to load products.');
                        render(payload.data.data, payload.data);
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
@endsection
