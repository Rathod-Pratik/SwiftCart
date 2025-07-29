<?php include __DIR__ . '/../../Componenets/AdminAuth.php' ?>

<?php

require __DIR__ . '/../../Database/db.php';
require __DIR__ . '/../../Database/Function.php';

$table = 'vender';

$createSQL = " CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(150) UNIQUE,
    password VARCHAR(100) UNIQUE,
    account_no VARCHAR(15),
    ifsc_code VARCHAR(15),
    company_name VARCHAR(30),
    address VARCHAR(50),
    mobile VARCHAR(10),
    userType VARCHAR(10) CHECK (userType IN ('customer', 'vender', 'admin')),
    status VARCHAR(7) DEFAULT 'active' CHECK (status IN ('active' , 'block')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);";
checkAndCreateTable($pdo, $table, $createSQL);

$ContactTable = 'contact';

$ContactSQL = " CREATE TABLE IF NOT EXISTS contact (
    id SERIAL PRIMARY KEY,
    email VARCHAR(150),
    reason VARCHAR(250),
    message TEXT,
    sender VARCHAR(10) CHECK (sender IN ('customer','admin')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);";
checkAndCreateTable($pdo, $ContactTable, $ContactSQL);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopizo | Admin</title>
    <?php include __DIR__ . '/../../Componenets/Header.php'; ?>
</head>

