<template>
  <div
    class="animate-pulse bg-slate-200"
    :class="roundedClass"
    :style="dimStyle"
  />
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  width: { type: String, default: '100%' },
  height: { type: String, default: '0.875rem' },
  rounded: { type: String, default: 'lg' },
})

const roundedMap = {
  none: 'rounded-none',
  sm: 'rounded-sm',
  md: 'rounded-md',
  lg: 'rounded-lg',
  xl: 'rounded-xl',
  '2xl': 'rounded-2xl',
  '3xl': 'rounded-3xl',
  full: 'rounded-full',
}

const roundedClass = computed(() => roundedMap[props.rounded] || 'rounded-lg')
const dimStyle = computed(() => ({
  width: props.width,
  height: props.height,
}))
</script>
