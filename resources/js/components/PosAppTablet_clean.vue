<template>
  <div class="min-h-screen transition-colors duration-300" :class="isDarkMode ? 'bg-gray-900' : 'bg-gray-50'">
    <!-- Tablet Layout (768x1024) -->
    <div class="flex flex-col h-screen">
      <!-- Top Header -->
      <div class="flex-shrink-0 p-4 transition-colors duration-300" :class="isDarkMode ? 'bg-gray-800 border-b border-gray-700' : 'bg-white border-b border-gray-200'">
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
              <h1 class="text-lg font-bold transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">POS Angkringan</h1>
              <p class="text-sm transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">Kasir: {{ processedBy || 'Tidak diketahui' }}</p>
            </div>
          </div>
          <div class="flex items-center space-x-2">
            <button @click="toggleDarkMode" class="p-2 rounded-lg transition-colors duration-300" :class="isDarkMode ? 'bg-gray-700 hover:bg-gray-600' : 'bg-gray-100 hover:bg-gray-200'">
              <svg v-if="isDarkMode" class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
              </svg>
              <svg v-else class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3 10a7 7 0 1114 0 7 7 0 01-14 0zm7-4a1 1 0 11-2 0 4 4 0 000 8 1 1 0 102 0 6 6 0 00-6-6zM8 10a2 2 0 114 0 2 2 0 01-4 0z" clip-rule="evenodd"></path>
              </svg>
            </button>
            <button @click="showTopSheet = true" class="p-2 rounded-lg transition-colors duration-300" :class="isDarkMode ? 'bg-gray-700 hover:bg-gray-600' : 'bg-gray-100 hover:bg-gray-200'">
              <svg class="w-5 h-5 transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Search Bar -->
      <div class="flex-shrink-0 p-4 transition-colors duration-300" :class="isDarkMode ? 'bg-gray-800' : 'bg-white'">
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
          </div>
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Cari produk..."
            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 transition-colors duration-300 focus:outline-none focus:placeholder-gray-500 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" :class="isDarkMode ? 'bg-gray-700 border-gray-600 text-white placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500'"
          >
        </div>
      </div>

      <!-- Main Content -->
      <div class="flex-1 flex overflow-hidden">
        <!-- Left Sidebar - Categories -->
        <div class="w-64 flex-shrink-0 border-r transition-colors duration-300" :class="isDarkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'">
          <div class="p-4">
            <h3 class="text-lg font-semibold mb-4 transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Kategori</h3>
            <div class="space-y-2">
              <button
                @click="selectedCategory = null"
                :class="[
                  'w-full text-left px-3 py-2 rounded-md text-sm font-medium transition-colors duration-300',
                  selectedCategory === null 
                    ? (isDarkMode ? 'bg-blue-600 text-white' : 'bg-blue-100 text-blue-700')
                    : (isDarkMode ? 'text-gray-300 hover:bg-gray-700 hover:text-white' : 'text-gray-700 hover:bg-gray-100')
                ]"
              >
                Semua Kategori
              </button>
              <button
                v-for="category in categories"
                :key="category.id"
                @click="selectedCategory = category.id"
                :class="[
                  'w-full text-left px-3 py-2 rounded-md text-sm font-medium transition-colors duration-300',
                  selectedCategory === category.id 
                    ? (isDarkMode ? 'bg-blue-600 text-white' : 'bg-blue-100 text-blue-700')
                    : (isDarkMode ? 'text-gray-300 hover:bg-gray-700 hover:text-white' : 'text-gray-700 hover:bg-gray-100')
                ]"
              >
                {{ category.name }}
              </button>
            </div>
          </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
          <!-- Products Grid -->
          <div class="flex-1 overflow-y-auto p-4">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
              <div
                v-for="product in filteredProducts"
                :key="product.id"
                @click="addToCart(product)"
                :class="[
                  'rounded-lg border p-4 cursor-pointer transition-all duration-200 hover:shadow-md',
                  isDarkMode ? 'bg-gray-800 border-gray-700 hover:border-blue-400' : 'bg-white border-gray-200 hover:border-blue-300',
                  'hover:scale-105'
                ]"
              >
                <!-- Product Image -->
                <div class="w-full h-32 rounded-lg mb-3 flex items-center justify-center transition-colors duration-300 overflow-hidden" :class="isDarkMode ? 'bg-gray-700' : 'bg-gray-100'">
                  <img 
                    v-if="product.image_url" 
                    :src="product.image_url" 
                    :alt="product.name"
                    class="w-full h-full object-cover rounded-lg"
                  >
                  <svg v-else class="w-8 h-8 transition-colors duration-300" :class="isDarkMode ? 'text-gray-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                  </svg>
                </div>

                <!-- Product Info -->
                <div class="space-y-2">
                  <h3 class="font-medium text-sm line-clamp-2 transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ product.name }}</h3>
                  <p class="text-xs line-clamp-2 transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">{{ product.description }}</p>
                  <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-blue-600">Rp {{ formatPrice(product.price) }}</span>
                    <span class="text-xs transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Stok: {{ product.stock }}</span>
                  </div>
                </div>

                <!-- Add Button -->
                <button
                  class="w-full mt-3 bg-blue-600 text-white py-2 px-3 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors"
                  @click.stop="addToCart(product)"
                >
                  + Tambah ke Keranjang
                </button>
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

          <!-- Cart Summary -->
          <div class="flex-shrink-0 border-t transition-colors duration-300" :class="isDarkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'">
            <div class="p-4">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Keranjang ({{ cart.length }} item)</h3>
                <button
                  v-if="cart.length > 0"
                  @click="clearCart"
                  class="text-sm text-red-600 hover:text-red-700 transition-colors"
                >
                  Hapus Semua
                </button>
              </div>

              <!-- Cart Items -->
              <div v-if="cart.length === 0" class="text-center py-8">
                <svg class="w-12 h-12 mx-auto mb-2 transition-colors duration-300" :class="isDarkMode ? 'text-gray-600' : 'text-gray-300'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                </svg>
                <p class="text-sm transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Keranjang kosong</p>
              </div>

              <div v-else class="space-y-2 max-h-32 overflow-y-auto">
                <div
                  v-for="item in cart"
                  :key="item.id"
                  class="flex items-center justify-between py-2 px-3 rounded-lg transition-colors duration-300" :class="isDarkMode ? 'bg-gray-700' : 'bg-gray-50'"
                >
                  <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-medium truncate transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ item.name }}</h4>
                    <p class="text-xs transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Rp {{ formatPrice(item.price) }}</p>
                  </div>
                  <div class="flex items-center space-x-2">
                    <button
                      @click="updateQuantity(item.id, item.quantity - 1)"
                      class="w-6 h-6 rounded-full flex items-center justify-center transition-colors duration-300" :class="isDarkMode ? 'bg-gray-600 hover:bg-gray-500 text-gray-300' : 'bg-gray-200 hover:bg-gray-300 text-gray-700'"
                    >
                      <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                      </svg>
                    </button>
                    <span class="w-8 text-center text-sm font-medium transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ item.quantity }}</span>
                    <button
                      @click="updateQuantity(item.id, item.quantity + 1)"
                      class="w-6 h-6 rounded-full flex items-center justify-center transition-colors duration-300" :class="isDarkMode ? 'bg-gray-600 hover:bg-gray-500 text-gray-300' : 'bg-gray-200 hover:bg-gray-300 text-gray-700'"
                    >
                      <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                      </svg>
                    </button>
                    <button
                      @click="removeFromCart(item.id)"
                      class="p-1 transition-colors duration-300" :class="isDarkMode ? 'text-red-400 hover:text-red-300' : 'text-red-500 hover:text-red-700'"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                      </svg>
                    </button>
                  </div>
                </div>
              </div>

              <!-- Order Summary -->
              <div v-if="cart.length > 0" class="mt-4 space-y-2">
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
                    <span class="text-blue-600">Rp {{ formatPrice(cartTotal) }}</span>
                  </div>
                </div>
                <button
                  @click="proceedToPayment"
                  class="w-full mt-4 bg-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-700 transition-colors"
                >
                  Proses Pembayaran
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Top Sheet Menu -->
    <transition name="fade">
      <div v-if="showTopSheet" class="fixed inset-0 z-50" @click.self="showTopSheet = false">
        <div class="absolute inset-0 bg-black/40"></div>
        <!-- Compact Top Sheet (quarter screen) -->
        <aside class="absolute top-0 left-0 right-0 mx-auto w-full max-w-md p-4 shadow-2xl rounded-b-2xl"
             :class="isDarkMode ? 'bg-gray-900 text-white' : 'bg-white text-gray-900'">
          <!-- Sheet Header -->
          <div class="flex items-center justify-between mb-3">
            <div class="font-semibold">Menu</div>
            <button @click="showTopSheet = false" class="p-2 rounded"
                    :class="isDarkMode ? 'hover:bg-gray-800' : 'hover:bg-gray-100'">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          <!-- Compact Grid Actions -->
          <div class="grid grid-cols-4 gap-2">
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
            <button @click="logout(); showTopSheet = false" class="flex flex-col items-center justify-center px-3 py-3 rounded-lg"
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

    <!-- Payment Modal -->
    <PaymentView
      v-if="showPaymentModal"
      :cart="cart"
      :selectedMember="selectedMember"
      @close="closePaymentModal"
      @success="handlePaymentSuccess"
    />

    <!-- Member Management Modal -->
    <PosMemberManagement
      v-if="showMemberCrudModal"
      @member-selected="handleMemberSelected"
      @close="handleMemberCrudClosed"
    />
  </div>
</template>

<script>
import PosAppTabletLogic from './PosAppTablet.js';
import PosMemberManagement from './PosMemberManagement.vue';
import PaymentView from './Kasir/Payment/PaymentView.vue';

export default {
  name: 'PosAppTablet',
  components: {
    PosMemberManagement,
    PaymentView
  },
  ...PosAppTabletLogic
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
