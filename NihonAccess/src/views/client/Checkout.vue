<template>
  <div class="mx-auto max-w-5xl space-y-8">
    <!-- Breadcrumb -->
    <Breadcrumb :items="[
      { label: 'Dashboard', to: '/client/dashboard' },
      { label: 'Beli Paket', to: '/client/packages' },
      { label: 'Checkout' },
    ]" />

    <div>
      <h1 class="text-2xl font-extrabold tracking-tight text-slate-800 sm:text-3xl">Checkout</h1>
      <p class="mt-1 text-sm text-slate-500">Lengkapi pembayaran untuk mengaktifkan paket kursus kamu.</p>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
      <!-- Left: form & payment method -->
      <div class="space-y-6 lg:col-span-2">
        <!-- Participant data -->
        <Card class="p-6">
          <h2 class="mb-4 text-sm font-extrabold uppercase tracking-wider text-slate-400">Data Peserta</h2>
          <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
              <Label>Nama Lengkap</Label>
              <Input modelValue="Budi Santoso" />
            </div>
            <div>
              <Label>Email</Label>
              <Input modelValue="budi@email.com" />
            </div>
            <div class="sm:col-span-2">
              <Label>Nomor WhatsApp</Label>
              <Input modelValue="081234567890" />
            </div>
          </div>
        </Card>

        <!-- Payment method -->
        <Card class="p-6">
          <h2 class="mb-4 text-sm font-extrabold uppercase tracking-wider text-slate-400">Metode Pembayaran</h2>
          <div class="space-y-3">
            <label
              v-for="method in paymentMethods"
              :key="method.id"
              class="flex cursor-pointer items-center gap-3 rounded-2xl border-2 p-4 transition"
              :class="selectedMethod === method.id
                ? 'border-[#cf3d3d] bg-[#cf3d3d]/5'
                : 'border-slate-200 hover:border-slate-300'"
            >
              <input
                v-model="selectedMethod"
                :value="method.id"
                type="radio"
                class="h-4 w-4 accent-[#cf3d3d]"
              />
              <div class="flex-1">
                <p class="text-sm font-bold text-slate-800">{{ method.name }}</p>
                <p class="text-xs text-slate-400">{{ method.desc }}</p>
              </div>
              <span class="text-2xl">{{ method.icon }}</span>
            </label>
          </div>
        </Card>
      </div>

      <!-- Right: summary -->
      <div class="lg:col-span-1">
        <div class="sticky top-24">
          <CheckoutSummary :pkg="pkg" />
          <Button class="mt-4 w-full" size="lg" :disabled="isProcessing" @click="handlePay">
            {{ isProcessing ? 'Memproses...' : 'Bayar Sekarang' }}
          </Button>
          <p class="mt-3 text-center text-[11px] text-slate-400">
            Dengan melanjutkan, kamu menyetujui Syarat & Ketentuan.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import Breadcrumb from '@/components/ui/Breadcrumb.vue'
import Card from '@/components/ui/Card.vue'
import Button from '@/components/ui/Button.vue'
import Input from '@/components/ui/Input.vue'
import Label from '@/components/ui/Label.vue'
import CheckoutSummary from '@/components/Client/CheckoutSummary.vue'

const props = defineProps({
  packageId: { type: [String, Number], default: '' },
})

const router = useRouter()
const isProcessing = ref(false)
const selectedMethod = ref('midtrans')

const pkg = {
  name: 'Premium Online',
  duration: '3 bulan',
  price: 'Rp199.000',
}

const paymentMethods = [
  { id: 'midtrans', name: 'Midtrans Snap', desc: 'Kartu kredit, VA, e-wallet, dll', icon: '💳' },
  { id: 'bank-transfer', name: 'Transfer Bank', desc: 'BCA, BNI, BRI, Mandiri', icon: '🏦' },
  { id: 'ewallet', name: 'E-Wallet', desc: 'GoPay, OVO, DANA, ShopeePay', icon: '📱' },
]

const handlePay = async () => {
  isProcessing.value = true
  // Simulasi proses pembayaran
  setTimeout(() => {
    isProcessing.value = false
    router.push({ name: 'client-payment-status', params: { orderId: 'NA-001-abcd1234' }, query: { status: 'success' } })
  }, 1500)
}
</script>