@extends('layouts.Dashboard')

@section('content')
    <x-slot name="title">Categories</x-slot>

    <div class="p-4 sm:p-6">
        <div class="mb-6">
            <p class="text-sm font-medium text-[#3d6f6d]">Catalog management</p>
            <h1 class="text-2xl font-bold text-gray-900">Categories</h1>
            <p class="mt-1 text-sm text-gray-600">Organize and manage the categories used across your catalog.</p>
        </div>

        <div class="mb-6 grid grid-cols-2 gap-4 sm:grid-cols-3">
            @foreach ([['Total Categories', 'category-stat-total', 'layers', 'bg-teal-50', 'text-[#3d6f6d]', 'text-[#3d6f6d]'], ['Active', 'category-stat-active', 'check-circle', 'bg-emerald-50', 'text-emerald-600', 'text-emerald-600'], ['Inactive', 'category-stat-inactive', 'pause-circle', 'bg-gray-100', 'text-gray-500', 'text-gray-600']] as [$label, $id, $icon, $iconBackground, $iconColor, $valueColor])
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">{{ $label }}</p>
                            <p id="{{ $id }}" class="mt-2 text-2xl font-bold {{ $valueColor }}">—</p>
                        </div>
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ $iconBackground }} {{ $iconColor }}">
                            <x-dynamic-component :component="'lucide-'.$icon" class="h-5 w-5" />
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

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
                        <th class="w-14 px-4 py-3 text-center font-semibold">#</th>
                        <th class="w-20 px-4 py-3 font-semibold">Image</th>
                        <th class="px-4 py-3 font-semibold">Name</th>
                        <th class="px-4 py-3 font-semibold">Slug</th>
                        <th class="px-4 py-3 font-semibold">Description</th>
                        <th class="px-4 py-3 font-semibold">Status</th>
                        <th class="px-4 py-3 text-right font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody id="category-list" class="divide-y divide-gray-100">
                     <tr id="category-loading">
                            <td colspan="7" class="px-4 py-12 text-center">
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

                fetch(@json(url('/api/admin/dashboard/stats')), { headers: { Accept: 'application/json' } })
                    .then((response) => {
                        if (!response.ok) throw new Error('Unable to load category statistics.');
                        return response.json();
                    })
                    .then((payload) => {
                        if (!payload.stats) throw new Error(payload.message || 'Unable to load category statistics.');
                        document.querySelector('#category-stat-total').textContent = payload.stats.total_categories;
                        document.querySelector('#category-stat-active').textContent = payload.stats.active_categories;
                        document.querySelector('#category-stat-inactive').textContent = payload.stats.inactive_categories;
                    })
                    .catch((error) => showStatus(error.message, true));

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

                const resolveImageUrl = async (keyOrUrl) => {
                    if (!keyOrUrl) return '';
                    if (keyOrUrl.startsWith('http://') || keyOrUrl.startsWith('https://')) return keyOrUrl;

                    try {
                        if (typeof window.s3Url === 'function') {
                            const url = await window.s3Url(keyOrUrl);
                            if (url) return url;
                        }
                        const response = await fetch(`/api/s3/sign?key=${encodeURIComponent(keyOrUrl)}`, {
                            headers: { Accept: 'application/json' },
                        });
                        if (response.ok) {
                            const payload = await response.json();
                            return payload.url || keyOrUrl;
                        }
                    } catch {}

                    return keyOrUrl;
                };

                const placeholderSvg = `data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%239CA3AF' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><rect width='18' height='18' x='3' y='3' rx='2' ry='2'/><circle cx='9' cy='9' r='2'/><path d='m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21'/></svg>`;

                const renderCategories = (categories, meta) => {
                    const fromIndex = meta && meta.from ? meta.from : 1;
                    list.innerHTML = categories.map((category, index) => {
                        const imageKey = category.icon || category.image || '';
                        return `
                        <tr class="text-gray-700 hover:bg-gray-50/70 transition">
                            <td class="px-4 py-4 text-center text-xs font-semibold text-gray-500">${fromIndex + index}</td>
                            <td class="px-4 py-4">
                                <div class="h-12 w-12 shrink-0 overflow-hidden rounded-xl border border-gray-200 bg-gray-50 shadow-sm flex items-center justify-center">
                                    <img data-image-key="${escapeHtml(imageKey)}"
                                         src="${placeholderSvg}"
                                         alt="${escapeHtml(category.name)}"
                                         class="h-full w-full object-cover"
                                         onerror="this.onerror=null;this.src='${placeholderSvg}';this.classList.add('p-2.5','opacity-60');">
                                </div>
                            </td>
                            <td class="px-4 py-4 font-semibold text-gray-900">${escapeHtml(category.name)}</td>
                            <td class="px-4 py-4">
                                <code class="rounded bg-gray-100 px-2 py-0.5 text-xs font-mono text-gray-700">${escapeHtml(category.slug)}</code>
                            </td>
                            <td class="max-w-xs truncate px-4 py-4 text-xs text-gray-600">${escapeHtml(category.description || '—')}</td>
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-semibold ${category.status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-gray-100 text-gray-600'}">
                                    <span class="h-1.5 w-1.5 rounded-full ${category.status === 'active' ? 'bg-emerald-500' : 'bg-gray-400'}"></span>
                                    ${escapeHtml(category.status)}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex justify-end gap-2">
                                    <a href="${@json(route('admin.category.details'))}?_id=${category.id}" class="rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">View</a>
                                    <a href="${editUrl}?_id=${encodeURIComponent(category.id)}" class="rounded-lg border border-[#3d6f6d] px-3 py-1.5 text-xs font-semibold text-[#3d6f6d] hover:bg-[#3d6f6d] hover:text-white transition">Edit</a>
                                    <button type="button" data-delete-id="${category.id}" class="rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-100 transition">Delete</button>
                                </div>
                            </td>
                        </tr>
                    `;
                    }).join('');

                    // Load category images via S3 signed URLs
                    const imgEls = list.querySelectorAll('img[data-image-key]');
                    const keys = [...new Set([...imgEls].map(img => img.dataset.imageKey).filter(Boolean))];

                    if (keys.length > 0) {
                        if (typeof window.s3Urls === 'function') {
                            window.s3Urls(keys).then(urlMap => {
                                imgEls.forEach(img => {
                                    const key = img.dataset.imageKey;
                                    if (urlMap && urlMap[key]) {
                                        img.src = urlMap[key];
                                    }
                                });
                            }).catch(() => {
                                imgEls.forEach(img => {
                                    const key = img.dataset.imageKey;
                                    if (key) resolveImageUrl(key).then(src => { if (src) img.src = src; });
                                });
                            });
                        } else {
                            imgEls.forEach(img => {
                                const key = img.dataset.imageKey;
                                if (key) resolveImageUrl(key).then(src => { if (src) img.src = src; });
                            });
                        }
                    }

                    emptyState.classList.toggle('hidden', categories.length > 0);
                    renderPagination(meta);
                };

                const showLoading = () => {
                    list.innerHTML = '<tr id="category-loading"><td colspan="7" class="px-4 py-12 text-center"><div class="flex items-center justify-center py-4"><div class="w-12 h-12 border-[3px] border-[#64883e] border-t-transparent rounded-full animate-spin"></div></div></td></tr>';
                };

                const loadCategories = async (page = 1) => {
                    hideStatus();
                    showLoading();
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
