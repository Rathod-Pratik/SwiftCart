<?php
require '../Database/db.php';
header('Content-Type: application/json');

$action = $_POST['action'] ?? '';
$id = $_POST['id'] ?? '';

if ($action === 'block') {
    try {
        $stmt = $pdo->prepare("UPDATE users SET status = 'block' WHERE id = :id");
        $success = $stmt->execute(['id' => (int)$id]);

        echo json_encode([
            'success' => $success,
            'action' => 'block'
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
} else if ($action === 'unblock') {
    try {
        $stmt = $pdo->prepare("UPDATE users SET status = 'active' WHERE id = :id");
        $success = $stmt->execute(['id' => (int)$id]);

        echo json_encode([
            'success' => $success,
            'action' => 'unblock'
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
} else if ($action === 'fetch') {
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE userType = 'customer'");
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'action' => 'fetch',
            'users' => $users
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}
