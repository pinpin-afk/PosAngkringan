<template>
  <div class="min-h-screen transition-colors duration-300" :class="isDarkMode ? 'bg-gray-900' : 'bg-gray-50'">
    <!-- Header -->
    <header class="transition-colors duration-300 shadow-sm border-b" :class="isDarkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'">
      <div class="px-6 py-4">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-2">
            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
              <img 
                src="/assets/img/WhatsApp Image 2025-09-07 at 17.12.58.jpeg" 
                alt="Logo" 
                class="w-8 h-8 rounded object-cover"
              >
            </div>
            <div>
              <h1 class="text-xl font-semibold transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">POS Angkringan</h1>
              <p class="text-sm transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-500'">Kasir: {{ processedBy || 'Tidak diketahui' }}</p>
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
            <button
              @click="showTopSheet = true"
              class="p-2 rounded-lg transition-colors duration-300"
              :class="isDarkMode ? 'hover:bg-gray-700 text-gray-200' : 'hover:bg-gray-100 text-gray-700'"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <div class="flex h-[calc(100vh-64px)]">
      <!-- Left Sidebar - Categories & Search -->
      <div class="w-48 md:w-52 lg:w-64 xl:w-72 transition-colors duration-300 border-r flex flex-col" :class="isDarkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'">
        <!-- Search Bar -->
        <div class="p-4 border-b transition-colors duration-300" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
          <div class="relative">
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Cari produk..."
              class="w-full pl-10 pr-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-300" :class="isDarkMode ? 'bg-gray-700 border-gray-600 text-white placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500'"
            >
            <svg class="absolute left-3 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
          </div>
        </div>

        <!-- Categories -->
        <div class="flex-1 overflow-y-auto">
          <div class="p-4">
            <h3 class="text-sm font-medium transition-colors duration-300 mb-3" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Kategori</h3>
            <div class="space-y-1">
              <button
                @click="selectedCategory = ''"
                :class="[
                  'w-full text-left px-3 py-2 rounded-lg text-sm transition-colors',
                  selectedCategory === '' 
                    ? (isDarkMode ? 'bg-blue-600 text-white font-medium' : 'bg-blue-100 text-blue-700 font-medium')
                    : (isDarkMode ? 'text-gray-300 hover:bg-gray-700' : 'text-gray-700 hover:bg-gray-100')
                ]"
              >
                Semua Produk
              </button>
              <button
                v-for="category in categories"
                :key="category.id"
                @click="selectedCategory = category.id"
                :class="[
                  'w-full text-left px-3 py-2 rounded-lg text-sm transition-colors',
                  selectedCategory == category.id 
                    ? (isDarkMode ? 'bg-blue-600 text-white font-medium' : 'bg-blue-100 text-blue-700 font-medium')
                    : (isDarkMode ? 'text-gray-300 hover:bg-gray-700' : 'text-gray-700 hover:bg-gray-100')
                ]"
              >
                {{ category.name }}
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Center - Products Grid -->
      <div class="flex-1 overflow-y-auto transition-colors duration-300" :class="isDarkMode ? 'bg-gray-900' : 'bg-gray-50'">
        <div class="p-3 md:p-4">
          <div class="mb-6">
            <h2 class="text-lg font-semibold transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
              {{ selectedCategory ? categories.find(c => c.id == selectedCategory)?.name : 'Semua Produk' }}
            </h2>
            <p class="text-sm transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">{{ filteredProducts.length }} produk tersedia</p>
          </div>

          <!-- Products Grid -->
          <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-3 md:gap-3.5">
            <div
              v-for="product in filteredProducts"
              :key="product.id"
              @click="addToCart(product)"
              :class="[
                'rounded-lg border p-4 cursor-pointer transition-all duration-200 hover:shadow-md',
                isDarkMode ? 'bg-gray-800 border-gray-700 hover:border-blue-400' : 'bg-white border-gray-200 hover:border-blue-300',
                product.stock <= 0 ? 'opacity-50 cursor-not-allowed' : 'hover:scale-105'
              ]"
            >
              <!-- Product Image -->
              <div class="w-full h-24 md:h-20 rounded-lg mb-2 md:mb-2.5 flex items-center justify-center transition-colors duration-300 overflow-hidden" :class="isDarkMode ? 'bg-gray-700' : 'bg-gray-100'">
                <img 
                  v-if="product.image_url" 
                  :src="product.image_url" 
                  :alt="product.name"
                  class="w-full h-full object-cover rounded-lg"
                >
                <svg v-else class="w-8 h-8 md:w-7 md:h-7 transition-colors duration-300" :class="isDarkMode ? 'text-gray-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
              </div>

              <!-- Product Info -->
              <div class="space-y-1">
                <h3 class="font-medium text-sm md:text-[0.9rem] line-clamp-2 transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ product.name }}</h3>
                <p class="text-xs md:text-[0.8rem] line-clamp-1 transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">{{ product.description }}</p>
                <div class="flex items-center justify-between">
                  <span class="text-sm md:text-base font-semibold text-blue-600">Rp {{ formatPrice(product.price) }}</span>
                  <span class="text-xs transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Stok: {{ product.stock }}</span>
                </div>
              </div>

              <!-- Add Button -->
              <button
                v-if="product.stock > 0"
                class="w-full mt-2 bg-blue-600 text-white py-1.5 md:py-2 px-3 rounded-lg text-sm md:text-[0.95rem] font-medium hover:bg-blue-700 transition-colors"
                @click.stop="addToCart(product)"
              >
                + Tambah
              </button>
              <div
                v-else
                class="w-full mt-3 py-2 px-3 rounded-lg text-sm font-medium text-center transition-colors duration-300" :class="isDarkMode ? 'bg-gray-600 text-gray-400' : 'bg-gray-300 text-gray-500'"
              >
                Stok Habis
              </div>
            </div>
          </div>

          <!-- Empty State -->
          <div v-if="filteredProducts.length === 0" class="text-center py-12">
            <svg class="w-16 h-16 mx-auto mb-4 transition-colors duration-300" :class="isDarkMode ? 'text-gray-600' : 'text-gray-300'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 009.586 13H7"></path>
            </svg>
            <h3 class="text-lg font-medium mb-2 transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Tidak ada produk</h3>
            <p class="transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Coba ubah kategori atau kata kunci pencarian</p>
          </div>
        </div>
      </div>

      <!-- Right Panel - Cart & Payment -->
      <div class="w-80 md:w-80 lg:w-96 transition-colors duration-300 border-l flex flex-col" :class="isDarkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'">
        <!-- Cart Header -->
        <div class="p-4 border-b transition-colors duration-300" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
          <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Keranjang</h3>
            <button
              v-if="cart.length > 0"
              @click="clearCart"
              class="text-sm font-medium transition-colors duration-300" :class="isDarkMode ? 'text-red-400 hover:text-red-300' : 'text-red-600 hover:text-red-700'"
            >
              Hapus Semua
            </button>
          </div>
        </div>

        <!-- Cart Items -->
        <div class="flex-1 overflow-y-auto">
          <div v-if="cart.length === 0" class="p-4 text-center">
            <svg class="w-12 h-12 mx-auto mb-3 transition-colors duration-300" :class="isDarkMode ? 'text-gray-600' : 'text-gray-300'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
            </svg>
            <p class="text-sm transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Keranjang kosong</p>
          </div>

          <div v-else class="p-4 space-y-3">
            <div
              v-for="(item, index) in cart"
              :key="index"
              class="flex items-center space-x-3 p-3 rounded-lg transition-colors duration-300" :class="isDarkMode ? 'bg-gray-700' : 'bg-gray-50'"
            >
              <!-- Product Info -->
              <div class="flex-1 min-w-0">
                <h4 class="font-medium text-sm truncate transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ item.name }}</h4>
                <p class="text-xs transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Rp {{ formatPrice(item.price) }}</p>
              </div>

              <!-- Quantity Controls -->
              <div class="flex items-center space-x-2">
                <button
                  @click="updateQuantity(index, item.quantity - 1)"
                  class="w-6 h-6 rounded-full flex items-center justify-center transition-colors duration-300" :class="isDarkMode ? 'bg-gray-600 hover:bg-gray-500 text-gray-300' : 'bg-gray-200 hover:bg-gray-300 text-gray-700'"
                >
                  <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                  </svg>
                </button>
                <span class="w-8 text-center text-sm font-medium transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ item.quantity }}</span>
                <button
                  @click="updateQuantity(index, item.quantity + 1)"
                  class="w-6 h-6 rounded-full flex items-center justify-center transition-colors duration-300" :class="isDarkMode ? 'bg-gray-600 hover:bg-gray-500 text-gray-300' : 'bg-gray-200 hover:bg-gray-300 text-gray-700'"
                >
                  <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                  </svg>
                </button>
              </div>

              <!-- Remove Button -->
              <button
                @click="removeFromCart(index)"
                class="p-1 transition-colors duration-300" :class="isDarkMode ? 'text-red-400 hover:text-red-300' : 'text-red-500 hover:text-red-700'"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
              </button>
            </div>
          </div>
        </div>

        <!-- Customer Information - Only show if member selected -->
        <div v-if="selectedMember" class="p-4 border-t" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
          <h3 class="text-lg font-semibold mb-3 transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Informasi Pelanggan</h3>
          <div class="space-y-3">
            <div>
              <label class="block text-sm font-medium mb-1 transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Nama Pelanggan</label>
              <input
                v-model="customerName"
                type="text"
                placeholder="Masukkan nama pelanggan"
                class="w-full px-3 py-2 border rounded-lg text-sm transition-colors duration-300" :class="isDarkMode ? 'bg-gray-700 border-gray-600 text-white placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500'"
              >
            </div>
            <div>
              <label class="block text-sm font-medium mb-1 transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">No. Telepon</label>
              <input
                v-model="customerPhone"
                type="tel"
                placeholder="Masukkan nomor telepon"
                class="w-full px-3 py-2 border rounded-lg text-sm transition-colors duration-300" :class="isDarkMode ? 'bg-gray-700 border-gray-600 text-white placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500'"
              >
            </div>
          </div>
        </div>

        <!-- Table Number - Always show -->
        <div class="p-4 border-t" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
          <h3 class="text-lg font-semibold mb-3 transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Nomor Meja</h3>
          <div>
            <label class="block text-sm font-medium mb-1 transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">No. Meja (Opsional)</label>
            <input
              v-model="tableNumber"
              type="text"
              placeholder="Masukkan nomor meja"
              class="w-full px-3 py-2 border rounded-lg text-sm transition-colors duration-300" :class="isDarkMode ? 'bg-gray-700 border-gray-600 text-white placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500'"
            >
          </div>
        </div>

        <!-- Cart Summary -->
        <div v-if="cart.length > 0" class="p-4 border-t space-y-4 transition-colors duration-300" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">

          <!-- Order Summary -->
          <div class="space-y-2">
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
                    <p class="text-xs font-medium text-yellow-600" :class="isDarkMode ? 'text-yellow-400' : 'text-yellow-600'">
                      💎 {{ selectedMember.points || 0 }} Points
                    </p>
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
            <div class="border-t pt-2 transition-colors duration-300" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
              <div class="flex justify-between text-lg font-semibold">
                <span class="transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Total</span>
                <span class="text-blue-600">Rp {{ formatPrice(finalTotal) }}</span>
              </div>
            </div>
          </div>


          <!-- Process Order Button -->
          <button
            @click="goToPayment"
            class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-700 transition-colors"
          >
            Bayar Sekarang
          </button>
        </div>
      </div>
    </div>

    <!-- QRIS Modal -->
    <div v-if="showQrisModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
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

    
    <!-- Reusable Member CRUD Modal -->
    <PosMemberManagement
      v-if="showMemberCrudModal"
      :enable-select="true"
      @select="handleMemberSelected"
      @close="handleMemberCrudClosed"
    />

    <!-- Top Sheet Compact Menu for Web -->
    <transition name="fade">
      <div v-if="showTopSheet" class="fixed inset-0 z-50" @click.self="showTopSheet = false">
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="absolute top-0 left-0 right-0 mx-auto w-full max-w-xl p-4 shadow-2xl rounded-b-2xl"
             :class="isDarkMode ? 'bg-gray-900 text-white' : 'bg-white text-gray-900'">
          <div class="flex items-center justify-between mb-3">
            <div class="font-semibold">Menu</div>
            <button @click="showTopSheet = false" class="p-2 rounded" :class="isDarkMode ? 'hover:bg-gray-800' : 'hover:bg-gray-100'">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          <div class="grid grid-cols-6 gap-2">
            <button @click="goToReport(); showTopSheet = false" class="flex flex-col items-center justify-center px-3 py-3 rounded-lg"
                    :class="isDarkMode ? 'bg-gray-800 hover:bg-gray-700' : 'bg-gray-100 hover:bg-gray-200'">
              <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-6m3 6v-4M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H9l-4 4v10a2 2 0 002 2z" />
              </svg>
              <span class="text-xs">Laporan</span>
            </button>
            <button @click="openMemberCrudModal(); showTopSheet = false" class="flex flex-col items-center justify-center px-3 py-3 rounded-lg"
                    :class="isDarkMode ? 'bg-gray-800 hover:bg-gray-700' : 'bg-gray-100 hover:bg-gray-200'">
              <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
              </svg>
              <span class="text-xs">Member</span>
            </button>
            <button @click="showDraftOrdersModal = true; loadDraftOrders(); showTopSheet = false" class="flex flex-col items-center justify-center px-3 py-3 rounded-lg"
                    :class="isDarkMode ? 'bg-gray-800 hover:bg-gray-700' : 'bg-gray-100 hover:bg-gray-200'">
              <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
              </svg>
              <span class="text-xs">Draft</span>
            </button>
            <button @click="logout(); showTopSheet = false" class="flex flex-col items-center justify-center px-3 py-3 rounded-lg"
                    :class="isDarkMode ? 'bg-gray-800 hover:bg-gray-700' : 'bg-gray-100 hover:bg-gray-200'">
              <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
              </svg>
              <span class="text-xs">Logout</span>
            </button>
          </div>
        </div>
      </div>
    </transition>

    <!-- Draft Orders Modal -->
    <div v-if="showDraftOrdersModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-hidden">
        <div class="p-6 border-b border-gray-200">
          <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Draft Orders</h3>
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
            <p class="text-gray-500">Tidak ada draft orders</p>
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
import PosAppISellerLogic from './PosAppISeller.js';
import Breadcrumb from '../../Breadcrumb.vue';
import PosMemberManagement from '../../PosMemberManagement.vue';

