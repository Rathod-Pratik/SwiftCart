@extends('layouts.Dashboard')

@section('content')
    @php($queryId = request()->query('_id'))

    <x-slot name="title">Query Details</x-slot>

    <div class="p-4 sm:p-6">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-[#3d6f6d]">Customer & Vendor Support</p>
                <h1 class="text-2xl font-bold text-gray-900">Query Details</h1>
                <p class="mt-1 text-sm text-gray-600">Review the complete conversation thread, product information, and participant profiles.</p>
            </div>
            <a href="{{ route('admin.query') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                Back to Queries
            </a>
        </div>

        <div id="query-message" class="mb-4 hidden rounded-lg px-4 py-3 text-sm" role="alert"></div>
        <div id="query-loading" class="rounded-xl bg-white p-8 text-center text-sm text-gray-500 shadow-sm">Loading query details...</div>

        <section id="query-details" class="hidden grid max-w-7xl grid-cols-1 gap-6 lg:grid-cols-[minmax(0,1fr)_22rem]">
            {{-- Left column: Conversation thread --}}
            <div class="flex flex-col rounded-xl bg-white p-5 shadow-sm sm:p-8">
                <div class="mb-6 border-b border-gray-100 pb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h2 id="thread-title" class="text-xl font-bold text-gray-900">Conversation</h2>
                        <p id="thread-subtitle" class="mt-1 text-sm text-gray-500"></p>
                    </div>
                    <span id="query-type-badge" class="px-3 py-1 rounded-full text-xs font-semibold w-fit"></span>
                </div>

                {{-- Messages container --}}
                <div id="messages-container" class="flex flex-1 max-h-[36rem] min-h-64 flex-col gap-4 overflow-y-auto rounded-xl bg-gray-50 p-4"></div>
            </div>

            {{-- Right column: Participant and Product details --}}
            <aside class="space-y-6">
                {{-- Product Details Card --}}
                <div id="product-card" class="rounded-xl bg-white p-5 shadow-sm">
                    <h3 class="mb-4 text-base font-bold text-gray-900 flex items-center gap-2">
                        <x-lucide-package class="w-4 h-4 text-[#3d6f6d]"/>
                        Product Details
                    </h3>
                    <div id="product-content" class="text-sm"></div>
                </div>

                {{-- Customer Details Card --}}
                <div class="rounded-xl bg-white p-5 shadow-sm">
                    <h3 class="mb-4 text-base font-bold text-gray-900 flex items-center gap-2">
                        <x-lucide-user class="w-4 h-4 text-[#3d6f6d]"/>
                        Customer
                    </h3>
                    <dl class="grid gap-3 text-sm">
                        <div>
                            <dt class="text-xs font-semibold uppercase text-gray-400">Name</dt>
                            <dd id="customer-name" class="mt-0.5 font-medium text-gray-800"></dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase text-gray-400">Email</dt>
                            <dd id="customer-email" class="mt-0.5 text-gray-600"></dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase text-gray-400">Phone</dt>
                            <dd id="customer-phone" class="mt-0.5 text-gray-600"></dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase text-gray-400">Address</dt>
                            <dd id="customer-address" class="mt-0.5 text-gray-600"></dd>
                        </div>
                    </dl>
                </div>

                {{-- Vendor Details Card --}}
                <div id="vendor-card" class="rounded-xl bg-white p-5 shadow-sm">
                    <h3 class="mb-4 text-base font-bold text-gray-900 flex items-center gap-2">
                        <x-lucide-store class="w-4 h-4 text-[#3d6f6d]"/>
                        Vendor / Store
                    </h3>
                    <dl class="grid gap-3 text-sm">
                        <div>
                            <dt class="text-xs font-semibold uppercase text-gray-400">Store Name</dt>
                            <dd id="vendor-store" class="mt-0.5 font-medium text-gray-800"></dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase text-gray-400">Contact Person</dt>
                            <dd id="vendor-name" class="mt-0.5 text-gray-600"></dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase text-gray-400">Email</dt>
                            <dd id="vendor-email" class="mt-0.5 text-gray-600"></dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase text-gray-400">Phone</dt>
                            <dd id="vendor-phone" class="mt-0.5 text-gray-600"></dd>
                        </div>
                    </dl>
                </div>

                {{-- Order Details Card (if applicable) --}}
                <div id="order-card" class="hidden rounded-xl bg-white p-5 shadow-sm">
                    <h3 class="mb-4 text-base font-bold text-gray-900 flex items-center gap-2">
                        <x-lucide-shopping-bag class="w-4 h-4 text-[#3d6f6d]"/>
                        Linked Order
                    </h3>
                    <dl class="grid gap-3 text-sm">
                        <div>
                            <dt class="text-xs font-semibold uppercase text-gray-400">Order Number</dt>
                            <dd id="order-number" class="mt-0.5 font-medium text-gray-800"></dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase text-gray-400">Status</dt>
                            <dd id="order-status" class="mt-0.5 capitalize text-gray-600"></dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase text-gray-400">Total Amount</dt>
                            <dd id="order-amount" class="mt-0.5 text-gray-600"></dd>
                        </div>
                    </dl>
                </div>
            </aside>
        </section>
    </div>

    @push('scripts')
        <script>
            (() => {
                const queryId = @json($queryId);
                const apiUrl = @json(url('/api/admin/queries'));
                const loading = document.querySelector('#query-loading');
                const details = document.querySelector('#query-details');
                const status = document.querySelector('#query-message');
                const messagesContainer = document.querySelector('#messages-container');

                const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (character) => ({
                    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;',
                }[character]));

                const setText = (selector, value) => {
                    const el = document.querySelector(selector);
                    if (el) el.textContent = value || 'Not provided';
                };

                const formatDate = (value) => {
                    if (!value) return '';
                    return new Intl.DateTimeFormat(undefined, {
                        dateStyle: 'medium',
                        timeStyle: 'short',
                    }).format(new Date(value));
                };

                const showError = (message) => {
                    status.textContent = message;
                    status.className = 'mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700';
                };

                const loadQueryDetails = async () => {
                    if (!queryId) {
                        loading.classList.add('hidden');
                        showError('A query ID is required in the URL, for example: ?_id=1');
                        return;
                    }

                    try {
                        const response = await fetch(`${apiUrl}/${encodeURIComponent(queryId)}`, {
                            headers: { Accept: 'application/json' },
                        });
                        const payload = await response.json();
                        if (!response.ok || !payload.data) {
                            throw new Error(payload.message || 'Unable to load query details.');
                        }

                        const query = payload.data;
                        const customer = query.customer || {};
                        const vendor = query.vendor || {};
                        const vendorProfile = vendor.vendor || {};
                        const product = query.product;
                        const order = query.order;
                        const messages = query.messages || [];

                        // Header info
                        setText('#thread-title', `Query #${query.id}`);
                        setText('#thread-subtitle', `Started on ${formatDate(query.created_at)}`);

                        const typeBadge = document.querySelector('#query-type-badge');
                        if (query.type === 'customer_vendor') {
                            typeBadge.textContent = 'Customer ↔ Vendor';
                            typeBadge.className = 'px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800';
                        } else {
                            typeBadge.textContent = 'Customer ↔ Admin';
                            typeBadge.className = 'px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800';
                        }

                        // Customer Card
                        setText('#customer-name', customer.name);
                        setText('#customer-email', customer.email);
                        setText('#customer-phone', customer.phone);
                        setText('#customer-address', customer.address);

                        // Vendor Card
                        if (vendor.name || vendorProfile.store_name) {
                            setText('#vendor-store', vendorProfile.store_name || vendor.name);
                            setText('#vendor-name', vendor.name);
                            setText('#vendor-email', vendor.email);
                            setText('#vendor-phone', vendor.phone);
                        } else {
                            document.querySelector('#vendor-card').classList.add('hidden');
                        }

                        // Product Card
                        const productContent = document.querySelector('#product-content');
                        if (product) {
                            productContent.innerHTML = `
                                <div class="space-y-3">
                                    <div>
                                        <p class="font-bold text-base text-gray-900">${escapeHtml(product.name)}</p>
                                        <p class="text-sm font-semibold text-[#204d4f] mt-1">₹${Number(product.price).toFixed(2)}</p>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 text-xs text-gray-600 bg-gray-50 p-3 rounded-lg">
                                        <div><span class="font-medium text-gray-400 uppercase">Category:</span> ${escapeHtml(product.category?.name || 'General')}</div>
                                        <div><span class="font-medium text-gray-400 uppercase">Stock:</span> ${escapeHtml(product.stock ?? 'N/A')}</div>
                                    </div>
                                    ${product.description ? `<p class="text-xs text-gray-500 line-clamp-3">${escapeHtml(product.description)}</p>` : ''}
                                </div>
                            `;
                        } else {
                            productContent.innerHTML = `<p class="text-xs text-gray-400 italic">No specific product linked to this query.</p>`;
                        }

                        // Order Card
                        if (order) {
                            setText('#order-number', order.order_number);
                            setText('#order-status', order.order_status);
                            setText('#order-amount', order.total_amount ? `₹${Number(order.total_amount).toFixed(2)}` : null);
                            document.querySelector('#order-card').classList.remove('hidden');
                        }

                        // Render Messages
                        if (messages.length === 0) {
                            messagesContainer.innerHTML = `<div class="py-12 text-center text-sm text-gray-400 italic">No messages in this query yet.</div>`;
                        } else {
                            messagesContainer.innerHTML = messages.map((msg) => {
                                const isCustomer = msg.sender_id === customer.id;
                                const senderName = msg.sender?.name || (isCustomer ? 'Customer' : 'Vendor');

                                return `
                                    <div class="flex flex-col ${isCustomer ? 'items-start' : 'items-end'}">
                                        <div class="flex items-center gap-2 mb-1 px-1">
                                            <span class="text-xs font-semibold ${isCustomer ? 'text-[#204d4f]' : 'text-gray-700'}">${escapeHtml(senderName)}</span>
                                            <span class="text-[10px] text-gray-400">${formatDate(msg.created_at)}</span>
                                        </div>
                                        <div class="max-w-[85%] rounded-2xl px-4 py-3 text-sm shadow-sm ${isCustomer ? 'bg-white text-gray-800 border border-gray-100 rounded-tl-sm' : 'bg-[#204d4f] text-white rounded-tr-sm'}">
                                            <p class="whitespace-pre-wrap">${escapeHtml(msg.body || '')}</p>
                                            ${msg.attachment_path ? `
                                                <div class="mt-2 pt-2 border-t ${isCustomer ? 'border-gray-100' : 'border-white/20'} text-xs">
                                                    <span class="underline">Attachment: ${escapeHtml(msg.attachment_path)}</span>
                                                </div>
                                            ` : ''}
                                        </div>
                                    </div>
                                `;
                            }).join('');
                            messagesContainer.scrollTop = messagesContainer.scrollHeight;
                        }

                        loading.classList.add('hidden');
                        details.classList.remove('hidden');
                    } catch (error) {
                        loading.classList.add('hidden');
                        showError(error.message);
                    }
                };

                loadQueryDetails();
            })();
        </script>
    @endpush
@endsection
