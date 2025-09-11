<template>
  <div class="min-h-screen transition-colors duration-300" :class="isDarkMode ? 'bg-gray-900' : 'bg-gray-50'">
    <!-- Header -->
    <header class="transition-colors duration-300 shadow-sm border-b" :class="isDarkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'">
      <div class="px-6 py-4">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <button 
              @click="goBack"
              class="p-2 rounded-lg transition-colors duration-300" :class="isDarkMode ? 'hover:bg-gray-700 text-gray-300' : 'hover:bg-gray-100 text-gray-600'"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
              </svg>
            </button>
            <div>
              <h1 class="text-xl font-semibold transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Laporan Order</h1>
              <p class="text-sm transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-500'">Analisis penjualan dan transaksi</p>
            </div>
          </div>
          <div class="flex items-center space-x-4">
            <button 
              @click="toggleDarkMode"
              class="p-2 rounded-lg transition-colors duration-300" :class="isDarkMode ? 'hover:bg-gray-700 text-yellow-400' : 'hover:bg-gray-100 text-gray-600'"
            >
              <svg v-if="!isDarkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
              </svg>
              <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </header>

    <div class="p-4 md:p-6 transition-colors duration-300" :class="isDarkMode ? 'bg-gray-900' : 'bg-gray-50'">
      <!-- Filters -->
      <div class="rounded-xl shadow-sm border p-6 mb-6 transition-colors duration-300" :class="isDarkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-lg font-semibold transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Filter Laporan</h2>
          <button @click="loadReport" :disabled="loading" 
                  class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
            <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <svg v-else class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            {{ loading ? 'Memuat...' : 'Filter' }}
          </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
          <div>
            <label class="block text-sm font-medium mb-2 transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Dari Tanggal</label>
            <input v-model="from" type="datetime-local" 
                   class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-300" :class="isDarkMode ? 'bg-gray-700 border-gray-600 text-white placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500'" />
          </div>
          <div>
            <label class="block text-sm font-medium mb-2 transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Sampai Tanggal</label>
            <input v-model="to" type="datetime-local" 
                   class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-300" :class="isDarkMode ? 'bg-gray-700 border-gray-600 text-white placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500'" />
          </div>
          <div>
            <label class="block text-sm font-medium mb-2 transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Kasir</label>
            <input v-model="cashier" type="text" placeholder="Nama kasir" 
                   class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-300" :class="isDarkMode ? 'bg-gray-700 border-gray-600 text-white placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500'" />
          </div>
          <div>
            <label class="block text-sm font-medium mb-2 transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Metode Pembayaran</label>
            <select v-model="method" 
                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-300" :class="isDarkMode ? 'bg-gray-700 border-gray-600 text-white' : 'bg-white border-gray-300 text-gray-900'">
              <option value="">Semua Metode</option>
              <option value="cash">Cash</option>
              <option value="qris">QRIS</option>
              <option value="card">Card</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Summary Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="rounded-xl shadow-sm border p-6 hover:shadow-md transition-all duration-300" :class="isDarkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">Total Penjualan</p>
              <p class="text-2xl font-bold transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Rp {{ format(summary.total_sales) }}</p>
            </div>
          </div>
        </div>

        <div class="rounded-xl shadow-sm border p-6 hover:shadow-md transition-all duration-300" :class="isDarkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">Jumlah Order</p>
              <p class="text-2xl font-bold transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ summary.total_orders }}</p>
            </div>
          </div>
        </div>

        <div class="rounded-xl shadow-sm border p-6 hover:shadow-md transition-all duration-300" :class="isDarkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">Jumlah Item</p>
              <p class="text-2xl font-bold transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ summary.total_items }}</p>
            </div>
          </div>
        </div>

        <div class="rounded-xl shadow-sm border p-6 hover:shadow-md transition-all duration-300" :class="isDarkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">Rata-rata Order</p>
              <p class="text-2xl font-bold transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Rp {{ format(summary.avg_order) }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Payment Method Breakdown -->
      <div class="rounded-xl shadow-sm border p-6 mb-6 transition-colors duration-300" :class="isDarkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'">
        <h2 class="text-lg font-semibold mb-4 transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Breakdown Per Metode Pembayaran</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div v-for="(val, key) in summary.by_method" :key="key" 
               class="rounded-lg p-4 hover:shadow-md transition-all duration-300" :class="isDarkMode ? 'bg-gray-700 hover:bg-gray-600' : 'bg-gradient-to-r from-gray-50 to-gray-100'">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">{{ getPaymentMethodLabel(key) }}</p>
                <p class="text-2xl font-bold transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Rp {{ format(val.total || 0) }}</p>
                <p class="text-xs transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">{{ val.count || 0 }} transaksi</p>
              </div>
              <div class="w-12 h-12 rounded-lg flex items-center justify-center" 
                   :class="getPaymentMethodColor(key)">
                <svg class="w-6 h-6" :class="getPaymentMethodIconColor(key)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="getPaymentMethodIcon(key)"></path>
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Orders Table -->
      <div class="rounded-xl shadow-sm border overflow-hidden transition-colors duration-300" :class="isDarkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'">
        <div class="px-6 py-4 border-b transition-colors duration-300" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
          <h2 class="text-lg font-semibold transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Daftar Order</h2>
          <p class="text-sm transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">Detail transaksi berdasarkan filter yang dipilih</p>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y transition-colors duration-300" :class="isDarkMode ? 'divide-gray-700' : 'divide-gray-200'">
            <thead class="transition-colors duration-300" :class="isDarkMode ? 'bg-gray-700' : 'bg-gray-50'">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-500'">Tanggal</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-500'">No Order</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-500'">Kasir</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-500'">Metode</th>
                <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-500'">Total</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-500'">Status</th>
              </tr>
            </thead>
            <tbody class="divide-y transition-colors duration-300" :class="isDarkMode ? 'bg-gray-800 divide-gray-700' : 'bg-white divide-gray-200'">
              <tr v-for="o in orders" :key="o.id" class="transition-colors duration-300" :class="isDarkMode ? 'hover:bg-gray-700' : 'hover:bg-gray-50'">
                <td class="px-6 py-4 whitespace-nowrap text-sm transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                  {{ new Date(o.created_at).toLocaleString('id-ID') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                  {{ o.order_number }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                  {{ o.processed_by || '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                        :class="getPaymentMethodBadgeColor(o.payment_method)">
                    {{ getPaymentMethodLabel(o.payment_method) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-right transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                  Rp {{ format(o.total) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                        :class="getStatusBadgeColor(o.status)">
                    {{ o.status }}
                  </span>
                </td>
              </tr>
              <tr v-if="orders.length === 0">
                <td colspan="6" class="px-6 py-12 text-center">
                  <div class="flex flex-col items-center">
                    <svg class="w-12 h-12 mb-4 transition-colors duration-300" :class="isDarkMode ? 'text-gray-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <p class="text-sm transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Tidak ada data order</p>
                    <p class="text-xs mt-1 transition-colors duration-300" :class="isDarkMode ? 'text-gray-500' : 'text-gray-400'">Coba ubah filter untuk melihat data</p>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  name: 'PosOrderReport',
  data() {
    const now = new Date();
    const start = new Date();
    start.setHours(0,0,0,0);
    return {
      from: start.toISOString().slice(0,16),
      to: now.toISOString().slice(0,16),
      cashier: '',
      method: '',
      summary: { total_sales: 0, total_orders: 0, total_items: 0, avg_order: 0, by_method: {} },
      orders: [],
      loading: false,
      isDarkMode: false,
    }
  },
  mounted() {
    this.loadReport();
    this.loadDarkModePreference();
  },
  methods: {
    async loadReport() {
      this.loading = true;
      try {
        const params = new URLSearchParams();
        if (this.from) params.append('from', new Date(this.from).toISOString());
        if (this.to) params.append('to', new Date(this.to).toISOString());
        if (this.cashier) params.append('cashier', this.cashier);
        if (this.method) params.append('method', this.method);
        const { data } = await axios.get(`/api/pos/orders/report?${params.toString()}`);
        this.summary = data.summary || this.summary;
        this.orders = data.orders || [];
      } catch (e) {
        alert(e?.response?.data?.message || e.message || 'Gagal memuat laporan');
      } finally {
        this.loading = false;
      }
    },
    format(n) {
      return new Intl.NumberFormat('id-ID').format(Math.round(n || 0));
    },
    getPaymentMethodLabel(method) {
      const labels = {
        'cash': 'Cash',
        'qris': 'QRIS',
        'card': 'Card',
        'gopay': 'GoPay',
        'shopeepay': 'ShopeePay'
      };
      return labels[method] || method.toUpperCase();
    },
    getPaymentMethodColor(method) {
      const colors = {
        'cash': 'bg-green-100',
        'qris': 'bg-blue-100',
        'card': 'bg-purple-100',
        'gopay': 'bg-green-100',
        'shopeepay': 'bg-orange-100'
      };
      return colors[method] || 'bg-gray-100';
    },
    getPaymentMethodIconColor(method) {
      const colors = {
        'cash': 'text-green-600',
        'qris': 'text-blue-600',
        'card': 'text-purple-600',
        'gopay': 'text-green-600',
        'shopeepay': 'text-orange-600'
      };
      return colors[method] || 'text-gray-600';
    },
    getPaymentMethodIcon(method) {
      const icons = {
        'cash': 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1',
        'qris': 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z',
        'card': 'M3 4a1 1 0 000 2h1a1 1 0 000-2H3zM3 8a1 1 0 000 2h1a1 1 0 000-2H3zM3 12a1 1 0 000 2h1a1 1 0 000-2H3zM7 4a1 1 0 000 2h1a1 1 0 000-2H7zM7 8a1 1 0 000 2h1a1 1 0 000-2H7zM7 12a1 1 0 000 2h1a1 1 0 000-2H7zM11 4a1 1 0 000 2h1a1 1 0 000-2h-1zM11 8a1 1 0 000 2h1a1 1 0 000-2h-1zM11 12a1 1 0 000 2h1a1 1 0 000-2h-1z',
        'gopay': 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z',
        'shopeepay': 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z'
      };
      return icons[method] || 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z';
    },
    getPaymentMethodBadgeColor(method) {
      const colors = {
        'cash': 'bg-green-100 text-green-800',
        'qris': 'bg-blue-100 text-blue-800',
        'card': 'bg-purple-100 text-purple-800',
        'gopay': 'bg-green-100 text-green-800',
        'shopeepay': 'bg-orange-100 text-orange-800'
      };
      return colors[method] || 'bg-gray-100 text-gray-800';
    },
    getStatusBadgeColor(status) {
      const colors = {
        'completed': 'bg-green-100 text-green-800',
        'pending': 'bg-yellow-100 text-yellow-800',
        'cancelled': 'bg-red-100 text-red-800',
        'processing': 'bg-blue-100 text-blue-800'
      };
      return colors[status] || 'bg-gray-100 text-gray-800';
    },
    loadDarkModePreference() {
      const saved = localStorage.getItem('darkMode');
      this.isDarkMode = saved === 'true';
    },
    toggleDarkMode() {
      this.isDarkMode = !this.isDarkMode;
      localStorage.setItem('darkMode', this.isDarkMode);
    },
    goBack() {
      window.history.back();
    }
  }
}
</script>
