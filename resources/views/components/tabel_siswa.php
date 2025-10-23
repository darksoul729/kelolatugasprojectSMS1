<div class="p-4 bg-white rounded shadow">
    <h2 class="text-xl font-semibold mb-4">
        Daftar Siswa Kelas <?= htmlspecialchars($kelas ?? '') ?>
    </h2>

    <?php if (!empty($siswa)): ?>
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">No</th>
                    <th class="px-4 py-2 border">Nama Lengkap</th>
                    <th class="px-4 py-2 border">NIS</th>
                    <th class="px-4 py-2 border">Kelas</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($siswa as $index => $item): ?>
                <tr class="<?= $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' ?>">
                    <td class="px-4 py-2 border"><?= $index + 1 ?></td>
                    <td class="px-4 py-2 border"><?= htmlspecialchars($item['nama_lengkap']) ?></td>
                    <td class="px-4 py-2 border"><?= htmlspecialchars($item['nip_nis']) ?></td>
                    <td class="px-4 py-2 border"><?= htmlspecialchars($item['kelas']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <p class="text-gray-500">Belum ada siswa di kelas ini.</p>
    <?php endif; ?>
</div>
