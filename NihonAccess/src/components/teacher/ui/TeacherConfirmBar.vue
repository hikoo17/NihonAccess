<!-- src/components/teacher/ui/TeacherConfirmBar.vue -->
<template>
  <div
    v-if="modelValue"
    class="flex items-center justify-between gap-4 border-t border-slate-100 bg-[#cf3d3d]/5 px-6 py-4"
  >
    <p class="text-sm font-semibold text-slate-700">
      <slot name="message">Hapus item ini?</slot>
    </p>
    <div class="flex gap-2">
      <Button variant="secondary" size="sm" @click="$emit('cancel')">Batal</Button>
      <Button size="sm" :disabled="deleting" @click="$emit('confirm')">
        {{ deleting ? 'Menghapus...' : 'Ya, Hapus' }}
      </Button>
    </div>
  </div>
</template>

<script setup>
import Button from '@/components/ui/Button.vue'

defineProps({
  modelValue: { default: null },
  deleting: { type: Boolean, default: false },
})
defineEmits(['cancel', 'confirm'])
</script>