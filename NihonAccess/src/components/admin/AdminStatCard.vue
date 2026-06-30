<template>
  <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">{{ label }}</p>
    <p class="text-3xl font-extrabold mt-2 tracking-tight tabular-nums text-slate-800" :class="valueClass">{{ value }}</p>
  </div>
</template>

<script setup>
defineProps({
  label: { type: String, required: true },
  value: { type: [String, Number], required: true },
  valueClass: { type: String, default: 'text-slate-800' }
})
</script>
