<?php
/**
 * File: env.php
 * Membaca variabel dari file .env dan memasukkannya ke $_ENV
 */

if (!function_exists('loadEnv')) {
    function loadEnv($path)
    {
        if (!file_exists($path)) {
            throw new Exception(".env file not found at: {$path}");
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            // Lewati komentar
            if (str_starts_with(trim($line), '#')) continue;

            [$key, $value] = array_map('trim', explode('=', $line, 2));
            $value = trim($value, "'\""); // hilangkan tanda kutip

            // Simpan ke environment
            $_ENV[$key] = $value;
            putenv("$key=$value");
        }
    }
}
