/**
 * Utility functions for PosAppTablet
 */
class PosAppTabletUtils {
  /**
   * Format price to Indonesian Rupiah
   */
  static formatPrice(price) {
    return new Intl.NumberFormat('id-ID').format(price);
  }

  /**
   * Load dark mode preference
   */
  static loadDarkModePreference() {
    const saved = localStorage.getItem('darkMode');
    return saved ? JSON.parse(saved) : false;
  }

  /**
   * Save dark mode preference
   */
  static saveDarkModePreference(isDarkMode) {
    localStorage.setItem('darkMode', JSON.stringify(isDarkMode));
  }

  /**
   * Filter products by category and search query
   */
  static filterProducts(products, selectedCategory, searchQuery) {
    let filtered = products;
    
    // Filter by category
    if (selectedCategory) {
      filtered = filtered.filter(product => product.category_id == selectedCategory);
    }
    
    // Filter by search query
    if (searchQuery) {
      const query = searchQuery.toLowerCase();
      filtered = filtered.filter(product => 
        product.name.toLowerCase().includes(query) || 
        product.description.toLowerCase().includes(query)
      );
    }
    
    return filtered;
  }

  /**
   * Calculate cart subtotal
   */
  static calculateCartSubtotal(cart) {
    return cart.reduce((total, item) => total + (item.price * item.quantity), 0);
  }

  /**
   * Calculate tax (10%)
   */
  static calculateTax(subtotal) {
    return subtotal * 0.1;
  }

  /**
   * Calculate cart total
   */
  static calculateCartTotal(subtotal, tax) {
    return subtotal + tax;
  }

  /**
   * Save draft to localStorage
   */
  static saveDraftToStorage(cart, selectedMember) {
    const draft = {
      cart: cart,
      selectedMember: selectedMember
    };
    localStorage.setItem('posDraft', JSON.stringify(draft));
  }

  /**
   * Load draft from localStorage
   */
  static loadDraftFromStorage() {
    const draft = localStorage.getItem('posDraft');
    if (draft) {
      const parsed = JSON.parse(draft);
      return {
        cart: parsed.cart || [],
        selectedMember: parsed.selectedMember || null
      };
    }
    return {
      cart: [],
      selectedMember: null
    };
  }
}

export default PosAppTabletUtils;
