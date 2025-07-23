<?php include __DIR__ . '/../../Componenets/AdminAuth.php' ?>

<?php
require __DIR__ . '/../../Database/Function.php';        // Database connection
require __DIR__ . '/../../Database/db.php';  // Utility functions

$tableName = 'category';

$createSQL = "
    CREATE TABLE IF NOT EXISTS category (
        image Text,
        id SERIAL PRIMARY KEY,
        name VARCHAR(100),
        description VARCHAR(150),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
";

checkAndCreateTable($pdo, $tableName, $createSQL);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Shopizo | Admin</title>
    <?php include __DIR__ . '/../../Componenets/Header.php'; ?>
</head>
<script>

</script>

<body>
    <?php require __DIR__ . '/../../Componenets/AdminNavbar.php' ?>
    <?php require __DIR__ . '/../../Componenets/AdminSideBar.php' ?>

    <div id="Categories" class="backdrop-blur-1 p-4 lg:ml-64 pt-20 bg-gray-100 min-h-[100vh]">
        <div class="flex justify-evenly gap-3 mb-6">
            <input
                oninput="handleSearch(this.value)"
                class="border-[#4fd1c5] bg-white border-2 outline-none rounded-md px-4 py-2 w-[90%]"
                type="text"
                placeholder="Search Category" />
            <button
                class="text-white bg-[#4fd1c5] px-5 cursor-pointer py-2 rounded-md"
                onclick="openModal()">
                New
            </button>
        </div>


        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-[#a0aec0] uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">Id</th>
                        <th scope="col" class="px-6 py-3">Image</th>
                        <th scope="col" class="px-6 py-3">Name</th>
                        <th scope="col" class="px-6 py-3">Description</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody id="category-table-body">

                </tbody>
            </table>
        </div>
    </div>

    <div
        id="popup-modal"
        tabindex="-1"
        class="backdrop-blur-sm hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full transform scale-95 opacity-0 translate-y-[-20px] transition-all duration-300 ease-out">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow-sm">
                <button
                    onclick="CloseDeleteModal()"
                    type="button"
                    class="absolute top-3 end-2.5 text-gray-400 cursor-pointer bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                    data-modal-hide="popup-modal">
                    <svg
                        class="w-3 h-3"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 14 14">
                        <path
                            stroke="currentColor"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="p-4 md:p-5 text-center">
                    <svg
                        class="mx-auto mb-4 text-gray-400 w-12 h-12"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 20 20">
                        <path
                            stroke="currentColor"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <h3
                        id="categoryName"
                        class="mb-5 text-lg font-normal text-gray-500"></h3>
                    <button
                        id="deleteBtn"
                        data-modal-hide="popup-modal"
                        onclick="DeleteCategory()"
                        type="button"
                        class="text-white cursor-pointer bg-red-600 hover:bg-red-800 outline-none gap-2 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                        <div id="modalSpinner" class="hidden w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin"></div>
                        <span id="btnText">Yes, I'm sure</span>
                    </button>

                    <button
                        onclick="CloseDeleteModal()"
                        data-modal-hide="popup-modal"
                        type="button"
                        class="py-2.5 px-5 ms-3 cursor-pointer text-sm font-medium text-gray-900 outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10">
                        No, cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div
        id="myModal"
        tabindex="-1"
        aria-hidden="true"
        class="backdrop-blur-sm hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full   rounded-2xl shadow-lg transform scale-95 opacity-0 translate-y-[-20px] transition-all duration-300 ease-out">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-2xl shadow-lg">
                <div
                    class="flex items-center justify-between p-4 md:p-5 rounded-t bgcolor">
                    <h3 class="text-lg font-semibold text-white" id="modalTitle">
                        Create New Category
                    </h3>
                    <button
                        type="button"
                        onclick="closeModal()"
                        class="text-white hover:text-[#4fd1c5] cursor-pointer hover:bg-white bg-transparent rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center transition-colors duration-200"
                        data-modal-toggle="crud-modal">
                        <svg
                            class="w-3 h-3"
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 14 14">
                            <path
                                stroke="currentColor"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form class="p-4 md:p-5" method="post" id="categoryForm">
                    <input type="hidden" name="action" id="formAction" />
                    <input type="hidden" name="id" id="edit_id" />
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">

                            <div class="flex flex-col items-center gap-4">
                                <label for="imageInput" class="w-full h-64 border-2 border-dashed border-[#4fd1c5] flex justify-center items-center overflow-hidden rounded-lg cursor-pointer">
                                    <img id="imagePreview" src="" alt="Preview" class="max-h-full object-contain hidden" />
                                    <span id="imagePlaceholder" class="text-gray-400">Select image</span>
                                </label>
                                <input type="file" name="image" id="imageInput" accept="image/*" class="hidden" />
                            </div>
                        </div>
                        <div class="col-span-2">
                            <label
                                for="name"
                                class="block mb-2 text-sm font-medium textcolor">Name</label>
                            <input
                                required
                                type="text"
                                name="name"
                                id="modal_name"
                                class="inputcolor border border-[#4fd1c5] text-gray-900 text-sm rounded-lg focus:border-[#4fd1c5] block w-full p-2.5"
                                placeholder="Category name" />
                        </div>
                        <div class="col-span-2">
                            <label
                                for="description"
                                class="block mb-2 text-sm font-medium textcolor">Description</label>
                            <textarea
                                required
                                name="description"
                                id="modal_description"
                                rows="4"
                                class="block p-2.5 w-full text-sm text-gray-900 inputcolor rounded-lg border border-[#4fd1c5] focus:border-[#4fd1c5]"
                                placeholder="Category description"></textarea>
                        </div>
                    </div>
                    <button
                        type="submit"
                        id="modalSubmitBtn"
                        class="text-white cursor-pointer inline-flex items-center gap-2 bg-[#4fd1c5] hover:border hover:border-[#4fd1c5] hover:text-[#4fd1c5] hover:bg-white outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors duration-200">

                        <!-- Spinner inherits text color -->
                        <div id="spinner" class="hidden w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin"></div>
                        <span id="modalBtnText">Create</span>
                    </button>



                </form>
            </div>
        </div>
    </div>

    <script>
        let deleteCategoryId;
        let allCategories = [];

        function handleSearch(searchText) {
            const query = searchText.toLowerCase().trim();

            const filtered = allCategories.filter(cat =>
                cat.name.toLowerCase().includes(query)
            );

            updateTableUI(filtered);
        }

        let file;
        const imageInput = document.getElementById('imageInput');
        const imagePreview = document.getElementById('imagePreview');
        const imagePlaceholder = document.getElementById('imagePlaceholder');
        const deleteImageBtn = document.getElementById('deleteImageBtn');

        imageInput.addEventListener('change', function() {
            file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                    imagePlaceholder.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        function openModal() {
            const modal = document.getElementById("myModal");

            // Reset form
            document.getElementById("categoryForm").reset();
            document.getElementById("formAction").value = "create";
            document.getElementById("modalTitle").textContent = "Create New Category";
            document.getElementById("modalBtnText").textContent = "Create";
            document.getElementById("edit_id").value = "";

            // Show modal
            modal.classList.remove("hidden");
            modal.classList.add("flex");

            // Animate drop popup effect
            setTimeout(() => {
                modal.classList.remove("scale-95", "opacity-0", "translate-y-[-20px]");
                modal.classList.add("scale-100", "opacity-100", "translate-y-0");
            }, 10);
        }

        function closeModal() {
            const modal = document.getElementById("myModal");

            // Animate out (back to initial state)
            modal.classList.remove("scale-100", "opacity-100", "translate-y-0");
            modal.classList.add("scale-95", "opacity-0", "translate-y-[-20px]");
            file = null;

            // After transition ends, hide the modal
            setTimeout(() => {
                modal.classList.remove("flex");
                modal.classList.add("hidden");

                document.getElementById("categoryForm").reset();
                document.getElementById('imagePreview').classList.add('hidden');
                document.getElementById('imagePlaceholder').classList.remove('hidden');
            }, 300);
        }

        function OpenDeleteModal(id, name) {
            deleteCategoryId = id;
            document.getElementById(
                "categoryName"
            ).textContent = `Are you sure you want to delete ${name} Category?`;
            document.getElementById("popup-modal").classList.remove("hidden");
            document.getElementById("popup-modal").classList.add("flex");
            setTimeout(() => {
                document.getElementById("popup-modal").classList.remove("scale-95", "opacity-0", "translate-y-[-20px]");
                document.getElementById("popup-modal").classList.add("scale-100", "opacity-100", "translate-y-0");
            }, 10);
        }

        function CloseDeleteModal() {
            document.getElementById("popup-modal").classList.remove("scale-100", "opacity-100", "translate-y-0");
            document.getElementById("popup-modal").classList.add("scale-95", "opacity-0", "translate-y-[-20px]");
            deleteCategoryId = null
            setTimeout(() => {
                document.getElementById("popup-modal").classList.remove("flex");
                document.getElementById("popup-modal").classList.add("hidden");
            }, 300);
        }

        function openEditModal(id, name, description, image) {
            openModal()
            document.getElementById("formAction").value = "update";
            document.getElementById("modalTitle").textContent = "Edit Category";
            document.getElementById("modalBtnText").textContent = "Update";
            imagePreview.src = image;
            imagePreview.classList.remove('hidden');
            imagePlaceholder.classList.add('hidden');
            document.getElementById("edit_id").value = id;
            document.getElementById("modal_name").value = name;
            document.getElementById("modal_description").value = description;
        }


        function fetchCategories() {
            fetch('/SwiftCart/AJAX/category_ajax.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'action=fetch'
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        allCategories = data.categories;
                        updateTableUI(data.categories);
                    }
                });
        }

        function updateTableUI(categories) {
            const tbody = document.getElementById('category-table-body');
            tbody.innerHTML = '';

            if (categories.length < 1) {
                const row = document.createElement('tr');
                row.id = 'emptyCategorytable'
                row.innerHTML = `
              <td colspan="5" class="text-center py-4 text-gray-500">
                No categories found.
               </td>
              `;
                tbody.appendChild(row);
                return;
            }
            categories.forEach(cat => {
                const row = document.createElement('tr');
                row.id = `row-${cat.id}`;
                row.innerHTML = `
                                 <td class="px-6 py-4">${cat.id}</td>
                                 <td class="px-6 py-4"> <img src='${cat.image}' class="w-16 h-16 object-contain rounded"  alt='categoryicon'/></td>
                                 <th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">${cat.name}</th>
                                 <td class="px-6 py-4">${cat.description}</td>
                                 <td class=" py-4 px-6 flex justify-end gap-2 mt-3">
                                     <button class="text-white bg-[#4fd1c5] px-5 cursor-pointer py-2 rounded-md"
                                         onclick="openEditModal(${cat.id}, '${cat.name}', '${cat.description}','${cat.image}')">
                                         Update
                                    </button>
                                     <button class="text-white bg-[#4fd1c5] px-5 cursor-pointer py-2 rounded-md"
                                         onclick="OpenDeleteModal(${cat.id}, '${cat.name}')">
                                         Delete
                                     </button>
                                 </td>
                                 `;
                tbody.appendChild(row);
            });

        }

        function DeleteCategory() {
            document.getElementById('modalSpinner').classList.remove('hidden')
            document.getElementById('deleteBtn').disabled = true;
            fetch("/SwiftCart/AJAX/category_ajax.php", {
                    method: "POST",
                    body: new URLSearchParams({
                        action: "delete",
                        id: deleteCategoryId,
                    }),
                })
                .then((res) => res.json())
                .then((data) => {
                    if (data.success) {
                        const row = document.getElementById(deleteCategoryId);
                        row.remove()
                        deleteCategoryId = null
                        allCategories = allCategories.filter(cat => cat.id !== deleteCategoryId);

                    } else {
                        showToast("Server is Down Try again leter", "denger")
                    }
                }).then(() => {
                    document.getElementById('modalSpinner').classList.add('hidden')
                    document.getElementById('deleteBtn').disabled = false;
                    CloseDeleteModal()
                });
        }

        document.addEventListener('DOMContentLoaded', function() {
            fetchCategories();
        });

        document.getElementById("categoryForm").addEventListener("submit", async function(e) {
            e.preventDefault();

            const spinner = document.getElementById('spinner');
            const text = document.getElementById('modalBtnText')
            const button = document.getElementById('modalSubmitBtn');

            const actionValue = document.getElementById("formAction").value;
            if (actionValue == "create") {
                spinner.classList.remove('hidden');
                text.textContent = 'Creating...';
                button.disabled = true;

                const form = e.target;
                const formData = new FormData(form);

                if (file) {
                    const uploadFormData = new FormData();
                    uploadFormData.append('action', 'upload');
                    uploadFormData.append('image', file);

                    await fetch('/SwiftCart/AJAX/Vender_Product_ajax.php', {
                            method: 'POST',
                            body: uploadFormData
                        })
                        .then(res => res.json())
                        .then(res => {
                            formData.set('image', res.data.secure_url)
                        })
                }
                fetch("/SwiftCart/AJAX/category_ajax.php", {
                        method: "POST",
                        body: formData,
                    })
                    .then((res) => res.json())
                    .then((data) => {
                        if (data.success) {
                            const emptyTable = document.getElementById('emptyCategorytable');
                            if (emptyTable) row.remove()
                            const row = document.createElement('tr');
                            row.id = `row-${data.data.id}`;
                            row.innerHTML = `
                                <td class="px-6 py-4">${data.data.id}</td>
                                 <td class="px-6 py-4"> <img src='${data.data.image}' class="w-16 h-16 object-contain rounded"  alt='categoryicon'/></td>
                                 <th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">${data.data.name}</th>
                                 <td class="px-6 py-4">${data.data.description}</td>
                                 <td class="">
                                     <button class="text-white bg-[#4fd1c5] px-5 cursor-pointer py-2 rounded-md"
                                         onclick="openEditModal(${data.data.id}, '${data.data.name}', '${data.data.description}','${data.data.image}')">
                                         Update
                                    </button>
                                     <button class="text-white bg-[#4fd1c5] px-5 cursor-pointer py-2 rounded-md"
                                         onclick="OpenDeleteModal(${data.data.id}, '${data.data.name}')">
                                         Delete
                                     </button>
                                 </td>
                                `;
                            document.getElementById('category-table-body').appendChild(row);
                            allCategories.push(data.data);
                        } else {
                            showToast("Server is Down Try again leter", "denger")
                        }

                    }).then(() => {
                        spinner.classList.add('hidden');
                        text.textContent = 'Create';
                        button.disabled = false;
                        closeModal();
                    })
            } else if (actionValue == "update") {
                spinner.classList.remove('hidden');
                text.textContent = 'Updating...';
                button.disabled = true;

                const form = e.target;
                const formData = new FormData(form);

                if (file) {
                    const uploadFormData = new FormData();
                    uploadFormData.append('action', 'upload');
                    uploadFormData.append('image', file);

                    await fetch('/SwiftCart/AJAX/Vender_Product_ajax.php', {
                            method: 'POST',
                            body: uploadFormData
                        })
                        .then(res => res.json())
                        .then(res => {
                            formData.set('image', res.data.secure_url)
                        })
                } else {
                    formData.set('image', imagePreview.src)
                }
                fetch("/SwiftCart/AJAX/category_ajax.php", {
                        method: "POST",
                        body: formData,
                    })
                    .then((res) => res.json())
                    .then((data) => {
                        if (data.success) {
                            const row = document.getElementById(`row-${data.data.id}`);
                            row.cells[0].textContent = data.data.id;
                            row.cells[1].querySelector('img').src = data.data.image;
                            row.cells[2].textContent = data.data.name;
                            row.cells[3].textContent = data.data.description;
                            row.cells[4].innerHTML = `
                                                <button class="text-white bg-[#4fd1c5] px-5 cursor-pointer py-2 rounded-md"
                                                    onclick="openEditModal(${data.data.id}, '${data.data.name}', '${data.data.description}','${data.data.image}')">
                                                    Update
                                                </button>
                                                <button class="text-white bg-[#4fd1c5] px-5 cursor-pointer py-2 rounded-md"
                                                    onclick="OpenDeleteModal(${data.data.id}, '${data.data.name}')">
                                                    Delete
                                                </button>
                                                `;
                            const index = allCategories.findIndex(cat => cat.id === data.data.id);
                            if (index !== -1) {
                                allCategories[index] = data.data;
                            }

                        } else {
                            showToast("Server is Down Try again later", "denger")
                        }
                    }).then(() => {
                        requestAnimationFrame(() => {
                            setTimeout(() => {
                                spinner.classList.add('hidden');
                                text.textContent = 'Update';
                                button.disabled = false;
                                closeModal();
                            }, 1000);
                        });
                    })
            }
        })
    </script>
</body>

</html>