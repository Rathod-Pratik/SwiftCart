@extends('layouts.Vendor')

@section('content')
    @php($productId = request()->query('_id'))

    <x-slot name="title">{{ $productId ? 'Edit Product' : 'Create Product' }}</x-slot>

    <div class="p-4 sm:p-6 lg:p-8">
        {{-- Page Header --}}
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <nav class="mb-1 flex items-center gap-2 text-xs font-medium text-gray-500">
                    <a href="{{ route('vendor.dashboard') }}" class="hover:text-[#3d6f6d]">Dashboard</a>
                    <span>/</span>
                    <a href="{{ route('vendor.product') }}" class="hover:text-[#3d6f6d]">Products</a>
                    <span>/</span>
                    <span class="text-[#3d6f6d]">{{ $productId ? 'Edit' : 'Create' }}</span>
                </nav>
                <h1 id="page-title" class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">
                    {{ $productId ? 'Edit Product' : 'Create New Product' }}
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    {{ $productId ? 'Update and manage your store item specifications and inventory.' : 'Provide comprehensive details to list a new product in your store.' }}
                </p>
            </div>
            <div class="flex items-center gap-3">
               
                <a href="{{ route('vendor.product') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 transition">
                    Back to Products
                </a>
            </div>
        </div>

        <form id="product-form" data-product-id="{{ $productId }}" class="flex flex-col gap-6" novalidate>
            @csrf

            {{-- Top Alert / Error Banner --}}
            <div id="form-status" class="hidden rounded-xl border px-5 py-4 text-sm font-medium shadow-sm" role="alert"></div>

            {{-- ======================================================== --}}
            {{-- SECTION 1: BASIC INFORMATION --}}
            {{-- ======================================================== --}}
            <section id="section-basic" class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-7">
                <div class="mb-5 flex items-center gap-3 border-b border-gray-100 pb-4">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#3d6f6d]/10 text-sm font-bold text-[#3d6f6d]">
                        1
                    </span>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Basic Information</h2>
                        <p class="text-xs text-gray-500">Essential identifiers and classification for your product listing.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    {{-- Product Name --}}
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700" for="input-name">
                            Product Name <span class="text-rose-500 font-bold">*</span>
                        </label>
                        <input id="input-name" name="name" type="text" maxlength="255" placeholder="e.g. Sony WH-1000XM5 Wireless Noise-Cancelling Headphones" class="mt-2 w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                        <p id="error-name" class="mt-1.5 text-xs font-medium text-rose-600 hidden"></p>
                    </div>

                    {{-- Category --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700" for="category_id">
                            Product Category <span class="text-rose-500 font-bold">*</span>
                        </label>
                        <select name="category_id" id="category_id" class="mt-2 w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                            <option value="">Loading categories...</option>
                        </select>
                        <p id="error-category_id" class="mt-1.5 text-xs font-medium text-rose-600 hidden"></p>
                    </div>

                    {{-- Store External Link --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700" for="input-store_link">
                            Store / Brand Website Link <span class="text-xs font-normal text-gray-400">(Optional)</span>
                        </label>
                        <input id="input-store_link" name="store_link" type="url" placeholder="https://brand.com/products/headphones" class="mt-2 w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                        <p id="error-store_link" class="mt-1.5 text-xs font-medium text-rose-600 hidden"></p>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700" for="select-status">
                            Catalog Status <span class="text-rose-500 font-bold">*</span>
                        </label>
                        <select id="select-status" name="status" class="mt-2 w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                            <option value="active">Active (Available for purchase)</option>
                            <option value="inactive">Inactive (Hidden from catalog)</option>
                        </select>
                    </div>

                    {{-- Visibility --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700" for="select-visibility">
                            Visibility <span class="text-rose-500 font-bold">*</span>
                        </label>
                        <select id="select-visibility" name="visibility" class="mt-2 w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                            <option value="public">Public (Visible in search & browsing)</option>
                            <option value="private">Private (Only accessible with direct link)</option>
                        </select>
                    </div>
                </div>
            </section>

            {{-- ======================================================== --}}
            {{-- SECTION 2: PRICING & INVENTORY --}}
            {{-- ======================================================== --}}
            <section id="section-pricing" class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-7">
                <div class="mb-5 flex items-center gap-3 border-b border-gray-100 pb-4">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#3d6f6d]/10 text-sm font-bold text-[#3d6f6d]">
                        2
                    </span>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Pricing & Inventory</h2>
                        <p class="text-xs text-gray-500">Configure base price, discounts, available warehouse stock, and offer IDs.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                    {{-- Regular Price --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700" for="input-price">
                            Regular Price (₹) <span class="text-rose-500 font-bold">*</span>
                        </label>
                        <div class="relative mt-2">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-sm font-bold text-gray-400">₹</span>
                            <input id="input-price" name="price" type="number" min="0" step="0.01" placeholder="2499.00" class="w-full rounded-xl border border-gray-300 pl-9 pr-4 py-3 text-sm font-semibold outline-none transition focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                        </div>
                        <p id="error-price" class="mt-1.5 text-xs font-medium text-rose-600 hidden"></p>
                    </div>

                    {{-- Discount --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700" for="input-discount">
                            Discount (%) <span class="text-xs font-normal text-gray-400">(Optional)</span>
                        </label>
                        <div class="relative mt-2">
                            <input id="input-discount" name="discount" type="number" min="0" max="100" step="0.01" value="0" placeholder="10" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                            <span class="absolute inset-y-0 right-0 flex items-center pr-4 text-sm font-bold text-gray-400">%</span>
                        </div>
                        <p id="error-discount" class="mt-1.5 text-xs font-medium text-rose-600 hidden"></p>
                    </div>

                    {{-- Stock Quantity --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700" for="input-stock">
                            Available Stock Units <span class="text-rose-500 font-bold">*</span>
                        </label>
                        <input id="input-stock" name="stock" type="number" min="0" step="1" placeholder="50" class="mt-2 w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                        <p id="error-stock" class="mt-1.5 text-xs font-medium text-rose-600 hidden"></p>
                    </div>

                    {{-- Live Price Summary Note --}}
                    <div class="sm:col-span-3">
                        <div id="price-preview-banner" class="flex flex-wrap items-center justify-between rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-2.5 text-xs font-medium text-emerald-800">
                            <span>Final Customer Price: <strong id="preview-final-price" class="text-sm font-bold text-emerald-900">₹0.00</strong></span>
                            <span id="preview-discount-saved" class="text-emerald-700">No discount applied</span>
                        </div>
                    </div>

                    {{-- Base/Initial Rating --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700" for="input-rating">
                            Base Rating (0 to 5) <span class="text-xs font-normal text-gray-400">(Optional)</span>
                        </label>
                        <div class="relative mt-2">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-amber-500 font-bold">★</span>
                            <input id="input-rating" name="rating" type="number" min="0" max="5" step="0.1" placeholder="4.5" class="w-full rounded-xl border border-gray-300 pl-8 pr-4 py-3 text-sm outline-none transition focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                        </div>
                        <p id="error-rating" class="mt-1.5 text-xs font-medium text-rose-600 hidden"></p>
                    </div>

                    {{-- Offer ID --}}
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700" for="input-offerId">
                            Promotional Offer ID / Code <span class="text-xs font-normal text-gray-400">(Optional)</span>
                        </label>
                        <input id="input-offerId" name="offerId" maxlength="255" placeholder="e.g. FESTIVE_SAVINGS_2026" class="mt-2 w-full rounded-xl border border-gray-300 px-4 py-3 text-sm uppercase outline-none transition focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                        <p class="mt-1 text-[11px] text-gray-400">Associates special discounts or coupon eligibility badges with this product.</p>
                    </div>
                </div>
            </section>

            {{-- ======================================================== --}}
            {{-- SECTION 3: MEDIA & PRODUCT GALLERY --}}
            {{-- ======================================================== --}}
            <section id="section-media" class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-7">
                <div class="mb-5 flex items-center gap-3 border-b border-gray-100 pb-4">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#3d6f6d]/10 text-sm font-bold text-[#3d6f6d]">
                        3
                    </span>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Media & Visual Assets</h2>
                        <p class="text-xs text-gray-500">Upload primary product showcase images and optional manufacturer authenticity proofs.</p>
                    </div>
                </div>

                <div class="flex flex-col gap-6">
                    {{-- Primary Product Images --}}
                    <div class="rounded-xl border border-gray-200 bg-gray-50/50 p-4 sm:p-5">
                        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <label class="text-sm font-semibold text-gray-900">
                                    Primary Product Images <span class="text-rose-500 font-bold">*</span>
                                </label>
                                <p class="text-xs text-gray-500">At least one product image is required. Supports JPG, PNG, WEBP (Max 5MB each).</p>
                            </div>
                            <span id="product-images-count" class="text-xs font-medium text-gray-500">0 images attached</span>
                        </div>

                        <div class="mt-3 flex items-center gap-3">
                            <label for="product-images-input" class="inline-flex cursor-pointer items-center gap-2 rounded-xl bg-[#3d6f6d] px-4 py-2.5 text-xs font-semibold text-white shadow-sm transition hover:bg-[#315b59]">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Select Product Images
                            </label>
                            <input type="file" id="product-images-input" multiple accept="image/jpeg,image/png,image/webp,image/gif" class="hidden">
                        </div>
                        <p id="error-image" class="mt-2 text-xs font-medium text-rose-600 hidden"></p>

                        {{-- Product Images Preview Container --}}
                        <div id="product-images-preview" class="mt-4 flex flex-wrap gap-3 empty:hidden"></div>
                    </div>

                    {{-- Manufacturer / Proof Images --}}
                    <div class="rounded-xl border border-gray-200 bg-gray-50/50 p-4 sm:p-5">
                        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <label class="text-sm font-semibold text-gray-900">
                                    Manufacturer / Authenticity Images <span class="text-xs font-normal text-gray-400">(Optional)</span>
                                </label>
                                <p class="text-xs text-gray-500">Factory inspection, package seal, certifications or origin verification photos.</p>
                            </div>
                            <span id="m-images-count" class="text-xs font-medium text-gray-500">0 images attached</span>
                        </div>

                        <div class="mt-3 flex items-center gap-3">
                            <label for="m-images-input" class="inline-flex cursor-pointer items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-xs font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
                                <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Select Manufacturer Images
                            </label>
                            <input type="file" id="m-images-input" multiple accept="image/jpeg,image/png,image/webp,image/gif" class="hidden">
                        </div>
                        <p id="error-m-image" class="mt-2 text-xs font-medium text-rose-600 hidden"></p>

                        {{-- Manufacturer Images Preview Container --}}
                        <div id="m-images-preview" class="mt-4 flex flex-wrap gap-3 empty:hidden"></div>
                    </div>
                </div>
            </section>

            {{-- ======================================================== --}}
            {{-- SECTION 4: SPECIFICATIONS & FEATURES --}}
            {{-- ======================================================== --}}
            <section id="section-specifications" class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-7">
                <div class="mb-5 flex items-center gap-3 border-b border-gray-100 pb-4">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#3d6f6d]/10 text-sm font-bold text-[#3d6f6d]">
                        4
                    </span>
                    <div class="flex-1">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h2 class="text-lg font-bold text-gray-900">Features & Specifications <span class="text-rose-500 font-bold">*</span></h2>
                                <p class="text-xs text-gray-500">Provide key-value pairs detailing specifications (e.g. Color: Matte Black, Warranty: 1 Year).</p>
                            </div>
                            <button type="button" id="add-feature-btn" class="inline-flex w-fit items-center gap-1.5 rounded-xl border border-[#3d6f6d] px-3.5 py-2 text-xs font-semibold text-[#3d6f6d] hover:bg-[#3d6f6d]/5 transition">
                                + Add Specification
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Quick Suggestion Chips --}}
                <div class="mb-4 flex flex-wrap items-center gap-1.5 text-xs text-gray-500">
                    <span class="font-medium text-gray-600">Quick suggestions:</span>
                    @foreach (['Brand', 'Model', 'Color', 'Material', 'Warranty', 'Dimensions', 'Weight', 'Connectivity'] as $chip)
                        <button type="button" class="spec-chip rounded-lg bg-gray-100 px-2.5 py-1 text-gray-700 hover:bg-[#3d6f6d] hover:text-white transition">
                            + {{ $chip }}
                        </button>
                    @endforeach
                </div>

                <div id="features-container" class="flex flex-col gap-2.5"></div>
                <p id="error-features" class="mt-2 text-xs font-medium text-rose-600 hidden"></p>
            </section>

            {{-- ======================================================== --}}
            {{-- SECTION 5: HIGHLIGHTS & DETAILED DESCRIPTION --}}
            {{-- ======================================================== --}}
            <section id="section-highlights" class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-7">
                <div class="mb-5 flex items-center gap-3 border-b border-gray-100 pb-4">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#3d6f6d]/10 text-sm font-bold text-[#3d6f6d]">
                        5
                    </span>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Description & Bullet Highlights</h2>
                        <p class="text-xs text-gray-500">Tell a compelling story with structured paragraphs and quick bullet points.</p>
                    </div>
                </div>

                <div class="flex flex-col gap-6">
                    {{-- About This Item (Bullet Points) --}}
                    <div class="rounded-xl border border-gray-200 p-4 sm:p-5">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <label class="text-sm font-semibold text-gray-900">About This Item (Bullet Points) <span class="text-rose-500 font-bold">*</span></label>
                                <p class="text-xs text-gray-500">Key selling propositions displayed prominently on the product card.</p>
                            </div>
                            <button type="button" id="add-about-btn" class="inline-flex w-fit items-center gap-1.5 rounded-xl border border-[#3d6f6d] px-3.5 py-2 text-xs font-semibold text-[#3d6f6d] hover:bg-[#3d6f6d]/5 transition">
                                + Add Bullet Point
                            </button>
                        </div>
                        <div id="about-container" class="mt-3.5 flex flex-col gap-2.5"></div>
                        <p id="error-about" class="mt-2 text-xs font-medium text-rose-600 hidden"></p>
                    </div>

                    {{-- Description Paragraphs --}}
                    <div class="rounded-xl border border-gray-200 p-4 sm:p-5">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <label class="text-sm font-semibold text-gray-900">Detailed Description Paragraphs <span class="text-rose-500 font-bold">*</span></label>
                                <p class="text-xs text-gray-500">Divide in-depth product information into structured, readable paragraphs.</p>
                            </div>
                            <button type="button" id="add-paragraph-btn" class="inline-flex w-fit items-center gap-1.5 rounded-xl border border-[#3d6f6d] px-3.5 py-2 text-xs font-semibold text-[#3d6f6d] hover:bg-[#3d6f6d]/5 transition">
                                + Add Paragraph
                            </button>
                        </div>
                        <div id="paragraphs-container" class="mt-3.5 flex flex-col gap-3"></div>
                        <p id="error-description" class="mt-2 text-xs font-medium text-rose-600 hidden"></p>
                    </div>
                </div>
            </section>

            {{-- ======================================================== --}}
            {{-- SECTION 6: SERVICES, BADGES & HIGHLIGHTS --}}
            {{-- ======================================================== --}}
            <section id="section-services" class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-7">
                <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between border-b border-gray-100 pb-4">
                    <div class="flex items-center gap-3">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#3d6f6d]/10 text-sm font-bold text-[#3d6f6d]">
                            6
                        </span>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">Services & Promotional Badges</h2>
                            <p class="text-xs text-gray-500">Attach delivery perks (Cash on Delivery, Express Shipping, etc.) and promotional badges.</p>
                        </div>
                    </div>
                    <a href="{{ route('vendor.service.create') }}" target="_blank" class="inline-flex items-center gap-1.5 rounded-xl border border-[#3d6f6d]/30 bg-[#3d6f6d]/5 px-3.5 py-2 text-xs font-semibold text-[#3d6f6d] hover:bg-[#3d6f6d] hover:text-white transition">
                        <x-lucide-plus class="h-3.5 w-3.5" />
                        <span>Create New Service</span>
                    </a>
                </div>

                <div class="flex flex-col gap-6">
                    {{-- Vendor Services Selection --}}
                    <div>
                        <div class="mb-3 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <label class="block text-sm font-semibold text-gray-900">
                                    Select Services for This Product
                                </label>
                                <p class="text-xs text-gray-500">Pick from your configured services and standard delivery perks below with icons from the library.</p>
                            </div>
                            <span id="service-selection-count" class="w-fit rounded-lg border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                0 perks selected
                            </span>
                        </div>

                        {{-- Dynamic Service Cards with Vector Icons from Library --}}
                        <div id="service-suggestions-container" class="grid grid-cols-1 gap-2.5 sm:grid-cols-2 lg:grid-cols-3">
                            <div class="col-span-full py-4 text-center text-xs text-gray-400">Loading service perks from library...</div>
                        </div>

                        {{-- Hidden/Synchronized input --}}
                        <input id="input-vendor_service_id" name="vendor_service_id" type="hidden" value="">

                        {{-- Selected Badges Summary --}}
                        <div class="mt-4 border-t border-gray-100 pt-3">
                            <span class="mb-2 block text-xs font-semibold text-gray-500">Active Perks for This Product:</span>
                            <div id="selected-services-chips" class="flex flex-wrap gap-2">
                                <span class="text-xs italic text-gray-400">None selected yet. Click cards above to attach perks.</span>
                            </div>
                        </div>

                        {{-- Inline Quick Add Service --}}
                        <div class="mt-4 rounded-xl border border-dashed border-gray-300 bg-gray-50/70 p-3.5">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-semibold text-gray-700">+ Add Custom Service Perk:</span>
                                <a href="{{ route('vendor.service') }}" target="_blank" class="text-2xs font-semibold text-[#3d6f6d] hover:underline">
                                    Manage All Services &rarr;
                                </a>
                            </div>
                            <div class="flex flex-col gap-2 sm:flex-row">
                                <input type="text" id="inline-service-name" placeholder="Service name (e.g. Free Gift Inside, 1-Year Guarantee)"
                                    class="flex-1 rounded-xl border border-gray-300 bg-white px-3.5 py-2 text-xs outline-none transition focus:border-[#3d6f6d] focus:ring-1 focus:ring-[#3d6f6d]">
                                <select id="inline-service-icon" class="rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-700 outline-none transition focus:border-[#3d6f6d]">
                                    <option value="truck">🚚 Express Shipping (truck)</option>
                                    <option value="banknote">💵 Cash on Delivery (banknote)</option>
                                    <option value="box">📦 Free Delivery / Box (box)</option>
                                    <option value="shield-check">🛡️ Warranty / Protection (shield-check)</option>
                                    <option value="refresh-cw">🔄 7-Day Replacement (refresh-cw)</option>
                                    <option value="headset">🎧 24/7 Support (headset)</option>
                                    <option value="award">🎖️ Quality Guaranteed (award)</option>
                                    <option value="zap">⚡ Lightning Dispatch (zap)</option>
                                    <option value="gift">🎁 Free Surprise Gift (gift)</option>
                                </select>
                                <button type="button" id="inline-add-service-btn"
                                    class="inline-flex items-center justify-center gap-1 rounded-xl bg-[#3d6f6d] px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-[#315b59]">
                                    + Add Perk
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Promotional Badges --}}
                    <div class="border-t border-gray-100 pt-4">
                        <label class="mb-2 block text-sm font-semibold text-gray-700">Promotional Badges</label>
                        <div class="flex flex-wrap gap-4 sm:gap-6">
                            @foreach (['is_featured' => 'Featured Product', 'is_trending' => 'Trending Deal', 'is_limited' => 'Limited Stock'] as $name => $label)
                                <label class="inline-flex cursor-pointer items-center gap-2 rounded-xl border border-gray-200 bg-gray-50/50 px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:border-[#3d6f6d] hover:bg-white">
                                    <input type="checkbox" name="{{ $name }}" value="1" class="h-4 w-4 rounded border-gray-300 text-[#3d6f6d] focus:ring-[#3d6f6d]">
                                    {{ $label }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>

            {{-- Form Submit & Cancel Controls --}}
            <div class="sticky bottom-3 z-20 flex flex-col-reverse gap-3 rounded-2xl border border-gray-200 bg-white/95 p-4 shadow-lg backdrop-blur sm:flex-row sm:items-center sm:justify-between">
                <p class="text-xs text-gray-500">
                    Review your inputs across all sections before submitting.
                </p>
                <div class="flex items-center gap-3">
                    <a href="{{ route('vendor.product') }}" class="inline-flex justify-center rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex justify-center items-center gap-2 rounded-xl bg-[#3d6f6d] px-7 py-2.5 text-sm font-semibold text-white shadow transition hover:bg-[#315b59]">
                        <span id="submit-spinner" class="hidden h-4 w-4 animate-spin rounded-full border-2 border-white border-t-transparent"></span>
                        <span id="submit-label">{{ $productId ? 'Save Changes' : 'Create Product' }}</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            (() => {
                const form = document.querySelector('#product-form');
                const productId = form.dataset.productId;
                const productUrl = @json(url('/api/products'));
                const categoryUrl = @json(url('/api/categories'));
                const statusAlert = document.querySelector('#form-status');
                const submitBtn = form.querySelector('button[type="submit"]');
                const submitLabel = document.querySelector('#submit-label');
                const submitSpinner = document.querySelector('#submit-spinner');
                const csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? @json(csrf_token());

                // State collections
                let selectedProductFiles = [];
                let existingProductImages = [];
                let selectedMFiles = [];
                let existingMImages = [];

                // Helper utilities
                const escapeHtml = (val) => String(val ?? '').replace(/[&<>"']/g, (c) => ({
                    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;',
                }[c]));

                const setFieldError = (fieldId, msg) => {
                    const errorEl = document.querySelector(`#error-${fieldId}`);
                    const inputEl = form.elements[fieldId] || document.querySelector(`#input-${fieldId}`);
                    if (errorEl) {
                        if (msg) {
                            errorEl.textContent = msg;
                            errorEl.classList.remove('hidden');
                        } else {
                            errorEl.textContent = '';
                            errorEl.classList.add('hidden');
                        }
                    }
                    if (inputEl) {
                        if (msg) {
                            inputEl.classList.add('border-rose-500', 'focus:border-rose-500', 'focus:ring-rose-200');
                            inputEl.classList.remove('border-gray-300', 'focus:border-[#3d6f6d]', 'focus:ring-[#3d6f6d]/20');
                        } else {
                            inputEl.classList.remove('border-rose-500', 'focus:border-rose-500', 'focus:ring-rose-200');
                            inputEl.classList.add('border-gray-300', 'focus:border-[#3d6f6d]', 'focus:ring-[#3d6f6d]/20');
                        }
                    }
                };

                const clearAllErrors = () => {
                    document.querySelectorAll('[id^="error-"]').forEach((el) => {
                        el.textContent = '';
                        el.classList.add('hidden');
                    });
                    document.querySelectorAll('.border-rose-500').forEach((el) => {
                        el.classList.remove('border-rose-500', 'focus:border-rose-500', 'focus:ring-rose-200');
                        el.classList.add('border-gray-300', 'focus:border-[#3d6f6d]', 'focus:ring-[#3d6f6d]/20');
                    });
                };

                const resolveImageUrl = async (keyOrUrl) => {
                    if (!keyOrUrl) return '';
                    if (keyOrUrl.startsWith('http://') || keyOrUrl.startsWith('https://')) return keyOrUrl;
                    try {
                        const res = await fetch(`/api/s3/sign?key=${encodeURIComponent(keyOrUrl)}`, { headers: { Accept: 'application/json' } });
                        if (res.ok) {
                            const data = await res.json();
                            return data.url || keyOrUrl;
                        }
                    } catch {}
                    return keyOrUrl;
                };

                // --- Live Price Calculator ---
                const priceInput = document.querySelector('#input-price');
                const discountInput = document.querySelector('#input-discount');
                const previewFinalPrice = document.querySelector('#preview-final-price');
                const previewDiscountSaved = document.querySelector('#preview-discount-saved');

                const updatePricePreview = () => {
                    const price = parseFloat(priceInput.value) || 0;
                    const discount = parseFloat(discountInput.value) || 0;
                    if (price <= 0) {
                        previewFinalPrice.textContent = '₹0.00';
                        previewDiscountSaved.textContent = 'Enter a valid price above';
                        return;
                    }
                    const discountAmt = (price * (discount / 100));
                    const finalPrice = Math.max(0, price - discountAmt);
                    previewFinalPrice.textContent = `₹${finalPrice.toFixed(2)}`;
                    if (discount > 0) {
                        previewDiscountSaved.textContent = `Customer saves ₹${discountAmt.toFixed(2)} (${discount}%)`;
                    } else {
                        previewDiscountSaved.textContent = 'No discount applied';
                    }
                };
                priceInput.addEventListener('input', updatePricePreview);
                discountInput.addEventListener('input', updatePricePreview);

                // --- 1. Product Images ---
                const productImgInput = document.querySelector('#product-images-input');
                const productImgPreview = document.querySelector('#product-images-preview');
                const productImgCount = document.querySelector('#product-images-count');

                const renderProductImages = async () => {
                    productImgPreview.innerHTML = '';
                    const totalCount = existingProductImages.length + selectedProductFiles.length;
                    productImgCount.textContent = totalCount ? `${totalCount} image${totalCount === 1 ? '' : 's'} attached` : 'No files selected';

                    // Existing images
                    for (let i = 0; i < existingProductImages.length; i++) {
                        const key = existingProductImages[i];
                        const div = document.createElement('div');
                        div.className = 'group relative h-24 w-24 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm';
                        div.innerHTML = `
                            <img src="/images/placeholder.png" alt="Product Image" class="h-full w-full object-cover">
                            <span class="absolute bottom-1 left-1 rounded bg-black/60 px-1 py-0.5 text-[9px] font-medium text-white">Saved</span>
                            <button type="button" class="absolute right-1 top-1 flex h-6 w-6 items-center justify-center rounded-full bg-rose-600 text-xs font-bold text-white opacity-0 transition group-hover:opacity-100 hover:bg-rose-700" title="Remove">&times;</button>
                        `;
                        const imgTag = div.querySelector('img');
                        resolveImageUrl(key).then((src) => { if (src) imgTag.src = src; });
                        div.querySelector('button').addEventListener('click', () => {
                            existingProductImages.splice(i, 1);
                            renderProductImages();
                            validateImages();
                        });
                        productImgPreview.appendChild(div);
                    }

                    // Newly chosen files
                    selectedProductFiles.forEach((file, index) => {
                        const div = document.createElement('div');
                        div.className = 'group relative h-24 w-24 overflow-hidden rounded-xl border border-emerald-300 bg-white shadow-sm';
                        const objectUrl = URL.createObjectURL(file);
                        div.innerHTML = `
                            <img src="${objectUrl}" alt="${escapeHtml(file.name)}" class="h-full w-full object-cover">
                            <span class="absolute bottom-1 left-1 rounded bg-emerald-600 px-1.5 py-0.5 text-[9px] font-medium text-white">New</span>
                            <button type="button" class="absolute right-1 top-1 flex h-6 w-6 items-center justify-center rounded-full bg-rose-600 text-xs font-bold text-white opacity-90 transition hover:bg-rose-700" title="Remove">&times;</button>
                        `;
                        div.querySelector('button').addEventListener('click', () => {
                            URL.revokeObjectURL(objectUrl);
                            selectedProductFiles.splice(index, 1);
                            renderProductImages();
                            validateImages();
                        });
                        productImgPreview.appendChild(div);
                    });
                };

                const validateImages = () => {
                    const totalImages = existingProductImages.length + selectedProductFiles.length;
                    if (totalImages === 0) {
                        setFieldError('image', 'At least one product image is required.');
                        return false;
                    }
                    setFieldError('image', '');
                    return true;
                };

                productImgInput.addEventListener('change', (e) => {
                    const files = Array.from(e.target.files);
                    const validTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
                    for (const file of files) {
                        if (!validTypes.includes(file.type)) {
                            setFieldError('image', `File "${file.name}" is not a supported image format.`);
                            return;
                        }
                        if (file.size > 5 * 1024 * 1024) {
                            setFieldError('image', `File "${file.name}" exceeds the 5MB size limit.`);
                            return;
                        }
                        selectedProductFiles.push(file);
                    }
                    validateImages();
                    productImgInput.value = '';
                    renderProductImages();
                });

                // --- 2. Manufacturer Images ---
                const mImgInput = document.querySelector('#m-images-input');
                const mImgPreview = document.querySelector('#m-images-preview');
                const mImgCount = document.querySelector('#m-images-count');

                const renderMImages = async () => {
                    mImgPreview.innerHTML = '';
                    const totalCount = existingMImages.length + selectedMFiles.length;
                    mImgCount.textContent = totalCount ? `${totalCount} image${totalCount === 1 ? '' : 's'} attached` : 'No files selected';

                    for (let i = 0; i < existingMImages.length; i++) {
                        const key = existingMImages[i];
                        const div = document.createElement('div');
                        div.className = 'group relative h-24 w-24 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm';
                        div.innerHTML = `
                            <img src="/images/placeholder.png" alt="Manufacturer" class="h-full w-full object-cover">
                            <span class="absolute bottom-1 left-1 rounded bg-black/60 px-1 py-0.5 text-[9px] font-medium text-white">Saved</span>
                            <button type="button" class="absolute right-1 top-1 flex h-6 w-6 items-center justify-center rounded-full bg-rose-600 text-xs font-bold text-white opacity-0 transition group-hover:opacity-100 hover:bg-rose-700" title="Remove">&times;</button>
                        `;
                        const imgTag = div.querySelector('img');
                        resolveImageUrl(key).then((src) => { if (src) imgTag.src = src; });
                        div.querySelector('button').addEventListener('click', () => {
                            existingMImages.splice(i, 1);
                            renderMImages();
                        });
                        mImgPreview.appendChild(div);
                    }

                    selectedMFiles.forEach((file, index) => {
                        const div = document.createElement('div');
                        div.className = 'group relative h-24 w-24 overflow-hidden rounded-xl border border-emerald-300 bg-white shadow-sm';
                        const objectUrl = URL.createObjectURL(file);
                        div.innerHTML = `
                            <img src="${objectUrl}" alt="${escapeHtml(file.name)}" class="h-full w-full object-cover">
                            <span class="absolute bottom-1 left-1 rounded bg-emerald-600 px-1.5 py-0.5 text-[9px] font-medium text-white">New</span>
                            <button type="button" class="absolute right-1 top-1 flex h-6 w-6 items-center justify-center rounded-full bg-rose-600 text-xs font-bold text-white opacity-90 transition hover:bg-rose-700" title="Remove">&times;</button>
                        `;
                        div.querySelector('button').addEventListener('click', () => {
                            URL.revokeObjectURL(objectUrl);
                            selectedMFiles.splice(index, 1);
                            renderMImages();
                        });
                        mImgPreview.appendChild(div);
                    });
                };

                mImgInput.addEventListener('change', (e) => {
                    const files = Array.from(e.target.files);
                    const validTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
                    for (const file of files) {
                        if (!validTypes.includes(file.type)) {
                            setFieldError('m-image', `File "${file.name}" is not a supported image format.`);
                            return;
                        }
                        if (file.size > 5 * 1024 * 1024) {
                            setFieldError('m-image', `File "${file.name}" exceeds 5MB size.`);
                            return;
                        }
                        selectedMFiles.push(file);
                    }
                    setFieldError('m-image', '');
                    mImgInput.value = '';
                    renderMImages();
                });

                // --- 3. Features & Specifications ---
                const featuresContainer = document.querySelector('#features-container');
                const addFeatureBtn = document.querySelector('#add-feature-btn');

                const addFeatureRow = (key = '', val = '') => {
                    const row = document.createElement('div');
                    row.className = 'feature-row flex items-center gap-2';
                    row.innerHTML = `
                        <input type="text" placeholder="Key (e.g. Color)" value="${escapeHtml(key)}" class="feature-key w-1/3 rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm outline-none transition focus:border-[#3d6f6d] focus:ring-1 focus:ring-[#3d6f6d]">
                        <input type="text" placeholder="Value (e.g. Matte Black)" value="${escapeHtml(val)}" class="feature-val flex-1 rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm outline-none transition focus:border-[#3d6f6d] focus:ring-1 focus:ring-[#3d6f6d]">
                        <button type="button" class="remove-btn rounded-xl p-2 text-gray-400 transition hover:bg-rose-50 hover:text-rose-600" title="Delete specification">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    `;
                    row.querySelector('.remove-btn').addEventListener('click', () => {
                        if (featuresContainer.children.length > 1) {
                            row.remove();
                        } else {
                            row.querySelector('.feature-key').value = '';
                            row.querySelector('.feature-val').value = '';
                        }
                        validateFeatures();
                    });
                    row.querySelector('.feature-key').addEventListener('input', validateFeatures);
                    row.querySelector('.feature-val').addEventListener('input', validateFeatures);
                    featuresContainer.appendChild(row);
                };
                addFeatureBtn.addEventListener('click', () => {
                    addFeatureRow();
                    validateFeatures();
                });

                // Specification Quick Chips
                document.querySelectorAll('.spec-chip').forEach((chip) => {
                    chip.addEventListener('click', () => {
                        const keyName = chip.textContent.replace('+', '').trim();
                        // Check if an empty row exists, otherwise add one
                        let filled = false;
                        document.querySelectorAll('.feature-row').forEach((row) => {
                            const keyInput = row.querySelector('.feature-key');
                            if (!filled && !keyInput.value.trim()) {
                                keyInput.value = keyName;
                                row.querySelector('.feature-val').focus();
                                filled = true;
                            }
                        });
                        if (!filled) {
                            addFeatureRow(keyName, '');
                            const lastRow = featuresContainer.lastElementChild;
                            lastRow.querySelector('.feature-val').focus();
                        }
                        validateFeatures();
                    });
                });

                const validateFeatures = () => {
                    let hasPair = false;
                    document.querySelectorAll('.feature-row').forEach((row) => {
                        const k = row.querySelector('.feature-key').value.trim();
                        const v = row.querySelector('.feature-val').value.trim();
                        if (k && v) hasPair = true;
                    });
                    if (!hasPair) {
                        setFieldError('features', 'Please add at least one complete specification (both Key and Value are required).');
                        return false;
                    }
                    setFieldError('features', '');
                    return true;
                };

                // --- 4. About This Item (Bullet Points) ---
                const aboutContainer = document.querySelector('#about-container');
                const addAboutBtn = document.querySelector('#add-about-btn');

                const addAboutRow = (text = '') => {
                    const row = document.createElement('div');
                    row.className = 'about-row flex items-center gap-2';
                    row.innerHTML = `
                        <span class="text-sm font-bold text-[#3d6f6d] select-none">•</span>
                        <input type="text" placeholder="e.g. Industry-leading noise cancellation optimized with two processors" value="${escapeHtml(text)}" class="about-input flex-1 rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm outline-none transition focus:border-[#3d6f6d] focus:ring-1 focus:ring-[#3d6f6d]">
                        <button type="button" class="remove-btn rounded-xl p-2 text-gray-400 transition hover:bg-rose-50 hover:text-rose-600" title="Delete bullet point">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    `;
                    row.querySelector('.remove-btn').addEventListener('click', () => {
                        if (aboutContainer.children.length > 1) {
                            row.remove();
                        } else {
                            row.querySelector('.about-input').value = '';
                        }
                        validateAbout();
                    });
                    row.querySelector('.about-input').addEventListener('input', validateAbout);
                    aboutContainer.appendChild(row);
                };
                addAboutBtn.addEventListener('click', () => {
                    addAboutRow();
                    validateAbout();
                });

                const validateAbout = () => {
                    let hasItem = false;
                    document.querySelectorAll('.about-row .about-input').forEach((input) => {
                        if (input.value.trim()) hasItem = true;
                    });
                    if (!hasItem) {
                        setFieldError('about', 'Please provide at least one highlight bullet point for "About this item".');
                        return false;
                    }
                    setFieldError('about', '');
                    return true;
                };

                // --- 5. Description Paragraphs ---
                const paragraphsContainer = document.querySelector('#paragraphs-container');
                const addParagraphBtn = document.querySelector('#add-paragraph-btn');

                const addParagraphRow = (text = '') => {
                    const row = document.createElement('div');
                    row.className = 'desc-row flex items-start gap-2';
                    row.innerHTML = `
                        <textarea rows="3" placeholder="Write a clear paragraph describing your product, craftsmanship, or customer benefits..." class="desc-input flex-1 rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm outline-none transition focus:border-[#3d6f6d] focus:ring-1 focus:ring-[#3d6f6d]">${escapeHtml(text)}</textarea>
                        <button type="button" class="remove-btn mt-2 rounded-xl p-2 text-gray-400 transition hover:bg-rose-50 hover:text-rose-600" title="Delete paragraph">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    `;
                    row.querySelector('.remove-btn').addEventListener('click', () => {
                        if (paragraphsContainer.children.length > 1) {
                            row.remove();
                        } else {
                            row.querySelector('.desc-input').value = '';
                        }
                        validateDescription();
                    });
                    row.querySelector('.desc-input').addEventListener('input', validateDescription);
                    paragraphsContainer.appendChild(row);
                };
                addParagraphBtn.addEventListener('click', () => {
                    addParagraphRow();
                    validateDescription();
                });

                const validateDescription = () => {
                    let hasPara = false;
                    document.querySelectorAll('.desc-row .desc-input').forEach((input) => {
                        if (input.value.trim()) hasPara = true;
                    });
                    if (!hasPara) {
                        setFieldError('description', 'Please write at least one descriptive paragraph.');
                        return false;
                    }
                    setFieldError('description', '');
                    return true;
                };

                // ============================================================
                // VENDOR SERVICES & PERKS SELECTION
                // ============================================================
                const serviceIconMap = {
                    'truck': '<path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M15 18H9"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.62l-3.48-4.35A1 1 0 0 0 17.52 8H14"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/>',
                    'banknote': '<rect width="20" height="12" x="2" y="6" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/>',
                    'box': '<path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/>',
                    'shield-check': '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/>',
                    'refresh-cw': '<path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/>',
                    'headset': '<path d="M3 11h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-5Zm0 0a9 9 0 1 1 18 0m0 0v5a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3Z"/><path d="M21 16v2a4 4 0 0 1-4 4h-5"/>',
                    'award': '<circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/>',
                    'clock': '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
                    'zap': '<path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"/>',
                    'credit-card': '<rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>',
                    'gift': '<rect x="3" y="8" width="18" height="4" rx="1"/><path d="M12 8v13"/><path d="M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7"/><path d="M7.5 8a2.5 2.5 0 0 1 0-5A4.8 4.8 0 0 1 12 8a4.8 4.8 0 0 1 4.5-5 2.5 2.5 0 0 1 0 5"/>',
                    'badge-percent': '<path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/><line x1="15" x2="9" y1="9" y2="15"/>',
                };

                const getServiceIconSvg = (key, className = 'h-4 w-4') => {
                    const cleanKey = (key || 'truck').toLowerCase().trim();
                    const svgPath = serviceIconMap[cleanKey] || serviceIconMap['truck'];
                    return `<svg class="${className}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">${svgPath}</svg>`;
                };

                let availableServices = [];
                const selectedServiceNames = new Set();
                const servicesContainer = document.querySelector('#service-suggestions-container');
                const selectedServicesChips = document.querySelector('#selected-services-chips');
                const serviceSelectionCount = document.querySelector('#service-selection-count');
                const vendorServiceInput = document.querySelector('#input-vendor_service_id');
                const inlineServiceName = document.querySelector('#inline-service-name');
                const inlineServiceIcon = document.querySelector('#inline-service-icon');
                const inlineAddBtn = document.querySelector('#inline-add-service-btn');

                const syncSelectedServices = () => {
                    const arr = Array.from(selectedServiceNames);
                    vendorServiceInput.value = arr.join(', ');
                    serviceSelectionCount.textContent = `${arr.length} perk${arr.length === 1 ? '' : 's'} selected`;

                    if (!arr.length) {
                        selectedServicesChips.innerHTML = '<span class="text-xs italic text-gray-400">None selected yet. Click cards above to attach perks.</span>';
                        return;
                    }

                    selectedServicesChips.innerHTML = arr.map((name) => {
                        const matched = availableServices.find((s) => s.name.toLowerCase() === name.toLowerCase());
                        const iconKey = matched ? matched.icon : 'truck';
                        const iconSvg = getServiceIconSvg(iconKey, 'h-3.5 w-3.5 text-emerald-700');

                        return `
                            <span class="inline-flex items-center gap-1.5 rounded-lg border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-800">
                                ${iconSvg}
                                <span>${escapeHtml(name)}</span>
                                <button type="button" data-remove-service="${escapeHtml(name)}" class="remove-service-chip text-emerald-600 hover:text-emerald-900 transition">
                                    &times;
                                </button>
                            </span>
                        `;
                    }).join('');

                    selectedServicesChips.querySelectorAll('.remove-service-chip').forEach((btn) => {
                        btn.addEventListener('click', (e) => {
                            e.stopPropagation();
                            selectedServiceNames.delete(btn.dataset.removeService);
                            syncSelectedServices();
                            renderServiceSuggestions();
                        });
                    });
                };

                const renderServiceSuggestions = () => {
                    if (!availableServices.length) {
                        servicesContainer.innerHTML = '<div class="col-span-full py-4 text-center text-xs text-gray-400">No services configured. Use the form below to add one!</div>';
                        return;
                    }

                    servicesContainer.innerHTML = availableServices.map((service) => {
                        const isSelected = selectedServiceNames.has(service.name);
                        const iconSvg = getServiceIconSvg(service.icon, `h-4 w-4 ${isSelected ? 'text-[#3d6f6d]' : 'text-gray-500'}`);

                        return `
                            <button type="button" data-service-name="${escapeHtml(service.name)}"
                                class="service-select-card group flex items-start gap-2.5 rounded-xl border p-3 text-left transition ${
                                    isSelected
                                        ? 'border-[#3d6f6d] bg-[#3d6f6d]/10 text-[#3d6f6d] ring-2 ring-[#3d6f6d]/30 font-semibold'
                                        : 'border-gray-200 bg-white hover:border-[#3d6f6d] hover:bg-gray-50 text-gray-700'
                                }">
                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg ${isSelected ? 'bg-[#3d6f6d] text-white' : 'bg-gray-100 text-gray-600 group-hover:bg-[#3d6f6d]/15 group-hover:text-[#3d6f6d]'}">
                                    ${iconSvg}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <p class="text-xs font-bold truncate">${escapeHtml(service.name)}</p>
                                        ${isSelected ? '<span class="text-xs font-bold text-[#3d6f6d]">✓</span>' : ''}
                                    </div>
                                    <p class="text-2xs text-gray-500 line-clamp-1 mt-0.5">${escapeHtml(service.description || 'Service perk for products')}</p>
                                </div>
                            </button>
                        `;
                    }).join('');

                    servicesContainer.querySelectorAll('.service-select-card').forEach((card) => {
                        card.addEventListener('click', () => {
                            const name = card.dataset.serviceName;
                            if (selectedServiceNames.has(name)) {
                                selectedServiceNames.delete(name);
                            } else {
                                selectedServiceNames.add(name);
                            }
                            syncSelectedServices();
                            renderServiceSuggestions();
                        });
                    });
                };

                const loadVendorServices = async () => {
                    try {
                        const res = await fetch(@json(url('/api/vendor/services')), {
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrf
                            }
                        });
                        const json = await res.json();
                        if (json.success && Array.isArray(json.data)) {
                            availableServices = json.data;
                        }
                    } catch (e) {
                        console.warn('Could not load vendor services:', e);
                    }

                    // Fallback default services if API returned empty
                    if (!availableServices.length) {
                        availableServices = [
                            { name: 'Cash on Delivery', icon: 'banknote', description: 'Pay upon doorstep delivery' },
                            { name: 'Express Shipping', icon: 'truck', description: 'Fast 24-48h delivery' },
                            { name: 'Free Delivery', icon: 'box', description: 'No shipping fees' },
                            { name: '7-Day Replacement', icon: 'refresh-cw', description: 'Hassle-free replacement' },
                            { name: '2-Year Warranty', icon: 'shield-check', description: 'Comprehensive warranty coverage' },
                            { name: '24/7 Support', icon: 'headset', description: 'Priority customer support' },
                        ];
                    }

                    renderServiceSuggestions();
                    syncSelectedServices();
                };

                // Inline quick add button
                if (inlineAddBtn) {
                    inlineAddBtn.addEventListener('click', async () => {
                        const name = inlineServiceName.value.trim();
                        const icon = inlineServiceIcon.value;

                        if (!name) {
                            inlineServiceName.focus();
                            return;
                        }

                        // Add to local list and select
                        if (!availableServices.some((s) => s.name.toLowerCase() === name.toLowerCase())) {
                            availableServices.push({ name, icon, description: 'Custom vendor perk' });
                        }
                        selectedServiceNames.add(name);
                        inlineServiceName.value = '';

                        syncSelectedServices();
                        renderServiceSuggestions();

                        // Persist to server in background
                        try {
                            await fetch(@json(url('/api/vendor/services')), {
                                method: 'POST',
                                headers: {
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrf
                                },
                                body: JSON.stringify({ name, icon, description: 'Custom vendor perk', is_active: true })
                            });
                        } catch (err) {
                            console.warn('Background service save failed:', err);
                        }
                    });
                }

                // Initialize service loading
                loadVendorServices();

                // Real-time Field Validations
                form.elements.name.addEventListener('input', () => {
                    const val = form.elements.name.value.trim();
                    if (!val || val.length < 2) {
                        setFieldError('name', 'Product name is required (minimum 2 characters).');
                    } else {
                        setFieldError('name', '');
                    }
                });

                form.elements.category_id.addEventListener('change', () => {
                    if (!form.elements.category_id.value) {
                        setFieldError('category_id', 'Please select a product category.');
                    } else {
                        setFieldError('category_id', '');
                    }
                });

                form.elements.price.addEventListener('input', () => {
                    const val = parseFloat(form.elements.price.value);
                    if (isNaN(val) || val <= 0) {
                        setFieldError('price', 'Please enter a valid price greater than 0.');
                    } else {
                        setFieldError('price', '');
                    }
                });

                form.elements.stock.addEventListener('input', () => {
                    const val = parseInt(form.elements.stock.value, 10);
                    if (isNaN(val) || val < 0) {
                        setFieldError('stock', 'Please enter an available stock quantity (integer >= 0).');
                    } else {
                        setFieldError('stock', '');
                    }
                });

                form.elements.rating.addEventListener('input', () => {
                    const val = form.elements.rating.value;
                    if (val !== '' && (parseFloat(val) < 0 || parseFloat(val) > 5)) {
                        setFieldError('rating', 'Rating must be between 0 and 5.');
                    } else {
                        setFieldError('rating', '');
                    }
                });

                // --- 6. Load Categories & Product Data ---
                const loadCategories = async () => {
                    const response = await fetch(`${categoryUrl}?per_page=100`, { headers: { Accept: 'application/json' } });
                    const payload = await response.json();
                    if (!response.ok || !payload.success) throw new Error(payload.message || 'Unable to load categories.');
                    const categories = payload.data.data ?? [];
                    form.elements.category_id.innerHTML = '<option value="">Select a category</option>' + categories.map((c) => `<option value="${c.id}">${escapeHtml(c.name)}</option>`).join('');
                };

                const loadProduct = async () => {
                    if (!productId) {
                        addFeatureRow();
                        addAboutRow();
                        addParagraphRow();
                        return;
                    }

                    const response = await fetch(`${productUrl}/${productId}`, { headers: { Accept: 'application/json' } });
                    const payload = await response.json();
                    if (!response.ok || !payload.success) throw new Error(payload.message || 'Unable to load product.');
                    const product = payload.data;

                    const setValue = (name, val) => { if (form.elements[name]) form.elements[name].value = val ?? ''; };
                    ['name', 'price', 'discount', 'stock', 'store_link', 'status', 'visibility', 'category_id', 'offerId'].forEach((field) => setValue(field, product[field]));
                    setValue('rating', product.rating != null ? product.rating : '');
                    setValue('vendor_service_id', Array.isArray(product.vendor_service_id) ? product.vendor_service_id.join(', ') : (product.vendor_service_id || ''));
                    selectedServiceNames.clear();
                    if (Array.isArray(product.vendor_service_id)) {
                        product.vendor_service_id.forEach((s) => { if (s && String(s).trim()) selectedServiceNames.add(String(s).trim()); });
                    } else if (typeof product.vendor_service_id === 'string' && product.vendor_service_id.trim()) {
                        product.vendor_service_id.split(',').forEach((s) => { if (s.trim()) selectedServiceNames.add(s.trim()); });
                    }
                    syncSelectedServices();
                    renderServiceSuggestions();
                    ['is_featured', 'is_trending', 'is_limited'].forEach((field) => { form.elements[field].checked = Boolean(product[field]); });
                    updatePricePreview();

                    // Load Images
                    if (Array.isArray(product.image)) {
                        existingProductImages = [...product.image];
                    } else if (typeof product.image === 'string' && product.image) {
                        try { existingProductImages = JSON.parse(product.image); } catch { existingProductImages = [product.image]; }
                    }
                    renderProductImages();

                    // Load Manufacturer Images
                    if (Array.isArray(product.menifectures_images)) {
                        existingMImages = [...product.menifectures_images];
                    } else if (typeof product.menifectures_images === 'string' && product.menifectures_images) {
                        try { existingMImages = JSON.parse(product.menifectures_images); } catch { existingMImages = [product.menifectures_images]; }
                    }
                    renderMImages();

                    // Load Features (Key-Value)
                    featuresContainer.innerHTML = '';
                    let parsedFeatures = product.features;
                    if (typeof parsedFeatures === 'string') {
                        try { parsedFeatures = JSON.parse(parsedFeatures); } catch {}
                    }
                    if (parsedFeatures && typeof parsedFeatures === 'object' && !Array.isArray(parsedFeatures)) {
                        Object.entries(parsedFeatures).forEach(([k, v]) => addFeatureRow(k, v));
                    } else if (Array.isArray(parsedFeatures)) {
                        parsedFeatures.forEach((item) => {
                            if (typeof item === 'object' && item !== null) {
                                addFeatureRow(item.key || Object.keys(item)[0] || '', item.value || Object.values(item)[0] || '');
                            } else {
                                addFeatureRow('Feature', String(item));
                            }
                        });
                    } else if (typeof parsedFeatures === 'string' && parsedFeatures.trim()) {
                        addFeatureRow('Specifications', parsedFeatures);
                    }
                    if (!featuresContainer.children.length) addFeatureRow();

                    // Load About Items
                    aboutContainer.innerHTML = '';
                    const aboutItems = Array.isArray(product.about) ? product.about : (product.about ? [product.about] : []);
                    aboutItems.forEach((text) => addAboutRow(text));
                    if (!aboutContainer.children.length) addAboutRow();

                    // Load Description Paragraphs
                    paragraphsContainer.innerHTML = '';
                    const descText = String(product.description || '');
                    const paragraphs = descText.split(/\n\s*\n/).map((p) => p.trim()).filter(Boolean);
                    if (paragraphs.length) {
                        paragraphs.forEach((p) => addParagraphRow(p));
                    } else if (descText.trim()) {
                        addParagraphRow(descText.trim());
                    } else {
                        addParagraphRow();
                    }
                };

                // --- 7. Full Form Validation & Submission ---
                form.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    clearAllErrors();
                    statusAlert.classList.add('hidden');

                    let firstInvalidEl = null;
                    const markInvalid = (el, id, msg) => {
                        setFieldError(id, msg);
                        if (!firstInvalidEl && el) firstInvalidEl = el;
                    };

                    // 1. Name
                    const nameInput = form.elements.name;
                    const nameVal = nameInput.value.trim();
                    if (!nameVal || nameVal.length < 2) {
                        markInvalid(nameInput, 'name', 'Product name is required (minimum 2 characters).');
                    }

                    // 2. Category
                    const categoryInput = form.elements.category_id;
                    if (!categoryInput.value) {
                        markInvalid(categoryInput, 'category_id', 'Please select a product category.');
                    }

                    // 3. Price
                    const priceInput = form.elements.price;
                    const priceNum = parseFloat(priceInput.value);
                    if (isNaN(priceNum) || priceNum <= 0) {
                        markInvalid(priceInput, 'price', 'Please enter a valid price greater than 0.');
                    }

                    // 4. Stock
                    const stockInput = form.elements.stock;
                    const stockNum = parseInt(stockInput.value, 10);
                    if (isNaN(stockNum) || stockNum < 0) {
                        markInvalid(stockInput, 'stock', 'Please enter an available stock quantity (integer >= 0).');
                    }

                    // 5. Rating
                    const ratingInput = form.elements.rating;
                    if (ratingInput.value !== '' && (parseFloat(ratingInput.value) < 0 || parseFloat(ratingInput.value) > 5)) {
                        markInvalid(ratingInput, 'rating', 'Rating must be between 0 and 5.');
                    }

                    // 6. Product Images (at least 1 image)
                    if (!validateImages()) {
                        if (!firstInvalidEl) firstInvalidEl = document.querySelector('#product-images-input');
                    }

                    // 7. Features (key-value)
                    const featuresObj = {};
                    let featureCount = 0;
                    document.querySelectorAll('.feature-row').forEach((row) => {
                        const k = row.querySelector('.feature-key').value.trim();
                        const v = row.querySelector('.feature-val').value.trim();
                        if (k && v) {
                            featuresObj[k] = v;
                            featureCount += 1;
                        }
                    });
                    if (featureCount === 0) {
                        setFieldError('features', 'Please add at least one complete specification (both Key and Value are required).');
                        if (!firstInvalidEl) firstInvalidEl = document.querySelector('#features-container');
                    }

                    // 8. About Bullet Points
                    const aboutList = [];
                    document.querySelectorAll('.about-row .about-input').forEach((input) => {
                        const val = input.value.trim();
                        if (val) aboutList.push(val);
                    });
                    if (aboutList.length === 0) {
                        setFieldError('about', 'Please provide at least one bullet point for "About this item".');
                        if (!firstInvalidEl) firstInvalidEl = document.querySelector('#about-container');
                    }

                    // 9. Description Paragraphs
                    const descList = [];
                    document.querySelectorAll('.desc-row .desc-input').forEach((input) => {
                        const val = input.value.trim();
                        if (val) descList.push(val);
                    });
                    if (descList.length === 0) {
                        setFieldError('description', 'Please write at least one detailed description paragraph.');
                        if (!firstInvalidEl) firstInvalidEl = document.querySelector('#paragraphs-container');
                    }

                    // If any error exists, scroll to first error and stop
                    if (firstInvalidEl) {
                        statusAlert.innerHTML = `
                            <div class="flex items-start gap-3">
                                <svg class="h-5 w-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="font-bold text-rose-900">Required information is missing or invalid</p>
                                    <p class="mt-0.5 text-xs text-rose-700">Please review the highlighted sections above and correct the indicated fields.</p>
                                </div>
                            </div>
                        `;
                        statusAlert.className = 'rounded-xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-medium shadow-sm';
                        statusAlert.classList.remove('hidden');

                        firstInvalidEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        if (typeof firstInvalidEl.focus === 'function') {
                            firstInvalidEl.focus();
                        }
                        return;
                    }

                    // Build Payload with FormData
                    submitBtn.disabled = true;
                    submitSpinner.classList.remove('hidden');
                    submitLabel.textContent = productId ? 'Saving Changes...' : 'Creating Product...';

                    const formData = new FormData();
                    formData.append('name', nameVal);
                    formData.append('category_id', categoryInput.value);
                    formData.append('price', priceNum);
                    formData.append('discount', parseFloat(form.elements.discount.value || 0));
                    formData.append('stock', stockNum);

                    if (form.elements.store_link.value.trim()) {
                        formData.append('store_link', form.elements.store_link.value.trim());
                    }
                    if (ratingInput.value !== '') {
                        formData.append('rating', parseFloat(ratingInput.value));
                    }
                    if (form.elements.offerId.value.trim()) {
                        formData.append('offerId', form.elements.offerId.value.trim());
                    }

                    formData.append('status', form.elements.status.value);
                    formData.append('visibility', form.elements.visibility.value);
                    formData.append('is_featured', form.elements.is_featured.checked ? '1' : '0');
                    formData.append('is_trending', form.elements.is_trending.checked ? '1' : '0');
                    formData.append('is_limited', form.elements.is_limited.checked ? '1' : '0');

                    // Description (joined paragraphs)
                    formData.append('description', descList.join('\n\n'));

                    // Vendor Services
                    const vendorServicesRaw = form.elements.vendor_service_id.value.trim();
                    if (vendorServicesRaw) {
                        const servicesArr = vendorServicesRaw.split(',').map((s) => s.trim()).filter(Boolean);
                        servicesArr.forEach((service, idx) => {
                            formData.append(`vendor_service_id[${idx}]`, service);
                        });
                    }

                    // About Bullet Points
                    aboutList.forEach((point, idx) => {
                        formData.append(`about[${idx}]`, point);
                    });

                    // Features Key-Value Object
                    Object.entries(featuresObj).forEach(([k, v]) => {
                        formData.append(`features[${k}]`, v);
                    });

                    // Existing image keys to retain
                    existingProductImages.forEach((imgKey, idx) => {
                        formData.append(`image[${idx}]`, imgKey);
                    });

                    // New image files
                    selectedProductFiles.forEach((file) => {
                        formData.append('image[]', file);
                    });

                    // Existing manufacturer image keys
                    existingMImages.forEach((mImgKey, idx) => {
                        formData.append(`menifectures_images[${idx}]`, mImgKey);
                    });

                    // New manufacturer image files
                    selectedMFiles.forEach((file) => {
                        formData.append('menifectures_images[]', file);
                    });

                    if (productId) {
                        formData.append('_method', 'PATCH');
                    }

                    try {
                        const response = await fetch(productId ? `${productUrl}/${productId}` : productUrl, {
                            method: 'POST',
                            headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrf },
                            body: formData,
                        });
                        const payload = await response.json();
                        if (!response.ok || !payload.success) {
                            if (payload.errors) {
                                Object.entries(payload.errors).forEach(([field, msgs]) => {
                                    setFieldError(field, msgs.join(' '));
                                });
                            }
                            throw new Error(payload.message || (payload.errors ? Object.values(payload.errors).flat().join(' ') : 'Unable to save product.'));
                        }
                        window.location.href = @json(route('vendor.product'));
                    } catch (error) {
                        statusAlert.innerHTML = `<p class="font-bold text-rose-900">Submission Error</p><p class="mt-0.5 text-xs text-rose-700">${escapeHtml(error.message)}</p>`;
                        statusAlert.className = 'rounded-xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-medium shadow-sm';
                        statusAlert.classList.remove('hidden');
                        statusAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });

                        submitBtn.disabled = false;
                        submitSpinner.classList.add('hidden');
                        submitLabel.textContent = productId ? 'Save Changes' : 'Create Product';
                    }
                });

                Promise.all([loadCategories(), loadProduct()]).catch((error) => {
                    statusAlert.innerHTML = `<p class="text-xs text-rose-700">${escapeHtml(error.message)}</p>`;
                    statusAlert.className = 'rounded-xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-medium shadow-sm';
                    statusAlert.classList.remove('hidden');
                });
            })();
        </script>
    @endpush
@endsection
