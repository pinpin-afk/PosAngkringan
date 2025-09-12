/**
 * PaymentView Component Logic
 */

import PaymentService from '../../../services/paymentOrder/PaymentService.js';
import DraftOrderService from '../../../services/paymentOrder/DraftOrderService.js';

export default {
  data() {
    return {
      cart: [],
      customerName: '',
      customerPhone: '',
      tableNumber: '',
      selectedMember: null,
      pointsEarned: 0,
      finalTotal: 0,
      paymentMethod: '',
      processedBy: '',
      isDarkMode: false,
      loading: false,
      // Cash payment
      cashAmount: '',
      showCashModal: false,
      // Success modal
      showSuccessModal: false,
      successData: null,
      // Midtrans state
      showMidtransModal: false,
      midtransOrderId: null,
      midtransSnapToken: null,
    // Draft orders
    draftOrders: [],
    showDraftOrdersModal: false
    }
  },

  computed: {
    cartSubtotal() {
      return this.cart.reduce((total, item) => total + (item.price * item.quantity), 0);
    },
    tax() {
      return this.cartSubtotal * 0.1;
    },
    total() {
      return this.cartSubtotal + this.tax;
    },
    displayTotal() {
      return this.finalTotal > 0 ? this.finalTotal : this.total;
    },
    changeAmount() {
      const cash = parseFloat(this.cashAmount) || 0;
      return Math.max(0, cash - this.displayTotal);
    },
    isCashValid() {
      const cash = parseFloat(this.cashAmount) || 0;
      return cash >= this.displayTotal;
    },
    pointsEarned() {
      if (this.selectedMember && this.cartSubtotal > 0) {
        return Math.floor(this.cartSubtotal * 0.02);
      }
      return 0;
    }
  },

  async mounted() {
    // Load cart data from localStorage or props
    this.loadCartData();
    await this.loadKasirName();
    this.loadDarkModePreference();
    // Load draft orders
    await this.loadDraftOrders();
  },

  methods: {
    loadCartData() {
      // Load cart from localStorage or get from parent component
      const savedCart = localStorage.getItem('pos_cart');
      if (savedCart) {
        this.cart = JSON.parse(savedCart);
      }
      
      // Load customer data
      const savedCustomerName = localStorage.getItem('pos_customer_name');
      if (savedCustomerName) {
        this.customerName = savedCustomerName;
      }
      
      const savedCustomerPhone = localStorage.getItem('pos_customer_phone');
      if (savedCustomerPhone) {
        this.customerPhone = savedCustomerPhone;
      }
    },

    async loadKasirName() {
      try {
        const res = await axios.get('/check-kasir-session');
        const data = res?.data || {};
        const user = data.user || data;
        this.processedBy = user.name || 'Kasir';
      } catch (error) {
        console.error('Error loading kasir name:', error);
        this.processedBy = 'Kasir';
      }
    },

    loadDarkModePreference() {
      const saved = localStorage.getItem('pos_dark_mode');
      this.isDarkMode = saved === 'true';
    },

    toggleDarkMode() {
      this.isDarkMode = !this.isDarkMode;
      localStorage.setItem('pos_dark_mode', this.isDarkMode);
    },

    selectPaymentMethod(method) {
      this.paymentMethod = method;
      if (method === 'cash') {
        this.cashAmount = '';
        this.showCashModal = true;
      } else if (method === 'midtrans') {
        // If a cash draft exists, cancel it before proceeding with Midtrans
        DraftOrderService.cancelExistingDrafts();
      }
    },

    async processPayment() {
      if (!this.paymentMethod) {
        alert('Pilih metode pembayaran terlebih dahulu!');
        return;
      }

      if (this.cart.length === 0) {
        alert('Keranjang kosong!');
        return;
      }

      // For cash payment, validate amount first
      if (this.paymentMethod === 'cash') {
        if (!this.cashAmount || !this.isCashValid) {
          alert('Nominal tunai tidak valid atau kurang dari total pembayaran!');
          return;
        }
      }

      this.loading = true;

      try {
        const orderData = {
          customer_name: this.customerName,
          customer_phone: this.customerPhone,
          processed_by: this.processedBy,
          payment_method: this.paymentMethod,
          selected_member: this.selectedMember,
          points_earned: this.pointsEarned,
          items: this.cart.map(item => ({
            product_id: item.id,
            quantity: item.quantity
          }))
        };

        // Add cash amount for cash payment
        if (this.paymentMethod === 'cash') {
          orderData.cash_amount = parseFloat(this.cashAmount);
          orderData.change_amount = this.changeAmount;
        }

        if (this.paymentMethod === 'midtrans') {
          // Create order first
          const orderRes = await PaymentService.createOrder({ 
            ...orderData, 
            payment_method: 'midtrans',
            payment_type: 'midtrans',
            idempotency_key: `mid-${Date.now()}-${Math.random().toString(36).slice(2,8)}`
          });
          const createdOrder = orderRes || {};
          const orderId = createdOrder.order_id || 
                         createdOrder.id || 
                         createdOrder.order?.id ||
                         `MID-${Date.now()}`;
          
          // Get Snap token from Midtrans
          const snapRes = await PaymentService.createMidtransSnapToken(orderId);
          
          if (snapRes.success) {
            this.midtransSnapToken = snapRes.snap_token;
            this.midtransOrderId = orderId;
            this.showMidtransModal = true;
            this.$nextTick(() => { this.initMidtransSnap(); });
          } else {
            throw new Error(snapRes.message || 'Failed to create payment token');
          }
        } else {
          // Cash payment
          const draftId = localStorage.getItem('pos_draft_order_id');
          let response;
          if (this.paymentMethod === 'cash' && draftId) {
            response = await PaymentService.updateOrder(draftId, {
              status: 'completed',
              selected_member: this.selectedMember,
              points_earned: this.pointsEarned
            });
          } else {
            response = await PaymentService.createOrder(orderData);
          }
          // Generate order ID if not provided by API
          const orderId = response?.order_id || 
                         response?.id || 
                         response?.order?.id ||
                         `CASH-${Date.now()}`;
          
          // Show success modal
          this.showSuccessModal = true;
          this.successData = {
            orderId: orderId,
            paymentMethod: this.paymentMethod,
            total: this.finalTotal,
            change: this.changeAmount
          };
          
          // Clear cart and customer data
          this.clearCart();
        }
      } catch (error) {
        console.error('Payment processing error:', error);
        const apiMsg = error?.response?.data?.status_message
          || error?.response?.data?.message
          || error?.message
          || 'Terjadi kesalahan saat memproses pembayaran!';
        alert(apiMsg);
      } finally {
        this.loading = false;
        // Refresh draft orders after any payment processing
        await this.loadDraftOrders();
      }
    },


    // Cash Methods
    confirmCashPayment() {
      if (this.isCashValid) {
        this.showCashModal = false;
        this.processPayment();
      }
    },

    cancelCashPayment() {
      this.showCashModal = false;
      this.paymentMethod = '';
      this.cashAmount = '';
    },


    // Midtrans Methods
    async initMidtransSnap() {
      await this.loadMidtransSdk();
      
      if (typeof snap !== 'undefined' && this.midtransSnapToken) {
        snap.pay(this.midtransSnapToken, {
          onSuccess: (result) => {
            console.log('Midtrans payment success:', result);
            this.handleMidtransSuccess(result);
          },
          onPending: (result) => {
            console.log('Midtrans payment pending:', result);
            this.handleMidtransPending(result);
          },
          onError: (result) => {
            console.log('Midtrans payment error:', result);
            this.handleMidtransError(result);
          },
          onClose: () => {
            console.log('Midtrans payment closed');
            this.cancelMidtransPayment();
          }
        });
      } else {
        console.error('Midtrans Snap SDK not loaded or token missing');
        alert('Payment gateway tidak tersedia. Silakan coba lagi.');
      }
    },

    loadMidtransSdk() {
      // If already loaded
      if (typeof snap !== 'undefined') return Promise.resolve();

      // Avoid duplicate injections
      if (document.getElementById('midtrans-snap-sdk')) {
        return new Promise((resolve, reject) => {
          const check = () => {
            if (typeof snap !== 'undefined') resolve();
            else setTimeout(check, 100);
          };
          check();
        });
      }

      return new Promise((resolve, reject) => {
        const script = document.createElement('script');
        script.id = 'midtrans-snap-sdk';
        script.src = 'https://app.sandbox.midtrans.com/snap/snap.js';
        script.setAttribute('data-client-key', 'Mid-client-Ojy9d3wWhlCX9XiU');
        script.setAttribute('data-environment', 'sandbox');
        script.onload = () => resolve();
        script.onerror = () => reject(new Error('Failed to load Midtrans SDK'));
        document.body.appendChild(script);
      });
    },

    handleMidtransSuccess(result) {
      this.showMidtransModal = false;
      this.showSuccessModal = true;
      this.successData = {
        orderId: this.midtransOrderId,
        paymentMethod: 'midtrans',
        total: this.finalTotal,
        transactionId: result.transaction_id
      };
    },

    handleMidtransPending(result) {
      this.showMidtransModal = false;
      alert('Pembayaran sedang diproses. Silakan tunggu konfirmasi.');
    },

    handleMidtransError(result) {
      this.showMidtransModal = false;
      alert('Pembayaran gagal. Silakan coba lagi.');
    },

    async cancelMidtransPayment() {
      this.showMidtransModal = false;
      
      if (this.midtransOrderId) {
        try {
          // Cancel the order on the backend
          await PaymentService.cancelOrder(this.midtransOrderId);
          console.log('Order cancelled successfully');
        } catch (error) {
          console.error('Error cancelling order:', error);
        }
      }
      
      this.midtransOrderId = null;
      this.midtransSnapToken = null;
      
      // Refresh draft orders to show updated status
      await this.loadDraftOrders();
    },

    // Draft order management methods
    async loadDraftOrders() {
      try {
        const response = await DraftOrderService.getDrafts();
        this.draftOrders = response;
      } catch (error) {
        console.error('Error loading draft orders:', error);
        this.draftOrders = [];
      }
    },
    
    async deleteDraft(draftId) {
      if (!confirm('Yakin ingin menghapus draft order ini?')) return;
      
      try {
        await DraftOrderService.deleteDraft(draftId);
        await this.loadDraftOrders(); // Refresh the list
        alert('Draft order berhasil dihapus');
      } catch (error) {
        console.error('Error deleting draft order:', error);
        alert('Gagal menghapus draft order');
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
        quantity: item.quantity
      })) || [];
      
      // Save cart to localStorage
      localStorage.setItem('pos_cart', JSON.stringify(this.cart));
      
      // Delete the draft since we're editing it
      this.deleteDraft(draft.id);
      
      alert('Draft order dimuat ke form. Silakan lanjutkan dengan memilih metode pembayaran.');
    },
    
    formatDateTime(dateString) {
      const date = new Date(dateString);
      return date.toLocaleString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
    },

    // Cancel Current Order
    cancelCurrentOrder() {
      if (confirm('Yakin ingin membatalkan pesanan ini? Semua data akan dihapus.')) {
        this.clearCart();
        this.paymentMethod = '';
        this.cashAmount = '';
        this.showCashModal = false;
        this.showMidtransModal = false;
        this.showSuccessModal = false;
        this.showDraftOrdersModal = false;
        
        // Clear any stored draft order IDs
        localStorage.removeItem('pos_draft_order_id');
        
        alert('Pesanan telah dibatalkan');
      }
    },

    // Utility Methods
    formatPrice(price) {
      return new Intl.NumberFormat('id-ID').format(price);
    },

    clearCart() {
      this.cart = [];
      this.customerName = '';
      this.customerPhone = '';
      this.tableNumber = '';
      this.selectedMember = null;
      this.pointsEarned = 0;
      this.finalTotal = 0;
      this.paymentMethod = '';
      this.cashAmount = '';
      
      // Clear localStorage
      localStorage.removeItem('pos_cart');
      localStorage.removeItem('pos_customer_name');
      localStorage.removeItem('pos_customer_phone');
      localStorage.removeItem('pos_draft_order_id');
    },

    goBack() {
      window.history.back();
    },

    closeSuccessModal() {
      this.showSuccessModal = false;
      this.successData = null;
    },


    // Receipt Methods
    printReceipt() {
      if (!this.successData) return;
      
      // Create a new window for printing
      const printWindow = window.open('', '_blank', 'width=400,height=600');
      
      const receiptContent = this.generateReceiptHTML();
      printWindow.document.write(receiptContent);
      printWindow.document.close();
      printWindow.print();
    },

    generateReceiptHTML() {
      const { successData } = this;
      const now = new Date();
      const dateTime = now.toLocaleString('id-ID');
      
      return `
        <!DOCTYPE html>
        <html>
        <head>
          <title>Struk Pembayaran</title>
          <style>
            body { font-family: monospace; font-size: 12px; margin: 0; padding: 10px; }
            .header { text-align: center; margin-bottom: 20px; }
            .line { border-bottom: 1px dashed #000; margin: 10px 0; }
            .item { display: flex; justify-content: space-between; margin: 5px 0; }
            .total { font-weight: bold; font-size: 14px; }
            .footer { text-align: center; margin-top: 20px; font-size: 10px; }
          </style>
        </head>
        <body>
          <div class="header">
            <h2>POS ANGRINGAN</h2>
            <p>Jl. Contoh No. 123</p>
            <p>Telp: 08123456789</p>
          </div>
          
          <div class="line"></div>
          
          <div class="item">
            <span>Order ID:</span>
            <span>${successData.orderId}</span>
          </div>
          <div class="item">
            <span>Tanggal:</span>
            <span>${dateTime}</span>
          </div>
          <div class="item">
            <span>Kasir:</span>
            <span>${this.processedBy}</span>
          </div>
          
          <div class="line"></div>
          
          ${this.cart.map(item => `
            <div class="item">
              <span>${item.name} x${item.quantity}</span>
              <span>Rp ${this.formatPrice(item.price * item.quantity)}</span>
            </div>
          `).join('')}
          
          <div class="line"></div>
          
          <div class="item">
            <span>Subtotal:</span>
            <span>Rp ${this.formatPrice(this.cartSubtotal)}</span>
          </div>
          <div class="item">
            <span>Pajak (10%):</span>
            <span>Rp ${this.formatPrice(this.tax)}</span>
          </div>
          <div class="item total">
            <span>TOTAL:</span>
            <span>Rp ${this.formatPrice(successData.total)}</span>
          </div>
          
          <div class="line"></div>
          
          <div class="item">
            <span>Metode:</span>
            <span>${successData.paymentMethod.toUpperCase()}</span>
          </div>
          ${successData.changeAmount ? `
            <div class="item">
              <span>Kembalian:</span>
              <span>Rp ${this.formatPrice(successData.changeAmount)}</span>
            </div>
          ` : ''}
          
          <div class="footer">
            <p>Terima kasih atas kunjungan Anda!</p>
            <p>Struk ini adalah bukti pembayaran yang sah</p>
          </div>
        </body>
        </html>
      `;
    }
  },

  beforeUnmount() {
    // Cleanup if needed
  }
};
