@extends('layouts.Dashboard')

@section('content')
    @php($userId = request()->query('_id'))

    <x-slot name="title">User Details</x-slot>

    <div class="p-4 sm:p-6">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-[#3d6f6d]">User management</p>
                <h1 class="text-2xl font-bold text-gray-900">User Details</h1>
                <p class="mt-1 text-sm text-gray-600">Review the registered user information.</p>
            </div>
            <a href="{{ route('admin.user') }}"
               class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                Back to Users
            </a>
        </div>

        <div id="user-status" class="mb-4 hidden rounded-lg px-4 py-3 text-sm" role="alert"></div>
        <div id="user-loading" class="rounded-xl bg-white p-8 text-center text-sm text-gray-500 shadow-sm">Loading user details...</div>

        <section id="user-details" class="hidden max-w-4xl rounded-xl bg-white p-5 shadow-sm sm:p-8">
            <div class="mb-8 flex flex-col gap-4 border-b border-gray-100 pb-6 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-4">
                    <div id="user-avatar" class="flex h-16 w-16 items-center justify-center rounded-full bg-[#b8d8da] text-xl font-bold text-[#3d6f6d]"></div>
                    <div>
                        <h2 id="user-name" class="text-2xl font-bold text-gray-900"></h2>
                        <p id="user-email" class="mt-1 text-sm text-gray-500"></p>
                    </div>
                </div>
                <span id="user-status-badge" class="w-fit rounded-full px-3 py-1 text-xs font-semibold"></span>
            </div>

            <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
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

                const formatDate = (value) => value
                    ? new Intl.DateTimeFormat(undefined, { dateStyle: 'medium', timeStyle: 'short' }).format(new Date(value))
                    : 'Not available';

                const showError = (message) => {
                    status.textContent = message;
                    status.className = 'mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700';
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
                        const initials = user.name.split(' ').map((part) => part[0]).slice(0, 2).join('').toUpperCase();
                        setText('#user-avatar', initials);
                        setText('#user-name', user.name);
                        setText('#user-email', user.email);
                        setText('#user-id', user.id);
                        setText('#user-role', user.role);
                        setText('#user-phone', user.phone);
                        setText('#user-address', user.address);
                        setText('#user-verification', user.email_verified_at ? `Verified on ${formatDate(user.email_verified_at)}` : 'Not verified');
                        setText('#user-created-at', formatDate(user.created_at));
                        setText('#user-updated-at', formatDate(user.updated_at));

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
