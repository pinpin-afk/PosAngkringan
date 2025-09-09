<template>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-gray-500">Total Pendapatan</p>
          <p class="text-2xl font-bold">Rp {{ format(data.cards.totalRevenue) }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V7m0 1v8"/></svg>
        </div>
      </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-gray-500">Pendapatan Hari Ini</p>
          <p class="text-2xl font-bold">Rp {{ format(data.cards.todayRevenue) }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18"/></svg>
        </div>
      </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-gray-500">Jumlah Pesanan</p>
          <p class="text-2xl font-bold">{{ data.cards.orders }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12l-4 10H6L4 7h4"/></svg>
        </div>
      </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-gray-500">Rata-rata Nilai Pesanan</p>
          <p class="text-2xl font-bold">Rp {{ format(data.cards.avgOrderValue) }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v8m-6-4h12"/></svg>
        </div>
      </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-gray-500">Produk</p>
          <p class="text-2xl font-bold">{{ data.cards.totalProducts }}</p>
        </div>
        <div class="w-10 h-10 rounded-2xl bg-fuchsia-50 text-fuchsia-600 flex items-center justify-center">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6"/></svg>
        </div>
      </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-gray-500">Kategori</p>
          <p class="text-2xl font-bold">{{ data.cards.totalCategories }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'FinanceSummary',
  props: { data: { type: Object, required: true } },
  methods: {
    format(n) { return new Intl.NumberFormat('id-ID').format(n || 0) }
  }
}
</script>


