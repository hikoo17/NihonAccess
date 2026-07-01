<template>
  <div class="p-6 rounded-[2rem] border border-slate-100 bg-white shadow-sm flex flex-col justify-between min-h-[150px]">
    <div class="flex items-center justify-between w-full mb-3">
      <Skeleton width="2.5rem" height="2.5rem" rounded="2xl" />
      <Skeleton width="2.75rem" height="1.25rem" rounded="full" />
    </div>
    <div class="space-y-2">
      <Skeleton width="45%" height="0.75rem" />
      <Skeleton width="60%" height="1.5rem" />
    </div>
  </div>
</template>

<script setup>
import Skeleton from '@/components/ui/Skeleton.vue'
</script>
