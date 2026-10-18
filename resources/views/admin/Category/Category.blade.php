@extends('layouts.Dashboard')

@section('content')
    <x-slot name="title">Categories</x-slot>

    <div class="p-4 sm:p-6">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-[#3d6f6d]">Category management</p>
                <h1 class="text-2xl font-bold text-gray-900">Categories</h1>
                <p class="mt-1 text-sm text-gray-600">Manage the categories available in your store.</p>
            </div>

            <a href="{{ route('admin.category.create') }}"
               class="inline-flex items-center justify-center rounded-lg bg-[#3d6f6d] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#315b59]">
                Add Category
            </a>
        </div>

        <div class="rounded-xl bg-white p-4 shadow-sm sm:p-6">
            <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <label class="relative block w-full sm:max-w-sm" for="category-search">
                    <span class="sr-only">Search categories</span>
                    <input id="category-search"
                           type="search"
                           placeholder="Search by name, slug, or description..."
                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                </label>
                <p id="category-count" class="text-sm text-gray-500" aria-live="polite"></p>
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
                    <tbody id="category-list" class="divide-y divide-gray-100"></tbody>
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

    <dialog id="edit-category-dialog" class="w-[min(100%-2rem,40rem)] rounded-xl p-0 shadow-xl backdrop:bg-gray-900/40">
        <form id="edit-category-form" method="dialog" class="flex flex-col gap-5 p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Edit Category</h2>
                    <p class="mt-1 text-sm text-gray-500">Update the category details.</p>
                </div>
                <button type="button" data-close-dialog class="text-2xl leading-none text-gray-400 hover:text-gray-700" aria-label="Close">&times;</button>
            </div>

            <input type="hidden" id="edit-category-id">
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <label class="text-sm font-semibold text-gray-700">
                    Name
                    <input id="edit-name" required maxlength="255" class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2.5 font-normal outline-none focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                </label>
                <label class="text-sm font-semibold text-gray-700">
                    Slug
                    <input id="edit-slug" required maxlength="255" class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2.5 font-normal outline-none focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                </label>
                <label class="text-sm font-semibold text-gray-700">
                    Icon
                    <input id="edit-icon" required maxlength="100" class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2.5 font-normal outline-none focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                </label>
                <label class="text-sm font-semibold text-gray-700">
                    Status
                    <select id="edit-status" required class="mt-2 w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 font-normal outline-none focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </label>
            </div>
            <label class="text-sm font-semibold text-gray-700">
                Description
                <textarea id="edit-description" required maxlength="255" rows="3" class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2.5 font-normal outline-none focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20"></textarea>
            </label>
            <div class="flex justify-end gap-3">
                <button type="button" data-close-dialog class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">Cancel</button>
                <button type="submit" class="rounded-lg bg-[#3d6f6d] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#315b59]">Save Changes</button>
            </div>
        </form>
    </dialog>

    @push('scripts')
        <script>
            (() => {
                const apiUrl = @json(url('/api/categories'));
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? @json(csrf_token());
                const list = document.querySelector('#category-list');
                const emptyState = document.querySelector('#category-empty');
                const pagination = document.querySelector('#pagination');
                const statusMessage = document.querySelector('#category-status');
                const count = document.querySelector('#category-count');
                const summary = document.querySelector('#pagination-summary');
                const searchInput = document.querySelector('#category-search');
                const editDialog = document.querySelector('#edit-category-dialog');
                const editForm = document.querySelector('#edit-category-form');
                let currentPage = 1;
                let currentCategories = [];
                let searchTimer;

                const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (character) => ({
                    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;',
                }[character]));

                const showStatus = (message, isError = false) => {
                    statusMessage.textContent = message;
                    statusMessage.className = `mb-4 rounded-lg px-4 py-3 text-sm ${isError ? 'bg-red-50 text-red-700' : 'bg-green-50 text-green-700'}`;
                };

                const hideStatus = () => statusMessage.classList.add('hidden');

                const renderPagination = (meta) => {
                    pagination.innerHTML = '';
                    for (let page = 1; page <= meta.last_page; page += 1) {
                        const button = document.createElement('button');
                        button.type = 'button';
                        button.textContent = page;
                        button.disabled = page === meta.current_page;
                        button.className = `rounded-lg px-3 py-2 text-sm font-semibold ${page === meta.current_page ? 'bg-[#3d6f6d] text-white' : 'border border-gray-300 text-gray-700 hover:bg-gray-50'}`;
                        button.addEventListener('click', () => loadCategories(page));
                        pagination.append(button);
                    }
                    summary.textContent = meta.total ? `Showing ${meta.from}-${meta.to} of ${meta.total}` : '';
                };

                const renderCategories = (categories, meta) => {
                    currentCategories = categories;
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
                                    <button type="button" data-edit-id="${category.id}" class="rounded-lg border border-[#3d6f6d] px-3 py-1.5 text-xs font-semibold text-[#3d6f6d] hover:bg-[#3d6f6d] hover:text-white">Edit</button>
                                    <button type="button" data-delete-id="${category.id}" class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50">Delete</button>
                                </div>
                            </td>
                        </tr>
                    `).join('');
                    emptyState.classList.toggle('hidden', categories.length > 0);
                    count.textContent = `${meta.total} categor${meta.total === 1 ? 'y' : 'ies'}`;
                    renderPagination(meta);
                };

                const loadCategories = async (page = 1) => {
                    hideStatus();
                    currentPage = page;
                    list.innerHTML = '<tr><td colspan="5" class="px-4 py-12 text-center text-sm text-gray-500">Loading categories...</td></tr>';
                    const params = new URLSearchParams({ page, per_page: 10 });
                    if (searchInput.value.trim()) params.set('search', searchInput.value.trim());
                    try {
                        const response = await fetch(`${apiUrl}?${params}`);
                        const payload = await response.json();
                        if (response.status === 404) {
                            renderCategories([], { current_page: 1, last_page: 1, total: 0, from: 0, to: 0 });
                            return;
                        }
                        if (!response.ok || !payload.success) throw new Error(payload.message || 'Unable to load categories.');
                        renderCategories(payload.data.data, payload.data);
                    } catch (error) {
                        list.innerHTML = '';
                        emptyState.classList.add('hidden');
                        showStatus(error.message, true);
                    }
                };

                const openEditDialog = (category) => {
                    document.querySelector('#edit-category-id').value = category.id;
                    document.querySelector('#edit-name').value = category.name ?? '';
                    document.querySelector('#edit-slug').value = category.slug ?? '';
                    document.querySelector('#edit-icon').value = category.icon ?? '';
                    document.querySelector('#edit-status').value = category.status ?? 'active';
                    document.querySelector('#edit-description').value = category.description ?? '';
                    editDialog.showModal();
                };

                list.addEventListener('click', async (event) => {
                    const editButton = event.target.closest('[data-edit-id]');
                    const deleteButton = event.target.closest('[data-delete-id]');
                    if (editButton) {
                        const category = currentCategories.find(({ id }) => id === Number(editButton.dataset.editId));
                        if (category) openEditDialog(category);
                    }
                    if (deleteButton && window.confirm('Are you sure you want to delete this category?')) {
                        const response = await fetch(`${apiUrl}/${deleteButton.dataset.deleteId}`, {
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': csrfToken, Accept: 'application/json' },
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

                editForm.addEventListener('submit', async (event) => {
                    event.preventDefault();
                    const id = document.querySelector('#edit-category-id').value;
                    const data = {
                        name: document.querySelector('#edit-name').value,
                        slug: document.querySelector('#edit-slug').value,
                        icon: document.querySelector('#edit-icon').value,
                        status: document.querySelector('#edit-status').value,
                        description: document.querySelector('#edit-description').value,
                    };
                    const response = await fetch(`${apiUrl}/${id}`, {
                        method: 'PATCH',
                        headers: { 'X-CSRF-TOKEN': csrfToken, Accept: 'application/json', 'Content-Type': 'application/json' },
                        body: JSON.stringify(data),
                    });
                    const payload = await response.json();
                    if (!response.ok || !payload.success) {
                        showStatus(payload.message || 'Unable to update category.', true);
                        return;
                    }
                    editDialog.close();
                    showStatus(payload.message);
                    await loadCategories(currentPage);
                });

                document.querySelectorAll('[data-close-dialog]').forEach((button) => button.addEventListener('click', () => editDialog.close()));
                searchInput.addEventListener('input', () => {
                    clearTimeout(searchTimer);
                    searchTimer = setTimeout(() => loadCategories(1), 300);
                });
                loadCategories();
            })();
        </script>
    @endpush
@endsection
