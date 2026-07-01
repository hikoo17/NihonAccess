<template>
  <div class="mx-auto max-w-6xl space-y-6">
    <div>
      <Breadcrumb :items="[{ label: 'Beranda', to: '/' }, { label: 'Quiz' }]" />
      <h1 class="mt-3 text-2xl font-extrabold tracking-tight text-slate-800 sm:text-3xl">
        Quiz
      </h1>
      <p class="mt-1 text-sm text-slate-500">
        Uji pemahamanmu dengan mengerjakan quiz dari setiap kursus.
      </p>
    </div>

    <!-- Error -->
    <div
      v-if="error"
      class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700"
    >
      Gagal memuat quiz: {{ error }}
    </div>

    <!-- Loading -->
    <div v-else-if="loading" class="grid grid-cols-1 gap-4 md:grid-cols-2">
      <Card v-for="i in 4" :key="i" class="p-5">
        <div class="animate-pulse space-y-3">
          <div class="h-4 w-2/3 rounded bg-slate-200"></div>
          <div class="h-3 w-1/3 rounded bg-slate-100"></div>
          <div class="h-3 w-full rounded bg-slate-100"></div>
          <div class="h-8 w-28 rounded-lg bg-slate-100"></div>
        </div>
      </Card>
    </div>

    <!-- Empty -->
    <Card v-else-if="quizzes.length === 0" class="p-10 text-center">
      <div class="mb-2 text-4xl">📝</div>
      <p class="text-sm font-bold text-slate-700">Belum ada quiz tersedia</p>
      <p class="mt-1 text-xs text-slate-400">
        Quiz akan muncul otomatis setelah kamu terdaftar di kursus yang memiliki quiz.
      </p>
      <RouterLink
        to="/client/my-courses"
        class="mt-3 inline-block text-xs font-bold text-[#cf3d3d] hover:underline"
      >
        Lihat Kursus Saya →
      </RouterLink>
    </Card>

    <!-- List -->
    <div v-else class="grid grid-cols-1 gap-4 md:grid-cols-2">
      <Card v-for="quiz in quizzes" :key="quiz.id" class="flex flex-col p-5">
        <div class="flex items-start justify-between gap-3">
          <div class="flex-1">
            <h3 class="font-bold text-slate-800">{{ quiz.title }}</h3>
            <p class="mt-0.5 text-xs text-slate-400">
              {{ quiz.course?.title ?? 'Kursus' }}
            </p>
          </div>
          <Badge
            :variant="quiz.has_passed ? 'success' : quiz.best_score !== null ? 'warning' : 'neutral'"
            size="sm"
          >
            {{ quiz.has_passed ? 'Lulus' : quiz.best_score !== null ? 'Belum Lulus' : 'Belum Dikerjakan' }}
          </Badge>
        </div>

        <p class="mt-3 line-clamp-2 text-sm text-slate-500">
          {{ quiz.description || 'Kerjakan quiz ini untuk menguji pemahamanmu.' }}
        </p>

        <div class="mt-4 flex flex-wrap items-center gap-4 text-[11px] font-semibold text-slate-500">
          <span class="flex items-center gap-1">📝 {{ quiz.questions_count }} soal</span>
          <span class="flex items-center gap-1">🎯 Lulus ≥ {{ quiz.passing_score }}</span>
          <span v-if="quiz.best_score !== null" class="flex items-center gap-1">
            🏆 Skor {{ quiz.best_score }}
          </span>
        </div>

        <div class="mt-4 border-t border-slate-100 pt-3">
          <Button size="sm" @click="start(quiz.id)">
            {{ quiz.best_score === null ? 'Mulai Quiz' : 'Coba Lagi' }} →
          </Button>
        </div>
      </Card>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import Breadcrumb from '@/components/ui/Breadcrumb.vue'
import Card from '@/components/ui/Card.vue'
import Button from '@/components/ui/Button.vue'
import Badge from '@/components/ui/Badge.vue'
import { clientApi } from '@/services/clientApi'

const router = useRouter()
const loading = ref(true)
const error = ref(null)
const quizzes = ref([])

const load = async () => {
  loading.value = true
  error.value = null
  try {
    const res = await clientApi.quizzes.list()
    quizzes.value = res.data
  } catch (e) {
    error.value = e.message
  } finally {
    loading.value = false
  }
}

const start = (quizId) => {
  router.push({ name: 'client-quiz', params: { id: quizId } })
}

onMounted(load)
</script>