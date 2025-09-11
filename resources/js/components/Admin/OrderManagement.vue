<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg p-6 text-white">
      <div class="flex justify-between items-center">
        <div>
          <h2 class="text-3xl font-bold mb-2">Manajemen Pesanan</h2>
          <p class="text-blue-100">Kelola pesanan dan transaksi pelanggan</p>
        </div>
        <div class="flex space-x-3">
          <!-- Pending Transfer Counter -->
          <div v-if="pendingTransferCount > 0" class="bg-yellow-500 text-white px-4 py-2 rounded-lg flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="font-semibold">{{ pendingTransferCount }} Transfer Menunggu</span>
          </div>
          <button 
            @click="refreshData"
            class="bg-white text-blue-600 hover:bg-blue-50 px-6 py-3 rounded-lg text-sm font-semibold shadow-lg hover:shadow-xl transition-all duration-200 flex items-center space-x-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            <span>Refresh Data</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white shadow-xl rounded-lg p-6">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">
            <div class="flex items-center space-x-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
              </svg>
              <span>Cari Pesanan</span>
            </div>
          </label>
          <input 
            v-model="searchQuery" 
            type="text" 
            placeholder="Nomor pesanan, nama pelanggan..." 
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-150"
          >
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
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-150"
          >
            <option value="">Semua Status</option>
            <option value="pending">Pending</option>
            <option value="completed">Selesai</option>
            <option value="cancelled">Dibatalkan</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">
            <div class="flex items-center space-x-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
              <span>Tanggal</span>
            </div>
          </label>
          <input 
            v-model="dateFilter" 
            type="date" 
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-150"
          >
        </div>
      </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white shadow-xl rounded-lg overflow-hidden">
      <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <div class="flex justify-between items-center">
          <div>
            <h3 class="text-lg font-semibold text-gray-900">Daftar Pesanan</h3>
            <p class="text-sm text-gray-600">Menampilkan {{ filteredOrders.length }} dari {{ orders.length }} pesanan</p>
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                  </svg>
                  <span>No. Pesanan</span>
                </div>
              </th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                <div class="flex items-center space-x-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                  </svg>
                  <span>Pelanggan</span>
                </div>
              </th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                <div class="flex items-center space-x-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                  </svg>
                  <span>Total</span>
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                  </svg>
                  <span>Pembayaran</span>
                </div>
              </th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                <div class="flex items-center space-x-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                  </svg>
                  <span>Info Transfer</span>
                </div>
              </th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                <div class="flex items-center space-x-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                  </svg>
                  <span>Tanggal</span>
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
            <tr v-for="order in paginatedOrders" :key="order.id" class="hover:bg-gray-50 transition-colors duration-150">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-semibold text-gray-900">{{ order.order_number }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div>
                  <div class="text-sm font-semibold text-gray-900">{{ order.customer_name || 'Tidak ada nama' }}</div>
                  <div class="text-sm text-gray-500">{{ order.customer_phone || 'Tidak ada telepon' }}</div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-semibold text-gray-900">Rp {{ formatPrice(order.total) }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span 
                  :class="getStatusClass(order.status)"
                  class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold"
                >
                  <div :class="getStatusDotClass(order.status)" class="w-2 h-2 rounded-full mr-2"></div>
                  {{ getStatusText(order.status) }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span 
                  :class="getPaymentStatusClass(order.payment_status)"
                  class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold"
                >
                  <div :class="getPaymentStatusDotClass(order.payment_status)" class="w-2 h-2 rounded-full mr-2"></div>
                  {{ getPaymentStatusText(order.payment_status) }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div v-if="order.payment_method === 'transfer'" class="text-sm">
                  <div class="font-semibold text-gray-900">Transfer Bank</div>
                  <div class="text-gray-500">
                    <span class="mr-1">Nominal:</span>
                    <span class="font-medium text-blue-600">Rp {{ formatPrice(order.transfer_amount || order.total) }}</span>
                  </div>
                  <div v-if="order.unique_code" class="text-gray-500">
                    <span class="mr-1">Kode Unik:</span>
                    <span class="font-medium">{{ order.unique_code }}</span>
                  </div>
                  <div v-if="order.transfer_verified_at" class="text-green-600 text-xs mt-1">
                    ✓ Diverifikasi {{ formatDate(order.transfer_verified_at) }}
                  </div>
                  <div v-else class="text-yellow-600 text-xs mt-1">
                    ⏳ Menunggu verifikasi
                  </div>
                </div>
                <div v-else class="text-sm text-gray-500">
                  {{ getPaymentMethodText(order.payment_method) }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ formatDate(order.created_at) }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex space-x-2">
                  <button 
                    @click="viewOrder(order)"
                    class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-md transition-colors duration-150 flex items-center space-x-1"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    <span>Lihat</span>
                  </button>
                  <button 
                    v-if="order.status === 'pending'"
                    @click="updateOrderStatus(order, 'completed')" 
                    class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 px-3 py-1 rounded-md transition-colors duration-150 flex items-center space-x-1"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Selesai</span>
                  </button>
                  <button 
                    v-if="order.status === 'pending'"
                    @click="updateOrderStatus(order, 'cancelled')" 
                    class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1 rounded-md transition-colors duration-150 flex items-center space-x-1"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span>Batal</span>
                  </button>
                  <button 
                    v-if="order.payment_status === 'pending' && (order.payment_method === 'qris' || order.payment_method === 'transfer')"
                    @click="verifyTransfer(order)"
                    class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-md transition-colors duration-150 flex items-center space-x-1"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Verifikasi</span>
                  </button>
                  <button 
                    v-if="order.payment_method === 'transfer'"
                    @click="showTransferDetails(order)"
                    class="text-purple-600 hover:text-purple-900 bg-purple-50 hover:bg-purple-100 px-3 py-1 rounded-md transition-colors duration-150 flex items-center space-x-1"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Detail Transfer</span>
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
              <span class="font-medium">{{ Math.min(currentPage * itemsPerPage, filteredOrders.length) }}</span>
              dari
              <span class="font-medium">{{ filteredOrders.length }}</span>
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
                :class="page === currentPage ? 'bg-blue-50 border-blue-500 text-blue-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'"
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

    <!-- Order Detail Modal -->
    <div v-if="showOrderModal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-2 sm:p-4">
      <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl transform transition-all max-h-[90vh] overflow-y-auto">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
          <div class="flex items-center justify-between">
            <h3 class="text-lg sm:text-xl font-bold text-gray-900">Detail Pesanan</h3>
            <button 
              @click="showOrderModal = false"
              class="text-gray-400 hover:text-gray-600 transition-colors duration-150 p-1"
            >
              <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
        </div>
        
        <div v-if="selectedOrder" class="px-4 sm:px-6 py-4">
          <div class="space-y-6">
            <!-- Order Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Informasi Pesanan</h4>
                <div class="space-y-2">
                  <div class="flex justify-between">
                    <span class="text-sm text-gray-600">No. Pesanan:</span>
                    <span class="text-sm font-medium text-gray-900">{{ selectedOrder.order_number }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Tanggal:</span>
                    <span class="text-sm font-medium text-gray-900">{{ formatDate(selectedOrder.created_at) }}</span>
                  </div>
                  <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Status:</span>
                    <span :class="getStatusClass(selectedOrder.status)" class="px-2 py-1 text-xs rounded-full font-semibold">
                      {{ getStatusText(selectedOrder.status) }}
                    </span>
                  </div>
                </div>
              </div>
              
              <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Informasi Pelanggan</h4>
                <div class="space-y-2">
                  <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Nama:</span>
                    <span class="text-sm font-medium text-gray-900">{{ selectedOrder.customer_name || 'Tidak ada nama' }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Telepon:</span>
                    <span class="text-sm font-medium text-gray-900">{{ selectedOrder.customer_phone || 'Tidak ada telepon' }}</span>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Order Items -->
            <div>
              <h4 class="text-sm font-semibold text-gray-700 mb-3">Item Pesanan</h4>
              <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                  <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                      <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Qty</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Subtotal</th>
                      </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                      <tr v-for="item in selectedOrder.order_items" :key="item.id" class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                          {{ item.product ? item.product.name : 'Produk tidak ditemukan' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                          Rp {{ formatPrice(item.price) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                          {{ item.quantity }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                          Rp {{ formatPrice(item.subtotal) }}
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            
            <!-- Order Summary -->
            <div class="bg-gray-50 rounded-lg p-4">
              <h4 class="text-sm font-semibold text-gray-700 mb-3">Ringkasan Pembayaran</h4>
              <div class="space-y-2">
                <div class="flex justify-between text-sm">
                  <span class="text-gray-600">Subtotal:</span>
                  <span class="font-medium text-gray-900">Rp {{ formatPrice(selectedOrder.subtotal) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-gray-600">Pajak (10%):</span>
                  <span class="font-medium text-gray-900">Rp {{ formatPrice(selectedOrder.tax) }}</span>
                </div>
                <div class="border-t border-gray-300 pt-2">
                  <div class="flex justify-between text-base font-bold">
                    <span class="text-gray-900">Total:</span>
                    <span class="text-blue-600">Rp {{ formatPrice(selectedOrder.total) }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Transfer Details Modal -->
    <div v-if="showTransferModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-lg p-6 max-w-md w-full">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-semibold text-gray-900">Detail Transfer Bank</h3>
          <button @click="showTransferModal = false" class="text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
        
        <div v-if="selectedTransferOrder" class="space-y-4">
          <div class="bg-gray-50 rounded-lg p-4">
            <h4 class="font-semibold text-gray-900 mb-2">Informasi Pesanan</h4>
            <div class="space-y-2 text-sm">
              <div class="flex justify-between">
                <span class="text-gray-600">Nomor Pesanan:</span>
                <span class="font-medium">{{ selectedTransferOrder.order_number }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Nama Customer:</span>
                <span class="font-medium">{{ selectedTransferOrder.customer_name || 'Tidak ada nama' }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Total Transfer:</span>
                <span class="font-medium text-blue-600">Rp {{ formatPrice(selectedTransferOrder.total) }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Status:</span>
                <span :class="getPaymentStatusClass(selectedTransferOrder.payment_status)" class="px-2 py-1 rounded-full text-xs font-semibold">
                  {{ getPaymentStatusText(selectedTransferOrder.payment_status) }}
                </span>
              </div>
            </div>
          </div>

          <div class="bg-blue-50 rounded-lg p-4">
            <h4 class="font-semibold text-gray-900 mb-2">Informasi Rekening</h4>
            <div class="space-y-2 text-sm">
              <div class="flex justify-between">
                <span class="text-gray-600">Bank:</span>
                <span class="font-medium">Bank BCA</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">No. Rekening:</span>
                <span class="font-medium">1234567890</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Atas Nama:</span>
                <span class="font-medium">Toko Angkringan</span>
              </div>
            </div>
          </div>

          <div v-if="selectedTransferOrder.transfer_notes" class="bg-yellow-50 rounded-lg p-4">
            <h4 class="font-semibold text-gray-900 mb-2">Catatan Transfer</h4>
            <p class="text-sm text-gray-700">{{ selectedTransferOrder.transfer_notes }}</p>
          </div>

          <div class="flex space-x-3">
            <button 
              @click="showTransferModal = false"
              class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
            >
              Tutup
            </button>
            <button 
              v-if="selectedTransferOrder.payment_status === 'pending'"
              @click="verifyTransfer(selectedTransferOrder); showTransferModal = false"
              class="flex-1 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors"
            >
              Verifikasi Transfer
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'OrderManagement',
  props: {
    isDarkMode: { type: Boolean, default: false }
  },
  data() {
    return {
      orders: [],
      searchQuery: '',
      statusFilter: '',
      dateFilter: '',
      showOrderModal: false,
      selectedOrder: null,
      currentPage: 1,
      itemsPerPage: 10,
      autoRefreshInterval: null,
      showTransferModal: false,
      selectedTransferOrder: null
    }
  },
  computed: {
    filteredOrders() {
      let filtered = this.orders;
      
      if (this.searchQuery) {
        filtered = filtered.filter(order => 
          order.order_number.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
          (order.customer_name && order.customer_name.toLowerCase().includes(this.searchQuery.toLowerCase()))
        );
      }
      
      if (this.statusFilter) {
        filtered = filtered.filter(order => order.status === this.statusFilter);
      }
      
      if (this.dateFilter) {
        filtered = filtered.filter(order => 
          order.created_at.startsWith(this.dateFilter)
        );
      }
      
      return filtered;
    },
    paginatedOrders() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.filteredOrders.slice(start, end);
    },
    totalPages() {
      return Math.ceil(this.filteredOrders.length / this.itemsPerPage);
    },
    visiblePages() {
      const pages = [];
      const start = Math.max(1, this.currentPage - 2);
      const end = Math.min(this.totalPages, this.currentPage + 2);
      
      for (let i = start; i <= end; i++) {
        pages.push(i);
      }
      return pages;
    },
    pendingTransferCount() {
      return this.orders.filter(order => 
        order.payment_method === 'transfer' && 
        order.payment_status === 'pending'
      ).length;
    }
  },
  async mounted() {
    await this.loadOrders();
    // Auto refresh every 30 seconds to check for new transfers
    this.startAutoRefresh();
  },
  beforeUnmount() {
    if (this.autoRefreshInterval) {
      clearInterval(this.autoRefreshInterval);
    }
  },
  methods: {
    async loadOrders() {
      try {
        const response = await axios.get('/api/pos/orders');
        this.orders = response.data;
      } catch (error) {
        console.error('Error loading orders:', error);
      }
    },
    async viewOrder(order) {
      try {
        const response = await axios.get(`/api/pos/orders/${order.id}`);
        this.selectedOrder = response.data;
        this.showOrderModal = true;
      } catch (error) {
        console.error('Error loading order details:', error);
      }
    },
    async updateOrderStatus(order, status) {
      if (confirm(`Apakah Anda yakin ingin mengubah status pesanan menjadi "${this.getStatusText(status)}"?`)) {
        try {
          await axios.put(`/api/pos/orders/${order.id}`, { status });
          await this.loadOrders();
        } catch (error) {
          console.error('Error updating order status:', error);
          alert('Error updating order status');
        }
      }
    },
    getStatusClass(status) {
      const classes = {
        pending: 'bg-yellow-100 text-yellow-800',
        completed: 'bg-green-100 text-green-800',
        cancelled: 'bg-red-100 text-red-800'
      };
      return classes[status] || 'bg-gray-100 text-gray-800';
    },
    getStatusText(status) {
      const texts = {
        pending: 'Pending',
        completed: 'Selesai',
        cancelled: 'Dibatalkan'
      };
      return texts[status] || status;
    },
    formatPrice(price) {
      return new Intl.NumberFormat('id-ID').format(price);
    },
    formatDate(date) {
      return new Date(date).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
    },
    getStatusDotClass(status) {
      const classes = {
        pending: 'bg-yellow-400',
        completed: 'bg-green-400',
        cancelled: 'bg-red-400'
      };
      return classes[status] || 'bg-gray-400';
    },
    getPaymentStatusClass(status) {
      const classes = {
        pending: 'bg-yellow-100 text-yellow-800',
        verified: 'bg-blue-100 text-blue-800',
        paid: 'bg-green-100 text-green-800',
        failed: 'bg-red-100 text-red-800'
      };
      return classes[status] || 'bg-gray-100 text-gray-800';
    },
    getPaymentStatusText(status) {
      const texts = {
        pending: 'Menunggu',
        verified: 'Terverifikasi',
        paid: 'Lunas',
        failed: 'Gagal'
      };
      return texts[status] || status;
    },
    getPaymentStatusDotClass(status) {
      const classes = {
        pending: 'bg-yellow-400',
        verified: 'bg-blue-400',
        paid: 'bg-green-400',
        failed: 'bg-red-400'
      };
      return classes[status] || 'bg-gray-400';
    },
    async verifyTransfer(order) {
      if (confirm(`Apakah Anda yakin transfer untuk pesanan ${order.order_number} sudah masuk?`)) {
        try {
          console.log('Verifying transfer for order:', order.id);
          
          // Get CSRF token
          const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
          console.log('CSRF Token:', csrfToken);
          
          const response = await axios.post(`/api/pos/orders/${order.id}/verify-transfer`, {
            payment_status: 'verified',
            transfer_notes: 'Transfer terverifikasi oleh admin'
          }, {
            headers: {
              'X-CSRF-TOKEN': csrfToken,
              'Content-Type': 'application/json',
              'Accept': 'application/json'
            }
          });
          
          console.log('Verify transfer response:', response.data);
          await this.loadOrders();
          alert('Transfer berhasil diverifikasi!');
        } catch (error) {
          console.error('Error verifying transfer:', error);
          console.error('Error response:', error.response);
          console.error('Error status:', error.response?.status);
          console.error('Error data:', error.response?.data);
          
          let errorMessage = 'Error verifying transfer';
          if (error.response?.data?.message) {
            errorMessage = error.response.data.message;
          } else if (error.response?.data?.errors) {
            errorMessage = JSON.stringify(error.response.data.errors);
          } else if (error.message) {
            errorMessage = error.message;
          }
          
          alert(`Error verifying transfer: ${errorMessage}`);
        }
      }
    },
    getPaymentMethodText(method) {
      const methods = {
        'cash': 'Tunai',
        'card': 'Kartu',
        'qris': 'QRIS',
        'transfer': 'Transfer Bank'
      };
      return methods[method] || method;
    },
    showTransferDetails(order) {
      this.selectedTransferOrder = order;
      this.showTransferModal = true;
    },
    startAutoRefresh() {
      this.autoRefreshInterval = setInterval(async () => {
        const oldPendingCount = this.pendingTransferCount;
        await this.loadOrders();
        
        // Show notification if new transfer pending
        if (this.pendingTransferCount > oldPendingCount) {
          this.showNotification(`Ada ${this.pendingTransferCount} transfer menunggu verifikasi!`);
        }
      }, 30000); // 30 seconds
    },
    showNotification(message) {
      // Create notification element
      const notification = document.createElement('div');
      notification.className = 'fixed top-4 right-4 bg-yellow-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center space-x-2';
      notification.innerHTML = `
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>${message}</span>
      `;
      
      document.body.appendChild(notification);
      
      // Auto remove after 5 seconds
      setTimeout(() => {
        if (notification.parentNode) {
          notification.parentNode.removeChild(notification);
        }
      }, 5000);
    },
    refreshData() {
      this.loadOrders();
    },
    exportData() {
      // Simple CSV export
      const csvContent = this.filteredOrders.map(order => 
        `${order.order_number},${order.customer_name || ''},${order.total},${this.getStatusText(order.status)},${this.formatDate(order.created_at)}`
      ).join('\n');
      
      const blob = new Blob([`No. Pesanan,Pelanggan,Total,Status,Tanggal\n${csvContent}`], { type: 'text/csv' });
      const url = window.URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;
      a.download = 'orders.csv';
      a.click();
      window.URL.revokeObjectURL(url);
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
