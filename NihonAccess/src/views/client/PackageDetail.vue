<template>
  <div class="mx-auto max-w-5xl space-y-8">
    <!-- Breadcrumb -->
    <Breadcrumb :items="[{ label: 'Dashboard', to: '/client/dashboard' }, { label: 'Beli Paket', to: '/client/packages' }, { label: pkg.name }]" />

    <!-- Hero -->
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-5">
      <!-- Left: detail -->
      <div class="space-y-6 lg:col-span-3">
        <div>
          <Badge variant="danger" dot>{{ pkg.tagline }}</Badge>
          <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-800">{{ pkg.name }}</h1>
          <p class="mt-2 text-sm leading-relaxed text-slate-500">
            Paket kursus bahasa Jepang terstruktur dengan materi lengkap, latihan interaktif, dan evaluasi berkala. Cocok untuk kamu yang serius ingin menguasai bahasa Jepang.
          </p>
        </div>

        <!-- What you'll learn -->
        <Card class="p-6">
          <h2 class="mb-4 text-sm font-extrabold uppercase tracking-wider text-slate-400">Yang Kamu Dapatkan</h2>
          <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
            <div v-for="(feature, i) in pkg.features" :key="i" class="flex items-start gap-2.5">
              <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#cf3d3d]/10">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-3 w-3 text-[#cf3d3d]">
                  <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                </svg>
              </span>
              <span class="text-sm font-medium text-slate-600">{{ feature }}</span>
            </div>
          </div>
        </Card>

        <!-- Syllabus -->
        <Card class="p-6">
          <h2 class="mb-4 text-sm font-extrabold uppercase tracking-wider text-slate-400">Silabus Singkat</h2>
          <div class="space-y-3">
            <div v-for="(item, i) in syllabus" :key="i" class="flex items-center gap-3 rounded-xl border border-slate-100 p-3">
              <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-xs font-extrabold text-slate-500">{{ i + 1 }}</span>
              <div>
                <p class="text-sm font-bold text-slate-700">{{ item.title }}</p>
                <p class="text-xs text-slate-400">{{ item.desc }}</p>
              </div>
            </div>
          </div>
        </Card>
      </div>

      <!-- Right: sticky checkout card -->
      <div class="lg:col-span-2">
        <div class="sticky top-24 space-y-4">
          <Card class="p-6">
            <div class="mb-4 flex items-end gap-1">
              <span class="text-3xl font-extrabold text-[#cf3d3d]">{{ pkg.price }}</span>
              <span class="mb-1 text-xs text-slate-400">/{{ pkg.duration }}</span>
            </div>
            <ul class="mb-6 space-y-2.5">
              <li v-for="(feature, i) in pkg.features" :key="i" class="flex items-center gap-2 text-xs font-medium text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4 text-[#cf3d3d]">
                  <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                </svg>
                {{ feature }}
              </li>
            </ul>
            <Button class="w-full" @click="goCheckout">Beli Sekarang</Button>
            <Button variant="outline" class="mt-2 w-full">Hubungi Konsultan</Button>
          </Card>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'
import Breadcrumb from '@/components/ui/Breadcrumb.vue'
import Card from '@/components/ui/Card.vue'
import Button from '@/components/ui/Button.vue'
import Badge from '@/components/ui/Badge.vue'

const props = defineProps({
  slug: { type: String, default: '' },
})

const router = useRouter()

const pkg = {
  name: 'Premium Online',
  tagline: 'Paket Terpopuler',
  price: 'Rp199.000',
  duration: '3 bulan',
  features: [
    'Hiragana & Katakana',
    'Kanji 200+',
    'Kosakata 1000+',
    'Latihan listening',
    'Quiz evaluasi',
    'Sertifikat kursus',
  ],
}

const syllabus = [
  { title: 'Pengenalan Hiragana', desc: 'Belajar membaca & menulis hiragana' },
  { title: 'Pengenalan Katakana', desc: 'Belajar membaca & menulis katakana' },
  { title: 'Kosakata Dasar', desc: '300+ kosakata sehari-hari' },
  { title: 'Kanji Dasar', desc: '200+ kanji dengan stroke order' },
  { title: 'Listening Practice', desc: 'Latihan mendengar percakapan' },
]

const goCheckout = () => {
  router.push({ name: 'client-checkout', params: { packageId: props.slug } })
}
</script>