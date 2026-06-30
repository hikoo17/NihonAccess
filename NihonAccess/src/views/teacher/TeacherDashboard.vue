<template>
  <div class="animate-fadeIn space-y-8">
    <div>
      <Breadcrumb :items="[{ label: 'Beranda', to: '/' }, { label: 'Dashboard' }]" />
      <h1 class="mt-3 text-2xl font-extrabold tracking-tight text-slate-800 sm:text-3xl">Selamat datang, <span class="text-[#cf3d3d]">{{ firstName }}</span> <span class="wave-emoji">👋</span></h1>
      <p class="mt-1 text-sm text-slate-500">Ringkasan aktivitas mengajar Anda hari ini.</p>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
      <TeacherStatCard
        v-for="(stat, index) in stats"
        :key="index"
        :label="stat.label"
        :value="stat.value"
      >
        <template #icon><component :is="stat.icon" /></template>
      </TeacherStatCard>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-5">
      <div class="rounded-3xl border border-slate-100 bg-white shadow-sm lg:col-span-3">
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
          <h3 class="text-base font-extrabold text-slate-900">Course Terbaru</h3>
          <router-link :to="{ name: 'TeacherCourses' }" class="text-sm font-bold text-[#cf3d3d] transition-colors hover:text-[#b83232]">
            Lihat semua →
          </router-link>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-left">
            <thead>
              <tr class="border-b border-slate-200">
                <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-slate-400">Judul</th>
                <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-slate-400">Lesson</th>
                <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-slate-400">Status</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
              <tr v-for="(course, idx) in courses" :key="idx" class="transition-colors hover:bg-slate-50/70">
                <td class="px-6 py-4 font-bold text-slate-800">{{ course.title }}</td>
                <td class="px-6 py-4 font-medium text-slate-500">{{ course.lessons_count }} lesson</td>
                <td class="px-6 py-4"><TeacherStatusBadge :active="course.is_active" /></td>
              </tr>
              <tr v-if="courses.length === 0">
                <td colspan="3" class="px-6 py-10 text-center text-sm font-medium text-slate-400">Belum ada course.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="rounded-3xl border border-slate-100 bg-white shadow-sm lg:col-span-2">
        <div class="border-b border-slate-100 px-6 py-4">
          <h3 class="text-base font-extrabold text-slate-900">Progress Siswa Terbaru</h3>
        </div>
        <div class="p-6">
          <div v-if="studentProgress.length === 0" class="py-8 text-center text-sm font-medium text-slate-400">Belum ada progress siswa.</div>
          <div v-else class="space-y-5">
            <div v-for="(student, idx) in studentProgress" :key="idx">
              <div class="mb-1.5 flex items-center justify-between gap-3">
                <div class="flex min-w-0 items-center gap-2.5">
                  <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[#cf3d3d]/10 text-xs font-bold text-[#cf3d3d]">
                    {{ initialsOf(student.name) }}
                  </span>
                  <div class="min-w-0">
                    <p class="truncate text-sm font-bold text-slate-800">{{ student.name }}</p>
                    <p class="truncate text-xs font-medium text-slate-400">{{ student.course }}</p>
                  </div>
                </div>
                <span class="text-xs font-bold text-slate-500 tabular-nums">{{ student.percentage }}%</span>
              </div>
              <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100">
                <div class="h-full rounded-full bg-gradient-to-r from-[#cf3d3d] to-[#e85555] transition-all duration-500" :style="{ width: `${student.percentage}%` }"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, h, onMounted } from 'vue'
import { teacherApi } from '@/services/teacherApi'
import { useTeacherToast } from '@/composables/useTeacherToast'
import Breadcrumb from '@/components/ui/Breadcrumb.vue'
import TeacherStatCard from '@/components/teacher/TeacherStatCard.vue'
import TeacherStatusBadge from '@/components/teacher/ui/TeacherStatusBadge.vue'

const { error } = useTeacherToast()

const stats = ref([])
const courses = ref([])
const studentProgress = ref([])

const firstName = computed(() => {
  try {
    const data = JSON.parse(localStorage.getItem('user_data') || '{}')
    return (data.name || 'Guru').split(' ')[0]
  } catch {
    return 'Guru'
  }
})

const initialsOf = (name) => {
  return String(name || '?').split(' ').map((w) => w[0]).slice(0, 2).join('').toUpperCase()
}

const IconCourse = () => ({
  render: () => h('svg', { class: 'h-5 w-5', fill: 'none', stroke: 'currentColor', 'stroke-width': '2', viewBox: '0 0 24 24' }, [
    h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25' }),
  ]),
})
const IconLesson = () => ({
  render: () => h('svg', { class: 'h-5 w-5', fill: 'none', stroke: 'currentColor', 'stroke-width': '2', viewBox: '0 0 24 24' }, [
    h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25' }),
  ]),
})
const IconStudent = () => ({
  render: () => h('svg', { class: 'h-5 w-5', fill: 'none', stroke: 'currentColor', 'stroke-width': '2', viewBox: '0 0 24 24' }, [
    h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.244m.972-4.346h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V11.75zm0-2.25h.008v.008h-.008v-.008zm-9.75 0h.008v.008H4.5v-.008zm0 2.25h.008v.008H4.5V11.75zm0-2.25h.008v.008H4.5v-.008zm9.75 0h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V11.75z' }),
  ]),
})
const IconQuiz = () => ({
  render: () => h('svg', { class: 'h-5 w-5', fill: 'none', stroke: 'currentColor', 'stroke-width': '2', viewBox: '0 0 24 24' }, [
    h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z' }),
  ]),
})

const iconMap = [IconCourse, IconLesson, IconStudent, IconQuiz]

const fetchDashboard = async () => {
  try {
    const res = await teacherApi.dashboard()
    const d = res.data
    stats.value = (d.stats || []).map((s, i) => ({ ...s, icon: iconMap[i] || IconCourse }))
    courses.value = d.courses || []
    studentProgress.value = d.student_progress || []
  } catch (e) {
    error(e.message || 'Gagal memuat data dashboard.')
  }
}

onMounted(fetchDashboard)
</script>

<style scoped>
.animate-fadeIn { animation: fadeIn 0.35s ease-out forwards; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: translateY(0); } }
.wave-emoji { display: inline-block; animation: wave-animation 2.5s infinite; transform-origin: 70% 70%; }
@keyframes wave-animation {
  0% { transform: rotate(0.0deg) }
  10% { transform: rotate(14.0deg) }
  20% { transform: rotate(-8.0deg) }
  30% { transform: rotate(14.0deg) }
  40% { transform: rotate(-4.0deg) }
  50% { transform: rotate(10.0deg) }
  60% { transform: rotate(0.0deg) }
  100% { transform: rotate(0.0deg) }
}
</style>
