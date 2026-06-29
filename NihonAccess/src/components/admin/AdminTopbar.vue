<template>
  <header class="h-16 border-b border-zinc-800/50 px-8 flex items-center justify-between bg-[#121212] shrink-0">
    <h2 class="text-lg font-semibold text-zinc-100">{{ title }}</h2>
    <div class="flex items-center gap-4">
      <div class="flex items-center gap-2.5">
        <span class="text-xs bg-[#cf3d3d]/20 border border-[#cf3d3d]/30 px-2.5 py-1 rounded-lg text-[#cf3d3d] font-semibold">{{ initials }}</span>
        <span class="text-sm font-medium text-zinc-300">{{ userName }}</span>
      </div>
    </div>
  </header>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  title: { type: String, default: 'Dashboard' }
})

const userName = computed(() => {
  try {
    const data = JSON.parse(localStorage.getItem('user_data') || '{}')
    return data.name || 'Admin'
  } catch {
    return 'Admin'
  }
})

const initials = computed(() => {
  const name = userName.value
  return name.split(' ').map(w => w[0]).slice(0, 2).join('').toUpperCase()
})
</script>
