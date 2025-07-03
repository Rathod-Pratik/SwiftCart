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
                class="border-[#d09523] border-2 outline-none rounded-md px-4 py-2 w-[90%]"
                type="text"
                placeholder="Search Booking" />
            <button class="text-white bg-[#d09523] px-5 cursor-pointer py-2 rounded-md" onclick="openModal()">
                New
            </button>
        </div>

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 ">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Id
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Description
                        </th>
                        <th scope="col" class="px-6 py-3">
                            <!-- Edit -->
                        </th>
                        <th scope="col" class="px-6 py-3">
                            <!-- Delete -->
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-6 py-4">1</td>
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Electronics</th>
                        <td class="px-6 py-4">Devices, gadgets, and accessories</td>
                        <td class="px-6 py-4 text-center">
                            <button class="text-blue-600 hover:text-blue-800" title="Edit">
                                <img src="/SwiftCart/Image/Edit.svg" alt="Delete" class="w-5 h-5 inline" />
                            </button>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button class="text-red-600 hover:text-red-800" title="Delete">
                                <img src="/SwiftCart/Image/Delete.svg" alt="Delete" class="w-5 h-5 inline" />
                            </button>
                        </td>
                    </tr>
                    <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-6 py-4">2</td>
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Fashion</th>
                        <td class="px-6 py-4">Clothing, shoes, and accessories</td>
                        <td class="px-6 py-4 text-center">
                            <button class="text-blue-600 hover:text-blue-800" title="Edit">
                                <img src="/SwiftCart/Image/Edit.svg" alt="Delete" class="w-5 h-5 inline" />
                            </button>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button class="text-red-600 hover:text-red-800" title="Delete">
                                <img src="/SwiftCart/Image/Delete.svg" alt="Delete" class="w-5 h-5 inline" />
                            </button>
                        </td>
                    </tr>
                    <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-6 py-4">3</td>
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Home & Kitchen</th>
                        <td class="px-6 py-4">Appliances, decor, and kitchenware</td>
                        <td class="px-6 py-4 text-center">
                            <button class="text-blue-600 hover:text-blue-800" title="Edit">
                                <img src="/SwiftCart/Image/Edit.svg" alt="Delete" class="w-5 h-5 inline" />
                            </button>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button class="text-red-600 hover:text-red-800" title="Delete">
                                <img src="/SwiftCart/Image/Delete.svg" alt="Delete" class="w-5 h-5 inline" />
                            </button>
                        </td>
                    </tr>
                    <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-6 py-4">4</td>
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Books</th>
                        <td class="px-6 py-4">Fiction, non-fiction, and educational</td>
                        <td class="px-6 py-4 text-center">
                            <button class="text-blue-600 hover:text-blue-800" title="Edit">
                                <img src="/SwiftCart/Image/Edit.svg" alt="Delete" class="w-5 h-5 inline" />
                            </button>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button class="text-red-600 hover:text-red-800" title="Delete">
                                <img src="/SwiftCart/Image/Delete.svg" alt="Delete" class="w-5 h-5 inline" />
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div id="myModal" class="fixed inset-0 backdrop-blur-[5px] bg-opacity-50 hidden items-center justify-center z-50">
  <div class="bg-white p-6 rounded shadow-lg w-1/3">
    <h2 class="text-xl font-bold mb-4">Modal Title</h2>
    <p>This is a modal body.</p>
    <button onclick="closeModal()" class="mt-4 bg-red-500 text-white px-4 py-2 rounded">Close</button>
  </div>
</div>
    <script>
function openModal() {
    document.getElementById("myModal").classList.remove("hidden");
    document.getElementById("myModal").classList.add("flex");
    console.log("Model is open")
}
function closeModal() {
    document.getElementById("myModal").classList.add("hidden");
}
</script>
</body>

</html>