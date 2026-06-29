<template>
  <div class="mx-auto flex max-w-xl flex-col items-center space-y-6 py-8 text-center">
    <!-- Icon -->
    <div class="flex h-20 w-20 items-center justify-center rounded-full" :class="iconBg">
      <span v-html="iconSvg" :class="iconColor"></span>
    </div>

    <!-- Title -->
    <div>
      <h1 class="text-2xl font-extrabold tracking-tight text-slate-800">{{ title }}</h1>
      <p class="mt-2 text-sm leading-relaxed text-slate-500">{{ message }}</p>
    </div>

    <!-- Order info -->
    <Card class="w-full p-6 text-left">
      <div class="space-y-3">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
          <span class="text-xs text-slate-400">Order ID</span>
          <span class="text-sm font-bold text-slate-800">{{ orderId }}</span>
        </div>
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
          <span class="text-xs text-slate-400">Paket</span>
          <span class="text-sm font-bold text-slate-800">Premium Online</span>
        </div>
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
          <span class="text-xs text-slate-400">Jumlah</span>
          <span class="text-sm font-extrabold text-[#cf3d3d]">Rp199.000</span>
        </div>
        <div class="flex items-center justify-between">
          <span class="text-xs text-slate-400">Status</span>
          <Badge :variant="badgeVariant" size="sm" dot>{{ badgeLabel }}</Badge>
        </div>
      </div>
    </Card>

    <!-- Actions -->
    <div class="flex w-full flex-col gap-3 sm:flex-row">
      <Button variant="outline" class="flex-1" @click="$router.push('/client/dashboard')">Ke Dashboard</Button>
      <Button class="flex-1" @click="$router.push('/client/my-courses')">Lihat Kursus Saya</Button>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import Card from '@/components/ui/Card.vue'
import Button from '@/components/ui/Button.vue'
import Badge from '@/components/ui/Badge.vue'

const props = defineProps({
  orderId: { type: [String, Number], default: '' },
})

const route = useRoute()
const status = computed(() => route.query.status || 'success')

const title = computed(() => ({
  success: 'Pembayaran Berhasil! 🎉',
  pending: 'Menunggu Pembayaran',
  failed: 'Pembayaran Gagal',
}[status.value]))

const message = computed(() => ({
  success: 'Pembayaran kamu telah berhasil diverifikasi. Akses kursus sudah aktif. Selamat belajar!',
  pending: 'Pembayaran kamu sedang diproses. Kami akan mengonfirmasi otomatis setelah pembayaran diterima.',
  failed: 'Maaf, pembayaran gagal diproses. Silakan coba lagi atau hubungi support kami.',
}[status.value]))

const iconBg = computed(() => ({
  success: 'bg-emerald-100',
  pending: 'bg-amber-100',
  failed: 'bg-[#cf3d3d]/10',
}[status.value]))

const iconColor = computed(() => ({
  success: 'text-emerald-600',
  pending: 'text-amber-600',
  failed: 'text-[#cf3d3d]',
}[status.value]))

const badgeVariant = computed(() => ({
  success: 'success',
  pending: 'warning',
  failed: 'danger',
}[status.value]))

const badgeLabel = computed(() => ({
  success: 'Lunas',
  pending: 'Pending',
  failed: 'Gagal',
}[status.value]))

const iconSvg = computed(() => ({
  success: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-10 w-10"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`,
  pending: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-10 w-10"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`,
  failed: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-10 w-10"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`,
}[status.value]))
</script>