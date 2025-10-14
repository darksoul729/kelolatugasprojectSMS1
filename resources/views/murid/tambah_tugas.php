<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Tugas</title>
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
            width: 20px;
            height: 20px;
            display: inline-block;
        }
        .card-hover {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card-hover:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
        }
    </style>
</head>
<body class="bg-background-light text-text-dark min-h-screen font-sans">
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <!-- Header -->
        <header class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-text-dark flex items-center">
                <svg class="hero-icon mr-2"><use href="#document-add-icon"/></svg>
                Tambah Tugas Baru
            </h1>
            <a href="?route=tugas/list" class="flex items-center text-primary-blue hover:underline">
                <svg class="hero-icon mr-1"><use href="#arrow-left-icon"/></svg>
                Kembali
            </a>
        </header>

        <!-- Form Tambah Tugas -->
        <div class="bg-background-white rounded-2xl shadow-sm p-6 card-hover">
            <form action="?route=tugas/store" method="POST">
                <div class="mb-4">
                    <label class="block text-text-dark font-medium mb-1">Judul</label>
                    <input type="text" name="judul" class="w-full px-4 py-2 border border-border-divider rounded-lg focus:ring-primary-blue focus:border-primary-blue" required>
                </div>

                <div class="mb-4">
                    <label class="block text-text-dark font-medium mb-1">Deskripsi</label>
                    <textarea name="deskripsi" class="w-full px-4 py-2 border border-border-divider rounded-lg focus:ring-primary-blue focus:border-primary-blue" required></textarea>
                </div>

                <div class="mb-6">
                    <label for="tugas" class="block text-text-dark font-medium mb-1">Pilihan Tugas</label>
                    <select id="tugas" name="tugas">
                    <option value="Teori">Teori</option>
                    <option value="Praktikum">Praktikum</option>
                    <option value="Proyek">Proyek</option>
                </div>

                <label for="tugas">Pilih Tugas</label>
                   
                </select>

            
                <div class="mb-6">
                    <label class="block text-text-dark font-medium mb-1">Deadline</label>
                    <input type="date" name="deadline" class="w-full px-4 py-2 border border-border-divider rounded-lg focus:ring-primary-blue focus:border-primary-blue" required>
                </div>
                

                <div class="flex justify-end space-x-3">
                    <a href="?route=tugas/list" class="px-4 py-2 text-text-dark hover:bg-gray-100 rounded-lg transition">
                        Batal
                    </a>
                    <button type="submit" class="bg-primary-blue text-background-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- SVG Icons -->
    <svg style="display: none;" xmlns="http://www.w3.org/2000/svg">
        <!-- Icon Document Add -->
        <symbol id="document-add-icon" viewBox="0 0 24 24" fill="currentColor">
            <path fill-rule="evenodd" d="M6.32 2.577a49.255 49.255 0 0 1 11.36 0c1.497.174 2.57 1.46 2.57 2.93V21a.75.75 0 0 1-1.085.67L12 18.089l-7.165 3.583A.75.75 0 0 1 3.75 21V5.507c0-1.47 1.073-2.756 2.57-2.93Z" clip-rule="evenodd" />
        </symbol>

        <!-- Icon Arrow Left -->
        <symbol id="arrow-left-icon" viewBox="0 0 24 24" fill="currentColor">
            <path fill-rule="evenodd" d="M7.28 7.72a.75.75 0 0 1 0 1.06l-2.47 2.47H21a.75.75 0 0 1 0 1.5H4.81l2.47 2.47a.75.75 0 1 1-1.06 1.06l-3.75-3.75a.75.75 0 0 1 0-1.06l3.75-3.75a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
        </symbol>
    </svg>
</body>
</html>