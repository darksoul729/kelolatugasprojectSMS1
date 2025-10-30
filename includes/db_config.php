<?php
/**
 * File: db_config.php
 * Koneksi ke database menggunakan PDO + .env
 */

require_once __DIR__ . '/env.php';
loadEnv(__DIR__ . '/../.env');

$host    = $_ENV['DB_HOST'];
$db      = $_ENV['DB_NAME'];
$user    = $_ENV['DB_USER'];
$pass    = $_ENV['DB_PASS'];
$charset = $_ENV['DB_CHARSET'];

$dsn = "mysql:host={$host};dbname={$db};charset={$charset}";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, 
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, 
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    exit("❌ Database connection failed: " . $e->getMessage());
}
