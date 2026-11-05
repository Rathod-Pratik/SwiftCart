@php
    /** @var \App\Models\User $adminUser */
    $adminUser = auth()->user();
    $adminInitials = collect(explode(' ', $adminUser->name ?? 'Admin'))
        ->filter()
        ->take(2)
        ->map(fn($part) => mb_substr($part, 0, 1))
        ->join('');
    $adminInitials = strtoupper($adminInitials ?: 'AD');
@endphp

<nav class="fixed top-0 z-50 w-full bg-[#224e51] text-[#64883e] shadow-lg">
    <div class="px-4 py-3 sm:px-8">
        <div class="flex items-center justify-between">

            <a href="/" class="text-2xl font-semibold tracking-tight text-[#FFFFFF] transition-colors duration-300 hover:text-[#F5F7F4]">
                SwiftCart
            </a>

            <div class="flex items-center gap-5">

                <div class="relative hidden sm:block">
                    <x-lucide-search
                        class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-[#344054]" />

                    <input
                        type="text"
                        placeholder="Search..."
                        class="h-10 w-72 rounded-xl border border-[#FFFFFF] bg-white py-2 pl-10 pr-4 text-sm text-[#000000] placeholder:text-[#000000] transition focus:border-[#FFFFFF] focus:outline-none focus:ring-2 focus:ring-[#FFFFFF]" />
                </div>

                <a href="{{ route('admin.notification') }}"
                   id="admin-notification-btn"
                   class="relative cursor-pointer rounded-xl p-2 transition duration-300 hover:bg-white/15 hover:text-[#d9e4e4]"
                   aria-label="Notifications"
                   title="Notifications">
                    <x-lucide-bell class="h-6 w-6 text-[#FFFFFF]" />
                    <span id="nav-unread-badge"
                        class="hidden absolute -top-1 -right-1 items-center justify-center min-w-5 h-5 px-1.5 text-[10px] font-bold text-white bg-red-500 rounded-full">
                        0
                    </span>
                </a>

                <a href="{{ route('admin.profile') }}" title="My Profile" class="block shrink-0">
                    <div class="flex h-10 w-10 items-center justify-center overflow-hidden rounded-full border-2 border-[#FFFFFF] bg-[#F4C95D] text-sm font-bold text-[#224e51] transition duration-300 hover:scale-105">
                        @if (!empty($adminUser?->image))
                            <img
                                src=""
                                data-s3-key="{{ $adminUser->image }}"
                                data-s3-auto="true"
                                onerror="this.classList.add('hidden'); document.getElementById('admin-nav-avatar-initials').classList.remove('hidden');"
                                class="h-full w-full object-cover"
                                alt="{{ $adminUser->name }}">
                            <span id="admin-nav-avatar-initials" class="hidden">{{ $adminInitials }}</span>
                        @else
                            <span id="admin-nav-avatar-initials">{{ $adminInitials }}</span>
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
        const STORAGE_KEY = 'swiftcart_admin_notifications_v1';
        const unreadBadge = document.getElementById('nav-unread-badge');
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
        window.addEventListener('admin-notifications-updated', (e) => {
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

        window.refreshAdminNotificationBadge = fetchBadgeCount;

        fetchBadgeCount();
    })();
</script>