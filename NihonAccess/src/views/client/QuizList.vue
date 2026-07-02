<template>
  <div class="mx-auto max-w-6xl space-y-6">
    <!-- Header (menyamakan dashboard) -->
    <div>
      <div class="hidden sm:block">
        <Breadcrumb
          :items="[
            { label: 'Dashboard', to: '/client/dashboard' },
            { label: 'Quiz' },
          ]"
        />
      </div>
      <h1 class="mt-3 text-xl font-extrabold tracking-tight text-slate-800 sm:text-2xl lg:text-3xl">
        Quiz
      </h1>
      <p class="mt-1 text-sm text-slate-500">
        Uji pemahamanmu dengan mengerjakan quiz dari setiap kursus.
      </p>
    </div>

    <!-- Search & Filter (MOBILE ONLY) -->
    <div class="flex items-center gap-2 md:hidden">
      <div class="relative flex-1">
        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
          </svg>
        </span>
        <input
          v-model="search"
          type="text"
          placeholder="Cari quiz..."
          class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-10 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:border-[#cf3d3d] focus:outline-none focus:ring-2 focus:ring-[#cf3d3d]/20"
        />
      </div>
      <button
        @click="cycleFilter"
        class="flex shrink-0 items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-xs font-bold text-slate-600 active:scale-95"
        :class="statusFilter !== 'all' ? 'border-[#cf3d3d]/30 text-[#cf3d3d]' : ''"
      >
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
        </svg>
        {{ statusFilterLabel }}
      </button>
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
      <RouterLink to="/client/my-courses" class="mt-3 inline-block text-xs font-bold text-[#cf3d3d] hover:underline">
        Lihat Kursus Saya →
      </RouterLink>
    </Card>

    <!-- Search/filter kosong -->
    <Card v-else-if="filteredQuizzes.length === 0" class="p-8 text-center">
      <div class="mb-2 text-3xl">🔍</div>
      <p class="text-sm font-bold text-slate-700">Quiz tidak ditemukan</p>
      <p class="mt-1 text-xs text-slate-400">Coba kata kunci atau filter lain.</p>
    </Card>

    <!-- List -->
    <div v-else class="grid grid-cols-1 gap-4 md:grid-cols-2">
      <Card
        v-for="quiz in filteredQuizzes"
        :key="quiz.id"
        class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm"
      >
        <!-- ============ MOBILE: list item ============ -->
        <div class="flex items-stretch gap-3 p-3 md:hidden">
          <!-- KIRI: thumbnail ikon kategori (soft bg) -->
          <div
            class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl text-xl font-bold"
            :class="[categoryMeta(quiz).bg, categoryMeta(quiz).text]"
          >
            {{ categoryMeta(quiz).icon }}
          </div>

          <!-- TENGAH: judul + kategori + chip info -->
          <div class="flex min-w-0 flex-1 flex-col justify-center">
            <h3 class="truncate text-sm font-bold text-slate-800">{{ quiz.title }}</h3>
            <p class="truncate text-[11px] font-medium text-slate-400">
              {{ quiz.course?.title ?? 'Kursus' }}
            </p>
            <div class="mt-1.5 flex flex-wrap items-center gap-1.5 text-[10px] font-medium text-slate-500">
              <span class="rounded border border-slate-100 bg-slate-50 px-1.5 py-0.5">
                {{ quiz.questions_count }} soal
              </span>
              <span class="rounded border border-slate-100 bg-slate-50 px-1.5 py-0.5">
                Lulus ≥ {{ quiz.passing_score }}
              </span>
              <span
                v-if="quiz.best_score !== null"
                class="rounded border border-amber-100 bg-amber-50 px-1.5 py-0.5 text-amber-700"
              >
                🏆 {{ quiz.best_score }}
              </span>
            </div>
          </div>

          <!-- KANAN: badge status (atas) + tombol aksi (bawah) -->
          <div class="flex shrink-0 flex-col items-end justify-between gap-2">
            <Badge
              :variant="quiz.has_passed ? 'success' : quiz.best_score !== null ? 'warning' : 'neutral'"
              size="sm"
              class="rounded-full px-2 py-0.5 text-[10px]"
            >
              {{ quiz.has_passed ? 'Lulus' : quiz.best_score !== null ? 'Belum Lulus' : 'Belum Dikerjakan' }}
            </Badge>
            <button
              @click="start(quiz.id)"
              class="whitespace-nowrap rounded-lg px-3 py-1.5 text-xs font-bold text-white shadow-sm transition-colors active:scale-95"
              :class="quiz.best_score === null ? 'bg-blue-600 hover:bg-blue-700' : 'bg-red-600 hover:bg-red-700'"
            >
              {{ quiz.best_score === null ? 'Mulai Quiz' : 'Coba Lagi' }}
            </button>
          </div>
        </div>

        <!-- ============ DESKTOP: card + background ilustrasi ============ -->
        <div
          class="relative hidden min-h-[180px] md:block"
          :style="{
            backgroundImage: `url(${quiz.id === 'hiragana' ? '/hiragana.png' : '/kanji.png'})`,
            backgroundSize: 'cover',
            backgroundPosition: 'center',
            backgroundRepeat: 'no-repeat',
          }"
        >
          <div class="absolute inset-0 bg-gradient-to-r from-white/85 via-white/60 to-transparent"></div>

          <div class="relative z-10 flex h-full flex-col justify-between p-6">
            <div>
              <div class="flex items-start justify-between gap-3">
                <div class="flex-1">
                  <h3 class="text-xl font-bold text-slate-800">{{ quiz.title }}</h3>
                  <p class="mt-0.5 text-xs font-medium text-[#cf3d3d]">
                    {{ quiz.course?.title ?? 'Kursus' }}
                  </p>
                </div>
                <Badge
                  :variant="quiz.has_passed ? 'success' : quiz.best_score !== null ? 'warning' : 'neutral'"
                  size="sm"
                  class="rounded-full px-3 py-1 font-semibold text-[11px]"
                >
                  {{ quiz.has_passed ? 'Lulus' : quiz.best_score !== null ? 'Belum Lulus' : 'Belum Dikerjakan' }}
                </Badge>
              </div>

              <p class="mt-3 line-clamp-2 max-w-[450px] text-sm text-slate-500">
                {{ quiz.description || 'Kerjakan quiz ini untuk menguji pemahamanmu.' }}
              </p>

              <div class="mt-4 flex flex-wrap items-center gap-3 text-xs font-medium text-slate-500">
                <span class="flex items-center gap-1.5 rounded-md border border-slate-100 bg-slate-50 px-2.5 py-1">
                  📄 {{ quiz.questions_count }} soal
                </span>
                <span class="flex items-center gap-1.5 rounded-md border border-slate-100 bg-slate-50 px-2.5 py-1">
                  🎯 Lulus ≥ {{ quiz.passing_score }}
                </span>
                <span
                  v-if="quiz.best_score !== null"
                  class="flex items-center gap-1.5 rounded-md border border-amber-100 bg-amber-50 px-2.5 py-1 text-amber-700"
                >
                  🏆 Skor {{ quiz.best_score }}
                </span>
              </div>
            </div>

            <div class="mt-5 pt-1">
              <Button
                size="sm"
                @click="start(quiz.id)"
                :class="quiz.best_score === null ? 'bg-blue-600 hover:bg-blue-700' : 'bg-red-600 hover:bg-red-700'"
                class="rounded-xl px-5 py-2 font-semibold text-white shadow-md transition-all duration-200"
              >
                {{ quiz.best_score === null ? 'Mulai Quiz' : 'Coba Lagi' }} →
              </Button>
            </div>
          </div>
        </div>
      </Card>
    </div>
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

