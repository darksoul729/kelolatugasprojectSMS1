<?php
// $tugas harus dikirim dari halaman pemanggil
?>
<div class="bg-white rounded-xl shadow overflow-hidden table-wrapper">
    <?php if (!empty($tugas)): ?>
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">#</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Judul Tugas</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Kategori</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Deadline</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Poin</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach ($tugas as $i => $t): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-gray-600"><?= $i + 1 ?></td>
                        <td class="px-4 py-3 font-medium text-gray-900"><?= htmlspecialchars($t['judul_tugas']) ?></td>
                        <td class="px-4 py-3 text-gray-600"><?= htmlspecialchars($t['nama_kategori'] ?? '-') ?></td>
                        <td class="px-4 py-3 text-gray-600"><?= htmlspecialchars($t['tanggal_deadline']) ?></td>
                        <td class="px-4 py-3 text-gray-600"><?= htmlspecialchars($t['poin_nilai']) ?></td>
                        <td class="px-4 py-3 text-center">
                            <a href="?route=guru/tugas/detail&id=<?= $t['id_tugas'] ?>"
                               class="px-3 py-1 bg-green-500 text-white text-xs rounded-md hover:bg-green-600 transition">
                                Detail
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="px-6 py-10 text-center text-gray-500">
            Belum ada tugas yang Anda buat.
        </div>
    <?php endif; ?>
</div>
