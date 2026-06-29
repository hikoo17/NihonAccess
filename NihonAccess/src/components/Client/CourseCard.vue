<template>
  <Card class="overflow-hidden">
    <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center">
      <!-- Icon -->
      <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-[#cf3d3d] to-[#b03333] text-2xl font-extrabold text-white">
        {{ initials }}
      </div>

      <!-- Info -->
      <div class="flex-1">
        <div class="flex flex-wrap items-center gap-2">
          <h3 class="font-bold text-slate-800">{{ course.title }}</h3>
          <Badge :variant="badgeVariant" size="sm" dot>{{ badgeLabel }}</Badge>
        </div>
        <p class="mt-0.5 text-xs text-slate-400">{{ course.level }} · Lesson {{ course.lesson }} dari {{ course.totalLessons }}</p>
        <div class="mt-3">
          <ProgressBar :value="course.lesson" :max="course.totalLessons" />
        </div>
        <p class="mt-2 text-[11px] text-slate-400">
          Berakhir: <span class="font-semibold text-slate-500">{{ course.endDate }}</span>
        </p>
      </div>
    </div>

    <!-- Footer -->
    <div class="flex items-center justify-between border-t border-slate-100 px-5 py-3">
      <RouterLink to="/client/my-courses" class="text-xs font-bold text-slate-400 hover:text-[#cf3d3d]">
        Detail Kursus
      </RouterLink>
      <RouterLink
        v-if="course.status === 'active'"
        :to="`/client/my-courses/${course.id}/learn`"
        class="text-xs font-bold text-[#cf3d3d] hover:underline"
      >
        Lanjutkan Belajar →
      </RouterLink>
      <span v-else class="text-xs font-bold text-slate-400">{{ badgeLabel }}</span>
    </div>
  </Card>
</template>

<script setup>
import { computed } from 'vue'
import Card from '@/components/ui/Card.vue'
import Badge from '@/components/ui/Badge.vue'
import ProgressBar from '@/components/ui/ProgresBar.vue'

const props = defineProps({
  course: { type: Object, required: true },
})

const initials = computed(() =>
  props.course.title
    .split(' ')
    .slice(0, 2)
    .map((w) => w[0])
    .join('')
    .toUpperCase()
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