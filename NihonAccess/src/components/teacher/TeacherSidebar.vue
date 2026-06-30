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
        Menu
      </p>

      <RouterLink
        v-for="item in menuItems"
        :key="item.routeName"
        :to="{ name: item.routeName }"
        class="nav-item"
        :class="isCollapsed ? 'nav-item-collapsed' : 'nav-item-expanded'"
        :title="isCollapsed ? item.label : undefined"
        @click="$emit('navigate')"
      >
        <span class="nav-icon" v-html="item.icon" />
        <span v-if="!isCollapsed">{{ item.label }}</span>
      </RouterLink>
    </nav>

    <!-- User card (hanya saat melebar) -->
    <div v-if="!isCollapsed" class="absolute bottom-4 left-4 right-4">
      <div class="flex items-center gap-3 rounded-2xl border border-slate-100 bg-white p-3 shadow-sm">
        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[#cf3d3d] to-[#b03333] text-xs font-extrabold text-white">
          {{ initials }}
        </div>
        <div class="min-w-0 flex-1">
          <p class="truncate text-xs font-bold text-slate-800">{{ userName }}</p>
          <p class="text-[10px] text-slate-400">Teacher</p>
        </div>
        <button @click="logout" class="text-slate-400 transition-colors hover:text-[#cf3d3d]" title="Logout">
          <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
          </svg>
        </button>
      </div>
    </div>
  </aside>
</template>

<script setup>
import { computed } from 'vue'
import { useRouter } from 'vue-router'

defineProps({ isCollapsed: Boolean })
defineEmits(['toggle', 'navigate'])

const router = useRouter()

const iconDashboard = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>`
const iconCourse = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>`
const iconQuiz = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" /></svg>`
const iconCharacter = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>`
const iconListening = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" /></svg>`

const menuItems = [
  { label: 'Dashboard', routeName: 'TeacherDashboard', icon: iconDashboard },
  { label: 'Course & Lesson', routeName: 'TeacherCourses', icon: iconCourse },
  { label: 'Quiz & Soal', routeName: 'TeacherQuiz', icon: iconQuiz },
  // { label: 'Tebak Huruf', routeName: 'TeacherCharacters', icon: iconCharacter },
  { label: 'Listening', routeName: 'TeacherListening', icon: iconListening },
]

const userName = computed(() => {
  try {
    const data = JSON.parse(localStorage.getItem('user_data') || '{}')
    return data.name || 'Guru'
  } catch {
    return 'Guru'
  }
})

const initials = computed(() => {
  const name = userName.value
  return name.split(' ').map((w) => w[0]).slice(0, 2).join('').toUpperCase()
})

const logout = () => {
  localStorage.removeItem('auth_token')
  localStorage.removeItem('user_role')
  localStorage.removeItem('user_data')
  router.push('/login')
}
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
