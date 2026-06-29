<template>
  <div class="space-y-8 animate-fadeIn">

    <div v-if="isLoading" class="flex items-center justify-center py-24">
      <div class="h-8 w-8 animate-spin rounded-full border-2 border-zinc-700 border-t-[#cf3d3d]"></div>
    </div>

    <template v-else>
      <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
        <AdminStatCard
          v-for="(stat, index) in stats"
          :key="index"
          :label="stat.label"
          :value="stat.value"
          :value-class="index === 1 ? 'text-[#cf3d3d]' : 'text-zinc-100'"
        />
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-[#1a1a1a] rounded-2xl border border-zinc-800/40 shadow-xl p-6">
          <h3 class="text-sm font-semibold text-zinc-200 mb-5">Ringkasan User</h3>
          <div class="space-y-4">
            <div class="flex items-center justify-between">
              <span class="text-sm text-zinc-400">Total User</span>
              <span class="text-lg font-bold text-zinc-100">{{ userSummary.total }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm text-zinc-400">Siswa (Client)</span>
              <span class="text-lg font-bold text-zinc-100">{{ userSummary.client }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm text-zinc-400">Pengajar (Teacher)</span>
              <span class="text-lg font-bold text-zinc-100">{{ userSummary.teacher }}</span>
            </div>
          </div>
        </div>

        <div class="bg-[#1a1a1a] rounded-2xl border border-zinc-800/40 shadow-xl p-6">
          <h3 class="text-sm font-semibold text-zinc-200 mb-5">Ringkasan Pesanan</h3>
          <div class="space-y-4">
            <div class="flex items-center justify-between">
              <span class="text-sm text-zinc-400">Total Pesanan</span>
              <span class="text-lg font-bold text-zinc-100">{{ orderSummary.total }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm text-zinc-400">Dibayar</span>
              <span class="text-lg font-bold text-emerald-500">{{ orderSummary.paid }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm text-zinc-400">Pending</span>
              <span class="text-lg font-bold text-amber-500">{{ orderSummary.pending }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm text-zinc-400">Expired</span>
              <span class="text-lg font-bold text-zinc-600">{{ orderSummary.expired }}</span>
            </div>
          </div>
        </div>

        <div class="bg-[#1a1a1a] rounded-2xl border border-zinc-800/40 shadow-xl p-6">
          <h3 class="text-sm font-semibold text-zinc-200 mb-5">Paket & Enrollment</h3>
          <div class="space-y-4">
            <div class="flex items-center justify-between">
              <span class="text-sm text-zinc-400">Total Paket</span>
              <span class="text-lg font-bold text-zinc-100">{{ totalPackages }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm text-zinc-400">Enrollment Aktif</span>
              <span class="text-lg font-bold text-[#cf3d3d]">{{ stats[3]?.value || 0 }}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-[#1a1a1a] rounded-2xl border border-zinc-800/40 shadow-xl overflow-hidden">
        <div class="p-5 px-6 flex items-center justify-between border-b border-zinc-800/60">
          <h3 class="text-sm font-semibold text-zinc-200">Pesanan Terbaru</h3>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="text-xs font-semibold text-zinc-500 border-b border-zinc-800/50 bg-zinc-900/10">
                <th class="p-4 px-6">Order ID</th>
                <th class="p-4 px-6">User</th>
                <th class="p-4 px-6">Paket</th>
                <th class="p-4 px-6">Jumlah</th>
                <th class="p-4 px-6">Status</th>
                <th class="p-4 px-6">Waktu</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-zinc-800/50 text-sm">
              <tr v-for="(order, idx) in recentOrders" :key="idx" class="hover:bg-zinc-800/20 transition-colors">
                <td class="p-4 px-6 font-mono text-xs text-zinc-300">{{ order.order_number }}</td>
                <td class="p-4 px-6">
                  <div class="font-medium text-zinc-200">{{ order.user_name }}</div>
                  <div class="text-xs text-zinc-500">{{ order.user_email }}</div>
                </td>
                <td class="p-4 px-6 text-zinc-300">{{ order.package_name }}</td>
                <td class="p-4 px-6 font-medium text-zinc-200">Rp{{ formatNumber(order.amount) }}</td>
                <td class="p-4 px-6">
                  <span :class="statusBadge(order.status)">
                    {{ order.status }}
                  </span>
                </td>
                <td class="p-4 px-6 text-zinc-500 text-xs">{{ order.created_at }}</td>
              </tr>
              <tr v-if="recentOrders.length === 0">
                <td colspan="6" class="p-8 text-center text-sm text-zinc-500">Belum ada pesanan.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="bg-[#1a1a1a] rounded-2xl border border-zinc-800/40 shadow-xl overflow-hidden">
        <div class="p-5 px-6 flex items-center justify-between border-b border-zinc-800/60">
          <h3 class="text-sm font-semibold text-zinc-200">User Terdaftar Terbaru</h3>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="text-xs font-semibold text-zinc-500 border-b border-zinc-800/50 bg-zinc-900/10">
                <th class="p-4 px-6">Nama</th>
                <th class="p-4 px-6">Email</th>
                <th class="p-4 px-6">Role</th>
                <th class="p-4 px-6">Status</th>
                <th class="p-4 px-6">Bergabung</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-zinc-800/50 text-sm">
              <tr v-for="(user, idx) in recentUsers" :key="idx" class="hover:bg-zinc-800/20 transition-colors">
                <td class="p-4 px-6">
                  <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-[#cf3d3d]/10 border border-[#cf3d3d]/20 flex items-center justify-center text-xs font-bold text-[#cf3d3d]">
                      {{ user.name?.charAt(0).toUpperCase() }}
                    </div>
                    <span class="font-medium text-zinc-200">{{ user.name }}</span>
                  </div>
                </td>
                <td class="p-4 px-6 text-zinc-400">{{ user.email }}</td>
                <td class="p-4 px-6">
                  <span :class="roleBadge(user.role)">{{ user.role }}</span>
                </td>
                <td class="p-4 px-6">
                  <span :class="user.is_active ? 'px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-500' : 'px-3 py-1 rounded-full text-xs font-bold bg-zinc-700 text-zinc-400'">
                    {{ user.is_active ? 'Aktif' : 'Nonaktif' }}
                  </span>
                </td>
                <td class="p-4 px-6 text-zinc-500 text-xs">{{ user.created_at }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </template>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AdminStatCard from '@/components/admin/AdminStatCard.vue'

const apiBaseUrl = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000'

const isLoading = ref(true)
const stats = ref([])
const userSummary = ref({ total: 0, client: 0, teacher: 0 })
const orderSummary = ref({ total: 0, paid: 0, pending: 0, expired: 0 })
const totalPackages = ref(0)
const recentOrders = ref([])
const recentUsers = ref([])

const formatNumber = (value) => {
  return Number(value).toLocaleString('id-ID')
}

const statusBadge = (status) => {
  const map = {
    paid: 'px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-500',
    pending: 'px-3 py-1 rounded-full text-xs font-bold bg-amber-500/10 text-amber-500',
    expired: 'px-3 py-1 rounded-full text-xs font-bold bg-zinc-700 text-zinc-400',
    cancelled: 'px-3 py-1 rounded-full text-xs font-bold bg-red-500/10 text-red-500',
    failed: 'px-3 py-1 rounded-full text-xs font-bold bg-red-500/10 text-red-500',
  }
  return map[status] || 'px-3 py-1 rounded-full text-xs font-bold bg-zinc-700 text-zinc-400'
}

const roleBadge = (role) => {
  const map = {
    admin: 'px-3 py-1 rounded-full text-xs font-bold bg-[#cf3d3d]/10 text-[#cf3d3d]',
    teacher: 'px-3 py-1 rounded-full text-xs font-bold bg-blue-500/10 text-blue-500',
    client: 'px-3 py-1 rounded-full text-xs font-bold bg-zinc-700 text-zinc-300',
  }
  return map[role] || 'px-3 py-1 rounded-full text-xs font-bold bg-zinc-700 text-zinc-400'
}

const fetchDashboard = async () => {
  try {
    const token = localStorage.getItem('auth_token')
    const response = await fetch(`${apiBaseUrl}/api/admin/dashboard`, {
      headers: { 'Authorization': `Bearer ${token}` }
    })

    const data = await response.json().catch(() => ({}))

    if (response.ok && data.success) {
      const d = data.data
      stats.value = d.stats
      userSummary.value = d.user_summary
      orderSummary.value = d.order_summary
      totalPackages.value = d.total_packages
      recentOrders.value = d.recent_orders
      recentUsers.value = d.recent_users
    }
  } catch (error) {
    console.error('Gagal memuat data dashboard:', error)
  } finally {
    isLoading.value = false
  }
}

onMounted(() => {
  fetchDashboard()
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
