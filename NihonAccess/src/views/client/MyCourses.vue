<template>
  <div class="mx-auto max-w-6xl space-y-8">
    <!-- Header -->
    <div>
      <Breadcrumb :items="[{ label: 'Dashboard', to: '/client/dashboard' }, { label: 'Kursus Saya' }]" />
      <h1 class="mt-3 text-2xl font-extrabold tracking-tight text-slate-800 sm:text-3xl">Kursus Saya</h1>
      <p class="mt-1 text-sm text-slate-500">Lihat semua kursus yang sudah kamu beli.</p>
    </div>

    <!-- Filter tabs -->
    <div class="flex items-center gap-2 overflow-x-auto">
      <button
        v-for="tab in tabs"
        :key="tab"
        @click="activeTab = tab"
        class="shrink-0 rounded-full px-4 py-2 text-xs font-bold transition"
        :class="activeTab === tab
          ? 'bg-[#cf3d3d] text-white shadow-md shadow-[#cf3d3d]/10'
          : 'bg-white text-slate-500 border border-slate-200 hover:border-[#cf3d3d]/40'"
      >
        {{ tab }} ({{ countByTab(tab) }})
      </button>
    </div>

    <!-- Grid -->
    <div v-if="filteredCourses.length" class="grid grid-cols-1 gap-5 md:grid-cols-2">
      <CourseCard v-for="course in filteredCourses" :key="course.id" :course="course" />
    </div>

    <!-- Empty state -->
    <Card v-else class="p-12 text-center">
      <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-slate-100">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-8 w-8 text-slate-400">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
        </svg>
      </div>
      <h3 class="text-sm font-extrabold text-slate-800">Belum ada kursus</h3>
      <p class="mx-auto mt-1 max-w-xs text-xs text-slate-400">Kamu belum punya kursus di kategori ini. Yuk beli paket untuk mulai belajar.</p>
      <Button class="mt-5" @click="$router.push('/client/packages')">Beli Paket Sekarang</Button>
    </Card>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import Breadcrumb from '@/components/ui/Breadcrumb.vue'
import Card from '@/components/ui/Card.vue'
import Button from '@/components/ui/Button.vue'
import CourseCard from '@/components/Client/CourseCard.vue'

const tabs = ['Semua', 'Aktif', 'Selesai', 'Kedaluwarsa']
const activeTab = ref('Semua')

const courses = [
  { id: 1, title: 'JLPT N5 Fundamental', level: 'N5', lesson: 12, totalLessons: 20, status: 'active', endDate: '15 Agu 2026' },
  { id: 2, title: 'Kanji Dasar', level: 'N5', lesson: 4, totalLessons: 16, status: 'active', endDate: '20 Sep 2026' },
  { id: 3, title: 'Hiragana Mastery', level: 'N5', lesson: 10, totalLessons: 10, status: 'completed', endDate: '10 Jun 2026' },
  { id: 4, title: 'Grammar Pemula', level: 'N5', lesson: 2, totalLessons: 14, status: 'expired', endDate: '01 Mei 2026' },
]

const filteredCourses = computed(() => {
  if (activeTab.value === 'Semua') return courses
  const map = { 'Aktif': 'active', 'Selesai': 'completed', 'Kedaluwarsa': 'expired' }
  return courses.filter((c) => c.status === map[activeTab.value])
})

const countByTab = (tab) => {
  if (tab === 'Semua') return courses.length
  const map = { 'Aktif': 'active', 'Selesai': 'completed', 'Kedaluwarsa': 'expired' }
  return courses.filter((c) => c.status === map[tab]).length
}
</script>