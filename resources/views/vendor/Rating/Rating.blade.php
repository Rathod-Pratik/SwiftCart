@extends('layouts.Vendor')

@section('content')
    <x-slot name="title">Ratings</x-slot>

    <div class="p-4 sm:p-6">
        <div class="mb-6">
            <p class="text-sm font-medium text-[#3d6f6d]">Product feedback</p>
            <h1 class="text-2xl font-bold text-gray-900">Ratings</h1>
            <p class="mt-1 text-sm text-gray-600">Review customer ratings for your products.</p>
        </div>

        <div class="rounded-xl bg-white p-4 shadow-sm sm:p-6">
            <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <label class="block w-full sm:max-w-sm" for="rating-search">
                    <span class="sr-only">Search products</span>
                    <input id="rating-search" type="search" placeholder="Search products..." class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                </label>
                <p id="rating-count" class="text-sm text-gray-500"></p>
            </div>
            <div id="rating-status" class="mb-4 hidden rounded-lg px-4 py-3 text-sm" role="alert"></div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[780px] text-left text-sm">
                    <thead class="border-b border-gray-200 text-xs uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Product</th>
                            <th class="px-4 py-3 font-semibold">Price</th>
                            <th class="px-4 py-3 font-semibold">Stock</th>
                            <th class="px-4 py-3 font-semibold">Average rating</th>
                            <th class="px-4 py-3 font-semibold">Reviews</th>
                            <th class="px-4 py-3 text-right font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="rating-list" class="divide-y divide-gray-100"></tbody>
                </table>
            </div>
            <div id="rating-empty" class="hidden py-12 text-center text-sm text-gray-500">No products found.</div>
            <div class="mt-5 flex flex-col gap-3 border-t border-gray-100 pt-4 sm:flex-row sm:items-center sm:justify-between">
                <p id="pagination-summary" class="text-sm text-gray-500"></p>
                <div id="pagination" class="flex flex-wrap gap-2"></div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (() => {
                const apiUrl = @json(url('/api/vendor/ratings/products'));
                const detailsUrl = @json(route('vendor.rating.details'));
                const list = document.querySelector('#rating-list');
                const empty = document.querySelector('#rating-empty');
                const status = document.querySelector('#rating-status');
                const search = document.querySelector('#rating-search');
                const count = document.querySelector('#rating-count');
                const pagination = document.querySelector('#pagination');
                const summary = document.querySelector('#pagination-summary');
                let timer;

                const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (character) => ({
                    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;',
                }[character]));
                const money = (value) => `₹${Number(value ?? 0).toFixed(2)}`;
                const stars = (value) => {
                    const rating = Number(value ?? 0);
                    return `<span class="font-semibold text-amber-500">${'★'.repeat(Math.round(rating))}${'☆'.repeat(5 - Math.round(rating))}</span><span class="ml-2 text-gray-600">${rating ? rating.toFixed(1) : 'No rating'}</span>`;
                };
                const showError = (text) => {
                    status.textContent = text;
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
                const renderProducts = (products, meta) => {
                    list.innerHTML = products.map((product) => `
                        <tr class="text-gray-700">
                            <td class="px-4 py-4"><p class="font-semibold text-gray-900">${escapeHtml(product.name)}</p><p class="text-xs capitalize text-gray-500">${escapeHtml(product.status)}</p></td>
                            <td class="px-4 py-4 font-medium">${money(product.price)}</td>
                            <td class="px-4 py-4">${escapeHtml(product.stock)}</td>
                            <td class="px-4 py-4">${stars(product.reviews_avg_rating)}</td>
                            <td class="px-4 py-4">${escapeHtml(product.reviews_count)}</td>
                            <td class="px-4 py-4 text-right"><a href="${detailsUrl}?_id=${product.id}" class="inline-flex rounded-lg border border-[#3d6f6d] px-3 py-1.5 text-xs font-semibold text-[#3d6f6d] hover:bg-[#3d6f6d] hover:text-white">View ratings</a></td>
                        </tr>
                    `).join('');
                    empty.classList.toggle('hidden', products.length > 0);
                    count.textContent = `${meta.total} product${meta.total === 1 ? '' : 's'}`;
                    renderPagination(meta);
                };
                const loadProducts = async (page = 1) => {
                    status.classList.add('hidden');
                    list.innerHTML = '<tr><td colspan="6" class="px-4 py-12 text-center text-sm text-gray-500">Loading products...</td></tr>';
                    const params = new URLSearchParams({ page, per_page: 10 });
                    if (search.value.trim()) params.set('search', search.value.trim());
                    try {
                        const response = await fetch(`${apiUrl}?${params}`, { headers: { Accept: 'application/json' } });
                        const payload = await response.json();
                        if (!response.ok || !payload.success) throw new Error(payload.message || 'Unable to load products.');
                        renderProducts(payload.data.data, payload.data);
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