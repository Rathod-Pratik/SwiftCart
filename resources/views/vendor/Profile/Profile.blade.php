@extends('layouts.Vendor')

@section('content')
    <x-slot name="title">Vendor Profile</x-slot>

    <div class="p-4 sm:p-6">
        <div class="mb-6">
            <p class="text-sm font-medium text-[#3d6f6d]">Account settings</p>
            <h1 class="text-2xl font-bold text-gray-900">Vendor Profile</h1>
            <p class="mt-1 text-sm text-gray-600">Manage your personal and business information.</p>
        </div>

        <div id="profile-message" class="mb-4 hidden rounded-lg px-4 py-3 text-sm" role="alert"></div>
        <div id="profile-loading" class="rounded-xl bg-white p-8 text-center text-sm text-gray-500 shadow-sm">Loading profile...</div>

        <div id="profile-content" class="hidden max-w-5xl">
            <form id="profile-form" class="rounded-xl bg-white p-5 shadow-sm sm:p-8">
                @csrf
                <div class="mb-8 flex items-center gap-4 border-b border-gray-100 pb-6">
                    <div id="profile-avatar" class="flex h-16 w-16 items-center justify-center rounded-full bg-[#b8d8da] text-xl font-bold text-[#3d6f6d]"></div>
                    <div>
                        <h2 id="profile-heading" class="text-xl font-bold text-gray-900"></h2>
                        <p id="profile-email" class="mt-1 text-sm text-gray-500"></p>
                        <span class="mt-2 inline-flex rounded-full bg-[#e6f7f5] px-3 py-1 text-xs font-semibold capitalize text-[#2f7770]">Vendor</span>
                    </div>
                </div>

                <h3 class="mb-4 text-lg font-bold text-gray-900">Personal information</h3>
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <label class="text-sm font-semibold text-gray-700">Name
                        <input name="name" required maxlength="50" class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-3 font-normal outline-none focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                    </label>
                    <label class="text-sm font-semibold text-gray-700">Email
                        <input name="email" type="email" required maxlength="255" class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-3 font-normal outline-none focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                    </label>
                    <label class="text-sm font-semibold text-gray-700">Phone
                        <input name="phone" minlength="10" maxlength="15" class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-3 font-normal outline-none focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                    </label>
                    <label class="text-sm font-semibold text-gray-700 sm:col-span-2">Address
                        <textarea name="address" rows="3" minlength="5" maxlength="255" class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-3 font-normal outline-none focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20"></textarea>
                    </label>
                </div>

                <h3 class="mb-4 mt-8 border-t border-gray-100 pt-8 text-lg font-bold text-gray-900">Business and bank information</h3>
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    @foreach ([
                        'account_holder_name' => 'Account holder name',
                        'company_name' => 'Company name',
                        'bank_name' => 'Bank name',
                        'account_number' => 'Account number',
                        'account_type' => 'Account type',
                        'ifsc_code' => 'IFSC code',
                        'branch_name' => 'Branch name',
                    ] as $field => $label)
                        <label class="text-sm font-semibold text-gray-700">{{ $label }}
                            <input name="{{ $field }}" type="{{ $field === 'account_number' ? 'password' : 'text' }}" {{ in_array($field, ['account_holder_name', 'bank_name', 'account_number', 'account_type', 'ifsc_code', 'branch_name'], true) ? 'required' : '' }} class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-3 font-normal outline-none focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                        </label>
                    @endforeach
                </div>
                <p class="mt-3 text-xs text-gray-500">For security, the saved account number is not returned. Enter it again only when updating bank details.</p>

                <div class="mt-8 flex justify-end border-t border-gray-100 pt-5">
                    <button type="submit" class="rounded-lg bg-[#3d6f6d] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#315b59]">
                        <span id="submit-label">Update Profile</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            (() => {
                const profileUrl = @json(url('/api/profile'));
                const bankUrl = @json(url('/api/vendor/bank-details'));
                const form = document.querySelector('#profile-form');
                const loading = document.querySelector('#profile-loading');
                const content = document.querySelector('#profile-content');
                const message = document.querySelector('#profile-message');
                const submit = form.querySelector('button[type="submit"]');
                const label = document.querySelector('#submit-label');
                const csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? @json(csrf_token());

                const setMessage = (text, isError = false) => {
                    message.textContent = text;
                    message.className = `mb-4 rounded-lg px-4 py-3 text-sm ${isError ? 'bg-red-50 text-red-700' : 'bg-green-50 text-green-700'}`;
                };
                const setField = (name, value) => { if (form.elements[name]) form.elements[name].value = value ?? ''; };

                const loadProfile = async () => {
                    try {
                        const response = await fetch(profileUrl, { headers: { Accept: 'application/json' } });
                        const payload = await response.json();
                        if (!response.ok || !payload.user) throw new Error(payload.message || 'Unable to load profile.');
                        const user = payload.user;
                        const bank = user.bank_detail || {};
                        setField('name', user.name);
                        setField('email', user.email);
                        setField('phone', user.phone);
                        setField('address', user.address);
                        Object.keys(bank).forEach((field) => {
                            if (field !== 'account_number') setField(field, bank[field]);
                        });
                        document.querySelector('#profile-heading').textContent = user.name;
                        document.querySelector('#profile-email').textContent = user.email;
                        document.querySelector('#profile-avatar').textContent = user.name.split(' ').map((part) => part[0]).slice(0, 2).join('').toUpperCase();
                        loading.classList.add('hidden');
                        content.classList.remove('hidden');
                    } catch (error) {
                        loading.classList.add('hidden');
                        setMessage(error.message, true);
                    }
                };

                form.addEventListener('submit', async (event) => {
                    event.preventDefault();
                    submit.disabled = true;
                    label.textContent = 'Updating...';
                    const values = Object.fromEntries(new FormData(form));
                    delete values._token;
                    const personal = {
                        name: values.name,
                        email: values.email,
                        phone: values.phone,
                        address: values.address,
                    };
                    const bank = {
                        account_holder_name: values.account_holder_name,
                        company_name: values.company_name,
                        bank_name: values.bank_name,
                        account_number: values.account_number,
                        account_type: values.account_type,
                        ifsc_code: values.ifsc_code,
                        branch_name: values.branch_name,
                    };
                    try {
                        const profileResponse = await fetch(profileUrl, {
                            method: 'PATCH',
                            headers: { Accept: 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                            body: JSON.stringify(personal),
                        });
                        const profilePayload = await profileResponse.json();
                        if (!profileResponse.ok || profilePayload.message !== 'Profile updated successfully') {
                            throw new Error(profilePayload.errors ? Object.values(profilePayload.errors).flat().join(' ') : profilePayload.message || 'Unable to update profile.');
                        }
                        if (bank.account_number) {
                            const bankResponse = await fetch(bankUrl, {
                                method: 'PATCH',
                                headers: { Accept: 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                                body: JSON.stringify(bank),
                            });
                            const bankPayload = await bankResponse.json();
                            if (!bankResponse.ok || !bankPayload.bank_detail) throw new Error(bankPayload.message || 'Unable to update bank details.');
                        }
                        setMessage('Profile updated successfully.');
                    } catch (error) {
                        setMessage(error.message, true);
                    } finally {
                        submit.disabled = false;
                        label.textContent = 'Update Profile';
                    }
                });
                loadProfile();
            })();
        </script>
    @endpush
@endsection