export default {
  name: 'PosAppISeller',
  components: {
    Breadcrumb,
    PosMemberManagement
  },
  ...PosAppISellerLogic
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}

.line-clamp-1 {
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Custom scrollbar */
.overflow-y-auto::-webkit-scrollbar {
  width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
  background: transparent;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
  background: #cbd5e0;
  border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
  background: #a0aec0;
}

/* Dark mode scrollbar */
.dark .overflow-y-auto::-webkit-scrollbar-thumb {
  background: #4a5568;
}

.dark .overflow-y-auto::-webkit-scrollbar-thumb:hover {
  background: #2d3748;
}

/* Ensure proper height calculation */
.h-\[calc\(100vh-64px\)\] {
  height: calc(100vh - 64px);
}

/* Modal backdrop blur effect */
.backdrop-blur {
  backdrop-filter: blur(4px);
}

/* Smooth transitions for all interactive elements */
button, input, select {
  transition: all 0.2s ease-in-out;
}

/* Focus states */
button:focus, input:focus, select:focus {
  outline: 2px solid #3b82f6;
  outline-offset: 2px;
}

/* Hover effects for cards */
.hover\:scale-105:hover {
  transform: scale(1.05);
}

/* Ensure proper spacing for mobile */
@media (max-width: 768px) {
  .grid-cols-2 {
    gap: 0.75rem;
  }
}

/* Custom animations */
@keyframes slideIn {
  from {
    transform: translateY(-10px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

.animate-slide-in {
  animation: slideIn 0.3s ease-out;
}

/* Ensure proper z-index stacking */
.z-50 {
  z-index: 50;
}

/* Custom button styles */
button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

/* Ensure proper text truncation */
.truncate {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

/* Custom modal styles */
.modal-backdrop {
  background-color: rgba(0, 0, 0, 0.5);
}

/* Ensure proper overflow handling */
.overflow-hidden {
  overflow: hidden;
}
</style>
