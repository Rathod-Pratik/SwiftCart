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
$productid = $_POST['productid'];

if ($action == "ADD") {
    $check = $pdo->prepare("SELECT 1 FROM cart WHERE userid = ? AND productid = ?");
    $check->execute([$userid, $productid]);

    if ($check->rowCount() == 0) {
        $stmt = $pdo->prepare("INSERT INTO cart (userid, productid) VALUES (?, ?)");
        $stmt->execute([$userid, $productid]);
        echo json_encode(['status' => 'added']);
    } else {
        echo json_encode(['status' => 'exists']);
    }
}
elseif ($action == "REMOVE") {
    $check = $pdo->prepare("SELECT 1 FROM cart WHERE userid = ? AND productid = ?");
    $check->execute([$userid, $productid]);

    if ($check->rowCount() > 0) {
        $stmt = $pdo->prepare("DELETE FROM cart WHERE userid = ? AND productid = ?");
        $stmt->execute([$userid, $productid]);
        echo json_encode(['status' => 'removed']);
    } else {
        echo json_encode(['status' => 'not_found']);
    }
}
?>