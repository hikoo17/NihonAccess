<template>
  <div class="flex items-center justify-center gap-3 px-6 py-16 text-slate-400">
    <svg class="h-6 w-6 animate-spin text-[#cf3d3d]" fill="none" viewBox="0 0 24 24">
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
      <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
    </svg>
    <span class="text-sm font-semibold">{{ label }}</span>
  </div>
</template>

<script setup>
defineProps({
  label: { type: String, default: 'Memuat data...' },
})
</script>
