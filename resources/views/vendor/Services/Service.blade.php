@extends('layouts.Vendor')

@section('content')
    <x-slot name="title">Services & Perks</x-slot>

    <div class="p-4 sm:p-6 lg:p-8">
        <nav class="mb-6 flex items-center gap-2 text-xs font-semibold text-gray-400" aria-label="Breadcrumb">
            <a href="{{ route('vendor.dashboard') }}" class="transition hover:text-gray-600">Dashboard</a>
            <span>/</span>
            <span class="text-gray-800">Services</span>
        </nav>
        {{-- Header --}}
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-2 text-sm font-semibold text-[#3d6f6d]">
                    <x-lucide-truck class="h-4 w-4" />
                    <span>Store Offerings & Perks</span>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">Vendor Services</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Configure services like Cash on Delivery, Express Shipping, Warranties & Returns to feature on your product pages.
                </p>
            </div>
            <div class="flex items-center gap-2.5">

                <a href="{{ route('vendor.service.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-[#3d6f6d] px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#315b59]">
                    <x-lucide-plus class="h-4 w-4" />
                    <span>Add New Service</span>
                </a>
            </div>
        </div>

        {{-- Overview Metric Cards --}}
        <div class="mb-6 grid grid-cols-2 gap-4 sm:grid-cols-4">
            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total Services</span>
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-teal-50 text-[#3d6f6d]">
                        <x-lucide-layers class="h-4 w-4" />
                    </span>
                </div>
                <p id="stat-total" class="mt-2 text-2xl font-bold text-gray-900">0</p>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Active</span>
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                        <x-lucide-check-circle class="h-4 w-4" />
                    </span>
                </div>
                <p id="stat-active" class="mt-2 text-2xl font-bold text-emerald-600">0</p>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Inactive</span>
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-500">
                        <x-lucide-pause-circle class="h-4 w-4" />
                    </span>
                </div>
                <p id="stat-inactive" class="mt-2 text-2xl font-bold text-gray-600">0</p>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Icon Library</span>
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                        <x-lucide-sparkles class="h-4 w-4" />
                    </span>
                </div>
                <p class="mt-2 text-2xl font-bold text-amber-600">20+ Icons</p>
            </div>
        </div>

        {{-- Main Table Card --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6">
            {{-- Filter and Search Toolbar --}}
            <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex flex-1 items-center gap-3">
                    <label class="relative block w-full max-w-sm" for="service-search">
                        <span class="sr-only">Search services</span>
                        <x-lucide-search class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" />
                        <input id="service-search" type="search" placeholder="Search by service name or icon..."
                            class="w-full rounded-xl border border-gray-300 py-2.5 pl-10 pr-4 text-sm outline-none transition focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                    </label>

                    <select id="filter-status" class="rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm text-gray-700 outline-none transition focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                        <option value="">All Status</option>
                        <option value="1">Active Only</option>
                        <option value="0">Inactive Only</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <span id="service-count" class="text-xs font-semibold text-gray-500"></span>
                    <button type="button" id="refresh-btn" class="rounded-xl border border-gray-200 p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-900 transition" title="Refresh">
                        <x-lucide-refresh-cw class="h-4 w-4" />
                    </button>
                </div>
            </div>

            {{-- Status Alert Box --}}
            <div id="service-status" class="mb-4 hidden rounded-xl px-4 py-3 text-sm font-medium" role="alert"></div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full min-w-[650px] text-left text-sm">
                    <thead class="border-b border-gray-200 text-xs font-semibold uppercase tracking-wider text-gray-500">
                        <tr>
                            <th class="px-4 py-3">Service & Icon</th>
                            <th class="px-4 py-3">Icon Key</th>
                            <th class="px-4 py-3">Description</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="service-list" class="divide-y divide-gray-100"></tbody>
                </table>
            </div>

            {{-- Empty State --}}
            <div id="service-empty" class="hidden py-14 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#3d6f6d]/10 text-[#3d6f6d]">
                    <x-lucide-truck class="h-7 w-7" />
                </div>
                <h3 class="mt-3 text-base font-bold text-gray-900">No Services Found</h3>
                <p class="mt-1 text-xs text-gray-500">Create your first custom service badge to show on your products.</p>
                <a href="{{ route('vendor.service.create') }}" class="mt-4 inline-flex items-center gap-2 rounded-xl bg-[#3d6f6d] px-4 py-2 text-xs font-semibold text-white transition hover:bg-[#315b59]">
                    <x-lucide-plus class="h-3.5 w-3.5" />
                    <span>Create Service</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div id="delete-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
        <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-rose-50 text-rose-600">
                    <x-lucide-alert-triangle class="h-6 w-6" />
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900">Delete Service</h3>
                    <p class="text-xs text-gray-500">Are you sure you want to delete this service?</p>
                </div>
            </div>
            <p class="mt-3 text-sm text-gray-600">
                Service "<span id="delete-service-name" class="font-bold text-gray-900"></span>" will be removed. Products referencing it will retain their text records.
            </p>
            <div class="mt-6 flex items-center justify-end gap-3">
                <button type="button" id="cancel-delete-btn" class="rounded-xl border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="button" id="confirm-delete-btn" class="inline-flex items-center gap-2 rounded-xl bg-rose-600 px-5 py-2 text-sm font-semibold text-white hover:bg-rose-700 transition">
                    <span id="delete-spinner" class="hidden h-4 w-4 animate-spin rounded-full border-2 border-white border-t-transparent"></span>
                    <span>Delete</span>
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (() => {
                const apiUrl = @json(url('/api/vendor/services'));
                const createUrl = @json(route('vendor.service.create'));
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
                const list = document.querySelector('#service-list');
                const empty = document.querySelector('#service-empty');
                const status = document.querySelector('#service-status');
                const search = document.querySelector('#service-search');
                const filterStatus = document.querySelector('#filter-status');
                const refreshBtn = document.querySelector('#refresh-btn');
                const count = document.querySelector('#service-count');
                const statTotal = document.querySelector('#stat-total');
                const statActive = document.querySelector('#stat-active');
                const statInactive = document.querySelector('#stat-inactive');

                // Delete modal elements
                const deleteModal = document.querySelector('#delete-modal');
                const deleteServiceName = document.querySelector('#delete-service-name');
                const cancelDeleteBtn = document.querySelector('#cancel-delete-btn');
                const confirmDeleteBtn = document.querySelector('#confirm-delete-btn');
                const deleteSpinner = document.querySelector('#delete-spinner');
                let serviceToDeleteId = null;

                let allServices = [];
                let currentVendorId = null;
                let debounceTimer = null;

                // SVG icon dictionary for rendering vector icons
                const iconMap = {
                    'truck': '<path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M15 18H9"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.62l-3.48-4.35A1 1 0 0 0 17.52 8H14"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/>',
                    'banknote': '<rect width="20" height="12" x="2" y="6" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/>',
                    'shield-check': '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/>',
                    'refresh-cw': '<path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/>',
                    'headset': '<path d="M3 11h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-5Zm0 0a9 9 0 1 1 18 0m0 0v5a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3Z"/><path d="M21 16v2a4 4 0 0 1-4 4h-5"/>',
                    'box': '<path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/>',
                    'award': '<circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/>',
                    'clock': '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
                    'zap': '<path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"/>',
                    'credit-card': '<rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>',
                    'gift': '<rect x="3" y="8" width="18" height="4" rx="1"/><path d="M12 8v13"/><path d="M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7"/><path d="M7.5 8a2.5 2.5 0 0 1 0-5A4.8 4.8 0 0 1 12 8a4.8 4.8 0 0 1 4.5-5 2.5 2.5 0 0 1 0 5"/>',
                    'badge-percent': '<path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/><line x1="15" x2="9" y1="9" y2="15"/>',
                    'map-pin': '<path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/>',
                    'lock': '<rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
                    'check-circle': '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/>',
                    'star': '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
                    'thumbs-up': '<path d="M7 10v12"/><path d="M15 5.88 14 10h5.83a2 2 0 0 1 1.92 2.56l-2.33 8A2 2 0 0 1 17.5 22H4a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h2.76a2 2 0 0 0 1.79-1.11L12 2h0a3.13 3.13 0 0 1 3 3.88Z"/>',
                    'heart-handshake': '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>',
                    'sparkles': '<path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/>'
                };

                const renderIcon = (key, className = 'h-5 w-5') => {
                    const cleanKey = (key || 'truck').toLowerCase().trim();
                    const svgPath = iconMap[cleanKey] || iconMap['truck'];
                    return `<svg class="${className}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">${svgPath}</svg>`;
                };

                const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (c) => ({
                    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;',
                }[c]));

                const showBanner = (message, isError = false) => {
                    status.textContent = message;
                    status.className = `mb-4 rounded-xl px-4 py-3 text-sm font-medium ${isError ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-emerald-50 text-emerald-800 border border-emerald-200'}`;
                    status.classList.remove('hidden');
                    setTimeout(() => status.classList.add('hidden'), 5000);
                };

                const updateStats = (services) => {
                    statTotal.textContent = services.length;
                    statActive.textContent = services.filter((s) => s.is_active).length;
                    statInactive.textContent = services.filter((s) => !s.is_active).length;
                };

                const filterAndRender = () => {
                    const query = (search.value || '').toLowerCase().trim();
                    const statusVal = filterStatus.value;

                    let filtered = allServices.filter((service) => {
                        const matchesQuery = !query ||
                            service.name.toLowerCase().includes(query) ||
                            (service.description && service.description.toLowerCase().includes(query)) ||
                            (service.icon && service.icon.toLowerCase().includes(query));

                        const matchesStatus = statusVal === '' || String(service.is_active ? 1 : 0) === statusVal;

                        return matchesQuery && matchesStatus;
                    });

                    count.textContent = `Showing ${filtered.length} of ${allServices.length} services`;

                    if (!filtered.length) {
                        list.innerHTML = '';
                        empty.classList.remove('hidden');
                        return;
                    }

                    empty.classList.add('hidden');
                    list.innerHTML = filtered.map((service) => {
                        const iconSvg = renderIcon(service.icon, 'h-5 w-5 text-[#3d6f6d]');

                        const statusBadge = service.is_active
                            ? `<span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-700 border border-emerald-200">
                                   <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Active
                               </span>`
                            : `<span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-semibold text-gray-500">
                                   <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span> Inactive
                               </span>`;

                        const editHref = `${createUrl}?id=${encodeURIComponent(service.id)}`;

                        const actions = `
                            <div class="flex items-center justify-end gap-2">
                                <a href="${editHref}" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 shadow-sm transition hover:border-[#3d6f6d] hover:bg-gray-50 hover:text-[#3d6f6d]">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    Edit
                                </a>
                                <button type="button" data-delete-id="${service.id}" data-delete-name="${escapeHtml(service.name)}" class="delete-service-btn inline-flex items-center gap-1.5 rounded-lg border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-600 transition hover:border-rose-300 hover:bg-rose-100">
                                    <svg class="h-3.5 w-3.5 text-rose-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                    Delete
                                </button>
                            </div>
                        `;

                        return `
                            <tr class="hover:bg-gray-50/70 transition">
                                <td class="px-4 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#3d6f6d]/10 text-[#3d6f6d]">
                                            ${iconSvg}
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900">${escapeHtml(service.name)}</p>
                                            <p class="text-xs text-gray-400">ID: #${service.id}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3.5">
                                    <code class="rounded bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-700">${escapeHtml(service.icon || 'truck')}</code>
                                </td>
                                <td class="px-4 py-3.5 text-xs text-gray-600 max-w-xs truncate">
                                    ${escapeHtml(service.description || '—')}
                                </td>
                                <td class="px-4 py-3.5">
                                    ${statusBadge}
                                </td>
                                <td class="px-4 py-3.5 text-right">
                                    ${actions}
                                </td>
                            </tr>
                        `;
                    }).join('');

                    // Attach delete listeners
                    document.querySelectorAll('.delete-service-btn').forEach((btn) => {
                        btn.addEventListener('click', () => {
                            serviceToDeleteId = btn.dataset.deleteId;
                            deleteServiceName.textContent = btn.dataset.deleteName;
                            deleteModal.classList.remove('hidden');
                        });
                    });
                };

                const loadServices = async () => {
                    try {
                        const response = await fetch(apiUrl, {
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            }
                        });
                        const result = await response.json();
                        if (result.success && Array.isArray(result.data)) {
                            allServices = result.data;
                            updateStats(allServices);
                            filterAndRender();
                        } else {
                            showBanner(result.message || 'Failed to load services', true);
                        }
                    } catch (err) {
                        console.error('Services load error:', err);
                        showBanner('Could not connect to services API', true);
                    }
                };

                // Cancel delete
                cancelDeleteBtn.addEventListener('click', () => {
                    deleteModal.classList.add('hidden');
                    serviceToDeleteId = null;
                });

                // Confirm delete
                confirmDeleteBtn.addEventListener('click', async () => {
                    if (!serviceToDeleteId) return;
                    deleteSpinner.classList.remove('hidden');
                    confirmDeleteBtn.disabled = true;

                    try {
                        const response = await fetch(`${apiUrl}/${serviceToDeleteId}`, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            }
                        });
                        const result = await response.json();
                        if (result.success) {
                            showBanner('Service deleted successfully');
                            deleteModal.classList.add('hidden');
                            await loadServices();
                        } else {
                            showBanner(result.message || 'Failed to delete service', true);
                        }
                    } catch (err) {
                        showBanner('Error occurred while deleting service', true);
                    } finally {
                        deleteSpinner.classList.add('hidden');
                        confirmDeleteBtn.disabled = false;
                        serviceToDeleteId = null;
                    }
                });

                // Search & Filter event listeners
                search.addEventListener('input', () => {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(filterAndRender, 250);
                });
                filterStatus.addEventListener('change', filterAndRender);
                refreshBtn.addEventListener('click', loadServices);

                // Initialize
                loadServices();
            })();
        </script>
    @endpush
@endsection
