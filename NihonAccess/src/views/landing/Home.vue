<template>
  <div class="min-h-screen bg-white font-sans text-slate-900 antialiased scroll-smooth">
    <Navbar />

    <div v-if="pendingRegistration" class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <div class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm" @click="dismissReminder"></div>
      
      <div class="relative w-full max-w-md transform rounded-3xl  bg-white p-6 shadow-2xl transition-all animate-in fade-in zoom-in-95 duration-200">
        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-amber-50 text-amber-600">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h.01M12 16h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
          </svg>
        </div>

        <div class="mt-4 text-center">
          <h3 class="text-lg font-extrabold text-slate-900">Lanjutkan Pembayaran?</h3>
          <p class="mt-2 text-sm leading-relaxed text-slate-600">
            Pendaftaran <span class="font-semibold text-slate-900">{{ pendingRegistration.name }}</span> untuk <span class="font-semibold text-slate-900">{{ pendingRegistration.package_name || pendingRegistration.packageType }}</span> masih pending.
          </p>
        </div>

        <div class="mt-6 flex flex-col gap-2 sm:flex-row-reverse">
          <button class="w-full rounded-full bg-[#cf3d3d] px-5 py-2.5 text-sm font-extrabold text-white shadow-sm transition hover:bg-[#b83232] sm:w-auto flex-1" @click="continuePayment">
            Lanjut Bayar
          </button>
          <button class="w-full rounded-full border border-slate-200 bg-white px-5 py-2.5 text-sm font-extrabold text-slate-700 transition hover:bg-slate-50 sm:w-auto flex-1" @click="dismissReminder">
            Nanti Saja
          </button>
        </div>
      </div>
    </div>

    <main>
      <Hero />
      <SocialProof id="kepercayaan" />
      <Features id="fitur" />
      <Syllabus id="silabus" />
      <Pricing id="harga" />
      <Testimonials id="testimoni" />
      <FAQ id="faq" />
      <FinalCTA />
    </main>

    <Footer />
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import Navbar from '../../components/Navbar.vue'
import Hero from '../../components/Hero.vue'
import SocialProof from '../../components/SocialProof.vue'
import Features from '../../components/Features.vue'
import Syllabus from '../../components/Syllabus.vue'
import Pricing from '../../components/Pricing.vue'
import Testimonials from '../../components/Testimonials.vue'
import FAQ from '../../components/FAQ.vue'
import FinalCTA from '../../components/FinalCTA.vue'
import Footer from '../../components/Footer.vue'
import { checkRegistrationStatus, confirmSnapPayment, loadMidtransSnap, syncRegistrationPayment } from '../../lib/midtrans.js'
import { fireAlert } from '../../lib/swal.js'

const storageKey = 'nihonaccess-registration-form'
const pendingRegistration = ref(null)

const readSavedRegistration = () => {
  try {
    return JSON.parse(localStorage.getItem(storageKey) || '{}')
  } catch {
    return {}
  }
}

const loadPendingRegistration = async () => {
  const saved = readSavedRegistration()

  if (!saved.order_id || !saved.payment_token) return

  pendingRegistration.value = saved

  try {
    const status = await checkRegistrationStatus(saved.order_id)

    if (status.should_clear_local_storage) {
      localStorage.removeItem(storageKey)
      return
    }

    if (status.should_show_reminder) {
      pendingRegistration.value = {
        ...saved,
        ...status.registration,
      }
    }
  } catch (error) {
    pendingRegistration.value = saved
  }
}

const continuePayment = async () => {
  if (!pendingRegistration.value?.payment_token) return

  await loadMidtransSnap()

  window.snap.pay(pendingRegistration.value.payment_token, {
    onSuccess: async (result) => {
      try {
        if (result?.signature_key) {
          await confirmSnapPayment(result, pendingRegistration.value.order_id)
        } else {
          await syncRegistrationPayment(pendingRegistration.value.order_id)
        }

        localStorage.removeItem(storageKey)
      } catch (error) {
        console.error(error)
      } finally {
        window.location.href = `/cek-email-verifikasi?order_id=${pendingRegistration.value.order_id}`
      }
    },
    onPending: () => {
      pendingRegistration.value = readSavedRegistration()
    },
    onError: () => {
      fireAlert({ icon: 'error', title: 'Pembayaran Gagal', text: 'Pembayaran belum bisa dilanjutkan. Silakan coba lagi beberapa saat lagi.' })
    },
  })
}

const dismissReminder = () => {
  pendingRegistration.value = null
}

onMounted(() => {
  loadPendingRegistration()
})
</script>