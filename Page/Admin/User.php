<?php include '../../Componenets/AdminAuth.php' ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopizo | Admin</title>
    <?php include '../../Componenets/Header.php'; ?>
</head>

<body>
    <?php require '../../Componenets/AdminNavbar.php' ?>
    <?php require '../../Componenets/AdminSideBar.php' ?>

    <div class="p-4 lg:ml-64 pt-20 bg-gray-100 min-h-[100vh]">
        <div>
            <div class="flex justify-evenly gap-3 mb-6">
                <input
                    oninput="SearchUser(this.value)"
                    class="border-[#4fd1c5] bg-white border-2 outline-none rounded-md px-4 py-2 w-[90%]"
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
                    <tbody id="user-table-body">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        let allUser = [];

        function SearchUser(searchValue) {
            const query = searchValue.toLowerCase().trim();

            const filtered = allUser.filter(cat =>
                cat.name.toLowerCase().includes(query))

            updateTableUI(filtered)
        }

        function ActiveUser(id) {
            fetch('/SwiftCart/AJAX/user_ajax.php', {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        action: "unblock",
                        id: id,
                    })
                }).then(res => res.json())
                .then(data => {
                    if (data.success) {
                        FetchUser();
                    }
                });
        }

        function BlockUser(id) {
            fetch('/SwiftCart/AJAX/user_ajax.php', {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        action: "block",
                        id: id,
                    })
                }).then(res => res.json())
                .then(data => {
                    if (data.success) {
                        FetchUser();
                    }
                })
        }

        function FetchUser() {
            fetch('/SwiftCart/AJAX/user_ajax.php', {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'action=fetch'
                }).then(res => res.json())
                .then(data => {
                    console.log(data)
                    if (data.success) {
                        allUser = data.users;
                        updateTableUI(data.users);
                    }
                });
        }

        function updateTableUI(user) {
            const tbody = document.getElementById('user-table-body');
            tbody.innerHTML = '';
            const row = document.createElement('tr');
            if (user.length < 1) {
                row.innerHTML='<td colspan="7" class="text-center py-4 text-gray-500"> No Users are found.</td>'
                tbody.appendChild(row)

            } else {
                user.forEach(user => {
                    row.innerHTML = `
        <td class="px-6 py-4">${user.id}</td>
        <td class="px-6 py-4">${user.name}</td>
        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">${user.email}</th>
        <td class="px-6 py-4">${user.address == null ? '-' : user.address}</td>
        <td class="px-6 py-4">${user.mobile === null ? '-' : user.mobile}</td>
        <td class="px-6 py-4">${user.status}</td>
        ${
            user.status === 'active'
                ? `<td class="px-6 py-4">
                    <button 
                    onclick="BlockUser(${user.id})"
                    class="text-white flex items-center justify-center bg-red-500 border border-transparent hover:border-red-500  hover:text-red-500 hover:bg-white outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors duration-200">
                        Block
                    </button>
                  </td>`
                : `<td class="px-6 py-4">
                    <button 
                    onclick="ActiveUser(${user.id})"
                    class="text-white flex items-center justify-center bg-green-500 border border-transparent  hover:border-green-500 hover:text-green-500 hover:bg-white focus:outline-none font-medium rounded-lg  text-sm px-5 py-2.5 text-center transition-colors duration-200">
                        Active
                    </button>
                  </td>`
        }
    `;

                    tbody.appendChild(row);
                });

            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            FetchUser()
        })
    </script>
</body>

</html>