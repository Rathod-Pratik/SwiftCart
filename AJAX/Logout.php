<?php
require '../Database/db.php';
header('Content-Type: application/json');

$action = $_POST['action'] ?? null;

if (isset($_COOKIE['authToken'])) {
    setcookie('authToken', '', time() - 3600, '/');
    echo json_encode(['status' => 'success', 'message' => 'authToken cookie removed']);
} elseif (isset($_COOKIE['AdminToken'])) {
    setcookie('AdminToken', '', time() - 3600, '/');
    echo json_encode(['status' => 'success', 'message' => 'AdminToken cookie removed']);
} elseif (isset($_COOKIE['venderToken'])) {
    setcookie('venderToken', '', time() - 3600, '/');
    echo json_encode(['status' => 'success', 'message' => 'venderToken cookie removed']);
} else {
    echo json_encode(['status' => 'unauthorized', 'message' => 'No token cookie found']);
}
exit;

?>