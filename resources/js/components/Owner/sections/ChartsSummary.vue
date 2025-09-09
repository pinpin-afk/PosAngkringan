<template>
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
      <div class="flex items-center justify-between mb-2">
        <p class="text-sm text-gray-600">Pendapatan 7 Hari</p>
      </div>
      <canvas ref="revenue"></canvas>
    </div>
    <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
      <p class="text-sm text-gray-600 mb-2">Top Produk 30 Hari</p>
      <canvas ref="top"></canvas>
    </div>
  </div>
</template>

<script>
import { Chart, registerables } from 'chart.js'
Chart.register(...registerables)

export default {
  name: 'ChartsSummary',
  props: { revenue7: { type: Array, required: true }, topProducts: { type: Array, required: true } },
  mounted() {
    const ctxRev = this.$refs.revenue.getContext('2d')
    const labels = this.revenue7.map(d => d.date)
    const data = this.revenue7.map(d => d.revenue)
    this.rev = new Chart(ctxRev, {
      type: 'line',
      data: { labels, datasets: [{ label: 'Pendapatan', data, borderColor: '#0ea5e9', backgroundColor: 'rgba(14,165,233,0.15)', tension: 0.35, fill: true }] },
      options: { scales: { y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } }, x: { grid: { display: false } } }, plugins: { legend: { display: false } } }
    })

    const ctxTop = this.$refs.top.getContext('2d')
    const labelsTop = this.topProducts.map(p => p.name)
    const dataTop = this.topProducts.map(p => p.qty)
    this.top = new Chart(ctxTop, {
      type: 'bar',
      data: { labels: labelsTop, datasets: [{ label: 'Qty', data: dataTop, backgroundColor: 'rgba(99,102,241,0.8)' }] },
      options: { scales: { y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } }, x: { grid: { display: false } } }, plugins: { legend: { display: false } } }
    })
  },
  beforeUnmount() {
    if (this.rev) this.rev.destroy()
    if (this.top) this.top.destroy()
  }
}
</script>


