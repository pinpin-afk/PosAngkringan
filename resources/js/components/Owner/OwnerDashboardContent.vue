<template>
  <div class="min-h-screen bg-gradient-to-b from-gray-50 to-white p-4 sm:p-6">
    <div class="max-w-7xl mx-auto space-y-6">
      <!-- Header Banner -->
      <div class="rounded-2xl bg-gradient-to-r from-gray-900 via-gray-800 to-gray-700 text-white p-6 shadow-lg">
        <div class="flex items-center justify-between">
          <div class="flex items-start space-x-4">
            <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center">
              <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18M7 13l3 3 7-7" />
              </svg>
            </div>
            <div>
              <h1 class="text-2xl sm:text-3xl font-bold">Owner Dashboard</h1>
              <p class="text-white/80 text-sm">Ringkasan keuangan, penjualan, stok, dan grafik</p>
            </div>
          </div>
          <div class="hidden sm:flex items-center space-x-2">
            <select class="bg-white/10 border border-white/20 text-white text-sm px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-white/30">
              <option class="bg-gray-800" value="7d">7 Hari</option>
              <option class="bg-gray-800" value="30d">30 Hari</option>
            </select>
            <button @click="loadSummary" class="px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-sm hover:bg-white/20 transition">Refresh</button>
          </div>
        </div>
      </div>

      <!-- Tabs -->
      <div class="bg-white rounded-2xl border border-gray-200 p-2 shadow-sm">
        <nav class="flex flex-wrap gap-2">
          <button @click="selectTab('ringkasan')" :class="tabClass('ringkasan')" class="px-4 py-2 rounded-lg text-sm font-medium">Ringkasan Keuangan</button>
          <button @click="selectTab('penjualan')" :class="tabClass('penjualan')" class="px-4 py-2 rounded-lg text-sm font-medium">Penjualan</button>
          <button @click="selectTab('stok')" :class="tabClass('stok')" class="px-4 py-2 rounded-lg text-sm font-medium">Stok</button>
          <button @click="selectTab('grafik')" :class="tabClass('grafik')" class="px-4 py-2 rounded-lg text-sm font-medium">Grafik</button>
        </nav>
      </div>

      <FinanceSummary v-if="activeTab === 'ringkasan'" :data="summary" />

      <ChartsSummary v-if="activeTab === 'grafik'" :revenue7="summary.revenue7" :topProducts="summary.topProducts" />

      <StockSummary v-if="activeTab === 'stok'" :lowStock="summary.lowStock" />

      <SalesSummary v-if="activeTab === 'penjualan'" :topProducts="summary.topProducts" :cards="summary.cards" />
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { Chart, registerables } from 'chart.js';
Chart.register(...registerables);
import FinanceSummary from './sections/FinanceSummary.vue'
import SalesSummary from './sections/SalesSummary.vue'
import StockSummary from './sections/StockSummary.vue'
import ChartsSummary from './sections/ChartsSummary.vue'

export default {
  name: 'OwnerDashboardContent',
  components: { FinanceSummary, SalesSummary, StockSummary, ChartsSummary },
  data() {
    return {
      summary: {
        cards: { totalRevenue: 0, todayRevenue: 0, orders: 0, avgOrderValue: 0, totalProducts: 0, totalCategories: 0 },
        lowStock: [],
        revenue7: [],
        topProducts: []
      },
      charts: {
        revenue: null,
        topProducts: null
      },
      activeTab: 'ringkasan'
    }
  },
  async mounted() {
    await this.loadSummary();
    if (this.activeTab === 'grafik') {
      this.$nextTick(() => this.initCharts());
    }
  },
  watch: {
    activeTab(newVal) {
      if (newVal === 'grafik' && !this.charts.revenue && !this.charts.topProducts) {
        this.$nextTick(() => this.initCharts());
      }
    }
  },
  beforeUnmount() {
    if (this.charts.revenue) this.charts.revenue.destroy();
    if (this.charts.topProducts) this.charts.topProducts.destroy();
  },
  methods: {
    async loadSummary() {
      const res = await axios.get('/admin/owner/summary');
      this.summary = res.data || this.summary;
    },
    initCharts() {
      const ctxRev = this.$refs.revenueChart.getContext('2d');
      const labels = this.summary.revenue7.map(d => d.date);
      const data = this.summary.revenue7.map(d => d.revenue);
      this.charts.revenue = new Chart(ctxRev, {
        type: 'line',
        data: {
          labels,
          datasets: [{
            label: 'Pendapatan',
            data,
            borderColor: '#0ea5e9',
            backgroundColor: 'rgba(14,165,233,0.15)',
            tension: 0.35,
            fill: true
          }]
        },
        options: {
          scales: {
            y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
            x: { grid: { display: false } }
          },
          plugins: {
            legend: { display: false },
            tooltip: {
              callbacks: {
                label: (ctx) => 'Rp ' + new Intl.NumberFormat('id-ID').format(ctx.parsed.y)
              }
            }
          }
        }
      });

      const ctxTop = this.$refs.topProductsChart.getContext('2d');
      const labelsTop = this.summary.topProducts.map(p => p.name);
      const dataTop = this.summary.topProducts.map(p => p.qty);
      this.charts.topProducts = new Chart(ctxTop, {
        type: 'bar',
        data: {
          labels: labelsTop,
          datasets: [{
            label: 'Qty',
            data: dataTop,
            backgroundColor: 'rgba(99,102,241,0.8)'
          }]
        },
        options: {
          scales: {
            y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
            x: { grid: { display: false } }
          },
          plugins: { legend: { display: false } }
        }
      });
    },
    selectTab(tab) {
      this.activeTab = tab;
    },
    tabClass(tab) {
      return this.activeTab === tab ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
    },
    format(n) {
      return new Intl.NumberFormat('id-ID').format(n || 0);
    }
  }
}
</script>


