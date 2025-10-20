<?php
$folderLampiran = __DIR__ . '/public/uploads/tugas/';
$filesLampiran = glob($folderLampiran . '*.{jpg,jpeg,png,gif,pdf,docx}', GLOB_BRACE);

if (!empty($filesLampiran)):
?>
<section>
    <h2 class="text-lg font-semibold mb-3 text-gray-800">Lampiran</h2>
    <div class="flex flex-wrap gap-3">
        <?php foreach ($filesLampiran as $file):
            $fileUrl = '/uploads/tugas/' . basename($file); // URL untuk browser
        ?>
            <a href="<?= htmlspecialchars($fileUrl) ?>" target="_blank"
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
               <?= htmlspecialchars(basename($file)) ?>
            </a>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>
