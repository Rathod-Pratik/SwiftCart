<?php

echo '
<section class="bg-gray-50 px-4 py-10 sm:px-6 md:px-20 m-auto">
  <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 items-stretch">
    
    <!-- Left Column -->
    <div class="flex flex-col lg:col-span-3 gap-6 h-full">
      
      <!-- Center Table Card -->
      <div class="bg-[#f6f6f6] rounded-xl p-6 shadow flex flex-col sm:flex-row justify-between items-center min-h-[270px]">
        <div class="w-full sm:w-2/3">
          <p class="text-xs py-2 px-2 font-semibold inline text-[#405d5b] shadow bg-white rounded-2xl">NEW COLLECTION</p>
          <h3 class="text-lg font-bold mt-4">Center Table</h3>
          <ul class="text-sm text-gray-600 list-disc list-inside mt-1 mb-4 space-y-1">
            <li>Square table</li>
            <li>Round table</li>
            <li>Wooden table</li>
            <li>Glass table</li>
          </ul>
          <a href="#" class="text-sm text-green-700 font-medium inline-flex items-center gap-1">
            View All →
          </a>
        </div>
        <div class="w-full sm:w-1/3 flex justify-center sm:justify-end mt-4 sm:mt-0">
          <img src="/SwiftCart/Image/Mobile.webp" alt="Center Table" class="h-40 object-contain max-w-full" />
        </div>
      </div>

      <!-- Lighting Lamp + Discount -->
      <div class="flex flex-col lg:flex-row gap-6 flex-1 min-h-[180px]">
        
        <!-- Lighting Lamp -->
        <div class="bg-[#f6f6f6] rounded-xl p-6 shadow flex flex-col sm:flex-row justify-between items-center flex-1">
          <div class="w-full sm:w-2/3">
            <p class="text-xs py-2 px-2 font-semibold inline text-[#405d5b] shadow bg-white rounded-2xl">NEW COLLECTION</p>
            <h3 class="text-lg font-bold mt-4">Lighting Lamp</h3>
            <ul class="text-sm text-gray-600 list-disc list-inside mt-1 mb-4 space-y-1">
              <li>Floor lamps</li>
              <li>Tripod lamps</li>
              <li>Table lamps</li>
              <li>Study lamps</li>
            </ul>
            <a href="#" class="text-sm text-green-700 font-medium inline-flex items-center gap-1">
              View All →
            </a>
          </div>
          <div class="w-full sm:w-1/3 flex justify-center sm:justify-end mt-4 sm:mt-0">
            <img src="/SwiftCart/Image/HeadPhone.webp" alt="Lamp" class="h-24 object-contain max-w-full" />
          </div>
        </div>

        <!-- Discount Offer Box -->
        <div class="bg-no-repeat bg-cover bg-[url(\'/SwiftCart/Image/HomeBackground.png\')] text-white rounded-xl p-6 flex flex-col justify-center items-center text-center shadow flex-1 min-h-[180px]">
          <span class="bg-[#f6b344] text-black text-xs font-bold px-4 py-1.5 rounded-full mb-2">
            GET DISCOUNT
          </span>
          <h2 class="text-2xl font-bold">20% OFFER</h2>
        </div>
      </div>
    </div>

    <!-- Right Column -->
    <div class="bg-[#f6f6f6] lg:col-span-2 rounded-xl p-6 shadow flex flex-col justify-between h-full">
      <div>
        <p class="text-xs py-2 px-2 font-semibold inline text-[#405d5b] shadow bg-white rounded-2xl">NEW COLLECTION</p>
        <h3 class="text-lg font-bold mt-4">Accent Chairs</h3>
        <ul class="text-sm text-gray-600 list-disc list-inside mt-1 mb-4 space-y-1">
          <li>Arm chair</li>
          <li>Wing chair</li>
          <li>Cafe chair</li>
          <li>Wheels chair</li>
        </ul>
        <a href="#" class="text-sm text-green-700 font-medium inline-flex items-center gap-1">
          View All →
        </a>
      </div>
      <div class="flex justify-center lg:justify-end mt-4">
        <img src="/SwiftCart/Image/Laptop.webp" alt="Accent Chair" class="h-48 object-contain max-w-full" />
      </div>
    </div>

  </div>
</section>

'
?>