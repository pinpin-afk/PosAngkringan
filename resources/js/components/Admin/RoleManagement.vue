<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg p-6 text-white">
      <div class="flex justify-between items-center">
        <div>
          <h2 class="text-3xl font-bold mb-2">Manajemen Role</h2>
          <p class="text-purple-100">Kelola peran dan izin akses pengguna</p>
        </div>
        <button 
          @click="showCreateModal = true"
          class="bg-white text-purple-600 hover:bg-purple-50 px-6 py-3 rounded-lg text-sm font-semibold shadow-lg hover:shadow-xl transition-all duration-200 flex items-center space-x-2"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
          </svg>
          <span>Tambah Role</span>
        </button>
      </div>
    </div>

    <!-- Role Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg overflow-hidden">
        <div class="p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                </svg>
              </div>
            </div>
            <div class="ml-4 flex-1">
              <dl>
                <dt class="text-sm font-medium text-purple-100 truncate">Total Admin</dt>
                <dd class="text-3xl font-bold text-white">{{ roleStats.admin }}</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg overflow-hidden">
        <div class="p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                </svg>
              </div>
            </div>
            <div class="ml-4 flex-1">
              <dl>
                <dt class="text-sm font-medium text-green-100 truncate">Total Kasir</dt>
                <dd class="text-3xl font-bold text-white">{{ roleStats.kasir }}</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg overflow-hidden">
        <div class="p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                </svg>
              </div>
            </div>
            <div class="ml-4 flex-1">
              <dl>
                <dt class="text-sm font-medium text-blue-100 truncate">Total User</dt>
                <dd class="text-3xl font-bold text-white">{{ roleStats.total }}</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Role Permissions Table -->
    <div class="bg-white shadow-xl rounded-lg overflow-hidden">
      <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <h3 class="text-xl font-bold text-gray-900">Matriks Permissions</h3>
        <p class="text-sm text-gray-600">Daftar izin akses untuk setiap role</p>
      </div>
      
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
            <tr>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                <div class="flex items-center space-x-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                  </svg>
                  <span>Role</span>
                </div>
              </th>
              <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                <div class="flex items-center justify-center space-x-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                  </svg>
                  <span>Dashboard</span>
                </div>
              </th>
              <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                <div class="flex items-center justify-center space-x-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                  </svg>
                  <span>POS</span>
                </div>
              </th>
              <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                <div class="flex items-center justify-center space-x-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                  </svg>
                  <span>Products</span>
                </div>
              </th>
              <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                <div class="flex items-center justify-center space-x-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                  </svg>
                  <span>Orders</span>
                </div>
              </th>
              <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                <div class="flex items-center justify-center space-x-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                  </svg>
                  <span>Users</span>
                </div>
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="role in roles" :key="role.name" class="hover:bg-gray-50 transition-colors duration-150">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10">
                    <div :class="getRoleIconBg(role.name)" class="h-10 w-10 rounded-lg flex items-center justify-center">
                      <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                      </svg>
                    </div>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-semibold text-gray-900">{{ role.name.charAt(0).toUpperCase() + role.name.slice(1) }}</div>
                    <div class="text-sm text-gray-500">{{ role.description }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-center">
                <span :class="role.permissions.dashboard ? 'text-green-600 bg-green-100' : 'text-red-600 bg-red-100'" 
                      class="inline-flex items-center justify-center w-8 h-8 rounded-full text-sm font-semibold">
                  {{ role.permissions.dashboard ? '✓' : '✗' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-center">
                <span :class="role.permissions.pos ? 'text-green-600 bg-green-100' : 'text-red-600 bg-red-100'" 
                      class="inline-flex items-center justify-center w-8 h-8 rounded-full text-sm font-semibold">
                  {{ role.permissions.pos ? '✓' : '✗' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-center">
                <span :class="role.permissions.products ? 'text-green-600 bg-green-100' : 'text-red-600 bg-red-100'" 
                      class="inline-flex items-center justify-center w-8 h-8 rounded-full text-sm font-semibold">
                  {{ role.permissions.products ? '✓' : '✗' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-center">
                <span :class="role.permissions.orders ? 'text-green-600 bg-green-100' : 'text-red-600 bg-red-100'" 
                      class="inline-flex items-center justify-center w-8 h-8 rounded-full text-sm font-semibold">
                  {{ role.permissions.orders ? '✓' : '✗' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-center">
                <span :class="role.permissions.users ? 'text-green-600 bg-green-100' : 'text-red-600 bg-red-100'" 
                      class="inline-flex items-center justify-center w-8 h-8 rounded-full text-sm font-semibold">
                  {{ role.permissions.users ? '✓' : '✗' }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Create Role Modal -->
    <div v-if="showCreateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah Role Baru</h3>
          
          <form @submit.prevent="submitRole">
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700">Nama Role</label>
                <input 
                  v-model="roleForm.name"
                  type="text" 
                  required
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                >
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea 
                  v-model="roleForm.description"
                  rows="3"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                ></textarea>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Permissions</label>
                <div class="space-y-2">
                  <label v-for="permission in availablePermissions" :key="permission.key" class="flex items-center">
                    <input 
                      v-model="roleForm.permissions"
                      :value="permission.key"
                      type="checkbox" 
                      class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    >
                    <span class="ml-2 text-sm text-gray-700">{{ permission.label }}</span>
                  </label>
                </div>
              </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
              <button 
                type="button"
                @click="closeModal"
                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50"
              >
                Batal
              </button>
              <button 
                type="submit"
                :disabled="loading"
                class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-medium hover:bg-indigo-700 disabled:opacity-50"
              >
                {{ loading ? 'Menyimpan...' : 'Tambah' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'RoleManagement',
  data() {
    return {
      roles: [],
      roleStats: {
        admin: 0,
        kasir: 0,
        total: 0
      },
      showCreateModal: false,
      loading: false,
      roleForm: {
        name: '',
        description: '',
        permissions: []
      },
      availablePermissions: [
        { key: 'dashboard', label: 'Dashboard' },
        { key: 'pos', label: 'POS' },
        { key: 'products', label: 'Products' },
        { key: 'orders', label: 'Orders' },
        { key: 'users', label: 'Users' }
      ]
    }
  },
  mounted() {
    this.loadRoles();
    this.loadRoleStats();
  },
  methods: {
    async loadRoles() {
      try {
        const response = await fetch('/pos/roles');
        this.roles = await response.json();
      } catch (error) {
        console.error('Error loading roles:', error);
      }
    },
    
    async loadRoleStats() {
      try {
        const response = await fetch('/pos/role-stats');
        this.roleStats = await response.json();
      } catch (error) {
        console.error('Error loading role stats:', error);
      }
    },
    
    async submitRole() {
      this.loading = true;
      
      try {
        const response = await fetch('/pos/roles', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify(this.roleForm)
        });
        
        if (response.ok) {
          this.closeModal();
          this.loadRoles();
          this.loadRoleStats();
        } else {
          const data = await response.json();
          alert('Error: ' + (data.message || 'Terjadi kesalahan'));
        }
      } catch (error) {
        console.error('Error saving role:', error);
        alert('Terjadi kesalahan saat menyimpan role');
      } finally {
        this.loading = false;
      }
    },
    
    closeModal() {
      this.showCreateModal = false;
      this.roleForm = {
        name: '',
        description: '',
        permissions: []
      };
    },
    
    getRoleBadgeClass(roleName) {
      switch (roleName) {
        case 'admin':
          return 'bg-purple-100 text-purple-800';
        case 'kasir':
          return 'bg-green-100 text-green-800';
        default:
          return 'bg-gray-100 text-gray-800';
      }
    },
    
    getRoleIconBg(roleName) {
      switch (roleName) {
        case 'admin':
          return 'bg-gradient-to-br from-purple-500 to-purple-600';
        case 'kasir':
          return 'bg-gradient-to-br from-green-500 to-green-600';
        default:
          return 'bg-gradient-to-br from-gray-500 to-gray-600';
      }
    }
  }
}
</script>
