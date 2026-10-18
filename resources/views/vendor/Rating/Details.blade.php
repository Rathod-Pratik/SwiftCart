@extends('layouts.Vendor')

@section('content')
    @php($productId = request()->query('_id'))

    <x-slot name="title">Product Ratings</x-slot>

    <div class="p-4 sm:p-6">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-[#3d6f6d]">Product feedback</p>
                <h1 class="text-2xl font-bold text-gray-900">Product Ratings</h1>
                <p class="mt-1 text-sm text-gray-600">View product information and customer reviews.</p>
            </div>
            <a href="{{ route('vendor.rating') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">Back to Ratings</a>
        </div>

        <div id="rating-message" class="mb-4 hidden rounded-lg px-4 py-3 text-sm" role="alert"></div>
        <div id="rating-loading" class="rounded-xl bg-white p-8 text-center text-sm text-gray-500 shadow-sm">Loading product ratings...</div>

        <div id="rating-details" class="hidden grid max-w-6xl grid-cols-1 gap-6 lg:grid-cols-[minmax(0,1fr)_20rem]">
            <section class="rounded-xl bg-white p-5 shadow-sm sm:p-8">
                <div class="mb-6 flex flex-col gap-4 border-b border-gray-100 pb-6 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Product</p>
                        <h2 id="product-name" class="mt-1 text-2xl font-bold text-gray-900"></h2>
                        <p id="product-description" class="mt-2 max-w-2xl text-sm leading-6 text-gray-600"></p>
                    </div>
                    <div class="rounded-lg bg-[#e6f7f5] px-4 py-3 text-center">
                        <p id="average-rating" class="text-2xl font-bold text-[#2f7770]"></p>
                        <p class="text-xs text-[#2f7770]">Average rating</p>
                    </div>
                </div>

                <div class="mb-5 flex items-center justify-between gap-4">
                    <h3 class="text-lg font-bold text-gray-900">Customer reviews</h3>
                    <p id="review-count" class="text-sm text-gray-500"></p>
                </div>
                <div id="review-list" class="grid gap-4"></div>
                <div id="review-empty" class="hidden rounded-lg bg-gray-50 py-12 text-center text-sm text-gray-500">No ratings have been submitted yet.</div>
                <div class="mt-5 flex flex-col gap-3 border-t border-gray-100 pt-4 sm:flex-row sm:items-center sm:justify-between">
                    <p id="pagination-summary" class="text-sm text-gray-500"></p>
                    <div id="pagination" class="flex flex-wrap gap-2"></div>
                </div>
            </section>

            <aside class="h-fit rounded-xl bg-white p-5 shadow-sm">
                <h2 class="mb-4 text-lg font-bold text-gray-900">Product details</h2>
                <dl class="grid gap-4 text-sm">
                    <div><dt class="text-xs font-semibold uppercase text-gray-500">Price</dt><dd id="product-price" class="mt-1 text-gray-700"></dd></div>
                    <div><dt class="text-xs font-semibold uppercase text-gray-500">Discount</dt><dd id="product-discount" class="mt-1 text-gray-700"></dd></div>
                    <div><dt class="text-xs font-semibold uppercase text-gray-500">Stock</dt><dd id="product-stock" class="mt-1 text-gray-700"></dd></div>
                    <div><dt class="text-xs font-semibold uppercase text-gray-500">Status</dt><dd id="product-status" class="mt-1 capitalize text-gray-700"></dd></div>
                    <div><dt class="text-xs font-semibold uppercase text-gray-500">Visibility</dt><dd id="product-visibility" class="mt-1 capitalize text-gray-700"></dd></div>
                </dl>
            </aside>
        </div>
    </div>

    @push('scripts')
        <script>
            (() => {
                const id = @json($productId);
                const apiUrl = @json(url('/api/vendor/ratings/products'));
                const loading = document.querySelector('#rating-loading');
                const details = document.querySelector('#rating-details');
                const message = document.querySelector('#rating-message');
                const list = document.querySelector('#review-list');
                const empty = document.querySelector('#review-empty');
                const pagination = document.querySelector('#pagination');
                const summary = document.querySelector('#pagination-summary');

                const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (character) => ({
                    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;',
                }[character]));
                const money = (value) => `₹${Number(value ?? 0).toFixed(2)}`;
                const date = (value) => value ? new Intl.DateTimeFormat(undefined, { dateStyle: 'medium' }).format(new Date(value)) : 'Unknown date';
                const stars = (value) => {
                    const rating = Number(value ?? 0);
                    return `<span class="text-amber-500">${'★'.repeat(rating)}${'☆'.repeat(5 - rating)}</span>`;
                };
                const set = (selector, value) => { document.querySelector(selector).textContent = value ?? 'Not provided'; };
                const showError = (text) => {
                    message.textContent = text;
                    message.className = 'mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700';
                };
                const renderPagination = (meta) => {
                    pagination.innerHTML = '';
                    for (let page = 1; page <= meta.last_page; page += 1) {
                        const button = document.createElement('button');
                        button.type = 'button';
                        button.textContent = page;
                        button.disabled = page === meta.current_page;
                        button.className = `rounded-lg px-3 py-2 text-sm font-semibold ${button.disabled ? 'bg-[#3d6f6d] text-white' : 'border border-gray-300 text-gray-700 hover:bg-gray-50'}`;
                        button.addEventListener('click', () => loadDetails(page));
                        pagination.append(button);
                    }
                    summary.textContent = meta.total ? `Showing ${meta.from}-${meta.to} of ${meta.total}` : '';
                };
                const renderReviews = (reviews, meta) => {
                    list.innerHTML = reviews.map((review) => `
                        <article class="rounded-lg border border-gray-100 p-4">
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                <div><p class="font-semibold text-gray-900">${escapeHtml(review.user?.name || 'Customer')}</p><p class="text-xs text-gray-500">${escapeHtml(review.user?.email || '')}</p></div>
                                <div class="text-sm">${stars(review.rating)} <span class="ml-2 text-gray-500">${escapeHtml(date(review.created_at))}</span></div>
                            </div>
                            <p class="mt-3 whitespace-pre-wrap text-sm leading-6 text-gray-700">${escapeHtml(review.comment || 'No comment provided.')}</p>
                        </article>
                    `).join('');
                    empty.classList.toggle('hidden', reviews.length > 0);
                    document.querySelector('#review-count').textContent = `${meta.total} review${meta.total === 1 ? '' : 's'}`;
                    renderPagination(meta);
                };
                const loadDetails = async (page = 1) => {
                    if (!id) { loading.classList.add('hidden'); showError('A product ID is required in the URL, for example: ?_id=1'); return; }
                    try {
                        const response = await fetch(`${apiUrl}/${id}?page=${page}&per_page=10`, { headers: { Accept: 'application/json' } });
                        const payload = await response.json();
                        if (!response.ok || !payload.success) throw new Error(payload.message || 'Unable to load product ratings.');
                        const data = payload.data;
                        const product = data.product;
                        set('#product-name', product.name);
                        set('#product-description', product.description || 'No description provided.');
                        set('#average-rating', product.reviews_avg_rating ? `${Number(product.reviews_avg_rating).toFixed(1)} / 5` : 'No rating');
                        set('#product-price', money(product.price));
                        set('#product-discount', `${Number(product.discount ?? 0).toFixed(2)}%`);
                        set('#product-stock', product.stock);
                        set('#product-status', product.status);
                        set('#product-visibility', product.visibility);
                        renderReviews(data.reviews.data, data.reviews);
                        loading.classList.add('hidden');
                        details.classList.remove('hidden');
                    } catch (error) { loading.classList.add('hidden'); showError(error.message); }
                };
                loadDetails();
            })();
        </script>
    @endpush
@endsection