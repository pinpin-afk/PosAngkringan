import PosAppTabletService from '../../../services/pos/PosAppTabletService.js';
import PosAppTabletUtils from '../../../services/pos/utils/PosAppTabletUtils.js';

/**
 * Main logic for PosAppTablet component
 */
export default {
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
      networkStatus: 'online',
      // Draft orders
      showDraftOrdersModal: false,
      draftOrders: []
    }
  },

  computed: {
    filteredProducts() {
      return PosAppTabletUtils.filterProducts(this.products, this.selectedCategory, this.searchQuery);
    },

    cartSubtotal() {
      return PosAppTabletUtils.calculateCartSubtotal(this.cart);
    },

    tax() {
      return PosAppTabletUtils.calculateTax(this.cartSubtotal);
    },

    cartTotal() {
      return PosAppTabletUtils.calculateCartTotal(this.cartSubtotal, this.tax);
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
    // Draft orders methods
    async loadDraftOrders() {
      try {
        const res = await fetch('/api/kasir/orders/drafts');
        const data = await res.json();
        this.draftOrders = Array.isArray(data) ? data : [];
      } catch (e) {
        this.draftOrders = [];
      }
    },

    async deleteDraft(draftId) {
      if (!confirm('Yakin ingin menghapus draft order ini?')) return;
      try {
        await fetch(`/api/kasir/orders/drafts/${draftId}`, { method: 'DELETE' });
        await this.loadDraftOrders();
        alert('Draft order berhasil dihapus');
      } catch (e) {
        alert('Gagal menghapus draft order');
      }
    },

    editDraft(draft) {
      // Convert order items back to cart format
      this.cart = (draft.order_items || []).map(item => ({
        id: item.product_id,
        name: item.product?.name || 'Unknown Product',
        price: item.price,
        quantity: item.quantity
      }));

      // Close modal
      this.showDraftOrdersModal = false;

      alert('Draft order dimuat ke form. Silakan lanjutkan dengan memilih metode pembayaran.');
    },
    // UI methods
    toggleSidebar() {
      this.showTopSheet = !this.showTopSheet;
    },

    // Navigation methods
    logout() {
      if (confirm('Apakah Anda yakin ingin logout?')) {
        window.location.href = '/kasir/logout';
      }
    },

    goToReport() {
      window.location.href = '/kasir/report';
    },

    // Member management methods
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

    // Data loading methods
    async loadProducts() {
      try {
        this.products = await PosAppTabletService.loadProducts();
      } catch (error) {
        console.error('Error loading products:', error);
      }
    },

    async loadCategories() {
      try {
        this.categories = await PosAppTabletService.loadCategories();
      } catch (error) {
        console.error('Error loading categories:', error);
      }
    },

    async loadKasirName() {
      try {
        this.processedBy = await PosAppTabletService.loadKasirName();
      } catch (error) {
        console.error('Error loading kasir name:', error);
      }
    },

    // Dark mode methods
    loadDarkModePreference() {
      this.isDarkMode = PosAppTabletUtils.loadDarkModePreference();
    },

    toggleDarkMode() {
      this.isDarkMode = !this.isDarkMode;
      PosAppTabletUtils.saveDarkModePreference(this.isDarkMode);
    },

    // Cart management methods
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

    // Payment methods
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

    // Utility methods
    formatPrice(price) {
      return PosAppTabletUtils.formatPrice(price);
    },

    // Storage methods
    saveDraftToStorage() {
      PosAppTabletUtils.saveDraftToStorage(this.cart, this.selectedMember);
    },

    loadDraftFromStorage() {
      const draft = PosAppTabletUtils.loadDraftFromStorage();
      this.cart = draft.cart;
      this.selectedMember = draft.selectedMember;
    }
  }
};
