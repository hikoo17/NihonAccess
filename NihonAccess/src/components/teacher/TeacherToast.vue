<template>
  <Teleport to="body">
    <div class="fixed top-5 right-5 z-[100] flex flex-col gap-2.5 w-[min(92vw,360px)]">
      <transition-group name="toast">
        <div
          v-for="t in toasts"
          :key="t.id"
          :class="[
            'flex items-start gap-3 rounded-2xl border px-4 py-3 shadow-lg backdrop-blur',
            t.type === 'success' ? 'bg-white border-emerald-100' : '',
            t.type === 'error' ? 'bg-white border-[#cf3d3d]/20' : '',
            t.type === 'info' ? 'bg-white border-slate-200' : '',
          ]"
        >
          <span
            :class="[
              'mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-white',
              t.type === 'success' ? 'bg-emerald-500' : '',
              t.type === 'error' ? 'bg-[#cf3d3d]' : '',
              t.type === 'info' ? 'bg-slate-500' : '',
            ]"
          >
            <svg v-if="t.type === 'success'" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
            <svg v-else class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
          </span>
          <p class="flex-1 text-sm font-medium text-slate-700 leading-snug">{{ t.message }}</p>
          <button @click="dismiss(t.id)" class="text-slate-300 hover:text-slate-500 transition-colors">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
          </button>
        </div>
      </transition-group>
    </div>
  </Teleport>
</template>

<script setup>
import { useTeacherToast } from '@/composables/useTeacherToast'
const { toasts, dismiss } = useTeacherToast()
</script>

<style scoped>
.toast-enter-active,
.toast-leave-active {
  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}
.toast-enter-from {
  opacity: 0;
  transform: translateX(120%);
}
.toast-leave-to {
  opacity: 0;
  transform: translateX(120%);
}
</style>
