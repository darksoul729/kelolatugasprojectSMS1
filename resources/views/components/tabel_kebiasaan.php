<?php if (!empty($anakkebiasaan)): ?>
    <div class="bg-white rounded-xl shadow overflow-hidden table-wrapper">
        <table class="min-w-full border-collapse border border-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-3 px-4 border border-gray-200 text-left text-xs font-semibold text-gray-500 uppercase">Nama</th>
                    <th class="py-3 px-4 border border-gray-200 text-left text-xs font-semibold text-gray-500 uppercase">Kelas</th>
                    <th class="py-3 px-4 border border-gray-200 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($anakkebiasaan as $anak): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-3 px-4 border border-gray-200 text-gray-800"><?= htmlspecialchars($anak['nama_lengkap']) ?></td>
                        <td class="py-3 px-4 border border-gray-200 text-gray-600"><?= htmlspecialchars($anak['kelas']) ?></td>
                        <td class="py-3 px-4 border border-gray-200 text-gray-600"><?= htmlspecialchars($anak['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="bg-white p-8 rounded-xl shadow text-center">
        <p class="text-gray-500 text-lg">Belum ada data kebiasaan.</p>
    </div>
<?php endif; ?>