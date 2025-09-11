<template>
  <footer class="mt-auto">
    <div class="px-4 py-4 text-sm border-t transition-colors duration-300"
         :class="isDarkMode ? 'text-gray-400 bg-gray-800 border-gray-700' : 'text-gray-500 bg-white border-gray-200'">
      <div class="flex items-center justify-between">
        <span>Sistem POS Angkringan</span>
        <span>&copy; {{ new Date().getFullYear() }} All rights reserved</span>
      </div>
    </div>
  </footer>
</template>

<script>
export default {
  name: 'AdminFooter',
  props: {
    isDarkMode: { type: Boolean, default: false }
  }
}
</script>


