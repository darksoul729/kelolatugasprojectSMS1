<?php
include '../../includes/session_check.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: /login.php");
    exit;
}

include '../../includes/db_config.php';

$stmt = $pdo->prepare("
    SELECT p.project_id, p.judul, p.deskripsi, p.deadline, p.status, p.created_at,
           u.nama_lengkap AS nama_siswa,
           (SELECT COUNT(*) FROM todo_list WHERE project_id = p.project_id) AS total_todos,
           (SELECT COUNT(*) FROM todo_list WHERE project_id = p.project_id AND is_done = 1) AS done_todos,
           (SELECT keterangan FROM progress_updates WHERE project_id = p.project_id ORDER BY tanggal_update DESC LIMIT 1) AS last_update
    FROM projects p
    JOIN users u ON p.user_id = u.user_id
    ORDER BY p.created_at DESC
");

$stmt->execute();
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'merah': '#e74c3c',
            'biru': '#3498db',
            'hijau': '#2ecc71',
          }
        }
      }
    }
  </script>
  <style>
    .status-pending { background-color: #f39c12; color: white; padding: 4px 8px; border-radius: 4px; }
    .status-progress { background-color: #3498db; color: white; padding: 4px 8px; border-radius: 4px; }
    .status-done { background-color: #2ecc71; color: white; padding: 4px 8px; border-radius: 4px; }
    .modal-overlay {
      background-color: rgba(0,0,0,0.5);
      backdrop-filter: blur(4px);
    }
    .animate-pulse-fast {
      animation: pulse 1s infinite;
    }
    @keyframes pulse {
      0%, 100% { opacity: 1; }
      50% { opacity: 0.5; }
    }
  </style>
</head>
<body class="font-sans bg-gray-100 text-gray-800">

  <header class="bg-white shadow-md py-4 px-6 flex justify-between items-center">
    <h1 class="text-xl font-bold text-gray-800">Dashboard Admin</h1>
    <div class="flex items-center space-x-4">
      <span class="text-gray-600">Halo, <?php echo htmlspecialchars($_SESSION['username']); ?> (Admin)</span>
      <button onclick="openLogoutModal()" class="text-red-500 hover:text-red-700">Logout</button>
    </div>
  </header>

  <main class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-6">Progress Project Siswa</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach ($projects as $project): ?>
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow border-l-4 border-<?php echo $project['status'] === 'pending' ? 'yellow' : ($project['status'] === 'progress' ? 'blue' : 'green'); ?>-500">
          <div class="flex justify-between items-start">
            <div>
              <h3 class="text-lg font-bold text-gray-800"><?php echo htmlspecialchars($project['judul']); ?></h3>
              <p class="text-gray-600 text-sm"><?php echo htmlspecialchars($project['nama_siswa']); ?></p>
            </div>
            <span class="status-<?php echo $project['status']; ?> text-sm font-medium">
              <?php echo ucfirst($project['status']); ?>
            </span>
          </div>

          <div class="mt-4">
            <p class="text-gray-700"><?php echo htmlspecialchars($project['deskripsi']); ?></p>
            <div class="mt-3 text-sm text-gray-500">
              <p>Deadline: <?php echo $project['deadline'] ? $project['deadline'] : '-'; ?></p>
              <p class="mt-1">Progress Terakhir: <?php echo htmlspecialchars($project['last_update'] ?? '-'); ?></p>
            </div>
          </div>

          <div class="mt-4 flex justify-between items-center">
            <div class="text-sm">
              <span class="font-bold"><?php echo $project['done_todos']; ?></span> dari <?php echo $project['total_todos']; ?> tugas selesai
            </div>
            <button onclick="openLoadingModal(<?php echo $project['project_id']; ?>)" class="text-biru hover:underline">Lihat Detail →</button>
          </div>

          <?php if ($project['total_todos'] > 0): ?>
            <div class="mt-3 w-full bg-gray-200 rounded-full h-2">
              <div class="bg-hijau h-2 rounded-full" style="width: <?php echo ($project['done_todos'] / $project['total_todos']) * 100; ?>%"></div>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </main>


  <div id="logoutModal" class="fixed inset-0 hidden modal-overlay z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 w-96 shadow-xl">
      <h3 class="text-lg font-bold mb-4">Konfirmasi Logout</h3>
      <p class="text-gray-600 mb-6">Apakah kamu yakin ingin keluar?</p>
      <div class="flex justify-end space-x-3">
        <button onclick="closeLogoutModal()" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded">Batal</button>
        <a href="/includes/logout.php" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Logout</a>
      </div>
    </div>
  </div>

  <div id="loadingModal" class="fixed inset-0 hidden modal-overlay z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl p-8 w-96 shadow-xl text-center">
      <div class="w-16 h-16 mx-auto mb-4 animate-pulse-fast bg-biru rounded-full"></div>
      <h3 class="text-lg font-bold mb-2">Mengambil Detail Project</h3>
      <p class="text-gray-600 mb-4">Harap tunggu sebentar...</p>
      <div class="w-full bg-gray-200 rounded-full h-2.5">
        <div id="loadingBar" class="bg-hijau h-2.5 rounded-full" style="width: 0%"></div>
      </div>
      <p id="loadingText" class="text-sm mt-2 text-gray-500">Memuat: 0%</p>
    </div>
  </div>

  <script>
    let currentProjectId = null;

    function openLogoutModal() {
      document.getElementById('logoutModal').classList.remove('hidden');
    }

    function closeLogoutModal() {
      document.getElementById('logoutModal').classList.add('hidden');
    }

    function openLoadingModal(projectId) {
      currentProjectId = projectId; 
      document.getElementById('loadingModal').classList.remove('hidden');
      simulateLoading();
    }

    function simulateLoading() {
      const bar = document.getElementById('loadingBar');
      const text = document.getElementById('loadingText');
      let width = 0;
      const interval = setInterval(() => {
        if (width >= 100) {
          clearInterval(interval);
          setTimeout(() => {
            document.getElementById('loadingModal').classList.add('hidden');
            window.location.href = `view_project.php?id=${currentProjectId}`;
          }, 500);
        } else {
          width += 5;
          bar.style.width = width + '%';
          text.textContent = `Memuat: ${width}%`;
        }
      }, 100);
    }

    window.onclick = function(event) {
      const logoutModal = document.getElementById('logoutModal');
      const loadingModal = document.getElementById('loadingModal');
      if (event.target === logoutModal) {
        logoutModal.classList.add('hidden');
      }
      if (event.target === loadingModal) {
        loadingModal.classList.add('hidden');
      }
    }
  </script>

</body>
</html>
