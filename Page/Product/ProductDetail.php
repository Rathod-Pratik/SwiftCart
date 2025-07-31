<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwiftCart | Product Detail</title>
    <?php include __DIR__ . '/../../Componenets/Header.php'; ?>
</head>

<body>
    <?php require __DIR__ . '/../../Componenets/Navbar.php' ?>
    <?php require __DIR__ . '/../../Componenets/Product/ProductDetailHeader.php' ?>
    <div class="p-6 max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Product Image -->
        <div class="bg-gray-100 rounded-2xl p-6 flex items-center justify-center">
            <img id="ProductImage" src="https://i.imgur.com/9U4QfHR.png" alt="Chair" class="w-full h-[45vh] max-w-sm rounded-xl object-contain">
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
                    My Art Design Faux Leather - Modern Swivel Lounge Chair With Hydraulic Lift For Home Office Hotel Cafe.
                </h1>
            </div>

            <!-- Price row -->
            <div class="flex items-center space-x-3 mb-2">
                <span id="price" class="text-2xl font-bold text-[#234445]">$130.00</span>
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

                <button class="bg-[#234445] hover:bg-[#1b3536] text-white text-lg font-semibold rounded-xl px-6 py-2 shadow transition-all duration-150">
                    Add To Cart
                </button>
                <!-- Like/share icons -->
                <button class="ml-2 p-2 rounded-full border border-gray-200 hover:bg-gray-100 text-gray-400 transition">
                    ♥
                </button>

            </div>
        </div>

    </div>

    <?php include __DIR__ . '/../../Componenets/Footer.php'; ?>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const productId = parseFloat(urlParams.get('productId'));
        if (productId) {
            const formData = new FormData();
            formData.append('action', 'fetchOneProduct');
            formData.append('id', productId);
            fetch('/SwiftCart/AJAX/Product_ajax.php', {
                method: "POST",
                body: formData
            }).then(res => res.json()).then((res) => {
                document.getElementById('ProductImage').src = res.product.image;
                document.getElementById('price').textContent = res.product.price;
                document.getElementById('product_name').textContent = res.product.product_name;
                 document.getElementById('ProductHeading').textContent = `Product Details/${res.product.product_name}`

            })
        } else {
            window.location.href = "/SwiftCart/products";
        }
    </script>
</body>

</html>