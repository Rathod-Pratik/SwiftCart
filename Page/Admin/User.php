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
        <div>
            <div class="flex justify-evenly gap-3 mb-6">
                <input
                    class="border-[#4fd1c5] border-2 outline-none rounded-md px-4 py-2 w-[90%]"
                    type="text"
                    placeholder="Search Users" />
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
                                Name
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Email
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Address
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Mobile
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3">

                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                            <td class="px-6 py-4">1</td>
                            <td class="px-6 py-4">Rathod Pratik</td>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">RathodPratik1928@gmail.com</th>
                            <td class="px-6 py-4">Harshiddhi city</td>
                            <td class="px-6 py-4">9016561625</td>
                            <td class="px-6 py-4">Active</td>
                            <td class="px-6 py-4"><button class="text-white flex items-center justify-center bg-[#4fd1c5] border border-transparent hover:border-[#4fd1c5] hover:text-[#4fd1c5] hover:bg-white focus:ring-4 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors duration-200" title="Delete">
                                    Block
                                </button></td>

                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>