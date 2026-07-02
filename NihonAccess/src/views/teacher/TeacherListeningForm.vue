<template>
  <div class="animate-fadeIn">
    <TeacherPageHeader
      eyebrow="Listening"
      :title="isEdit ? 'Edit Listening' : 'Tambah Listening'"
      subtitle="Latihan mendengarkan dengan soal isian/pilihan."
      :back-to="{ name: 'TeacherListening' }"
    >
      <template #actions>
        <Button variant="secondary" size="sm" @click="$router.push({ name: 'TeacherListening' })">Batal</Button>
        <Button size="sm" :disabled="submitting" @click="submit">
          <span v-if="submitting">Menyimpan...</span>
          <span v-else>{{ isEdit ? 'Simpan Perubahan' : 'Simpan' }}</span>
        </Button>
      </template>
    </TeacherPageHeader>

    <div class="mx-auto max-w-4xl space-y-6">
      <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm sm:p-8">
        <div v-if="loading" class="py-12"><TeacherLoading label="Memuat data..." /></div>

        <form v-else class="space-y-5" @submit.prevent="submit">
          <div>
            <label class="mb-1.5 block text-sm font-bold text-slate-700">Course <span class="text-[#cf3d3d]">*</span></label>
            <select v-model="form.course_id" :class="formInputClass(errors.course_id)" :disabled="isEdit">
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
          </div>

          <div>
            <label class="mb-1.5 block text-sm font-bold text-slate-700">Judul <span class="text-[#cf3d3d]">*</span></label>
            <input v-model="form.title" type="text" placeholder="cth. Listening Hiragana" :class="formInputClass(errors.title)" />
            <p v-if="errors.title" class="mt-1 text-xs font-semibold text-[#cf3d3d]">{{ errors.title[0] }}</p>
          </div>

          <div>
            <label class="mb-1.5 block text-sm font-bold text-slate-700">Deskripsi</label>
            <textarea v-model="form.description" rows="3" :class="formInputClass(errors.description)"></textarea>
          </div>

          <div>
            <label class="mb-1.5 block text-sm font-bold text-slate-700">URL Audio <span class="text-[#cf3d3d]">*</span></label>
            <input v-model="form.audio_url" type="url" placeholder="https://..." :class="formInputClass(errors.audio_url)" />
            <p v-if="errors.audio_url" class="mt-1 text-xs font-semibold text-[#cf3d3d]">{{ errors.audio_url[0] }}</p>
          </div>

          <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50/60 px-4 py-3">
            <input v-model="form.is_active" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-[#cf3d3d] focus:ring-[#cf3d3d]/30" />
            <span class="text-sm font-semibold text-slate-700">Aktifkan latihan ini</span>
          </label>
        </form>
      </div>

      <div v-if="!loading" class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm sm:p-8">
        <div class="mb-5 flex items-center justify-between">
          <div>
            <h3 class="text-base font-extrabold text-slate-900">Daftar Soal</h3>
            <p class="text-sm font-medium text-slate-500">{{ form.questions.length }} soal</p>
          </div>
          <div class="flex items-center gap-2">
            <Button variant="outline" size="sm" @click="addQuestion">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
              Tambah Soal
            </Button>
            <Button variant="secondary" size="sm" :disabled="!canGenerateAi || generating" :title="aiTooltip" @click="generateAi">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.455z" /></svg>
              Generate Soal (AI)
            </Button>
          </div>
        </div>

        <div v-if="generating" class="rounded-2xl border border-slate-200 bg-slate-50 py-6 text-center">
          <TeacherLoading label="AI sedang membuat soal..." />
        </div>
        <p v-if="genError" class="mt-2 text-xs font-semibold text-[#cf3d3d]">
          {{ genError }} &mdash; <button class="underline" @click="generateAi">coba lagi</button>
        </p>

        <div v-if="form.questions.length === 0 && !generating" class="rounded-2xl border border-dashed border-slate-200 py-10 text-center">
          <p class="text-sm font-medium text-slate-400">Belum ada soal. Klik "Tambah Soal" untuk mulai.</p>
        </div>

        <div v-else class="space-y-5">
          <div v-for="(q, idx) in form.questions" :key="idx" class="rounded-2xl border border-slate-200 bg-slate-50/50 p-5">
            <div class="mb-4 flex items-center justify-between">
              <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-[#cf3d3d] text-xs font-bold text-white">{{ idx + 1 }}</span>
              <button @click="removeQuestion(idx)" class="text-slate-400 transition-colors hover:text-[#cf3d3d]">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
              </button>
            </div>

            <div class="space-y-4">
              <div>
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Pertanyaan</label>
                <textarea v-model="q.question" rows="2" placeholder="Tulis pertanyaan..." :class="formInputClass(errors[`questions.${idx}.question`])"></textarea>
                <p v-if="errors[`questions.${idx}.question`]" class="mt-1 text-xs font-semibold text-[#cf3d3d]">{{ errors[`questions.${idx}.question`][0] }}</p>
              </div>

              <div>
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Opsi Jawaban (opsional, satu per baris)</label>
                <textarea v-model="q.optionsText" rows="3" placeholder="Kosongkan jika soal isian" :class="formInputClass()"></textarea>
              </div>

              <div>
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Jawaban Benar</label>
                <input v-model="q.correct_answer" type="text" :class="formInputClass(errors[`questions.${idx}.correct_answer`])" />
                <p v-if="errors[`questions.${idx}.correct_answer`]" class="mt-1 text-xs font-semibold text-[#cf3d3d]">{{ errors[`questions.${idx}.correct_answer`][0] }}</p>
              </div>
            </div>
          </div>
        </div>

        <div v-if="!loading" class="mt-6 flex justify-end gap-3 border-t border-slate-100 pt-6">
          <Button variant="secondary" size="sm" @click="$router.push({ name: 'TeacherListening' })">Batal</Button>
          <Button size="sm" :disabled="submitting" @click="submit">
            <span v-if="submitting">Menyimpan...</span>
            <span v-else>{{ isEdit ? 'Simpan Perubahan' : 'Simpan' }}</span>
          </Button>
        </div>
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
import { useAiGeneration } from '@/composables/useAiGeneration'
import TeacherPageHeader from '@/components/teacher/ui/TeacherPageHeader.vue'
import TeacherLoading from '@/components/teacher/ui/TeacherLoading.vue'
import Button from '@/components/ui/Button.vue'
import { formInputClass } from '@/lib/form'

const route = useRoute()
const router = useRouter()
const { success, error } = useTeacherToast()
const { courses, fetchCourses, fetchLessonsFor } = useTeacherOptions()
const { generating, genError, start } = useAiGeneration()

const editId = route.params.id
const isEdit = computed(() => !!editId)

const loading = ref(true)
const submitting = ref(false)
const lessons = ref([])
const errors = ref({})

const blankQuestion = () => ({ question: '', optionsText: '', correct_answer: '', sort_order: 0 })

const form = reactive({
  course_id: '',
  lesson_id: null,
  title: '',
  description: '',
  audio_url: '',
  is_active: true,
  questions: [],
})


const addQuestion = () => { form.questions.push(blankQuestion()) }
const removeQuestion = (idx) => { form.questions.splice(idx, 1) }

const canGenerateAi = computed(() => !!form.audio_url)
const aiTooltip = computed(() =>
  !form.audio_url ? 'Isi URL Audio dulu' : 'Generate soal dari audio')

const generateAi = async () => {
  if (!canGenerateAi.value) {
    error('Isi URL Audio terlebih dahulu.')
    return
  }
  genError.value = ''
  try {
    const drafts = await start({
      type: 'listening',
      payload: { audio_url: form.audio_url, count: 5 },
      toDraft: (result) => result.map((q) => ({
        question: q.question,
        optionsText: Array.isArray(q.options) ? q.options.join('\n') : '',
        correct_answer: q.correct_answer,
        sort_order: form.questions.length,
      })),
    })
    form.questions.push(...drafts)
    success(`${drafts.length} soal ditambahkan. Edit dulu sebelum simpan.`)
  } catch (e) {
    error(e.message || 'Gagal generate soal.')
  }
}

const loadLessons = async (cid) => {
  if (!cid) { lessons.value = []; return }
  lessons.value = await fetchLessonsFor(cid)
}
watch(() => form.course_id, (cid) => loadLessons(cid))

const parseOptions = (text) => {
  const arr = String(text || '').split('\n').map((s) => s.trim()).filter(Boolean)
  return arr.length ? arr : null
}

const buildPayload = () => ({
  course_id: Number(form.course_id),
  lesson_id: form.lesson_id ? Number(form.lesson_id) : null,
  title: form.title,
  description: form.description || null,
  audio_url: form.audio_url,
  is_active: !!form.is_active,
  questions: form.questions.map((q, i) => ({
    question: q.question,
    options: parseOptions(q.optionsText),
    correct_answer: q.correct_answer,
    sort_order: i,
  })),
})

const submit = async () => {
  submitting.value = true
  errors.value = {}
  try {
    if (isEdit.value) {
      await teacherApi.listening.update(editId, buildPayload())
      success('Listening berhasil diperbarui.')
    } else {
      await teacherApi.listening.create(buildPayload())
      success('Listening berhasil dibuat.')
    }
    router.push({ name: 'TeacherListening' })
  } catch (e) {
    if (e.errors) errors.value = e.errors
    error(e.message || 'Gagal menyimpan listening.')
  } finally {
    submitting.value = false
  }
}

onMounted(async () => {
  await fetchCourses()
  if (isEdit.value) {
    try {
      const res = await teacherApi.listening.get(editId)
      const d = res.data
      form.course_id = d.course_id
      form.lesson_id = d.lesson_id
      form.title = d.title
      form.description = d.description || ''
      form.audio_url = d.audio_url || ''
      form.is_active = d.is_active ?? true
      form.questions = (d.questions || []).map((q) => ({
        question: q.question || '',
        optionsText: Array.isArray(q.options) ? q.options.join('\n') : '',
        correct_answer: q.correct_answer || '',
        sort_order: q.sort_order ?? 0,
      }))
      await loadLessons(d.course_id)
    } catch (e) {
      error('Gagal memuat data listening.')
    }
  }
  loading.value = false
})
</script>

