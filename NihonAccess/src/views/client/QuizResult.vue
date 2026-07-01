<template>
  <div class="mx-auto max-w-4xl space-y-6">
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
      <p class="text-sm font-bold text-[#cf3d3d]">Gagal memuat hasil quiz</p>
      <p class="mt-1 text-xs text-slate-500">{{ error }}</p>
      <div class="mt-4 flex justify-center gap-2">
        <Button variant="outline" size="sm" @click="load">Coba Lagi</Button>
        <Button variant="outline" size="sm" @click="$router.push({ name: 'client-quiz-list' })">
          Kembali ke Daftar Quiz
        </Button>
      </div>
    </Card>

    <!-- Result -->
    <template v-else>
      <!-- Top bar -->
      <div class="flex items-center justify-between">
        <Breadcrumb
          :items="[
            { label: 'Kursus Saya', to: '/client/my-courses' },
            { label: quiz?.course?.title || 'Kursus', to: quiz?.course_id ? `/client/my-courses/${quiz.course_id}/learn` : '/client/my-courses' },
            { label: 'Hasil Quiz' },
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

      <!-- Result summary -->
      <Card class="overflow-hidden">
        <div class="p-6 text-center">
          <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full"
            :class="passed ? 'bg-emerald-100' : 'bg-[#cf3d3d]/10'">
            <svg v-if="passed" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
              fill="currentColor" class="h-10 w-10 text-emerald-600">
              <path fill-rule="evenodd"
                d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                clip-rule="evenodd" />
            </svg>
            <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
              stroke-width="2" stroke="currentColor" class="h-10 w-10 text-[#cf3d3d]">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z" />
            </svg>
          </div>

          <div class="mb-2 flex justify-center">
            <Badge :variant="passed ? 'success' : 'danger'" size="sm">
              {{ passed ? 'Lulus' : 'Belum Lulus' }}
            </Badge>
          </div>

          <p class="text-sm font-semibold text-slate-500">Skor Akhir</p>
          <p class="text-5xl font-extrabold tracking-tight"
            :class="passed ? 'text-emerald-600' : 'text-[#cf3d3d]'">
            {{ score }}<span class="text-2xl text-slate-300">/100</span>
          </p>
          <p class="mt-2 text-xs text-slate-400">
            Batas lulus: {{ passingScore }} · Benar {{ correct }} dari {{ total }} soal
          </p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-3 divide-x divide-slate-100 border-t border-slate-100">
          <div class="p-4 text-center">
            <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Benar</p>
            <p class="mt-1 text-xl font-extrabold text-emerald-600">{{ correct }}</p>
          </div>
          <div class="p-4 text-center">
            <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Salah</p>
            <p class="mt-1 text-xl font-extrabold text-[#cf3d3d]">{{ wrong }}</p>
          </div>
          <div class="p-4 text-center">
            <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Kosong</p>
            <p class="mt-1 text-xl font-extrabold text-slate-400">{{ empty }}</p>
          </div>
        </div>
      </Card>

      <!-- Review -->
      <div v-if="review.length">
        <div class="mb-3 flex items-center justify-between">
          <h3 class="text-sm font-extrabold text-slate-800">Pembahasan Jawaban</h3>
          <span class="text-xs font-bold text-slate-400">{{ review.length }} soal</span>
        </div>

        <div class="space-y-3">
          <Card v-for="(q, i) in review" :key="q.id ?? i" class="p-5">
            <div class="mb-3 flex items-start justify-between gap-3">
              <div class="flex items-center gap-2">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100 text-xs font-extrabold text-slate-600">
                  {{ i + 1 }}
                </span>
                <p class="text-sm font-bold text-slate-800">{{ q.question }}</p>
              </div>
              <Badge :variant="q.isCorrect ? 'success' : 'danger'" size="sm">
                {{ q.isCorrect ? 'Benar' : 'Salah' }}
              </Badge>
            </div>

            <div class="space-y-2">
              <div
                v-for="(opt, oi) in q.options"
                :key="oi"
                class="review-opt"
                :class="{
                  'opt-correct': oi === q.correctIndex,
                  'opt-wrong': oi === q.userIndex && oi !== q.correctIndex,
                }"
              >
                <span class="option-letter"
                  :class="oi === q.correctIndex ? 'bg-emerald-500 text-white'
                        : (oi === q.userIndex ? 'bg-[#cf3d3d] text-white' : '')">
                  {{ String.fromCharCode(65 + oi) }}
                </span>
                <span class="flex-1 text-sm font-bold"
                  :class="oi === q.correctIndex ? 'text-emerald-700'
                        : (oi === q.userIndex ? 'text-[#cf3d3d]' : 'text-slate-700')">
                  {{ opt }}
                </span>
                <svg v-if="oi === q.correctIndex" xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5 text-emerald-500">
                  <path fill-rule="evenodd"
                    d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                    clip-rule="evenodd" />
                </svg>
              </div>
            </div>

            <div v-if="q.explanation" class="mt-3 rounded-xl bg-slate-50 px-3 py-2">
              <p class="text-xs text-slate-500">
                <span class="font-extrabold text-slate-600">Pembahasan:</span> {{ q.explanation }}
              </p>
            </div>
          </Card>
        </div>
      </div>

      <!-- Actions -->
      <div class="flex flex-wrap gap-3">
        <Button variant="outline" class="flex-1" @click="retry">
          Ulangi Quiz
        </Button>
        <Button class="flex-1" @click="backToCourse">
          Lanjut Belajar
        </Button>
      </div>
    </template>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import Breadcrumb from '@/components/ui/Breadcrumb.vue'
