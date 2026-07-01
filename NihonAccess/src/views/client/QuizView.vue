<template>
  <div class="mx-auto max-w-7xl space-y-6">
    <!-- Loading -->
    <Card v-if="loading" class="p-6">
      <div class="animate-pulse space-y-3">
        <div class="h-5 w-1/3 rounded bg-slate-200"></div>
        <div class="h-3 w-2/3 rounded bg-slate-100"></div>
        <div class="h-24 w-full rounded bg-slate-100"></div>
      </div>
    </Card>

    <!-- Error -->
    <Card v-else-if="error" class="p-6 text-center">
      <p class="text-sm font-bold text-[#cf3d3d]">Gagal memuat quiz</p>
      <p class="mt-1 text-xs text-slate-500">{{ error }}</p>
      <div class="mt-4 flex justify-center gap-2">
        <Button variant="outline" size="sm" @click="load">Coba Lagi</Button>
        <Button variant="outline" size="sm" @click="$router.push({ name: 'client-quiz-list' })">
          Kembali ke Daftar Quiz
        </Button>
      </div>
    </Card>

    <!-- Empty: quiz tanpa soal -->
    <Card v-else-if="questions.length === 0" class="p-10 text-center">
      <div class="mb-2 text-4xl">📝</div>
      <p class="text-sm font-bold text-slate-700">Quiz belum punya soal</p>
      <p class="mt-1 text-xs text-slate-400">Silakan hubungi pengajar.</p>
      <Button class="mt-4" size="sm" variant="outline" @click="$router.push({ name: 'client-quiz-list' })">
        ← Kembali
      </Button>
    </Card>

    <!-- Quiz body -->
    <template v-else>
      <!-- Top bar -->
      <div class="flex items-center justify-between">
        <Breadcrumb
          :items="[
            { label: 'Kursus Saya', to: '/client/my-courses' },
            { label: quiz?.course?.title || 'Kursus', to: quiz?.course_id ? `/client/my-courses/${quiz.course_id}/learn` : '/client/my-courses' },
            { label: quiz?.title || 'Quiz' },
          ]"
        />
        <RouterLink
          v-if="quiz?.course_id"
          :to="`/client/my-courses/${quiz.course_id}/learn`"
          class="text-xs font-bold text-slate-400 hover:text-[#cf3d3d]"
        >
          ← Kembali ke Kursus
        </RouterLink>
      </div>

      <!-- Quiz header -->
      <Card class="p-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
          <div>
            <div class="mb-2 flex flex-wrap items-center gap-2">
              <Badge variant="warning" size="sm">Quiz</Badge>
              <Badge variant="neutral" size="sm">⏱ {{ totalMinutes }} menit</Badge>
              <Badge variant="info" size="sm">{{ questions.length }} Soal</Badge>
            </div>
            <h1 class="text-xl font-extrabold tracking-tight text-slate-800">
              {{ quiz?.title || 'Quiz' }}
            </h1>
            <p class="mt-1 text-sm text-slate-500">
              Jawab semua pertanyaan sebelum waktu habis. Pilih satu jawaban paling tepat.
            </p>
          </div>

          <!-- Timer -->
          <div class="flex items-center gap-3 rounded-2xl bg-slate-50 px-4 py-3">
            <svg
              xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
              stroke-width="2" stroke="currentColor"
              class="h-6 w-6 text-[#cf3d3d]"
            >
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
              <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Sisa Waktu</p>
              <p class="font-mono text-lg font-extrabold" :class="timeLow ? 'text-[#cf3d3d]' : 'text-slate-800'">
                {{ formattedTime }}
              </p>
            </div>
          </div>
        </div>

        <!-- Progress -->
        <div class="mt-5">
          <ProgressBar :value="answeredCount" :max="questions.length">
            Soal terjawab
          </ProgressBar>
        </div>
      </Card>

      <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Left: question -->
        <div class="space-y-6 lg:col-span-2">
          <Card class="p-6">
            <div class="mb-4 flex items-center justify-between">
              <Badge variant="info" size="sm">
                Soal {{ currentIndex + 1 }} dari {{ questions.length }}
              </Badge>
            </div>

            <h2 class="text-base font-extrabold leading-relaxed text-slate-800">
              {{ currentQuestion.question }}
            </h2>

            <div v-if="currentQuestion.explanation" class="mt-3 hidden rounded-xl bg-amber-50 px-3 py-2">
              <!-- explanation disembunyikan saat mengerjakan, baru tampil di hasil -->
            </div>

            <!-- Options -->
            <div class="mt-5 space-y-3">
              <button
                v-for="(opt, i) in currentQuestion.options"
                :key="i"
                @click="selectAnswer(opt)"
                class="option-item"
                :class="{ selected: answers[currentIndex] === opt }"
              >
                <span class="option-letter">{{ String.fromCharCode(65 + i) }}</span>
                <span class="flex-1 text-left text-sm font-bold text-slate-700">{{ opt }}</span>
                <svg
                  v-if="answers[currentIndex] === opt"
                  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                  class="h-5 w-5 text-[#cf3d3d]"
                >
                  <path fill-rule="evenodd"
                    d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                    clip-rule="evenodd" />
                </svg>
              </button>
            </div>
          </Card>

          <!-- Navigation -->
          <div class="flex items-center justify-between">
            <Button variant="outline" size="sm" :disabled="currentIndex === 0" @click="prev">
              ← Sebelumnya
            </Button>
            <Button v-if="currentIndex < questions.length - 1" size="sm" @click="next">
              Berikutnya →
            </Button>
            <Button v-else size="sm" :disabled="submitting" @click="showConfirm = true">
              {{ submitting ? 'Mengirim...' : 'Submit Quiz' }}
            </Button>
          </div>
        </div>

        <!-- Right: navigator -->
        <div class="lg:col-span-1">
          <div class="sticky top-24 space-y-4">
            <Card class="p-4">
              <p class="mb-3 text-[10px] font-extrabold uppercase tracking-wider text-slate-400">
                Navigasi Soal
              </p>
              <div class="grid grid-cols-5 gap-2">
                <button
                  v-for="(q, i) in questions"
                  :key="i"
                  @click="goTo(i)"
                  class="nav-btn"
                  :class="{
                    active: i === currentIndex,
                    answered: answers[i] !== null,
                  }"
                >
                  {{ i + 1 }}
                </button>
              </div>

              <!-- Legend -->
              <div class="mt-4 space-y-2 border-t border-slate-100 pt-3">
                <div class="flex items-center gap-2">
                  <span class="h-3 w-3 rounded-md bg-[#cf3d3d]"></span>
                  <span class="text-[11px] font-semibold text-slate-500">Soal aktif</span>
                </div>
                <div class="flex items-center gap-2">
                  <span class="h-3 w-3 rounded-md bg-emerald-100 ring-1 ring-emerald-300"></span>
                  <span class="text-[11px] font-semibold text-slate-500">Sudah dijawab</span>
                </div>
                <div class="flex items-center gap-2">
                  <span class="h-3 w-3 rounded-md bg-slate-100 ring-1 ring-slate-200"></span>
                  <span class="text-[11px] font-semibold text-slate-500">Belum dijawab</span>
                </div>
              </div>

              <Button class="mt-4 w-full" :disabled="submitting" @click="showConfirm = true">
                {{ submitting ? 'Mengirim...' : 'Submit Quiz' }}
              </Button>
            </Card>
          </div>
        </div>
      </div>

      <!-- Confirm modal -->
      <transition name="fade">
        <div v-if="showConfirm" class="modal-backdrop">
          <Card class="w-full max-w-md p-6">
            <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-100">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="2" stroke="currentColor" class="h-6 w-6 text-amber-600">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
              </svg>
            </div>
            <h3 class="text-lg font-extrabold text-slate-800">Kumpulkan Quiz?</h3>
            <p class="mt-1 text-sm text-slate-500">
              Kamu sudah menjawab <span class="font-bold text-slate-700">{{ answeredCount }}</span>
              dari <span class="font-bold text-slate-700">{{ questions.length }}</span> soal.
              Soal yang kosong dianggap salah.
            </p>
            <p v-if="submitError" class="mt-2 text-xs font-semibold text-[#cf3d3d]">{{ submitError }}</p>
            <div class="mt-5 flex gap-2">
              <Button variant="outline" class="flex-1" :disabled="submitting" @click="showConfirm = false">Batal</Button>
              <Button class="flex-1" :disabled="submitting" @click="submit">
                {{ submitting ? 'Mengirim...' : 'Ya, Kumpulkan' }}
              </Button>
            </div>
          </Card>
        </div>
      </transition>
    </template>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import Breadcrumb from '@/components/ui/Breadcrumb.vue'
