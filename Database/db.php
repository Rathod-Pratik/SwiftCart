<?php

$env = parse_ini_file(__DIR__ . '/../.env');

$host = $env['host'];
$port = $env['port'];
$dbname = $env['dbname'];
$user = $env['user'];
$password = $env['password'];

try {
    $pdo = new PDO(
        "pgsql:host=$host;port=$port;dbname=$dbname",
        $user,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT => false,
            PDO::ATTR_EMULATE_PREPARES => true
        ]
    );
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
