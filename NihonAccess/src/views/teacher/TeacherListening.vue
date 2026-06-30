<template>
  <div class="animate-fadeIn">
    <TeacherPageHeader
      eyebrow="Listening"
      title="Listening"
      subtitle="Latihan mendengarkan dengan audio dan soal."
    >
      <template #actions>
        <div class="relative">
          <input v-model="search" type="text" placeholder="Cari listening..." class="h-10 w-48 rounded-2xl border border-slate-200 bg-white pl-9 pr-3 text-sm font-medium text-slate-700 placeholder:text-slate-400 transition focus:border-[#cf3d3d] focus:outline-none focus:ring-2 focus:ring-[#cf3d3d]/20" />
          <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
        </div>
        <Button size="sm" @click="$router.push({ name: 'TeacherListeningCreate' })">
          <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
          Tambah Listening
        </Button>
      </template>
    </TeacherPageHeader>

    <div class="rounded-3xl border border-slate-100 bg-white shadow-sm">
      <TeacherLoading v-if="loading" label="Memuat listening..." />

      <TeacherEmptyState
        v-else-if="items.length === 0"
        title="Belum ada listening"
        :description="search ? 'Tidak ada yang cocok.' : 'Buat latihan listening pertama Anda.'"
      >
        <template #action><Button size="sm" @click="$router.push({ name: 'TeacherListeningCreate' })">Tambah Listening</Button></template>
      </TeacherEmptyState>

      <template v-else>
        <TeacherDataTable :items="items" :columns="columns">
          <template #cell-title="{ row }">
            <span class="font-bold text-slate-800">{{ row.title }}</span>
            <p v-if="row.description" class="mt-0.5 line-clamp-1 text-xs font-medium text-slate-400">{{ row.description }}</p>
          </template>
          <template #cell-course="{ row }">
            <span class="font-medium text-slate-600">{{ row.course?.title || '-' }}</span>
          </template>
          <template #cell-questions="{ row }">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-600">{{ row.questions?.length || 0 }} soal</span>
          </template>
          <template #cell-is_active="{ row }"><TeacherStatusBadge :active="row.is_active" /></template>
          <template #cell-actions="{ row }">
            <div class="flex items-center justify-end gap-1.5">
              <button @click="$router.push({ name: 'TeacherListeningEdit', params: { id: row.id } })" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition-colors hover:border-[#cf3d3d]/40 hover:text-[#cf3d3d]" title="Edit">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
              </button>
              <button @click="askDelete(row)" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition-colors hover:border-[#cf3d3d]/40 hover:bg-[#cf3d3d]/5 hover:text-[#cf3d3d]" title="Hapus">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
              </button>
            </div>
          </template>
        </TeacherDataTable>

        <div v-if="confirmId" class="flex items-center justify-between gap-4 border-t border-slate-100 bg-[#cf3d3d]/5 px-6 py-4">
          <p class="text-sm font-semibold text-slate-700">Hapus "<span class="text-[#cf3d3d]">{{ confirmTitle }}</span>"?</p>
          <div class="flex gap-2">
            <Button variant="secondary" size="sm" @click="confirmId = null">Batal</Button>
            <Button size="sm" :disabled="deleting" @click="confirmDelete">{{ deleting ? 'Menghapus...' : 'Ya, Hapus' }}</Button>
          </div>
        </div>

        <TeacherPagination :meta="meta" @change="onPage" />
      </template>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import { teacherApi } from '@/services/teacherApi'
import { useTeacherToast } from '@/composables/useTeacherToast'
import TeacherPageHeader from '@/components/teacher/ui/TeacherPageHeader.vue'
import TeacherDataTable from '@/components/teacher/ui/TeacherDataTable.vue'
import TeacherLoading from '@/components/teacher/ui/TeacherLoading.vue'
import TeacherEmptyState from '@/components/teacher/ui/TeacherEmptyState.vue'
import TeacherStatusBadge from '@/components/teacher/ui/TeacherStatusBadge.vue'
import TeacherPagination from '@/components/teacher/ui/TeacherPagination.vue'
import Button from '@/components/ui/Button.vue'

const { success, error } = useTeacherToast()
const loading = ref(true)
const items = ref([])
const meta = ref(null)
const page = ref(1)
const search = ref('')
const confirmId = ref(null)
const confirmTitle = ref('')
const deleting = ref(false)

const columns = [
  { key: 'title', label: 'Judul' },
  { key: 'course', label: 'Course' },
  { key: 'questions', label: 'Soal' },
  { key: 'is_active', label: 'Status' },
  { key: 'actions', label: 'Aksi', align: 'right' },
]

let searchTimer = null
watch(search, () => {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => { page.value = 1; fetchItems() }, 350)
})

const fetchItems = async () => {
  loading.value = true
  try {
    const res = await teacherApi.listening.list({ page: page.value, per_page: 10, ...(search.value ? { search: search.value } : {}) })
    items.value = res.data
    meta.value = res.meta
  } catch (e) {
    error(e.message || 'Gagal memuat listening.')
  } finally {
    loading.value = false
  }
}

const onPage = (p) => { page.value = p; fetchItems() }
const askDelete = (row) => { confirmId.value = row.id; confirmTitle.value = row.title }
const confirmDelete = async () => {
  deleting.value = true
  try {
    await teacherApi.listening.remove(confirmId.value)
    success('Listening berhasil dihapus.')
    confirmId.value = null
    fetchItems()
  } catch (e) {
    error(e.message || 'Gagal menghapus listening.')
  } finally {
    deleting.value = false
  }
}

onMounted(fetchItems)
</script>

<style scoped>
.animate-fadeIn { animation: fadeIn 0.35s ease-out forwards; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: translateY(0); } }
</style>
