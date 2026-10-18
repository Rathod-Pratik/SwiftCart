@extends('layouts.Dashboard')

@section('content')
    <x-slot name="title">Users</x-slot>

    <div class="p-4 sm:p-6">
        <div class="mb-6">
            <p class="text-sm font-medium text-[#3d6f6d]">User management</p>
            <h1 class="text-2xl font-bold text-gray-900">Users</h1>
            <p class="mt-1 text-sm text-gray-600">Search and view registered users.</p>
        </div>

        <div class="rounded-xl bg-white p-4 shadow-sm sm:p-6">
            <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <label class="block w-full sm:max-w-sm" for="user-search">
                    <span class="sr-only">Search users</span>
                    <input id="user-search"
                           type="search"
                           placeholder="Search by name, email, role, or status..."
                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                </label>
                <p id="user-count" class="text-sm text-gray-500" aria-live="polite"></p>
            </div>

            <div id="user-status" class="mb-4 hidden rounded-lg px-4 py-3 text-sm" role="alert"></div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-left text-sm">
                    <thead class="border-b border-gray-200 text-xs uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-4 py-3 font-semibold">User</th>
                            <th class="px-4 py-3 font-semibold">Email</th>
                            <th class="px-4 py-3 font-semibold">Role</th>
                            <th class="px-4 py-3 font-semibold">Status</th>
                            <th class="px-4 py-3 text-right font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="user-list" class="divide-y divide-gray-100"></tbody>
                </table>
            </div>

            <div id="user-empty" class="hidden py-12 text-center text-sm text-gray-500">No users found.</div>

            <div class="mt-5 flex flex-col gap-3 border-t border-gray-100 pt-4 sm:flex-row sm:items-center sm:justify-between">
                <p id="pagination-summary" class="text-sm text-gray-500"></p>
                <div id="pagination" class="flex flex-wrap gap-2"></div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (() => {
                const apiUrl = @json(url('/api/users'));
                const detailsUrl = @json(route('admin.user.details'));
                const list = document.querySelector('#user-list');
                const emptyState = document.querySelector('#user-empty');
                const pagination = document.querySelector('#pagination');
                const status = document.querySelector('#user-status');
                const count = document.querySelector('#user-count');
                const summary = document.querySelector('#pagination-summary');
                const searchInput = document.querySelector('#user-search');
                let searchTimer;

                const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (character) => ({
                    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;',
                }[character]));

                const showError = (message) => {
                    status.textContent = message;
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
                        button.addEventListener('click', () => loadUsers(page));
                        pagination.append(button);
                    }
                    summary.textContent = meta.total ? `Showing ${meta.from}-${meta.to} of ${meta.total}` : '';
                };

                const renderUsers = (users, meta) => {
                    list.innerHTML = users.map((user) => `
                        <tr class="text-gray-700">
                            <td class="px-4 py-4">
                                <p class="font-semibold text-gray-900">${escapeHtml(user.name)}</p>
                                <p class="text-xs text-gray-500">ID: ${escapeHtml(user.id)}</p>
                            </td>
                            <td class="px-4 py-4">${escapeHtml(user.email)}</td>
                            <td class="px-4 py-4 capitalize">${escapeHtml(user.role)}</td>
                            <td class="px-4 py-4">
                                <span class="rounded-full px-3 py-1 text-xs font-semibold ${user.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'}">
                                    ${escapeHtml(user.status)}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <a href="${detailsUrl}?_id=${encodeURIComponent(user.id)}"
                                   class="inline-flex rounded-lg border border-[#3d6f6d] px-3 py-1.5 text-xs font-semibold text-[#3d6f6d] hover:bg-[#3d6f6d] hover:text-white">
                                    Details
                                </a>
                            </td>
                        </tr>
                    `).join('');
                    emptyState.classList.toggle('hidden', users.length > 0);
                    count.textContent = `${meta.total} user${meta.total === 1 ? '' : 's'}`;
                    renderPagination(meta);
                };

                const loadUsers = async (page = 1) => {
                    status.classList.add('hidden');
                    list.innerHTML = '<tr><td colspan="5" class="px-4 py-12 text-center text-sm text-gray-500">Loading users...</td></tr>';
                    const params = new URLSearchParams({ page, per_page: 10 });
                    if (searchInput.value.trim()) params.set('search', searchInput.value.trim());

                    try {
                        const response = await fetch(`${apiUrl}?${params}`, { headers: { Accept: 'application/json' } });
                        const payload = await response.json();
                        if (!response.ok || !payload.success) throw new Error(payload.message || 'Unable to load users.');
                        renderUsers(payload.data.data, payload.data);
                    } catch (error) {
                        list.innerHTML = '';
                        emptyState.classList.add('hidden');
                        showError(error.message);
                    }
                };

                searchInput.addEventListener('input', () => {
                    clearTimeout(searchTimer);
                    searchTimer = setTimeout(() => loadUsers(1), 300);
                });
                loadUsers();
            })();
        </script>
    @endpush
@endsection