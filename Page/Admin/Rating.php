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

    <div class="p-4 lg:ml-64 pt-20">
        <div class="flex justify-evenly gap-3 mb-6">
            <input
                class="border-[#4fd1c5] border-2 outline-none rounded-md px-4 py-2 w-[90%]"
                type="text"
                placeholder="Search Product" />
            <button class="text-white bg-[#4fd1c5] px-5 cursor-pointer py-2 rounded-md">
                Search
            </button>
        </div>

        <div class="overflow-x-auto">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                    <thead class="text-xs text-[#a0aec0] uppercase bg-gray-50 ">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Id
                            </th>
                            <th scope="col" class="px-6 py-3">
                                ProductId
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Product Name
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Rating
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Review
                            </th>
                            <th scope="col" class="px-6 py-3">
                                
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4">1</td>
                            <td class="px-6 py-4">101</td>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Apple MacBook Pro 17</td>
                            <td class="px-6 py-4 flex items-center gap-1">
                                <span class="text-yellow-400">★</span>
                                <span class="text-yellow-400">★</span>
                                <span class="text-yellow-400">★</span>
                                <span class="text-yellow-400">★</span>
                                <span class="text-gray-300">★</span>
                                <span class="ml-2 text-sm text-gray-700">4.0</span>
                            </td>
                            <td class="px-6 py-4">Great performance and battery life.</td>
                             <td class="px-6 py-4 text-center">
                                <button class="flex items-center justify-center text-red-400 hover:text-red-500 rounded-full w-8 h-8 transition-colors duration-200" title="Delete">
                                    <svg class="w-5 h-5 m-auto" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                                            <path d="M 10 2 L 9 3 L 3 3 L 3 5 L 21 5 L 21 3 L 15 3 L 14 2 L 10 2 z 
                                                M 4.3652344 7 L 5.8925781 20.263672 C 6.0245781 21.253672 6.877 22 7.875 22 
                                                L 16.123047 22 C 17.121047 22 17.974422 21.254859 18.107422 20.255859 
                                                L 19.634766 7 L 4.3652344 7 z" />
                                        </svg>
                                </button>
                            </td>
                        </tr>
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4">2</td>
                            <td class="px-6 py-4">102</td>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Samsung Galaxy S22</td>
                            <td class="px-6 py-4 flex items-center gap-1">
                                <span class="text-yellow-400">★</span>
                                <span class="text-yellow-400">★</span>
                                <span class="text-yellow-400">★</span>
                                <span class="text-yellow-400">★</span>
                                <span class="text-yellow-400">★</span>
                                <span class="ml-2 text-sm text-gray-700">5.0</span>
                            </td>
                            <td class="px-6 py-4">Excellent camera and display quality.</td>
                            <td class="px-6 py-4 text-center">
                                <button class="flex items-center justify-center text-red-400 hover:text-red-500 rounded-full w-8 h-8 transition-colors duration-200" title="Delete">
                                    <svg class="w-5 h-5 m-auto" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                                            <path d="M 10 2 L 9 3 L 3 3 L 3 5 L 21 5 L 21 3 L 15 3 L 14 2 L 10 2 z 
                                                M 4.3652344 7 L 5.8925781 20.263672 C 6.0245781 21.253672 6.877 22 7.875 22 
                                                L 16.123047 22 C 17.121047 22 17.974422 21.254859 18.107422 20.255859 
                                                L 19.634766 7 L 4.3652344 7 z" />
                                        </svg>
                                </button>
                            </td>
                        </tr>
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4">3</td>
                            <td class="px-6 py-4">103</td>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Sony WH-1000XM4</td>
                            <td class="px-6 py-4 flex items-center gap-1">
                                <span class="text-yellow-400">★</span>
                                <span class="text-yellow-400">★</span>
                                <span class="text-yellow-400">★</span>
                                <span class="text-yellow-400">★</span>
                                <span class="text-gray-300">★</span>
                                <span class="ml-2 text-sm text-gray-700">4.0</span>
                            </td>
                            <td class="px-6 py-4">Superb noise cancellation.</td>
                            <td class="px-6 py-4 text-center">
                                <button class="flex items-center justify-center text-red-400 hover:text-red-500 rounded-full w-8 h-8 transition-colors duration-200" title="Delete">
                                    <svg class="w-5 h-5 m-auto" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                                            <path d="M 10 2 L 9 3 L 3 3 L 3 5 L 21 5 L 21 3 L 15 3 L 14 2 L 10 2 z 
                                                M 4.3652344 7 L 5.8925781 20.263672 C 6.0245781 21.253672 6.877 22 7.875 22 
                                                L 16.123047 22 C 17.121047 22 17.974422 21.254859 18.107422 20.255859 
                                                L 19.634766 7 L 4.3652344 7 z" />
                                        </svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
    </div>
</body>

</html>