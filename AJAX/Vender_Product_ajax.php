<?php
require '../Database/db.php';
header('Content-Type: application/json');

$env = parse_ini_file(__DIR__ . '/../.env');
$action = $_POST['action'];

require '../vendor/autoload.php';

use Dotenv\Dotenv;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

function DeleteImage($image)
{
    $parsedUrl = parse_url($image, PHP_URL_PATH);
    $parts = explode('/', $parsedUrl);

    array_shift($parts);

    array_splice($parts, 0, 4);

    $publicId = implode('/', $parts);
    $publicId = pathinfo($publicId, PATHINFO_DIRNAME) . '/' . pathinfo($publicId, PATHINFO_FILENAME);

    $publicId = ltrim($publicId, '/');

    $cloudName = $_ENV['name'];
    $apiKey = $_ENV['apikey'];
    $apiSecret = $_ENV['cloudinarySecret'];
    $timestamp = time();

    // 3. Prepare params
    $params = [
        'public_id' => $publicId,
        'timestamp' => $timestamp,
    ];

    // 4. Generate signature
    ksort($params);
    $signatureBase = '';
    foreach ($params as $key => $value) {
        $signatureBase .= "$key=$value&";
    }
    $signatureBase = rtrim($signatureBase, '&');
    $signature = sha1($signatureBase . $apiSecret);

    // 5. Add to request
    $params['api_key'] = $apiKey;
    $params['signature'] = $signature;

    // 6. Send DELETE request
    $url = "https://api.cloudinary.com/v1_1/$cloudName/image/destroy";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    // json_encode(['response' => $response]);
}

