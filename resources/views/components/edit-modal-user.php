<div id="edit-user-modal" class="hidden !important fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
  <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 mx-auto">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Edit Pengguna</h3>
    <form id="edit-user-form" method="POST" action="?route=admin/users/update">
      <input type="hidden" name="id_user" id="edit-user-id">

      <!-- Nama -->
      <div class="mb-3">
        <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
        <input type="text" name="nama_lengkap" id="edit-nama-lengkap"
               class="mt-1 block w-full border rounded-md px-3 py-2">
      </div>

      <!-- Email -->
      <div class="mb-3">
        <label class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="email" id="edit-email"
               class="mt-1 block w-full border rounded-md px-3 py-2">
      </div>

      <!-- Peran -->
      <div class="mb-3">
        <label class="block text-sm font-medium text-gray-700">Peran</label>
        <select name="peran" id="edit-peran"
                class="mt-1 block w-full border rounded-md px-3 py-2">
          <option value="admin">Admin</option>
          <option value="guru">Guru</option>
          <option value="siswa">Siswa</option>
          <option value="belum_verifikasi">Belum Verif</option>
        </select>
      </div>

      <!-- Wali Kelas -->
      <div class="mb-3">
        <label class="block text-sm font-medium text-gray-700">Wali Kelas</label>
        <select name="wali_kelas" id="edit-wali-kelas"
                class="mt-1 block w-full border rounded-md px-3 py-2">
          <option value="">-- Pilih Wali Kelas --</option>
          <!-- Kelas 7 -->
          <option value="7 A">7 A</option>
          <option value="7 B">7 B</option>
          <option value="7 C">7 C</option>
          <option value="7 D">7 D</option>
          <!-- Kelas 8 -->
          <option value="8 A">8 A</option>
          <option value="8 B">8 B</option>
          <option value="8 C">8 C</option>
          <option value="8 D">8 D</option>
          <!-- Kelas 9 -->
          <option value="9 A">9 A</option>
          <option value="9 B">9 B</option>
          <option value="9 C">9 C</option>
          <option value="9 D">9 D</option>
        </select>
      </div>

      <!-- Kelas -->
      <div class="mb-3" id="kelasContainer">
        <label class="block text-sm font-medium text-gray-700">Kelas</label>
        <select name="kelas" id="edit-kelas"
                class="mt-1 block w-full border rounded-md px-3 py-2">
          <option value="">-- Pilih Kelas --</option>
          <option value="7 A">7 A</option><option value="7 B">7 B</option>
          <option value="7 C">7 C</option><option value="7 D">7 D</option>
          <option value="7 E">7 E</option><option value="7 F">7 F</option>
          <option value="7 G">7 G</option><option value="7 H">7 H</option>
          <option value="7 I">7 I</option><option value="7 J">7 J</option>
          <option value="8 A">8 A</option><option value="8 B">8 B</option>
          <option value="8 C">8 C</option><option value="8 D">8 D</option>
          <option value="8 E">8 E</option><option value="8 F">8 F</option>
          <option value="8 G">8 G</option><option value="8 H">8 H</option>
          <option value="8 I">8 I</option>
          <option value="9 A">9 A</option><option value="9 B">9 B</option>
          <option value="9 C">9 C</option><option value="9 D">9 D</option>
          <option value="9 E">9 E</option><option value="9 F">9 F</option>
          <option value="9 G">9 G</option><option value="9 H">9 H</option>
        </select>
      </div>

      <!-- NIP/NIS -->
      <div class="mb-3">
        <label class="block text-sm font-medium text-gray-700">NIP/NIS</label>
        <input type="text" name="nip_nis" id="edit-nip-nis"
               class="mt-1 block w-full border rounded-md px-3 py-2">
      </div>

      <!-- 🔒 Password baru -->
      <div class="mb-3">
        <label class="block text-sm font-medium text-gray-700">Password Baru</label>
        <input type="password" name="password" id="edit-password"
               placeholder="Kosongkan jika tidak ingin ubah"
               class="mt-1 block w-full border rounded-md px-3 py-2">
      </div>

      <!-- 🔒 Konfirmasi Password -->
      <div class="mb-3">
        <label class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
        <input type="password" name="confirm_password" id="edit-confirm-password"
               placeholder="Ulangi password baru"
               class="mt-1 block w-full border rounded-md px-3 py-2">
      </div>

      <!-- Status Aktif -->
      <div class="mb-3">
        <label class="block text-sm font-medium text-gray-700">Status Aktif</label>
        <select name="status_aktif" id="edit-status-aktif"
                class="mt-1 block w-full border rounded-md px-3 py-2">
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
