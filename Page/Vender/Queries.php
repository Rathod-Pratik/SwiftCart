<?php   require __DIR__ . '/../../Componenets/Vender/VenderAuth.php'  ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwiftCart | Query Section</title>
     <?php include __DIR__ . '/../../Componenets/Other/Header.php'; ?>
</head>

<body>
 <?php require __DIR__ . '/../../Componenets/Vender/VenderNavbar.php' ?>
  <?php require __DIR__ . '/../../Componenets/Vender/VenderSideBar.php' ?>
    <div class="p-4 lg:ml-64 pt-20 bg-gray-100 min-h-[100vh]">
        <div class="flex justify-evenly gap-3 mb-6">
            <input
                class="border-[#4fd1c5] bg-white border-2 outline-none rounded-md px-4 py-2 w-[90%]"
                type="text"
                oninput="handleSearch(this.value)"
                placeholder="Search Query" />
            <button class="text-white bg-[#4fd1c5] px-5 cursor-pointer py-2 rounded-md">
                Search
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
                            Image
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Product Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Subject
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Description
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3">
                        </th>

                    </tr>
                </thead>
                <tbody id="Product-table-body">
                </tbody>
            </table>
        </div>
    </div>

    <script>
        let AllProduct = []

        function handleSearch(searchText) {
            const query = searchText.toLowerCase().trim();

            const filtered = AllProduct.filter(cat =>
                cat.product_name.toLowerCase().includes(query)
            );

            UpdateProductTableUI(filtered);
        }

        function FetchQuery() {
            const formData = new FormData()
            formData.append('action', 'fetch')
            fetch('/SwiftCart/AJAX/Query_ajax.php', {
                method: "POST",
                body: formData
            }).then(res => res.json()).then((res) => {
                if (res.success == true) {
                    AllProduct = res.data
                    UpdateProductTableUI(res.data)
                }
            })
        }

        function UpdateProductTableUI(products) {
            const tbody = document.getElementById('Product-table-body');
            tbody.innerHTML = '';

            if (!Array.isArray(products) || products.length < 1) {
                const row = document.createElement('tr');
                row.id = 'EmptyProductTable'
                row.classList.add('bg-white', 'border-b', 'border-gray-200', 'hover:bg-gray-50');
                row.innerHTML = `
                    <td colspan="9" class="text-center py-4 text-gray-500">
                        No Query found.
                    </td>
                `;
                tbody.appendChild(row);
                return;
            }

            products.forEach(product => {
                const tr = document.createElement('tr');
                tr.classList.add('bg-white', 'border-b', 'border-gray-200', 'hover:bg-gray-50');
                const productJSON = encodeURIComponent(JSON.stringify(product));
                tr.id = `Query-${product.id}`
                tr.innerHTML = `
                    <td class="px-6 py-4">${product.id}</td>
                    <td class="px-6 py-4"><img src='${product.image}' class='w-8 h-8' /></td>
                    <th class="px-2 py-4">${product.product_name}</th>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">${product.user_name}</th>
                    <td class="px-6 py-4">${product.subject}</td>
                    <td class="px-6 py-4">${product.message}</td>
                    <td class="px-6 py-4">${product.resolve ? "Resolved":"Pending"}</td>
                     <td class="px-6 py-4">
                                    <button onclick="ResolveRequest(${product.id}, this)"  ${product.resolve ? 'disabled' : ''} class="cursor-pointer text-white flex items-center justify-center bg-[#4fd1c5] border border-transparent hover:border-[#4fd1c5] hover:text-[#4fd1c5] hover:bg-white font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors duration-200" title="Delete">
                                            <div id="Spinner-${product.id}" class="hidden w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin"></div>
                                        Resolve
                                    </button>
                                </td>
                `;
                tbody.appendChild(tr);
            });
        }

        function ResolveRequest(productid, buttonEl) {
            const formData = new FormData();
            formData.append('action', 'resolve');
            formData.append('id', productid);

            // Show spinner
            document.getElementById(`Spinner-${productid}`).classList.remove("hidden");

            fetch('/SwiftCart/AJAX/Query_ajax.php', {
                method: "POST",
                body: formData
            }).then(res => res.json()).then((res) => {
                document.getElementById(`Spinner-${productid}`).classList.add("hidden");

                if (res.success === true) {
                    showToast("Query Resolved Successfully", 'success');

                    // Disable button visually and functionally
                    buttonEl.disabled = true;
                    const statusTd = buttonEl.closest("tr").querySelector("td:nth-last-child(2)");
                    if (statusTd) statusTd.textContent = "Resolved";
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            FetchQuery()
        })
    </script>
</body>

</html>