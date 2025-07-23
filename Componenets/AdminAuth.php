<?php
if (isset($_COOKIE['AdminToken'])) {
    $userData = json_decode($_COOKIE['AdminToken'], true); // true = return associative array
    $userType = $userData['userType'];

    // Example: Redirect if not vendor
    if ($userType !== 'admin') {
        header('Location: /SwiftCart/login');
        exit();
    }
} else {
    // Cookie not set — redirect to login
    header('Location: /SwiftCart/login');
    exit();
}
