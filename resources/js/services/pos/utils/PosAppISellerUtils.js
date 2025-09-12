/**
 * Utility functions for PosAppISeller
 */
class PosAppISellerUtils {
  /**
   * Format price to Indonesian Rupiah
   */
  static formatPrice(price) {
    return new Intl.NumberFormat('id-ID').format(price);
  }

  /**
   * Format date time to Indonesian format
   */
  static formatDateTime(dateString) {
    const date = new Date(dateString);
    return date.toLocaleString('id-ID', {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    });
  }

  /**
   * Load draft data from localStorage
   */
  static loadDraftFromStorage() {
    try {
      const savedCart = localStorage.getItem('pos_cart');
      if (savedCart) {
        return JSON.parse(savedCart);
      }
    } catch (_) {}
    return [];
  }

  /**
   * Load customer data from localStorage
   */
  static loadCustomerFromStorage() {
    const savedCustomerName = localStorage.getItem('pos_customer_name');
    const savedCustomerPhone = localStorage.getItem('pos_customer_phone');
    const savedTableNumber = localStorage.getItem('pos_table_number');
    const savedSelectedMember = localStorage.getItem('pos_selected_member');

    return {
      customerName: savedCustomerName || '',
      customerPhone: savedCustomerPhone || '',
      tableNumber: savedTableNumber || '',
      selectedMember: savedSelectedMember ? JSON.parse(savedSelectedMember) : null
    };
  }

  /**
   * Save cart to localStorage
   */
  static saveCartToStorage(cart) {
    localStorage.setItem('pos_cart', JSON.stringify(cart));
  }

  /**
   * Save customer data to localStorage
   */
  static saveCustomerToStorage(customerData) {
    if (customerData.customerName) {
      localStorage.setItem('pos_customer_name', customerData.customerName);
    }
    if (customerData.customerPhone) {
      localStorage.setItem('pos_customer_phone', customerData.customerPhone);
    }
    if (customerData.tableNumber) {
      localStorage.setItem('pos_table_number', customerData.tableNumber);
    }
    if (customerData.selectedMember) {
      localStorage.setItem('pos_selected_member', JSON.stringify(customerData.selectedMember));
    }
  }

  /**
   * Load dark mode preference
   */
  static loadDarkModePreference() {
    const saved = localStorage.getItem('darkMode');
    return saved === 'true';
  }

  /**
   * Save dark mode preference
   */
  static saveDarkModePreference(isDarkMode) {
    localStorage.setItem('darkMode', isDarkMode);
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
   * Filter members by search query
   */
  static filterMembers(members, searchQuery) {
    if (!searchQuery) return members;
    
    const query = searchQuery.toLowerCase();
    return members.filter(member => 
      member.member_id.toLowerCase().includes(query) ||
      member.name.toLowerCase().includes(query) ||
      member.phone.toLowerCase().includes(query) ||
      (member.email && member.email.toLowerCase().includes(query))
    );
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
   * Calculate total
   */
  static calculateTotal(subtotal, tax) {
    return subtotal + tax;
  }

  /**
   * Calculate points earned (2% for members)
   */
  static calculatePointsEarned(subtotal, hasMember) {
    if (hasMember) {
      return Math.floor(subtotal * 0.02);
    }
    return 0;
  }

  /**
   * Get payment title
   */
  static getPaymentTitle(paymentMethod) {
    const map = { qris: 'QRIS', gopay: 'GoPay', shopeepay: 'ShopeePay' };
    return map[paymentMethod] || 'Pembayaran';
  }

  /**
   * Generate QR image from string
   */
  static generateQrImageFromString(qrString) {
    if (!qrString) return '';
    return 'https://api.qrserver.com/v1/create-qr-code/?size=240x240&data=' + encodeURIComponent(qrString);
  }

  /**
   * Create breadcrumb items for member management
   */
  static createMemberBreadcrumbs() {
    return [
      {
        label: 'POS',
        path: '#',
        icon: 'svg'
      },
      {
        label: 'Kelola Member',
        path: '#'
      }
    ];
  }
}

export default PosAppISellerUtils;