<body>
    <?php require __DIR__ . '/../../Componenets/AdminNavbar.php' ?>
    <?php require __DIR__ . '/../../Componenets/AdminSideBar.php' ?>

    <div class="p-4 lg:ml-64 pt-20 bg-gray-100 min-h-[100vh]">
        <div>
            <div class="flex justify-evenly gap-3 mb-6">
                <input
                    class="border-[#4fd1c5] bg-white border-2 outline-none rounded-md px-4 py-2 w-[90%]"
                    type="text"
                    oninput="handleSearch(this.value)"
                    placeholder="Search Vender" />
                <button onclick="OpenModal()" class="text-white bg-[#4fd1c5] px-5 cursor-pointer py-2 rounded-md">
                    New
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
                            <th scope="col" class="px-6 py-3">

                            </th>
                        </tr>
                    </thead>
                    <tbody id="vender-table-body">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div
        id="myModal"
        tabindex="-1"
        aria-hidden="true"
        class="backdrop-blur-sm hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full transform scale-95 opacity-0 translate-y-[-20px] transition-all duration-300 ease-out">
        <div class="relative  w-full max-w-md max-h-full bg-white rounded-2xl shadow-lg">
            <div class="relative ">
                <div
                    class="flex items-center justify-between p-4 md:p-5 rounded-t bgcolor">
                    <h3 class="text-lg font-semibold text-white" id="modalTitle">
                        Create New Vender
                    </h3>
                    <button
                        type="button"
                        onclick="CloseModal()"
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
                <form class="p-4 md:p-5" method="post" id="VenderForm">
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
                                id="name"
                                class="inputcolor outline-none border border-[#4fd1c5] text-gray-900 text-sm rounded-lg focus:border-[#4fd1c5] block w-full p-2.5"
                                placeholder="Name" />
                        </div>
                        <div class="col-span-1">
                            <label
                                for="account_no"
                                class="block mb-2 text-sm font-medium textcolor">Account no</label>
                            <input
                                required=""
                                type="text"
                                name="account_no"
                                id="account_no"
                                class="inputcolor border border-[#4fd1c5] text-gray-900 text-sm rounded-lg focus:border-[#4fd1c5] block w-full p-2.5"
                                placeholder="Account number" />
                        </div>
                        <div class="col-span-1">
                            <label
                                for="ifsc_code"
                                class="block mb-2 text-sm font-medium textcolor">IFSC</label>
                            <input
                                required=""
                                type="text"
                                name="ifsc_code"
                                id="ifsc_code"
                                class="inputcolor outline-none border border-[#4fd1c5] text-gray-900 text-sm rounded-lg focus:border-[#4fd1c5] block w-full p-2.5"
                                placeholder="IFSC code" />
                        </div>
                        <div class="col-span-1">
                            <label
                                for="mobile"
                                class="block mb-2 text-sm font-medium textcolor">Moblle no</label>
                            <input
                                required
                                type="tel"
                                name="mobile"
                                id="mobile"
                                pattern="^[0-9]{10}$"
                                maxlength="10"
                                inputmode="numeric"
                                class="inputcolor outline-none border border-[#4fd1c5] text-gray-900 text-sm rounded-lg focus:border-[#4fd1c5] block w-full p-2.5"
                                placeholder="Enter 10-digit mobile number" />
                        </div>
                        <div class="col-span-1">
                            <label
                                for="company_name"
                                class="block mb-2 text-sm font-medium textcolor">Company</label>
                            <input
                                required=""
                                type="text"
                                name="company_name"
                                id="company_name"
                                class="inputcolor outline-none border border-[#4fd1c5] text-gray-900 text-sm rounded-lg focus:border-[#4fd1c5] block w-full p-2.5"
                                placeholder="Company Name" />
                        </div>
                        <div class="col-span-2">
                            <label
                                for="email"
                                class="block mb-2 text-sm font-medium textcolor">Email</label>
                            <input
                                required=""
                                type="email"
                                name="email"
                                id="email"
                                class="inputcolor outline-none border border-[#4fd1c5] text-gray-900 text-sm rounded-lg focus:border-[#4fd1c5] block w-full p-2.5"
                                placeholder="Email" />
                        </div>
                        <div class="col-span-2">
                            <label
                                for="address"
                                class="block mb-2 text-sm font-medium textcolor">Address</label>
                            <input
                                required=""
                                type="text"
                                name="address"
                                id="address"
                                class="inputcolor outline-none border border-[#4fd1c5] text-gray-900 text-sm rounded-lg focus:border-[#4fd1c5] block w-full p-2.5"
                                placeholder="Address" />
                        </div>
                        <div class="col-span-2">
                            <label
                                for="password"
                                class="block mb-2 text-sm font-medium textcolor">Password</label>
                            <input
                                required=""
                                type="text"
                                name="password"
                                id="password"
                                class="inputcolor outline-none border border-[#4fd1c5] text-gray-900 text-sm rounded-lg focus:border-[#4fd1c5] block w-full p-2.5"
                                placeholder="Password" />
                        </div>
                    </div>
                    <button
                        type="submit"
                        id="modalSubmitBtn"
                        class="text-white p-4 cursor-pointer inline-flex items-center gap-2 bg-[#4fd1c5] border border-transparent hover:border-[#4fd1c5] hover:text-[#4fd1c5] hover:bg-white outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center duration-300 transition-colors">

                        <!-- Spinner inherits text color -->
                        <div id="spinner" class="hidden w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin"></div>
                        <span id="modalBtnText">Create</span>
                    </button>
            </div>
            </form>
        </div>
    </div>

    <div
        id="myMessageModal"
        tabindex="-1"
        aria-hidden="true"
        class="backdrop-blur-sm hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full transform scale-95 opacity-0 translate-y-[-20px] transition-all duration-300 ease-out">
        <div class="relative  w-full max-w-md max-h-full bg-white rounded-2xl shadow-lg">
            <div class="relative ">
                <div
                    class="flex items-center justify-between p-4 md:p-5 rounded-t bgcolor">
                    <h3 class="text-lg font-semibold text-white" id="modalTitle">
                        Send Message
                    </h3>
                    <button
                        type="button"
                        onclick="CloseMessageModal()"
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
                <form class="p-4 md:p-5" method="post" id="VenderMessageForm">
                    <input type="hidden" name="SendTo" id="SendTo" />
                    <input type="hidden" name="action" id="MessageformAction" />
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">
                            <label
                                class="block mb-2 text-sm font-medium textcolor">Send To</label>
                            <p id="MessageName" class="inputcolor outline-none border border-[#4fd1c5] text-gray-900 text-sm rounded-lg focus:border-[#4fd1c5] block w-full p-2.5"></p>
                        </div>
                        <div class="col-span-2">
                            <label
                                for="Subject"
                                class="block mb-2 text-sm font-medium textcolor">Subject</label>
                            <input
                                required=""
                                type="text"
                                name="Subject"
                                id="Subject"
                                class="inputcolor outline-none border border-[#4fd1c5] text-gray-900 text-sm rounded-lg focus:border-[#4fd1c5] block w-full p-2.5"
                                placeholder="Subject" />
                        </div>
                        <div class="col-span-2">
                            <label
                                for="message"
                                class="block mb-2 text-sm font-medium textcolor">Message</label>
                            <textarea required=""
                                type="text"
                                name="message"
                                id="message"
                                class="inputcolor outline-none border border-[#4fd1c5] text-gray-900 text-sm rounded-lg focus:border-[#4fd1c5] block w-full p-2.5"
                                placeholder="message"></textarea>
                        </div>
                    </div>
                    <button
                        type="submit"
                        id="MessageButton"
                        class="text-white p-4 cursor-pointer inline-flex items-center gap-2 bg-[#4fd1c5] border border-transparent hover:border-[#4fd1c5] hover:text-[#4fd1c5] hover:bg-white outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center duration-300 transition-colors">

                        <!-- Spinner inherits text color -->
                        <div id="Messagespinner" class="hidden w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin"></div>
                        <span id="MessagemodalBtnText">Message</span>
                    </button>
            </div>
            </form>
        </div>
    </div>
    </div>
    <script>
        let AllVender = []

         function handleSearch(searchText) {
            const query = searchText.toLowerCase().trim();

            const filtered = AllVender.filter(cat =>
                cat.name.toLowerCase().includes(query)
            );

            updateTableUI(filtered);
        }

        function OpenModal() {
            document.getElementById('myModal').classList.remove('hidden')
            document.getElementById('myModal').classList.add('flex')
            document.getElementById("formAction").value = "create";
            setTimeout(() => {
                document.getElementById('myModal').classList.remove("scale-95", "opacity-0", "translate-y-[-20px]");
                document.getElementById('myModal').classList.add("scale-100", "opacity-100", "translate-y-0");
            }, 10);
        }

        function OpenMessageModal(name, email) {
            document.getElementById('MessageformAction').value = 'message'
            document.getElementById('MessageName').textContent = name;
            document.getElementById('SendTo').value = email;
            document.getElementById('myMessageModal').classList.remove('hidden')
            document.getElementById('myMessageModal').classList.add('flex')

             setTimeout(() => {
                document.getElementById('myMessageModal').classList.remove("scale-95", "opacity-0", "translate-y-[-20px]");
                document.getElementById('myMessageModal').classList.add("scale-100", "opacity-100", "translate-y-0");
            }, 10);
        }

        function CloseMessageModal() {
            const modal = document.getElementById('myMessageModal');
            modal.classList.remove("scale-100", "opacity-100", "translate-y-0");
            modal.classList.add("scale-95", "opacity-0", "translate-y-[-20px]");
            setTimeout(() => {
                modal.classList.remove("flex");
                modal.classList.add("hidden");
                document.getElementById('VenderMessageForm').reset();
            }, 300);
        }

        function CloseModal() {
            const modal = document.getElementById('myModal');
            modal.classList.remove("scale-100", "opacity-100", "translate-y-0");
            modal.classList.add("scale-95", "opacity-0", "translate-y-[-20px]");
            setTimeout(() => {
                modal.classList.remove("flex");
                modal.classList.add("hidden");
                document.getElementById('VenderForm').reset();
            }, 300);
        }

        function OpenEditModal(name, account_no, ifsc_code, mobile, company_name, email, address) {
            setTimeout(() => {
                document.getElementById('myModal').classList.remove("scale-95", "opacity-0", "translate-y-[-20px]");
                document.getElementById('myModal').classList.add("scale-100", "opacity-100", "translate-y-0");
            }, 10);

            document.getElementById('myModal').classList.remove('hidden')
            document.getElementById('myModal').classList.add('flex')
            document.getElementById("formAction").value = "update";
            document.getElementById('modalBtnText').textContent = 'Update';
            document.getElementById('password').removeAttribute('required');

            document.getElementById("name").value = name;
            document.getElementById("account_no").value = account_no;
            document.getElementById("ifsc_code").value = ifsc_code;
            document.getElementById("mobile").value = mobile;
            document.getElementById("company_name").value = company_name;
            document.getElementById("email").value = email;
            document.getElementById("address").value = address == undefined ? '' : address;
            document.getElementById("password").placeholder = 'New Password';
        }

        function updateTableUI(value) {
            const tbody = document.getElementById('vender-table-body');
            tbody.innerHTML = '';
            const row = document.createElement('tr');

            if (value.length < 1) {
                row.innerHTML = '<td colspan="10" id="emptyTable" class="text-center py-4 text-gray-500"> No Vender are found.</td>'
                tbody.appendChild(row)

            } else {
                value.forEach(value => {
                    const row = document.createElement('tr');
                    row.id = `row-${value.id}`
                    row.innerHTML = ` <td class="px-6 py-4">${value.id}</td>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">${value.name}</td>
                            <td class="px-6 py-4">${value.account_no}</td>
                            <td class="px-6 py-4">${value.ifsc_code}</td>
                            <td class="px-6 py-4">${value.mobile}</td>
                            <td class="px-6 py-4">${value.email}</td>
                            <td class="px-6 py-4">${value.address}</td>
                            <td class="px-6 py-4">${value.company_name}</td>
                            <td class="px-6 py-4">${value.status}</td>
                             <td class="flex flex-row gap-2 mt-3">
                                <button 
                                id="auth-${value.id}"
                                  onclick="${value.status == 'block' ?  `unblock(${value.id})` :`blockVender(${value.id})` }"
                                  class="cursor-pointer text-white ${value.status === 'active' 
                                    ? 'bg-red-500 hover:border-red-500 hover:text-red-500 hover:bg-white' 
                                    : 'bg-green-500 hover:border-green-500 hover:text-green-500 hover:bg-white'} 
                                    border border-transparent font-medium rounded-md text-xs px-5 py-2 transition-colors duration-200 flex flex-row gap-2">
                                <div id="BlockSpin${value.id}" class="hidden w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin"></div>
                             <span id="authButtonText-${value.id}">    ${value.status === 'active' ? 'Block' : 'Active'} </span>
                                </button>
                               <button onclick='OpenEditModal(
                                    ${JSON.stringify(value.name)},
                                    ${JSON.stringify(value.account_no)},
                                    ${JSON.stringify(value.ifsc_code)},
                                    ${JSON.stringify(value.mobile)},
                                    ${JSON.stringify(value.company_name)},
                                    ${JSON.stringify(value.email)},
                                    ${JSON.stringify(value.address)}
                                    )' class="text-white bg-[#4fd1c5] px-5 cursor-pointer py-2 rounded-md"
                                    id="editbutton-${value.id}"
                                    >
                                     Update
                                </button>

                                <button onclick="OpenMessageModal(
                                  '${value.name}',
                                  '${value.email}'
                                )" class="text-white bg-[#4fd1c5] px-5 cursor-pointer py-2 rounded-md" id="messagebtn-${value.id}">
                                  <svg viewBox="-2 -2.5 24 24" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMinYMin" width="20" height="20">
                                    <path fill="white" d="M9.378 12H17a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1 1 1 0 0 1 1 1v3.013L9.378 12zM3 0h14a3 3 0 0 1 3 3v8a3 3 0 0 1-3 3h-6.958l-6.444 4.808A1 1 0 0 1 2 18.006V14a2 2 0 0 1-2-2V3a3 3 0 0 1 3-3z"/>
                                   </svg>
                                 </button>
                                </td>
                    `;
                    tbody.appendChild(row);
                });

            }
        }

        function FetchVender() {
            fetch('/SwiftCart/AJAX/vender_ajax.php', {
                method: "POST",
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'action=fetch'
            }).then(res => res.json()).then(data => {
                if (data.success) {
                    AllVender = data.vender;
                    updateTableUI(data.vender)
                } else {
                    showToast("Server is Down Try again later", "denger")
                }
            })
        }

        function blockVender(id) {
            document.getElementById(`BlockSpin${id}`).classList.remove('hidden');

            fetch('/SwiftCart/AJAX/vender_ajax.php', {
                    method: 'POST',
                    body: new URLSearchParams({
                        action: 'block',
                        id: id
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`BlockSpin${id}`).classList.add('hidden');
                        showToast("User Blocked Successfully", "danger");

                        const row = document.getElementById(`row-${id}`);
                        row.cells[8].textContent = 'Blocked';

                        let btn = document.getElementById(`auth-${id}`);
                        btn.setAttribute('onclick', `unblock(${id})`);

                        btn.classList.add(
                            'bg-green-500',
                            'hover:border-green-500',
                            'hover:text-green-500',
                            'hover:bg-white'
                        );
                        document.getElementById(`authButtonText-${id}`).textContent = 'Active';
                    } else {
                        showToast("Server is Down Try again later", "denger")
                    }
                });
        }

        function unblock(id) {
            document.getElementById(`BlockSpin${id}`).classList.remove('hidden');

            fetch('/SwiftCart/AJAX/vender_ajax.php', {
                    method: 'POST',
                    body: new URLSearchParams({
                        action: 'active',
                        id: id
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`BlockSpin${id}`).classList.add('hidden');

                        const row = document.getElementById(`row-${id}`);
                        row.cells[8].textContent = 'Active';

                        let btn = document.getElementById(`auth-${id}`);
                        btn.setAttribute('onclick', `blockVender(${id})`);
                        document.getElementById(`authButtonText-${id}`).textContent = 'Block';
                        btn.classList.add(
                            'bg-red-500',
                            'hover:border-red-500',
                            'hover:text-red-500',
                            'hover:bg-white'
                        );


                        showToast("User Unblocked Successfully", "success");
                    } else {
                        showToast("Server is Down Try again later", "denger")
                    }
                });
        }

        document.addEventListener('DOMContentLoaded', function() {
            FetchVender();
        });
        document.getElementById('VenderForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const actionValue = document.getElementById('formAction').value;
            if (actionValue == "create") {
                const spinner = document.getElementById('spinner')
                const text = document.getElementById('modalBtnText')
                const button = document.getElementById('modalSubmitBtn')
                spinner.classList.remove('hidden');
                text.textContent = 'Creating...';
                button.disabled = true;

                const form = e.target;
                const formData = new FormData(form);
                fetch("/SwiftCart/AJAX/vender_ajax.php", {
                        method: "POST",
                        body: formData,
                    })
                    .then((res) => res.json())
                    .then((data) => {
                        if (data.success) {
                            showToast("Vendor's account created successfully", "success");
                            const emptyTable = document.getElementById('emptyCategorytable');
                            if (emptyTable) row.remove()
                            const row = document.createElement('tr');
                            row.id = `row-${data.data.id}`;
                            row.innerHTML = `
                               <td class="px-6 py-4">${data.data.id}</td>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">${data.data.name}</td>
                            <td class="px-6 py-4">${data.data.account_no}</td>
                            <td class="px-6 py-4">${data.data.ifsc_code}</td>
                            <td class="px-6 py-4">${data.data.mobile}</td>
                            <td class="px-6 py-4">${data.data.email}</td>
                            <td class="px-6 py-4">${data.data.address}</td>
                            <td class="px-6 py-4">${data.data.company_name}</td>
                            <td class="px-6 py-4">${data.data.status}</td>
                             <td class="flex flex-row gap-2 mt-3">
                                <button 
                                  onclick="${data.data.status == 'block' ?  `unblock(${data.data.id})` :`blockVender(${data.data.id})` }"
                                  class="cursor-pointer text-white ${data.data.status === 'active' 
                                    ? 'bg-red-500 hover:border-red-500 hover:text-red-500 hover:bg-white' 
                                    : 'bg-green-500 hover:border-green-500 hover:text-green-500 hover:bg-white'} 
                                    border border-transparent font-medium rounded-md text-xs px-5 py-2 transition-colors duration-200 flex flex-row gap-2">
                                <div id="BlockSpin${value.id}" class="hidden w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin"></div>
                                 ${value.status === 'active' ? 'Block' : 'Active'}
                                </button>
                               <button onclick='OpenEditModal(
                                    ${JSON.stringify(data.data.name)},
                                    ${JSON.stringify(data.data.account_no)},
                                    ${JSON.stringify(data.data.ifsc_code)},
                                    ${JSON.stringify(data.data.mobile)},
                                    ${JSON.stringify(data.data.company_name)},
                                    ${JSON.stringify(data.data.email)},
                                    ${JSON.stringify(data.data.address)}
                                    )' class="text-white bg-[#4fd1c5] px-5 cursor-pointer py-2 rounded-md">
                                     Update
                                </button>

                                <button onclick="OpenMessageModal(
                                  '${data.data.name}',
                                  '${data.data.email}'
                                )" class="text-white bg-[#4fd1c5] px-5 cursor-pointer py-2 rounded-md">
                                  <svg viewBox="-2 -2.5 24 24" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMinYMin" width="20" height="20">
                                    <path fill="white" d="M9.378 12H17a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1 1 1 0 0 1 1 1v3.013L9.378 12zM3 0h14a3 3 0 0 1 3 3v8a3 3 0 0 1-3 3h-6.958l-6.444 4.808A1 1 0 0 1 2 18.006V14a2 2 0 0 1-2-2V3a3 3 0 0 1 3-3z"/>
                                   </svg>
                                 </button>
                                </td>
                                `;
                            document.getElementById('vender-table-body').appendChild(row);
                            AllVender.push(data.data);
                        } else {
                            showToast("Server is Down Try again later", "denger")
                        }
                    }).then(() => {
                        spinner.classList.add('hidden');
                        text.textContent = 'Create';
                        button.disabled = false;
                        CloseModal();
                    })
            } else if (actionValue == 'update') {
                const spinner = document.getElementById('spinner')
                const text = document.getElementById('modalBtnText')
                const button = document.getElementById('modalSubmitBtn')
                spinner.classList.remove('hidden');
                text.textContent = 'Updating...';
                button.disabled = true;

                const form = e.target;
                const formData = new FormData(form);
                fetch("/SwiftCart/AJAX/vender_ajax.php", {
                        method: "POST",
                        body: formData,
                    })
                    .then((res) => res.json())
                    .then((data) => {
                        if (data.success) {
                            showToast("Vender's Details updated successfully", 'success');
                            const row = document.getElementById(`row-${data.data.id}`);
                            row.cells[1].textContent = data.data.name
                            row.cells[2].textContent = data.data.account_no
                            row.cells[3].textContent = data.data.ifsc_code
                            row.cells[4].textContent = data.data.mobile
                            row.cells[5].textContent = data.data.email
                            row.cells[6].textContent = data.data.address
                            row.cells[7].textContent = data.data.company_name
                            const index = AllVender.findIndex(cat => cat.id === data.data.id);
                            if (index !== -1) {
                                AllVender[index] = data.data;
                            }                        
                        } else {
                            showToast("Server is Down Try again later", "denger")
                        }
                    }).then(() => {
                        spinner.classList.add('hidden');
                        text.textContent = 'Create';
                        button.disabled = false;
                        CloseModal();

                    })
            }
        })

        document.getElementById('VenderMessageForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const spinner = document.getElementById('Messagespinner');
            const text = document.getElementById('MessagemodalBtnText');
            const button = document.getElementById('MessageButton');

            spinner.classList.remove('hidden');
            text.textContent = 'Sending...';
            button.disabled = true;

            const form = e.target;
            const formData = new FormData(form);

            fetch("/SwiftCart/AJAX/vender_ajax.php", {
                    method: "POST",
                    body: formData
                })
                .then((res) => res.json())
                .then((data) => {
                    if (data.success) {
                        showToast("Message sent to Vender successfully", "success");
                        form.reset();
                    } else {
                        showToast("Server is Down Try again later", "denger")
                        console.log(data)
                    }
                })
                .catch((err) => {
                    console.error(err);
                })
                .finally(() => {
                    spinner.classList.add('hidden');
                    text.textContent = 'Message';
                    button.disabled = false;
                    CloseMessageModal();
                });
        });
    </script>
</body>

</html>