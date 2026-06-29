<template>
  <span :class="[baseClass, variantClass, sizeClass]">
    <span v-if="dot" class="h-1.5 w-1.5 rounded-full" :class="dotClass" />
    <slot />
  </span>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  variant: { type: String, default: 'neutral' },
  size: { type: String, default: 'default' },
  dot: { type: Boolean, default: false },
})

const baseClass = 'inline-flex items-center gap-1.5 rounded-full font-bold whitespace-nowrap'

const variants = {
  neutral: 'bg-slate-100 text-slate-600',
  success: 'bg-emerald-100 text-emerald-700',
  warning: 'bg-amber-100 text-amber-700',
  danger: 'bg-[#cf3d3d]/10 text-[#cf3d3d]',
  info: 'bg-blue-100 text-blue-700',
}

const dotVariants = {
  neutral: 'bg-slate-400',
  success: 'bg-emerald-500',
  warning: 'bg-amber-500',
  danger: 'bg-[#cf3d3d]',
  info: 'bg-blue-500',
}

const sizes = {
  default: 'px-3 py-1 text-xs',
  sm: 'px-2 py-0.5 text-[10px]',
}

const variantClass = computed(() => variants[props.variant] || variants.neutral)
const dotClass = computed(() => dotVariants[props.variant] || dotVariants.neutral)
const sizeClass = computed(() => sizes[props.size] || sizes.default)
</script>