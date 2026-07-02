<!-- src/components/teacher/ui/TeacherToggle.vue -->
<!-- Saklar aktif/nonaktif. Status dibaca dari modelValue; klik memancarkan `toggle`. -->
<template>
  <button
    type="button"
    role="switch"
    :aria-checked="modelValue ? 'true' : 'false'"
    :disabled="disabled || loading"
    :title="modelValue ? 'Klik untuk menonaktifkan' : 'Klik untuk mengaktifkan'"
    class="relative inline-flex h-6 w-11 shrink-0 items-center rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-[#cf3d3d]/30 disabled:cursor-not-allowed disabled:opacity-60"
    :class="modelValue ? 'bg-emerald-500' : 'bg-slate-300'"
    @click="onClick"
  >
    <span
      class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-white shadow ring-0 transition-transform duration-200"
      :class="modelValue ? 'translate-x-5' : 'translate-x-0.5'"
    >
      <svg v-if="loading" class="h-3 w-3 animate-spin text-slate-400" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
      </svg>
    </span>
  </button>
</template>

<script setup>
const props = defineProps({
  modelValue: { type: Boolean, default: false },
  loading: { type: Boolean, default: false },
  disabled: { type: Boolean, default: false },
})
const emit = defineEmits(['toggle'])
const onClick = () => {
  if (props.disabled || props.loading) return
  emit('toggle')
}
</script>
