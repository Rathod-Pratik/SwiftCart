@extends('layouts.Dashboard')

@section('content')
    <x-slot name="title">Categories</x-slot>

    <div class="p-4 sm:p-6">
        <div class="mb-5 rounded-xl bg-white p-4 shadow-sm sm:p-6">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <label class="relative block w-full sm:max-w-sm" for="category-search">
                    <span class="sr-only">Search categories</span>
                    <input id="category-search" type="search" placeholder="Search by name, slug, or description..."
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                </label>
                    <a href="{{ route('admin.category.create') }}"
                        class="inline-flex items-center justify-center rounded-lg bg-[#3d6f6d] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#315b59]">
                        Create
                    </a>
            </div>
        </div>

        <div id="category-status" class="mb-4 hidden rounded-lg px-4 py-3 text-sm" role="alert"></div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] text-left text-sm">
                <thead class="border-b border-gray-200 text-xs uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Name</th>
                        <th class="px-4 py-3 font-semibold">Slug</th>
                        <th class="px-4 py-3 font-semibold">Description</th>
                        <th class="px-4 py-3 font-semibold">Status</th>
                        <th class="px-4 py-3 text-right font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody id="category-list" class="divide-y divide-gray-100">
                     <tr id="category-loading">
                            <td colspan="5" class="px-4 py-12 text-center">
                                <x-Loading />
                            </td>
                        </tr>
                </tbody>
            </table>
        </div>

        <div id="category-empty" class="hidden py-12 text-center text-sm text-gray-500">
            No categories found.
        </div>

        <div class="mt-5 flex flex-col gap-3 border-t border-gray-100 pt-4 sm:flex-row sm:items-center sm:justify-between">
            <p id="pagination-summary" class="text-sm text-gray-500"></p>
            <div id="pagination" class="flex gap-2"></div>
        </div>
    </div>
    </div>

    @push('scripts')
        <script>
            (() => {
                const apiUrl = @json(url('/api/categories'));
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ??
                @json(csrf_token());
                const list = document.querySelector('#category-list');
                const emptyState = document.querySelector('#category-empty');
                const loading = document.querySelector('#category-loading');
                const pagination = document.querySelector('#pagination');
                const statusMessage = document.querySelector('#category-status');
                const summary = document.querySelector('#pagination-summary');
                const searchInput = document.querySelector('#category-search');
                const editUrl = @json(route('admin.category.create'));
                let currentPage = 1;
                let searchTimer;

                const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (character) => ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;',
                } [character]));

                const showStatus = (message, isError = false) => {
                    statusMessage.textContent = message;
                    statusMessage.className =
                        `mb-4 rounded-lg px-4 py-3 text-sm ${isError ? 'bg-red-50 text-red-700' : 'bg-green-50 text-green-700'}`;
                };

                const hideStatus = () => statusMessage.classList.add('hidden');

                const renderPagination = (meta) => {
                    pagination.innerHTML = '';
                    for (let page = 1; page <= meta.last_page; page += 1) {
                        const button = document.createElement('button');
                        button.type = 'button';
                        button.textContent = page;
                        button.disabled = page === meta.current_page;
                        button.className =
                            `rounded-lg px-3 py-2 text-sm font-semibold ${page === meta.current_page ? 'bg-[#3d6f6d] text-white' : 'border border-gray-300 text-gray-700 hover:bg-gray-50'}`;
                        button.addEventListener('click', () => loadCategories(page));
                        pagination.append(button);
                    }
                    summary.textContent = meta.total ? `Showing ${meta.from}-${meta.to} of ${meta.total}` : '';
                };

                const renderCategories = (categories, meta) => {
                    list.innerHTML = categories.map((category) => `
                        <tr class="text-gray-700">
                            <td class="px-4 py-4 font-semibold text-gray-900">${escapeHtml(category.name)}</td>
                            <td class="px-4 py-4">${escapeHtml(category.slug)}</td>
                            <td class="max-w-xs truncate px-4 py-4">${escapeHtml(category.description)}</td>
                            <td class="px-4 py-4">
                                <span class="rounded-full px-3 py-1 text-xs font-semibold ${category.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'}">
                                    ${escapeHtml(category.status)}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex justify-end gap-2">
                                    <a href="${@json(route('admin.category.details'))}?_id=${category.id}" class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50">View</a>
                                    <a href="${editUrl}?_id=${encodeURIComponent(category.id)}" class="rounded-lg border border-[#3d6f6d] px-3 py-1.5 text-xs font-semibold text-[#3d6f6d] hover:bg-[#3d6f6d] hover:text-white">Edit</a>
                                    <button type="button" data-delete-id="${category.id}" class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50">Delete</button>
                                </div>
                            </td>
                        </tr>
                    `).join('');
                    emptyState.classList.toggle('hidden', categories.length > 0);
                    renderPagination(meta);
                };

                const loadCategories = async (page = 1) => {
                    hideStatus();
                    loading.classList.remove('hidden');
                     list.querySelectorAll('tr:not(#category-loading)').forEach((row) => row.remove());
                    currentPage = page;
                    
                    const params = new URLSearchParams({
                        page,
                        per_page: 10
                    });
                    if (searchInput.value.trim()) params.set('search', searchInput.value.trim());
                    try {
                        const response = await fetch(`${apiUrl}?${params}`);
                        const payload = await response.json();
                        if (response.status === 404) {
                            renderCategories([], {
                                current_page: 1,
                                last_page: 1,
                                total: 0,
                                from: 0,
                                to: 0
                            });
                            return;
                        }
                        if (!response.ok || !payload.success) throw new Error(payload.message ||
                            'Unable to load categories.');
                        renderCategories(payload.data.data, payload.data);
                    } catch (error) {
                        list.innerHTML = '';
                        emptyState.classList.add('hidden');
                        showStatus(error.message, true);
                    }
                };

                list.addEventListener('click', async (event) => {
                    const deleteButton = event.target.closest('[data-delete-id]');
                    if (deleteButton && window.confirm('Are you sure you want to delete this category?')) {
                        const response = await fetch(`${apiUrl}/${deleteButton.dataset.deleteId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                Accept: 'application/json'
                            },
                        });
                        const payload = await response.json();
                        if (!response.ok || !payload.success) {
                            showStatus(payload.message || 'Unable to delete category.', true);
                            return;
                        }
                        showStatus(payload.message);
                        await loadCategories(currentPage);
                    }
                });

                searchInput.addEventListener('input', () => {
                    clearTimeout(searchTimer);
                    searchTimer = setTimeout(() => loadCategories(1), 300);
                });
                loadCategories();
            })();
        </script>
    @endpush
@endsection
