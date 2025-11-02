<?php include __DIR__ . '/../../Componenets/Admin/AdminAuth.php' ?>

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
     <?php include __DIR__ . '/../../Componenets/Other/Header.php'; ?>
</head>
<script>

</script>

<body>
    <?php require __DIR__ . '/../../Componenets/Admin/AdminNavbar.php' ?>
    <?php require __DIR__ . '/../../Componenets/Admin/AdminSideBar.php' ?>

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
        <?php include __DIR__ . '/../../Componenets/Admin/Category_modal.php' ?>
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