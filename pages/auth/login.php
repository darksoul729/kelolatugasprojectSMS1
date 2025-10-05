<?php
session_start();

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: ../../pages/admin/dashboard.php");
    } else {
        header("Location: ../../pages/user/dashboard.php");
    }
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include '../../includes/db_config.php';

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Username dan password harus diisi.";
    } else {
        $stmt = $pdo->prepare("SELECT user_id, username, password, role FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['logged_in'] = true;

            if ($user['role'] === 'admin') {
                header("Location: ../../pages/admin/dashboard.php");
            } else {
                header("Location: ../../pages/user/dashboard.php");
            }
            exit;
        } else {
            $error = "Username atau password salah.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        float: 'float 6s ease-in-out infinite',
                        pulseSlow: 'pulse 8s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-12px)' },
                        }
                    }
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        @layer utilities {
            .bg-gradient-main {
                background: linear-gradient(135deg, #dbeafe 0%, #e0f2fe 50%, #f0f9ff 100%);
            }
            .bg-card-custom {
                background-color: rgba(255, 255, 255, 0.92);
                backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }
            .input-group { position: relative; margin-bottom: 1.5rem; }
            .input-icon {
                position: absolute; left: 1rem; top: 50%; transform: translateY(-50%);
                width: 1.25rem; height: 1.25rem; color: #9ca3af; pointer-events: none;
            }
            .input-field { 
                width: 100%; padding-left: 3rem; padding-top: 0.75rem; padding-bottom: 0.75rem;
                border: 2px solid #d1d5db; border-radius: 0.5rem;
                background-color: white; /* Putih polos */
                transition: border-color 0.2s, box-shadow 0.2s;
            }
            .input-field:focus {
                outline: none; border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            }
            .input-label {
                display: block; margin-bottom: 0.25rem;
                font-size: 0.875rem; font-weight: 500; color: #374151;
            }
            .btn-gradient {
                width: 100%; padding: 0.75rem 1rem; border-radius: 0.5rem;
                background: linear-gradient(to right, #2563eb, #0891b2);
                color: white; font-weight: 500; transition: all 0.3s ease;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }
            .btn-gradient:hover {
                background: linear-gradient(to right, #1d4ed8, #0e7490);
                transform: translateY(-2px);
                box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
            }
            .btn-outline {
                width: 100%; padding: 0.75rem 1rem;
                border-radius: 0.5rem; border: 2px solid #d1d5db;
                background-color: white; color: #374151;
                font-weight: 500; transition: all 0.3s ease;
            }
            .btn-outline:hover { background-color: #f9fafb; border-color: #9ca3af; }
        }
    </style>
</head>
<body class="bg-gradient-main flex items-center justify-center min-h-screen">
    <!-- Efek Bubble Background -->
    <div class="fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-float"></div>
        <div class="absolute top-1/3 right-1/4 w-72 h-72 bg-cyan-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-float" style="animation-delay: -2s;"></div>
        <div class="absolute bottom-1/4 left-1/2 w-60 h-60 bg-sky-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulseSlow"></div>
    </div>

    <!-- Kartu Dua Kolom -->
    <div class="bg-card-custom rounded-2xl shadow-xl overflow-hidden w-full max-w-5xl grid md:grid-cols-2">
        
        <!-- Bagian Form -->
        <div class="p-8 bg-white bg-opacity-100 flex flex-col justify-center">
            <h2 class="text-3xl font-bold text-center mb-2 text-gray-800">Masuk</h2>
            <p class="text-center text-gray-600 mb-6">Akses akun kamu untuk melanjutkan</p>

            <?php if (!empty($error)): ?>
                <div class="mb-6 p-3 bg-red-100 text-red-700 rounded-lg text-center">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="input-group">
                    <label for="username" class="input-label">Username</label>
                    <div class="relative">
                        <div class="input-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <input type="text" name="username" id="username" class="input-field" placeholder="Username Anda" required>
                    </div>
                </div>

                <div class="input-group">
                    <label for="password" class="input-label">Password</label>
                    <div class="relative">
                        <div class="input-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input type="password" name="password" id="password" class="input-field" placeholder="••••••••" required>
                    </div>
                </div>

                <button type="submit" class="btn-gradient">Login</button>
            </form>

            <div class="mt-6">
                <a href="../../index.php" class="btn-outline">← Kembali ke Beranda</a>
            </div>

            <p class="text-center mt-6 text-gray-600">
                Belum punya akun?
                <a href="register.php" class="text-blue-600 font-medium hover:underline transition">Daftar di sini</a>
            </p>
        </div>

        <!-- Bagian Dekorasi -->
        <div class="relative hidden md:flex items-center justify-center bg-gradient-to-tr from-blue-100 via-cyan-100 to-white overflow-hidden">
            <!-- Bubble Layer -->
            <div class="absolute inset-0 flex justify-center items-center">
                <div class="absolute w-64 h-64 bg-blue-200 rounded-full opacity-20 blur-3xl animate-float"></div>
                <div class="absolute w-80 h-80 bg-cyan-300 rounded-full opacity-20 blur-3xl animate-pulseSlow" style="animation-delay:-3s;"></div>
            </div>
            <!-- SVG Icon Overlay -->
            <div class="relative z-10 grid grid-cols-3 gap-8">
                <svg class="w-16 h-16 text-blue-400 animate-float" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <svg class="w-16 h-16 text-cyan-400 animate-pulseSlow" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                <svg class="w-16 h-16 text-sky-400 animate-float" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <svg class="w-16 h-16 text-blue-300 animate-pulseSlow" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                <svg class="w-16 h-16 text-cyan-300 animate-float" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="3"></circle>
                    <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06a1.65 1.65 0 001.82.33H9a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06a1.65 1.65 0 00-.33 1.82V9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z"></path>
                </svg>
                <svg class="w-16 h-16 text-sky-300 animate-pulseSlow" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>
</body>
</html>