<?php
require '../Database/db.php';
header('Content-Type: application/json');

$action = $_POST['action'];

if ($action == 'Create') {
    $userData = json_decode($_COOKIE['venderToken'] ?? $_COOKIE['authToken'] ?? $_COOKIE['AdminToken'] ?? '', true);
    $id = $userData['id'] ?? null;

    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    $order_id = $_POST['order_id'];
    $product_id = $_POST['product_id'];

    $stmt = $pdo->prepare('INSERT INTO review (user_id,product_id,rating,comment,order_id) VALUES(?,?,?,?,?)');
    $stmt->execute([$id, $product_id, $rating, $comment,$order_id]);

    echo json_encode([
        'success' => true,
    ]);
} else if ($action == 'fetch') {
     $userData = json_decode($_COOKIE['venderToken'] ?? $_COOKIE['authToken'] ?? $_COOKIE['AdminToken'] ?? '', true);
    $id = $userData['id'] ?? null;

    $stmt = $pdo->prepare('
    SELECT 
        review.*, 
        users.name AS user_name, 
        product.product_name, 
        product.image 
        FROM review
        JOIN users ON review.user_id = users.id
        JOIN product ON review.product_id = product.id
        WHERE product.venderid = ?
    ');
    $stmt->execute([$id]);

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => $data
    ]);
}else if($action == 'fetchAll'){
      $stmt = $pdo->query('
    SELECT 
        review.*, 
        users.name AS user_name, 
        product.product_name, 
        product.id AS productid, 
        product.image 
        FROM review
        JOIN users ON review.user_id = users.id
        JOIN product ON review.product_id = product.id
    ');

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => $data
    ]);
}
