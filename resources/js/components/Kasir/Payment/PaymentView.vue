<template>
  <div class="min-h-screen transition-colors duration-300" :class="isDarkMode ? 'bg-gray-900' : 'bg-gray-50'">
    <!-- Header -->
    <header class="transition-colors duration-300 shadow-sm border-b" :class="isDarkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'">
      <div class="px-6 py-4">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <button 
              @click="goBack"
              class="p-2 rounded-lg transition-colors duration-300" :class="isDarkMode ? 'hover:bg-gray-700 text-gray-300' : 'hover:bg-gray-100 text-gray-600'"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
              </svg>
            </button>
            <div>
              <h1 class="text-xl font-semibold transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Pembayaran</h1>
              <p class="text-sm transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-500'">Pilih metode pembayaran untuk menyelesaikan pesanan</p>
            </div>
          </div>
          <div class="flex items-center space-x-4">
            <button 
              @click="toggleDarkMode"
              class="p-2 rounded-lg transition-colors duration-300" :class="isDarkMode ? 'hover:bg-gray-700 text-yellow-400' : 'hover:bg-gray-100 text-gray-600'"
            >
              <svg v-if="!isDarkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
              </svg>
              <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto p-6">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Order Summary -->
        <div class="space-y-6">
          <!-- Customer Info -->
          <div class="transition-colors duration-300 rounded-lg border p-6" :class="isDarkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'">
            <h3 class="text-lg font-semibold mb-4 transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Informasi Pelanggan</h3>
            <div class="space-y-4">
              <!-- Table Number -->
              <div>
                <label class="block text-sm font-medium mb-2 transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Nomor Meja (Opsional)</label>
                <input
                  v-model="tableNumber"
                  type="text"
                  placeholder="Masukkan nomor meja"
                  class="w-full px-4 py-3 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-300" :class="isDarkMode ? 'bg-gray-700 border-gray-600 text-white placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500'"
                >
              </div>

              <!-- Customer Info -->
              <div>
                <label class="block text-sm font-medium mb-2 transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Nama Pelanggan</label>
                <input
                  v-model="customerName"
                  type="text"
                  placeholder="Masukkan nama pelanggan"
                  class="w-full px-4 py-3 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-300" :class="isDarkMode ? 'bg-gray-700 border-gray-600 text-white placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500'"
                >
              </div>
              <div>
                <label class="block text-sm font-medium mb-2 transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Nomor HP</label>
                <input
                  v-model="customerPhone"
                  type="tel"
                  placeholder="Masukkan nomor HP"
                  class="w-full px-4 py-3 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-300" :class="isDarkMode ? 'bg-gray-700 border-gray-600 text-white placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500'"
                >
              </div>
            </div>
          </div>

          <!-- Order Items -->
          <div class="transition-colors duration-300 rounded-lg border p-6" :class="isDarkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'">
            <h3 class="text-lg font-semibold mb-4 transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Detail Pesanan</h3>
            <div class="space-y-3">
              <div
                v-for="(item, index) in cart"
                :key="index"
                class="flex items-center justify-between py-3 border-b transition-colors duration-300" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'"
              >
                <div class="flex-1">
                  <h4 class="font-medium transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ item.name }}</h4>
                  <p class="text-sm transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">{{ item.quantity }}x Rp {{ formatPrice(item.price) }}</p>
                </div>
                <span class="font-semibold transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Rp {{ formatPrice(item.price * item.quantity) }}</span>
              </div>
            </div>
            
            <!-- Order Summary -->
            <div class="mt-6 pt-4 border-t transition-colors duration-300" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
              <div class="space-y-2">
                <div class="flex justify-between text-sm">
                  <span class="transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">Subtotal</span>
                  <span class="transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Rp {{ formatPrice(cartSubtotal) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">Pajak (10%)</span>
                  <span class="transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Rp {{ formatPrice(tax) }}</span>
                </div>
                <div class="flex justify-between text-lg font-semibold pt-2 border-t transition-colors duration-300" :class="isDarkMode ? 'border-gray-700 text-white' : 'border-gray-200 text-gray-900'">
                  <span>Total</span>
                  <span>Rp {{ formatPrice(displayTotal) }}</span>
                </div>
              </div>
            </div>
          </div>

        </div>

        <!-- Payment Methods -->
        <div class="space-y-6">
          <!-- Payment Method Selection -->
          <div class="transition-colors duration-300 rounded-lg border p-6" :class="isDarkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'">
            <h3 class="text-lg font-semibold mb-4 transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Pilih Metode Pembayaran</h3>
            <div class="space-y-3">
              <!-- Cash Payment -->
              <div
                @click="selectPaymentMethod('cash')"
                class="p-4 rounded-lg border-2 cursor-pointer transition-all duration-200" :class="[
                  paymentMethod === 'cash' 
                    ? (isDarkMode ? 'border-blue-400 bg-blue-900/20' : 'border-blue-500 bg-blue-50')
                    : (isDarkMode ? 'border-gray-600 hover:border-gray-500' : 'border-gray-300 hover:border-gray-400')
                ]"
              >
                <div class="flex items-center space-x-3">
                  <div class="w-12 h-12 rounded-lg flex items-center justify-center" :class="isDarkMode ? 'bg-gray-700' : 'bg-gray-100'">
                    <svg class="w-6 h-6" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                  </div>
                  <div class="flex-1">
                    <h4 class="font-medium transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Tunai</h4>
                    <p class="text-sm transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Bayar dengan uang tunai</p>
                  </div>
                  <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center" :class="[
                    paymentMethod === 'cash' 
                      ? (isDarkMode ? 'border-blue-400 bg-blue-400' : 'border-blue-500 bg-blue-500')
                      : (isDarkMode ? 'border-gray-600' : 'border-gray-300')
                  ]">
                    <div v-if="paymentMethod === 'cash'" class="w-2 h-2 rounded-full bg-white"></div>
                  </div>
                </div>
              </div>


              <!-- Midtrans Payment -->
              <div
                @click="selectPaymentMethod('midtrans')"
                class="p-4 rounded-lg border-2 cursor-pointer transition-all duration-200" :class="[
                  paymentMethod === 'midtrans' 
                    ? (isDarkMode ? 'border-blue-400 bg-blue-900/20' : 'border-blue-500 bg-blue-50')
                    : (isDarkMode ? 'border-gray-600 hover:border-gray-500' : 'border-gray-300 hover:border-gray-400')
                ]"
              >
                <div class="flex items-center space-x-3">
                  <div class="w-12 h-12 rounded-lg flex items-center justify-center" :class="isDarkMode ? 'bg-gray-700' : 'bg-gray-100'">
                    <svg class="w-6 h-6" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                  </div>
                  <div class="flex-1">
                    <h4 class="font-medium transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Midtrans</h4>
                    <p class="text-sm transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Kartu kredit, e-wallet, dll</p>
                  </div>
                  <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center" :class="[
                    paymentMethod === 'midtrans' 
                      ? (isDarkMode ? 'border-blue-400 bg-blue-400' : 'border-blue-500 bg-blue-500')
                      : (isDarkMode ? 'border-gray-600' : 'border-gray-300')
                  ]">
                    <div v-if="paymentMethod === 'midtrans'" class="w-2 h-2 rounded-full bg-white"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="transition-colors duration-300 rounded-lg border p-6" :class="isDarkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'">
            <!-- Process Payment Button -->
            <button
              @click="processPayment"
              :disabled="loading || !paymentMethod || cart.length === 0"
              class="w-full py-4 px-6 rounded-lg font-semibold text-white transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed mb-4"
              :class="loading 
                ? 'bg-gray-400 cursor-not-allowed' 
                : 'bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5'"
            >
              <div v-if="loading" class="flex items-center justify-center space-x-2">
                <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Memproses...</span>
              </div>
              <div v-else class="flex items-center justify-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
                <span>Proses Pembayaran</span>
              </div>
            </button>

            <!-- Secondary Action Buttons -->
            <div class="flex space-x-3">
              <!-- Cancel Order Button -->
              <button
                @click="cancelCurrentOrder"
                :disabled="loading"
                class="flex-1 py-3 px-4 text-sm font-medium rounded-lg transition-colors duration-300 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center space-x-2"
                :class="isDarkMode ? 'text-red-300 bg-red-900/20 hover:bg-red-900/30 border border-red-700' : 'text-red-700 bg-red-50 hover:bg-red-100 border border-red-200'"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                <span>Batalkan Pesanan</span>
              </button>

              <!-- Draft Orders Button -->
              <button
                @click="showDraftOrdersModal = true"
                :disabled="loading"
                class="flex-1 py-3 px-4 text-sm font-medium rounded-lg transition-colors duration-300 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center space-x-2"
                :class="isDarkMode ? 'text-blue-300 bg-blue-900/20 hover:bg-blue-900/30 border border-blue-700' : 'text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200'"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <span>Draft Pesanan</span>
              </button>
            </div>
          </div>

        </div>
      </div>
    </div>

    <!-- Cash Payment Modal -->
    <div v-if="showCashModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="rounded-lg p-6 max-w-md w-full transition-colors duration-300" :class="isDarkMode ? 'bg-gray-800' : 'bg-white'">
        <div class="text-center">
          <h3 class="text-lg font-semibold mb-4 transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Pembayaran Tunai</h3>
          <p class="text-sm mb-6 transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">Masukkan nominal uang yang diterima dari pelanggan</p>
          
          <!-- Total Amount -->
          <div class="rounded-lg p-4 mb-6 transition-colors duration-300" :class="isDarkMode ? 'bg-gray-700' : 'bg-gray-50'">
            <p class="text-sm mb-1 transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">Total Pembayaran</p>
            <p class="text-2xl font-bold transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Rp {{ formatPrice(displayTotal) }}</p>
          </div>
          
          <!-- Cash Amount Input -->
          <div class="mb-6">
            <label class="block text-sm font-medium mb-2 transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Nominal Uang Diterima</label>
            <input
              v-model="cashAmount"
              type="number"
              step="100"
              min="0"
              placeholder="Masukkan nominal uang"
              class="w-full px-4 py-3 border rounded-lg text-lg text-center focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-300" :class="isDarkMode ? 'bg-gray-700 border-gray-600 text-white placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500'"
            >
          </div>
          
          <!-- Change Amount Display -->
          <div v-if="cashAmount && isCashValid" class="rounded-lg p-4 mb-6 transition-colors duration-300" :class="isDarkMode ? 'bg-green-900/20 border border-green-700' : 'bg-green-50 border border-green-200'">
            <p class="text-sm mb-1 transition-colors duration-300" :class="isDarkMode ? 'text-green-300' : 'text-green-600'">Kembalian</p>
            <p class="text-xl font-bold transition-colors duration-300" :class="isDarkMode ? 'text-green-400' : 'text-green-700'">Rp {{ formatPrice(changeAmount) }}</p>
          </div>
          
          <!-- Error Message -->
          <div v-if="cashAmount && !isCashValid" class="rounded-lg p-4 mb-6 transition-colors duration-300" :class="isDarkMode ? 'bg-red-900/20 border border-red-700' : 'bg-red-50 border border-red-200'">
            <p class="text-sm transition-colors duration-300" :class="isDarkMode ? 'text-red-300' : 'text-red-600'">Nominal kurang dari total pembayaran</p>
          </div>
          
          <div class="flex space-x-3">
            <button
              @click="cancelCashPayment"
              class="flex-1 px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-300" :class="isDarkMode ? 'text-gray-300 bg-gray-700 hover:bg-gray-600' : 'text-gray-700 bg-gray-100 hover:bg-gray-200'"
            >
              Batal
            </button>
            <button
              @click="confirmCashPayment"
              :disabled="!isCashValid"
              class="flex-1 px-4 py-2 text-sm font-medium text-white rounded-lg transition-colors duration-300 disabled:opacity-50 disabled:cursor-not-allowed" :class="isCashValid ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-400'"
            >
              Konfirmasi
            </button>
          </div>
        </div>
      </div>
    </div>



    <!-- Midtrans Payment Modal -->
    <div v-if="showMidtransModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-lg p-6 max-w-md w-full">
        <div class="text-center">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Pembayaran Midtrans</h3>
          <p class="text-sm text-gray-600 mb-6">Pilih metode pembayaran yang tersedia</p>
          
          <!-- Midtrans Snap Container -->
          <div id="midtrans-snap-container" class="mb-6">
            <div class="bg-gray-50 rounded-lg p-4">
              <div class="flex items-center justify-center space-x-2">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                <span class="text-sm text-gray-600">Memuat halaman pembayaran...</span>
              </div>
            </div>
          </div>
          
          <!-- Order Info -->
          <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <p class="text-sm text-gray-600 mb-1">Order ID</p>
            <p class="font-semibold text-gray-900">{{ midtransOrderId }}</p>
            <p class="text-sm text-gray-600 mt-2 mb-1">Total</p>
            <p class="text-lg font-bold text-gray-900">Rp {{ formatPrice(displayTotal) }}</p>
          </div>
          
          <div class="flex space-x-3">
            <button
              @click="cancelMidtransPayment"
              class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
            >
              Batal
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Success Modal -->
    <div v-if="showSuccessModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-lg p-6 max-w-md w-full">
        <div class="text-center">
          <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
          </div>
          
          <h3 class="text-lg font-semibold text-gray-900 mb-2">Pembayaran Berhasil!</h3>
          <p class="text-sm text-gray-600 mb-6">Pesanan telah diproses dengan sukses</p>
          
          <!-- Success Details -->
          <div v-if="successData" class="bg-gray-50 rounded-lg p-4 mb-6 text-left">
            <div class="space-y-2">
              <div class="flex justify-between">
                <span class="text-sm text-gray-600">Order ID:</span>
                <span class="font-semibold text-gray-900">{{ successData.orderId }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm text-gray-600">Metode:</span>
                <span class="font-semibold text-gray-900 capitalize">{{ successData.paymentMethod }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm text-gray-600">Total:</span>
                <span class="font-semibold text-gray-900">Rp {{ formatPrice(successData.total) }}</span>
              </div>
              <div v-if="successData.changeAmount" class="flex justify-between">
                <span class="text-sm text-gray-600">Kembalian:</span>
                <span class="font-semibold text-gray-900">Rp {{ formatPrice(successData.changeAmount) }}</span>
              </div>
            </div>
          </div>
          
          <div class="flex space-x-3">
            <button
              @click="printReceipt"
              class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
            >
              Cetak Struk
            </button>
            <button
              @click="closeSuccessModal"
              class="flex-1 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors"
            >
              Selesai
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Draft Orders Modal -->
    <div v-if="showDraftOrdersModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-hidden">
        <div class="p-6 border-b border-gray-200">
          <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Draft Pesanan</h3>
            <div class="flex space-x-2">
              <button
                @click="loadDraftOrders"
                class="px-3 py-1 text-sm rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors"
              >
                Refresh
              </button>
              <button
                @click="showDraftOrdersModal = false"
                class="p-2 text-gray-400 hover:text-gray-600 transition-colors"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>
          </div>
        </div>
        
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
          <div v-if="draftOrders.length === 0" class="text-center py-12">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center bg-gray-100">
              <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
              </svg>
            </div>
            <p class="text-gray-500">Tidak ada draft pesanan</p>
          </div>
          
          <div v-else class="space-y-4">
            <div 
              v-for="draft in draftOrders" 
              :key="draft.id"
              class="p-4 rounded-lg border bg-gray-50 border-gray-200"
            >
              <div class="flex items-start justify-between">
                <div class="flex-1">
                  <div class="flex items-center space-x-2 mb-2">
                    <span class="text-sm font-medium text-gray-900">Order #{{ draft.order_number }}</span>
                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                      {{ draft.status }}
                    </span>
                  </div>
                  
                  <div class="text-sm text-gray-600 space-y-1">
                    <p v-if="draft.customer_name"><strong>Customer:</strong> {{ draft.customer_name }}</p>
                    <p v-if="draft.customer_phone"><strong>Phone:</strong> {{ draft.customer_phone }}</p>
                    <p><strong>Items:</strong> {{ draft.order_items?.length || 0 }} produk</p>
                    <p><strong>Total:</strong> Rp {{ formatPrice(draft.total) }}</p>
                    <p><strong>Created:</strong> {{ formatDateTime(draft.created_at) }}</p>
                  </div>
                </div>
                
                <div class="flex space-x-2 ml-4">
                  <button
                    @click="editDraft(draft); showDraftOrdersModal = false"
                    class="px-3 py-1 text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 transition-colors"
                  >
                    Edit
                  </button>
                  <button
                    @click="deleteDraft(draft.id)"
                    class="px-3 py-1 text-xs font-medium rounded text-red-700 bg-red-100 hover:bg-red-200 transition-colors"
                  >
                    Hapus
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import PaymentViewLogic from './PaymentView.js';

export default {
  ...PaymentViewLogic
};
</script>
