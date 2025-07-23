<?php
require '../Database/db.php';
header('Content-Type: application/json');

$action = $_POST['action'];

if ($action == 'fetch') {
    $userData = json_decode($_COOKIE['venderToken'], true);
    $venderid = $userData['id'];

    $stmt = $pdo->prepare('SELECT * FROM users WHERE id=?');
    $stmt->execute([$venderid]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => $user
    ]);
} else if ('updateProfile') {
    $userData = json_decode($_COOKIE['venderToken'], true);
    $venderid = $userData['id'];
    $photo = $_POST['photo'];

    $stmt = $pdo->prepare('UPDATE users SET photo=? WHERE id=?');
    $stmt->execute([$photo,$venderid]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => $user
    ]);
}
