<?php include '../../Componenets/Header.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopizo | Admin</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <?php require '../../Componenets/AdminNavbar.php' ?>
    <?php require '../../Componenets/AdminSideBar.php' ?>

    <div class="p-4 lg:ml-64 pt-20  max-w-7xl mx-auto">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-10">
            <div class="bg-white p-6 rounded-2xl shadow text-center">
                <p class="text-gray-500 mb-2">Total Revenue</p>
                <h2 class="text-2xl font-bold text-green-600">₹ 50000</h2>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow text-center">
                <p class="text-gray-500 mb-2">Total Users</p>
                <h2 class="text-2xl font-bold text-blue-600">50</h2>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow text-center">
                <p class="text-gray-500 mb-2">Total Order</p>
                <h2 class="text-2xl font-bold text-purple-600">50</h2>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow text-center">
                <p class="text-gray-500 mb-2">Total Products</p>
                <h2 class="text-2xl font-bold text-cyan-600">50</h2>
            </div>
        </div>
        <div class="bg-gray-100 font-sans p-6">


            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Left: Bar Chart -->
                <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-xl p-6 shadow-lg">
                    <h2 class="text-white text-lg font-semibold mb-2">Active Users</h2>
                    <p class="text-green-400 text-sm mb-4">(+23) than last week</p>
                    <canvas id="barChart" class="w-full h-48"></canvas>
                    <div class="grid grid-cols-4 text-center text-white mt-6 text-sm">
                        <div>
                            <p class="text-[#4fd1c5] font-bold">👤</p>
                            <p>32,984</p>
                            <p class="text-xs text-gray-400">Users</p>
                        </div>
                        <div>
                            <p class="text-[#4fd1c5] font-bold">🖱️</p>
                            <p>2.42m</p>
                            <p class="text-xs text-gray-400">Clicks</p>
                        </div>
                        <div>
                            <p class="text-[#4fd1c5] font-bold">💵</p>
                            <p>3,400$</p>
                            <p class="text-xs text-gray-400">Sales</p>
                        </div>
                        <div>
                            <p class="text-[#4fd1c5] font-bold">📦</p>
                            <p>320</p>
                            <p class="text-xs text-gray-400">Items</p>
                        </div>
                    </div>
                </div>

                <!-- Right: Area Chart -->
                <div class="bg-white rounded-xl p-6 shadow-lg">
                    <h2 class="text-gray-800 text-lg font-semibold mb-2">Sales overview</h2>
                    <p class="text-green-500 text-sm mb-4">(+5) more in 2021</p>
                    <canvas id="areaChart" class="w-full h-48"></canvas>
                </div>
            </div>
        </div>


        <h1 class="text-2xl font-bold  p-2">Recent Orders</h1>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 ">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Id
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Product name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            UserId
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Category
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Price
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Quentity
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Total Amount
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="bg-white border-b  border-gray-200 hover:bg-gray-50 ">
                        <td class="px-6 py-4">
                            1
                        </td>
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
                            Apple MacBook Pro 17"
                        </th>
                        <td class="px-6 py-4">
                            901561625
                        </td>
                        <td class="px-6 py-4">
                            Laptop
                        </td>
                        <td class="px-6 py-4">
                            $2999
                        </td>
                        <td class="px-6 py-4">
                            2
                        </td>
                        <td class="px-6 py-4">
                            6000
                        </td>
                    </tr>
                    <tr class="bg-white border-b  border-gray-200 hover:bg-gray-50 ">
                        <td class="px-6 py-4">
                            1
                        </td>
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
                            Apple MacBook Pro 17"
                        </th>
                        <td class="px-6 py-4">
                            901561625
                        </td>
                        <td class="px-6 py-4">
                            Laptop
                        </td>
                        <td class="px-6 py-4">
                            $2999
                        </td>
                        <td class="px-6 py-4">
                            2
                        </td>
                        <td class="px-6 py-4">
                            6000
                        </td>
                    </tr>
                    <tr class="bg-white border-b  border-gray-200 hover:bg-gray-50 ">
                        <td class="px-6 py-4">
                            1
                        </td>
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
                            Apple MacBook Pro 17"
                        </th>
                        <td class="px-6 py-4">
                            901561625
                        </td>
                        <td class="px-6 py-4">
                            Laptop
                        </td>
                        <td class="px-6 py-4">
                            $2999
                        </td>
                        <td class="px-6 py-4">
                            2
                        </td>
                        <td class="px-6 py-4">
                            6000
                        </td>
                    </tr>
                    <tr class="bg-white border-b  border-gray-200 hover:bg-gray-50 ">
                        <td class="px-6 py-4">
                            1
                        </td>
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
                            Apple MacBook Pro 17"
                        </th>
                        <td class="px-6 py-4">
                            901561625
                        </td>
                        <td class="px-6 py-4">
                            Laptop
                        </td>
                        <td class="px-6 py-4">
                            $2999
                        </td>
                        <td class="px-6 py-4">
                            2
                        </td>
                        <td class="px-6 py-4">
                            6000
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
   
    <script>
        // Bar Chart
    const barCtx = document.getElementById('barChart').getContext('2d');
    new Chart(barCtx, {
      type: 'bar',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'],
        datasets: [{
          data: [200, 300, 150, 400, 500, 350, 450, 180],
          backgroundColor: '#ffffff',
          borderRadius: 10,
          barThickness: 12
        }]
      },
      options: {
        plugins: {
          legend: { display: false }
        },
        scales: {
          x: {
            grid: { display: false },
            ticks: { color: '#cbd5e1' }
          },
          y: {
            grid: { color: 'rgba(255,255,255,0.1)' },
            ticks: { color: '#cbd5e1', stepSize: 100 }
          }
        }
      }
    });

    // Area Chart
    const areaCtx = document.getElementById('areaChart').getContext('2d');
    const grad1 = areaCtx.createLinearGradient(0, 0, 0, 400);
    grad1.addColorStop(0, 'rgba(79, 209, 197, 0.5)');
    grad1.addColorStop(1, 'rgba(79, 209, 197, 0.05)');

    const grad2 = areaCtx.createLinearGradient(0, 0, 0, 400);
    grad2.addColorStop(0, 'rgba(45, 55, 72, 0.4)');
    grad2.addColorStop(1, 'rgba(45, 55, 72, 0.05)');

    new Chart(areaCtx, {
      type: 'line',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [
          {
            label: '2021',
            data: [200, 150, 250, 300, 350, 450, 400, 370, 330, 390, 300, 430],
            fill: true,
            backgroundColor: grad1,
            borderColor: '#4fd1c5',
            tension: 0.4,
            pointRadius: 0
          },
          {
            label: '2020',
            data: [300, 400, 280, 290, 320, 310, 330, 270, 230, 280, 310, 260],
            fill: true,
            backgroundColor: grad2,
            borderColor: '#2d3748',
            tension: 0.4,
            pointRadius: 0
          }
        ]
      },
      options: {
        plugins: {
          legend: { display: false }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: { stepSize: 100 },
            grid: { color: 'rgba(0,0,0,0.05)' }
          },
          x: {
            grid: { display: false }
          }
        }
      }
    });
    </script>

</body>

</html>