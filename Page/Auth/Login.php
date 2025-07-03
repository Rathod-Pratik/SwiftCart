<?php
require '../../Database/Function.php';        // Database connection
require '../../Database/db.php';  // Utility functions

$tableName = 'users';

$createSQL = "
    CREATE TABLE IF NOT EXISTS users (
        id SERIAL PRIMARY KEY,
        name VARCHAR(100),
        email VARCHAR(150) UNIQUE,
        password VARCHAR(20) UNIQUE,
        address VARCHAR(50),
        userType VARCHAR(10) CHECK (userType IN ('customer', 'vender', 'admin')),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
";

checkAndCreateTable($pdo, $tableName, $createSQL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function Login($pdo, $email, $password)
{
    // 1. Check if user exists
    $checkSql = "SELECT * FROM users WHERE email = :email";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([':email' => $email]);
    $user = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $_SESSION['message'] = "User not found with this email.";
        $_SESSION['msg_type'] = "danger";
        header("Location: Login.php");
        exit();
    }

    // 2. Verify password
    if (!password_verify($password, $user['password'])) {
        $_SESSION['message'] = "Incorrect password.";
        $_SESSION['msg_type'] = "danger";
        header("Location: Login.php");
        exit();
    }

    // 3. Set secure cookie (excluding raw password)
    $userData = [
        'email' => $user['email'],
        'userType' => $user['userType'],
        'password' => $user['password']
    ];

    if ($user['usertype'] == 'customer') {
        setcookie("authToken", json_encode($userData), [
            'expires' => time() + 86400,
            'path' => '/',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);
        $_SESSION['message'] = "Login successful.";
        $_SESSION['msg_type'] = "success";

        // 4. Set success message
        header("Location: /SwiftCart/Page/Home/Home.php");
        exit();
    } elseif ($user['usertype'] == 'admin') {
        setcookie("AdminToken", json_encode($userData), [
            'expires' => time() + 86400,
            'path' => '/',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);

        $_SESSION['message'] = "Login successful.";
        $_SESSION['msg_type'] = "success";

        // 4. Set success message
       header("Location: /SwiftCart/Page/Admin/DashBoard.php");
        exit();
    } else {
        setcookie("venderToken", json_encode($userData), [
            'expires' => time() + 86400,
            'path' => '/',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);

        $_SESSION['message'] = "Login successful.";
        $_SESSION['msg_type'] = "success";
        header("Location: /SwiftCart/Page/Vender/DashBoard.php");
        exit();
    }


    $_SESSION['message'] = "Login successful.";
    $_SESSION['msg_type'] = "success";
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['Email'];
    $password = $_POST['Password'];
    // Validate password length
    if (strlen($password) < 8) {
        $_SESSION['message'] = "Password must be at least 8 characters long.";
        $_SESSION['msg_type'] = "danger";
        return;
    }

    Login($pdo, $email, $password);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwiftCart | Login</title>
</head>

<body>
    <?php include '../../Componenets/Header.php'; ?>
    <?php include '../../Componenets/Navbar.php'; ?>
    <div class="min-h-[80vh] bg-[#e6f0f1] text-gray-900 flex justify-center">
        <div class="max-w-screen-xl m-0 sm:m-10 bg-white shadow sm:rounded-lg flex justify-center flex-1">
            <div class="flex-1 bg-[#b8d8da] text-center hidden lg:flex">
                <div class="m-12 xl:m-16 w-full bg-contain bg-center bg-no-repeat"
                    style="background-image: url('../../Image/Login.webp');">
                </div>
            </div>
            <div class="lg:w-1/2 xl:w-5/12 p-6 sm:p-12">
                <div class="mt-12 flex flex-col items-center">
                    <h1 class="text-2xl xl:text-3xl font-extrabold">
                        Login Now
                    </h1>
                    <div class="w-full flex-1 mt-8">
                        <form class="mx-auto my-auto max-w-xs" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                            <input
                            name="Email"
                                class="w-full px-8 py-4 rounded-lg font-medium bg-gray-100 border border-gray-200 placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white"
                                type="email" placeholder="Email" />
                            <input
                            name="Password"
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
                                <a href="./SignUp.php" class="border-b border-gray-500 border-dotted">
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
    <?php include '../../Componenets/Footer.php'; ?>
</body>

</html>