<?php
require '../Database/db.php';
header('Content-Type: application/json');
$env = parse_ini_file(__DIR__ . '/../.env');
$action = $_POST['action'];

if ($action == 'upload') {
    $cloudName = $env['name'];
    $apiKey = $env['apikey'];
    $apiSecret = $env['cloudinarySecret'];

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
} else if ($action == 'delete') {
    $image = $_POST['image'];
    $parsedUrl = parse_url($image, PHP_URL_PATH);
    $parts = explode('/', $parsedUrl);

    array_shift($parts);

    array_splice($parts, 0, 4);

    $publicId = implode('/', $parts);
    $publicId = pathinfo($publicId, PATHINFO_DIRNAME) . '/' . pathinfo($publicId, PATHINFO_FILENAME);

    $publicId = ltrim($publicId, '/');

    $cloudName = 'rathodpratik';
    $apiKey    = 'your_api_key';
    $apiSecret = 'your_api_secret';
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

    json_encode(['response' => $response]);
} else if ($action == ' create') {
    try {
        $userData = json_decode($_COOKIE['venderToken'], true);

        $image = $_POST['image'];
        $venderid = $userData['id'];
        $product_name = $_POST['product_name'];
        $price = $_POST['price'];
        $highlight = $_POST['highlight'];
        $stock = $_POST['stock'];
        $discount = $_POST['discount'];
        $category = $_POST['category'];
        $info_keys = $_POST['info_key'] ?? [];
        $info_values = $_POST['info_value'] ?? [];

        $information = [];

        for ($i = 0; $i < count($info_keys); $i++) {
            $key = trim($info_keys[$i]);
            $value = trim($info_values[$i]);
            if ($key !== '') {
                $information[$key] = $value;
            }
        }

        $description = json_decode($_POST['description']);
        $product_state = 'requested';

        $create = $pdo->prepare('INSERT INTO product (image, venderid, product_name, price, highlight, stock, discount, category, information, description, product_state) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');

        $success = $create->execute([
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

        echo json_encode([
            'success' => $success,
            'action' => 'create'
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
        exit;
    }
}
