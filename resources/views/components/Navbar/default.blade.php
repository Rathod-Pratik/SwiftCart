<nav class="bg-[#204d4f] sticky top-0 z-20 w-full">
  <div class="max-w-7xl mx-auto flex items-center justify-between p-4">
    <a href="{{ url('/') }}" class="text-2xl font-semibold text-white">
      SwiftCart
    </a>

    {{-- Desktop Menu --}}
    <ul class="hidden md:flex items-center gap-8 text-white font-medium">
      <li>
        <a
          href="{{ route('home') }}"
          class="{{ request()->routeIs('home') ? 'text-[#d09523] font-bold' : 'hover:text-[#d09523]' }}"
          >Home</a
        >
      </li>
      <li>
        <a
          href="{{ route('product') }}"
          class="{{ request()->routeIs('product') ? 'text-[#d09523] font-bold' : 'hover:text-[#d09523]' }}"
          >Product</a
        >
      </li>
      <li>
        <a
          href="{{ route('about') }}"
          class="{{ request()->routeIs('about') ? 'text-[#d09523] font-bold' : 'hover:text-[#d09523]' }}"
          >About</a
        >
      </li>
      <li>
        <a
          href="{{ route('contact') }}"
          class="{{ request()->routeIs('contact') ? 'text-[#d09523] font-bold' : 'hover:text-[#d09523]' }}"
          >Contact</a
        >
      </li>
    </ul>

    {{-- Login Buttons --}}
    <div class="hidden md:flex gap-3">
      <x-button href="{{ route('login') }}"> Login </x-button>

      <x-button href="{{ route('register') }}"> Sign Up </x-button>
    </div>

    {{-- Mobile Toggle --}}
    <button id="menu-toggle" class="md:hidden text-white">☰</button>
  </div>

  <div id="mobile-menu" class="hidden md:hidden bg-[#204d4f] px-6 py-4">
    <ul class="flex flex-col gap-4 text-white">
      <li>
        <a
          href="{{ route('home') }}"
          class="{{ request()->routeIs('home') ? 'text-[#d09523] font-bold' : 'hover:text-[#d09523]' }}"
          >Home</a
        >
      </li>
      <li>
        <a
          href="{{ route('product') }}"
          class="{{ request()->routeIs('product') ? 'text-[#d09523] font-bold' : 'hover:text-[#d09523]' }}"
          >Products</a
        >
      </li>
      <li>
        <a
          href="{{ route('about') }}"
          class="{{ request()->routeIs('about') ? 'text-[#d09523] font-bold' : 'hover:text-[#d09523]' }}"
          >About</a
        >
      </li>
      <li>
        <a
          href="{{ route('contact') }}"
          class="{{ request()->routeIs('contact') ? 'text-[#d09523] font-bold' : 'hover:text-[#d09523]' }}"
          >Contact</a
        >
      </li>

      <hr />

      <li>
        <a
          href="{{ route('login') }}"
          class="{{ request()->routeIs('login') ? 'text-[#d09523] font-bold' : 'hover:text-[#d09523]' }}"
          >Login</a
        >
      </li>
      <li>
        <a
          href="{{ route('register') }}"
          class="{{ request()->routeIs('register') ? 'text-[#d09523] font-bold' : 'hover:text-[#d09523]' }}"
          >Sign Up</a
        >
      </li>
    </ul>
  </div>
</nav>