<template>
  <div class="animate-fadeIn">
    <TeacherPageHeader eyebrow="Writing (Tebak Huruf)" title="Writing" subtitle="Latihan tebak huruf dengan urutan goresan.">
      <template #actions>
        <select v-model="filterType" class="h-10 rounded-2xl border border-slate-200 bg-white px-3 text-sm font-semibold text-slate-700 transition focus:border-[#cf3d3d] focus:outline-none focus:ring-2 focus:ring-[#cf3d3d]/20">
          <option value="">Semua tipe</option>
          <option value="hiragana">Hiragana</option>
          <option value="katakana">Katakana</option>
          <option value="kanji">Kanji</option>
        </select>
        <Button size="sm" @click="$router.push({ name: 'TeacherWritingCreate' })">
          <span v-html="iconPlus" /> Tambah Writing
        </Button>
      </template>
    </TeacherPageHeader>

    <div class="rounded-3xl border border-slate-100 bg-white shadow-sm">
      <TeacherLoading v-if="loading" label="Memuat writing..." />

      <TeacherEmptyState
        v-else-if="items.length === 0"
        title="Belum ada writing"
        description="Tambahkan latihan writing pertama Anda."
      >
        <template #action><Button size="sm" @click="$router.push({ name: 'TeacherWritingCreate' })">Tambah Writing</Button></template>
      </TeacherEmptyState>

      <template v-else>
        <TeacherDataTable :items="items" :columns="columns">
          <template #cell-character="{ row }">
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 font-jp text-xl font-bold text-slate-700">{{ row.character }}</span>
          </template>
          <template #cell-character_type="{ row }">
            <span class="inline-flex items-center rounded-full bg-[#cf3d3d]/10 px-2.5 py-1 text-xs font-bold capitalize text-[#cf3d3d]">{{ row.character_type }}</span>
          </template>
          <template #cell-course="{ row }">
            <span class="font-medium text-slate-600">{{ row.course?.title || '-' }}</span>
          </template>
          <template #cell-is_active="{ row }"><TeacherStatusBadge :active="row.is_active" /></template>
          <template #cell-actions="{ row }">
            <div class="flex items-center justify-end gap-1.5">
              <button @click="$router.push({ name: 'TeacherWritingEdit', params: { id: row.id } })" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition-colors hover:border-[#cf3d3d]/40 hover:text-[#cf3d3d]" title="Edit">
                <span v-html="iconEdit" />
              </button>
              <button @click="askDelete(row)" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition-colors hover:border-[#cf3d3d]/40 hover:bg-[#cf3d3d]/5 hover:text-[#cf3d3d]" title="Hapus">
                <span v-html="iconDelete" />
              </button>
            </div>
          </template>
        </TeacherDataTable>

        <TeacherConfirmBar :model-value="confirmId" :deleting="deleting" @cancel="cancelDelete" @confirm="confirmDelete">
          <template #message>Hapus "<span class="font-jp text-[#cf3d3d]">{{ confirmLabel }}</span>"?</template>
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
import { iconPlus, iconEdit, iconDelete } from '@/lib/teacherIcons'
import TeacherPageHeader from '@/components/teacher/ui/TeacherPageHeader.vue'
import TeacherDataTable from '@/components/teacher/ui/TeacherDataTable.vue'
import TeacherLoading from '@/components/teacher/ui/TeacherLoading.vue'
import TeacherEmptyState from '@/components/teacher/ui/TeacherEmptyState.vue'
import TeacherStatusBadge from '@/components/teacher/ui/TeacherStatusBadge.vue'
import TeacherPagination from '@/components/teacher/ui/TeacherPagination.vue'
import TeacherConfirmBar from '@/components/teacher/ui/TeacherConfirmBar.vue'
import Button from '@/components/ui/Button.vue'

const filterType = ref('')

const { loading, items, meta, page, confirmId, confirmLabel, deleting, fetchItems, onPage, askDelete, cancelDelete, confirmDelete } = useTeacherList({
  api: teacherApi.writing,
  label: 'Writing',
  labelKey: 'character',
  queryFor: () => (filterType.value ? { character_type: filterType.value } : {}),
})

const columns = [
  { key: 'character', label: 'Karakter' },
  { key: 'character_type', label: 'Tipe' },
  { key: 'course', label: 'Course' },
  { key: 'is_active', label: 'Status' },
  { key: 'actions', label: 'Aksi', align: 'right' },
]

watch(filterType, () => { page.value = 1; fetchItems() })

onMounted(fetchItems)
</script>