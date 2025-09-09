<template>
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
      <p class="text-sm text-gray-600 mb-3">Produk Terlaris 30 Hari</p>
      <div v-if="topProducts.length === 0" class="text-sm text-gray-500">Belum ada data.</div>
      <ul v-else class="divide-y divide-gray-100">
        <li v-for="tp in topProducts" :key="tp.name" class="py-3 flex items-center justify-between">
          <div class="flex items-center space-x-3">
            <div class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-semibold">{{ tp.name.charAt(0).toUpperCase() }}</div>
            <span class="text-sm text-gray-800">{{ tp.name }}</span>
          </div>
          <span class="text-sm font-semibold text-gray-900">{{ tp.qty }}</span>
        </li>
      </ul>
    </div>
    <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
      <p class="text-sm text-gray-600 mb-2">Ringkasan</p>
      <div class="space-y-3">
        <div class="flex items-center justify-between">
          <span class="text-sm text-gray-600">Jumlah Pesanan</span>
          <span class="text-sm font-semibold text-gray-900">{{ cards.orders }}</span>
        </div>
        <div class="flex items-center justify-between">
          <span class="text-sm text-gray-600">Rata-rata Nilai</span>
          <span class="text-sm font-semibold text-gray-900">Rp {{ format(cards.avgOrderValue) }}</span>
        </div>
        <div class="flex items-center justify-between">
          <span class="text-sm text-gray-600">Pendapatan Hari Ini</span>
          <span class="text-sm font-semibold text-gray-900">Rp {{ format(cards.todayRevenue) }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'SalesSummary',
  props: { topProducts: { type: Array, required: true }, cards: { type: Object, required: true } },
  methods: {
    format(n) { return new Intl.NumberFormat('id-ID').format(n || 0) }
  }
}
</script>


