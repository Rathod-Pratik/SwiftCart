<?php
require '../Database/db.php';
header('Content-Type: application/json');

$action = $_POST['action'];

if ($action == 'Create') {
    $userData = json_decode($_COOKIE['venderToken'] ?? $_COOKIE['authToken'] ?? $_COOKIE['AdminToken'] ?? '', true);
    $id = $userData['id'] ?? null;
    $product_id = $_POST['product_id'];
    $order_id = $_POST['order_id'];

    $subject = $_POST['subject'];
    $message = $_POST['message'];

    $stmt = $pdo->prepare('INSERT INTO query (user_id, product_id, order_id, subject, message) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$id, $product_id, $order_id, $subject, $message]);

    echo json_encode([
        'success' => true,
    ]);
} else if ($action == 'fetch') {
    $userData = json_decode($_COOKIE['venderToken'] ?? $_COOKIE['authToken'] ?? $_COOKIE['AdminToken'] ?? '', true);
    $id = $userData['id'] ?? null;

    $stmt = $pdo->prepare('
    SELECT 
        query.*, 
        users.name AS user_name, 
        product.product_name, 
        product.image 
        FROM query
        JOIN users ON query.user_id = users.id
        JOIN product ON query.product_id = product.id
        WHERE product.venderid = ?
    ');
    $stmt->execute([$id]);

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => $data
    ]);
} else if ($action == 'resolve') {
    $id = $_POST['id'];

    $stmt = $pdo->prepare('UPDATE query SET resolve=TRUE WHERE id=?');
    $stmt->execute([$id]);

    echo json_encode([
        'success' => true
    ]);
}
