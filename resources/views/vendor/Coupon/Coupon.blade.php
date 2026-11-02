@extends('layouts.Vendor')

@section('content')
    <x-slot name="title">Coupons & Discounts</x-slot>

    <div class="p-4 sm:p-6 lg:p-8">
        <nav class="mb-6 flex items-center gap-2 text-xs font-semibold text-gray-400" aria-label="Breadcrumb">
            <a href="{{ route('vendor.dashboard') }}" class="transition hover:text-gray-600">Dashboard</a>
            <span>/</span>
            <span class="text-gray-800">Coupons</span>
        </nav>
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-2 text-sm font-semibold text-[#3d6f6d]">
                    <x-lucide-ticket-percent class="h-4 w-4" />
                    <span>Promotions & Offers</span>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">Vendor Coupons</h1>
                <p class="mt-1 text-sm text-gray-500">Create discount codes, manage offer validity, and track coupon usage.</p>
            </div>
            <a href="{{ route('vendor.coupon.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-[#3d6f6d] px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#315b59]">
                <x-lucide-plus class="h-4 w-4" />
                <span>Add New Coupon</span>
            </a>
        </div>

        <div class="mb-6 grid grid-cols-2 gap-4 sm:grid-cols-4">
            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total Coupons</span>
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-teal-50 text-[#3d6f6d]"><x-lucide-ticket class="h-4 w-4" /></span>
                </div>
                <p id="stat-total" class="mt-2 text-2xl font-bold text-gray-900">0</p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Active</span>
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600"><x-lucide-check-circle class="h-4 w-4" /></span>
                </div>
                <p id="stat-active" class="mt-2 text-2xl font-bold text-emerald-600">0</p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Inactive</span>
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-500"><x-lucide-pause-circle class="h-4 w-4" /></span>
                </div>
                <p id="stat-inactive" class="mt-2 text-2xl font-bold text-gray-600">0</p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total Redemptions</span>
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-50 text-amber-600"><x-lucide-shopping-bag class="h-4 w-4" /></span>
                </div>
                <p id="stat-redemptions" class="mt-2 text-2xl font-bold text-amber-600">0</p>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6">
            <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex flex-1 items-center gap-3">
                    <label class="relative block w-full max-w-sm" for="coupon-search">
                        <span class="sr-only">Search coupons</span>
                        <x-lucide-search class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" />
                        <input id="coupon-search" type="search" placeholder="Search by coupon code or description..."
                            class="w-full rounded-xl border border-gray-300 py-2.5 pl-10 pr-4 text-sm outline-none transition focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                    </label>
                    <select id="filter-status" class="rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm text-gray-700 outline-none transition focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                        <option value="">All Status</option>
                        <option value="1">Active Only</option>
                        <option value="0">Inactive Only</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <span id="coupon-count" class="text-xs font-semibold text-gray-500"></span>
                    <button type="button" id="refresh-btn" class="rounded-xl border border-gray-200 p-2 text-gray-500 transition hover:bg-gray-100 hover:text-gray-900" title="Refresh">
                        <x-lucide-refresh-cw class="h-4 w-4" />
                    </button>
                </div>
            </div>

            <div id="coupon-status" class="mb-4 hidden rounded-xl px-4 py-3 text-sm font-medium" role="alert"></div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[850px] text-left text-sm">
                    <thead class="border-b border-gray-200 text-xs font-semibold uppercase tracking-wider text-gray-500">
                        <tr>
                            <th class="px-4 py-3">Coupon Code</th>
                            <th class="px-4 py-3">Discount</th>
                            <th class="px-4 py-3">Minimum Order</th>
                            <th class="px-4 py-3">Usage</th>
                            <th class="px-4 py-3">Expiry</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="coupon-list" class="divide-y divide-gray-100"></tbody>
                </table>
            </div>

            <div id="coupon-empty" class="hidden py-14 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#3d6f6d]/10 text-[#3d6f6d]">
                    <x-lucide-ticket-percent class="h-7 w-7" />
                </div>
                <h3 class="mt-3 text-base font-bold text-gray-900">No Coupons Found</h3>
                <p class="mt-1 text-xs text-gray-500">Create your first discount coupon to attract more customers.</p>
                <a href="{{ route('vendor.coupon.create') }}" class="mt-4 inline-flex items-center gap-2 rounded-xl bg-[#3d6f6d] px-4 py-2 text-xs font-semibold text-white transition hover:bg-[#315b59]">
                    <x-lucide-plus class="h-3.5 w-3.5" />
                    <span>Create Coupon</span>
                </a>
            </div>
        </div>
    </div>

    <div id="delete-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4 backdrop-blur-sm">
        <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-rose-50 text-rose-600"><x-lucide-alert-triangle class="h-6 w-6" /></div>
                <div>
                    <h3 class="text-base font-bold text-gray-900">Delete Coupon</h3>
                    <p class="text-xs text-gray-500">Are you sure you want to delete this coupon?</p>
                </div>
            </div>
            <p class="mt-3 text-sm text-gray-600">Coupon "<span id="delete-coupon-code" class="font-bold text-gray-900"></span>" will be removed.</p>
            <div class="mt-6 flex items-center justify-end gap-3">
                <button type="button" id="cancel-delete-btn" class="rounded-xl border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">Cancel</button>
                <button type="button" id="confirm-delete-btn" class="inline-flex items-center gap-2 rounded-xl bg-rose-600 px-5 py-2 text-sm font-semibold text-white transition hover:bg-rose-700">
                    <span id="delete-spinner" class="hidden h-4 w-4 animate-spin rounded-full border-2 border-white border-t-transparent"></span>
                    <span>Delete</span>
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (() => {
                const apiUrl = @json(url('/api/vendor/coupons'));
                const createUrl = @json(route('vendor.coupon.create'));
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
                const list = document.querySelector('#coupon-list');
                const empty = document.querySelector('#coupon-empty');
                const status = document.querySelector('#coupon-status');
                const search = document.querySelector('#coupon-search');
                const filterStatus = document.querySelector('#filter-status');
                const refreshButton = document.querySelector('#refresh-btn');
                const count = document.querySelector('#coupon-count');
                const statTotal = document.querySelector('#stat-total');
                const statActive = document.querySelector('#stat-active');
                const statInactive = document.querySelector('#stat-inactive');
                const statRedemptions = document.querySelector('#stat-redemptions');
                const deleteModal = document.querySelector('#delete-modal');
                const deleteCode = document.querySelector('#delete-coupon-code');
                const cancelDelete = document.querySelector('#cancel-delete-btn');
                const confirmDelete = document.querySelector('#confirm-delete-btn');
                const deleteSpinner = document.querySelector('#delete-spinner');

                let coupons = [];
                let deleteId = null;
                let debounceTimer = null;

                const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (character) => ({
                    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
                }[character]));

                const showBanner = (message, isError = false) => {
                    status.textContent = message;
                    status.className = `mb-4 rounded-xl border px-4 py-3 text-sm font-medium ${isError ? 'border-rose-200 bg-rose-50 text-rose-700' : 'border-emerald-200 bg-emerald-50 text-emerald-800'}`;
                    status.classList.remove('hidden');
                    setTimeout(() => status.classList.add('hidden'), 5000);
                };

                const formatCurrency = (value) => `₹${Number(value || 0).toLocaleString('en-IN', { maximumFractionDigits: 2 })}`;
                const formatDate = (value) => {
                    if (!value) return 'No expiry';
                    const date = new Date(value);
                    return Number.isNaN(date.getTime()) ? '—' : date.toLocaleString('en-IN', { dateStyle: 'medium', timeStyle: 'short' });
                };

                const updateStats = () => {
                    statTotal.textContent = coupons.length;
                    statActive.textContent = coupons.filter((coupon) => coupon.is_active).length;
                    statInactive.textContent = coupons.filter((coupon) => !coupon.is_active).length;
                    statRedemptions.textContent = coupons.reduce((total, coupon) => total + Number(coupon.used_count || 0), 0);
                };

                const render = () => {
                    const query = search.value.trim().toLowerCase();
                    const statusValue = filterStatus.value;

                    const filtered = coupons.filter((coupon) => {
                        const matchesQuery = !query ||
                            String(coupon.code || '').toLowerCase().includes(query) ||
                            String(coupon.description || '').toLowerCase().includes(query);
                        const matchesStatus = statusValue === '' || String(coupon.is_active ? 1 : 0) === statusValue;
                        return matchesQuery && matchesStatus;
                    });

                    count.textContent = `Showing ${filtered.length} of ${coupons.length} coupons`;

                    if (!filtered.length) {
                        list.innerHTML = '';
                        empty.classList.remove('hidden');
                        return;
                    }

                    empty.classList.add('hidden');
                    list.innerHTML = filtered.map((coupon) => {
                        const discount = coupon.discount_type === 'percentage'
                            ? `${Number(coupon.discount_value)}% OFF`
                            : `${formatCurrency(coupon.discount_value)} OFF`;
                        const usage = coupon.usage_limit
                            ? `${Number(coupon.used_count || 0)} / ${Number(coupon.usage_limit)}`
                            : `${Number(coupon.used_count || 0)} / Unlimited`;
                        const statusBadge = coupon.is_active
                            ? '<span class="inline-flex items-center gap-1 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-700"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Active</span>'
                            : '<span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-semibold text-gray-500"><span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>Inactive</span>';
                        const editHref = `${createUrl}?id=${encodeURIComponent(coupon.id)}`;

                        return `
                            <tr class="transition hover:bg-gray-50/70">
                                <td class="px-4 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#3d6f6d]/10 text-[#3d6f6d]">
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9a3 3 0 0 0 0 6v3a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-3a3 3 0 0 0 0-6V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/><path d="M13 5v2m0 4v2m0 4v2"/></svg>
                                        </div>
                                        <div>
                                            <p class="font-bold uppercase text-gray-900">${escapeHtml(coupon.code)}</p>
                                            <p class="max-w-xs truncate text-xs text-gray-400">${escapeHtml(coupon.description || 'No description')}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3.5"><span class="font-semibold text-gray-900">${discount}</span></td>
                                <td class="px-4 py-3.5 text-xs text-gray-600">${coupon.minimum_order_amount ? formatCurrency(coupon.minimum_order_amount) : 'No minimum'}</td>
                                <td class="px-4 py-3.5 text-xs text-gray-600">${usage}</td>
                                <td class="px-4 py-3.5 text-xs text-gray-600">${formatDate(coupon.expires_at)}</td>
                                <td class="px-4 py-3.5">${statusBadge}</td>
                                <td class="px-4 py-3.5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="${editHref}" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 shadow-sm transition hover:border-[#3d6f6d] hover:bg-gray-50 hover:text-[#3d6f6d]">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                            Edit
                                        </a>
                                        <button type="button" data-delete-id="${escapeHtml(coupon.id)}" data-delete-code="${escapeHtml(coupon.code)}" class="delete-coupon-btn inline-flex items-center gap-1.5 rounded-lg border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-600 transition hover:border-rose-300 hover:bg-rose-100">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `;
                    }).join('');

                    document.querySelectorAll('.delete-coupon-btn').forEach((button) => {
                        button.addEventListener('click', () => {
                            deleteId = button.dataset.deleteId;
                            deleteCode.textContent = button.dataset.deleteCode;
                            deleteModal.classList.remove('hidden');
                            deleteModal.classList.add('flex');
                        });
                    });
                };

                const loadCoupons = async () => {
                    try {
                        const response = await fetch(apiUrl, {
                            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
                        });
                        const result = await response.json();
                        if (!response.ok || !result.success || !Array.isArray(result.data)) {
                            showBanner(result.message || 'Failed to load coupons.', true);
                            return;
                        }
                        coupons = result.data;
                        updateStats();
                        render();
                    } catch (error) {
                        showBanner('Could not connect to coupons API.', true);
                    }
                };

                cancelDelete.addEventListener('click', () => {
                    deleteModal.classList.add('hidden');
                    deleteModal.classList.remove('flex');
                    deleteId = null;
                });

                confirmDelete.addEventListener('click', async () => {
                    if (!deleteId) return;
                    deleteSpinner.classList.remove('hidden');
                    confirmDelete.disabled = true;
                    try {
                        const response = await fetch(`${apiUrl}/${deleteId}`, {
                            method: 'DELETE',
                            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
                        });
                        const result = await response.json();
                        if (!response.ok || !result.success) {
                            showBanner(result.message || 'Failed to delete coupon.', true);
                            return;
                        }
                        showBanner('Coupon deleted successfully.');
                        deleteModal.classList.add('hidden');
                        deleteModal.classList.remove('flex');
                        await loadCoupons();
                    } catch (error) {
                        showBanner('An error occurred while deleting the coupon.', true);
                    } finally {
                        deleteSpinner.classList.add('hidden');
                        confirmDelete.disabled = false;
                        deleteId = null;
                    }
                });

                search.addEventListener('input', () => {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(render, 250);
                });
                filterStatus.addEventListener('change', render);
                refreshButton.addEventListener('click', loadCoupons);

                loadCoupons();
            })();
        </script>
    @endpush
@endsection
