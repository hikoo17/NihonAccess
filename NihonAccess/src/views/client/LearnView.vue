<template>
  <div class="mx-auto max-w-7xl space-y-6">
    <!-- Top bar -->
    <div class="flex items-center justify-between">
      <Breadcrumb
        :items="[
          { label: 'Kursus Saya', to: '/client/my-courses' },
          { label: 'JLPT N5 Fundamental' },
        ]"
      />
      <RouterLink
        to="/client/my-courses"
        class="text-xs font-bold text-slate-400 hover:text-[#cf3d3d]"
      >
        ← Kembali
      </RouterLink>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
      <!-- Left: lesson content -->
      <div class="space-y-6 lg:col-span-2">
        <!-- Video player -->
        <Card class="overflow-hidden">
          <div
            class="flex aspect-video items-center justify-center bg-slate-900"
          >
            <button
              class="flex h-16 w-16 items-center justify-center rounded-full bg-white/20 backdrop-blur transition hover:scale-110 hover:bg-white/30"
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
            </button>
          </div>
        </Card>

        <!-- Lesson info -->
        <Card class="p-6">
          <div class="mb-4 flex items-center gap-2">
            <Badge variant="info" size="sm">Lesson 3 dari 20</Badge>
            <Badge variant="neutral" size="sm">⏱ 12 menit</Badge>
          </div>
          <h1 class="text-xl font-extrabold tracking-tight text-slate-800">
            {{ currentLesson.title }}
          </h1>
          <p class="mt-2 text-sm leading-relaxed text-slate-500">
            Dalam pelajaran ini kamu akan mempelajari huruf hiragana dasar, cara
            membaca dan menulisnya dengan benar. Perhatikan stroke order agar
            tulisan kamu rapi dan sesuai standar.
          </p>

          <!-- Content -->
          <div class="mt-5 space-y-3 rounded-2xl bg-slate-50 p-5">
            <h3 class="text-sm font-extrabold text-slate-700">
              Ringkasan Materi
            </h3>
            <ul class="space-y-2 text-xs text-slate-600">
              <li class="flex items-start gap-2">
                <span class="mt-1 h-1.5 w-1.5 rounded-full bg-[#cf3d3d]"></span>
                Pengenalan 46 huruf hiragana dasar (あ 〜 ん)
              </li>
              <li class="flex items-start gap-2">
                <span class="mt-1 h-1.5 w-1.5 rounded-full bg-[#cf3d3d]"></span>
                Teknik menulis dengan stroke order yang benar
              </li>
              <li class="flex items-start gap-2">
                <span class="mt-1 h-1.5 w-1.5 rounded-full bg-[#cf3d3d]"></span>
                Kosakata sederhana menggunakan hiragana
              </li>
            </ul>
          </div>
        </Card>

        <!-- Navigation -->
        <div class="flex items-center justify-between">
          <Button variant="outline" size="sm">← Sebelumnya</Button>
          <Button size="sm" @click="completeLesson"
            >Tandai Selesai & Lanjut →</Button
          >
        </div>
      </div>

      <!-- Right: lesson sidebar -->
      <div class="lg:col-span-1">
        <div class="sticky top-24">
          <LessonSidebar
            course-title="JLPT N5 Fundamental"
            :lessons="lessons"
            :current-id="currentLesson.id"
            @select="selectLesson"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import Breadcrumb from "@/components/ui/Breadcrumb.vue";
import Card from "@/components/ui/Card.vue";
import Button from "@/components/ui/Button.vue";
import Badge from "@/components/ui/Badge.vue";
import LessonSidebar from "@/components/client/LessonSidebar.vue";

const props = defineProps({
  id: { type: [String, Number], default: "" },
});

const lessons = ref([
  {
    id: 1,
    title: "Pengenalan Hiragana",
    duration: "10 menit",
    completed: true,
  },
  { id: 2, title: "Hiragana: あ 〜 さ", duration: "12 menit", completed: true },
  {
    id: 3,
    title: "Hiragana: た 〜 や",
    duration: "12 menit",
    completed: false,
  },
  {
    id: 4,
    title: "Hiragana: ら 〜 ん",
    duration: "10 menit",
    completed: false,
  },
  {
    id: 5,
    title: "Dakuten & Handakuten",
    duration: "15 menit",
    completed: false,
  },
  {
    id: 6,
    title: "Kombinasi Hiragana",
    duration: "14 menit",
    completed: false,
  },
  { id: 7, title: "Latihan Menulis", duration: "20 menit", completed: false },
]);

const currentLesson = ref(lessons.value[2]);

const selectLesson = (lesson) => {
  currentLesson.value = lesson;
};

const completeLesson = () => {
  const idx = lessons.value.findIndex((l) => l.id === currentLesson.value.id);
  if (idx !== -1) {
    lessons.value[idx].completed = true;
    if (idx + 1 < lessons.value.length) {
      currentLesson.value = lessons.value[idx + 1];
    }
  }
};
</script>
