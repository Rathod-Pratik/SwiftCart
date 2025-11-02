<?php
require '../Database/db.php';
header('Content-Type: application/json');

$action = $_POST['action'];

if ($action == 'login') {

    try {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $checkStmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $checkStmt->execute([':email' => $email]);
        $user = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            echo json_encode([
                'success' => false,
                'notfound' => true,
                'message' => 'User not found'
            ]);
            exit();
        }

        if (!password_verify($password, $user['password'])) {
            echo json_encode([
                'success' => false,
                'incurrect' => true,
                'message' => 'Password is incorrect'
            ]);
            exit();
        }

        $userData = [
            'id' => $user['id'],
            'email' => $user['email'],
            'userType' => $user['usertype'],
        ];

        if ($user['usertype'] == 'customer') {
            setcookie("authToken", json_encode($userData), [
                'expires' => time() + 86400,
                'path' => '/',
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Strict'
            ]);
            echo json_encode([
                'redirect' => 'customer',
                'success' => true,
                'message' => 'Login successfully'
            ]);
            exit();
        } elseif ($user['usertype'] == 'admin') {
            setcookie("AdminToken", json_encode($userData), [
                'expires' => time() + 86400,
                'path' => '/',
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Strict'
            ]);
            echo json_encode([
                'redirect' => 'admin',
                'success' => true,
                'message' => 'Login successfully'
            ]);
            exit();
        } else {
            setcookie("venderToken", json_encode($userData), [
                'expires' => time() + 86400,
                'path' => '/',
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Strict'
            ]);
            echo json_encode([
                'redirect' => 'vender',
                'success' => true,
                'message' => 'Login successfully'
            ]);
            exit();
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit();
    }
} else if ($action == 'signup') {
    try {
        $email = $_POST['email'];
        $name = $_POST['name'];
        $password = $_POST['Password'];
        $userType = 'customer';

        $checkStmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $checkStmt->execute([':email' => $email]);

        if ($checkStmt->fetch()) {
            echo json_encode([
                'success' => false,
                'alreadyExist' => true
            ]);
            exit();
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (name, email, password, usertype) 
            VALUES (:name, :email, :password, :userType)";
        $stmt = $pdo->prepare($sql);

        $result = $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $hashedPassword,
            ':userType' => $userType
        ]);
        if ($result) {
            $lastId = $pdo->lastInsertId();

            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
            $stmt->execute([':id' => $lastId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $userData = [
                    'id' => $user['id'],
                    'role' => 'customer',
                    'email' => $user['email'],
                    'userType' => $user['usertype'],
                ];

                setcookie("authToken", json_encode($userData), [
                    'expires' => time() + 86400,
                    'path' => '/',
                    'secure' => true,
                    'httponly' => true,
                    'samesite' => 'Strict'
                ]);

                echo json_encode([
                    'success' => true,
                    'created' => true
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'error' => 'User not found after insert'
                ]);
            }
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit();
    }
}
