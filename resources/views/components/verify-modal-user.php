<div id="verifyModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
  <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 mx-auto">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Verifikasi User</h3>
    <form method="POST" action="?route=admin/users/verify">
        <!-- ID user akan diisi secara dinamis -->
        <input type="hidden" name="id_user" id="verify-user-id">

        <!-- Pilihan Peran -->
        <div class="mb-3">
            <label class="block text-sm font-medium text-gray-700">Peran</label>
            <select name="peran" id="verify-peran" class="mt-1 block w-full border rounded-md px-3 py-2">
                <option value="siswa">Siswa</option>
                <option value="guru">Guru</option>
            </select>
        </div>

        <!-- Wali Kelas (hanya untuk guru) -->
        <div class="mb-3 hidden" id="waliKelasContainer">
            <label class="block text-sm font-medium text-gray-700">Wali Kelas</label>
            <select name="wali_kelas" id="verify-wali-kelas" class="mt-1 block w-full border rounded-md px-3 py-2">
                <option value="">-- Pilih Wali Kelas --</option>
                <!-- Kelas 7 -->
                <option value="7A">7 A</option>
                <option value="7B">7 B</option>
                <option value="7C">7 C</option>
                <option value="7D">7 D</option>
                <!-- Kelas 8 -->
                <option value="8A">8 A</option>
                <option value="8B">8 B</option>
                <option value="8C">8 C</option>
                <option value="8D">8 D</option>
                <!-- Kelas 9 -->
                <option value="9A">9 A</option>
                <option value="9B">9 B</option>
                <option value="9C">9 C</option>
                <option value="9D">9 D</option>
            </select>
        </div>

        <!-- Tombol aksi -->
        <div class="flex justify-end gap-3">
            <button type="button" onclick="closeVerifyModal()" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Batal</button>
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Verifikasi</button>
        </div>
    </form>
  </div>
</div>

<script>
    const verifyPeran = document.getElementById('verify-peran');
    const waliContainer = document.getElementById('waliKelasContainer');

    // Tampilkan Wali Kelas hanya jika peran Guru
    verifyPeran.addEventListener('change', () => {
        if (verifyPeran.value === 'guru') {
            waliContainer.classList.remove('hidden');
            document.getElementById('verify-wali-kelas').required = true;
        } else {
            waliContainer.classList.add('hidden');
            document.getElementById('verify-wali-kelas').required = false;
        }
    });

    // Fungsi buka modal
    function openVerifyModal(id_user) {
        document.getElementById('verify-user-id').value = id_user;
        document.getElementById('verifyModal').classList.remove('hidden');
        verifyPeran.dispatchEvent(new Event('change'));
    }

    // Fungsi tutup modal
    function closeVerifyModal() {
        document.getElementById('verifyModal').classList.add('hidden');
    }
</script>
