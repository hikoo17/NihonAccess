<template>
  <Card
    class="group relative cursor-pointer p-5 transition hover:-translate-y-0.5 hover:shadow-md sm:p-6"
    role="button"
    tabindex="0"
    @click="onClick"
    @keydown.enter="onClick"
  >
    <div class="flex items-start justify-between">
      <!-- Ikon dalam lingkaran aksen (kiri-atas) -->
      <div
        class="flex h-11 w-11 items-center justify-center rounded-full"
        :class="iconBg || 'bg-[#cf3d3d]/10'"
      >
        <span :class="iconColor || 'text-[#cf3d3d]'" v-html="icon"></span>
      </div>

      <!-- Chevron kanan-atas -->
      <svg
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        stroke-width="2.5"
        stroke="currentColor"
        class="h-4 w-4 text-slate-300 transition-colors group-hover:text-[#cf3d3d]"
      >
        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
      </svg>
    </div>

    <div class="mt-4">
      <p class="text-xs font-bold uppercase tracking-wider text-slate-400">{{ label }}</p>
      <p class="mt-1 text-3xl font-extrabold text-slate-800">{{ value }}</p>
      <p v-if="subtitle" class="mt-1 text-xs text-slate-400">{{ subtitle }}</p>
    </div>
  </Card>
</template>

<script setup>
import { useRouter } from 'vue-router'
import Card from '@/components/ui/Card.vue'

const props = defineProps({
  label: { type: String, required: true },
  value: { type: [String, Number], required: true },
  subtitle: { type: String, default: '' },
  icon: { type: String, default: '' },
  iconBg: { type: String, default: 'bg-[#cf3d3d]/10' },
  iconColor: { type: String, default: 'text-[#cf3d3d]' },
  to: { type: String, default: '' },
})

const router = useRouter()
const onClick = () => {
  if (props.to) router.push(props.to)
}
</script>