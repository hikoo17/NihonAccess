<template>
  <div class="min-h-screen bg-slate-50/50">
    <Navbar simple />
    
    <section class="pt-24 pb-20 text-slate-900">
      <div class="mx-auto max-w-6xl px-6 sm:px-8">
        
        <Breadcrumb class="mb-8" :items="breadcrumbItems" />

        <div v-if="isLoading" class="flex items-center justify-center py-32">
          <div class="h-8 w-8 animate-spin rounded-full border-2 border-slate-200 border-t-[#cf3d3d]"></div>
        </div>

        <div v-else-if="loadError" class="py-32 text-center">
          <p class="text-sm text-slate-500">{{ loadError }}</p>
          <Button variant="outline" class="mt-4" @click="$router.push('/')">Kembali ke Beranda</Button>
        </div>

        <template v-else>
          <div class="mb-10 max-w-3xl">
            <div class="mb-4 flex flex-wrap items-center gap-3">
              <span v-if="pkg.badge" class="rounded-full bg-[#cf3d3d]/10 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-[#cf3d3d]">{{ pkg.badge }}</span>
              <span v-if="pkg.format" class="rounded-full bg-slate-100 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-slate-500">{{ pkg.format }}</span>
            </div>

            <h1 class="mb-3 text-3xl font-extrabold tracking-tight text-slate-800 sm:text-4xl">
              {{ pkg.name }}
            </h1>
            <p class="max-w-2xl text-base font-normal leading-relaxed text-slate-500">
              {{ pkg.description }}
            </p>
          </div>

          <div class="grid items-start gap-8 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-8">
              <div class="grid gap-4 sm:grid-cols-3">
                <Card v-for="(stat, idx) in pkg.highlights" :key="idx" class="p-5">
                  <div class="text-xs font-bold uppercase tracking-wider text-slate-400">{{ stat.label }}</div>
                  <div class="mt-2 text-lg font-extrabold text-slate-800">{{ stat.value }}</div>
                </Card>
              </div>

              <Card class="p-6 sm:p-8">
                <h3 class="mb-4 text-lg font-bold text-slate-800">Apa yang akan Anda Dapatkan</h3>
                <ul class="space-y-3">
                  <li v-for="(feature, idx) in pkg.features" :key="idx" class="flex items-start gap-3">
                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-white font-bold text-sm mt-0.5">✓</span>
                    <span class="text-sm leading-relaxed text-slate-500">{{ feature.name }}</span>
                  </li>
                </ul>
              </Card>

              <Card v-if="pkg.modules?.length" class="p-6 sm:p-8">
                <h3 class="mb-4 text-lg font-bold text-slate-800">Komponen Paket</h3>
                <div class="space-y-4">
                  <div v-for="(item, idx) in pkg.modules" :key="idx" class="border-l-4 border-[#cf3d3d] pl-4 py-2">
                    <h4 class="font-bold text-slate-800">{{ item.name }}</h4>
                    <p class="mt-0.5 text-sm text-slate-500">{{ item.description }}</p>
                  </div>
                </div>
              </Card>

              <Card v-if="pkg.suitable_for?.length" class="p-6 sm:p-8">
                <h3 class="mb-4 text-lg font-bold text-slate-800">Cocok Untuk</h3>
                <ul class="space-y-3">
                  <li v-for="(item, idx) in pkg.suitable_for" :key="idx" class="flex items-start gap-3">
                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-[#cf3d3d]/10 text-[#cf3d3d] font-bold text-sm mt-0.5">→</span>
                    <span class="text-sm leading-relaxed text-slate-500">{{ item }}</span>
                  </li>
                </ul>
              </Card>
            </div>

            <div class="lg:col-span-1">
              <Card class="sticky top-28 p-6 sm:p-8">
                <div class="text-center mb-6">
                  <div class="mb-2 text-xs font-bold uppercase tracking-wider text-slate-400">Harga Paket</div>
                  <div class="text-4xl font-extrabold text-[#cf3d3d]">{{ pkg.price_formatted }}</div>
                  <div class="mt-2 text-sm font-medium text-slate-500">{{ pkg.price_note }}</div>
                </div>

                <div class="mb-6 space-y-3 rounded-2xl border border-slate-100 bg-slate-50 p-4 text-sm text-slate-500">
                  <div class="flex items-center justify-between gap-4">
                    <span>Durasi</span>
                    <span class="font-bold text-slate-900">{{ pkg.duration_label }}</span>
                  </div>
                  <div class="flex items-center justify-between gap-4">
                    <span>Level</span>
                    <span class="font-bold text-slate-900">{{ pkg.level }}</span>
                  </div>
                </div>
                  
                <Button 
                  @click="goToRegistrationPage"
                  class="w-full bg-[#cf3d3d] hover:bg-[#b03333] text-white py-3 font-bold text-base transition-all"
                >
                  {{ pkg.slug === 'corporate' ? 'Hubungi Admin' : 'Daftar Sekarang' }}
                </Button>

                <p class="mt-4 text-xs text-center text-slate-500 leading-relaxed">
                  Silakan isi formulir data diri pendaftaran terlebih dahulu sebelum melanjutkan ke proses pembayaran resmi.
                </p>
              </Card>
            </div>
          </div>
        </template>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import Navbar from '../../components/Navbar.vue'
import Breadcrumb from '../../components/ui/Breadcrumb.vue'
import Button from '../../components/ui/Button.vue'
import Card from '../../components/ui/Card.vue'
import { fetchPackage } from '../../lib/packages.js'

const props = defineProps({
  type: { type: String, required: true }
})

const router = useRouter()

const pkg = ref({})
const isLoading = ref(true)
const loadError = ref('')

onMounted(async () => {
  try {
    const data = await fetchPackage(props.type)
    if (!data) {
      loadError.value = 'Paket tidak ditemukan.'
    } else {
      pkg.value = data
    }
  } catch (error) {
    loadError.value = error.message
  } finally {
    isLoading.value = false
  }
})

const goToRegistrationPage = () => {
  if (props.type === 'corporate') {
    window.open('https://wa.me/nomorkamu?text=Halo%20Nihon%20Access,%20saya%20tertarik%20dengan%20Paket%20Private%20Corporate', '_blank')
  } else {
    router.push(`/daftar/${props.type}`)
  }
}

const breadcrumbItems = computed(() => [
  { label: 'Beranda', to: '/' },
  { label: pkg.value?.name || 'Detail Paket' }
])
</script>
