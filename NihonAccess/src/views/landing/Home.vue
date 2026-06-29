<template>
  <div class="min-h-screen bg-white font-sans text-slate-900 antialiased scroll-smooth">
    <Navbar />

    <div v-if="pendingRegistration" class="fixed left-0 right-0 top-[56px] z-40 px-4 sm:top-[64px]">
      <div class="mx-auto flex max-w-5xl flex-col gap-3 rounded-3xl border border-amber-200 bg-amber-50/95 p-4 shadow-lg shadow-amber-900/5 backdrop-blur sm:flex-row sm:items-center sm:justify-between">
        <div>
          <p class="text-sm font-extrabold text-amber-900">Lanjutkan pembayaran sebelumnya?</p>
          <p class="mt-1 text-xs leading-relaxed text-amber-700">
            Pendaftaran {{ pendingRegistration.name }} untuk {{ pendingRegistration.package_name || pendingRegistration.packageType }} masih pending.
          </p>
        </div>
        <div class="flex flex-col gap-2 sm:flex-row">
          <button class="rounded-full bg-[#cf3d3d] px-5 py-2 text-xs font-extrabold text-white shadow-sm transition hover:bg-[#b83232]" @click="continuePayment">
            Lanjut Bayar
          </button>
          <button class="rounded-full border border-amber-200 bg-white px-5 py-2 text-xs font-extrabold text-amber-700 transition hover:bg-amber-100" @click="dismissReminder">
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
      alert('Pembayaran belum bisa dilanjutkan. Silakan coba lagi beberapa saat lagi.')
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
