<?php include __DIR__ . '/../../Componenets/Admin/AdminAuth.php' ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopizo | Admin</title>
     <?php include __DIR__ . '/../../Componenets/Other/Header.php'; ?>
</head>

<body>
       <?php require __DIR__ . '/../../Componenets/Admin/AdminNavbar.php' ?>
   <?php require __DIR__ . '/../../Componenets/Admin/AdminSideBar.php' ?>

    <div class="p-4 lg:ml-64 pt-20 bg-gray-100 min-h-[100vh]">
        <div class="flex justify-evenly gap-3 mb-6">
            <input
                class="border-[#4fd1c5] bg-white border-2 outline-none rounded-md px-4 py-2 w-[90%]"
                type="text"
                oninput="handleSearch(this.value)"
                placeholder="Search Contact" />
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
                            Email
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Number
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Message
                        </th>
                    </tr>
                </thead>
                <tbody id="contact-table-body">
                </tbody>
            </table>
        </div>
    </div>
    <script>
        let allContact = []

           function handleSearch(searchText) {
            const query = searchText.toLowerCase().trim();

            const filtered = allContact.filter(cat =>
                cat.name.toLowerCase().includes(query)
            );

            UpdateContactTable(filtered);
        }

        function UpdateContactTable(data){
            const tbody = document.getElementById('contact-table-body');
            tbody.innerHTML = '';

            if (!Array.isArray(data) || data.length < 1) {
                const row = document.createElement('tr');
                row.id = 'EmptyProductTable';
                row.classList.add('bg-white', 'border-b', 'hover:bg-gray-50');
                row.innerHTML = `
            <td colspan="6" class="text-center py-4 text-gray-500">
                No contact found.
            </td>
            `;
                tbody.appendChild(row);
                return;
            }

            data.forEach(data => {
                const tr = document.createElement('tr');
                tr.classList.add('bg-white', 'border-b', 'hover:bg-gray-50');
                tr.id = `data-${data.id}`;

                tr.innerHTML = `
                        <td class="px-6 py-4">${data.id}</td>
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">${data.name}</td>
                        <td class="px-6 py-4">${data.email}</td>
                        <td class="px-6 py-4">${data.mobile}</td>
                        <td class="px-6 py-4">${data.message}</td>
             `;
                tbody.appendChild(tr);

               
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const formData = new FormData();
            formData.append('action', 'fetch')

            fetch('/SwiftCart/AJAX/Contact_ajax.php', {
                method: "POST",
                body: formData
            }).then(res => res.json()).then((res) => {
                if (res.Success) {
                    allContact = res.contact;
                    UpdateContactTable(res.contact)
                }
            })
        })
    </script>
</body>

</html>