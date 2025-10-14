<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tugas</title>
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Heroicons -->
    <script src="https://cdn.jsdelivr.net/npm/heroicons@2.0.18/dist/outline.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-blue': '#4A7AFF',
                        'primary-blue-light': '#E6EEFF',
                        'success-green': '#4CAF50',
                        'warning-orange': '#FFC107',
                        'danger-red': '#F44336',
                        'text-dark': '#333333',
                        'text-muted': '#666666',
                        'border-divider': '#CCCCCC',
                        'background-light': '#F8F8F8',
                        'background-white': '#FFFFFF',
                    }
                }
            }
        }
    </script>
    <style>
        .hero-icon {
            width: 24px;
            height: 24px;
            display: inline-block;
        }
        .card-hover {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        }
        .modal-enter {
            animation: modalEnter 0.2s ease-out;
        }
        .modal-exit {
            animation: modalExit 0.2s ease-in;
        }
        @keyframes modalEnter {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        @keyframes modalExit {
            from { opacity: 1; transform: scale(1); }
            to { opacity: 0; transform: scale(0.95); }
        }
    </style>
</head>
<body class="bg-background-light text-text-dark min-h-screen font-sans">
    <div class="container mx-auto px-4 py-8 max-w-screen-xl"> <!-- Lebih lebar di desktop -->
        <!-- Header -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 p-4 bg-background-white rounded-2xl shadow-sm">
            <div>
                <h1 class="text-2xl font-bold text-text-dark flex items-center">
                    <svg class="hero-icon mr-2"><use href="#book-open-icon"/></svg>
                    Daftar Tugas Kamu
                </h1>
                <p class="text-text-muted text-sm mt-1">Kelola tugas dan proyekmu dengan mudah</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                <a href="?route=tugas/tambah" class="bg-primary-blue text-background-white px-5 py-2.5 rounded-lg hover:bg-blue-600 transition flex items-center justify-center shadow-sm">
                    <svg class="hero-icon mr-1.5"><use href="#plus-circle-icon"/></svg>
                    Tambah Tugas
                </a>
                <!-- Tombol Profile -->
                <button onclick="openProfileModal()" class="flex items-center justify-center w-10 h-10 rounded-full bg-primary-blue text-white hover:bg-blue-600 transition">
                    <svg class="hero-icon"><use href="#user-icon"/></svg>
                </button>
            </div>
        </header>

        <!-- Konten Utama -->
        <main>
            <?php if (empty($projects)): ?>
                <div class="text-center py-16 bg-background-white rounded-2xl shadow-sm">
                    <svg class="hero-icon w-16 h-16 mx-auto text-text-muted"><use href="#clipboard-document-list-icon"/></svg>
                    <h3 class="text-xl font-semibold text-text-dark mt-4">Belum ada tugas</h3>
                    <p class="text-text-muted mt-2">Mulai tambahkan tugas pertamamu sekarang!</p>
                    <a href="?route=tugas/tambah" class="mt-4 inline-block bg-primary-blue text-background-white px-5 py-2.5 rounded-lg hover:bg-blue-600 transition shadow-sm">
                        Buat Tugas Baru
                    </a>
                </div>
            <?php else: ?>
                <!-- Grid Card -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                    <?php foreach ($projects as $p): ?>
                        <div class="bg-background-white rounded-2xl shadow-sm border border-border-divider card-hover overflow-hidden">
                            <!-- Header Card -->
                            <div class="p-4 bg-primary-blue-light">
                                <h2 class="text-lg font-semibold text-primary-blue truncate"><?= htmlspecialchars($p['judul']) ?></h2>
                            </div>

                            <!-- Isi Card -->
                            <div class="p-4">
                                <p class="text-text-muted text-sm mb-3 line-clamp-3"><?= htmlspecialchars($p['deskripsi']) ?></p>
                                <div class="flex justify-between items-center text-xs mb-3">
                                    <span class="text-warning-orange font-semibold">
                                        Deadline: <?= htmlspecialchars($p['deadline']) ?>
                                    </span>
                                    <span class="text-text-muted">
                                        Tipe: <?= htmlspecialchars($p['tipe_tugas']) ?>
                                    <div class="flex space-x-2">
                                        <a href="?route=tugas/detail&project_id=<?= $p['project_id'] ?>" class="text-primary-blue hover:underline">
                                            Detail
                                        </a>
                                        <button onclick="openDeleteModal(<?= $p['project_id'] ?>)" class="text-danger-red hover:underline">
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            <?php endif; ?>
        </main>

    </div>

    <!-- Modal Profil -->
    <div id="profile-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div id="profile-modal-content" class="bg-background-white rounded-xl shadow-lg w-full max-w-md p-6 relative modal-enter">
            <button onclick="closeProfileModal()" class="absolute top-4 right-4 text-text-muted hover:text-danger-red text-xl">&times;</button>
            <h3 class="text-xl font-bold text-text-dark mb-4 flex items-center">
                <svg class="hero-icon mr-2"><use href="#user-icon"/></svg>
                Profil Saya
            </h3>
            <div class="text-text-muted">
                <p class="mb-2"><strong>Nama:</strong> <?= htmlspecialchars($_SESSION['username'] ?? 'User') ?></p>
                <p class="mb-2"><strong>Role:</strong> <?= htmlspecialchars($_SESSION['role'] ?? 'siswa') ?></p>
                <p class="mb-4"><strong>Terdaftar:</strong> <?= date('d M Y') ?></p>
                <div class="flex justify-end space-x-3">
                    <button onclick="closeProfileModal()" class="px-4 py-2 text-text-dark hover:bg-gray-100 rounded-lg transition">Tutup</button>
                    <button class="px-4 py-2 bg-primary-blue text-background-white rounded-lg hover:bg-blue-600 transition">
                        Edit Profil
                    </button>
                </div>
                 
                <!-- Tombol Logout -->
                <div class="mt-6 pt-4 border-t border-border-divider">
                    <a href="/includes/logout.php" class="w-full flex items-center justify-center px-4 py-2 bg-danger-red text-background-white rounded-lg hover:bg-red-600 transition">
                        <svg class="hero-icon mr-2"><use href="#arrow-left-on-rectangle-icon"/></svg>
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Logout -->
    <div id="logout-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div id="logout-modal-content" class="bg-background-white rounded-xl shadow-lg w-full max-w-md p-6 relative modal-enter">
            <h3 class="text-xl font-bold text-text-dark mb-4 flex items-center">
                <svg class="hero-icon mr-2"><use href="#exclamation-triangle-icon"/></svg>
                Konfirmasi Logout
            </h3>
            <p class="text-text-muted mb-4">Apakah Anda yakin ingin logout?</p>
            <div class="flex justify-end space-x-3">
                <button onclick="closeLogoutModal()" class="px-4 py-2 text-text-dark hover:bg-gray-100 rounded-lg transition">Batal</button>
                <a href="/includes/logout.php" class="px-4 py-2 bg-danger-red text-background-white rounded-lg hover:bg-red-600 transition">Logout</a>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div id="delete-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div id="delete-modal-content" class="bg-background-white rounded-xl shadow-lg w-full max-w-md p-6 relative modal-enter">
            <h3 class="text-xl font-bold text-text-dark mb-4 flex items-center">
                <svg class="hero-icon mr-2"><use href="#exclamation-triangle-icon"/></svg>
                Konfirmasi Hapus
            </h3>
            <p class="text-text-muted mb-4">Apakah Anda yakin ingin menghapus tugas ini?</p>
            <div class="flex justify-end space-x-3">
                <button onclick="closeDeleteModal()" class="px-4 py-2 text-text-dark hover:bg-gray-100 rounded-lg transition">Batal</button>
                <a id="delete-link" href="#" class="px-4 py-2 bg-danger-red text-background-white rounded-lg hover:bg-red-600 transition">Hapus</a>
            </div>
        </div>
    </div>

    <!-- SVG Icons -->
    <svg style="display: none;" xmlns="http://www.w3.org/2000/svg">
        <!-- Icon Book Open -->
        <symbol id="book-open-icon" viewBox="0 0 24 24" fill="currentColor">
            <path d="M11.25 4.533A9.707 9.707 0 0 0 6 3a9.735 9.735 0 0 0-3.25.555.75.75 0 0 0-.5.707v14.25a.75.75 0 0 0 1 .707A8.237 8.237 0 0 1 6 18.75c1.995 0 3.823.707 5.25 1.886V4.533ZM12.75 20.636A8.214 8.214 0 0 1 18 18.75c.966 0 1.89.166 2.75.47a.75.75 0 0 0 1-.708V4.262a.75.75 0 0 0-.5-.707A9.735 9.735 0 0 0 18 3a9.707 9.707 0 0 0-5.25 1.533v16.103Z" />
        </symbol>

        <!-- Icon Plus Circle -->
        <symbol id="plus-circle-icon" viewBox="0 0 24 24" fill="currentColor">
            <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 9a.75.75 0 0 0-1.5 0v2.25H9a.75.75 0 0 0 0 1.5h2.25V15a.75.75 0 0 0 1.5 0v-2.25H15a.75.75 0 0 0 0-1.5h-2.25V9Z" clip-rule="evenodd" />
        </symbol>

        <!-- Icon User -->
        <symbol id="user-icon" viewBox="0 0 24 24" fill="currentColor">
            <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
        </symbol>

        <!-- Icon Clipboard Document List -->
        <symbol id="clipboard-document-list-icon" viewBox="0 0 24 24" fill="currentColor">
            <path fill-rule="evenodd" d="M3 3a1 1 0 0 0 0 2v12a3 3 0 0 0 3 3h12a3 3 0 0 0 3-3V5a1 1 0 1 0 0-2H3Zm1 2h16v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V5Zm5 2a1 1 0 0 1 1-1h6a1 1 0 1 1 0 2H9a1 1 0 0 1-1-1Zm-2 4a1 1 0 1 0 0 2h10a1 1 0 1 0 0-2H7Zm0 4a1 1 0 1 0 0 2h10a1 1 0 1 0 0-2H7Z" clip-rule="evenodd" />
        </symbol>

        <!-- Icon Exclamation Triangle -->
        <symbol id="exclamation-triangle-icon" viewBox="0 0 24 24" fill="currentColor">
            <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
        </symbol>
    </svg>

    <script>
        // Modal Functions
        function openProfileModal() {
            document.getElementById('profile-modal').classList.remove('hidden');
            setTimeout(() => {
                document.getElementById('profile-modal-content').classList.add('modal-enter');
            }, 10);
        }

        function closeProfileModal() {
            const modal = document.getElementById('profile-modal-content');
            modal.classList.remove('modal-enter');
            modal.classList.add('modal-exit');
            setTimeout(() => {
                document.getElementById('profile-modal').classList.add('hidden');
                modal.classList.remove('modal-exit');
            }, 200);
        }

        function openLogoutModal() {
            document.getElementById('logout-modal').classList.remove('hidden');
            setTimeout(() => {
                document.getElementById('logout-modal-content').classList.add('modal-enter');
            }, 10);
        }

        function closeLogoutModal() {
            const modal = document.getElementById('logout-modal-content');
            modal.classList.remove('modal-enter');
            modal.classList.add('modal-exit');
            setTimeout(() => {
                document.getElementById('logout-modal').classList.add('hidden');
                modal.classList.remove('modal-exit');
            }, 200);
        }

        function openDeleteModal(projectId) {
            document.getElementById('delete-modal').classList.remove('hidden');
            document.getElementById('delete-link').href = `?route=tugas/delete&project_id=${projectId}`;
            setTimeout(() => {
                document.getElementById('delete-modal-content').classList.add('modal-enter');
            }, 10);
        }

        function closeDeleteModal() {
            const modal = document.getElementById('delete-modal-content');
            modal.classList.remove('modal-enter');
            modal.classList.add('modal-exit');
            setTimeout(() => {
                document.getElementById('delete-modal').classList.add('hidden');
                modal.classList.remove('modal-exit');
            }, 200);
        }
    </script>
</body>
</html>