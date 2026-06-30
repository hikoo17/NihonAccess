<template>
  <div v-if="meta && meta.last_page > 1" class="flex flex-col items-center gap-4 border-t border-slate-100 px-6 py-4 sm:flex-row sm:justify-between">
    <p class="text-xs font-medium text-slate-500">
      Menampilkan <span class="font-bold text-slate-700">{{ rangeFrom }}</span>–<span class="font-bold text-slate-700">{{ rangeTo }}</span> dari
      <span class="font-bold text-slate-700">{{ meta.total }}</span> data
    </p>
    <div class="flex items-center gap-1.5">
      <button
        :disabled="meta.current_page <= 1"
        @click="$emit('change', meta.current_page - 1)"
        class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 transition-colors hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40"
      >
        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
      </button>
      <template v-for="p in pages" :key="p">
        <span v-if="p === '...'" class="px-2 text-sm font-semibold text-slate-400">…</span>
        <button
          v-else
          @click="$emit('change', p)"
          :class="[
            'h-9 min-w-9 rounded-xl px-3 text-sm font-bold transition-colors',
            p === meta.current_page ? 'bg-[#cf3d3d] text-white shadow-md shadow-[#cf3d3d]/20' : 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50',
          ]"
        >
          {{ p }}
        </button>
      </template>
      <button
        :disabled="meta.current_page >= meta.last_page"
        @click="$emit('change', meta.current_page + 1)"
        class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 transition-colors hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40"
      >
        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
      </button>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
const props = defineProps({
  meta: { type: Object, default: null },
})
defineEmits(['change'])

const perPage = computed(() => props.meta?.per_page || 15)
const rangeFrom = computed(() => ((props.meta?.current_page - 1) * perPage.value) + 1)
const rangeTo = computed(() => Math.min(props.meta?.current_page * perPage.value, props.meta?.total || 0))

const pages = computed(() => {
  const last = props.meta?.last_page || 1
  const cur = props.meta?.current_page || 1
  const out = []
  if (last <= 7) {
    for (let i = 1; i <= last; i++) out.push(i)
    return out
  }
  out.push(1)
  if (cur > 3) out.push('...')
  for (let i = Math.max(2, cur - 1); i <= Math.min(last - 1, cur + 1); i++) out.push(i)
  if (cur < last - 2) out.push('...')
  out.push(last)
  return out
})
</script>
