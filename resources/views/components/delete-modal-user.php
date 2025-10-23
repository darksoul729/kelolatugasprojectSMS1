  <div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-80 p-5">
      <h2 class="text-lg font-semibold text-gray-800 mb-3">Konfirmasi Hapus</h2>
      <p class="text-gray-600 mb-5">Apakah Anda yakin ingin menghapus pengguna ini?</p>
      <div class="flex justify-end space-x-2">
        <button onclick="closeDeleteModal()" class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400 transition text-sm">
          Batal
        </button>
        <a id="confirmDeleteBtn" href="#" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition text-sm">
          Ya, Hapus
        </a>
      </div>
    </div>
  </div>