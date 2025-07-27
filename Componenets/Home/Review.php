<?php
echo '
<section class="py-8 bg-gray-50 font-sans">
  <div class="flex justify-between lg:w-[90vw] m-auto mt-3 px-4">
    <h1 class="text-2xl font-semibold">Customer Reviews</h1>
    <div class="flex gap-3">
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
      <div
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
            src="/SwiftCart/Image/VajaKaran.jpg"
            alt="Customer"
            class="md:w-60 md:h-80 h-24 w-24 rounded-2xl object-cover border-4 border-white/20 shadow-md"
          />
        </div>

        <!-- Review Text (Vertically Centered) -->
        <div class="flex-1 z-10">
          <p class="text-base leading-relaxed font-light mb-6">
            I recently purchased a beautiful dining set from FurniFlex, and I
            couldnt be happier! The quality is top-notch, and it looks even
            better in person than it did online. Plus, the customer service team
            was incredibly helpful throughout the entire process. Highly
            recommend!
          </p>
          <hr class="border-white/30 mb-3" />
          <h4 class="text-lg font-semibold">Emily K.</h4>
          <p class="text-sm text-teal-100">- New York, NY</p>
        </div>
      </div>
      <div
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
            src="/SwiftCart/Image/VajaKaran.jpg"
            alt="Customer"
            class="md:w-60 md:h-80 h-24 w-24 rounded-2xl object-cover border-4 border-white/20 shadow-md"
          />
        </div>

        <!-- Review Text (Vertically Centered) -->
        <div class="flex-1 z-10">
          <p class="text-base leading-relaxed font-light mb-6">
            I recently purchased a beautiful dining set from FurniFlex, and I
            couldnt be happier! The quality is top-notch, and it looks even
            better in person than it did online. Plus, the customer service team
            was incredibly helpful throughout the entire process. Highly
            recommend!
          </p>
          <hr class="border-white/30 mb-3" />
          <h4 class="text-lg font-semibold">Emily K.</h4>
          <p class="text-sm text-teal-100">- New York, NY</p>
        </div>
      </div>
      <div
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
            src="/SwiftCart/Image/VajaKaran.jpg"
            alt="Customer"
            class="md:w-60 md:h-80 h-24 w-24 rounded-2xl object-cover border-4 border-white/20 shadow-md"
          />
        </div>

        <!-- Review Text (Vertically Centered) -->
        <div class="flex-1 z-10">
          <p class="text-base leading-relaxed font-light mb-6">
            I recently purchased a beautiful dining set from FurniFlex, and I
            couldnt be happier! The quality is top-notch, and it looks even
            better in person than it did online. Plus, the customer service team
            was incredibly helpful throughout the entire process. Highly
            recommend!
          </p>
          <hr class="border-white/30 mb-3" />
          <h4 class="text-lg font-semibold">Emily K.</h4>
          <p class="text-sm text-teal-100">- New York, NY</p>
        </div>
      </div>
      <div
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
            src="/SwiftCart/Image/VajaKaran.jpg"
            alt="Customer"
            class="md:w-60 md:h-80 h-24 w-24 rounded-2xl object-cover border-4 border-white/20 shadow-md"
          />
        </div>

        <!-- Review Text (Vertically Centered) -->
        <div class="flex-1 z-10">
          <p class="text-base leading-relaxed font-light mb-6">
            I recently purchased a beautiful dining set from FurniFlex, and I
            couldnt be happier! The quality is top-notch, and it looks even
            better in person than it did online. Plus, the customer service team
            was incredibly helpful throughout the entire process. Highly
            recommend!
          </p>
          <hr class="border-white/30 mb-3" />
          <h4 class="text-lg font-semibold">Emily K.</h4>
          <p class="text-sm text-teal-100">- New York, NY</p>
        </div>
      </div>

    </div>
  </div>
</section>

<style>
.scrollbar-hide::-webkit-scrollbar { display: none; }
.scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const container = document.getElementById("reviewScrollWrapper");
  const STEP = 660; // width + gap
  const DURATION = 400;

  function smoothScrollBy(delta){
    return new Promise(res=>{
      const start = container.scrollLeft;
      const target = start + delta;
      container.scrollTo({left:target, behavior:"smooth"});
      setTimeout(res, DURATION);
    });
  }

  document.getElementById("reviewNextBtn").addEventListener("click",()=>smoothScrollBy(STEP));
  document.getElementById("reviewPrevBtn").addEventListener("click",()=>smoothScrollBy(-STEP));
});
</script>
';