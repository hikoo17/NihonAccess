<template>
  <div class="mx-auto max-w-6xl space-y-8">
    <!-- Header -->
    <div>
      <Breadcrumb
        :items="[
          { label: 'Dashboard', to: '/client/dashboard' },
          { label: 'Beli Paket' },
        ]"
      />
      <h1
        class="mt-3 text-2xl font-extrabold tracking-tight text-slate-800 sm:text-3xl"
      >
        Pilih Paket Kursus
      </h1>
      <p class="mt-1 text-sm text-slate-500">
        Tingkatkan skill bahasa Jepang kamu dengan paket yang tersedia.
      </p>
    </div>

    <!-- Search & filter -->
    <div
      class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
    >
      <div class="relative max-w-xs flex-1">
        <Input v-model="search" placeholder="Cari paket..." />
      </div>
      <div class="flex items-center gap-2">
        <button
          v-for="level in levels"
          :key="level"
          @click="activeLevel = level"
          class="rounded-full px-4 py-2 text-xs font-bold transition"
          :class="
            activeLevel === level
              ? 'bg-[#cf3d3d] text-white shadow-md shadow-[#cf3d3d]/10'
              : 'bg-white text-slate-500 border border-slate-200 hover:border-[#cf3d3d]/40'
          "
        >
          {{ level }}
        </button>
      </div>
    </div>

    <!-- Grid -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
      <PackageCard v-for="pkg in packages" :key="pkg.slug" :pkg="pkg" />
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import Breadcrumb from "@/components/ui/Breadcrumb.vue";
import Input from "@/components/ui/Input.vue";
import PackageCard from "@/components/Client/PackageCard.vue";

const search = ref("");
const levels = ["Semua", "Online", "On-Site"];
const activeLevel = ref("Semua");

const packages = [
  {
    slug: "basic-online",
    name: "Basic",
    tagline: "Untuk pemula yang baru mengenal bahasa Jepang",
    price: "Rp99.000",
    duration: "1 bulan",
    popular: false,
    features: [
      "Hiragana & Katakana",
      "Kosakata dasar 300+",
      "Akses materi video",
      "Latihan interaktif",
    ],
  },
  {
    slug: "premium-online",
    name: "Premium Online",
    tagline: "Paket terlengkap untuk persiapan JLPT N5-N4",
    price: "Rp199.000",
    duration: "3 bulan",
    popular: true,
    features: [
      "Semua fitur Basic",
      "Kanji 200+",
      "Latihan listening",
      "Quiz evaluasi",
      "Sertifikat",
    ],
  },
  {
    slug: "private-online",
    name: "Private Online",
    tagline: "Kelas privat 1-on-1 dengan mentor",
    price: "Rp499.000",
    duration: "6 bulan",
    popular: false,
    features: [
      "Semua fitur Premium",
      "Mentor pribadi",
      "Jadwal fleksibel",
      "Konsultasi langsung",
    ],
  },
];
</script>
