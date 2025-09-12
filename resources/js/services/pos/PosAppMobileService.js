import axios from 'axios';

/**
 * Service class for PosAppMobile API calls
 */
class PosAppMobileService {
  /**
   * Load products from API
   */
  static async loadProducts() {
    try {
      const response = await axios.get('/api/pos/products');
      return response.data;
    } catch (error) {
      throw new Error('Error loading products: ' + (error.response?.data?.message || error.message));
    }
  }

  /**
   * Load categories from API
   */
  static async loadCategories() {
    try {
      const response = await axios.get('/api/pos/categories');
      return response.data;
    } catch (error) {
      throw new Error('Error loading categories: ' + (error.response?.data?.message || error.message));
    }
  }

  /**
   * Load kasir name from session
   */
  static async loadKasirName() {
    try {
      const res = await axios.get('/check-kasir-session');
      const data = res?.data || {};
      const user = data.user || data;
      if (user && (user.name || user.username)) {
        return user.name || user.username;
      }
      return null;
    } catch (e) {
      // ignore if not logged in as kasir
      return null;
    }
  }

  /**
   * Load members from API
   */
  static async loadMembers() {
    try {
      const response = await axios.get('/api/kasir/members');
      return response.data || [];
    } catch (error) {
      throw new Error('Gagal memuat data member: ' + (error.response?.data?.message || error.message));
    }
  }

  /**
   * Load draft orders
   */
  static async loadDraftOrders() {
    try {
      const response = await axios.get('/api/kasir/orders/drafts');
      return response.data || [];
    } catch (error) {
      return [];
    }
  }

  /**
   * Delete draft order
   */
  static async deleteDraft(draftId) {
    try {
      await axios.delete(`/api/kasir/orders/drafts/${draftId}`);
    } catch (error) {
      throw new Error('Gagal menghapus draft order');
    }
  }

  /**
   * Check payment status
   */
  static async checkPaymentStatus(orderId) {
    try {
      const res = await axios.get(`/api/pos/orders/${orderId}/status`);
      return res.data;
    } catch (error) {
      throw new Error('Gagal mengecek status pembayaran.');
    }
  }
}

export default PosAppMobileService;
