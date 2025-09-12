/**
 * Draft Order Service - Handles draft order management
 */

class DraftOrderService {
  /**
   * Get all draft orders for current cashier
   */
  static async getDrafts() {
    const response = await axios.get('/api/kasir/orders/drafts');
    return response.data;
  }

  /**
   * Delete a draft order
   */
  static async deleteDraft(draftId) {
    const response = await axios.delete(`/api/kasir/orders/drafts/${draftId}`);
    return response.data;
  }

  /**
   * Update a draft order
   */
  static async updateDraft(draftId, draftData) {
    const response = await axios.put(`/api/kasir/orders/drafts/${draftId}`, draftData);
    return response.data;
  }

  /**
   * Cancel existing draft orders when selecting new payment method
   */
  static async cancelExistingDrafts() {
    const draftId = localStorage.getItem('pos_draft_order_id');
    if (draftId) {
      try {
        await axios.put(`/api/kasir/orders/${draftId}`, { status: 'cancelled' });
        localStorage.removeItem('pos_draft_order_id');
      } catch (error) {
        console.error('Error cancelling draft order:', error);
      }
    }
  }
}

export default DraftOrderService;
