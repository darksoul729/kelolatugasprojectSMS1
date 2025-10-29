<?php
if (!function_exists('e')) {
    function e($value) {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}
?>

<div class="p-4 bg-white rounded shadow">
  <h2 class="text-2xl text-blue-700 font-bold mb-4">
    Daftar Siswa Kelas <?= e($kelas ?? '') ?>
  </h2>

  <div class="bg-white overflow-x-auto rounded-2xl shadow-sm border border-gray-100">
    <table class="min-w-full border-collapse text-sm">
      <thead class="bg-blue-50 border-b border-gray-200 text-gray-700">
        <tr>
          <th class="px-4 py-2 border">No</th>
          <th class="px-4 py-2 border">Nama Lengkap</th>
          <th class="px-4 py-2 border">NIS</th>
          <th class="px-4 py-2 border">Kelas</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        <?php if (empty($siswa)): ?>
          <tr>
            <td colspan="4" class="py-6 text-center text-gray-500 italic">
              Belum ada siswa di kelas ini.
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($siswa as $index => $item): ?>
            <tr class="<?= $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' ?> hover:bg-gray-50 transition">
              <td class="px-4 py-2 border text-center"><?= e($index + 1) ?></td>
              <td class="px-4 py-2 border"><?= e($item['nama_lengkap'] ?? '') ?></td>
              <td class="px-4 py-2 border text-center"><?= e($item['nip_nis'] ?? '') ?></td>
              <td class="px-4 py-2 border text-center"><?= e($item['kelas'] ?? '') ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
