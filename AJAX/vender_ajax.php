<?php
require '../Database/db.php';
header('Content-Type: application/json');
session_start();

$action = $_POST['action'] ?? '';

try {
    if ($action == 'create') {
        $name = $_POST['name'];
        $account_no = $_POST['account_no'];
        $ifsc_code = $_POST['ifsc_code'];
        $mobile = $_POST['mobile'];
        $company_name = $_POST['company_name'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $password = $_POST['password'];

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $create = $pdo->prepare('INSERT INTO users (name,account_no,ifsc_code,mobile,email,address,company_name,password,userType) VALUES (?,?,?,?,?,?,?,?,?)');

        $success =  $create->execute([
            $name,
            $account_no,
            $ifsc_code,
            $mobile,
            $email,
            $address,
            $company_name,
            $hashedPassword,
            'vender'
        ]);
        echo json_encode([
            'success' => $success,
            'action' => 'create',
            'name' => $name,
            'account_no' => $account_no,
            'ifsc_code' => $ifsc_code,
            'mobile' => $mobile,
            'email' => $email,
            'address' => $address,
            'company_name' => $company_name,
            'password' => $password
        ]);
    } elseif ($action === 'update') {
        $name = $_POST['name'];
        $account_no = $_POST['account_no'];
        $ifsc_code = $_POST['ifsc_code'];
        $mobile = $_POST['mobile'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $company_name = $_POST['company_name'];
        $password = $_POST['password'];

        $hashedPassword=password_hash($password,PASSWORD_DEFAULT);

        $update = $pdo->prepare('
                 UPDATE users 
                 SET 
                     name = :name,
                     account_no = :account_no,
                     ifsc_code = :ifsc_code,
                     mobile = :mobile,
                     address = :address,
                     company_name = :company_name 
                     password = :password
                 WHERE email = :email
                ');

        // Execute with bound values
        $success = $update->execute([
            ':name' => $name,
            ':account_no' => $account_no,
            ':ifsc_code' => $ifsc_code,
            ':mobile' => $mobile,
            ':address' => $address,
            ':company_name' => $company_name,
            ':email' => $email,
            ':password' => $hashedPassword
        ]);

        echo json_encode([
            'success' => $success,
            'action' => 'update'
        ]);
    } elseif ($action == 'block') {
       $id = $_POST['id'];
        $block = $pdo->prepare("UPDATE users SET status ='block' WHERE id = :id");

        $success = $block->execute([
            ':id' => $id
        ]);
            echo json_encode([
            'success' => $success,
            'action' => 'block'
        ]);
    } elseif ($action == 'active') {
        $id = $_POST['id'];
        $active = $pdo->prepare("UPDATE users SET status ='active' WHERE id = :id");

        $success = $active->execute([
            ':id' => $id
        ]);
            echo json_encode([
            'success' => $success,
            'action' => 'active'
        ]);
    } elseif ($action == 'message') {
        $email = $_POST['SendTo'];
        $reason = $_POST['Subject'];
        $userMessage = $_POST['message'];

        $message = $pdo->prepare('INSERT INTO contact (email,reason,message,sender)
        VALUES(:email,:reason,:message,:sender)');
        $success = $message->execute([
            ':email' => $email,
            ':reason' => $reason,
            ':message' => $userMessage,
            ':sender' => 'admin'
        ]);
        echo json_encode([
            'success' => $success,
            'action' => 'message'
        ]);
    } elseif ($action == 'fetch') {
        $vender = $pdo->query("SELECT * FROM users WHERE userType='vender' ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode([
            'success' => true,
            'action' => 'fetch',
            'vender' => $vender
        ]);
        exit;
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
