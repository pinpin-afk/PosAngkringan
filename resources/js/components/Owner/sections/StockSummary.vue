<template>
  <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
    <p class="text-sm text-gray-600 mb-3">Stok Menipis (≤5)</p>
    <div v-if="lowStock.length === 0" class="text-sm text-gray-500">Semua stok aman.</div>
    <ul v-else class="divide-y divide-gray-100">
      <li v-for="p in lowStock" :key="p.id" class="py-2 flex items-center justify-between">
        <span class="text-sm text-gray-800 truncate">{{ p.name }}</span>
        <span class="text-xs px-2 py-1 rounded-full bg-red-100 text-red-700">{{ p.stock }}</span>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: 'StockSummary',
  props: { lowStock: { type: Array, required: true } }
}
</script>


