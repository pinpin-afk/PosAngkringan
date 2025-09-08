<template>
  <header>
    <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600">
      <div class="flex items-center justify-between px-4 py-4">
        <div class="flex items-center">
          <button @click="$emit('toggle')" class="p-2 rounded-md text-white/90 hover:text-white hover:bg-white/10">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
          </button>
          <div class="ml-4">
            <h2 class="text-xl font-semibold text-white">{{ title }}</h2>
            <p class="text-sm text-white/80" v-if="subtitle">{{ subtitle }}</p>
          </div>
        </div>
        <div class="flex items-center space-x-4">
          <span class="text-sm text-white/90">Halo, {{ user?.name }}</span>
          <button @click="$emit('logout')" class="text-sm text-white bg-white/10 hover:bg-white/20 px-3 py-1 rounded-md">Logout</button>
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


