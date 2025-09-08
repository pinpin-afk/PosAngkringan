<template>
  <div>
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Manajemen Pesanan</h1>
    </div>

    <!-- Filter -->
    <div class="bg-white p-4 rounded-lg shadow mb-6">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Cari Pesanan</label>
          <input v-model="searchQuery" type="text" placeholder="Nomor pesanan..." 
                 class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
          <select v-model="statusFilter" 
                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Semua Status</option>
            <option value="pending">Pending</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
          <input v-model="dateFilter" type="date" 
                 class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
      </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Pesanan</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="order in filteredOrders" :key="order.id">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
              {{ order.order_number }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
              <div>
                <div class="font-medium">{{ order.customer_name || 'Tidak ada nama' }}</div>
                <div class="text-gray-500">{{ order.customer_phone || 'Tidak ada telepon' }}</div>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
              Rp {{ formatPrice(order.total) }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span :class="getStatusClass(order.status)" 
                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                {{ getStatusText(order.status) }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
              {{ formatDate(order.created_at) }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
              <button @click="viewOrder(order)" class="text-indigo-600 hover:text-indigo-900 mr-3">Lihat</button>
              <button v-if="order.status === 'pending'" @click="updateOrderStatus(order, 'completed')" 
                      class="text-green-600 hover:text-green-900 mr-3">Selesai</button>
              <button v-if="order.status === 'pending'" @click="updateOrderStatus(order, 'cancelled')" 
                      class="text-red-600 hover:text-red-900">Batal</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Order Detail Modal -->
    <div v-if="showOrderModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Detail Pesanan</h3>
            <button @click="showOrderModal = false" class="text-gray-400 hover:text-gray-600">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
          
          <div v-if="selectedOrder" class="space-y-6">
            <!-- Order Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <h4 class="text-sm font-medium text-gray-700 mb-2">Informasi Pesanan</h4>
                <div class="space-y-2">
                  <div><span class="font-medium">No. Pesanan:</span> {{ selectedOrder.order_number }}</div>
                  <div><span class="font-medium">Tanggal:</span> {{ formatDate(selectedOrder.created_at) }}</div>
                  <div><span class="font-medium">Status:</span> 
                    <span :class="getStatusClass(selectedOrder.status)" class="ml-2 px-2 py-1 text-xs rounded-full">
                      {{ getStatusText(selectedOrder.status) }}
                    </span>
                  </div>
                </div>
              </div>
              
              <div>
                <h4 class="text-sm font-medium text-gray-700 mb-2">Informasi Pelanggan</h4>
                <div class="space-y-2">
                  <div><span class="font-medium">Nama:</span> {{ selectedOrder.customer_name || 'Tidak ada nama' }}</div>
                  <div><span class="font-medium">Telepon:</span> {{ selectedOrder.customer_phone || 'Tidak ada telepon' }}</div>
                </div>
              </div>
            </div>
            
            <!-- Order Items -->
            <div>
              <h4 class="text-sm font-medium text-gray-700 mb-2">Item Pesanan</h4>
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="item in selectedOrder.order_items" :key="item.id">
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ item.product ? item.product.name : 'Produk tidak ditemukan' }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        Rp {{ formatPrice(item.price) }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ item.quantity }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        Rp {{ formatPrice(item.subtotal) }}
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            
            <!-- Order Summary -->
            <div class="border-t pt-4">
              <div class="flex justify-end">
                <div class="w-64 space-y-2">
                  <div class="flex justify-between">
                    <span>Subtotal:</span>
                    <span>Rp {{ formatPrice(selectedOrder.subtotal) }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span>Pajak (10%):</span>
                    <span>Rp {{ formatPrice(selectedOrder.tax) }}</span>
                  </div>
                  <div class="flex justify-between font-bold text-lg">
                    <span>Total:</span>
                    <span>Rp {{ formatPrice(selectedOrder.total) }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'OrderManagement',
  data() {
    return {
      orders: [],
      searchQuery: '',
      statusFilter: '',
      dateFilter: '',
      showOrderModal: false,
      selectedOrder: null
    }
  },
  computed: {
    filteredOrders() {
      let filtered = this.orders;
      
      if (this.searchQuery) {
        filtered = filtered.filter(order => 
          order.order_number.toLowerCase().includes(this.searchQuery.toLowerCase())
        );
      }
      
      if (this.statusFilter) {
        filtered = filtered.filter(order => order.status === this.statusFilter);
      }
      
      if (this.dateFilter) {
        filtered = filtered.filter(order => 
          order.created_at.startsWith(this.dateFilter)
        );
      }
      
      return filtered;
    }
  },
  async mounted() {
    await this.loadOrders();
  },
  methods: {
    async loadOrders() {
      try {
        const response = await axios.get('/pos/orders');
        this.orders = response.data;
      } catch (error) {
        console.error('Error loading orders:', error);
      }
    },
    async viewOrder(order) {
      try {
        const response = await axios.get(`/pos/orders/${order.id}`);
        this.selectedOrder = response.data;
        this.showOrderModal = true;
      } catch (error) {
        console.error('Error loading order details:', error);
      }
    },
    async updateOrderStatus(order, status) {
      if (confirm(`Apakah Anda yakin ingin mengubah status pesanan menjadi "${this.getStatusText(status)}"?`)) {
        try {
          await axios.put(`/pos/orders/${order.id}`, { status });
          await this.loadOrders();
        } catch (error) {
          console.error('Error updating order status:', error);
          alert('Error updating order status');
        }
      }
    },
    getStatusClass(status) {
      const classes = {
        pending: 'bg-yellow-100 text-yellow-800',
        completed: 'bg-green-100 text-green-800',
        cancelled: 'bg-red-100 text-red-800'
      };
      return classes[status] || 'bg-gray-100 text-gray-800';
    },
    getStatusText(status) {
      const texts = {
        pending: 'Pending',
        completed: 'Selesai',
        cancelled: 'Dibatalkan'
      };
      return texts[status] || status;
    },
    formatPrice(price) {
      return new Intl.NumberFormat('id-ID').format(price);
    },
    formatDate(date) {
      return new Date(date).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
    }
  }
}
</script>
