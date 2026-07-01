<template>
  <aside
    class="fixed inset-y-0 left-0 z-40 border-r border-slate-100 bg-white transition-all duration-300 ease-in-out"
    :class="isCollapsed ? 'w-20' : 'w-64'"
  >
    <!-- Header: logo -->
    <div
      class="flex h-16 items-center border-b border-slate-100"
      :class="isCollapsed ? 'justify-center px-2' : 'px-6'"
    >
      <img src="/logo.png" alt="Nihon Access" class="h-7 w-auto shrink-0" />
    </div>

    <!-- Toggle button (floating di tepi kanan) -->
    <button
      @click="$emit('toggle')"
      class="absolute -right-3 top-20 flex h-6 w-6 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:border-[#cf3d3d] hover:bg-[#cf3d3d] hover:text-white"
      :aria-label="isCollapsed ? 'Lebarkan menu' : 'Persempit menu'"
    >
      <!-- Ikon » saat menyempit (klik untuk lebarkan) -->
      <svg v-if="isCollapsed" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4">
        <path stroke-linecap="round" stroke-linejoin="round" d="m5.25 4.5 7.5 7.5-7.5 7.5m6-15 7.5 7.5-7.5 7.5" />
      </svg>
      <!-- Ikon « saat melebar (klik untuk sempit) -->
      <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4">
        <path stroke-linecap="round" stroke-linejoin="round" d="M18.75 19.5l-7.5-7.5 7.5-7.5m-6 15L5.25 12l7.5-7.5" />
      </svg>
    </button>

    <!-- Navigation -->
    <nav class="flex flex-col gap-1 p-3" :class="isCollapsed ? 'items-center' : 'p-4'">
      <p
        v-if="!isCollapsed"
        class="px-3 pb-2 pt-3 text-[10px] font-extrabold uppercase tracking-wider text-slate-400"
      >
        Menu Utama
      </p>
      <RouterLink
        v-for="item in mainMenu"
        :key="item.name"
        :to="item.to"
        class="nav-item"
        :class="isCollapsed ? 'nav-item-collapsed' : 'nav-item-expanded'"
        :title="isCollapsed ? item.label : undefined"
        @click="$emit('navigate')"
      >
        <span class="nav-icon" v-html="item.icon" />
        <span v-if="!isCollapsed">{{ item.label }}</span>
      </RouterLink>

      <p
        v-if="!isCollapsed"
        class="px-3 pb-2 pt-5 text-[10px] font-extrabold uppercase tracking-wider text-slate-400"
      >
        Akun
      </p>
      <RouterLink
        v-for="item in accountMenu"
        :key="item.name"
        :to="item.to"
        class="nav-item"
        :class="isCollapsed ? 'nav-item-collapsed' : 'nav-item-expanded'"
        :title="isCollapsed ? item.label : undefined"
        @click="$emit('navigate')"
      >
        <span class="nav-icon" v-html="item.icon" />
        <span v-if="!isCollapsed">{{ item.label }}</span>
      </RouterLink>
    </nav>

    <!-- Card upgrade (hanya saat melebar) -->
    <div v-if="!isCollapsed" class="absolute bottom-4 left-4 right-4">
      <div class="rounded-2xl bg-gradient-to-br from-[#cf3d3d] to-[#b03333] p-4 text-white shadow-lg shadow-[#cf3d3d]/20">
        <p class="text-sm font-extrabold">Mau akses lebih banyak?</p>
        <p class="mt-1 text-xs text-white/80">Upgrade paket kamu untuk fitur premium.</p>
        <RouterLink to="/client/packages" class="mt-3 block rounded-xl bg-white/20 px-3 py-2 text-center text-xs font-bold backdrop-blur transition hover:bg-white/30">
          Lihat Paket
        </RouterLink>
      </div>
    </div>
  </aside>
</template>

<script setup>
defineProps({ isCollapsed: Boolean })
defineEmits(['toggle', 'navigate'])

const iconDashboard = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.949c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.5a.75.75 0 00.75.75h4.5a.75.75 0 00.75-.75V15a.75.75 0 01.75-.75h3a.75.75 0 01.75.75v5.25a.75.75 0 00.75.75h4.5a.75.75 0 00.75-.75V9.75M8.25 21h8.25" /></svg>`
const iconCourses = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>`
const iconQuiz = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-6 w-6"><path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm3.75-3h3.75m-3.75 3h3.75m-3.75 3h3.75" /></svg>`
const iconOrders = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" /></svg>`
const iconProfile = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.648 0-5.195-.429-7.499-1.182z" /></svg>`

const mainMenu = [
  { name: 'dashboard', to: '/client/dashboard', label: 'Dashboard', icon: iconDashboard },
  { name: 'courses', to: '/client/my-courses', label: 'Kursus Saya', icon: iconCourses },
  { name: 'quiz', to: '/client/quiz', label: 'Quiz', icon: iconQuiz },
]

const accountMenu = [
  { name: 'profile', to: '/client/profile', label: 'Profil Saya', icon: iconProfile },
]
</script>

<style scoped>
.nav-item {
  display: flex;
  align-items: center;
  border-radius: 0.75rem;
  font-size: 0.875rem;
  font-weight: 700;
  color: #64748b;
  transition: all 0.2s ease;
}
.nav-item-expanded {
  gap: 0.75rem;
  padding: 0.7rem 0.85rem;
}
.nav-item-collapsed {
  justify-content: center;
  width: 3rem;
  height: 3rem;
  margin: 0 auto;
}
.nav-item:hover {
  background-color: #f8fafc;
  color: #cf3d3d;
}
.nav-item.router-link-active {
  background-color: #cf3d3d;
  color: #ffffff;
  box-shadow: 0 4px 12px rgba(207, 61, 61, 0.25);
}
.nav-item.router-link-active :deep(.nav-icon svg) {
  color: #ffffff;
}
.nav-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  color: #94a3b8;
  transition: color 0.2s ease;
}
.nav-item:hover .nav-icon {
  color: #cf3d3d;
}
</style>