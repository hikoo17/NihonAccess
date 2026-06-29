<template>
  <Card class="flex flex-col p-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-slate-200/60">
    <!-- Badge popular -->
    <div v-if="pkg.popular" class="mb-4">
      <Badge variant="danger" dot>🔥 Paling Populer</Badge>
    </div>

    <!-- Header -->
    <div class="mb-4">
      <h3 class="text-lg font-extrabold text-slate-800">{{ pkg.name }}</h3>
      <p class="mt-1 text-xs text-slate-400">{{ pkg.tagline }}</p>
    </div>

    <!-- Price -->
    <div class="mb-5 flex items-end gap-1">
      <span class="text-3xl font-extrabold text-[#cf3d3d]">{{ pkg.price }}</span>
      <span class="mb-1 text-xs text-slate-400">/{{ pkg.duration }}</span>
    </div>

    <!-- Features -->
    <ul class="mb-6 flex-1 space-y-3">
      <li v-for="(feature, i) in pkg.features" :key="i" class="flex items-start gap-2.5">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="mt-0.5 h-4 w-4 shrink-0 text-[#cf3d3d]">
          <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
        </svg>
        <span class="text-xs font-medium leading-relaxed text-slate-600">{{ feature }}</span>
      </li>
    </ul>

    <!-- CTA -->
    <RouterLink
      :to="`/client/packages/${pkg.slug}`"
      class="block rounded-2xl px-5 py-3 text-center text-sm font-bold transition-all"
      :class="pkg.popular
        ? 'bg-[#cf3d3d] text-white shadow-md shadow-[#cf3d3d]/10 hover:bg-[#b83232]'
        : 'border border-slate-200 bg-white text-[#cf3d3d] hover:border-[#cf3d3d]/40 hover:bg-slate-50'"
    >
      Lihat Detail
    </RouterLink>
  </Card>
</template>

<script setup>
import Card from '@/components/ui/Card.vue'
import Badge from '@/components/ui/Badge.vue'

defineProps({
  pkg: { type: Object, required: true },
})
</script>