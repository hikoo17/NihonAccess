<template>
  <div class="mx-auto max-w-7xl space-y-6 animate-fadeIn">
    <div v-if="isLoading" class="space-y-6">
      <!-- Stat cards skeleton -->
      <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
        <SkeletonStatCard v-for="n in 4" :key="`sk-stat-${n}`" />
      </div>

      <!-- Charts skeleton -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 rounded-[2rem] border border-slate-100 bg-white shadow-sm p-6">
          <Skeleton width="40%" height="0.875rem" />
          <Skeleton width="25%" height="0.75rem" class="mt-2" />
          <Skeleton width="100%" height="280px" rounded="xl" class="mt-4" />
        </div>
        <div class="rounded-[2rem] border border-slate-100 bg-white shadow-sm p-6">
          <Skeleton width="50%" height="0.875rem" />
          <Skeleton width="30%" height="0.75rem" class="mt-2" />
          <Skeleton width="100%" height="280px" rounded="xl" class="mt-4" />
        </div>
      </div>

      <!-- Summary cards skeleton -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div v-for="n in 3" :key="`sk-sum-${n}`" class="rounded-[2rem] border border-slate-100 bg-white shadow-sm p-6">
          <Skeleton width="45%" height="0.875rem" />
          <div class="mt-5 space-y-4">
            <div v-for="m in 3" :key="m" class="flex items-center justify-between">
              <Skeleton width="35%" height="0.875rem" />
              <Skeleton width="4rem" height="1.25rem" />
            </div>
          </div>
        </div>
      </div>

      <!-- Tables skeleton -->
      <div class="rounded-[2rem] border border-slate-100 bg-white shadow-sm overflow-hidden">
        <div class="p-5 px-6 border-b border-slate-100">
          <Skeleton width="30%" height="0.875rem" />
        </div>
        <div v-for="n in 4" :key="`sk-row-${n}`" class="flex items-center gap-4 p-4 px-6 border-b border-slate-50">
          <Skeleton width="2rem" height="2rem" rounded="lg" />
          <div class="flex-1 space-y-2">
            <Skeleton width="45%" />
            <Skeleton width="30%" height="0.625rem" />
          </div>
          <Skeleton width="5rem" />
          <Skeleton width="4rem" height="1.25rem" rounded="full" />
          <Skeleton width="3.5rem" height="0.75rem" />
        </div>
      </div>
    </div>

    <template v-else>
      <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
        <AdminStatCard :label="stats[0]?.label" :value="stats[0]?.value">
          <template #icon>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.243m15.15 6.592a3 3 0 00-1.539-2.625 9.337 9.337 0 00-4.121-.952 9.337 9.337 0 00-4.121.952 3 3 0 00-1.539 2.625M15 6.75a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
          </template>
        </AdminStatCard>
        <AdminStatCard :label="stats[1]?.label" :value="stats[1]?.value" is-primary>
          <template #icon>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.627.282 1.353.282 1.98 0A60.07 60.07 0 0121.75 18.75M16.5 6v.75m0 0a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
            </svg>
          </template>
        </AdminStatCard>
        <AdminStatCard :label="stats[2]?.label" :value="stats[2]?.value">
          <template #icon>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z" />
            </svg>
          </template>
        </AdminStatCard>
        <AdminStatCard :label="stats[3]?.label" :value="stats[3]?.value">
          <template #icon>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.62 48.62 0 0012 20.904a48.62 48.62 0 008.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.636 50.636 0 00-2.658 8.09M4.26 10.147a48.628 48.628 0 0013.115-1.697M4.26 10.148a48.628 48.628 0 0113.115-1.697m0 0a48.628 48.628 0 0113.115 1.697M19.5 8.25v.75a2.25 2.25 0 01-1.672 2.18 48.514 48.514 0 00-6.621 2.18M5.25 8.25v.75a2.25 2.25 0 001.672 2.18 48.514 48.514 0 016.621 2.18M5.25 8.25a48.628 48.628 0 0113.115-1.697M19.5 8.25a48.628 48.628 0 00-13.115-1.697" />
            </svg>
          </template>
        </AdminStatCard>
      </div>

      <!-- Charts Section -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Area Chart -->
        <div class="lg:col-span-2 bg-white rounded-[2rem] border border-slate-100 shadow-sm p-6">
          <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <div>
              <h3 class="text-sm font-bold text-slate-800">Tren Pesanan & Pendapatan</h3>
              <p class="text-xs text-slate-400">{{ periodSubtitle }}</p>
            </div>
            <div class="inline-flex rounded-xl bg-slate-100 p-1">
              <button
                v-for="opt in periodOptions"
                :key="opt.value"
                type="button"
                :disabled="chartLoading"
                @click="changePeriod(opt.value)"
                :class="[
                  'px-3 py-1.5 text-xs font-bold rounded-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed',
                  chartPeriod === opt.value
                    ? 'bg-white text-[#cf3d3d] shadow-sm'
                    : 'text-slate-500 hover:text-slate-700'
                ]"
              >
                {{ opt.label }}
              </button>
            </div>
          </div>
          <apexchart
            v-if="trendSeries.length"
            type="area"
            height="280"
            :options="trendChartOptions"
            :series="trendSeries"
          />
          <div v-else class="flex h-[280px] items-center justify-center text-sm text-slate-400">Memuat grafik...</div>
        </div>

        <!-- Donut Chart -->
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-6">
          <h3 class="mb-1 text-sm font-bold text-slate-800">Distribusi User</h3>
          <p class="mb-2 text-xs text-slate-400">Berdasarkan role</p>
          <apexchart
            v-if="roleSeries.length"
            type="donut"
            height="280"
            :options="roleChartOptions"
            :series="roleSeries"
          />
          <div v-else class="flex h-[280px] items-center justify-center text-sm text-slate-400">Memuat grafik...</div>
        </div>
      </div>

      <!-- Summary Cards Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Summary -->
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-6">
          <h3 class="text-sm font-bold text-slate-800 mb-5">Ringkasan User</h3>
          <div class="space-y-4">
            <div class="flex items-center justify-between">
              <span class="text-sm text-slate-500">Total User</span>
              <span class="text-lg font-extrabold text-slate-800">{{ userSummary.total }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm text-slate-500">Siswa (Client)</span>
              <span class="text-lg font-extrabold text-slate-800">{{ userSummary.client }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm text-slate-500">Pengajar (Teacher)</span>
              <span class="text-lg font-extrabold text-slate-800">{{ userSummary.teacher }}</span>
            </div>
          </div>
        </div>

        <!-- Order Summary -->
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-6">
          <h3 class="text-sm font-bold text-slate-800 mb-5">Ringkasan Pesanan</h3>
          <div class="space-y-4">
            <div class="flex items-center justify-between">
              <span class="text-sm text-slate-500">Total Pesanan</span>
              <span class="text-lg font-extrabold text-slate-800">{{ orderSummary.total }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm text-slate-500">Dibayar</span>
              <span class="text-lg font-extrabold text-emerald-600">{{ orderSummary.paid }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm text-slate-500">Pending</span>
              <span class="text-lg font-extrabold text-amber-600">{{ orderSummary.pending }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm text-slate-500">Expired</span>
              <span class="text-lg font-extrabold text-slate-400">{{ orderSummary.expired }}</span>
            </div>
          </div>
        </div>

        <!-- Package Summary -->
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-6">
          <h3 class="text-sm font-bold text-slate-800 mb-5">Paket & Enrollment</h3>
          <div class="space-y-4">
            <div class="flex items-center justify-between">
              <span class="text-sm text-slate-500">Total Paket</span>
              <span class="text-lg font-extrabold text-slate-800">{{ totalPackages }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm text-slate-500">Enrollment Aktif</span>
              <span class="text-lg font-extrabold text-[#cf3d3d]">{{ stats[3]?.value || 0 }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Orders Table -->
      <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-5 px-6 flex items-center justify-between border-b border-slate-100">
          <h3 class="text-sm font-bold text-slate-800">Pesanan Terbaru</h3>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="text-xs font-bold text-slate-400 border-b border-slate-100 bg-slate-50/50">
                <th class="p-4 px-6">Order ID</th>
                <th class="p-4 px-6">User</th>
                <th class="p-4 px-6">Paket</th>
                <th class="p-4 px-6">Jumlah</th>
                <th class="p-4 px-6">Status</th>
                <th class="p-4 px-6">Waktu</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 text-sm">
              <tr v-for="(order, idx) in recentOrders" :key="idx" class="hover:bg-slate-50 transition-colors">
                <td class="p-4 px-6 font-mono text-xs text-slate-600">{{ order.order_number }}</td>
                <td class="p-4 px-6">
                  <div class="font-bold text-slate-800">{{ order.user_name }}</div>
                  <div class="text-xs text-slate-400">{{ order.user_email }}</div>
                </td>
                <td class="p-4 px-6 text-slate-700">{{ order.package_name }}</td>
                <td class="p-4 px-6 font-bold text-slate-800">Rp{{ formatNumber(order.amount) }}</td>
                <td class="p-4 px-6">
                  <span :class="statusBadge(order.status)">
                    {{ order.status }}
                  </span>
                </td>
                <td class="p-4 px-6 text-slate-400 text-xs">{{ order.created_at }}</td>
              </tr>
              <tr v-if="recentOrders.length === 0">
                <td colspan="6" class="p-8 text-center text-sm text-slate-400">Belum ada pesanan.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Recent Users Table -->
      <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-5 px-6 flex items-center justify-between border-b border-slate-100">
          <h3 class="text-sm font-bold text-slate-800">User Terdaftar Terbaru</h3>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="text-xs font-bold text-slate-400 border-b border-slate-100 bg-slate-50/50">
                <th class="p-4 px-6">Nama</th>
                <th class="p-4 px-6">Email</th>
                <th class="p-4 px-6">Role</th>
                <th class="p-4 px-6">Status</th>
                <th class="p-4 px-6">Bergabung</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 text-sm">
              <tr v-for="(user, idx) in recentUsers" :key="idx" class="hover:bg-slate-50 transition-colors">
                <td class="p-4 px-6">
                  <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-[#cf3d3d]/10 flex items-center justify-center text-xs font-bold text-[#cf3d3d]">
                      {{ user.name?.charAt(0).toUpperCase() }}
                    </div>
                    <span class="font-bold text-slate-800">{{ user.name }}</span>
                  </div>
                </td>
                <td class="p-4 px-6 text-slate-500">{{ user.email }}</td>
                <td class="p-4 px-6">
                  <span :class="roleBadge(user.role)">{{ user.role }}</span>
                </td>
                <td class="p-4 px-6">
                  <span :class="user.is_active ? 'px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700' : 'px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500'">
                    {{ user.is_active ? 'Aktif' : 'Nonaktif' }}
                  </span>
                </td>
                <td class="p-4 px-6 text-slate-400 text-xs">{{ user.created_at }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </template>

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import Breadcrumb from '@/components/ui/Breadcrumb.vue'
import Skeleton from '@/components/ui/Skeleton.vue'
import AdminStatCard from '@/components/admin/AdminStatCard.vue'
import SkeletonStatCard from '@/components/admin/SkeletonStatCard.vue'
import { fireToast } from '@/lib/swal.js'

const flash = (type, message) => fireToast(type, message)
const apiBaseUrl = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000'

const isLoading = ref(true)
const stats = ref([])
const userSummary = ref({ total: 0, client: 0, teacher: 0 })
const orderSummary = ref({ total: 0, paid: 0, pending: 0, expired: 0 })
const totalPackages = ref(0)
const recentOrders = ref([])
const recentUsers = ref([])

const chart = ref({ months: [], orders: [], revenue: [], role_distribution: [], status_distribution: [] })
const chartPeriod = ref('month')
const chartLoading = ref(false)

const periodOptions = [
  { value: 'day', label: 'Hari' },
  { value: 'week', label: 'Minggu' },
  { value: 'month', label: 'Bulan' },
]

const periodSubtitle = computed(() => {
  const map = { day: '7 hari terakhir', week: '6 minggu terakhir', month: '6 bulan terakhir' }
  return map[chartPeriod.value] || ''
})

const changePeriod = async (period) => {
  if (chartPeriod.value === period || chartLoading.value) return
  chartPeriod.value = period
  await fetchChartData()
}

const formatNumber = (value) => {
  return Number(value).toLocaleString('id-ID')
}

const statusBadge = (status) => {
  const map = {
    paid: 'px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700',
    pending: 'px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700',
    expired: 'px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500',
    cancelled: 'px-3 py-1 rounded-full text-xs font-bold bg-[#cf3d3d]/10 text-[#cf3d3d]',
    failed: 'px-3 py-1 rounded-full text-xs font-bold bg-[#cf3d3d]/10 text-[#cf3d3d]',
  }
  return map[status] || 'px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500'
}

const roleBadge = (role) => {
  const map = {
    admin: 'px-3 py-1 rounded-full text-xs font-bold bg-[#cf3d3d]/10 text-[#cf3d3d]',
    teacher: 'px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700',
    client: 'px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600',
  }
  return map[role] || 'px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500'
}

// Chart Configurations (Menyesuaikan Aksen Merah)
const trendSeries = computed(() => {
  if (!chart.value.months.length) return []
  return [
    { name: 'Pesanan', data: chart.value.orders },
    { name: 'Pendapatan (Rp ribu)', data: chart.value.revenue.map((v) => Math.round(v / 1000)) },
  ]
})

const trendChartOptions = computed(() => ({
  chart: { toolbar: { show: false }, fontFamily: 'Inter, sans-serif', foreColor: '#94a3b8' },
  colors: ['#cf3d3d', '#3b82f6'],
  dataLabels: { enabled: false },
  stroke: { curve: 'smooth', width: [3, 2] },
  fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: [0.35, 0.15], opacityTo: [0.05, 0], stops: [0, 90] } },
  grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
  xaxis: { categories: chart.value.months, axisBorder: { show: false }, axisTicks: { show: false } },
  yaxis: [{ labels: { style: { colors: '#94a3b8' } } }, { opposite: true, labels: { style: { colors: '#94a3b8' } } }],
  legend: { position: 'top', horizontalAlign: 'right', fontWeight: 700, fontSize: '12px' },
  tooltip: { shared: true },
}))

const roleSeries = computed(() => chart.value.role_distribution.map((r) => r.value))

const roleChartOptions = computed(() => ({
  chart: { fontFamily: 'Inter, sans-serif' },
  labels: chart.value.role_distribution.map((r) => r.label),
  colors: ['#cf3d3d', '#3b82f6', '#f59e0b'],
  legend: { position: 'bottom', fontWeight: 700, fontSize: '12px' },
  dataLabels: { enabled: true, style: { fontWeight: 700 } },
  plotOptions: { pie: { donut: { size: '68%' } } },
  stroke: { width: 0 },
}))

// Fetch Data
const fetchDashboard = async () => {
  try {
    const token = localStorage.getItem('auth_token')
    const response = await fetch(`${apiBaseUrl}/api/admin/dashboard`, {
      headers: { 'Authorization': `Bearer ${token}` }
    })
    const data = await response.json().catch(() => ({}))

    if (response.ok && data.success) {
      const d = data.data
      
      // Map API data agar support meta data tren (Gaya image_b6faa2.png)
      const mockMeta = [
        { trend: '2.05', trendType: 'up', subtext: 'Pesanan vs bln lalu' },
        { trend: '12.4', trendType: 'up', subtext: 'Target vs bln lalu' },
        { trend: '2.01', trendType: 'down', subtext: 'User vs bln lalu' },
        { trend: '12.1', trendType: 'up', subtext: 'Aktif vs bln lalu' }
      ]

      stats.value = d.stats.map((item, index) => ({
        ...item,
        trend: mockMeta[index]?.trend || '0',
        trendType: mockMeta[index]?.trendType || 'up',
        subtext: mockMeta[index]?.subtext || 'vs bln lalu'
      }))

      userSummary.value = d.user_summary
      orderSummary.value = d.order_summary
      totalPackages.value = d.total_packages
      recentOrders.value = d.recent_orders
      recentUsers.value = d.recent_users
    }
  } catch (error) {
    flash('error', 'Gagal memuat data dashboard.')
  } finally {
    isLoading.value = false
  }
}

const fetchChartData = async () => {
  chartLoading.value = true
  try {
    const token = localStorage.getItem('auth_token')
    const response = await fetch(`${apiBaseUrl}/api/admin/chart-data?period=${chartPeriod.value}`, {
      headers: { 'Authorization': `Bearer ${token}` }
    })
    const data = await response.json().catch(() => ({}))

    if (response.ok && data.success) {
      chart.value = data.data
    }
  } catch (error) {
    flash('error', 'Gagal memuat data chart.')
  } finally {
    chartLoading.value = false
  }
}

onMounted(() => {
  fetchDashboard()
  fetchChartData()
})
</script>

<style scoped>
.animate-fadeIn {
  animation: fadeIn 0.35s ease-out forwards;
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(4px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>