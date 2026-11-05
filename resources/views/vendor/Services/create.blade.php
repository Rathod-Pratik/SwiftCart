@extends('layouts.Vendor')

@section('content')
    <x-slot name="title">Add Service</x-slot>

    <div class="p-4 sm:p-6 lg:p-8">
        {{-- Breadcrumb & Navigation --}}
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <nav class="flex items-center gap-2 text-xs font-semibold text-gray-400">
                    <a href="{{ route('vendor.dashboard') }}" class="hover:text-gray-600 transition">Dashboard</a>
                    <span>/</span>
                    <a href="{{ route('vendor.service') }}" class="hover:text-[#3d6f6d] transition">Services</a>
                    <span>/</span>
                    <span id="breadcrumb-current" class="text-gray-800">New Service</span>
                </nav>
                <h1 id="page-title" class="mt-1 text-2xl font-bold text-gray-900 sm:text-3xl">Add Service & Perk</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Create a delivery perk, warranty, or customer assurance badge to offer across your products.
                </p>
            </div>
            <a href="{{ route('vendor.service') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
                <x-lucide-arrow-left class="h-4 w-4 text-gray-500" />
                <span>Back to Services</span>
            </a>
        </div>

        {{-- Status Alert Box --}}
        <div id="form-status" class="mb-5 hidden rounded-xl px-4 py-3 text-sm font-medium" role="alert"></div>

        <form id="service-form" class="space-y-6">
            <input type="hidden" id="service-id" value="">

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                {{-- Left 2 Columns: Service Details & Icon Library --}}
                <div class="space-y-6 lg:col-span-2">
                    {{-- Card 1: Service Information --}}
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-7">
                        <div class="mb-5 flex items-center gap-3 border-b border-gray-100 pb-4">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#3d6f6d]/10 text-sm font-bold text-[#3d6f6d]">
                                1
                            </span>
                            <div>
                                <h2 class="text-lg font-bold text-gray-900">Service Information</h2>
                                <p class="text-xs text-gray-500">Provide the title, quick suggestions, and description for this service.</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            {{-- Service Name --}}
                            <div>
                                <label for="service-name" class="block text-sm font-semibold text-gray-900">
                                    Service Name <span class="text-rose-500 font-bold">*</span>
                                </label>
                                <input type="text" id="service-name" placeholder="e.g. Cash on Delivery, Express Shipping..."
                                    class="mt-1.5 w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                                <p id="error-name" class="mt-1.5 text-xs font-medium text-rose-600 hidden"></p>

                                {{-- Quick Suggestion Pills --}}
                                <div class="mt-2.5 flex flex-wrap items-center gap-1.5 text-xs text-gray-500">
                                    <span class="font-medium text-gray-600">Popular presets:</span>
                                    @php
                                        $presets = [
                                            ['name' => 'Cash on Delivery', 'icon' => 'banknote'],
                                            ['name' => 'Express Shipping', 'icon' => 'truck'],
                                            ['name' => 'Free Delivery', 'icon' => 'box'],
                                            ['name' => 'Same-Day Dispatch', 'icon' => 'zap'],
                                            ['name' => '7-Day Replacement', 'icon' => 'refresh-cw'],
                                            ['name' => '2-Year Warranty', 'icon' => 'shield-check'],
                                            ['name' => '24/7 Support', 'icon' => 'headset'],
                                            ['name' => 'Verified Authenticity', 'icon' => 'award'],
                                        ];
                                    @endphp
                                    @foreach ($presets as $p)
                                        <button type="button" data-preset-name="{{ $p['name'] }}" data-preset-icon="{{ $p['icon'] }}"
                                            class="preset-chip rounded-lg bg-gray-100 px-2.5 py-1 text-gray-700 hover:bg-[#3d6f6d] hover:text-white transition">
                                            + {{ $p['name'] }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Description --}}
                            <div>
                                <label for="service-description" class="block text-sm font-semibold text-gray-900">
                                    Description <span class="text-xs font-normal text-gray-400">(Optional explanation for customers)</span>
                                </label>
                                <textarea id="service-description" rows="3" placeholder="Explain what customers receive with this service perk (e.g. Pay in cash at doorstep when order arrives)..."
                                    class="mt-1.5 w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20"></textarea>
                            </div>

                            {{-- Active Status Toggle --}}
                            <div class="border-t border-gray-100 pt-4">
                                <label class="inline-flex cursor-pointer items-center gap-3">
                                    <input type="checkbox" id="service-status-toggle" checked class="h-5 w-5 rounded border-gray-300 text-[#3d6f6d] focus:ring-[#3d6f6d]">
                                    <div>
                                        <span class="text-sm font-semibold text-gray-900">Active & Available for Products</span>
                                        <p class="text-xs text-gray-500">When enabled, this service can be attached to products across your store.</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Card 2: Icon Library Selector --}}
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-7">
                        <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between border-b border-gray-100 pb-4">
                            <div class="flex items-center gap-3">
                                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#3d6f6d]/10 text-sm font-bold text-[#3d6f6d]">
                                    2
                                </span>
                                <div>
                                    <h2 class="text-lg font-bold text-gray-900">Choose Icon from Library <span class="text-rose-500 font-bold">*</span></h2>
                                    <p class="text-xs text-gray-500">Select a vector badge icon to visually represent this service perk.</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="search" id="icon-search" placeholder="Search icon (e.g. truck, cash)..."
                                    class="w-full sm:w-48 rounded-xl border border-gray-300 px-3 py-1.5 text-xs outline-none focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                            </div>
                        </div>

                        {{-- Hidden input for selected icon --}}
                        <input type="hidden" id="service-icon" value="truck">
                        <p id="error-icon" class="mb-3 text-xs font-medium text-rose-600 hidden"></p>

                        {{-- Category filter chips --}}
                        <div class="mb-4 flex flex-wrap gap-1.5 text-xs">
                            <button type="button" data-cat="all" class="icon-cat-btn rounded-lg bg-[#3d6f6d] px-3 py-1 font-semibold text-white transition">All Icons</button>
                            <button type="button" data-cat="shipping" class="icon-cat-btn rounded-lg bg-gray-100 px-3 py-1 font-medium text-gray-700 hover:bg-gray-200 transition">Shipping</button>
                            <button type="button" data-cat="payment" class="icon-cat-btn rounded-lg bg-gray-100 px-3 py-1 font-medium text-gray-700 hover:bg-gray-200 transition">Payment & COD</button>
                            <button type="button" data-cat="warranty" class="icon-cat-btn rounded-lg bg-gray-100 px-3 py-1 font-medium text-gray-700 hover:bg-gray-200 transition">Warranty & Returns</button>
                            <button type="button" data-cat="trust" class="icon-cat-btn rounded-lg bg-gray-100 px-3 py-1 font-medium text-gray-700 hover:bg-gray-200 transition">Trust & Quality</button>
                        </div>

                        {{-- Interactive Icon Grid --}}
                        <div id="icon-grid" class="grid grid-cols-2 gap-3 sm:grid-cols-4 md:grid-cols-5"></div>

                        {{-- Custom icon manual override --}}
                        <div class="mt-4 border-t border-gray-100 pt-3 flex items-center justify-between">
                            <span class="text-xs text-gray-500">Selected Icon Key: <strong id="selected-icon-label" class="text-[#3d6f6d] font-bold">truck</strong></span>
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-gray-400">Custom key:</span>
                                <input type="text" id="custom-icon-key" placeholder="other-lucide-name"
                                    class="w-36 rounded-lg border border-gray-300 px-2.5 py-1 text-xs outline-none focus:border-[#3d6f6d]">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Live Simulator Preview & Action Card --}}
                <div class="space-y-6">
                    {{-- Live Simulator Preview --}}
                    <div class="sticky top-24 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">
                        <div class="mb-4 flex items-center justify-between border-b border-gray-100 pb-3">
                            <div class="flex items-center gap-2">
                                <span class="flex h-2.5 w-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                <h3 class="text-sm font-bold text-gray-900">Live Customer Preview</h3>
                            </div>
                            <span class="rounded bg-teal-50 px-2 py-0.5 text-2xs font-semibold text-[#3d6f6d]">Simulator</span>
                        </div>

                        <p class="text-xs text-gray-500 mb-4">
                            Here is how this service perk will appear on your product details card for shoppers:
                        </p>

                        {{-- Simulator Card Box --}}
                        <div class="rounded-2xl border-2 border-[#3d6f6d]/20 bg-[#F5F7F4]/60 p-4 transition-all">
                            <div class="flex items-start gap-3.5">
                                <div id="preview-icon-wrapper" class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#3d6f6d] text-[#F4C95D] shadow-md transition-transform">
                                    <div id="preview-icon"></div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <h4 id="preview-name" class="text-sm font-bold text-gray-900">Cash on Delivery</h4>
                                        <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-2xs font-bold text-emerald-800">Verified</span>
                                    </div>
                                    <p id="preview-desc" class="mt-1 text-xs text-gray-600 line-clamp-3">
                                        Pay securely with cash upon delivery at your doorstep.
                                    </p>
                                    <div class="mt-2.5 flex items-center gap-2 text-2xs font-semibold text-[#3d6f6d]">
                                        <svg class="h-3.5 w-3.5 text-[#3d6f6d]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                        <span>Guaranteed Seller Perk</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Selected Badge Mini-Preview --}}
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <span class="text-xs font-semibold text-gray-500 block mb-2">Chip Preview (on listing):</span>
                            <div class="flex flex-wrap gap-2">
                                <span class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 border border-emerald-200">
                                    <span id="preview-mini-icon"></span>
                                    <span id="preview-mini-name">Cash on Delivery</span>
                                </span>
                            </div>
                        </div>

                        {{-- Submit Buttons --}}
                        <div class="mt-6 flex flex-col gap-2.5">
                            <button type="submit" id="save-service-btn"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#3d6f6d] py-3 text-sm font-bold text-white shadow-md transition hover:bg-[#315b59]">
                                <span id="save-spinner" class="hidden h-4 w-4 animate-spin rounded-full border-2 border-white border-t-transparent"></span>
                                <span id="save-label">Create Service</span>
                            </button>
                            <a href="{{ route('vendor.service') }}" class="inline-flex w-full items-center justify-center rounded-xl border border-gray-300 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            (() => {
                const form = document.querySelector('#service-form');
                const serviceIdInput = document.querySelector('#service-id');
                const nameInput = document.querySelector('#service-name');
                const descInput = document.querySelector('#service-description');
                const statusToggle = document.querySelector('#service-status-toggle');
                const iconInput = document.querySelector('#service-icon');
                const customIconInput = document.querySelector('#custom-icon-key');
                const iconSearch = document.querySelector('#icon-search');
                const iconGrid = document.querySelector('#icon-grid');
                const selectedIconLabel = document.querySelector('#selected-icon-label');
                const formStatus = document.querySelector('#form-status');
                const saveBtn = document.querySelector('#save-service-btn');
                const saveLabel = document.querySelector('#save-label');
                const saveSpinner = document.querySelector('#save-spinner');
                const pageTitle = document.querySelector('#page-title');
                const breadcrumbCurrent = document.querySelector('#breadcrumb-current');

                // Preview elements
                const previewName = document.querySelector('#preview-name');
                const previewDesc = document.querySelector('#preview-desc');
                const previewIcon = document.querySelector('#preview-icon');
                const previewMiniIcon = document.querySelector('#preview-mini-icon');
                const previewMiniName = document.querySelector('#preview-mini-name');

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
                const apiUrl = @json(url('/api/vendor/services'));
                const indexUrl = @json(route('vendor.service'));

                // Determine Edit Mode from URL query parameter ?id=... or ?_id=...
                const urlParams = new URLSearchParams(window.location.search);
                const editId = urlParams.get('id') || urlParams.get('_id');
                const paramName = urlParams.get('name');
                const paramIcon = urlParams.get('icon');
                const paramDesc = urlParams.get('desc') || urlParams.get('description');
                const paramActive = urlParams.get('active') || urlParams.get('is_active');

                if (editId) {
                    pageTitle.textContent = 'Edit Service';
                    breadcrumbCurrent.textContent = 'Edit Service';
                    saveLabel.textContent = 'Update Service';
                    serviceIdInput.value = editId;

                    if (paramName) nameInput.value = paramName;
                    if (paramDesc) descInput.value = paramDesc;
                    if (paramIcon) iconInput.value = paramIcon;
                    if (paramActive !== null) statusToggle.checked = paramActive === '1' || paramActive === 'true';
                }

                // Comprehensive Icon Library with metadata & SVG paths
                const iconLibrary = [
                    { key: 'truck', label: 'Express Shipping', cat: 'shipping', svg: '<path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M15 18H9"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.62l-3.48-4.35A1 1 0 0 0 17.52 8H14"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/>' },
                    { key: 'banknote', label: 'Cash on Delivery', cat: 'payment', svg: '<rect width="20" height="12" x="2" y="6" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/>' },
                    { key: 'box', label: 'Free Delivery / Package', cat: 'shipping', svg: '<path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/>' },
                    { key: 'shield-check', label: 'Warranty & Security', cat: 'warranty', svg: '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/>' },
                    { key: 'refresh-cw', label: '7-Day Replacement', cat: 'warranty', svg: '<path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/>' },
                    { key: 'headset', label: '24/7 Support', cat: 'trust', svg: '<path d="M3 11h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-5Zm0 0a9 9 0 1 1 18 0m0 0v5a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3Z"/><path d="M21 16v2a4 4 0 0 1-4 4h-5"/>' },
                    { key: 'award', label: 'Verified Authenticity', cat: 'trust', svg: '<circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/>' },
                    { key: 'zap', label: 'Lightning Dispatch', cat: 'shipping', svg: '<path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"/>' },
                    { key: 'credit-card', label: 'Online / Card Payment', cat: 'payment', svg: '<rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>' },
                    { key: 'clock', label: '24h Fast Delivery', cat: 'shipping', svg: '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>' },
                    { key: 'gift', label: 'Free Surprise Gift', cat: 'trust', svg: '<rect x="3" y="8" width="18" height="4" rx="1"/><path d="M12 8v13"/><path d="M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7"/><path d="M7.5 8a2.5 2.5 0 0 1 0-5A4.8 4.8 0 0 1 12 8a4.8 4.8 0 0 1 4.5-5 2.5 2.5 0 0 1 0 5"/>' },
                    { key: 'badge-percent', label: 'Best Price Deal', cat: 'payment', svg: '<path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/><line x1="15" x2="9" y1="9" y2="15"/>' },
                    { key: 'lock', label: '100% Secure Checkout', cat: 'trust', svg: '<rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>' },
                    { key: 'map-pin', label: 'Pan-India Delivery', cat: 'shipping', svg: '<path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/>' },
                    { key: 'check-circle', label: 'Quality Inspected', cat: 'warranty', svg: '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/>' },
                    { key: 'star', label: 'Top Rated Vendor', cat: 'trust', svg: '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>' },
                    { key: 'thumbs-up', label: 'Satisfaction Guarantee', cat: 'trust', svg: '<path d="M7 10v12"/><path d="M15 5.88 14 10h5.83a2 2 0 0 1 1.92 2.56l-2.33 8A2 2 0 0 1 17.5 22H4a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h2.76a2 2 0 0 0 1.79-1.11L12 2h0a3.13 3.13 0 0 1 3 3.88Z"/>' },
                    { key: 'heart-handshake', label: 'Hassle-Free Care', cat: 'trust', svg: '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>' },
                    { key: 'sparkles', label: 'Premium Store Perk', cat: 'trust', svg: '<path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/>' }
                ];

                let currentCategory = 'all';

                const renderSvg = (svgContent, className = 'h-5 w-5') => {
                    return `<svg class="${className}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">${svgContent}</svg>`;
                };

                const updatePreview = () => {
                    const name = nameInput.value.trim() || 'Service Perk Title';
                    const desc = descInput.value.trim() || 'Service description will appear here as you type.';
                    const selectedKey = iconInput.value.trim().toLowerCase();

                    const matchedIcon = iconLibrary.find((i) => i.key === selectedKey) || iconLibrary[0];

                    previewName.textContent = name;
                    previewDesc.textContent = desc;
                    previewMiniName.textContent = name;

                    previewIcon.innerHTML = renderSvg(matchedIcon.svg, 'h-6 w-6');
                    previewMiniIcon.innerHTML = renderSvg(matchedIcon.svg, 'h-4 w-4 text-emerald-600');
                    selectedIconLabel.textContent = selectedKey;
                };

                const renderIconGrid = () => {
                    const searchVal = (iconSearch.value || '').toLowerCase().trim();
                    const selectedKey = iconInput.value.trim().toLowerCase();

                    const filtered = iconLibrary.filter((icon) => {
                        const matchesCategory = currentCategory === 'all' || icon.cat === currentCategory;
                        const matchesSearch = !searchVal || icon.key.includes(searchVal) || icon.label.toLowerCase().includes(searchVal);
                        return matchesCategory && matchesSearch;
                    });

                    iconGrid.innerHTML = filtered.map((icon) => {
                        const isSelected = icon.key === selectedKey;
                        return `
                            <button type="button" data-icon-key="${icon.key}"
                                class="icon-card flex flex-col items-center justify-center gap-1.5 rounded-xl border p-3 text-center transition ${isSelected ? 'border-[#3d6f6d] bg-[#3d6f6d]/10 ring-2 ring-[#3d6f6d]/30 text-[#3d6f6d]' : 'border-gray-200 bg-white hover:border-[#3d6f6d] hover:bg-gray-50 text-gray-700'}">
                                <div class="flex h-9 w-9 items-center justify-center rounded-lg ${isSelected ? 'bg-[#3d6f6d] text-white' : 'bg-gray-100 text-gray-600'}">
                                    ${renderSvg(icon.svg, 'h-5 w-5')}
                                </div>
                                <span class="text-xs font-semibold truncate w-full">${icon.label}</span>
                                <span class="text-2xs text-gray-400 font-mono">${icon.key}</span>
                            </button>
                        `;
                    }).join('');

                    // Attach click handlers
                    document.querySelectorAll('.icon-card').forEach((card) => {
                        card.addEventListener('click', () => {
                            const key = card.dataset.iconKey;
                            iconInput.value = key;
                            customIconInput.value = '';
                            renderIconGrid();
                            updatePreview();
                        });
                    });
                };

                // Category filter chips
                document.querySelectorAll('.icon-cat-btn').forEach((btn) => {
                    btn.addEventListener('click', () => {
                        document.querySelectorAll('.icon-cat-btn').forEach((b) => {
                            b.className = 'icon-cat-btn rounded-lg bg-gray-100 px-3 py-1 font-medium text-gray-700 hover:bg-gray-200 transition';
                        });
                        btn.className = 'icon-cat-btn rounded-lg bg-[#3d6f6d] px-3 py-1 font-semibold text-white transition';
                        currentCategory = btn.dataset.cat;
                        renderIconGrid();
                    });
                });

                // Icon Search
                iconSearch.addEventListener('input', renderIconGrid);

                // Custom icon key manual override
                customIconInput.addEventListener('input', () => {
                    if (customIconInput.value.trim()) {
                        iconInput.value = customIconInput.value.trim().toLowerCase();
                        renderIconGrid();
                        updatePreview();
                    }
                });

                // Popular preset chips click
                document.querySelectorAll('.preset-chip').forEach((chip) => {
                    chip.addEventListener('click', () => {
                        nameInput.value = chip.dataset.presetName;
                        iconInput.value = chip.dataset.presetIcon;
                        customIconInput.value = '';
                        if (!descInput.value.trim()) {
                            if (chip.dataset.presetName === 'Cash on Delivery') {
                                descInput.value = 'Pay securely with cash upon delivery at your doorstep.';
                            } else if (chip.dataset.presetName === 'Express Shipping') {
                                descInput.value = 'Fast delivery within 24 to 48 hours directly to your address.';
                            } else if (chip.dataset.presetName === 'Free Delivery') {
                                descInput.value = 'Standard free shipping included on this product.';
                            } else if (chip.dataset.presetName === '7-Day Replacement') {
                                descInput.value = 'Hassle-free replacement policy if damaged or defective.';
                            } else if (chip.dataset.presetName === '2-Year Warranty') {
                                descInput.value = 'Official manufacturer and seller warranty coverage.';
                            } else if (chip.dataset.presetName === '24/7 Support') {
                                descInput.value = 'Dedicated priority support anytime you need help.';
                            }
                        }
                        renderIconGrid();
                        updatePreview();
                        nameInput.focus();
                    });
                });

                // Input listeners for live preview
                nameInput.addEventListener('input', updatePreview);
                descInput.addEventListener('input', updatePreview);

                // Load existing service if edit mode
                const loadServiceForEdit = async (id) => {
                    pageTitle.textContent = 'Edit Service';
                    breadcrumbCurrent.textContent = 'Edit Service';
                    saveLabel.textContent = 'Update Service';
                    serviceIdInput.value = id;

                    try {
                        const response = await fetch(`${apiUrl}/${id}`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            }
                        });
                        const result = await response.json();
                        if (result.success && result.data) {
                            const svc = result.data;
                            nameInput.value = svc.name || '';
                            descInput.value = svc.description || '';
                            statusToggle.checked = Boolean(svc.is_active);
                            iconInput.value = svc.icon || 'truck';
                            if (!iconLibrary.some((i) => i.key === svc.icon)) {
                                customIconInput.value = svc.icon || '';
                            }
                            renderIconGrid();
                            updatePreview();
                        } else if (!nameInput.value) {
                            window.showToast?.(result.message || 'Service not found', 'error');
                        }
                    } catch (err) {
                        console.error('Error fetching service:', err);
                    }
                };

                // Form submit handler
                form.addEventListener('submit', async (e) => {
                    e.preventDefault();

                    // Validation
                    let hasError = false;
                    const errName = document.querySelector('#error-name');
                    const errIcon = document.querySelector('#error-icon');

                    errName.classList.add('hidden');
                    errIcon.classList.add('hidden');

                    if (!nameInput.value.trim()) {
                        errName.textContent = 'Please enter a service name.';
                        errName.classList.remove('hidden');
                        nameInput.classList.add('border-rose-500');
                        nameInput.focus();
                        hasError = true;
                    } else {
                        nameInput.classList.remove('border-rose-500');
                    }

                    if (!iconInput.value.trim()) {
                        errIcon.textContent = 'Please select an icon from the library.';
                        errIcon.classList.remove('hidden');
                        hasError = true;
                    }

                    if (hasError) return;

                    saveSpinner.classList.remove('hidden');
                    saveBtn.disabled = true;

                    const payload = {
                        name: nameInput.value.trim(),
                        icon: iconInput.value.trim(),
                        description: descInput.value.trim() || null,
                        is_active: statusToggle.checked
                    };

                    const isEdit = Boolean(serviceIdInput.value);
                    const targetUrl = isEdit ? `${apiUrl}/${serviceIdInput.value}` : apiUrl;
                    const targetMethod = isEdit ? 'PUT' : 'POST';

                    try {
                        const response = await fetch(targetUrl, {
                            method: targetMethod,
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify(payload)
                        });

                        const result = await response.json();

                        if (result.success) {
                            window.showToast?.(isEdit ? 'Service updated successfully!' : 'Service created successfully!', 'success');

                            setTimeout(() => {
                                window.location.href = indexUrl;
                            }, 1000);
                        } else {
                            const errMessage = result.message || (result.errors ? Object.values(result.errors).flat().join(', ') : 'Failed to save service');
                            window.showToast?.(errMessage, 'error');
                        }
                    } catch (err) {
                        window.showToast?.('A network error occurred. Please try again.', 'error');
                    } finally {
                        saveSpinner.classList.add('hidden');
                        saveBtn.disabled = false;
                    }
                });

                // Init
                renderIconGrid();
                updatePreview();

                if (editId) {
                    loadServiceForEdit(editId);
                }
            })();
        </script>
    @endpush
@endsection
