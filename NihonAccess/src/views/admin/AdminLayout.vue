<template>
  <div class="flex min-h-screen bg-[#121212] text-zinc-100 font-sans antialiased">
    <AdminSidebar />

    <div class="flex-1 flex flex-col min-w-0">
      <AdminTopbar :title="currentRouteTitle" />

      <main class="flex-1 overflow-y-auto p-8">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import AdminSidebar from '@/components/admin/AdminSidebar.vue'
import AdminTopbar from '@/components/admin/AdminTopbar.vue'

const route = useRoute()

const currentRouteTitle = computed(() => {
  return route.meta.titlePrefix || route.meta.title || 'Panel Admin'
})
</script>
