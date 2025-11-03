<?php   require __DIR__ . '/../../Componenets/Vender/VenderAuth.php'  ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwiftCart | Order Section</title>
     <?php include __DIR__ . '/../../Componenets/Other/Header.php'; ?>
</head>

<body>
    <?php require __DIR__ . '/../../Componenets/Vender/VenderNavbar.php' ?>
    <?php require __DIR__ . '/../../Componenets/Vender/VenderSideBar.php' ?>
    <div class="p-4 lg:ml-64 pt-20 bg-gray-100 min-h-[100vh]">
        <div class="flex justify-evenly gap-3 mb-6">
            <input
                class="border-[#4fd1c5] bg-white border-2 outline-none rounded-md px-4 py-2 w-[90%]"
                type="text"
                oninput="handleSearch(this.value)"
                placeholder="Search Product" />
            <button class="text-white bg-[#4fd1c5] px-5 cursor-pointer py-2 rounded-md">
                Search
            </button>
        </div>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-[#a0aec0] uppercase bg-gray-50 ">
                    <tr>
                        <th scope="col" class="px-6 py-3">Image</th>
                        <th scope="col" class="px-6 py-3">Product</th>
                        <th scope="col" class="px-6 py-3">Price</th>
                        <th scope="col" class="px-6 py-3">Quantity</th>
                        <th scope="col" class="px-6 py-3">Total</th>
                        <th scope="col" class="px-6 py-3">Payment</th>
                        <th scope="col" class="px-6 py-3">Ordered At</th>
                    </tr>
                </thead>
                <tbody id="orderContainer">
                </tbody>
            </table>
        </div>
    </div>

    <script>
        let AllProduct = []

        function handleSearch(searchText) {
            const query = searchText.toLowerCase().trim();

            const filtered = AllProduct.filter(cat =>
                cat.first_name.toLowerCase().includes(query)
            );

            UpdateProductTableUI(filtered);
        }

        function UpdateProductTableUI(data) {
            const container = document.getElementById('orderContainer');

            if (data.length === 0) {
                container.innerHTML = `
                        <div class="flex justify-center items-center min-h-[60vh] text-gray-500 text-xl font-medium">
                            No Order Found 
                        </div>`;
            } else {
                data.forEach(order => {
                    const tr = document.createElement('tr');
                    tr.classList.add('bg-white', 'border-b', 'border-gray-200', 'hover:bg-gray-50');
                    tr.id = `product-${order.id}`
                    tr.innerHTML = `
                                    <td class="px-4 py-3">
                                        <img src="${order.image}" alt="${order.product_name}" class="w-16 h-16 object-contain rounded border">
                                    </td>
                                    <td class="px-4 py-3 font-medium text-gray-800">${order.product_name}</td>
                                    <td class="px-4 py-3 text-gray-600">₹${order.price}</td>
                                    <td class="px-4 py-3 text-gray-600">${order.quantity}</td>
                                    <td class="px-4 py-3 text-gray-600">₹${order.total_amount}</td>
                                    <td class="px-4 py-3 text-gray-600">${order.payment_status}</td>
                                    <td class="px-4 py-3 text-gray-500 text-xs">${new Date(order.created_at).toLocaleString()}</td>
                            
                            `;
                    container.append(tr)
                });
            }
        }
        async function loadOrders() {
            const formData = new FormData();
            formData.append('action', 'FetchVenderOrder')
            const res = await fetch('../AJAX/Checkout_ajax.php', {
                method: "POST",
                body: formData
            });
            const json = await res.json();

            if (json.status === 'success') {
                AllProduct = json.data;
                UpdateProductTableUI(json.data)

            } else {
                showToast("Failed to load orders", 'danger');
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            loadOrders()
        })
    </script>
</body>

</html>