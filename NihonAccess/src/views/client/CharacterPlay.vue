<template>
  <div class="mx-auto max-w-3xl space-y-6">
    <!-- Top bar -->
    <div class="flex items-center justify-between">
      <Breadcrumb
        :items="[
          { label: 'Kursus Saya', to: '/client/my-courses' },
          { label: 'Tebak Huruf' },
        ]"
      />
      <RouterLink to="/client/my-courses" class="text-xs font-bold text-slate-400 hover:text-[#cf3d3d]">
        ← Kembali
      </RouterLink>
    </div>

    <!-- Loading -->
    <Card v-if="loading" class="p-12 text-center">
      <p class="text-sm font-medium text-slate-400">Memuat soal tebak huruf...</p>
    </Card>

    <!-- Empty -->
    <Card v-else-if="exercises.length === 0" class="p-12 text-center">
      <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-slate-100">
        <svg class="h-8 w-8 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
      </div>
      <h3 class="text-sm font-extrabold text-slate-800">Belum ada soal</h3>
      <p class="mx-auto mt-1 max-w-xs text-xs text-slate-400">Belum ada latihan tebak huruf yang aktif untuk kamu kerjakan.</p>
    </Card>

    <!-- Result -->
    <Card v-else-if="finished" class="overflow-hidden">
      <div class="p-6 text-center">
        <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full"
          :class="scorePercent >= 70 ? 'bg-emerald-100' : 'bg-[#cf3d3d]/10'">
          <svg v-if="scorePercent >= 70" class="h-10 w-10 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
          <svg v-else class="h-10 w-10 text-[#cf3d3d]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z" /></svg>
        </div>
        <Badge :variant="scorePercent >= 70 ? 'success' : 'danger'" size="sm">
          {{ scorePercent >= 70 ? 'Lulus' : 'Belum Lulus' }}
        </Badge>
        <p class="mt-3 text-sm font-semibold text-slate-500">Skor Akhir</p>
        <p class="text-5xl font-extrabold tracking-tight"
          :class="scorePercent >= 70 ? 'text-emerald-600' : 'text-[#cf3d3d]'">
          {{ scorePercent }}<span class="text-2xl text-slate-300">%</span>
        </p>
        <p class="mt-2 text-xs text-slate-400">Benar {{ correctCount }} dari {{ exercises.length }} soal</p>
      </div>
      <div class="grid grid-cols-2 divide-x divide-slate-100 border-t border-slate-100">
        <div class="p-4 text-center">
          <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Benar</p>
          <p class="mt-1 text-xl font-extrabold text-emerald-600">{{ correctCount }}</p>
        </div>
        <div class="p-4 text-center">
          <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Salah</p>
          <p class="mt-1 text-xl font-extrabold text-[#cf3d3d]">{{ exercises.length - correctCount }}</p>
        </div>
      </div>
      <div class="border-t border-slate-100 p-4">
        <Button class="w-full" @click="restart">Ulangi Latihan</Button>
      </div>
    </Card>

    <!-- Soal -->
    <template v-else>
      <!-- Progress -->
      <Card class="p-4">
        <div class="flex items-center justify-between">
          <Badge variant="info" size="sm">Soal {{ currentIndex + 1 }} dari {{ exercises.length }}</Badge>
          <div class="flex items-center gap-3 text-xs font-semibold text-slate-500">
            <span class="inline-flex items-center gap-1.5">
              <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>Benar {{ correctCount }}
            </span>
            <span class="inline-flex items-center gap-1.5">
              <span class="h-2.5 w-2.5 rounded-full bg-[#cf3d3d]"></span>Salah {{ wrongCount }}
            </span>
          </div>
        </div>
        <div class="mt-3 h-2 w-full overflow-hidden rounded-full bg-slate-100">
          <div class="h-full rounded-full bg-[#cf3d3d] transition-all duration-300" :style="{ width: `${progressPercent}%` }"></div>
        </div>
      </Card>

      <!-- Soal card -->
      <Card class="p-8 text-center">
        <p class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">{{ currentTypeLabel }}</p>
        <div class="my-6 flex items-center justify-center">
          <span class="font-jp text-8xl font-bold text-slate-900">{{ current.character }}</span>
        </div>
        <div v-if="current.hint" class="mx-auto mb-2 inline-flex items-center gap-2 rounded-xl bg-amber-50 px-3 py-1.5">
          <svg class="h-4 w-4 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18" /></svg>
          <span class="text-xs font-semibold text-amber-700">Hint: {{ current.hint }}</span>
        </div>

        <!-- Options -->
        <div class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-2">
          <button
            v-for="(opt, i) in currentShuffled"
            :key="i"
            :disabled="answered"
            @click="choose(opt)"
            class="option-btn"
            :class="optionClass(opt)"
          >
            <span class="option-letter">{{ String.fromCharCode(65 + i) }}</span>
            <span class="flex-1 text-left text-sm font-bold">{{ opt }}</span>
            <svg v-if="answered && opt === current.correct_answer" class="h-5 w-5 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
            <svg v-else-if="answered && opt === selectedAnswer && !isCorrect" class="h-5 w-5 text-[#cf3d3d]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
          </button>
        </div>
      </Card>

      <div class="flex justify-end">
        <Button v-if="answered" size="sm" @click="next">
          {{ currentIndex < exercises.length - 1 ? 'Soal Berikutnya →' : 'Selesai' }}
        </Button>
      </div>
    </template>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { clientApi } from '@/services/clientApi'
