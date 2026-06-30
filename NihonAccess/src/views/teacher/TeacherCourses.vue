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
        <Button size="sm" @click="$router.push({ name: 'TeacherCourseCreate' })">
          <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
          Tambah Course
        </Button>
      </template>
    </TeacherPageHeader>

    <div class="rounded-3xl border border-slate-100 bg-white shadow-sm">
      <TeacherLoading v-if="loading" label="Memuat course..." />

      <TeacherEmptyState
        v-else-if="filtered.length === 0"
        :title="search ? 'Tidak ditemukan' : 'Belum ada course'"
        :description="search ? 'Tidak ada course yang cocok dengan pencarian.' : 'Buat course pertama Anda dan mulai kelola lesson di dalamnya.'"
      >
        <template #action><Button size="sm" @click="$router.push({ name: 'TeacherCourseCreate' })">Tambah Course</Button></template>
      </TeacherEmptyState>

      <div v-else class="grid grid-cols-1 gap-4 p-6 sm:grid-cols-2 lg:grid-cols-3">
        <div
          v-for="course in filtered"
          :key="course.id"
          class="group flex flex-col overflow-hidden rounded-2xl border border-slate-100 bg-slate-50/40 transition-all duration-300 hover:border-[#cf3d3d]/30 hover:bg-white hover:shadow-[0_10px_30px_rgba(15,23,42,0.04)]"
        >
          <div class="relative h-36 w-full overflow-hidden bg-slate-100">
            <img v-if="course.thumbnail_url" :src="course.thumbnail_url" :alt="course.title" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105" />
            <div v-else class="flex h-full w-full items-center justify-center">
              <svg class="h-10 w-10 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5" /></svg>
            </div>
            <div class="absolute right-2 top-2">
              <TeacherStatusBadge :active="course.is_active" />
            </div>
          </div>

          <div class="flex flex-1 flex-col p-5">
            <h3 class="text-base font-extrabold text-slate-900">{{ course.title }}</h3>
            <p class="mt-1 line-clamp-2 text-sm font-medium text-slate-500">{{ course.description || 'Tanpa deskripsi' }}</p>

            <div class="mt-4 flex items-center gap-4 text-xs font-semibold text-slate-400">
              <span class="inline-flex items-center gap-1.5">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z" /></svg>
                {{ course.lessons_count ?? 0 }} lesson
              </span>
              <span v-if="course.level" class="rounded-full bg-slate-200/70 px-2 py-0.5 text-slate-600">{{ course.level }}</span>
            </div>

            <div class="mt-5 flex items-center gap-2">
              <router-link
                :to="{ name: 'TeacherLessons', params: { courseId: course.id } }"
                class="inline-flex flex-1 items-center justify-center gap-2 rounded-2xl bg-white px-4 py-2.5 text-sm font-bold text-[#cf3d3d] border border-slate-200 transition-all hover:border-[#cf3d3d]/40 hover:bg-[#cf3d3d]/5"
              >
                Kelola Lesson
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
              </router-link>
              <button @click="$router.push({ name: 'TeacherCourseEdit', params: { id: course.id } })" class="flex h-10 w-10 items-center justify-center rounded-2xl border border-slate-200 text-slate-500 transition-colors hover:border-[#cf3d3d]/40 hover:text-[#cf3d3d]" title="Edit Course">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
              </button>
              <button @click="askDelete(course)" class="flex h-10 w-10 items-center justify-center rounded-2xl border border-slate-200 text-slate-500 transition-colors hover:border-[#cf3d3d]/40 hover:bg-[#cf3d3d]/5 hover:text-[#cf3d3d]" title="Hapus Course">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
              </button>
            </div>
          </div>
        </div>
      </div>

      <div v-if="confirmId" class="flex items-center justify-between gap-4 border-t border-slate-100 bg-[#cf3d3d]/5 px-6 py-4">
        <p class="text-sm font-semibold text-slate-700">Hapus course "<span class="text-[#cf3d3d]">{{ confirmTitle }}</span>"?</p>
        <div class="flex gap-2">
          <Button variant="secondary" size="sm" @click="confirmId = null">Batal</Button>
          <Button size="sm" :disabled="deleting" @click="confirmDelete">{{ deleting ? 'Menghapus...' : 'Ya, Hapus' }}</Button>
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
import Button from '@/components/ui/Button.vue'

const { success, error } = useTeacherToast()

const loading = ref(true)
const items = ref([])
const search = ref('')
const confirmId = ref(null)
const confirmTitle = ref('')
const deleting = ref(false)

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

const askDelete = (course) => { confirmId.value = course.id; confirmTitle.value = course.title }

const confirmDelete = async () => {
  deleting.value = true
  try {
    await teacherApi.courses.remove(confirmId.value)
    success('Course berhasil dihapus.')
    confirmId.value = null
    fetchCourses()
  } catch (e) {
    error(e.message || 'Gagal menghapus course.')
  } finally {
    deleting.value = false
  }
}

onMounted(fetchCourses)
</script>

<style scoped>
.animate-fadeIn { animation: fadeIn 0.35s ease-out forwards; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: translateY(0); } }
</style>
