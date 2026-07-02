<template>
  <div class="mx-auto max-w-6xl space-y-8">
    <!-- Header -->
    <div>
      <div class="hidden sm:block">
        <Breadcrumb
          :items="[
            { label: 'Dashboard', to: '/client/dashboard' },
            { label: 'Kursus Saya' },
          ]"
        />
      </div>
      <h1
        class="mt-3 text-2xl font-extrabold tracking-tight text-slate-800 sm:text-3xl"
      >
        Kursus Saya
      </h1>
      <p class="mt-1 text-sm text-slate-500">
        Lihat semua kursus yang sudah kamu beli.
      </p>
    </div>

    <!-- Error banner -->
    <div
      v-if="error"
      class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700"
    >
      Gagal memuat data kursus: {{ error }}
    </div>

    <!-- Filter tabs -->
    <div v-if="!loading" class="flex items-center gap-2 overflow-x-auto  whitespace-nowrap pb-2">
      <button
        v-for="tab in tabs"
        :key="tab"
        @click="activeTab = tab"
        class="shrink-0 rounded-full px-4 py-2 text-xs font-bold transition"
        :class="
          activeTab === tab
            ? 'bg-[#cf3d3d] text-white shadow-md shadow-[#cf3d3d]/10'
            : 'bg-white text-slate-500 border border-slate-200 hover:border-[#cf3d3d]/40'
        "
      >
        {{ tab }} ({{ countByTab(tab) }})
      </button>
    </div>

    <!-- Loading skeleton -->
    <div v-if="loading" class="grid grid-cols-1 gap-5 md:grid-cols-2">
      <div
        v-for="n in 4"
        :key="n"
        class="animate-pulse overflow-hidden rounded-2xl border border-slate-100 bg-white"
      >
        <div class="h-40 w-full bg-slate-200"></div>
        <div class="space-y-3 p-5">
          <div class="h-4 w-2/3 rounded bg-slate-200"></div>
          <div class="h-3 w-1/3 rounded bg-slate-100"></div>
          <div class="mt-4 h-2 w-full rounded bg-slate-100"></div>
        </div>
      </div>
    </div>

    <!-- Grid -->
    <div
      v-else-if="filteredCourses.length"
      class="grid grid-cols-1 gap-5 md:grid-cols-2"
    >
      <CourseCard
        v-for="course in filteredCourses"
        :key="course.id"
        :course="course"
      />
    </div>

    <!-- Empty state -->
    <Card v-else class="p-12 text-center">
      <div
        class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-slate-100"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
          stroke-width="1.5"
          stroke="currentColor"
          class="h-8 w-8 text-slate-400"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"
          />
        </svg>
      </div>
      <h3 class="text-sm font-extrabold text-slate-800">Belum ada kursus</h3>
      <p class="mx-auto mt-1 max-w-xs text-xs text-slate-400">
        Kamu belum punya kursus di kategori ini. Yuk beli paket untuk mulai
        belajar.
      </p>
      <Button class="mt-5" @click="$router.push('/client/packages')"
        >Beli Paket Sekarang</Button
      >
    </Card>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import Breadcrumb from "@/components/ui/Breadcrumb.vue";
import Card from "@/components/ui/Card.vue";
import Button from "@/components/ui/Button.vue";
import CourseCard from "@/components/Client/CourseCard.vue";
import { clientApi } from "@/services/clientApi";

const tabs = ["Semua", "Aktif", "Selesai", "Kedaluwarsa"];
const activeTab = ref("Semua");

const loading = ref(true);
const error = ref(null);
const courses = ref([]);

// Format tanggal ISO (2026-08-15) → "15 Agu 2026"
function formatDate(iso) {
  if (!iso) return "TBA";
  try {
    return new Date(iso).toLocaleDateString("id-ID", {
      day: "2-digit",
      month: "short",
      year: "numeric",
    });
  } catch {
    return "TBA";
  }
}

onMounted(async () => {
  try {
    const res = await clientApi.myCourses();
    // Mapping enrollment → bentuk yang dipakai CourseCard
    courses.value = (res.data ?? []).map((e) => ({
      id: e.id,
      title: e.package?.name ?? "Tanpa Judul",
      level: e.package?.level ?? "General",
      lesson: e.progress?.current_lesson ?? 0,
      totalLessons: e.progress?.total_lessons ?? 0,
      status: e.effective_status ?? "active",
      endDate: formatDate(e.end_date),
    }));
  } catch (err) {
    error.value = err.message || "Terjadi kesalahan.";
  } finally {
    loading.value = false;
  }
});

const filteredCourses = computed(() => {
  if (activeTab.value === "Semua") return courses.value;
  const map = { Aktif: "active", Selesai: "completed", Kedaluwarsa: "expired" };
  return courses.value.filter((c) => c.status === map[activeTab.value]);
});

const countByTab = (tab) => {
  if (tab === "Semua") return courses.value.length;
  const map = { Aktif: "active", Selesai: "completed", Kedaluwarsa: "expired" };
  return courses.value.filter((c) => c.status === map[tab]).length;
};
</script>
