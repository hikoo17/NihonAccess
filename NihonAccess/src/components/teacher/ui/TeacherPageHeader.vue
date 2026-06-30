<template>
  <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div class="min-w-0">
      <Breadcrumb :items="breadcrumbItems" />
      <h1 class="mt-3 text-2xl font-extrabold tracking-tight text-slate-800 sm:text-3xl">{{ title }}</h1>
      <p v-if="subtitle" class="mt-1 text-sm text-slate-500">{{ subtitle }}</p>
    </div>
    <div v-if="$slots.actions" class="flex shrink-0 flex-wrap items-center gap-3">
      <slot name="actions" />
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import Breadcrumb from '@/components/ui/Breadcrumb.vue'

const props = defineProps({
  title: { type: String, required: true },
  subtitle: { type: String, default: '' },
  eyebrow: { type: String, default: '' },
})

const breadcrumbItems = computed(() => {
  const items = [{ label: 'Dashboard', to: { name: 'TeacherDashboard' } }]
  if (props.eyebrow && props.eyebrow !== props.title) {
    items.push({ label: props.eyebrow })
  }
  items.push({ label: props.title })
  return items
})
</script>
