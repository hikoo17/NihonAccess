<template>
  <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div class="min-w-0">
      <button
        v-if="backTo"
        type="button"
        @click="goBack"
        class="mb-2 inline-flex items-center gap-1.5 text-sm font-semibold text-slate-400 transition-colors hover:text-[#cf3d3d]"
      >
        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
        Kembali
      </button>
      <span v-if="eyebrow" class="inline-flex items-center gap-3 text-xs font-extrabold uppercase tracking-[0.22em] text-[#cf3d3d]">
        <span class="h-px w-8 bg-[#cf3d3d]"></span>{{ eyebrow }}
      </span>
      <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900">{{ title }}</h1>
      <p v-if="subtitle" class="mt-1 text-sm font-medium text-slate-500">{{ subtitle }}</p>
    </div>
    <div v-if="$slots.actions" class="flex shrink-0 flex-wrap items-center gap-3">
      <slot name="actions" />
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'
const props = defineProps({
  title: { type: String, required: true },
  subtitle: { type: String, default: '' },
  eyebrow: { type: String, default: '' },
  backTo: { type: [String, Object], default: null },
})
const router = useRouter()
const goBack = () => {
  if (props.backTo) router.push(typeof props.backTo === 'string' ? { name: props.backTo } : props.backTo)
  else router.back()
}
</script>
