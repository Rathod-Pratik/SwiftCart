@extends('layouts.Vendor')

@section('content')
    @php($queryId = request()->query('_id'))

    <x-slot name="title">Query Details</x-slot>

    <div class="p-4 sm:p-6">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-[#3d6f6d]">Customer support</p>
                <h1 class="text-2xl font-bold text-gray-900">Query Details</h1>
                <p class="mt-1 text-sm text-gray-600">Review the customer conversation and respond.</p>
            </div>
            <a href="{{ route('vendor.query') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">Back to Queries</a>
        </div>

        <div id="query-message" class="mb-4 hidden rounded-lg px-4 py-3 text-sm" role="alert"></div>
        <div id="query-loading" class="rounded-xl bg-white p-8 text-center text-sm text-gray-500 shadow-sm">Loading query details...</div>

        <section id="query-details" class="hidden grid max-w-6xl grid-cols-1 gap-6 lg:grid-cols-[minmax(0,1fr)_18rem]">
            <div class="rounded-xl bg-white p-5 shadow-sm sm:p-8">
                <div class="mb-6 border-b border-gray-100 pb-5">
                    <h2 id="customer-name" class="text-xl font-bold text-gray-900"></h2>
                    <p id="customer-email" class="mt-1 text-sm text-gray-500"></p>
                    <p id="product-name" class="mt-3 text-sm text-[#3d6f6d]"></p>
                </div>
                <div id="messages" class="flex max-h-[32rem] min-h-48 flex-col gap-3 overflow-y-auto rounded-lg bg-gray-50 p-4"></div>
                <form id="message-form" class="mt-4 flex flex-col gap-3 sm:flex-row">
                    <label class="sr-only" for="message-body">Reply</label>
                    <textarea id="message-body" rows="2" required maxlength="2000" placeholder="Write a reply..." class="min-w-0 flex-1 resize-y rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20"></textarea>
                    <button type="submit" class="rounded-lg bg-[#3d6f6d] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#315c5a]">Send reply</button>
                </form>
            </div>

            <aside class="h-fit rounded-xl bg-white p-5 shadow-sm">
                <h2 class="mb-4 text-lg font-bold text-gray-900">Customer details</h2>
                <dl class="grid gap-4 text-sm">
                    <div><dt class="text-xs font-semibold uppercase text-gray-500">Phone</dt><dd id="customer-phone" class="mt-1 text-gray-700"></dd></div>
                    <div><dt class="text-xs font-semibold uppercase text-gray-500">Address</dt><dd id="customer-address" class="mt-1 text-gray-700"></dd></div>
                    <div><dt class="text-xs font-semibold uppercase text-gray-500">Order</dt><dd id="order-number" class="mt-1 text-gray-700"></dd></div>
                    <div><dt class="text-xs font-semibold uppercase text-gray-500">Order status</dt><dd id="order-status" class="mt-1 capitalize text-gray-700"></dd></div>
                </dl>
            </aside>
        </section>
    </div>

    @push('scripts')
        <script>
            (() => {
                const id = @json($queryId);
                const apiUrl = @json(url('/api/vendor/queries'));
                const loading = document.querySelector('#query-loading');
                const details = document.querySelector('#query-details');
                const message = document.querySelector('#query-message');
                const messages = document.querySelector('#messages');
                const form = document.querySelector('#message-form');
                const body = document.querySelector('#message-body');
                const csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? @json(csrf_token());

                const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (character) => ({
                    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;',
                }[character]));
                const set = (selector, value) => { document.querySelector(selector).textContent = value || 'Not provided'; };
                const showMessage = (text, isError = true) => {
                    message.textContent = text;
                    message.className = `mb-4 rounded-lg px-4 py-3 text-sm ${isError ? 'bg-red-50 text-red-700' : 'bg-green-50 text-green-700'}`;
                };
                const loadMessages = async () => {
                    const response = await fetch(`${apiUrl}/${id}/messages`, { headers: { Accept: 'application/json' } });
                    const payload = await response.json();
                    if (!response.ok || !payload.success) throw new Error(payload.message || 'Unable to load messages.');
                    const items = payload.data.data.slice().reverse();
                    messages.innerHTML = items.length ? items.map((item) => `
                        <div class="max-w-[85%] rounded-lg px-4 py-3 ${item.sender_id === payload.current_user_id ? 'self-end bg-[#3d6f6d] text-white' : 'self-start bg-white text-gray-700 shadow-sm'}">
                            <p class="mb-1 text-xs font-semibold opacity-70">${escapeHtml(item.sender?.name || 'Participant')}</p>
                            <p class="whitespace-pre-wrap text-sm">${escapeHtml(item.body || 'Attachment')}</p>
                            <p class="mt-2 text-right text-[11px] opacity-70">${escapeHtml(new Date(item.created_at).toLocaleString())}</p>
                        </div>
                    `).join('') : '<p class="py-8 text-center text-sm text-gray-500">No messages yet.</p>';
                    messages.scrollTop = messages.scrollHeight;
                };
                const loadQuery = async () => {
                    if (!id) { loading.classList.add('hidden'); showMessage('A query ID is required in the URL, for example: ?_id=1'); return; }
                    try {
                        const response = await fetch(`${apiUrl}/${id}`, { headers: { Accept: 'application/json' } });
                        const payload = await response.json();
                        if (!response.ok || !payload.success) throw new Error(payload.message || 'Unable to load query.');
                        const query = payload.data;
                        set('#customer-name', query.customer?.name);
                        set('#customer-email', query.customer?.email);
                        set('#customer-phone', query.customer?.phone);
                        set('#customer-address', query.customer?.address);
                        set('#product-name', `Product: ${query.product?.name || 'General query'}`);
                        set('#order-number', query.order?.order_number);
                        set('#order-status', query.order?.order_status);
                        await loadMessages();
                        loading.classList.add('hidden');
                        details.classList.remove('hidden');
                    } catch (exception) { loading.classList.add('hidden'); showMessage(exception.message); }
                };
                form.addEventListener('submit', async (event) => {
                    event.preventDefault();
                    if (!body.value.trim()) return;
                    const button = form.querySelector('button');
                    button.disabled = true;
                    try {
                        const response = await fetch(`${apiUrl}/${id}/messages`, {
                            method: 'POST',
                            headers: { Accept: 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                            body: JSON.stringify({ body: body.value.trim() }),
                        });
                        const payload = await response.json();
                        if (!response.ok || !payload.success) throw new Error(payload.message || 'Unable to send reply.');
                        body.value = '';
                        await loadMessages();
                    } catch (exception) { showMessage(exception.message); } finally { button.disabled = false; }
                });
                loadQuery();
            })();
        </script>
    @endpush
@endsection