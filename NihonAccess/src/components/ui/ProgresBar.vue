<template>
  <div class="w-full">
    <div class="flex items-center justify-between mb-1.5" v-if="showLabel">
      <span class="text-xs font-bold text-slate-500"><slot>Progress</slot></span>
      <span class="text-xs font-extrabold text-[#cf3d3d]">{{ percent }}%</span>
    </div>
    <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100">
      <div
        class="h-full rounded-full bg-gradient-to-r from-[#cf3d3d] to-[#e85555] transition-all duration-500"
        :style="{ width: `${clampedPercent}%` }"
      />
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  value: { type: Number, default: 0 },
  max: { type: Number, default: 100 },
  showLabel: { type: Boolean, default: true },
})

const percent = computed(() =>
  Math.round((props.value / props.max) * 100)
)
const clampedPercent = computed(() =>
  Math.min(100, Math.max(0, percent.value))
)
</script>