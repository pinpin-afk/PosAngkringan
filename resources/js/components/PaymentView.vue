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

              <!-- QRIS Payment -->
              <div
                @click="selectPaymentMethod('qris')"
                class="p-4 rounded-lg border-2 cursor-pointer transition-all duration-200" :class="[
                  paymentMethod === 'qris' 
                    ? (isDarkMode ? 'border-blue-400 bg-blue-900/20' : 'border-blue-500 bg-blue-50')
                    : (isDarkMode ? 'border-gray-600 hover:border-gray-500' : 'border-gray-300 hover:border-gray-400')
                ]"
              >
                <div class="flex items-center space-x-3">
                  <div class="w-12 h-12 rounded-lg flex items-center justify-center" :class="isDarkMode ? 'bg-gray-700' : 'bg-gray-100'">
                    <svg class="w-6 h-6" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                    </svg>
                  </div>
                  <div class="flex-1">
                    <h4 class="font-medium transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">QRIS</h4>
                    <p class="text-sm transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Bayar dengan QRIS (GoPay, OVO, DANA, dll)</p>
                  </div>
                  <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center" :class="[
                    paymentMethod === 'qris' 
                      ? (isDarkMode ? 'border-blue-400 bg-blue-400' : 'border-blue-500 bg-blue-500')
                      : (isDarkMode ? 'border-gray-600' : 'border-gray-300')
                  ]">
                    <div v-if="paymentMethod === 'qris'" class="w-2 h-2 rounded-full bg-white"></div>
                  </div>
                </div>
              </div>

              <!-- Credit Card Payment -->
              <div
                @click="selectPaymentMethod('credit_card')"
                class="p-4 rounded-lg border-2 cursor-pointer transition-all duration-200" :class="[
                  paymentMethod === 'credit_card' 
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
                    <h4 class="font-medium transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Kartu Kredit/Debit</h4>
                    <p class="text-sm transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Bayar dengan kartu kredit atau debit</p>
                  </div>
                  <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center" :class="[
                    paymentMethod === 'credit_card' 
                      ? (isDarkMode ? 'border-blue-400 bg-blue-400' : 'border-blue-500 bg-blue-500')
                      : (isDarkMode ? 'border-gray-600' : 'border-gray-300')
                  ]">
                    <div v-if="paymentMethod === 'credit_card'" class="w-2 h-2 rounded-full bg-white"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Order Summary -->
          <div class="transition-colors duration-300 rounded-lg border p-6" :class="isDarkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'">
            <h3 class="text-lg font-semibold mb-4 transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Ringkasan Pesanan</h3>
            <div class="space-y-3">


              <!-- Member Info -->
              <div v-if="selectedMember" class="mb-3 p-3 rounded-lg" :class="isDarkMode ? 'bg-green-900/20 border border-green-700' : 'bg-green-50 border border-green-200'">
                <div class="flex items-center justify-between">
                  <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <div>
                      <p class="text-sm font-medium text-green-800" :class="isDarkMode ? 'text-green-300' : 'text-green-800'">Member: {{ selectedMember.name }}</p>
                      <p class="text-xs text-green-600" :class="isDarkMode ? 'text-green-400' : 'text-green-600'">ID: {{ selectedMember.member_id }}</p>
                    </div>
                  </div>
                  <button
                    @click="unselectMember"
                    class="text-red-600 hover:text-red-800 transition-colors p-1"
                    title="Hapus Member"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                  </button>
                </div>
              </div>
              
              <div class="flex justify-between text-sm">
                <span class="transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">Subtotal</span>
                <span class="font-medium transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Rp {{ formatPrice(cartSubtotal) }}</span>
              </div>
              <div class="flex justify-between text-sm">
                <span class="transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">Pajak (10%)</span>
                <span class="font-medium transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Rp {{ formatPrice(tax) }}</span>
              </div>
            <div v-if="selectedMember" class="flex justify-between text-sm">
              <span class="transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">Points Earned (2%)</span>
              <span class="font-medium text-yellow-600">+{{ pointsEarned }} Points</span>
            </div>
              <div class="border-t pt-3 transition-colors duration-300" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
                <div class="flex justify-between text-lg font-semibold">
                  <span class="transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Total</span>
                  <span class="text-blue-600">Rp {{ formatPrice(displayTotal) }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Process / Cancel Actions -->
          <div class="flex space-x-3">
            <button
              @click="clearCartAndRedirect"
              class="flex-1 bg-gray-200 text-gray-800 py-4 px-6 rounded-lg font-medium hover:bg-gray-300 transition-colors duration-300"
            >
              Batalkan Pesanan
            </button>
            <button
              @click="processPayment"
              :disabled="!paymentMethod"
              class="flex-1 bg-blue-600 text-white py-4 px-6 rounded-lg font-medium hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors duration-300"
            >
              <span v-if="loading" class="flex items-center justify-center">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memproses Pembayaran...
              </span>
              <span v-else>
                Proses Pembayaran
              </span>
            </button>
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
            <p class="text-2xl font-bold transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Rp {{ formatPrice(total) }}</p>
          </div>

          <!-- Cash Input -->
          <div class="mb-6">
            <label class="block text-sm font-medium mb-2 transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Nominal Tunai</label>
            <input
              v-model="cashAmount"
              type="number"
              placeholder="Masukkan nominal tunai"
              class="w-full px-4 py-3 border rounded-lg text-lg text-center focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-300" :class="isDarkMode ? 'bg-gray-700 border-gray-600 text-white placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500'"
              @input="formatCashInput"
            >
          </div>

          <!-- Change Amount -->
          <div v-if="cashAmount && parseFloat(cashAmount) > 0" class="mb-6">
            <div class="rounded-lg p-4 transition-colors duration-300" :class="isDarkMode ? 'bg-blue-900/20' : 'bg-blue-50'">
              <p class="text-sm mb-1 transition-colors duration-300" :class="isDarkMode ? 'text-blue-300' : 'text-blue-600'">Kembalian</p>
              <p class="text-xl font-bold" :class="changeAmount >= 0 ? 'text-green-600' : 'text-red-600'">
                {{ changeAmount >= 0 ? 'Rp ' + formatPrice(changeAmount) : 'Kurang Rp ' + formatPrice(Math.abs(changeAmount)) }}
              </p>
            </div>
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
              class="flex-1 px-4 py-2 text-sm font-medium text-white rounded-lg transition-colors duration-300"
              :class="isCashValid ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-400 cursor-not-allowed'"
            >
              Konfirmasi
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Success Modal -->
    <div v-if="showSuccessModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="rounded-lg p-6 max-w-md w-full transition-colors duration-300" :class="isDarkMode ? 'bg-gray-800' : 'bg-white'">
        <div class="text-center">
          <!-- Success Icon -->
          <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
          </div>
          
          <h3 class="text-lg font-semibold mb-4 transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Pembayaran Berhasil!</h3>
          
          <!-- Payment Details -->
          <div class="space-y-3 mb-6">
            <div class="text-sm transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">
              <strong>No. Order:</strong> {{ successData?.orderId }}
            </div>
            <div class="text-sm transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">
              <strong>Total:</strong> Rp {{ formatPrice(successData?.total || 0) }}
            </div>
            <div class="text-sm transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">
              <strong>Metode:</strong> {{ successData?.paymentMethod === 'cash' ? 'Tunai' : successData?.paymentMethod === 'qris' ? 'QRIS' : successData?.paymentMethod === 'debit' ? 'Debit' : 'Kredit' }}
            </div>
            
            <!-- Cash Payment Details -->
            <div v-if="successData?.paymentMethod === 'cash'" class="bg-blue-50 rounded-lg p-3 mt-4" :class="isDarkMode ? 'bg-blue-900/20' : 'bg-blue-50'">
              <div class="text-sm space-y-1">
                <div class="flex justify-between">
                  <span class="transition-colors duration-300" :class="isDarkMode ? 'text-blue-300' : 'text-blue-600'">Bayar:</span>
                  <span class="font-medium transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Rp {{ formatPrice(successData?.cashAmount || 0) }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="transition-colors duration-300" :class="isDarkMode ? 'text-blue-300' : 'text-blue-600'">Kembalian:</span>
                  <span class="font-medium text-green-600">Rp {{ formatPrice(successData?.changeAmount || 0) }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Print Status -->
          <div class="mb-6">
            <div class="flex items-center justify-center space-x-2 text-sm transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">
              <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z"></path>
              </svg>
              <span>Struk sedang dicetak...</span>
            </div>
          </div>

          <div class="flex space-x-3">
            <button
              @click="printReceipt"
              class="flex-1 px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-300" :class="isDarkMode ? 'text-gray-300 bg-gray-700 hover:bg-gray-600' : 'text-gray-700 bg-gray-100 hover:bg-gray-200'"
            >
              Print Ulang
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

    <!-- QRIS Modal -->
    <div v-if="showQrisModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-lg p-6 max-w-md w-full">
        <div class="text-center">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Pembayaran QRIS</h3>
          <p class="text-sm text-gray-600 mb-4">Scan QR code dengan aplikasi e-wallet Anda</p>
          
          <!-- QR Code Placeholder -->
          <div class="w-48 h-48 bg-gray-100 rounded-lg mx-auto mb-4 flex items-center justify-center">
            <div class="text-center">
              <div class="w-32 h-32 bg-gray-300 rounded-lg mx-auto mb-2"></div>
              <p class="text-xs text-gray-500">QR Code akan muncul di sini</p>
            </div>
          </div>

          <div class="space-y-2">
            <p class="text-sm font-medium">Total: Rp {{ formatPrice(total) }}</p>
            <p class="text-xs text-gray-500">Order ID: {{ qrisOrderId }}</p>
          </div>

          <div class="flex space-x-3 mt-6">
            <button
              @click="cancelQrisPayment"
              class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
            >
              Batal
            </button>
            <button
              @click="checkPaymentStatus"
              class="flex-1 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors"
            >
              Cek Status
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
  name: 'PaymentView',
  data() {
    return {
      cart: [],
      customerName: '',
      customerPhone: '',
      tableNumber: '',
      selectedMember: null,
      pointsEarned: 0,
      finalTotal: 0,
      paymentMethod: '',
      processedBy: '',
      isDarkMode: false,
      loading: false,
      // Cash payment
      cashAmount: '',
      showCashModal: false,
      // Success modal
      showSuccessModal: false,
      successData: null,
      // QRIS state
      showQrisModal: false,
      qrisOrderId: null,
      qrisQrString: null,
      qrisQrUrl: null,
      qrisPollTimer: null,
      deeplinkUrl: null
    }
  },
  computed: {
    cartSubtotal() {
      return this.cart.reduce((total, item) => total + (item.price * item.quantity), 0);
    },
    tax() {
      return this.cartSubtotal * 0.1;
    },
    total() {
      return this.cartSubtotal + this.tax;
    },
    displayTotal() {
      return this.finalTotal > 0 ? this.finalTotal : this.total;
    },
    changeAmount() {
      const cash = parseFloat(this.cashAmount) || 0;
      const total = this.displayTotal;
      return cash - total;
    },
    isCashValid() {
      const cash = parseFloat(this.cashAmount) || 0;
      return cash >= this.displayTotal;
    },
    pointsEarned() {
      // 2% points for members
      if (this.selectedMember) {
        return Math.floor(this.cartSubtotal * 0.02);
      }
      return 0;
    }
  },
  async mounted() {
    // Load cart data from localStorage or props
    this.loadCartData();
    await this.loadKasirName();
    this.loadDarkModePreference();
    // Create draft order for cash if not yet created
    try {
      if (!this.qrisOrderId && !localStorage.getItem('pos_draft_order_id')) {
        const orderData = {
          customer_name: this.customerName,
          customer_phone: this.customerPhone,
          processed_by: this.processedBy,
          payment_method: 'cash',
          selected_member: this.selectedMember,
          points_earned: this.pointsEarned,
          items: this.cart.map(item => ({ product_id: item.id, quantity: item.quantity })),
          draft: true
        };
        if (orderData.items.length > 0) {
          const res = await axios.post('/api/pos/orders', orderData);
          const draftId = res?.data?.id;
          if (draftId) {
            localStorage.setItem('pos_draft_order_id', String(draftId));
          }
        }
      }
    } catch (_) {}
  },
  methods: {
    loadCartData() {
      // Load cart from localStorage or get from parent component
      const savedCart = localStorage.getItem('pos_cart');
      if (savedCart) {
        this.cart = JSON.parse(savedCart);
      }
      
      // Load customer info if available
      const savedCustomerName = localStorage.getItem('pos_customer_name');
      const savedCustomerPhone = localStorage.getItem('pos_customer_phone');
      const savedTableNumber = localStorage.getItem('pos_table_number');
      const savedSelectedMember = localStorage.getItem('pos_selected_member');
      const savedPointsEarned = localStorage.getItem('pos_points_earned');
      const savedFinalTotal = localStorage.getItem('pos_final_total');
      
      if (savedCustomerName) this.customerName = savedCustomerName;
      if (savedCustomerPhone) this.customerPhone = savedCustomerPhone;
      if (savedTableNumber) this.tableNumber = savedTableNumber;
      if (savedSelectedMember) this.selectedMember = JSON.parse(savedSelectedMember);
      if (savedPointsEarned) this.pointsEarned = parseInt(savedPointsEarned);
      if (savedFinalTotal) this.finalTotal = parseFloat(savedFinalTotal);
    },
    async loadKasirName() {
      try {
        const res = await axios.get('/check-kasir-session');
        const data = res?.data || {};
        const user = data.user || data;
        if (user && (user.name || user.username)) {
          this.processedBy = user.name || user.username;
        }
      } catch (e) {
        // ignore if not logged in as kasir
      }
    },
    loadDarkModePreference() {
      const saved = localStorage.getItem('darkMode');
      this.isDarkMode = saved === 'true';
    },
    toggleDarkMode() {
      this.isDarkMode = !this.isDarkMode;
      localStorage.setItem('darkMode', this.isDarkMode);
    },
    selectPaymentMethod(method) {
      this.paymentMethod = method;
      if (method === 'cash') {
        this.cashAmount = '';
        this.showCashModal = true;
      } else if (method === 'qris') {
        // If a cash draft exists, cancel it before proceeding with QRIS
        const draftId = localStorage.getItem('pos_draft_order_id');
        if (draftId) {
          axios.put(`/api/pos/orders/${draftId}`, { status: 'cancelled' }).catch(() => {});
          localStorage.removeItem('pos_draft_order_id');
        }
      }
    },
    formatPrice(price) {
      return new Intl.NumberFormat('id-ID').format(price);
    },
    goBack() {
      // Go back to POS view
      window.history.back();
    },
    async processPayment() {
      if (!this.paymentMethod) {
        alert('Pilih metode pembayaran terlebih dahulu!');
        return;
      }

      if (this.cart.length === 0) {
        alert('Keranjang kosong!');
        return;
      }

      // For cash payment, validate amount first
      if (this.paymentMethod === 'cash') {
        if (!this.cashAmount || !this.isCashValid) {
          alert('Nominal tunai tidak valid atau kurang dari total pembayaran!');
          return;
        }
      }

      this.loading = true;

      try {
        const orderData = {
          customer_name: this.customerName,
          customer_phone: this.customerPhone,
          processed_by: this.processedBy,
          payment_method: this.paymentMethod,
          selected_member: this.selectedMember,
          points_earned: this.pointsEarned,
          items: this.cart.map(item => ({
            product_id: item.id,
            quantity: item.quantity
          }))
        };

        // Add cash amount for cash payment
        if (this.paymentMethod === 'cash') {
          orderData.cash_amount = parseFloat(this.cashAmount);
          orderData.change_amount = this.changeAmount;
        }

        if (this.paymentMethod === 'qris') {
          const res = await axios.post('/api/pos/orders/charge', { ...orderData, payment_type: 'qris' });
          const data = res.data || {};
          // Generate order ID if not provided by API
          const orderId = data.order_id || 
                         data.id || 
                         data.order?.id ||
                         `QRIS-${Date.now()}`;
          
          this.qrisOrderId = orderId;
          this.qrisQrString = data.qr_string || null;
          this.qrisQrUrl = data.qr_url || null;
          this.deeplinkUrl = data.deeplink_url || null;
          this.showQrisModal = true;
          this.startQrisPolling();
        } else {
          // If draft order exists for cash, complete it; otherwise create new
          const draftId = localStorage.getItem('pos_draft_order_id');
          let response;
          if (this.paymentMethod === 'cash' && draftId) {
            response = await axios.put(`/api/pos/orders/${draftId}`, {
              status: 'completed',
              selected_member: this.selectedMember,
              points_earned: this.pointsEarned
            });
          } else {
            response = await axios.post('/api/pos/orders', orderData);
          }
          // Generate order ID if not provided by API
          const orderId = response.data?.order_id || 
                         response.data?.id || 
                         response.data?.order?.id ||
                         `ORD-${Date.now()}`;
          
          // Show success modal with payment details
          this.successData = {
            orderId: orderId,
            total: this.displayTotal,
            paymentMethod: this.paymentMethod,
            cashAmount: this.paymentMethod === 'cash' ? parseFloat(this.cashAmount) : null,
            changeAmount: this.paymentMethod === 'cash' ? this.changeAmount : null,
            items: this.cart,
            customerName: this.customerName,
            customerPhone: this.customerPhone,
            tableNumber: this.tableNumber,
            selectedMember: this.selectedMember,
            pointsEarned: this.pointsEarned,
            processedBy: this.processedBy,
            timestamp: new Date().toLocaleString('id-ID')
          };
          
          this.showSuccessModal = true;
          
          // Auto print receipt after a short delay
          setTimeout(() => {
            this.printReceipt();
          }, 1000);
        }
      } catch (error) {
        console.error('Error processing payment:', error);
        const apiMsg = error?.response?.data?.detail?.status_message
          || error?.response?.data?.status_message
          || error?.response?.data?.message
          || error?.message
          || 'Terjadi kesalahan saat memproses pembayaran!';
        alert(apiMsg);
      } finally {
        this.loading = false;
      }
    },
    startQrisPolling() {
      this.stopQrisPolling();
      this.qrisPollTimer = setInterval(async () => {
        try {
          const res = await axios.get(`/api/pos/orders/${this.qrisOrderId}/status`);
          if (res.data.status === 'settlement') {
            this.showQrisModal = false;
            this.stopQrisPolling();
            
            // Show success modal with payment details
            this.successData = {
              orderId: this.qrisOrderId,
              total: this.total,
              paymentMethod: this.paymentMethod,
              cashAmount: null,
              changeAmount: null,
              items: this.cart,
              customerName: this.customerName,
              customerPhone: this.customerPhone,
              selectedMember: this.selectedMember,
              pointsEarned: this.pointsEarned,
              processedBy: this.processedBy,
              timestamp: new Date().toLocaleString('id-ID')
            };
            
            this.showSuccessModal = true;
            
            // Auto print receipt after a short delay
            setTimeout(() => {
              this.printReceipt();
            }, 1000);
          }
        } catch (error) {
          console.error('Error checking payment status:', error);
        }
      }, 3000);
    },
    stopQrisPolling() {
      if (this.qrisPollTimer) {
        clearInterval(this.qrisPollTimer);
        this.qrisPollTimer = null;
      }
    },
    async cancelQrisPayment() {
      this.showQrisModal = false;
      this.stopQrisPolling();
      try {
        if (this.qrisOrderId) {
          await axios.put(`/api/pos/orders/${this.qrisOrderId}`, { status: 'cancelled' });
        }
      } catch (_) {}
    },
    async checkPaymentStatus() {
      try {
        const res = await axios.get(`/api/pos/orders/${this.qrisOrderId}/status`);
        if (res.data.status === 'settlement') {
          this.showQrisModal = false;
          this.stopQrisPolling();
          
          // Show success modal with payment details
          this.successData = {
            orderId: this.qrisOrderId,
            total: this.total,
            paymentMethod: this.paymentMethod,
            cashAmount: null,
            changeAmount: null,
            items: this.cart,
            customerName: this.customerName,
            customerPhone: this.customerPhone,
            selectedMember: this.selectedMember,
            pointsEarned: this.pointsEarned,
            processedBy: this.processedBy,
            timestamp: new Date().toLocaleString('id-ID')
          };
          
          this.showSuccessModal = true;
          
          // Auto print receipt after a short delay
          setTimeout(() => {
            this.printReceipt();
          }, 1000);
        } else {
          alert('Pembayaran belum selesai. Silakan coba lagi.');
        }
      } catch (error) {
        console.error('Error checking payment status:', error);
        alert('Gagal mengecek status pembayaran.');
      }
    },
    async clearCartAndRedirect() {
      try {
        const draftId = localStorage.getItem('pos_draft_order_id');
        if (this.qrisOrderId) {
          await axios.put(`/api/pos/orders/${this.qrisOrderId}`, { status: 'cancelled' });
        } else if (draftId) {
          await axios.put(`/api/pos/orders/${draftId}`, { status: 'cancelled' });
        }
      } catch (_) {}
      // Clear cart and customer data
      localStorage.removeItem('pos_cart');
      localStorage.removeItem('pos_customer_name');
      localStorage.removeItem('pos_customer_phone');
      localStorage.removeItem('pos_table_number');
      localStorage.removeItem('pos_selected_member');
      localStorage.removeItem('pos_points_earned');
      localStorage.removeItem('pos_final_total');
      localStorage.removeItem('pos_draft_order_id');
      
      // Redirect to POS
      window.location.href = '/kasir/pos';
    },
    unselectMember() {
      this.selectedMember = null;
      // Update localStorage
      localStorage.setItem('pos_selected_member', JSON.stringify(null));
      // Recalculate totals
      this.pointsEarned = 0;
      this.finalTotal = this.total;
      // Customer info will be hidden automatically when no member selected
    },
    formatCashInput() {
      // Format cash input to remove non-numeric characters except decimal point
      this.cashAmount = this.cashAmount.replace(/[^0-9.]/g, '');
    },
    confirmCashPayment() {
      if (this.isCashValid) {
        this.showCashModal = false;
        this.processPayment();
      }
    },
    cancelCashPayment() {
      this.showCashModal = false;
      this.paymentMethod = '';
      this.cashAmount = '';
    },
    printReceipt() {
      if (!this.successData) return;
      
      // Create a new window for printing
      const printWindow = window.open('', '_blank', 'width=400,height=600');
      
      const receiptContent = this.generateReceiptHTML();
      printWindow.document.write(receiptContent);
      printWindow.document.close();
      
      // Wait for content to load then print
      printWindow.onload = function() {
        printWindow.print();
        printWindow.close();
      };
    },
    generateReceiptHTML() {
      const { orderId, total, paymentMethod, cashAmount, changeAmount, items, customerName, customerPhone, processedBy, timestamp } = this.successData;
      
      return `
        <!DOCTYPE html>
        <html>
        <head>
          <title>Struk POS Angkringan</title>
          <style>
            body { 
              font-family: 'Courier New', monospace; 
              font-size: 12px; 
              line-height: 1.4; 
              margin: 0; 
              padding: 10px;
              max-width: 300px;
            }
            .header { text-align: center; margin-bottom: 15px; }
            .title { font-size: 16px; font-weight: bold; margin-bottom: 5px; }
            .subtitle { font-size: 10px; color: #666; }
            .divider { border-top: 1px dashed #000; margin: 10px 0; }
            .item { display: flex; justify-content: space-between; margin-bottom: 3px; }
            .item-name { flex: 1; }
            .item-qty { margin: 0 10px; }
            .item-price { text-align: right; }
            .total-section { margin-top: 10px; }
            .total-line { display: flex; justify-content: space-between; font-weight: bold; margin-bottom: 3px; }
            .footer { text-align: center; margin-top: 15px; font-size: 10px; }
            .cash-details { background: #f5f5f5; padding: 8px; margin: 10px 0; border-radius: 4px; }
            .cash-line { display: flex; justify-content: space-between; margin-bottom: 2px; }
          </style>
        </head>
        <body>
          <div class="header">
            <div class="title">POS ANGRINGAN</div>
            <div class="subtitle">Jl. Contoh No. 123, Jakarta</div>
            <div class="subtitle">Telp: (021) 1234-5678</div>
          </div>
          
          <div class="divider"></div>
          
          <div>
            <div><strong>No. Order:</strong> ${orderId}</div>
            <div><strong>Tanggal:</strong> ${timestamp}</div>
            <div><strong>Kasir:</strong> ${processedBy}</div>
            ${customerName ? `<div><strong>Pelanggan:</strong> ${customerName}</div>` : ''}
            ${customerPhone ? `<div><strong>Telp:</strong> ${customerPhone}</div>` : ''}
          </div>
          
          <div class="divider"></div>
          
          <div>
            ${items.map(item => `
              <div class="item">
                <div class="item-name">${item.name}</div>
                <div class="item-qty">${item.quantity}x</div>
                <div class="item-price">Rp ${this.formatPrice(item.price * item.quantity)}</div>
              </div>
            `).join('')}
          </div>
          
          <div class="divider"></div>
          
          <div class="total-section">
            <div class="total-line">
              <span>Subtotal:</span>
              <span>Rp ${this.formatPrice(this.cartSubtotal)}</span>
            </div>
            <div class="total-line">
              <span>Pajak (10%):</span>
              <span>Rp ${this.formatPrice(this.tax)}</span>
            </div>
            <div class="total-line">
              <span>TOTAL:</span>
              <span>Rp ${this.formatPrice(total)}</span>
            </div>
          </div>
          
          ${paymentMethod === 'cash' ? `
            <div class="cash-details">
              <div class="cash-line">
                <span>Metode:</span>
                <span>Tunai</span>
              </div>
              <div class="cash-line">
                <span>Bayar:</span>
                <span>Rp ${this.formatPrice(cashAmount)}</span>
              </div>
              <div class="cash-line">
                <span>Kembalian:</span>
                <span>Rp ${this.formatPrice(changeAmount)}</span>
              </div>
            </div>
          ` : `
            <div class="cash-details">
              <div class="cash-line">
                <span>Metode:</span>
                <span>${paymentMethod === 'qris' ? 'QRIS' : paymentMethod === 'debit' ? 'Debit' : 'Kredit'}</span>
              </div>
            </div>
          `}
          
          <div class="divider"></div>
          
          <div class="footer">
            <div>Terima kasih atas kunjungan Anda!</div>
            <div>Struk ini adalah bukti pembayaran yang sah</div>
          </div>
        </body>
        </html>
      `;
    },
    closeSuccessModal() {
      this.showSuccessModal = false;
      this.successData = null;
      this.clearCartAndRedirect();
    }
  },
  beforeUnmount() {
    this.stopQrisPolling();
  }
}
</script>
