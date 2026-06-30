<template>
  <div class="animate-fadeIn">
    <TeacherPageHeader
      eyebrow="Course & Lesson"
      title="Course Saya"
      subtitle="Daftar course yang Anda kelola. Klik untuk mengelola lesson di dalamnya."
    >
      <template #actions>
        <div class="relative">
          <input
            v-model="search"
            type="text"
            placeholder="Cari course..."
            class="h-10 w-56 rounded-2xl border border-slate-200 bg-white pl-9 pr-3 text-sm font-medium text-slate-700 placeholder:text-slate-400 transition focus:border-[#cf3d3d] focus:outline-none focus:ring-2 focus:ring-[#cf3d3d]/20"
          />
          <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
        </div>
      </template>
    </TeacherPageHeader>

    <div class="rounded-3xl border border-slate-100 bg-white shadow-sm">
      <TeacherLoading v-if="loading" label="Memuat course..." />

      <TeacherEmptyState
        v-else-if="filtered.length === 0"
        title="Belum ada course"
        :description="search ? 'Tidak ada course yang cocok dengan pencarian.' : 'Course dibuat oleh admin dan ditugaskan ke Anda.'"
      />

      <div v-else class="grid grid-cols-1 gap-4 p-6 sm:grid-cols-2 lg:grid-cols-3">
        <div
          v-for="course in filtered"
          :key="course.id"
          class="group flex flex-col rounded-2xl border border-slate-100 bg-slate-50/40 p-5 transition-all duration-300 hover:border-[#cf3d3d]/30 hover:bg-white hover:shadow-[0_10px_30px_rgba(15,23,42,0.04)]"
        >
          <div class="mb-4 flex items-start justify-between gap-3">
            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#cf3d3d]/10 text-[#cf3d3d]">
              <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
            </div>
            <TeacherStatusBadge :active="course.is_active" />
          </div>

          <h3 class="text-base font-extrabold text-slate-900">{{ course.title }}</h3>
          <p class="mt-1 line-clamp-2 text-sm font-medium text-slate-500">{{ course.description || 'Tanpa deskripsi' }}</p>

          <div class="mt-4 flex items-center gap-4 text-xs font-semibold text-slate-400">
            <span class="inline-flex items-center gap-1.5">
              <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
              {{ course.lessons_count ?? 0 }} lesson
            </span>
            <span v-if="course.level" class="rounded-full bg-slate-200/70 px-2 py-0.5 text-slate-600">{{ course.level }}</span>
          </div>

          <router-link
            :to="{ name: 'TeacherLessons', params: { courseId: course.id } }"
            class="mt-5 inline-flex items-center justify-center gap-2 rounded-2xl bg-white px-4 py-2.5 text-sm font-bold text-[#cf3d3d] border border-slate-200 transition-all hover:border-[#cf3d3d]/40 hover:bg-[#cf3d3d]/5"
          >
            Kelola Lesson
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { teacherApi } from '@/services/teacherApi'
import { useTeacherToast } from '@/composables/useTeacherToast'
import TeacherPageHeader from '@/components/teacher/ui/TeacherPageHeader.vue'
import TeacherLoading from '@/components/teacher/ui/TeacherLoading.vue'
import TeacherEmptyState from '@/components/teacher/ui/TeacherEmptyState.vue'
import TeacherStatusBadge from '@/components/teacher/ui/TeacherStatusBadge.vue'

const { error } = useTeacherToast()

const loading = ref(true)
const items = ref([])
const search = ref('')

const filtered = computed(() => {
  if (!search.value) return items.value
  const q = search.value.toLowerCase()
  return items.value.filter((c) => c.title?.toLowerCase().includes(q))
})

const fetchCourses = async () => {
  loading.value = true
  try {
    const res = await teacherApi.courses.list({ per_page: 100 })
    items.value = res.data
  } catch (e) {
    error(e.message || 'Gagal memuat course.')
  } finally {
    loading.value = false
  }
}

onMounted(fetchCourses)
</script>

<style scoped>
.animate-fadeIn { animation: fadeIn 0.35s ease-out forwards; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: translateY(0); } }
</style>
