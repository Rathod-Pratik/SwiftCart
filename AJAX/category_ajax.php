<?php
require '../Database/db.php';
header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

if ($action == 'fetch') {
    try {
        $categories = $pdo->query("SELECT * FROM category ORDER BY id
      DESC")->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'action' => 'fetch',
            'categories' => $categories
        ]);
        exit;
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit();
    }
} else if ($action === 'create') {
    $name = $_POST['name'] ?? '';
    $desc = $_POST['description'] ?? '';
    $image = $_POST['image'] ?? '';

    try {
        $stmt = $pdo->prepare("INSERT INTO category (name, description,image) VALUES (?, ?, ?)");
        $stmt->execute([$name, $desc, $image]);
        $id = $pdo->lastInsertId();

        echo json_encode([
            'success' => true,
            'create' => true,
            'data' => [
                'id' => $id,
                'name' => $name,
                'description' => $desc,
                'image' => $image
            ]
        ]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit();
    }
    exit;
} else if ($action === 'update') {
    try {
        $id = $_POST['id'] ?? '';
        $name = $_POST['name'] ?? '';
        $desc = $_POST['description'] ?? '';
        $image = $_POST['image'];
        $stmt = $pdo->prepare("UPDATE category SET name = ?, description = ? , image =? WHERE id = ?");
        $stmt->execute([$name, $desc, $image, $id]);

        echo json_encode([
            'success' => true,
            'update' => true,
            'data'=> [
                'name' => $name,
                'image' => $image,
                'description' => $desc,
                'id'=>$id
            ]
        ]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit();
    }
} else if ($action === 'delete') {
    try {
        $id = $_POST['id'] ?? '';
        $stmt = $pdo->prepare("DELETE FROM category WHERE id = ?");
        $stmt->execute([$id]);

        echo json_encode([
            'success' => true,
            'delete' => true
        ]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit();
    }
}
