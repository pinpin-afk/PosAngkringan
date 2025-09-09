<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Sidebar -->
    <AdminSidebar :open="sidebarOpen" :current-page="currentPage" />

    <!-- Main Content -->
    <div class="flex-1 flex flex-col lg:ml-64" :class="{ 'ml-64': sidebarOpen }">
      <!-- Top Navigation -->
      <AdminNavbar 
        :title="currentPageTitle" 
        :subtitle="currentPage === 'dashboard' ? 'Ringkasan performa dan aktivitas terbaru' : ''"
        :user="user"
        @toggle="toggleSidebar"
        @logout="logout"
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
                    <p class="text-xs sm:text-sm text-white/80">Pendapatan Hari Ini</p>
                    <p class="text-lg sm:text-2xl font-bold">Rp {{ formatNumber(stats.today_revenue) }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Quick Actions -->
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            <a href="/admin/products" class="group rounded-xl border border-gray-200 bg-white p-5 hover:border-indigo-300 hover:shadow-md transition-all">
              <div class="flex items-center">
                <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center group-hover:bg-indigo-200">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V7a2 2 0 00-2-2h-3l-2-2H9L7 5H4a2 2 0 00-2 2v6m18 0v5a2 2 0 01-2 2H4a2 2 0 01-2-2v-5m18 0H2"></path>
                  </svg>
                </div>
                <div class="ml-4">
                  <p class="text-sm text-gray-600">Kelola</p>
                  <p class="text-base font-semibold text-gray-900">Produk</p>
                </div>
              </div>
            </a>
            <a href="/admin/categories" class="group rounded-xl border border-gray-200 bg-white p-5 hover:border-emerald-300 hover:shadow-md transition-all">
              <div class="flex items-center">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center group-hover:bg-emerald-200">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                  </svg>
                </div>
                <div class="ml-4">
                  <p class="text-sm text-gray-600">Kelola</p>
                  <p class="text-base font-semibold text-gray-900">Kategori</p>
                </div>
              </div>
            </a>
            <a href="/admin/orders" class="group rounded-xl border border-gray-200 bg-white p-5 hover:border-amber-300 hover:shadow-md transition-all">
              <div class="flex items-center">
                <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center group-hover:bg-amber-200">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                  </svg>
                </div>
                <div class="ml-4">
                  <p class="text-sm text-gray-600">Pantau</p>
                  <p class="text-base font-semibold text-gray-900">Pesanan</p>
                </div>
              </div>
            </a>
          </div>
        </section>
        
        <ProductManagement v-else-if="currentPage === 'products'" />
        <CategoryManagement v-else-if="currentPage === 'categories'" />
        <OrderManagement v-else-if="currentPage === 'orders'" />
        <UserManagement v-else-if="currentPage === 'users'" />
        <RoleManagement v-else-if="currentPage === 'roles'" />
        <div v-else-if="currentPage === 'pos'" id="pos-app"></div>
      </main>
      <AdminFooter />
    </div>

    <!-- Overlay for mobile -->
    <div v-if="sidebarOpen" @click="toggleSidebar" class="fixed inset-0 z-40 lg:hidden" style="background-color: rgba(30, 41, 59, 0.7);"></div>
  </div>
</template>

<script>
import AdminSidebar from './Layout/AdminSidebar.vue';
import AdminNavbar from './Layout/AdminNavbar.vue';
import AdminFooter from './Layout/AdminFooter.vue';
export default {
  name: 'AdminDashboard',
  components: {
    AdminSidebar,
    AdminNavbar,
    AdminFooter
  },
  data() {
    return {
      user: window.user || {},
      sidebarOpen: true,
      currentPageTitle: 'Dashboard',
      currentPage: 'dashboard',
      stats: {
        total_products: 0,
        total_categories: 0,
        total_orders: 0,
        today_revenue: 0
      }
    }
  },
  mounted() {
    this.updateCurrentPage();
    this.handleHashNavigation();
    this.loadStats();
    
    // Listen for custom page change events
    window.addEventListener('admin-page-change', (event) => {
      this.currentPage = event.detail.page;
      this.currentPageTitle = event.detail.title;
    });
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
            total_products: data.total_products ?? 0,
            total_categories: data.total_categories ?? 0,
            total_orders: data.total_orders ?? 0,
            today_revenue: data.today_revenue ?? 0
          };
        }
      } catch (e) {
        console.error('Failed to load dashboard stats', e);
      }
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
    }
  }
}
</script>