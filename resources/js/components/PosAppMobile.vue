<template>
  <div class="min-h-screen transition-colors duration-300" :class="isDarkMode ? 'bg-gray-900' : 'bg-gray-50'">
    <!-- Mobile Header -->
    <header class="transition-colors duration-300 shadow-sm border-b sticky top-0 z-40" :class="isDarkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'">
      <div class="px-4 py-3">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
              <img 
                src="/assets/img/WhatsApp Image 2025-09-07 at 17.12.58.jpeg" 
                alt="Logo" 
                class="w-6 h-6 rounded object-cover"
              >
            </div>
            <div>
              <h1 class="text-lg font-semibold transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Lesehan 3 Point</h1>
              <!-- <p class="text-xs transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-500'">{{ processedBy || 'Kasir' }}</p> -->
            </div>
          </div>
          <div class="flex items-center space-x-2">
            <!-- Sidebar Toggle (Hamburger) -->
            <button
              @click="showSidebar = true"
              class="p-2 rounded-lg transition-colors duration-300"
              :class="isDarkMode ? 'hover:bg-gray-700 text-gray-200' : 'hover:bg-gray-100 text-gray-700'"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
              </svg>
            </button>
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

    <!-- Mobile Navigation Tabs -->
    <div class="sticky top-16 z-30 border-b transition-colors duration-300" :class="isDarkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'">
      <div class="flex">
        <button
          @click="activeTab = 'products'"
          :class="[
            'flex-1 px-4 py-3 text-sm font-medium text-center border-b-2 transition-colors',
            activeTab === 'products' 
              ? (isDarkMode ? 'border-blue-400 text-blue-400' : 'border-blue-500 text-blue-600')
              : (isDarkMode ? 'border-transparent text-gray-400 hover:text-gray-300' : 'border-transparent text-gray-500 hover:text-gray-700')
          ]"
        >
          Produk
        </button>
        <button
          @click="activeTab = 'cart'"
          :class="[
            'flex-1 px-4 py-3 text-sm font-medium text-center border-b-2 transition-colors relative',
            activeTab === 'cart' 
              ? (isDarkMode ? 'border-blue-400 text-blue-400' : 'border-blue-500 text-blue-600')
              : (isDarkMode ? 'border-transparent text-gray-400 hover:text-gray-300' : 'border-transparent text-gray-500 hover:text-gray-700')
          ]"
        >
          Keranjang
          <span v-if="cart.length > 0" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
            {{ cart.length }}
          </span>
        </button>
      </div>
    </div>

    <!-- Mobile Content -->
    <div class="pb-20">
      <!-- Products Tab -->
      <div v-if="activeTab === 'products'" class="p-4">
        <!-- Search Bar -->
        <div class="mb-4">
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

        <!-- Category Filter -->
        <div class="mb-4">
          <div class="flex space-x-2 overflow-x-auto pb-2">
            <button
              @click="selectedCategory = ''"
              :class="[
                'px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-colors',
                selectedCategory === '' 
                  ? (isDarkMode ? 'bg-blue-600 text-white' : 'bg-blue-100 text-blue-700')
                  : (isDarkMode ? 'bg-gray-700 text-gray-300 hover:bg-gray-600' : 'bg-gray-100 text-gray-700 hover:bg-gray-200')
              ]"
            >
              Semua
            </button>
            <button
              v-for="category in categories"
              :key="category.id"
              @click="selectedCategory = category.id"
              :class="[
                'px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-colors',
                selectedCategory == category.id 
                  ? (isDarkMode ? 'bg-blue-600 text-white' : 'bg-blue-100 text-blue-700')
                  : (isDarkMode ? 'bg-gray-700 text-gray-300 hover:bg-gray-600' : 'bg-gray-100 text-gray-700 hover:bg-gray-200')
              ]"
            >
              {{ category.name }}
            </button>
          </div>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-2 gap-3">
            <div
              v-for="product in filteredProducts"
              :key="product.id"
              @click="addToCart(product)"
              :class="[
                'rounded-lg border p-3 cursor-pointer transition-all duration-200 hover:shadow-md',
                isDarkMode ? 'bg-gray-800 border-gray-700 hover:border-blue-400' : 'bg-white border-gray-200 hover:border-blue-300',
                product.stock <= 0 ? 'opacity-50 cursor-not-allowed' : 'hover:scale-105'
              ]"
            >
            <!-- Product Image -->
            <div class="w-full h-20 rounded-lg mb-2 flex items-center justify-center transition-colors duration-300 overflow-hidden" :class="isDarkMode ? 'bg-gray-700' : 'bg-gray-100'">
              <img 
                v-if="product.image_url" 
                :src="product.image_url" 
                :alt="product.name"
                class="w-full h-full object-cover rounded-lg"
              >
              <svg v-else class="w-6 h-6 transition-colors duration-300" :class="isDarkMode ? 'text-gray-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
            </div>

            <!-- Product Info -->
            <div class="space-y-1">
              <h3 class="font-medium text-sm line-clamp-2 transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ product.name }}</h3>
              <p class="text-xs line-clamp-1 transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">{{ product.description }}</p>
              <div class="flex items-center justify-between">
                <span class="text-sm font-semibold text-blue-600">Rp {{ formatPrice(product.price) }}</span>
                <span class="text-xs transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">{{ product.stock }}</span>
              </div>
            </div>

            <!-- Add Button -->
            <button
              v-if="product.stock > 0"
              class="w-full mt-2 bg-blue-600 text-white py-1.5 px-2 rounded-lg text-xs font-medium hover:bg-blue-700 transition-colors"
              @click.stop="addToCart(product)"
            >
              + Tambah
            </button>
            <div
              v-else
              class="w-full mt-2 py-1.5 px-2 rounded-lg text-xs font-medium text-center transition-colors duration-300" :class="isDarkMode ? 'bg-gray-600 text-gray-400' : 'bg-gray-300 text-gray-500'"
            >
              Habis
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-if="filteredProducts.length === 0" class="text-center py-12">
          <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 009.586 13H7"></path>
          </svg>
          <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada produk</h3>
          <p class="text-gray-500">Coba ubah kategori atau kata kunci pencarian</p>
        </div>
      </div>

      <!-- Cart Tab -->
      <div v-if="activeTab === 'cart'" class="p-4">
        <!-- Cart Items -->
        <div v-if="cart.length === 0" class="text-center py-12">
          <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
          </svg>
          <h3 class="text-lg font-medium text-gray-900 mb-2">Keranjang kosong</h3>
          <p class="text-gray-500">Pilih produk untuk ditambahkan ke keranjang</p>
        </div>

        <div v-else class="space-y-3">
          <div
            v-for="(item, index) in cart"
            :key="index"
            class="bg-white rounded-lg border border-gray-200 p-4"
          >
            <div class="flex items-center space-x-3">
              <!-- Product Info -->
              <div class="flex-1 min-w-0">
                <h4 class="font-medium text-gray-900 text-sm">{{ item.name }}</h4>
                <p class="text-xs text-gray-500">Rp {{ formatPrice(item.price) }}</p>
              </div>

              <!-- Quantity Controls -->
              <div class="flex items-center space-x-3">
                <button
                  @click="updateQuantity(index, item.quantity - 1)"
                  class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center hover:bg-gray-300 transition-colors"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                  </svg>
                </button>
                <span class="w-8 text-center text-sm font-medium">{{ item.quantity }}</span>
                <button
                  @click="updateQuantity(index, item.quantity + 1)"
                  class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center hover:bg-gray-300 transition-colors"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                  </svg>
                </button>
              </div>

              <!-- Remove Button -->
              <button
                @click="removeFromCart(index)"
                class="text-red-500 hover:text-red-700 p-2"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Sidebar Drawer -->
    <transition name="fade">
      <div v-if="showSidebar" class="fixed inset-0 z-50" @click.self="showSidebar = false">
        <div class="absolute inset-0 bg-black/40"></div>
        <!-- Compact Top Sheet (quarter screen) -->
        <aside class="absolute top-0 left-0 right-0 mx-auto w-full max-w-md p-4 shadow-2xl rounded-b-2xl"
             :class="isDarkMode ? 'bg-gray-900 text-white' : 'bg-white text-gray-900'">
          <!-- Sheet Header -->
          <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-lg overflow-hidden bg-blue-600 flex items-center justify-center">
                <img src="/assets/img/WhatsApp Image 2025-09-07 at 17.12.58.jpeg" alt="Logo" class="w-7 h-7 object-cover" />
              </div>
              <div>
                <div class="text-sm text-gray-500" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Kasir</div>
                <div class="font-semibold -mt-0.5">{{ processedBy || '—' }}</div>
              </div>
            </div>
            <button @click="showSidebar = false" class="p-2 rounded"
                    :class="isDarkMode ? 'hover:bg-gray-800' : 'hover:bg-gray-100'">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          <!-- Compact Grid Actions -->
          <div class="grid grid-cols-3 gap-2">
            <button @click="goToReport(); showSidebar = false" class="flex flex-col items-center justify-center px-3 py-3 rounded-lg"
                    :class="isDarkMode ? 'bg-gray-800 hover:bg-gray-700' : 'bg-gray-100 hover:bg-gray-200'">
              <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-6m3 6v-4M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H9l-4 4v10a2 2 0 002 2z" />
              </svg>
              <span class="text-xs">Laporan</span>
            </button>
            <button @click="showMemberManagement = true; showSidebar = false" class="flex flex-col items-center justify-center px-3 py-3 rounded-lg"
                    :class="isDarkMode ? 'bg-gray-800 hover:bg-gray-700' : 'bg-gray-100 hover:bg-gray-200'">
              <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
              </svg>
              <span class="text-xs">Member</span>
            </button>
            <button @click="logout(); showSidebar = false" class="flex flex-col items-center justify-center px-3 py-3 rounded-lg"
                    :class="isDarkMode ? 'bg-gray-800 hover:bg-gray-700' : 'bg-gray-100 hover:bg-gray-200'">
              <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
              </svg>
              <span class="text-xs">Logout</span>
            </button>
          </div>
        </aside>
      </div>
    </transition>

    <!-- Mobile Member & Table Input -->
    <div v-if="cart.length > 0" class="fixed bottom-0 left-0 right-0 border-t p-4 z-40 transition-colors duration-300" :class="isDarkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'">
      <!-- Table Number -->
      <!-- <div class="mb-3">
        <label class="block text-sm font-medium mb-1 transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">No. Meja (Opsional)</label>
        <input
          v-model="tableNumber"
          type="text"
          placeholder="Masukkan nomor meja"
          class="w-full px-3 py-2 border rounded-lg text-sm transition-colors duration-300" :class="isDarkMode ? 'bg-gray-700 border-gray-600 text-white placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500'"
        >
      </div> -->


      <!-- Customer Information - Only show if member selected -->
      <div v-if="selectedMember" class="mb-3">
        <div class="grid grid-cols-2 gap-2">
          <input
            v-model="customerName"
            type="text"
            placeholder="Nama Pelanggan"
            class="px-3 py-2 border rounded-lg text-sm transition-colors duration-300" :class="isDarkMode ? 'bg-gray-700 border-gray-600 text-white placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500'"
          >
          <input
            v-model="customerPhone"
            type="tel"
            placeholder="No. Telepon"
            class="px-3 py-2 border rounded-lg text-sm transition-colors duration-300" :class="isDarkMode ? 'bg-gray-700 border-gray-600 text-white placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500'"
          >
        </div>
      </div>
    </div>

    <!-- Mobile Bottom Cart Summary -->
    <div v-if="cart.length > 0" class="fixed bottom-0 left-0 right-0 border-t p-4 z-50 transition-colors duration-300" :class="isDarkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'">

      <!-- Order Summary -->
      <div class="mb-4 space-y-2">
        <!-- Member Info -->
        <div v-if="selectedMember" class="mb-3 p-2 rounded-lg" :class="isDarkMode ? 'bg-green-900/20 border border-green-700' : 'bg-green-50 border border-green-200'">
          <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                  <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                  </svg>
                  <div>
                    <p class="text-xs font-medium text-green-800" :class="isDarkMode ? 'text-green-300' : 'text-green-800'">Member: {{ selectedMember.name }}</p>
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
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

    <!-- QRIS Modal -->
    <div v-if="showQrisModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-lg p-6 max-w-sm w-full">
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

    <!-- Member Management Modal -->
    <div v-if="showMemberManagement" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-hidden" :class="isDarkMode ? 'bg-gray-800' : 'bg-white'">
        <!-- Header with Breadcrumb -->
        <div class="p-6 border-b" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
          <div class="flex justify-between items-start">
            <div class="flex-1">
              <Breadcrumb 
                :items="memberBreadcrumbs" 
                :is-dark-mode="isDarkMode"
                @navigate="handleBreadcrumbNavigate"
              />
              <h2 class="text-xl font-bold mt-2" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                Kelola Member
              </h2>
              <p class="text-sm mt-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">
                Tambah, edit, atau hapus data member
              </p>
            </div>
            <button 
              @click="showMemberManagement = false" 
              class="text-gray-400 hover:text-gray-600 transition-colors ml-4"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
        </div>
        
        <!-- Content -->
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
          <div class="space-y-6">
            <!-- Search and Add Button -->
            <div class="flex flex-col md:flex-row gap-4">
              <div class="flex-1">
                <input
                  v-model="memberSearchQuery"
                  type="text"
                  placeholder="Cari member berdasarkan nama atau telepon..."
                  class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  :class="isDarkMode ? 'bg-gray-700 border-gray-600 text-white placeholder-gray-400' : 'bg-white border-gray-300'"
                >
              </div>
              <button
                @click="showAddMemberModal = true"
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
              <div v-if="memberLoading" class="text-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                <p class="mt-2" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">Memuat data member...</p>
              </div>
              
              <div v-else-if="filteredMembers.length === 0" class="text-center py-8" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                {{ memberSearchQuery ? 'Tidak ada member yang ditemukan' : 'Belum ada member terdaftar' }}
              </div>
              
              <div v-else class="space-y-2">
                <div
                  v-for="member in filteredMembers"
                  :key="member.id"
                  class="flex items-center justify-between p-4 rounded-lg hover:opacity-90 transition-opacity"
                  :class="isDarkMode ? 'bg-gray-700 hover:bg-gray-600' : 'bg-gray-50 hover:bg-gray-100'"
                >
                  <div class="flex-1">
                    <div class="flex items-center space-x-3">
                      <div>
                        <h3 class="font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ member.name }}</h3>
                        <p class="text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">{{ member.phone }}</p>
                        <p v-if="member.email" class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">{{ member.email }}</p>
                        <p class="text-xs font-medium text-yellow-600" :class="isDarkMode ? 'text-yellow-400' : 'text-yellow-600'">
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
                      @click="selectMember(member)"
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
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import Breadcrumb from './Breadcrumb.vue';

