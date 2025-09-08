<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-gradient-to-r from-indigo-600 via-blue-600 to-cyan-600 text-white">
      <div class="container mx-auto px-4 py-4">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-bold">Sistem POS Angkringan</h1>
            <p class="text-white/80 text-sm">Kasir • Cepat, mudah, dan intuitif</p>
          </div>
          <div class="hidden md:flex items-center space-x-2">
            <span class="text-sm text-white/90">Metode: </span>
            <select v-model="paymentMethod" class="bg-white/10 border border-white/20 text-white text-sm px-3 py-1 rounded-md focus:outline-none">
              <option value="cash">Tunai</option>
              <option value="card">Kartu</option>
              <option value="qris">QRIS</option>
              <option value="gopay">GoPay</option>
              <option value="shopeepay">ShopeePay</option>
            </select>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <div class="container mx-auto p-4">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Product List -->
        <div class="lg:col-span-2">
          <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-xl font-semibold">Daftar Produk</h2>
              <div class="relative w-full md:w-1/2 ml-0 md:ml-6">
                <input v-model="searchQuery" type="text" placeholder="Cari nama/desk produk..." class="w-full pl-10 pr-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
              </div>
            </div>

            <!-- Category Chips -->
            <div class="flex items-center flex-wrap gap-2 mb-5">
              <button @click="selectedCategory = ''" :class="selectedCategory === '' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'" class="px-3 py-1.5 rounded-full text-sm">Semua</button>
              <button v-for="category in categories" :key="category.id" @click="selectedCategory = category.id" :class="selectedCategory === category.id ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'" class="px-3 py-1.5 rounded-full text-sm">
                {{ category.name }}
              </button>
            </div>

            <!-- Product Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
              <div v-for="product in filteredProducts" :key="product.id" @click="addToCart(product)" class="rounded-xl border border-gray-200 hover:border-indigo-300 hover:shadow transition-all cursor-pointer bg-white">
                <div class="p-4">
                  <div class="w-full h-24 rounded-lg bg-gradient-to-br from-indigo-100 to-blue-100 flex items-center justify-center mb-3">
                    <span class="text-indigo-600 font-bold text-xl">{{ product.name.charAt(0).toUpperCase() }}</span>
                  </div>
                  <h3 class="font-semibold text-gray-900 text-sm truncate">{{ product.name }}</h3>
                  <p class="text-xs text-gray-500 truncate">{{ product.description }}</p>
                  <div class="mt-2 flex items-center justify-between">
                    <span class="text-indigo-600 font-bold">Rp {{ formatPrice(product.price) }}</span>
                    <span class="text-xs px-2 py-0.5 rounded-full" :class="product.stock > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'">Stok: {{ product.stock }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Shopping Cart -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-xl shadow p-6 lg:sticky lg:top-6">
            <h2 class="text-xl font-semibold mb-4">Keranjang Belanja</h2>
            
            <!-- Cart Items -->
            <div class="space-y-2 mb-4 max-h-72 overflow-y-auto pr-1">
              <div v-for="item in cart" :key="item.id" class="flex items-center justify-between border rounded-lg px-3 py-2">
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-semibold truncate">{{ item.name }}</p>
                  <p class="text-xs text-gray-500">Rp {{ formatPrice(item.price) }}</p>
                </div>
                <div class="flex items-center space-x-2">
                  <button @click="decreaseQuantity(item)" class="w-7 h-7 bg-gray-100 text-gray-700 rounded hover:bg-gray-200">-</button>
                  <span class="text-sm w-6 text-center">{{ item.quantity }}</span>
                  <button @click="increaseQuantity(item)" class="w-7 h-7 bg-gray-100 text-gray-700 rounded hover:bg-gray-200">+</button>
                </div>
              </div>
            </div>

            <!-- Cart Summary -->
            <div class="border-t pt-4">
              <div class="flex justify-between mb-2">
                <span>Subtotal:</span>
                <span>Rp {{ formatPrice(cartSubtotal) }}</span>
              </div>
              <div class="flex justify-between mb-2">
                <span>Pajak (10%):</span>
                <span>Rp {{ formatPrice(tax) }}</span>
              </div>
              <div class="flex justify-between font-bold text-lg">
                <span>Total:</span>
                <span>Rp {{ formatPrice(total) }}</span>
              </div>
            </div>

            <!-- Customer Info -->
            <div class="mt-4 space-y-2">
              <input v-model="customerName" type="text" placeholder="Nama Pelanggan" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
              <input v-model="customerPhone" type="text" placeholder="No. Telepon" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Payment Method -->
            <div class="mt-4">
              <label class="block text-sm font-medium mb-2">Metode Pembayaran:</label>
              <select v-model="paymentMethod" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <option value="cash">Tunai</option>
                <option value="card">Kartu</option>
                <option value="qris">QRIS</option>
                <option value="gopay">GoPay</option>
                <option value="shopeepay">ShopeePay</option>
              </select>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 space-y-2">
              <button @click="clearCart" class="w-full bg-gray-100 text-gray-800 py-2 rounded-lg hover:bg-gray-200">
                Kosongkan Keranjang
              </button>
              <button @click="processOrder" :disabled="cart.length === 0" class="w-full bg-gradient-to-r from-indigo-600 to-blue-600 text-white py-2 rounded-lg hover:from-indigo-700 hover:to-blue-700 disabled:bg-gray-400">
                Proses Pesanan
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Pembayaran Modal (QR/Deeplink) -->
  <div v-if="showQrisModal" class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-bold text-gray-900">Bayar dengan {{ paymentTitle }}</h3>
      </div>
      <div class="px-6 py-5 text-center">
        <div class="mb-4">
          <img v-if="qrisQrUrl" :src="qrisQrUrl" alt="QR" class="mx-auto w-60 h-60 object-contain" />
          <img v-else-if="qrisQrString" :src="qrImageFromString()" alt="QR" class="mx-auto w-60 h-60 object-contain" />
          <div v-else class="text-sm text-gray-500">Menyiapkan instruksi...</div>
        </div>
        <div v-if="deeplinkUrl" class="mb-4">
          <a :href="deeplinkUrl" target="_blank" class="inline-block px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">Buka Aplikasi</a>
        </div>
        <p class="text-sm text-gray-600">Ikuti instruksi di aplikasi pembayaran Anda.</p>
        <p v-if="paymentMethod === 'gopay'" class="text-xs text-gray-500 mt-1">QR yang ditampilkan adalah QRIS dan dapat di-scan menggunakan GoPay.</p>
        <p class="text-xs text-gray-500 mt-1">Halaman akan otomatis menutup setelah pembayaran terkonfirmasi.</p>
      </div>
      <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
        <button @click="closeQrisModal" class="px-4 py-2 text-sm rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700">Tutup</button>
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
      cart: [],
      customerName: '',
      customerPhone: '',
      processedBy: '',
      paymentMethod: 'cash',
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
      if (!this.selectedCategory) {
        return this.products;
      }
      return this.products.filter(product => product.category_id == this.selectedCategory);
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
    }
  }
}
</script>
