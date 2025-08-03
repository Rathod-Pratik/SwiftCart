<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwiftCart | Rating Section</title>
    <?php include __DIR__ . '/../../Componenets/Header.php'; ?>
</head>

<body>
    <?php require __DIR__ . '/../../Componenets/VenderNavbar.php' ?>
    <?php require __DIR__ . '/../../Componenets/VenderSideBar.php' ?>
    <div class="p-4 lg:ml-64 pt-20 bg-gray-100 min-h-[100vh]">
        <div class="flex justify-evenly gap-3 mb-6">
            <input
                class="border-[#4fd1c5] bg-white border-2 outline-none rounded-md px-4 py-2 w-[90%]"
                type="text"
                oninput="handleSearch(this.value)"
                placeholder="Search Product" />
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
                            Product Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Rating
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Comment
                        </th>
                    </tr>
                </thead>
                <tbody id="Product-table-body">
                </tbody>
            </table>
        </div>
    </div>
    <script>
        let AllProduct = [];

        function handleSearch(searchText) {
            const query = searchText.toLowerCase().trim();

            const filtered = AllProduct.filter(cat =>
                cat.product_name.toLowerCase().includes(query)
            );

            UpdateProductTableUI(filtered);
        }

        function FetchReview() {
            const formData = new FormData()
            formData.append('action', 'fetch')
            fetch('/SwiftCart/AJAX/Review_ajax.php', {
                method: "POST",
                body: formData
            }).then(res => res.json()).then((res) => {
                if (res.success == true) {
                    AllProduct = res.data
                    UpdateProductTableUI(res.data)
                }
            })
        }

        function UpdateProductTableUI(reviews) {
            const tbody = document.getElementById('Product-table-body');
            tbody.innerHTML = '';

            if (!Array.isArray(reviews) || reviews.length < 1) {
                const row = document.createElement('tr');
                row.id = 'EmptyProductTable';
                row.classList.add('bg-white', 'border-b', 'border-gray-200', 'hover:bg-gray-50');
                row.innerHTML = `
            <td colspan="6" class="text-center py-4 text-gray-500">
                No reviews found.
            </td>
            `;
                tbody.appendChild(row);
                return;
            }

            reviews.forEach(review => {
                const tr = document.createElement('tr');
                tr.classList.add('bg-white', 'border-b', 'border-gray-200', 'hover:bg-gray-50');
                tr.id = `Review-${review.id}`;

                tr.innerHTML = `
            <td class="px-6 py-4">${review.id}</td>
            <td class="px-6 py-4">
                <div class="flex items-center gap-2">
                    <img src="${review.image}" alt="${review.product_name}" class="w-10 h-10 rounded-md object-cover">
                    <span class="font-medium text-gray-900">${review.product_name}</span>
                </div>
            </td>
            <td class="px-6 py-4">${review.user_name}</td>
            <td class="px-6 py-4">${review.rating} ⭐</td>
            <td class="px-6 py-4">${review.comment}</td>
             `;
                tbody.appendChild(tr);
            });
        }
        document.addEventListener('DOMContentLoaded', function() {
            FetchReview()
        })
    </script>
</body>

</html>