import Card from '@/components/ui/Card.vue'
import Button from '@/components/ui/Button.vue'
import Badge from '@/components/ui/Badge.vue'
import ProgressBar from '@/components/ui/ProgresBar.vue'
import { clientApi } from '@/services/clientApi'

const props = defineProps({
  id: { type: [String, Number], default: '' },
})

const router = useRouter()

// ---- State ----
const loading = ref(true)
const error = ref(null)
const quiz = ref(null)
const questions = ref([])
const currentIndex = ref(0)
// answers[i] = string opsi yang dipilih (match format correct_answer di DB), atau null
const answers = ref([])
const showConfirm = ref(false)
const submitting = ref(false)
const submitError = ref(null)

// Timer default (model Quiz belum punya kolom durasi)
const totalMinutes = 15
const totalSeconds = totalMinutes * 60
const remaining = ref(totalSeconds)
let timer = null

// ---- Computed ----
const currentQuestion = computed(() => questions.value[currentIndex.value] || null)
const answeredCount = computed(() => answers.value.filter((a) => a !== null && a !== undefined).length)

const formattedTime = computed(() => {
  const m = Math.floor(remaining.value / 60)
  const s = remaining.value % 60
  return `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`
})
const timeLow = computed(() => remaining.value <= 60)

// ---- Load quiz dari backend ----
const load = async () => {
  loading.value = true
  error.value = null
  try {
    const res = await clientApi.quizzes.start(props.id)
    const data = res.data
    quiz.value = data
    // urutkan by sort_order kalau ada
    questions.value = (data.questions || []).slice().sort((a, b) => (a.sort_order ?? 0) - (b.sort_order ?? 0))
    answers.value = Array(questions.value.length).fill(null)
    startTimer()
  } catch (e) {
    error.value = e.message || 'Quiz tidak dapat dimuat.'
  } finally {
    loading.value = false
  }
}

