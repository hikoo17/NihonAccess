<template>
  <div class="animate-fadeIn">
    <TeacherPageHeader
      :eyebrow="courseTitle || 'Course'"
      :title="isEdit ? 'Edit Lesson' : 'Tambah Lesson'"
      subtitle="Isi materi pelajaran untuk course ini."
      :back-to="{ name: 'TeacherLessons', params: { courseId } }"
    >
      <template #actions>
        <Button variant="secondary" size="sm" @click="goBack">Batal</Button>
        <Button size="sm" :disabled="submitting" @click="submit">
          <span v-if="submitting">Menyimpan...</span>
          <span v-else>{{ isEdit ? 'Simpan Perubahan' : 'Simpan Lesson' }}</span>
        </Button>
      </template>
    </TeacherPageHeader>

    <div class="mx-auto max-w-3xl">
      <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm sm:p-8">
        <div v-if="loading" class="py-12"><TeacherLoading label="Memuat data lesson..." /></div>

        <form v-else class="space-y-5" @submit.prevent="submit">
          <div>
            <label class="mb-1.5 block text-sm font-bold text-slate-700">Judul Lesson <span class="text-[#cf3d3d]">*</span></label>
            <input v-model="form.title" type="text" placeholder="cth. Hiragana Dasar - A I U E O"
              :class="formInputClass(errors.title)" />
            <p v-if="errors.title" class="mt-1 text-xs font-semibold text-[#cf3d3d]">{{ errors.title[0] }}</p>
          </div>

          <div>
            <label class="mb-1.5 block text-sm font-bold text-slate-700">Konten</label>
            <textarea v-model="form.content" rows="6" placeholder="Materi penjelasan lesson..."
              :class="formInputClass(errors.content)"></textarea>
            <p v-if="errors.content" class="mt-1 text-xs font-semibold text-[#cf3d3d]">{{ errors.content[0] }}</p>
          </div>

          <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
              <label class="mb-1.5 block text-sm font-bold text-slate-700">URL Video</label>
              <input v-model="form.video_url" type="url" placeholder="https://..." :class="formInputClass(errors.video_url)" />
              <p v-if="errors.video_url" class="mt-1 text-xs font-semibold text-[#cf3d3d]">{{ errors.video_url[0] }}</p>
            </div>
            <div>
              <label class="mb-1.5 block text-sm font-bold text-slate-700">Urutan</label>
              <input v-model.number="form.sort_order" type="number" min="0" :class="formInputClass(errors.sort_order)" />
              <p v-if="errors.sort_order" class="mt-1 text-xs font-semibold text-[#cf3d3d]">{{ errors.sort_order[0] }}</p>
            </div>
          </div>

          <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50/60 px-4 py-3">
            <input v-model="form.is_active" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-[#cf3d3d] focus:ring-[#cf3d3d]/30" />
            <span class="text-sm font-semibold text-slate-700">Aktifkan lesson ini</span>
          </label>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { teacherApi } from '@/services/teacherApi'
import { useTeacherToast } from '@/composables/useTeacherToast'
import TeacherPageHeader from '@/components/teacher/ui/TeacherPageHeader.vue'
import TeacherLoading from '@/components/teacher/ui/TeacherLoading.vue'
import Button from '@/components/ui/Button.vue'
import { formInputClass } from '@/lib/form'

const route = useRoute()
const router = useRouter()
const { success, error } = useTeacherToast()

const courseId = route.params.courseId
const editId = route.params.id
const isEdit = computed(() => !!editId)

const loading = ref(true)
const submitting = ref(false)
const courseTitle = ref('')
const errors = ref({})

const form = reactive({
  title: '',
  content: '',
  video_url: '',
  sort_order: 0,
  is_active: true,
})


const goBack = () => router.push({ name: 'TeacherLessons', params: { courseId } })

const buildPayload = () => ({
  title: form.title,
  content: form.content || null,
  video_url: form.video_url || null,
  sort_order: Number(form.sort_order) || 0,
  is_active: !!form.is_active,
})

const submit = async () => {
  submitting.value = true
  errors.value = {}
  try {
    if (isEdit.value) {
      await teacherApi.lessons.update(editId, buildPayload())
      success('Lesson berhasil diperbarui.')
    } else {
      await teacherApi.lessons.create({ course_id: Number(courseId), ...buildPayload() })
      success('Lesson berhasil dibuat.')
    }
    goBack()
  } catch (e) {
    if (e.errors) errors.value = e.errors
    error(e.message || 'Gagal menyimpan lesson.')
  } finally {
    submitting.value = false
  }
}

onMounted(async () => {
  try {
    const course = await teacherApi.courses.get(courseId)
    courseTitle.value = course.data.title
  } catch { courseTitle.value = 'Course' }

  if (isEdit.value) {
    try {
      const res = await teacherApi.lessons.get(editId)
      const d = res.data
      form.title = d.title || ''
      form.content = d.content || ''
      form.video_url = d.video_url || ''
      form.sort_order = d.sort_order ?? 0
      form.is_active = d.is_active ?? true
    } catch (e) {
      error('Gagal memuat data lesson.')
    }
  }
  loading.value = false
})
</script>

