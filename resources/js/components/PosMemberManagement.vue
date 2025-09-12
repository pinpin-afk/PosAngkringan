<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-hidden">
      <!-- Header -->
      <div class="flex justify-between items-center p-6 border-b">
        <div>
          <h2 class="text-xl font-bold text-gray-900">Kelola Member</h2>
          <p class="text-gray-600">Tambah, edit, atau hapus data member</p>
        </div>
        <button
          @click="$emit('close')"
          class="text-gray-400 hover:text-gray-600 transition-colors"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>

      <!-- Content -->
      <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
        <!-- Search and Add Button -->
        <div class="flex flex-col md:flex-row gap-4 mb-6">
          <div class="flex-1">
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Cari member berdasarkan nama atau telepon..."
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
          </div>
          <button
            @click="showAddModal = true"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center space-x-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            <span>Tambah Member</span>
          </button>
        </div>

        <!-- Members List -->
        <div class="space-y-3">
          <div v-if="loading" class="text-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
            <p class="mt-2 text-gray-600">Memuat data member...</p>
          </div>
          
          <div v-else-if="filteredMembers.length === 0" class="text-center py-8 text-gray-500">
            {{ searchQuery ? 'Tidak ada member yang ditemukan' : 'Belum ada member terdaftar' }}
          </div>
          
          <div v-else class="space-y-2">
            <div
              v-for="member in filteredMembers"
              :key="member.id"
              class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors"
            >
              <div class="flex-1">
                <div class="flex items-center space-x-3">
                  <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                    <span class="text-yellow-600 font-semibold text-sm">💎</span>
                  </div>
                  <div>
                    <h3 class="font-medium text-gray-900">{{ member.name }}</h3>
                    <p class="text-sm text-gray-600">{{ member.phone }}</p>
                    <p v-if="member.email" class="text-xs text-gray-500">{{ member.email }}</p>
                    <p class="text-xs font-medium text-yellow-600">
                      💎 {{ member.points || 0 }} Points
                    </p>
                  </div>
                </div>
              </div>
              <div class="flex items-center space-x-2">
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full" :class="member.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                  {{ member.is_active ? 'Aktif' : 'Tidak Aktif' }}
                </span>
                <button
                  v-if="enableSelect"
                  @click="$emit('select', member)"
                  class="text-green-600 hover:text-green-900 transition-colors p-1"
                  title="Pilih Member"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                  </svg>
                </button>
                <button
                  @click="editMember(member)"
                  class="text-blue-600 hover:text-blue-900 transition-colors p-1"
                  title="Edit Member"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                  </svg>
                </button>
                <button
                  @click="deleteMember(member)"
                  class="text-red-600 hover:text-red-900 transition-colors p-1"
                  title="Hapus Member"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Add/Edit Member Modal -->
      <div v-if="showAddModal || showEditModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-60 p-4">
        <div class="bg-white rounded-lg p-6 max-w-md w-full max-h-[90vh] overflow-y-auto">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">
              {{ showAddModal ? 'Tambah Member' : 'Edit Member' }}
            </h3>
            <button
              @click="closeModal"
              class="text-gray-400 hover:text-gray-600 transition-colors"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>

          <form @submit.prevent="saveMember" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
              <input
                v-model="form.name"
                type="text"
                placeholder="Masukkan nama lengkap"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                required
              >
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
              <input
                v-model="form.phone"
                type="tel"
                placeholder="Masukkan nomor telepon"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                required
              >
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Email (Opsional)</label>
              <input
                v-model="form.email"
                type="email"
                placeholder="Masukkan email"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Alamat (Opsional)</label>
              <textarea
                v-model="form.address"
                placeholder="Masukkan alamat"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              ></textarea>
            </div>

            <div v-if="showEditModal">
              <div class="flex items-center mb-4">
                <input
                  v-model="form.is_active"
                  type="checkbox"
                  class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
                >
                <label class="ml-2 text-sm text-gray-700">Member Aktif</label>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Points</label>
                <input
                  v-model="form.points"
                  type="number"
                  min="0"
                  placeholder="Masukkan jumlah points"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
              </div>
            </div>

            <div class="flex space-x-3 pt-4">
              <button
                type="button"
                @click="closeModal"
                class="flex-1 px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
              >
                Batal
              </button>
              <button
                type="submit"
                :disabled="saving"
                class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
              >
                {{ saving ? 'Menyimpan...' : (showAddModal ? 'Tambah' : 'Update') }}
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Delete Confirmation Modal -->
      <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-60 p-4">
        <div class="bg-white rounded-lg p-6 max-w-md w-full">
          <div class="text-center">
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 19.5c-.77.833.192 2.5 1.732 2.5z"></path>
              </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Konfirmasi Hapus</h3>
            <p class="text-gray-600 mb-6">
              Apakah Anda yakin ingin menghapus member <strong>{{ memberToDelete?.name }}</strong>?
              Tindakan ini tidak dapat dibatalkan.
            </p>
            <div class="flex space-x-3">
              <button
                @click="showDeleteModal = false"
                class="flex-1 px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
              >
                Batal
              </button>
              <button
                @click="confirmDelete"
                :disabled="deleting"
                class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
              >
                {{ deleting ? 'Menghapus...' : 'Hapus' }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'PosMemberManagement',
  emits: ['close', 'select'],
  props: {
    enableSelect: { type: Boolean, default: false }
  },
  data() {
    return {
      members: [],
      loading: false,
      saving: false,
      deleting: false,
      searchQuery: '',
      showAddModal: false,
      showEditModal: false,
      showDeleteModal: false,
      memberToDelete: null,
      form: {
        name: '',
        phone: '',
        email: '',
        address: '',
        is_active: true,
        points: 0
      }
    }
  },
  computed: {
    filteredMembers() {
      if (!this.searchQuery) return this.members;
      
      const query = this.searchQuery.toLowerCase();
      return this.members.filter(member => 
        member.name.toLowerCase().includes(query) ||
        member.phone.toLowerCase().includes(query) ||
        (member.email && member.email.toLowerCase().includes(query))
      );
    }
  },
  async mounted() {
    await this.loadMembers();
  },
  methods: {
    async loadMembers() {
      this.loading = true;
      try {
        const response = await window.axios.get('/api/kasir/members');
        this.members = response.data || [];
      } catch (error) {
        console.error('Error loading members:', error);
        alert('Gagal memuat data member: ' + (error.response?.data?.message || error.message));
      } finally {
        this.loading = false;
      }
    },
    async saveMember() {
      this.saving = true;
      try {
        if (this.showAddModal) {
          await window.axios.post('/api/kasir/members', this.form);
          alert('Member berhasil ditambahkan');
        } else {
          await window.axios.put(`/api/kasir/members/${this.form.id}`, this.form);
          alert('Member berhasil diupdate');
        }
        await this.loadMembers();
        this.closeModal();
      } catch (error) {
        console.error('Error saving member:', error);
        const message = error.response?.data?.message || 'Gagal menyimpan member';
        alert(message);
      } finally {
        this.saving = false;
      }
    },
    editMember(member) {
      this.form = { ...member };
      this.showEditModal = true;
    },
    deleteMember(member) {
      this.memberToDelete = member;
      this.showDeleteModal = true;
    },
    async confirmDelete() {
      this.deleting = true;
      try {
        const id = this.memberToDelete?.id;
        if (!id) {
          alert('Gagal menghapus: ID member tidak ditemukan');
          this.deleting = false;
          return;
        }
        await window.axios.delete(`/api/kasir/members/${id}`);
        alert('Member berhasil dihapus');
        await this.loadMembers();
        this.showDeleteModal = false;
        this.memberToDelete = null;
      } catch (error) {
        console.error('Error deleting member:', error);
        const message = error?.response?.data?.message || error?.message || 'Gagal menghapus member';
        alert(message);
      } finally {
        this.deleting = false;
      }
    },
    closeModal() {
      this.showAddModal = false;
      this.showEditModal = false;
      this.form = {
        name: '',
        phone: '',
        email: '',
        address: '',
        is_active: true,
        points: 0
      };
    }
  }
}
</script>
