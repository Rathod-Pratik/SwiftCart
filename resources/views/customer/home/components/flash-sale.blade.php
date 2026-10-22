<section class="bg-gray-50 px-4 py-10 sm:px-6 md:px-20">
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 md:w-[65vw] lg:w-[80vw] m-auto">

        <div data-aos="fade-right" class="lg:col-span-3 bg-[#f6f6f6] rounded-xl p-6 shadow flex flex-col justify-between min-h-75">

            <!-- Header -->
            <div class="flex items-start justify-between flex-row flex-wrap gap-4">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Flash Sale!</h2>
                    <p class="mt-2 text-gray-600 max-w-md">
                        Act fast to grab incredible deals on select gaming gear & electronics in our limited-time flash sale.
                    </p>
                </div>
            </div>

            <!-- Bottom Section -->
            <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-6">

                <!-- Image -->
                <div class="shrink-0">
                    <img src="{{ asset('images/GamingMonitor.png') }}" alt="Gaming Monitor" class="w-48 h-40 object-contain" />
                </div>

                <!-- Details -->
                <div class="flex flex-col gap-2">
                    <h3 class="text-lg font-semibold text-gray-900">UltraWide Gaming Monitor</h3>
                    <p class="text-gray-600 max-w-xs text-sm">
                        Experience immersive gameplay with this 34" UltraWide monitor featuring 144Hz refresh rate and vibrant colors for the ultimate gaming setup.
                    </p>
                    <div class="flex items-center gap-4 mt-2">
                        <span class="text-2xl font-bold text-gray-900">₹24,999</span>
                        <span class="text-gray-400 line-through">₹29,999</span>
                    </div>
                    <x-button href="{{ route('product') }}" class="mt-4 px-5 py-2.5 rounded-full text-sm w-fit">
                        Shop Now &rarr;
                    </x-button>
                </div>
            </div>
        </div>

        <div data-aos="fade-left" class="lg:col-span-2 flex flex-col justify-between gap-6">

            <!-- Card 1 -->
            <div class="bg-[#f6f6f6] rounded-xl p-6 shadow flex justify-between items-center">
                <div class="flex flex-col w-full">
                    <div class="flex items-center justify-center mb-2 w-full">
                        <img src="{{ asset('images/KeyBoard.png') }}" alt="Mechanical Keyboard" class="w-full max-w-24 h-24 object-contain" />
                    </div>
                    <div class="flex flex-row justify-between items-center w-full">
                        <h4 class="text-lg font-medium text-gray-800">Mechanical Keyboard</h4>
                        <x-button href="{{ route('product') }}" class="p-2.5 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </x-button>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="bg-[#f6f6f6] rounded-xl p-6 shadow flex justify-between items-center">
                <div class="flex flex-col w-full">
                    <div class="flex items-center justify-center mb-2 w-full">
                        <img src="{{ asset('images/Mouse.png') }}" alt="Wireless Mouse" class="w-full max-w-24 h-24 object-contain" />
                    </div>
                    <div class="flex flex-row justify-between items-center w-full">
                        <h4 class="text-lg font-medium text-gray-800">Wireless Mouse</h4>
                        <x-button href="{{ route('product') }}" class="p-2.5 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </x-button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
