<template>
  <div class="mx-auto max-w-6xl space-y-8">
    <!-- Header -->
    <div>
      <Breadcrumb
        :items="[{ label: 'Beranda', to: '/' }, { label: 'Dashboard' }]"
      />
      <h1
        class="mt-3 text-2xl font-extrabold tracking-tight text-slate-800 sm:text-3xl"
      >
        Selamat datang kembali, <span class="text-[#cf3d3d]">Nama!</span> 👋
      </h1>
      <p class="mt-1 text-sm text-slate-500">
        Lanjutkan belajar dan capai targetmu hari ini.
      </p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
      <StatCard
        label="Kursus Aktif"
        :value="3"
        subtitle="2 sedang berjalan"
        :icon="iconBook"
      />
      <StatCard
        label="Progress Belajar"
        :value="'68%'"
        subtitle="Kerja bagus!"
        :icon="iconChart"
        iconBg="bg-emerald-100"
        iconColor="text-emerald-600"
      />
      <StatCard
        label="Pelajaran Selesai"
        :value="24"
        subtitle="dari 36 pelajaran"
        :icon="iconCheck"
        iconBg="bg-blue-100"
        iconColor="text-blue-600"
      />
      <StatCard
        label="Hari Aktif"
        :value="12"
        subtitle="bulan ini"
        :icon="iconCalendar"
        iconBg="bg-amber-100"
        iconColor="text-amber-600"
      />
    </div>

    <!-- Continue learning -->
    <div>
      <div class="mb-4 flex items-center justify-between">
        <h2 class="text-lg font-extrabold text-slate-800">Lanjutkan Belajar</h2>
        <RouterLink
          to="/client/my-courses"
          class="text-xs font-bold text-[#cf3d3d] hover:underline"
          >Lihat semua →</RouterLink
        >
      </div>

      <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <Card
          v-for="course in courses"
          :key="course.id"
          class="overflow-hidden"
        >
          <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center">
            <div
              class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-[#cf3d3d] to-[#b03333] text-2xl font-extrabold text-white"
            >
              JL
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

    <!-- Recent orders -->
    <div>
      <div class="mb-4 flex items-center justify-between">
        <h2 class="text-lg font-extrabold text-slate-800">Pesanan Terbaru</h2>
        <RouterLink
          to="/client/orders"
          class="text-xs font-bold text-[#cf3d3d] hover:underline"
          >Riwayat →</RouterLink
        >
      </div>
      <Card class="divide-y divide-slate-50">
        <div
          v-for="order in orders"
          :key="order.id"
          class="flex items-center justify-between p-5"
        >
          <div class="flex items-center gap-4">
            <div
              class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-500"
            >
              <span v-html="iconReceipt"></span>
            </div>
            <div>
              <p class="text-sm font-bold text-slate-800">
                {{ order.package }}
              </p>
              <p class="text-xs text-slate-400">{{ order.orderNumber }}</p>
            </div>
          </div>
          <div class="text-right">
            <p class="text-sm font-extrabold text-slate-800">
              {{ order.amount }}
            </p>
            <Badge
              :variant="order.status === 'paid' ? 'success' : 'warning'"
              size="sm"
            >
              {{ order.status === "paid" ? "Lunas" : "Pending" }}
            </Badge>
          </div>
        </div>
      </Card>
    </div>
  </div>
</template>

<script setup>
import Breadcrumb from "@/components/ui/Breadcrumb.vue";
import Card from "@/components/ui/Card.vue";
import Badge from "@/components/ui/Badge.vue";
import ProgressBar from "@/components/ui/ProgresBar.vue";
import StatCard from "@/components/Client/StatCard.vue";

const iconBook = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>`;
const iconChart = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" /></svg>`;
const iconCheck = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`;
const iconCalendar = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25" /></svg>`;
const iconReceipt = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z" /></svg>`;

const courses = [
  { id: 1, title: "JLPT N5 Fundamental", lesson: 12, totalLessons: 20 },
  { id: 2, title: "Kanji Dasar", lesson: 4, totalLessons: 16 },
];

const orders = [
  {
    id: 1,
    package: "Paket Premium Online",
    orderNumber: "NA-001-abcd1234",
    amount: "Rp199.000",
    status: "paid",
  },
  {
    id: 2,
    package: "Paket Basic Online",
    orderNumber: "NA-002-efgh5678",
    amount: "Rp99.000",
    status: "pending",
  },
];
</script>
