<template>
  <div class="animate-fadeIn">
    <TeacherPageHeader
      :eyebrow="courseTitle || 'Course'"
      title="Kelola Lesson"
      :subtitle="`Daftar lesson di dalam ${courseTitle || 'course ini'}.`"
      :back-to="{ name: 'TeacherCourses' }"
    >
      <template #actions>
        <Button size="sm" @click="$router.push({ name: 'TeacherLessonCreate', params: { courseId } })">
          <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
          Tambah Lesson
        </Button>
      </template>
    </TeacherPageHeader>

    <div class="rounded-3xl border border-slate-100 bg-white shadow-sm">
      <TeacherLoading v-if="loading" label="Memuat lesson..." />

      <TeacherEmptyState
        v-else-if="items.length === 0"
        title="Belum ada lesson"
        description="Tambahkan lesson pertama untuk course ini."
      >
        <template #action>
          <Button size="sm" @click="$router.push({ name: 'TeacherLessonCreate', params: { courseId } })">Tambah Lesson</Button>
        </template>
      </TeacherEmptyState>

      <template v-else>
        <TeacherDataTable :items="items" :columns="columns">
          <template #cell-title="{ row }">
            <span class="font-bold text-slate-800">{{ row.title }}</span>
            <p v-if="row.slug" class="text-xs font-medium text-slate-400">/{{ row.slug }}</p>
          </template>
          <template #cell-sort_order="{ row }">
            <span class="font-semibold text-slate-500">#{{ row.sort_order ?? 0 }}</span>
          </template>
          <template #cell-is_active="{ row }">
            <TeacherStatusBadge :active="row.is_active" />
          </template>
          <template #cell-actions="{ row }">
            <div class="flex items-center justify-end gap-1.5">
              <button @click="$router.push({ name: 'TeacherLessonEdit', params: { courseId, id: row.id } })" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition-colors hover:border-[#cf3d3d]/40 hover:text-[#cf3d3d]" title="Edit">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
              </button>
              <button @click="askDelete(row)" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition-colors hover:border-[#cf3d3d]/40 hover:bg-[#cf3d3d]/5 hover:text-[#cf3d3d]" title="Hapus">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
              </button>
            </div>
          </template>
        </TeacherDataTable>

        <div v-if="confirmId" class="flex items-center justify-between gap-4 border-t border-slate-100 bg-[#cf3d3d]/5 px-6 py-4">
          <p class="text-sm font-semibold text-slate-700">Hapus lesson "<span class="text-[#cf3d3d]">{{ confirmTitle }}</span>"?</p>
          <div class="flex gap-2">
            <Button variant="secondary" size="sm" @click="confirmId = null">Batal</Button>
            <Button size="sm" :disabled="deleting" @click="confirmDelete">{{ deleting ? 'Menghapus...' : 'Ya, Hapus' }}</Button>
          </div>
        </div>
      </template>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { teacherApi } from '@/services/teacherApi'
import { useTeacherToast } from '@/composables/useTeacherToast'
import TeacherPageHeader from '@/components/teacher/ui/TeacherPageHeader.vue'
import TeacherDataTable from '@/components/teacher/ui/TeacherDataTable.vue'
import TeacherLoading from '@/components/teacher/ui/TeacherLoading.vue'
import TeacherEmptyState from '@/components/teacher/ui/TeacherEmptyState.vue'
import TeacherStatusBadge from '@/components/teacher/ui/TeacherStatusBadge.vue'
import Button from '@/components/ui/Button.vue'

const route = useRoute()
const { success, error } = useTeacherToast()
const courseId = route.params.courseId

const loading = ref(true)
const items = ref([])
const courseTitle = ref('')
const confirmId = ref(null)
const confirmTitle = ref('')
const deleting = ref(false)

const columns = [
  { key: 'title', label: 'Judul' },
  { key: 'sort_order', label: 'Urutan' },
  { key: 'is_active', label: 'Status' },
  { key: 'actions', label: 'Aksi', align: 'right' },
]

const fetchLessons = async () => {
  loading.value = true
  try {
    const res = await teacherApi.courses.get(courseId)
    courseTitle.value = res.data.title
    items.value = res.data.lessons || []
  } catch (e) {
    error(e.message || 'Gagal memuat lesson.')
  } finally {
    loading.value = false
  }
}

const askDelete = (row) => {
  confirmId.value = row.id
  confirmTitle.value = row.title
}

const confirmDelete = async () => {
  deleting.value = true
  try {
    await teacherApi.lessons.remove(confirmId.value)
    success('Lesson berhasil dihapus.')
    items.value = items.value.filter((i) => i.id !== confirmId.value)
    confirmId.value = null
  } catch (e) {
    error(e.message || 'Gagal menghapus lesson.')
  } finally {
    deleting.value = false
  }
}

onMounted(fetchLessons)
</script>

<style scoped>
.animate-fadeIn { animation: fadeIn 0.35s ease-out forwards; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: translateY(0); } }
</style>
