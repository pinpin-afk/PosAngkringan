<template>
  <!-- Mobile overlay -->
  <div v-if="open" class="fixed inset-0 z-40 lg:hidden" style="background-color: rgba(30, 41, 59, 0.7);" @click="$emit('toggle')"></div>
  
  <div class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-800 transform transition-transform duration-300 ease-in-out lg:translate-x-0"
       :class="{ '-translate-x-full': !open, 'translate-x-0': open }">
    <div class="flex items-center justify-between h-16 bg-gray-900 px-4">
      <div class="flex items-center space-x-3 min-w-0">
        <img 
          src="/assets/img/WhatsApp Image 2025-09-07 at 17.12.58.jpeg" 
          alt="Logo Angkringan" 
          class="w-8 h-8 rounded-lg object-cover flex-shrink-0"
        >
        <div class="min-w-0 flex-1">
          <h1 class="text-lg font-bold text-white truncate">Dashboard</h1>
          <p class="text-xs text-gray-300 truncate">Angkringan</p>
        </div>
      </div>
      <button @click="$emit('toggle')" class="lg:hidden text-white hover:text-gray-300">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>
    </div>

    <nav class="mt-8">
      <div class="px-4 space-y-2">
        <a href="/admin/dashboard"
           class="flex items-center px-4 py-3 text-sm font-medium text-white rounded-md hover:bg-gray-700 transition-colors duration-150"
           :class="{ 'bg-gray-700': currentPage === 'dashboard' }">
          <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
          </svg>
          <span class="truncate">Dashboard</span>
        </a>
        <a href="/admin/products"
           class="flex items-center px-4 py-3 text-sm font-medium text-white rounded-md hover:bg-gray-700 transition-colors duration-150"
           :class="{ 'bg-gray-700': currentPage === 'products' }">
          <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V7a2 2 0 00-2-2h-3l-2-2H9L7 5H4a2 2 0 00-2 2v6m18 0v5a2 2 0 01-2 2H4a2 2 0 01-2-2v-5m18 0H2"></path>
          </svg>
          <span class="truncate">Produk</span>
        </a>
        <a href="/admin/categories"
           class="flex items-center px-4 py-3 text-sm font-medium text-white rounded-md hover:bg-gray-700 transition-colors duration-150"
           :class="{ 'bg-gray-700': currentPage === 'categories' }">
          <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
          </svg>
          <span class="truncate">Kategori</span>
        </a>
        <a href="/admin/orders"
           class="flex items-center px-4 py-3 text-sm font-medium text-white rounded-md hover:bg-gray-700 transition-colors duration-150"
           :class="{ 'bg-gray-700': currentPage === 'orders' }">
          <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
          </svg>
          <span class="truncate">Pesanan</span>
        </a>
        <a href="/admin/users"
           class="flex items-center px-4 py-3 text-sm font-medium text-white rounded-md hover:bg-gray-700 transition-colors duration-150"
           :class="{ 'bg-gray-700': currentPage === 'users' }">
          <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
          </svg>
          <span class="truncate">User Management</span>
        </a>
        <a href="/admin/members"
           class="flex items-center px-4 py-3 text-sm font-medium text-white rounded-md hover:bg-gray-700 transition-colors duration-150"
           :class="{ 'bg-gray-700': currentPage === 'members' }">
          <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
          </svg>
          <span class="truncate">Member Management</span>
        </a>
        <a href="/admin/roles"
           class="flex items-center px-4 py-3 text-sm font-medium text-white rounded-md hover:bg-gray-700 transition-colors duration-150"
           :class="{ 'bg-gray-700': currentPage === 'roles' }">
          <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
          </svg>
          <span class="truncate">Role Management</span>
        </a>
        <a href="/admin/pos"
           class="flex items-center px-4 py-3 text-sm font-medium text-white rounded-md hover:bg-gray-700 transition-colors duration-150"
           :class="{ 'bg-gray-700': currentPage === 'pos' }">
          <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
          </svg>
          <span class="truncate">POS Kasir</span>
        </a>
      </div>
    </nav>
  </div>
</template>

<script>
export default {
  name: 'AdminSidebar',
  props: {
    open: { type: Boolean, default: true },
    currentPage: { type: String, default: 'dashboard' }
  }
}
</script>


