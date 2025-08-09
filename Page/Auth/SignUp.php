<?php

ob_start();

if (isset($_COOKIE['venderToken'])) {
    header("Location: /SwiftCart/vender/dashboard");
    exit;
}
if (isset($_COOKIE['AdminToken'])) {
    header("Location: /SwiftCart/admin/dashboard");
    exit;
}
if (isset($_COOKIE['authToken'])) {
    header("Location: /SwiftCart/");
    exit;
}

require __DIR__ . '/../../Database/Function.php';
require __DIR__ . '/../../Database/db.php';

$tableName = 'users';

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

echo checkAndCreateTable($pdo, $tableName, $createSQL);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwiftCart | SignUp</title>
    <?php include __DIR__ . '/../../Componenets/Other/Header.php'; ?>
</head>

<body>
      <?php require __DIR__ . '/../../Componenets/Home/Navbar.php'; ?>
    <div class="min-h-[80vh] bg-[#e6f0f1] text-gray-900 flex justify-center">
        <div class="max-w-screen-xl m-0 sm:m-10 bg-white shadow sm:rounded-lg flex justify-center flex-1">
            <div data-aos="fade-right" class="flex-1 bg-[#b8d8da] text-center hidden lg:flex items-center justify-center">
                <div class="m-12 xl:m-16 w-full h-[420px] bg-contain bg-center bg-no-repeat flex items-center justify-center"
                    style="background-image: url('/SwiftCart/Image/Login.webp');">
                </div>
            </div>
            <div data-aos="fade-left" class="lg:w-1/2 xl:w-5/12 p-6 sm:p-12 flex items-center">
                <div class="w-full flex flex-col items-center">
                    <h1 class="text-2xl xl:text-3xl font-extrabold">
                        SignUp Now
                    </h1>
                    <div class="w-full flex-1 mt-8 flex justify-center items-center">
                        <form method="POST" id="SignUp" class="w-full max-w-xs">
                            <input type="hidden" value="signup" name="action">
                            <input
                                required
                                name='name'
                                minlength="5"
                                class="w-full mb-5 px-8 py-4 rounded-lg font-medium bg-gray-100 border border-gray-200 text-sm focus:outline-none focus:border-gray-400 focus:bg-white"
                                type="text" placeholder="Name" />
                            <input
                                required
                                name='email'
                                class="w-full px-8 py-4 rounded-lg font-medium bg-gray-100 border border-gray-200 text-sm focus:outline-none focus:border-gray-400 focus:bg-white"
                                type="email" placeholder="Email" />
                            <input
                                required
                                name='Password'
                                minlength="8"
                                class="w-full px-8 py-4 rounded-lg font-medium bg-gray-100 border border-gray-200 text-sm focus:outline-none focus:border-gray-400 focus:bg-white mt-5"
                                type="password" placeholder="Password" />

                            <input type="submit" value="Sign Up" class="bg-[#d09523] hover:bg-[#f4b942] cursor-pointer mt-5  font-semibold  text-gray-100 w-full py-4 rounded-lg  transition-all duration-300 ease-in-out flex items-center justify-center focus:shadow-outline focus:outline-none">

                            <button>
                                <span class="ml-3 text-white">
                                    Sign Up
                                </span>
                            </button>
                            <p class="mt-6 text-xs text-gray-600 text-center">
                                Already have Account ?
                                <a href="/SwiftCart/login" class="border-b border-gray-500 border-dotted">
                                    Login
                                </a>
                                now
                            </p>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <?php include __DIR__ . '/../../Componenets/Home/Footer.php'; ?>
    <script>
        document.getElementById('SignUp').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = e.target;
            const forData = new FormData(form);

            fetch('/SwiftCart/AJAX/Auth.php', {
                method: "POST",
                body: forData,
                credentials: "same-origin"
            }).then(res => res.json()).then((res) => {
                if (res.success == false && res.alreadyExist == true) {
                    showToast("User is already Exist with this Email", 'denger')
                    return;
                }
                if (res.success == true && res.created == true) {
                    showToast("SignUp successfully", 'success')
                    setTimeout(() => {
                        window.location.href = '/SwiftCart';
                    }, 1500);
                }
            })


        })
    </script>
</body>

</html>