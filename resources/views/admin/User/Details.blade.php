@extends('layouts.Dashboard')

@section('content')
    @php($userId = request()->query('_id'))

    <x-slot name="title">User Details</x-slot>

    <div class="min-h-[calc(100vh-7rem)] p-4 sm:p-6">
        <nav class="mb-6 flex items-center gap-2 text-xs font-semibold text-gray-400" aria-label="Breadcrumb">
            <a href="{{ route('admin.dashboard') }}" class="transition hover:text-gray-600">Dashboard</a>
            <span>/</span>
            <a href="{{ route('admin.user') }}" class="transition hover:text-gray-600">Users</a>
            <span>/</span>
            <span class="text-gray-800">Details</span>
        </nav>

        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">User Details</h1>
                <p class="mt-1 text-sm text-gray-600">Review the registered user information.</p>
            </div>
            <a href="{{ route('admin.user') }}"
               class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                Back to Users
            </a>
        </div>

        <div id="user-status" class="mb-4 hidden rounded-lg px-4 py-3 text-sm" role="alert"></div>
        <div id="user-loading" class="min-h-[calc(100vh-14rem)] rounded-xl bg-white p-8 text-center text-sm text-gray-500 shadow-sm">Loading user details...</div>

        <section id="user-details" class="hidden w-full rounded-xl bg-white p-5 shadow-sm sm:p-8 lg:p-10">
            <div class="mb-10 flex flex-col gap-5 border-b border-gray-100 pb-8 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-4">
                    <div class="relative flex h-16 w-16 shrink-0 items-center justify-center overflow-hidden rounded-full bg-[#b8d8da] text-xl font-bold text-[#3d6f6d]">
                        <span id="user-avatar"></span>
                        <img id="user-avatar-image" class="absolute inset-0 hidden h-full w-full object-cover" alt="User profile image">
                    </div>
                    <div>
                        <h2 id="user-name" class="text-2xl font-bold text-gray-900"></h2>
                        <p id="user-email" class="mt-1 text-sm text-gray-500"></p>
                    </div>
                </div>
                <span id="user-status-badge" class="w-fit rounded-full px-3 py-1 text-xs font-semibold"></span>
            </div>

            <dl class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-lg bg-gray-50 p-4">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">User ID</dt>
                    <dd id="user-id" class="mt-2 text-sm font-medium text-gray-900"></dd>
                </div>
                <div class="rounded-lg bg-gray-50 p-4">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Role</dt>
                    <dd id="user-role" class="mt-2 text-sm capitalize text-gray-700"></dd>
                </div>
                <div class="rounded-lg bg-gray-50 p-4">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Phone</dt>
                    <dd id="user-phone" class="mt-2 text-sm text-gray-700"></dd>
                </div>
                <div class="rounded-lg bg-gray-50 p-4">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Email verification</dt>
                    <dd id="user-verification" class="mt-2 text-sm text-gray-700"></dd>
                </div>
                <div class="rounded-lg bg-teal-50 p-4">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-[#3d6f6d]">Orders</dt>
                    <dd id="user-orders-count" class="mt-2 text-2xl font-bold text-[#3d6f6d]"></dd>
                </div>
                <div class="rounded-lg bg-emerald-50 p-4">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-emerald-700">Reviews</dt>
                    <dd id="user-reviews-count" class="mt-2 text-2xl font-bold text-emerald-700"></dd>
                </div>
                <div class="rounded-lg bg-amber-50 p-4">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-amber-700">Wishlist items</dt>
                    <dd id="user-wishlists-count" class="mt-2 text-2xl font-bold text-amber-700"></dd>
                </div>
                <div class="rounded-lg bg-indigo-50 p-4">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-indigo-700">Cart items</dt>
                    <dd id="user-carts-count" class="mt-2 text-2xl font-bold text-indigo-700"></dd>
                </div>
                <div class="rounded-lg bg-gray-50 p-4 sm:col-span-2">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Address</dt>
                    <dd id="user-address" class="mt-2 text-sm text-gray-700"></dd>
                </div>
                <div class="rounded-lg bg-gray-50 p-4">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Joined</dt>
                    <dd id="user-created-at" class="mt-2 text-sm text-gray-700"></dd>
                </div>
                <div class="rounded-lg bg-gray-50 p-4">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Last updated</dt>
                    <dd id="user-updated-at" class="mt-2 text-sm text-gray-700"></dd>
                </div>
            </dl>

            <div class="mt-10 border-t border-gray-100 pt-8">
                <div class="mb-5 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Order history</h3>
                        <p class="mt-1 text-sm text-gray-500">Orders placed by this user.</p>
                    </div>
                    <span id="user-order-total" class="text-sm font-semibold text-gray-500"></span>
                </div>
                <div class="overflow-x-auto rounded-xl border border-gray-200">
                    <table class="w-full min-w-[760px] text-left text-sm">
                        <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                            <tr>
                                <th class="px-4 py-3 font-semibold">Order</th>
                                <th class="px-4 py-3 font-semibold">Items</th>
                                <th class="px-4 py-3 font-semibold">Total</th>
                                <th class="px-4 py-3 font-semibold">Payment</th>
                                <th class="px-4 py-3 font-semibold">Status</th>
                                <th class="px-4 py-3 font-semibold">Placed</th>
                            </tr>
                        </thead>
                        <tbody id="user-orders-list" class="divide-y divide-gray-100"></tbody>
                    </table>
                </div>
                <p id="user-orders-empty" class="hidden rounded-xl border border-dashed border-gray-300 px-4 py-10 text-center text-sm text-gray-500">
                    This user has not placed any orders.
                </p>
            </div>

            <div class="mt-10 grid grid-cols-1 gap-6 border-t border-gray-100 pt-8 lg:grid-cols-2">
                <div class="rounded-xl border border-gray-200 bg-gray-50 p-5">
                    <h3 class="text-lg font-bold text-gray-900">Account activity</h3>
                    <dl class="mt-5 grid grid-cols-2 gap-4">
                        <div><dt class="text-xs uppercase tracking-wide text-gray-500">Account status</dt><dd id="user-account-status" class="mt-1 text-sm font-semibold text-gray-800"></dd></div>
                        <div><dt class="text-xs uppercase tracking-wide text-gray-500">Last login</dt><dd id="user-last-login" class="mt-1 text-sm text-gray-700"></dd></div>
                        <div><dt class="text-xs uppercase tracking-wide text-gray-500">Created</dt><dd id="user-created-at" class="mt-1 text-sm text-gray-700"></dd></div>
                        <div><dt class="text-xs uppercase tracking-wide text-gray-500">Updated</dt><dd id="user-updated-at" class="mt-1 text-sm text-gray-700"></dd></div>
                    </dl>
                </div>
                <div class="rounded-xl border border-gray-200 bg-gray-50 p-5">
                    <h3 class="text-lg font-bold text-gray-900">Saved address</h3>
                    <p id="user-address-detail" class="mt-5 whitespace-pre-wrap text-sm leading-6 text-gray-700"></p>
                </div>
            </div>
        </section>
    </div>

    @push('scripts')
        <script>
            (() => {
                const userId = @json($userId);
                const apiUrl = @json(url('/api/users'));
                const loading = document.querySelector('#user-loading');
                const details = document.querySelector('#user-details');
                const status = document.querySelector('#user-status');

                const setText = (selector, value) => {
                    document.querySelector(selector).textContent = value ?? 'Not provided';
                };

                const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (character) => ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;',
                }[character]));

                const formatDate = (value) => value
                    ? new Intl.DateTimeFormat(undefined, { dateStyle: 'medium', timeStyle: 'short' }).format(new Date(value))
                    : 'Not available';

                const formatCurrency = (value) => new Intl.NumberFormat('en-IN', {
                    style: 'currency',
                    currency: 'INR',
                    maximumFractionDigits: 2,
                }).format(Number(value ?? 0));

                const renderOrders = (orders) => {
                    const orderList = document.querySelector('#user-orders-list');
                    const emptyState = document.querySelector('#user-orders-empty');
                    const orderTotal = document.querySelector('#user-order-total');
                    const list = Array.isArray(orders) ? orders : [];

                    orderTotal.textContent = `${list.length} order${list.length === 1 ? '' : 's'}`;
                    if (!list.length) {
                        orderList.innerHTML = '';
                        emptyState.classList.remove('hidden');
                        return;
                    }

                    emptyState.classList.add('hidden');
                    orderList.innerHTML = list.map((order) => {
                        const itemCount = (order.items ?? []).reduce((total, item) => total + Number(item.quantity ?? 0), 0);
                        const statusClass = {
                            pending: 'bg-amber-100 text-amber-700',
                            confirmed: 'bg-blue-100 text-blue-700',
                            processing: 'bg-indigo-100 text-indigo-700',
                            shipped: 'bg-purple-100 text-purple-700',
                            delivered: 'bg-emerald-100 text-emerald-700',
                            cancelled: 'bg-red-100 text-red-700',
                            returned: 'bg-gray-100 text-gray-700',
                        }[order.order_status] ?? 'bg-gray-100 text-gray-700';

                        return `
                            <tr class="text-gray-700">
                                <td class="px-4 py-4 font-semibold text-gray-900">${escapeHtml(order.order_number || `#${order.id}`)}</td>
                                <td class="px-4 py-4">${itemCount} item${itemCount === 1 ? '' : 's'}</td>
                                <td class="px-4 py-4 font-semibold text-gray-900">${escapeHtml(formatCurrency(order.total_amount))}</td>
                                <td class="px-4 py-4"><span class="capitalize">${escapeHtml(order.payment_status || 'Not available')}</span><span class="mt-1 block text-xs text-gray-500">${escapeHtml(order.payment_method || '')}</span></td>
                                <td class="px-4 py-4"><span class="rounded-full px-2.5 py-1 text-xs font-semibold ${statusClass}">${escapeHtml(order.order_status || 'Unknown')}</span></td>
                                <td class="px-4 py-4 text-gray-500">${escapeHtml(formatDate(order.created_at))}</td>
                            </tr>
                        `;
                    }).join('');
                };

                const showError = (message) => {
                    status.textContent = message;
                    status.className = 'mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700';
                    status.classList.remove('hidden');
                };

                const loadUser = async () => {
                    if (!userId) {
                        loading.classList.add('hidden');
                        showError('A user ID is required in the URL, for example: ?_id=1');
                        return;
                    }

                    try {
                        const response = await fetch(`${apiUrl}/${encodeURIComponent(userId)}`, {
                            headers: { Accept: 'application/json' },
                        });
                        const payload = await response.json();
                        if (!response.ok || !payload.user) throw new Error(payload.message || 'Unable to load user details.');

                        const user = payload.user;
                        const initials = (user.name || 'User').split(' ').map((part) => part[0]).slice(0, 2).join('').toUpperCase();
                        setText('#user-avatar', initials);
                        setText('#user-name', user.name);
                        setText('#user-email', user.email);
                        setText('#user-id', user.id);
                        setText('#user-role', user.role);
                        setText('#user-phone', user.phone);
                        setText('#user-address', user.address);
                        setText('#user-address-detail', user.address);
                        setText('#user-verification', user.email_verified_at ? `Verified on ${formatDate(user.email_verified_at)}` : 'Not verified');
                        setText('#user-created-at', formatDate(user.created_at));
                        setText('#user-updated-at', formatDate(user.updated_at));
                        setText('#user-account-status', user.status);
                        setText('#user-last-login', formatDate(user.last_login_at));
                        setText('#user-orders-count', user.orders_count);
                        setText('#user-reviews-count', user.reviews_count);
                        setText('#user-wishlists-count', user.wishlists_count);
                        setText('#user-carts-count', user.carts_count);
                        renderOrders(user.orders);

                        const avatarImage = document.querySelector('#user-avatar-image');
                        if (user.image && typeof window.s3Url === 'function') {
                            window.s3Url(user.image).then((url) => {
                                if (!url) return;
                                avatarImage.src = url;
                                avatarImage.classList.remove('hidden');
                            });
                        }

                        const badge = document.querySelector('#user-status-badge');
                        badge.textContent = user.status;
                        badge.className = `w-fit rounded-full px-3 py-1 text-xs font-semibold ${user.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'}`;

                        loading.classList.add('hidden');
                        details.classList.remove('hidden');
                    } catch (error) {
                        loading.classList.add('hidden');
                        showError(error.message);
                    }
                };

                loadUser();
            })();
        </script>
    @endpush
@endsection
