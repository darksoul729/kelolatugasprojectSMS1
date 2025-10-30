<div id="verifyModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
  <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 mx-auto">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Verifikasi User</h3>
    <form method="POST" action="?route=admin/users/verify" id="verifyForm">
        <!-- Input hidden untuk banyak user -->
        <div id="verify-user-ids"></div>

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
                <option value="7A">7 A</option>
                <option value="7B">7 B</option>
                <option value="7C">7 C</option>
                <option value="7D">7 D</option>
                <option value="8A">8 A</option>
                <option value="8B">8 B</option>
                <option value="8C">8 C</option>
                <option value="8D">8 D</option>
                <option value="9A">9 A</option>
                <option value="9B">9 B</option>
                <option value="9C">9 C</option>
                <option value="9D">9 D</option>
            </select>
        </div>

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
const verifyUserIdsContainer = document.getElementById('verify-user-ids');

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

// Buka modal bulk verify
function openVerifyModal(ids) {
    // Hapus input lama
    verifyUserIdsContainer.innerHTML = '';

    // Buat input hidden untuk tiap ID
    if (Array.isArray(ids)) {
        ids.forEach(id => {
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'id_user[]';
            hidden.value = id;
            verifyUserIdsContainer.appendChild(hidden);
        });
    } else {
        // single user (legacy)
        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'id_user[]';
        hidden.value = ids;
        verifyUserIdsContainer.appendChild(hidden);
    }

    document.getElementById('verifyModal').classList.remove('hidden');
    verifyPeran.dispatchEvent(new Event('change'));
}

// Tutup modal
function closeVerifyModal() {
    document.getElementById('verifyModal').classList.add('hidden');
}

// Tombol bulk verify
document.getElementById('bulkVerifyBtn').addEventListener('click', function() {
    const selectedIds = Array.from(document.querySelectorAll('.verify-checkbox:checked'))
                             .map(cb => cb.value);
    if (selectedIds.length === 0) return alert('Pilih minimal 1 user untuk diverifikasi!');
    openVerifyModal(selectedIds);
});

// Select all checkbox
document.getElementById('selectAll').addEventListener('change', function() {
    document.querySelectorAll('.verify-checkbox').forEach(cb => cb.checked = this.checked);
});

</script>