<template>
  <span
    :class="[
      'inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-bold',
      active ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700',
    ]"
  >
    <span :class="['h-1.5 w-1.5 rounded-full', active ? 'bg-emerald-500' : 'bg-amber-500']"></span>
    {{ active ? 'Aktif' : 'Nonaktif' }}
  </span>
</template>

<script setup>
defineProps({
  active: { type: Boolean, default: false },
})
</script>
