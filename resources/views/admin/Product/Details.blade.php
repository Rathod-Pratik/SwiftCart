@extends('layouts.Dashboard')

@section('content')
    @php($productId = request()->query('_id'))

    <x-slot name="title">Product Details</x-slot>

    <div class="p-4 sm:p-6">
        <nav class="mb-6 flex items-center gap-2 text-xs font-semibold text-gray-400" aria-label="Breadcrumb">
            <a href="{{ route('admin.dashboard') }}" class="transition hover:text-gray-600">Dashboard</a>
            <span>/</span>
            <a href="{{ route('admin.product') }}" class="transition hover:text-gray-600">Products</a>
            <span>/</span>
            <span class="text-gray-800">Details</span>
        </nav>

        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Product Details</h1>
                <p class="mt-1 text-sm text-gray-600">Review product and vendor information.</p>
            </div>
            <a href="{{ route('admin.product') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">Back to Products</a>
        </div>

        <div id="product-status" class="mb-4 hidden rounded-lg px-4 py-3 text-sm" role="alert"></div>
        <div id="product-loading" class="rounded-xl bg-white p-8 shadow-sm min-h-[60vh] justify-center flex items-center">
            <x-loading />
        </div>

        <section id="product-details" class="hidden w-full bg-white p-5 sm:p-8">
            <div class="mb-8 flex flex-col gap-4 border-b border-gray-100 pb-6 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p id="product-category" class="text-sm font-medium text-[#3d6f6d]"></p>
                    <h2 id="product-name" class="mt-1 text-3xl font-bold text-gray-900"></h2>
                    <p id="product-id" class="mt-2 text-sm text-gray-500"></p>
                </div>
                <span id="product-status-badge" class="w-fit rounded-full px-3 py-1 text-xs font-semibold"></span>
            </div>

            <div class="mb-8">
                <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-500">Product Images</h3>
                <div id="details-images-gallery" class="mt-3 flex flex-wrap gap-3"></div>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div class="rounded-lg bg-gray-50 p-4"><p class="text-xs font-semibold uppercase text-gray-500">Price</p><p id="product-price" class="mt-2 text-lg font-bold text-gray-900"></p></div>
                <div class="rounded-lg bg-gray-50 p-4"><p class="text-xs font-semibold uppercase text-gray-500">Discount</p><p id="product-discount" class="mt-2 text-lg font-bold text-gray-900"></p></div>
                <div class="rounded-lg bg-gray-50 p-4"><p class="text-xs font-semibold uppercase text-gray-500">Stock</p><p id="product-stock" class="mt-2 text-lg font-bold text-gray-900"></p></div>
                <div class="rounded-lg bg-gray-50 p-4"><p class="text-xs font-semibold uppercase text-gray-500">Visibility</p><p id="product-visibility" class="mt-2 text-sm capitalize text-gray-700"></p></div>
            </div>

            <dl class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div class="rounded-lg bg-gray-50 p-4 sm:col-span-2">
                    <div class="flex items-center justify-between gap-3">
                        <dt class="text-xs font-semibold uppercase text-gray-500">Vendor Details</dt>
                        <a id="vendor-details-link" href="#" class="hidden rounded-lg border border-[#3d6f6d] px-3 py-1.5 text-xs font-semibold text-[#3d6f6d] hover:bg-[#3d6f6d] hover:text-white">View Vendor Details</a>
                    </div>
                    <dd class="mt-3">
                        <div class="flex items-center gap-3">
                            <div class="h-12 w-12 shrink-0 overflow-hidden rounded-full bg-gray-200">
                                <img id="vendor-image" src="/images/placeholder.png" alt="Vendor" class="h-full w-full object-cover">
                            </div>
                            <div>
                                <p id="vendor-name" class="font-semibold text-gray-900"></p>
                                <p id="vendor-email" class="text-sm text-gray-500"></p>
                            </div>
                        </div>
                        <div class="mt-4 grid gap-3 sm:grid-cols-2">
                            <div><p class="text-xs font-semibold uppercase text-gray-500">Phone</p><p id="vendor-phone" class="mt-1 text-sm text-gray-700"></p></div>
                            <div><p class="text-xs font-semibold uppercase text-gray-500">Address</p><p id="vendor-address" class="mt-1 text-sm text-gray-700"></p></div>
                        </div>
                    </dd>
                </div>
                <div class="rounded-lg bg-gray-50 p-4 sm:col-span-2">
                    <dt class="text-xs font-semibold uppercase text-gray-500">Vendor Services</dt>
                    <dd id="product-services" class="mt-2 flex flex-wrap gap-2 text-sm text-gray-700"></dd>
                </div>
                <div id="details-m-section" class="hidden rounded-lg bg-gray-50 p-4 sm:col-span-2">
                    <dt class="text-xs font-semibold uppercase text-gray-500">Manufacturer & Factory Proof</dt>
                    <dd id="details-m-gallery" class="mt-2 flex flex-wrap gap-3"></dd>
                </div>
                <div class="rounded-lg bg-gray-50 p-4 sm:col-span-2"><dt class="text-xs font-semibold uppercase text-gray-500">About this item</dt><dd id="product-about" class="mt-2 text-sm text-gray-700"></dd></div>
                <div class="rounded-lg bg-gray-50 p-4 sm:col-span-2"><dt class="text-xs font-semibold uppercase text-gray-500">Features & Specifications</dt><dd id="product-features" class="mt-2 text-sm text-gray-700"></dd></div>
                <div class="rounded-lg bg-gray-50 p-4 sm:col-span-2"><dt class="text-xs font-semibold uppercase text-gray-500">Detailed Description</dt><dd id="product-description" class="mt-2 text-sm leading-relaxed text-gray-700"></dd></div>
                <div class="rounded-lg bg-gray-50 p-4"><dt class="text-xs font-semibold uppercase text-gray-500">Created</dt><dd id="product-created" class="mt-2 text-sm text-gray-700"></dd></div>
                <div class="rounded-lg bg-gray-50 p-4"><dt class="text-xs font-semibold uppercase text-gray-500">Last updated</dt><dd id="product-updated" class="mt-2 text-sm text-gray-700"></dd></div>
            </dl>

            <div class="mt-8 flex flex-wrap justify-end gap-2 border-t border-gray-100 pt-6">
                <label class="inline-flex cursor-pointer items-center gap-2 rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:border-[#3d6f6d] hover:bg-white">
                    <input type="checkbox" data-flag="is_featured" class="h-4 w-4 rounded border-gray-300 text-[#224e51] focus:ring-[#224e51]">
                    <x-lucide-star class="h-4 w-4" />
                    <span>Featured</span>
                </label>
                <label class="inline-flex cursor-pointer items-center gap-2 rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:border-[#3d6f6d] hover:bg-white">
                    <input type="checkbox" data-flag="is_trending" class="h-4 w-4 rounded border-gray-300 text-[#224e51] focus:ring-[#224e51]">
                    <x-lucide-trending-up class="h-4 w-4" />
                    <span>Trending</span>
                </label>
                <label class="inline-flex cursor-pointer items-center gap-2 rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:border-[#3d6f6d] hover:bg-white">
                    <input type="checkbox" data-flag="is_limited" class="h-4 w-4 rounded border-gray-300 text-[#224e51] focus:ring-[#224e51]">
                    <x-lucide-tag class="h-4 w-4" />
                    <span>Limited</span>
                </label>
                <x-Button type="button" id="remove-product" class="!bg-rose-600 hover:!bg-rose-700 !px-6 !py-2.5 shadow-sm">
                    <x-lucide-trash-2 class="mr-2 h-4 w-4" />
                    <span>Remove</span>
                </x-Button>
            </div>

            <section class="mt-8 border-t border-gray-100 pt-6">
                <div class="mb-5 flex items-center justify-between gap-3">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Reviews</h3>
                        <p id="reviews-count" class="mt-1 text-sm text-gray-500"></p>
                    </div>
                </div>
                <div id="reviews-status" class="hidden rounded-lg px-4 py-3 text-sm" role="status"></div>
                <div id="reviews-content" class="grid gap-5 lg:grid-cols-[minmax(0,1fr)_280px]">
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                        <div class="grid gap-5 sm:grid-cols-[150px_minmax(0,1fr)]">
                            <div class="text-center sm:border-r sm:border-gray-100 sm:pr-5">
                                <p id="reviews-average" class="text-4xl font-bold text-gray-900">—</p>
                                <div id="reviews-stars" class="mt-1 flex justify-center gap-0.5 text-[#224e51]" aria-label="Average rating"></div>
                                <p class="mt-1 text-xs text-gray-500">Average rating</p>
                            </div>
                            <div id="reviews-distribution" class="space-y-2"></div>
                        </div>
                    </div>
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Rating summary</p>
                        <p id="reviews-summary" class="mt-3 text-sm leading-relaxed text-gray-600"></p>
                    </div>
                </div>
                <div id="reviews-list" class="mt-5 divide-y divide-gray-100 rounded-xl border border-gray-200 bg-white shadow-sm"></div>
            </section>
        </section>
    </div>

    <div id="remove-product-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-900/60 p-4 opacity-0 transition-opacity duration-300 ease-out" role="dialog" aria-modal="true" aria-labelledby="remove-product-title">
        <div id="remove-product-panel" class="w-full max-w-lg translate-y-4 scale-95 rounded-2xl bg-white p-6 opacity-0 shadow-xl transition duration-300 ease-out">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 id="remove-product-title" class="text-lg font-bold text-gray-900">Remove Product</h2>
                    <p class="mt-1 text-sm text-gray-500">The vendor will receive this reason and description by notification and email.</p>
                </div>
                <x-Button type="button" id="close-remove-modal" class="!bg-transparent !px-2 !py-1 text-2xl leading-none !text-gray-400 hover:!bg-gray-100 hover:!text-gray-700" aria-label="Close">&times;</x-Button>
            </div>
            <div id="remove-product-error" class="mt-4 hidden rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700"></div>
            <form id="remove-product-form" class="mt-5 space-y-4">
                <div>
                    <label for="remove-reason" class="mb-1 block text-sm font-semibold text-gray-700">Reason</label>
                    <input id="remove-reason" name="reason" required maxlength="255" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                </div>
                <div>
                    <label for="remove-description" class="mb-1 block text-sm font-semibold text-gray-700">Description</label>
                    <textarea id="remove-description" name="description" required maxlength="2000" rows="5" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20"></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <x-Button type="button" id="cancel-remove-modal" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">Cancel</x-Button>
                    <x-Button type="submit" id="confirm-remove-product" class="rounded-lg bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-rose-700">Remove Product</x-Button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            (() => {
                const id = @json($productId);
                const api = @json(url('/api/products'));
                const adminApi = @json(url('/api/admin/products'));
                const csrfToken = @json(csrf_token());
                const loading = document.querySelector('#product-loading');
                const details = document.querySelector('#product-details');
                const status = document.querySelector('#product-status');
                const removeModal = document.querySelector('#remove-product-modal');
                const removeForm = document.querySelector('#remove-product-form');
                const removeError = document.querySelector('#remove-product-error');
                let product;
                const vendorDetailsUrl = @json(route('admin.vendor.details'));
                const set = (selector, value) => { document.querySelector(selector).textContent = value ?? 'Not provided'; };
                const date = (value) => value ? new Intl.DateTimeFormat(undefined, { dateStyle: 'medium', timeStyle: 'short' }).format(new Date(value)) : 'Not available';
                const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (character) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[character]));
                const error = (message) => { status.textContent = message; status.className = 'mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700'; };
                const setFlagCheckbox = (field) => {
                    const checkbox = document.querySelector(`[data-flag="${field}"]`);
                    checkbox.checked = Boolean(product[field]);
                    checkbox.disabled = false;
                };
                const updateFlag = async (field, value, checkbox) => {
                    checkbox.disabled = true;
                    try {
                        const response = await fetch(`${adminApi}/${id}/flags`, {
                            method: 'PATCH',
                            headers: { Accept: 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                            body: JSON.stringify({ [field]: value }),
                        });
                        const payload = await response.json();
                        if (!response.ok || !payload.success) throw new Error(payload.message || 'Unable to update product.');
                        product[field] = Boolean(payload.data[field]);
                        setFlagCheckbox(field);
                    } catch (exception) {
                        checkbox.checked = Boolean(product[field]);
                        checkbox.disabled = false;
                        error(exception.message);
                    }
                };
                const renderStars = (rating) => {
                    const rounded = Math.round(Number(rating) || 0);
                    const star = (filled) => `
                        <svg class="h-4 w-4 ${filled ? 'fill-[#224e51] text-[#224e51]' : 'text-gray-300'}" viewBox="0 0 24 24" fill="${filled ? 'currentColor' : 'none'}" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m12 3 2.78 5.63 6.22.9-4.5 4.38 1.06 6.2L12 17.18l-5.56 2.93 1.06-6.2L3 9.53l6.22-.9L12 3Z" />
                        </svg>`;
                    return Array.from({ length: 5 }, (_, index) => star(index < rounded)).join('');
                };
                const loadReviews = async () => {
                    const reviewsStatus = document.querySelector('#reviews-status');
                    try {
                        const response = await fetch(`/api/reviews?product_id=${encodeURIComponent(id)}&per_page=100`, {
                            headers: { Accept: 'application/json' },
                        });
                        const payload = await response.json();
                        const reviews = payload.success ? (payload.data?.data ?? []) : [];
                        const total = payload.success ? (payload.data?.total ?? reviews.length) : 0;
                        const distribution = [5, 4, 3, 2, 1].map((rating) => ({
                            rating,
                            count: reviews.filter((review) => Number(review.rating) === rating).length,
                        }));
                        const average = reviews.length
                            ? reviews.reduce((sum, review) => sum + Number(review.rating || 0), 0) / reviews.length
                            : 0;
                        const maxCount = Math.max(...distribution.map((item) => item.count), 1);

                        document.querySelector('#reviews-count').textContent = `${total} review${total === 1 ? '' : 's'}`;
                        document.querySelector('#reviews-average').textContent = average ? average.toFixed(1) : '—';
                        document.querySelector('#reviews-stars').innerHTML = renderStars(average);
                        document.querySelector('#reviews-summary').textContent = reviews.length
                            ? `${average.toFixed(1)} average rating from ${total} customer review${total === 1 ? '' : 's'}.`
                            : 'No customer reviews have been submitted for this product.';
                        document.querySelector('#reviews-distribution').innerHTML = distribution.map((item) => `
                            <div class="flex items-center gap-2 text-xs text-[#224e51]">
                                <span class="w-10">${item.rating} Star</span>
                                <div class="h-2 flex-1 overflow-hidden rounded-full bg-gray-200">
                                    <div class="h-full rounded-full bg-[#224e51]" style="width: ${(item.count / maxCount) * 100}%"></div>
                                </div>
                                <span class="w-12 text-right">${item.count}</span>
                            </div>
                        `).join('');
                        document.querySelector('#reviews-list').innerHTML = reviews.length ? reviews.map((review) => `
                            <article class="p-5">
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                                    <div>
                                        <p class="font-semibold text-gray-900">${escapeHtml(review.user?.name || 'Customer')}</p>
                                        <p class="text-xs text-gray-500">${date(review.created_at)}</p>
                                    </div>
                                    <span class="flex items-center gap-1 font-semibold text-[#224e51]">${renderStars(review.rating)} <span class="text-xs text-gray-500">${Number(review.rating).toFixed(1)}</span></span>
                                </div>
                                <p class="mt-3 text-sm leading-relaxed text-gray-600">${escapeHtml(review.comment || 'No comment provided.')}</p>
                            </article>
                        `).join('') : '<p class="p-6 text-center text-sm text-gray-500">No reviews found.</p>';
                        reviewsStatus.classList.add('hidden');
                    } catch (exception) {
                        reviewsStatus.textContent = 'Unable to load reviews.';
                        reviewsStatus.className = 'rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700';
                    }
                };
                const closeRemoveModal = () => {
                    const panel = document.querySelector('#remove-product-panel');
                    panel.classList.add('translate-y-4', 'scale-95', 'opacity-0');
                    removeModal.classList.add('opacity-0');
                    window.setTimeout(() => {
                        removeModal.classList.add('hidden');
                        removeModal.classList.remove('flex');
                    }, 300);
                    removeError.classList.add('hidden');
                };
                const openRemoveModal = () => {
                    const panel = document.querySelector('#remove-product-panel');
                    removeForm.reset();
                    removeError.classList.add('hidden');
                    removeModal.classList.remove('hidden');
                    removeModal.classList.add('flex');
                    window.requestAnimationFrame(() => {
                        removeModal.classList.remove('opacity-0');
                        panel.classList.remove('translate-y-4', 'scale-95', 'opacity-0');
                        document.querySelector('#remove-reason').focus();
                    });
                };
                const removeProduct = async (event) => {
                    event.preventDefault();
                    const submit = document.querySelector('#confirm-remove-product');
                    submit.disabled = true;
                    removeError.classList.add('hidden');
                    try {
                        const response = await fetch(`${adminApi}/${id}`, {
                            method: 'DELETE',
                            headers: { Accept: 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                            body: JSON.stringify({
                                reason: document.querySelector('#remove-reason').value.trim(),
                                description: document.querySelector('#remove-description').value.trim(),
                            }),
                        });
                        const payload = await response.json();
                        if (!response.ok || !payload.success) {
                            const validationMessage = payload.errors
                                ? Object.values(payload.errors).flat()[0]
                                : payload.message;
                            throw new Error(validationMessage || 'Unable to remove product.');
                        }
                        window.location.href = @json(route('admin.product'));
                    } catch (exception) {
                        removeError.textContent = exception.message;
                        removeError.classList.remove('hidden');
                        submit.disabled = false;
                    }
                };
                document.querySelectorAll('[data-flag]').forEach((checkbox) => {
                    checkbox.addEventListener('change', () => updateFlag(checkbox.dataset.flag, checkbox.checked, checkbox));
                });
                document.querySelector('#remove-product').addEventListener('click', openRemoveModal);
                document.querySelector('#close-remove-modal').addEventListener('click', closeRemoveModal);
                document.querySelector('#cancel-remove-modal').addEventListener('click', closeRemoveModal);
                removeForm.addEventListener('submit', removeProduct);
                const resolveImageUrl = async (keyOrUrl) => {
                    if (!keyOrUrl || keyOrUrl.startsWith('http://') || keyOrUrl.startsWith('https://')) return keyOrUrl || '';
                    try {
                        const response = await fetch(`/api/s3/sign?key=${encodeURIComponent(keyOrUrl)}`, { headers: { Accept: 'application/json' } });
                        if (response.ok) return (await response.json()).url || keyOrUrl;
                    } catch {}
                    return keyOrUrl;
                };
                const renderGallery = (container, values, dataKey, alt) => {
                    const list = Array.isArray(values) ? values : (values ? [values] : []);
                    if (!list.length) { container.innerHTML = '<span class="text-sm text-gray-400">No images uploaded.</span>'; return; }
                    container.innerHTML = list.map((key) => `<div class="h-28 w-28 overflow-hidden rounded-xl border border-gray-200 bg-gray-50 shadow-sm"><img data-key="${escapeHtml(key)}" src="/images/placeholder.png" alt="${alt}" class="h-full w-full object-cover"></div>`).join('');
                    container.querySelectorAll(`img[data-key]`).forEach((image) => resolveImageUrl(image.dataset.key).then((src) => { if (src) image.src = src; }));
                };
                const load = async () => {
                    if (!id) { loading.classList.add('hidden'); error('A product ID is required in the URL, for example: ?_id=1'); return; }
                    try {
                        const response = await fetch(`${api}/${id}`, { headers: { Accept: 'application/json' } });
                        const payload = await response.json();
                        if (!response.ok || !payload.success) throw new Error(payload.message || 'Unable to load product.');
                        product = payload.data;
                        const vendorProfile = product.vendor?.vendor;
                        set('#product-category', product.category?.name);
                        set('#product-name', product.name);
                        set('#product-id', `Product ID: ${product.id}`);
                        set('#product-price', `₹${product.price}`);
                        set('#product-discount', `${product.discount ?? 0}%`);
                        set('#product-stock', product.stock);
                        set('#product-visibility', product.visibility);
                        set('#vendor-name', product.vendor?.name);
                        set('#vendor-email', product.vendor?.email);
                        set('#vendor-phone', product.vendor?.phone || vendorProfile?.phone || vendorProfile?.alternate_phone);
                        set('#vendor-address', product.vendor?.address || vendorProfile?.business_address || vendorProfile?.pickup_address);
                        set('#product-created', date(product.created_at));
                        set('#product-updated', date(product.updated_at));
                        if (product.vendor?.id) {
                            const link = document.querySelector('#vendor-details-link');
                            link.href = `${vendorDetailsUrl}?_id=${encodeURIComponent(product.vendor.id)}`;
                            link.classList.remove('hidden');
                        }
                        if (product.vendor?.image) resolveImageUrl(product.vendor.image).then((src) => { if (src) document.querySelector('#vendor-image').src = src; });
                        renderGallery(document.querySelector('#details-images-gallery'), product.image, 'key', 'Product Image');
                        const manufacturerSection = document.querySelector('#details-m-section');
                        const manufacturerGallery = document.querySelector('#details-m-gallery');
                        const manufacturerList = Array.isArray(product.menifectures_images) ? product.menifectures_images : (product.menifectures_images ? [product.menifectures_images] : []);
                        if (manufacturerList.length) { manufacturerSection.classList.remove('hidden'); renderGallery(manufacturerGallery, manufacturerList, 'key', 'Manufacturer Image'); }
                        const services = document.querySelector('#product-services');
                        services.innerHTML = Array.isArray(product.vendor_service_id) && product.vendor_service_id.length
                            ? product.vendor_service_id.map((service) => `<span class="inline-flex items-center rounded-md border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">✓ ${escapeHtml(service)}</span>`).join('')
                            : '<span class="text-sm text-gray-400">No vendor services listed.</span>';
                        const about = document.querySelector('#product-about');
                        about.innerHTML = Array.isArray(product.about) && product.about.length ? `<ul class="list-disc space-y-1.5 pl-5 text-sm text-gray-700">${product.about.map((item) => `<li>${escapeHtml(item)}</li>`).join('')}</ul>` : '<span class="text-sm text-gray-400">No bullet points provided.</span>';
                        let features = product.features;
                        if (typeof features === 'string') { try { features = JSON.parse(features); } catch {} }
                        document.querySelector('#product-features').innerHTML = features && typeof features === 'object' && !Array.isArray(features)
                            ? `<div class="overflow-hidden rounded-lg border border-gray-200 bg-white"><table class="min-w-full divide-y divide-gray-100 text-sm"><tbody class="divide-y divide-gray-100">${Object.entries(features).map(([key, value]) => `<tr><td class="w-1/3 bg-gray-50/60 px-4 py-2 font-medium text-gray-600">${escapeHtml(key)}</td><td class="px-4 py-2 text-gray-700">${escapeHtml(value)}</td></tr>`).join('')}</tbody></table></div>`
                            : '<span class="text-sm text-gray-400">No specifications provided.</span>';
                        const description = String(product.description || '');
                        document.querySelector('#product-description').innerHTML = description ? description.split(/\n\s*\n/).map((paragraph) => `<p class="mb-3 last:mb-0">${escapeHtml(paragraph.trim())}</p>`).join('') : '<span class="text-sm text-gray-400">No description provided.</span>';
                        const badge = document.querySelector('#product-status-badge');
                        badge.textContent = product.status;
                        badge.className = `w-fit rounded-full px-3 py-1 text-xs font-semibold ${product.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'}`;
                        setFlagCheckbox('is_featured');
                        setFlagCheckbox('is_trending');
                        setFlagCheckbox('is_limited');
                        loadReviews();
                        loading.classList.add('hidden');
                        details.classList.remove('hidden');
                    } catch (exception) { loading.classList.add('hidden'); error(exception.message); }
                };
                load();
            })();
        </script>
    @endpush
@endsection
