<template>
  <div class="min-h-screen bg-slate-50/50 font-sans text-slate-900 antialiased">
    <!-- Sidebar (desktop only) -->
    <ClientSidebar
      class="hidden lg:block"
      :is-collapsed="isCollapsed"
      @toggle="toggleSidebar"
    />

    <!-- Konten utama: padding kiri hanya di desktop -->
    <div
      class="transition-all duration-300 ease-in-out"
      :class="isCollapsed ? 'lg:pl-20' : 'lg:pl-64'"
    >
      <ClientTopbar />

      <main class="px-6 pt-8 pb-28 sm:px-8 lg:px-10 lg:pb-8">
        <router-view v-slot="{ Component }">
          <transition name="fade" mode="out-in">
            <component :is="Component" />
          </transition>
        </router-view>
      </main>
    </div>

    <!-- Bottom navigation (mobile only) -->
    <ClientBottomNav class="lg:hidden" />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import ClientSidebar from '@/components/Client/ClientSidebar.vue'
import ClientTopbar from '@/components/Client/ClientTopbar.vue'
import ClientBottomNav from '@/components/Client/ClientBottomNav.vue'

// Hanya relevan di desktop (mobile pakai bottom nav)
const isCollapsed = ref(false)

const toggleSidebar = () => {
  isCollapsed.value = !isCollapsed.value
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>