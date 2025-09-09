<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Mobile Topbar -->
    <div class="lg:hidden sticky top-0 z-50 bg-gray-900 text-white flex items-center justify-between px-4 py-3">
      <div class="flex items-center space-x-2">
        <button @click="open = !open" class="p-2 bg-white/10 rounded-lg">☰</button>
        <span class="font-semibold">Owner</span>
      </div>
      <button @click="refresh" class="px-3 py-1.5 bg-white/10 rounded-lg text-xs">Refresh</button>
    </div>

    <!-- Sidebar -->
    <OwnerSidebar :active="activeTab" :open="open" @navigate="navigate" />

    <!-- Content -->
    <main class="lg:pl-64">
      <OwnerDashboardContent ref="content" />
      <OwnerFooter />
    </main>
  </div>
</template>

<script>
import OwnerSidebar from './Layout/OwnerSidebar.vue'
import OwnerFooter from './Layout/OwnerFooter.vue'
import OwnerDashboardContent from './OwnerDashboardContent.vue'

export default {
  name: 'OwnerDashboardLayout',
  components: { OwnerSidebar, OwnerFooter, OwnerDashboardContent },
  data() {
    return { open: false, activeTab: 'ringkasan' }
  },
  methods: {
    navigate(tab) {
      this.activeTab = tab
      // Forward to inner content component if it supports tab change
      if (this.$refs.content && this.$refs.content.selectTab) {
        this.$refs.content.selectTab(tab)
      }
      if (this.open) this.open = false
    },
    refresh() {
      if (this.$refs.content && this.$refs.content.loadSummary) {
        this.$refs.content.loadSummary()
      }
    }
  }
}
</script>


