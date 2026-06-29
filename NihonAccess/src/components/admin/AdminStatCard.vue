<template>
  <div class="bg-[#1a1a1a] p-6 rounded-2xl border border-zinc-800/40 shadow-xl">
    <p class="text-xs font-semibold text-zinc-500 tracking-wide uppercase">{{ label }}</p>
    <p class="text-3xl font-bold mt-2 tracking-tight tabular-nums" :class="valueClass">{{ value }}</p>
  </div>
</template>

<script setup>
defineProps({
  label: { type: String, required: true },
  value: { type: [String, Number], required: true },
  valueClass: { type: String, default: 'text-zinc-100' }
})
</script>
