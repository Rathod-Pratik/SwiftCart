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
    <?php require '../../Componenets/AdminSideBar.php'?>

 <div class="p-4 lg:ml-64 pt-20">
        <div>
            <div class="flex justify-evenly gap-3 mb-6">
                <input
                    class="border-[#4fd1c5] border-2 outline-none rounded-md px-4 py-2 w-[90%]"
                    type="text"
                    placeholder="Search Vender" />
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
                                Name
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Account No
                            </th>
                            <th scope="col" class="px-6 py-3">
                                IFSC Code
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Name
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Mobile
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Email
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Address
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Company name
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4">1</td>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Amit Sharma</td>
                            <td class="px-6 py-4">1234567890</td>
                            <td class="px-6 py-4">SBIN0001234</td>
                            <td class="px-6 py-4">Amit Sharma</td>
                            <td class="px-6 py-4">9876543210</td>
                            <td class="px-6 py-4">amit@example.com</td>
                            <td class="px-6 py-4">123 Main St, Delhi</td>
                            <td class="px-6 py-4">Sharma Traders</td>
                            <td class="px-6 py-4"><span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Active</span></td>
                        </tr>
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4">2</td>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Priya Patel</td>
                            <td class="px-6 py-4">9876543211</td>
                            <td class="px-6 py-4">HDFC0005678</td>
                            <td class="px-6 py-4">Priya Patel</td>
                            <td class="px-6 py-4">9123456789</td>
                            <td class="px-6 py-4">priya@example.com</td>
                            <td class="px-6 py-4">456 Park Ave, Mumbai</td>
                            <td class="px-6 py-4">Patel Exports</td>
                            <td class="px-6 py-4"><span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">Pending</span></td>
                        </tr>
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4">3</td>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Rahul Verma</td>
                            <td class="px-6 py-4">1122334455</td>
                            <td class="px-6 py-4">ICIC0009876</td>
                            <td class="px-6 py-4">Rahul Verma</td>
                            <td class="px-6 py-4">9988776655</td>
                            <td class="px-6 py-4">rahul@example.com</td>
                            <td class="px-6 py-4">789 Lake Rd, Bangalore</td>
                            <td class="px-6 py-4">Verma Supplies</td>
                            <td class="px-6 py-4"><span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Inactive</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>