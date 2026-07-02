<template>
  <div class="min-h-screen bg-slate-50/50 font-sans text-slate-900 antialiased">
    <!-- Sidebar (desktop only) -->
    <TeacherSidebar
      class="hidden lg:block"
      :is-collapsed="isCollapsed"
      @toggle="toggleSidebar"
    />

    <!-- Konten utama: padding kiri hanya di desktop -->
    <div
      class="transition-all duration-300 ease-in-out"
      :class="isCollapsed ? 'lg:pl-20' : 'lg:pl-64'"
    >
      <TeacherTopbar />

      <!-- pb-28 untuk akomodasi bottom nav di mobile -->
      <main class="px-6 pt-8 pb-28 sm:px-8 lg:px-10 lg:pb-8">
        <router-view v-slot="{ Component }">
          <transition name="fade" mode="out-in">
            <component :is="Component" />
          </transition>
        </router-view>
      </main>
    </div>

    <!-- Bottom navigation (mobile only) -->
    <TeacherBottomNav class="lg:hidden" />
    <TeacherToast />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import TeacherSidebar from '@/components/teacher/TeacherSidebar.vue'
import TeacherTopbar from '@/components/teacher/TeacherTopbar.vue'
import TeacherBottomNav from '@/components/teacher/TeacherBottomNav.vue'
import TeacherToast from '@/components/teacher/TeacherToast.vue'

const isCollapsed = ref(false)
const toggleSidebar = () => { isCollapsed.value = !isCollapsed.value }
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active { transition: opacity 0.2s ease; }
.fade-enter-from,
.fade-leave-to { opacity: 0; }
</style>