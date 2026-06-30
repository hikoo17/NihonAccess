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

      <template v-for="item in menuItems" :key="item.label">
        <RouterLink
          v-if="!item.disabled"
          :to="{ name: item.routeName }"
          class="nav-item"
          :class="isCollapsed ? 'nav-item-collapsed' : 'nav-item-expanded'"
          :title="isCollapsed ? item.label : undefined"
          @click="$emit('navigate')"
        >
          <span class="nav-icon" v-html="item.icon" />
          <span v-if="!isCollapsed">{{ item.label }}</span>
        </RouterLink>

        <button
          v-else
          type="button"
          disabled
          class="nav-item nav-item-disabled"
          :class="isCollapsed ? 'nav-item-collapsed' : 'nav-item-expanded'"
          :title="isCollapsed ? item.label : undefined"
        >
          <span class="nav-icon" v-html="item.icon" />
          <span v-if="!isCollapsed">{{ item.label }}</span>
          <span v-if="!isCollapsed" class="ml-auto rounded bg-slate-100 px-1.5 py-0.5 text-[9px] font-bold text-slate-400">Soon</span>
        </button>
      </template>
    </nav>

    <!-- User card (hanya saat melebar) -->
    <div v-if="!isCollapsed" class="absolute bottom-4 left-4 right-4">
      <div class="flex items-center gap-3 rounded-2xl border border-slate-100 bg-white p-3 shadow-sm">
        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[#cf3d3d] to-[#b03333] text-xs font-extrabold text-white">
          {{ initials }}
        </div>
        <div class="min-w-0 flex-1">
          <p class="truncate text-xs font-bold text-slate-800">{{ userName }}</p>
          <p class="text-[10px] text-slate-400">Administrator</p>
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
const iconUsers = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.244m.972-4.346h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V11.75zm0-2.25h.008v.008h-.008v-.008zm-9.75 0h.008v.008H4.5v-.008zm0 2.25h.008v.008H4.5V11.75zm0-2.25h.008v.008H4.5v-.008zm9.75 0h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V11.75z" /></svg>`
const iconPackage = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 109.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1114.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>`
const iconOrder = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" /></svg>`
const iconSettings = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 010 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.241.437-.613.43-.992a7.723 7.723 0 010-.255c.007-.378-.138-.75-.43-.991l-1.004-.827a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.281z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>`

const menuItems = [
  { label: 'Dashboard', routeName: 'AdminDashboard', icon: iconDashboard },
  { label: 'Manajemen User', routeName: 'AdminUsers', icon: iconUsers, disabled: true },
  { label: 'Manajemen Paket', routeName: 'AdminPackages', icon: iconPackage, disabled: true },
  { label: 'Pesanan & Pembayaran', routeName: 'AdminOrders', icon: iconOrder, disabled: true },
  { label: 'Pengaturan', routeName: 'AdminSettings', icon: iconSettings, disabled: true },
]

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
.nav-item-disabled {
  opacity: 0.45;
  cursor: not-allowed;
}
.nav-item-disabled:hover {
  background-color: transparent;
  color: #64748b;
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