const router = useRouter()
const loading = ref(true)
const error = ref(null)
const quizzes = ref([])
const search = ref('')
const statusFilter = ref('all') // all | not_started | passed

const statusFilterLabel = computed(
  () => ({ all: 'Semua', not_started: 'Belum', passed: 'Lulus' }[statusFilter.value])
)

const cycleFilter = () => {
  const order = ['all', 'not_started', 'passed']
  const idx = order.indexOf(statusFilter.value)
  statusFilter.value = order[(idx + 1) % order.length]
}

const filteredQuizzes = computed(() => {
  const q = search.value.trim().toLowerCase()
  return quizzes.value.filter((quiz) => {
    const matchSearch =
      !q ||
      quiz.title?.toLowerCase().includes(q) ||
      quiz.course?.title?.toLowerCase().includes(q)

    let matchStatus = true
    if (statusFilter.value === 'not_started') matchStatus = quiz.best_score === null
    if (statusFilter.value === 'passed') matchStatus = quiz.has_passed

    return matchSearch && matchStatus
  })
})

// Thumbnail ikon + warna soft berdasarkan kategori quiz
function categoryMeta(quiz) {
  const text = `${quiz.title ?? ''} ${quiz.course?.title ?? ''}`.toLowerCase()
  if (text.includes('hiragana')) return { icon: 'あ', bg: 'bg-rose-50', text: 'text-rose-600' }
  if (text.includes('katakana')) return { icon: 'ア', bg: 'bg-violet-50', text: 'text-violet-600' }
  if (text.includes('kanji')) return { icon: '漢', bg: 'bg-amber-50', text: 'text-amber-600' }
  if (text.includes('kosakata') || text.includes('vocab')) return { icon: '📚', bg: 'bg-blue-50', text: 'text-blue-600' }
  if (text.includes('grammar') || text.includes('bunpou')) return { icon: '✏️', bg: 'bg-emerald-50', text: 'text-emerald-600' }
  return { icon: '📕', bg: 'bg-slate-100', text: 'text-slate-600' }
}

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