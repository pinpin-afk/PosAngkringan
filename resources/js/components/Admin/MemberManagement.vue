<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">Manajemen Member</h2>
        <p class="text-gray-600">Kelola data member dan loyalty program</p>
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

    <!-- Search and Filter -->
    <div class="bg-white rounded-lg border p-4">
      <div class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Cari member berdasarkan nama, ID, atau telepon..."
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          >
        </div>
        <div class="flex space-x-2">
          <select
            v-model="statusFilter"
            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          >
            <option value="">Semua Status</option>
            <option value="active">Aktif</option>
            <option value="inactive">Tidak Aktif</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Members Table -->
    <div class="bg-white rounded-lg border overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Member</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telepon</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Daftar</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-if="loading" class="animate-pulse">
              <td colspan="7" class="px-6 py-4 text-center">
                <div class="flex justify-center">
                  <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                </div>
              </td>
            </tr>
            <tr v-else-if="filteredMembers.length === 0">
              <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                {{ searchQuery ? 'Tidak ada member yang ditemukan' : 'Belum ada member terdaftar' }}
              </td>
            </tr>
            <tr v-else v-for="member in filteredMembers" :key="member.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ member.member_id }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ member.name }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ member.phone }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ member.email || '-' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                  💎 {{ member.points || 0 }} Points
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full" :class="member.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                  {{ member.is_active ? 'Aktif' : 'Tidak Aktif' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ formatDate(member.created_at) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex space-x-2">
                  <button
                    @click="editMember(member)"
                    class="text-blue-600 hover:text-blue-900 transition-colors"
                  >
                    Edit
                  </button>
                  <button
                    @click="toggleMemberStatus(member)"
                    class="transition-colors"
                    :class="member.is_active ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900'"
                  >
                    {{ member.is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                  </button>
                  <button
                    @click="deleteMember(member)"
                    class="text-red-600 hover:text-red-900 transition-colors"
                  >
                    Hapus
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Add/Edit Member Modal -->
    <div v-if="showAddModal || showEditModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
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

          <div v-if="showEditModal" class="flex items-center">
            <input
              v-model="form.is_active"
              type="checkbox"
              class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
            >
            <label class="ml-2 text-sm text-gray-700">Member Aktif</label>
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
    <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
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
</template>

<script>
// import axios from 'axios'; // Using window.axios instead

export default {
  name: 'MemberManagement',
  data() {
    return {
      members: [],
      loading: false,
      saving: false,
      deleting: false,
      searchQuery: '',
      statusFilter: '',
      showAddModal: false,
      showEditModal: false,
      showDeleteModal: false,
      memberToDelete: null,
      form: {
        name: '',
        phone: '',
        email: '',
        address: '',
        is_active: true
      }
    }
  },
  computed: {
    filteredMembers() {
      let filtered = this.members;

      // Filter by search query
      if (this.searchQuery) {
        const query = this.searchQuery.toLowerCase();
        filtered = filtered.filter(member => 
          member.member_id.toLowerCase().includes(query) ||
          member.name.toLowerCase().includes(query) ||
          member.phone.toLowerCase().includes(query) ||
          (member.email && member.email.toLowerCase().includes(query))
        );
      }

      // Filter by status
      if (this.statusFilter) {
        filtered = filtered.filter(member => 
          this.statusFilter === 'active' ? member.is_active : !member.is_active
        );
      }

      return filtered;
    }
  },
  async mounted() {
    // Set CSRF token
    if (window.csrfToken) {
      window.axios.defaults.headers.common['X-CSRF-TOKEN'] = window.csrfToken;
    }
    await this.loadMembers();
  },
  methods: {
    async loadMembers() {
      this.loading = true;
      try {
        // Set CSRF token for this request
        if (window.csrfToken) {
          window.axios.defaults.headers.common['X-CSRF-TOKEN'] = window.csrfToken;
        }
        
        const response = await window.axios.get('/api/admin/members');
        this.members = response.data || [];
      } catch (error) {
        console.error('Error loading members:', error);
        if (error.response && error.response.status === 401) {
          alert('Sesi login telah berakhir. Silakan login ulang.');
          window.location.href = '/pos/login';
        } else {
          alert('Gagal memuat data member: ' + (error.response?.data?.message || error.message));
        }
      } finally {
        this.loading = false;
      }
    },
    async saveMember() {
      this.saving = true;
      try {
        // Set CSRF token for this request
        if (window.csrfToken) {
          window.axios.defaults.headers.common['X-CSRF-TOKEN'] = window.csrfToken;
        }
        
        if (this.showAddModal) {
          await window.axios.post('/api/admin/members', this.form);
          alert('Member berhasil ditambahkan');
        } else {
          await window.axios.put(`/api/admin/members/${this.form.id}`, this.form);
          alert('Member berhasil diupdate');
        }
        await this.loadMembers();
        this.closeModal();
      } catch (error) {
        console.error('Error saving member:', error);
        if (error.response && error.response.status === 401) {
          alert('Sesi login telah berakhir. Silakan login ulang.');
          window.location.href = '/pos/login';
        } else {
          const message = error.response?.data?.message || 'Gagal menyimpan member';
          alert(message);
        }
      } finally {
        this.saving = false;
      }
    },
    editMember(member) {
      this.form = { ...member };
      this.showEditModal = true;
    },
    async toggleMemberStatus(member) {
      try {
        // Set CSRF token for this request
        if (window.csrfToken) {
          window.axios.defaults.headers.common['X-CSRF-TOKEN'] = window.csrfToken;
        }
        
        await window.axios.put(`/api/admin/members/${member.id}`, {
          ...member,
          is_active: !member.is_active
        });
        await this.loadMembers();
        alert(`Member ${member.is_active ? 'dinonaktifkan' : 'diaktifkan'}`);
      } catch (error) {
        console.error('Error toggling member status:', error);
        if (error.response && error.response.status === 401) {
          alert('Sesi login telah berakhir. Silakan login ulang.');
          window.location.href = '/pos/login';
        } else {
          alert('Gagal mengubah status member');
        }
      }
    },
    deleteMember(member) {
      this.memberToDelete = member;
      this.showDeleteModal = true;
    },
    async confirmDelete() {
      this.deleting = true;
      try {
        // Set CSRF token for this request
        if (window.csrfToken) {
          window.axios.defaults.headers.common['X-CSRF-TOKEN'] = window.csrfToken;
        }
        
        await window.axios.delete(`/api/admin/members/${this.memberToDelete.id}`);
        alert('Member berhasil dihapus');
        await this.loadMembers();
        this.showDeleteModal = false;
        this.memberToDelete = null;
      } catch (error) {
        console.error('Error deleting member:', error);
        if (error.response && error.response.status === 401) {
          alert('Sesi login telah berakhir. Silakan login ulang.');
          window.location.href = '/pos/login';
        } else {
          alert('Gagal menghapus member');
        }
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
        is_active: true
      };
    },
    formatDate(dateString) {
      if (!dateString) return '-';
      const date = new Date(dateString);
      return date.toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      });
    }
  }
}
</script>
