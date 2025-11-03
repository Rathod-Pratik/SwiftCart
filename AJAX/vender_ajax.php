<?php
require '../Database/db.php';
header('Content-Type: application/json');
$action = $_POST['action'] ?? '';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

if ($action == 'create') {
    try {
        $name = $_POST['name'];
        $account_no = $_POST['account_no'];
        $ifsc_code = $_POST['ifsc_code'];
        $mobile = $_POST['mobile'];
        $company_name = $_POST['company_name'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $password = $_POST['password'];

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $create = $pdo->prepare('INSERT INTO users (name,account_no,ifsc_code,mobile,email,address,company_name,password,userType) VALUES (?,?,?,?,?,?,?,?,?)');

        $success =  $create->execute([
            $name,
            $account_no,
            $ifsc_code,
            $mobile,
            $email,
            $address,
            $company_name,
            $hashedPassword,
            'vender'
        ]);
        $vendor_name     = $name;
        $vendor_email    = $email;
        $vendor_password = $password;
        $vendor_phone    = $mobile;
        $vendor_account  = $account_no;
        $vendor_ifsc     = $ifsc_code;
        $vendor_address  = $address;
        $vendor_company  = $company_name;


        $template = "
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset='UTF-8'>
                    <title>SwiftCart Vendor Account</title>
                </head>
                <body>
                    <h2>🎉 You're In! SwiftCart Vendor Account Activated</h2>
                <div style='font-family: system-ui, sans-serif, Arial; font-size: 12px;'>
                <div>&nbsp;</div>
                <div style='margin-top: 20px; padding: 15px 0; border-width: 1px 0; border-style: dashed; border-color: lightgrey;'>
                    
                    <p>Dear <strong>{$vendor_name}</strong>,</p>
                    <p>We are pleased to inform you that your vendor registration request has been accepted. Your account has been successfully created on SwiftCart.</p>
                    <p><strong>Login Details:</strong><br>
                    Email: {$vendor_email}<br>
                    Password: {$vendor_password}</p>
                    <p>You can login at: <a href='https://swiftcart.com/vendor-login'>https://swiftcart.com/vendor-login</a></p>
                    <p><strong>Please confirm your information:</strong><br>
                    Name: {$vendor_name}<br>
                    Email: {$vendor_email}<br>
                    Phone: {$vendor_phone}<br>
                    Account no: {$vendor_account}<br>
                    IFSC: {$vendor_ifsc}<br>
                    Address: {$vendor_address}<br>
                    Company: {$vendor_company}</p>
                    <p>If anything is incorrect, please contact us on the website.</p>
                    <p>Regards,<br>SwiftCart Team</p>
                </div>
                </div>
                </body>
                </html>
                ";



        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host     = getenv('SMTP_HOST');
        $mail->Port     = getenv('SMTP_PORT');
        $mail->Username = getenv('SMTP_USER');
        $mail->Password = getenv('SMTP_PASS');
        $mail->SMTPAuth   = true;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        $mail->setFrom('noreply@swiftcart.com', 'SwiftCart');
        $mail->addAddress($vendor_email, $vendor_name);
        $mail->addReplyTo('noreply@swiftcart.com');

        $mail->isHTML(true);
        $mail->Subject = "You're In! SwiftCart Vendor Account Activated";
        $mail->Body    = $template;

        $mail->send();

        $id = $pdo->lastInsertId();

        echo json_encode([
            'success' => $success,
            'action' => 'create',
            'data' => [
                'id' => $id,
                'name' => $name,
                'account_no' => $account_no,
                'ifsc_code' => $ifsc_code,
                'mobile' => $mobile,
                'email' => $email,
                'address' => $address,
                'company_name' => $company_name,
                'password' => $password
            ]
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
} elseif ($action === 'update') {
    try {
        $name = $_POST['name'];
        $account_no = $_POST['account_no'];
        $ifsc_code = $_POST['ifsc_code'];
        $mobile = $_POST['mobile'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $company_name = $_POST['company_name'];
        $password = $_POST['password'];

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $update = $pdo->prepare('
                 UPDATE users 
                 SET 
                     name = :name,
                     account_no = :account_no,
                     ifsc_code = :ifsc_code,
                     mobile = :mobile,
                     address = :address,
                     company_name = :company_name 
                     password = :password
                 WHERE email = :email
                ');

        // Execute with bound values
        $success = $update->execute([
            ':name' => $name,
            ':account_no' => $account_no,
            ':ifsc_code' => $ifsc_code,
            ':mobile' => $mobile,
            ':address' => $address,
            ':company_name' => $company_name,
            ':email' => $email,
            ':password' => $hashedPassword
        ]);

        if ($success) {
            $vendor_name     = $name;
            $vendor_email    = $email;

            $template = "
                <html>
                <body>
                <h2>Account Updated - SwiftCart Vendor</h2>
                <div style='font-family: system-ui, sans-serif; font-size: 12px; padding: 15px 0; border-top: 1px dashed #ccc;'>
                    <p>Dear <strong>{$vendor_name}</strong>,</p>
                    <p>Your vendor account information has been successfully updated on <strong>SwiftCart</strong>.</p>
                    <p>If you did not request this change, please contact our support team immediately.</p>
                    <p>Regards,<br>SwiftCart Team</p>
                </div>
                </body>
                </html>
                ";

            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host     = getenv('SMTP_HOST');
            $mail->Port     = getenv('SMTP_PORT');
            $mail->Username = getenv('SMTP_USER');
            $mail->Password = getenv('SMTP_PASS');
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

            $mail->setFrom('noreply@swiftcart.com', 'SwiftCart');
            $mail->addAddress($vendor_email, $vendor_name);
            $mail->Subject = "Your SwiftCart Account Has Been Updated";
            $mail->isHTML(true);
            $mail->Body = $template;
            $mail->send();
        }

        $id = $pdo->lastInsertId();
        echo json_encode([
            'success' => $success,
            'action' => 'update',
            'data' => [
                'id' => $id,
                'name' => $name,
                'account_no' => $account_no,
                'ifsc_code' => $ifsc_code,
                'mobile' => $mobile,
                'email' => $email,
                'address' => $address,
                'company_name' => $company_name,
                'password' => $password
            ]
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
} elseif ($action == 'block') {
    try {
        $id = $_POST['id'];
        $block = $pdo->prepare("UPDATE users SET status ='block' WHERE id = :id");

        $success = $block->execute([
            ':id' => $id
        ]);

        if ($success) {
            $stmt = $pdo->prepare("SELECT email, name FROM users WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $user = $stmt->fetch();

            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host     = getenv('SMTP_HOST');
            $mail->Port     = getenv('SMTP_PORT');
            $mail->Username = getenv('SMTP_USER');
            $mail->Password = getenv('SMTP_PASS');
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

            $mail->setFrom('noreply@swiftcart.com', 'SwiftCart');
            $mail->addAddress($user['email'], $user['name']);
            $mail->Subject = "Your SwiftCart Vendor Account Has Been Blocked";
            $mail->isHTML(true);
            $mail->Body = "
                    <html>
                    <body>
                        <h2>🚫 Account Blocked</h2>
                        <p>Dear <strong>{$user['name']}</strong>,</p>
                        <p>We regret to inform you that your vendor account has been temporarily blocked due to policy violations or inactivity.</p>
                        <p>Please contact our support if you believe this is a mistake.</p>
                        <p>SwiftCart Team</p>
                    </body>
                    </html>
                    ";
            $mail->send();
        }


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
} elseif ($action == 'active') {
    try {
        $id = $_POST['id'];
        $active = $pdo->prepare("UPDATE users SET status ='active' WHERE id = :id");

        $success = $active->execute([
            ':id' => $id
        ]);

        if ($success) {
            $stmt = $pdo->prepare("SELECT email, name FROM users WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $user = $stmt->fetch();

            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host     = getenv('SMTP_HOST');
            $mail->Port     = getenv('SMTP_PORT');
            $mail->Username = getenv('SMTP_USER');
            $mail->Password = getenv('SMTP_PASS');
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

            $mail->setFrom('noreply@swiftcart.com', 'SwiftCart');
            $mail->addAddress($user['email'], $user['name']);
            $mail->Subject = "Your SwiftCart Vendor Account Is Now Active";
            $mail->isHTML(true);
            $mail->Body = "
                            <html>
                            <body>
                                <h2>✅ Account Activated</h2>
                                <p>Dear <strong>{$user['name']}</strong>,</p>
                                <p>Good news! Your vendor account has been reactivated and is now fully operational.</p>
                                <p>You may now log in and continue using SwiftCart services.</p>
                                <p>SwiftCart Team</p>
                            </body>
                            </html>
                            ";
            $mail->send();
        }


        echo json_encode([
            'success' => $success,
            'action' => 'active'
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
} elseif ($action == 'fetch') {
    try {
        $vender = $pdo->query("SELECT * FROM users WHERE userType='vender' ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode([
            'success' => true,
            'action' => 'fetch',
            'vender' => $vender
        ]);
        exit;
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}
