<template>
  <header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-slate-100 bg-white/80 px-6 backdrop-blur-md sm:px-8">
    <!-- Left: logo (mobile) / title (desktop) -->
    <div class="flex items-center gap-3">
      <img src="/logo.png" alt="Nihon Access" class="h-7 w-auto lg:hidden" />
      <h2 class="hidden text-base font-extrabold text-slate-800 lg:block">{{ pageTitle }}</h2>
    </div>

    <!-- Right: notif + profile -->
    <div class="flex items-center gap-3">
      <button class="relative inline-flex items-center justify-center rounded-xl p-2 text-slate-500 transition hover:bg-slate-100 hover:text-[#cf3d3d]">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
        </svg>
        <span class="absolute right-1.5 top-1.5 h-2 w-2 rounded-full bg-[#cf3d3d] ring-2 ring-white"></span>
      </button>

      <!-- Logout (mobile only, di desktop tersedia di sidebar) -->
      <button
        @click="logout"
        class="inline-flex items-center justify-center rounded-xl p-2 text-slate-500 transition hover:bg-slate-100 hover:text-[#cf3d3d] lg:hidden"
        title="Logout"
      >
        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
        </svg>
      </button>

      <div class="flex items-center gap-3 rounded-2xl border border-slate-100 bg-white py-1.5 pl-1.5 pr-4 shadow-sm">
        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-[#cf3d3d] to-[#b03333] text-sm font-extrabold text-white">
          {{ initials }}
        </div>
        <div class="hidden sm:block">
          <p class="text-xs font-bold leading-tight text-slate-800">{{ userName }}</p>
          <p class="text-[10px] text-slate-400">Administrator</p>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()
const pageTitle = computed(() => route.meta.titlePrefix || route.meta.title || 'Panel Admin')

const userName = computed(() => {
  try {
    const data = JSON.parse(localStorage.getItem('user_data') || '{}')
    return data.name || 'Admin'
  } catch {
    return 'Admin'
  }
})

const initials = computed(() => {
  const name = userName.value
  return name.split(' ').map(w => w[0]).slice(0, 2).join('').toUpperCase()
})

const logout = () => {
  localStorage.removeItem('auth_token')
  localStorage.removeItem('user_role')
  localStorage.removeItem('user_data')
  router.push('/login')
}
</script>
