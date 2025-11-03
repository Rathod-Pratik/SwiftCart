<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwiftCart | WishList</title>
      <?php include __DIR__ . '/../../Componenets/Other/Header.php'; ?>

</head>

<body>
    <?php require __DIR__ . '/../../Componenets/Home/Navbar.php' ?>
    <div class="max-w-7xl mx-auto min-h-[70vh] px-4 py-10">
        <h1 class="text-2xl font-semibold mb-6" data-aos="fade-down">My Wishlist</h1>
        <div id="productItems" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"></div>
    </div>
    <?php include __DIR__ . '/../../Componenets/Home/Footer.php'; ?>
    <script>
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
                const badge = p.discount > 0 ?
                    `<div  class="absolute top-3 left-3 bg-teal-600 text-white text-xs px-2 py-[2px] rounded-full font-medium">
                -${parseInt(p.discount)}%
                 </div>` : '';

                const productHTML = `
                        <div data-aos="zoom-in" class="rounded-2xl shadow-md bg-white relative overflow-hidden" id=${p.productid}>
                        ${badge}
                        <button onclick="RemoveFromWishList('${p.productid}')" class="absolute top-3 right-3 text-gray-400 hover:text-red-500">
                        <span class="group text-gray-500 cursor-pointer">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                        <path d="M10 11V17" class="group-hover:stroke-red-500" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M14 11V17" class="group-hover:stroke-red-500" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M4 7H20" class="group-hover:stroke-red-500" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M6 7H12H18V18C18 19.6569 16.6569 21 15 21H9C7.34315 21 6 19.6569 6 18V7Z" class="group-hover:stroke-red-500" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M9 5C9 3.89543 9.89543 3 11 3H13C14.1046 3 15 3.89543 15 5V7H9V5Z" class="group-hover:stroke-red-500" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </g>
                    </svg>
            </span>

            </button>
            <img src="${p.image}" alt="${p.product_name}" class="w-full h-44 object-contain mt-6 mb-4" />
            <div class="bg-[#234445] text-white px-4 py-3 rounded-b-2xl flex items-center justify-between">
                <div>
                <h3 class="text-sm font-medium">${p.product_name}</h3>
                <p class="text-xs mt-1">₹${Number(p.price).toFixed(2)}</p>
                </div>
                <button onclick="AddToCart('${p.id}')" class="bg-white cursor-pointer text-[#234445] rounded-full p-2 hover:bg-gray-100 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.6 6M7 13l-1.3 5.2a1 1 0 001 .8h12a1 1 0 001-.8L17 13M10 21a1 1 0 100-2 1 1 0 000 2zm7 0a1 1 0 100-2 1 1 0 000 2z" />
                </svg>
                </button>
            </div>
            </div>
             `;
                container.insertAdjacentHTML('beforeend', productHTML);
            });


        }

        function FetchWishList() {
            const formData = new FormData();
            formData.append('action', 'fetch');
            fetch('./AJAX/WishList_ajax.php', {
                    method: "POST",
                    body: formData
                })
                .then(res => res.json())
                .then((res) => {
                    RenderProduct(res.product);
                });
        }

        function RemoveFromWishList(id) {
            const formData = new FormData();
            formData.append("action", "REMOVE")
            formData.append("productid", id)

            fetch('./AJAX/WishList_ajax.php', {
                method: "POST",
                body: formData
            }).then(res => res.json()).then((res) => {
                if (res.status == 'removed' ) {
                    showToast("Product Removed From WishList", "success")
                    document.getElementById(id).remove();
                    const WishList = parseInt(document.getElementById('wishListLength').textContent);
                    const updatedWishList = WishList - 1;
                    document.getElementById('wishListLength').textContent = updatedWishList;

                }
                if (res.status == 'not_found') {
                    showToast("Product Not found in WishList", "warning")
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

        document.addEventListener("DOMContentLoaded", function() {
            FetchWishList();
        });
    </script>
</body>

</html>