if ($action == 'upload') {
    $cloudName = $_ENV['name'];
    $apiKey = $_ENV['apikey'];
    $apiSecret = $_ENV['cloudinarySecret'];

    // Step 2: Check if image file exists
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        die("❌ No image uploaded or upload error.");
    }

    // Step 3: Prepare file
    $tempPath = $_FILES['image']['tmp_name'];
    $imageFile = curl_file_create($tempPath, $_FILES['image']['type'], $_FILES['image']['name']);

    // Step 4: Prepare signed parameters
    $timestamp = time();
    $params = [
        'timestamp' => $timestamp,
        'folder' => 'SwiftCart',                // optional: folder in Cloudinary
        'public_id' => pathinfo($_FILES['image']['name'], PATHINFO_FILENAME), // use uploaded file name
    ];

    // Step 5: Create signature string
    ksort($params);
    $signatureString = '';
    foreach ($params as $key => $value) {
        $signatureString .= $key . '=' . $value . '&';
    }
    $signatureString = rtrim($signatureString, '&');
    $signature = sha1($signatureString . $apiSecret);

    // Step 6: Add signature, api key, and file to request
    $params['signature'] = $signature;
    $params['api_key'] = $apiKey;
    $params['file'] = $imageFile;

    // Step 7: Upload to Cloudinary
    $uploadUrl = "https://api.cloudinary.com/v1_1/$cloudName/image/upload";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $uploadUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    if (isset($result['secure_url'])) {
        echo json_encode([
            'success' => true,
            'data' => $result
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'error' => $result
        ]);
    }
} else if ($action == 'create') {
    try {
        if (!isset($_COOKIE['venderToken'])) {
            throw new Exception("Vender token not found.");
        }

        $userData = json_decode($_COOKIE['venderToken'], true);
        $venderid = $userData['id'] ?? null;

        // Fetch required POST values
        $product_name = $_POST['product_name'] ?? '';
        $price = $_POST['price'] ?? '';
        $highlight = $_POST['highlight'] ?? '';
        $stock = $_POST['stock'] ?? '';
        $discount = $_POST['discount'] ?? '';
        $category = $_POST['category'] ?? '';
        $image = $_POST['image'] ?? '';
        $product_state = 'requested';

        $info_keys = $_POST['info_key'] ?? $_POST['info_key[]'] ?? [];
        $info_values = $_POST['info_value'] ?? $_POST['info_value[]'] ?? [];

        // Ensure arrays
        if (is_string($info_keys)) $info_keys = [$info_keys];
        if (is_string($info_values)) $info_values = [$info_values];

        $info_array = [];
        for ($i = 0; $i < count($info_keys); $i++) {
            $key = trim($info_keys[$i]);
            $value = trim($info_values[$i]);
            if ($key !== '') {
                $info_array[$key] = $value;
            }
        }
        $information = json_encode($info_array);

        // --- Description (Paragraphs) ---
        $descValueRaw = $_POST['description'] ?? '';
        $descValue = json_decode($descValueRaw, true);

        if (is_string($descValue)) {
            $descValue = [$descValue];
        } elseif (!is_array($descValue)) {
            $descValue = [];
        }

        $description = [];
        foreach ($descValue as $i => $para) {
            $description['para' . $i] = $para;
        }
        $description = json_encode($description);

        // --- Validation ---
        if (
            empty($image) || empty($product_name) || empty($price) ||
            empty($highlight) || empty($stock) || empty($discount) ||
            empty($category) || empty($information) || empty($description) ||
            empty($product_state) || empty($venderid)
        ) {
            echo json_encode(["success" => false, "message" => "All fields are required"]);
            return;
        }

        // --- Insert into DB ---
        $stmt = $pdo->prepare('
                    INSERT INTO product (
                        image, venderid, product_name, price, highlight, stock,
                        discount, category, information, description, product_state
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ');

        $success = $stmt->execute([
            $image,
            $venderid,
            $product_name,
            $price,
            $highlight,
            $stock,
            $discount,
            $category,
            $information,
            $description,
            $product_state
        ]);

        if ($success) {
            $userStmt = $pdo->prepare('SELECT email FROM users WHERE id = ?');
            $userStmt->execute([$venderid]);
            $user = $userStmt->fetch(PDO::FETCH_ASSOC);
            $email = $user['email'] ?? null;

            if ($email) {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = $_ENV['SMTP_HOST'];
                $mail->Port = $_ENV['SMTP_PORT'];
                $mail->Username = $_ENV['SMTP_USER'];
                $mail->Password = $_ENV['SMTP_PASS'];
                $mail->SMTPAuth = true;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

                $mail->CharSet = 'UTF-8';
                $mail->setFrom($_ENV['SMTP_USER'], 'SwiftCart Admin');
                $mail->addAddress($email);
                $mail->Subject = "🛒 New Product Created - " . htmlspecialchars($product_name);
                $mail->isHTML(true);

                // Secure variables
                $encodedName = htmlspecialchars($product_name);
                $encodedCategory = htmlspecialchars($category);
                $encodedPrice = number_format(floatval($price), 2);

                $mail->Body = "
                                    <div style=\"font-family: Arial, sans-serif; color: #333; padding: 10px;\">
                                        <h2 style=\"color: #4caf50;\">&#128722; Your Product Has Been Submitted!</h2>
                                        <p>Thank you for submitting a new product. Here's a summary of your submission:</p>

                                        <table style=\"margin-top: 15px;\">
                                            <tr><td><strong>Product Name:</strong></td><td> $encodedName</td></tr>
                                            <tr><td><strong>Price:</strong></td><td> ₹$encodedPrice</td></tr>
                                            <tr><td><strong>Category:</strong></td><td> $encodedCategory</td></tr>
                                            <tr><td><strong>Status:</strong></td><td> Requested</td></tr>
                                        </table>

                                        <hr style=\"margin: 20px 0;\" />
                                        <p>🕒 <strong>Next Step:</strong> Our admin will review your product. You'll receive an update once it's approved.</p>
                                        <p style=\"font-size: 12px; color: #888;\">SwiftCart Team - This is an automated email. Please do not reply.</p>
                                    </div>
                                ";

                $mail->send();
            }
        }


          $stmt = $pdo->prepare('SELECT * FROM product WHERE product_name=?');
          $stmt->execute([$product_name]);
          $product=$stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => $success,
            'action' => 'create',
            'data' =>  $product
        ]);
    } catch (Exception $e) {
        echo json_encode([
            "success" => false,
            "message" => $e->getMessage()
        ]);
        exit;
    }
} else if ($action == 'update') {
    try {
        if (!isset($_COOKIE['venderToken'])) {
            throw new Exception("Vender token not found.");
        }

        $userData = json_decode($_COOKIE['venderToken'], true);
        $venderid = $userData['id'];

        $product_name = $_POST['product_name'] ?? '';
        $price = $_POST['price'] ?? '';
        $highlight = $_POST['highlight'] ?? '';
        $stock = $_POST['stock'] ?? '';
        $discount = $_POST['discount'] ?? '';
        $category = $_POST['category'] ?? '';
        $image = $_POST['image'] ?? '';
        $id = $_POST['id'] ?? '';
        $product_state = 'requested';
        // --- Information ---
        $info_keys = $_POST['info_key'] ?? $_POST['info_key[]'] ?? [];
        $info_values = $_POST['info_value'] ?? $_POST['info_value[]'] ?? [];

        // Ensure arrays
        if (is_string($info_keys)) $info_keys = [$info_keys];
        if (is_string($info_values)) $info_values = [$info_values];

        $info_array = [];
        for ($i = 0; $i < count($info_keys); $i++) {
            $key = trim($info_keys[$i]);
            $value = trim($info_values[$i]);
            if ($key !== '') {
                $info_array[$key] = $value;
            }
        }
        $information = json_encode($info_array);

        // --- Description ---
        $descValueRaw = $_POST['description'] ?? '';
        $descValue = json_decode($descValueRaw, true);

        if (is_string($descValue)) {
            $descValue = [$descValue];
        } elseif (!is_array($descValue)) {
            $descValue = [];
        }

        $description = [];
        foreach ($descValue as $i => $para) {
            $description['para' . $i] = $para;
        }
        $description = json_encode($description);

        if (
            empty($image) || empty($product_name) || empty($price) ||
            empty($highlight) || empty($stock) || empty($discount) ||
            empty($category) || empty($information) || empty($description) ||
            empty($venderid) || empty($id)
        ) {
            echo json_encode(["success" => false, "message" => "All fields are required"]);
            return;
        }

        $stmt = $pdo->prepare('SELECT * FROM product WHERE id = ? AND venderid = ?');
        $stmt->execute([$id, $venderid]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            echo json_encode(["notfound" => true, "message" => "Product not found"]);
            return;
        }

        if ($product['image'] != $image) {
            DeleteImage(($product['image']));
        }

        $stmt = $pdo->prepare('
            UPDATE product 
            SET product_name =? , image = ?, price = ?, highlight = ?, stock = ?, discount = ?, category = ?, information = ?, description = ? ,product_state=?
            WHERE id = ? AND venderid = ?
        ');

        $success = $stmt->execute([
            $product_name,
            $image,
            $price,
            $highlight,
            $stock,
            $discount,
            $category,
            $information,
            $description,
            $product_state,
            $id,
            $venderid,
        ]);
        if ($success) {
            $userStmt = $pdo->prepare('SELECT email FROM users WHERE id = ?');
            $userStmt->execute([$venderid]);
            $user = $userStmt->fetch(PDO::FETCH_ASSOC);
            $email = $user['email'] ?? null;

            if ($email) {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = $_ENV['SMTP_HOST'];
                $mail->Port = $_ENV['SMTP_PORT'];
                $mail->Username = $_ENV['SMTP_USER'];
                $mail->Password = $_ENV['SMTP_PASS'];
                $mail->SMTPAuth = true;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

                $mail->setFrom($_ENV['SMTP_USER'], 'SwiftCart Admin');
                $mail->addAddress($email);
                $mail->CharSet = 'UTF-8';
                $mail->Subject = "✏️ Product Updated - " . htmlspecialchars($product_name);
                $mail->isHTML(true);

                // Secure variables
                $encodedName = htmlspecialchars($product_name);
                $encodedCategory = htmlspecialchars($category);
                $encodedPrice = number_format(floatval($price), 2);

                $mail->Body = "
                            <div style=\"font-family: Arial, sans-serif; color: #333; padding: 10px;\">
                                <h2 style=\"color: #2196f3;\">&#9998; Product Update Submitted</h2>
                                <p>Your product update has been successfully submitted. Here's the updated information:</p>

                                <table style=\"margin-top: 15px;\">
                                    <tr><td><strong>Product Name:</strong></td><td> $encodedName</td></tr>
                                    <tr><td><strong>Price:</strong></td><td> ₹$encodedPrice</td></tr>
                                    <tr><td><strong>Category:</strong></td><td> $encodedCategory</td></tr>
                                    <tr><td><strong>Status:</strong></td><td> Re-Requested for Approval</td></tr>
                                </table>

                                <hr style=\"margin: 20px 0;\" />
                                <p>🔎 <strong>Next Step:</strong> Admin will review the changes. You'll be notified after approval.</p>
                                <p style=\"font-size: 12px; color: #888;\">SwiftCart Team - This is an automated email. Please do not reply.</p>
                            </div>
                        ";

                $mail->send();
            }
        }


        $stmt = $pdo->prepare('SELECT * FROM product WHERE product_name=?');
          $stmt->execute([$product_name]);
          $product=$stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => $success,
            'action' => 'update',
            'data'=> $product
        ]);
    } catch (Exception $e) {
        echo json_encode([
            "success" => false,
            "message" => $e->getMessage()
        ]);
    }
} else if ($action == 'fetch') {
    try {
        $userData = json_decode($_COOKIE['venderToken'], true);
        $venderid = $userData['id'];
        $stmt = $pdo->prepare("SELECT * FROM product where venderid=?");

        $stmt->execute([$venderid]);
        $product = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'product' => $product,
            'success' => true
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
} else if ($action == 'delete') {
    try {
        $productid = $_POST['id'];
        $venderid = $_POST['venderid'];

        // Fetch product
        $stmt = $pdo->prepare('SELECT * FROM product WHERE id = ?');
        $stmt->execute([$productid]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt1 = $pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt1->execute([$venderid]);
        $vender = $stmt1->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            echo json_encode([
                'success' => false,
                'notfound' => true
            ]);
            exit;
        }

        // Delete image
        DeleteImage($product['image']); // assumes function exists

        // Delete from DB
        $stmt = $pdo->prepare('DELETE FROM product WHERE id = ?');
        $success = $stmt->execute([$productid]);

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $_ENV['SMTP_HOST'];
        $mail->Port = $_ENV['SMTP_PORT'];
        $mail->Username = $_ENV['SMTP_USER'];
        $mail->Password = $_ENV['SMTP_PASS'];
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        $mail->CharSet = 'UTF-8';
        $mail->setFrom($_ENV['SMTP_USER'], 'SwiftCart Admin');
        $mail->addAddress($vender['email']);
        $mail->Subject = "🗑️ Product Deleted - " . htmlspecialchars($product['product_name']);
        $mail->isHTML(true);

        // Sanitize and format values
        $productName = htmlspecialchars($product['product_name']);
        $category = htmlspecialchars($product['category']);
        $price = number_format(floatval($product['price']), 2);

        $mail->Body = "
            <div style=\"font-family: Arial, sans-serif; color: #333; padding: 10px;\">
                <h2 style=\"color: #e53935;\">&#128465; Product Deleted</h2>
                <p>We want to inform you that the following product has been <strong>removed</strong> from the SwiftCart platform:</p>

                <table style=\"margin-top: 15px;\">
                    <tr><td><strong>Product Name:</strong></td><td> $productName</td></tr>
                    <tr><td><strong>Category:</strong></td><td> $category</td></tr>
                    <tr><td><strong>Price:</strong></td><td> ₹$price</td></tr>
                </table>

                <hr style=\"margin: 20px 0;\" />
                <p>If you believe this was a mistake or would like to re-list the product, please contact support or re-submit it for review.</p>
                <p style=\"font-size: 12px; color: #888;\">SwiftCart Team - This is an automated email. Please do not reply.</p>
            </div>
        ";


        echo json_encode([
            'success' => $success,
            'action' => 'delete'
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}
