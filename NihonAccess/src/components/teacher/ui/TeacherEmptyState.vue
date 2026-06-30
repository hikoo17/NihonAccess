<template>
  <div class="flex flex-col items-center justify-center px-6 py-16 text-center">
    <div class="flex h-16 w-16 items-center justify-center rounded-3xl bg-[#cf3d3d]/10 text-[#cf3d3d]">
      <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
      </svg>
    </div>
    <h3 class="mt-5 text-base font-bold text-slate-800">{{ title }}</h3>
    <p v-if="description" class="mt-1 max-w-sm text-sm font-medium text-slate-500">{{ description }}</p>
    <div v-if="$slots.action" class="mt-6">
      <slot name="action" />
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: { type: String, default: 'Belum ada data' },
  description: { type: String, default: '' },
})
</script>
