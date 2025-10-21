<?php
/**
 * File: db_config.php
 * Koneksi ke database menggunakan PDO
 */

const DB_HOST    = '127.0.0.1';
const DB_NAME    = 'kelola_tugas';
const DB_USER    = 'root';
const DB_PASS    = 'root';
const DB_CHARSET = 'utf8mb4';

$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, 
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, 
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    exit("Database connection failed: " . $e->getMessage());
}

