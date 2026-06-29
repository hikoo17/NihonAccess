<template>
  <div class="space-y-8 animate-fadeIn">
    
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
      <TeacherStatCard 
        v-for="(stat, index) in summaryStats" 
        :key="index"
        :label="stat.label"
        :value="stat.value"
      />
    </div>

    <div class="bg-[#1a1a1a] rounded-2xl border border-zinc-800/40 shadow-xl overflow-hidden">
      <div class="p-5 px-6 flex items-center justify-between border-b border-zinc-800/60">
        <h3 class="text-sm font-semibold text-zinc-200">Course saya</h3>
        <router-link :to="{ name: 'TeacherCourses' }" class="text-xs font-semibold px-4 py-2 rounded-xl bg-zinc-900 hover:bg-zinc-800 text-zinc-300 border border-zinc-800/80 transition-all">
          Lihat semua
        </router-link>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="text-xs font-semibold text-zinc-500 border-b border-zinc-800/50 bg-zinc-900/10">
              <th class="p-4 px-6">Judul</th>
              <th class="p-4 px-6">Lesson</th>
              <th class="p-4 px-6">Status</th>
              <th class="p-4 px-6 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-zinc-800/50 text-sm">
            <tr v-for="(course, idx) in myCourses" :key="idx" class="hover:bg-zinc-800/20 transition-colors">
              <td class="p-4 px-6 font-medium text-zinc-200">{{ course.title }}</td>
              <td class="p-4 px-6 text-zinc-400 font-medium">{{ course.lessons_count }} lesson</td>
              <td class="p-4 px-6">
                <span :class="[
                  'px-3 py-1 rounded-full text-xs font-bold tracking-wide shadow-sm',
                  course.status === 'Aktif' ? 'bg-emerald-500/10 text-emerald-500 border border-emerald-500/10' : 'bg-amber-500/10 text-amber-500 border border-amber-500/10'
                ]">
                  {{ course.status }}
                </span>
              </td>
              <td class="p-4 px-6 text-right">
                <button class="p-1.5 rounded-lg bg-zinc-900 border border-zinc-800/60 text-zinc-400 hover:text-zinc-200 hover:bg-zinc-800 transition-all">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                  </svg>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="bg-[#1a1a1a] rounded-2xl border border-zinc-800/40 shadow-xl overflow-hidden">
      <div class="p-5 px-6 flex items-center justify-between border-b border-zinc-800/60">
        <h3 class="text-sm font-semibold text-zinc-200">Progress siswa terbaru</h3>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="text-xs font-semibold text-zinc-500 border-b border-zinc-800/50 bg-zinc-900/10">
              <th class="p-4 px-6">Siswa</th>
              <th class="p-4 px-6">Course</th>
              <th class="p-4 px-6">Progress</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-zinc-800/50 text-sm">
            <tr v-for="(student, sIdx) in studentProgress" :key="sIdx" class="hover:bg-zinc-800/20 transition-colors">
              <td class="p-4 px-6">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 rounded-lg bg-blue-600/10 border border-blue-500/20 flex items-center justify-center text-xs font-bold text-blue-400">
                    {{ student.initials }}
                  </div>
                  <span class="font-medium text-zinc-200">{{ student.name }}</span>
                </div>
              </td>
              <td class="p-4 px-6 text-zinc-300 font-medium">{{ student.course }}</td>
              <td class="p-4 px-6">
                <div class="flex items-center gap-4 max-w-[240px]">
                  <div class="w-full bg-zinc-800 h-2 rounded-full overflow-hidden">
                    <div class="bg-blue-600 h-full rounded-full transition-all duration-500" :style="{ width: `${student.percentage}%` }"></div>
                  </div>
                  <span class="text-xs font-bold text-zinc-400 w-8 text-right tabular-nums">{{ student.percentage }}%</span>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import TeacherStatCard from '@/components/Teacher/TeacherStatCard.vue'

const summaryStats = ref([])
const myCourses = ref([])
const studentProgress = ref([])

const fetchDashboardData = async () => {
  try {
    const response = await axios.get('/api/teacher/dashboard')
    if (response.data.success) {
      summaryStats.value = response.data.data.stats
      myCourses.value = response.data.data.courses
      studentProgress.value = response.data.data.student_progress
    }
  } catch (error) {
    console.error('Terjadi kesalahan muat data:', error)
    // Fallback data statis jika API belum siap / token expired
    summaryStats.value = [
      { label: 'Total course', value: 4 },
      { label: 'Total lesson', value: 32 },
      { label: 'Siswa aktif', value: 87 },
      { label: 'Quiz dibuat', value: 12 }
    ]
    myCourses.value = [
      { title: 'Hiragana dasar', lessons_count: 8, status: 'Aktif' },
      { title: 'Katakana dasar', lessons_count: 6, status: 'Aktif' },
      { title: 'Kanji N5', lessons_count: 10, status: 'Non-Aktif' }
    ]
    studentProgress.value = [
      { initials: 'AS', name: 'Andi S.', course: 'Hiragana dasar', percentage: 75 },
      { initials: 'RN', name: 'Rina N.', course: 'Katakana dasar', percentage: 40 }
    ]
  }
}

onMounted(() => {
  fetchDashboardData()
})
</script>

<style scoped>
.animate-fadeIn {
  animation: fadeIn 0.35s ease-out forwards;
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(4px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>