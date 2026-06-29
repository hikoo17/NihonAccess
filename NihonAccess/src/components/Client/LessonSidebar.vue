<template>
  <Card class="p-4">
    <!-- Course title -->
    <div class="mb-4 border-b border-slate-100 pb-4">
      <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Kursus</p>
      <h3 class="mt-1 text-sm font-extrabold text-slate-800">{{ courseTitle }}</h3>
      <div class="mt-2">
        <ProgressBar :value="completedCount" :max="lessons.length" showLabel>
          Progress
        </ProgressBar>
      </div>
    </div>

    <!-- Lessons -->
    <nav class="space-y-1">
      <button
        v-for="(lesson, index) in lessons"
        :key="lesson.id"
        @click="$emit('select', lesson)"
        class="lesson-item w-full text-left"
        :class="{ active: lesson.id === currentId }"
      >
        <!-- Status icon -->
        <span class="lesson-status" :class="statusClass(lesson)">
          <svg v-if="lesson.completed" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-3.5 w-3.5">
            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
          </svg>
          <span v-else class="text-[10px] font-extrabold">{{ index + 1 }}</span>
        </span>

        <div class="flex-1">
          <p class="text-xs font-bold leading-tight" :class="lesson.id === currentId ? 'text-white' : 'text-slate-700'">{{ lesson.title }}</p>
          <p class="mt-0.5 text-[10px]" :class="lesson.id === currentId ? 'text-white/70' : 'text-slate-400'">{{ lesson.duration }}</p>
        </div>

        <svg v-if="lesson.id === currentId" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4 text-white/70">
          <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 1 1 1.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
        </svg>
      </button>
    </nav>

    <!-- Quiz section -->
    <div class="mt-4 border-t border-slate-100 pt-4">
      <p class="mb-2 px-2 text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Evaluasi</p>
      <button class="lesson-item w-full text-left">
        <span class="lesson-status status-quiz">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3.5 w-3.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
          </svg>
        </span>
        <p class="flex-1 text-xs font-bold text-slate-700">Quiz Akhir Kursus</p>
      </button>
    </div>
  </Card>
</template>

<script setup>
import { computed } from 'vue'
import Card from '@/components/ui/Card.vue'
import ProgressBar from '@/components/ui/ProgresBar.vue'

const props = defineProps({
  courseTitle: { type: String, default: '' },
  lessons: { type: Array, default: () => [] },
  currentId: { type: [String, Number], default: null },
})

defineEmits(['select'])

const completedCount = computed(() =>
  props.lessons.filter((l) => l.completed).length
)

const statusClass = (lesson) => {
  if (lesson.completed) return 'status-done'
  if (lesson.id === props.currentId) return 'status-current'
  return 'status-todo'
}
</script>

<style scoped>
.lesson-item {
  display: flex;
  align-items: center;
  gap: 0.625rem;
  padding: 0.6rem 0.75rem;
  border-radius: 0.75rem;
  transition: all 0.2s ease;
}
.lesson-item:hover {
  background-color: #f8fafc;
}
.lesson-item.active {
  background-color: #cf3d3d;
  box-shadow: 0 4px 12px rgba(207, 61, 61, 0.25);
}
.lesson-status {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 1.5rem;
  width: 1.5rem;
  border-radius: 0.5rem;
  flex-shrink: 0;
}
.status-done {
  background-color: #d1fae5;
  color: #059669;
}
.status-current {
  background-color: rgba(255, 255, 255, 0.25);
  color: #ffffff;
}
.status-todo {
  background-color: #f1f5f9;
  color: #94a3b8;
}
.status-quiz {
  background-color: #fef3c7;
  color: #d97706;
}
</style>