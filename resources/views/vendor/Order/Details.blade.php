@extends('layouts.Vendor')

@section('content')
    @php($orderId = request()->query('_id'))

    <x-slot name="title">Order Details</x-slot>

    <div class="p-4 sm:p-6">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-[#3d6f6d]">Order management</p>
                <h1 class="text-2xl font-bold text-gray-900">Order Details</h1>
                <p class="mt-1 text-sm text-gray-600">Review customer, item, payment, and delivery information.</p>
            </div>
            <a href="{{ route('vendor.order') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">Back to Orders</a>
        </div>

        <div id="order-status-message" class="mb-4 hidden rounded-lg px-4 py-3 text-sm" role="alert"></div>
        <div id="order-loading" class="rounded-xl bg-white p-8 text-center text-sm text-gray-500 shadow-sm">Loading order details...</div>

        <section id="order-details" class="hidden max-w-5xl rounded-xl bg-white p-5 shadow-sm sm:p-8">
            <div class="mb-8 flex flex-col gap-4 border-b border-gray-100 pb-6 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p class="text-sm text-gray-500">Order number</p>
                    <h2 id="order-number" class="mt-1 text-2xl font-bold text-gray-900"></h2>
                    <p id="order-date" class="mt-2 text-sm text-gray-500"></p>
                </div>
                <label class="text-sm font-semibold text-gray-700">Order status
                    <select id="order-status" class="mt-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm capitalize outline-none focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="processing">Processing</option>
                        <option value="shipped">Shipped</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="returned">Returned</option>
                    </select>
                </label>
            </div>

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                <div>
                    <h3 class="mb-4 text-lg font-bold text-gray-900">Customer</h3>
                    <dl class="grid gap-4">
                        <div class="rounded-lg bg-gray-50 p-4"><dt class="text-xs font-semibold uppercase text-gray-500">Name</dt><dd id="customer-name" class="mt-2 text-sm text-gray-700"></dd></div>
                        <div class="rounded-lg bg-gray-50 p-4"><dt class="text-xs font-semibold uppercase text-gray-500">Email</dt><dd id="customer-email" class="mt-2 text-sm text-gray-700"></dd></div>
                        <div class="rounded-lg bg-gray-50 p-4"><dt class="text-xs font-semibold uppercase text-gray-500">Phone</dt><dd id="shipping-phone" class="mt-2 text-sm text-gray-700"></dd></div>
                        <div class="rounded-lg bg-gray-50 p-4"><dt class="text-xs font-semibold uppercase text-gray-500">Shipping address</dt><dd id="shipping-address" class="mt-2 text-sm leading-6 text-gray-700"></dd></div>
                    </dl>
                </div>
                <div>
                    <h3 class="mb-4 text-lg font-bold text-gray-900">Payment</h3>
                    <dl class="grid gap-4">
                        <div class="rounded-lg bg-gray-50 p-4"><dt class="text-xs font-semibold uppercase text-gray-500">Payment method</dt><dd id="payment-method" class="mt-2 text-sm capitalize text-gray-700"></dd></div>
                        <div class="rounded-lg bg-gray-50 p-4"><dt class="text-xs font-semibold uppercase text-gray-500">Payment status</dt><dd id="payment-status" class="mt-2 text-sm capitalize text-gray-700"></dd></div>
                        <div class="rounded-lg bg-gray-50 p-4"><dt class="text-xs font-semibold uppercase text-gray-500">Transaction ID</dt><dd id="transaction-id" class="mt-2 text-sm text-gray-700"></dd></div>
                    </dl>
                </div>
            </div>

            <div class="mt-8">
                <h3 class="mb-4 text-lg font-bold text-gray-900">Your items</h3>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[600px] text-left text-sm">
                        <thead class="border-b border-gray-200 text-xs uppercase tracking-wide text-gray-500"><tr><th class="px-4 py-3">Product</th><th class="px-4 py-3">Quantity</th><th class="px-4 py-3">Price</th><th class="px-4 py-3 text-right">Total</th></tr></thead>
                        <tbody id="order-items" class="divide-y divide-gray-100"></tbody>
                    </table>
                </div>
            </div>

            <dl class="mt-6 ml-auto grid max-w-sm gap-3 border-t border-gray-100 pt-5 text-sm">
                <div class="flex justify-between"><dt class="text-gray-500">Subtotal</dt><dd id="subtotal" class="font-medium text-gray-900"></dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Shipping</dt><dd id="shipping-cost" class="font-medium text-gray-900"></dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Tax</dt><dd id="tax" class="font-medium text-gray-900"></dd></div>
                <div class="flex justify-between border-t border-gray-100 pt-3 text-base"><dt class="font-bold text-gray-900">Total</dt><dd id="total" class="font-bold text-gray-900"></dd></div>
            </dl>
        </section>
    </div>

    @push('scripts')
        <script>
            (() => {
                const id = @json($orderId);
                const apiUrl = @json(url('/api/vendor/orders'));
                const loading = document.querySelector('#order-loading');
                const details = document.querySelector('#order-details');
                const message = document.querySelector('#order-status-message');
                const csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? @json(csrf_token());

                const set = (selector, value) => { document.querySelector(selector).textContent = value ?? 'Not provided'; };
                const money = (value) => `₹${Number(value ?? 0).toFixed(2)}`;
                const date = (value) => value ? new Intl.DateTimeFormat(undefined, { dateStyle: 'medium', timeStyle: 'short' }).format(new Date(value)) : 'Not available';
                const showMessage = (text, isError = true) => {
                    message.textContent = text;
                    message.className = `mb-4 rounded-lg px-4 py-3 text-sm ${isError ? 'bg-red-50 text-red-700' : 'bg-green-50 text-green-700'}`;
                };
                const loadOrder = async () => {
                    if (!id) { loading.classList.add('hidden'); showMessage('An order ID is required in the URL, for example: ?_id=1'); return; }
                    try {
                        const response = await fetch(`${apiUrl}/${id}`, { headers: { Accept: 'application/json' } });
                        const payload = await response.json();
                        if (!response.ok || !payload.success) throw new Error(payload.message || 'Unable to load order.');
                        const order = payload.data;
                        set('#order-number', order.order_number);
                        set('#order-date', date(order.created_at));
                        set('#customer-name', order.shipping_name);
                        set('#customer-email', order.user?.email);
                        set('#shipping-phone', order.shipping_phone);
                        set('#shipping-address', [order.shipping_address, order.shipping_city, order.shipping_state, order.shipping_postal_code, order.shipping_country].filter(Boolean).join(', '));
                        set('#payment-method', order.payment_method);
                        set('#payment-status', order.payment_status);
                        set('#transaction-id', order.transaction_id);
                        set('#subtotal', money(order.subtotal));
                        set('#shipping-cost', money(order.shipping_cost));
                        set('#tax', money(order.tax));
                        set('#total', money(order.total_amount));
                        document.querySelector('#order-status').value = order.order_status;
                        document.querySelector('#order-items').innerHTML = order.items.map((item) => `<tr><td class="px-4 py-4 font-medium text-gray-900">${item.product?.name || item.product_name}</td><td class="px-4 py-4">${item.quantity}</td><td class="px-4 py-4">${money(item.price)}</td><td class="px-4 py-4 text-right font-semibold">${money(item.total_price)}</td></tr>`).join('');
                        loading.classList.add('hidden');
                        details.classList.remove('hidden');
                    } catch (exception) { loading.classList.add('hidden'); showMessage(exception.message); }
                };
                document.querySelector('#order-status').addEventListener('change', async (event) => {
                    try {
                        const response = await fetch(`${apiUrl}/${id}/status`, { method: 'PATCH', headers: { Accept: 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf }, body: JSON.stringify({ status: event.target.value }) });
                        const payload = await response.json();
                        if (!response.ok || !payload.success) throw new Error(payload.message || 'Unable to update order status.');
                        showMessage(payload.message, false);
                    } catch (exception) { showMessage(exception.message); }
                });
                loadOrder();
            })();
        </script>
    @endpush
@endsection
