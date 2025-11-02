<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwiftCart | Product</title>
      <?php include __DIR__ . '/../../Componenets/Other/Header.php'; ?>
</head>

<body>
    <?php require __DIR__ . '/../../Componenets/Home/Navbar.php' ?>
    <?php require __DIR__ . '/../../Componenets/Product/ProductHeader.php' ?>
    <?php require __DIR__ . '/../../Componenets/Product/Products.php' ?>

    <?php include __DIR__ . '/../../Componenets/Home/Footer.php'; ?>
    <script>
        function filterProduct(value) {
            const formData = new FormData();
            formData.append('action', 'filter')
            formData.append('category', value)
            fetch('/SwiftCart/AJAX/Product_ajax.php', {
                method: "POST",
                body: formData
            }).then(res => res.json()).then((res) => {
                RenderProduct(res.product)
                document.getElementById('ProductResult').textContent = `Showing ${res.length} Results`
            })
        }

        function FetchProduct() {
            const formData = new FormData();
            formData.append('action', 'fetch')
            fetch('/SwiftCart/AJAX/Product_ajax.php', {
                method: "POST",
                body: formData
            }).then(res => res.json()).then((res) => {
                RenderProduct(res.product)
                document.getElementById('ProductResult').textContent = `Showing ${res.length} Results`
            })
        }

        function RenderProduct(products) {
            const container = document.getElementById('productItems');
            container.innerHTML = '';
            if (!products || products.length === 0) {
                container.innerHTML = `
                    <div class="col-span-full text-center flex justify-center items-center h-[50vh] text-gray-500 py-10 text-lg font-medium">
                        No products found.
                    </div>
                `;
                return;
            }
            products.forEach(p => {
                const badge = p && p.discount > 0 ?
                    `<div class="absolute top-3 left-3 bg-teal-600 text-white text-xs px-2 py-[2px] rounded-full font-medium">
                        -${parseInt(p.discount)}%
                    </div>` : '';

                // Defensive: fallback values if p is missing or fields are undefined
                const productId = p && p.id ? p.id : '';
                const productName = p && p.product_name ? p.product_name : 'Unknown Product';
                const productImage = p && p.image ? p.image : '/SwiftCart/Image/placeholder.png';
                const productPrice = p && p.price ? Number(p.price).toFixed(2) : '0.00';

                const productHTML = `
                    <div class="rounded-2xl shadow-md bg-white relative overflow-hidden" >
                        ${badge}
                        <button onclick="AddToWishList('${productId}')" class="absolute top-3 right-3 text-gray-400 hover:text-red-500">
                            <span class="group cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-colors duration-200" viewBox="0 0 24 24" fill="white" stroke="black" stroke-width="2">
                                    <path class="group-hover:fill-red-500 group-hover:stroke-red-500" stroke-linecap="round" stroke-linejoin="round"
                                        d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 
                                        20.364l-7.682-7.682a4.5 4.5 0 010-6.364z"/>
                                </svg>
                            </span>
                        </button>
                        <a href='/SwiftCart/productdetails?productId=${productId}'>
                            <img src="${productImage}" alt="${productName}" class="w-full h-44 object-contain mt-6 mb-4" />
                        </a>
                        <div class="bg-[#234445] text-white px-4 py-3 rounded-b-2xl flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-medium">${productName}</h3>
                                <p class="text-xs mt-1">₹${productPrice}</p>
                            </div>
                            <button onclick="AddToCart('${productId}')" class="w-10 h-10 flex items-center justify-center bg-white cursor-pointer text-[#234445] rounded-full hover:bg-gray-100 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.6 6M7 
                                        13l-1.3 5.2a1 1 0 001 .8h12a1 1 0 
                                        001-.8L17 13M10 21a1 1 0 100-2 1 1 0 
                                        000 2zm7 0a1 1 0 100-2 1 1 0 000 2z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                `;

                container.insertAdjacentHTML('beforeend', productHTML);
            });
        }
        document.addEventListener('DOMContentLoaded', () => {
            const params = new URLSearchParams(window.location.search);
            const category = params.get('category');
            if (category) {
                filterProduct(category)
            } else {
                FetchProduct();
            }
        });
        const AddToWishList = (productid) => {
            const formData = new FormData();
            formData.append('action', "ADD")
            formData.append('productid', productid)
            fetch('/SwiftCart/AJAX/WishList_ajax.php', {
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
            fetch('/SwiftCart/AJAX/Cart_ajax.php', {
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