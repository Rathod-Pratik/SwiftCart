<?php
require '../Database/db.php';
header('Content-Type: application/json');
require '../vendor/autoload.php';

use Dotenv\Dotenv;
use PHPMailer\PHPMailer\PHPMailer;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
$action = $_POST['action'];

if ($action == 'fetchRequestedProduct') {
     try {
          $sql = "
               SELECT 
                    product.*, 
                    users.name AS vendor_name, 
                    users.email AS vendor_email
               FROM 
                    product
               JOIN 
                    users 
               ON 
                    product.venderid = users.id
               WHERE 
                    product.product_state = 'requested'
          ";

          $result = $pdo->query($sql);
          $product = $result->fetchAll(PDO::FETCH_ASSOC);

          echo json_encode([
               'product' => $product,
               'success' => true
          ]);
     } catch (PDOException $e) {
          echo json_encode([
               'success' => false,
               'error' => $e->getMessage()
          ]);
     }
} else if ($action == 'fetchApprovedProduct') {
     try {
          $result = $pdo->query("
        SELECT 
            product.*, 
            users.name AS vendor_name
        FROM 
            product
        JOIN 
            users 
        ON 
            product.venderid = users.id
        WHERE 
            product.product_state = 'approved'
    ");
          $product = $result->fetchAll(PDO::FETCH_ASSOC);

          echo json_encode([
               'product' => $product,
               'success' => true
          ]);
     } catch (PDOException $e) {
          echo json_encode([
               'error' => $e->getMessage(),
               'success' => false
          ]);
     }
} else if ($action == 'approved') {
     try {
          $productid = $_POST['id'];
          $email = $_POST['email'];
          // Fetch product info
          $stmt = $pdo->prepare("SELECT * FROM product WHERE id = ?");
          $stmt->execute([$productid]);
          $product = $stmt->fetch(PDO::FETCH_ASSOC);

          if (!$product) {
               echo json_encode(['error' => 'Product not found']);
               exit;
          }

          $name = $product['product_name'];
          $price = $product['price'];
          $category = $product['category'];
          $description = $product['description'];

          // Update product state
          $stmp = $pdo->prepare('UPDATE product SET product_state=? WHERE id=?');
          $accept = $stmp->execute(['approved', $productid]);

          // Send email
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
          $mail->Subject = "✅ Product Approved - " . htmlspecialchars($name);
          $mail->isHTML(true);

          // Safely encode dynamic values
          $encodedName = htmlspecialchars($name);
          $encodedCategory = htmlspecialchars($category);
          $encodedPrice = number_format(floatval($price), 2);

          $mail->Body = "
    <div style=\"font-family: Arial, sans-serif; color: #333; padding: 10px;\">
        <h2 style=\"color: #4CAF50;\">&#127881; Your Product Has Been Approved!</h2>
        <p>We're excited to let you know that your product has been approved and is now live on <strong>SwiftCart</strong>!</p>
        
        <table style=\"margin-top: 20px;\">
            <tr><td><strong>Product Name:</strong></td><td> $encodedName</td></tr>
            <tr><td><strong>Category:</strong></td><td> $encodedCategory</td></tr>
            <tr><td><strong>Price:</strong></td><td> ₹$encodedPrice</td></tr>
        </table>

        <hr style=\"margin-top: 20px; margin-bottom: 20px;\" />
        <p>🚀 <strong>Next Step:</strong> You can now view your product live on the SwiftCart platform and start selling!</p>
        <p style=\"font-size: 13px; color: #888;\">This is an automated message from SwiftCart. Please do not reply directly.</p>
    </div>
          ";

          $mail->send();
          $vender = $pdo->prepare('SELECT name FROM users WHERE email = ?');
          $exe = $vender->execute([$email]);

          $name = $vender->fetchColumn();


          echo json_encode(['action' => 'approved', 'success' => true, 'data' => $product, 'name' => $name]);
     } catch (PDOException $e) {
          echo json_encode(['success' => false, 'error' => $e->getMessage()]);
          exit();
     }
} else if ($action == 'reject') {
     try {
          $productid = $_POST['id'];
          $email = $_POST['email'];

          // Fetch product info
          $stmt = $pdo->prepare("SELECT product_name, price, category, description, (SELECT email FROM users WHERE users.id = product.venderid) as email FROM product WHERE id = ?");
          $stmt->execute([$productid]);
          $product = $stmt->fetch(PDO::FETCH_ASSOC);

          if (!$product) {
               echo json_encode(['error' => 'Product not found']);
               exit;
          }

          $name = $product['product_name'];
          $price = $product['price'];
          $category = $product['category'];
          $description = $product['description'];
          $email = $product['email'];

          // Update product state
          $stmp = $pdo->prepare('UPDATE product SET product_state=? WHERE id=?');
          $accept = $stmp->execute(['rejected', $productid]);

          // Send email
          $mail = new PHPMailer(true);
          $mail->isSMTP();
          $mail->Host = $_ENV['SMTP_HOST'];
          $mail->Port = $_ENV['SMTP_PORT'];
          $mail->Username = $_ENV['SMTP_USER'];
          $mail->Password = $_ENV['SMTP_PASS'];
          $mail->SMTPAuth = true;
          $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

          $mail->setFrom($_ENV['SMTP_USER'], 'SwiftCart Admin');
          $mail->CharSet = 'UTF-8';
          $mail->addAddress($email);
          $mail->CharSet = 'UTF-8';
          $mail->Subject = "❌ Product Rejected - " . htmlspecialchars($name);
          $mail->isHTML(true);

          // Encode dynamic variables to prevent XSS
          $encodedName = htmlspecialchars($name);
          $encodedCategory = htmlspecialchars($category);
          $encodedPrice = number_format(floatval($price), 2);

          $mail->Body = "
               <div style=\"font-family: Arial, sans-serif; color: #333; padding: 10px;\">
                    <h2 style=\"color: #e53935;\">&#10060; Your Product Has Been Rejected</h2>
                    <p>Unfortunately, your product did not meet our listing criteria. Below are the details you submitted:</p>
                    
                    <table style=\"margin-top: 20px;\">
                         <tr><td><strong>Product Name:</strong></td><td> $encodedName</td></tr>
                         <tr><td><strong>Category:</strong></td><td> $encodedCategory</td></tr>
                         <tr><td><strong>Price:</strong></td><td> ₹$encodedPrice</td></tr>
                    </table>

                    <hr style=\"margin-top: 20px; margin-bottom: 20px;\" />
                    <p>🔁 <strong>Next Step:</strong> Please review the product details and correct any issues before resubmitting.</p>
                    <p style=\"font-size: 13px; color: #888;\">This is an automated message from SwiftCart. Please do not reply directly.</p>
               </div>
               ";

          $mail->send();

          echo json_encode(['action' => 'rejected', 'success' => true]);
     } catch (PDOException $e) {
          echo json_encode(['success' => false, 'error' => $e->getMessage()]);
          exit();
     }
} else if ($action == 'publish') {
     try {
          $id = $_POST['id'];

          // Fetch product and vendor email
          $stmt = $pdo->prepare("SELECT product_name, venderid FROM product WHERE id = ?");
          $stmt->execute([$id]);
          $product = $stmt->fetch(PDO::FETCH_ASSOC);

          if (!$product) {
               echo json_encode(['success' => false, 'error' => 'Product not found']);
               exit();
          }

          $name = $product['product_name'];
          $venderid = $product['venderid'];

          // Get vendor email
          $stmtEmail = $pdo->prepare("SELECT email FROM users WHERE id = ?");
          $stmtEmail->execute([$venderid]);
          $user = $stmtEmail->fetch(PDO::FETCH_ASSOC);
          $email = $user['email'];

          // Unpublish the product
          $stmt = $pdo->prepare('UPDATE product SET is_published = TRUE WHERE id = ?');
          $unpublish = $stmt->execute([$id]);

          // Send email to vendor
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
          $mail->Subject = "✅ Product Republished - " . htmlspecialchars($name);
          $mail->isHTML(true);

          $mail->Body = "
            <div style=\"font-family: Arial, sans-serif; color: #333; padding: 10px;\">
                <h2 style=\"color: #fb8c00;\">✅ Product Republished</h2>
                <p>Dear Vendor,</p>
                <p>Your product <strong>" . htmlspecialchars($name) . "</strong> has been Republished by the admin. Now It is visible on the site.</p>
                <p>You can now view your product live on the SwiftCart platform and start selling!</p>
                <hr style=\"margin-top: 20px; margin-bottom: 20px;\" />
                <p style=\"font-size: 13px; color: #888;\">This is an automated message from SwiftCart. Please do not reply directly.</p>
            </div>
        ";

          $mail->send();

          echo json_encode([
               'success' => true,
               'id' => $id
          ]);
     } catch (Exception $e) {
          echo json_encode(['success' => false, 'error' => 'Email failed: ' . $e->getMessage()]);
          exit();
     }
} else if ($action == 'unpublish') {
     try {
          $id = $_POST['id'];

          // Fetch product and vendor email
          $stmt = $pdo->prepare("SELECT product_name, venderid FROM product WHERE id = ?");
          $stmt->execute([$id]);
          $product = $stmt->fetch(PDO::FETCH_ASSOC);

          if (!$product) {
               echo json_encode(['success' => false, 'error' => 'Product not found']);
               exit();
          }

          $name = $product['product_name'];
          $venderid = $product['venderid'];

          // Get vendor email
          $stmtEmail = $pdo->prepare("SELECT email FROM users WHERE id = ?");
          $stmtEmail->execute([$venderid]);
          $user = $stmtEmail->fetch(PDO::FETCH_ASSOC);
          $email = $user['email'];

          // Unpublish the product
          $stmt = $pdo->prepare('UPDATE product SET is_published = FALSE WHERE id = ?');
          $unpublish = $stmt->execute([$id]);

          // Send email to vendor
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
          $mail->Subject = "🚫 Product Unpublished - " . htmlspecialchars($name);
          $mail->isHTML(true);

          $mail->Body = "
            <div style=\"font-family: Arial, sans-serif; color: #333; padding: 10px;\">
                <h2 style=\"color: #fb8c00;\">🚫 Product Unpublished</h2>
                <p>Dear Vendor,</p>
                <p>Your product <strong>" . htmlspecialchars($name) . "</strong> has been unpublished by the admin. It is no longer visible on the site.</p>
                <p>If you believe this is a mistake, please contact our support team.</p>
                <hr style=\"margin-top: 20px; margin-bottom: 20px;\" />
                <p style=\"font-size: 13px; color: #888;\">This is an automated message from SwiftCart. Please do not reply directly.</p>
            </div>
        ";

          $mail->send();

          echo json_encode([
               'success' => true,
               'id' => $id
          ]);
     } catch (Exception $e) {
          echo json_encode(['success' => false, 'error' => 'Email failed: ' . $e->getMessage()]);
          exit();
     }
}