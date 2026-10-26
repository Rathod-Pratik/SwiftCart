@extends('layouts.Dashboard')

@section('content')
    @php($vendorId = request()->query('_id') ?? request()->query('id'))

    <x-slot name="title">Vendor Request Details</x-slot>

    <div class="space-y-6 p-4 sm:p-6 lg:p-8">
        {{-- Top Navigation & Action Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.vendor.request') }}"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white text-gray-600 transition hover:bg-gray-50 hover:text-gray-900 shadow-xs"
                    title="Back to Vendor Requests">
                    <x-lucide-arrow-left class="h-5 w-5" />
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-2xl font-bold tracking-tight text-gray-900">
                            Vendor Application
                        </h1>
                        <span id="application-id-badge" class="rounded-lg bg-gray-100 px-2.5 py-0.5 text-xs font-semibold text-gray-600">
                            #{{ $vendorId ?? '...' }}
                        </span>
                    </div>
                    <p class="mt-0.5 text-xs text-gray-500">
                        Review merchant credentials, identity verification (KYC), and shipping logistics.
                    </p>
                </div>
            </div>

            {{-- Action Buttons (Approve / Reject) --}}
            <div id="action-buttons-container" class="flex flex-wrap items-center gap-2.5">
                <button type="button" id="btn-reject"
                    class="inline-flex items-center gap-1.5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-2 text-sm font-semibold text-rose-700 transition-all hover:bg-rose-100 hover:shadow-xs disabled:opacity-50">
                    <x-lucide-x class="h-4 w-4" />
                    <span>Reject</span>
                </button>
                <button type="button" id="btn-approve"
                    class="inline-flex items-center gap-1.5 rounded-xl bg-[#224E51] px-4 py-2 text-sm font-semibold text-white shadow-xs transition-all hover:bg-[#1b3f41] hover:shadow-md disabled:opacity-50">
                    <x-lucide-check class="h-4 w-4" />
                    <span>Approve Application</span>
                </button>
            </div>
        </div>

        {{-- Status Notification Toast/Banner --}}
        <div id="request-status-banner" class="hidden rounded-xl px-4 py-3 text-sm font-medium transition" role="alert"></div>

        {{-- Loading Skeleton Card --}}
        <div id="request-loading" class="rounded-2xl border border-gray-100 bg-white p-12 text-center shadow-xs">
            <x-Loading />
            <p class="mt-3 text-sm font-medium text-gray-500">Loading vendor request details...</p>
        </div>

        {{-- Main Details Content (Hidden while loading) --}}
        <div id="request-content" class="hidden space-y-6">
            {{-- Top Store Overview Card --}}
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-xs">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex items-start gap-4 sm:items-center">
                        <div id="store-logo-wrapper"
                            class="relative flex h-20 w-20 shrink-0 items-center justify-center overflow-hidden rounded-2xl border border-gray-100 bg-[#224E51]/10 text-2xl font-bold text-[#224E51] shadow-xs">
                            <span id="store-logo-fallback"></span>
                            <img id="store-logo-img" src="" alt="Store Logo" class="hidden h-full w-full object-cover">
                        </div>
                        <div>
                            <div class="flex flex-wrap items-center gap-2.5">
                                <h2 id="store-name" class="text-2xl font-extrabold text-gray-900"></h2>
                                <span id="store-status-badge" class="rounded-full border px-3 py-1 text-xs font-semibold uppercase tracking-wider"></span>
                            </div>
                            <p id="store-type" class="mt-1 text-sm font-medium text-gray-500 capitalize"></p>
                            <p id="store-description" class="mt-2 text-sm text-gray-600 max-w-2xl"></p>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2 rounded-xl bg-gray-50 p-4 text-xs lg:min-w-[240px]">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Submission Date:</span>
                            <span id="submitted-date" class="font-semibold text-gray-900"></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Last Updated:</span>
                            <span id="updated-date" class="font-semibold text-gray-900"></span>
                        </div>
                        <div class="flex items-center justify-between border-t border-gray-200/60 pt-1.5">
                            <span class="text-gray-500">Account Role:</span>
                            <span id="user-role-badge" class="font-bold uppercase text-[#224E51]"></span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2-Column Grid: Store Info & Applicant Account --}}
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                {{-- Store Information --}}
                <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-xs">
                    <div class="flex items-center gap-2 border-b border-gray-100 pb-4">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-teal-50 text-[#224E51]">
                            <x-lucide-store class="h-5 w-5" />
                        </span>
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Store Information</h3>
                            <p class="text-xs text-gray-500">Physical address and primary business contact</p>
                        </div>
                    </div>

                    <dl class="mt-5 space-y-4 text-sm">
                        <div class="flex items-start gap-3 rounded-xl bg-gray-50 p-3.5">
                            <x-lucide-map-pin class="mt-0.5 h-4 w-4 shrink-0 text-gray-400" />
                            <div>
                                <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Registered Business Address</dt>
                                <dd id="business-address" class="mt-1 font-medium text-gray-800"></dd>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 rounded-xl bg-gray-50 p-3.5">
                            <x-lucide-phone class="mt-0.5 h-4 w-4 shrink-0 text-gray-400" />
                            <div>
                                <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Primary Phone</dt>
                                <dd id="store-phone" class="mt-1 font-medium text-gray-800"></dd>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 rounded-xl bg-gray-50 p-3.5">
                            <x-lucide-building-2 class="mt-0.5 h-4 w-4 shrink-0 text-gray-400" />
                            <div>
                                <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Business Entity Type</dt>
                                <dd id="business-type-detail" class="mt-1 font-medium capitalize text-gray-800"></dd>
                            </div>
                        </div>
                    </dl>
                </div>

                {{-- Applicant Account Details --}}
                <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-xs">
                    <div class="flex items-center gap-2 border-b border-gray-100 pb-4">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                            <x-lucide-user class="h-5 w-5" />
                        </span>
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Applicant Account</h3>
                            <p class="text-xs text-gray-500">Registered user profile details</p>
                        </div>
                    </div>

                    <dl class="mt-5 space-y-4 text-sm">
                        <div class="flex items-start gap-3 rounded-xl bg-gray-50 p-3.5">
                            <x-lucide-user class="mt-0.5 h-4 w-4 shrink-0 text-gray-400" />
                            <div class="w-full">
                                <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Full Name</dt>
                                <dd id="applicant-name" class="mt-1 font-medium text-gray-800"></dd>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 rounded-xl bg-gray-50 p-3.5">
                            <x-lucide-mail class="mt-0.5 h-4 w-4 shrink-0 text-gray-400" />
                            <div class="w-full">
                                <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Account Email Address</dt>
                                <div class="mt-1 flex items-center justify-between">
                                    <span id="applicant-email" class="font-medium text-gray-800"></span>
                                    <span id="applicant-email-status" class="rounded-full px-2 py-0.5 text-[11px] font-semibold"></span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 rounded-xl bg-gray-50 p-3.5">
                            <x-lucide-shield-check class="mt-0.5 h-4 w-4 shrink-0 text-gray-400" />
                            <div>
                                <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">User ID</dt>
                                <dd id="applicant-user-id" class="mt-1 font-mono text-xs text-gray-600"></dd>
                            </div>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Identity Verification & KYC Card --}}
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-xs">
                <div class="flex items-center gap-2 border-b border-gray-100 pb-4">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-purple-50 text-purple-600">
                        <x-lucide-shield-check class="h-5 w-5" />
                    </span>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Identity Verification & KYC</h3>
                        <p class="text-xs text-gray-500">Government identity verification and compliance documents</p>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    {{-- Identity Document Type --}}
                    <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">
                        <div class="text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Identity Document Type
                        </div>
                        <div id="id-document-type-badge" class="mt-2 font-semibold text-gray-900">
                            Not Provided
                        </div>
                    </div>

                    {{-- ID / PAN Number --}}
                    <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">
                        <div class="text-xs font-semibold uppercase tracking-wider text-gray-500">
                            ID / PAN Number
                        </div>
                        <div id="id-number-val" class="mt-2 font-mono text-sm font-bold text-gray-900">
                            Not Provided
                        </div>
                    </div>

                    {{-- ID Document Upload Download --}}
                    <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 flex flex-col justify-between">
                        <div>
                            <div class="text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Government ID Proof File
                            </div>
                            <div id="id-doc-name" class="mt-2 text-xs text-gray-600 truncate">
                                No file uploaded
                            </div>
                        </div>
                        <div class="mt-3">
                            <a id="btn-download-id-doc" href="#" target="_blank"
                                class="hidden inline-flex items-center gap-1.5 rounded-lg bg-[#224E51] px-3 py-1.5 text-xs font-semibold text-white shadow-xs transition hover:bg-[#1b3f41]">
                                <x-lucide-download class="h-3.5 w-3.5" />
                                <span>Download ID Proof</span>
                            </a>
                            <span id="no-id-doc-badge" class="inline-flex items-center gap-1 text-xs text-gray-400">
                                <x-lucide-x class="h-3.5 w-3.5" />
                                None uploaded
                            </span>
                        </div>
                    </div>

                    {{-- Business Certificate Document --}}
                    <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 flex flex-col justify-between">
                        <div>
                            <div class="text-xs font-semibold uppercase tracking-wider text-gray-500">
                                GST / Registration Certificate
                            </div>
                            <div id="biz-cert-name" class="mt-2 text-xs text-gray-600 truncate">
                                No certificate uploaded
                            </div>
                        </div>
                        <div class="mt-3">
                            <a id="btn-download-biz-cert" href="#" target="_blank"
                                class="hidden inline-flex items-center gap-1.5 rounded-lg bg-[#224E51] px-3 py-1.5 text-xs font-semibold text-white shadow-xs transition hover:bg-[#1b3f41]">
                                <x-lucide-download class="h-3.5 w-3.5" />
                                <span>Download Certificate</span>
                            </a>
                            <span id="no-biz-cert-badge" class="inline-flex items-center gap-1 text-xs text-gray-400">
                                <x-lucide-x class="h-3.5 w-3.5" />
                                None uploaded
                            </span>
                        </div>
                    </div>

                    {{-- Business Registration Number --}}
                    <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">
                        <div class="text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Business Registration # (BRN/CIN)
                        </div>
                        <div id="doc-brn" class="mt-2 font-mono text-sm font-bold text-gray-900">
                            Not Provided
                        </div>
                    </div>

                    {{-- Tax Identification Number --}}
                    <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">
                        <div class="text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Tax ID (TIN / GSTIN / PAN)
                        </div>
                        <div id="doc-tin" class="mt-2 font-mono text-sm font-bold text-gray-900">
                            Not Provided
                        </div>
                    </div>
                </div>
            </div>

            {{-- Shipping & Warehouse Logistics Card --}}
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-xs">
                <div class="flex items-center gap-2 border-b border-gray-100 pb-4">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                        <x-lucide-package class="h-5 w-5" />
                    </span>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Shipping & Warehouse Logistics</h3>
                        <p class="text-xs text-gray-500">Courier pickup location and dedicated customer support details</p>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    {{-- Warehouse Pickup Address --}}
                    <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 sm:col-span-2">
                        <div class="text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Pickup / Warehouse Address
                        </div>
                        <div id="pickup-address-val" class="mt-2 font-medium text-sm text-gray-900">
                            Same as registered business address
                        </div>
                    </div>

                    {{-- Pickup Location (City, State, Pincode) --}}
                    <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">
                        <div class="text-xs font-semibold uppercase tracking-wider text-gray-500">
                            City, State & Pincode
                        </div>
                        <div id="pickup-location-val" class="mt-2 font-medium text-sm text-gray-900">
                            Not specified
                        </div>
                    </div>

                    {{-- Support & Alternate Contacts --}}
                    <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">
                        <div class="text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Support Contacts
                        </div>
                        <div class="mt-2 space-y-1 text-xs">
                            <div>
                                <span class="text-gray-400">Email:</span>
                                <a id="support-email-link" href="#" class="font-medium text-[#224E51] hover:underline"></a>
                            </div>
                            <div>
                                <span class="text-gray-400">Alt Phone:</span>
                                <a id="alt-phone-link" href="#" class="font-medium text-[#224E51] hover:underline"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (() => {
                const vendorId = @json($vendorId);
                const apiUrl = @json(url('/api/vendor-applications'));
                const downloadDocBaseUrl = @json(url('/api/vendors'));
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                const loadingCard = document.querySelector('#request-loading');
                const contentCard = document.querySelector('#request-content');
                const statusBanner = document.querySelector('#request-status-banner');
                const btnApprove = document.querySelector('#btn-approve');
                const btnReject = document.querySelector('#btn-reject');

                const showBanner = (text, isError = false) => {
                    statusBanner.textContent = text;
                    statusBanner.className = `rounded-xl px-4 py-3 text-sm font-medium transition ${isError ? 'bg-red-50 text-red-700 border border-red-200' : 'bg-emerald-50 text-emerald-800 border border-emerald-200'}`;
                    statusBanner.classList.remove('hidden');
                };

                const formatDate = (val) => {
                    if (!val) return 'Not available';
                    return new Intl.DateTimeFormat(undefined, {
                        dateStyle: 'medium',
                        timeStyle: 'short'
                    }).format(new Date(val));
                };

                const updateStatusBadge = (status) => {
                    const badge = document.querySelector('#store-status-badge');
                    badge.textContent = status;
                    if (status === 'approved') {
                        badge.className = 'rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-emerald-700';
                        btnApprove.disabled = true;
                        btnReject.disabled = false;
                        btnApprove.classList.add('opacity-50');
                        btnReject.classList.remove('opacity-50');
                    } else if (status === 'rejected') {
                        badge.className = 'rounded-full border border-rose-200 bg-rose-50 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-rose-700';
                        btnApprove.disabled = false;
                        btnReject.disabled = true;
                        btnApprove.classList.remove('opacity-50');
                        btnReject.classList.add('opacity-50');
                    } else {
                        badge.className = 'rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-amber-700';
                        btnApprove.disabled = false;
                        btnReject.disabled = false;
                        btnApprove.classList.remove('opacity-50');
                        btnReject.classList.remove('opacity-50');
                    }
                };

                const renderVendorDetails = (vendor) => {
                    // Application ID
                    document.querySelector('#application-id-badge').textContent = `#APP-${String(vendor.id).padStart(4, '0')}`;

                    // Store Info
                    const storeName = vendor.store_name || vendor.business_name || 'Unnamed Store';
                    document.querySelector('#store-name').textContent = storeName;
                    document.querySelector('#store-type').textContent = `${vendor.business_type || 'Retailer'} Store`;
                    document.querySelector('#store-description').textContent = vendor.description || 'No store description provided.';
                    document.querySelector('#business-address').textContent = vendor.business_address || vendor.address || 'Address not provided';
                    document.querySelector('#store-phone').textContent = vendor.phone || 'Phone not provided';
                    document.querySelector('#business-type-detail').textContent = vendor.business_type || 'Standard';

                    // Logo
                    const logoFallback = document.querySelector('#store-logo-fallback');
                    const logoImg = document.querySelector('#store-logo-img');
                    if (vendor.logo) {
                        logoImg.src = `{{ asset('storage') }}/${vendor.logo}`;
                        logoImg.classList.remove('hidden');
                        logoFallback.classList.add('hidden');
                    } else {
                        logoFallback.textContent = storeName.split(' ').map(p => p[0]).slice(0, 2).join('').toUpperCase();
                        logoFallback.classList.remove('hidden');
                        logoImg.classList.add('hidden');
                    }

                    // Applicant Info
                    const user = vendor.user || {};
                    document.querySelector('#applicant-name').textContent = user.name || 'Not available';
                    document.querySelector('#applicant-email').textContent = user.email || 'Not available';
                    document.querySelector('#applicant-user-id').textContent = user.id ? `UID-${user.id}` : 'N/A';
                    document.querySelector('#user-role-badge').textContent = user.role || 'User';

                    const emailStatus = document.querySelector('#applicant-email-status');
                    if (user.email_verified_at) {
                        emailStatus.textContent = 'Verified';
                        emailStatus.className = 'rounded-full bg-emerald-100 text-emerald-800 px-2 py-0.5 text-[11px] font-semibold';
                    } else {
                        emailStatus.textContent = 'Unverified';
                        emailStatus.className = 'rounded-full bg-amber-100 text-amber-800 px-2 py-0.5 text-[11px] font-semibold';
                    }

                    // Dates
                    document.querySelector('#submitted-date').textContent = formatDate(vendor.created_at);
                    document.querySelector('#updated-date').textContent = formatDate(vendor.updated_at);

                    // KYC & Legal
                    document.querySelector('#id-document-type-badge').textContent = vendor.id_document_type || 'Not Provided';
                    document.querySelector('#id-number-val').textContent = vendor.id_number || 'Not Provided';
                    document.querySelector('#doc-brn').textContent = vendor.business_registration_number || 'Not Provided';
                    document.querySelector('#doc-tin').textContent = vendor.tax_identification_number || 'Not Provided';

                    // ID Document Download
                    const btnIdDoc = document.querySelector('#btn-download-id-doc');
                    const noIdDoc = document.querySelector('#no-id-doc-badge');
                    const idDocName = document.querySelector('#id-doc-name');
                    if (vendor.id_document_image) {
                        idDocName.textContent = vendor.id_document_image.split('/').pop();
                        btnIdDoc.href = `${downloadDocBaseUrl}/${encodeURIComponent(vendor.id)}/kyc-document/id_document`;
                        btnIdDoc.classList.remove('hidden');
                        noIdDoc.classList.add('hidden');
                    } else {
                        idDocName.textContent = 'No file uploaded';
                        btnIdDoc.classList.add('hidden');
                        noIdDoc.classList.remove('hidden');
                    }

                    // Business Certificate Download
                    const btnBizCert = document.querySelector('#btn-download-biz-cert');
                    const noBizCert = document.querySelector('#no-biz-cert-badge');
                    const bizCertName = document.querySelector('#biz-cert-name');
                    if (vendor.business_certificate_document) {
                        bizCertName.textContent = vendor.business_certificate_document.split('/').pop();
                        btnBizCert.href = `${downloadDocBaseUrl}/${encodeURIComponent(vendor.id)}/kyc-document/business_certificate`;
                        btnBizCert.classList.remove('hidden');
                        noBizCert.classList.add('hidden');
                    } else {
                        bizCertName.textContent = 'No certificate uploaded';
                        btnBizCert.classList.add('hidden');
                        noBizCert.classList.remove('hidden');
                    }

                    // Shipping & Logistics
                    document.querySelector('#pickup-address-val').textContent = vendor.pickup_address || vendor.business_address || 'Same as registered business address';
                    const locationParts = [vendor.pickup_city, vendor.pickup_state, vendor.pickup_pincode].filter(Boolean);
                    document.querySelector('#pickup-location-val').textContent = locationParts.length ? locationParts.join(', ') : 'Not specified';

                    const supportEmailLink = document.querySelector('#support-email-link');
                    if (vendor.support_email) {
                        supportEmailLink.textContent = vendor.support_email;
                        supportEmailLink.href = `mailto:${vendor.support_email}`;
                    } else {
                        supportEmailLink.textContent = user.email || 'None';
                        supportEmailLink.href = user.email ? `mailto:${user.email}` : '#';
                    }

                    const altPhoneLink = document.querySelector('#alt-phone-link');
                    if (vendor.alternate_phone) {
                        altPhoneLink.textContent = vendor.alternate_phone;
                        altPhoneLink.href = `tel:${vendor.alternate_phone}`;
                    } else {
                        altPhoneLink.textContent = 'None';
                        altPhoneLink.href = '#';
                    }

                    // Status
                    updateStatusBadge(vendor.status);

                    loadingCard.classList.add('hidden');
                    contentCard.classList.remove('hidden');
                };

                const loadApplicationDetails = async () => {
                    if (!vendorId) {
                        loadingCard.classList.add('hidden');
                        showBanner('No vendor application ID provided in URL (e.g. ?_id=1).', true);
                        return;
                    }

                    try {
                        const response = await fetch(`${apiUrl}/${encodeURIComponent(vendorId)}`, {
                            headers: { Accept: 'application/json' }
                        });
                        const payload = await response.json();
                        if (!response.ok || !payload.vendor) {
                            throw new Error(payload.message || 'Unable to retrieve vendor application.');
                        }

                        renderVendorDetails(payload.vendor);
                    } catch (error) {
                        loadingCard.classList.add('hidden');
                        showBanner(error.message, true);
                    }
                };

                const handleStatusUpdate = async (status) => {
                    const actionName = status === 'approved' ? 'APPROVE' : 'REJECT';
                    if (!confirm(`Are you sure you want to ${actionName} this vendor application?`)) {
                        return;
                    }

                    btnApprove.disabled = true;
                    btnReject.disabled = true;

                    try {
                        const response = await fetch(`${apiUrl}/${encodeURIComponent(vendorId)}/status`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({ status })
                        });

                        const payload = await response.json();
                        if (!response.ok) {
                            throw new Error(payload.message || `Failed to update status to ${status}.`);
                        }

                        showBanner(payload.message || `Vendor application marked as ${status}.`);
                        if (payload.vendor) {
                            updateStatusBadge(payload.vendor.status);
                        } else {
                            updateStatusBadge(status);
                        }
                    } catch (error) {
                        showBanner(error.message, true);
                        btnApprove.disabled = false;
                        btnReject.disabled = false;
                    }
                };

                btnApprove.addEventListener('click', () => handleStatusUpdate('approved'));
                btnReject.addEventListener('click', () => handleStatusUpdate('rejected'));

                loadApplicationDetails();
            })();
        </script>
    @endpush
@endsection