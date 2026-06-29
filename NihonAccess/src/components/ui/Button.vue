<template>
  <button :class="[baseClass, variantClass, sizeClass]">
    <slot />
  </button>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  variant: { type: String, default: 'default' },
  size: { type: String, default: 'default' }
})

const baseClass = 'inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-2xl text-sm font-bold transition-all duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#cf3d3d]/30 disabled:pointer-events-none disabled:opacity-50 active:scale-[0.99]'

const variants = {
  default: 'bg-[#cf3d3d] text-white shadow-md shadow-[#cf3d3d]/10 hover:bg-[#b83232] hover:shadow-lg hover:shadow-[#cf3d3d]/20',
  outline: 'border border-slate-200 bg-white text-[#cf3d3d] hover:border-[#cf3d3d]/40 hover:bg-slate-50',
  secondary: 'bg-slate-100 text-slate-700 hover:bg-slate-200',
  ghost: 'text-slate-500 hover:bg-slate-100 hover:text-[#cf3d3d]'
}

const sizes = {
  default: 'h-12 px-5 py-3',
  sm: 'h-9 rounded-xl px-3',
  lg: 'h-14 px-8 text-base',
  icon: 'h-10 w-10 rounded-xl'
}

const variantClass = computed(() => variants[props.variant] || variants.default)
const sizeClass = computed(() => sizes[props.size] || sizes.default)
</script>
