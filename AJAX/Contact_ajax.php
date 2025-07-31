<?php
require '../Database/db.php';
header('Content-Type: application/json');

$name    = $_POST['name'] ?? '';
$email   = $_POST['email'] ?? '';
$mobile  = $_POST['mobile'] ?? '';
$message = $_POST['message'] ?? '';
$sender  = 'customer';
$stmp=$pdo->prepare('INSERT INTO contact (name,email,message,sender,mobile) VALUES(?,?,?,?,?)');
$stmp->execute([
    $name,$email,$message,$sender, $mobile
]);
echo json_encode([
    'Success' => true
]);
?>