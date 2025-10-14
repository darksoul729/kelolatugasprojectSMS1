<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Tugas</title>
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
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <!-- Header -->
        <header class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-text-dark flex items-center">
                <svg class="hero-icon mr-2"><use href="#document-text-icon"/></svg>
                Detail Tugas: <?= htmlspecialchars($project['judul']) ?>
            </h1>
            <a href="?route=tugas/list" class="flex items-center text-primary-blue hover:underline">
                <svg class="hero-icon mr-1"><use href="#arrow-left-icon"/></svg>
                Kembali
            </a>
        </header>

        <!-- Info Tugas -->
        <div class="bg-background-white rounded-2xl shadow-sm p-6 mb-8 card-hover">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-text-dark">Deskripsi</h3>
                    <p class="text-text-muted mt-1"><?= nl2br(htmlspecialchars($project['deskripsi'])) ?></p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-text-dark">Deadline</h3>
                    <p class="text-warning-orange font-medium mt-1"><?= htmlspecialchars($project['deadline']) ?></p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-text-dark">Tipe Tugas</h3>
                    <p class="text-text-muted mt-1"><?= htmlspecialchars($project['tipe_tugas']) ?></p>
            </div>
        </div>

        <!-- Progress -->
        <div class="bg-background-white rounded-2xl shadow-sm p-6 mb-8 card-hover">
            <h2 class="text-xl font-bold text-text-dark mb-4 flex items-center">
                <svg class="hero-icon mr-2"><use href="#chart-bar-icon"/></svg>
                Progress
            </h2>
            <form action="?route=tugas/addProgress" method="POST" class="flex gap-2 mb-4">
                <input type="hidden" name="project_id" value="<?= $project['id'] ?>">
                <input type="text" name="keterangan" placeholder="Tambah progress..." class="flex-1 px-4 py-2 border border-border-divider rounded-lg focus:ring-primary-blue focus:border-primary-blue" required>
                <button type="submit" class="bg-primary-blue text-background-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                    Tambah
                </button>
            </form>

            <?php if (!empty($progress_updates)): ?>
                <ul class="space-y-3">
                    <?php foreach ($progress_updates as $update): ?>
                        <li class="p-3 bg-primary-blue-light rounded-lg flex justify-between items-start">
                            <p class="text-text-dark"><?= nl2br(htmlspecialchars($update['keterangan'])) ?></p>
                            <a href="?route=tugas/deleteProgress&update_id=<?= $update['id'] ?>&project_id=<?= $project['id'] ?>" onclick="return confirm('Hapus progress ini?')" class="text-danger-red hover:text-red-700 ml-3">
                                <svg class="hero-icon"><use href="#trash-icon"/></svg>
                            </a>
                        </li>
                    <?php endforeach ?>
                </ul>
            <?php else: ?>
                <p class="text-text-muted italic">Belum ada progress.</p>
            <?php endif; ?>
        </div>

        <!-- To-Do List -->
        <div class="bg-background-white rounded-2xl shadow-sm p-6 card-hover">
            <h2 class="text-xl font-bold text-text-dark mb-4 flex items-center">
                <svg class="hero-icon mr-2"><use href="#check-circle-icon"/></svg>
                To-Do List
            </h2>
            <form action="?route=todo/tambah" method="POST" class="flex gap-2 mb-4">
                <input type="hidden" name="project_id" value="<?= $project['project_id'] ?>">
                <input type="text" name="deskripsi" placeholder="Tambahkan kegiatan..." class="flex-1 px-4 py-2 border border-border-divider rounded-lg focus:ring-primary-blue focus:border-primary-blue" required>
                <button type="submit" class="bg-primary-blue text-background-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                    Tambah
                </button>
            </form>

           <?php if (!empty($todos)): ?>
    <ul class="space-y-3">
        <?php foreach ($todos as $todo): ?>
            <li class="p-3 rounded-lg flex justify-between items-center <?= $todo['is_done'] ? 'bg-success-green bg-opacity-10' : 'bg-background-light'; ?>">
                
                <form action="?route=todo/update" method="POST" class="flex items-center">
                    <input type="hidden" name="project_id" value="<?= $project['project_id'] ?>">
                    <input type="hidden" name="todo_id" value="<?= $todo['todo_id'] ?>">
                    <input type="hidden" name="deskripsi" value="<?= htmlspecialchars($todo['deskripsi']) ?>">
                    <input type="checkbox" name="is_done" value="1"
                           <?= $todo['is_done'] ? 'checked' : '' ?>
                           onchange="this.form.submit()" class="mr-3">
                    <span class="<?= $todo['is_done'] ? 'line-through text-text-muted' : 'text-text-dark'; ?>">
                        <?= htmlspecialchars($todo['deskripsi']) ?>
                    </span>
                </form>

                <a href="?route=todo/delete&todo_id=<?= $todo['todo_id'] ?>&project_id=<?= $project['project_id'] ?>"
                   onclick="return confirm('Hapus to-do ini?')"
                   class="text-danger-red hover:text-red-700 ml-3">
                    <svg class="hero-icon"><use href="#trash-icon"/></svg>
                </a>

            </li>
        <?php endforeach ?>
    </ul>
<?php else: ?>
    <p class="text-text-muted italic">Belum ada to-do list.</p>
<?php endif; ?>

        </div>
    </div>

    <!-- SVG Icons -->
    <svg style="display: none;" xmlns="http://www.w3.org/2000/svg">
        <!-- Icon Document Text -->
        <symbol id="document-text-icon" viewBox="0 0 24 24" fill="currentColor">
            <path fill-rule="evenodd" d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625ZM7.5 15a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5A.75.75 0 0 1 7.5 15Zm.75 2.25a.75.75 0 0 0 0 1.5H12a.75.75 0 0 0 0-1.5H8.25Z" clip-rule="evenodd" />
        </symbol>

        <!-- Icon Arrow Left -->
        <symbol id="arrow-left-icon" viewBox="0 0 24 24" fill="currentColor">
            <path fill-rule="evenodd" d="M7.28 7.72a.75.75 0 0 1 0 1.06l-2.47 2.47H21a.75.75 0 0 1 0 1.5H4.81l2.47 2.47a.75.75 0 1 1-1.06 1.06l-3.75-3.75a.75.75 0 0 1 0-1.06l3.75-3.75a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
        </symbol>

        <!-- Icon Chart Bar -->
        <symbol id="chart-bar-icon" viewBox="0 0 24 24" fill="currentColor">
            <path d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75ZM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 0 1-1.875-1.875V8.625ZM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 0 1 3 19.875v-6.75Z" />
        </symbol>

        <!-- Icon Check Circle -->
        <symbol id="check-circle-icon" viewBox="0 0 24 24" fill="currentColor">
            <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.06-1.06L9.75 13.94 7.47 11.66a.75.75 0 0 0-1.06 1.06l3.06 3.06c.15.15.36.22.57.22s.42-.07.57-.22l5.44-5.44Z" clip-rule="evenodd" />
        </symbol>

        <!-- Icon Trash -->
        <symbol id="trash-icon" viewBox="0 0 24 24" fill="currentColor">
            <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 1 0 1.5.058l.345-9Z" clip-rule="evenodd" />
        </symbol>
    </svg>
</body>
</html>