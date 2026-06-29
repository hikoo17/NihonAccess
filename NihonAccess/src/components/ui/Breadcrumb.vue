<template>
  <nav aria-label="Breadcrumb" class="flex flex-wrap items-center gap-2 text-sm font-medium text-slate-500">
    <template v-for="(item, index) in items" :key="`${item.label}-${index}`">
      <RouterLink
        v-if="item.to && index < items.length - 1"
        :to="item.to"
        class="transition-colors hover:text-[#cf3d3d]"
      >
        {{ item.label }}
      </RouterLink>
      <span v-else class="text-slate-800" :aria-current="index === items.length - 1 ? 'page' : undefined">
        {{ item.label }}
      </span>
      <svg
        v-if="index < items.length - 1"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
        fill="currentColor"
        class="h-4 w-4 text-slate-300"
        aria-hidden="true"
      >
        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 0 1 .02-1.06L11.168 10 7.23 6.29a.75.75 0 1 1 1.04-1.08l4.5 4.25a.75.75 0 0 1 0 1.08l-4.5 4.25a.75.75 0 0 1-1.06-.02Z" clip-rule="evenodd" />
      </svg>
    </template>
  </nav>
</template>

<script setup>
defineProps({
  items: { type: Array, required: true }
})
</script>
