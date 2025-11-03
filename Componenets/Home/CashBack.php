<?php
echo '
<section class="px-4 py-10 sm:px-6 md:px-20 bg-gray-50 font-sans">
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- Left Card -->
    <div data-aos="fade-right" class="relative rounded-2xl overflow-hidden min-h-[350px] flex items-center justify-start p-8 text-white bg-cover bg-center" style="background-image: url(\'/SwiftCart/Image/offer.png \');">
      <!-- Overlay -->
      <div class="absolute inset-0 bg-[url(\'./Image/OfferBackGround.webp\')] "></div>

      <div class="relative z-10 space-y-4 max-w-md">
        <div class="flex items-center gap-3">
          <span class="text-sm font-medium tracking-wide">Exclusive Offer</span>
          <span class="bg-white text-yellow-600 text-xs font-bold px-3 py-1 rounded-full">15% OFF</span>
        </div>

        <h2 class="text-4xl font-extrabold leading-snug">Best Online<br>Deals, Free Stuff</h2>
        <p class="text-base text-gray-200">Only on this week... Don’t miss</p>

        <button class="cursor-pointer mt-3 bg-yellow-400 hover:bg-yellow-500 text-black font-semibold px-6 py-2 rounded-full inline-flex items-center gap-2 transition-all">
          Get Best Deal →
        </button>
      </div>
    </div>

    <!-- Right Card -->
    <div data-aos="fade-left" class="bg-[#f6f6f6] rounded-2xl shadow p-8 flex flex-col justify-center font-sans">
      <span class="text-sm text-gray-600 font-medium tracking-wide">Regular Offer</span>
      <h3 class="text-3xl font-bold mt-2 mb-4 text-gray-900 leading-snug">10% cash-back<br>on personal care</h3>
      <p class="text-base text-gray-600 mb-6">Max cashback: $12. Code: <strong class="font-semibold">CADHL837</strong></p>

      <button class="bg-emerald-800 cursor-pointer hover:bg-emerald-900 text-white font-semibold px-6 py-2 rounded-full inline-flex items-center gap-2 w-fit transition-all">
        Shop Now →
      </button>
    </div>

  </div>
</section>


'
?>