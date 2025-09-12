import axios from 'axios';

/**
 * Service class for PosAppISeller API calls
 */
class PosAppISellerService {
  /**
   * Load products from API
   */
  static async loadProducts() {
    try {
      console.log('Loading products...');
      const response = await axios.get('/api/pos/products');
      console.log('Products response:', response.data);
      return response.data;
    } catch (error) {
      console.error('Error loading products:', error);
      console.error('Error details:', error.response?.data);
      throw new Error('Error loading products: ' + (error.response?.data?.message || error.message));
    }
  }

  /**
   * Load categories from API
   */
  static async loadCategories() {
    try {
      console.log('Loading categories...');
      const response = await axios.get('/api/pos/categories');
      console.log('Categories response:', response.data);
      return response.data;
    } catch (error) {
      console.error('Error loading categories:', error);
      console.error('Error details:', error.response?.data);
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
      console.error('Error loading members:', error);
      throw new Error('Gagal memuat data member: ' + (error.response?.data?.message || error.message));
    }
  }

  /**
   * Add new member
   */
  static async addMember(memberData) {
    try {
      await axios.post('/api/kasir/members', memberData);
    } catch (error) {
      const msg = error?.response?.data?.message || error?.message || 'Gagal menambah member';
      throw new Error(msg);
    }
  }

  /**
   * Update member
   */
  static async updateMember(memberId, memberData) {
    try {
      await axios.put(`/api/kasir/members/${memberId}`, memberData);
    } catch (error) {
      const msg = error?.response?.data?.message || error?.message || 'Gagal mengupdate member';
      throw new Error(msg);
    }
  }

  /**
   * Delete member
   */
  static async deleteMember(memberId) {
    try {
      await axios.delete(`/api/kasir/members/${memberId}`);
    } catch (error) {
      const msg = error?.response?.data?.message || error?.message || 'Gagal menghapus member';
      throw new Error(msg);
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
      console.error('Error loading draft orders:', error);
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
      console.error('Error deleting draft order:', error);
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
      console.error('Error checking payment status:', error);
      throw new Error('Gagal mengecek status pembayaran.');
    }
  }
}

export default PosAppISellerService;
