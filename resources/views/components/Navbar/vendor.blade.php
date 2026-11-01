@php
    /** @var \App\Models\User $vendorUser */
    $vendorUser = auth()->user();
    $vendorInitials = collect(explode(' ', $vendorUser->name ?? 'Vendor'))
        ->filter()
        ->take(2)
        ->map(fn($p) => mb_substr($p, 0, 1))
        ->join('');
    $vendorInitials = strtoupper($vendorInitials ?: 'VD');
@endphp

<nav class="fixed top-0 z-50 w-full bg-[#224e51] text-[#64883e] shadow-lg">
    <div class="px-4 py-3 sm:px-8">
        <div class="flex items-center justify-between">

            <a href="/" class="text-2xl font-semibold tracking-tight text-[#FFFFFF] transition-colors duration-300 hover:text-[#F5F7F4]">
                SwiftCart
            </a>

            <div class="flex items-center gap-5">

                <p class="vendor-nav-name hidden text-sm text-[#E1EBE8] sm:block">Welcome, {{ $vendorUser->name }}</p>

                <a href="{{ route('vendor.profile') }}" title="My Profile" class="block shrink-0">
                    <div id="vendor-nav-avatar" class="flex h-10 w-10 items-center justify-center overflow-hidden rounded-full border-2 border-[#FFFFFF] bg-[#F4C95D] text-sm font-bold text-[#224e51] transition duration-300 hover:scale-105 cursor-pointer">
                        @if (!empty($vendorUser->image))
                            <img id="vendor-nav-avatar-img"
                                 src=""
                                 data-s3-key="{{ $vendorUser->image }}"
                                 data-s3-auto="true"
                                 alt="{{ $vendorUser->name }}"
                                 onerror="this.classList.add('hidden'); document.getElementById('vendor-nav-avatar-initials').classList.remove('hidden');"
                                 class="h-full w-full object-cover">
                            <span id="vendor-nav-avatar-initials" class="hidden">{{ $vendorInitials }}</span>
                        @else
                            <span id="vendor-nav-avatar-initials">{{ $vendorInitials }}</span>
                        @endif
                    </div>
                </a>

            </div>

        </div>
    </div>
</nav>
