@extends('layouts.Vendor')

@section('content')
    @php($productId = request()->query('_id'))

    <x-slot name="title">Product Details</x-slot>

    <div class="p-4 sm:p-6">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-[#3d6f6d]">Product management</p>
                <h1 class="text-2xl font-bold text-gray-900">Product Details</h1>
                <p class="mt-1 text-sm text-gray-600">Review and manage this product.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('vendor.product') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">Back to Products</a>
                <a id="edit-link" href="#" class="pointer-events-none inline-flex items-center justify-center rounded-lg bg-[#3d6f6d] px-4 py-2.5 text-sm font-semibold text-white opacity-50 hover:bg-[#315b59]">Update Product</a>
                <button id="delete-button" type="button" disabled class="rounded-lg border border-red-200 px-4 py-2.5 text-sm font-semibold text-red-600 opacity-50 hover:bg-red-50 disabled:cursor-not-allowed">Delete</button>
            </div>
        </div>
        <div id="product-status" class="mb-4 hidden rounded-lg px-4 py-3 text-sm" role="alert"></div>
        <div id="product-loading" class="rounded-xl bg-white p-8 text-center text-sm text-gray-500 shadow-sm">Loading product details...</div>
        <section id="product-details" class="hidden max-w-5xl rounded-xl bg-white p-5 shadow-sm sm:p-8">
            <div class="mb-8 flex flex-col gap-4 border-b border-gray-100 pb-6 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p id="product-category" class="text-sm font-medium text-[#3d6f6d]"></p>
                    <h2 id="product-name" class="mt-1 text-3xl font-bold text-gray-900"></h2>
                    <p id="product-id" class="mt-2 text-sm text-gray-500"></p>
                </div>
                <span id="product-status-badge" class="w-fit rounded-full px-3 py-1 text-xs font-semibold"></span>
            </div>
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-lg bg-gray-50 p-4"><p class="text-xs font-semibold uppercase text-gray-500">Price</p><p id="product-price" class="mt-2 text-lg font-bold text-gray-900"></p></div>
                <div class="rounded-lg bg-gray-50 p-4"><p class="text-xs font-semibold uppercase text-gray-500">Discount</p><p id="product-discount" class="mt-2 text-lg font-bold text-gray-900"></p></div>
                <div class="rounded-lg bg-gray-50 p-4"><p class="text-xs font-semibold uppercase text-gray-500">Stock</p><p id="product-stock" class="mt-2 text-lg font-bold text-gray-900"></p></div>
                <div class="rounded-lg bg-gray-50 p-4"><p class="text-xs font-semibold uppercase text-gray-500">Visibility</p><p id="product-visibility" class="mt-2 text-sm capitalize text-gray-700"></p></div>
            </div>
            <dl class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div class="rounded-lg bg-gray-50 p-4 sm:col-span-2"><dt class="text-xs font-semibold uppercase text-gray-500">Description</dt><dd id="product-description" class="mt-2 whitespace-pre-wrap text-sm leading-6 text-gray-700"></dd></div>
                <div class="rounded-lg bg-gray-50 p-4 sm:col-span-2"><dt class="text-xs font-semibold uppercase text-gray-500">Features</dt><dd id="product-features" class="mt-2 whitespace-pre-wrap text-sm leading-6 text-gray-700"></dd></div>
                <div class="rounded-lg bg-gray-50 p-4"><dt class="text-xs font-semibold uppercase text-gray-500">Created</dt><dd id="product-created" class="mt-2 text-sm text-gray-700"></dd></div>
                <div class="rounded-lg bg-gray-50 p-4"><dt class="text-xs font-semibold uppercase text-gray-500">Last updated</dt><dd id="product-updated" class="mt-2 text-sm text-gray-700"></dd></div>
            </dl>
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
                const edit = document.querySelector('#edit-link');
                const remove = document.querySelector('#delete-button');
                const csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? @json(csrf_token());
                const set = (selector, value) => { document.querySelector(selector).textContent = value ?? 'Not provided'; };
                const date = (value) => value ? new Intl.DateTimeFormat(undefined, { dateStyle: 'medium', timeStyle: 'short' }).format(new Date(value)) : 'Not available';
                const error = (message) => { status.textContent = message; status.className = 'mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700'; };
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
                        set('#product-discount', `₹${product.discount ?? 0}`);
                        set('#product-stock', product.stock);
                        set('#product-visibility', product.visibility);
                        set('#product-description', product.description);
                        set('#product-features', product.features);
                        set('#product-created', date(product.created_at));
                        set('#product-updated', date(product.updated_at));
                        const badge = document.querySelector('#product-status-badge');
                        badge.textContent = product.status;
                        badge.className = `w-fit rounded-full px-3 py-1 text-xs font-semibold ${product.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'}`;
                        edit.href = `${@json(route('vendor.product.create'))}?_id=${id}`;
                        edit.classList.remove('pointer-events-none', 'opacity-50');
                        remove.disabled = false;
                        remove.classList.remove('opacity-50');
                        loading.classList.add('hidden');
                        details.classList.remove('hidden');
                    } catch (exception) { loading.classList.add('hidden'); error(exception.message); }
                };
                remove.addEventListener('click', async () => {
                    if (!window.confirm('Are you sure you want to delete this product?')) return;
                    remove.disabled = true;
                    try {
                        const response = await fetch(`${api}/${id}`, { method: 'DELETE', headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrf } });
                        const payload = await response.json();
                        if (!response.ok || !payload.success) throw new Error(payload.message || 'Unable to delete product.');
                        window.location.href = @json(route('vendor.product'));
                    } catch (exception) { error(exception.message); remove.disabled = false; }
                });
                load();
            })();
        </script>
    @endpush
@endsection
