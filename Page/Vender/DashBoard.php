<?php   require __DIR__ . '/../../Componenets/Vender/VenderAuth.php'  ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwiftCart | Dashboard</title>
     <?php include __DIR__ . '/../../Componenets/Other/Header.php'; ?>
</head>

<body>
    <?php require __DIR__ . '/../../Componenets/Vender/VenderNavbar.php' ?>
    <?php require __DIR__ . '/../../Componenets/Vender/VenderSideBar.php' ?>
    <div class="p-4 lg:ml-64 pt-20  max-w-7xl h-full mx-auto bg-gray-100">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white p-6 rounded-2xl shadow text-center">
                <p class="text-gray-500 mb-2">Total Revenue</p>
                <h2 class="text-2xl font-bold text-green-600 " id="totalamount">₹ 0</h2>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow text-center">
                <p class="text-gray-500 mb-2">Total Order</p>
                <h2 class="text-2xl font-bold text-purple-600" id="TotalOrder">0</h2>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow text-center">
                <p class="text-gray-500 mb-2">Total Products</p>
                <h2 class="text-2xl font-bold text-cyan-600" id="TotalProduct">0</h2>
            </div>
        </div>
    </div>

    <script>
        function FetchData() {
            const formData = new FormData();
            formData.append('action', 'FetchVenderData')
            fetch('../AJAX/DashBoard_ajax.php', {
                method: "POST",
                body: formData
            }).then(res => res.json()).then((res) => {
                document.getElementById('totalamount').textContent = res.amount.sum;
                document.getElementById('TotalOrder').textContent = res.order.count;
                document.getElementById('TotalProduct').textContent = res.product.total_products;

            })
        }
        document.addEventListener('DOMContentLoaded', function() {
            FetchData()
        })
    </script>
</body>

</html>