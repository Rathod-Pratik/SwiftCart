@extends('layouts.app')

@section('title', 'Become a Seller — SwiftCart')

@section('content')
<div class="bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <div class="text-center mb-8">
            <span class="inline-block py-1 px-3 rounded-full text-xs font-semibold bg-[#204d4f]/10 text-[#204d4f] uppercase tracking-wider mb-2">Partner With Us</span>
            <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">Become a SwiftCart Seller</h1>
            <p class="mt-3 text-lg text-gray-600">Start selling to millions of active shoppers across the marketplace.</p>
        </div>

        @if (session('status'))
            <div class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 text-sm font-medium text-green-800" role="alert">
                {{ session('status') }}
            </div>
        @endif

        @guest
            <div class="bg-white shadow-sm rounded-2xl p-8 border border-gray-100 text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-[#204d4f]/10 text-[#204d4f] mb-4">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-900">Sign in to Apply</h2>
                <p class="mt-2 text-sm text-gray-600 max-w-md mx-auto">
                    Only authenticated customers can submit a seller registration application. Please log in or create an account to proceed.
                </p>
                <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('login') }}" class="inline-flex justify-center items-center px-6 py-2.5 rounded-lg text-sm font-medium text-white bg-[#204d4f] hover:bg-[#183a3b] transition">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex justify-center items-center px-6 py-2.5 rounded-lg text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 transition">
                        Create Customer Account
                    </a>
                </div>
            </div>
        @else
            @if ($vendor)
                {{-- Vendor Application Status Card --}}
                <div class="bg-white shadow-sm rounded-2xl p-8 border border-gray-100">
                    <div class="flex items-center justify-between pb-6 border-b border-gray-100">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">{{ $vendor->store_name }}</h2>
                            <p class="text-sm text-gray-500">Submitted on {{ $vendor->created_at->format('M d, Y') }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            @if($vendor->status === 'approved') bg-green-100 text-green-800
                            @elseif($vendor->status === 'rejected') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst($vendor->status) }}
                        </span>
                    </div>

                    <div class="mt-6">
                        @if ($vendor->status === 'approved')
                            <div class="p-4 rounded-lg bg-green-50 border border-green-100 mb-6 text-sm text-green-800">
                                <p class="font-semibold">Congratulations! Your vendor profile has been approved.</p>
                                <p class="mt-1">You now have full access to create products and manage your store inventory.</p>
                            </div>
                            <a href="{{ route('vendor.dashboard') }}" class="inline-flex items-center justify-center px-6 py-2.5 rounded-lg text-sm font-medium text-white bg-[#204d4f] hover:bg-[#183a3b] transition">
                                Go to Seller Dashboard
                            </a>
                        @elseif ($vendor->status === 'rejected')
                            <div class="p-4 rounded-lg bg-red-50 border border-red-100 text-sm text-red-800">
                                <p class="font-semibold">Your seller application was not approved.</p>
                                <p class="mt-1">Please contact support or an administrator if you believe this was an error.</p>
                            </div>
                        @else
                            <div class="p-4 rounded-lg bg-yellow-50 border border-yellow-100 text-sm text-yellow-800">
                                <p class="font-semibold">Your seller application is under review.</p>
                                <p class="mt-1">Our compliance team is verifying your submitted store and business details. We will notify you once your application has been reviewed.</p>
                            </div>
                        @endif

                        <dl class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <dt class="text-xs uppercase font-semibold text-gray-500">Business Type</dt>
                                <dd class="mt-1 font-medium text-gray-800">{{ ucfirst(str_replace('_', ' ', $vendor->business_type)) }}</dd>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <dt class="text-xs uppercase font-semibold text-gray-500">Phone</dt>
                                <dd class="mt-1 font-medium text-gray-800">{{ $vendor->phone }}</dd>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg sm:col-span-2">
                                <dt class="text-xs uppercase font-semibold text-gray-500">Business Address</dt>
                                <dd class="mt-1 font-medium text-gray-800">{{ $vendor->business_address }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            @else
                {{-- Vendor Registration Form --}}
                <div class="bg-white shadow-sm rounded-2xl p-6 sm:p-10 border border-gray-100">
                    <form method="POST" action="{{ route('vendor.register.post') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div class="border-b border-gray-100 pb-4">
                            <h2 class="text-lg font-semibold text-gray-900">Store & Contact Details</h2>
                            <p class="text-xs text-gray-500">Provide the required store profile information.</p>
                        </div>

                        {{-- Store Name --}}
                        <div>
                            <label for="store_name" class="block text-sm font-medium text-gray-700">Store Name <span class="text-red-500">*</span></label>
                            <input type="text" id="store_name" name="store_name" value="{{ old('store_name') }}" required
                                   class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-[#204d4f] focus:ring-1 focus:ring-[#204d4f] outline-none">
                            @error('store_name')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Phone & Business Type --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number <span class="text-red-500">*</span></label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required
                                       class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-[#204d4f] focus:ring-1 focus:ring-[#204d4f] outline-none">
                                @error('phone')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="business_type" class="block text-sm font-medium text-gray-700">Business Type <span class="text-red-500">*</span></label>
                                <select id="business_type" name="business_type" required
                                        class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-[#204d4f] focus:ring-1 focus:ring-[#204d4f] outline-none bg-white">
                                    <option value="">Select business type</option>
                                    <option value="individual" {{ old('business_type') === 'individual' ? 'selected' : '' }}>Individual</option>
                                    <option value="registered business" {{ old('business_type') === 'registered business' ? 'selected' : '' }}>Registered Business</option>
                                </select>
                                @error('business_type')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Business Address --}}
                        <div>
                            <label for="business_address" class="block text-sm font-medium text-gray-700">Business Address <span class="text-red-500">*</span></label>
                            <textarea id="business_address" name="business_address" rows="3" required
                                      class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-[#204d4f] focus:ring-1 focus:ring-[#204d4f] outline-none">{{ old('business_address', auth()->user()->address) }}</textarea>
                            @error('business_address')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Store Description <span class="text-gray-400 font-normal">(Optional)</span></label>
                            <textarea id="description" name="description" rows="3"
                                      class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-[#204d4f] focus:ring-1 focus:ring-[#204d4f] outline-none">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="border-b border-gray-100 pt-4 pb-2">
                            <h2 class="text-lg font-semibold text-gray-900">Identity Verification & KYC (Compliance & Fraud Prevention)</h2>
                            <p class="text-xs text-gray-500">Submit your government identity proof and registration certificate for quick verification.</p>
                        </div>

                        {{-- ID Document Type & ID/PAN Number --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="id_document_type" class="block text-sm font-medium text-gray-700">Identity Document Type <span class="text-gray-400 font-normal">(Optional)</span></label>
                                <select id="id_document_type" name="id_document_type"
                                        class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-[#204d4f] focus:ring-1 focus:ring-[#204d4f] outline-none bg-white">
                                    <option value="">Select ID Type</option>
                                    <option value="Aadhaar Card" {{ old('id_document_type') === 'Aadhaar Card' ? 'selected' : '' }}>Aadhaar Card</option>
                                    <option value="PAN Card" {{ old('id_document_type') === 'PAN Card' ? 'selected' : '' }}>PAN Card</option>
                                    <option value="Passport" {{ old('id_document_type') === 'Passport' ? 'selected' : '' }}>Passport</option>
                                    <option value="Voter ID" {{ old('id_document_type') === 'Voter ID' ? 'selected' : '' }}>Voter ID</option>
                                    <option value="Driving License" {{ old('id_document_type') === 'Driving License' ? 'selected' : '' }}>Driving License</option>
                                </select>
                                @error('id_document_type')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="id_number" class="block text-sm font-medium text-gray-700">ID / PAN Number <span class="text-gray-400 font-normal">(Optional)</span></label>
                                <input type="text" id="id_number" name="id_number" value="{{ old('id_number') }}" placeholder="e.g. ABCDE1234F or 12-digit Aadhaar"
                                       class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-[#204d4f] focus:ring-1 focus:ring-[#204d4f] outline-none">
                                @error('id_number')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- ID Document Upload & Business Certificate Document --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="id_document_image" class="block text-sm font-medium text-gray-700">ID Document Upload <span class="text-gray-400 font-normal">(Photo/PDF, max 5MB)</span></label>
                                <input type="file" id="id_document_image" name="id_document_image" accept=".pdf,image/jpeg,image/png,image/webp"
                                       class="mt-1 block w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#204d4f]/10 file:text-[#204d4f] hover:file:bg-[#204d4f]/20">
                                @error('id_document_image')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="business_certificate_document" class="block text-sm font-medium text-gray-700">GST / Business Certificate Document <span class="text-gray-400 font-normal">(PDF/Image)</span></label>
                                <input type="file" id="business_certificate_document" name="business_certificate_document" accept=".pdf,image/jpeg,image/png,image/webp"
                                       class="mt-1 block w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#204d4f]/10 file:text-[#204d4f] hover:file:bg-[#204d4f]/20">
                                @error('business_certificate_document')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Registration & Tax numbers --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="business_registration_number" class="block text-sm font-medium text-gray-700">Business Registration Number <span class="text-gray-400 font-normal">(Optional)</span></label>
                                <input type="text" id="business_registration_number" name="business_registration_number" value="{{ old('business_registration_number') }}"
                                       class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-[#204d4f] focus:ring-1 focus:ring-[#204d4f] outline-none">
                                @error('business_registration_number')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tax_identification_number" class="block text-sm font-medium text-gray-700">Tax Identification Number (TIN/GST) <span class="text-gray-400 font-normal">(Optional)</span></label>
                                <input type="text" id="tax_identification_number" name="tax_identification_number" value="{{ old('tax_identification_number') }}"
                                       class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-[#204d4f] focus:ring-1 focus:ring-[#204d4f] outline-none">
                                @error('tax_identification_number')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Store Logo & Document Uploads --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="logo" class="block text-sm font-medium text-gray-700">Store Logo <span class="text-gray-400 font-normal">(JPEG, PNG, WEBP, max 2MB)</span></label>
                                <input type="file" id="logo" name="logo" accept="image/jpeg,image/png,image/webp"
                                       class="mt-1 block w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#204d4f]/10 file:text-[#204d4f] hover:file:bg-[#204d4f]/20">
                                @error('logo')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="verification_document" class="block text-sm font-medium text-gray-700">Other Verification Document <span class="text-gray-400 font-normal">(PDF, JPG, PNG, max 5MB)</span></label>
                                <input type="file" id="verification_document" name="verification_document" accept=".pdf,image/jpeg,image/png"
                                       class="mt-1 block w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#204d4f]/10 file:text-[#204d4f] hover:file:bg-[#204d4f]/20">
                                @error('verification_document')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Shipping & Warehouse Logistics Section --}}
                        <div class="border-b border-gray-100 pt-4 pb-2">
                            <h2 class="text-lg font-semibold text-gray-900">Shipping & Warehouse Logistics</h2>
                            <p class="text-xs text-gray-500">Pickup address for delivery couriers and dedicated customer service contacts.</p>
                        </div>

                        <div>
                            <label for="pickup_address" class="block text-sm font-medium text-gray-700">Pickup / Warehouse Address <span class="text-gray-400 font-normal">(Optional, if different from business address)</span></label>
                            <input type="text" id="pickup_address" name="pickup_address" value="{{ old('pickup_address') }}" placeholder="Warehouse street address, building or unit"
                                   class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-[#204d4f] focus:ring-1 focus:ring-[#204d4f] outline-none">
                            @error('pickup_address')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label for="pickup_city" class="block text-sm font-medium text-gray-700">Pickup City <span class="text-gray-400 font-normal">(Optional)</span></label>
                                <input type="text" id="pickup_city" name="pickup_city" value="{{ old('pickup_city') }}" placeholder="e.g. Mumbai"
                                       class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-[#204d4f] focus:ring-1 focus:ring-[#204d4f] outline-none">
                                @error('pickup_city')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="pickup_state" class="block text-sm font-medium text-gray-700">Pickup State <span class="text-gray-400 font-normal">(Optional)</span></label>
                                <input type="text" id="pickup_state" name="pickup_state" value="{{ old('pickup_state') }}" placeholder="e.g. Maharashtra"
                                       class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-[#204d4f] focus:ring-1 focus:ring-[#204d4f] outline-none">
                                @error('pickup_state')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="pickup_pincode" class="block text-sm font-medium text-gray-700">Postal / Pincode <span class="text-gray-400 font-normal">(Optional)</span></label>
                                <input type="text" id="pickup_pincode" name="pickup_pincode" value="{{ old('pickup_pincode') }}" placeholder="e.g. 400001"
                                       class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-[#204d4f] focus:ring-1 focus:ring-[#204d4f] outline-none">
                                @error('pickup_pincode')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="support_email" class="block text-sm font-medium text-gray-700">Customer Support Email <span class="text-gray-400 font-normal">(Optional)</span></label>
                                <input type="email" id="support_email" name="support_email" value="{{ old('support_email') }}" placeholder="support@yourbrand.com"
                                       class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-[#204d4f] focus:ring-1 focus:ring-[#204d4f] outline-none">
                                @error('support_email')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="alternate_phone" class="block text-sm font-medium text-gray-700">Alternate Contact Number <span class="text-gray-400 font-normal">(Optional)</span></label>
                                <input type="text" id="alternate_phone" name="alternate_phone" value="{{ old('alternate_phone') }}" placeholder="Secondary mobile or helpline"
                                       class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-[#204d4f] focus:ring-1 focus:ring-[#204d4f] outline-none">
                                @error('alternate_phone')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                            <p class="text-xs text-gray-500">
                                Bank and payout details are collected separately once approved.
                            </p>
                            <button type="submit" class="inline-flex justify-center items-center px-6 py-2.5 rounded-lg text-sm font-medium text-white bg-[#204d4f] hover:bg-[#183a3b] transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#204d4f]">
                                Submit Application
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        @endguest
    </div>
</div>
@endsection
