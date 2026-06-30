<template>
  <div class="mx-auto max-w-7xl space-y-6">
    <!-- Top bar -->
    <div class="flex items-center justify-between">
      <Breadcrumb
        :items="[
          { label: 'Kursus Saya', to: '/client/my-courses' },
          { label: loading ? 'Memuat...' : courseTitle },
        ]"
      />
      <RouterLink
        to="/client/my-courses"
        class="text-xs font-bold text-slate-400 hover:text-[#cf3d3d]"
      >
        ← Kembali
      </RouterLink>
    </div>

    <!-- Error banner -->
    <div
      v-if="error"
      class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700"
    >
      Gagal memuat pelajaran: {{ error }}
    </div>

    <!-- Loading -->
    <div v-else-if="loading" class="grid grid-cols-1 gap-6 lg:grid-cols-3">
      <div class="space-y-6 lg:col-span-2">
        <div class="aspect-video animate-pulse rounded-2xl bg-slate-200"></div>
        <div class="space-y-3 rounded-2xl border border-slate-100 bg-white p-6">
          <div class="h-4 w-1/3 rounded bg-slate-200"></div>
          <div class="h-5 w-2/3 rounded bg-slate-200"></div>
          <div class="mt-3 h-2 w-full rounded bg-slate-100"></div>
          <div class="h-2 w-full rounded bg-slate-100"></div>
        </div>
      </div>
      <div class="h-64 animate-pulse rounded-2xl bg-slate-200"></div>
    </div>

    <!-- Content -->
    <div v-else-if="currentLesson" class="grid grid-cols-1 gap-6 lg:grid-cols-3">
      <!-- Left: lesson content -->
      <div class="space-y-6 lg:col-span-2">
        <!-- Video player -->
        <Card class="overflow-hidden">
          <div class="flex aspect-video items-center justify-center bg-slate-900">
            <!-- Video asli kalau ada -->
            <video
              v-if="currentLesson.video_url"
              :key="currentLesson.id"
              class="h-full w-full"
              controls
              :src="currentLesson.video_url"
            ></video>
            <!-- Placeholder kalau tidak ada video -->
            <div v-else class="text-center">
              <div
                class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-white/20 backdrop-blur"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 24 24"
                  fill="currentColor"
                  class="h-8 w-8 text-white"
                >
                  <path
                    fill-rule="evenodd"
                    d="M4.5 5.653c0-1.426 1.529-2.33 2.779-1.643l11.54 6.348c1.295.712 1.295 2.573 0 3.285L7.28 19.991c-1.25.687-2.779-.217-2.779-1.643V5.653z"
                    clip-rule="evenodd"
                  />
                </svg>
              </div>
              <p class="mt-3 text-xs font-medium text-white/60">
                Video belum tersedia
              </p>
            </div>
          </div>
        </Card>

        <!-- Lesson info -->
        <Card class="p-6">
          <div class="mb-4 flex items-center gap-2">
            <Badge variant="info" size="sm"
              >Lesson {{ currentIndex + 1 }} dari {{ lessons.length }}</Badge
            >
            <Badge variant="neutral" size="sm">⏱ Pelajaran</Badge>
          </div>
          <h1 class="text-xl font-extrabold tracking-tight text-slate-800">
            {{ currentLesson.title }}
          </h1>

          <!-- Content -->
          <p
            v-if="currentLesson.content"
            class="mt-3 whitespace-pre-line text-sm leading-relaxed text-slate-500"
          >
            {{ currentLesson.content }}
          </p>
          <p v-else class="mt-3 text-sm italic text-slate-400">
            Konten pelajaran belum tersedia.
          </p>
        </Card>

        <!-- Navigation -->
        <div class="flex items-center justify-between">
          <Button
            variant="outline"
            size="sm"
            :disabled="currentIndex === 0"
            @click="goTo(currentIndex - 1)"
          >
            ← Sebelumnya
          </Button>
          <Button
            size="sm"
            :disabled="completing"
            @click="completeLesson"
          >
            {{ completing ? "Menyimpan..." : "Tandai Selesai & Lanjut →" }}
          </Button>
        </div>
      </div>

      <!-- Right: lesson sidebar -->
      <div class="lg:col-span-1">
        <div class="sticky top-24">
          <LessonSidebar
            :course-title="courseTitle"
            :lessons="sidebarLessons"
            :current-id="currentLesson.id"
            @select="onSelectLesson"
          />
        </div>
      </div>
    </div>

    <!-- Empty -->
    <Card v-else class="p-12 text-center">
      <h3 class="text-sm font-extrabold text-slate-800">Belum ada pelajaran</h3>
      <p class="mx-auto mt-1 max-w-xs text-xs text-slate-400">
        Kursus ini belum memiliki pelajaran yang dapat diakses.
      </p>
    </Card>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import Breadcrumb from "@/components/ui/Breadcrumb.vue";
import Card from "@/components/ui/Card.vue";
import Button from "@/components/ui/Button.vue";
import Badge from "@/components/ui/Badge.vue";
import LessonSidebar from "@/components/Client/LessonSidebar.vue";
import { clientApi } from "@/services/clientApi";

const props = defineProps({
  id: { type: [String, Number], default: "" },
});

const loading = ref(true);
const error = ref(null);
const completing = ref(false);

const courseTitle = ref("");
const lessons = ref([]);
const currentIndex = ref(0);

const currentLesson = computed(() => lessons.value[currentIndex.value] ?? null);

// Mapping ke bentuk yang dibutuhkan LessonSidebar
const sidebarLessons = computed(() =>
  lessons.value.map((l) => ({
    id: l.id,
    title: l.title,
    duration: "Pelajaran", // DB tidak punya kolom duration → pakai placeholder
    completed: l.completed,
  }))
);

onMounted(async () => {
  try {
    const res = await clientApi.courseLessons(props.id);
    const data = res.data ?? {};
    courseTitle.value = data.title ?? "Kursus";
    lessons.value = data.lessons ?? [];
  } catch (err) {
    error.value = err.message || "Terjadi kesalahan.";
  } finally {
    loading.value = false;
  }
});

function onSelectLesson(lesson) {
  const idx = lessons.value.findIndex((l) => l.id === lesson.id);
  if (idx !== -1) currentIndex.value = idx;
}

function goTo(idx) {
  if (idx >= 0 && idx < lessons.value.length) {
    currentIndex.value = idx;
  }
}

async function completeLesson() {
  if (!currentLesson.value || completing.value) return;
  completing.value = true;
  try {
    await clientApi.completeLesson(currentLesson.value.id);
    // tandai selesai secara lokal
    lessons.value[currentIndex.value].completed = true;
    // lanjut ke pelajaran berikutnya
    if (currentIndex.value + 1 < lessons.value.length) {
      currentIndex.value += 1;
    }
  } catch (err) {
    error.value = err.message || "Gagal menandai pelajaran.";
  } finally {
    completing.value = false;
  }
}
</script>