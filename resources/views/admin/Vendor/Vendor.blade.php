@extends('layouts.Dashboard')

@section('content')
    <x-slot name="title">Vendors</x-slot>

    <div class="p-4 sm:p-6">
        <div class="mb-6">
            <p class="text-sm font-medium text-[#3d6f6d]">Marketplace management</p>
            <h1 class="text-2xl font-bold text-gray-900">Vendors</h1>
            <p class="mt-1 text-sm text-gray-600">Review vendor accounts and manage marketplace partners.</p>
        </div>

        <div class="mb-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
            @foreach ([['Total Vendors', 'vendor-stat-total', 'store', 'bg-teal-50', 'text-[#3d6f6d]', 'text-[#3d6f6d]'], ['Approved', 'vendor-stat-approved', 'circle-check', 'bg-emerald-50', 'text-emerald-600', 'text-emerald-600'], ['Pending', 'vendor-stat-pending', 'clock', 'bg-amber-50', 'text-amber-600', 'text-amber-600'], ['Rejected', 'vendor-stat-rejected', 'x', 'bg-rose-50', 'text-rose-600', 'text-rose-600']] as [$label, $id, $icon, $iconBackground, $iconColor, $valueColor])
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

        <div class=" rounded-xl bg-white p-4 shadow-sm sm:p-6">
            <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <label class="relative block w-full sm:max-w-sm" for="vendor-search">
                    <span class="sr-only">Search vendors</span>
                    <input id="vendor-search"
                           type="search"
                           placeholder="Search by name, email, or company..."
                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                </label>
                   <a href="{{ route('admin.vendor.request') }}"
                        class="inline-flex items-center justify-center rounded-lg bg-[#3d6f6d] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#315b59]">
                        Request
                    </a>
               
            </div>

            <div id="vendor-status" class="mb-4 hidden rounded-lg px-4 py-3 text-sm" role="alert"></div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[780px] text-left text-sm">
                    <thead class="border-b border-gray-200 text-xs uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Vendor</th>
                            <th class="px-4 py-3 font-semibold">Company</th>
                            <th class="px-4 py-3 font-semibold">Email</th>
                            <th class="px-4 py-3 font-semibold">Status</th>
                            <th class="px-4 py-3 text-right font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="vendor-list" class="divide-y divide-gray-100">
                        <tr id="vendor-loading">
                            <td colspan="5" class="px-4 py-12 text-center">
                                <x-Loading class="!static !inset-auto !bg-transparent py-2" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div id="vendor-empty" class="hidden py-12 text-center text-sm text-gray-500">No vendors found.</div>

            <div class="mt-5 flex flex-col gap-3 border-t border-gray-100 pt-4 sm:flex-row sm:items-center sm:justify-between">
                <p id="pagination-summary" class="text-sm text-gray-500"></p>
                <div id="pagination" class="flex flex-wrap gap-2"></div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (() => {
                const apiUrl = @json(url('/api/vendors'));
                const detailsUrl = @json(route('admin.vendor.details'));
                const list = document.querySelector('#vendor-list');
                const emptyState = document.querySelector('#vendor-empty');
                const pagination = document.querySelector('#pagination');
                const status = document.querySelector('#vendor-status');
                const summary = document.querySelector('#pagination-summary');
                const searchInput = document.querySelector('#vendor-search');
                const loadingRow = document.getElementById('vendor-loading');
                let searchTimer;

                const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (character) => ({
                    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;',
                }[character]));

                const showError = (message) => {
                    status.textContent = message;
                    status.className = 'mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700';
                };

                fetch(@json(url('/api/admin/dashboard/stats')), { headers: { Accept: 'application/json' } })
                    .then((response) => {
                        if (!response.ok) throw new Error('Unable to load vendor statistics.');
                        return response.json();
                    })
                    .then((payload) => {
                        if (!payload.stats) throw new Error(payload.message || 'Unable to load vendor statistics.');
                        document.querySelector('#vendor-stat-total').textContent = payload.stats.total_vendors;
                        document.querySelector('#vendor-stat-approved').textContent = payload.stats.approved_vendors;
                        document.querySelector('#vendor-stat-pending').textContent = payload.stats.pending_vendors;
                        document.querySelector('#vendor-stat-rejected').textContent = payload.stats.rejected_vendors;
                    })
                    .catch((error) => showError(error.message));

                const clearDataRows = () => {
                    list.querySelectorAll('tr:not(#vendor-loading)').forEach((row) => row.remove());
                };

                const renderPagination = (meta) => {
                    pagination.innerHTML = '';
                    for (let page = 1; page <= meta.last_page; page += 1) {
                        const button = document.createElement('button');
                        button.type = 'button';
                        button.textContent = page;
                        button.disabled = page === meta.current_page;
                        button.className = `rounded-lg px-3 py-2 text-sm font-semibold ${button.disabled ? 'bg-[#3d6f6d] text-white' : 'border border-gray-300 text-gray-700 hover:bg-gray-50 transition'}`;
                        button.addEventListener('click', () => loadVendors(page));
                        pagination.append(button);
                    }
                    summary.textContent = meta.total ? `Showing ${meta.from}-${meta.to} of ${meta.total}` : '';
                };

                const renderVendors = (vendors, meta) => {
                    clearDataRows();
                    loadingRow.classList.add('hidden');

                    if (!vendors.length) {
                        emptyState.classList.remove('hidden');
                        renderPagination(meta);
                        return;
                    }

                    emptyState.classList.add('hidden');

                    const rowsHtml = vendors.map((vendor) => `
                        <tr class="text-gray-700">
                            <td class="px-4 py-4">
                                <p class="font-semibold text-gray-900">${escapeHtml(vendor.name)}</p>
                                <p class="text-xs text-gray-500">ID: ${escapeHtml(vendor.id)}</p>
                            </td>
                            <td class="px-4 py-4">${escapeHtml(vendor.vendor?.store_name || vendor.bank_detail?.company_name || 'Not provided')}</td>
                            <td class="px-4 py-4">${escapeHtml(vendor.email)}</td>
                            <td class="px-4 py-4">
                                <span class="rounded-full px-3 py-1 text-xs font-semibold ${vendor.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'}">
                                    ${escapeHtml(vendor.status)}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <a href="${detailsUrl}?_id=${encodeURIComponent(vendor.id)}"
                                   class="inline-flex rounded-lg border border-[#3d6f6d] px-3 py-1.5 text-xs font-semibold text-[#3d6f6d] hover:bg-[#3d6f6d] hover:text-white transition">
                                    Details
                                </a>
                            </td>
                        </tr>
                    `).join('');

                    list.insertAdjacentHTML('beforeend', rowsHtml);
                    renderPagination(meta);
                };

                const loadVendors = async (page = 1) => {
                    status.classList.add('hidden');
                    emptyState.classList.add('hidden');
                    clearDataRows();
                    loadingRow.classList.remove('hidden');

                    const params = new URLSearchParams({
                        page: String(page),
                        per_page: '10',
                    });
                    if (searchInput.value.trim()) params.set('search', searchInput.value.trim());

                    try {
                        const response = await fetch(`${apiUrl}?${params}`, { headers: { Accept: 'application/json' } });
                        const payload = await response.json();
                        if (!response.ok || !payload.success) throw new Error(payload.message || 'Unable to load vendors.');
                        renderVendors(payload.data.data, payload.data);
                    } catch (error) {
                        clearDataRows();
                        loadingRow.classList.add('hidden');
                        emptyState.classList.add('hidden');
                        showError(error.message);
                    }
                };

                searchInput.addEventListener('input', () => {
                    clearTimeout(searchTimer);
                    searchTimer = setTimeout(() => loadVendors(1), 300);
                });
                loadVendors();
            })();
        </script>
    @endpush
@endsection
