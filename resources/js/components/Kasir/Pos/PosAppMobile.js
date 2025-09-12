import PosAppMobileService from '../../../services/pos/PosAppMobileService.js';
import PosAppMobileUtils from '../../../services/pos/utils/PosAppMobileUtils.js';

/**
 * Main logic for PosAppMobile component
 */
export default {
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
      selectedMember: null,
      // Draft orders
      showDraftOrdersModal: false,
      draftOrders: []
    }
  },

  computed: {
    filteredProducts() {
      return PosAppMobileUtils.filterProducts(this.products, this.selectedCategory, this.searchQuery);
    },

    cartSubtotal() {
      return PosAppMobileUtils.calculateCartSubtotal(this.cart);
    },

    tax() {
      return PosAppMobileUtils.calculateTax(this.cartSubtotal);
    },

    total() {
      return PosAppMobileUtils.calculateTotal(this.cartSubtotal, this.tax);
    },

    pointsEarned() {
      return PosAppMobileUtils.calculatePointsEarned(this.cartSubtotal, !!this.selectedMember);
    },

    finalTotal() {
      return this.total;
    },

    memberBreadcrumbs() {
      return PosAppMobileUtils.createMemberBreadcrumbs();
    },

    filteredMembers() {
      return PosAppMobileUtils.filterMembers(this.members, this.memberSearchQuery);
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
    // Data loading methods
    async loadKasirName() {
      try {
        this.processedBy = await PosAppMobileService.loadKasirName();
      } catch (e) {
        // ignore if not logged in as kasir
      }
    },

    async loadProducts() {
      try {
        this.products = await PosAppMobileService.loadProducts();
      } catch (error) {
        alert(error.message);
      }
    },

    async loadCategories() {
      try {
        this.categories = await PosAppMobileService.loadCategories();
      } catch (error) {
        alert(error.message);
      }
    },

    // Cart management methods
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

    // Utility methods
    formatPrice(price) {
      return PosAppMobileUtils.formatPrice(price);
    },

    formatDateTime(dateString) {
      return PosAppMobileUtils.formatDateTime(dateString);
    },

    // UI methods
    toggleDarkMode() {
      this.isDarkMode = !this.isDarkMode;
      PosAppMobileUtils.saveDarkModePreference(this.isDarkMode);
    },

    loadDarkModePreference() {
      this.isDarkMode = PosAppMobileUtils.loadDarkModePreference();
    },

    // Logout method
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

    // Member management methods
    async loadMembers() {
      this.memberLoading = true;
      try {
        this.members = await PosAppMobileService.loadMembers();
      } catch (error) {
        alert(error.message);
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

    // Navigation methods
    handleBreadcrumbNavigate(path) {
      if (path === '#') {
        this.showMemberManagement = false;
      }
    },

    goToReport() {
      window.location.href = '/kasir/report';
    },

    // Payment methods
    goToPayment() {
      if (this.cart.length === 0) {
        alert('Keranjang kosong!');
        return;
      }

      // Save cart and customer data to localStorage
      PosAppMobileUtils.saveCartAndCustomerData(
        this.cart,
        {
          customerName: this.customerName,
          customerPhone: this.customerPhone,
          tableNumber: this.tableNumber,
          selectedMember: this.selectedMember
        },
        this.pointsEarned,
        this.finalTotal
      );

      // Redirect to payment page
      window.location.href = '/kasir/payment';
    },

    // QRIS methods
    startQrisPolling() {
      this.stopQrisPolling();
      this.qrisPollTimer = setInterval(async () => {
        try {
          const res = await PosAppMobileService.checkPaymentStatus(this.qrisOrderId);
          if (res.status === 'settlement') {
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

    async checkPaymentStatus() {
      try {
        const res = await PosAppMobileService.checkPaymentStatus(this.qrisOrderId);
        if (res.status === 'settlement') {
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
        alert(error.message);
      }
    },

    resetAfterOrder() {
      this.cart = [];
      this.customerName = '';
      this.customerPhone = '';
      this.paymentMethod = 'cash';
      this.activeTab = 'products';
    },

    // Draft orders methods
    async loadDraftOrders() {
      try {
        this.draftOrders = await PosAppMobileService.loadDraftOrders();
      } catch (error) {
        console.error('Error loading draft orders:', error);
        this.draftOrders = [];
      }
    },

    async deleteDraft(draftId) {
      if (!confirm('Yakin ingin menghapus draft order ini?')) return;
      
      try {
        await PosAppMobileService.deleteDraft(draftId);
        await this.loadDraftOrders(); // Refresh the list
        alert('Draft order berhasil dihapus');
      } catch (error) {
        alert(error.message);
      }
    },

    editDraft(draft) {
      // Load draft data into current form
      this.customerName = draft.customer_name || '';
      this.customerPhone = draft.customer_phone || '';
      this.tableNumber = draft.table_number || '';
      
      // Convert order items back to cart format
      this.cart = draft.order_items?.map(item => ({
        id: item.product_id,
        name: item.product?.name || 'Unknown Product',
        price: item.price,
        quantity: item.quantity,
        stock: item.product?.stock || 0
      })) || [];
      
      // Save cart to localStorage
      PosAppMobileUtils.saveCartToStorage(this.cart);
      PosAppMobileUtils.saveCustomerToStorage({
        customerName: this.customerName,
        customerPhone: this.customerPhone,
        tableNumber: this.tableNumber,
        selectedMember: this.selectedMember
      });
      
      // Switch to cart tab
      this.activeTab = 'cart';
      
      // Delete the draft since we're editing it
      this.deleteDraft(draft.id);
      
      alert('Draft order dimuat ke form. Silakan lanjutkan dengan memilih metode pembayaran.');
    }
  },

  beforeUnmount() {
    this.stopQrisPolling();
  }
};
