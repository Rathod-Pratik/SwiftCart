@extends('layouts.Vendor')

@section('content')
    @php($productId = request()->query('_id'))

    <x-slot name="title">{{ $productId ? 'Edit Product' : 'Create Product' }}</x-slot>

    <div class="p-4 sm:p-6">
        <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-[#3d6f6d]">Product management</p>
                <h1 id="page-title" class="text-2xl font-bold text-gray-900">{{ $productId ? 'Edit Product' : 'Create Product' }}</h1>
                <p class="mt-1 text-sm text-gray-600">{{ $productId ? 'Update your product details.' : 'Add a product to your store.' }}</p>
            </div>
            <a href="{{ route('vendor.product') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">Back to Products</a>
        </div>

        <div class="max-w-4xl rounded-xl bg-white p-5 shadow-sm sm:p-8">
            <form id="product-form" data-product-id="{{ $productId }}" class="flex flex-col gap-6">
                @csrf
                <div id="form-status" class="hidden rounded-lg px-4 py-3 text-sm" role="alert"></div>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <label class="text-sm font-semibold text-gray-700">Product name
                        <input name="name" required maxlength="255" placeholder="e.g. Wireless headphones" class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-3 font-normal outline-none focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                    </label>
                    <label class="text-sm font-semibold text-gray-700">Category
                        <select name="category_id" id="category_id" required class="mt-2 w-full rounded-lg border border-gray-300 bg-white px-4 py-3 font-normal outline-none focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20"><option value="">Loading categories...</option></select>
                    </label>
                    <label class="text-sm font-semibold text-gray-700">Price
                        <input name="price" type="number" min="0" step="0.01" required class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-3 font-normal outline-none focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                    </label>
                    <label class="text-sm font-semibold text-gray-700">Discount
                        <input name="discount" type="number" min="0" step="0.01" value="0" class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-3 font-normal outline-none focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                    </label>
                    <label class="text-sm font-semibold text-gray-700">Stock
                        <input name="stock" type="number" min="0" required class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-3 font-normal outline-none focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                    </label>
                    <label class="text-sm font-semibold text-gray-700">Store link
                        <input name="store_link" type="url" placeholder="https://..." class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-3 font-normal outline-none focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                    </label>
                </div>
                <label class="text-sm font-semibold text-gray-700">Description
                    <textarea name="description" rows="4" class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-3 font-normal outline-none focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20"></textarea>
                </label>
                <label class="text-sm font-semibold text-gray-700">Features
                    <textarea name="features" rows="3" required placeholder="Describe the main product features" class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-3 font-normal outline-none focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20"></textarea>
                </label>
                <label class="text-sm font-semibold text-gray-700">Image URLs
                    <input name="image_urls" placeholder="Separate multiple URLs with commas" class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-3 font-normal outline-none focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                </label>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <label class="text-sm font-semibold text-gray-700">Status
                        <select name="status" class="mt-2 w-full rounded-lg border border-gray-300 bg-white px-4 py-3 font-normal outline-none focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20"><option value="active">Active</option><option value="inactive">Inactive</option></select>
                    </label>
                    <label class="text-sm font-semibold text-gray-700">Visibility
                        <select name="visibility" class="mt-2 w-full rounded-lg border border-gray-300 bg-white px-4 py-3 font-normal outline-none focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20"><option value="public">Public</option><option value="private">Private</option></select>
                    </label>
                </div>
                <div class="flex flex-wrap gap-5">
                    @foreach (['is_featured' => 'Featured', 'is_trending' => 'Trending', 'is_limited' => 'Limited stock'] as $name => $label)
                        <label class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700"><input type="checkbox" name="{{ $name }}" value="1" class="h-4 w-4 rounded border-gray-300 text-[#3d6f6d] focus:ring-[#3d6f6d]"> {{ $label }}</label>
                    @endforeach
                </div>
                <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-5 sm:flex-row sm:justify-end">
                    <a href="{{ route('vendor.product') }}" class="inline-flex justify-center rounded-lg border border-gray-300 px-5 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="rounded-lg bg-[#3d6f6d] px-5 py-3 text-sm font-semibold text-white hover:bg-[#315b59]"><span id="submit-label">{{ $productId ? 'Save Changes' : 'Create Product' }}</span></button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            (() => {
                const form = document.querySelector('#product-form');
                const productId = form.dataset.productId;
                const productUrl = @json(url('/api/products'));
                const categoryUrl = @json(url('/api/categories'));
                const status = document.querySelector('#form-status');
                const submit = form.querySelector('button[type="submit"]');
                const submitLabel = document.querySelector('#submit-label');
                const csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? @json(csrf_token());
                const showError = (message) => {
                    status.textContent = message;
                    status.className = 'rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700';
                };
                const setValue = (name, value) => { if (form.elements[name]) form.elements[name].value = value ?? ''; };
                const loadCategories = async () => {
                    const response = await fetch(`${categoryUrl}?per_page=100`, { headers: { Accept: 'application/json' } });
                    const payload = await response.json();
                    if (!response.ok || !payload.success) throw new Error(payload.message || 'Unable to load categories.');
                    const categories = payload.data.data ?? [];
                    form.elements.category_id.innerHTML = '<option value="">Select a category</option>' + categories.map((category) => `<option value="${category.id}">${category.name}</option>`).join('');
                };
                const loadProduct = async () => {
                    if (!productId) return;
                    const response = await fetch(`${productUrl}/${productId}`, { headers: { Accept: 'application/json' } });
                    const payload = await response.json();
                    if (!response.ok || !payload.success) throw new Error(payload.message || 'Unable to load product.');
                    const product = payload.data;
                    ['name', 'price', 'discount', 'stock', 'description', 'features', 'store_link', 'status', 'visibility', 'category_id'].forEach((field) => setValue(field, product[field]));
                    setValue('image_urls', Array.isArray(product.image) ? product.image.join(', ') : '');
                    ['is_featured', 'is_trending', 'is_limited'].forEach((field) => { form.elements[field].checked = Boolean(product[field]); });
                };
                form.addEventListener('submit', async (event) => {
                    event.preventDefault();
                    submit.disabled = true;
                    submitLabel.textContent = productId ? 'Saving...' : 'Creating...';
                    const data = Object.fromEntries(new FormData(form));
                    delete data._token;
                    data.price = Number(data.price);
                    data.discount = Number(data.discount || 0);
                    data.stock = Number(data.stock);
                    data.image = data.image_urls ? data.image_urls.split(',').map((value) => value.trim()).filter(Boolean) : [];
                    delete data.image_urls;
                    ['is_featured', 'is_trending', 'is_limited'].forEach((field) => { data[field] = form.elements[field].checked; });
                    try {
                        const response = await fetch(productId ? `${productUrl}/${productId}` : productUrl, {
                            method: productId ? 'PATCH' : 'POST',
                            headers: { Accept: 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                            body: JSON.stringify(data),
                        });
                        const payload = await response.json();
                        if (!response.ok || !payload.success) throw new Error(payload.errors ? Object.values(payload.errors).flat().join(' ') : payload.message || 'Unable to save product.');
                        window.location.href = @json(route('vendor.product'));
                    } catch (error) {
                        showError(error.message);
                        submit.disabled = false;
                        submitLabel.textContent = productId ? 'Save Changes' : 'Create Product';
                    }
                });
                Promise.all([loadCategories(), loadProduct()]).catch((error) => showError(error.message));
            })();
        </script>
    @endpush
@endsection
