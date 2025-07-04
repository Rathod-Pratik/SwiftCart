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
                placeholder="Search Category" />
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
                            <button  title="Edit">
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
                            <button  title="Edit">
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
                            <button  title="Edit">
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
                            <button  title="Edit">
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
<div id="myModal" tabindex="-1" aria-hidden="true" class="backdrop-blur-sm hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-2xl shadow-lg">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 rounded-t bgcolor ">
                <h3 class="text-lg font-semibold text-white">
                    Create New Category
                </h3>
                <button type="button" onclick="closeModal()" class="text-white hover:text-[#4fd1c5] hover:bg-white bg-transparent rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center transition-colors duration-200" data-modal-toggle="crud-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form  class="p-4 md:p-5">
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                        <label for="name" class="block mb-2 text-sm font-medium textcolor">Name</label>
                        <input type="text" name="name" id="name" class="inputcolor border border-[#4fd1c5] text-gray-900 text-sm rounded-lg  focus:border-[#4fd1c5] block w-full p-2.5 " placeholder="Type product name" required="">
                    </div>
                    <!-- <div class="col-span-2 sm:col-span-1">
                        <label for="price" class="block mb-2 text-sm font-medium textcolor">Price</label>
                        <input type="number" name="price" id="price" class="inputcolor border border-[#4fd1c5] text-gray-900 text-sm rounded-lg  focus:border-[#4fd1c5] block w-full p-2.5" placeholder="$2999" required="">
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="category" class="block mb-2 text-sm font-medium textcolor">Category</label>
                        <select id="category" class="inputcolor border border-[#4fd1c5] text-gray-900 text-sm rounded-lg  focus:border-[#4fd1c5] block w-full p-2.5">
                            <option selected="">Select category</option>
                            <option value="TV">TV/Monitors</option>
                            <option value="PC">PC</option>
                            <option value="GA">Gaming/Console</option>
                            <option value="PH">Phones</option>
                        </select>
                    </div> -->
                    <div class="col-span-2">
                        <label for="description" class="block mb-2 text-sm font-medium textcolor">Product Description</label>
                        <textarea id="description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 inputcolor  rounded-lg border border-[#4fd1c5]  focus:border-[#4fd1c5]" placeholder="Write product description here"></textarea>                    
                    </div>
                </div>
                <button type="submit" class=" text-white inline-flex items-center bg-[#4fd1c5] hover:border hover:border-[#4fd1c5] hover:text-[#4fd1c5] hover:bg-white  focus:ring-4 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors duration-200">
                    <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                    Create
                </button>
            </form>
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
</script>
</body>

</html>