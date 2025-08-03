<?php

require __DIR__ . '/../../Database/db.php';
require __DIR__ . '/../../Database/Function.php';

$table = 'orders';

$createSQL = " CREATE TABLE IF NOT EXISTS orders (
    id SERIAL PRIMARY KEY,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    phone VARCHAR(20),
    email VARCHAR(100),
    company VARCHAR(100),
    country VARCHAR(100),
    city VARCHAR(100),
    address TEXT,
    productid INTEGER REFERENCES product(id) ON DELETE CASCADE,
    userid INTEGER REFERENCES users(id) ON DELETE CASCADE,
    quantity INTEGER,
    zip_code VARCHAR(20),
    total_amount DECIMAL(10, 2),
    payment_status VARCHAR(20) DEFAULT 'Completed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
";
checkAndCreateTable($pdo, $table, $createSQL);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwiftCart | Checkout</title>
    <?php include __DIR__ . '/../../Componenets/Header.php'; ?>
</head>

<body>
    <?php require __DIR__ . '/../../Componenets/Navbar.php' ?>
    <section class="px-4 py-12 max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold mb-8 text-[#234445]">Checkout</h1>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Billing Form -->
            <form id="ConfirmForm" class="bg-white rounded-2xl shadow-lg p-8 lg:col-span-2">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                        <input type="text" name="first_name" placeholder="First Name" class="block w-full rounded-lg border border-gray-300 p-3 focus:ring-2 focus:ring-yellow-500 focus:outline-none" required />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></label>
                        <input type="text" placeholder="Last Name" class="block w-full rounded-lg border border-gray-300 p-3 focus:ring-2 focus:ring-yellow-500 focus:outline-none" name="last_name" required />
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Phone Number <span class="text-red-500">*</span></label>
                        <div class="flex items-center rounded-lg border border-gray-300 overflow-hidden">
                            <input name="phone" type="text" placeholder="Phone Number" class="flex-1 p-3 outline-none" required />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                        <input name="email" type="email" placeholder="Email Address" class="block w-full rounded-lg border border-gray-300 p-3 focus:ring-2 focus:ring-yellow-500 focus:outline-none" required />
                    </div>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Company Name <span class="text-gray-400">(Optional)</span></label>
                    <input name="company" type="text" placeholder="Company Name" class="block w-full rounded-lg border border-gray-300 p-3 focus:ring-2 focus:ring-yellow-500 focus:outline-none" />
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Country <span class="text-red-500">*</span></label>
                    <input name="country" type="text" placeholder="Country" class="block w-full rounded-lg border border-gray-300 p-3 focus:ring-2 focus:ring-yellow-500 focus:outline-none" required />
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">City <span class="text-red-500">*</span></label>
                        <input type="text" name="city" placeholder="City" class="block w-full rounded-lg border border-gray-300 p-3 focus:ring-2 focus:ring-yellow-500 focus:outline-none" required />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Address <span class="text-red-500">*</span></label>
                        <input type="text" name="address" placeholder="Address" class="block w-full rounded-lg border border-gray-300 p-3 focus:ring-2 focus:ring-yellow-500 focus:outline-none" required />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">ZIP Code <span class="text-red-500">*</span></label>
                        <input type="text" name="zip_code" placeholder="ZIP Code" class="block w-full rounded-lg border border-gray-300 p-3 focus:ring-2 focus:ring-yellow-500 focus:outline-none" required />
                    </div>
                </div>
                <button type="submit" id="submitbutton" disabled class="hidden cursor-pointer flex-1 bg-yellow-600 hover:bg-yellow-700 text-white py-3 rounded-lg font-semibold transition">Confirm Payment</button>

            </form>

            <!-- Order Summary -->
            <div class="bg-white rounded-2xl shadow-lg p-8 h-fit">
                <h2 class="text-xl font-semibold text-[#234445] mb-6">Order Summary</h2>
                <div class="space-y-4 text-sm text-gray-700">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span id="subtotal" class="font-semibold text-gray-900"></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Shipping</span>
                        <span class="font-semibold text-green-600">Free</span>
                    </div>
                    <div class="border-t border-gray-200 pt-4 flex justify-between text-base font-semibold">
                        <span>Total</span>
                        <span id="total" class="text-gray-900"></span>
                    </div>
                    <div class="mt-8 flex gap-4">
                        <button onclick="ConfirmPayment()" class="cursor-pointer flex-1 bg-yellow-600 hover:bg-yellow-700 text-white py-3 rounded-lg font-semibold transition">Confirm Payment</button>
                        <button onclick="window.location.href='/SwiftCart/cart'" class="cursor-pointer flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-3 rounded-lg font-semibold transition">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php include __DIR__ . '/../../Componenets/Footer.php'; ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let cart = JSON.parse(localStorage.getItem('cart')) || {};
            let subtotal = 0;

            Object.values(cart).forEach(p => {
                subtotal += p.price * p.quantity;
            });

            document.getElementById('subtotal').textContent = "$" + subtotal.toFixed(2);
            document.getElementById('total').textContent = "$" + subtotal.toFixed(2);
        })

        function ConfirmPayment() {
            const btn = document.getElementById('submitbutton');
            btn.disabled = false;
            btn.click();
        }

        document.getElementById('ConfirmForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(e.target);

            formData.append('action','createOrder');

            const total = document.getElementById('total').textContent;
            const cleanedTotal = total.replace(/[^\d.-]/g, '');
            formData.append('total', cleanedTotal)

            const cartObject = JSON.parse(localStorage.getItem('cart') || '{}');

            const cartArray = Object.values(cartObject);

            formData.append('cart', JSON.stringify(cartArray));

            fetch('/SwiftCart/AJAX/Checkout_ajax.php',{
                method:"POST",
                body:formData
            }).then(res=>res.json()).then((res)=>{
                if(res.status == 'success'){
                    showToast("Order placed successfully.",'success')
                    setTimeout(()=>{
                        window.location.href = "/SwiftCart/Completeorder";
                        document.getElementById('CartLength').classList.add('hidden');
                        document.getElementById('ConfirmForm').reset();
                    },2000)
                }else{
                    showToast("message' => 'Something went wrong.",'danger')
                }
            })
        })
    </script>
</body>

</html>