<template>
  <div class="animate-fadeIn">
    <TeacherPageHeader
      eyebrow="Karakter & Huruf"
      :title="isEdit ? 'Edit Karakter' : 'Tambah Karakter'"
      subtitle="Latihan menulis/menebak karakter Jepang."
      :back-to="{ name: 'TeacherCharacters' }"
    >
      <template #actions>
        <Button variant="secondary" size="sm" @click="$router.push({ name: 'TeacherCharacters' })">Batal</Button>
        <Button size="sm" :disabled="submitting" @click="submit">
          <span v-if="submitting">Menyimpan...</span>
          <span v-else>{{ isEdit ? 'Simpan Perubahan' : 'Simpan' }}</span>
        </Button>
      </template>
    </TeacherPageHeader>

    <div class="mx-auto max-w-3xl">
      <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm sm:p-8">
        <div v-if="loading" class="py-12"><TeacherLoading label="Memuat data..." /></div>

        <form v-else class="space-y-5" @submit.prevent="submit">
          <div>
            <label class="mb-1.5 block text-sm font-bold text-slate-700">Course <span class="text-[#cf3d3d]">*</span></label>
            <select v-model="form.course_id" :class="formInputClass(errors.course_id)">
              <option value="" disabled>Pilih course</option>
              <option v-for="c in courses" :key="c.id" :value="c.id">{{ c.title }}</option>
            </select>
            <p v-if="errors.course_id" class="mt-1 text-xs font-semibold text-[#cf3d3d]">{{ errors.course_id[0] }}</p>
          </div>

          <div>
            <label class="mb-1.5 block text-sm font-bold text-slate-700">Lesson (opsional)</label>
            <select v-model="form.lesson_id" :disabled="!form.course_id" :class="formInputClass(errors.lesson_id)">
              <option :value="null">Tanpa lesson</option>
              <option v-for="l in lessons" :key="l.id" :value="l.id">{{ l.title }}</option>
            </select>
            <p class="mt-1 text-xs font-medium text-slate-400">Pilih lesson dulu untuk memuat daftar.</p>
          </div>

          <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
              <label class="mb-1.5 block text-sm font-bold text-slate-700">Tipe Karakter <span class="text-[#cf3d3d]">*</span></label>
              <select v-model="form.character_type" :class="formInputClass(errors.character_type)">
                <option value="" disabled>Pilih tipe</option>
                <option value="hiragana">Hiragana</option>
                <option value="katakana">Katakana</option>
                <option value="kanji">Kanji</option>
              </select>
              <p v-if="errors.character_type" class="mt-1 text-xs font-semibold text-[#cf3d3d]">{{ errors.character_type[0] }}</p>
            </div>
            <div>
              <label class="mb-1.5 block text-sm font-bold text-slate-700">Karakter <span class="text-[#cf3d3d]">*</span></label>
              <input v-model="form.character" type="text" placeholder="cth. あ" :class="formInputClass(errors.character)" />
              <p v-if="errors.character" class="mt-1 text-xs font-semibold text-[#cf3d3d]">{{ errors.character[0] }}</p>
            </div>
          </div>

          <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
              <label class="mb-1.5 block text-sm font-bold text-slate-700">Jawaban <span class="text-[#cf3d3d]">*</span></label>
              <input v-model="form.answer" type="text" placeholder="cth. a" :class="formInputClass(errors.answer)" @input="syncAnswerToOptions" />
              <p v-if="errors.answer" class="mt-1 text-xs font-semibold text-[#cf3d3d]">{{ errors.answer[0] }}</p>
            </div>
            <div>
              <label class="mb-1.5 block text-sm font-bold text-slate-700">Hint (opsional)</label>
              <input v-model="form.hint" type="text" placeholder="Petunjuk jawaban" :class="formInputClass(errors.hint)" />
              <p v-if="errors.hint" class="mt-1 text-xs font-semibold text-[#cf3d3d]">{{ errors.hint[0] }}</p>
            </div>
          </div>

          <div>
            <div class="mb-1.5 flex items-center justify-between">
              <label class="block text-sm font-bold text-slate-700">Opsi Jawaban <span class="text-[#cf3d3d]">*</span></label>
              <button type="button" @click="fillAnswerAsOption" class="text-xs font-bold text-[#cf3d3d] hover:underline">Isi jawaban ke opsi 1</button>
            </div>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
              <div v-for="(_, i) in form.options" :key="i">
                <div class="flex items-center gap-2">
                  <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-xs font-bold text-slate-500">{{ String.fromCharCode(65 + i) }}</span>
                  <input v-model="form.options[i]" type="text" :placeholder="`Opsi ${String.fromCharCode(65 + i)}`" :class="formInputClass(optionsErrors[i])" />
                </div>
                <p v-if="optionsErrors[i]" class="mt-1 text-xs font-semibold text-[#cf3d3d]">{{ optionsErrors[i] }}</p>
              </div>
            </div>
            <p v-if="optionsValidationError" class="mt-1.5 text-xs font-semibold text-[#cf3d3d]">{{ optionsValidationError }}</p>
            <p class="mt-1.5 text-xs font-medium text-slate-400">Isi 4 opsi. Salah satu opsi harus sama persis dengan jawaban benar. Untuk hiragana/katakana cukup tulis romaji.</p>
          </div>

          <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50/60 px-4 py-3">
            <input v-model="form.is_active" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-[#cf3d3d] focus:ring-[#cf3d3d]/30" />
            <span class="text-sm font-semibold text-slate-700">Aktifkan latihan ini</span>
          </label>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { teacherApi } from '@/services/teacherApi'
import { useTeacherOptions } from '@/composables/useTeacherOptions'
import { useTeacherToast } from '@/composables/useTeacherToast'
import TeacherPageHeader from '@/components/teacher/ui/TeacherPageHeader.vue'
import TeacherLoading from '@/components/teacher/ui/TeacherLoading.vue'
import Button from '@/components/ui/Button.vue'
import { formInputClass } from '@/lib/form'

const route = useRoute()
const router = useRouter()
const { success, error } = useTeacherToast()
const { courses, fetchCourses, fetchLessonsFor } = useTeacherOptions()

const editId = route.params.id
const isEdit = computed(() => !!editId)

const loading = ref(true)
const submitting = ref(false)
const lessons = ref([])
const errors = ref({})
const optionsValidationError = ref('')
const optionsErrors = ref([])

const form = reactive({
  course_id: '',
  lesson_id: null,
  character_type: '',
  character: '',
  answer: '',
  options: ['', '', '', ''],
  hint: '',
  is_active: true,
})

const loadLessons = async (cid) => {
  if (!cid) { lessons.value = []; return }
  lessons.value = await fetchLessonsFor(cid)
}

watch(() => form.course_id, (cid) => loadLessons(cid))

const fillAnswerAsOption = () => {
  if (form.answer) form.options[0] = form.answer
}

const syncAnswerToOptions = () => {
  // kosongkan error opsi saat jawaban berubah
  optionsValidationError.value = ''
}

const validateOptions = () => {
  optionsErrors.value = []
  optionsValidationError.value = ''
  let valid = true

  const filled = form.options.map((o) => (o || '').trim())
  const emptyIdx = filled.findIndex((o) => o === '')
  if (emptyIdx !== -1) {
    optionsErrors.value = form.options.map(() => '')
    optionsErrors.value[emptyIdx] = 'Opsi tidak boleh kosong.'
    valid = false
  }

  const lower = filled.map((o) => o.toLowerCase())
  if (new Set(lower).size !== lower.length) {
    optionsValidationError.value = 'Ada opsi yang duplikat. Pastikan 4 opsi berbeda.'
    valid = false
  }

  const answerLower = (form.answer || '').trim().toLowerCase()
  if (answerLower && !lower.includes(answerLower)) {
    optionsValidationError.value = 'Jawaban benar harus sama dengan salah satu opsi.'
    valid = false
  }

  return valid
}

const buildPayload = () => ({
  course_id: Number(form.course_id),
  lesson_id: form.lesson_id ? Number(form.lesson_id) : null,
  character_type: form.character_type,
  character: form.character,
  answer: form.answer,
  options: form.options.map((o) => o.trim()),
  hint: form.hint || null,
  is_active: !!form.is_active,
})

const submit = async () => {
  if (!validateOptions()) return
  submitting.value = true
  errors.value = {}
  try {
    if (isEdit.value) {
      await teacherApi.characters.update(editId, buildPayload())
      success('Karakter berhasil diperbarui.')
    } else {
      await teacherApi.characters.create(buildPayload())
      success('Karakter berhasil dibuat.')
    }
    router.push({ name: 'TeacherCharacters' })
  } catch (e) {
    if (e.errors) errors.value = e.errors
    error(e.message || 'Gagal menyimpan karakter.')
  } finally {
    submitting.value = false
  }
}

onMounted(async () => {
  await fetchCourses()
  if (isEdit.value) {
    try {
      const res = await teacherApi.characters.get(editId)
      const d = res.data
      form.course_id = d.course_id
      form.lesson_id = d.lesson_id
      form.character_type = d.character_type
      form.character = d.character
      form.answer = d.answer
      form.options = Array.isArray(d.options) && d.options.length === 4
        ? [...d.options]
        : [d.answer, '', '', '']
      form.hint = d.hint || ''
      form.is_active = d.is_active ?? true
      await loadLessons(d.course_id)
    } catch (e) {
      error('Gagal memuat data karakter.')
    }
  }
  loading.value = false
})
</script>
