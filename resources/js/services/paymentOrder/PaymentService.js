/**
 * Payment Service - Handles all payment-related API calls
 */

class PaymentService {
  /**
   * Create a new order
   */
  static async createOrder(orderData) {
    const response = await axios.post('/api/kasir/orders', orderData);
    return response.data;
  }

  /**
   * Update an existing order
   */
  static async updateOrder(orderId, orderData) {
    const response = await axios.put(`/api/kasir/orders/${orderId}`, orderData);
    return response.data;
  }

  /**
   * Get order status
   */
  static async getOrderStatus(orderId) {
    const response = await axios.get(`/api/kasir/orders/${orderId}/status`);
    return response.data;
  }

  /**
   * Cancel an order
   */
  static async cancelOrder(orderId) {
    const response = await axios.post(`/api/kasir/orders/${orderId}/cancel`);
    return response.data;
  }

  /**
   * Create QRIS charge
   */
  static async createQrisCharge(orderData) {
    const response = await axios.post('/api/kasir/orders/charge', { 
      ...orderData, 
      payment_type: 'qris' 
    });
    return response.data;
  }

  /**
   * Create Midtrans snap token
   */
  static async createMidtransSnapToken(orderId) {
    const response = await axios.post('/api/kasir/midtrans/snap-token', {
      order_id: orderId
    });
    return response.data;
  }

  /**
   * Verify transfer payment
   */
  static async verifyTransfer(orderId, verificationData) {
    const response = await axios.post(`/api/kasir/orders/${orderId}/verify-transfer`, verificationData);
    return response.data;
  }
}

export default PaymentService;