import Card from '@/components/ui/Card.vue'
import Button from '@/components/ui/Button.vue'
import Badge from '@/components/ui/Badge.vue'
import { clientApi } from '@/services/clientApi'

const props = defineProps({
  // id ini = attempt_id (bukan quiz_id), karena QuizView mengirim attempt.id
  id: { type: [String, Number], default: '' },
})

const router = useRouter()

// ---- State ----
const loading = ref(true)
const error = ref(null)
const attempt = ref(null)
const quiz = computed(() => attempt.value?.quiz ?? null)

// ---- Nilai dasar dari attempt (lebih akurat dari backend) ----
const score = computed(() => attempt.value?.score ?? 0)
const passingScore = computed(() => quiz.value?.passing_score ?? 70)
const passed = computed(() => !!attempt.value?.is_passed)

// ---- Build review dari questions + answers user ----
const review = ref([])

const buildReview = () => {
  const questions = quiz.value?.questions ?? []
  const answers = attempt.value?.answers ?? []

  // urutkan by sort_order
  const sorted = questions.slice().sort((a, b) => (a.sort_order ?? 0) - (b.sort_order ?? 0))

  review.value = sorted.map((q) => {
    const userAnswerObj = answers.find((a) => String(a.question_id) === String(q.id))
    const userAnswer = userAnswerObj?.answer ?? ''

    const options = Array.isArray(q.options) ? q.options : []
    const correctIndex = options.indexOf(q.correct_answer)
    const userIndex = options.indexOf(userAnswer)

    const answered = userAnswer !== '' && userAnswer !== null && userAnswer !== undefined
    const isCorrect = answered && userAnswer === q.correct_answer

    return {
      id: q.id,
      question: q.question,
      options,
      correct_answer: q.correct_answer,
      correctIndex: correctIndex === -1 ? null : correctIndex,
      userAnswer,
      userIndex: userIndex === -1 ? null : userIndex,
      isCorrect,
      answered,
      explanation: q.explanation || '',
    }
  })
}

// ---- Statistik ----
const total = computed(() => review.value.length)
const correct = computed(() => review.value.filter((r) => r.isCorrect).length)
const wrong = computed(() => review.value.filter((r) => r.answered && !r.isCorrect).length)
const empty = computed(() => total.value - correct.value - wrong.value)

// ---- Load attempt dari backend ----
const load = async () => {
  loading.value = true
  error.value = null
  try {
    const res = await clientApi.quizAttempts.get(props.id)
    attempt.value = res.data
    buildReview()
  } catch (e) {
    error.value = e.message || 'Hasil quiz tidak dapat dimuat.'
  } finally {
    loading.value = false
  }
}

onMounted(load)

// ---- Actions ----
const retry = () => {
  if (quiz.value?.id) {
    router.push({ name: 'client-quiz', params: { id: quiz.value.id } })
  } else {
    router.push({ name: 'client-quiz-list' })
  }
}
const backToCourse = () => {
  if (quiz.value?.course_id) {
    router.push(`/client/my-courses/${quiz.value.course_id}/learn`)
  } else {
    router.push({ name: 'client-my-courses' })
  }
}
</script>

<style scoped>
.review-opt {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.625rem 0.875rem;
  border-radius: 0.75rem;
  border: 1.5px solid #e2e8f0;
  background: #ffffff;
}
.opt-correct {
  border-color: #6ee7b7;
  background: #ecfdf5;
}
.opt-wrong {
  border-color: #fca5a5;
  background: #fef2f2;
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
</style>