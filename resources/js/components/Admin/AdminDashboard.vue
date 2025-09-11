<template>
  <div class="min-h-screen transition-colors duration-300" :class="isDarkMode ? 'bg-gray-900' : 'bg-gray-100'">
    <!-- Sidebar -->
    <AdminSidebar :open="sidebarOpen" :current-page="currentPage" :is-dark-mode="isDarkMode" />

    <!-- Main Content -->
    <div class="flex-1 flex flex-col lg:ml-64" :class="{ 'ml-64': sidebarOpen }">
      <!-- Top Navigation -->
      <AdminNavbar 
        :title="currentPageTitle" 
        :subtitle="currentPage === 'dashboard' ? 'Ringkasan performa dan aktivitas terbaru' : ''"
        :user="user"
        :is-dark-mode="isDarkMode"
        @toggle="toggleSidebar"
        @logout="logout"
        @toggle-dark-mode="toggleDarkMode"
      />

      <!-- Page Content -->
      <main class="flex-1 p-3 sm:p-6">
        <section v-if="currentPage === 'dashboard'" class="space-y-6">
          <!-- Stats Cards -->
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <div class="rounded-xl shadow-lg overflow-hidden bg-gradient-to-br from-indigo-500 to-indigo-600 text-white">
              <div class="p-4 sm:p-5">
                <div class="flex items-center">
                  <div class="flex-shrink-0">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white/20 rounded-xl flex items-center justify-center">
                      <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18M3 7h18M3 21h18M5 7v14m7-14v14m7-14v14"></path>
                      </svg>
                    </div>
                  </div>
                  <div class="ml-3 sm:ml-4 min-w-0">
                    <p class="text-xs sm:text-sm text-white/80">Total Produk</p>
                    <p class="text-xl sm:text-2xl font-bold">{{ stats.total_products }}</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="rounded-xl shadow-lg overflow-hidden bg-gradient-to-br from-emerald-500 to-emerald-600 text-white">
              <div class="p-4 sm:p-5">
                <div class="flex items-center">
                  <div class="flex-shrink-0">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white/20 rounded-xl flex items-center justify-center">
                      <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V7a2 2 0 00-2-2h-3l-2-2H9L7 5H4a2 2 0 00-2 2v6m18 0v5a2 2 0 01-2 2H4a2 2 0 01-2-2v-5m18 0H2"></path>
                      </svg>
                    </div>
                  </div>
                  <div class="ml-3 sm:ml-4 min-w-0">
                    <p class="text-xs sm:text-sm text-white/80">Total Kategori</p>
                    <p class="text-xl sm:text-2xl font-bold">{{ stats.total_categories }}</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="rounded-xl shadow-lg overflow-hidden bg-gradient-to-br from-amber-500 to-amber-600 text-white">
              <div class="p-4 sm:p-5">
                <div class="flex items-center">
                  <div class="flex-shrink-0">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white/20 rounded-xl flex items-center justify-center">
                      <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h11M9 21V3m7 18V3m4 7h-7"></path>
                      </svg>
                    </div>
                  </div>
                  <div class="ml-3 sm:ml-4 min-w-0">
                    <p class="text-xs sm:text-sm text-white/80">Total Pesanan</p>
                    <p class="text-xl sm:text-2xl font-bold">{{ stats.total_orders }}</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="rounded-xl shadow-lg overflow-hidden bg-gradient-to-br from-pink-500 to-rose-600 text-white">
              <div class="p-4 sm:p-5">
                <div class="flex items-center">
                  <div class="flex-shrink-0">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white/20 rounded-xl flex items-center justify-center">
                      <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                      </svg>
                    </div>
                  </div>
                  <div class="ml-3 sm:ml-4 min-w-0">
                    <p class="text-xs sm:text-sm text-white/80">Total Pendapatan</p>
                    <p class="text-lg sm:text-2xl font-bold">Rp {{ formatNumber(stats.today_revenue) }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Charts Section -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
            <!-- Revenue Chart -->
            <div class="rounded-xl shadow-xl p-4 sm:p-6 transition-colors duration-300" :class="isDarkMode ? 'bg-gray-800 border border-gray-700' : 'bg-white border border-gray-200'">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Pendapatan 7 Hari Terakhir</h3>
                <div class="flex items-center space-x-2">
                  <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                  <span class="text-sm transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">Pendapatan</span>
                </div>
              </div>
              <div class="h-64">
                <canvas ref="revenueChart" class="w-full h-full"></canvas>
              </div>
            </div>

            <!-- Orders Chart -->
            <div class="rounded-xl shadow-xl p-4 sm:p-6 transition-colors duration-300" :class="isDarkMode ? 'bg-gray-800 border border-gray-700' : 'bg-white border border-gray-200'">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Pesanan 7 Hari Terakhir</h3>
                <div class="flex items-center space-x-2">
                  <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                  <span class="text-sm transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">Pesanan</span>
                </div>
              </div>
              <div class="h-64">
                <canvas ref="ordersChart" class="w-full h-full"></canvas>
              </div>
            </div>
          </div>

          <!-- Product Categories Chart -->
          <div class="rounded-xl shadow-xl p-4 sm:p-6 transition-colors duration-300" :class="isDarkMode ? 'bg-gray-800 border border-gray-700' : 'bg-white border border-gray-200'">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-lg font-semibold transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Distribusi Produk per Kategori</h3>
              <div class="text-sm transition-colors duration-300" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">{{ stats.total_products }} Total Produk</div>
            </div>
            <div class="h-80">
              <canvas ref="categoryChart" class="w-full h-full"></canvas>
            </div>
          </div>

          <!-- Quick Actions -->
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            <a href="/admin/products" class="group rounded-xl border p-5 hover:shadow-md transition-all duration-300" :class="isDarkMode ? 'border-gray-700 bg-gray-800 hover:border-indigo-500' : 'border-gray-200 bg-white hover:border-indigo-300'">
              <div class="flex items-center">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center transition-colors duration-300" :class="isDarkMode ? 'bg-indigo-900 text-indigo-300 group-hover:bg-indigo-800' : 'bg-indigo-100 text-indigo-600 group-hover:bg-indigo-200'">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V7a2 2 0 00-2-2h-3l-2-2H9L7 5H4a2 2 0 00-2 2v6m18 0v5a2 2 0 01-2 2H4a2 2 0 01-2-2v-5m18 0H2"></path>
                  </svg>
                </div>
                <div class="ml-4">
                  <p class="text-sm transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">Kelola</p>
                  <p class="text-base font-semibold transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Produk</p>
                </div>
              </div>
            </a>
            <a href="/admin/categories" class="group rounded-xl border p-5 hover:shadow-md transition-all duration-300" :class="isDarkMode ? 'border-gray-700 bg-gray-800 hover:border-emerald-500' : 'border-gray-200 bg-white hover:border-emerald-300'">
              <div class="flex items-center">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center transition-colors duration-300" :class="isDarkMode ? 'bg-emerald-900 text-emerald-300 group-hover:bg-emerald-800' : 'bg-emerald-100 text-emerald-600 group-hover:bg-emerald-200'">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                  </svg>
                </div>
                <div class="ml-4">
                  <p class="text-sm transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">Kelola</p>
                  <p class="text-base font-semibold transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Kategori</p>
                </div>
              </div>
            </a>
            <a href="/admin/orders" class="group rounded-xl border p-5 hover:shadow-md transition-all duration-300" :class="isDarkMode ? 'border-gray-700 bg-gray-800 hover:border-amber-500' : 'border-gray-200 bg-white hover:border-amber-300'">
              <div class="flex items-center">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center transition-colors duration-300" :class="isDarkMode ? 'bg-amber-900 text-amber-300 group-hover:bg-amber-800' : 'bg-amber-100 text-amber-600 group-hover:bg-amber-200'">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                  </svg>
                </div>
                <div class="ml-4">
                  <p class="text-sm transition-colors duration-300" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">Pantau</p>
                  <p class="text-base font-semibold transition-colors duration-300" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Pesanan</p>
                </div>
              </div>
            </a>
          </div>
        </section>
        
        <ProductManagement v-else-if="currentPage === 'products'" :is-dark-mode="isDarkMode" />
        <CategoryManagement v-else-if="currentPage === 'categories'" :is-dark-mode="isDarkMode" />
        <OrderManagement v-else-if="currentPage === 'orders'" :is-dark-mode="isDarkMode" />
        <UserManagement v-else-if="currentPage === 'users'" />
        <RoleManagement v-else-if="currentPage === 'roles'" />
        <div v-else-if="currentPage === 'members'" class="space-y-6">
          <!-- Breadcrumb -->
          <nav class="flex items-center space-x-2 text-sm text-gray-600">
            <button @click="updateCurrentPage('dashboard')" class="flex items-center space-x-1 hover:text-gray-900 transition-colors">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
              </svg>
              <span>Dashboard</span>
            </button>
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="font-medium text-gray-900">Kelola Member</span>
          </nav>
          
          <!-- Member Management Component -->
          <MemberManagement />
        </div>
        <div v-else-if="currentPage === 'pos'" id="pos-app"></div>
      </main>
      <AdminFooter :is-dark-mode="isDarkMode" />
    </div>

    <!-- Overlay for mobile -->
    <div v-if="sidebarOpen" @click="toggleSidebar" class="fixed inset-0 z-40 lg:hidden" style="background-color: rgba(30, 41, 59, 0.7);"></div>
  </div>
</template>

<script>
import { Chart, registerables } from 'chart.js';
import AdminSidebar from './Layout/AdminSidebar.vue';
import AdminNavbar from './Layout/AdminNavbar.vue';
import AdminFooter from './Layout/AdminFooter.vue';
import MemberManagement from './MemberManagement.vue';

// Register Chart.js components
Chart.register(...registerables);
export default {
  name: 'AdminDashboard',
  components: {
    AdminSidebar,
    AdminNavbar,
    AdminFooter,
    MemberManagement
  },
  data() {
    return {
      user: window.user || {},
      sidebarOpen: true,
      currentPageTitle: 'Dashboard',
      currentPage: 'dashboard',
      isDarkMode: false,
      stats: {
        total_products: 0,
        total_categories: 0,
        total_orders: 0,
        today_revenue: 0
      },
      chartData: {
        revenue: [],
        orders: [],
        categories: []
      },
      revenueChart: null,
      ordersChart: null,
      categoryChart: null
    }
  },
  mounted() {
    this.updateCurrentPage();
    this.handleHashNavigation();
    this.loadStats();
    this.loadChartData();
    this.loadDarkModePreference();
    
    // Listen for custom page change events
    window.addEventListener('admin-page-change', (event) => {
      this.currentPage = event.detail.page;
      this.currentPageTitle = event.detail.title;
    });
  },
  beforeUnmount() {
    // Cleanup charts when component is destroyed
    if (this.revenueChart) {
      this.revenueChart.destroy();
    }
    if (this.ordersChart) {
      this.ordersChart.destroy();
    }
    if (this.categoryChart) {
      this.categoryChart.destroy();
    }
  },
  methods: {
    toggleSidebar() {
      this.sidebarOpen = !this.sidebarOpen;
    },
    updateCurrentPage() {
      const path = window.location.pathname;
      if (path.includes('/admin/products')) {
        this.currentPage = 'products';
        this.currentPageTitle = 'Manajemen Produk';
      } else if (path.includes('/admin/categories')) {
        this.currentPage = 'categories';
        this.currentPageTitle = 'Manajemen Kategori';
      } else if (path.includes('/admin/orders')) {
        this.currentPage = 'orders';
        this.currentPageTitle = 'Manajemen Pesanan';
      } else if (path.includes('/admin/users')) {
        this.currentPage = 'users';
        this.currentPageTitle = 'Manajemen User';
      } else if (path.includes('/admin/members')) {
        this.currentPage = 'members';
        this.currentPageTitle = 'Manajemen Member';
      } else if (path.includes('/admin/roles')) {
        this.currentPage = 'roles';
        this.currentPageTitle = 'Manajemen Role';
      } else if (path.includes('/admin/pos')) {
        this.currentPage = 'pos';
        this.currentPageTitle = 'POS Kasir';
      } else {
        this.currentPage = 'dashboard';
        this.currentPageTitle = 'Dashboard';
      }
    },
    handleHashNavigation() {
      // Check for hash navigation
      const hash = window.location.hash.substring(1);
      if (hash === 'users') {
        this.currentPage = 'users';
        this.currentPageTitle = 'Manajemen User';
      } else if (hash === 'members') {
        this.currentPage = 'members';
        this.currentPageTitle = 'Manajemen Member';
      } else if (hash === 'roles') {
        this.currentPage = 'roles';
        this.currentPageTitle = 'Manajemen Role';
      }
    },
    async loadStats() {
      try {
        const res = await fetch('/admin/dashboard-data');
        if (res.ok) {
          const data = await res.json();
          this.stats = {
            total_products: data.totalProducts ?? 0,
            total_categories: data.totalCategories ?? 0,
            total_orders: data.totalOrders ?? 0,
            today_revenue: data.totalRevenue ?? 0
          };
        }
      } catch (e) {
        console.error('Failed to load dashboard stats', e);
      }
    },

    async loadChartData() {
      try {
        // Load revenue data for last 7 days
        const revenueRes = await fetch('/admin/dashboard/revenue-chart');
        if (revenueRes.ok) {
          this.chartData.revenue = await revenueRes.json();
        }

        // Load orders data for last 7 days
        const ordersRes = await fetch('/admin/dashboard/orders-chart');
        if (ordersRes.ok) {
          this.chartData.orders = await ordersRes.json();
        }

        // Load categories data
        const categoriesRes = await fetch('/admin/dashboard/categories-chart');
        if (categoriesRes.ok) {
          this.chartData.categories = await categoriesRes.json();
        }

        // Initialize charts after data is loaded
        this.$nextTick(() => {
          this.initCharts();
        });
      } catch (error) {
        console.error('Error loading chart data:', error);
        // Initialize charts with sample data if API fails
        this.initChartsWithSampleData();
      }
    },

    initCharts() {
      this.createRevenueChart();
      this.createOrdersChart();
      this.createCategoryChart();
    },

    initChartsWithSampleData() {
      // Sample data for demonstration
      this.chartData.revenue = [
        { date: '2024-01-01', revenue: 150000 },
        { date: '2024-01-02', revenue: 200000 },
        { date: '2024-01-03', revenue: 180000 },
        { date: '2024-01-04', revenue: 220000 },
        { date: '2024-01-05', revenue: 250000 },
        { date: '2024-01-06', revenue: 300000 },
        { date: '2024-01-07', revenue: 280000 }
      ];

      this.chartData.orders = [
        { date: '2024-01-01', orders: 12 },
        { date: '2024-01-02', orders: 18 },
        { date: '2024-01-03', orders: 15 },
        { date: '2024-01-04', orders: 22 },
        { date: '2024-01-05', orders: 25 },
        { date: '2024-01-06', orders: 30 },
        { date: '2024-01-07', orders: 28 }
      ];

      this.chartData.categories = [
        { name: 'Makanan', count: 15 },
        { name: 'Minuman', count: 8 },
        { name: 'Snack', count: 12 },
        { name: 'Dessert', count: 5 }
      ];

      this.$nextTick(() => {
        this.initCharts();
      });
    },

    createRevenueChart() {
      const ctx = this.$refs.revenueChart?.getContext('2d');
      if (!ctx) return;

      if (this.revenueChart) {
        this.revenueChart.destroy();
      }

      const labels = this.chartData.revenue.map(item => {
        const date = new Date(item.date);
        return date.toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric' });
      });
      const data = this.chartData.revenue.map(item => item.revenue);

      this.revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: labels,
          datasets: [{
            label: 'Pendapatan',
            data: data,
            borderColor: '#3B82F6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#3B82F6',
            pointBorderColor: this.isDarkMode ? '#1f2937' : '#ffffff',
            pointBorderWidth: 2,
            pointRadius: 6,
            pointHoverRadius: 8
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              grid: {
                color: this.isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.05)'
              },
              ticks: {
                color: this.isDarkMode ? '#d1d5db' : '#374151',
                callback: function(value) {
                  return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                }
              }
            },
            x: {
              grid: {
                display: false
              },
              ticks: {
                color: this.isDarkMode ? '#d1d5db' : '#374151'
              }
            }
          }
        }
      });
    },

    createOrdersChart() {
      const ctx = this.$refs.ordersChart?.getContext('2d');
      if (!ctx) return;

      if (this.ordersChart) {
        this.ordersChart.destroy();
      }

      const labels = this.chartData.orders.map(item => {
        const date = new Date(item.date);
        return date.toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric' });
      });
      const data = this.chartData.orders.map(item => item.orders);

      this.ordersChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
            label: 'Pesanan',
            data: data,
            backgroundColor: 'rgba(34, 197, 94, 0.8)',
            borderColor: '#22C55E',
            borderWidth: 1,
            borderRadius: 6,
            borderSkipped: false
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              grid: {
                color: this.isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.05)'
              },
              ticks: {
                color: this.isDarkMode ? '#d1d5db' : '#374151'
              }
            },
            x: {
              grid: {
                display: false
              },
              ticks: {
                color: this.isDarkMode ? '#d1d5db' : '#374151'
              }
            }
          }
        }
      });
    },

    createCategoryChart() {
      const ctx = this.$refs.categoryChart?.getContext('2d');
      if (!ctx) return;

      if (this.categoryChart) {
        this.categoryChart.destroy();
      }

      const labels = this.chartData.categories.map(item => item.name);
      const data = this.chartData.categories.map(item => item.count);
      const colors = [
        '#3B82F6', '#22C55E', '#F59E0B', '#EF4444', 
        '#8B5CF6', '#06B6D4', '#F97316', '#84CC16'
      ];

      this.categoryChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: labels,
          datasets: [{
            data: data,
            backgroundColor: colors.slice(0, labels.length),
            borderWidth: 0,
            cutout: '60%'
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'bottom',
              labels: {
                padding: 20,
                usePointStyle: true,
                pointStyle: 'circle',
                color: this.isDarkMode ? '#d1d5db' : '#374151'
              }
            }
          }
        }
      });
    },
    formatNumber(value) {
      try {
        return new Intl.NumberFormat('id-ID').format(Number(value || 0));
      } catch (_) {
        return value;
      }
    },
    logout() {
      if (confirm('Apakah Anda yakin ingin logout?')) {
        // Create a form to submit POST request
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/logout';
        
        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
          const csrfInput = document.createElement('input');
          csrfInput.type = 'hidden';
          csrfInput.name = '_token';
          csrfInput.value = csrfToken.getAttribute('content');
          form.appendChild(csrfInput);
        }
        
        document.body.appendChild(form);
        form.submit();
      }
    },
    
    loadDarkModePreference() {
      const saved = localStorage.getItem('darkMode');
      this.isDarkMode = saved === 'true';
    },
    
    toggleDarkMode() {
      this.isDarkMode = !this.isDarkMode;
      localStorage.setItem('darkMode', this.isDarkMode);
      // Recreate charts with new colors
      this.$nextTick(() => {
        this.initCharts();
      });
    }
  }
}
</script>