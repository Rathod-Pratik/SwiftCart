@extends('layouts.Dashboard')

@section('content')
    @php($vendorId = request()->query('_id'))

    <x-slot name="title">Vendor Details</x-slot>

    <div class="p-4 sm:p-6">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-[#3d6f6d]">Vendor management</p>
                <h1 class="text-2xl font-bold text-gray-900">Vendor Details</h1>
                <p class="mt-1 text-sm text-gray-600">Review the vendor and business information.</p>
            </div>
            <a href="{{ route('admin.vendor') }}"
               class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                Back to Vendors
            </a>
        </div>

        <div id="vendor-status" class="mb-4 hidden rounded-lg px-4 py-3 text-sm" role="alert"></div>
        <div id="vendor-loading" class="rounded-xl bg-white p-8 text-center text-sm text-gray-500 shadow-sm">Loading vendor details...</div>

        <section id="vendor-details" class="hidden max-w-5xl rounded-xl bg-white p-5 shadow-sm sm:p-8">
            <div class="mb-8 flex flex-col gap-4 border-b border-gray-100 pb-6 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-4">
                    <div id="vendor-avatar" class="flex h-16 w-16 items-center justify-center rounded-full bg-[#b8d8da] text-xl font-bold text-[#3d6f6d]"></div>
                    <div>
                        <h2 id="vendor-name" class="text-2xl font-bold text-gray-900"></h2>
                        <p id="vendor-email" class="mt-1 text-sm text-gray-500"></p>
                    </div>
                </div>
                <span id="vendor-status-badge" class="w-fit rounded-full px-3 py-1 text-xs font-semibold"></span>
            </div>

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                <div>
                    <h3 class="mb-4 text-lg font-bold text-gray-900">Contact Information</h3>
                    <dl class="grid gap-4">
                        <div class="rounded-lg bg-gray-50 p-4"><dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Vendor ID</dt><dd id="vendor-id" class="mt-2 text-sm text-gray-700"></dd></div>
                        <div class="rounded-lg bg-gray-50 p-4"><dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Phone</dt><dd id="vendor-phone" class="mt-2 text-sm text-gray-700"></dd></div>
                        <div class="rounded-lg bg-gray-50 p-4"><dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Address</dt><dd id="vendor-address" class="mt-2 text-sm text-gray-700"></dd></div>
                        <div class="rounded-lg bg-gray-50 p-4"><dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Email verification</dt><dd id="vendor-verification" class="mt-2 text-sm text-gray-700"></dd></div>
                    </dl>
                </div>
                <div>
                    <h3 class="mb-4 text-lg font-bold text-gray-900">Business Information</h3>
                    <dl class="grid gap-4">
                        <div class="rounded-lg bg-gray-50 p-4"><dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Company</dt><dd id="vendor-company" class="mt-2 text-sm text-gray-700"></dd></div>
                        <div class="rounded-lg bg-gray-50 p-4"><dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Account holder</dt><dd id="vendor-account-holder" class="mt-2 text-sm text-gray-700"></dd></div>
                        <div class="rounded-lg bg-gray-50 p-4"><dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Bank</dt><dd id="vendor-bank" class="mt-2 text-sm text-gray-700"></dd></div>
                        <div class="rounded-lg bg-gray-50 p-4"><dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Branch / IFSC</dt><dd id="vendor-branch" class="mt-2 text-sm text-gray-700"></dd></div>
                    </dl>
                </div>
            </div>
            <div class="mt-8 grid grid-cols-1 gap-4 border-t border-gray-100 pt-6 sm:grid-cols-2">
                <div class="rounded-lg bg-gray-50 p-4"><dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Joined</dt><dd id="vendor-created-at" class="mt-2 text-sm text-gray-700"></dd></div>
                <div class="rounded-lg bg-gray-50 p-4"><dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Last updated</dt><dd id="vendor-updated-at" class="mt-2 text-sm text-gray-700"></dd></div>
            </div>
        </section>
    </div>

    @push('scripts')
        <script>
            (() => {
                const vendorId = @json($vendorId);
                const apiUrl = @json(url('/api/vendors'));
                const loading = document.querySelector('#vendor-loading');
                const details = document.querySelector('#vendor-details');
                const status = document.querySelector('#vendor-status');

                const setText = (selector, value) => { document.querySelector(selector).textContent = value ?? 'Not provided'; };
                const formatDate = (value) => value ? new Intl.DateTimeFormat(undefined, { dateStyle: 'medium', timeStyle: 'short' }).format(new Date(value)) : 'Not available';
                const showError = (message) => {
                    status.textContent = message;
                    status.className = 'mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700';
                };

                const loadVendor = async () => {
                    if (!vendorId) {
                        loading.classList.add('hidden');
                        showError('A vendor ID is required in the URL, for example: ?_id=1');
                        return;
                    }
                    try {
                        const response = await fetch(`${apiUrl}/${encodeURIComponent(vendorId)}`, { headers: { Accept: 'application/json' } });
                        const payload = await response.json();
                        if (!response.ok || !payload.vendor) throw new Error(payload.message || 'Unable to load vendor details.');
                        const vendor = payload.vendor;
                        const bank = vendor.bank_detail || {};
                        setText('#vendor-avatar', vendor.name.split(' ').map((part) => part[0]).slice(0, 2).join('').toUpperCase());
                        setText('#vendor-name', vendor.name);
                        setText('#vendor-email', vendor.email);
                        setText('#vendor-id', vendor.id);
                        setText('#vendor-phone', vendor.phone);
                        setText('#vendor-address', vendor.address);
                        setText('#vendor-company', vendor.vendor?.store_name || bank.company_name);
                        setText('#vendor-account-holder', bank.account_holder_name);
                        setText('#vendor-bank', bank.bank_name);
                        setText('#vendor-branch', [bank.branch_name, bank.ifsc_code].filter(Boolean).join(' / ') || null);
                        setText('#vendor-verification', vendor.email_verified_at ? `Verified on ${formatDate(vendor.email_verified_at)}` : 'Not verified');
                        setText('#vendor-created-at', formatDate(vendor.created_at));
                        setText('#vendor-updated-at', formatDate(vendor.updated_at));
                        const badge = document.querySelector('#vendor-status-badge');
                        badge.textContent = vendor.status;
                        badge.className = `w-fit rounded-full px-3 py-1 text-xs font-semibold ${vendor.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'}`;
                        loading.classList.add('hidden');
                        details.classList.remove('hidden');
                    } catch (error) {
                        loading.classList.add('hidden');
                        showError(error.message);
                    }
                };
                loadVendor();
            })();
        </script>
    @endpush
@endsection
