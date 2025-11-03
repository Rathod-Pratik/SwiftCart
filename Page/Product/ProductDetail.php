<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwiftCart | Product Detail</title>
      <?php include __DIR__ . '/../../Componenets/Other/Header.php'; ?>
</head>

<body>
    <?php require __DIR__ . '/../../Componenets/Home/Navbar.php' ?>
    <?php require __DIR__ . '/../../Componenets/Product/ProductDetailHeader.php' ?>
    <div class="p-6 max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Product Image -->
        <div class="bg-gray-100 rounded-2xl p-6 flex items-center justify-center">
            <img id="ProductImage" src="" alt="Chair" class="w-full h-[45vh] max-w-sm rounded-xl object-contain">
        </div>

        <!-- Product Details -->
        <div class="max-w-xl mx-auto bg-white relative">
            <!-- Discount & Choice Day -->
            <div class="flex justify-between items-center mb-6">
                <span class="bg-[#e8faf6] text-[#28afa2] text-sm font-medium px-4 py-1 rounded-lg flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="#28afa2">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3" />
                    </svg>
                    Save 25$
                </span>
                <span class="flex items-center text-xs text-gray-400 bg-gray-100 px-3 py-1 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 10h10m-9 4h1m8 0h1m-5 0v2m2-2v2" />
                    </svg>
                    Choice Day
                </span>
            </div>

            <!-- Product Title & Details -->
            <div class="mb-4">
                <span id="product_name" class="text-sm uppercase tracking-wide text-[#28afa2] font-semibold mb-1"></span>
                <h1 class="text-2xl font-bold leading-tight text-[#234445] mb-2">
                </h1>
            </div>

            <!-- Price row -->
            <div class="flex items-center space-x-3 mb-2">
                <span id="price" class="text-2xl font-bold text-[#234445]"></span>
            </div>
            <div class="flex items-center space-x-4 mb-2">
                <div class="text-xs text-gray-500">Product code : <span class="font-medium text-gray-700">992965</span></div>
                <span class="flex items-center space-x-1 text-xs text-[#28afa2]">
                    <svg class="w-4 h-4 inline mr-0.5" fill="none" viewBox="0 0 24 24" stroke="#28afa2">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    In Stock
                </span>
            </div>

            <!-- Rating, reviews, sold -->
            <div class="flex items-center space-x-2 mb-6">
                <div class="flex items-center text-yellow-400">
                    <!-- 5 star icons (could also use Heroicons/star) -->
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink"
                        viewBox="0 0 24 24" class="w-4 h-4">
                        <path
                            d="M12 .587l3.668 7.431 8.2 1.192-5.934 5.782 1.4 8.168L12 18.897l-7.334 3.863 1.4-8.168L.132 9.21l8.2-1.192z"
                            fill="#FFD700" />
                    </svg>
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink"
                        viewBox="0 0 24 24" class="w-4 h-4">
                        <path
                            d="M12 .587l3.668 7.431 8.2 1.192-5.934 5.782 1.4 8.168L12 18.897l-7.334 3.863 1.4-8.168L.132 9.21l8.2-1.192z"
                            fill="#FFD700" />
                    </svg>
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink"
                        viewBox="0 0 24 24" class="w-4 h-4">
                        <path
                            d="M12 .587l3.668 7.431 8.2 1.192-5.934 5.782 1.4 8.168L12 18.897l-7.334 3.863 1.4-8.168L.132 9.21l8.2-1.192z"
                            fill="#FFD700" />
                    </svg>
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink"
                        viewBox="0 0 24 24" class="w-4 h-4">
                        <path
                            d="M12 .587l3.668 7.431 8.2 1.192-5.934 5.782 1.4 8.168L12 18.897l-7.334 3.863 1.4-8.168L.132 9.21l8.2-1.192z"
                            fill="#FFD700" />
                    </svg>
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink"
                        viewBox="0 0 24 24" class="w-4 h-4">
                        <path
                            d="M12 .587l3.668 7.431 8.2 1.192-5.934 5.782 1.4 8.168L12 18.897l-7.334 3.863 1.4-8.168L.132 9.21l8.2-1.192z"
                            fill="#FFD700" />
                    </svg>
                </div>
                <span class="ml-2 text-sm text-[#234445] font-medium">4.9</span>
                <span class="text-xs text-gray-500">140 Reviews</span>
            </div>

            <!-- Quantity + Buttons row -->
            <div class="flex items-center mb-6">

                <button id="CartButton" onclick="AddToCart()" class="bg-[#234445] hover:bg-[#1b3536] text-white text-lg font-semibold rounded-xl px-6 py-2 shadow transition-all duration-150 cursor-pointer">
                    Add To Cart
                </button>
                <!-- Like/share icons -->
                <button id="wishlistButton" class="ml-2 p-2 rounded-full border border-gray-200 hover:bg-gray-100 bg-white text-gray-700 flex items-center gap-2 transition cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="#4a5565" stroke="white" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z" />
                    </svg>

                </button>


            </div>
        </div>

    </div>
    <div class="w-full max-w-3xl mx-auto mt-10">
        <!-- Tab Buttons -->
        <div class="flex space-x-4 justify-center">
            <button onclick="showTab('description')" id="descriptionBtn" class="py-2 cursor-pointer rounded-full px-4 border border-yellow-500 font-medium text-yellow-600">
                Description
            </button>
            <button onclick="showTab('additional')" id="additionalBtn" class="py-2 cursor-pointer rounded-full px-4 text-gray-600 border border-gray-600">
                Additional Information
            </button>
            <button onclick="showTab('review')" id="reviewBtn" class="py-2 px-4 cursor-pointer rounded-full text-gray-600 border border-gray-600">
                Review
            </button>
        </div>
    </div>
    <div>
        <div class="mt-6 m-auto md:w-[70vw]">
            <div id="descriptionTab">
                <ul class="list-disc ml-6 space-y-2 text-gray-700" id="Description">
                </ul>
            </div>

            <!-- Additional Info Tab -->
            <div id="additionalTab" class="hidden">
                <div class="flex flex-col lg:flex-row gap-10 p-6">
                    <!-- Technical Details -->
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold mb-4 text-[#234445]">Technical Details</h2>
                        <div class="rounded-2xl overflow-hidden shadow border border-gray-100 bg-white">
                            <div class="bg-[#234445] text-white font-semibold text-lg grid grid-cols-2">
                                <div class="px-6 py-4 border-r border-teal-200">Features</div>
                                <div class="px-6 py-4">Details</div>
                            </div>
                            <div class="insert-technical-info"></div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold mb-4 text-[#234445]">Additional Information</h2>
                        <div class="rounded-2xl overflow-hidden shadow border border-gray-100 bg-white">
                            <div class="bg-[#234445] text-white font-semibold text-lg grid grid-cols-2">
                                <div class="px-6 py-4 border-r border-teal-200">Features</div>
                                <div class="px-6 py-4">Information</div>
                            </div>
                            <div class="insert-additional-info"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Review Tab -->
            <div id="reviewTab" class="hidden">
                <div id="reviewsContainer" class="space-y-6"></div>

            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../../Componenets/Home/Footer.php'; ?>
    <script>
        document.getElementById('wishlistButton').onclick = function() {
            AddToWishList(res.product.id);
        };

        document.getElementById('CartButton').onclick = function() {
            AddToCart(res.product.id);
        };

        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const productId = parseFloat(urlParams.get('productId'));
            if (productId) {
                const formData = new FormData();
                formData.append('action', 'fetchOneProduct');
                formData.append('id', productId);
                fetch('./AJAX/Product_ajax.php', {
                    method: "POST",
                    body: formData
                }).then(res => res.json()).then((res) => {
                    document.getElementById('ProductImage').src = res.product.image;
                    document.getElementById('wishlistButton').onclick = function() {
                        AddToWishList(res.product.id);
                    };

                    document.getElementById('CartButton').onclick = function() {
                        AddToCart(res.product.id);
                    };
                    document.getElementById('price').textContent = res.product.price;
                    document.getElementById('product_name').textContent = res.product.product_name;
                    document.getElementById('ProductHeading').textContent = `Product Details/${res.product.product_name}`

                    const parsedDescription = JSON.parse(res.product.description);
                    const descriptionDiv = document.getElementById("Description");

                    Object.values(parsedDescription).forEach(para => {
                        const p = document.createElement("p");
                        p.textContent = para;
                        p.className = "text-base leading-relaxed";
                        descriptionDiv.appendChild(p);
                    });

                    const info = JSON.parse(res.product.information);
                    const entries = Object.entries(info);
                    const half = Math.ceil(entries.length / 2);

                    const technicalContainer = document.querySelector('.insert-technical-info');
                    const additionalContainer = document.querySelector('.insert-additional-info');

                    // Function to create one row
                    function createRow(key, value) {
                        const row = document.createElement('div');
                        row.className = 'grid grid-cols-2 even:bg-gray-50 odd:bg-white';

                        row.innerHTML = `
                                    <div class="px-6 py-4 border-r border-gray-100">${key}</div>
                                    <div class="px-6 py-4">${value}</div>
                                `;
                        return row;
                    }

                    // Fill first half in technical, rest in additional
                    entries.forEach((entry, index) => {
                        const [key, value] = entry;
                        const row = createRow(key, value);
                        if (index < half) {
                            technicalContainer.appendChild(row);
                        } else {
                            additionalContainer.appendChild(row);
                        }
                    });



                    const container = document.getElementById('reviewsContainer');

                    if (res.review.length == 0) {
                        const html = `<div class="flex justify-center items-center h-[30vh]">No Review Found</div> `
                        container.insertAdjacentHTML('beforeend', html);
                    } else {
                        res.review.forEach(review => {
                            const firstLetter = review.name.charAt(0).toUpperCase();
                            const date = new Date(review.created_at).toLocaleDateString('en-US', {
                                year: 'numeric',
                                month: 'short',
                                day: 'numeric'
                            });

                            const stars = Array.from({
                                length: 5
                            }, (_, i) => `
                                            <svg class="w-4 h-4 fill-current ${i < review.rating ? 'text-yellow-500' : 'text-gray-300'}" viewBox="0 0 20 20">
                                            <path d="M10 15l-5.878 3.09L5.64 12.545.763 8.91l6.24-.91L10 2.5l2.997 5.5 6.24.91-4.878 3.636 1.518 5.545z" />
                                            </svg>
                                        `).join('');

                            const html = `
                                                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-300 max-w-4xl mx-auto">
                                                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                                                    <div class="flex gap-4">
                                                    <div class="w-12 h-12 rounded-full bg-gray-200 text-gray-700 flex items-center justify-center text-lg font-semibold">
                                                        ${firstLetter}
                                                    </div>
                                                    <div>
                                                        <h4 class="font-semibold text-gray-900">${review.name}</h4>
                                                        <p class="text-sm text-green-600 font-medium">Verified Purchase</p>
                                                    </div>
                                                    </div>
                                                    <div class="flex items-start sm:items-center gap-2 sm:flex-col sm:gap-1 text-right">
                                                    <span class="text-sm text-gray-500">${date}</span>
                                                    <div class="flex justify-end">${stars}</div>
                                                    </div>
                                                </div>
                                                <p class="mt-2 text-sm text-gray-700 leading-relaxed">
                                                    ${review.comment}
                                                </p>
                                                </div>
                                            `;

                            container.insertAdjacentHTML('beforeend', html);
                        })
                    }
                })
            } else {
                window.location.href = "/products";
            }
        })

        function showTab(tab) {
            const tabs = ['description', 'additional', 'review'];

            tabs.forEach(t => {
                document.getElementById(`${t}Tab`).classList.add('hidden');
                document.getElementById(`${t}Btn`).classList.remove('text-yellow-600', 'border-yellow-500');
                document.getElementById(`${t}Btn`).classList.add('text-gray-600');
            });

            document.getElementById(`${tab}Tab`).classList.remove('hidden');
            document.getElementById(`${tab}Btn`).classList.add('text-yellow-600', 'border-yellow-500');
            document.getElementById(`${tab}Btn`).classList.remove('text-gray-600');
        }

        const AddToWishList = (productid) => {
            const formData = new FormData();
            formData.append('action', "ADD")
            formData.append('productid', productid)
            fetch('./AJAX/WishList_ajax.php', {
                method: "POST",
                body: formData,
                credentials: "include"
            }).then(res => res.json()).then((res) => {
                if (res.status == 'unauthorized') {
                    showToast(res.message, "denger")
                }
                if (res.status == 'added') {
                    showToast("Product Add To WishList Successfully", "success")
                    const WishList = parseInt(document.getElementById('wishListLength').textContent) || 0;
                    const updatedWishList = WishList + 1;
                    document.getElementById('wishListLength').classList.remove('hidden')
                    document.getElementById('wishListLength').textContent = updatedWishList;

                }
                if (res.status == 'exists') {
                    showToast("Product Already Exist in Cart", "warning")
                }
            })
        }

        const AddToCart = (productid) => {
            const formData = new FormData();
            formData.append('action', "ADD")
            formData.append('productid', productid)
            fetch('./AJAX/Cart_ajax.php', {
                method: "POST",
                body: formData,
                credentials: "include"
            }).then(res => res.json()).then((res) => {
                if (res.status == 'unauthorized') {
                    showToast(res.message, "denger")
                }
                if (res.status == 'added') {
                    showToast("Product Add To Cart Successfully", "success")
                    const Cart = parseInt(document.getElementById('CartLength').textContent) || 0;
                    const UpdatedCart = Cart + 1;
                    document.getElementById('CartLength').classList.remove('hidden')
                    document.getElementById('CartLength').textContent = UpdatedCart;

                }
                if (res.status == 'exists') {
                    showToast("Product Already Exist in Cart", "warning")
                }
            })
        }
    </script>
</body>

</html>