<template>
  <header>
    <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600">
      <div class="flex items-center justify-between px-3 sm:px-4 py-3 sm:py-4">
        <div class="flex items-center min-w-0 flex-1">
          <button @click="$emit('toggle')" class="p-2 rounded-md text-white/90 hover:text-white hover:bg-white/10 flex-shrink-0">
            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
          </button>
          <div class="ml-2 sm:ml-4 min-w-0 flex-1">
            <h2 class="text-lg sm:text-xl font-semibold text-white truncate">{{ title }}</h2>
            <p class="text-xs sm:text-sm text-white/80 truncate" v-if="subtitle">{{ subtitle }}</p>
          </div>
        </div>
        <div class="flex items-center space-x-2 sm:space-x-4 flex-shrink-0">
          <span class="text-xs sm:text-sm text-white/90 hidden sm:inline">Halo, {{ user?.name }}</span>
          <span class="text-xs sm:text-sm text-white/90 sm:hidden">{{ user?.name?.split(' ')[0] }}</span>
          <button @click="$emit('logout')" class="text-xs sm:text-sm text-white bg-white/10 hover:bg-white/20 px-2 sm:px-3 py-1 rounded-md transition-colors duration-150">
            <span class="hidden sm:inline">Logout</span>
            <span class="sm:hidden">Keluar</span>
          </button>
        </div>
      </div>
    </div>
    <div class="bg-white border-b border-gray-200"></div>
  </header>
</template>

<script>
export default {
  name: 'AdminNavbar',
  props: {
    title: { type: String, default: 'Dashboard' },
    subtitle: { type: String, default: '' },
    user: { type: Object, default: () => ({}) }
  }
}
</script>


