<template>
  <div>
    <!-- Desktop Layout -->
    <PosAppISeller 
      v-if="!isMobile"
      :products="products"
      :categories="categories"
      :cart="cart"
      :customer-name="customerName"
      :customer-phone="customerPhone"
      :processed-by="processedBy"
      :payment-method="paymentMethod"
      :search-query="searchQuery"
      :selected-category="selectedCategory"
      @update:cart="updateCart"
      @update:customer-name="updateCustomerName"
      @update:customer-phone="updateCustomerPhone"
      @update:payment-method="updatePaymentMethod"
      @update:search-query="updateSearchQuery"
      @update:selected-category="updateSelectedCategory"
      @process-order="processOrder"
      @logout="logout"
    />
    
    <!-- Mobile Layout -->
    <PosAppMobile 
      v-else
      :products="products"
      :categories="categories"
      :cart="cart"
      :customer-name="customerName"
      :customer-phone="customerPhone"
      :processed-by="processedBy"
      :payment-method="paymentMethod"
      :search-query="searchQuery"
      :selected-category="selectedCategory"
      @update:cart="updateCart"
      @update:customer-name="updateCustomerName"
      @update:customer-phone="updateCustomerPhone"
      @update:payment-method="updatePaymentMethod"
      @update:search-query="updateSearchQuery"
      @update:selected-category="updateSelectedCategory"
      @process-order="processOrder"
      @logout="logout"
    />
  </div>
</template>

<script>
import PosAppISeller from './PosAppISeller.vue';
import PosAppMobile from './PosAppMobile.vue';
import axios from 'axios';

export default {
  name: 'PosAppResponsive',
  components: {
    PosAppISeller,
    PosAppMobile
  },
  data() {
    return {
      isMobile: false,
      products: [],
      categories: [],
      cart: [],
      customerName: '',
      customerPhone: '',
      processedBy: '',
      paymentMethod: 'cash',
      searchQuery: '',
      selectedCategory: ''
    }
  },
  mounted() {
    this.checkScreenSize();
    window.addEventListener('resize', this.checkScreenSize);
    this.loadData();
  },
  beforeUnmount() {
    window.removeEventListener('resize', this.checkScreenSize);
  },
  methods: {
    checkScreenSize() {
      this.isMobile = window.innerWidth < 768; // md breakpoint
    },
    async loadData() {
      await this.loadProducts();
      await this.loadCategories();
      await this.loadKasirName();
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
    async loadProducts() {
      try {
        const response = await axios.get('/pos/products');
        this.products = response.data;
      } catch (error) {
        console.error('Error loading products:', error);
        alert('Error loading products: ' + (error.response?.data?.message || error.message));
      }
    },
    async loadCategories() {
      try {
        const response = await axios.get('/pos/categories');
        this.categories = response.data;
      } catch (error) {
        console.error('Error loading categories:', error);
        alert('Error loading categories: ' + (error.response?.data?.message || error.message));
      }
    },
    updateCart(newCart) {
      this.cart = newCart;
    },
    updateCustomerName(name) {
      this.customerName = name;
    },
    updateCustomerPhone(phone) {
      this.customerPhone = phone;
    },
    updatePaymentMethod(method) {
      this.paymentMethod = method;
    },
    updateSearchQuery(query) {
      this.searchQuery = query;
    },
    updateSelectedCategory(category) {
      this.selectedCategory = category;
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
          const paymentTypeToSend = this.paymentMethod === 'gopay' ? 'qris' : this.paymentMethod;
          const res = await axios.post('/api/pos/orders/charge', { ...orderData, payment_type: paymentTypeToSend });
          const data = res.data || {};
          // Handle QRIS modal in child components
          this.$refs.posComponent?.showQrisModal(data);
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
    resetAfterOrder() {
      this.cart = [];
      this.customerName = '';
      this.customerPhone = '';
      this.paymentMethod = 'cash';
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
    }
  }
}
</script>
