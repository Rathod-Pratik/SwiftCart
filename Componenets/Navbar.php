<?php
echo '
<nav class="bg-[#204d4f] w-full z-20 top-0 start-0 sticky">
  <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
    <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
      <span class="self-center text-white text-2xl font-semibold whitespace-nowrap">SwiftCart</span>
    </a>
    <div class="hidden md:flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse gap-4 relative">
';

if (isset($_COOKIE['authToken']) || isset($_COOKIE['AdminToken']) || isset($_COOKIE['venderToken'])) {
    echo '
      <!-- Wishlist Icon with Badge -->
      <div class="relative">
        <button class="text-gray-400 cursor-pointer">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="#204d4f" stroke="white" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z"/>
          </svg>
          <span class="absolute -top-1 -right-2 bg-red-600 text-white text-xs px-1.5 py-0.5 rounded-full">3</span>
        </button>
      </div>

      <!-- Cart Icon with Badge -->
      <div class="relative">
        <button class="text-gray-400 cursor-pointer">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 512 512" fill="#204d4f" stroke="white" stroke-width="32" stroke-linecap="round" stroke-linejoin="round">
            <path d="M80 160v256c0 17.7 14.3 32 32 32h288c17.7 0 32-14.3 32-32V160"/>
            <path d="M432 160H80"/>
            <path d="M320 160v-32c0-35.3-28.7-64-64-64s-64 28.7-64 64v32"/>
            <path d="M112 160l144 112 144-112"/>
          </svg>
          <span class="absolute -top-1 -right-2 bg-red-600 text-white text-xs px-1.5 py-0.5 rounded-full">5</span>
        </button>
      </div>

      <!-- User Icon with Dropdown -->
      <div class="relative">
        <button id="userButton" class="text-gray-400 focus:outline-none cursor-pointer">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="#204d4f" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
            <circle cx="12" cy="7" r="4" />
          </svg>
        </button>

        <!-- Dropdown -->
        <div id="dropdownMenu" class="hidden absolute right-0 mt-2 z-10 bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700">
          <ul class="py-2 text-sm text-gray-700 dark:text-gray-200">
            <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Account</a></li>
            <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Order</a></li>
            <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Logout</a></li>
          </ul>
        </div>
      </div>

      
    ';
} else {
    echo '
      <button type="button" class="text-white bg-[#d09523] hover:bg-[#f4b942] font-medium rounded-lg text-sm px-4 py-2">Login</button>
      <button type="button" class="text-white bg-[#d09523] hover:bg-[#f4b942] font-medium rounded-lg text-sm px-4 py-2">SignUp</button>
    ';
}

echo '
</div>
          <button id="menu-toggle" class="md:hidden cursor-pointer text-white mr-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
           <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1">
              <ul class="flex mt-4 font-medium border border-gray-100 rounded-lg md:space-x-8 rtl:space-x-reverse flex-row md:mt-0 md:border-0">
                <li><a href="#" class="block py-2 px-3 text-white bg-blue-700 rounded-sm md:bg-transparent md:text-[#d09523] md:p-0" aria-current="page">Home</a></li>
                <li><a href="#" class="block py-2 px-3 text-white rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-[#d09523] md:p-0">About</a></li>
                <li><a href="#" class="block py-2 px-3 text-white rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-[#d09523] md:p-0">Services</a></li>
                <li><a href="#" class="block py-2 px-3 text-white rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-[#d09523] md:p-0">Contact</a></li>
              </ul>
            </div>
             <div id="navbar-sticky"
                  class="fixed w-[80%] top-0 left-0 right-0 z-40 md:hidden m-auto rounded-md p-4 transition-transform duration-300 transform -translate-y-full backdrop-blur bg-[#204d4f]">
                <ul class="flex flex-col items-center justify-center gap-6 text-lg font-medium ">
                  <li><a href="#" class="text-white hover:text-[#d09523]">Home</a></li>
                  <li><a href="#" class="text-white hover:text-[#d09523]">About</a></li>
                  <li><a href="#" class="text-white hover:text-[#d09523]">Services</a></li>
                  <li><a href="#" class="text-white hover:text-[#d09523]">Contact</a></li>
                </ul>
              </div>


  </div>
</nav>

<script>
  const userBtn = document.getElementById("userButton");
  const dropdown = document.getElementById("dropdownMenu");

  userBtn.addEventListener("click", () => {
    dropdown.classList.toggle("hidden");
  });

  document.addEventListener("click", (e) => {
    if (!userBtn.contains(e.target) && !dropdown.contains(e.target)) {
      dropdown.classList.add("hidden");
    }
  });

  const toggleBtn = document.getElementById("menu-toggle");
  const navbar = document.getElementById("navbar-sticky");

  toggleBtn.addEventListener("click", () => {
    if (navbar.classList.contains("-translate-y-full")) {
      navbar.classList.remove("hidden");
      navbar.classList.add("mt-[82px]");
      setTimeout(() => navbar.classList.remove("-translate-y-full"), 10);
    } else {
      navbar.classList.add("-translate-y-full");
    navbar.classList.remove("mt-[82px]");
      setTimeout(() => navbar.classList.add("hidden"), 300); 
    }
  });
</script>
';
?>
