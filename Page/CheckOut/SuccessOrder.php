<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Order Success</title>
      <?php include __DIR__ . '/../../Componenets/Other/Header.php'; ?>
</head>

<body>
    <?php require __DIR__ . '/../../Componenets/Home/Navbar.php' ?>
    <div class="bg-gradient-to-br from-teal-50 to-yellow-50 min-h-screen flex flex-col items-center justify-center py-10 px-4">
        <div class="flex flex-col items-center w-full max-w-lg">
            <div class="flex justify-center mb-6">
                <img src="./Image/Success.png" alt="Success Image" class="w-40 h-40 object-contain drop-shadow-lg rounded-full border-4 border-amber-200 bg-white" />
            </div>
            <div class="text-center">
                <h1 class="text-4xl font-extrabold text-[#234445] mb-2">Thank you for your purchase!</h1>
                <p class="text-gray-600 text-lg mb-2">Your order has been successfully processed.</p>
                <p class="text-gray-500 text-base">Here are your order details:</p>
            </div>
            <div class="w-full mt-8">
                <div class="bg-white border border-gray-100 rounded-2xl shadow-xl overflow-hidden">
                    <div class="bg-teal-700 text-white px-8 py-4 font-semibold text-xl rounded-t-2xl">Order Summary</div>
                    <div class="px-8 py-6 space-y-5 text-base">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700">Date</span>
                            <span class="font-bold text-gray-900" id="date"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700">Items Purchased</span>
                            <span class="font-bold text-gray-900" id="Items">4 Total Item</span>
                        </div>
                    </div>
                    <div class="border-t px-8 py-5 flex justify-between font-bold text-lg">
                        <span>Total</span>
                        <span class="text-amber-600" id="total"></span>
                    </div>
                    <div class="px-8 pb-8 pt-4">
                        <a href="/SwiftCart/order" class="block w-full text-center bg-amber-600 hover:bg-amber-700 text-white font-semibold py-3 rounded-full shadow transition duration-200">
                            View Order Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include __DIR__ . '/../../Componenets/Home/Footer.php'; ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date();
            const options = {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            document.getElementById('date').textContent = today.toLocaleDateString('en-US', options);

            let cart = JSON.parse(localStorage.getItem('cart')) || {};
            let subtotal = 0;

            Object.values(cart).forEach(p => {
                subtotal += p.price * p.quantity;
            });
            document.getElementById('total').textContent = "$" + subtotal.toFixed(2);

            const cartObject = JSON.parse(localStorage.getItem('cart') || '{}');
            const cartArray = Object.values(cartObject);
            document.getElementById('Items').textContent=`${cartArray.length} Total Item`

        })
    </script>
</body>

</html>