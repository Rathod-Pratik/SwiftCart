<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwiftCart | Cart</title>
    <?php include __DIR__ . '/../../Componenets/Header.php'; ?>
</head>

<body>
    <?php require __DIR__ . '/../../Componenets/Navbar.php' ?>
    <?php require __DIR__ . '/../../Componenets/Cart/CartHeader.php' ?>

    <div class="bg-gray-100 p-10">
        <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 bg-white rounded-lg overflow-hidden shadow">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-[#2c4c49] text-white">
                            <tr>
                                <th class="px-6 py-4 text-left font-semibold text-sm">Product</th>
                                <th class="px-6 py-4 text-left font-semibold text-sm">Price</th>
                                <th class="px-6 py-4 text-left font-semibold text-sm">Quantity</th>
                                <th class="px-6 py-4 text-left font-semibold text-sm">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="cartItem" class="bg-white divide-y divide-gray-200">
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="bg-white rounded-2xl shadow-lg p-6 w-full max-w-sm mx-auto h-[275px]">
                <!-- Header -->
                <h2 class="text-xl font-semibold text-[#2c4c49] mb-6">Order Summary</h2>

                <div class="space-y-3 text-sm text-gray-700">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span id="subtotal" class="font-semibold text-gray-900">$774.96</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Shipping</span>
                        <span class="font-semibold text-gray-900">Free</span>
                    </div>

                    <div class="border-t border-gray-200 pt-4 flex justify-between text-base font-semibold">
                        <span>Total</span>
                        <span id="total">$774.96</span>
                    </div>
                </div>

                <button class="w-full mt-6 bg-yellow-600 hover:bg-yellow-700 text-white py-3 rounded-full font-semibold transition cursor-pointer">
                    Proceed To Checkout
                </button>
            </div>

        </div>
    </div>


    <?php include __DIR__ . '/../../Componenets/Footer.php'; ?>
    <script>
        function FetchCart() {
            const formData = new FormData();
            formData.append('action', 'fetch');
            fetch('/SwiftCart/AJAX/Cart_ajax.php', {
                    method: "POST",
                    body: formData
                })
                .then(res => res.json())
                .then((res) => {
                    if (res.success) {
                        RenderCart(res.product);
                        UpdateCartTotals();
                    } else {
                        // If no products, still update totals to 0
                        UpdateCartTotals();
                    }
                });
        }

        function RenderCart(products) {
            const container = document.getElementById('cartItem');
            container.innerHTML = '';

            if (!products || products.length === 0) {
                container.innerHTML = `
            <tr><td colspan="4" class="text-center h-[50vh] text-gray-500 py-10 text-lg font-medium">No Products found in Cart.</td></tr>
          `;
                // Set totals to 0
                document.getElementById('subtotal').textContent = "$0.00";
                document.getElementById('total').textContent = "$0.00";
                return;
            }

            products.forEach(p => {
                const quantity = 1;
                const subtotal = (p.price * quantity).toFixed(2);

                const productHTML = `
          <tr id="${p.id}">
            <td class="px-6 py-6">
              <div class="flex items-center gap-4">
                <img src="${p.image}" class="w-12 h-12 bg-gray-200 rounded-md" />
                <span class="text-sm font-medium">${p.product_name}</span>
              </div>
            </td>
            <td class="px-6 py-6 text-sm font-medium">$${p.price}</td>
            <td class="px-6 py-6">
              <div class="flex items-center bg-gray-100 rounded-full w-max px-3 py-1">
                <button onclick="DecreaseQuantity(${p.id},${p.price})" class="text-xl cursor-pointer font-bold text-gray-500 px-1">−</button>
                <span id="cartItem-${p.id}" class="mx-2 w-6 text-center">${quantity}</span>
                <button onclick="IncreaseQuantity(${p.id},${p.price})" class="text-xl cursor-pointer font-bold text-gray-500 px-1">+</button>
              </div>
            </td>
            <td class="px-6 py-6">
              <div class="flex justify-between items-center">
                <span id="subtotal-${p.id}" class="text-sm font-medium">$${subtotal}</span>
                <button onclick="RemoveFromCart(${p.id})" class="ml-4 text-xl border-gray-500 cursor-pointer px-3 py-1 border-2 rounded-full text-gray-500 hover:bg-red-500 hover:border-white hover:text-white transition-all duration-300">×</button>
              </div>
            </td>
          </tr>
           `;
                container.insertAdjacentHTML('beforeend', productHTML);
            });

            // ✅ Initial update of summary totals
            UpdateCartTotals();
        }

        function DecreaseQuantity(id, price) {
            const quantityElement = document.getElementById("cartItem-" + id);
            const subtotalElement = document.getElementById("subtotal-" + id);

            let quantity = parseInt(quantityElement.textContent);

            if (quantity > 1) {
                quantity -= 1;
                quantityElement.textContent = quantity;
                subtotalElement.textContent = "$" + (price * quantity).toFixed(2);
                UpdateCartTotals(); // ✅ Update after change
            }
        }

        function IncreaseQuantity(id, price) {
            const quantityElement = document.getElementById("cartItem-" + id);
            const subtotalElement = document.getElementById("subtotal-" + id);

            let quantity = parseInt(quantityElement.textContent);
            quantity += 1;

            quantityElement.textContent = quantity;
            subtotalElement.textContent = "$" + (price * quantity).toFixed(2);
            UpdateCartTotals(); // ✅ Update after change
        }

        // ✅ GLOBAL FUNCTION: Calculates total from all product subtotals
        function UpdateCartTotals() {
            let subtotal = 0;

            document.querySelectorAll('[id^="subtotal-"]').forEach(el => {
                const value = parseFloat(el.textContent.replace('$', ''));
                if (!isNaN(value)) subtotal += value;
            });

            document.getElementById('subtotal').textContent = "$" + subtotal.toFixed(2);
            document.getElementById('total').textContent = "$" + subtotal.toFixed(2); // Shipping = Free
        }




        function RemoveFromCart(id) {
            const formData = new FormData();
            formData.append("action", "REMOVE")
            formData.append("productid", id)

            fetch('/SwiftCart/AJAX/Cart_ajax.php', {
                method: "POST",
                body: formData
            }).then(res => res.json()).then((res) => {
                if (res.status == 'removed') {
                    showToast("Product Removed From Cart", "success")
                    document.getElementById(id).remove();
                    const WishList = parseInt(document.getElementById('CartLength').textContent);
                    const updatedWishList = WishList - 1;
                    document.getElementById('CartLength').textContent = updatedWishList;
                    UpdateCartTotals()
                }
                if (res.status == 'not_found') {
                    showToast("Product Not found in Cart", "warning")
                }
            })
        }
        document.addEventListener('DOMContentLoaded', function() {
            FetchCart()
        })
    </script>
</body>

</html>