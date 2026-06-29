<template>
  <div class="min-h-screen bg-slate-50/50 font-sans text-slate-900 antialiased">
    <!-- Sidebar -->
    <ClientSidebar :is-open="isSidebarOpen" @close="isSidebarOpen = false" />

    <!-- Overlay mobile -->
    <div
      v-if="isSidebarOpen"
      class="fixed inset-0 z-30 bg-slate-900/40 backdrop-blur-sm lg:hidden"
      @click="isSidebarOpen = false"
    />

    <!-- Konten utama -->
    <div class="lg:pl-64">
      <ClientTopbar @toggle-sidebar="isSidebarOpen = !isSidebarOpen" />

      <main class="px-6 py-8 sm:px-8 lg:px-10">
        <router-view v-slot="{ Component }">
          <transition name="fade" mode="out-in">
            <component :is="Component" />
          </transition>
        </router-view>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import ClientSidebar from '@/components/Client/ClientSidebar.vue'
import ClientTopbar from '@/components/Client/ClientTopbar.vue'

const isSidebarOpen = ref(false)
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