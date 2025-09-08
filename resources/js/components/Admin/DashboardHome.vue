<template>
  <div>
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <!-- Today's Revenue -->
      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
              </div>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Pendapatan Hari Ini</dt>
                <dd class="text-lg font-medium text-gray-900">Rp {{ formatPrice(todayRevenue) }}</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>

      <!-- Today's Orders -->
      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
              </div>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Transaksi Hari Ini</dt>
                <dd class="text-lg font-medium text-gray-900">{{ todayOrdersCount }}</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>

      <!-- Month's Revenue -->
      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
              </div>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Pendapatan Bulan Ini</dt>
                <dd class="text-lg font-medium text-gray-900">Rp {{ formatPrice(monthRevenue) }}</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>

      <!-- Month's Orders -->
      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-8 h-8 bg-orange-500 rounded-md flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
              </div>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">Transaksi Bulan Ini</dt>
                <dd class="text-lg font-medium text-gray-900">{{ monthOrdersCount }}</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts and Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Sales Chart -->
      <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Penjualan 7 Hari Terakhir</h3>
        <div class="h-64 flex items-end space-x-2">
          <div v-for="(data, index) in chartData" :key="index" 
               class="flex-1 bg-blue-200 rounded-t flex flex-col justify-end"
               :style="{ height: getBarHeight(data.sales) + '%' }">
            <div class="text-xs text-center text-gray-600 mt-1">{{ data.day }}</div>
            <div class="text-xs text-center text-gray-800 font-medium">Rp {{ formatPrice(data.sales) }}</div>
          </div>
        </div>
      </div>

      <!-- Top Products -->
      <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Produk Terlaris</h3>
        <div class="space-y-3">
          <div v-for="product in topProducts" :key="product.id" class="flex items-center justify-between">
            <div class="flex-1">
              <p class="text-sm font-medium text-gray-900">{{ product.name }}</p>
              <p class="text-sm text-gray-500">{{ product.order_items_count }} terjual</p>
            </div>
            <div class="text-sm text-gray-900">
              Rp {{ formatPrice(product.price) }}
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
  name: 'DashboardHome',
  data() {
    return {
      todayRevenue: 0,
      todayOrdersCount: 0,
      monthRevenue: 0,
      monthOrdersCount: 0,
      topProducts: [],
      chartData: []
    }
  },
  async mounted() {
    await this.loadDashboardData();
  },
  methods: {
    async loadDashboardData() {
      try {
        const response = await axios.get('/admin/dashboard-data');
        const data = response.data;
        
        this.todayRevenue = data.todayRevenue;
        this.todayOrdersCount = data.todayOrdersCount;
        this.monthRevenue = data.monthRevenue;
        this.monthOrdersCount = data.monthOrdersCount;
        this.topProducts = data.topProducts;
        this.chartData = data.chartData;
      } catch (error) {
        console.error('Error loading dashboard data:', error);
      }
    },
    formatPrice(price) {
      return new Intl.NumberFormat('id-ID').format(price);
    },
    getBarHeight(sales) {
      const maxSales = Math.max(...this.chartData.map(d => d.sales));
      return sales > 0 ? (sales / maxSales) * 100 : 5;
    }
  }
}
</script>
