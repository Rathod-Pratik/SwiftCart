<?php
require '../Database/db.php';
header('Content-Type: application/json');

$action = $_POST['action'];

if ($action == 'fetch') {
    $userData = json_decode($_COOKIE['venderToken'] ?? $_COOKIE['authToken'] ?? '', true);
    $id = $userData['id'];
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id=?');
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => $user
    ]);
} else if ($action == 'updateProfile') {
    $userData = json_decode($_COOKIE['venderToken'], true);
    $venderid = $userData['id'];
    $photo = $_POST['photo'];

    $stmt = $pdo->prepare('UPDATE users SET photo=? WHERE id=?');
    $stmt->execute([$photo, $venderid]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'photo' => $photo
    ]);
} else if ($action == 'UpdateCustomerProfile') {
    $userData = json_decode($_COOKIE['venderToken'] ?? $_COOKIE['authToken'] ?? $_COOKIE['AdminToken'] ?? '', true);
    $venderid = $userData['id'] ?? null;

    if (!$venderid) {
        echo json_encode(['success' => false, 'message' => 'User not authenticated.']);
        exit;
    }

    $firstName = $_POST['firstName'] ?? '';
    $lastName = $_POST['lastName'] ?? '';
    $fullname = $firstName . " " . $lastName;
    $email = $_POST['email'] ?? '';
    $mobile = $_POST['mobile'] ?? '';
    $address = $_POST['address'] ?? '';
    $newPassword = $_POST['newPassword'] ?? '';
    $oldPassword = $_POST['oldPassword'] ?? '';

    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$venderid]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(['notfound' => false, 'message' => 'User not found.']);
        exit;
    }

    if (!password_verify($oldPassword, $user['password'])) {
        echo json_encode(['wrongpass' => false, 'message' => 'Incorrect old password.']);
        exit;
    }

    $finalPassword = !empty($newPassword) ? password_hash($newPassword, PASSWORD_DEFAULT) : $user['password'];

    $stmt = $pdo->prepare('UPDATE users SET name = ?, email = ?, mobile = ?, address = ?, password = ? WHERE id = ?');
    $success = $stmt->execute([$fullname, $email, $mobile, $address, $finalPassword, $venderid]);

    if ($success) {
        echo json_encode([
            'success' => true,
            'message' => 'Profile updated successfully.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to update profile.'
        ]);
    }
}
