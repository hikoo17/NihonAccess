<template>
  <div class="flex min-h-screen bg-[#121212] text-zinc-100 font-sans antialiased">
    <TeacherSidebar />

    <div class="flex-1 flex flex-col min-w-0">
      <TeacherTopbar :title="currentRouteTitle" />

      <main class="flex-1 overflow-y-auto p-8">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';
import TeacherSidebar from '@/components/teacher/TeacherSidebar.vue';
import TeacherTopbar from '@/components/teacher/TeacherTopbar.vue';

const route = useRoute()

// Mengambil title dinamis dari meta route untuk dikirim ke Topbar
const currentRouteTitle = computed(() => {
  return route.meta.titlePrefix || route.meta.title || 'Panel Guru'
})
</script>