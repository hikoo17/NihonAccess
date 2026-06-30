<template>
  <aside class="flex min-h-screen w-64 shrink-0 flex-col justify-between border-r border-slate-200 bg-white">
    <div class="p-6">
      <div class="mb-8 flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-[#cf3d3d] text-white shadow-md shadow-[#cf3d3d]/20">
          <span class="text-lg font-black">N</span>
        </div>
        <div>
          <h1 class="text-base font-extrabold tracking-tight text-slate-900">Panel Guru</h1>
          <p class="text-xs font-medium text-slate-400">NihonAccess</p>
        </div>
      </div>

      <nav class="space-y-1">
        <p class="mb-3 px-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Menu</p>
        <router-link
          v-for="item in menuItems"
          :key="item.routeName"
          :to="{ name: item.routeName }"
          v-slot="{ isActive, navigate }"
          custom
        >
          <button
            @click="navigate"
            :class="[
              'group flex w-full items-center gap-3.5 rounded-xl px-3 py-2.5 text-left text-sm font-semibold transition-all duration-200',
              isActive ? 'bg-[#cf3d3d]/10 text-[#cf3d3d]' : 'text-slate-500 hover:bg-slate-100 hover:text-slate-900',
            ]"
          >
            <component
              :is="item.icon"
              :class="['h-5 w-5 transition-colors', isActive ? 'text-[#cf3d3d]' : 'text-slate-400 group-hover:text-slate-600']"
            />
            <span>{{ item.label }}</span>
          </button>
        </router-link>
      </nav>
    </div>

    <div class="flex items-center gap-3 border-t border-slate-100 bg-slate-50/50 p-4">
      <div class="flex h-9 w-9 items-center justify-center rounded-xl border border-[#cf3d3d]/30 bg-[#cf3d3d]/10 text-xs font-bold text-[#cf3d3d]">
        {{ initials }}
      </div>
      <div class="min-w-0 flex-1">
        <p class="truncate text-xs font-bold text-slate-800">{{ userName }}</p>
        <p class="text-[10px] font-medium text-slate-400">Teacher</p>
      </div>
      <button @click="logout" title="Logout" class="text-slate-400 transition-colors hover:text-[#cf3d3d]">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
        </svg>
      </button>
    </div>
  </aside>
</template>

<script setup>
import { shallowRef, h, computed } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const IconDashboard = () => ({
  render: () => h('svg', { fill: 'none', stroke: 'currentColor', 'stroke-width': '2', viewBox: '0 0 24 24' }, [
    h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z' }),
  ]),
})
const IconCourse = () => ({
  render: () => h('svg', { fill: 'none', stroke: 'currentColor', 'stroke-width': '2', viewBox: '0 0 24 24' }, [
    h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25' }),
  ]),
})
const IconQuiz = () => ({
  render: () => h('svg', { fill: 'none', stroke: 'currentColor', 'stroke-width': '2', viewBox: '0 0 24 24' }, [
    h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z' }),
  ]),
})
const IconCharacter = () => ({
  render: () => h('svg', { fill: 'none', stroke: 'currentColor', 'stroke-width': '2', viewBox: '0 0 24 24' }, [
    h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10' }),
  ]),
})
const IconListening = () => ({
  render: () => h('svg', { fill: 'none', stroke: 'currentColor', 'stroke-width': '2', viewBox: '0 0 24 24' }, [
    h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z' }),
  ]),
})
const IconWriting = () => ({
  render: () => h('svg', { fill: 'none', stroke: 'currentColor', 'stroke-width': '2', viewBox: '0 0 24 24' }, [
    h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10' }),
  ]),
})

const menuItems = shallowRef([
  { label: 'Dashboard', routeName: 'TeacherDashboard', icon: IconDashboard },
  { label: 'Course & Lesson', routeName: 'TeacherCourses', icon: IconCourse },
  { label: 'Quiz & Soal', routeName: 'TeacherQuiz', icon: IconQuiz },
  { label: 'Tebak Huruf', routeName: 'TeacherCharacters', icon: IconCharacter },
  { label: 'Listening', routeName: 'TeacherListening', icon: IconListening },
])

const userName = computed(() => {
  try {
    const data = JSON.parse(localStorage.getItem('user_data') || '{}')
    return data.name || 'Guru'
  } catch {
    return 'Guru'
  }
})

const initials = computed(() => {
  return userName.value.split(' ').map((w) => w[0]).slice(0, 2).join('').toUpperCase()
})

const logout = () => {
  localStorage.removeItem('auth_token')
  localStorage.removeItem('user_role')
  localStorage.removeItem('user_data')
  router.push('/login')
}
</script>
