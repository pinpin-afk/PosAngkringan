<template>
  <aside :class="['fixed inset-y-0 left-0 z-40 w-64 transform transition-transform duration-200 lg:translate-x-0', open ? 'translate-x-0' : '-translate-x-full']">
    <div class="h-full bg-gray-900 text-white flex flex-col">
      <div class="flex items-center space-x-3 p-4 border-b border-white/10">
        <img src="/assets/img/WhatsApp Image 2025-09-07 at 17.12.58.jpeg" class="w-9 h-9 rounded-lg object-cover" alt="Logo">
        <div>
          <p class="text-sm text-white/70">Owner</p>
          <h1 class="text-base font-bold">Angkringan</h1>
        </div>
      </div>
      <nav class="flex-1 p-3 space-y-1">
        <button @click="$emit('navigate','ringkasan')" :class="itemClass('ringkasan')" class="w-full flex items-center space-x-3 px-3 py-2 rounded-lg">
          <span>Ringkasan Keuangan</span>
        </button>
        <button @click="$emit('navigate','penjualan')" :class="itemClass('penjualan')" class="w-full flex items-center space-x-3 px-3 py-2 rounded-lg">
          <span>Penjualan</span>
        </button>
        <button @click="$emit('navigate','stok')" :class="itemClass('stok')" class="w-full flex items-center space-x-3 px-3 py-2 rounded-lg">
          <span>Stok</span>
        </button>
        <button @click="$emit('navigate','grafik')" :class="itemClass('grafik')" class="w-full flex items-center space-x-3 px-3 py-2 rounded-lg">
          <span>Grafik</span>
        </button>
      </nav>
      <div class="p-3 border-t border-white/10 text-xs text-white/60">© {{ new Date().getFullYear() }} Angkringan</div>
    </div>
  </aside>
</template>

<script>
export default {
  name: 'OwnerSidebar',
  props: { active: { type: String, default: 'ringkasan' }, open: { type: Boolean, default: false } },
  methods: {
    itemClass(key) {
      return this.active === key ? 'bg-white/10 text-white' : 'text-white/80 hover:bg-white/5'
    }
  }
}
</script>