import Breadcrumb from '@/components/ui/Breadcrumb.vue'
import Card from '@/components/ui/Card.vue'
import Button from '@/components/ui/Button.vue'
import Badge from '@/components/ui/Badge.vue'

const props = defineProps({
  courseId: { type: [String, Number], default: '' },
})

const loading = ref(true)
const exercises = ref([])
const currentIndex = ref(0)
const results = ref([])
const selectedAnswer = ref(null)
const answered = ref(false)
const isCorrect = ref(false)
const finished = ref(false)

const current = computed(() => exercises.value[currentIndex.value] || {})
const currentShuffled = computed(() => current.value._shuffled || [])

const currentTypeLabel = computed(() => {
  const map = { hiragana: 'Hiragana', katakana: 'Katakana', kanji: 'Kanji' }
  return map[current.value.character_type] || 'Karakter'
})

const correctCount = computed(() => results.value.filter((r) => r.is_correct).length)
const wrongCount = computed(() => results.value.filter((r) => !r.is_correct).length)
const progressPercent = computed(() => {
  const total = exercises.value.length
  return total === 0 ? 0 : Math.round(((currentIndex.value) / total) * 100)
})
const scorePercent = computed(() => {
  const total = exercises.value.length
  return total === 0 ? 0 : Math.round((correctCount.value / total) * 100)
})

const shuffle = (arr) => {
  const a = [...arr]
  for (let i = a.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1))
    ;[a[i], a[j]] = [a[j], a[i]]
  }
  return a
}

const prepareExercises = (items) => {
  exercises.value = items.map((ex) => {
    const opts = Array.isArray(ex.options) && ex.options.length ? ex.options : [ex.answer]
    return {
      ...ex,
      _shuffled: shuffle(opts),
    }
  })
}

const optionClass = (opt) => {
  if (!answered.value) return ''
  if (opt === current.value.correct_answer) return 'opt-correct'
  if (opt === selectedAnswer.value) return 'opt-wrong'
  return 'opt-muted'
}

const choose = async (opt) => {
  if (answered.value) return
  selectedAnswer.value = opt
  answered.value = true

  try {
    const res = await clientApi.characters.submit({
      character_exercise_id: current.value.id,
      answer: opt,
    })
    isCorrect.value = res.data.is_correct
    results.value.push({
      character_exercise_id: current.value.id,
      is_correct: res.data.is_correct,
    })
  } catch {
    // fallback: bandingkan lokal kalau submit gagal
    const correct = opt === current.value.correct_answer
    isCorrect.value = correct
    results.value.push({ character_exercise_id: current.value.id, is_correct: correct })
  }
}

const next = () => {
  if (currentIndex.value < exercises.value.length - 1) {
    currentIndex.value++
    selectedAnswer.value = null
    answered.value = false
    isCorrect.value = false
  } else {
    finished.value = true
  }
}

const restart = () => {
  currentIndex.value = 0
  results.value = []
  selectedAnswer.value = null
  answered.value = false
  isCorrect.value = false
  finished.value = false
  prepareExercises(exercises.value)
}

const fetchExercises = async () => {
  loading.value = true
  try {
    const res = await clientApi.characters.list({
      per_page: 100,
      ...(props.courseId ? { course_id: props.courseId } : {}),
    })
    prepareExercises(res.data)
  } catch {
    exercises.value = []
  } finally {
    loading.value = false
  }
}

onMounted(fetchExercises)
</script>

<style scoped>
.option-btn {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  width: 100%;
  padding: 0.875rem 1rem;
  border-radius: 1rem;
  border: 1.5px solid #e2e8f0;
  background: #ffffff;
  transition: all 0.2s ease;
  text-align: left;
}
.option-btn:not(:disabled):hover {
  border-color: #f1c2c2;
  background: #fff8f8;
}
.option-btn:disabled { cursor: default; }
.option-letter {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 1.75rem;
  width: 1.75rem;
  border-radius: 0.625rem;
  background: #f1f5f9;
  color: #64748b;
  font-size: 0.75rem;
  font-weight: 800;
  flex-shrink: 0;
}
.opt-correct { border-color: #6ee7b7; background: #ecfdf5; }
.opt-correct .option-letter { background: #10b981; color: #fff; }
.opt-wrong { border-color: #fca5a5; background: #fef2f2; }
.opt-wrong .option-letter { background: #cf3d3d; color: #fff; }
.opt-muted { opacity: 0.5; }
.font-jp { font-family: 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif; }
</style>
