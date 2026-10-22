@props([
    'products' => [
        [
            'id' => 1,
            'product_name' => 'ASUS ROG Strix G16',
            'image' => asset('images/GamingLaptop.png'),
            'price' => 89999,
            'discount' => 15,
        ],
        [
            'id' => 2,
            'product_name' => 'Sony WH-1000XM5',
            'image' => asset('images/GamingHeadPhones.png'),
            'price' => 24999,
            'discount' => 10,
        ],
        [
            'id' => 3,
            'product_name' => 'LG UltraGear 27"',
            'image' => asset('images/GamingMonitor.png'),
            'price' => 32999,
            'discount' => 20,
        ],
        [
            'id' => 4,
            'product_name' => 'Keychron K2 RGB',
            'image' => asset('images/KeyBoard.png'),
            'price' => 6999,
            'discount' => 5,
        ],
        [
            'id' => 5,
            'product_name' => 'Logitech G Pro X',
            'image' => asset('images/Mouse.png'),
            'price' => 4999,
            'discount' => 12,
        ],
        [
            'id' => 6,
            'product_name' => 'iPhone 16',
            'image' => asset('images/Mobile.webp'),
            'price' => 79999,
            'discount' => 8,
        ],
        [
            'id' => 7,
            'product_name' => 'Premium Notebook Set',
            'image' => asset('images/stationary.jpg'),
            'price' => 599,
            'discount' => 25,
        ],
        [
            'id' => 8,
            'product_name' => 'Apple MacBook Air M4',
            'image' => asset('images/GamingLaptop.png'),
            'price' => 114999,
            'discount' => 10,
        ],
    ]
])

<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 m-auto px-4 py-10 sm:px-6 md:px-20">

    @forelse($products as $product)

        <div class="rounded-2xl shadow-md bg-white relative overflow-hidden">

            @if($product['discount'] > 0)
                <div class="absolute top-3 left-3 bg-teal-600 text-white text-xs px-2 py-0.5 rounded-full font-medium">
                    -{{ $product['discount'] }}%
                </div>
            @endif

            <button
                class="absolute top-3 right-3 text-gray-400 hover:text-red-500"
            >
                <span class="group cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5 transition-colors duration-200"
                        viewBox="0 0 24 24"
                        fill="white"
                        stroke="black"
                        stroke-width="2">
                        <path
                            class="group-hover:fill-red-500 group-hover:stroke-red-500"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z"/>
                    </svg>
                </span>
            </button>

            <a href="/productdetails?productId={{ $product['id'] }}">
                <img
                    src="{{ $product['image'] }}"
                    alt="{{ $product['product_name'] }}"
                    class="w-full h-44 object-contain mt-6 mb-4"
                >
            </a>

            <div class="bg-[#234445] text-white px-4 py-3 rounded-b-2xl flex items-center justify-between">

                <div>
                    <h3 class="text-sm font-medium">
                        {{ $product['product_name'] }}
                    </h3>

                    <p class="text-xs mt-1">
                        ₹{{ number_format($product['price']) }}
                    </p>
                </div>

                <button
                    class="w-10 h-10 flex items-center justify-center bg-white text-[#234445] rounded-full hover:bg-gray-100 transition"
                >
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.6 6M7 13l-1.3 5.2a1 1 0 001 .8h12a1 1 0 001-.8L17 13M10 21a1 1 0 100-2 1 1 0 000 2zm7 0a1 1 0 100-2 1 1 0 000 2z"/>
                    </svg>
                </button>

            </div>

        </div>

    @empty

        <div class="col-span-full flex justify-center items-center h-[50vh] text-gray-500 text-lg">
            No products found.
        </div>

    @endforelse

</div>