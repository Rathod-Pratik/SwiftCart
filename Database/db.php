<?php

$env = parse_ini_file(__DIR__ . '/../.env');

$host = $env['host'];  // Replace with your Supabase host
$port = $env['port'];                        // Default PostgreSQL port
$dbname = $env['dbname'];                 // Supabase default database
$user = $env['user'];                   // Supabase default username
$password = $env['password'];   // Password you set in Supabase

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
