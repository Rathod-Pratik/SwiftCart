@php
    $authUser = auth()->user();
    $authInitials = null;
    $profileRoute = url('/profile');
    $dashboardRoute = null;
    $notificationRoute = url('/notifications');

    if ($authUser) {
        $authInitials = collect(explode(' ', $authUser->name ?? 'User'))
            ->filter()
            ->take(2)
            ->map(fn($part) => mb_substr($part, 0, 1))
            ->join('');
        $authInitials = strtoupper($authInitials ?: 'U');

        $profileRoute = $authUser->isAdmin()
            ? route('admin.profile')
            : ($authUser->isVendor() ? route('vendor.profile') : url('/profile'));

        $dashboardRoute = $authUser->isAdmin()
            ? route('admin.dashboard')
            : ($authUser->isVendor() ? route('vendor.dashboard') : null);

        $notificationRoute = $authUser->isAdmin()
            ? route('admin.notification')
            : url('/notifications');
    }
@endphp

<nav class="bg-[#204d4f] sticky top-0 z-50 w-full shadow-md">
  <div class="max-w-7xl mx-auto flex items-center justify-between p-4">
    {{-- Brand Logo --}}
    <a href="{{ url('/') }}" class="text-2xl font-bold tracking-tight text-white hover:text-white/90 transition shrink-0">
      SwiftCart
    </a>

    {{-- Desktop Navigation Links with Icons --}}
    <ul class="hidden md:flex items-center gap-6 lg:gap-8 text-white font-medium text-sm">
      <li>
        <a
          href="{{ route('home') }}"
          class="inline-flex items-center gap-2 transition duration-200 {{ request()->routeIs('home') ? 'text-[#d09523] font-bold' : 'hover:text-[#d09523]' }}"
        >
          <span>Home</span>
        </a>
      </li>
      <li>
        <a
          href="{{ route('product') }}"
          class="inline-flex items-center gap-2 transition duration-200 {{ request()->routeIs('product') ? 'text-[#d09523] font-bold' : 'hover:text-[#d09523]' }}"
        >
          <span>Product</span>
        </a>
      </li>
      <li>
        <a
          href="{{ route('about') }}"
          class="inline-flex items-center gap-2 transition duration-200 {{ request()->routeIs('about') ? 'text-[#d09523] font-bold' : 'hover:text-[#d09523]' }}"
        >
          <span>About</span>
        </a>
      </li>
      <li>
        <a
          href="{{ route('contact') }}"
          class="inline-flex items-center gap-2 transition duration-200 {{ request()->routeIs('contact') ? 'text-[#d09523] font-bold' : 'hover:text-[#d09523]' }}"
        >
          <span>Contact</span>
        </a>
      </li>
    </ul>

    {{-- Auth / Guest Actions (Desktop) --}}
    <div class="hidden md:flex items-center gap-2 shrink-0">
      @auth
        <a href="{{ url('/wishlist') }}" title="My Wishlist"
           class="relative flex h-10 w-10 items-center justify-center rounded-xl text-white transition duration-200 hover:bg-white/10 hover:text-[#d09523]">
          <x-lucide-heart class="h-5 w-5" />
          <span class="sr-only">Wishlist</span>
        </a>

        <a href="{{ url('/cart') }}" title="Shopping Cart"
           class="relative flex h-10 w-10 items-center justify-center rounded-xl text-white transition duration-200 hover:bg-white/10 hover:text-[#d09523]">
          <x-lucide-shopping-cart class="h-5 w-5" />
          <span class="sr-only">Cart</span>
        </a>

        <a href="{{ $notificationRoute }}" title="Notifications"
           class="relative flex h-10 w-10 items-center justify-center rounded-xl text-white transition duration-200 hover:bg-white/10 hover:text-[#d09523]">
          <x-lucide-bell class="h-5 w-5" />
          <span class="absolute top-2 right-2 flex h-2 w-2 rounded-full bg-red-500 ring-2 ring-[#204d4f]"></span>
          <span class="sr-only">Notifications</span>
        </a>

        <a href="{{ $profileRoute }}" title="My Profile"
           class="flex items-center gap-2 rounded-xl p-1.5 text-xs font-semibold text-white  ">
          <div class="flex h-8 w-8 items-center justify-center overflow-hidden rounded-lg bg-[#d09523] text-xs font-bold text-white shadow-xs shrink-0 transition duration-200 hover:scale-105 focus:outline-none">
            @if (!empty($authUser->image))
              <img src="{{ str_starts_with($authUser->image, 'http') ? $authUser->image : asset('storage/' . $authUser->image) }}"
                   alt="{{ $authUser->name }}"
                   onerror="this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');"
                   class="h-full w-full object-cover">
              <span class="hidden">{{ $authInitials }}</span>
            @else
              <span>{{ $authInitials }}</span>
            @endif
          </div>
        </a>
      @else
        <x-button href="{{ route('login') }}" class="!py-2 !px-4 !text-xs"> Login </x-button>
        <x-button href="{{ route('register') }}" class="!py-2 !px-4 !text-xs !bg-[#d09523] hover:!bg-[#f4b942]"> Sign Up </x-button>
      @endauth
    </div>

    <button id="menu-toggle" type="button" aria-expanded="false" aria-label="Toggle Navigation"
            class="md:hidden flex items-center justify-center p-2 rounded-xl text-white hover:bg-white/10 transition">
      <x-lucide-menu class="h-6 w-6" id="menu-icon-open" />
      <x-lucide-x class="h-6 w-6 hidden" id="menu-icon-close" />
    </button>
  </div>

  <div id="mobile-menu"
       class="md:hidden bg-[#1a4143] border-t border-white/10 overflow-hidden transition-all duration-300 ease-in-out max-h-0 opacity-0 -translate-y-2 pointer-events-none">
    <div class="px-6 py-4">
      <ul class="flex flex-col gap-3 text-white text-sm font-medium">
        <li>
          <a
            href="{{ route('home') }}"
            class="flex items-center gap-3 py-2 transition duration-200 {{ request()->routeIs('home') ? 'text-[#d09523] font-bold' : 'hover:text-[#d09523]' }}"
          >
            <x-lucide-home class="h-4 w-4 text-[#d09523]" />
            <span>Home</span>
          </a>
        </li>
        <li>
          <a
            href="{{ route('product') }}"
            class="flex items-center gap-3 py-2 transition duration-200 {{ request()->routeIs('product') ? 'text-[#d09523] font-bold' : 'hover:text-[#d09523]' }}"
          >
            <x-lucide-package class="h-4 w-4 text-[#d09523]" />
            <span>Products</span>
          </a>
        </li>
        <li>
          <a
            href="{{ route('about') }}"
            class="flex items-center gap-3 py-2 transition duration-200 {{ request()->routeIs('about') ? 'text-[#d09523] font-bold' : 'hover:text-[#d09523]' }}"
          >
            <x-lucide-info class="h-4 w-4 text-[#d09523]" />
            <span>About</span>
          </a>
        </li>
        <li>
          <a
            href="{{ route('contact') }}"
            class="flex items-center gap-3 py-2 transition duration-200 {{ request()->routeIs('contact') ? 'text-[#d09523] font-bold' : 'hover:text-[#d09523]' }}"
          >
            <x-lucide-phone class="h-4 w-4 text-[#d09523]" />
            <span>Contact</span>
          </a>
        </li>

        @auth
          @if ($dashboardRoute)
            <li class="border-t border-white/10 pt-2">
              <a href="{{ $dashboardRoute }}" class="flex items-center gap-3 py-2 text-white hover:text-[#d09523] transition">
                <x-lucide-layout-dashboard class="h-4 w-4 text-[#d09523]" />
                <span>{{ $authUser->isAdmin() ? 'Admin Dashboard' : 'Vendor Dashboard' }}</span>
              </a>
            </li>
          @endif

          <li class="{{ $dashboardRoute ? '' : 'border-t border-white/10 pt-2' }}">
            <a href="{{ $profileRoute }}" class="flex items-center gap-3 py-2 text-white hover:text-[#d09523] transition">
              <x-lucide-user class="h-4 w-4 text-[#d09523]" />
              <span>Profile</span>
            </a>
          </li>

          <li>
            <a href="{{ url('/wishlist') }}" class="flex items-center gap-3 py-2 text-white hover:text-[#d09523] transition">
              <x-lucide-heart class="h-4 w-4 text-[#d09523]" />
              <span>Wishlist</span>
            </a>
          </li>

          <li>
            <a href="{{ url('/cart') }}" class="flex items-center gap-3 py-2 text-white hover:text-[#d09523] transition">
              <x-lucide-shopping-cart class="h-4 w-4 text-[#d09523]" />
              <span>Cart</span>
            </a>
          </li>

          <li>
            <a href="{{ $notificationRoute }}" class="flex items-center gap-3 py-2 text-white hover:text-[#d09523] transition">
              <x-lucide-bell class="h-4 w-4 text-[#d09523]" />
              <span>Notifications</span>
            </a>
          </li>

          <li class="pt-3 border-t border-white/10">
            <button type="button" onclick="handleNavbarLogout()" class="flex w-full items-center gap-3 py-2 text-red-300 hover:text-red-100 transition">
              <x-lucide-log-out class="h-4 w-4" />
              <span>Log Out</span>
            </button>
          </li>
        @else
          <li class="flex flex-col gap-2 pt-3 border-t border-white/10">
            <a
              href="{{ route('login') }}"
              class="text-center rounded-xl bg-white/10 py-2.5 text-sm font-semibold text-white hover:bg-white/20 transition"
              >Login</a
            >
            <a
              href="{{ route('register') }}"
              class="text-center rounded-xl bg-[#d09523] py-2.5 text-sm font-semibold text-white hover:bg-[#f4b942] transition"
              >Sign Up</a
            >
          </li>
        @endauth
      </ul>
    </div>
  </div>
