<?php include __DIR__ . '/../../Componenets/Admin/AdminAuth.php' ?>

<!DOCTYPE html>
<html lang="en">
    
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>SwiftCart | Admin</title>
         <?php include __DIR__ . '/../../Componenets/Other/Header.php'; ?>
</head>

<body>
    <?php require __DIR__ . '/../../Componenets/Admin/AdminNavbar.php' ?>
    <?php require __DIR__ . '/../../Componenets/Admin/AdminSideBar.php' ?>

    <div class="p-4 lg:ml-64 pt-20 bg-gray-100 min-h-[100vh]">
        <div>
            <div class="flex justify-evenly gap-3 mb-6">
                <input
                    class="border-[#4fd1c5] bg-white border-2 outline-none rounded-md px-4 py-2 w-[90%]"
                    type="text"
                    oninput="handleSearch(this.value)"
                    placeholder="Search Orders" />
                <button class="text-white bg-[#4fd1c5] px-5 cursor-pointer py-2 rounded-md">
                    Search
                </button>
            </div>
            <div>
                <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                    <thead class="text-xs text-[#a0aec0] uppercase bg-gray-50 ">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Id
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Customer
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Product Name
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Price
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Quentity
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Total Amount
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Date
                            </th>
                        </tr>
                    </thead>
                    <tbody id="order-body">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        let AllProduct = []

        function handleSearch(searchText) {
            const query = searchText.toLowerCase().trim();

            const filtered = AllProduct.filter(cat =>
                cat.name.toLowerCase().includes(query)
            );

            UpdateProductTableUI(filtered);
        }

        function UpdateProductTableUI(data) {
            const container = document.getElementById('order-body');
            container.innerHTML = ""
            if (data.length === 0) {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td colspan="7" class="bg-white border-b border-gray-200" >
                        <div class="flex items-center justify-center h-32 text-gray-500 text-lg">
                            No Order Found
                        </div>
                    </td>
                `;
                container.append(tr);

            } else {
                data.forEach(order => {
                    const tr = document.createElement('tr');
                    tr.classList.add('bg-white', 'border-b', 'border-gray-200', 'hover:bg-gray-50');
                    tr.id = `product-${order.id}`
                    tr.innerHTML = `
                             <td class="px-6 py-4">${order.id}</td>
                            <td class="px-6 py-4">${order.name}</td>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">${order.product_name}</th>
                            <td class="px-6 py-4">${order.price}</td>
                            <td class="px-6 py-4">${order.quantity}</td>
                            <td class="px-6 py-4">${order.price * order.quantity}</td>
                            <td class="px-6 py-4">${new Date(order.created_at).toLocaleString()}</td>
                            
                            `;
                    container.append(tr)
                });
            }
        }
        async function loadOrders() {
            const formData = new FormData();
            formData.append('action', 'fetchAllOrder')
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