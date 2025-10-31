<?php
/**
 * File: env.php
 * Membaca variabel dari file .env dan memasukkannya ke $_ENV dan $_SERVER
 */

if (!function_exists('loadEnv')) {
    function loadEnv($path)
    {
        if (!file_exists($path)) {
            throw new Exception(".env file not found at: {$path}");
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $line = trim($line);

            // Lewati komentar dan baris kosong
            if ($line === '' || str_starts_with($line, '#')) continue;

            // Pisahkan KEY dan VALUE
            [$key, $value] = array_map('trim', explode('=', $line, 2));

            // Hilangkan tanda kutip jika ada
            $value = trim($value, "'\"");

            // Simpan ke environment PHP
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;

            // Jangan panggil putenv() (karena dinonaktifkan di hosting)
            // putenv("$key=$value");
        }
    }
}
