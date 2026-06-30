<template>
  <div class="flex min-h-screen bg-slate-50/50 font-sans text-slate-900 antialiased">
    <TeacherSidebar />

    <div class="flex min-w-0 flex-1 flex-col">
      <TeacherTopbar :title="currentRouteTitle" />

      <main class="flex-1 overflow-y-auto px-6 py-8">
        <router-view />
      </main>
    </div>

    <TeacherToast />
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import TeacherSidebar from '@/components/teacher/TeacherSidebar.vue'
import TeacherTopbar from '@/components/teacher/TeacherTopbar.vue'
import TeacherToast from '@/components/teacher/TeacherToast.vue'

const route = useRoute()

const currentRouteTitle = computed(() => {
  const raw = route.meta.titlePrefix || route.meta.title || 'Panel Guru'
  return raw.replace(/\s*\|\s*Teacher.*$/i, '')
})
</script>
