<?php
require '../Database/db.php';
header('Content-Type: application/json');

$action = $_POST['action'];
if (isset($_COOKIE['authToken'])) {
    $userData = json_decode($_COOKIE['authToken'], true);
} elseif (isset($_COOKIE['AdminToken'])) {
    $userData = json_decode($_COOKIE['AdminToken'], true);
} elseif (isset($_COOKIE['venderToken'])) {
    $userData = json_decode($_COOKIE['venderToken'], true);
} else {
    echo json_encode(['status' => 'unauthorized', 'message' => 'User not logged in or cookie missing']);
    exit;
}
if ($action == 'createOrder') {

    $cartItems = json_decode($_POST['cart'] ?? '[]', true);

    foreach ($cartItems as $item) {
        $data = [
            'first_name'   => $_POST['first_name'] ?? '',
            'last_name'    => $_POST['last_name'] ?? '',
            'phone'        => $_POST['phone'] ?? '',
            'email'        => $_POST['email'] ?? '',
            'company'      => $_POST['company'] ?? '',
            'country'      => $_POST['country'] ?? '',
            'city'         => $_POST['city'] ?? '',
            'address'      => $_POST['address'] ?? '',
            'zip_code'     => $_POST['zip_code'] ?? '',
            'total_amount' => $_POST['total'] ?? 0,
            'productid'    => $item['id'],
            'quantity'     => $item['quantity'],
            'userid'       => $userData['id'],
        ];

        $sql = "INSERT INTO orders (
        first_name, last_name, phone, email, company, country, city,
        address, zip_code, total_amount, productid, quantity, userid
    ) VALUES (
        :first_name, :last_name, :phone, :email, :company, :country, :city,
        :address, :zip_code, :total_amount, :productid, :quantity, :userid
    )";

        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute($data);
    }

    // Clear cart
    $stmt = $pdo->prepare("DELETE FROM cart WHERE userid = ?");
    $stmt->execute([$userData['id']]);

    echo json_encode([
        'status' => $result ? 'success' : 'error',
        'message' => $result ? 'Order placed successfully.' : 'Something went wrong.'
    ]);
} else if ($action == 'FetchOrder') {
    try {
        $stmt = $pdo->prepare("
                SELECT 
                    orders.*, 
                    product.product_name AS product_name, 
                    product.image AS product_image, 
                    product.price AS product_price 
                FROM orders 
                JOIN product ON orders.productid = product.id 
                WHERE orders.userid = ? 
                ORDER BY orders.created_at DESC
                ");

        $stmt->execute([$userData['id']]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['status' => 'success', 'data' => $orders]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else if ($action == 'fetchAllOrder') {
    try {
        $stmt = $pdo->query(" SELECT 
                    orders.*, 
                    product.product_name, 
                    product.category, 
                    product.price,
                    users.name
                FROM orders 
                JOIN product ON orders.productid = product.id 
                JOIN users ON orders.userid =users.id
                ORDER BY orders.created_at DESC");
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['status' => 'success', 'data' => $orders]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else if ($action == 'FetchVenderOrder') {
    try {
        $stmt = $pdo->prepare("
                SELECT 
                    orders.*, 
                    product.product_name, 
                    product.image, 
                    product.price
                FROM orders 
                JOIN product ON orders.productid = product.id 
                WHERE product.venderid = :venderid
                ORDER BY orders.created_at DESC
            ");

        $stmt->execute(['venderid' => $userData['id']]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['status' => 'success', 'data' => $orders]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
