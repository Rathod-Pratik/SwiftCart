@extends('layouts.Dashboard')

@section('content')
    @php($productId = request()->query('_id'))

    <x-slot name="title">Product Details</x-slot>

    <div class="p-4 sm:p-6">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-[#3d6f6d]">Catalog management</p>
                <h1 class="text-2xl font-bold text-gray-900">Product Details</h1>
                <p class="mt-1 text-sm text-gray-600">Review product, vendor, inventory, and catalog information.</p>
            </div>
            <a href="{{ route('admin.product') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">Back to Products</a>
        </div>

        <div id="product-message" class="mb-4 hidden rounded-lg px-4 py-3 text-sm" role="alert"></div>
        <div id="product-loading" class="rounded-xl bg-white p-8 text-center text-sm text-gray-500 shadow-sm">Loading product details...</div>

        <section id="product-details" class="hidden max-w-6xl rounded-xl bg-white p-5 shadow-sm sm:p-8">
            <div class="mb-8 flex flex-col gap-4 border-b border-gray-100 pb-6 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p class="text-sm text-gray-500">Product</p>
                    <h2 id="product-name" class="mt-1 text-2xl font-bold text-gray-900"></h2>
                    <p id="product-description" class="mt-2 max-w-3xl text-sm leading-6 text-gray-600"></p>
                </div>
                <span id="product-status" class="w-fit rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold capitalize text-gray-700"></span>
            </div>

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                <div>
                    <h3 class="mb-4 text-lg font-bold text-gray-900">Catalog information</h3>
                    <dl class="grid gap-4 sm:grid-cols-2">
                        <div class="rounded-lg bg-gray-50 p-4"><dt class="text-xs font-semibold uppercase text-gray-500">Price</dt><dd id="product-price" class="mt-2 text-sm font-semibold text-gray-900"></dd></div>
                        <div class="rounded-lg bg-gray-50 p-4"><dt class="text-xs font-semibold uppercase text-gray-500">Discount</dt><dd id="product-discount" class="mt-2 text-sm text-gray-700"></dd></div>
                        <div class="rounded-lg bg-gray-50 p-4"><dt class="text-xs font-semibold uppercase text-gray-500">Stock</dt><dd id="product-stock" class="mt-2 text-sm text-gray-700"></dd></div>
                        <div class="rounded-lg bg-gray-50 p-4"><dt class="text-xs font-semibold uppercase text-gray-500">Category</dt><dd id="product-category" class="mt-2 text-sm text-gray-700"></dd></div>
                        <div class="rounded-lg bg-gray-50 p-4"><dt class="text-xs font-semibold uppercase text-gray-500">Visibility</dt><dd id="product-visibility" class="mt-2 text-sm capitalize text-gray-700"></dd></div>
                        <div class="rounded-lg bg-gray-50 p-4"><dt class="text-xs font-semibold uppercase text-gray-500">Created</dt><dd id="product-created" class="mt-2 text-sm text-gray-700"></dd></div>
                    </dl>
                </div>
                <div>
                    <h3 class="mb-4 text-lg font-bold text-gray-900">Vendor</h3>
                    <dl class="grid gap-4">
                        <div class="rounded-lg bg-gray-50 p-4"><dt class="text-xs font-semibold uppercase text-gray-500">Name</dt><dd id="vendor-name" class="mt-2 text-sm text-gray-700"></dd></div>
                        <div class="rounded-lg bg-gray-50 p-4"><dt class="text-xs font-semibold uppercase text-gray-500">Email</dt><dd id="vendor-email" class="mt-2 text-sm text-gray-700"></dd></div>
                        <div class="rounded-lg bg-gray-50 p-4"><dt class="text-xs font-semibold uppercase text-gray-500">Features</dt><dd id="product-features" class="mt-2 text-sm text-gray-700"></dd></div>
                        <div class="rounded-lg bg-gray-50 p-4"><dt class="text-xs font-semibold uppercase text-gray-500">Store link</dt><dd id="product-store-link" class="mt-2 break-all text-sm text-gray-700"></dd></div>
                    </dl>
                </div>
            </div>

            <div class="mt-8 border-t border-gray-100 pt-6">
                <h3 class="mb-3 text-lg font-bold text-gray-900">About this product</h3>
                <div id="product-about" class="whitespace-pre-wrap text-sm leading-6 text-gray-700"></div>
            </div>
        </section>
    </div>

    @push('scripts')
        <script>
            (() => {
                const id = @json($productId);
                const apiUrl = @json(url('/api/products'));
                const loading = document.querySelector('#product-loading');
                const details = document.querySelector('#product-details');
                const message = document.querySelector('#product-message');
                const set = (selector, value) => { document.querySelector(selector).textContent = value || 'Not provided'; };
                const money = (value) => `₹${Number(value ?? 0).toFixed(2)}`;
                const date = (value) => value ? new Intl.DateTimeFormat(undefined, { dateStyle: 'medium', timeStyle: 'short' }).format(new Date(value)) : 'Not available';
                const showError = (text) => {
                    message.textContent = text;
                    message.className = 'mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700';
                };
                const loadProduct = async () => {
                    if (!id) { loading.classList.add('hidden'); showError('A product ID is required in the URL, for example: ?_id=1'); return; }
                    try {
                        const response = await fetch(`${apiUrl}/${id}`, { headers: { Accept: 'application/json' } });
                        const payload = await response.json();
                        if (!response.ok || !payload.success) throw new Error(payload.message || 'Unable to load product.');
                        const product = payload.data;
                        set('#product-name', product.name);
                        set('#product-description', product.description);
                        set('#product-status', product.status);
                        set('#product-price', money(product.price));
                        set('#product-discount', `${Number(product.discount ?? 0).toFixed(2)}%`);
                        set('#product-stock', product.stock);
                        set('#product-category', product.category?.name);
                        set('#product-visibility', product.visibility);
                        set('#product-created', date(product.created_at));
                        set('#vendor-name', product.vendor?.name);
                        set('#vendor-email', product.vendor?.email);
                        set('#product-features', product.features);
                        set('#product-store-link', product.store_link);
                        set('#product-about', Array.isArray(product.about) ? product.about.join('\n') : product.about);
                        loading.classList.add('hidden');
                        details.classList.remove('hidden');
                    } catch (error) { loading.classList.add('hidden'); showError(error.message); }
                };
                loadProduct();
            })();
        </script>
    @endpush
@endsection