<?php   require __DIR__ . '/../../Componenets/Vender/VenderAuth.php'  ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwiftCart | Dashboard</title>
     <?php include __DIR__ . '/../../Componenets/Other/Header.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <?php require __DIR__ . '/../../Componenets/Vender/VenderNavbar.php' ?>
    <?php require __DIR__ . '/../../Componenets/Vender/VenderSideBar.php' ?>
    <div class="p-4 lg:ml-64 pt-20  max-w-7xl mx-auto bg-gray-100">
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
        <div class="bg-gray-100 font-sans pb-6">
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
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-6 text-sm">
                        <div class="bg-gray-50 rounded-lg p-3 flex flex-col items-center">
                            <span class="text-[#4fd1c5] text-lg font-bold">$2,300</span>
                            <span class="text-gray-500">Jan Sales</span>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3 flex flex-col items-center">
                            <span class="text-[#4fd1c5] text-lg font-bold">$1,800</span>
                            <span class="text-gray-500">Feb Sales</span>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3 flex flex-col items-center">
                            <span class="text-[#4fd1c5] text-lg font-bold">$2,750</span>
                            <span class="text-gray-500">Mar Sales</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function FetchData() {
            const formData = new FormData();
            formData.append('action', 'FetchVenderData')
            fetch('/SwiftCart/AJAX/DashBoard_ajax.php', {
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
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#cbd5e1'
                        }
                    },
                    y: {
                        grid: {
                            color: 'rgba(255,255,255,0.1)'
                        },
                        ticks: {
                            color: '#cbd5e1',
                            stepSize: 100
                        }
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
                datasets: [{
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
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 100
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>