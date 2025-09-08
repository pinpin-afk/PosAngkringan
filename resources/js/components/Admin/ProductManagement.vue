<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-green-600 to-blue-600 rounded-lg p-6 text-white">
      <div class="flex justify-between items-center">
        <div>
          <h2 class="text-3xl font-bold mb-2">Manajemen Produk</h2>
          <p class="text-green-100">Kelola katalog produk dan inventori</p>
        </div>
        <button 
          @click="showAddModal = true"
          class="bg-white text-green-600 hover:bg-green-50 px-6 py-3 rounded-lg text-sm font-semibold shadow-lg hover:shadow-xl transition-all duration-200 flex items-center space-x-2"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
          </svg>
          <span>Tambah Produk</span>
        </button>
      </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white shadow-xl rounded-lg p-6">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">
            <div class="flex items-center space-x-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
              </svg>
              <span>Cari Produk</span>
            </div>
          </label>
          <input 
            v-model="searchQuery" 
            type="text" 
            placeholder="Nama produk, deskripsi..." 
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-150"
          >
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">
            <div class="flex items-center space-x-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
              </svg>
              <span>Kategori</span>
            </div>
          </label>
          <select 
            v-model="selectedCategory" 
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-150"
          >
            <option value="">Semua Kategori</option>
            <option v-for="category in categories" :key="category.id" :value="category.id">
              {{ category.name }}
            </option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">
            <div class="flex items-center space-x-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <span>Status</span>
            </div>
          </label>
          <select 
            v-model="statusFilter" 
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-150"
          >
            <option value="">Semua Status</option>
            <option value="active">Aktif</option>
            <option value="inactive">Tidak Aktif</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">
            <div class="flex items-center space-x-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z"></path>
              </svg>
              <span>Urutkan</span>
            </div>
          </label>
          <select 
            v-model="sortBy" 
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-150"
          >
            <option value="name">Nama A-Z</option>
            <option value="name_desc">Nama Z-A</option>
            <option value="price">Harga Terendah</option>
            <option value="price_desc">Harga Tertinggi</option>
            <option value="created_at">Terbaru</option>
            <option value="created_at_desc">Terlama</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white shadow-xl rounded-lg overflow-hidden">
      <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <div class="flex justify-between items-center">
          <div>
            <h3 class="text-lg font-semibold text-gray-900">Daftar Produk</h3>
            <p class="text-sm text-gray-600">Menampilkan {{ filteredProducts.length }} dari {{ products.length }} produk</p>
          </div>
          <div class="flex items-center space-x-2">
            <button 
              @click="refreshData"
              class="text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100 transition-colors duration-150"
              title="Refresh Data"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
              </svg>
            </button>
            <button 
              @click="exportData"
              class="text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100 transition-colors duration-150"
              title="Export Data"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
            </button>
          </div>
        </div>
      </div>
      
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
            <tr>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                <div class="flex items-center space-x-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                  </svg>
                  <span>Produk</span>
                </div>
              </th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                <div class="flex items-center space-x-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                  </svg>
                  <span>Kategori</span>
                </div>
              </th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                <div class="flex items-center space-x-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                  </svg>
                  <span>Harga</span>
                </div>
              </th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                <div class="flex items-center space-x-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                  </svg>
                  <span>Stok</span>
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
            <tr v-for="product in paginatedProducts" :key="product.id" class="hover:bg-gray-50 transition-colors duration-150">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-12 w-12">
                    <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center">
                      <span class="text-white font-bold text-lg">{{ product.name.charAt(0).toUpperCase() }}</span>
                    </div>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-semibold text-gray-900">{{ product.name }}</div>
                    <div class="text-sm text-gray-500 truncate max-w-xs">{{ product.description }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                  {{ product.category?.name || 'Tidak ada kategori' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-semibold text-gray-900">Rp {{ formatPrice(product.price) }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="text-sm font-semibold text-gray-900">{{ product.stock }}</div>
                  <div class="ml-2">
                    <div :class="getStockStatusClass(product.stock)" class="w-2 h-2 rounded-full"></div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span 
                  :class="product.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                  class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold"
                >
                  <div :class="product.is_active ? 'bg-green-400' : 'bg-red-400'" class="w-2 h-2 rounded-full mr-2"></div>
                  {{ product.is_active ? 'Aktif' : 'Tidak Aktif' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex space-x-2">
                  <button 
                    @click="editProduct(product)"
                    class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-md transition-colors duration-150 flex items-center space-x-1"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span>Edit</span>
                  </button>
                  <button 
                    @click="deleteProduct(product.id)"
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

      <!-- Pagination -->
      <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
        <div class="flex-1 flex justify-between sm:hidden">
          <button 
            @click="previousPage"
            :disabled="currentPage === 1"
            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Previous
          </button>
          <button 
            @click="nextPage"
            :disabled="currentPage === totalPages"
            class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Next
          </button>
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
          <div>
            <p class="text-sm text-gray-700">
              Menampilkan
              <span class="font-medium">{{ (currentPage - 1) * itemsPerPage + 1 }}</span>
              sampai
              <span class="font-medium">{{ Math.min(currentPage * itemsPerPage, filteredProducts.length) }}</span>
              dari
              <span class="font-medium">{{ filteredProducts.length }}</span>
              hasil
            </p>
          </div>
          <div>
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
              <button 
                @click="previousPage"
                :disabled="currentPage === 1"
                class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
              </button>
              
              <button 
                v-for="page in visiblePages" 
                :key="page"
                @click="goToPage(page)"
                :class="page === currentPage ? 'bg-green-50 border-green-500 text-green-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'"
                class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
              >
                {{ page }}
              </button>
              
              <button 
                @click="nextPage"
                :disabled="currentPage === totalPages"
                class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
              </button>
            </nav>
          </div>
        </div>
      </div>
    </div>

    <!-- Add/Edit Product Modal -->
    <div v-if="showAddModal || showEditModal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
      <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl transform transition-all">
        <div class="px-6 py-4 border-b border-gray-200">
          <div class="flex items-center justify-between">
            <h3 class="text-xl font-bold text-gray-900">
              {{ showAddModal ? 'Tambah Produk Baru' : 'Edit Produk' }}
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
        
        <form @submit.prevent="submitProduct" class="px-6 py-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Produk</label>
              <input 
                v-model="productForm.name"
                type="text" 
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-150"
                placeholder="Masukkan nama produk"
              >
            </div>
            
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori</label>
              <select 
                v-model="productForm.category_id"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-150"
              >
                <option value="">Pilih Kategori</option>
                <option v-for="category in categories" :key="category.id" :value="category.id">
                  {{ category.name }}
                </option>
              </select>
            </div>
            
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Harga</label>
              <input 
                v-model="productForm.price"
                type="number" 
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-150"
                placeholder="Masukkan harga"
              >
            </div>
            
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Stok</label>
              <input 
                v-model="productForm.stock"
                type="number" 
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-150"
                placeholder="Masukkan stok"
              >
            </div>
            
            <div class="md:col-span-2">
              <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
              <textarea 
                v-model="productForm.description"
                rows="3"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-150"
                placeholder="Masukkan deskripsi produk"
              ></textarea>
            </div>
            
            <div class="md:col-span-2">
              <div class="flex items-center">
                <input 
                  v-model="productForm.is_active"
                  type="checkbox" 
                  class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                >
                <label class="ml-3 text-sm font-medium text-gray-700">
                  Produk aktif
                </label>
              </div>
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
              class="px-6 py-2 bg-gradient-to-r from-green-600 to-blue-600 text-white rounded-lg text-sm font-medium hover:from-green-700 hover:to-blue-700 disabled:opacity-50 transition-all duration-150 flex items-center space-x-2"
            >
              <svg v-if="loading" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span>{{ loading ? 'Menyimpan...' : (showAddModal ? 'Tambah Produk' : 'Update Produk') }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ProductManagement',
  data() {
    return {
      products: [],
      categories: [],
      searchQuery: '',
      selectedCategory: '',
      statusFilter: '',
      sortBy: 'name',
      showAddModal: false,
      showEditModal: false,
      loading: false,
      currentPage: 1,
      itemsPerPage: 10,
      productForm: {
        name: '',
        description: '',
        price: '',
        stock: '',
        category_id: '',
        is_active: true
      }
    }
  },
  computed: {
    filteredProducts() {
      let filtered = this.products;

      // Search filter
      if (this.searchQuery) {
        const query = this.searchQuery.toLowerCase();
        filtered = filtered.filter(product => 
          product.name.toLowerCase().includes(query) ||
          product.description.toLowerCase().includes(query)
        );
      }

      // Category filter
      if (this.selectedCategory) {
        filtered = filtered.filter(product => product.category_id == this.selectedCategory);
      }

      // Status filter
      if (this.statusFilter) {
        const isActive = this.statusFilter === 'active';
        filtered = filtered.filter(product => product.is_active === isActive);
      }

      // Sort
      filtered.sort((a, b) => {
        switch (this.sortBy) {
          case 'name':
            return a.name.localeCompare(b.name);
          case 'name_desc':
            return b.name.localeCompare(a.name);
          case 'price':
            return a.price - b.price;
          case 'price_desc':
            return b.price - a.price;
          case 'created_at':
            return new Date(a.created_at) - new Date(b.created_at);
          case 'created_at_desc':
            return new Date(b.created_at) - new Date(a.created_at);
          default:
            return 0;
        }
      });

      return filtered;
    },
    paginatedProducts() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.filteredProducts.slice(start, end);
    },
    totalPages() {
      return Math.ceil(this.filteredProducts.length / this.itemsPerPage);
    },
    visiblePages() {
      const pages = [];
      const start = Math.max(1, this.currentPage - 2);
      const end = Math.min(this.totalPages, this.currentPage + 2);
      
      for (let i = start; i <= end; i++) {
        pages.push(i);
      }
      return pages;
    }
  },
  mounted() {
    this.loadProducts();
    this.loadCategories();
  },
  methods: {
    async loadProducts() {
      try {
        const response = await fetch('/pos/products');
        this.products = await response.json();
      } catch (error) {
        console.error('Error loading products:', error);
      }
    },
    
    async loadCategories() {
      try {
        const response = await fetch('/pos/categories');
        this.categories = await response.json();
      } catch (error) {
        console.error('Error loading categories:', error);
      }
    },
    
    async submitProduct() {
      this.loading = true;
      
      try {
        const url = this.showAddModal ? '/pos/products' : `/pos/products/${this.productForm.id}`;
        const method = this.showAddModal ? 'POST' : 'PUT';
        
        const response = await fetch(url, {
          method: method,
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify(this.productForm)
        });
        
        if (response.ok) {
          this.closeModal();
          this.loadProducts();
        } else {
          const data = await response.json();
          alert('Error: ' + (data.message || 'Terjadi kesalahan'));
        }
      } catch (error) {
        console.error('Error saving product:', error);
        alert('Terjadi kesalahan saat menyimpan produk');
      } finally {
        this.loading = false;
      }
    },
    
    editProduct(product) {
      this.productForm = { ...product };
      this.showEditModal = true;
    },
    
    async deleteProduct(productId) {
      if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
        try {
          const response = await fetch(`/pos/products/${productId}`, {
            method: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
          });
          
          if (response.ok) {
            this.loadProducts();
          } else {
            alert('Error menghapus produk');
          }
        } catch (error) {
          console.error('Error deleting product:', error);
          alert('Terjadi kesalahan saat menghapus produk');
        }
      }
    },
    
    closeModal() {
      this.showAddModal = false;
      this.showEditModal = false;
      this.productForm = {
        name: '',
        description: '',
        price: '',
        stock: '',
        category_id: '',
        is_active: true
      };
    },
    
    refreshData() {
      this.loadProducts();
      this.loadCategories();
    },
    
    exportData() {
      // Simple CSV export
      const csvContent = this.filteredProducts.map(product => 
        `${product.name},${product.category?.name || ''},${product.price},${product.stock},${product.is_active ? 'Aktif' : 'Tidak Aktif'}`
      ).join('\n');
      
      const blob = new Blob([`Nama,Kategori,Harga,Stok,Status\n${csvContent}`], { type: 'text/csv' });
      const url = window.URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;
      a.download = 'products.csv';
      a.click();
      window.URL.revokeObjectURL(url);
    },
    
    formatPrice(price) {
      return new Intl.NumberFormat('id-ID').format(price);
    },
    
    getStockStatusClass(stock) {
      if (stock <= 0) return 'bg-red-500';
      if (stock <= 10) return 'bg-yellow-500';
      return 'bg-green-500';
    },
    
    previousPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    },
    
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    },
    
    goToPage(page) {
      this.currentPage = page;
    }
  }
}
</script>