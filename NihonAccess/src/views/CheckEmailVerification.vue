<template>
  <div class="min-h-screen bg-stone-50">

    <section class="flex min-h-screen items-center justify-center px-4 py-16 sm:px-6">
      <!-- Card dengan border tipis warna merah muda -->
      <Card class="w-full max-w-xl p-6 text-center shadow-sm sm:p-10 border border-red-100">
        
        <!-- Icon: Menggunakan palet merah [#cf3d3d] -->
        <div 
          :class="[
            'mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-2xl transition-all duration-300',
            isSynced ? 'bg-red-50 text-[#cf3d3d]' : 'bg-stone-100 text-stone-600'
          ]"
        >
          <svg v-if="isSynced" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-8 w-8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-8 w-8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5A2.25 2.25 0 0 1 19.5 19.5h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0l-7.5-4.615a2.25 2.25 0 0 1-1.07-1.916V6.75" />
          </svg>
        </div>

        <!-- Badge Status: Menggunakan [#cf3d3d] dengan opacity -->
        <div 
          :class="[
            'mb-4 inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold tracking-wide uppercase',
            isSynced ? 'bg-[#cf3d3d]/10 text-[#cf3d3d] border border-[#cf3d3d]/20' : 'bg-stone-100 text-stone-700 border border-stone-200'
          ]"
        >
          <span :class="['h-2 w-2 rounded-full', isSynced ? 'bg-[#cf3d3d]' : 'bg-stone-400 animate-pulse']"></span>
          {{ isSynced ? 'Pembayaran Berhasil' : 'Memproses Pembayaran' }}
        </div>

        <!-- Title & Message -->
        <h1 class="text-2xl font-bold tracking-tight text-stone-900 sm:text-3xl">
          {{ isSynced ? 'Akun Anda Siap Digunakan!' : 'Cek Email untuk Verifikasi Akun' }}
        </h1>

        <p class="mt-3 text-sm leading-relaxed text-stone-600 sm:text-base">
          {{ statusMessage }}
        </p>

        <!-- Informasi & Real-time Info Sandbox -->
        <div class="mt-8 overflow-hidden rounded-xl border border-stone-200 bg-white text-left shadow-sm">
          <div class="bg-stone-50 px-4 py-3 border-b border-stone-100">
            <h3 class="text-xs font-bold uppercase tracking-wider text-stone-500">Langkah Pemeriksaan</h3>
          </div>
          <ul class="divide-y divide-stone-100 px-4 text-sm text-stone-600">
            <li class="py-2.5 flex items-start gap-2">
              <span class="text-stone-400 font-medium">1.</span>
              <span>Inbox email utama yang kamu daftarkan.</span>
            </li>
            <li class="py-2.5 flex items-start gap-2">
              <span class="text-stone-400 font-medium">2.</span>
              <span>Folder <span class="font-medium text-stone-800">Spam</span> atau <span class="font-medium text-stone-800">Promosi</span> jika belum masuk.</span>
            </li>
            <li class="py-2.5 flex items-start gap-2">
              <span class="text-stone-400 font-medium">3.</span>
              <span>Pesan WhatsApp sebagai cadangan sistem.</span>
            </li>
          </ul>

          <!-- Status Alert Log -->
          <div v-if="isSyncing || syncError" class="border-t border-stone-100 bg-red-50/50 p-3 text-xs">
            <div v-if="isSyncing" class="flex items-center gap-2 text-[#cf3d3d] font-medium">
              <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24" fill="none">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span>Menyinkronkan status pembayaran sandbox...</span>
            </div>
            <div v-if="syncError" class="flex items-center gap-2 text-[#cf3d3d] font-medium">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 shrink-0">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
              </svg>
              <span>{{ syncError }}</span>
            </div>
          </div>
        </div>

        <!-- Action Buttons (Menyisakan 2 Tombol dengan Hierarki Baru) -->
        <div class="mt-8 flex flex-col gap-2 sm:flex-row-reverse sm:justify-start">
          <Button 
            class="w-full sm:w-auto px-6 py-2.5 bg-[#cf3d3d] hover:bg-[#b83535] text-white font-medium shadow-sm" 
            :disabled="!isSynced"
            @click="router.push('/login')"
          >
            Saya Sudah Terima Akun
          </Button>
          
          <Button 
            variant="outline" 
            class="w-full sm:w-auto px-5 py-2.5 text-stone-700 hover:bg-stone-50 border-stone-300" 
            @click="router.push('/')"
          >
            Kembali
          </Button>
        </div>
      </Card>
    </section>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import Navbar from '../components/Navbar.vue'
import Button from '../components/ui/Button.vue'
import Card from '../components/ui/Card.vue'
import { syncRegistrationPayment } from '../lib/midtrans.js'

const route = useRoute()
const router = useRouter()
const storageKey = 'nihonaccess-registration-form'
const isSyncing = ref(false)
const isSynced = ref(false)
const syncError = ref('')

const savedRegistration = (() => {
  try {
    return JSON.parse(localStorage.getItem(storageKey) || '{}')
  } catch {
    return {}
  }
})()

const orderId = computed(() => route.query.order_id || savedRegistration.order_id || '')
const statusMessage = computed(() => {
  if (isSynced.value) {
    return 'Akun kamu sudah dibuat. Username dan password sedang dikirim ke email dan WhatsApp yang didaftarkan.'
  }

  return 'Kami sedang memastikan pembayaran sandbox kamu ke Midtrans. Setelah terkonfirmasi, username dan password akan dikirim ke email dan WhatsApp.'
})

const delay = (ms) => new Promise((resolve) => setTimeout(resolve, ms))

const syncPayment = async () => {
  if (!orderId.value) {
    syncError.value = 'Order ID tidak ditemukan. Silakan cek kembali riwayat pendaftaran.'
    return false
  }

  const result = await syncRegistrationPayment(orderId.value)

  if (result.payment_status === 'success') {
    localStorage.removeItem(storageKey)
    isSynced.value = true
    syncError.value = ''
    return true
  }

  syncError.value = `Status Midtrans saat ini masih ${result.midtrans_status || result.payment_status || 'pending'}.`
  return false
}

const retrySync = async () => {
  isSyncing.value = true

  try {
    for (let attempt = 0; attempt < 5; attempt++) {
      const success = await syncPayment()

      if (success) return

      await delay(3000)
    }
  } catch (error) {
    syncError.value = error.message || 'Gagal menyinkronkan status pembayaran.'
  } finally {
    isSyncing.value = false
  }
}

onMounted(() => {
  retrySync()
})
</script>
