<template>
  <nav class="flex items-center space-x-2 text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">
    <div class="flex items-center space-x-2">
      <button
        v-for="(item, index) in breadcrumbs"
        :key="index"
        @click="navigateTo(item.path)"
        class="flex items-center space-x-1 hover:opacity-80 transition-opacity"
        :class="index === breadcrumbs.length - 1 ? 'font-medium' : ''"
      >
        <component
          :is="item.icon"
          v-if="item.icon"
          class="w-4 h-4"
        />
        <span>{{ item.label }}</span>
      </button>
    </div>
    
    <!-- Separator -->
    <svg
      v-for="(item, index) in breadcrumbs.slice(0, -1)"
      :key="`separator-${index}`"
      class="w-4 h-4 flex-shrink-0"
      :class="isDarkMode ? 'text-gray-500' : 'text-gray-400'"
      fill="none"
      stroke="currentColor"
      viewBox="0 0 24 24"
    >
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
    </svg>
  </nav>
</template>

<script>
export default {
  name: 'Breadcrumb',
  props: {
    items: {
      type: Array,
      required: true,
      default: () => []
    },
    isDarkMode: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    breadcrumbs() {
      return this.items.map(item => ({
        label: item.label,
        path: item.path,
        icon: item.icon || null
      }));
    }
  },
  methods: {
    navigateTo(path) {
      if (path && path !== '#') {
        if (path.startsWith('http')) {
          window.open(path, '_blank');
        } else {
          this.$emit('navigate', path);
        }
      }
    }
  }
}
</script>
