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

            <div class="flex items-center gap-3 sm:gap-5">

                <p class="vendor-nav-name hidden text-sm text-[#E1EBE8] sm:block">Welcome, {{ $vendorUser->name }}</p>

                {{-- Vendor Notification Bell (Direct Link to Notification Page) --}}
                <a href="{{ route('vendor.notification') }}"
                   id="vendor-notification-btn"
                   class="relative cursor-pointer rounded-xl p-2 text-white transition duration-200 hover:bg-white/15 hover:text-[#F4C95D] focus:outline-none"
                   aria-label="Vendor Notifications"
                   title="Notifications">
                    <x-lucide-bell class="h-6 w-6 text-[#FFFFFF]" />
                    <span id="vendor-nav-unread-badge"
                          class="hidden absolute -top-1 -right-1 items-center justify-center min-w-5 h-5 px-1.5 text-[10px] font-bold text-white bg-red-500 rounded-full ring-2 ring-[#224e51]">
                        0
                    </span>
                </a>

                {{-- Profile Avatar --}}
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

{{-- Navbar Notification Badge Sync Script --}}
<script>
    (() => {
        const STORAGE_KEY = 'swiftcart_vendor_notifications_v1';
        const unreadBadge = document.getElementById('vendor-nav-unread-badge');
        if (!unreadBadge) return;

        const setBadgeCount = (count) => {
            const num = parseInt(count, 10) || 0;
            if (num > 0) {
                unreadBadge.textContent = num > 9 ? '9+' : num;
                unreadBadge.classList.remove('hidden');
                unreadBadge.classList.add('flex');
            } else {
                unreadBadge.textContent = '0';
                unreadBadge.classList.add('hidden');
                unreadBadge.classList.remove('flex');
            }
        };

        const loadBadgeFromStorage = () => {
            try {
                const stored = localStorage.getItem(STORAGE_KEY);
                if (stored) {
                    const items = JSON.parse(stored);
                    if (Array.isArray(items)) {
                        const unread = items.filter(n => !n.read).length;
                        setBadgeCount(unread);
                        return true;
                    }
                }
            } catch (e) {}
            return false;
        };

        const fetchBadgeCount = async () => {
            loadBadgeFromStorage();

            try {
                const res = await fetch('/api/notifications/unread-count', {
                    headers: { 'Accept': 'application/json' }
                });
                if (res.ok) {
                    const payload = await res.json();
                    if (typeof payload.unread_count === 'number') {
                        if (payload.unread_count > 0 || !loadBadgeFromStorage()) {
                            setBadgeCount(payload.unread_count);
                        }
                    }
                }
            } catch (e) {
                loadBadgeFromStorage();
            }
        };

        // Listen for updates from Notification Page
        window.addEventListener('vendor-notifications-updated', (e) => {
            if (e.detail && Array.isArray(e.detail)) {
                const unread = e.detail.filter(n => !n.read).length;
                setBadgeCount(unread);
            } else {
                fetchBadgeCount();
            }
        });

        window.addEventListener('storage', (e) => {
            if (e.key === STORAGE_KEY) {
                loadBadgeFromStorage();
            }
        });

        window.refreshVendorNotificationBadge = fetchBadgeCount;

        fetchBadgeCount();
    })();
</script>
