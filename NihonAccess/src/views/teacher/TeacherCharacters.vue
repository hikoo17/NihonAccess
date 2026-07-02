<template>
  <div class="animate-fadeIn">
    <TeacherPageHeader eyebrow="Karakter & Huruf" title="Karakter & Huruf" subtitle="Latihan menulis dan menebak karakter Jepang.">
      <template #actions>
        <select v-model="filterType" class="h-10 w-full rounded-2xl border border-slate-200 bg-white px-3 text-sm font-semibold text-slate-700 transition focus:border-[#cf3d3d] focus:outline-none focus:ring-2 focus:ring-[#cf3d3d]/20 sm:w-auto">
          <option value="">Semua tipe</option>
          <option value="hiragana">Hiragana</option>
          <option value="katakana">Katakana</option>
          <option value="kanji">Kanji</option>
        </select>
        <Button size="sm" @click="$router.push({ name: 'TeacherCharacterCreate' })">
          <span v-html="iconPlus" /> Tambah Karakter
        </Button>
      </template>
    </TeacherPageHeader>

    <div class="rounded-3xl border border-slate-100 bg-white shadow-sm">
      <TeacherLoading v-if="loading" label="Memuat karakter..." />

      <TeacherEmptyState
        v-else-if="items.length === 0"
        title="Belum ada karakter"
        description="Tambahkan latihan karakter pertama Anda."
      >
        <template #action><Button size="sm" @click="$router.push({ name: 'TeacherCharacterCreate' })">Tambah Karakter</Button></template>
      </TeacherEmptyState>

      <template v-else>
        <TeacherDataTable :items="items" :columns="columns">
          <template #cell-character="{ row }">
            <div class="flex items-center gap-3">
              <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 font-jp text-xl font-bold text-slate-700">{{ row.character }}</span>
              <span class="font-bold text-slate-800">{{ row.answer }}</span>
            </div>
          </template>
          <template #cell-character_type="{ row }">
            <span class="inline-flex items-center rounded-full bg-[#cf3d3d]/10 px-2.5 py-1 text-xs font-bold capitalize text-[#cf3d3d]">{{ row.character_type }}</span>
          </template>
          <template #cell-course="{ row }">
            <span class="font-medium text-slate-600">{{ row.course?.title || '-' }}</span>
          </template>
          <template #cell-options="{ row }">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-600">{{ (row.options || []).length }} opsi</span>
          </template>
          <template #cell-is_active="{ row }"><TeacherStatusBadge :active="row.is_active" /></template>
          <template #cell-actions="{ row }">
            <div class="flex items-center justify-end gap-1.5">
              <button @click="$router.push({ name: 'TeacherCharacterEdit', params: { id: row.id } })" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition-colors hover:border-[#cf3d3d]/40 hover:text-[#cf3d3d]" title="Edit">
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
                <div class="flex min-w-0 flex-1 items-start gap-3">
                  <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-slate-100 font-jp text-xl font-bold text-slate-700">{{ row.character }}</span>
                  <div class="min-w-0">
                    <span class="font-bold text-slate-800">{{ row.answer }}</span>
                    <p class="mt-0.5 text-xs font-medium text-slate-500">{{ row.course?.title || '-' }}</p>
                    <div class="mt-2 flex flex-wrap items-center gap-1.5">
                      <span class="inline-flex items-center rounded-full bg-[#cf3d3d]/10 px-2 py-0.5 text-[11px] font-bold capitalize text-[#cf3d3d]">{{ row.character_type }}</span>
                      <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-bold text-slate-600">{{ (row.options || []).length }} opsi</span>
                      <TeacherStatusBadge :active="row.is_active" />
                    </div>
                  </div>
                </div>
                <div class="flex shrink-0 gap-1.5">
                  <button @click="$router.push({ name: 'TeacherCharacterEdit', params: { id: row.id } })" class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 text-slate-500 transition-colors hover:border-[#cf3d3d]/40 hover:text-[#cf3d3d]" title="Edit">
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
          <template #message>Hapus karakter "<span class="font-jp text-[#cf3d3d]">{{ confirmLabel }}</span>"?</template>
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
  api: teacherApi.characters,
  label: 'Karakter',
  labelKey: 'character',
  queryFor: () => (filterType.value ? { character_type: filterType.value } : {}),
})

const columns = [
  { key: 'character', label: 'Karakter' },
  { key: 'character_type', label: 'Tipe' },
  { key: 'course', label: 'Course' },
  { key: 'options', label: 'Opsi', align: 'center' },
  { key: 'is_active', label: 'Status' },
  { key: 'actions', label: 'Aksi', align: 'right' },
]

watch(filterType, () => { page.value = 1; fetchItems() })
onMounted(fetchItems)
</script>

<style scoped>
.animate-fadeIn { animation: fadeIn 0.35s ease-out forwards; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: translateY(0); } }
</style>