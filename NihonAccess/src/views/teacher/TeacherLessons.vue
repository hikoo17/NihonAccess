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
          <span v-html="iconPlus" /> Tambah Lesson
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
                <span v-html="iconEdit" />
              </button>
              <button @click="askDelete(row)" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition-colors hover:border-[#cf3d3d]/40 hover:bg-[#cf3d3d]/5 hover:text-[#cf3d3d]" title="Hapus">
                <span v-html="iconDelete" />
              </button>
            </div>
          </template>

          <!-- kartu mobile -->
          <template #mobile="{ items }">
            <div v-for="row in items" :key="row.id" class="p-4">
              <div class="flex items-start justify-between gap-3">
                <div class="min-w-0 flex-1">
                  <span class="font-bold text-slate-800">{{ row.title }}</span>
                  <p v-if="row.slug" class="text-xs font-medium text-slate-400">/{{ row.slug }}</p>
                  <div class="mt-2 flex flex-wrap items-center gap-1.5">
                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-bold text-slate-600">Urutan #{{ row.sort_order ?? 0 }}</span>
                    <TeacherStatusBadge :active="row.is_active" />
                  </div>
                </div>
                <div class="flex shrink-0 gap-1.5">
                  <button @click="$router.push({ name: 'TeacherLessonEdit', params: { courseId, id: row.id } })" class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 text-slate-500 transition-colors hover:border-[#cf3d3d]/40 hover:text-[#cf3d3d]" title="Edit">
                    <span v-html="iconEdit" />
                  </button>
                  <button @click="askDelete(row)" class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 text-slate-500 transition-colors hover:border-[#cf3d3d]/40 hover:bg-[#cf3d3d]/5 hover:text-[#cf3d3d]" title="Hapus">
                    <span v-html="iconDelete" />
                  </button>
                </div>
              </div>
            </div>
          </template>
        </TeacherDataTable>

        <TeacherConfirmBar :model-value="confirmId" :deleting="deleting" @cancel="confirmId = null" @confirm="confirmDelete">
          <template #message>Hapus lesson "<span class="text-[#cf3d3d]">{{ confirmTitle }}</span>"?</template>
        </TeacherConfirmBar>
      </template>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { teacherApi } from '@/services/teacherApi'
import { useTeacherToast } from '@/composables/useTeacherToast'
import { iconPlus, iconEdit, iconDelete } from '@/lib/teacherIcons'
import TeacherPageHeader from '@/components/teacher/ui/TeacherPageHeader.vue'
import TeacherDataTable from '@/components/teacher/ui/TeacherDataTable.vue'
import TeacherLoading from '@/components/teacher/ui/TeacherLoading.vue'
import TeacherEmptyState from '@/components/teacher/ui/TeacherEmptyState.vue'
import TeacherStatusBadge from '@/components/teacher/ui/TeacherStatusBadge.vue'
import TeacherConfirmBar from '@/components/teacher/ui/TeacherConfirmBar.vue'
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