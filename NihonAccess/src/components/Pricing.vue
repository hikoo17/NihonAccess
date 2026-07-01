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

      <div v-if="isLoading" class="mt-12 grid gap-8 md:grid-cols-3 items-stretch">
        <div v-for="n in 3" :key="`sk-${n}`" class="flex flex-col justify-between rounded-[2rem] bg-white p-8 border border-slate-100 shadow-sm">
          <div>
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 mb-6">
              <Skeleton width="1.25rem" height="1.25rem" rounded="lg" />
            </div>
            <Skeleton width="60%" height="1.5rem" />
            <div class="mt-4 space-y-2">
              <Skeleton width="100%" height="0.75rem" />
              <Skeleton width="85%" height="0.75rem" />
            </div>
            <div class="mt-6">
              <Skeleton width="45%" height="1.875rem" />
            </div>
            <div class="mt-8 space-y-3.5">
              <div v-for="i in 4" :key="i" class="flex items-center gap-3">
                <Skeleton width="1.25rem" height="1.25rem" rounded="full" />
                <Skeleton :width="i % 2 ? '70%' : '55%'" height="0.875rem" />
              </div>
            </div>
          </div>
          <Skeleton width="100%" height="3.25rem" rounded="2xl" class="mt-8" />
        </div>
      </div>

      <div v-else-if="loadError" class="py-16 text-center text-sm text-slate-500">
        {{ loadError }}
      </div>

      <div v-else class="mt-12 grid gap-8 md:grid-cols-3 items-stretch">
        <div 
          v-for="(tier, idx) in filteredPricing" 
          :key="tier.slug" 
          data-aos="fade-up"
          :data-aos-delay="idx * 150"
          class="relative flex flex-col justify-between rounded-[2rem] bg-white p-8 text-slate-900 border border-slate-100 shadow-sm transition-all duration-300 hover:shadow-md"
        >
          <div>
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#cf3d3d] text-white mb-6 shadow-sm shadow-[#cf3d3d]/15">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                <path :d="tier.icon || 'M5 13l4 4L19 7'" />
              </svg>
            </div>

            <h3 class="text-2xl font-bold tracking-tight text-left">{{ tier.name }}</h3>
            <p class="text-sm text-left mt-2 leading-relaxed text-slate-500">
              {{ tier.description }}
            </p>
            
            <div class="mt-6 flex items-baseline justify-start gap-1">
              <span class="text-3xl font-extrabold tracking-tight text-[#cf3d3d]">{{ tier.price_formatted }}</span>
              <span v-if="tier.duration_label !== 'Custom'" class="text-xs text-slate-400">/ paket</span>
            </div>
            
            <ul class="mt-8 space-y-3.5 text-left text-sm">
              <li 
                v-for="(feat, fIdx) in tier.features" 
                :key="fIdx" 
                class="flex items-center gap-3 font-medium text-slate-600"
              >
                <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-white text-xs font-bold">✓</span> 
                <span>{{ feat.name }}</span>
              </li>
            </ul>
          </div>
          
          <button 
            @click="$router.push(`/paket/${tier.slug}`)"
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
import { ref, computed, onMounted } from 'vue'
import { fetchPackages } from '../lib/packages.js'
import Skeleton from './ui/Skeleton.vue'

const activeType = ref('online')
const pricingData = ref([])
const isLoading = ref(true)
const loadError = ref('')

onMounted(async () => {
  try {
    pricingData.value = await fetchPackages()
  } catch (error) {
    loadError.value = error.message
  } finally {
    isLoading.value = false
  }
})

const filteredPricing = computed(() => {
  return pricingData.value.filter(tier => tier.type === activeType.value)
})
</script>
