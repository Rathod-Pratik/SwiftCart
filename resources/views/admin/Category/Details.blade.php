@extends('layouts.Dashboard')

@section('content')
    @php($categoryId = request()->query('_id'))

    <x-slot name="title">Category Details</x-slot>

    <div class="p-4 sm:p-6">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Category Details</h1>
            </div>

            <div>
                <a href="{{ route('admin.category') }}"
                   class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                    Back
                </a>
            </div>
        </div>

        <div id="category-status" class="mb-4 hidden rounded-lg px-4 py-3 text-sm" role="alert"></div>

        <div id="category-loading" class="rounded-xl bg-white p-8 text-center text-sm text-gray-500 shadow-sm">
            Loading category details...
        </div>

        <section id="category-details" class="hidden max-w-4xl overflow-hidden rounded-2xl bg-white shadow-sm">
            <div class="flex flex-col gap-5 border-b border-gray-100 bg-[#f7faf9] p-5 sm:flex-row sm:items-center sm:justify-between sm:p-8">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#3d6f6d]">Category details</p>
                    <h2 id="category-name" class="mt-2 text-2xl font-bold text-gray-900 sm:text-3xl"></h2>
                </div>
                <span id="category-status-badge" class="w-fit rounded-full px-3 py-1 text-xs font-semibold"></span>
            </div>

            <div class="p-5 sm:p-8">
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Category ID</dt>
                    <dd id="category-id" class="mt-2 text-sm font-medium text-gray-900"></dd>
                </div>
                <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Slug</dt>
                    <dd id="category-slug-detail" class="mt-2 break-all text-sm font-medium text-gray-900"></dd>
                </div>
                <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Category image</dt>
                    <dd class="mt-2">
                        <img id="category-image"
                             class="hidden h-28 w-28 rounded-xl border border-gray-200 object-cover shadow-sm"
                             alt="Category image">
                        <span id="category-image-empty" class="text-sm text-gray-500">No image available</span>
                    </dd>
                </div>
                <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 sm:col-span-2">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Description</dt>
                    <dd id="category-description" class="mt-2 whitespace-pre-wrap text-sm leading-6 text-gray-700"></dd>
                </div>
                <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Created</dt>
                    <dd id="category-created-at" class="mt-2 text-sm text-gray-700"></dd>
                </div>
                <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Last updated</dt>
                    <dd id="category-updated-at" class="mt-2 text-sm text-gray-700"></dd>
                </div>
                </dl>

                <div class="mt-8 flex flex-col-reverse gap-3 border-t border-gray-100 pt-5 sm:flex-row sm:justify-end">
                    <button id="delete-category-button"
                            type="button"
                            disabled
                            class="inline-flex items-center justify-center rounded-lg border border-red-200 px-5 py-2.5 text-sm font-semibold text-red-600 transition hover:bg-red-50 disabled:cursor-not-allowed disabled:opacity-50">
                        Delete
                    </button>
                    <a id="edit-category-link"
                       href="#"
                       class="pointer-events-none inline-flex items-center justify-center rounded-lg bg-[#3d6f6d] px-5 py-2.5 text-sm font-semibold text-white opacity-50 transition hover:bg-[#315b59]">
                        Edit
                    </a>
                </div>
            </div>
        </section>
    </div>

    @push('scripts')
        <script>
            (() => {
                const categoryId = @json($categoryId);
                const apiUrl = @json(url('/api/categories'));
                const storageUrl = @json(asset('storage'));
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
                        setText('#category-id', category.id);
                        setText('#category-slug-detail', category.slug);
                        setText('#category-description', category.description);
                        setText('#category-created-at', formatDate(category.created_at));
                        setText('#category-updated-at', formatDate(category.updated_at));

                        const categoryImage = document.querySelector('#category-image');
                        const categoryImageEmpty = document.querySelector('#category-image-empty');
                        categoryImage.onerror = () => {
                            categoryImage.classList.add('hidden');
                            categoryImageEmpty.classList.remove('hidden');
                        };
                        if (category.icon) {
                            categoryImage.src = category.icon.startsWith('http')
                                ? category.icon
                                : `${storageUrl}/${category.icon}`;
                            categoryImage.alt = `${category.name} category image`;
                            categoryImage.classList.remove('hidden');
                            categoryImageEmpty.classList.add('hidden');
                        } else {
                            categoryImage.classList.add('hidden');
                            categoryImageEmpty.classList.remove('hidden');
                        }

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
