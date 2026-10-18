@extends('layouts.Dashboard')

@section('content')
    @php($categoryId = request()->query('_id'))

    <x-slot name="title">Category Details</x-slot>

    <div class="p-4 sm:p-6">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-[#3d6f6d]">Category management</p>
                <h1 class="text-2xl font-bold text-gray-900">Category Details</h1>
                <p class="mt-1 text-sm text-gray-600">Review and manage this category.</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.category') }}"
                   class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                    Back to Categories
                </a>
                <a id="edit-category-link"
                   href="#"
                   class="pointer-events-none inline-flex items-center justify-center rounded-lg bg-[#3d6f6d] px-4 py-2.5 text-sm font-semibold text-white opacity-50 transition hover:bg-[#315b59]">
                    Update Category
                </a>
                <button id="delete-category-button"
                        type="button"
                        disabled
                        class="rounded-lg border border-red-200 px-4 py-2.5 text-sm font-semibold text-red-600 opacity-50 transition hover:bg-red-50 disabled:cursor-not-allowed">
                    Delete Category
                </button>
            </div>
        </div>

        <div id="category-status" class="mb-4 hidden rounded-lg px-4 py-3 text-sm" role="alert"></div>

        <div id="category-loading" class="rounded-xl bg-white p-8 text-center text-sm text-gray-500 shadow-sm">
            Loading category details...
        </div>

        <section id="category-details" class="hidden max-w-4xl rounded-xl bg-white p-5 shadow-sm sm:p-8">
            <div class="mb-8 flex flex-col gap-4 border-b border-gray-100 pb-6 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p class="text-sm text-gray-500">Category name</p>
                    <h2 id="category-name" class="mt-1 text-3xl font-bold text-gray-900"></h2>
                    <p id="category-slug" class="mt-2 text-sm text-gray-500"></p>
                </div>
                <span id="category-status-badge" class="w-fit rounded-full px-3 py-1 text-xs font-semibold"></span>
            </div>

            <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div class="rounded-lg bg-gray-50 p-4">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Category ID</dt>
                    <dd id="category-id" class="mt-2 text-sm font-medium text-gray-900"></dd>
                </div>
                <div class="rounded-lg bg-gray-50 p-4">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Icon</dt>
                    <dd id="category-icon" class="mt-2 text-sm font-medium text-gray-900"></dd>
                </div>
                <div class="rounded-lg bg-gray-50 p-4 sm:col-span-2">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Description</dt>
                    <dd id="category-description" class="mt-2 whitespace-pre-wrap text-sm leading-6 text-gray-700"></dd>
                </div>
                <div class="rounded-lg bg-gray-50 p-4">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Created</dt>
                    <dd id="category-created-at" class="mt-2 text-sm text-gray-700"></dd>
                </div>
                <div class="rounded-lg bg-gray-50 p-4">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Last updated</dt>
                    <dd id="category-updated-at" class="mt-2 text-sm text-gray-700"></dd>
                </div>
            </dl>
        </section>
    </div>

    @push('scripts')
        <script>
            (() => {
                const categoryId = @json($categoryId);
                const apiUrl = @json(url('/api/categories'));
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? @json(csrf_token());
                const loading = document.querySelector('#category-loading');
                const details = document.querySelector('#category-details');
                const statusMessage = document.querySelector('#category-status');
                const editLink = document.querySelector('#edit-category-link');
                const deleteButton = document.querySelector('#delete-category-button');

                const showStatus = (message, isError = false) => {
                    statusMessage.textContent = message;
                    statusMessage.className = `mb-4 rounded-lg px-4 py-3 text-sm ${isError ? 'bg-red-50 text-red-700' : 'bg-green-50 text-green-700'}`;
                };

                const formatDate = (value) => value
                    ? new Intl.DateTimeFormat(undefined, { dateStyle: 'medium', timeStyle: 'short' }).format(new Date(value))
                    : 'Not available';

                const setText = (selector, value) => {
                    document.querySelector(selector).textContent = value ?? 'Not available';
                };

                const loadCategory = async () => {
                    if (!categoryId) {
                        loading.classList.add('hidden');
                        showStatus('A category ID is required in the URL, for example: ?_id=1', true);
                        return;
                    }

                    try {
                        const response = await fetch(`${apiUrl}/${encodeURIComponent(categoryId)}`, {
                            headers: { Accept: 'application/json' },
                        });
                        const payload = await response.json();
                        if (!response.ok || !payload.success) {
                            throw new Error(payload.message || 'Unable to load category details.');
                        }

                        const category = payload.data;
                        setText('#category-name', category.name);
                        setText('#category-slug', `/${category.slug}`);
                        setText('#category-id', category.id);
                        setText('#category-icon', category.icon);
                        setText('#category-description', category.description);
                        setText('#category-created-at', formatDate(category.created_at));
                        setText('#category-updated-at', formatDate(category.updated_at));

                        const badge = document.querySelector('#category-status-badge');
                        badge.textContent = category.status;
                        badge.className = `w-fit rounded-full px-3 py-1 text-xs font-semibold ${category.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'}`;

                        editLink.href = `${@json(route('admin.category.create'))}?_id=${encodeURIComponent(category.id)}`;
                        editLink.classList.remove('pointer-events-none', 'opacity-50');
                        deleteButton.disabled = false;
                        deleteButton.classList.remove('opacity-50');
                        loading.classList.add('hidden');
                        details.classList.remove('hidden');
                    } catch (error) {
                        loading.classList.add('hidden');
                        showStatus(error.message, true);
                    }
                };

                deleteButton.addEventListener('click', async () => {
                    if (!window.confirm('Are you sure you want to delete this category?')) return;

                    deleteButton.disabled = true;
                    try {
                        const response = await fetch(`${apiUrl}/${encodeURIComponent(categoryId)}`, {
                            method: 'DELETE',
                            headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrfToken },
                        });
                        const payload = await response.json();
                        if (!response.ok || !payload.success) {
                            throw new Error(payload.message || 'Unable to delete category.');
                        }
                        window.location.href = @json(route('admin.category'));
                    } catch (error) {
                        showStatus(error.message, true);
                        deleteButton.disabled = false;
                    }
                });

                loadCategory();
            })();
        </script>
    @endpush
@endsection
