<div id="edit-user-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 mx-auto">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Edit Pengguna</h3>
        <form id="edit-user-form" method="POST" action="?route=admin/users/update">
            <input type="hidden" name="id_user" id="edit-user-id">

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" id="edit-nama-lengkap" class="mt-1 block w-full border rounded-md px-3 py-2">
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="edit-email" class="mt-1 block w-full border rounded-md px-3 py-2">
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Peran</label>
                <select name="peran" id="edit-peran" class="mt-1 block w-full border rounded-md px-3 py-2">
                    <option value="admin">Admin</option>
                    <option value="guru">Guru</option>
                    <option value="siswa">Siswa</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Wali Kelas</label>
                <input type="text" name="wali_kelas" id="edit-wali-kelas" class="mt-1 block w-full border rounded-md px-3 py-2">
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Kelas</label>
                <input type="text" name="kelas" id="edit-kelas" class="mt-1 block w-full border rounded-md px-3 py-2">
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">NIP/NIS</label>
                <input type="text" name="nip_nis" id="edit-nip-nis" class="mt-1 block w-full border rounded-md px-3 py-2">
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Status Aktif</label>
                <select name="status_aktif" id="edit-status-aktif" class="mt-1 block w-full border rounded-md px-3 py-2">
                    <option value="1">Aktif</option>
                    <option value="0">Tidak Aktif</option>
                </select>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeEditUserModal()" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>
</div>
<script>
    function openEditUserModal(user) {
    document.getElementById('edit-user-id').value = user.id_user;
    document.getElementById('edit-nama-lengkap').value = user.nama_lengkap;
    document.getElementById('edit-email').value = user.email;
    document.getElementById('edit-peran').value = user.peran;
    document.getElementById('edit-wali-kelas').value = user.wali_kelas || '';
    document.getElementById('edit-kelas').value = user.kelas || '';
    document.getElementById('edit-nip-nis').value = user.nip_nis || '';
    document.getElementById('edit-status-aktif').value = user.status_aktif;

    document.getElementById('edit-user-modal').classList.remove('hidden');
}

function closeEditUserModal() {
    document.getElementById('edit-user-modal').classList.add('hidden');
}

</script>