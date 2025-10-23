<?php
class LandingController {
    public function kirim_pesan() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama  = htmlspecialchars(trim($_POST['nama'] ?? ''));
            $email = htmlspecialchars(trim($_POST['email'] ?? ''));
            $pesan = htmlspecialchars(trim($_POST['pesan'] ?? ''));

            if (empty($nama) || empty($email) || empty($pesan)) {
                $_SESSION['message'] = [
                    'type' => 'danger',
                    'text' => 'Semua kolom wajib diisi.'
                ];
                header('Location: ?route=home');
                exit;
            }

            // ✉️ Konfigurasi SMTP Gmail
            $mailHost     = 'smtp.gmail.com';
            $mailPort     = 587;
            $mailUsername = 'hermansyahkevin362@gmail.com'; // Gmail kamu
            $mailPassword = 'wreeloehmteobixk';             // App password (16 digit tanpa spasi)
            $mailTo       = 'polaholang@gmail.com';         // Tujuan email

            // 📩 Isi email
            $subject = "Pesan dari Form Kontak Website";
            $body = "
                <html>
                <body style='font-family:Arial,sans-serif;color:#333'>
                    <h3>Pesan Baru dari Website</h3>
                    <p><strong>Nama:</strong> {$nama}</p>
                    <p><strong>Email:</strong> {$email}</p>
                    <p><strong>Pesan:</strong><br>{$pesan}</p>
                    <hr>
                    <small>Dikirim otomatis dari Form Kontak Website</small>
                </body>
                </html>
            ";

            // 🔥 Kirim email via SMTP
            $result = $this->smtpSendMail($mailHost, $mailPort, $mailUsername, $mailPassword, $mailTo, $subject, $body, $email, $nama);

            if ($result) {
                $_SESSION['message'] = [
                    'type' => 'success',
                    'text' => 'Pesan berhasil dikirim! Terima kasih telah menghubungi kami.'
                ];
            } else {
                $_SESSION['message'] = [
                    'type' => 'danger',
                    'text' => 'Gagal mengirim pesan. Coba lagi nanti.'
                ];
            }

            header('Location: ?route=home');
            exit;
        }
    }

    private function smtpSendMail($host, $port, $username, $password, $to, $subject, $message, $replyToEmail, $replyToName) {
        $socket = fsockopen($host, $port, $errno, $errstr, 15);
        if (!$socket) {
            error_log("SMTP ERROR: $errstr ($errno)");
            return false;
        }

        // Baca sambutan awal server
        $this->smtpRead($socket);

        // Jalankan perintah SMTP
        $this->smtpCmd($socket, "EHLO localhost");
        $this->smtpCmd($socket, "STARTTLS");

        // Aktifkan TLS
        if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
            error_log("Gagal aktifkan TLS");
            fclose($socket);
            return false;
        }

        // Login SMTP
        $this->smtpCmd($socket, "EHLO localhost");
        $this->smtpCmd($socket, "AUTH LOGIN");
        $this->smtpCmd($socket, base64_encode($username));
        $this->smtpCmd($socket, base64_encode($password));

        // Header email
        $headers = "From: Website Form <{$username}>\r\n";
        $headers .= "Reply-To: {$replyToName} <{$replyToEmail}>\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        // Kirim data email
        $this->smtpCmd($socket, "MAIL FROM:<$username>");
        $this->smtpCmd($socket, "RCPT TO:<$to>");
        $this->smtpCmd($socket, "DATA");

        fputs($socket, "Subject: $subject\r\n$headers\r\n$message\r\n.\r\n");
        $this->smtpRead($socket);

        $this->smtpCmd($socket, "QUIT");
        fclose($socket);

        return true;
    }

    private function smtpCmd($socket, $cmd) {
        fputs($socket, $cmd . "\r\n");
        return $this->smtpRead($socket);
    }

    private function smtpRead($socket) {
        $data = '';
        while ($str = fgets($socket, 515)) {
            $data .= $str;
            if (substr($str, 3, 1) == ' ') break;
        }
        return $data;
    }
}
