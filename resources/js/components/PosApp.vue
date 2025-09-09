<template>
  <div class="min-h-screen transition-colors duration-300" :class="isDarkMode ? 'bg-gray-900' : 'bg-gray-50'">
    <!-- Header -->
    <header class="transition-colors duration-300 shadow-2xl" :class="isDarkMode ? 'bg-gray-800 text-white' : 'bg-white text-gray-900'">
      <div class="container mx-auto px-4 py-6">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center" :class="isDarkMode ? 'bg-gray-700' : 'bg-gray-100'">
              <img 
                src="/assets/img/WhatsApp Image 2025-09-07 at 17.12.58.jpeg" 
                alt="Logo Angkringan" 
                class="w-10 h-10 rounded-lg object-cover"
              >
            </div>
            <div>
              <h1 class="text-2xl font-bold">Sistem POS Angkringan</h1>
              <p class="text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">Kasir • Cepat, mudah, dan intuitif</p>
            </div>
          </div>
          <div class="hidden md:flex items-center space-x-4">
            <!-- Dark Mode Toggle -->
            <button @click="toggleDarkMode" class="flex items-center space-x-2 px-3 py-2 rounded-lg transition-all duration-200" :class="isDarkMode ? 'bg-gray-700 hover:bg-gray-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'">
              <svg v-if="isDarkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
              </svg>
              <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
              </svg>
              <span class="text-sm font-medium">{{ isDarkMode ? 'Light' : 'Dark' }}</span>
            </button>
            
            <div class="flex items-center space-x-3">
              <!-- <span class="text-sm font-medium" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Metode Pembayaran:</span>
              <select v-model="paymentMethod" class="text-sm px-4 py-2 rounded-xl focus:outline-none focus:ring-2 transition-all duration-200" :class="isDarkMode ? 'bg-gray-700 border-gray-600 text-white focus:ring-gray-500' : 'bg-white border-gray-300 text-gray-900 focus:ring-gray-500'">
                <option value="cash">💵 Tunai</option>
                <option value="card">💳 Kartu</option>
                <option value="qris">📱 QRIS</option>
                <option value="gopay">🟢 GoPay</option>
                <option value="shopeepay">🟠 ShopeePay</option>
              </select> -->
            </div>
            <div class="flex items-center space-x-3 pl-4" :class="isDarkMode ? 'border-l border-gray-600' : 'border-l border-gray-300'">
              <div class="flex items-center space-x-2">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" :class="isDarkMode ? 'bg-gray-700' : 'bg-gray-100'">
                  <svg class="w-5 h-5" :class="isDarkMode ? 'text-white' : 'text-gray-700'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                  </svg>
                </div>
                <span class="text-sm font-medium" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">{{ user?.name || 'Kasir' }}</span>
              </div>
              <button @click="handleLogout" class="flex items-center space-x-1 px-3 py-2 rounded-lg transition-all duration-200 text-sm font-medium" :class="isDarkMode ? 'bg-red-600 hover:bg-red-700 text-white' : 'bg-red-100 hover:bg-red-200 text-red-700'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                <span>Logout</span>
              </button>
            </div>
          </div>
          
          <!-- Mobile User Info -->
          <div class="md:hidden flex items-center space-x-3">
            <!-- Dark Mode Toggle Mobile -->
            <button @click="toggleDarkMode" class="flex items-center space-x-1 px-2 py-2 rounded-lg transition-all duration-200" :class="isDarkMode ? 'bg-gray-700 hover:bg-gray-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'">
              <svg v-if="isDarkMode" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
              </svg>
              <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
              </svg>
            </button>
            
            <div class="flex items-center space-x-2">
              <div class="w-8 h-8 rounded-lg flex items-center justify-center" :class="isDarkMode ? 'bg-gray-700' : 'bg-gray-100'">
                <svg class="w-5 h-5" :class="isDarkMode ? 'text-white' : 'text-gray-700'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
              </div>
              <span class="text-sm font-medium" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">{{ user?.name || 'Kasir' }}</span>
            </div>
            <button @click="handleLogout" class="flex items-center space-x-1 px-3 py-2 rounded-lg transition-all duration-200 text-sm font-medium" :class="isDarkMode ? 'bg-red-600 hover:bg-red-700 text-white' : 'bg-red-100 hover:bg-red-200 text-red-700'">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
              </svg>
              <span class="hidden sm:inline">Logout</span>
            </button>
          </div>
        </div>
      </div>
    </header>

    <!-- Mobile Payment Method -->
    <div class="md:hidden border-b transition-colors duration-300" :class="isDarkMode ? 'bg-gray-800 border-gray-700' : 'bg-gray-100 border-gray-200'">
      <div class="container mx-auto px-4 py-3">
        <div class="flex items-center justify-between">
          <span class="text-sm font-medium" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Metode Pembayaran:</span>
          <select v-model="paymentMethod" class="text-sm px-3 py-2 rounded-lg focus:outline-none focus:ring-2 transition-all duration-200" :class="isDarkMode ? 'bg-gray-700 border-gray-600 text-white focus:ring-gray-500' : 'bg-white border-gray-300 text-gray-900 focus:ring-gray-500'">
            <option value="cash">💵 Tunai</option>
            <option value="card">💳 Kartu</option>
            <option value="qris">📱 QRIS</option>
            <option value="gopay">🟢 GoPay</option>
            <option value="shopeepay">🟠 ShopeePay</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto p-6">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Product List -->
        <div class="lg:col-span-2">
          <div class="rounded-2xl shadow-xl border p-8 transition-colors duration-300" :class="isDarkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'">
            <div class="flex items-center justify-between mb-6">
              <div>
                <h2 class="text-2xl font-bold transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-800'">Daftar Produk</h2>
                <p class="text-sm mt-1 transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">Pilih produk untuk ditambahkan ke keranjang</p>
              </div>
              <div class="relative w-full md:w-1/2 ml-0 md:ml-6">
                <input v-model="searchQuery" type="text" placeholder="Cari nama/desk produk..." class="w-full pl-12 pr-4 py-3 border rounded-xl focus:ring-2 focus:ring-gray-500 transition-all duration-200" :class="isDarkMode ? 'bg-gray-700 border-gray-600 text-white placeholder-gray-400 focus:border-gray-500' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500 focus:border-gray-500'" />
                <svg class="absolute left-4 top-3.5 w-5 h-5 transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
              </div>
            </div>

            <!-- Category Chips -->
            <div class="flex items-center flex-wrap gap-3 mb-6">
              <button @click="selectedCategory = ''" class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 border" :class="[ selectedCategory === '' ? (isDarkMode ? 'bg-gray-900 text-white shadow-lg border-gray-600' : 'bg-gray-900 text-white shadow-lg border-gray-300') : (isDarkMode ? 'text-gray-300 hover:bg-gray-700 border-gray-600' : 'text-gray-700 hover:bg-gray-100 border-gray-300') ]">
                🏪 Semua
              </button>
              <button v-for="category in categories" :key="category.id" @click="selectedCategory = category.id" class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 border" :class="[ selectedCategory === category.id ? (isDarkMode ? 'bg-gray-900 text-white shadow-lg border-gray-600' : 'bg-gray-900 text-white shadow-lg border-gray-300') : (isDarkMode ? 'text-gray-300 hover:bg-gray-700 border-gray-600' : 'text-gray-700 hover:bg-gray-100 border-gray-300') ]">
                {{ category.name }}
              </button>
            </div>

            <!-- Product Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">
              <div v-for="product in filteredProducts" :key="product.id" @click="addToCart(product)" class="group rounded-2xl border transition-all duration-300 cursor-pointer transform hover:-translate-y-1" :class="isDarkMode ? 'border-gray-600 hover:border-gray-500 bg-gray-700 hover:bg-gray-600 hover:shadow-xl' : 'border-gray-200 hover:border-gray-300 bg-gray-50 hover:bg-white hover:shadow-xl'">
                <div class="p-5">
                  <div class="w-full h-28 rounded-xl flex items-center justify-center mb-4 transition-all duration-300" :class="isDarkMode ? 'bg-gray-600 group-hover:bg-gray-500' : 'bg-gray-200 group-hover:bg-gray-100'">
                    <span class="font-bold text-2xl transition-colors duration-300" :class="isDarkMode ? 'text-white group-hover:text-gray-200' : 'text-gray-700 group-hover:text-gray-800'">{{ product.name.charAt(0).toUpperCase() }}</span>
                  </div>
                  <h3 class="font-bold text-sm truncate mb-1 transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ product.name }}</h3>
                  <p class="text-xs truncate mb-3 transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">{{ product.description }}</p>
                  <div class="flex items-center justify-between">
                    <span class="font-bold text-sm transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Rp {{ formatPrice(product.price) }}</span>
                    <span class="text-xs px-3 py-1 rounded-full font-medium" :class="product.stock > 0 ? (isDarkMode ? 'bg-green-900 text-green-300' : 'bg-green-100 text-green-700') : (isDarkMode ? 'bg-red-900 text-red-300' : 'bg-red-100 text-red-700')">
                      {{ product.stock > 0 ? `Stok: ${product.stock}` : 'Habis' }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Shopping Cart -->
        <div class="lg:col-span-1">
          <div class="rounded-2xl shadow-xl border p-6 lg:sticky lg:top-6 transition-colors duration-300" :class="isDarkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'">
            <div class="flex items-center mb-6">
              <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 transition-colors duration-300" :class="isDarkMode ? 'bg-gray-700' : 'bg-gray-100'">
                <svg class="w-5 h-5 transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-700'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                </svg>
              </div>
              <h2 class="text-xl font-bold transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-800'">Keranjang Belanja</h2>
            </div>
            
            <!-- Cart Items -->
            <div class="space-y-3 mb-6 max-h-80 overflow-y-auto pr-2">
              <div v-for="item in cart" :key="item.id" class="flex items-center justify-between rounded-xl px-4 py-3 border transition-colors duration-300" :class="isDarkMode ? 'bg-gray-700 border-gray-600' : 'bg-gray-50 border-gray-200'">
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-semibold truncate transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-800'">{{ item.name }}</p>
                  <p class="text-xs transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Rp {{ formatPrice(item.price) }}</p>
                </div>
                <div class="flex items-center space-x-2">
                  <button @click="decreaseQuantity(item)" class="w-8 h-8 rounded-lg transition-colors duration-200 flex items-center justify-center" :class="isDarkMode ? 'bg-gray-600 text-gray-300 hover:bg-gray-500' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                    </svg>
                  </button>
                  <span class="text-sm w-8 text-center font-semibold transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ item.quantity }}</span>
                  <button @click="increaseQuantity(item)" class="w-8 h-8 rounded-lg transition-colors duration-200 flex items-center justify-center" :class="isDarkMode ? 'bg-gray-600 text-white hover:bg-gray-500' : 'bg-gray-900 text-white hover:bg-gray-800'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                  </button>
                </div>
              </div>
              <div v-if="cart.length === 0" class="text-center py-8 transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                <svg class="w-12 h-12 mx-auto mb-3 transition-colors duration-300" :class="isDarkMode ? 'text-gray-600' : 'text-gray-300'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                </svg>
                <p class="text-sm">Keranjang kosong</p>
              </div>
            </div>

            <!-- Cart Summary -->
            <div v-if="cart.length > 0" class="rounded-xl p-4 mb-6 border transition-colors duration-300" :class="isDarkMode ? 'bg-gray-700 border-gray-600' : 'bg-gray-50 border-gray-200'">
              <div class="space-y-2">
                <div class="flex justify-between text-sm">
                  <span class="transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">Subtotal:</span>
                  <span class="font-semibold transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Rp {{ formatPrice(cartSubtotal) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">Pajak (10%):</span>
                  <span class="font-semibold transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Rp {{ formatPrice(tax) }}</span>
                </div>
                <div class="border-t pt-2 transition-colors duration-300" :class="isDarkMode ? 'border-gray-600' : 'border-gray-200'">
                  <div class="flex justify-between font-bold text-lg">
                    <span class="transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-800'">Total:</span>
                    <span class="transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Rp {{ formatPrice(total) }}</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Customer Info -->
            <div class="space-y-3 mb-6">
              <div>
                <label class="block text-sm font-medium mb-2 transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Nama Pelanggan</label>
                <input v-model="customerName" type="text" placeholder="Masukkan nama pelanggan" class="w-full border rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-gray-500 transition-all duration-200" :class="isDarkMode ? 'bg-gray-700 border-gray-600 text-white placeholder-gray-400 focus:border-gray-500' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500 focus:border-gray-500'">
              </div>
              <div>
                <label class="block text-sm font-medium mb-2 transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">No. Telepon</label>
                <input v-model="customerPhone" type="text" placeholder="Masukkan nomor telepon" class="w-full border rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-gray-500 transition-all duration-200" :class="isDarkMode ? 'bg-gray-700 border-gray-600 text-white placeholder-gray-400 focus:border-gray-500' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500 focus:border-gray-500'">
              </div>
            </div>

            <!-- Payment Method -->
            <div class="mb-6">
              <label class="block text-sm font-medium mb-2 transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Metode Pembayaran:</label>
              <select v-model="paymentMethod" class="w-full border rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-gray-500 transition-all duration-200" :class="isDarkMode ? 'bg-gray-700 border-gray-600 text-white focus:border-gray-500' : 'bg-white border-gray-300 text-gray-900 focus:border-gray-500'">
                <option value="cash">💵 Tunai</option>
                <option value="card">💳 Kartu</option>
                <option value="qris">📱 QRIS</option>
                <option value="gopay">🟢 GoPay</option>
                <option value="shopeepay">🟠 ShopeePay</option>
              </select>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
              <button @click="clearCart" :disabled="cart.length === 0" class="w-full py-3 rounded-xl transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed font-medium" :class="isDarkMode ? 'bg-gray-700 text-gray-300 hover:bg-gray-600' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'">
                🗑️ Kosongkan Keranjang
              </button>
              <button @click="processOrder" :disabled="cart.length === 0" class="w-full text-white py-3 rounded-xl disabled:bg-gray-400 disabled:cursor-not-allowed transition-all duration-200 font-semibold shadow-lg hover:shadow-xl" :class="isDarkMode ? 'bg-gray-900 hover:bg-gray-800' : 'bg-gray-900 hover:bg-gray-800'">
                💳 Proses Pesanan
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Pembayaran Modal (QR/Deeplink) -->
  <div v-if="showQrisModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">
    <div class="rounded-2xl shadow-2xl w-full max-w-lg border transition-colors duration-300" :class="isDarkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'">
      <div class="px-8 py-6 border-b transition-colors duration-300" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-colors duration-300" :class="isDarkMode ? 'bg-gray-700' : 'bg-gray-100'">
              <svg class="w-6 h-6 transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-700'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
              </svg>
            </div>
            <div>
              <h3 class="text-xl font-bold transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Bayar dengan {{ paymentTitle }}</h3>
              <p class="text-sm transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">Total: Rp {{ formatPrice(total) }}</p>
            </div>
          </div>
          <button @click="closeQrisModal" class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors duration-200" :class="isDarkMode ? 'bg-gray-700 hover:bg-gray-600' : 'bg-gray-100 hover:bg-gray-200'">
            <svg class="w-5 h-5 transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
      </div>
      <div class="px-8 py-8 text-center">
        <div class="mb-6">
          <div class="rounded-2xl p-6 shadow-lg border transition-colors duration-300" :class="isDarkMode ? 'bg-gray-700 border-gray-600' : 'bg-white border-gray-100'">
            <img v-if="qrisQrUrl" :src="qrisQrUrl" alt="QR Code" class="mx-auto w-64 h-64 object-contain rounded-xl" />
            <img v-else-if="qrisQrString" :src="qrImageFromString()" alt="QR Code" class="mx-auto w-64 h-64 object-contain rounded-xl" />
            <div v-else class="w-64 h-64 mx-auto rounded-xl flex items-center justify-center transition-colors duration-300" :class="isDarkMode ? 'bg-gray-600' : 'bg-gray-100'">
              <div class="text-center">
                <svg class="w-12 h-12 mx-auto mb-3 animate-spin transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <p class="text-sm transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Menyiapkan QR Code...</p>
              </div>
            </div>
          </div>
        </div>
        
        <div v-if="deeplinkUrl" class="mb-6">
          <a :href="deeplinkUrl" target="_blank" class="inline-flex items-center px-6 py-3 text-white rounded-xl transition-all duration-200 font-semibold shadow-lg hover:shadow-xl" :class="isDarkMode ? 'bg-gray-900 hover:bg-gray-800' : 'bg-gray-900 hover:bg-gray-800'">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
            </svg>
            Buka Aplikasi
          </a>
        </div>
        
        <div class="space-y-3 text-sm transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">
          <p class="font-medium">Ikuti instruksi di aplikasi pembayaran Anda</p>
          <p v-if="paymentMethod === 'gopay'" class="text-xs px-3 py-2 rounded-lg transition-colors duration-300" :class="isDarkMode ? 'text-gray-400 bg-gray-700' : 'text-gray-500 bg-blue-50'">QR yang ditampilkan adalah QRIS dan dapat di-scan menggunakan GoPay</p>
          <p class="text-xs px-3 py-2 rounded-lg transition-colors duration-300" :class="isDarkMode ? 'text-gray-400 bg-gray-700' : 'text-gray-500 bg-gray-50'">Halaman akan otomatis menutup setelah pembayaran terkonfirmasi</p>
        </div>
      </div>
      <div class="px-8 py-6 border-t rounded-b-2xl transition-colors duration-300" :class="isDarkMode ? 'border-gray-700 bg-gray-700' : 'border-gray-200 bg-gray-50'">
        <div class="flex justify-center">
          <button @click="closeQrisModal" class="px-6 py-2 text-sm rounded-xl transition-colors duration-200 font-medium" :class="isDarkMode ? 'bg-gray-600 hover:bg-gray-500 text-gray-300' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'">
            Batalkan Pembayaran
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'PosApp',
  data() {
    return {
      products: [],
      categories: [],
      selectedCategory: '',
      searchQuery: '',
      cart: [],
      customerName: '',
      customerPhone: '',
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
      deeplinkUrl: null
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
    paymentTitle() {
      const map = { qris: 'QRIS', gopay: 'GoPay', shopeepay: 'ShopeePay' };
      return map[this.paymentMethod] || 'Pembayaran'
    }
  },
  async mounted() {
    await this.loadProducts();
    await this.loadCategories();
    await this.loadKasirName();
    this.loadDarkModePreference();
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
    filterProducts() {
      // Products are filtered in computed property
    },
    addToCart(product) {
      if (product.stock <= 0) {
        alert('Stok produk habis!');
        return;
      }

      const existingItem = this.cart.find(item => item.id === product.id);
      if (existingItem) {
        if (existingItem.quantity < product.stock) {
          existingItem.quantity++;
        } else {
          alert('Stok tidak mencukupi!');
        }
      } else {
        this.cart.push({
          id: product.id,
          name: product.name,
          price: product.price,
          quantity: 1
        });
      }
    },
    increaseQuantity(item) {
      const product = this.products.find(p => p.id === item.id);
      if (item.quantity < product.stock) {
        item.quantity++;
      } else {
        alert('Stok tidak mencukupi!');
      }
    },
    decreaseQuantity(item) {
      if (item.quantity > 1) {
        item.quantity--;
      } else {
        this.removeFromCart(item);
      }
    },
    removeFromCart(item) {
      const index = this.cart.findIndex(cartItem => cartItem.id === item.id);
      if (index > -1) {
        this.cart.splice(index, 1);
      }
    },
    clearCart() {
      this.cart = [];
    },
    async processOrder() {
      if (this.cart.length === 0) {
        alert('Keranjang kosong!');
        return;
      }

      try {
        const orderData = {
          customer_name: this.customerName,
          customer_phone: this.customerPhone,
          processed_by: this.processedBy,
          payment_method: this.paymentMethod,
          items: this.cart.map(item => ({
            product_id: item.id,
            quantity: item.quantity
          }))
        };

        if (['qris','gopay','shopeepay'].includes(this.paymentMethod)) {
          // Untuk GoPay, gunakan QRIS agar QR muncul
          const paymentTypeToSend = this.paymentMethod === 'gopay' ? 'qris' : this.paymentMethod;
          const res = await axios.post('/api/pos/orders/charge', { ...orderData, payment_type: paymentTypeToSend });
          const data = res.data || {};
          this.qrisOrderId = data.order_id || null;
          this.qrisQrString = data.qr_string || null;
          this.qrisQrUrl = data.qr_url || null;
          this.deeplinkUrl = data.deeplink_url || null;
          this.showQrisModal = true;
          this.startQrisPolling();
        } else {
          await axios.post('/api/pos/orders', orderData);
          alert('Pesanan berhasil diproses!');
          this.resetAfterOrder();
        }
      } catch (error) {
        console.error('Error processing order:', error);
        const apiMsg = error?.response?.data?.detail?.status_message
          || error?.response?.data?.status_message
          || error?.response?.data?.message
          || error?.message
          || 'Terjadi kesalahan saat memproses pesanan!';
        alert(apiMsg);
      }
    },
    startQrisPolling() {
      this.stopQrisPolling();
      if (!this.qrisOrderId) return;
      this.qrisPollTimer = setInterval(async () => {
        try {
          const res = await axios.get(`/api/pos/orders/${this.qrisOrderId}/status`);
          const status = res.data?.status || '';
          if (status === 'completed') {
            this.stopQrisPolling();
            this.showQrisModal = false;
            alert('Pembayaran berhasil!');
            this.resetAfterOrder();
          }
        } catch (e) {
          console.warn('QRIS polling error', e);
        }
      }, 2500);
    },
    stopQrisPolling() {
      if (this.qrisPollTimer) {
        clearInterval(this.qrisPollTimer);
        this.qrisPollTimer = null;
      }
    },
    closeQrisModal() {
      this.showQrisModal = false;
    },
    resetAfterOrder() {
      this.clearCart();
      this.customerName = '';
      this.customerPhone = '';
      this.paymentMethod = 'cash';
      this.deeplinkUrl = null;
      this.loadProducts();
    },
    qrImageFromString() {
      if (!this.qrisQrString) return '';
      return 'https://api.qrserver.com/v1/create-qr-code/?size=240x240&data=' + encodeURIComponent(this.qrisQrString);
    },
    formatPrice(price) {
      return new Intl.NumberFormat('id-ID').format(price);
    },
    handleLogout() {
      if (confirm('Apakah Anda yakin ingin logout?')) {
        // Create a form and submit it for logout
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = window.logoutUrl;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = window.csrfToken;
        
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
      }
    },
    toggleDarkMode() {
      this.isDarkMode = !this.isDarkMode;
      this.saveDarkModePreference();
    },
    loadDarkModePreference() {
      const saved = localStorage.getItem('pos-dark-mode');
      if (saved !== null) {
        this.isDarkMode = saved === 'true';
      }
    },
    saveDarkModePreference() {
      localStorage.setItem('pos-dark-mode', this.isDarkMode.toString());
    }
  }
}
</script>
