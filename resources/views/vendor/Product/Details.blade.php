@extends('layouts.Vendor')

@section('content')
    @php($productId = request()->query('_id'))

    <x-slot name="title">Product Details</x-slot>

    <div class="p-4 sm:p-6">
        <nav class="mb-6 flex items-center gap-2 text-xs font-semibold text-gray-400" aria-label="Breadcrumb">
            <a href="{{ route('vendor.dashboard') }}" class="transition hover:text-gray-600">Dashboard</a>
            <span>/</span>
            <a href="{{ route('vendor.product') }}" class="transition hover:text-gray-600">Products</a>
            <span>/</span>
            <span class="text-gray-800">Details</span>
        </nav>
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Product Details</h1>
                <p class="mt-1 text-sm text-gray-600">Review and manage this product.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('vendor.product') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">Back to Products</a>
            </div>
        </div>
        <div id="product-status" class="mb-4 hidden rounded-lg px-4 py-3 text-sm" role="alert"></div>
        <div id="product-loading" class="rounded-xl bg-white min-h-[60vh] flex align-center justify-center p-8 shadow-sm">
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

            {{-- Product Images Gallery --}}
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
              {{--    <div class="rounded-lg bg-gray-50 p-4 sm:col-span-2">
                    <div class="flex items-center justify-between gap-3">
                        <dt class="text-xs font-semibold uppercase text-gray-500">Vendor Details</dt>
                        <a href="{{ route('vendor.profile') }}" class="rounded-lg border border-[#3d6f6d] px-3 py-1.5 text-xs font-semibold text-[#3d6f6d] hover:bg-[#3d6f6d] hover:text-white">
                            View Vendor Details
                        </a>
                    </div>
                    <dd id="vendor-details-content" class="mt-3">
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
                            <div>
                                <p class="text-xs font-semibold uppercase text-gray-500">Phone</p>
                                <p id="vendor-phone" class="mt-1 text-sm text-gray-700"></p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase text-gray-500">Address</p>
                                <p id="vendor-address" class="mt-1 text-sm text-gray-700"></p>
                            </div>
                        </div>
                    </dd>
                </div> --}}
                <div class="rounded-lg bg-gray-50 p-4 sm:col-span-2">
                    <dt class="text-xs font-semibold uppercase text-gray-500">Vendor Services</dt>
                    <dd id="product-services" class="mt-2 flex flex-wrap gap-2 text-sm text-gray-700"></dd>
                </div>

                {{-- Manufacturer Images Gallery --}}
                <div id="details-m-section" class="hidden rounded-lg bg-gray-50 p-4 sm:col-span-2">
                    <dt class="text-xs font-semibold uppercase text-gray-500">Manufacturer & Factory Proof</dt>
                    <dd id="details-m-gallery" class="mt-2 flex flex-wrap gap-3"></dd>
                </div>

                <div class="rounded-lg bg-gray-50 p-4 sm:col-span-2">
                    <dt class="text-xs font-semibold uppercase text-gray-500">About this item</dt>
                    <dd id="product-about" class="mt-2 text-sm text-gray-700"></dd>
                </div>

                <div class="rounded-lg bg-gray-50 p-4 sm:col-span-2">
                    <dt class="text-xs font-semibold uppercase text-gray-500">Features & Specifications</dt>
                    <dd id="product-features" class="mt-2 text-sm text-gray-700"></dd>
                </div>

                <div class="rounded-lg bg-gray-50 p-4 sm:col-span-2">
                    <dt class="text-xs font-semibold uppercase text-gray-500">Detailed Description</dt>
                    <dd id="product-description" class="mt-2 text-sm leading-relaxed text-gray-700"></dd>
                </div>

                <div class="rounded-lg bg-gray-50 p-4"><dt class="text-xs font-semibold uppercase text-gray-500">Created</dt><dd id="product-created" class="mt-2 text-sm text-gray-700"></dd></div>
                <div class="rounded-lg bg-gray-50 p-4"><dt class="text-xs font-semibold uppercase text-gray-500">Last updated</dt><dd id="product-updated" class="mt-2 text-sm text-gray-700"></dd></div>
            </dl> 

            <div class="mt-8 flex flex-wrap justify-end gap-3 border-t border-gray-100 pt-6">
                <a id="bottom-edit-link" href="#" class="pointer-events-none inline-flex items-center justify-center rounded-lg bg-[#3d6f6d] px-4 py-2.5 text-sm font-semibold text-white opacity-50 hover:bg-[#315b59]">Update Product</a>
                <button id="bottom-delete-button" type="button" disabled class="rounded-lg border border-red-200 px-4 py-2.5 text-sm font-semibold text-red-600 opacity-50 hover:bg-red-50 disabled:cursor-not-allowed">Delete</button>
            </div>
        </section>
    </div>
    @push('scripts')
        <script>
            (() => {
                const id = @json($productId);
                const api = @json(url('/api/products'));
                const loading = document.querySelector('#product-loading');
                const details = document.querySelector('#product-details');
                const status = document.querySelector('#product-status');
                const bottomEdit = document.querySelector('#bottom-edit-link');
                const bottomRemove = document.querySelector('#bottom-delete-button');
                const csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? @json(csrf_token());
                const set = (selector, value) => { document.querySelector(selector).textContent = value ?? 'Not provided'; };
                const date = (value) => value ? new Intl.DateTimeFormat(undefined, { dateStyle: 'medium', timeStyle: 'short' }).format(new Date(value)) : 'Not available';
                const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (character) => ({
                    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;',
                }[character]));
                const error = (message) => { window.showToast?.(message, 'error'); };

                const resolveImageUrl = async (keyOrUrl) => {
                    if (!keyOrUrl) return '';
                    if (keyOrUrl.startsWith('http://') || keyOrUrl.startsWith('https://')) return keyOrUrl;
                    try {
                        const res = await fetch(`/api/s3/sign?key=${encodeURIComponent(keyOrUrl)}`, { headers: { Accept: 'application/json' } });
                        if (res.ok) {
                            const data = await res.json();
                            return data.url || keyOrUrl;
                        }
                    } catch {}
                    return keyOrUrl;
                };

                const load = async () => {
                    if (!id) { loading.classList.add('hidden'); error('A product ID is required in the URL, for example: ?_id=1'); return; }
                    try {
                        const response = await fetch(`${api}/${id}`, { headers: { Accept: 'application/json' } });
                        const payload = await response.json();
                        if (!response.ok || !payload.success) throw new Error(payload.message || 'Unable to load product.');
                        const product = payload.data;
                        set('#product-category', product.category?.name);
                        set('#product-name', product.name);
                        set('#product-id', `Product ID: ${product.id}`);
                        set('#product-price', `₹${product.price}`);
                        set('#product-discount', `${product.discount ?? 0}%`);
                        set('#product-stock', product.stock);
                        set('#product-visibility', product.visibility);
                        // set('#vendor-name', product.vendor?.name);
                        // set('#vendor-email', product.vendor?.email);
                        // const vendorProfile = product.vendor?.vendor;
                        // set('#vendor-phone', product.vendor?.phone || vendorProfile?.phone || vendorProfile?.alternate_phone);
                        // set('#vendor-address', product.vendor?.address || vendorProfile?.business_address || vendorProfile?.pickup_address);
                        set('#product-created', date(product.created_at));
                        set('#product-updated', date(product.updated_at));
                        if (product.vendor?.image) {
                            resolveImageUrl(product.vendor.image).then((src) => {
                                if (src) document.querySelector('#vendor-image').src = src;
                            });
                        }

                        // Render Product Images
                        const imgGallery = document.querySelector('#details-images-gallery');
                        const imagesList = Array.isArray(product.image) ? product.image : (product.image ? [product.image] : []);
                        if (imagesList.length) {
                            imgGallery.innerHTML = imagesList.map((k) => `
                                <div class="h-28 w-28 overflow-hidden rounded-xl border border-gray-200 bg-gray-50 shadow-sm">
                                    <img data-key="${escapeHtml(k)}" src="/images/placeholder.png" alt="Product Image" class="h-full w-full object-cover">
                                </div>
                            `).join('');
                            imgGallery.querySelectorAll('img[data-key]').forEach((img) => {
                                resolveImageUrl(img.dataset.key).then((src) => { if (src) img.src = src; });
                            });
                        } else {
                            imgGallery.innerHTML = '<span class="text-sm text-gray-400">No images uploaded.</span>';
                        }

                        // Render Manufacturer Images
                        const mGallery = document.querySelector('#details-m-gallery');
                        const mSection = document.querySelector('#details-m-section');
                        const mList = Array.isArray(product.menifectures_images) ? product.menifectures_images : (product.menifectures_images ? [product.menifectures_images] : []);
                        if (mList.length) {
                            mSection.classList.remove('hidden');
                            mGallery.innerHTML = mList.map((k) => `
                                <div class="h-24 w-24 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                                    <img data-mkey="${escapeHtml(k)}" src="/images/placeholder.png" alt="Manufacturer Image" class="h-full w-full object-cover">
                                </div>
                            `).join('');
                            mGallery.querySelectorAll('img[data-mkey]').forEach((img) => {
                                resolveImageUrl(img.dataset.mkey).then((src) => { if (src) img.src = src; });
                            });
                        }

                        // Render Vendor Services
                        const servicesContainer = document.querySelector('#product-services');
                        if (Array.isArray(product.vendor_service_id) && product.vendor_service_id.length) {
                            servicesContainer.innerHTML = product.vendor_service_id.map((s) => `
                                <span class="inline-flex items-center rounded-md bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 border border-emerald-200">
                                    ✓ ${escapeHtml(s)}
                                </span>
                            `).join('');
                        } else {
                            servicesContainer.innerHTML = '<span class="text-sm text-gray-400">No vendor services listed.</span>';
                        }

                        // Render About Bullet Points
                        const aboutContainer = document.querySelector('#product-about');
                        if (Array.isArray(product.about) && product.about.length) {
                            aboutContainer.innerHTML = `
                                <ul class="list-disc space-y-1.5 pl-5 text-sm text-gray-700">
                                    ${product.about.map((item) => `<li>${escapeHtml(item)}</li>`).join('')}
                                </ul>
                            `;
                        } else {
                            aboutContainer.innerHTML = '<span class="text-sm text-gray-400">No bullet points provided.</span>';
                        }

                        // Render Features (Key-Value)
                        const featuresContainer = document.querySelector('#product-features');
                        let featuresData = product.features;
                        if (typeof featuresData === 'string') {
                            try { featuresData = JSON.parse(featuresData); } catch {}
                        }
                        if (featuresData && typeof featuresData === 'object' && !Array.isArray(featuresData) && Object.keys(featuresData).length) {
                            featuresContainer.innerHTML = `
                                <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
                                    <table class="min-w-full divide-y divide-gray-100 text-sm">
                                        <tbody class="divide-y divide-gray-100">
                                            ${Object.entries(featuresData).map(([k, v]) => `
                                                <tr>
                                                    <td class="w-1/3 bg-gray-50/60 px-4 py-2.5 font-medium text-gray-600">${escapeHtml(k)}</td>
                                                    <td class="px-4 py-2.5 text-gray-900">${escapeHtml(v)}</td>
                                                </tr>
                                            `).join('')}
                                        </tbody>
                                    </table>
                                </div>
                            `;
                        } else if (typeof product.features === 'string' && product.features.trim()) {
                            featuresContainer.innerHTML = `<p class="whitespace-pre-wrap">${escapeHtml(product.features)}</p>`;
                        } else {
                            featuresContainer.innerHTML = '<span class="text-sm text-gray-400">No features listed.</span>';
                        }

                        // Render Description (Multiple Paragraphs)
                        const descContainer = document.querySelector('#product-description');
                        const descText = String(product.description || '');
                        const paragraphs = descText.split(/\n\s*\n/).map((p) => p.trim()).filter(Boolean);
                        if (paragraphs.length) {
                            descContainer.innerHTML = paragraphs.map((p) => `<p class="mb-3 last:mb-0">${escapeHtml(p)}</p>`).join('');
                        } else {
                            descContainer.innerHTML = '<span class="text-sm text-gray-400">No description provided.</span>';
                        }

                        const badge = document.querySelector('#product-status-badge');
                        badge.textContent = product.status;
                        badge.className = `w-fit rounded-full px-3 py-1 text-xs font-semibold ${product.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'}`;
                        bottomEdit.href = `${@json(route('vendor.product.create'))}?_id=${id}`;
                        bottomEdit.classList.remove('pointer-events-none', 'opacity-50');
                        bottomRemove.disabled = false;
                        bottomRemove.classList.remove('opacity-50');
                        loading.classList.add('hidden');
                        details.classList.remove('hidden');
                    } catch (exception) { loading.classList.add('hidden'); error(exception.message); }
                };
                const deleteProduct = async () => {
                    if (!window.confirm('Are you sure you want to delete this product?')) return;
                    bottomRemove.disabled = true;
                    try {
                        const response = await fetch(`${api}/${id}`, { method: 'DELETE', headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrf } });
                        const payload = await response.json();
                        if (!response.ok || !payload.success) throw new Error(payload.message || 'Unable to delete product.');
                        window.location.href = @json(route('vendor.product'));
                    } catch (exception) {
                        error(exception.message);
                        bottomRemove.disabled = false;
                    }
                };
                bottomRemove.addEventListener('click', deleteProduct);
                load();
            })();
        </script>
    @endpush
@endsection
