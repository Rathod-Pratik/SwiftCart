<?php include '../../Componenets/Header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopizo | Admin</title>
</head>

<body>
    <?php require '../../Componenets/AdminNavbar.php' ?>
    <?php require '../../Componenets/AdminSideBar.php' ?>

    <div class="p-4 lg:ml-64 pt-20  max-w-7xl mx-auto">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-10">
            <div class="bg-white p-6 rounded-2xl shadow text-center">
                <p class="text-gray-500 mb-2">Total Revenue</p>
                <h2 class="text-2xl font-bold text-green-600">₹ 50000</h2>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow text-center">
                <p class="text-gray-500 mb-2">Total Users</p>
                <h2 class="text-2xl font-bold text-blue-600">50</h2>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow text-center">
                <p class="text-gray-500 mb-2">Total Order</p>
                <h2 class="text-2xl font-bold text-purple-600">50</h2>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow text-center">
                <p class="text-gray-500 mb-2">Total Products</p>
                <h2 class="text-2xl font-bold text-cyan-600">50</h2>
            </div>
        </div>

        
<h1 class="text-2xl font-bold  p-2">Recent Orders</h1>
<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 ">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Id
                </th>
                <th scope="col" class="px-6 py-3">
                    Product name
                </th>
                <th scope="col" class="px-6 py-3">
                    UserId
                </th>
                <th scope="col" class="px-6 py-3">
                    Category
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
            </tr>
        </thead>
        <tbody>
            <tr class="bg-white border-b  border-gray-200 hover:bg-gray-50 ">
                 <td class="px-6 py-4">
                    1
                </td>
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
                    Apple MacBook Pro 17"
                </th>
                <td class="px-6 py-4">
                    901561625
                </td>
                <td class="px-6 py-4">
                    Laptop
                </td>
                <td class="px-6 py-4">
                    $2999
                </td>
                <td class="px-6 py-4">
                    2
                </td>
                 <td class="px-6 py-4">
                    6000
                </td>
            </tr>
              <tr class="bg-white border-b  border-gray-200 hover:bg-gray-50 ">
                 <td class="px-6 py-4">
                    1
                </td>
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
                    Apple MacBook Pro 17"
                </th>
                <td class="px-6 py-4">
                    901561625
                </td>
                <td class="px-6 py-4">
                    Laptop
                </td>
                <td class="px-6 py-4">
                    $2999
                </td>
                <td class="px-6 py-4">
                    2
                </td>
                 <td class="px-6 py-4">
                    6000
                </td>
            </tr>
                 <tr class="bg-white border-b  border-gray-200 hover:bg-gray-50 ">
                 <td class="px-6 py-4">
                    1
                </td>
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
                    Apple MacBook Pro 17"
                </th>
                <td class="px-6 py-4">
                    901561625
                </td>
                <td class="px-6 py-4">
                    Laptop
                </td>
                <td class="px-6 py-4">
                    $2999
                </td>
                <td class="px-6 py-4">
                    2
                </td>
                 <td class="px-6 py-4">
                    6000
                </td>
            </tr>
                 <tr class="bg-white border-b  border-gray-200 hover:bg-gray-50 ">
                 <td class="px-6 py-4">
                    1
                </td>
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
                    Apple MacBook Pro 17"
                </th>
                <td class="px-6 py-4">
                    901561625
                </td>
                <td class="px-6 py-4">
                    Laptop
                </td>
                <td class="px-6 py-4">
                    $2999
                </td>
                <td class="px-6 py-4">
                    2
                </td>
                 <td class="px-6 py-4">
                    6000
                </td>
            </tr>
        </tbody>
    </table>
</div>

    </div>
</body>

</html>