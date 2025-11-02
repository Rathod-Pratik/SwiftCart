<?php
ob_start();

if (!isset($_COOKIE['venderToken'])) {
    header("Location: /SwiftCart/login");
    exit;
}

?>
