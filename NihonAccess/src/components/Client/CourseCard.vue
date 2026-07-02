<template>
  <Card class="overflow-hidden h-full group transition-shadow duration-300 hover:shadow-md">
    <!-- ====================== MOBILE (< md) : HORIZONTAL ====================== -->
    <component
      :is="cardTag"
      :to="cardTo"
      class="flex flex-row items-stretch md:hidden"
    >
      <!-- KIRI: thumbnail gambar cover + badge status (absolute pojok kiri atas) -->
      <div class="relative w-24 shrink-0 overflow-hidden bg-slate-100">
        <img
          :src="course.coverImage || getDefaultCover(course.title)"
          class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
          alt="Course Cover"
        />
        <div class="absolute left-2 top-2">
          <Badge
            :variant="badgeVariant"
            size="sm"
            class="backdrop-blur-md bg-white/90 shadow-sm"
          >
            {{ badgeLabel }}
          </Badge>
        </div>
      </div>

      <!-- KANAN: konten teks + progress + tanggal + chevron -->
      <div class="flex min-w-0 flex-1 flex-col justify-between p-3">
        <div class="min-w-0">
          <h3
            class="line-clamp-2 text-sm font-bold leading-snug text-slate-800 transition-colors group-hover:text-[#cf3d3d]"
          >
            {{ course.title }}
          </h3>
          <p class="mt-1 truncate text-[11px] font-medium text-slate-400">
            {{ course.level || 'General' }} · Lesson {{ course.lesson }}/{{
              course.totalLessons
            }}
          </p>
          <div class="mt-2">
            <ProgressBar
              :value="course.lesson"
              :max="course.totalLessons"
              :show-label="false"
            />
          </div>
        </div>

        <div class="mt-2 flex items-end justify-between gap-2">
          <div class="flex flex-col">
            <span
              class="text-[9px] font-medium uppercase tracking-wider text-slate-400"
              >Berakhir</span
            >
            <span class="text-[11px] font-semibold text-slate-600">{{
              course.endDate || 'TBA'
            }}</span>
          </div>

          <!-- Chevron-right: untuk kursus aktif & selesai (bisa dibuka ulang) -->
          <svg
            v-if="isClickable"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="2.5"
            stroke="currentColor"
            class="h-5 w-5 shrink-0 text-[#cf3d3d]"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="m8.25 4.5 7.5 7.5-7.5 7.5"
            />
          </svg>
        </div>
      </div>
    </component>

    <!-- ============ DESKTOP (>= md) : VERTIKAL (tidak diubah) ============ -->
    <div class="hidden h-full flex-col md:flex">
      <div class="relative h-40 w-full shrink-0 overflow-hidden bg-slate-100">
        <img
          :src="course.coverImage || getDefaultCover(course.title)"
          class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
          alt="Course Cover"
        />
        <div class="absolute left-3 top-3">
          <Badge
            :variant="badgeVariant"
            size="sm"
            class="backdrop-blur-md bg-white/90 shadow-sm"
          >
            {{ badgeLabel }}
          </Badge>
        </div>
      </div>

      <div class="flex flex-1 flex-col justify-between p-5">
        <div>
          <h3
            class="text-base font-bold leading-snug text-slate-800 transition-colors group-hover:text-[#cf3d3d]"
          >
            {{ course.title }}
          </h3>
          <p class="mt-1 text-xs font-medium text-slate-400">
            {{ course.level || 'General' }} · Lesson {{ course.lesson }} dari
            {{ course.totalLessons }}
          </p>
        </div>

        <div class="mt-5">
          <ProgressBar :value="course.lesson" :max="course.totalLessons" />
        </div>
      </div>

      <div
        class="flex items-center justify-between border-t border-slate-100 bg-slate-50/50 px-5 py-3"
      >
        <div class="flex flex-col">
          <span class="text-[10px] font-medium uppercase tracking-wider text-slate-400"
            >Berakhir</span
          >
          <span class="text-xs font-semibold text-slate-600">{{
            course.endDate || 'TBA'
          }}</span>
        </div>

        <div class="flex items-center gap-4">
          <!-- Aktif → Lanjutkan, Selesai → Ulasan (review), Kedaluwarsa → dikunci -->
          <RouterLink
            v-if="isClickable"
            :to="`/client/my-courses/${course.id}/learn`"
            class="text-xs font-bold text-[#cf3d3d] hover:underline"
          >
            {{ actionLabel }} →
          </RouterLink>
        </div>
      </div>
    </div>
  </Card>
</template>

<script setup>
import { computed } from 'vue'
import { RouterLink } from 'vue-router'
import Card from '@/components/ui/Card.vue'
import Badge from '@/components/ui/Badge.vue'
import ProgressBar from '@/components/ui/ProgresBar.vue'

const props = defineProps({
  course: { type: Object, required: true },
})

// Logika penentuan gambar cadangan (jika data kursus tidak memiliki properti .coverImage)
const getDefaultCover = (title) => {
  const lowerTitle = title.toLowerCase()
  if (lowerTitle.includes('kanji')) {
    return 'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?auto=format&fit=crop&w=600&q=80'
  }
  return 'https://images.unsplash.com/photo-1528164344705-47542687000d?auto=format&fit=crop&w=600&q=80'
}

const initials = computed(() =>
  props.course.title
    .split(' ')
    .slice(0, 2)
    .map((w) => w[0])
    .join('')
    .toUpperCase()
)

// Kartu bisa dibuka untuk kursus aktif & selesai (review);
// kedaluwarsa tetap dikunci (akses sudah berakhir).
const isClickable = computed(() =>
  ['active', 'completed'].includes(props.course.status)
)
const cardTag = computed(() => (isClickable.value ? RouterLink : 'div'))
const cardTo = computed(() =>
  isClickable.value
    ? `/client/my-courses/${props.course.id}/learn`
    : undefined
)

// Label link desktop: "Lanjutkan" untuk aktif, "Ulasan" untuk selesai.
const actionLabel = computed(() =>
  props.course.status === 'completed' ? 'Ulasan' : 'Lanjutkan'
)

const badgeVariant = computed(() => {
  switch (props.course.status) {
    case 'active': return 'success'
    case 'expired': return 'danger'
    case 'completed': return 'info'
    default: return 'neutral'
  }
})

const badgeLabel = computed(() => {
  switch (props.course.status) {
    case 'active': return 'Aktif'
    case 'expired': return 'Kedaluwarsa'
    case 'completed': return 'Selesai'
    default: return props.course.status
  }
})
</script>