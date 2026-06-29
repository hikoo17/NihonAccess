<template>
  <section class="section-flow-white relative overflow-hidden py-24" data-aos="fade-up">
    <div class="relative mx-auto max-w-7xl px-6 sm:px-8">
      
      <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-16">
        <div class="text-left max-w-2xl">
          <span class="mb-4 inline-flex items-center gap-3 text-xs font-extrabold uppercase tracking-[0.22em] text-[#cf3d3d]">
            <span class="h-px w-10 bg-[#cf3d3d]"></span>
            Investasi Terbaik
          </span>
          <h2 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl lg:text-5xl">
            Pilih Paket Sesuai Kebutuhan
          </h2>
          <p class="mt-4 text-base sm:text-lg text-slate-600 font-medium leading-relaxed">
            Pilih paket yang paling cocok untuk mempercepat perjalanan belajarmu bersama kami.
          </p>
        </div>

        <div class="flex justify-start md:justify-end shrink-0">
          <div class="relative flex rounded-full bg-slate-100 p-1 font-medium text-sm border border-slate-200">
            <button 
              @click="activeType = 'online'"
              :class="[activeType === 'online' ? 'bg-white text-slate-950 shadow-sm' : 'text-slate-500 hover:text-slate-900', 'relative rounded-full px-6 py-2 transition-all duration-200 focus:outline-none']"
            >
              Online
            </button>
            <button 
              @click="activeType = 'onsite'"
              :class="[activeType === 'onsite' ? 'bg-white text-slate-950 shadow-sm' : 'text-slate-500 hover:text-slate-900', 'relative rounded-full px-6 py-2 transition-all duration-200 focus:outline-none']"
            >
              On-Site
            </button>
          </div>
        </div>
      </div>

      <div class="mt-12 grid gap-8 md:grid-cols-3 items-stretch">
        <div 
          v-for="(tier, idx) in filteredPricing" 
          :key="idx" 
          data-aos="fade-up"
          :data-aos-delay="idx * 150"
          class="relative flex flex-col justify-between rounded-[2rem] bg-white p-8 text-slate-900 border border-slate-100 shadow-sm transition-all duration-300 hover:shadow-md"
        >
          <div>
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#cf3d3d] text-white mb-6 shadow-sm shadow-[#cf3d3d]/15">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                <path :d="tier.icon" />
              </svg>
            </div>

            <h3 class="text-2xl font-bold tracking-tight text-left">{{ tier.name }}</h3>
            <p class="text-sm text-left mt-2 leading-relaxed text-slate-500">
              {{ tier.description }}
            </p>
            
            <div class="mt-6 flex items-baseline justify-start gap-1">
              <span class="text-3xl font-extrabold tracking-tight text-[#cf3d3d]">{{ tier.price }}</span>
              <span class="text-xs text-slate-400">/ paket</span>
            </div>
            
            <ul class="mt-8 space-y-3.5 text-left text-sm">
              <li 
                v-for="(feat, fIdx) in tier.features" 
                :key="fIdx" 
                class="flex items-center gap-3 font-medium text-slate-600"
              >
                <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-white text-xs font-bold">✓</span> 
                <span>{{ feat }}</span>
              </li>
            </ul>
          </div>
          
          <button 
            @click="$router.push(`/paket/${getRouteType(tier.name)}`)"
            class="mt-8 w-full rounded-2xl bg-[#cf3d3d] py-3.5 font-bold text-sm text-white shadow-sm shadow-[#cf3d3d]/20 transition-all duration-300 hover:bg-[#b03030]"
          >
            Lihat Detail
          </button>
        </div>
      </div>

    </div>
  </section>
</template>

<script setup>
import { ref, computed } from 'vue'

const activeType = ref('online')

const pricingData = ref([
  // DATA ONLINE
  { 
    type: 'online', 
    name: 'Basic', 
    description: 'Belajar melalui video pembelajaran berkualitas tinggi dengan akses selamanya. Cocok untuk yang mandiri.',
    price: 'Rp99.000', 
    icon: 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
    features: ['Hiragana & Katakana', 'Salam Sehari-hari', 'Kosakata Dasar', 'Akses Materi 1 Bulan via Web'] 
  },
  { 
    type: 'online', 
    name: 'Premium', 
    description: 'Program intensif dengan mentor melalui zoom. Cocok untuk yang ingin belajar secara langsung.',
    price: 'Rp199.000', 
    icon: 'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z',
    features: ['Semua di Paket Basic', 'Kanji Dasar', 'Grammar JLPT N5', 'Latihan Soal JLPT N5', 'Akses Materi 3 Bulan'] 
  },
  { 
    type: 'online', 
    name: 'Private', 
    description: 'Kerjasama strategis untuk perusahaan, universitas, atau komunitas. Tingkatkan kapasitas talenta.',
    price: 'Rp499.000', 
    icon: 'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm14 10v-2a4 4 0 0 0-3-3.87m-4-12a4 4 0 0 1 0 7.75',
    features: ['Semua di Paket Premium', 'Kelas Private via Zoom', 'Konsultasi Belajar 1-on-1', 'Akses Materi 6 Bulan'] 
  },

  // DATA ON-SITE
  { 
    type: 'onsite', 
    name: 'Basic On-Site', 
    description: 'Belajar interaktif langsung tatap muka di ruang kelas bersama teman kelompok.',
    price: 'Rp350.000', 
    icon: 'M19 21V5a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v5',
    features: ['Kelas Tatap Muka (Seminggu 2x)', 'Modul Cetak & Merchandise', 'Grup Diskusi Off-line', 'Evaluasi Bulanan'] 
  },
  { 
    type: 'onsite', 
    name: 'Premium On-Site', 
    description: 'Akselerasi materi terstruktur padat dengan bimbingan harian yang dipandu penuh.',
    price: 'Rp599.000', 
    icon: 'M13 10V3L4 14h7v7l9-11h-7z',
    features: ['Semua fitur Reguler', 'Kelas Tatap Muka (Setiap Hari)', 'Free-flow Coffee & Ruang Belajar', 'Simulasi JLPT Offline'] 
  },
  { 
    type: 'onsite', 
    name: 'Private Corporate', 
    description: 'Pelatihan kurikulum khusus bisnis skala korporat yang jadwalnya fleksibel.',
    price: 'Hubungi Kami', 
    icon: 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
    features: ['Jadwal Fleksibel', 'Lokasi bisa disesuaikan', 'Kurikulum Khusus Bisnis', 'Sertifikat Resmi Pelatihan'] 
  }
])

const filteredPricing = computed(() => {
  return pricingData.value.filter(tier => tier.type === activeType.value)
})

const getRouteType = (name) => {
  const map = {
    'Basic': 'basic',
    'Premium': 'premium',
    'Private': 'private',
    'Basic On-Site': 'reguler',
    'Premium On-Site': 'intensive',
    'Private Corporate': 'corporate'
  }
  return map[name] || 'basic'
}
</script>
