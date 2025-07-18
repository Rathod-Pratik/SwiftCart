<?php
// Start output buffering to allow header redirection
ob_start();

if (!isset($_COOKIE['venderToken'])) {
    header("Location: /SwiftCart/Auth/Login.php");
    exit;
}

$userData = json_decode($_COOKIE['venderToken'], true);

?>