</nav>

<script>
  (() => {
    const menuToggle = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const menuOpenIcon = document.getElementById('menu-icon-open');
    const menuCloseIcon = document.getElementById('menu-icon-close');
    let isMenuOpen = false;

    if (menuToggle && mobileMenu) {
      menuToggle.addEventListener('click', () => {
        isMenuOpen = !isMenuOpen;
        menuToggle.setAttribute('aria-expanded', isMenuOpen);

        if (isMenuOpen) {
          mobileMenu.classList.remove('max-h-0', 'opacity-0', '-translate-y-2', 'pointer-events-none');
          mobileMenu.classList.add('max-h-[600px]', 'opacity-100', 'translate-y-0', 'pointer-events-auto');
          if (menuOpenIcon) menuOpenIcon.classList.add('hidden');
          if (menuCloseIcon) menuCloseIcon.classList.remove('hidden');
        } else {
          mobileMenu.classList.add('max-h-0', 'opacity-0', '-translate-y-2', 'pointer-events-none');
          mobileMenu.classList.remove('max-h-[600px]', 'opacity-100', 'translate-y-0', 'pointer-events-auto');
          if (menuOpenIcon) menuOpenIcon.classList.remove('hidden');
          if (menuCloseIcon) menuCloseIcon.classList.add('hidden');
        }
      });
    }

    window.handleNavbarLogout = async () => {
      try {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        await fetch('{{ url('/api/logout') }}', {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrf,
            'X-Requested-With': 'XMLHttpRequest'
          }
        });
      } finally {
        window.location.href = '{{ route('login') }}';
      }
    };
  })();
</script>