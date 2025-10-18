<?php
// --- IZINKAN FILE STATIC ---
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (preg_match('#^/uploads/#', $requestUri)) {
    $decodedPath = urldecode($requestUri);
    $filePath = __DIR__ . '/public' . $decodedPath;

    if (file_exists($filePath)) {
        header("Content-Type: " . mime_content_type($filePath));
        readfile($filePath);
        exit;
    } else {
        http_response_code(404);
        echo "File tidak ditemukan: " . htmlspecialchars($filePath);
        exit;
    }
}
require_once __DIR__ . '/routes/web.php';


?>


