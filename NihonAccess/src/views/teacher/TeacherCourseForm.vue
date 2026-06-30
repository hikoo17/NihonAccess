<template>
  <div class="animate-fadeIn">
    <TeacherPageHeader
      eyebrow="Course & Lesson"
      :title="isEdit ? 'Edit Course' : 'Tambah Course'"
      subtitle="Buat course baru beserta thumbnail untuk dikelola."
      :back-to="{ name: 'TeacherCourses' }"
    >
      <template #actions>
        <Button variant="secondary" size="sm" @click="$router.push({ name: 'TeacherCourses' })">Batal</Button>
        <Button size="sm" :disabled="submitting" @click="submit">
          <span v-if="submitting">Menyimpan...</span>
          <span v-else>{{ isEdit ? 'Simpan Perubahan' : 'Simpan Course' }}</span>
        </Button>
      </template>
    </TeacherPageHeader>

    <div class="mx-auto max-w-3xl">
      <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm sm:p-8">
        <div v-if="loading" class="py-12"><TeacherLoading label="Memuat data course..." /></div>

        <form v-else class="space-y-5" @submit.prevent="submit">
          <div>
            <label class="mb-1.5 block text-sm font-bold text-slate-700">Judul Course <span class="text-[#cf3d3d]">*</span></label>
            <input v-model="form.title" type="text" placeholder="cth. JLPT N5 Fundamental" :class="inputClass(errors.title)" />
            <p v-if="errors.title" class="mt-1 text-xs font-semibold text-[#cf3d3d]">{{ errors.title[0] }}</p>
          </div>

          <div>
            <label class="mb-1.5 block text-sm font-bold text-slate-700">Deskripsi</label>
            <textarea v-model="form.description" rows="4" placeholder="Deskripsi singkat course..." :class="inputClass(errors.description)"></textarea>
            <p v-if="errors.description" class="mt-1 text-xs font-semibold text-[#cf3d3d]">{{ errors.description[0] }}</p>
          </div>

          <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
              <label class="mb-1.5 block text-sm font-bold text-slate-700">Level</label>
              <select v-model="form.level" :class="inputClass(errors.level)">
                <option value="">Tanpa level</option>
                <option value="Pemula">Pemula</option>
                <option value="N5">N5</option>
                <option value="N4">N4</option>
                <option value="N3">N3</option>
                <option value="N2">N2</option>
                <option value="N1">N1</option>
              </select>
              <p v-if="errors.level" class="mt-1 text-xs font-semibold text-[#cf3d3d]">{{ errors.level[0] }}</p>
            </div>
            <div class="flex items-end">
              <label class="flex w-full cursor-pointer items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50/60 px-4 py-3">
                <input v-model="form.is_active" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-[#cf3d3d] focus:ring-[#cf3d3d]/30" />
                <span class="text-sm font-semibold text-slate-700">Aktifkan course</span>
              </label>
            </div>
          </div>

          <div>
            <label class="mb-1.5 block text-sm font-bold text-slate-700">Thumbnail</label>
            <div class="flex items-center gap-5">
              <div class="flex h-24 w-40 shrink-0 items-center justify-center overflow-hidden rounded-2xl border border-dashed border-slate-200 bg-slate-50">
                <img v-if="previewUrl" :src="previewUrl" alt="Preview thumbnail" class="h-full w-full object-cover" />
                <div v-else class="text-center">
                  <svg class="mx-auto h-7 w-7 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
                  <p class="mt-1 text-[10px] font-medium text-slate-400">No image</p>
                </div>
              </div>
              <div class="min-w-0 flex-1">
                <input ref="fileInput" type="file" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden" @change="onFileChange" />
                <Button type="button" variant="outline" size="sm" @click="$refs.fileInput.click()">
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" /></svg>
                  {{ thumbnailFile ? 'Ganti Gambar' : 'Upload Thumbnail' }}
                </Button>
                <p class="mt-1.5 text-xs font-medium text-slate-400">Format: JPG, PNG, WEBP · Maks 2MB</p>
                <p v-if="fileError" class="mt-1 text-xs font-semibold text-[#cf3d3d]">{{ fileError }}</p>
                <p v-if="errors.thumbnail" class="mt-1 text-xs font-semibold text-[#cf3d3d]">{{ errors.thumbnail[0] }}</p>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, onUnmounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { teacherApi } from '@/services/teacherApi'
import { useTeacherToast } from '@/composables/useTeacherToast'
import TeacherPageHeader from '@/components/teacher/ui/TeacherPageHeader.vue'
import TeacherLoading from '@/components/teacher/ui/TeacherLoading.vue'
import Button from '@/components/ui/Button.vue'

const route = useRoute()
const router = useRouter()
const { success, error } = useTeacherToast()

const editId = route.params.id
const isEdit = computed(() => !!editId)

const loading = ref(true)
const submitting = ref(false)
const errors = ref({})
const thumbnailFile = ref(null)
const previewUrl = ref('')
const existingThumbnail = ref(null)
const fileError = ref('')
let createdObjectUrl = ''

const form = reactive({
  title: '',
  description: '',
  level: '',
  is_active: true,
})

const inputClass = (err) => [
  'w-full rounded-2xl border bg-white px-4 py-3 text-sm font-medium text-slate-800 placeholder:text-slate-400 transition focus:outline-none focus:ring-2',
  err ? 'border-[#cf3d3d] focus:border-[#cf3d3d] focus:ring-[#cf3d3d]/20' : 'border-slate-200 focus:border-[#cf3d3d] focus:ring-[#cf3d3d]/20',
]

const MAX_SIZE = 2 * 1024 * 1024
const ALLOWED = ['image/jpeg', 'image/png', 'image/webp']

const onFileChange = (e) => {
  fileError.value = ''
  const file = e.target.files?.[0]
  if (!file) return

  if (!ALLOWED.includes(file.type)) {
    fileError.value = 'Format tidak didukung. Gunakan JPG, PNG, atau WEBP.'
    e.target.value = ''
    return
  }
  if (file.size > MAX_SIZE) {
    fileError.value = 'Ukuran file melebihi 2MB.'
    e.target.value = ''
    return
  }

  thumbnailFile.value = file
  if (createdObjectUrl) URL.revokeObjectURL(createdObjectUrl)
  createdObjectUrl = URL.createObjectURL(file)
  previewUrl.value = createdObjectUrl
}

const buildForm = () => {
  const fd = new FormData()
  if (isEdit.value) fd.append('_method', 'PUT')
  fd.append('title', form.title)
  fd.append('description', form.description || '')
  if (form.level) fd.append('level', form.level)
  fd.append('is_active', form.is_active ? '1' : '0')
  if (thumbnailFile.value) fd.append('thumbnail', thumbnailFile.value)
  return fd
}

const submit = async () => {
  submitting.value = true
  errors.value = {}
  try {
    if (isEdit.value) {
      await teacherApi.courses.update(editId, buildForm())
      success('Course berhasil diperbarui.')
    } else {
      await teacherApi.courses.create(buildForm())
      success('Course berhasil dibuat.')
    }
    router.push({ name: 'TeacherCourses' })
  } catch (e) {
    if (e.errors) errors.value = e.errors
    error(e.message || 'Gagal menyimpan course.')
  } finally {
    submitting.value = false
  }
}

onMounted(async () => {
  if (isEdit.value) {
    try {
      const res = await teacherApi.courses.get(editId)
      const d = res.data
      form.title = d.title || ''
      form.description = d.description || ''
      form.level = d.level || ''
      form.is_active = d.is_active ?? true
      existingThumbnail.value = d.thumbnail_url
      previewUrl.value = d.thumbnail_url || ''
    } catch (e) {
      error('Gagal memuat data course.')
    }
  }
  loading.value = false
})

onUnmounted(() => {
  if (createdObjectUrl) URL.revokeObjectURL(createdObjectUrl)
})
</script>

<style scoped>
.animate-fadeIn { animation: fadeIn 0.35s ease-out forwards; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: translateY(0); } }
</style>
