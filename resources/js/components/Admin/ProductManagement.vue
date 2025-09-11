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
    <div class="shadow-xl rounded-lg p-4 md:p-6 transition-colors duration-300" :class="isDarkMode ? 'bg-gray-800 border border-gray-700' : 'bg-white border border-gray-200'">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div>
          <label class="block text-sm font-semibold mb-2 transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">
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
            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-150" :class="isDarkMode ? 'bg-gray-700 border-gray-600 text-white placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500'"
          >
        </div>
        <div>
          <label class="block text-sm font-semibold mb-2 transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">
            <div class="flex items-center space-x-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
              </svg>
              <span>Kategori</span>
            </div>
          </label>
          <select 
            v-model="selectedCategory" 
            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-150" :class="isDarkMode ? 'bg-gray-700 border-gray-600 text-white' : 'bg-white border-gray-300 text-gray-900'"
          >
            <option value="">Semua Kategori</option>
            <option v-for="category in categories" :key="category.id" :value="category.id">
              {{ category.name }}
            </option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-semibold mb-2 transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">
            <div class="flex items-center space-x-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <span>Status</span>
            </div>
          </label>
          <select 
            v-model="statusFilter" 
            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-150" :class="isDarkMode ? 'bg-gray-700 border-gray-600 text-white' : 'bg-white border-gray-300 text-gray-900'"
          >
            <option value="">Semua Status</option>
            <option value="active">Aktif</option>
            <option value="inactive">Tidak Aktif</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-semibold mb-2 transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">
            <div class="flex items-center space-x-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z"></path>
              </svg>
              <span>Urutkan</span>
            </div>
          </label>
          <select 
            v-model="sortBy" 
            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-150" :class="isDarkMode ? 'bg-gray-700 border-gray-600 text-white' : 'bg-white border-gray-300 text-gray-900'"
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
    <div class="shadow-xl rounded-lg overflow-hidden transition-colors duration-300" :class="isDarkMode ? 'bg-gray-800 border border-gray-700' : 'bg-white border border-gray-200'">
      <div class="px-6 py-4 border-b transition-colors duration-300" :class="isDarkMode ? 'bg-gray-700 border-gray-600' : 'bg-gray-50 border-gray-200'">
        <div class="flex justify-between items-center">
          <div>
            <h3 class="text-lg font-semibold transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Daftar Produk</h3>
            <p class="text-sm transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">Menampilkan {{ filteredProducts.length }} dari {{ products.length }} produk</p>
          </div>
          <div class="flex items-center space-x-2">
            <button 
              @click="refreshData"
              class="p-2 rounded-lg transition-colors duration-150" :class="isDarkMode ? 'text-gray-300 hover:text-white hover:bg-gray-600' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100'"
              title="Refresh Data"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
              </svg>
            </button>
            <button 
              @click="exportData"
              class="p-2 rounded-lg transition-colors duration-150" :class="isDarkMode ? 'text-gray-300 hover:text-white hover:bg-gray-600' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100'"
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
        <table class="min-w-full transition-colors duration-300" :class="isDarkMode ? 'divide-gray-700' : 'divide-gray-200'">
          <thead class="transition-colors duration-300" :class="isDarkMode ? 'bg-gradient-to-r from-gray-700 to-gray-600' : 'bg-gradient-to-r from-gray-50 to-gray-100'">
            <tr>
              <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">
                <div class="flex items-center space-x-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                  </svg>
                  <span>Gambar</span>
                </div>
              </th>
              <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">
                <div class="flex items-center space-x-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                  </svg>
                  <span>Produk</span>
                </div>
              </th>
              <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">
                <div class="flex items-center space-x-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                  </svg>
                  <span>Kategori</span>
                </div>
              </th>
              <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">
                <div class="flex items-center space-x-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                  </svg>
                  <span>Harga</span>
                </div>
              </th>
              <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">
                <div class="flex items-center space-x-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                  </svg>
                  <span>Stok</span>
                </div>
              </th>
              <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">
                <div class="flex items-center space-x-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                  <span>Status</span>
                </div>
              </th>
              <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">
                <div class="flex items-center space-x-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                  </svg>
                  <span>Aksi</span>
                </div>
              </th>
            </tr>
          </thead>
          <tbody class="transition-colors duration-300" :class="isDarkMode ? 'bg-gray-800 divide-gray-700' : 'bg-white divide-gray-200'">
            <tr v-for="product in paginatedProducts" :key="product.id" class="transition-colors duration-150" :class="isDarkMode ? 'hover:bg-gray-700' : 'hover:bg-gray-50'">
              <td class="px-3 md:px-6 py-4 whitespace-nowrap">
                <div class="flex-shrink-0 h-12 w-12 md:h-16 md:w-16">
                  <img 
                    v-if="product.image_url" 
                    :src="product.image_url" 
                    :alt="product.name"
                    class="h-12 w-12 md:h-16 md:w-16 rounded-lg object-cover border border-gray-200"
                  >
                  <div 
                    v-else
                    class="h-12 w-12 md:h-16 md:w-16 rounded-lg bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center border border-gray-200"
                  >
                    <span class="text-white font-bold text-sm md:text-lg">{{ product.name.charAt(0).toUpperCase() }}</span>
                  </div>
                </div>
              </td>
              <td class="px-3 md:px-6 py-4 whitespace-nowrap">
                <div class="min-w-0">
                  <div class="text-sm font-semibold truncate transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ product.name }}</div>
                  <div class="text-xs md:text-sm truncate max-w-32 md:max-w-xs transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">{{ product.description }}</div>
                </div>
              </td>
              <td class="px-3 md:px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center px-2 md:px-3 py-1 rounded-full text-xs font-semibold transition-colors duration-300" :class="isDarkMode ? 'bg-blue-900 text-blue-300' : 'bg-blue-100 text-blue-800'">
                  {{ product.category?.name || 'Tidak ada kategori' }}
                </span>
              </td>
              <td class="px-3 md:px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-semibold transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Rp {{ formatPrice(product.price) }}</div>
              </td>
              <td class="px-3 md:px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="text-sm font-semibold transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ product.stock }}</div>
                  <div class="ml-2">
                    <div :class="getStockStatusClass(product.stock)" class="w-2 h-2 rounded-full"></div>
                  </div>
                </div>
              </td>
              <td class="px-3 md:px-6 py-4 whitespace-nowrap">
                <span 
                  :class="product.is_active ? (isDarkMode ? 'bg-green-900 text-green-300' : 'bg-green-100 text-green-800') : (isDarkMode ? 'bg-red-900 text-red-300' : 'bg-red-100 text-red-800')"
                  class="inline-flex items-center px-2 md:px-3 py-1 rounded-full text-xs font-semibold transition-colors duration-300"
                >
                  <div :class="product.is_active ? 'bg-green-400' : 'bg-red-400'" class="w-2 h-2 rounded-full mr-1 md:mr-2"></div>
                  <span class="hidden sm:inline">{{ product.is_active ? 'Aktif' : 'Tidak Aktif' }}</span>
                  <span class="sm:hidden">{{ product.is_active ? 'A' : 'TA' }}</span>
                </span>
              </td>
              <td class="px-3 md:px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex flex-col sm:flex-row space-y-1 sm:space-y-0 sm:space-x-2">
                  <button 
                    @click="editProduct(product)"
                    class="px-2 md:px-3 py-1 rounded-md transition-colors duration-150 flex items-center justify-center space-x-1 text-xs" :class="isDarkMode ? 'text-blue-400 hover:text-blue-300 bg-blue-900 hover:bg-blue-800' : 'text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100'"
                  >
                    <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span class="hidden sm:inline">Edit</span>
                  </button>
                  <button 
                    @click="deleteProduct(product.id)"
                    class="px-2 md:px-3 py-1 rounded-md transition-colors duration-150 flex items-center justify-center space-x-1 text-xs" :class="isDarkMode ? 'text-red-400 hover:text-red-300 bg-red-900 hover:bg-red-800' : 'text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100'"
                  >
                    <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    <span class="hidden sm:inline">Hapus</span>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="px-4 py-3 flex items-center justify-between border-t sm:px-6 transition-colors duration-300" :class="isDarkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'">
        <div class="flex-1 flex justify-between sm:hidden">
          <button 
            @click="previousPage"
            :disabled="currentPage === 1"
            class="relative inline-flex items-center px-4 py-2 border text-sm font-medium rounded-md disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-150" :class="isDarkMode ? 'border-gray-600 text-gray-300 bg-gray-700 hover:bg-gray-600' : 'border-gray-300 text-gray-700 bg-white hover:bg-gray-50'"
          >
            Previous
          </button>
          <button 
            @click="nextPage"
            :disabled="currentPage === totalPages"
            class="ml-3 relative inline-flex items-center px-4 py-2 border text-sm font-medium rounded-md disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-150" :class="isDarkMode ? 'border-gray-600 text-gray-300 bg-gray-700 hover:bg-gray-600' : 'border-gray-300 text-gray-700 bg-white hover:bg-gray-50'"
          >
            Next
          </button>
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
          <div>
            <p class="text-sm transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">
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
                class="relative inline-flex items-center px-2 py-2 rounded-l-md border text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-150" :class="isDarkMode ? 'border-gray-600 bg-gray-700 text-gray-300 hover:bg-gray-600' : 'border-gray-300 bg-white text-gray-500 hover:bg-gray-50'"
              >
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
              </button>
              
              <button 
                v-for="page in visiblePages" 
                :key="page"
                @click="goToPage(page)"
                :class="page === currentPage ? (isDarkMode ? 'bg-green-600 border-green-500 text-white' : 'bg-green-50 border-green-500 text-green-600') : (isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-300 hover:bg-gray-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50')"
                class="relative inline-flex items-center px-4 py-2 border text-sm font-medium transition-colors duration-150"
              >
                {{ page }}
              </button>
              
              <button 
                @click="nextPage"
                :disabled="currentPage === totalPages"
                class="relative inline-flex items-center px-2 py-2 rounded-r-md border text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-150" :class="isDarkMode ? 'border-gray-600 bg-gray-700 text-gray-300 hover:bg-gray-600' : 'border-gray-300 bg-white text-gray-500 hover:bg-gray-50'"
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
    <div v-if="showAddModal || showEditModal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-2 sm:p-4">
      <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl transform transition-all max-h-[90vh] overflow-y-auto">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
          <div class="flex items-center justify-between">
            <h3 class="text-lg sm:text-xl font-bold text-gray-900">
              {{ showAddModal ? 'Tambah Produk Baru' : 'Edit Produk' }}
            </h3>
            <button 
              @click="closeModal"
              class="text-gray-400 hover:text-gray-600 transition-colors duration-150 p-1"
            >
              <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
        </div>
        
        <form @submit.prevent="submitProduct" class="px-4 sm:px-6 py-4">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
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
            
            <div class="sm:col-span-2">
              <label class="block text-sm font-semibold text-gray-700 mb-2">Gambar Produk</label>
              <div class="space-y-4">
                <!-- Image Preview -->
                <div v-if="productForm.image_preview || productForm.image_url" class="flex items-center space-x-4">
                  <img 
                    :src="productForm.image_preview || productForm.image_url" 
                    :alt="productForm.name"
                    class="h-20 w-20 rounded-lg object-cover border border-gray-200"
                  >
                  <div>
                    <p class="text-sm text-gray-600">Gambar saat ini</p>
                    <button 
                      type="button"
                      @click="removeImage"
                      class="text-red-600 hover:text-red-800 text-sm font-medium"
                    >
                      Hapus Gambar
                    </button>
                  </div>
                </div>
                
                <!-- Image Upload -->
                <div class="flex items-center justify-center w-full">
                  <label for="image-upload" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors duration-150">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                      <svg class="w-8 h-8 mb-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                      </svg>
                      <p class="mb-2 text-sm text-gray-500">
                        <span class="font-semibold">Klik untuk upload</span> atau drag & drop
                      </p>
                      <p class="text-xs text-gray-500">PNG, JPG, GIF (MAX. 2MB)</p>
                    </div>
                    <input 
                      id="image-upload" 
                      type="file" 
                      class="hidden" 
                      accept="image/*"
                      @change="handleImageUpload"
                    >
                  </label>
                </div>
              </div>
            </div>
            
            <div class="sm:col-span-2">
              <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
              <textarea 
                v-model="productForm.description"
                rows="3"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-150"
                placeholder="Masukkan deskripsi produk"
              ></textarea>
            </div>
            
            <div class="sm:col-span-2">
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
          
          <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3 mt-6 sm:mt-8 pt-4 border-t border-gray-200">
            <button 
              type="button"
              @click="closeModal"
              class="w-full sm:w-auto px-6 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors duration-150"
            >
              Batal
            </button>
            <button 
              type="submit"
              :disabled="loading"
              class="w-full sm:w-auto px-6 py-2 bg-gradient-to-r from-green-600 to-blue-600 text-white rounded-lg text-sm font-medium hover:from-green-700 hover:to-blue-700 disabled:opacity-50 transition-all duration-150 flex items-center justify-center space-x-2"
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
  props: {
    isDarkMode: { type: Boolean, default: false }
  },
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
        is_active: true,
        image: null,
        image_preview: null,
        image_url: null
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
        console.log('Loaded products:', this.products);
        
        // Log image URLs for debugging
        this.products.forEach(product => {
          if (product.image_url) {
            console.log(`Product ${product.name} image_url:`, product.image_url);
          }
        });
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
        
        console.log('Submitting product:', {
          url,
          method,
          productForm: this.productForm,
          hasImage: !!this.productForm.image
        });
        
        // Validate required fields before sending
        if (!this.productForm.name || !this.productForm.price || !this.productForm.stock || !this.productForm.category_id) {
          alert('Mohon lengkapi semua field yang wajib diisi!');
          this.loading = false;
          return;
        }
        
        // Create FormData for file upload
        const formData = new FormData();
        formData.append('name', this.productForm.name);
        formData.append('description', this.productForm.description || '');
        formData.append('price', this.productForm.price.toString());
        formData.append('stock', this.productForm.stock.toString());
        formData.append('category_id', this.productForm.category_id.toString());
        formData.append('is_active', this.productForm.is_active ? '1' : '0');
        
        // Add _method for PUT request (Laravel needs this for form data)
        if (method === 'PUT') {
          formData.append('_method', 'PUT');
          console.log('Added _method=PUT to FormData');
        }
        
        // Add image if exists
        if (this.productForm.image) {
          formData.append('image', this.productForm.image);
          console.log('Image file added:', this.productForm.image.name, this.productForm.image.size);
        }
        
        // Log FormData contents
        console.log('FormData contents:');
        for (let [key, value] of formData.entries()) {
          console.log(key, value);
        }
        
        // Check if FormData is empty
        const formDataSize = Array.from(formData.entries()).length;
        console.log('FormData size:', formDataSize);
        
        if (formDataSize === 0) {
          console.error('FormData is empty!');
          alert('Error: Form data kosong. Silakan coba lagi.');
          this.loading = false;
          return;
        }
        
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        console.log('CSRF Token:', csrfToken);
        
        if (!csrfToken) {
          console.error('CSRF token not found!');
          alert('Error: CSRF token tidak ditemukan. Silakan refresh halaman.');
          this.loading = false;
          return;
        }
        
        // Try with axios instead of fetch
        const axios = window.axios || (await import('axios')).default;
        
        // For PUT requests, use POST with _method field (Laravel form method spoofing)
        const actualMethod = method === 'PUT' ? 'POST' : method.toLowerCase();
        const actualUrl = method === 'PUT' ? url.replace(`/products/${this.productForm.id}`, '/products/' + this.productForm.id) : url;
        
        console.log('Actual method:', actualMethod);
        console.log('Actual URL:', actualUrl);
        
        const response = await axios({
          method: actualMethod,
          url: actualUrl,
          data: formData,
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'multipart/form-data'
          }
        });
        
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        const responseData = response.data;
        console.log('Response data:', responseData);
        
        if (response.status >= 200 && response.status < 300) {
          this.closeModal();
          this.loadProducts();
          alert('Produk berhasil disimpan!');
        } else {
          console.error('Error response:', responseData);
          let errorMessage = 'Terjadi kesalahan saat menyimpan produk';
          
          if (responseData.message) {
            errorMessage = responseData.message;
          } else if (responseData.errors) {
            const errorList = Object.values(responseData.errors).flat().join(', ');
            errorMessage = `Validasi gagal: ${errorList}`;
          } else if (responseData.error) {
            errorMessage = responseData.error;
          }
          
          alert('Error: ' + errorMessage);
        }
      } catch (error) {
        console.error('Error saving product:', error);
        
        let errorMessage = 'Terjadi kesalahan saat menyimpan produk';
        
        if (error.response) {
          // Server responded with error status
          const responseData = error.response.data;
          console.error('Error response data:', responseData);
          console.error('Error status:', error.response.status);
          console.error('Error headers:', error.response.headers);
          
          if (responseData.message) {
            errorMessage = responseData.message;
          } else if (responseData.errors) {
            const errorList = Object.values(responseData.errors).flat().join(', ');
            errorMessage = `Validasi gagal: ${errorList}`;
            console.error('Validation errors:', responseData.errors);
          } else if (responseData.error) {
            errorMessage = responseData.error;
          }
        } else if (error.request) {
          // Request was made but no response received
          errorMessage = 'Tidak ada response dari server. Periksa koneksi internet.';
        } else {
          // Something else happened
          errorMessage = error.message;
        }
        
        alert('Error: ' + errorMessage);
      } finally {
        this.loading = false;
      }
    },
    
    editProduct(product) {
      this.productForm = { 
        ...product,
        image: null,
        image_preview: null,
        image_url: product.image_url || null
      };
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
        is_active: true,
        image: null,
        image_preview: null,
        image_url: null
      };
      
      // Reset file input
      const fileInput = document.getElementById('image-upload');
      if (fileInput) {
        fileInput.value = '';
      }
      
      console.log('Modal closed, form reset');
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
    },
    
    handleImageUpload(event) {
      const file = event.target.files[0];
      console.log('Image upload triggered:', file);
      
      if (file) {
        // Validate file size (2MB max)
        if (file.size > 2 * 1024 * 1024) {
          alert('Ukuran file terlalu besar. Maksimal 2MB.');
          event.target.value = ''; // Reset input
          return;
        }
        
        // Validate file type
        if (!file.type.startsWith('image/')) {
          alert('File harus berupa gambar.');
          event.target.value = ''; // Reset input
          return;
        }
        
        console.log('Valid image file:', {
          name: file.name,
          size: file.size,
          type: file.type
        });
        
        this.productForm.image = file;
        
        // Create preview
        const reader = new FileReader();
        reader.onload = (e) => {
          this.productForm.image_preview = e.target.result;
          console.log('Image preview created');
        };
        reader.readAsDataURL(file);
      } else {
        console.log('No file selected');
      }
    },
    
    removeImage() {
      this.productForm.image = null;
      this.productForm.image_preview = null;
      this.productForm.image_url = null;
      
      // Reset file input
      const fileInput = document.getElementById('image-upload');
      if (fileInput) {
        fileInput.value = '';
      }
    },
    
    // Method to handle image removal during edit
    removeExistingImage() {
      this.productForm.image_url = null;
      this.productForm.image_preview = null;
      this.productForm.image = null;
      
      // Reset file input
      const fileInput = document.getElementById('image-upload');
      if (fileInput) {
        fileInput.value = '';
      }
    }
  }
}
</script>