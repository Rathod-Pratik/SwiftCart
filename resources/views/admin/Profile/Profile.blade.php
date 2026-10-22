@extends('layouts.Dashboard')

@section('content')
    <x-slot name="title">Admin Profile</x-slot>

    @php
        /** @var \App\Models\User $admin */
        $admin = auth()->user();
        $initials = collect(explode(' ', $admin->name ?? 'Admin'))
            ->filter()
            ->take(2)
            ->map(fn($part) => mb_substr($part, 0, 1))
            ->join('');
        $initials = strtoupper($initials ?: 'AD');
    @endphp

    <div class="p-4 sm:p-6 lg:p-8">
        {{-- Flash / Dynamic Status Alert --}}
        <div id="profile-alert" class="mb-6 hidden items-center justify-between rounded-xl px-5 py-4 text-sm font-medium transition-all duration-300 shadow-sm" role="alert">
            <div class="flex items-center gap-3">
                <span id="alert-icon"></span>
                <span id="alert-message"></span>
            </div>
            <button type="button" onclick="dismissAlert()" class="rounded-lg p-1 opacity-70 transition hover:opacity-100 focus:outline-none">
                <x-lucide-x class="h-4 w-4" />
            </button>
        </div>

        {{-- Profile Header Card --}}
        <div class="relative mb-8 overflow-hidden rounded-2xl bg-gradient-to-r from-[#224e51] via-[#2d5d60] to-[#3d6f6d] p-6 text-white shadow-md sm:p-8">
            <div class="absolute -right-16 -top-16 h-64 w-64 rounded-full bg-white/5 blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-16 right-32 h-48 w-48 rounded-full bg-[#F4C95D]/10 blur-xl pointer-events-none"></div>

            <div class="relative flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5 text-center sm:text-left">
                    {{-- Avatar Container with Upload Overlay --}}
                    <div class="relative group">
                        <div id="header-avatar" class="flex h-24 w-24 sm:h-28 sm:w-28 shrink-0 items-center justify-center rounded-2xl bg-[#F4C95D] text-3xl font-extrabold text-[#224e51] shadow-lg ring-4 ring-white/20 transition-all duration-300 group-hover:ring-[#F4C95D]">
                            @if (!empty($admin->image))
                                <img id="header-avatar-img" src="{{ str_starts_with($admin->image, 'http') ? $admin->image : asset('storage/' . $admin->image) }}"
                                     alt="{{ $admin->name }}"
                                     onerror="this.classList.add('hidden'); document.getElementById('header-avatar-initials').classList.remove('hidden');"
                                     class="h-full w-full rounded-2xl object-cover">
                                <span id="header-avatar-initials" class="hidden">{{ $initials }}</span>
                            @else
                                <span id="header-avatar-initials">{{ $initials }}</span>
                            @endif
                        </div>
                        <label for="avatar-input" title="Change Avatar" class="absolute -bottom-2 -right-2 flex h-9 w-9 cursor-pointer items-center justify-center rounded-xl bg-white text-[#224e51] shadow-md transition hover:scale-110 hover:bg-[#F4C95D] hover:text-[#224e51] focus:outline-none">
                            <x-lucide-camera class="h-4 w-4" />
                            <span class="sr-only">Change profile photo</span>
                        </label>
                    </div>

                    {{-- User Basic Information --}}
                    <div>
                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2.5">
                            <h1 id="header-name" class="text-2xl font-bold tracking-tight text-white sm:text-3xl">{{ $admin->name }}</h1>
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-[#F4C95D] px-3 py-0.5 text-xs font-bold text-[#224e51] shadow-sm">
                                <x-lucide-shield-check class="h-3.5 w-3.5" />
                                Administrator
                            </span>
                        </div>
                        <p id="header-email" class="mt-1.5 text-sm text-[#E1EBE8]/90 flex items-center justify-center sm:justify-start gap-2">
                            <x-lucide-mail class="h-4 w-4 shrink-0 text-[#F4C95D]" />
                            <span>{{ $admin->email }}</span>
                        </p>

                        {{-- Metadata Badges --}}
                        <div class="mt-4 flex flex-wrap items-center justify-center sm:justify-start gap-2 text-xs">
                            <span class="inline-flex items-center gap-1 rounded-lg bg-white/10 px-2.5 py-1 text-white backdrop-blur-sm">
                                <x-lucide-calendar class="h-3.5 w-3.5 text-[#F4C95D]" />
                                Joined {{ $admin->created_at ? $admin->created_at->format('M Y') : 'Recently' }}
                            </span>

                            @if ($admin->hasVerifiedEmail())
                                <span class="inline-flex items-center gap-1 rounded-lg bg-emerald-500/20 px-2.5 py-1 text-emerald-200 border border-emerald-400/30">
                                    <x-lucide-check-circle class="h-3.5 w-3.5 text-emerald-300" />
                                    Email Verified
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-lg bg-amber-500/20 px-2.5 py-1 text-amber-200 border border-amber-400/30">
                                    <x-lucide-alert-circle class="h-3.5 w-3.5 text-amber-300" />
                                    Unverified Email
                                </span>
                            @endif

                            <span class="inline-flex items-center gap-1 rounded-lg bg-white/10 px-2.5 py-1 text-white backdrop-blur-sm">
                                <x-lucide-check class="h-3.5 w-3.5 text-emerald-400" />
                                System Active
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Action shortcuts --}}
                <div class="flex items-center justify-center sm:justify-end gap-3 shrink-0">
                    <button type="button" onclick="Logout()"
                            class="inline-flex items-center gap-2 rounded-xl bg-white/10 px-4 py-2.5 text-sm font-semibold text-white backdrop-blur-sm transition duration-200 hover:bg-red-600/80 hover:text-white focus:outline-none">
                        <x-lucide-log-out class="h-4 w-4" />
                        <span>Sign Out</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Tabs Navigation --}}
        <div class="mb-6 border-b border-gray-200">
            <nav class="-mb-px flex space-x-2 sm:space-x-8 overflow-x-auto pb-1" aria-label="Profile Tabs">
                <button type="button" onclick="switchTab('general')" id="tab-btn-general"
                        class="tab-button inline-flex items-center gap-2 whitespace-nowrap border-b-2 border-[#224e51] px-3 py-3 text-sm font-semibold text-[#224e51] transition">
                    <x-lucide-user class="h-4 w-4" />
                    Personal Information
                </button>
                <button type="button" onclick="switchTab('security')" id="tab-btn-security"
                        class="tab-button inline-flex items-center gap-2 whitespace-nowrap border-b-2 border-transparent px-3 py-3 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 transition">
                    <x-lucide-lock class="h-4 w-4" />
                    Password & Security
                </button>
                <button type="button" onclick="switchTab('permissions')" id="tab-btn-permissions"
                        class="tab-button inline-flex items-center gap-2 whitespace-nowrap border-b-2 border-transparent px-3 py-3 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 transition">
                    <x-lucide-shield class="h-4 w-4" />
                    Role & System Access
                </button>
                <button type="button" onclick="switchTab('danger')" id="tab-btn-danger"
                        class="tab-button inline-flex items-center gap-2 whitespace-nowrap border-b-2 border-transparent px-3 py-3 text-sm font-medium text-red-600 hover:border-red-300 transition">
                    <x-lucide-trash-2 class="h-4 w-4" />
                    Account Management
                </button>
            </nav>
        </div>

        {{-- TAB 1: General Personal Information --}}
        <div id="tab-panel-general" class="tab-panel">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Form Section --}}
                <div class="lg:col-span-2">
                    <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm sm:p-8">
                        <div class="mb-6 border-b border-gray-100 pb-4">
                            <h2 class="text-lg font-bold text-gray-900">Personal Details</h2>
                            <p class="mt-1 text-sm text-gray-500">Update your official administrator account information and contact details.</p>
                        </div>

                        <form id="profile-general-form" enctype="multipart/form-data" novalidate class="space-y-6">
                            @csrf
                            {{-- Hidden file input --}}
                            <input id="avatar-input" name="image" type="file" accept="image/jpeg,image/png,image/webp,image/jpg" class="hidden">

                            {{-- Avatar Preview helper inside form --}}
                            <div id="avatar-preview-container" class="hidden items-center gap-4 rounded-xl bg-gray-50 p-4 border border-dashed border-gray-200">
                                <img id="avatar-preview-img" class="h-16 w-16 rounded-xl object-cover border-2 border-[#224e51]" alt="Preview">
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-800" id="avatar-file-name"></p>
                                    <p class="text-xs text-gray-500">New profile picture selected. Click 'Save Changes' to update.</p>
                                </div>
                                <button type="button" onclick="clearAvatarSelection()" class="rounded-lg p-2 text-gray-400 hover:bg-gray-200 hover:text-gray-700">
                                    <x-lucide-x class="h-4 w-4" />
                                </button>
                            </div>

                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                {{-- Full Name --}}
                                <div>
                                    <label for="admin-name" class="block text-sm font-semibold text-gray-700">
                                        Full Name <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative mt-2">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                            <x-lucide-user class="h-4 w-4" />
                                        </div>
                                        <input id="admin-name" name="name" type="text" required minlength="2" maxlength="50"
                                               value="{{ old('name', $admin->name) }}"
                                               placeholder="John Doe"
                                               class="w-full rounded-xl border border-gray-300 py-3 pl-10 pr-4 text-sm text-gray-900 outline-none transition focus:border-[#224e51] focus:ring-2 focus:ring-[#224e51]/20">
                                    </div>
                                    <p id="error-name" class="mt-1.5 hidden text-xs text-red-600"></p>
                                </div>

                                {{-- Email Address --}}
                                <div>
                                    <label for="admin-email" class="block text-sm font-semibold text-gray-700">
                                        Email Address <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative mt-2">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                            <x-lucide-mail class="h-4 w-4" />
                                        </div>
                                        <input id="admin-email" name="email" type="email" required maxlength="255"
                                               value="{{ old('email', $admin->email) }}"
                                               placeholder="admin@swiftcart.com"
                                               class="w-full rounded-xl border border-gray-300 py-3 pl-10 pr-4 text-sm text-gray-900 outline-none transition focus:border-[#224e51] focus:ring-2 focus:ring-[#224e51]/20">
                                    </div>
                                    <p id="error-email" class="mt-1.5 hidden text-xs text-red-600"></p>
                                </div>

                                {{-- Phone Number --}}
                                <div>
                                    <label for="admin-phone" class="block text-sm font-semibold text-gray-700">
                                        Phone Number
                                    </label>
                                    <div class="relative mt-2">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                            <x-lucide-phone class="h-4 w-4" />
                                        </div>
                                        <input id="admin-phone" name="phone" type="tel" minlength="10" maxlength="15"
                                               value="{{ old('phone', $admin->phone) }}"
                                               placeholder="+1 (555) 000-0000"
                                               class="w-full rounded-xl border border-gray-300 py-3 pl-10 pr-4 text-sm text-gray-900 outline-none transition focus:border-[#224e51] focus:ring-2 focus:ring-[#224e51]/20">
                                    </div>
                                    <p id="error-phone" class="mt-1.5 hidden text-xs text-red-600"></p>
                                </div>

                                {{-- Role Readonly --}}
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700">
                                        Assigned Role
                                    </label>
                                    <div class="relative mt-2">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                            <x-lucide-shield-check class="h-4 w-4" />
                                        </div>
                                        <input type="text" readonly disabled
                                               value="Super Administrator"
                                               class="w-full cursor-not-allowed rounded-xl border border-gray-200 bg-gray-50 py-3 pl-10 pr-4 text-sm font-medium text-gray-600">
                                    </div>
                                    <p class="mt-1.5 text-xs text-gray-400">Role permissions are managed globally.</p>
                                </div>
                            </div>

                            {{-- Physical / Office Address --}}
                            <div>
                                <label for="admin-address" class="block text-sm font-semibold text-gray-700">
                                    Office / Street Address
                                </label>
                                <div class="relative mt-2">
                                    <div class="pointer-events-none absolute top-3.5 left-3.5 text-gray-400">
                                        <x-lucide-map-pin class="h-4 w-4" />
                                    </div>
                                    <textarea id="admin-address" name="address" rows="3" minlength="5" maxlength="255"
                                              placeholder="Suite 500, SwiftCart Headquarters, San Francisco, CA"
                                              class="w-full rounded-xl border border-gray-300 py-3 pl-10 pr-4 text-sm text-gray-900 outline-none transition focus:border-[#224e51] focus:ring-2 focus:ring-[#224e51]/20">{{ old('address', $admin->address) }}</textarea>
                                </div>
                                <p id="error-address" class="mt-1.5 hidden text-xs text-red-600"></p>
                            </div>

                            {{-- Save Button --}}
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

                {{-- Sidebar Info Card --}}
                <div class="space-y-6">
                    {{-- Email Verification Card --}}
                    <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl {{ $admin->hasVerifiedEmail() ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                @if ($admin->hasVerifiedEmail())
                                    <x-lucide-check-circle class="h-5 w-5" />
                                @else
                                    <x-lucide-alert-circle class="h-5 w-5" />
                                @endif
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-gray-900">Email Verification</h3>
                                <p class="text-xs text-gray-500">
                                    {{ $admin->hasVerifiedEmail() ? 'Verified on ' . $admin->email_verified_at->format('M d, Y') : 'Email is not verified yet.' }}
                                </p>
                            </div>
                        </div>

                        @if (!$admin->hasVerifiedEmail())
                            <div class="mt-4 border-t border-gray-100 pt-3">
                                <p class="text-xs text-gray-600 mb-3">Verify your email to receive high-priority system alerts and order updates.</p>
                                <button type="button" id="btn-resend-verification" onclick="resendVerificationEmail()"
                                        class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-amber-500 px-3 py-2 text-xs font-semibold text-white transition hover:bg-amber-600">
                                    <x-lucide-mail class="h-3.5 w-3.5" />
                                    <span>Resend Verification Email</span>
                                </button>
                            </div>
                        @endif
                    </div>

                    {{-- Admin Overview Card --}}
                    <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                        <h3 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <x-lucide-shield class="h-4 w-4 text-[#224e51]" />
                            System Authority
                        </h3>
                        <dl class="space-y-3 text-xs">
                            <div class="flex justify-between py-1.5 border-b border-gray-50">
                                <dt class="text-gray-500">Administrator ID</dt>
                                <dd class="font-semibold text-gray-900">#ADM-{{ str_pad($admin->id, 4, '0', STR_PAD_LEFT) }}</dd>
                            </div>
                            <div class="flex justify-between py-1.5 border-b border-gray-50">
                                <dt class="text-gray-500">Access Tier</dt>
                                <dd class="font-semibold text-[#224e51]">Full Root Privileges</dd>
                            </div>
                            <div class="flex justify-between py-1.5 border-b border-gray-50">
                                <dt class="text-gray-500">Registered</dt>
                                <dd class="font-semibold text-gray-900">{{ $admin->created_at ? $admin->created_at->format('M d, Y') : 'N/A' }}</dd>
                            </div>
                            <div class="flex justify-between py-1.5">
                                <dt class="text-gray-500">Last Synced</dt>
                                <dd id="sidebar-updated-at" class="font-semibold text-gray-900">{{ $admin->updated_at ? $admin->updated_at->diffForHumans() : 'Just now' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        {{-- TAB 2: Password & Security --}}
        <div id="tab-panel-security" class="tab-panel hidden">
            <div class="max-w-3xl rounded-2xl border border-gray-100 bg-white p-6 shadow-sm sm:p-8">
                <div class="mb-6 border-b border-gray-100 pb-4">
                    <h2 class="text-lg font-bold text-gray-900">Change Password</h2>
                    <p class="mt-1 text-sm text-gray-500">Ensure your administrative account remains protected with a strong, unpredictable password.</p>
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
                                   class="w-full rounded-xl border border-gray-300 py-3 pl-10 pr-10 text-sm text-gray-900 outline-none transition focus:border-[#224e51] focus:ring-2 focus:ring-[#224e51]/20">
                            <button type="button" onclick="togglePasswordVisibility('new_password', this)" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-gray-400 hover:text-gray-600 focus:outline-none">
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
                                   class="w-full rounded-xl border border-gray-300 py-3 pl-10 pr-10 text-sm text-gray-900 outline-none transition focus:border-[#224e51] focus:ring-2 focus:ring-[#224e51]/20">
                            <button type="button" onclick="togglePasswordVisibility('new_password_confirmation', this)" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-gray-400 hover:text-gray-600 focus:outline-none">
                                <x-lucide-eye class="h-4 w-4" />
                            </button>
                        </div>
                    </div>

                    {{-- Password Rules Helper --}}
                    <div class="rounded-xl bg-gray-50 p-4 border border-gray-100">
                        <p class="text-xs font-semibold text-gray-700 mb-2">Password Requirements:</p>
                        <ul class="space-y-1.5 text-xs text-gray-600">
                            <li class="flex items-center gap-2">
                                <x-lucide-check class="h-3.5 w-3.5 text-[#224e51]" />
                                Minimum 8 characters in length (maximum 64)
                            </li>
                            <li class="flex items-center gap-2">
                                <x-lucide-check class="h-3.5 w-3.5 text-[#224e51]" />
                                Both passwords must match exactly
                            </li>
                            <li class="flex items-center gap-2">
                                <x-lucide-check class="h-3.5 w-3.5 text-[#224e51]" />
                                Recommended: mix uppercase, lowercase, numbers, and symbols
                            </li>
                        </ul>
                    </div>

                    <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-5">
                        <x-Button type="submit" id="btn-save-password" class="!bg-[#224e51] hover:!bg-[#1a3d3f] !px-6 !py-2.5 shadow-sm">
                            <x-lucide-lock class="mr-2 h-4 w-4" />
                            <span id="btn-save-password-text">Update Password</span>
                        </x-Button>
                    </div>
                </form>
            </div>
        </div>

        {{-- TAB 3: Role & System Access --}}
        <div id="tab-panel-permissions" class="tab-panel hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl">
                {{-- Permission Card 1 --}}
                <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#224e51]/10 text-[#224e51]">
                            <x-lucide-users class="h-5 w-5" />
                        </span>
                        <div>
                            <h3 class="font-bold text-gray-900">User & Vendor Management</h3>
                            <p class="text-xs text-gray-500">Unrestricted operational control</p>
                        </div>
                    </div>
                    <ul class="space-y-2 text-xs text-gray-600">
                        <li class="flex items-center gap-2"><x-lucide-check class="h-3.5 w-3.5 text-emerald-600" /> Review & approve vendor registrations</li>
                        <li class="flex items-center gap-2"><x-lucide-check class="h-3.5 w-3.5 text-emerald-600" /> Manage user account statuses and roles</li>
                        <li class="flex items-center gap-2"><x-lucide-check class="h-3.5 w-3.5 text-emerald-600" /> View vendor bank and KYC documentation</li>
                    </ul>
                </div>

                {{-- Permission Card 2 --}}
                <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#224e51]/10 text-[#224e51]">
                            <x-lucide-package class="h-5 w-5" />
                        </span>
                        <div>
                            <h3 class="font-bold text-gray-900">Catalog & Products</h3>
                            <p class="text-xs text-gray-500">Global category and inventory rights</p>
                        </div>
                    </div>
                    <ul class="space-y-2 text-xs text-gray-600">
                        <li class="flex items-center gap-2"><x-lucide-check class="h-3.5 w-3.5 text-emerald-600" /> Create, edit, and archive product categories</li>
                        <li class="flex items-center gap-2"><x-lucide-check class="h-3.5 w-3.5 text-emerald-600" /> Audit published store products and listings</li>
                        <li class="flex items-center gap-2"><x-lucide-check class="h-3.5 w-3.5 text-emerald-600" /> Manage store promotions and discount coupons</li>
                    </ul>
                </div>

                {{-- Permission Card 3 --}}
                <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#224e51]/10 text-[#224e51]">
                            <x-lucide-message-square class="h-5 w-5" />
                        </span>
                        <div>
                            <h3 class="font-bold text-gray-900">Customer Support & Inquiries</h3>
                            <p class="text-xs text-gray-500">Support tickets and conversation administration</p>
                        </div>
                    </div>
                    <ul class="space-y-2 text-xs text-gray-600">
                        <li class="flex items-center gap-2"><x-lucide-check class="h-3.5 w-3.5 text-emerald-600" /> Access customer and vendor inquiries</li>
                        <li class="flex items-center gap-2"><x-lucide-check class="h-3.5 w-3.5 text-emerald-600" /> Moderate customer reviews and ratings</li>
                        <li class="flex items-center gap-2"><x-lucide-check class="h-3.5 w-3.5 text-emerald-600" /> Oversee order dispute resolution</li>
                    </ul>
                </div>

                {{-- Permission Card 4 --}}
                <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#224e51]/10 text-[#224e51]">
                            <x-lucide-shield-check class="h-5 w-5" />
                        </span>
                        <div>
                            <h3 class="font-bold text-gray-900">Security & Core System</h3>
                            <p class="text-xs text-gray-500">Environment and system configurations</p>
                        </div>
                    </div>
                    <ul class="space-y-2 text-xs text-gray-600">
                        <li class="flex items-center gap-2"><x-lucide-check class="h-3.5 w-3.5 text-emerald-600" /> CSRF and session token protection enabled</li>
                        <li class="flex items-center gap-2"><x-lucide-check class="h-3.5 w-3.5 text-emerald-600" /> S3 cloud storage direct asset uploads</li>
                        <li class="flex items-center gap-2"><x-lucide-check class="h-3.5 w-3.5 text-emerald-600" /> System broadcast and real-time events</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- TAB 4: Danger Zone / Account Management --}}
        <div id="tab-panel-danger" class="tab-panel hidden">
            <div class="max-w-3xl space-y-6">
                {{-- Logout Box --}}
                <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm sm:p-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h3 class="text-base font-bold text-gray-900">End Current Session</h3>
                            <p class="mt-1 text-sm text-gray-500">Sign out of this device. You will need to log back in to access the administrator panel.</p>
                        </div>
                        <button type="button" onclick="Logout()"
                                class="shrink-0 inline-flex items-center justify-center gap-2 rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                            <x-lucide-log-out class="h-4 w-4" />
                            Log Out
                        </button>
                    </div>
                </div>

                {{-- Delete Account Box --}}
                <div class="rounded-2xl border border-red-200 bg-red-50/50 p-6 shadow-sm sm:p-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h3 class="text-base font-bold text-red-900 flex items-center gap-2">
                                <x-lucide-alert-triangle class="h-5 w-5 text-red-600" />
                                Delete Account
                            </h3>
                            <p class="mt-1 text-sm text-red-700">
                                Permanently delete your administrator account. All personal credentials and session data will be permanently wiped.
                            </p>
                        </div>
                        <button type="button" onclick="openDeleteModal()"
                                class="shrink-0 inline-flex items-center justify-center gap-2 rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700 focus:outline-none">
                            <x-lucide-trash-2 class="h-4 w-4" />
                            Delete Account
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Account Confirmation Modal --}}
    <div id="delete-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl transition-all">
            <div class="flex items-center gap-3 border-b border-gray-100 pb-4">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-100 text-red-600">
                    <x-lucide-alert-triangle class="h-5 w-5" />
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Confirm Deletion</h3>
                    <p class="text-xs text-gray-500">This action cannot be undone.</p>
                </div>
            </div>

            <p class="mt-4 text-sm text-gray-600">
                Please enter your current administrator password to verify and permanently delete your account.
            </p>

            <form id="delete-account-form" class="mt-4 space-y-4">
                @csrf
                <div>
                    <label for="delete-password" class="block text-xs font-semibold uppercase tracking-wider text-gray-700">Current Password</label>
                    <input id="delete-password" name="password" type="password" required
                           placeholder="Enter your current password"
                           class="mt-1 w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20">
                    <p id="delete-error" class="mt-1 hidden text-xs text-red-600"></p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3">
                    <button type="button" onclick="closeDeleteModal()"
                            class="rounded-xl border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" id="btn-confirm-delete"
                            class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-700">
                        Permanently Delete
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            (() => {
                const profileApiUrl = @json(url('/api/profile'));
                const resendEmailUrl = @json(url('/api/email/verification-notification'));
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || @json(csrf_token());

                const alertBox = document.getElementById('profile-alert');
                const alertMessage = document.getElementById('alert-message');
                const alertIcon = document.getElementById('alert-icon');

                const generalForm = document.getElementById('profile-general-form');
                const passwordForm = document.getElementById('profile-password-form');
                const avatarInput = document.getElementById('avatar-input');
                const avatarPreviewContainer = document.getElementById('avatar-preview-container');
                const avatarPreviewImg = document.getElementById('avatar-preview-img');
                const avatarFileName = document.getElementById('avatar-file-name');

                const headerAvatar = document.getElementById('header-avatar');
                const headerName = document.getElementById('header-name');
                const headerEmail = document.getElementById('header-email');
                const headerAvatarImg = document.getElementById('header-avatar-img');
                const headerAvatarInitials = document.getElementById('header-avatar-initials');

                // Tabs Switching
                window.switchTab = (tabName) => {
                    document.querySelectorAll('.tab-panel').forEach(panel => panel.classList.add('hidden'));
                    document.querySelectorAll('.tab-button').forEach(btn => {
                        btn.classList.remove('border-[#224e51]', 'text-[#224e51]', 'font-semibold');
                        btn.classList.add('border-transparent', 'text-gray-500', 'font-medium');
                    });

                    const targetPanel = document.getElementById(`tab-panel-${tabName}`);
                    const targetBtn = document.getElementById(`tab-btn-${tabName}`);

                    if (targetPanel && targetBtn) {
                        targetPanel.classList.remove('hidden');
                        targetBtn.classList.remove('border-transparent', 'text-gray-500', 'font-medium');
                        targetBtn.classList.add('border-[#224e51]', 'text-[#224e51]', 'font-semibold');
                    }
                };

                // Notification Alert helper
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

                window.dismissAlert = () => {
                    alertBox.classList.add('hidden');
                };

                // Password toggle helper
                window.togglePasswordVisibility = (inputId, button) => {
                    const input = document.getElementById(inputId);
                    if (!input) return;
                    const isPassword = input.type === 'password';
                    input.type = isPassword ? 'text' : 'password';
                    button.classList.toggle('text-[#224e51]', isPassword);
                };

                // Avatar Input Preview
                if (avatarInput) {
                    avatarInput.addEventListener('change', (e) => {
                        const file = e.target.files?.[0];
                        if (file) {
                            if (file.size > 2048 * 1024) {
                                showAlert('The avatar image may not be greater than 2048 kilobytes.', 'error');
                                avatarInput.value = '';
                                return;
                            }
                            const reader = new FileReader();
                            reader.onload = (event) => {
                                avatarPreviewImg.src = event.target.result;
                                avatarFileName.textContent = file.name;
                                avatarPreviewContainer.classList.remove('hidden');
                                avatarPreviewContainer.classList.add('flex');
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                }

                window.clearAvatarSelection = () => {
                    if (avatarInput) avatarInput.value = '';
                    avatarPreviewContainer.classList.add('hidden');
                    avatarPreviewContainer.classList.remove('flex');
                };

                // Clear inline form errors
                const clearErrors = (form) => {
                    form.querySelectorAll('[id^="error-"]').forEach(el => {
                        el.textContent = '';
                        el.classList.add('hidden');
                    });
                };

                // Show inline validation errors
                const showErrors = (errors) => {
                    for (const [key, messages] of Object.entries(errors)) {
                        const errorEl = document.getElementById(`error-${key}`);
                        if (errorEl) {
                            errorEl.textContent = Array.isArray(messages) ? messages[0] : messages;
                            errorEl.classList.remove('hidden');
                        }
                    }
                };

                // Fetch latest profile state from API on load
                const refreshProfileData = async () => {
                    try {
                        const response = await fetch(profileApiUrl, {
                            headers: { 'Accept': 'application/json' }
                        });
                        if (!response.ok) return;
                        const data = await response.json();
                        const user = data.user;
                        if (!user) return;

                        if (user.name) {
                            headerName.textContent = user.name;
                            if (generalForm.elements['name']) generalForm.elements['name'].value = user.name;
                        }
                        if (user.email) {
                            headerEmail.querySelector('span').textContent = user.email;
                            if (generalForm.elements['email']) generalForm.elements['email'].value = user.email;
                        }
                        if (generalForm.elements['phone'] && user.phone) {
                            generalForm.elements['phone'].value = user.phone;
                        }
                        if (generalForm.elements['address'] && user.address) {
                            generalForm.elements['address'].value = user.address;
                        }

                        // Update avatar initials / image if returned
                        if (user.image) {
                            const imgSrc = user.image.startsWith('http') ? user.image : `/storage/${user.image}`;
                            if (headerAvatarImg) {
                                headerAvatarImg.src = imgSrc;
                                headerAvatarImg.classList.remove('hidden');
                                if (headerAvatarInitials) headerAvatarInitials.classList.add('hidden');
                            }
                        }
                    } catch (err) {
                        console.warn('Could not sync profile with API:', err);
                    }
                };

                // Handle Personal Information Update
                if (generalForm) {
                    generalForm.addEventListener('submit', async (e) => {
                        e.preventDefault();
                        clearErrors(generalForm);
                        dismissAlert();

                        const submitBtn = document.getElementById('btn-save-general');
                        const submitText = document.getElementById('btn-save-general-text');
                        submitBtn.disabled = true;
                        submitText.textContent = 'Saving...';

                        try {
                            const formData = new FormData(generalForm);
                            formData.append('_method', 'PATCH');

                            const response = await fetch(profileApiUrl, {
                                method: 'POST',
                                headers: {
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken
                                },
                                body: formData
                            });

                            const payload = await response.json();

                            if (!response.ok) {
                                if (response.status === 422 && payload.errors) {
                                    showErrors(payload.errors);
                                    showAlert('Please check the form for validation errors.', 'error');
                                } else {
                                    showAlert(payload.message || 'Failed to update profile.', 'error');
                                }
                                return;
                            }

                            showAlert(payload.message || 'Profile updated successfully!', 'success');
                            clearAvatarSelection();

                            if (payload.user) {
                                headerName.textContent = payload.user.name;
                                headerEmail.querySelector('span').textContent = payload.user.email;
                                const initials = payload.user.name.split(' ').map(p => p[0]).slice(0, 2).join('').toUpperCase();
                                if (headerAvatarInitials) headerAvatarInitials.textContent = initials;

                                if (payload.user.image && headerAvatarImg) {
                                    headerAvatarImg.src = payload.user.image.startsWith('http') ? payload.user.image : `/storage/${payload.user.image}`;
                                    headerAvatarImg.classList.remove('hidden');
                                    if (headerAvatarInitials) headerAvatarInitials.classList.add('hidden');
                                }
                            }
                        } catch (err) {
                            console.error('Update profile error:', err);
                            showAlert('An unexpected network error occurred.', 'error');
                        } finally {
                            submitBtn.disabled = false;
                            submitText.textContent = 'Save Changes';
                        }
                    });
                }

                // Handle Password Update
                if (passwordForm) {
                    passwordForm.addEventListener('submit', async (e) => {
                        e.preventDefault();
                        clearErrors(passwordForm);
                        dismissAlert();

                        const submitBtn = document.getElementById('btn-save-password');
                        const submitText = document.getElementById('btn-save-password-text');
                        submitBtn.disabled = true;
                        submitText.textContent = 'Updating...';

                        try {
                            const payloadData = {
                                password: passwordForm.elements['password'].value,
                                password_confirmation: passwordForm.elements['password_confirmation'].value,
                            };

                            const response = await fetch(profileApiUrl, {
                                method: 'PATCH',
                                headers: {
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken
                                },
                                body: JSON.stringify(payloadData)
                            });

                            const payload = await response.json();

                            if (!response.ok) {
                                if (response.status === 422 && payload.errors) {
                                    showErrors(payload.errors);
                                    showAlert('Password requirements not met.', 'error');
                                } else {
                                    showAlert(payload.message || 'Failed to update password.', 'error');
                                }
                                return;
                            }

                            showAlert('Password updated successfully!', 'success');
                            passwordForm.reset();
                        } catch (err) {
                            console.error('Update password error:', err);
                            showAlert('An unexpected network error occurred.', 'error');
                        } finally {
                            submitBtn.disabled = false;
                            submitText.textContent = 'Update Password';
                        }
                    });
                }

                // Resend Email Verification
                window.resendVerificationEmail = async () => {
                    const btn = document.getElementById('btn-resend-verification');
                    if (btn) btn.disabled = true;
                    try {
                        const response = await fetch(resendEmailUrl, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            }
                        });
                        const payload = await response.json();
                        showAlert(payload.message || 'Verification link sent to your email address.', 'success');
                    } catch (err) {
                        showAlert('Unable to send verification email.', 'error');
                    } finally {
                        if (btn) btn.disabled = false;
                    }
                };

                // Delete Modal handling
                const deleteModal = document.getElementById('delete-modal');
                const deleteForm = document.getElementById('delete-account-form');
                const deleteError = document.getElementById('delete-error');

                window.openDeleteModal = () => {
                    deleteModal.classList.remove('hidden');
                    deleteModal.classList.add('flex');
                    deleteError.classList.add('hidden');
                    deleteForm.reset();
                };

                window.closeDeleteModal = () => {
                    deleteModal.classList.add('hidden');
                    deleteModal.classList.remove('flex');
                };

                if (deleteForm) {
                    deleteForm.addEventListener('submit', async (e) => {
                        e.preventDefault();
                        deleteError.classList.add('hidden');
                        const confirmBtn = document.getElementById('btn-confirm-delete');
                        confirmBtn.disabled = true;
                        confirmBtn.textContent = 'Deleting...';

                        try {
                            const response = await fetch(profileApiUrl, {
                                method: 'DELETE',
                                headers: {
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken
                                },
                                body: JSON.stringify({
                                    password: deleteForm.elements['password'].value
                                })
                            });

                            const payload = await response.json();

                            if (!response.ok) {
                                deleteError.textContent = payload.errors?.password?.[0] || payload.message || 'Incorrect password.';
                                deleteError.classList.remove('hidden');
                                return;
                            }

                            window.location.href = @json(route('login'));
                        } catch (err) {
                            deleteError.textContent = 'Unable to delete account.';
                            deleteError.classList.remove('hidden');
                        } finally {
                            confirmBtn.disabled = false;
                            confirmBtn.textContent = 'Permanently Delete';
                        }
                    });
                }

                // Initial fetch
                refreshProfileData();
            })();
        </script>
    @endpush
@endsection