// ---- Timer ----
const startTimer = () => {
  clearInterval(timer)
  remaining.value = totalSeconds
  timer = setInterval(tick, 1000)
}
const tick = () => {
  if (remaining.value > 0) {
    remaining.value--
  } else {
    clearInterval(timer)
    submit()
  }
}
onMounted(load)
onUnmounted(() => clearInterval(timer))

// ---- Actions ----
const selectAnswer = (opt) => { answers.value[currentIndex.value] = opt }
const prev = () => { if (currentIndex.value > 0) currentIndex.value-- }
const next = () => { if (currentIndex.value < questions.value.length - 1) currentIndex.value++ }
const goTo = (i) => { currentIndex.value = i }

// ---- Submit ke backend ----
const submit = async () => {
  if (submitting.value) return
  showConfirm.value = true
  submitting.value = true
  submitError.value = null
  clearInterval(timer)

  try {
    const payload = {
      answers: questions.value.map((q, i) => ({
        question_id: q.id,
        answer: answers.value[i] ?? '', // string opsi, kosong => dianggap salah backend
      })),
    }

    const res = await clientApi.quizzes.submit(props.id, payload)
    const attempt = res.data

    // Navigasi ke halaman hasil pakai attempt_id
    router.push({
      name: 'client-quiz-result',
      params: { id: attempt.id },
    })
  } catch (e) {
    submitError.value = e.message || 'Gagal mengirim jawaban. Coba lagi.'
    // kalau gagal, nyalakan timer lagi supaya user bisa lanjut
    if (remaining.value > 0) timer = setInterval(tick, 1000)
  } finally {
    submitting.value = false
  }
}
</script>

<style scoped>
.option-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  width: 100%;
  padding: 0.875rem 1rem;
  border-radius: 1rem;
  border: 1.5px solid #e2e8f0;
  background: #ffffff;
  transition: all 0.2s ease;
}
.option-item:hover {
  border-color: #f1c2c2;
  background: #fff8f8;
}
.option-item.selected {
  border-color: #cf3d3d;
  background: #fef2f2;
  box-shadow: 0 4px 12px rgba(207, 61, 61, 0.12);
}
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
.option-item.selected .option-letter {
  background: #cf3d3d;
  color: #ffffff;
}
.nav-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 2.25rem;
  border-radius: 0.625rem;
  background: #f1f5f9;
  color: #94a3b8;
  font-size: 0.75rem;
  font-weight: 800;
  border: 1.5px solid transparent;
  transition: all 0.2s ease;
}
.nav-btn:hover { background: #e2e8f0; }
.nav-btn.answered {
  background: #d1fae5;
  color: #059669;
  box-shadow: inset 0 0 0 1px #6ee7b7;
}
.nav-btn.active {
  background: #cf3d3d;
  color: #ffffff;
  box-shadow: 0 4px 12px rgba(207, 61, 61, 0.25);
}
.modal-backdrop {
  position: fixed;
  inset: 0;
  z-index: 50;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1rem;
  background: rgba(15, 23, 42, 0.5);
  backdrop-filter: blur(4px);
}
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>