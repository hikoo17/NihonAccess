<template>
  <section class="section-flow-white relative overflow-hidden py-24" data-aos="fade-up">
    <div class="relative mx-auto max-w-7xl px-6 sm:px-8">
      <div class="grid gap-12 lg:grid-cols-12 lg:items-start">
        <div class="text-left lg:col-span-5">
          <span class="inline-flex items-center gap-3 text-xs font-extrabold uppercase tracking-[0.22em] text-[#cf3d3d]">
            <span class="h-px w-10 bg-[#cf3d3d]"></span>
            FAQ
          </span>
          <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl lg:text-5xl">
            Pertanyaan yang Sering Diajukan
          </h2>
          <p class="mt-4 text-base sm:text-lg text-slate-600 font-medium leading-relaxed">
            Temukan jawaban singkat sebelum mulai belajar bersama Nihon Access.
          </p>
        </div>

        <div class="lg:col-span-7 space-y-4">
          <div v-for="(faq, idx) in faqs" :key="idx" 
             data-aos="fade-up"
             :data-aos-delay="idx * 100"
             class="group rounded-2xl border border-slate-100 bg-white overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
            <button @click="faq.open = !faq.open" class="flex w-full items-center justify-between gap-5 p-5 text-left font-bold text-slate-900 transition-colors hover:bg-slate-50">
              <span class="flex items-center gap-4">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#cf3d3d] text-xs font-extrabold text-white">
                  {{ String(idx + 1).padStart(2, '0') }}
                </span>
                <span>{{ faq.q }}</span>
              </span>
              <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-100 text-[#cf3d3d] text-sm transition-transform duration-300" :class="{'rotate-180': faq.open}">↓</span>
            </button>
            <div v-show="faq.open" class="px-5 pb-5 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-4 sm:pl-[4.25rem]">
              {{ faq.a }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue'
const faqs = ref([
  { q: 'Apakah cocok untuk pemula?', a: 'Sangat cocok! Materi kami disusun rapi benar-benar mulai dari nol, dari pengenalan huruf dasar, hingga kamu bisa berbahasa Jepang dengan lancar.', open: false },
  { q: 'Kapan saya bisa mulai belajar?', a: 'Seketika setelah pembayaran dikonfirmasi, Anda bisa langsung mengakses seluruh materi video dan modul kapan saja secara fleksibel.', open: false },
  { q: 'Bagaimana cara pembayarannya?', a: 'Kami mendukung berbagai macam metode pembayaran digital mulai dari Transfer Bank, E-Wallet (OVO, Dana, GoPay), hingga QRIS.', open: false }
])
</script>
