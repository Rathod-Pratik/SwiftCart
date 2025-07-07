<?php include '../../Componenets/Header.php'; ?>
<?php include '../../Componenets/AdminAuth.php' ?>

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

    <div class="p-4 lg:ml-64 pt-20 bg-gray-100 min-h-[100vh]">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-10">
            <div class="relative bg-gradient-to-br from-green-100 to-green-50 p-6 rounded-2xl shadow-lg text-center border border-green-200 hover:scale-105 transition-transform duration-200">
                <div class="flex items-center justify-center w-12 h-12 mx-auto mb-3 rounded-full bg-green-200/60">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <p class="text-green-700 font-semibold mb-1 tracking-wide">Complete</p>
                <h2 class="text-2xl font-bold text-green-600">200</h2>
            </div>
            <div class="relative bg-gradient-to-br from-blue-100 to-blue-50 p-6 rounded-2xl shadow-lg text-center border border-blue-200 hover:scale-105 transition-transform duration-200">
                <div class="flex items-center justify-center w-12 h-12 mx-auto mb-3 rounded-full bg-blue-200/60">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h4v4" />
                    </svg>
                </div>
                <p class="text-blue-700 font-semibold mb-1 tracking-wide">Pending</p>
                <h2 class="text-2xl font-bold text-blue-600">50</h2>
            </div>
            <div class="relative bg-gradient-to-br from-purple-100 to-purple-50 p-6 rounded-2xl shadow-lg text-center border border-purple-200 hover:scale-105 transition-transform duration-200">
                <div class="flex items-center justify-center w-12 h-12 mx-auto mb-3 rounded-full bg-purple-200/60">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2l4-4" />
                        <circle cx="12" cy="12" r="10" />
                    </svg>
                </div>
                <p class="text-purple-700 font-semibold mb-1 tracking-wide">Delivered</p>
                <h2 class="text-2xl font-bold text-purple-600">50</h2>
            </div>
            <div class="relative bg-gradient-to-br from-cyan-100 to-cyan-50 p-6 rounded-2xl shadow-lg text-center border border-cyan-200 hover:scale-105 transition-transform duration-200">
                <div class="flex items-center justify-center w-12 h-12 mx-auto mb-3 rounded-full bg-cyan-200/60">
                    <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" />
                        <circle cx="12" cy="12" r="10" />
                    </svg>
                </div>
                <p class="text-cyan-700 font-semibold mb-1 tracking-wide">Process</p>
                <h2 class="text-2xl font-bold text-cyan-600">50</h2>
            </div>
        </div>

        <div>
            <div class="flex justify-evenly gap-3 mb-6">
                <input
                    class="border-[#4fd1c5] bg-white border-2 outline-none rounded-md px-4 py-2 w-[90%]"
                    type="text"
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
                    <tbody>
                        <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                            <td class="px-6 py-4">1</td>
                            <td class="px-6 py-4">Rathod Pratik</td>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Apple MacBook Pro 17</th>
                            <td class="px-6 py-4">200</td>
                            <td class="px-6 py-4">2</td>
                            <td class="px-6 py-4">400</td>
                            <td class="px-6 py-4">Demo</td>
                          
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>