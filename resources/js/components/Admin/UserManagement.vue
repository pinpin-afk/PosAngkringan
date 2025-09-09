<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg p-6 text-white">
      <div class="flex justify-between items-center">
        <div>
          <h2 class="text-3xl font-bold mb-2">Manajemen User</h2>
          <p class="text-blue-100">Kelola data pengguna dan akses sistem</p>
        </div>
        <button 
          @click="showCreateModal = true"
          class="bg-white text-blue-600 hover:bg-blue-50 px-6 py-3 rounded-lg text-sm font-semibold shadow-lg hover:shadow-xl transition-all duration-200 flex items-center space-x-2"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
          </svg>
          <span>Tambah User</span>
        </button>
      </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white shadow-xl rounded-lg overflow-hidden">
      <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Daftar Pengguna</h3>
        <p class="text-sm text-gray-600">Total {{ users.length }} pengguna terdaftar</p>
      </div>
      
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
            <tr>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                <div class="flex items-center space-x-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                  </svg>
                  <span>Pengguna</span>
                </div>
              </th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                <div class="flex items-center space-x-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                  </svg>
                  <span>Email</span>
                </div>
              </th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                <div class="flex items-center space-x-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                  </svg>
                  <span>Role</span>
                </div>
              </th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                <div class="flex items-center space-x-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                  <span>Status</span>
                </div>
              </th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                <div class="flex items-center space-x-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                  </svg>
                  <span>Aksi</span>
                </div>
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="user in users" :key="user.id" class="hover:bg-gray-50 transition-colors duration-150">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10">
                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center">
                      <span class="text-white font-semibold text-sm">{{ user.name.charAt(0).toUpperCase() }}</span>
                    </div>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-semibold text-gray-900">{{ user.name }}</div>
                    <div class="text-sm text-gray-500">ID: {{ user.id }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ user.email }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span 
                  :class="getRoleBadgeClass(user.role)"
                  class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold"
                >
                  <div :class="getRoleDotClass(user.role)" class="w-2 h-2 rounded-full mr-2"></div>
                  {{ user.role.charAt(0).toUpperCase() + user.role.slice(1) }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span 
                  :class="user.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                  class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold"
                >
                  <div :class="user.is_active ? 'bg-green-400' : 'bg-red-400'" class="w-2 h-2 rounded-full mr-2"></div>
                  {{ user.is_active ? 'Aktif' : 'Tidak Aktif' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex space-x-2">
                  <button 
                    @click="editUser(user)"
                    class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-md transition-colors duration-150 flex items-center space-x-1"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span>Edit</span>
                  </button>
                  <button 
                    @click="deleteUser(user.id)"
                    class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1 rounded-md transition-colors duration-150 flex items-center space-x-1"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    <span>Hapus</span>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Create/Edit User Modal -->
    <div v-if="showCreateModal || showEditModal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
      <div class="bg-white rounded-xl shadow-2xl w-full max-w-md transform transition-all">
        <div class="px-6 py-4 border-b border-gray-200">
          <div class="flex items-center justify-between">
            <h3 class="text-xl font-bold text-gray-900">
              {{ showCreateModal ? 'Tambah User Baru' : 'Edit User' }}
            </h3>
            <button 
              @click="closeModal"
              class="text-gray-400 hover:text-gray-600 transition-colors duration-150"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
        </div>
          
        <form @submit.prevent="submitUser" class="px-6 py-4">
          <div class="space-y-6">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
              <input 
                v-model="userForm.name"
                type="text" 
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-150"
                placeholder="Masukkan nama lengkap"
              >
            </div>
            
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
              <input 
                v-model="userForm.email"
                type="email" 
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-150"
                placeholder="Masukkan email"
              >
            </div>
            
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
              <input 
                v-model="userForm.password"
                type="password" 
                :required="showCreateModal"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-150"
                placeholder="Masukkan password"
              >
            </div>
            
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Role</label>
              <select 
                v-model="userForm.role"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-150"
              >
                <option value="admin">Administrator</option>
                <option value="kasir">Kasir</option>
                <option value="owner">Owner</option>
              </select>
            </div>
            
            <div class="flex items-center">
              <input 
                v-model="userForm.is_active"
                type="checkbox" 
                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
              >
              <label class="ml-3 text-sm font-medium text-gray-700">
                Akun aktif
              </label>
            </div>
          </div>
          
          <div class="flex justify-end space-x-3 mt-8 pt-4 border-t border-gray-200">
            <button 
              type="button"
              @click="closeModal"
              class="px-6 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors duration-150"
            >
              Batal
            </button>
            <button 
              type="submit"
              :disabled="loading"
              class="px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg text-sm font-medium hover:from-blue-700 hover:to-purple-700 disabled:opacity-50 transition-all duration-150 flex items-center space-x-2"
            >
              <svg v-if="loading" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span>{{ loading ? 'Menyimpan...' : (showCreateModal ? 'Tambah User' : 'Update User') }}</span>
            </button>
          </div>
        </form>
        </div>
      </div>
    </div>
</template>

<script>
export default {
  name: 'UserManagement',
  data() {
    return {
      users: [],
      showCreateModal: false,
      showEditModal: false,
      loading: false,
      userForm: {
        name: '',
        email: '',
        password: '',
        role: 'kasir',
        is_active: true
      }
    }
  },
  mounted() {
    this.loadUsers();
  },
  methods: {
    async loadUsers() {
      try {
        const response = await fetch('/pos/users');
        this.users = await response.json();
      } catch (error) {
        console.error('Error loading users:', error);
      }
    },
    
    async submitUser() {
      this.loading = true;
      
      try {
        const url = this.showCreateModal ? '/pos/users' : `/pos/users/${this.userForm.id}`;
        const method = this.showCreateModal ? 'POST' : 'PUT';
        
        const response = await fetch(url, {
          method: method,
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify(this.userForm)
        });
        
        if (response.ok) {
          this.closeModal();
          this.loadUsers();
        } else {
          const data = await response.json();
          alert('Error: ' + (data.message || 'Terjadi kesalahan'));
        }
      } catch (error) {
        console.error('Error saving user:', error);
        alert('Terjadi kesalahan saat menyimpan user');
      } finally {
        this.loading = false;
      }
    },
    
    editUser(user) {
      this.userForm = { ...user };
      this.showEditModal = true;
    },
    
    async deleteUser(userId) {
      if (confirm('Apakah Anda yakin ingin menghapus user ini?')) {
        try {
          const response = await fetch(`/pos/users/${userId}`, {
            method: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
          });
          
          if (response.ok) {
            this.loadUsers();
          } else {
            alert('Error menghapus user');
          }
        } catch (error) {
          console.error('Error deleting user:', error);
          alert('Terjadi kesalahan saat menghapus user');
        }
      }
    },
    
    closeModal() {
      this.showCreateModal = false;
      this.showEditModal = false;
      this.userForm = {
        name: '',
        email: '',
        password: '',
        role: 'kasir',
        is_active: true
      };
    },
    
    getRoleBadgeClass(role) {
      switch (role) {
        case 'admin':
          return 'bg-purple-100 text-purple-800';
        case 'kasir':
          return 'bg-green-100 text-green-800';
        default:
          return 'bg-gray-100 text-gray-800';
      }
    },
    
    getRoleDotClass(role) {
      switch (role) {
        case 'admin':
          return 'bg-purple-500';
        case 'kasir':
          return 'bg-green-500';
        default:
          return 'bg-gray-500';
      }
    }
  }
}
</script>
