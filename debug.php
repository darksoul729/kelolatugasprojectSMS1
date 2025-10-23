<?php
$connection = fsockopen("smtp.gmail.com", 587, $errno, $errstr, 10);
if (!$connection) {
    echo "Gagal konek ke SMTP Gmail: $errstr ($errno)";
} else {
    echo "✅ Bisa konek ke SMTP Gmail!";
    fclose($connection);
}
