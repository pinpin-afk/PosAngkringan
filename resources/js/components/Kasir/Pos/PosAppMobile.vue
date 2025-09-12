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
            'flex-1 py-3 px-4 text-sm font-medium transition-colors duration-300',
            activeTab === 'products'
              ? (isDarkMode ? 'text-blue-400 border-b-2 border-blue-400' : 'text-blue-600 border-b-2 border-blue-600')
              : (isDarkMode ? 'text-gray-400 hover:text-gray-300' : 'text-gray-500 hover:text-gray-700')
          ]"
        >
          Produk
        </button>
        <button
          @click="activeTab = 'cart'"
          :class="[
            'flex-1 py-3 px-4 text-sm font-medium transition-colors duration-300 relative',
            activeTab === 'cart'
              ? (isDarkMode ? 'text-blue-400 border-b-2 border-blue-400' : 'text-blue-600 border-b-2 border-blue-600')
              : (isDarkMode ? 'text-gray-400 hover:text-gray-300' : 'text-gray-500 hover:text-gray-700')
          ]"
        >
          Keranjang
          <span v-if="cart.length > 0" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
            {{ cart.length }}
          </span>
        </button>
      </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto">
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

        <!-- Categories -->
        <div class="mb-4">
          <div class="flex space-x-2 overflow-x-auto pb-2">
            <button
              @click="selectedCategory = ''"
              :class="[
                'px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-colors duration-300',
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
                'px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-colors duration-300',
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
                <span class="text-xs transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Stok: {{ product.stock }}</span>
              </div>
            </div>

            <!-- Add Button -->
            <button
              v-if="product.stock > 0"
              class="w-full mt-2 bg-blue-600 text-white py-2 px-3 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors"
              @click.stop="addToCart(product)"
            >
              + Tambah
            </button>
            <div
              v-else
              class="w-full mt-2 py-2 px-3 rounded-lg text-sm font-medium text-center transition-colors duration-300" :class="isDarkMode ? 'bg-gray-600 text-gray-400' : 'bg-gray-300 text-gray-500'"
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

      <!-- Cart Tab -->
      <div v-if="activeTab === 'cart'" class="p-4">
        <div v-if="cart.length === 0" class="text-center py-12">
          <svg class="w-16 h-16 mx-auto mb-4 transition-colors duration-300" :class="isDarkMode ? 'text-gray-600' : 'text-gray-300'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
          </svg>
          <h3 class="text-lg font-medium mb-2 transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Keranjang kosong</h3>
          <p class="transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Tambahkan produk ke keranjang untuk memulai transaksi</p>
        </div>

        <div v-else class="space-y-4">
          <!-- Cart Items -->
          <div class="space-y-3">
            <div
              v-for="(item, index) in cart"
              :key="index"
              class="flex items-center space-x-3 p-3 rounded-lg transition-colors duration-300" :class="isDarkMode ? 'bg-gray-800' : 'bg-gray-50'"
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

          <!-- Selected Member / Choose Member -->
          <div class="p-4 rounded-lg transition-colors duration-300" :class="isDarkMode ? 'bg-gray-800' : 'bg-gray-50'">
            <div v-if="selectedMember" class="flex items-start justify-between">
              <div>
                <div class="text-sm font-medium mb-1 transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                  Member dipilih
                </div>
                <div class="text-sm transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">
                  {{ selectedMember.name }}<span v-if="selectedMember.phone"> • {{ selectedMember.phone }}</span>
                </div>
              </div>
              <div class="flex items-center space-x-2">
                <button
                  @click="showMemberManagement = true"
                  class="px-3 py-1 text-xs font-medium rounded transition-colors duration-300"
                  :class="isDarkMode ? 'bg-gray-700 text-gray-200 hover:bg-gray-600' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
                >
                  Ganti
                </button>
                <button
                  @click="unselectMember"
                  class="px-3 py-1 text-xs font-medium rounded text-red-700 bg-red-100 hover:bg-red-200 transition-colors"
                >
                  Lepas
                </button>
              </div>
            </div>
            <div v-else class="flex items-center justify-between">
              <div class="text-sm transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">
                Tidak ada member dipilih
              </div>
              <button
                @click="showMemberManagement = true"
                class="px-3 py-2 text-xs font-medium rounded transition-colors duration-300"
                :class="isDarkMode ? 'bg-blue-600 text-white hover:bg-blue-500' : 'bg-blue-600 text-white hover:bg-blue-700'"
              >
                Pilih Member
              </button>
            </div>
          </div>

          <!-- Order Summary -->
          <div class="space-y-2 p-4 rounded-lg transition-colors duration-300" :class="isDarkMode ? 'bg-gray-800' : 'bg-gray-50'">
            <div class="flex justify-between text-sm">
              <span class="transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">Subtotal</span>
              <span class="font-medium transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Rp {{ formatPrice(cartSubtotal) }}</span>
            </div>
            <div class="flex justify-between text-sm">
              <span class="transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">Pajak (10%)</span>
              <span class="font-medium transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Rp {{ formatPrice(tax) }}</span>
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

    <!-- Sidebar Drawer -->
    <transition name="fade">
      <div v-if="showSidebar" class="fixed inset-0 z-50" @click.self="showSidebar = false">
        <div class="absolute inset-0 bg-black/40"></div>
        <!-- Compact Top Sheet (quarter screen) -->
        <aside class="absolute top-0 left-0 right-0 mx-auto w-full max-w-md p-4 shadow-2xl rounded-b-2xl"
             :class="isDarkMode ? 'bg-gray-900 text-white' : 'bg-white text-gray-900'">
          <!-- Sheet Header -->
          <div class="flex items-center justify-between mb-3">
            <div class="font-semibold">Menu</div>
            <button @click="showSidebar = false" class="p-2 rounded"
                    :class="isDarkMode ? 'hover:bg-gray-800' : 'hover:bg-gray-100'">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          <!-- Compact Grid Actions -->
          <div class="grid grid-cols-4 gap-2">
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
            <button @click="showDraftOrdersModal = true; loadDraftOrders(); showSidebar = false" class="flex flex-col items-center justify-center px-3 py-3 rounded-lg"
                    :class="isDarkMode ? 'bg-gray-800 hover:bg-gray-700' : 'bg-gray-100 hover:bg-gray-200'">
              <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
              </svg>
              <span class="text-xs">Draft</span>
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

    <!-- Member Management Modal -->
    <PosMemberManagement
      v-if="showMemberManagement"
      :enableSelect="true"
      @select="selectMember"
      @close="showMemberManagement = false"
    />

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
import PosAppMobileLogic from './PosAppMobile.js';
import Breadcrumb from '../../Breadcrumb.vue';
import PosMemberManagement from '../../PosMemberManagement.vue';

export default {
  name: 'PosAppMobile',
  components: {
    Breadcrumb,
    PosMemberManagement
  },
  ...PosAppMobileLogic
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
</style>
