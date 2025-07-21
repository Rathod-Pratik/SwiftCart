<?php include '../../Componenets/AdminAuth.php' ?>
<?php

require '../../Database/db.php';
require '../../Database/Function.php';

$table = 'product';

$createSQL = " CREATE TABLE IF NOT EXISTS product (
    id SERIAL PRIMARY KEY,
    image TEXT,
    venderid INTEGER REFERENCES users(id),
    product_name VARCHAR(50),
    price NUMERIC(10, 2),
    highlight VARCHAR(150),
    stock FLOAT,
    discount NUMERIC(5,2),
    category TEXT,
    information JSONB,
    description TEXT[],
    product_state TEXT DEFAULT 'requested' CHECK (product_state IN ('requested', 'approved', 'rejected')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
";
checkAndCreateTable($pdo, $table, $createSQL);

$ContactTable = 'contact';

$ContactSQL = " CREATE TABLE IF NOT EXISTS contact (
    id SERIAL PRIMARY KEY,
    email VARCHAR(150),
    reason VARCHAR(250),
    message TEXT,
    sender VARCHAR(10) CHECK (sender IN ('customer','admin')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);";
checkAndCreateTable($pdo, $ContactTable, $ContactSQL);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopizo | Admin</title>
    <?php include '../../Componenets/Header.php'; ?>
</head>

<body>
    <?php require '../../Componenets/AdminNavbar.php' ?>
    <?php require '../../Componenets/AdminSideBar.php' ?>

    <div class="p-4 lg:ml-64 pt-20 bg-gray-100 min-h-[100vh]">
        <div class="flex justify-evenly gap-3 mb-6">
            <input
                class="border-[#4fd1c5] bg-white border-2 outline-none rounded-md px-4 py-2 w-[90%]"
                type="text"
                placeholder="Search Product" />
            <button class="text-white bg-[#4fd1c5] px-5 cursor-pointer py-2 rounded-md" onclick="openModal()">
                New
            </button>
        </div>

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-[#a0aec0] uppercase bg-gray-50 ">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Id
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Category
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Price
                        </th>
                        <th scope="col" class="px-6 py-3">
                            stock
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Discount
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Seller
                        </th>
                        <th scope="col" class="px-6 py-3">
                            <!-- Delete -->
                        </th>
                    </tr>
                </thead>
                <tbody id="Approved-table-body">
                    <tr class="">

                    </tr>

                </tbody>
            </table>
        </div>
    </div>
    <div id="myModal" tabindex="-1" aria-hidden="true" class="backdrop-blur-sm hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-40 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4  max-w-5xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-2xl shadow-lg">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 rounded-t bgcolor ">
                    <h3 class="text-lg font-semibold text-white">
                        Product Request
                    </h3>
                    <button type="button" onclick="closeModal()" class="text-white hover:text-[#4fd1c5] hover:bg-white bg-transparent rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center transition-colors duration-200" data-modal-toggle="crud-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="text-sm text-left rtl:text-right text-gray-500 hidden lg:block">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">
                            <tr>
                                <th scope="col" class="px-6 py-3">

                                </th>
                                <th scope="col" class="px-5 py-3">
                                    Request From
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Product Name
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Category
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Price
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Stock
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Discount
                                </th>
                                <th scope="col" class="px-6 py-3">

                                </th>
                            </tr>
                        </thead>
                        <tbody id="product-table-body">
                            <tr class="bg-white border-b  border-gray-200 hover:bg-gray-50 ">

                            </tr>
                        </tbody>
                    </table>
                    <div class="flex flex-col lg:hidden">
                        <a href="#" class="flex my-2 flex-col sm:flex-row items-center bg-white border border-gray-200 rounded-lg shadow-sm  m-auto max-w-full sm:max-w-xl hover:bg-gray-100 transition-all duration-200 overflow-hidden">
                            <img class="object-cover w-full sm:w-48 h-56 sm:h-auto rounded-t-lg sm:rounded-none sm:rounded-s-lg" src="https://cdn.pixabay.com/photo/2024/03/09/15/01/macbook-air-8622749_1280.png" alt="Product image">
                            <div class="flex flex-col justify-between p-4 w-full">
                                <h5 class="mb-2 text-lg sm:text-2xl font-bold tracking-tight text-gray-900">Apple MacBook Pro 17</h5>
                                <div class="flex flex-col sm:flex-row sm:items-center sm:gap-4 mb-2">
                                    <span class="text-sm sm:text-base text-gray-700">Category: <span class="font-medium">Electronics</span></span>
                                    <span class="text-sm sm:text-base text-gray-700">Price: <span class="font-medium">50000</span></span>
                                    <span class="text-sm sm:text-base text-gray-700">Stock: <span class="font-medium">50</span></span>
                                </div>
                                <div class="flex gap-2 mt-2">
                                    <button type="submit" class="flex items-center justify-center text-white bg-[#4fd1c5] hover:border hover:border-[#4fd1c5] hover:text-[#4fd1c5] hover:bg-white focus:ring-4 focus:outline-none font-medium rounded-lg text-sm px-4 py-2 transition-colors duration-200" title="Add">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                    <button class="text-white flex items-center justify-center bg-[#4fd1c5] hover:border hover:border-[#4fd1c5] hover:text-[#4fd1c5] hover:bg-white focus:ring-4 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors duration-200" title="Delete">
                                        <svg class="w-5 h-5 m-auto" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                                            <path d="M 10 2 L 9 3 L 3 3 L 3 5 L 21 5 L 21 3 L 15 3 L 14 2 L 10 2 z 
                                                M 4.3652344 7 L 5.8925781 20.263672 C 6.0245781 21.253672 6.877 22 7.875 22 
                                                L 16.123047 22 C 17.121047 22 17.974422 21.254859 18.107422 20.255859 
                                                L 19.634766 7 L 4.3652344 7 z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function openModal() {
            document.getElementById("myModal").classList.remove("hidden");
            document.getElementById("myModal").classList.add("flex");
        }

        function closeModal() {
            document.getElementById("myModal").classList.add("hidden");
        }

        function RejectProduct(id, email) {
            const formData = new FormData();
            formData.append("id", id);
            formData.append("email", email);
            formData.append("action", "approved");
            fetch('/SwiftCart/AJAX/Admin_Product_ajax.php', {
                method: "POST",
                body: formData
            }).then(res => res.json()).then((res) => {
                if (res.success) {
                    showToast("Product Rejected successfully", 'success')
                    FetchRequestedProduct()
                }
            })
        }

        function updateRequestedProductTableUI(value) {
            const tbody = document.getElementById('product-table-body');
            tbody.innerHTML = '';
            const row = document.createElement('tr');

            if (value.length < 1) {
                row.innerHTML = '<td colspan="8" class="text-center py-4 text-gray-500"> No Product are found.</td>'
                tbody.appendChild(row)

            } else {
                value.forEach(value => {
                    const row = document.createElement('tr');
                    row.innerHTML = `<td class="px-6 py-4">
                    <img src='${value.image}' class='h-8 w-8' />
                
                                </td>
                                <td class="px-6 py-4">
                                    ${value.vendor_name}
                                </td>
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
                                    ${value.product_name}
                                </th>
                                <td class="px-6 py-4">
                                    ${value.category}
                                </td>
                                <td class="px-6 py-4">
                                   ${value.price}
                                </td>
                                <td class="px-6 py-4">
                                    ${value.stock}
                                </td>
                                <td class="px-6 py-4">
                                    ${value.discount}
                                </td>
                                <td class="px-6 py-4 flex gap-2">
                                    <button onclick="ApprovedProduct(${value.id},'${value.vendor_email}')" class="cursor-pointer text-white border-transparent bg-[#4fd1c5] border hover:border-[#4fd1c5] hover:text-[#4fd1c5] font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors duration-200 hover:bg-white flex items-center justify-center" title="Add">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                    <button onclick="RejectProduct(${value.id},'${value.vendor_email}')" class="cursor-pointer text-white flex items-center justify-center bg-[#4fd1c5] border border-transparent hover:border-[#4fd1c5] hover:text-[#4fd1c5] hover:bg-white font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors duration-200" title="Delete">
                                        <svg class="w-5 h-5 m-auto" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                                            <path d="M 10 2 L 9 3 L 3 3 L 3 5 L 21 5 L 21 3 L 15 3 L 14 2 L 10 2 z 
                                                M 4.3652344 7 L 5.8925781 20.263672 C 6.0245781 21.253672 6.877 22 7.875 22 
                                                L 16.123047 22 C 17.121047 22 17.974422 21.254859 18.107422 20.255859 
                                                L 19.634766 7 L 4.3652344 7 z" />
                                        </svg>
                                    </button>
                                </td>
                    `;
                    tbody.appendChild(row);
                });

            }
        }

        function FetchRequestedProduct() {
            fetch('/SwiftCart/AJAX/Admin_Product_ajax.php', {
                method: "POST",
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'action=fetchRequestedProduct'
            }).then(res => res.json()).then(data => {
                console.log(data)
                if (data.success) {
                    updateRequestedProductTableUI(data.product)
                }
            })
        }

        function FetchApprovedProduct() {
            fetch('/SwiftCart/AJAX/Admin_Product_ajax.php', {
                method: "POST",
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'action=fetchApprovedProduct'
            }).then(res => res.json()).then(data => {
                console.log(data)
                if (data.success) {
                    UpdateApprovedProductTableUI(data.product)
                }
            })
        }

        function UpdateApprovedProductTableUI(products) {
            const tbody = document.getElementById('Approved-table-body');
            tbody.innerHTML = '';

            if (!Array.isArray(products) || products.length < 1) {
                const row = document.createElement('tr');
                row.classList.add('bg-white', 'border-b', 'border-gray-200', 'hover:bg-gray-50');
                row.innerHTML = `
                    <td colspan="8" class="text-center py-4 text-gray-500">
                        No Products found.
                    </td>
                `;
                tbody.appendChild(row);
                return;
            }

            products.forEach(product => {
                const tr = document.createElement('tr');
                tr.classList.add('bg-white', 'border-b', 'border-gray-200', 'hover:bg-gray-50');
                tr.innerHTML = `
                    <td class="px-6 py-4">${product.id}</td>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">${product.product_name}</th>
                    <td class="px-6 py-4">${product.category}</td>
                    <td class="px-6 py-4">${product.price}</td>
                    <td class="px-6 py-4">${product.stock ==0 ? 'out of stock':product.stock}</td>
                    <td class="px-6 py-4">${product.discount}%</td>
                    <td class="px-6 py-4">${product.vendor_name}</td>
                    <td class="px-6 py-4 text-center">
                        <button class="text-red-600 hover:text-red-800" title="Delete">
                            <img src="/SwiftCart/Image/Delete.svg" alt="Delete Product" class="w-5 h-5 inline" />
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        function ApprovedProduct(id, email) {
            const formData = new FormData();
            formData.append("id", id);
            formData.append("email", email);
            formData.append("action", "approved");
            fetch('/SwiftCart/AJAX/Admin_Product_ajax.php', {
                method: "POST",
                body: formData
            }).then(res => res.json()).then((res) => {
                if (res.success) {
                    showToast("Product Added successfully", 'success')
                    FetchRequestedProduct()
                    FetchApprovedProduct()
                }
            })
        }

        document.addEventListener('DOMContentLoaded', function() {
            FetchRequestedProduct();
            FetchApprovedProduct()
        });

    </script>
</body>

</html>