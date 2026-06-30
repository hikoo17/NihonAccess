<template>
  <header class="sticky top-0 z-30 flex h-16 shrink-0 items-center justify-between border-b border-slate-200 bg-white/80 px-8 backdrop-blur">
    <div class="min-w-0">
      <h2 class="truncate text-lg font-bold text-slate-900">{{ title }}</h2>
    </div>
    <div class="flex items-center gap-3">
      <div class="flex items-center gap-2.5">
        <span class="flex h-9 w-9 items-center justify-center rounded-xl border border-[#cf3d3d]/30 bg-[#cf3d3d]/10 text-xs font-bold text-[#cf3d3d]">
          {{ initials }}
        </span>
        <div class="hidden sm:block">
          <p class="text-sm font-bold text-slate-800">{{ userName }}</p>
          <p class="text-[11px] font-medium text-slate-400">Teacher</p>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { computed } from 'vue'

defineProps({
  title: { type: String, default: 'Dashboard' },
})

const userName = computed(() => {
  try {
    const data = JSON.parse(localStorage.getItem('user_data') || '{}')
    return data.name || 'Guru'
  } catch {
    return 'Guru'
  }
})

const initials = computed(() => {
  return userName.value.split(' ').map((w) => w[0]).slice(0, 2).join('').toUpperCase()
})
</script>
