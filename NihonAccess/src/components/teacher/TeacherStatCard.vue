<template>
  <Card class="p-5 sm:p-6">
    <div class="flex items-start justify-between">
      <div class="min-w-0">
        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">{{ label }}</p>
        <p class="mt-2 text-3xl font-extrabold tracking-tight text-slate-800 tabular-nums">{{ value }}</p>
        <p v-if="subtitle" class="mt-1 text-xs text-slate-400">{{ subtitle }}</p>
      </div>
      <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl" :class="iconBg">
        <span :class="iconColor" v-html="icon"></span>
      </div>
    </div>
  </Card>
</template>

<script setup>
import Card from '@/components/ui/Card.vue'

defineProps({
  label: { type: String, required: true },
  value: { type: [String, Number], required: true },
  subtitle: { type: String, default: '' },
  icon: { type: String, default: '' },
  iconBg: { type: String, default: 'bg-[#cf3d3d]/10' },
  iconColor: { type: String, default: 'text-[#cf3d3d]' },
})
</script>
