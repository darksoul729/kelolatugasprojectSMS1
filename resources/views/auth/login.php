<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Masuk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        float: 'float 6s ease-in-out infinite',
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
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4">

    <!-- Background animasi lembut -->
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-blue-100 rounded-full opacity-30 animate-float"></div>
        <div class="absolute top-1/3 right-1/4 w-72 h-72 bg-cyan-100 rounded-full opacity-30 animate-float" style="animation-delay: -2s;"></div>
        <div class="absolute bottom-1/4 left-1/2 w-60 h-60 bg-sky-100 rounded-full opacity-30 animate-float" style="animation-delay: -4s;"></div>
    </div>

    <!-- Kartu Login -->
    <div class="w-full max-w-md">
        <div class="bg-white rounded-xl shadow-lg p-7 md:p-8">

            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-800">Masuk ke Akun Anda</h2>
                <p class="text-gray-600 mt-2">Gunakan email dan password Anda</p>
            </div>

            <!-- Notifikasi -->
            <?php if (isset($_SESSION['message'])): ?>
                <div class="mb-6 p-3 rounded-lg border 
                    <?php if ($_SESSION['message']['type'] === 'danger'): ?>
                        bg-red-50 border-red-200 text-red-700
                    <?php else: ?>
                        bg-green-50 border-green-200 text-green-700
                    <?php endif; ?>">
                    <span class="font-medium"><?= htmlspecialchars($_SESSION['message']['text']) ?></span>
                </div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <form method="POST" action="?route=auth/doLogin">
                <!-- Email -->
                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H8m8 0a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            required
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Masukkan email"
                        />
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            required
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="••••••••"
                        />
                    </div>
                </div>

                <!-- Tombol Login -->
                <button
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg shadow transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    Masuk
                </button>
            </form>

            <!-- Footer -->
            <div class="mt-6 text-center">
                <a href="?route=home" class="text-sm text-gray-600 hover:text-gray-900 transition">← Kembali ke Beranda</a>
            </div>

            <div class="mt-4 text-center text-sm text-gray-600">
                Belum punya akun?
                <a href="?route=auth/register" class="text-blue-600 font-medium hover:underline">Daftar di sini</a>
            </div>
        </div>
    </div>
</body>
</html>
