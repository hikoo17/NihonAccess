<template>
  <div class="mx-auto max-w-6xl space-y-8">
    <div>
      <Breadcrumb :items="[{ label: 'Dashboard' }]" />
      <h1
        class="mt-3 text-2xl font-extrabold tracking-tight text-slate-800 sm:text-3xl"
      >
        Selamat datang kembali,
        <span class="text-[#cf3d3d]">{{ loading ? '...' : (userName || 'Pengguna') }}</span>
        <span class="wave-emoji">👋</span>
      </h1>
      <p class="mt-1 text-sm text-slate-500">
        Lanjutkan belajar dan capai targetmu hari ini.
      </p>
    </div>

    <!-- Error banner -->
    <div
      v-if="error"
      class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700"
    >
      Gagal memuat data dashboard: {{ error }}
    </div>

    <!-- Stat cards -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
      <StatCard
        label="Kursus Aktif"
        :value="loading ? '...' : stats.active_courses"
        :subtitle="loading ? '' : `${stats.active_courses} sedang berjalan`"
        :icon="iconKursusAktif"
        iconBg="white"
      />
      <StatCard
        label="Progress Belajar"
        :value="loading ? '...' : `${stats.progress_percent}%`"
        subtitle="Kerja bagus!"
        :icon="iconRocket"
        iconBg=""
        iconColor="text-emerald-600"
      />
      <StatCard
        label="Pelajaran Selesai"
        :value="loading ? '...' : stats.completed_lessons"
        :subtitle="loading ? '' : `dari ${stats.total_lessons} pelajaran`"
        :icon="iconTarget"
        iconBg=""
        iconColor="text-blue-600"
      />
      <StatCard
        label="Hari Aktif"
        :value="loading ? '...' : stats.active_days"
        subtitle="bulan ini"
        :icon="iconStreak"
        iconBg=""
        iconColor=""
      />
    </div>

    <!-- Lanjutkan Belajar -->
    <div>
      <div class="mb-4 flex items-center justify-between">
        <h2 class="text-lg font-extrabold text-slate-800">Lanjutkan Belajar</h2>
        <RouterLink
          to="/client/my-courses"
          class="text-xs font-bold text-[#cf3d3d] hover:underline"
          >Lihat semua →</RouterLink
        >
      </div>

      <!-- Loading skeleton -->
      <div
        v-if="loading"
        class="grid grid-cols-1 gap-4 md:grid-cols-2"
      >
        <div
          v-for="n in 2"
          :key="n"
          class="animate-pulse rounded-2xl border border-slate-100 bg-white p-5"
        >
          <div class="flex gap-4">
            <div class="h-16 w-16 rounded-2xl bg-slate-200"></div>
            <div class="flex-1 space-y-2">
              <div class="h-4 w-2/3 rounded bg-slate-200"></div>
              <div class="h-3 w-1/3 rounded bg-slate-100"></div>
              <div class="mt-3 h-2 w-full rounded bg-slate-100"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty state -->
      <div
        v-else-if="courses.length === 0"
        class="rounded-2xl border border-dashed border-slate-200 bg-white p-8 text-center"
      >
        <p class="text-sm font-semibold text-slate-500">
          Belum ada kursus aktif.
        </p>
        <RouterLink
          to="/client/packages"
          class="mt-2 inline-block text-xs font-bold text-[#cf3d3d] hover:underline"
        >
          Lihat paket belajar →
        </RouterLink>
      </div>

      <!-- Course cards -->
      <div
        v-else
        class="grid grid-cols-1 gap-4 md:grid-cols-2"
      >
        <Card
          v-for="course in courses"
          :key="course.id"
          class="overflow-hidden"
        >
          <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center">
            <div
              class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-[#cf3d3d] to-[#b03333] text-sm font-extrabold text-white"
            >
              {{ initials(course.title) }}
            </div>
            <div class="flex-1">
              <div class="flex items-center gap-2">
                <h3 class="font-bold text-slate-800">{{ course.title }}</h3>
                <Badge variant="success" size="sm" dot>Aktif</Badge>
              </div>
              <p class="mt-0.5 text-xs text-slate-400">
                Lesson {{ course.lesson }} dari {{ course.totalLessons }}
              </p>
              <div class="mt-3">
                <ProgressBar
                  :value="course.lesson"
                  :max="course.totalLessons"
                />
              </div>
            </div>
          </div>
          <div class="border-t border-slate-100 px-5 py-3">
            <RouterLink
              :to="`/client/my-courses/${course.id}/learn`"
              class="text-xs font-bold text-[#cf3d3d] hover:underline"
            >
              Lanjutkan →
            </RouterLink>
          </div>
        </Card>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import Breadcrumb from "@/components/ui/Breadcrumb.vue";
import Card from "@/components/ui/Card.vue";
import Badge from "@/components/ui/Badge.vue";
import ProgressBar from "@/components/ui/ProgresBar.vue";
import StatCard from "@/components/Client/StatCard.vue";
import { clientApi } from "@/services/clientApi";

// Ikon SVG
const iconBook = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>`;
const iconChart = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" /></svg>`;
const iconCheck = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`;
const iconStreak = `<img src="https://fonts.gstatic.com/s/e/notoemoji/latest/1f525/512.gif" alt="streak" class="h-9 w-9 animate-pulse" />`;
const iconTarget = `<img src="https://fonts.gstatic.com/s/e/notoemoji/latest/1f3af/512.gif" alt="target" class="h-9 w-9" />`;
const iconKursusAktif = `<img src="https://fonts.gstatic.com/s/e/notoemoji/latest/1f393/512.gif" alt="graduation cap" class="h-9 w-9" />`;
const iconRocket = `<img src="https://fonts.gstatic.com/s/e/notoemoji/latest/1f680/512.gif" alt="rocket" class="h-9 w-9" />`;

// State
const loading = ref(true);
const error = ref(null);
const userName = ref("");
const stats = ref({
  active_courses: 0,
  progress_percent: 0,
  completed_lessons: 0,
  total_lessons: 0,
  active_days: 0,
});
const courses = ref([]);

// Helper: ambil inisial dari judul paket (untuk avatar kotak merah)
function initials(title = "") {
  const words = title.trim().split(/\s+/).filter(Boolean);
  if (words.length === 0) return "?";
  if (words.length === 1) return words[0].slice(0, 2).toUpperCase();
  return (words[0][0] + words[1][0]).toUpperCase();
}

onMounted(async () => {
  try {
    const res = await clientApi.dashboard();
    const data = res.data;

    userName.value = data.user?.name ?? "";
    stats.value = {
      active_courses: data.stats?.active_courses ?? 0,
      progress_percent: data.stats?.progress_percent ?? 0,
      completed_lessons: data.stats?.completed_lessons ?? 0,
      total_lessons: data.stats?.total_lessons ?? 0,
      active_days: data.stats?.active_days ?? 0,
    };

    // Mapping enrollment → bentuk yang dipakai template
    courses.value = (data.enrollments ?? []).map((e) => ({
      id: e.id,
      title: e.package?.name ?? "Tanpa Judul",
      lesson: e.progress?.current_lesson ?? 0,
      totalLessons: e.progress?.total_lessons ?? 0,
    }));
  } catch (err) {
    error.value = err.message || "Terjadi kesalahan.";
  } finally {
    loading.value = false;
  }
});
</script>

<style scoped>
.wave-emoji {
  display: inline-block;
  animation: wave-animation 2.5s infinite;
  transform-origin: 70% 70%;
}

@keyframes wave-animation {
    0% { transform: rotate( 0.0deg) }
   10% { transform: rotate(14.0deg) }
   20% { transform: rotate(-8.0deg) }
   30% { transform: rotate(14.0deg) }
   40% { transform: rotate(-4.0deg) }
   50% { transform: rotate(10.0deg) }
   60% { transform: rotate( 0.0deg) }
  100% { transform: rotate( 0.0deg) }
}
</style>