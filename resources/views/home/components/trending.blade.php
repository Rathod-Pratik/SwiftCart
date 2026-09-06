@props([
    'products' => [
        [
            'id' => 1,
            'product_name' => 'Gaming Laptop',
            'price' => 85000,
            'discount' => 15,
            'image' => asset('images/GamingLaptop.png'),
        ],
        [
            'id' => 2,
            'product_name' => 'Wireless Gaming Headphones',
            'price' => 4500,
            'discount' => 20,
            'image' => asset('images/GamingHeadPhones.png'),
        ],
        [
            'id' => 3,
            'product_name' => '4K Ultra Gaming Monitor',
            'price' => 24999,
            'discount' => 10,
            'image' => asset('images/GamingMonitor.png'),
        ],
        [
            'id' => 4,
            'product_name' => 'RGB Mechanical Keyboard',
            'price' => 3200,
            'discount' => 0,
            'image' => asset('images/KeyBoard.png'),
        ],
        [
            'id' => 5,
            'product_name' => 'Ergonomic Gaming Mouse',
            'price' => 1800,
            'discount' => 5,
            'image' => asset('images/Mouse.png'),
        ],
        [
            'id' => 6,
            'product_name' => 'Flagship Smartphone',
            'price' => 69999,
            'discount' => 12,
            'image' => asset('images/Mobile.webp'),
        ],
    ]
])

<section class="py-8">
    <div class="flex justify-between items-center lg:w-[90vw] m-auto mt-3 px-4">
        <h2 data-aos="fade-right" class="text-2xl font-semibold text-gray-900">
            Trending Products for you!
        </h2>
        <div data-aos="fade-left">
            <x-button href="{{ route('product') }}" class="px-6 py-3 rounded-full text-sm">
                View All &rarr;
            </x-button>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 w-[90vw] mx-auto mt-6">
        @foreach ($products as $p)
            <div data-aos="zoom-in" class="rounded-2xl shadow-md bg-white border border-gray-100 relative overflow-hidden group hover:shadow-xl transition-all duration-300">
                @if ((float) $p['discount'] > 0)
                    <div class="absolute top-3 left-3 z-10 bg-teal-600 text-white text-xs px-2.5 py-1 rounded-full font-medium shadow-sm">
                        -{{ (int) $p['discount'] }}%
                    </div>
                @endif

                <button type="button" class="absolute top-3 right-3 z-10 text-gray-400 hover:text-red-500 bg-white/80 p-2 rounded-full shadow-sm hover:bg-white transition-all">
                    <span class="group/icon cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-colors duration-200" viewBox="0 0 24 24" fill="white" stroke="black" stroke-width="2">
                            <path class="group-hover/icon:fill-red-500 group-hover/icon:stroke-red-500" stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z"/>
                        </svg>
                    </span>
                </button>

                <div class="p-4 flex items-center justify-center h-48 bg-gray-50/50">
                    <img src="{{ $p['image'] }}"
                         alt="{{ $p['product_name'] }}"
                         class="max-h-40 w-full object-contain group-hover:scale-105 transition-transform duration-300" />
                </div>

                <div class="bg-[#204d4f] text-white px-5 py-4 rounded-b-2xl flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium tracking-wide">{{ $p['product_name'] }}</h3>
                        <p class="text-xs font-semibold mt-1 text-[#f4b942]">₹{{ number_format($p['price'], 2) }}</p>
                    </div>
                    <button type="button" class="bg-white cursor-pointer text-[#204d4f] rounded-full p-2.5 hover:bg-[#f4b942] hover:text-white transition-colors shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.6 6M7 13l-1.3 5.2a1 1 0 001 .8h12a1 1 0 001-.8L17 13M10 21a1 1 0 100-2 1 1 0 000 2zm7 0a1 1 0 100-2 1 1 0 000 2z"/>
                        </svg>
                    </button>
                </div>
            </div>
        @endforeach
    </div>
</section>
