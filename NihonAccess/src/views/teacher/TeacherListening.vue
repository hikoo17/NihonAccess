<template>
  <div class="animate-fadeIn">
    <TeacherPageHeader eyebrow="Listening" title="Listening" subtitle="Latihan mendengarkan dengan audio dan soal.">
      <template #actions>
        <div class="relative w-full sm:w-48">
          <input v-model="search" type="text" placeholder="Cari listening..." class="h-10 w-full rounded-2xl border border-slate-200 bg-white pl-9 pr-3 text-sm font-medium text-slate-700 placeholder:text-slate-400 transition focus:border-[#cf3d3d] focus:outline-none focus:ring-2 focus:ring-[#cf3d3d]/20" />
          <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" v-html="iconSearch" />
        </div>
        <Button size="sm" @click="$router.push({ name: 'TeacherListeningCreate' })">
          <span v-html="iconPlus" /> Tambah Listening
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
        <template #action>
          <Button size="sm" @click="$router.push({ name: 'TeacherListeningCreate' })">Tambah Listening</Button>
        </template>
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
                  <p v-if="row.description" class="mt-0.5 line-clamp-1 text-xs font-medium text-slate-400">{{ row.description }}</p>
                  <p class="mt-1 text-xs font-medium text-slate-500">{{ row.course?.title || '-' }}</p>
                  <div class="mt-2 flex flex-wrap items-center gap-1.5">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-bold text-slate-600">{{ row.questions?.length || 0 }} soal</span>
                    <TeacherStatusBadge :active="row.is_active" />
                  </div>
                </div>
                <div class="flex shrink-0 gap-1.5">
                  <button @click="$router.push({ name: 'TeacherListeningEdit', params: { id: row.id } })" class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 text-slate-500 transition-colors hover:border-[#cf3d3d]/40 hover:text-[#cf3d3d]" title="Edit">
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

        <TeacherConfirmBar :model-value="confirmId" :deleting="deleting" @cancel="cancelDelete" @confirm="confirmDelete">
          <template #message>Hapus "<span class="text-[#cf3d3d]">{{ confirmLabel }}</span>"?</template>
        </TeacherConfirmBar>

        <TeacherPagination :meta="meta" @change="onPage" />
      </template>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import { teacherApi } from '@/services/teacherApi'
import { useTeacherList } from '@/composables/useTeacherList'
import { iconPlus, iconEdit, iconDelete, iconSearch } from '@/lib/teacherIcons'
import TeacherPageHeader from '@/components/teacher/ui/TeacherPageHeader.vue'
import TeacherDataTable from '@/components/teacher/ui/TeacherDataTable.vue'
import TeacherLoading from '@/components/teacher/ui/TeacherLoading.vue'
import TeacherEmptyState from '@/components/teacher/ui/TeacherEmptyState.vue'
import TeacherStatusBadge from '@/components/teacher/ui/TeacherStatusBadge.vue'
import TeacherPagination from '@/components/teacher/ui/TeacherPagination.vue'
import TeacherConfirmBar from '@/components/teacher/ui/TeacherConfirmBar.vue'
import Button from '@/components/ui/Button.vue'

const search = ref('')

const { loading, items, meta, page, confirmId, confirmLabel, deleting, fetchItems, onPage, askDelete, cancelDelete, confirmDelete } = useTeacherList({
  api: teacherApi.listening,
  label: 'Listening',
  queryFor: () => (search.value ? { search: search.value } : {}),
})

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

onMounted(fetchItems)
</script>

<style scoped>
.animate-fadeIn { animation: fadeIn 0.35s ease-out forwards; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: translateY(0); } }
</style>