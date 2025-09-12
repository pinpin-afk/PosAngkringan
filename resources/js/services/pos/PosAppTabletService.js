import axios from 'axios';

/**
 * Service class for PosAppTablet API calls
 */
class PosAppTabletService {
  /**
   * Load products from API
   */
  static async loadProducts() {
    try {
      const response = await axios.get('/api/kasir/products');
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
      const response = await axios.get('/api/kasir/categories');
      return response.data;
    } catch (error) {
      throw new Error('Error loading categories: ' + (error.response?.data?.message || error.message));
    }
  }

  /**
   * Load kasir name from API
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
}

export default PosAppTabletService;
