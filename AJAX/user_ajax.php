<?php
require '../Database/db.php';
header('Content-Type: application/json');

$action = $_POST['action'] ?? '';
$id = $_POST['id'] ?? '';

try {
    if ($action === 'block' && is_numeric($id)) {
        $stmt = $pdo->prepare("UPDATE users SET status = 'block' WHERE id = :id");
        $success = $stmt->execute(['id' => (int)$id]);

        echo json_encode([
            'success' => $success,
            'action' => 'block'
        ]);
    } else if ($action === 'unblock' && is_numeric($id)) {
        $stmt = $pdo->prepare("UPDATE users SET status = 'active' WHERE id = :id");
        $success = $stmt->execute(['id' => (int)$id]);

        echo json_encode([
            'success' => $success,
            'action' => 'unblock'
        ]);
    } else if ($action === 'fetch') {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE userType = 'customer'");
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'action' => 'fetch',
            'users' => $users
        ]);
    } else {
        throw new Exception('Invalid action or parameters.');
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
