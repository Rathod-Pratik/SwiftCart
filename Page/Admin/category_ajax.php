<?php
require '../../Database/db.php';
session_start();
header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

if ($action == 'fetch') {
    $categories = $pdo->query("SELECT * FROM category ORDER BY id
      DESC")->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'action' => 'fetch',
        'categories' => $categories
    ]);
    exit;
}

if ($action === 'create') {
    $name = $_POST['name'] ?? '';
    $desc = $_POST['description'] ?? '';

    if ($name) {
        try {
            $stmt = $pdo->prepare("INSERT INTO category (name, description) VALUES (?, ?)");
            $stmt->execute([$name, $desc]);
            $id = $pdo->lastInsertId();

            $_SESSION["message"] = "Category created successfully";
            $_SESSION["msg_type"] = "success";

            echo json_encode([
                'success' => true,
            ]);
        } catch (PDOException $e) {
            $_SESSION["message"] = "Failed to create category: ";
            $_SESSION["msg_type"] = "danger";
        }
        exit;
    }
}
if ($action === 'update') {
    $id = $_POST['id'] ?? '';
    $name = $_POST['name'] ?? '';
    $desc = $_POST['description'] ?? '';
    if ($id && $name) {
        try {
            $stmt = $pdo->prepare("UPDATE category SET name = ?, description = ? WHERE id = ?");
            $stmt->execute([$name, $desc, $id]);

              echo json_encode([
                'success' => true,
            ]);

            $_SESSION["message"] = "Category Updated successfully";
            $_SESSION["msg_type"] = "success";
        } catch (PDOException $e) {
            $_SESSION["message"] = "Failed to Update category: ";
            $_SESSION["msg_type"] = "danger";
        }
    }
    exit;
}
if ($action === 'delete') {
    $id = $_POST['id'] ?? '';
    if ($id) {
        try {
            $stmt = $pdo->prepare("DELETE FROM category WHERE id = ?");
            $stmt->execute([$id]);

               echo json_encode([
                'success' => true,
            ]);

            $_SESSION["message"] = "Category Deleted successfully";
            $_SESSION["msg_type"] = "success";
        } catch (PDOException $e) {
            $_SESSION["message"] = "Failed to Delete category";
            $_SESSION["msg_type"] = "danger";
        }
        exit;
    }
}
