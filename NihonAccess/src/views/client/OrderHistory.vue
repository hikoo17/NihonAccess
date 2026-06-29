<template>
  <div class="mx-auto max-w-6xl space-y-8">
    <!-- Header -->
    <div>
      <Breadcrumb :items="[{ label: 'Dashboard', to: '/client/dashboard' }, { label: 'Riwayat Pesanan' }]" />
      <h1 class="mt-3 text-2xl font-extrabold tracking-tight text-slate-800 sm:text-3xl">Riwayat Pesanan</h1>
      <p class="mt-1 text-sm text-slate-500">Semua transaksi pembelian paket kursus kamu.</p>
    </div>

    <!-- Filter -->
    <div class="flex items-center gap-2 overflow-x-auto">
      <button
        v-for="tab in tabs"
        :key="tab"
        @click="activeTab = tab"
        class="shrink-0 rounded-full px-4 py-2 text-xs font-bold transition"
        :class="activeTab === tab
          ? 'bg-[#cf3d3d] text-white shadow-md shadow-[#cf3d3d]/10'
          : 'bg-white text-slate-500 border border-slate-200 hover:border-[#cf3d3d]/40'"
      >
        {{ tab }}
      </button>
    </div>

    <!-- Table (desktop) -->
    <Card class="hidden overflow-hidden md:block">
      <table class="w-full">
        <thead>
          <tr class="border-b border-slate-100 bg-slate-50/50 text-left text-[10px] font-extrabold uppercase tracking-wider text-slate-400">
            <th class="px-6 py-4">Order ID</th>
            <th class="px-6 py-4">Paket</th>
            <th class="px-6 py-4">Tanggal</th>
            <th class="px-6 py-4">Jumlah</th>
            <th class="px-6 py-4">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
          <tr v-for="order in filteredOrders" :key="order.id" class="transition hover:bg-slate-50/50">
            <td class="px-6 py-4 text-xs font-bold text-slate-700">{{ order.orderNumber }}</td>
            <td class="px-6 py-4 text-xs font-medium text-slate-600">{{ order.package }}</td>
            <td class="px-6 py-4 text-xs text-slate-400">{{ order.date }}</td>
            <td class="px-6 py-4 text-xs font-extrabold text-slate-800">{{ order.amount }}</td>
            <td class="px-6 py-4">
              <Badge :variant="statusVariant(order.status)" size="sm" dot>{{ statusLabel(order.status) }}</Badge>
            </td>
          </tr>
        </tbody>
      </table>
    </Card>

    <!-- Cards (mobile) -->
    <div class="space-y-3 md:hidden">
      <Card v-for="order in filteredOrders" :key="order.id" class="p-4">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-xs font-bold text-slate-700">{{ order.package }}</p>
            <p class="mt-0.5 text-[11px] text-slate-400">{{ order.orderNumber }}</p>
            <p class="mt-1 text-[11px] text-slate-400">{{ order.date }}</p>
          </div>
          <div class="text-right">
            <p class="text-xs font-extrabold text-slate-800">{{ order.amount }}</p>
            <div class="mt-1">
              <Badge :variant="statusVariant(order.status)" size="sm" dot>{{ statusLabel(order.status) }}</Badge>
            </div>
          </div>
        </div>
      </Card>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import Breadcrumb from '@/components/ui/Breadcrumb.vue'
import Card from '@/components/ui/Card.vue'
import Badge from '@/components/ui/Badge.vue'

const tabs = ['Semua', 'Lunas', 'Pending', 'Gagal']
const activeTab = ref('Semua')

const orders = [
  { id: 1, orderNumber: 'NA-001-abcd1234', package: 'Premium Online', date: '29 Jun 2026', amount: 'Rp199.000', status: 'paid' },
  { id: 2, orderNumber: 'NA-002-efgh5678', package: 'Basic Online', date: '28 Jun 2026', amount: 'Rp99.000', status: 'pending' },
  { id: 3, orderNumber: 'NA-003-ijkl9012', package: 'Private Online', date: '20 Jun 2026', amount: 'Rp499.000', status: 'failed' },
  { id: 4, orderNumber: 'NA-004-mnop3456', package: 'Premium Online', date: '15 Jun 2026', amount: 'Rp199.000', status: 'paid' },
]

const filteredOrders = computed(() => {
  if (activeTab.value === 'Semua') return orders
  const map = { 'Lunas': 'paid', 'Pending': 'pending', 'Gagal': 'failed' }
  return orders.filter((o) => o.status === map[activeTab.value])
})

const statusVariant = (status) => ({
  paid: 'success', pending: 'warning', failed: 'danger', expired: 'neutral',
}[status])

const statusLabel = (status) => ({
  paid: 'Lunas', pending: 'Pending', failed: 'Gagal', expired: 'Kedaluwarsa',
}[status])
</script>