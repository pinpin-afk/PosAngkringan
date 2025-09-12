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
            class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg leading-5 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            :class="isDarkMode ? 'bg-gray-700 border-gray-600 text-white placeholder-gray-400' : 'bg-white text-gray-900 placeholder-gray-500'"
          />
        </div>
      </div>

      <!-- Categories -->
      <div class="flex-shrink-0 px-4 pb-4 transition-colors duration-300" :class="isDarkMode ? 'bg-gray-800' : 'bg-white'">
        <h3 class="text-sm font-medium mb-3 transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Kategori</h3>
        <div class="flex space-x-2 overflow-x-auto pb-2">
          <button
            @click="selectedCategory = null"
            class="flex-shrink-0 px-3 py-1.5 text-sm font-medium rounded-full transition-colors duration-300"
            :class="selectedCategory === null 
              ? 'bg-blue-600 text-white' 
              : isDarkMode 
                ? 'bg-gray-700 text-gray-300 hover:bg-gray-600' 
                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
          >
            Semua Produk
          </button>
          <button
            v-for="category in categories"
            :key="category.id"
            @click="selectedCategory = category.id"
            class="flex-shrink-0 px-3 py-1.5 text-sm font-medium rounded-full transition-colors duration-300"
            :class="selectedCategory === category.id 
              ? 'bg-blue-600 text-white' 
              : isDarkMode 
                ? 'bg-gray-700 text-gray-300 hover:bg-gray-600' 
                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
          >
            {{ category.name }}
          </button>
        </div>
      </div>

      <!-- Main Content Area -->
      <div class="flex-1 flex overflow-hidden">
        <!-- Products Grid -->
        <div class="flex-1 overflow-y-auto p-4 transition-colors duration-300" :class="isDarkMode ? 'bg-gray-900' : 'bg-gray-50'">
          <div class="mb-4">
            <h2 class="text-lg font-semibold transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
              {{ selectedCategory ? categories.find(c => c.id === selectedCategory)?.name : 'Semua Produk' }}
            </h2>
            <p class="text-sm transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">
              {{ filteredProducts.length }} produk tersedia
            </p>
          </div>
          <div class="grid grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-4">
            <div
              v-for="product in filteredProducts"
              :key="product.id"
              class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:shadow-md transition-all duration-200 cursor-pointer"
              :class="isDarkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'"
            >
              <div class="w-full h-24 rounded-lg mb-3 flex items-center justify-center transition-colors duration-300 overflow-hidden" :class="isDarkMode ? 'bg-gray-700' : 'bg-gray-100'">
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
              <div class="space-y-2">
                <h3 class="font-medium text-sm line-clamp-2 transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ product.name }}</h3>
                <p class="text-xs line-clamp-1 transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">{{ product.description }}</p>
                <div class="flex items-center justify-between">
                  <span class="text-sm font-semibold text-blue-600">Rp {{ formatPrice(product.price) }}</span>
                  <span class="text-xs transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Stok: {{ product.stock }}</span>
                </div>
              </div>
              <button
                class="w-full mt-3 bg-blue-600 text-white py-2 px-3 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors"
                @click.stop="addToCart(product)"
              >
                + Tambah
              </button>
            </div>
          </div>
        </div>

        <!-- Cart Sidebar -->
        <div class="w-80 xl:w-96 transition-colors duration-300 border-l flex flex-col" :class="isDarkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'">
          <div class="p-4 border-b transition-colors duration-300" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
            <div class="flex items-center justify-between">
              <h3 class="text-lg font-semibold transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Keranjang</h3>
              <button
                v-if="cart.length > 0"
                @click="clearCart"
                class="text-sm text-red-600 hover:text-red-700 transition-colors duration-300"
              >
                Hapus Semua
              </button>
            </div>
          </div>

          <div class="flex-1 overflow-y-auto p-4">
            <div v-if="cart.length === 0" class="text-center py-8">
              <svg class="mx-auto h-12 w-12 transition-colors duration-300" :class="isDarkMode ? 'text-gray-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
              </svg>
              <p class="mt-2 text-sm transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Keranjang kosong</p>
            </div>
            <div v-else class="space-y-3">
              <div
                v-for="item in cart"
                :key="item.id"
                class="flex items-center space-x-3 p-3 rounded-lg transition-colors duration-300"
                :class="isDarkMode ? 'bg-gray-700' : 'bg-gray-50'"
              >
                <div class="flex-1">
                  <h4 class="font-medium text-sm transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ item.name }}</h4>
                  <p class="text-sm transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">Rp {{ formatPrice(item.price) }}</p>
                </div>
                <div class="flex items-center space-x-2">
                  <button
                    @click="updateQuantity(item.id, item.quantity - 1)"
                    class="w-8 h-8 rounded-full flex items-center justify-center transition-colors duration-300"
                    :class="isDarkMode ? 'bg-gray-600 hover:bg-gray-500 text-white' : 'bg-gray-200 hover:bg-gray-300 text-gray-700'"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                    </svg>
                  </button>
                  <span class="w-8 text-center font-medium transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ item.quantity }}</span>
                  <button
                    @click="updateQuantity(item.id, item.quantity + 1)"
                    class="w-8 h-8 rounded-full flex items-center justify-center transition-colors duration-300"
                    :class="isDarkMode ? 'bg-gray-600 hover:bg-gray-500 text-white' : 'bg-gray-200 hover:bg-gray-300 text-gray-700'"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                  </button>
                </div>
                <button
                  @click="removeFromCart(item.id)"
                  class="p-1 text-red-600 hover:text-red-700 transition-colors duration-300"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                  </svg>
                </button>
              </div>
            </div>
          </div>

          <!-- Cart Summary -->
          <div v-if="cart.length > 0" class="p-4 border-t transition-colors duration-300" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
            <div class="space-y-3">
              <div class="flex items-center justify-between">
                <span class="text-sm font-medium transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Subtotal</span>
                <span class="text-lg font-semibold transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Rp {{ formatPrice(cartTotal) }}</span>
              </div>
              <button
                @click="proceedToPayment"
                class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-700 transition-colors duration-300"
              >
                Bayar Sekarang
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Tablet Top Sheet Menu -->
      <div v-if="showTopSheet" class="fixed inset-0 z-50 overflow-hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50" @click="showTopSheet = false"></div>
        <div class="absolute top-0 left-0 right-0 bg-white rounded-b-2xl shadow-xl transform transition-transform duration-300" :class="isDarkMode ? 'bg-gray-800' : 'bg-white'">
          <div class="p-6">
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-xl font-bold transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Menu</h2>
              <button @click="showTopSheet = false" class="p-2 rounded-lg transition-colors duration-300" :class="isDarkMode ? 'hover:bg-gray-700' : 'hover:bg-gray-100'">
                <svg class="w-6 h-6 transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>
            
            <div class="space-y-4">
              <button
                @click="goToReport"
                class="w-full flex items-center space-x-3 p-4 rounded-lg transition-colors duration-300"
                :class="isDarkMode ? 'hover:bg-gray-700' : 'hover:bg-gray-100'"
              >
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center" :class="isDarkMode ? 'bg-blue-900' : 'bg-blue-100'">
                  <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                  </svg>
                </div>
                <div class="flex-1 text-left">
                  <h3 class="font-medium transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Laporan Order</h3>
                  <p class="text-sm transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Lihat laporan penjualan</p>
                </div>
                <svg class="w-5 h-5 transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
              </button>

              <button
                @click="openMemberCrudModal"
                class="w-full flex items-center space-x-3 p-4 rounded-lg transition-colors duration-300"
                :class="isDarkMode ? 'hover:bg-gray-700' : 'hover:bg-gray-100'"
              >
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center" :class="isDarkMode ? 'bg-green-900' : 'bg-green-100'">
                  <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                  </svg>
                </div>
                <div class="flex-1 text-left">
                  <h3 class="font-medium transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Kelola Member</h3>
                  <p class="text-sm transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Tambah, edit, hapus member</p>
                </div>
                <svg class="w-5 h-5 transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
              </button>

              <button
                @click="logout"
                class="w-full flex items-center space-x-3 p-4 rounded-lg transition-colors duration-300"
                :class="isDarkMode ? 'hover:bg-gray-700' : 'hover:bg-gray-100'"
              >
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center" :class="isDarkMode ? 'bg-red-900' : 'bg-red-100'">
                  <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                  </svg>
                </div>
                <div class="flex-1 text-left">
                  <h3 class="font-medium transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Logout</h3>
                  <p class="text-sm transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Keluar dari aplikasi</p>
                </div>
                <svg class="w-5 h-5 transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Payment Modal -->
    <PaymentView
      v-if="showPaymentModal"
      :cart="cart"
      :cart-total="cartTotal"
      :selected-member="selectedMember"
      @close="closePaymentModal"
      @payment-success="handlePaymentSuccess"
    />

    <!-- Reusable Member CRUD Modal -->
    <PosMemberManagement
      v-if="showMemberCrudModal"
      :enable-select="true"
      @select="handleMemberSelected"
      @close="handleMemberCrudClosed"
    />
  </div>
