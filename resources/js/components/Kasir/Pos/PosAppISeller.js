import PosAppISellerService from '../../../services/pos/PosAppISellerService.js';
import PosAppISellerUtils from '../../../services/pos/utils/PosAppISellerUtils.js';

/**
 * Main logic for PosAppISeller component
 */
export default {
  data() {
    return {
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
      // Member management (reusable modal only)
      showMemberCrudModal: false,
      memberSearchQuery: '',
      memberLoading: false,
      members: [],
      selectedMember: null,
      qrisPollTimer: null,
      deeplinkUrl: null,
      showTopSheet: false,
      // Draft orders
      showDraftOrdersModal: false,
      draftOrders: []
    }
  },

  computed: {
    filteredProducts() {
      return PosAppISellerUtils.filterProducts(this.products, this.selectedCategory, this.searchQuery);
    },

    cartSubtotal() {
      return PosAppISellerUtils.calculateCartSubtotal(this.cart);
    },

    tax() {
      return PosAppISellerUtils.calculateTax(this.cartSubtotal);
    },

    total() {
      return PosAppISellerUtils.calculateTotal(this.cartSubtotal, this.tax);
    },

    pointsEarned() {
      return PosAppISellerUtils.calculatePointsEarned(this.cartSubtotal, !!this.selectedMember);
    },

    finalTotal() {
      return this.total;
    },

    memberBreadcrumbs() {
      return PosAppISellerUtils.createMemberBreadcrumbs();
    },

    filteredMembers() {
      return PosAppISellerUtils.filterMembers(this.members, this.memberSearchQuery);
    },

    paymentTitle() {
      return PosAppISellerUtils.getPaymentTitle(this.paymentMethod);
    }
  },

  async mounted() {
    await this.loadProducts();
    await this.loadCategories();
    await this.loadKasirName();
    this.loadDarkModePreference();
    this.loadDraftFromStorage();
  },

  watch: {},

  methods: {
    // Navigation methods
    goToReport() {
      window.location.href = '/kasir/report';
    },

    // Member management methods
    openMemberCrudModal() {
      this.showMemberCrudModal = true;
    },

    handleMemberSelected(member) {
      this.selectMember(member);
      this.showMemberCrudModal = false;
    },

    async handleMemberCrudClosed() {
      this.showMemberCrudModal = false;
      await this.loadMembers();
    },

    // Data loading methods
    loadDraftFromStorage() {
      try {
        this.cart = PosAppISellerUtils.loadDraftFromStorage();
        const customerData = PosAppISellerUtils.loadCustomerFromStorage();
        this.customerName = customerData.customerName;
        this.customerPhone = customerData.customerPhone;
        this.tableNumber = customerData.tableNumber;
        this.selectedMember = customerData.selectedMember;
      } catch (_) {}
    },

    async loadKasirName() {
      try {
        this.processedBy = await PosAppISellerService.loadKasirName();
      } catch (e) {
        // ignore if not logged in as kasir
      }
    },

    async loadProducts() {
      try {
        this.products = await PosAppISellerService.loadProducts();
      } catch (error) {
        alert(error.message);
      }
    },

    async loadCategories() {
      try {
        this.categories = await PosAppISellerService.loadCategories();
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
        existingItem.quantity++;
      } else {
        this.cart.push({
          id: product.id,
          name: product.name,
          price: product.price,
          quantity: 1,
          stock: product.stock
        });
      }
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

    // Member management methods
    async loadMembers() {
      this.memberLoading = true;
      try {
        this.members = await PosAppISellerService.loadMembers();
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
      this.customerName = '';
      this.customerPhone = '';
    },

    async addMemberFlow() {
      try {
        const name = prompt('Nama member:');
        if (!name) return;
        const phone = prompt('Nomor telepon:');
        if (!phone) return;
        
        const memberData = {
          name: name,
          phone: phone,
          email: '',
          address: '',
          member_id: 'M' + Date.now()
        };
        
        await PosAppISellerService.addMember(memberData);
        alert('Member berhasil ditambahkan');
        await this.loadMembers();
      } catch (error) {
        alert(error.message);
      }
    },

    async editMember(member) {
      try {
        const name = prompt('Nama member:', member.name);
        if (!name) return;
        const phone = prompt('Nomor telepon:', member.phone);
        if (!phone) return;
        
        const memberData = {
          name: name,
          phone: phone,
          email: member.email || '',
          address: member.address || ''
        };
        
        await PosAppISellerService.updateMember(member.id, memberData);
        alert('Member berhasil diupdate');
        await this.loadMembers();
      } catch (error) {
        alert(error.message);
      }
    },

    async deleteMember(member) {
      try {
        if (!confirm(`Yakin ingin menghapus member ${member.name}?`)) return;
        await PosAppISellerService.deleteMember(member.id);
        alert('Member berhasil dihapus');
        await this.loadMembers();
      } catch (error) {
        alert(error.message);
      }
    },

    // Navigation methods
    handleBreadcrumbNavigate(path) {
      if (path === '#') {
        this.showMemberManagement = false;
      }
    },

    // Payment methods
    goToPayment() {
      if (this.cart.length === 0) {
        alert('Keranjang kosong!');
        return;
      }

      // Save current state to localStorage
      PosAppISellerUtils.saveCartToStorage(this.cart);
      PosAppISellerUtils.saveCustomerToStorage({
        customerName: this.customerName,
        customerPhone: this.customerPhone,
        tableNumber: this.tableNumber,
        selectedMember: this.selectedMember
      });

      // Redirect to payment page
      window.location.href = '/kasir/payment';
    },

    // QRIS methods
    startQrisPolling() {
      this.stopQrisPolling();
      this.qrisPollTimer = setInterval(async () => {
        try {
          const res = await PosAppISellerService.checkPaymentStatus(this.qrisOrderId);
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

    async checkPaymentStatus() {
      try {
        const res = await PosAppISellerService.checkPaymentStatus(this.qrisOrderId);
        if (res.status === 'settlement') {
          this.showQrisModal = false;
          this.stopQrisPolling();
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
    },

    // Draft orders methods
    async loadDraftOrders() {
      try {
        this.draftOrders = await PosAppISellerService.loadDraftOrders();
      } catch (error) {
        console.error('Error loading draft orders:', error);
        this.draftOrders = [];
      }
    },

    async deleteDraft(draftId) {
      if (!confirm('Yakin ingin menghapus draft order ini?')) return;
      
      try {
        await PosAppISellerService.deleteDraft(draftId);
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
      PosAppISellerUtils.saveCartToStorage(this.cart);
      PosAppISellerUtils.saveCustomerToStorage({
        customerName: this.customerName,
        customerPhone: this.customerPhone,
        tableNumber: this.tableNumber,
        selectedMember: this.selectedMember
      });
      
      // Delete the draft since we're editing it
      this.deleteDraft(draft.id);
      
      alert('Draft order dimuat ke form. Silakan lanjutkan dengan memilih metode pembayaran.');
    },

    // Utility methods
    formatPrice(price) {
      return PosAppISellerUtils.formatPrice(price);
    },

    formatDateTime(dateString) {
      return PosAppISellerUtils.formatDateTime(dateString);
    },

    qrImageFromString() {
      return PosAppISellerUtils.generateQrImageFromString(this.qrisQrString);
    },

    // UI methods
    toggleDarkMode() {
      this.isDarkMode = !this.isDarkMode;
      PosAppISellerUtils.saveDarkModePreference(this.isDarkMode);
    },

    loadDarkModePreference() {
      this.isDarkMode = PosAppISellerUtils.loadDarkModePreference();
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
    }
  },

  beforeUnmount() {
    this.stopQrisPolling();
  }
};
