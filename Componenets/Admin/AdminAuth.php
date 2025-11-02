<?php
ob_start();

if (!isset($_COOKIE['AdminToken'])) {
    header("Location: /SwiftCart/login");
    exit;
}
?>