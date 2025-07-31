<?php
require '../Database/db.php';
header('Content-Type: application/json');

// Identify cookie and decode
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

$userid = $userData['id'];
$action = $_POST['action'];

if ($action == "ADD") {
    $productid = $_POST['productid'];
    $check = $pdo->prepare("SELECT 1 FROM wishlist WHERE userid = ? AND productid = ?");
    $check->execute([$userid, $productid]);

    if ($check->rowCount() == 0) {
        $stmt = $pdo->prepare("INSERT INTO wishlist (userid, productid) VALUES (?, ?)");
        $stmt->execute([$userid, $productid]);
        echo json_encode(['status' => 'added']);
    } else {
        echo json_encode(['status' => 'exists']);
    }
} elseif ($action == "REMOVE") {
    $productid = $_POST['productid'];
    $check = $pdo->prepare("SELECT 1 FROM wishlist WHERE userid = ? AND productid = ?");
    $check->execute([$userid, $productid]);

    if ($check->rowCount() > 0) {
        $stmt = $pdo->prepare("DELETE FROM wishlist WHERE userid = ? AND productid = ?");
        $stmt->execute([$userid, $productid]);
        echo json_encode(['status' => 'removed']);
    } else {
        echo json_encode(['status' => 'not_found']);
    }
} else if ($action == 'fetch') {
    $stmt = $pdo->prepare('
    SELECT 
        w.id AS wishlist_id,
        w.userid,
        w.productid,
        w.created_at,
        p.id AS product_id,
        p.product_name,
        p.price,
        p.discount,
        p.image
    FROM wishlist w
    JOIN product p ON w.productid = p.id
    WHERE w.userid = ?
    ORDER BY w.created_at DESC
    ');

    $stmt->execute([$userid]);
    $wishlistWithProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'product' => $wishlistWithProducts,
        'action' => 'fetch'
    ]);
}
