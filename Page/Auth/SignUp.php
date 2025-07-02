<?php
require '../../Database/Function.php'; 
require '../../Database/db.php';  

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

echo checkAndCreateTable($pdo, $tableName, $createSQL);

// Function to insert a new user
function insertUser($pdo, $name, $email, $password, $address, $userType) {

    // 1. Check if user with this email already exists
    $checkSql = "SELECT * FROM users WHERE email = :email";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([':email' => $email]);

    if ($checkStmt->fetch()) {
        return "❌ User already exists with this email.";
    }

    // 2. Hash the password before storing
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // 3. Insert new user
    $sql = "INSERT INTO users (name, email, password, address, userType) 
            VALUES (:name, :email, :password, :address, :userType)";
    $stmt = $pdo->prepare($sql);

    $result = $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':password' => $hashedPassword,
        ':address' => $address,
        ':userType' => $userType
    ]);

    if ($result) {
        return "✅ User inserted successfully.";
    } else {
        return "❌ Failed to insert user.";
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['Name'];
    $email = $_POST['Email'];
    $password = $_POST['Password'];
    $address = $_POST['Address'];
    $userType = 'customer'; 

    $HashPassword=password_hash($password)

    insertUser($pdo, $name, $email, $HashPassword, $address, $userType);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwiftCart | SignUp</title>
</head>
<body>
    <?php include '../../Componenets/Header.php'; ?>
    <?php include '../../Componenets/Navbar.php'; ?>
    <div class="min-h-[80vh] bg-[#e6f0f1] text-gray-900 flex justify-center">
    <div class="max-w-screen-xl m-0 sm:m-10 bg-white shadow sm:rounded-lg flex justify-center flex-1">
        <div class="flex-1 bg-[#b8d8da] text-center hidden lg:flex items-center justify-center">
            <div class="m-12 xl:m-16 w-full h-[420px] bg-contain bg-center bg-no-repeat flex items-center justify-center"
                style="background-image: url('../../Image/Login.webp');">
            </div>
        </div>
        <div class="lg:w-1/2 xl:w-5/12 p-6 sm:p-12 flex items-center">
            <div class="w-full flex flex-col items-center">
                <h1 class="text-2xl xl:text-3xl font-extrabold">
                    SignUp Now
                </h1>
                <div class="w-full flex-1 mt-8 flex justify-center items-center">
                    <div class="w-full max-w-xs">
                        <input
                            name='Name'
                            class="w-full mb-5 px-8 py-4 rounded-lg font-medium bg-gray-100 border border-gray-200 text-sm focus:outline-none focus:border-gray-400 focus:bg-white"
                            type="text" placeholder="Name" />
                        <input
                         name='Email'
                            class="w-full px-8 py-4 rounded-lg font-medium bg-gray-100 border border-gray-200 text-sm focus:outline-none focus:border-gray-400 focus:bg-white"
                            type="email" placeholder="Email" />
                        <input
                         name='Password'
                            class="w-full px-8 py-4 rounded-lg font-medium bg-gray-100 border border-gray-200 text-sm focus:outline-none focus:border-gray-400 focus:bg-white mt-5"
                            type="password" placeholder="Password" />
                        <button
                            class="bg-[#d09523] hover:bg-[#f4b942] cursor-pointer mt-5  font-semibold  text-gray-100 w-full py-4 rounded-lg  transition-all duration-300 ease-in-out flex items-center justify-center focus:shadow-outline focus:outline-none">
      
                            <span class="ml-3 text-white">
                                Sign Up
                            </span>
                        </button>
                        <p class="mt-6 text-xs text-gray-600 text-center">
                          Already have Account ?
                            <a href="./Login.php" class="border-b border-gray-500 border-dotted">
                                Login 
                            </a>
                            now
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
<?php include '../../Componenets/Footer.php'; ?>
</body>
</html>