export default {
  name: 'PosAppMobile',
  components: {
    Breadcrumb
  },
  data() {
    return {
      activeTab: 'products',
      showSidebar: false,
      products: [],
      categories: [],
      selectedCategory: '',
      searchQuery: '',
      cart: [],
      customerName: '',
      customerPhone: '',
      tableNumber: '',
      processedBy: '',
      paymentMethod: 'cash',
      user: window.user || null,
      isDarkMode: false,
      // QRIS state
      showQrisModal: false,
      qrisOrderId: null,
      qrisQrString: null,
      qrisQrUrl: null,
      qrisPollTimer: null,
      deeplinkUrl: null,
      // Member management
      showMemberManagement: false,
      memberSearchQuery: '',
      memberLoading: false,
      members: [],
      showAddMemberModal: false,
      selectedMember: null
    }
  },
  computed: {
    filteredProducts() {
      let filtered = this.products;
      
      // Filter by category
      if (this.selectedCategory) {
        filtered = filtered.filter(product => product.category_id == this.selectedCategory);
      }
      
      // Filter by search query
      if (this.searchQuery) {
        const query = this.searchQuery.toLowerCase();
        filtered = filtered.filter(product => 
          product.name.toLowerCase().includes(query) || 
          product.description.toLowerCase().includes(query)
        );
      }
      
      return filtered;
    },
    cartSubtotal() {
      return this.cart.reduce((total, item) => total + (item.price * item.quantity), 0);
    },
    tax() {
      return this.cartSubtotal * 0.1;
    },
    total() {
      return this.cartSubtotal + this.tax;
    },
    pointsEarned() {
      // 2% points for members
      if (this.selectedMember) {
        return Math.floor(this.cartSubtotal * 0.02);
      }
      return 0;
    },
    finalTotal() {
      return this.total;
    },
    memberBreadcrumbs() {
      return [
        {
          label: 'POS',
          path: '#',
          icon: 'svg'
        },
        {
          label: 'Kelola Member',
          path: '#'
        }
      ];
    },
    filteredMembers() {
      if (!this.memberSearchQuery) return this.members;
      
      const query = this.memberSearchQuery.toLowerCase();
      return this.members.filter(member => 
        member.member_id.toLowerCase().includes(query) ||
        member.name.toLowerCase().includes(query) ||
        member.phone.toLowerCase().includes(query) ||
        (member.email && member.email.toLowerCase().includes(query))
      );
    }
  },
  async mounted() {
    await this.loadProducts();
    await this.loadCategories();
    await this.loadKasirName();
    this.loadDarkModePreference();
  },
  watch: {
    showMemberManagement(newVal) {
      if (newVal) {
        this.loadMembers();
      }
    }
  },
  methods: {
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
    async loadProducts() {
      try {
        console.log('Loading products...');
        const response = await axios.get('/pos/products');
        console.log('Products response:', response.data);
        this.products = response.data;
      } catch (error) {
        console.error('Error loading products:', error);
        console.error('Error details:', error.response?.data);
        alert('Error loading products: ' + (error.response?.data?.message || error.message));
      }
    },
    async loadCategories() {
      try {
        console.log('Loading categories...');
        const response = await axios.get('/pos/categories');
        console.log('Categories response:', response.data);
        this.categories = response.data;
      } catch (error) {
        console.error('Error loading categories:', error);
        console.error('Error details:', error.response?.data);
        alert('Error loading categories: ' + (error.response?.data?.message || error.message));
      }
    },
    addToCart(product) {
      if (product.stock <= 0) {
        alert('Stok produk habis!');
        return;
      }

      const existingItem = this.cart.find(item => item.id === product.id);
      if (existingItem) {
        if (existingItem.quantity >= product.stock) {
          alert('Stok tidak mencukupi!');
          return;
        }
        existingItem.quantity += 1;
      } else {
        this.cart.push({
          id: product.id,
          name: product.name,
          price: product.price,
          quantity: 1,
          stock: product.stock
        });
      }
      
      // Auto switch to cart tab when item added
      this.activeTab = 'cart';
    },
    updateQuantity(index, newQuantity) {
      if (newQuantity <= 0) {
        this.removeFromCart(index);
        return;
      }
      
      const item = this.cart[index];
      if (newQuantity > item.stock) {
        alert('Stok tidak mencukupi!');
        return;
      }
      
      this.cart[index].quantity = newQuantity;
    },
    removeFromCart(index) {
      this.cart.splice(index, 1);
    },
    clearCart() {
      this.cart = [];
    },
    formatPrice(price) {
      return new Intl.NumberFormat('id-ID').format(price);
    },
    toggleDarkMode() {
      this.isDarkMode = !this.isDarkMode;
      localStorage.setItem('darkMode', this.isDarkMode);
    },
    loadDarkModePreference() {
      const saved = localStorage.getItem('darkMode');
      this.isDarkMode = saved === 'true';
    },
    logout() {
      if (confirm('Yakin ingin logout?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = window.logoutUrl;
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = window.csrfToken;
        form.appendChild(csrfInput);
        document.body.appendChild(form);
        form.submit();
      }
    },
    async loadMembers() {
      this.memberLoading = true;
      try {
        const response = await axios.get('/pos/members/api');
        this.members = response.data || [];
      } catch (error) {
        console.error('Error loading members:', error);
        alert('Gagal memuat data member: ' + (error.response?.data?.message || error.message));
      } finally {
        this.memberLoading = false;
      }
    },
    selectMember(member) {
      this.selectedMember = member;
      this.showMemberManagement = false;
      // Update customer info with member data
      this.customerName = member.name;
      this.customerPhone = member.phone;
    },
    unselectMember() {
      this.selectedMember = null;
      // Customer info will be hidden automatically when no member selected
    },
    editMember(member) {
      // TODO: Implement edit member
      alert('Edit member: ' + member.name);
    },
    deleteMember(member) {
      if (confirm(`Yakin ingin menghapus member ${member.name}?`)) {
        // TODO: Implement delete member
        alert('Delete member: ' + member.name);
      }
    },
    handleBreadcrumbNavigate(path) {
      if (path === '#') {
        this.showMemberManagement = false;
      }
    },
    goToPayment() {
      if (this.cart.length === 0) {
        alert('Keranjang kosong!');
        return;
      }

      // Save cart and customer data to localStorage
      localStorage.setItem('pos_cart', JSON.stringify(this.cart));
      localStorage.setItem('pos_customer_name', this.customerName);
      localStorage.setItem('pos_customer_phone', this.customerPhone);
      localStorage.setItem('pos_table_number', this.tableNumber);
      localStorage.setItem('pos_selected_member', JSON.stringify(this.selectedMember));
      localStorage.setItem('pos_points_earned', this.pointsEarned);
      localStorage.setItem('pos_final_total', this.finalTotal);

      // Redirect to payment page
      window.location.href = '/kasir/payment';
    },
    startQrisPolling() {
      this.stopQrisPolling();
      this.qrisPollTimer = setInterval(async () => {
        try {
          const res = await axios.get(`/api/pos/orders/${this.qrisOrderId}/status`);
          if (res.data.status === 'settlement') {
            this.showQrisModal = false;
            this.stopQrisPolling();
            alert('Pembayaran berhasil!');
            this.resetAfterOrder();
            // Refresh halaman untuk memulai transaksi baru
            window.location.reload();
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
    cancelQrisPayment() {
      this.showQrisModal = false;
      this.stopQrisPolling();
    },
    goToReport() {
      window.location.href = '/kasir/report';
    },
    async checkPaymentStatus() {
      try {
        const res = await axios.get(`/api/pos/orders/${this.qrisOrderId}/status`);
        if (res.data.status === 'settlement') {
          this.showQrisModal = false;
          this.stopQrisPolling();
          alert('Pembayaran berhasil!');
          this.resetAfterOrder();
          // Refresh halaman untuk memulai transaksi baru
          window.location.reload();
        } else {
          alert('Pembayaran belum selesai. Silakan coba lagi.');
        }
      } catch (error) {
        console.error('Error checking payment status:', error);
        alert('Gagal mengecek status pembayaran.');
      }
    },
    resetAfterOrder() {
      this.cart = [];
      this.customerName = '';
      this.customerPhone = '';
      this.paymentMethod = 'cash';
      this.activeTab = 'products';
    }
  },
  beforeUnmount() {
    this.stopQrisPolling();
  }
}
</script>

<style scoped>
.line-clamp-1 {
  display: -webkit-box;
  -webkit-line-clamp: 1;
  line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
