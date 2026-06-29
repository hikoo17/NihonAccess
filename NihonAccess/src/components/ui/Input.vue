<template>
  <input
    :value="modelValue"
    class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 transition placeholder:text-slate-400 focus:border-[#cf3d3d]/40 focus:outline-none focus:ring-2 focus:ring-[#cf3d3d]/20"
    @input="$emit('update:modelValue', $event.target.value)"
  />
</template>

<script setup>
defineProps({
  modelValue: { type: [String, Number], default: '' }
})

defineEmits(['update:modelValue'])
</script>