</template>

<script>
import axios from 'axios';
import PosMemberManagement from './PosMemberManagement.vue';
import PaymentView from './Kasir/Payment/PaymentView.vue';

export default {
  name: 'PosAppTablet',
  components: {
    PosMemberManagement,
    PaymentView
  },
  data() {
    return {
      products: [],
      categories: [],
      cart: [],
      searchQuery: '',
      selectedCategory: null,
      isDarkMode: false,
      showPaymentModal: false,
      showMemberCrudModal: false,
      showTopSheet: false,
      selectedMember: null,
      processedBy: null,
      isOnline: true,
      networkStatus: 'online'
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
    cartTotal() {
      return this.cartSubtotal + this.tax;
    }
  },
  async mounted() {
    await this.loadProducts();
    await this.loadCategories();
    await this.loadKasirName();
    this.loadDarkModePreference();
    this.loadDraftFromStorage();
  },
  methods: {
    toggleSidebar() {
      this.showTopSheet = !this.showTopSheet;
    },
    logout() {
      if (confirm('Apakah Anda yakin ingin logout?')) {
        window.location.href = '/kasir/logout';
      }
    },
    goToReport() {
      window.location.href = '/kasir/report';
    },
    openMemberCrudModal() {
      this.showMemberCrudModal = true;
    },
    handleMemberSelected(member) {
      this.selectedMember = member;
      this.showMemberCrudModal = false;
    },
    handleMemberCrudClosed() {
      this.showMemberCrudModal = false;
    },
    async loadProducts() {
      try {
        const response = await axios.get('/api/products');
        this.products = response.data;
      } catch (error) {
        console.error('Error loading products:', error);
      }
    },
    async loadCategories() {
      try {
        const response = await axios.get('/api/categories');
        this.categories = response.data;
      } catch (error) {
        console.error('Error loading categories:', error);
      }
    },
    async loadKasirName() {
      try {
        const response = await axios.get('/api/kasir/name');
        this.processedBy = response.data.name;
      } catch (error) {
        console.error('Error loading kasir name:', error);
      }
    },
    loadDarkModePreference() {
      const saved = localStorage.getItem('darkMode');
      this.isDarkMode = saved ? JSON.parse(saved) : false;
    },
    toggleDarkMode() {
      this.isDarkMode = !this.isDarkMode;
      localStorage.setItem('darkMode', JSON.stringify(this.isDarkMode));
    },
    addToCart(product) {
      const existingItem = this.cart.find(item => item.id === product.id);
      if (existingItem) {
        existingItem.quantity += 1;
      } else {
        this.cart.push({
          id: product.id,
          name: product.name,
          price: product.price,
          quantity: 1
        });
      }
      this.saveDraftToStorage();
    },
    updateQuantity(productId, newQuantity) {
      if (newQuantity <= 0) {
        this.removeFromCart(productId);
        return;
      }
      const item = this.cart.find(item => item.id === productId);
      if (item) {
        item.quantity = newQuantity;
        this.saveDraftToStorage();
      }
    },
    removeFromCart(productId) {
      this.cart = this.cart.filter(item => item.id !== productId);
      this.saveDraftToStorage();
    },
    clearCart() {
      this.cart = [];
      this.saveDraftToStorage();
    },
    proceedToPayment() {
      if (this.cart.length === 0) {
        alert('Keranjang kosong');
        return;
      }
      this.showPaymentModal = true;
    },
    closePaymentModal() {
      this.showPaymentModal = false;
    },
    handlePaymentSuccess() {
      this.cart = [];
      this.selectedMember = null;
      this.showPaymentModal = false;
      this.saveDraftToStorage();
    },
    formatPrice(price) {
      return new Intl.NumberFormat('id-ID').format(price);
    },
    saveDraftToStorage() {
      const draft = {
        cart: this.cart,
        selectedMember: this.selectedMember
      };
      localStorage.setItem('posDraft', JSON.stringify(draft));
    },
    loadDraftFromStorage() {
      const draft = localStorage.getItem('posDraft');
      if (draft) {
        const parsed = JSON.parse(draft);
        this.cart = parsed.cart || [];
        this.selectedMember = parsed.selectedMember || null;
      }
    }
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
