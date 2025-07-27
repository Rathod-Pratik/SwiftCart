<?php
echo '
<section class="bg-gray-50 px-4 py-10 sm:px-6 md:px-20 ">
  <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 md:w-[65vw] lg:w-[80vw] m-auto">

    <div class="lg:col-span-3 bg-[#f6f6f6] rounded-xl p-6 shadow flex flex-col justify-between min-h-[300px]">

      <!-- Header -->
      <div class="flex items-start justify-between flex-row flex-wrap gap-4">
        <div>
          <h2 class="text-3xl font-bold text-gray-900">Flash Sale!</h2>
          <p class="mt-2 text-gray-600 max-w-md">
            Act fast to grab incredible deals on select furniture pieces in our limited-time flash sale.
          </p>
        </div>
        <div class="flex items-center gap-2">
          <span class="text-gray-700 font-semibold">End time</span>
          <div class="flex gap-1 text-white text-sm">
            <span class="bg-white text-black px-2 py-1 rounded">12 H</span>
            <span class="bg-white text-black px-2 py-1 rounded">36 M</span>
            <span class="bg-white text-black px-2 py-1 rounded">57 S</span>
          </div>
        </div>
      </div>

      <!-- Bottom Section -->
      <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-6">
        
        <!-- Image -->
        <div class="flex-shrink-0">
          <img src="/SwiftCart/Image/stationary.jpg" alt="Sofa" class="w-48 h-40 object-contain" />
        </div>

        <!-- Details -->
        <div class="flex flex-col gap-2">
          <h3 class="text-lg font-semibold text-gray-900">Vintage Leather Armchai</h3>
          <p class="text-gray-600 max-w-xs">
            Bring a touch of retro charm to your home with this vintage leather armchair. Sturdy construction ensures durability.
          </p>
          <div class="flex items-center gap-4 mt-2">
            <span class="text-2xl font-bold text-gray-900">$599</span>
            <span class="text-gray-400 line-through">$799</span>
          </div>
          <button class="mt-4 bg-yellow-600 hover:bg-yellow-700 text-white font-medium py-2 px-5 rounded-full flex items-center gap-2 w-fit">
            Shop Now
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <div class="lg:col-span-2 flex flex-col justify-between gap-6">

      <!-- Card 1 -->
      <div class="bg-[#f6f6f6] rounded-xl p-6 shadow flex justify-between items-center">
        <div class="flex flex-col w-full">
          <div class="flex items-center justify-center mb-2 w-full">
            <img src="/SwiftCart/Image/stationary.jpg" alt="Bookshelf" class="w-full max-w-[96px] h-24 object-contain" />
          </div>
          <div class="flex flex-row justify-between w-full">
            <h4 class="text-lg font-medium text-gray-800">Modern Bookshelf</h4>
            <button class="bg-yellow-600 hover:bg-yellow-700 text-white p-2 rounded-full">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Card 2 (same layout as Card 1) -->
      <div class="bg-[#f6f6f6] rounded-xl p-6 shadow flex justify-between items-center">
        <div class="flex flex-col w-full">
          <div class="flex items-center justify-center mb-2 w-full">
            <img src="/SwiftCart/Image/stationary.jpg" alt="Bookshelf" class="w-full max-w-[96px] h-24 object-contain" />
          </div>
          <div class="flex flex-row justify-between w-full">
            <h4 class="text-lg font-medium text-gray-800">Modern Bookshelf</h4>
            <button class="bg-yellow-600 hover:bg-yellow-700 text-white p-2 rounded-full">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
              </svg>
            </button>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>
';
?>
