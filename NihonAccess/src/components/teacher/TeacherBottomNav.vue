<template>
  <nav
    class="fixed inset-x-0 bottom-0 z-40 border-t border-slate-200 bg-white/95 backdrop-blur-md lg:hidden"
    style="padding-bottom: max(0.375rem, env(safe-area-inset-bottom))"
    aria-label="Navigasi utama"
  >
    <div class="mx-auto flex max-w-md items-center justify-around px-1.5 pt-1.5">
      <RouterLink
        v-for="item in items"
        :key="item.routeName"
        :to="{ name: item.routeName }"
        class="nav-bottom-item"
      >
        <span class="nav-bottom-icon" v-html="item.icon" />
        <span class="mt-0.5 text-[10px] font-bold">{{ item.label }}</span>
      </RouterLink>

      <button class="nav-bottom-item" @click="logout">
        <span class="nav-bottom-icon" v-html="iconLogout" />
        <span class="mt-0.5 text-[10px] font-bold">Keluar</span>
      </button>
    </div>
  </nav>
</template>

<script setup>
import { useRouter } from 'vue-router'

const router = useRouter()

const iconDashboard = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>`
const iconCourse = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>`
const iconQuiz = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" /></svg>`
const iconListening = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" /></svg>`
const iconLogout = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" /></svg>`

const items = [
  { label: 'Beranda', routeName: 'TeacherDashboard', icon: iconDashboard },
  { label: 'Course', routeName: 'TeacherCourses', icon: iconCourse },
  { label: 'Quiz', routeName: 'TeacherQuiz', icon: iconQuiz },
  { label: 'Listening', routeName: 'TeacherListening', icon: iconListening },
]

const logout = () => {
  localStorage.removeItem('auth_token')
  localStorage.removeItem('user_role')
  localStorage.removeItem('user_data')
  router.push('/login')
}
</script>

<style scoped>
.nav-bottom-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  flex: 1 1 0;
  padding: 0.4rem 0.25rem;
  border-radius: 0.75rem;
  color: #94a3b8;
  transition: color 0.2s ease, background-color 0.2s ease;
}
.nav-bottom-item:hover { color: #cf3d3d; }
.nav-bottom-item.router-link-active { color: #cf3d3d; }
.nav-bottom-item.router-link-active .nav-bottom-icon { background-color: rgba(207, 61, 61, 0.1); }
.nav-bottom-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 2.25rem;
  height: 2.25rem;
  border-radius: 9999px;
  transition: background-color 0.2s ease;
}
</style>