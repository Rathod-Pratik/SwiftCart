<?php
require __DIR__ . '/../../Database/Function.php';        // Database connection
require __DIR__ . '/../../Database/db.php';  // Utility functions

$tableName = 'users';

$createSQL = " CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    photo TEXT,
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

checkAndCreateTable($pdo, $tableName, $createSQL);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwiftCart | Login</title>
</head>

<body>
    <?php include __DIR__ . '/../../Componenets/Header.php'; ?>
    <?php include __DIR__ . '/../../Componenets/Navbar.php'; ?>
    <div class="min-h-[80vh] bg-[#e6f0f1] text-gray-900 flex justify-center">
        <div class="max-w-screen-xl m-0 sm:m-10 bg-white shadow sm:rounded-lg flex justify-center flex-1">
            <div class="flex-1 bg-[#b8d8da] text-center hidden lg:flex">
                <div class="m-12 xl:m-16 w-full bg-contain bg-center bg-no-repeat"
                    style="background-image: url('/SwiftCart/Image/Login.webp');">
                </div>
            </div>
            <div class="lg:w-1/2 xl:w-5/12 p-6 sm:p-12">
                <div class="mt-12 flex flex-col items-center">
                    <h1 class="text-2xl xl:text-3xl font-extrabold">
                        Login Now
                    </h1>
                    <div class="w-full flex-1 mt-8">
                        <form class="mx-auto my-auto max-w-xs" method="POST" id="login">
                            <input type="hidden" value="login" name="action">
                            <input
                                name="email"
                                class="w-full px-8 py-4 rounded-lg font-medium bg-gray-100 border border-gray-200 placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white"
                                type="email" placeholder="Email" />
                            <input
                                name="password"
                                class="w-full px-8 py-4 rounded-lg font-medium bg-gray-100 border border-gray-200 placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white mt-5"
                                type="password" placeholder="Password" />
                            <button
                                type="submit"
                                class="bg-[#d09523] hover:bg-[#f4b942] cursor-pointer mt-5  font-semibold  text-gray-100 w-full py-4 rounded-lg  transition-all duration-300 ease-in-out flex items-center justify-center focus:shadow-outline focus:outline-none">

                                <span class="ml-3 text-white">
                                    Login
                                </span>
                            </button>
                            <p class="mt-6 text-xs text-gray-600 text-center">
                                Don't have Account ?
                                <a href="/SwiftCart/signup" class="border-b border-gray-500 border-dotted">
                                    Sign Up
                                </a>
                                now
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include __DIR__ . '/../../Componenets/Footer.php'; ?>
    <script>
        document.getElementById('login').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = e.target;
            const forData = new FormData(form);

            fetch('/SwiftCart/AJAX/Auth.php', {
                method: "POST",
                body: forData,
                credentials: "same-origin"
            }).then(res => res.json()).then((res) => {
                if (res.success == false && res.notfound == true) {
                    showToast("User not exist with this email", 'denger')
                    return;
                }
                if (res.success == false && res.incurrect == true) {
                    showToast("Please Enter currect Password", 'denger')
                    return;
                }
                if (res.success == true) {
                    showToast("Login successfully", 'success')

                    setTimeout(() => {
                        if (res.redirect == 'customer') {
                            window.location.href = '/SwiftCart/home';
                        }
                        if (res.redirect == 'admin') {
                            window.location.href = '/SwiftCart/admin/dashboard';
                        }
                        if (res.redirect == 'vender') {
                            window.location.href = '/SwiftCart/vender/dashboard';
                        }
                    }, 1500);
                }
            })


        })
    </script>
</body>

</html>