<div id="product-list" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 m-auto px-4 py-10 sm:px-6 md:px-20">
    <div id="empty" class="col-span-full flex justify-center items-center h-[50vh] text-gray-500 text-lg">
        No products found.
    </div>
</div>
<div id="product-loading" class="rounded-xl bg-white p-8 shadow-sm min-h-[60vh] justify-center flex items-center">
    <x-loading />
</div>
@push('scripts')
    <script>
        const apiUrl = @json(url('/api/products'));
        const empty = document.getElementById('empty');
        const list = document.getElementById('product-list');
        const loading = document.getElementById("product-loading");

        const resolveImageUrl = async (keyOrUrl) => {
            if (!keyOrUrl || keyOrUrl.startsWith('http://') || keyOrUrl.startsWith('https://')) return keyOrUrl ||
                '';
            try {
                const response = await fetch(`/api/s3/sign?key=${encodeURIComponent(keyOrUrl)}`, {
                    headers: {
                        Accept: 'application/json'
                    }
                });
                if (response.ok) return (await response.json()).url || keyOrUrl;
            } catch {}
            return keyOrUrl;
        };

        const renderProducts = (items, meta) => {
            list.innerHTML = '';
            if (items.length === 0) {
                empty.classList.remove('hidden');
                loading.classList.add('hidden');
                return;
            }
            empty.classList.add('hidden');
            loading.classList.add('hidden');
            items.forEach(item => {
                const productCard = document.createElement('div');
                productCard.className = 'rounded-2xl shadow-md bg-white relative overflow-hidden';
                productCard.innerHTML = `
                            ${item.discount>0?`<div class="absolute top-3 left-3 bg-teal-600 text-white text-xs px-2 py-0.5 rounded-full font-medium">-${item.discount}%</div>`:''}
                            <button class="absolute top-3 right-3 text-gray-400 hover:text-red-500">
                                <span class="group cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-colors duration-200" viewBox="0 0 24 24" fill="white" stroke="black" stroke-width="2">
                                        <path class="group-hover:fill-red-500 group-hover:stroke-red-500" stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z"/>
                                    </svg>
                                </span>
                            </button>
                            <a href="/productdetails?productId=${item.id}">
                                <img alt="${item.name}" data-image="${item.image}" class="w-full h-44 object-contain mt-6 mb-4">
                            </a>
                            <div class="bg-[#234445] text-white px-4 py-3 rounded-b-2xl flex items-center justify-between">
                                <div>
                                    <h3 class="text-sm font-medium">${item.name}</h3>
                                    <p class="text-xs mt-1">₹${new Intl.NumberFormat().format(item.price)}</p>
                                </div>
                                <button class="w-10 h-10 flex items-center justify-center bg-white text-[#234445] rounded-full hover:bg-gray-100 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.6 6M7 13l-1.3 5.2a1 1 0 001
        .8h12a1 1 0 001-.8L17 13M10 21a1 1 0 100-2 1 1 0 000 2zm7 0a1 1 0 100-2 1 1 0 000 2z"/>
                                    </svg>
                                </button>
                            </div>
                        `;
                            const imgElement = productCard.querySelector('img');
              window.setS3ImgSrc(imgElement, item.image, '/images/placeholder.png');

                list.appendChild(productCard);
            });
        };
        const loadProducts = async (page = 1) => {
            empty.classList.add('hidden');
            loading.classList.remove('hidden');

            const params = new URLSearchParams({
                page,
                per_page: 10
            });
            try {
                const response = await fetch(`${apiUrl}?${params}`, {
                    headers: {
                        Accept: 'application/json'
                    }
                });
                const payload = await response.json();
                if (!response.ok || !payload.success) throw new Error(payload.message ||
                    'Unable to load products.');
                const items = payload.data?.data ?? [];
                const meta = payload.data ?? {
                    total: items.length,
                    current_page: 1,
                    last_page: 1
                };
                renderProducts(items, meta);
            } catch (error) {
                list.innerHTML = '';
                empty.classList.remove('hidden');
                loading.classList.add('hidden');
            }
        };


        loadProducts();
    </script>
@endpush
