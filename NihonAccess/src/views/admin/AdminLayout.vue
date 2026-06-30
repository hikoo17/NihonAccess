<template>
  <div class="min-h-screen bg-slate-50/50 font-sans text-slate-900 antialiased">
    <!-- Sidebar -->
    <AdminSidebar :is-collapsed="isCollapsed" @toggle="toggleSidebar" @navigate="onNavigate" />

    <!-- Backdrop (mobile only, saat melebar) -->
    <div
      v-if="!isCollapsed"
      class="fixed inset-0 z-30 bg-slate-900/40 backdrop-blur-sm lg:hidden"
      @click="isCollapsed = true"
    />

    <!-- Konten utama: padding kiri dinamis -->
    <div
      class="transition-all duration-300 ease-in-out pl-20"
      :class="isCollapsed ? 'lg:pl-20' : 'lg:pl-64'"
    >
      <AdminTopbar />

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
import { ref, onMounted, onUnmounted } from 'vue'
import AdminSidebar from '@/components/admin/AdminSidebar.vue'
import AdminTopbar from '@/components/admin/AdminTopbar.vue'

const DESKTOP_BREAKPOINT = 1024

// isCollapsed: true = menyempit (logo+ikon), false = melebar (full)
const isCollapsed = ref(false)

const toggleSidebar = () => {
  isCollapsed.value = !isCollapsed.value
}

// Klik menu di mobile → otomatis menyempit balik
const onNavigate = () => {
  if (typeof window !== 'undefined' && window.innerWidth < DESKTOP_BREAKPOINT) {
    isCollapsed.value = true
  }
}

const handleResize = () => {
  if (window.innerWidth < DESKTOP_BREAKPOINT) {
    isCollapsed.value = true
  }
}

onMounted(() => {
  // Default: melebar di desktop, menyempit di mobile
  isCollapsed.value = window.innerWidth < DESKTOP_BREAKPOINT
  window.addEventListener('resize', handleResize)
})

onUnmounted(() => {
  window.removeEventListener('resize', handleResize)
})
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
