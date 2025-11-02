<?php
require '../Database/db.php';
header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

if ($action == "FetchAdminData") {

    $Userstmt = $pdo->query("SELECT COUNT(*) AS total_users FROM users WHERE userType = 'customer'");
    $users = $Userstmt->fetch(PDO::FETCH_ASSOC)['total_users'];

    // Count total products
    $productstmt = $pdo->query("SELECT COUNT(*) AS total_products FROM product");
    $products = $productstmt->fetch(PDO::FETCH_ASSOC)['total_products'];

    // Count total orders
    $orderstmt = $pdo->query("SELECT COUNT(*) AS total_orders FROM orders"); // backticks for reserved word
    $orders = $orderstmt->fetch(PDO::FETCH_ASSOC)['total_orders'];

    // Total revenue (price * quantity)
    $amountstmt = $pdo->query("SELECT SUM(total_amount) AS total_amount FROM orders;");
    $amount = $amountstmt->fetch(PDO::FETCH_ASSOC)['total_amount'] ?? 0;

    // Send response
    echo json_encode([
        'user' => $users,
        'product' => $products,
        'order' => $orders,
        'amount' => $amount
    ]);
    exit;
} elseif ($action == "FetchVenderData") {
$userData = json_decode($_COOKIE['venderToken'] ?? '', true);
$id = $userData['id'];

$productstmt = $pdo->prepare("SELECT COUNT(*) AS total_products FROM product where venderid=?");
$productstmt->execute([$id]); // ✅ FIXED HERE
$products = $productstmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM orders o
    JOIN product p ON o.productid = p.id
    WHERE p.venderid = :venderid
");
$stmt->execute(['venderid' => $id]); // 🛠 Also fixed to use named param correctly
$order = $stmt->fetch(PDO::FETCH_ASSOC);

$payment = $pdo->prepare("
    SELECT SUM(o.total_amount)
    FROM orders o
    JOIN product p ON o.productid = p.id
    WHERE p.venderid = :venderid
");
$payment->execute(['venderid' => $id]);
$payments = $payment->fetch(PDO::FETCH_ASSOC);

echo json_encode([
    'amount'  => $payments,
    'order'   => $order,
    'product' => $products
]);

}
