@extends('layouts.Vendor')

@section('content')
    <x-slot name="title">Queries</x-slot>

    <div class="p-4 sm:p-6">
        <div class="mb-6">
            <p class="text-sm font-medium text-[#3d6f6d]">Customer support</p>
            <h1 class="text-2xl font-bold text-gray-900">Queries</h1>
            <p class="mt-1 text-sm text-gray-600">Review customer conversations about your products.</p>
        </div>

        <div class="rounded-xl bg-white p-4 shadow-sm sm:p-6">
            <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <label class="block w-full sm:max-w-sm" for="query-search">
                    <span class="sr-only">Search queries</span>
                    <input id="query-search" type="search" placeholder="Search customer, product, or message..." class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                </label>
                <p id="query-count" class="text-sm text-gray-500"></p>
            </div>
            <div id="query-status" class="mb-4 hidden rounded-lg px-4 py-3 text-sm" role="alert"></div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-left text-sm">
                    <thead class="border-b border-gray-200 text-xs uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Customer</th>
                            <th class="px-4 py-3 font-semibold">Product</th>
                            <th class="px-4 py-3 font-semibold">Latest message</th>
                            <th class="px-4 py-3 font-semibold">Last activity</th>
                            <th class="px-4 py-3 text-right font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="query-list" class="divide-y divide-gray-100"></tbody>
                </table>
            </div>
            <div id="query-empty" class="hidden py-12 text-center text-sm text-gray-500">No customer queries found.</div>
            <div class="mt-5 flex flex-col gap-3 border-t border-gray-100 pt-4 sm:flex-row sm:items-center sm:justify-between">
                <p id="pagination-summary" class="text-sm text-gray-500"></p>
                <div id="pagination" class="flex flex-wrap gap-2"></div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (() => {
                const apiUrl = @json(url('/api/vendor/queries'));
                const detailsUrl = @json(route('vendor.query.details'));
                const list = document.querySelector('#query-list');
                const empty = document.querySelector('#query-empty');
                const status = document.querySelector('#query-status');
                const search = document.querySelector('#query-search');
                const count = document.querySelector('#query-count');
                const pagination = document.querySelector('#pagination');
                const summary = document.querySelector('#pagination-summary');
                let timer;

                const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (character) => ({
                    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;',
                }[character]));
                const formatDate = (value) => value ? new Intl.DateTimeFormat(undefined, { dateStyle: 'medium', timeStyle: 'short' }).format(new Date(value)) : 'No activity';
                const showError = (text) => {
                    status.textContent = text;
                    status.className = 'mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700';
                };
                const renderPagination = (meta) => {
                    pagination.innerHTML = '';
                    for (let page = 1; page <= meta.last_page; page += 1) {
                        const button = document.createElement('button');
                        button.type = 'button';
                        button.textContent = page;
                        button.disabled = page === meta.current_page;
                        button.className = `rounded-lg px-3 py-2 text-sm font-semibold ${button.disabled ? 'bg-[#3d6f6d] text-white' : 'border border-gray-300 text-gray-700 hover:bg-gray-50'}`;
                        button.addEventListener('click', () => loadQueries(page));
                        pagination.append(button);
                    }
                    summary.textContent = meta.total ? `Showing ${meta.from}-${meta.to} of ${meta.total}` : '';
                };
                const renderQueries = (queries, meta) => {
                    list.innerHTML = queries.map((query) => `
                        <tr class="text-gray-700">
                            <td class="px-4 py-4"><p class="font-semibold text-gray-900">${escapeHtml(query.customer?.name)}</p><p class="text-xs text-gray-500">${escapeHtml(query.customer?.email)}</p></td>
                            <td class="px-4 py-4">${escapeHtml(query.product?.name || 'General query')}</td>
                            <td class="max-w-xs px-4 py-4"><p class="truncate">${escapeHtml(query.latest_message?.body || 'No message yet')}</p>${query.unread_messages_count ? `<span class="mt-1 inline-flex rounded-full bg-[#e6f7f5] px-2 py-0.5 text-xs font-semibold text-[#2f7770]">${query.unread_messages_count} unread</span>` : ''}</td>
                            <td class="px-4 py-4 text-sm text-gray-500">${escapeHtml(formatDate(query.last_message_at || query.updated_at))}</td>
                            <td class="px-4 py-4 text-right"><a href="${detailsUrl}?_id=${query.id}" class="inline-flex rounded-lg border border-[#3d6f6d] px-3 py-1.5 text-xs font-semibold text-[#3d6f6d] hover:bg-[#3d6f6d] hover:text-white">Details</a></td>
                        </tr>
                    `).join('');
                    empty.classList.toggle('hidden', queries.length > 0);
                    count.textContent = `${meta.total} quer${meta.total === 1 ? 'y' : 'ies'}`;
                    renderPagination(meta);
                };
                const loadQueries = async (page = 1) => {
                    status.classList.add('hidden');
                    list.innerHTML = '<tr><td colspan="5" class="px-4 py-12 text-center text-sm text-gray-500">Loading queries...</td></tr>';
                    const params = new URLSearchParams({ page, per_page: 10 });
                    if (search.value.trim()) params.set('search', search.value.trim());
                    try {
                        const response = await fetch(`${apiUrl}?${params}`, { headers: { Accept: 'application/json' } });
                        const payload = await response.json();
                        if (!response.ok || !payload.success) throw new Error(payload.message || 'Unable to load queries.');
                        renderQueries(payload.data.data, payload.data);
                    } catch (error) {
                        list.innerHTML = '';
                        empty.classList.add('hidden');
                        showError(error.message);
                    }
                };
                search.addEventListener('input', () => {
                    clearTimeout(timer);
                    timer = setTimeout(() => loadQueries(1), 300);
                });
                loadQueries();
            })();
        </script>
    @endpush
@endsection