<?php
ob_start();

if (!isset($_COOKIE['AdminToken'])) {
    header("Location: /login");
    exit;
}
?>