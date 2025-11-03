<?php
echo '
<section class="py-8 bg-gray-50 font-sans">
  <div class="flex justify-between lg:w-[90vw] m-auto mt-3 px-4">
    <h1 data-aos="fade-right" class="text-2xl font-semibold">Customer Reviews</h1>
    <div data-aos="fade-left" class="flex gap-3">
      <button
        id="reviewPrevBtn"
        class="px-6 py-2 rounded-md font-semibold shadow text-white bg-[#d09523] hover:bg-[#f4b942] cursor-pointer text-sm transition"
      >
        ←
      </button>
      <button
        id="reviewNextBtn"
        class="px-6 py-2 rounded-md font-semibold shadow text-white bg-[#d09523] hover:bg-[#f4b942] cursor-pointer text-sm transition"
      >
        →
      </button>
    </div>
  </div>

  <!-- Scrollable Review Cards Container -->
  <div
    id="reviewScrollWrapper"
    class="overflow-x-auto overflow-y-hidden mt-6 px-4 max-w-[90vw] m-auto scrollbar-hide scroll-smooth"
  >
    <div class="flex gap-6 w-max pb-4">
      <!-- New Styled Review Card -->
      <div data-aos="zoom-in"
        class="md:w-[720px] md:h-[420px] w-[380px] h-[410px] flex-col md:flex-row  bg-[#245955] text-white rounded-2xl p-8 relative overflow-hidden flex items-center gap-8 shadow-lg"
       >
        <!-- Decorative Stars -->
        <div class="absolute inset-0 pointer-events-none">
          <div class="absolute top-4 left-4 text-white opacity-10 text-3xl">
            ★
          </div>
          <div class="absolute top-24 right-16 text-white opacity-10 text-xl">
            ★
          </div>
          <div class="absolute bottom-6 right-8 text-white opacity-10 text-2xl">
            ★
          </div>
        </div>

        <!-- Reviewer Photo (Larger Image) -->
        <div class="flex-shrink-0 z-10">
          <img
            src="./Image/Review1.jpg"
            alt="Customer"
            class="md:w-60 md:h-80 h-24 w-24 rounded-2xl object-cover border-4 border-white/20 shadow-md"
          />
        </div>

        <!-- Review Text (Vertically Centered) -->
        <div class="flex-1 z-10">
          <p class="text-base leading-relaxed font-light mb-6">
            I ordered a smartphone from SwiftCart and received it within two days! The packaging was secure and the product was exactly as described. Will definitely shop again.
          </p>
          <hr class="border-white/30 mb-3" />
          <h4 class="text-lg font-semibold">Isha Sonaria.</h4>
          <p class="text-sm text-teal-100">- Gujrat, INDIA</p>
        </div>
      </div>
      <div
      data-aos="zoom-in"
        class="md:w-[720px] h-[410px] md:h-[420px] w-[380px] flex-col md:flex-row  bg-[#245955] text-white rounded-2xl p-8 relative overflow-hidden flex items-center gap-8 shadow-lg"
       >
        <!-- Decorative Stars -->
        <div class="absolute inset-0 pointer-events-none">
          <div class="absolute top-4 left-4 text-white opacity-10 text-3xl">
            ★
          </div>
          <div class="absolute top-24 right-16 text-white opacity-10 text-xl">
            ★
          </div>
          <div class="absolute bottom-6 right-8 text-white opacity-10 text-2xl">
            ★
          </div>
        </div>

        <!-- Reviewer Photo (Larger Image) -->
        <div class="flex-shrink-0 z-10">
         <img
            src="./Image/Review2.jpg"
            alt="Customer"
            class="md:w-60 md:h-80 h-24 w-24 rounded-2xl object-cover object-top border-4 border-white/20 shadow-md"
          />
        </div>

        <!-- Review Text (Vertically Centered) -->
        <div class="flex-1 z-10">
          <p class="text-base leading-relaxed font-light mb-6">
            As a vendor, listing my products on SwiftCart was super easy. The dashboard is user-friendly and I started getting orders quickly. Great platform for sellers!
          </p>
          <hr class="border-white/30 mb-3" />
          <h4 class="text-lg font-semibold">Harshil Rughani.</h4>
          <p class="text-sm text-teal-100">- Gujrat, INDIA</p>
        </div>
      </div>
      <div
        data-aos="zoom-in"
        class="md:w-[720px] md:h-[420px] h-[410px] w-[380px] flex-col md:flex-row  bg-[#245955] text-white rounded-2xl p-8 relative overflow-hidden flex items-center gap-8 shadow-lg"
        >
        <!-- Decorative Stars -->
        <div class="absolute inset-0 pointer-events-none">
          <div class="absolute top-4 left-4 text-white opacity-10 text-3xl">
            ★
          </div>
          <div class="absolute top-24 right-16 text-white opacity-10 text-xl">
            ★
          </div>
          <div class="absolute bottom-6 right-8 text-white opacity-10 text-2xl">
            ★
          </div>
        </div>

        <!-- Reviewer Photo (Larger Image) -->
        <div class="flex-shrink-0 z-10">
         <img
            src="./Image/Review3.jpg"
            alt="Customer"
            class="md:w-60 md:h-80 h-24 w-24 rounded-2xl object-cover object-top border-4 border-white/20 shadow-md"
          />
        </div>

        <!-- Review Text (Vertically Centered) -->
        <div class="flex-1 z-10">
          <p class="text-base leading-relaxed font-light mb-6">
            The variety of products on SwiftCart is amazing! I found everything I needed for my home in one place, and the prices were very reasonable.
          </p>
          <hr class="border-white/30 mb-3" />
          <h4 class="text-lg font-semibold">Kavya Sirodariya.</h4>
          <p class="text-sm text-teal-100">- Gujrat, INDIA</p>
        </div>
      </div>
      <div data-aos="zoom-in"
        class="md:w-[720px] md:h-[420px] h-[410px] w-[380px] flex-col md:flex-row  bg-[#245955] text-white rounded-2xl p-8 relative overflow-hidden flex items-center gap-8 shadow-lg"
         >
        <!-- Decorative Stars -->
        <div class="absolute inset-0 pointer-events-none">
          <div class="absolute top-4 left-4 text-white opacity-10 text-3xl">
            ★
          </div>
          <div class="absolute top-24 right-16 text-white opacity-10 text-xl">
            ★
          </div>
          <div class="absolute bottom-6 right-8 text-white opacity-10 text-2xl">
            ★
          </div>
        </div>

        <!-- Reviewer Photo (Larger Image) -->
        <div class="flex-shrink-0 z-10">
          <img
            src="./Image/Review4.jpg"
            alt="Customer"
            class="md:w-60 md:h-80 h-24 w-24 rounded-2xl object-cover border-4 border-white/20 shadow-md"
          />
        </div>

        <!-- Review Text (Vertically Centered) -->
        <div class="flex-1 z-10">
          <p class="text-base leading-relaxed font-light mb-6">
            Customer support at SwiftCart is fantastic. They resolved my query about a delayed order quickly and kept me updated throughout. Highly recommend!
          </p>
          <hr class="border-white/30 mb-3" />
          <h4 class="text-lg font-semibold">Vatsal Satvani.</h4>
          <p class="text-sm text-teal-100">- Gujrat, INDIA</p>
        </div>
      </div>

    </div>
  </div>
</section>';
