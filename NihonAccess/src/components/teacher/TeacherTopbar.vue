<template>
  <header class="h-16 border-b border-zinc-800/50 px-8 flex items-center justify-between bg-[#121212] shrink-0">
    <h2 class="text-lg font-semibold text-zinc-100">{{ title }}</h2>
    <div class="flex items-center gap-4">
      <div class="flex items-center gap-2.5">
        <span class="text-xs bg-zinc-900 border border-zinc-800 px-2.5 py-1 rounded-lg text-zinc-400 font-medium">BU</span>
        <span class="text-sm font-medium text-zinc-300">Budi Utomo</span>
      </div>
    </div>
  </header>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    default: 'Dashboard'
  }
})
</script>