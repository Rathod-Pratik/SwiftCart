@extends('layouts.Vendor')

@section('content')
    <x-slot name="title">Add Coupon</x-slot>

    <div class="p-4 sm:p-6 lg:p-8">
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <nav class="flex items-center gap-2 text-xs font-semibold text-gray-400">
                    <a href="{{ route('vendor.dashboard') }}" class="transition hover:text-gray-600">Dashboard</a>
                    <span>/</span>
                    <a href="{{ route('vendor.coupon') }}" class="transition hover:text-[#3d6f6d]">Coupons</a>
                    <span>/</span>
                    <span id="breadcrumb-current" class="text-gray-800">New Coupon</span>
                </nav>
                <h1 id="page-title" class="mt-1 text-2xl font-bold text-gray-900 sm:text-3xl">Create Discount Coupon</h1>
                <p class="mt-1 text-sm text-gray-500">Create promotional codes and manage discount rules for your store.</p>
            </div>
            <a href="{{ route('vendor.coupon') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
                <x-lucide-arrow-left class="h-4 w-4 text-gray-500" />
                <span>Back to Coupons</span>
            </a>
        </div>

        <div id="form-status" class="mb-5 hidden rounded-xl px-4 py-3 text-sm font-medium" role="alert"></div>

        <form id="coupon-form" class="space-y-6">
            <input type="hidden" id="coupon-id" value="">

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <div class="space-y-6 lg:col-span-2">
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-7">
                        <div class="mb-5 flex items-center gap-3 border-b border-gray-100 pb-4">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#3d6f6d]/10 text-sm font-bold text-[#3d6f6d]">1</span>
                            <div>
                                <h2 class="text-lg font-bold text-gray-900">Coupon Information</h2>
                                <p class="text-xs text-gray-500">Set the coupon code and explain the offer to customers.</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label for="coupon-code" class="block text-sm font-semibold text-gray-900">Coupon Code <span class="font-bold text-rose-500">*</span></label>
                                <div class="mt-1.5 flex flex-col gap-2 sm:flex-row">
                                    <input type="text" id="coupon-code" maxlength="40" placeholder="e.g. WELCOME20"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm uppercase outline-none transition focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                                    <button type="button" id="generate-code" class="rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">Generate Code</button>
                                </div>
                                <p id="error-code" class="mt-1.5 hidden text-xs font-medium text-rose-600"></p>
                                <div class="mt-2.5 flex flex-wrap items-center gap-1.5 text-xs text-gray-500">
                                    <span class="font-medium text-gray-600">Popular presets:</span>
                                    <button type="button" data-code="WELCOME10" class="code-preset rounded-lg bg-gray-100 px-2.5 py-1 text-gray-700 transition hover:bg-[#3d6f6d] hover:text-white">WELCOME10</button>
                                    <button type="button" data-code="SAVE20" class="code-preset rounded-lg bg-gray-100 px-2.5 py-1 text-gray-700 transition hover:bg-[#3d6f6d] hover:text-white">SAVE20</button>
                                    <button type="button" data-code="FREESHIP" class="code-preset rounded-lg bg-gray-100 px-2.5 py-1 text-gray-700 transition hover:bg-[#3d6f6d] hover:text-white">FREESHIP</button>
                                    <button type="button" data-code="FESTIVE25" class="code-preset rounded-lg bg-gray-100 px-2.5 py-1 text-gray-700 transition hover:bg-[#3d6f6d] hover:text-white">FESTIVE25</button>
                                </div>
                            </div>

                            <div>
                                <label for="coupon-description" class="block text-sm font-semibold text-gray-900">Description <span class="text-xs font-normal text-gray-400">(Optional)</span></label>
                                <textarea id="coupon-description" rows="3" maxlength="500" placeholder="Describe this offer for your customers..."
                                    class="mt-1.5 w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-7">
                        <div class="mb-5 flex items-center gap-3 border-b border-gray-100 pb-4">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#3d6f6d]/10 text-sm font-bold text-[#3d6f6d]">2</span>
                            <div>
                                <h2 class="text-lg font-bold text-gray-900">Discount Configuration</h2>
                                <p class="text-xs text-gray-500">Choose the discount type and define the minimum purchase rules.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label for="discount-type" class="block text-sm font-semibold text-gray-900">Discount Type <span class="text-rose-500">*</span></label>
                                <select id="discount-type" class="mt-1.5 w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                                    <option value="percentage">Percentage (%)</option>
                                    <option value="fixed">Fixed Amount (₹)</option>
                                </select>
                            </div>
                            <div>
                                <label for="discount-value" class="block text-sm font-semibold text-gray-900">Discount Value <span class="text-rose-500">*</span></label>
                                <div class="relative mt-1.5">
                                    <span id="discount-prefix" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm font-semibold text-gray-500">%</span>
                                    <input type="number" id="discount-value" min="0.01" step="0.01" placeholder="20"
                                        class="w-full rounded-xl border border-gray-300 py-3 pl-9 pr-4 text-sm outline-none transition focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                                </div>
                                <p id="error-value" class="mt-1.5 hidden text-xs font-medium text-rose-600"></p>
                            </div>
                            <div>
                                <label for="minimum-order" class="block text-sm font-semibold text-gray-900">Minimum Order Amount (₹)</label>
                                <input type="number" id="minimum-order" min="0" step="0.01" placeholder="0"
                                    class="mt-1.5 w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                            </div>
                            <div id="max-discount-wrapper">
                                <label for="maximum-discount" class="block text-sm font-semibold text-gray-900">Maximum Discount (₹) <span class="text-xs font-normal text-gray-400">(For percentage)</span></label>
                                <input type="number" id="maximum-discount" min="0" step="0.01" placeholder="e.g. 500"
                                    class="mt-1.5 w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-7">
                        <div class="mb-5 flex items-center gap-3 border-b border-gray-100 pb-4">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#3d6f6d]/10 text-sm font-bold text-[#3d6f6d]">3</span>
                            <div>
                                <h2 class="text-lg font-bold text-gray-900">Usage & Validity</h2>
                                <p class="text-xs text-gray-500">Control how often the coupon can be used and when it is available.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label for="usage-limit" class="block text-sm font-semibold text-gray-900">Total Usage Limit <span class="text-xs font-normal text-gray-400">(Optional)</span></label>
                                <input type="number" id="usage-limit" min="1" step="1" placeholder="Unlimited"
                                    class="mt-1.5 w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                            </div>
                            <div>
                                <label for="per-user-limit" class="block text-sm font-semibold text-gray-900">Usage Limit Per Customer</label>
                                <input type="number" id="per-user-limit" min="1" step="1" value="1"
                                    class="mt-1.5 w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                            </div>
                            <div>
                                <label for="starts-at" class="block text-sm font-semibold text-gray-900">Start Date & Time</label>
                                <input type="datetime-local" id="starts-at"
                                    class="mt-1.5 w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                            </div>
                            <div>
                                <label for="expires-at" class="block text-sm font-semibold text-gray-900">Expiry Date & Time <span class="text-rose-500">*</span></label>
                                <input type="datetime-local" id="expires-at"
                                    class="mt-1.5 w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                                <p id="error-expiry" class="mt-1.5 hidden text-xs font-medium text-rose-600"></p>
                            </div>
                        </div>

                        <div class="mt-5 border-t border-gray-100 pt-4">
                            <label class="inline-flex cursor-pointer items-center gap-3">
                                <input type="checkbox" id="coupon-status-toggle" checked class="h-5 w-5 rounded border-gray-300 text-[#3d6f6d] focus:ring-[#3d6f6d]">
                                <span>
                                    <span class="block text-sm font-semibold text-gray-900">Active & Available to Customers</span>
                                    <span class="block text-xs text-gray-500">Customers can apply this coupon when it is active and within its validity period.</span>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="sticky top-24 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">
                        <div class="mb-4 flex items-center justify-between border-b border-gray-100 pb-3">
                            <div class="flex items-center gap-2">
                                <span class="h-2.5 w-2.5 animate-pulse rounded-full bg-emerald-500"></span>
                                <h3 class="text-sm font-bold text-gray-900">Live Coupon Preview</h3>
                            </div>
                            <span class="rounded bg-teal-50 px-2 py-0.5 text-xs font-semibold text-[#3d6f6d]">Simulator</span>
                        </div>
                        <p class="mb-4 text-xs text-gray-500">Preview how the offer may appear to customers at checkout.</p>

                        <div class="rounded-2xl border-2 border-dashed border-[#3d6f6d]/30 bg-[#F5F7F4]/70 p-4">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#3d6f6d] text-[#F4C95D]">
                                    <x-lucide-ticket-percent class="h-6 w-6" />
                                </div>
                                <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-bold text-emerald-800">SPECIAL OFFER</span>
                            </div>
                            <p id="preview-discount" class="mt-4 text-2xl font-extrabold text-gray-900">20% OFF</p>
                            <p id="preview-description" class="mt-1 text-xs text-gray-600">Enjoy savings on your next order.</p>
                            <div class="mt-4 flex items-center justify-between rounded-lg border border-dashed border-gray-300 bg-white px-3 py-2">
                                <code id="preview-code" class="font-bold tracking-wider text-[#3d6f6d]">WELCOME20</code>
                                <span class="text-xs font-semibold text-gray-400">COUPON CODE</span>
                            </div>
                            <p id="preview-minimum" class="mt-2 text-xs text-gray-500">No minimum order</p>
                        </div>

                        <div class="mt-6 flex flex-col gap-2.5">
                            <button type="submit" id="save-coupon-btn" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#3d6f6d] py-3 text-sm font-bold text-white shadow-md transition hover:bg-[#315b59]">
                                <span id="save-spinner" class="hidden h-4 w-4 animate-spin rounded-full border-2 border-white border-t-transparent"></span>
                                <span id="save-label">Create Coupon</span>
                            </button>
                            <a href="{{ route('vendor.coupon') }}" class="inline-flex w-full items-center justify-center rounded-xl border border-gray-300 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            (() => {
                const form = document.querySelector('#coupon-form');
                const idInput = document.querySelector('#coupon-id');
                const codeInput = document.querySelector('#coupon-code');
                const descriptionInput = document.querySelector('#coupon-description');
                const typeInput = document.querySelector('#discount-type');
                const valueInput = document.querySelector('#discount-value');
                const minimumInput = document.querySelector('#minimum-order');
                const maximumInput = document.querySelector('#maximum-discount');
                const usageInput = document.querySelector('#usage-limit');
                const perUserInput = document.querySelector('#per-user-limit');
                const startsInput = document.querySelector('#starts-at');
                const expiresInput = document.querySelector('#expires-at');
                const activeInput = document.querySelector('#coupon-status-toggle');
                const status = document.querySelector('#form-status');
                const saveButton = document.querySelector('#save-coupon-btn');
                const saveLabel = document.querySelector('#save-label');
                const spinner = document.querySelector('#save-spinner');
                const apiUrl = @json(url('/api/vendor/coupons'));
                const indexUrl = @json(route('vendor.coupon'));
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
                const params = new URLSearchParams(window.location.search);
                const editId = params.get('id');

                const showStatus = (message, isError = false) => {
                    window.showToast?.(message, isError ? 'error' : 'success');
                };

                const updatePreview = () => {
                    const type = typeInput.value;
                    const value = Number(valueInput.value || 0);
                    document.querySelector('#preview-code').textContent = codeInput.value.trim().toUpperCase() || 'COUPONCODE';
                    document.querySelector('#preview-discount').textContent = value > 0
                        ? (type === 'percentage' ? `${value}% OFF` : `₹${value.toFixed(2)} OFF`)
                        : (type === 'percentage' ? 'Discount Offer' : 'Special Discount');
                    document.querySelector('#preview-description').textContent = descriptionInput.value.trim() || 'Enjoy savings on your next order.';
                    document.querySelector('#preview-minimum').textContent = Number(minimumInput.value) > 0
                        ? `Minimum order: ₹${Number(minimumInput.value).toFixed(2)}`
                        : 'No minimum order';
                    document.querySelector('#discount-prefix').textContent = type === 'percentage' ? '%' : '₹';
                    document.querySelector('#max-discount-wrapper').classList.toggle('hidden', type !== 'percentage');
                    valueInput.max = type === 'percentage' ? '100' : '';
                };

                const setDateValue = (input, value) => {
                    if (!value) return;
                    const date = new Date(value);
                    if (!Number.isNaN(date.getTime())) {
                        const local = new Date(date.getTime() - date.getTimezoneOffset() * 60000);
                        input.value = local.toISOString().slice(0, 16);
                    }
                };

                const loadForEdit = async () => {
                    document.querySelector('#page-title').textContent = 'Edit Coupon';
                    document.querySelector('#breadcrumb-current').textContent = 'Edit Coupon';
                    saveLabel.textContent = 'Update Coupon';
                    idInput.value = editId;

                    try {
                        const response = await fetch(`${apiUrl}/${editId}`, {
                            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
                        });
                        const result = await response.json();
                        if (!response.ok || !result.success || !result.data) {
                            showStatus(result.message || 'Unable to load coupon details.', true);
                            return;
                        }

                        const coupon = result.data;
                        codeInput.value = coupon.code || '';
                        descriptionInput.value = coupon.description || '';
                        typeInput.value = coupon.discount_type || 'percentage';
                        valueInput.value = coupon.discount_value ?? '';
                        minimumInput.value = coupon.minimum_order_amount ?? '';
                        maximumInput.value = coupon.maximum_discount ?? '';
                        usageInput.value = coupon.usage_limit ?? '';
                        perUserInput.value = coupon.per_user_limit ?? 1;
                        setDateValue(startsInput, coupon.starts_at);
                        setDateValue(expiresInput, coupon.expires_at);
                        activeInput.checked = Boolean(coupon.is_active);
                        updatePreview();
                    } catch (error) {
                        showStatus('Could not connect to the coupons API.', true);
                    }
                };

                codeInput.addEventListener('input', () => {
                    codeInput.value = codeInput.value.toUpperCase().replace(/\s+/g, '');
                    updatePreview();
                });

                [descriptionInput, typeInput, valueInput, minimumInput].forEach((input) => {
                    input.addEventListener('input', updatePreview);
                    input.addEventListener('change', updatePreview);
                });

                typeInput.addEventListener('change', updatePreview);

                document.querySelector('#generate-code').addEventListener('click', () => {
                    const part = Math.random().toString(36).slice(2, 7).toUpperCase();
                    codeInput.value = `SAVE${part}`;
                    updatePreview();
                });

                document.querySelectorAll('.code-preset').forEach((button) => {
                    button.addEventListener('click', () => {
                        codeInput.value = button.dataset.code;
                        updatePreview();
                    });
                });

                form.addEventListener('submit', async (event) => {
                    event.preventDefault();

                    ['code', 'value', 'expiry'].forEach((key) => document.querySelector(`#error-${key}`).classList.add('hidden'));
                    codeInput.classList.remove('border-rose-500');
                    valueInput.classList.remove('border-rose-500');
                    expiresInput.classList.remove('border-rose-500');

                    let invalid = false;
                    const code = codeInput.value.trim().toUpperCase();
                    const value = Number(valueInput.value);
                    const expiry = expiresInput.value;
                    const start = startsInput.value;

                    if (!code) {
                        document.querySelector('#error-code').textContent = 'Please enter a coupon code.';
                        document.querySelector('#error-code').classList.remove('hidden');
                        codeInput.classList.add('border-rose-500');
                        invalid = true;
                    }

                    if (!Number.isFinite(value) || value <= 0 || (typeInput.value === 'percentage' && value > 100)) {
                        document.querySelector('#error-value').textContent = typeInput.value === 'percentage'
                            ? 'Enter a discount between 0 and 100.'
                            : 'Enter a discount amount greater than zero.';
                        document.querySelector('#error-value').classList.remove('hidden');
                        valueInput.classList.add('border-rose-500');
                        invalid = true;
                    }

                    if (!expiry || (start && new Date(expiry) <= new Date(start))) {
                        document.querySelector('#error-expiry').textContent = 'Enter a valid expiry date after the start date.';
                        document.querySelector('#error-expiry').classList.remove('hidden');
                        expiresInput.classList.add('border-rose-500');
                        invalid = true;
                    }

                    if (invalid) return;

                    const payload = {
                        code,
                        description: descriptionInput.value.trim() || null,
                        discount_type: typeInput.value,
                        discount_value: value,
                        minimum_order_amount: minimumInput.value === '' ? null : Number(minimumInput.value),
                        maximum_discount: typeInput.value === 'percentage' && maximumInput.value !== '' ? Number(maximumInput.value) : null,
                        usage_limit: usageInput.value === '' ? null : Number(usageInput.value),
                        per_user_limit: perUserInput.value === '' ? 1 : Number(perUserInput.value),
                        starts_at: start || null,
                        expires_at: expiry,
                        is_active: activeInput.checked
                    };

                    const isEdit = Boolean(idInput.value);
                    const url = isEdit ? `${apiUrl}/${idInput.value}` : apiUrl;
                    spinner.classList.remove('hidden');
                    saveButton.disabled = true;

                    try {
                        const response = await fetch(url, {
                            method: isEdit ? 'PUT' : 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify(payload)
                        });
                        const result = await response.json();

                        if (!response.ok || !result.success) {
                            const message = result.message || (result.errors ? Object.values(result.errors).flat().join(', ') : 'Failed to save coupon.');
                            showStatus(message, true);
                            return;
                        }

                        showStatus(isEdit ? 'Coupon updated successfully!' : 'Coupon created successfully!');
                        setTimeout(() => window.location.href = indexUrl, 1000);
                    } catch (error) {
                        showStatus('A network error occurred. Please try again.', true);
                    } finally {
                        spinner.classList.add('hidden');
                        saveButton.disabled = false;
                    }
                });

                updatePreview();
                if (editId) loadForEdit();
            })();
        </script>
    @endpush
@endsection
