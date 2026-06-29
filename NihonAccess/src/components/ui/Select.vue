<template>
  <select
    :value="modelValue"
    class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 transition focus:border-[#cf3d3d]/40 focus:outline-none focus:ring-2 focus:ring-[#cf3d3d]/20"
    @change="$emit('update:modelValue', $event.target.value)"
  >
    <slot />
  </select>
</template>

<script setup>
defineProps({
  modelValue: { type: [String, Number], default: '' }
})

defineEmits(['update:modelValue'])
</script>
