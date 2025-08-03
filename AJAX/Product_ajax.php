<?php
require '../Database/db.php';
header('Content-Type: application/json');

$action = $_POST['action'];

if ($action == 'fetch') {
    $stmt = $pdo->prepare("
    SELECT *
    FROM product
    ORDER BY created_at DESC
    ");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $countStmt = $pdo->prepare("SELECT COUNT(*) as total FROM product");
    $countStmt->execute();
    $countResult = $countStmt->fetch(PDO::FETCH_ASSOC);
    $totalProducts = $countResult['total'];

    echo json_encode([
        'product' => $products,
        'length' => $totalProducts,
        'action' => 'fetch'
    ]);
} else if ($action == 'filter') {
    $category = $_POST['category'];

    if ($category == 'All Product') {
        $stmt = $pdo->prepare("
            SELECT *
            FROM product
            ORDER BY created_at DESC
        ");
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $countStmt = $pdo->prepare("SELECT COUNT(*) as total FROM product");
        $countStmt->execute();
        $countResult = $countStmt->fetch(PDO::FETCH_ASSOC);
        $totalProducts = $countResult['total'];

        echo json_encode([
            'product' => $products,
            'length' => $totalProducts,
            'action' => 'filter'
        ]);
    } else {
        $stmt = $pdo->prepare("SELECT *
            FROM product WHERE category=?
            ORDER BY created_at DESC
        ");
        $stmt->execute([$category]);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $countStmt = $pdo->prepare("SELECT COUNT(*) as total FROM product WHERE category=?");
        $countStmt->execute([$category]);
        $countResult = $countStmt->fetch(PDO::FETCH_ASSOC);
        $totalProducts = $countResult['total'];

        echo json_encode([
            'product' => $products,
            'length' => $totalProducts,
            'action' => 'filter'
        ]);
    }
} else if ($action == 'fetchOneProduct') {
    $productid = $_POST['id'];
    $stmt = $pdo->prepare('SELECT * FROM product WHERE id=?');
    $stmt->execute([$productid]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt2 = $pdo->prepare('
            SELECT 
                r.*, 
                u.name 
            FROM 
                review r
            INNER JOIN 
                users u 
            ON 
                r.user_id = u.id
            WHERE 
                r.product_id = ?
        ');
    $stmt2->execute([$productid]);
    $review = $stmt2->fetchAll(PDO::FETCH_ASSOC);


    echo json_encode([
        'action' => 'fetchOne',
        'product' => $product,
        'review' => $review
    ]);
}
