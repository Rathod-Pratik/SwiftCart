@extends('layouts.Dashboard')

@section('content')
    @php($vendorId = request()->query('_id'))

    <x-slot name="title">Vendor Details</x-slot>

    <div class="p-4 sm:p-6">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Vendor Details</h1>
            </div>
            <a href="{{ route('admin.vendor') }}"
               class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                Back
            </a>
        </div>
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

            <div id="vendor-status" class="hidden mb-4 rounded-lg px-4 py-3 text-sm"></div>

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

            {{-- 3. Identity Verification & KYC --}}
            <div class="mt-8 border-t border-gray-100 pt-6">
                <div class="mb-4 flex items-center gap-2">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-purple-50 text-purple-600">
                        <x-lucide-shield-check class="h-4 w-4" />
                    </span>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">3. Identity Verification & KYC</h3>
                        <p class="text-xs text-gray-500">Compliance & Fraud Prevention</p>
                    </div>
                </div>

                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="rounded-lg bg-gray-50 p-4">
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Identity Document Type</dt>
                        <dd id="vendor-id-type" class="mt-2 text-sm font-semibold text-gray-900"></dd>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">ID / PAN Number</dt>
                        <dd id="vendor-id-number" class="mt-2 font-mono text-sm font-bold text-gray-900"></dd>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-4 flex flex-col justify-between">
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">ID Document Upload</dt>
                            <dd id="vendor-id-doc-text" class="mt-2 text-xs text-gray-600 truncate">No file uploaded</dd>
                        </div>
                        <div class="mt-3">
                            <a id="btn-vendor-id-doc" href="#" target="_blank"
                               class="hidden inline-flex items-center gap-1.5 rounded-lg bg-[#3d6f6d] px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-[#2e5554]">
                                <x-lucide-download class="h-3.5 w-3.5" />
                                <span>Download ID Proof</span>
                            </a>
                            <span id="badge-no-id-doc" class="text-xs text-gray-400">None uploaded</span>
                        </div>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-4 flex flex-col justify-between">
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">GST / Business Certificate</dt>
                            <dd id="vendor-biz-cert-text" class="mt-2 text-xs text-gray-600 truncate">No file uploaded</dd>
                        </div>
                        <div class="mt-3">
                            <a id="btn-vendor-biz-cert" href="#" target="_blank"
                               class="hidden inline-flex items-center gap-1.5 rounded-lg bg-[#3d6f6d] px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-[#2e5554]">
                                <x-lucide-download class="h-3.5 w-3.5" />
                                <span>Download Certificate</span>
                            </a>
                            <span id="badge-no-biz-cert" class="text-xs text-gray-400">None uploaded</span>
                        </div>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Business Registration # (BRN)</dt>
                        <dd id="vendor-brn" class="mt-2 font-mono text-sm font-medium text-gray-700"></dd>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Tax ID (TIN / GSTIN)</dt>
                        <dd id="vendor-tin" class="mt-2 font-mono text-sm font-medium text-gray-700"></dd>
                    </div>
                </dl>
            </div>

            {{-- 4. Shipping & Warehouse Logistics --}}
            <div class="mt-8 border-t border-gray-100 pt-6">
                <div class="mb-4 flex items-center gap-2">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                        <x-lucide-package class="h-4 w-4" />
                    </span>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">4. Shipping & Warehouse Logistics</h3>
                        <p class="text-xs text-gray-500">Courier pickup location and dedicated buyer support</p>
                    </div>
                </div>

                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="rounded-lg bg-gray-50 p-4 sm:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Pickup / Warehouse Address</dt>
                        <dd id="vendor-pickup-address" class="mt-2 text-sm text-gray-700"></dd>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Pickup City, State & Pincode</dt>
                        <dd id="vendor-pickup-location" class="mt-2 text-sm font-medium text-gray-800"></dd>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Support Contacts</dt>
                        <dd class="mt-2 space-y-1 text-xs">
                            <div><span class="text-gray-400">Email:</span> <span id="vendor-support-email" class="font-medium text-gray-800"></span></div>
                            <div><span class="text-gray-400">Alt Phone:</span> <span id="vendor-alt-phone" class="font-medium text-gray-800 font-mono"></span></div>
                        </dd>
                    </div>
                </dl>
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
                    if (!status) return;
                    status.textContent = message;
                    status.className = 'mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700';
                    status.classList.remove('hidden');
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
                        const v = vendor.vendor || {};

                        setText('#vendor-avatar', vendor.name.split(' ').map((part) => part[0]).slice(0, 2).join('').toUpperCase());
                        setText('#vendor-name', vendor.name);
                        setText('#vendor-email', vendor.email);
                        setText('#vendor-id', vendor.id);
                        setText('#vendor-phone', vendor.phone || v.phone);
                        setText('#vendor-address', vendor.address || v.business_address);
                        setText('#vendor-company', v.store_name || bank.company_name);
                        setText('#vendor-account-holder', bank.account_holder_name);
                        setText('#vendor-bank', bank.bank_name);
                        setText('#vendor-branch', [bank.branch_name, bank.ifsc_code].filter(Boolean).join(' / ') || null);
                        setText('#vendor-verification', vendor.email_verified_at ? `Verified on ${formatDate(vendor.email_verified_at)}` : 'Not verified');
                        setText('#vendor-created-at', formatDate(vendor.created_at));
                        setText('#vendor-updated-at', formatDate(vendor.updated_at));

                        // 3. Identity Verification & KYC
                        setText('#vendor-id-type', v.id_document_type);
                        setText('#vendor-id-number', v.id_number);
                        setText('#vendor-brn', v.business_registration_number);
                        setText('#vendor-tin', v.tax_identification_number);

                        const btnIdDoc = document.querySelector('#btn-vendor-id-doc');
                        const badgeNoIdDoc = document.querySelector('#badge-no-id-doc');
                        const textIdDoc = document.querySelector('#vendor-id-doc-text');
                        if (v.id_document_image && v.id) {
                            btnIdDoc.href = `${apiUrl}/${encodeURIComponent(v.id)}/kyc-document/id_document`;
                            btnIdDoc.classList.remove('hidden');
                            badgeNoIdDoc.classList.add('hidden');
                            textIdDoc.textContent = v.id_document_image.split('/').pop();
                        } else {
                            btnIdDoc.classList.add('hidden');
                            badgeNoIdDoc.classList.remove('hidden');
                            textIdDoc.textContent = 'No file uploaded';
                        }

                        const btnBizCert = document.querySelector('#btn-vendor-biz-cert');
                        const badgeNoBizCert = document.querySelector('#badge-no-biz-cert');
                        const textBizCert = document.querySelector('#vendor-biz-cert-text');
                        if (v.business_certificate_document && v.id) {
                            btnBizCert.href = `${apiUrl}/${encodeURIComponent(v.id)}/kyc-document/business_certificate`;
                            btnBizCert.classList.remove('hidden');
                            badgeNoBizCert.classList.add('hidden');
                            textBizCert.textContent = v.business_certificate_document.split('/').pop();
                        } else {
                            btnBizCert.classList.add('hidden');
                            badgeNoBizCert.classList.remove('hidden');
                            textBizCert.textContent = 'No certificate uploaded';
                        }

                        // 4. Shipping & Warehouse Logistics
                        setText('#vendor-pickup-address', v.pickup_address || v.business_address || vendor.address || 'Same as registered business address');
                        const locationParts = [v.pickup_city, v.pickup_state, v.pickup_pincode ? `PIN: ${v.pickup_pincode}` : null].filter(Boolean);
                        setText('#vendor-pickup-location', locationParts.length > 0 ? locationParts.join(', ') : 'Not specified');
                        setText('#vendor-support-email', v.support_email || 'Not provided');
                        setText('#vendor-alt-phone', v.alternate_phone || 'Not provided');

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
