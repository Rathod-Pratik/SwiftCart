@extends('layouts.Dashboard')
@section('content')
    <x-slot name='title'>Vendor Request</x-slot>
    <div class="p-4 sm:p-6">
        <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Vendor Request
                </h1>
                <p class="mt-1 text-sm text-gray-600">
                    View all vendor applications and their status.
                </p>
            </div>
            <a href="{{ route('admin.vendor') }}"
                class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                Back
            </a>
        </div>

        <div class=" rounded-xl bg-white p-4 shadow-sm sm:p-6">
            <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <label class="relative block w-full sm:max-w-sm" for="vendor-search">
                    <span class="sr-only">Search vendors</span>
                    <input id="vendor-search" type="search" placeholder="Search by name, email, or company..."
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                </label>
                <p id="vendor-request-count" class="text-sm font-semibold text-gray-700"></p>
            </div>
            <div class='overflow-x-auto'>
                <table class="w-full min-w-[850px] text-left text-sm">
                    <thead class="border-b border-gray-200 text-xs uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Vendor Name</th>
                            <th class="px-4 py-3 font-semibold">Email</th>
                            <th class="px-4 py-3 font-semibold">Phone</th>
                            <th class="px-4 py-3 font-semibold">KYC / ID Proof</th>
                            <th class="px-4 py-3 font-semibold">Pickup Location</th>
                            <th class="px-4 py-3 font-semibold">Status</th>
                            <th class="px-4 py-3 font-semibold">Action</th>
                        </tr>
                    </thead>
                    <tbody id="vendor-list" class="divide-y divide-gray-100">
                        <tr id="vendor-loading">
                            <td colspan="7" class="px-4 py-12 text-center">
                                <x-Loading class="!static !inset-auto !bg-transparent py-2" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="vendor-empty" class="hidden py-12 text-center text-sm text-gray-500">
                No vendor requests found.
            </div>
            <div
                class="mt-4 flex flex-col gap-3 border-t border-gray-100 pt-4 sm:flex-row sm:items-center sm:justify-between">
                <p id="pagination-summary" class="text-sm text-gray-500"></p>
                <div id="pagination" class="flex gap-2"></div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            const apiUrl = @json(url('api/vendor-applications'));
            const detailsUrl = @json(route('admin.vendor.request.details'));
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const list = document.getElementById('vendor-list');
            const emptyState = document.getElementById('vendor-empty');
            const pagination = document.getElementById('pagination');
            const searchInput = document.getElementById('vendor-search');
            const paginationSummary = document.getElementById('pagination-summary');
            const loadingRow = document.getElementById('vendor-loading');
            const requestCount = document.getElementById('vendor-request-count');

            let currentPage = 1;
            let searchTimer;

            const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (character) => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;',
            }[character]));

            const clearDataRows = () => {
                list.querySelectorAll('tr:not(#vendor-loading)').forEach((row) => row.remove());
            };

            const renderVendorRequests = (vendors) => {
                clearDataRows();
                loadingRow.classList.add('hidden');

                if (!vendors.length) {
                    emptyState.classList.remove('hidden');
                    return;
                }

                emptyState.classList.add('hidden');

                vendors.forEach((vendor) => {
                    const row = document.createElement('tr');

                    const idProofHtml = vendor.id_document_type
                        ? `<div>
                             <span class="inline-flex items-center gap-1 font-medium text-gray-900">${escapeHtml(vendor.id_document_type)}</span>
                             <div class="font-mono text-xs text-gray-500">${escapeHtml(vendor.id_number || 'ID pending')}</div>
                           </div>`
                        : `<span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-0.5 text-xs text-gray-500">Not provided</span>`;

                    const locationParts = [vendor.pickup_city, vendor.pickup_state].filter(Boolean);
                    const locationHtml = locationParts.length > 0
                        ? `<div>
                             <span class="font-medium text-gray-900">${escapeHtml(locationParts.join(', '))}</span>
                             <div class="text-xs text-gray-500 font-mono">${escapeHtml(vendor.pickup_pincode ? 'PIN: ' + vendor.pickup_pincode : (vendor.pickup_address || ''))}</div>
                           </div>`
                        : (vendor.business_address
                            ? `<div class="text-xs text-gray-700 truncate max-w-[180px]" title="${escapeHtml(vendor.business_address)}">${escapeHtml(vendor.business_address)}</div>`
                            : `<span class="text-xs text-gray-400">Same as office</span>`);

                    row.innerHTML = `
                        <td class="px-4 py-4">
                            <div class="font-semibold text-gray-900">${escapeHtml(vendor.store_name)}</div>
                            <div class="text-xs text-gray-500 capitalize">${escapeHtml(vendor.business_type || 'individual')}</div>
                        </td>
                        <td class="px-4 py-4 text-gray-700">${escapeHtml(vendor.user?.email || 'N/A')}</td>
                        <td class="px-4 py-4 text-gray-700 font-mono text-xs">${escapeHtml(vendor.phone || 'N/A')}</td>
                        <td class="px-4 py-4">${idProofHtml}</td>
                        <td class="px-4 py-4">${locationHtml}</td>
                        <td class="px-4 py-4">
                            <span class="rounded-full px-2.5 py-1 text-xs font-semibold ${
                                vendor.status === 'approved' ? 'bg-green-100 text-green-700' :
                                vendor.status === 'rejected' ? 'bg-red-100 text-red-700' :
                                'bg-yellow-100 text-yellow-700'
                            }">
                                ${escapeHtml(vendor.status)}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <a href="${detailsUrl}?_id=${encodeURIComponent(vendor.id)}"
                               class="inline-flex rounded-lg border border-[#3d6f6d] px-3 py-1.5 text-xs font-semibold text-[#3d6f6d] hover:bg-[#3d6f6d] hover:text-white transition shadow-xs">
                                Details
                            </a>
                        </td>
                    `;

                    list.appendChild(row);
                });
            };

            const renderPagination = (pageData) => {
                pagination.innerHTML = '';

                paginationSummary.textContent =
                    `Showing ${pageData.from ?? 0}–${pageData.to ?? 0} of ${pageData.total} requests`;

                if (requestCount) {
                    requestCount.textContent = `${pageData.total} Request${pageData.total === 1 ? '' : 's'}`;
                }

                if (pageData.last_page <= 1) return;

                const createButton = (label, page, disabled = false) => {
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.textContent = label;
                    button.disabled = disabled;
                    button.className =
                        'rounded-lg border border-gray-300 px-3 py-1.5 text-sm disabled:opacity-50 hover:bg-gray-50 transition';

                    button.addEventListener('click', () => loadVendorRequest(page));
                    pagination.appendChild(button);
                };

                createButton('Previous', pageData.current_page - 1, !pageData.prev_page_url);
                createButton('Next', pageData.current_page + 1, !pageData.next_page_url);
            };

            const loadVendorRequest = async (page = 1) => {
                emptyState.classList.add('hidden');
                clearDataRows();
                loadingRow.classList.remove('hidden');

                const params = new URLSearchParams({
                    page: String(page),
                    per_page: '10',
                });

                if (searchInput.value.trim()) {
                    params.append('search', searchInput.value.trim());
                }

                try {
                    const response = await fetch(`${apiUrl}?${params.toString()}`, {
                        headers: {
                            Accept: 'application/json',
                            ...(csrfToken ? {
                                'X-CSRF-TOKEN': csrfToken
                            } : {}),
                        },
                    });

                    if (!response.ok) {
                        throw new Error(`Request failed with status ${response.status}`);
                    }

                    const result = await response.json();
                    const pageData = result.data;

                    if (!Array.isArray(pageData?.data)) {
                        throw new Error('Unexpected API response structure');
                    }

                    currentPage = pageData.current_page;
                    renderVendorRequests(pageData.data);
                    renderPagination(pageData);
                } catch (error) {
                    console.error('Error fetching vendor requests:', error);
                    loadingRow.classList.add('hidden');
                    clearDataRows();
                    const errorRow = document.createElement('tr');
                    errorRow.innerHTML = `
                        <td colspan="7" class="px-4 py-12 text-center text-red-600">
                            Unable to load vendor requests. Check the console for details.
                        </td>
                    `;
                    list.appendChild(errorRow);
                }
            };

            searchInput.addEventListener('input', () => {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(() => loadVendorRequest(1), 300);
            });

            loadVendorRequest(currentPage);
        </script>
    @endpush
@endsection
