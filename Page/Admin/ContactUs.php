<?php include __DIR__ .'/../../Componenets/AdminAuth.php' ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopizo | Admin</title>
    <?php include __DIR__ .'/../../Componenets/Header.php'; ?>
</head>
<body>
    <?php require __DIR__ .'/../../Componenets/AdminNavbar.php' ?>
    <?php require __DIR__ .'/../../Componenets/AdminSideBar.php'?>

    <div class="p-4 lg:ml-64 pt-20 bg-gray-100 min-h-[100vh]">
            <div class="flex justify-evenly gap-3 mb-6">
                <input
                    class="border-[#4fd1c5] bg-white border-2 outline-none rounded-md px-4 py-2 w-[90%]"
                    type="text"
                    placeholder="Search Vender" />
                <button class="text-white bg-[#4fd1c5] px-5 cursor-pointer py-2 rounded-md">
                    Contact 
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
                                Name
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Reason
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Email
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Number
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Message
                            </th>
                            <th scope="col" class="px-6 py-3">
                                
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4">1</td>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Rohit Mehra</td>
                            <td class="px-6 py-4">Order Issue</td>
                            <td class="px-6 py-4">rohit@example.com</td>
                            <td class="px-6 py-4">9876543210</td>
                            <td class="px-6 py-4">My order has not arrived yet.</td>
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
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Anjali Singh</td>
                            <td class="px-6 py-4">Payment Query</td>
                            <td class="px-6 py-4">anjali@example.com</td>
                            <td class="px-6 py-4">9123456789</td>
                            <td class="px-6 py-4">I was charged twice for my order.</td>
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
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Suresh Kumar</td>
                            <td class="px-6 py-4">General Inquiry</td>
                            <td class="px-6 py-4">suresh@example.com</td>
                            <td class="px-6 py-4">9988776655</td>
                            <td class="px-6 py-4">How can I change my shipping address?</td>
                            <td class="px-6 py-4 text-center">
                                <button class="flex items-center justify-center  text-red-400 hover:text-red-500 rounded-full w-8 h-8 transition-colors duration-200" title="Delete">
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