<?php include '../../Componenets/Header.php'; ?>
<?php include '../../Componenets/AdminAuth.php' ?>

<?php
require '../../Database/Function.php';        // Database connection
require '../../Database/db.php';  // Utility functions

$tableName = 'category';

$createSQL = "
    CREATE TABLE IF NOT EXISTS category (
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
</head>
<script>

</script>

<body>
    <?php require '../../Componenets/AdminNavbar.php' ?>
    <?php require '../../Componenets/AdminSideBar.php' ?>

    <div class="p-4 lg:ml-64 pt-20 bg-gray-100 min-h-[100vh]">
        <div class="flex justify-evenly gap-3 mb-6">
            <input
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
                        <th scope="col" class="px-6 py-3">Name</th>
                        <th scope="col" class="px-6 py-3">Description</th>
                        <th scope="col" class="px-6 py-3">Edit</th>
                        <th scope="col" class="px-6 py-3">Delete</th>
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
        class="backdrop-blur-sm hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
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
                        data-modal-hide="popup-modal"
                        onclick="DeleteCategory()"
                        type="button"
                        class="text-white cursor-pointer bg-red-600 hover:bg-red-800 outline-none  font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                        Yes, I'm sure
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

    <!-- Modal for Create/Update Category -->
    <div
        id="myModal"
        tabindex="-1"
        aria-hidden="true"
        class="backdrop-blur-sm hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
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
                            <label
                                for="name"
                                class="block mb-2 text-sm font-medium textcolor">Name</label>
                            <input
                            required=""
                                type="text"
                                name="name"
                                id="modal_name"
                                class="inputcolor border border-[#4fd1c5] text-gray-900 text-sm rounded-lg focus:border-[#4fd1c5] block w-full p-2.5"
                                placeholder="Category name"
                                 />
                        </div>
                        <div class="col-span-2">
                            <label
                                for="description"
                                class="block mb-2 text-sm font-medium textcolor">Description</label>
                            <textarea
                             required=""
                                name="description"
                                id="modal_description"
                                rows="4"
                                class="block p-2.5 w-full text-sm text-gray-900 inputcolor rounded-lg border border-[#4fd1c5] focus:border-[#4fd1c5]"
                                placeholder="Category description"></textarea>
                        </div>
                    </div>
                    <button
                        type="submit"
                        class="text-white cursor-pointer inline-flex items-center bg-[#4fd1c5] hover:border hover:border-[#4fd1c5] hover:text-[#4fd1c5] hover:bg-white outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors duration-200">
                        <svg
                            class="me-1 -ms-1 w-5 h-5"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span id="modalBtnText">Create</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        let deleteCategoryId = null;

        function openModal() {
            document.getElementById("categoryForm").reset();
            document.getElementById("formAction").value = "create";
            document.getElementById("modalTitle").textContent =
                "Create New Category";
            document.getElementById("modalBtnText").textContent = "Create";
            document.getElementById("edit_id").value = "";
            document.getElementById("myModal").classList.remove("hidden");
            document.getElementById("myModal").classList.add("flex");
        }

        function OpenDeleteModal(id, name) {
            deleteCategoryId = id;
            document.getElementById(
                "categoryName"
            ).textContent = `Are you sure you want to delete ${name} Category?`;
            document.getElementById("popup-modal").classList.remove("hidden");
            document.getElementById("popup-modal").classList.add("flex");
        }

        function CloseDeleteModal() {
            document.getElementById("popup-modal").classList.add("hidden");
        }

        function openEditModal(id, name, description) {
            document.getElementById("myModal").classList.remove("hidden");
            document.getElementById("myModal").classList.add("flex");
            document.getElementById("formAction").value = "update";
            document.getElementById("modalTitle").textContent = "Edit Category";
            document.getElementById("modalBtnText").textContent = "Update";
            document.getElementById("edit_id").value = id;
            document.getElementById("modal_name").value = name;
            document.getElementById("modal_description").value = description;
        }

        function closeModal() {
            document.getElementById("myModal").classList.remove("flex");
            document.getElementById("myModal").classList.add("hidden");
        }

        function fetchCategories() {
            fetch('category_ajax.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'action=fetch'
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        updateTableUI(data.categories);
                    }
                });
        }

        function updateTableUI(categories) {
            const tbody = document.getElementById('category-table-body');
            tbody.innerHTML = '';

            categories.forEach(cat => {
                const row = document.createElement('tr');
                row.innerHTML = `
                                 <td class="px-6 py-4">${cat.id}</td>
                                 <th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">${cat.name}</th>
                                 <td class="px-6 py-4">${cat.description}</td>
                                 <td class="px-6 py-4">
                                     <button class="cursor-pointer edit-button cursor-pointer"
                                         onclick="openEditModal(${cat.id}, '${cat.name}', '${cat.description}')">
                                         <img src="/SwiftCart/Image/Edit.svg" class="w-5 h-5 inline" />
                                     </button>
                                 </td>
                                 <td class="px-6 py-4">
                                     <button class="cursor-pointer delete-button text-red-600 hover:text-red-800 cursor-pointer"
                                         onclick="OpenDeleteModal(${cat.id}, '${cat.name}')">
                                         <img src="/SwiftCart/Image/Delete.svg" class="w-5 h-5 inline" />
                                     </button>
                                 </td>
                                 `;
                tbody.appendChild(row);
            });

        }

        function DeleteCategory() {
            if (!deleteCategoryId) return;
            fetch("category_ajax.php", {
                    method: "POST",
                    body: new URLSearchParams({
                        action: "delete",
                        id: deleteCategoryId,
                    }),
                })
                .then((res) => res.json())
                .then((data) => {
                    if (data.success) {
                        return fetchCategories()
                    }
                }).then(() => {
                    requestAnimationFrame(() => {
                        setTimeout(() => {
                            CloseDeleteModal()
                        }, 1000);
                    });

                });
        }

        document.addEventListener('DOMContentLoaded', function() {
            fetchCategories();
        });

        // AJAX for create category
        document.getElementById("categoryForm").addEventListener("submit", function(e) {
            e.preventDefault();
            const actionValue = document.getElementById("formAction").value;
            if (actionValue == "create") {
                const form = e.target;
                const formData = new FormData(form);
                fetch("category_ajax.php", {
                        method: "POST",
                        body: formData,
                    })
                    .then((res) => res.json())
                    .then((data) => {
                        if (data.success) {
                            return fetchCategories();
                        }
                    }).then(() => {
                        requestAnimationFrame(() => {
                            setTimeout(() => {
                                closeModal();
                            }, 1000);
                        });
                    })
            } else if (actionValue == "update") {
                const form = e.target;
                const formData = new FormData(form);
                fetch("category_ajax.php", {
                        method: "POST",
                        body: formData,
                    })
                    .then((res) => res.json())
                    .then((data) => {
                        if (data.success) {
                            return fetchCategories()
                        }
                    }).then(() => {
                        requestAnimationFrame(() => {
                            setTimeout(() => {
                                closeModal();
                            }, 1000);
                        });
                    })
            }

        });
    </script>
</body>

</html>