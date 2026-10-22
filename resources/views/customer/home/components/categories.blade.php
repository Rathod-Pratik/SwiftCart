@props([
    'categories' => [
        ['name' => 'Laptops', 'icon' => '💻'],
        ['name' => 'Headphones', 'icon' => '🎧'],
        ['name' => 'Monitors', 'icon' => '🖥️'],
        ['name' => 'Keyboards', 'icon' => '⌨️'],
        ['name' => 'Mice', 'icon' => '🖱️'],
        ['name' => 'Mobiles', 'icon' => '📱'],
        ['name' => 'Stationary', 'icon' => '✏️'],
    ]
])

<style>
    #scrollWrapper::-webkit-scrollbar {
        display: none;
    }
    #scrollWrapper {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

<section class="py-8">
    <div class="flex justify-between lg:w-[90vw] m-auto mt-3 px-4 items-center">
        <h2 data-aos="fade-right" class="text-2xl font-semibold text-gray-900">
            Featured Categories
        </h2>
        <div data-aos="fade-left" class="flex gap-3">
            <x-button id="prevBtn" type="button" class="px-5 py-2">
                &larr;
            </x-button>
            <x-button id="nextBtn" type="button" class="px-5 py-2">
                &rarr;
            </x-button>
        </div>
    </div>

    <!-- Scrollable Slider Container -->
    <div data-aos="fade-down" id="scrollWrapper" class="overflow-x-auto overflow-y-hidden mt-6 px-4 max-w-[90vw] m-auto scroll-smooth">
        <div class="flex gap-6 w-max py-2">
            @foreach ($categories as $category)
                <a href="{{ route('product', ['category' => $category['name']]) }}"
                   class="min-w-55 sm:min-w-62.5 bg-white rounded-2xl shadow-md p-6 text-center hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-gray-100 group flex flex-col items-center">
                    <div class="w-32 h-32 mb-4 bg-gray-50 rounded-full flex items-center justify-center p-4 group-hover:scale-105 transition-transform duration-300">
                        <span class="text-6xl" role="img" aria-label="{{ $category['name'] }}">{{ $category['icon'] }}</span>
                    </div>
                    <h3 class="font-semibold text-lg text-gray-800 group-hover:text-[#d09523] transition-colors">
                        {{ $category['name'] }}
                    </h3>
                </a>
            @endforeach
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const wrapper = document.getElementById('scrollWrapper');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');

        if (wrapper && prevBtn && nextBtn) {
            prevBtn.addEventListener('click', () => {
                wrapper.scrollBy({ left: -300, behavior: 'smooth' });
            });
            nextBtn.addEventListener('click', () => {
                wrapper.scrollBy({ left: 300, behavior: 'smooth' });
            });
        }
    });
</script>
