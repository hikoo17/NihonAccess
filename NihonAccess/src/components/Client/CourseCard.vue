<template>
  <Card class="overflow-hidden flex flex-col h-full group hover:shadow-md transition-shadow duration-300">
    <div class="relative h-40 w-full bg-slate-100 overflow-hidden shrink-0">
      <img 
        :src="course.coverImage || getDefaultCover(course.title)" 
        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
        alt="Course Cover"
      />
      <div class="absolute top-3 left-3">
        <Badge :variant="badgeVariant" size="sm" class="backdrop-blur-md bg-white/90 shadow-sm">
          {{ badgeLabel }}
        </Badge>
      </div>

      <!-- <div class="absolute bottom-3 right-3 flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-[#cf3d3d] to-[#b03333] text-sm font-extrabold text-white shadow-md">
        {{ initials }}
      </div> -->
    </div>

    <div class="p-5 flex-1 flex flex-col justify-between">
      <div>
        <h3 class="font-bold text-base text-slate-800 leading-snug group-hover:text-[#cf3d3d] transition-colors">
          {{ course.title }}
        </h3>
        <p class="mt-1 text-xs text-slate-400 font-medium">
          {{ course.level || 'General' }} · Lesson {{ course.lesson }} dari {{ course.totalLessons }}
        </p>
      </div>

      <div class="mt-5">
        <!-- <div class="flex justify-between items-center mb-1 text-xs font-bold text-[#cf3d3d]">
          <span>Progress</span>
          <span>{{ Math.round((course.lesson / course.totalLessons) * 100) }}%</span>
        </div> -->
        <ProgressBar :value="course.lesson" :max="course.totalLessons" />
      </div>
    </div>

    <div class="flex items-center justify-between border-t border-slate-100 px-5 py-3 bg-slate-50/50">
      <div class="flex flex-col">
        <span class="text-[10px] uppercase tracking-wider text-slate-400 font-medium">Berakhir</span>
        <span class="text-xs font-semibold text-slate-600">{{ course.endDate || 'TBA' }}</span>
      </div>

      <div class="flex items-center gap-4">
        <!-- <RouterLink to="/client/my-courses" class="text-xs font-bold text-slate-400 hover:text-[#cf3d3d] transition-colors">
          Detail
        </RouterLink> -->
        <RouterLink
          v-if="course.status === 'active'"
          :to="`/client/my-courses/${course.id}/learn`"
          class="text-xs font-bold text-[#cf3d3d] hover:underline"
        >
          Lanjutkan →
        </RouterLink>
        <!-- <span v-else class="text-xs font-bold text-slate-400">{{ badgeLabel }}</span> -->
      </div>
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

// Logika penentuan gambar cadangan (jika data kursus tidak memiliki properti .coverImage)
const getDefaultCover = (title) => {
  const lowerTitle = title.toLowerCase()
  if (lowerTitle.includes('kanji')) {
    return 'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?auto=format&fit=crop&w=600&q=80' // Gambar Pagoda Jepang
  }
  return 'https://images.unsplash.com/photo-1528164344705-47542687000d?auto=format&fit=crop&w=600&q=80' // Gambar Gerbang Torii Sakura
}

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