@extends('layouts.Vendor')

@section('content')
    <x-slot name="title">Vendor Profile</x-slot>

    @php
        /** @var \App\Models\User $vendorAuth */
        $vendorAuth = auth()->user();
        $initials   = collect(explode(' ', $vendorAuth->name ?? 'Vendor'))
            ->filter()->take(2)->map(fn($p) => mb_substr($p, 0, 1))->join('');
        $initials   = strtoupper($initials ?: 'VD');
    @endphp

    <div class="p-4 sm:p-6 lg:p-8">

        {{-- Flash Alert --}}
        <div id="profile-alert" class="mb-6 hidden items-center justify-between rounded-xl px-5 py-4 text-sm font-medium shadow-sm transition-all duration-300" role="alert">
            <div class="flex items-center gap-3">
                <span id="alert-icon"></span>
                <span id="alert-message"></span>
            </div>
            <button type="button" onclick="dismissAlert()" class="rounded-lg p-1 opacity-70 transition hover:opacity-100">
                <x-lucide-x class="h-4 w-4" />
            </button>
        </div>

        {{-- ── Hero Header Card ── --}}
        <div class="relative mb-8 overflow-hidden rounded-2xl bg-gradient-to-r from-[#224e51] via-[#2d5d60] to-[#3d6f6d] p-6 text-white shadow-md sm:p-8">
            <div class="absolute -right-16 -top-16 h-64 w-64 rounded-full bg-white/5 blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-16 right-32 h-48 w-48 rounded-full bg-[#F4C95D]/10 blur-xl pointer-events-none"></div>

            <div class="relative flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex flex-col items-center gap-5 text-center sm:flex-row sm:items-start sm:text-left">

                    {{-- Avatar with Upload --}}
                    <div class="relative group">
                        <div id="header-avatar"
                             class="flex h-24 w-24 shrink-0 items-center justify-center overflow-hidden rounded-2xl bg-[#F4C95D] text-3xl font-extrabold text-[#224e51] shadow-lg ring-4 ring-white/20 transition-all duration-300 group-hover:ring-[#F4C95D] sm:h-28 sm:w-28">
                            @if (!empty($vendorAuth->image))
                                <img id="header-avatar-img"
                                     src=""
                                     data-s3-key="{{ $vendorAuth->image }}"
                                     alt="{{ $vendorAuth->name }}"
                                     onerror="this.classList.add('hidden'); document.getElementById('header-avatar-initials').classList.remove('hidden');"
                                     class="h-full w-full rounded-2xl object-cover">
                                <span id="header-avatar-initials" class="hidden">{{ $initials }}</span>
                            @else
                                <span id="header-avatar-initials">{{ $initials }}</span>
                            @endif
                        </div>

                        {{-- Spinner overlay during upload --}}
                        <div id="header-avatar-spinner"
                             class="absolute inset-0 hidden items-center justify-center rounded-2xl bg-black/60">
                            <svg class="h-7 w-7 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                        </div>

                        <label for="avatar-input" title="Change Photo"
                               class="absolute -bottom-2 -right-2 flex h-9 w-9 cursor-pointer items-center justify-center rounded-xl bg-white text-[#224e51] shadow-md transition hover:scale-110 hover:bg-[#F4C95D]">
                            <x-lucide-camera class="h-4 w-4" />
                            <span class="sr-only">Change profile photo</span>
                        </label>
                        <input id="avatar-input" type="file" accept="image/jpeg,image/png,image/webp" class="hidden">
                    </div>

                    {{-- Identity Info --}}
                    <div>
                        <div class="flex flex-wrap items-center justify-center gap-2.5 sm:justify-start">
                            <h1 id="header-name" class="text-2xl font-bold tracking-tight text-white sm:text-3xl">{{ $vendorAuth->name }}</h1>
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-[#F4C95D] px-3 py-0.5 text-xs font-bold text-[#224e51] shadow-sm">
                                <x-lucide-store class="h-3.5 w-3.5" />
                                Vendor
                            </span>
                        </div>
                        <p id="header-email" class="mt-1.5 flex items-center justify-center gap-2 text-sm text-[#E1EBE8]/90 sm:justify-start">
                            <x-lucide-mail class="h-4 w-4 shrink-0 text-[#F4C95D]" />
                            <span>{{ $vendorAuth->email }}</span>
                        </p>
                        <div class="mt-4 flex flex-wrap items-center justify-center gap-2 text-xs sm:justify-start">
                            <span class="inline-flex items-center gap-1 rounded-lg bg-white/10 px-2.5 py-1 text-white backdrop-blur-sm">
                                <x-lucide-calendar class="h-3.5 w-3.5 text-[#F4C95D]" />
                                Joined {{ $vendorAuth->created_at?->format('M Y') ?? 'Recently' }}
                            </span>
                            @if ($vendorAuth->hasVerifiedEmail())
                                <span class="inline-flex items-center gap-1 rounded-lg border border-emerald-400/30 bg-emerald-500/20 px-2.5 py-1 text-emerald-200">
                                    <x-lucide-check-circle class="h-3.5 w-3.5 text-emerald-300" />
                                    Email Verified
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-lg border border-amber-400/30 bg-amber-500/20 px-2.5 py-1 text-amber-200">
                                    <x-lucide-alert-circle class="h-3.5 w-3.5 text-amber-300" />
                                    Unverified Email
                                </span>
                            @endif
                            <span class="inline-flex items-center gap-1 rounded-lg bg-white/10 px-2.5 py-1 text-white backdrop-blur-sm">
                                <x-lucide-check class="h-3.5 w-3.5 text-emerald-400" />
                                Store Active
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex shrink-0 items-center justify-center gap-3 sm:justify-end">
                    <button type="button" onclick="Logout()"
                            class="inline-flex items-center gap-2 rounded-xl bg-white/10 px-4 py-2.5 text-sm font-semibold text-white backdrop-blur-sm transition duration-200 hover:bg-red-600/80 focus:outline-none">
                        <x-lucide-log-out class="h-4 w-4" />
                        <span>Sign Out</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- ── Tab Navigation ── --}}
        <div class="mb-6 border-b border-gray-200">
            <nav class="-mb-px flex space-x-2 overflow-x-auto pb-1 sm:space-x-6" aria-label="Profile Tabs">
                <button type="button" onclick="switchTab('general')" id="tab-btn-general"
                        class="tab-button cursor-pointer inline-flex items-center gap-2 whitespace-nowrap border-b-2 border-[#224e51] px-3 py-3 text-sm font-semibold text-[#224e51] transition">
                    <x-lucide-user class="h-4 w-4" />
                    Personal Info
                </button>
                <button type="button" onclick="switchTab('security')" id="tab-btn-security"
                        class="tab-button cursor-pointer inline-flex items-center gap-2 whitespace-nowrap border-b-2 border-transparent px-3 py-3 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 transition">
                    <x-lucide-lock class="h-4 w-4" />
                    Password & Security
                </button>
                <button type="button" onclick="switchTab('business')" id="tab-btn-business"
                        class="tab-button cursor-pointer inline-flex items-center gap-2 whitespace-nowrap border-b-2 border-transparent px-3 py-3 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 transition">
                    <x-lucide-building-2 class="h-4 w-4" />
                    Business & Bank
                </button>
                <button type="button" onclick="switchTab('danger')" id="tab-btn-danger"
                        class="tab-button cursor-pointer inline-flex items-center gap-2 whitespace-nowrap border-b-2 border-transparent px-3 py-3 text-sm font-medium text-red-500 hover:border-red-300 transition">
                    <x-lucide-trash-2 class="h-4 w-4" />
                    Account
                </button>
                <button type="button" onclick="switchTab('documents')" id="tab-btn-documents"
                        class="tab-button cursor-pointer inline-flex items-center gap-2 whitespace-nowrap border-b-2 border-transparent px-3 py-3 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 transition">
                    <x-lucide-file-text class="h-4 w-4" />
                    Documents
                </button>
            </nav>
        </div>

        {{-- ══════════════════════════════════════════════ --}}
        {{-- TAB 1 — Personal Information                  --}}
        {{-- ══════════════════════════════════════════════ --}}
        <div id="tab-panel-general" class="tab-panel">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                {{-- Form --}}
                <div class="lg:col-span-2">
                    <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm sm:p-8">
                        <div class="mb-6 border-b border-gray-100 pb-4">
                            <h2 class="text-lg font-bold text-gray-900">Personal Details</h2>
                            <p class="mt-1 text-sm text-gray-500">Update your name, contact, and address information.</p>
                        </div>

                        <form id="profile-general-form" novalidate class="space-y-6">
                            @csrf
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <div>
                                    <label for="vendor-name" class="block text-sm font-semibold text-gray-700">
                                        Full Name <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative mt-2">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                            <x-lucide-user class="h-4 w-4" />
                                        </div>
                                        <input id="vendor-name" name="name" type="text" required minlength="2" maxlength="50"
                                               value="{{ old('name', $vendorAuth->name) }}" placeholder="Your full name"
                                               class="w-full rounded-xl border border-gray-300 py-3 pl-10 pr-4 text-sm text-gray-900 outline-none transition focus:border-[#224e51] focus:ring-2 focus:ring-[#224e51]/20">
                                    </div>
                                    <p id="error-name" class="mt-1.5 hidden text-xs text-red-600"></p>
                                </div>

                                <div>
                                    <label for="vendor-email" class="block text-sm font-semibold text-gray-700">
                                        Email Address <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative mt-2">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                            <x-lucide-mail class="h-4 w-4" />
                                        </div>
                                        <input id="vendor-email" name="email" type="email" required maxlength="255"
                                               value="{{ old('email', $vendorAuth->email) }}" placeholder="you@example.com"
                                               class="w-full rounded-xl border border-gray-300 py-3 pl-10 pr-4 text-sm text-gray-900 outline-none transition focus:border-[#224e51] focus:ring-2 focus:ring-[#224e51]/20">
                                    </div>
                                    <p id="error-email" class="mt-1.5 hidden text-xs text-red-600"></p>
                                </div>

                                <div>
                                    <label for="vendor-phone" class="block text-sm font-semibold text-gray-700">Phone Number</label>
                                    <div class="relative mt-2">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                            <x-lucide-phone class="h-4 w-4" />
                                        </div>
                                        <input id="vendor-phone" name="phone" type="tel" minlength="10" maxlength="15"
                                               value="{{ old('phone', $vendorAuth->phone) }}" placeholder="+91 98765 43210"
                                               class="w-full rounded-xl border border-gray-300 py-3 pl-10 pr-4 text-sm text-gray-900 outline-none transition focus:border-[#224e51] focus:ring-2 focus:ring-[#224e51]/20">
                                    </div>
                                    <p id="error-phone" class="mt-1.5 hidden text-xs text-red-600"></p>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700">Assigned Role</label>
                                    <div class="relative mt-2">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                            <x-lucide-store class="h-4 w-4" />
                                        </div>
                                        <input type="text" readonly disabled value="Verified Vendor"
                                               class="w-full cursor-not-allowed rounded-xl border border-gray-200 bg-gray-50 py-3 pl-10 pr-4 text-sm font-medium text-gray-600">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="vendor-address" class="block text-sm font-semibold text-gray-700">Address</label>
                                <div class="relative mt-2">
                                    <div class="pointer-events-none absolute left-3.5 top-3.5 text-gray-400">
                                        <x-lucide-map-pin class="h-4 w-4" />
                                    </div>
                                    <textarea id="vendor-address" name="address" rows="3" minlength="5" maxlength="255"
                                              placeholder="Your business or home address"
                                              class="w-full rounded-xl border border-gray-300 py-3 pl-10 pr-4 text-sm text-gray-900 outline-none transition focus:border-[#224e51] focus:ring-2 focus:ring-[#224e51]/20">{{ old('address', $vendorAuth->address) }}</textarea>
                                </div>
                                <p id="error-address" class="mt-1.5 hidden text-xs text-red-600"></p>
                            </div>

                            <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-5">
                                <button type="reset" class="rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                                    Reset
                                </button>
                                <x-Button type="submit" id="btn-save-general" class="!bg-[#224e51] hover:!bg-[#1a3d3f] !px-6 !py-2.5 shadow-sm">
                                    <x-lucide-save class="mr-2 h-4 w-4" />
                                    <span id="btn-save-general-text">Save Changes</span>
                                </x-Button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="space-y-6">
                    {{-- Email Verification --}}
                    <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl {{ $vendorAuth->hasVerifiedEmail() ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                @if ($vendorAuth->hasVerifiedEmail())
                                    <x-lucide-check-circle class="h-5 w-5" />
                                @else
                                    <x-lucide-alert-circle class="h-5 w-5" />
                                @endif
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-gray-900">Email Verification</h3>
                                <p class="text-xs text-gray-500">
                                    {{ $vendorAuth->hasVerifiedEmail() ? 'Verified on '.$vendorAuth->email_verified_at->format('M d, Y') : 'Email is not verified yet.' }}
                                </p>
                            </div>
                        </div>
                        @if (!$vendorAuth->hasVerifiedEmail())
                            <div class="mt-4 border-t border-gray-100 pt-3">
                                <button type="button" id="btn-resend-verification" onclick="resendVerification()"
                                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-amber-500 px-3 py-2 text-xs font-semibold text-white transition hover:bg-amber-600">
                                    <x-lucide-mail class="h-3.5 w-3.5" />
                                    Resend Verification Email
                                </button>
                            </div>
                        @endif
                    </div>

                    {{-- Account Overview --}}
                    <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                        <h3 class="mb-4 flex items-center gap-2 text-sm font-bold text-gray-900">
                            <x-lucide-store class="h-4 w-4 text-[#224e51]" />
                            Vendor Account
                        </h3>
                        <dl class="space-y-3 text-xs">
                            <div class="flex justify-between border-b border-gray-50 py-1.5">
                                <dt class="text-gray-500">Vendor ID</dt>
                                <dd class="font-semibold text-gray-900">#VND-{{ str_pad($vendorAuth->id, 4, '0', STR_PAD_LEFT) }}</dd>
                            </div>
                            <div class="flex justify-between border-b border-gray-50 py-1.5">
                                <dt class="text-gray-500">Store Status</dt>
                                <dd class="font-semibold text-[#224e51]">Active & Approved</dd>
                            </div>
                            <div class="flex justify-between border-b border-gray-50 py-1.5">
                                <dt class="text-gray-500">Registered</dt>
                                <dd class="font-semibold text-gray-900">{{ $vendorAuth->created_at?->format('M d, Y') ?? 'N/A' }}</dd>
                            </div>
                            <div class="flex justify-between py-1.5">
                                <dt class="text-gray-500">Last Updated</dt>
                                <dd class="font-semibold text-gray-900">{{ $vendorAuth->updated_at?->diffForHumans() ?? 'Just now' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════ --}}
        {{-- TAB 2 — Password & Security                   --}}
        {{-- ══════════════════════════════════════════════ --}}
        <div id="tab-panel-security" class="tab-panel hidden">
            <div class="max-w-3xl rounded-2xl border border-gray-100 bg-white p-6 shadow-sm sm:p-8">
                <div class="mb-6 border-b border-gray-100 pb-4">
                    <h2 class="text-lg font-bold text-gray-900">Change Password</h2>
                    <p class="mt-1 text-sm text-gray-500">Keep your vendor account secure with a strong password.</p>
                </div>

                <form id="profile-password-form" novalidate class="space-y-6">
                    @csrf
                    <div>
                        <label for="new_password" class="block text-sm font-semibold text-gray-700">
                            New Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative mt-2">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                <x-lucide-key class="h-4 w-4" />
                            </div>
                            <input id="new_password" name="password" type="password" required minlength="8" maxlength="64"
                                   placeholder="Minimum 8 characters"
                                   class="w-full rounded-xl border border-gray-300 py-3 pl-10 pr-10 text-sm outline-none transition focus:border-[#224e51] focus:ring-2 focus:ring-[#224e51]/20">
                            <button type="button" onclick="togglePasswordVisibility('new_password', this)"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-gray-400 hover:text-gray-600 focus:outline-none">
                                <x-lucide-eye class="h-4 w-4" />
                            </button>
                        </div>
                        <p id="error-password" class="mt-1.5 hidden text-xs text-red-600"></p>
                    </div>

                    <div>
                        <label for="new_password_confirmation" class="block text-sm font-semibold text-gray-700">
                            Confirm New Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative mt-2">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                <x-lucide-check-circle class="h-4 w-4" />
                            </div>
                            <input id="new_password_confirmation" name="password_confirmation" type="password" required minlength="8" maxlength="64"
                                   placeholder="Re-type your new password"
                                   class="w-full rounded-xl border border-gray-300 py-3 pl-10 pr-10 text-sm outline-none transition focus:border-[#224e51] focus:ring-2 focus:ring-[#224e51]/20">
                            <button type="button" onclick="togglePasswordVisibility('new_password_confirmation', this)"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-gray-400 hover:text-gray-600 focus:outline-none">
                                <x-lucide-eye class="h-4 w-4" />
                            </button>
                        </div>
                    </div>

                    <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">
                        <p class="mb-2 text-xs font-semibold text-gray-700">Password Requirements:</p>
                        <ul class="space-y-1.5 text-xs text-gray-600">
                            <li class="flex items-center gap-2"><x-lucide-check class="h-3.5 w-3.5 text-[#224e51]" /> Minimum 8 characters (maximum 64)</li>
                            <li class="flex items-center gap-2"><x-lucide-check class="h-3.5 w-3.5 text-[#224e51]" /> Both passwords must match exactly</li>
                            <li class="flex items-center gap-2"><x-lucide-check class="h-3.5 w-3.5 text-[#224e51]" /> Mix uppercase, lowercase, numbers & symbols</li>
                        </ul>
                    </div>

                    <div class="flex justify-end border-t border-gray-100 pt-5">
                        <x-Button type="submit" id="btn-save-password" class="!bg-[#224e51] hover:!bg-[#1a3d3f] !px-6 !py-2.5 shadow-sm">
                            <x-lucide-lock class="mr-2 h-4 w-4" />
                            <span id="btn-save-password-text">Update Password</span>
                        </x-Button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════ --}}
        {{-- TAB 3 — Business & Bank Information           --}}
        {{-- ══════════════════════════════════════════════ --}}
        <div id="tab-panel-business" class="tab-panel hidden">
            <div class="max-w-4xl rounded-2xl border border-gray-100 bg-white p-6 shadow-sm sm:p-8">
                <div class="mb-6 border-b border-gray-100 pb-4">
                    <h2 class="text-lg font-bold text-gray-900">Business & Bank Details</h2>
                    <p class="mt-1 text-sm text-gray-500">Update your bank account and business information for payouts.</p>
                </div>

                <form id="profile-bank-form" novalidate class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        @foreach ([
                            'account_holder_name' => ['label' => 'Account Holder Name', 'required' => true,  'icon' => 'user',          'placeholder' => 'Full name on bank account'],
                            'company_name'        => ['label' => 'Company Name',         'required' => false, 'icon' => 'building-2',    'placeholder' => 'Your registered company'],
                            'bank_name'           => ['label' => 'Bank Name',            'required' => true,  'icon' => 'landmark',      'placeholder' => 'e.g. HDFC Bank'],
                            'account_number'      => ['label' => 'Account Number',       'required' => true,  'icon' => 'hash',          'placeholder' => 'Hidden for security'],
                            'account_type'        => ['label' => 'Account Type',         'required' => true,  'icon' => 'credit-card',   'placeholder' => 'Savings / Current'],
                            'ifsc_code'           => ['label' => 'IFSC Code',            'required' => true,  'icon' => 'key',           'placeholder' => 'e.g. HDFC0001234'],
                            'branch_name'         => ['label' => 'Branch Name',          'required' => true,  'icon' => 'map-pin',       'placeholder' => 'Branch location name'],
                        ] as $field => $cfg)
                            <div>
                                <label for="bank-{{ $field }}" class="block text-sm font-semibold text-gray-700">
                                    {{ $cfg['label'] }} @if($cfg['required'])<span class="text-red-500">*</span>@endif
                                </label>
                                <div class="relative mt-2">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                        <x-dynamic-component :component="'lucide-'.$cfg['icon']" class="h-4 w-4" />
                                    </div>
                                    <input id="bank-{{ $field }}" name="{{ $field }}"
                                           type="{{ $field === 'account_number' ? 'password' : 'text' }}"
                                           {{ $cfg['required'] ? 'required' : '' }}
                                           placeholder="{{ $cfg['placeholder'] }}"
                                           class="w-full rounded-xl border border-gray-300 py-3 pl-10 pr-4 text-sm text-gray-900 outline-none transition focus:border-[#224e51] focus:ring-2 focus:ring-[#224e51]/20">
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="rounded-xl border border-amber-100 bg-amber-50 p-4">
                        <p class="flex items-start gap-2 text-xs text-amber-700">
                            <x-lucide-alert-circle class="mt-0.5 h-3.5 w-3.5 shrink-0" />
                            For security, the saved account number is not displayed. Enter it again only when updating bank details.
                        </p>
                    </div>

                    <div class="flex justify-end border-t border-gray-100 pt-5">
                        <x-Button type="submit" id="btn-save-bank" class="!bg-[#224e51] hover:!bg-[#1a3d3f] !px-6 !py-2.5 shadow-sm">
                            <x-lucide-save class="mr-2 h-4 w-4" />
                            <span id="btn-save-bank-text">Save Bank Details</span>
                        </x-Button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════ --}}
        {{-- TAB 4 — Account Management / Danger Zone      --}}
        {{-- ══════════════════════════════════════════════ --}}
        <div id="tab-panel-danger" class="tab-panel hidden">
            <div class="max-w-3xl space-y-6">
                {{-- Logout --}}
                <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm sm:p-8">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-base font-bold text-gray-900">End Current Session</h3>
                            <p class="mt-1 text-sm text-gray-500">Sign out of this device. You will need to log back in to access your vendor panel.</p>
                        </div>
                        <button type="button" onclick="Logout()"
                                class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                            <x-lucide-log-out class="h-4 w-4" />
                            Log Out
                        </button>
                    </div>
                </div>

                {{-- Delete Account --}}
                <div class="rounded-2xl border border-red-200 bg-red-50/50 p-6 shadow-sm sm:p-8">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="flex items-center gap-2 text-base font-bold text-red-900">
                                <x-lucide-alert-triangle class="h-5 w-5 text-red-600" />
                                Delete Account
                            </h3>
                            <p class="mt-1 text-sm text-red-700">
                                Permanently delete your vendor account. All your products, orders, and store data will be removed.
                            </p>
                        </div>
                        <button type="button" onclick="openDeleteModal()"
                                class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700 focus:outline-none">
                            <x-lucide-trash-2 class="h-4 w-4" />
                            Delete Account
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- ═════════════════════════════════════════════ --}}
        {{-- TAB 5 — KYC & Store Documents                --}}
        {{-- ═════════════════════════════════════════════ --}}
        <div id="tab-panel-documents" class="tab-panel hidden">
            <div class="max-w-4xl">
                <div class="mb-6 rounded-2xl border border-[#224e51]/20 bg-[#224e51]/5 p-4">
                    <p class="flex items-start gap-2 text-sm text-[#224e51]">
                        <x-lucide-info class="mt-0.5 h-4 w-4 shrink-0" />
                        Upload official documents in <strong>PDF format only</strong> (max 5 MB each). Documents are stored securely and accessible only to you and admins.
                    </p>
                </div>

                <div id="docs-loading" class="py-6 text-center text-sm text-gray-400">
                    <svg class="mx-auto mb-2 h-5 w-5 animate-spin text-gray-300" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    Loading document status…
                </div>

                <div id="docs-content" class="hidden">
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                        {{-- Card macro: repeated per doc type --}}
                        @foreach ([
                            ['field' => 'verification_document',      'label' => 'Verification Document',  'sub' => 'GST Certificate, Trade License or Shop Act',       'color' => 'teal',   'icon' => 'file-check'],
                            ['field' => 'id_document_image',          'label' => 'ID Proof',               'sub' => 'Aadhaar, PAN Card or Passport (PDF scan)',           'color' => 'violet', 'icon' => 'id-card'],
                            ['field' => 'business_certificate_document','label' => 'Business Certificate', 'sub' => 'Incorporation or MSME certificate',                  'color' => 'amber',  'icon' => 'briefcase'],
                            ['field' => 'logo',                        'label' => 'Store Logo Document',   'sub' => 'Official store / brand authorization (PDF)',         'color' => 'sky',    'icon' => 'store'],
                        ] as $doc)
                        @php
                            $colors = [
                                'teal'   => ['bg' => 'bg-teal-50',   'text' => 'text-[#224e51]',  'border' => 'border-[#224e51]',  'hover_border' => 'hover:border-[#224e51]',  'hover_text' => 'hover:text-[#224e51]',  'btn_hover' => 'hover:bg-[#224e51]'],
                                'violet' => ['bg' => 'bg-violet-50', 'text' => 'text-violet-600', 'border' => 'border-violet-500', 'hover_border' => 'hover:border-violet-500', 'hover_text' => 'hover:text-violet-600', 'btn_hover' => 'hover:bg-violet-500'],
                                'amber'  => ['bg' => 'bg-amber-50',  'text' => 'text-amber-600',  'border' => 'border-amber-500',  'hover_border' => 'hover:border-amber-500',  'hover_text' => 'hover:text-amber-600',  'btn_hover' => 'hover:bg-amber-500'],
                                'sky'    => ['bg' => 'bg-sky-50',    'text' => 'text-sky-600',    'border' => 'border-sky-500',    'hover_border' => 'hover:border-sky-500',    'hover_text' => 'hover:text-sky-600',    'btn_hover' => 'hover:bg-sky-500'],
                            ][$doc['color']];
                        @endphp
                        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                            <div class="mb-4 flex items-center gap-3">
                                <span class="flex h-10 w-10 items-center justify-center rounded-xl {{ $colors['bg'] }} {{ $colors['text'] }}">
                                    <x-dynamic-component :component="'lucide-'.$doc['icon']" class="h-5 w-5" />
                                </span>
                                <div>
                                    <h3 class="text-sm font-bold text-gray-900">{{ $doc['label'] }}</h3>
                                    <p class="text-xs text-gray-500">{{ $doc['sub'] }}</p>
                                </div>
                            </div>

                            <div id="doc-status-{{ $doc['field'] }}" class="mb-4 flex items-center gap-2 rounded-lg bg-gray-50 px-3 py-2 text-xs text-gray-500">
                                <svg class="h-3.5 w-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                Checking status…
                            </div>

                            <div class="flex items-center gap-2">
                                <label for="doc-{{ $doc['field'] }}"
                                       class="flex flex-1 cursor-pointer flex-col items-center rounded-xl border-2 border-dashed border-gray-200 px-3 py-3 text-center text-xs font-semibold text-gray-600 transition {{ $colors['hover_border'] }} {{ $colors['hover_text'] }}">
                                    <x-lucide-upload class="mb-1 h-4 w-4" />
                                    Choose PDF
                                </label>
                                <input id="doc-{{ $doc['field'] }}" data-field="{{ $doc['field'] }}" type="file" accept="application/pdf" class="doc-upload hidden">
                                <a id="view-{{ $doc['field'] }}" href="#" target="_blank"
                                   class="hidden shrink-0 rounded-xl border {{ $colors['border'] }} px-3 py-3 text-xs font-semibold {{ $colors['text'] }} transition {{ $colors['btn_hover'] }} hover:text-white">
                                    <x-lucide-external-link class="h-4 w-4" />
                                </a>
                            </div>

                            <div id="doc-uploading-{{ $doc['field'] }}" class="mt-2 hidden text-xs {{ $colors['text'] }}">
                                <svg class="mr-1 inline h-3.5 w-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                Uploading…
                            </div>
                        </div>
                        @endforeach

                    </div>{{-- /grid --}}
                </div>{{-- /docs-content --}}
            </div>
        </div>

    </div>{{-- /p-4 sm:p-6 lg:p-8 --}}

    {{-- ── Delete Modal ── --}}
    <div id="delete-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4 backdrop-blur-sm">
        <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
            <div class="flex items-center gap-3 border-b border-gray-100 pb-4">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-100 text-red-600">
                    <x-lucide-alert-triangle class="h-5 w-5" />
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Confirm Deletion</h3>
                    <p class="text-xs text-gray-500">This action cannot be undone.</p>
                </div>
            </div>
            <p class="mt-4 text-sm text-gray-600">Enter your current password to permanently delete your vendor account.</p>
            <form id="delete-account-form" class="mt-4 space-y-4">
                @csrf
                <div>
                    <label for="delete-password" class="block text-xs font-semibold uppercase tracking-wider text-gray-700">Current Password</label>
                    <input id="delete-password" name="password" type="password" required placeholder="Enter your password"
                           class="mt-1 w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20">
                    <p id="delete-error" class="mt-1 hidden text-xs text-red-600"></p>
                </div>
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" onclick="closeDeleteModal()"
                            class="rounded-xl border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Cancel</button>
                    <button type="submit" id="btn-confirm-delete"
                            class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Permanently Delete</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            // ── Tab switching — top-level so onclick= always resolves it ──
            function switchTab(tabName) {
                document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
                document.querySelectorAll('.tab-button').forEach(b => {
                    b.classList.remove('border-[#224e51]', 'text-[#224e51]', 'font-semibold', 'border-red-300', 'text-red-600');
                    b.classList.add('border-transparent', 'text-gray-500', 'font-medium');
                });
                const panel = document.getElementById('tab-panel-' + tabName);
                const btn   = document.getElementById('tab-btn-' + tabName);
                if (panel && btn) {
                    panel.classList.remove('hidden');
                    btn.classList.remove('border-transparent', 'text-gray-500', 'font-medium');
                    if (tabName === 'danger') {
                        btn.classList.add('border-red-300', 'text-red-600', 'font-semibold');
                    } else {
                        btn.classList.add('border-[#224e51]', 'text-[#224e51]', 'font-semibold');
                    }
                }
            }

            (() => {
                const profileUrl  = @json(url('/api/profile'));
                const bankUrl     = @json(url('/api/vendor/bank-details'));
                const resendUrl   = @json(url('/api/email/verification-notification'));
                const csrf        = document.querySelector('meta[name="csrf-token"]')?.content ?? @json(csrf_token());

                // ── DOM refs ────────────────────────────────────────
                const alertBox        = document.getElementById('profile-alert');
                const alertMessage    = document.getElementById('alert-message');
                const alertIcon       = document.getElementById('alert-icon');
                const generalForm     = document.getElementById('profile-general-form');
                const passwordForm    = document.getElementById('profile-password-form');
                const bankForm        = document.getElementById('profile-bank-form');
                const avatarInput     = document.getElementById('avatar-input');
                const headerName      = document.getElementById('header-name');
                const headerEmail     = document.getElementById('header-email');
                const headerAvatarImg = document.getElementById('header-avatar-img');
                const headerAvatarInitials = document.getElementById('header-avatar-initials');
                const headerSpinner   = document.getElementById('header-avatar-spinner');
                const navAvatarImg    = document.getElementById('vendor-nav-avatar-img');
                const navAvatarInitials = document.getElementById('vendor-nav-avatar-initials');

                // ── Load initial S3 avatar ──────────────────────────
                if (headerAvatarImg?.dataset.s3Key) {
                    window.s3Url(headerAvatarImg.dataset.s3Key).then(url => {
                        if (url) {
                            headerAvatarImg.src = url;
                            headerAvatarImg.classList.remove('hidden');
                            if (headerAvatarInitials) headerAvatarInitials.classList.add('hidden');
                        }
                    });
                }

                // ── Alert helpers ───────────────────────────────────
                window.showAlert = (message, type = 'success') => {
                    alertBox.classList.remove('hidden', 'bg-emerald-50', 'text-emerald-800', 'border-emerald-200', 'bg-red-50', 'text-red-800', 'border-red-200');
                    if (type === 'success') {
                        alertBox.classList.add('flex', 'bg-emerald-50', 'text-emerald-800', 'border', 'border-emerald-200');
                        alertIcon.innerHTML = `<svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`;
                    } else {
                        alertBox.classList.add('flex', 'bg-red-50', 'text-red-800', 'border', 'border-red-200');
                        alertIcon.innerHTML = `<svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>`;
                    }
                    alertMessage.textContent = message;
                    alertBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                };
                window.dismissAlert = () => alertBox.classList.add('hidden');

                // switchTab is defined at top-level above the IIFE

                // ── Password toggle ─────────────────────────────────
                window.togglePasswordVisibility = (id, btn) => {
                    const input = document.getElementById(id);
                    if (!input) return;
                    const isPw = input.type === 'password';
                    input.type = isPw ? 'text' : 'password';
                    btn.classList.toggle('text-[#224e51]', isPw);
                };

                // ── Update avatar in header & navbar ────────────────
                const updateAvatarSrc = (url) => {
                    if (!url) return;
                    if (headerAvatarImg) {
                        headerAvatarImg.src = url;
                        headerAvatarImg.classList.remove('hidden');
                        if (headerAvatarInitials) headerAvatarInitials.classList.add('hidden');
                    } else {
                        const wrap = document.getElementById('header-avatar');
                        const initSpan = document.getElementById('header-avatar-initials');
                        const img = document.createElement('img');
                        img.id = 'header-avatar-img'; img.className = 'h-full w-full rounded-2xl object-cover';
                        img.src = url; img.alt = 'Profile';
                        if (initSpan) wrap.insertBefore(img, initSpan); else wrap.appendChild(img);
                        if (initSpan) initSpan.classList.add('hidden');
                    }
                    if (navAvatarImg) {
                        navAvatarImg.src = url;
                        navAvatarImg.classList.remove('hidden');
                        if (navAvatarInitials) navAvatarInitials.classList.add('hidden');
                    } else {
                        const navWrap = document.getElementById('vendor-nav-avatar');
                        if (navWrap) {
                            const initSpan = document.getElementById('vendor-nav-avatar-initials');
                            const img = document.createElement('img');
                            img.id = 'vendor-nav-avatar-img'; img.className = 'h-full w-full object-cover';
                            img.src = url; img.alt = 'Vendor';
                            if (initSpan) navWrap.insertBefore(img, initSpan); else navWrap.appendChild(img);
                            if (initSpan) initSpan.classList.add('hidden');
                        }
                    }
                };

                // ── Auto-upload avatar on file select ───────────────
                avatarInput?.addEventListener('change', async () => {
                    const file = avatarInput.files[0];
                    if (!file) return;
                    if (headerSpinner) { headerSpinner.classList.remove('hidden'); headerSpinner.classList.add('flex'); }
                    try {
                        const fd = new FormData();
                        fd.append('image', file);
                        fd.append('_method', 'PATCH');
                        const res = await fetch(profileUrl, { method: 'POST', headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrf }, body: fd });
                        const payload = await res.json();
                        if (!res.ok) throw new Error(payload.message || 'Upload failed.');
                        if (payload.user?.image) {
                            const signed = await window.s3Url(payload.user.image);
                            updateAvatarSrc(signed);
                        }
                        showAlert('Profile photo updated.');
                    } catch (err) {
                        showAlert(err.message, 'error');
                    } finally {
                        if (headerSpinner) { headerSpinner.classList.add('hidden'); headerSpinner.classList.remove('flex'); }
                        avatarInput.value = '';
                    }
                });

                // ── Error helpers ───────────────────────────────────
                const clearErrors = (form) => form.querySelectorAll('[id^="error-"]').forEach(el => { el.textContent = ''; el.classList.add('hidden'); });
                const showErrors  = (errors) => {
                    for (const [key, msgs] of Object.entries(errors)) {
                        const el = document.getElementById(`error-${key}`);
                        if (el) { el.textContent = Array.isArray(msgs) ? msgs[0] : msgs; el.classList.remove('hidden'); }
                    }
                };

                // ── Load profile data from API ──────────────────────
                const loadProfile = async () => {
                    try {
                        const res  = await fetch(profileUrl, { headers: { Accept: 'application/json' } });
                        const data = await res.json();
                        const user = data.user; if (!user) return;
                        if (generalForm) {
                            ['name','email','phone','address'].forEach(f => { if (generalForm.elements[f]) generalForm.elements[f].value = user[f] ?? ''; });
                        }
                        if (user.image) {
                            const signed = await window.s3Url(user.image);
                            updateAvatarSrc(signed);
                        }
                    } catch {}
                };

                // ── Load bank data from API ─────────────────────────
                const loadBank = async () => {
                    try {
                        const res  = await fetch(bankUrl, { headers: { Accept: 'application/json' } });
                        const data = await res.json();
                        const bank = data.bank_detail; if (!bank || !bankForm) return;
                        Object.keys(bank).forEach(f => { if (f !== 'account_number' && bankForm.elements[f]) bankForm.elements[f].value = bank[f] ?? ''; });
                    } catch {}
                };

                // ── Load document status ────────────────────────────
                const vendorAppUrl = @json(url('/api/vendor-application'));
                const vendorDocsUrl = @json(url('/api/vendor/documents'));
                const DOC_FIELDS = ['verification_document','id_document_image','business_certificate_document','logo'];

                const loadDocuments = async () => {
                    const loadingEl = document.getElementById('docs-loading');
                    const contentEl = document.getElementById('docs-content');
                    try {
                        const res  = await fetch(vendorAppUrl, { headers: { Accept: 'application/json' } });
                        const data = await res.json();
                        const vendor = data.vendor;

                        for (const field of DOC_FIELDS) {
                            const statusEl    = document.getElementById(`doc-status-${field}`);
                            const viewEl      = document.getElementById(`view-${field}`);
                            if (!statusEl) continue;

                            const s3Key = vendor?.[field];
                            if (s3Key) {
                                // Show uploaded badge + generate signed view link
                                statusEl.innerHTML = `<svg class="h-3.5 w-3.5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg><span class="text-emerald-700 font-semibold">Uploaded</span>`;
                                if (viewEl && window.s3Url) {
                                    window.s3Url(s3Key).then(url => {
                                        if (url) { viewEl.href = url; viewEl.classList.remove('hidden'); }
                                    });
                                }
                            } else {
                                statusEl.innerHTML = `<svg class="h-3.5 w-3.5 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg><span>Not uploaded yet</span>`;
                            }
                        }
                    } catch (err) {
                        console.warn('Could not load vendor documents:', err);
                    } finally {
                        if (loadingEl) loadingEl.classList.add('hidden');
                        if (contentEl) contentEl.classList.remove('hidden');
                    }
                };

                // ── Auto-upload on doc file select ──────────────────
                document.querySelectorAll('.doc-upload').forEach(input => {
                    input.addEventListener('change', async () => {
                        const file = input.files[0]; if (!file) return;
                        const field       = input.dataset.field;
                        const uploadingEl = document.getElementById(`doc-uploading-${field}`);
                        const statusEl    = document.getElementById(`doc-status-${field}`);
                        const viewEl      = document.getElementById(`view-${field}`);
                        if (uploadingEl) uploadingEl.classList.remove('hidden');
                        try {
                            const fd = new FormData();
                            fd.append(field, file);
                            const res = await fetch(vendorDocsUrl, { method: 'POST', headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrf }, body: fd });
                            const payload = await res.json();
                            if (!res.ok) { showAlert(payload.message || 'Upload failed.', 'error'); return; }
                            showAlert(`${field.replace(/_/g,' ')} uploaded successfully!`);
                            // Update status badge
                            if (statusEl) statusEl.innerHTML = `<svg class="h-3.5 w-3.5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg><span class="text-emerald-700 font-semibold">Uploaded</span>`;
                            // Refresh view link
                            const newKey = payload.vendor?.[field];
                            if (newKey && viewEl && window.s3Url) {
                                window.s3Url(newKey).then(url => { if (url) { viewEl.href = url; viewEl.classList.remove('hidden'); } });
                            }
                        } catch { showAlert('A network error occurred.', 'error'); }
                        finally { if (uploadingEl) uploadingEl.classList.add('hidden'); input.value = ''; }
                    });
                });

                loadProfile();
                loadBank();
                loadDocuments();

                // ── Personal Info submit ────────────────────────────
                generalForm?.addEventListener('submit', async (e) => {
                    e.preventDefault(); clearErrors(generalForm); dismissAlert();
                    const btn = document.getElementById('btn-save-general');
                    const txt = document.getElementById('btn-save-general-text');
                    btn.disabled = true; txt.textContent = 'Saving…';
                    try {
                        const fd = new FormData(generalForm); fd.append('_method', 'PATCH');
                        const res = await fetch(profileUrl, { method: 'POST', headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrf }, body: fd });
                        const payload = await res.json();
                        if (!res.ok) { if (res.status === 422 && payload.errors) { showErrors(payload.errors); showAlert('Please check the form for errors.', 'error'); } else { showAlert(payload.message || 'Update failed.', 'error'); } return; }
                        showAlert(payload.message || 'Profile updated successfully!');
                        if (payload.user) {
                            if (headerName) headerName.textContent = payload.user.name;
                            if (headerEmail) headerEmail.querySelector('span').textContent = payload.user.email;
                            const navName = document.querySelector('.vendor-nav-name');
                            if (navName) navName.textContent = `Welcome, ${payload.user.name}`;
                        }
                    } catch { showAlert('A network error occurred.', 'error'); }
                    finally { btn.disabled = false; txt.textContent = 'Save Changes'; }
                });

                // ── Password submit ─────────────────────────────────
                passwordForm?.addEventListener('submit', async (e) => {
                    e.preventDefault(); clearErrors(passwordForm); dismissAlert();
                    const btn = document.getElementById('btn-save-password');
                    const txt = document.getElementById('btn-save-password-text');
                    btn.disabled = true; txt.textContent = 'Updating…';
                    try {
                        const res = await fetch(profileUrl, { method: 'PATCH', headers: { Accept: 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf }, body: JSON.stringify({ password: passwordForm.elements['password'].value, password_confirmation: passwordForm.elements['password_confirmation'].value }) });
                        const payload = await res.json();
                        if (!res.ok) { if (res.status === 422 && payload.errors) { showErrors(payload.errors); showAlert('Password requirements not met.', 'error'); } else { showAlert(payload.message || 'Update failed.', 'error'); } return; }
                        showAlert('Password updated successfully!');
                        passwordForm.reset();
                    } catch { showAlert('A network error occurred.', 'error'); }
                    finally { btn.disabled = false; txt.textContent = 'Update Password'; }
                });

                // ── Bank Details submit ─────────────────────────────
                bankForm?.addEventListener('submit', async (e) => {
                    e.preventDefault(); dismissAlert();
                    const btn = document.getElementById('btn-save-bank');
                    const txt = document.getElementById('btn-save-bank-text');
                    btn.disabled = true; txt.textContent = 'Saving…';
                    try {
                        const data = Object.fromEntries(new FormData(bankForm)); delete data._token;
                        const res = await fetch(bankUrl, { method: 'PATCH', headers: { Accept: 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf }, body: JSON.stringify(data) });
                        const payload = await res.json();
                        if (!res.ok) { showAlert(payload.message || 'Failed to update bank details.', 'error'); return; }
                        showAlert('Bank details updated successfully!');
                        bankForm.elements['account_number'].value = '';
                    } catch { showAlert('A network error occurred.', 'error'); }
                    finally { btn.disabled = false; txt.textContent = 'Save Bank Details'; }
                });

                // ── Email Verification ──────────────────────────────
                window.resendVerification = async () => {
                    const btn = document.getElementById('btn-resend-verification');
                    if (btn) btn.disabled = true;
                    try {
                        const res = await fetch(resendUrl, { method: 'POST', headers: { Accept: 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf } });
                        const payload = await res.json();
                        showAlert(payload.message || 'Verification email sent!');
                    } catch { showAlert('Unable to send verification email.', 'error'); }
                    finally { if (btn) btn.disabled = false; }
                };

                // ── Delete Modal ────────────────────────────────────
                const deleteModal = document.getElementById('delete-modal');
                const deleteForm  = document.getElementById('delete-account-form');
                const deleteError = document.getElementById('delete-error');

                window.openDeleteModal  = () => { deleteModal.classList.remove('hidden'); deleteModal.classList.add('flex'); deleteError.classList.add('hidden'); deleteForm.reset(); };
                window.closeDeleteModal = () => { deleteModal.classList.add('hidden'); deleteModal.classList.remove('flex'); };

                deleteForm?.addEventListener('submit', async (e) => {
                    e.preventDefault(); deleteError.classList.add('hidden');
                    const btn = document.getElementById('btn-confirm-delete');
                    btn.disabled = true; btn.textContent = 'Deleting…';
                    try {
                        const res = await fetch(profileUrl, { method: 'DELETE', headers: { Accept: 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf }, body: JSON.stringify({ password: deleteForm.elements['password'].value }) });
                        const payload = await res.json();
                        if (!res.ok) { deleteError.textContent = payload.errors?.password?.[0] || payload.message || 'Incorrect password.'; deleteError.classList.remove('hidden'); return; }
                        window.location.href = @json(route('login'));
                    } catch { deleteError.textContent = 'Unable to delete account.'; deleteError.classList.remove('hidden'); }
                    finally { btn.disabled = false; btn.textContent = 'Permanently Delete'; }
                });
            })();
        </script>
    @endpush
@endsection
