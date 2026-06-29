<template>
  <aside class="w-64 bg-[#161616] border-r border-zinc-800/80 flex flex-col justify-between shrink-0 min-h-screen">
    <div class="p-6">
      <div class="mb-8">
        <h1 class="text-xl font-semibold tracking-tight text-zinc-200">Panel Admin</h1>
        <p class="text-xs text-zinc-500 font-medium">Nihon Access</p>
      </div>

      <nav class="space-y-1.5">
        <p class="text-[10px] font-bold text-zinc-600 tracking-wider uppercase mb-3 px-3">Menu</p>

        <router-link
          v-for="item in menuItems"
          :key="item.routeName"
          :to="item.disabled ? '' : { name: item.routeName }"
          v-slot="{ isActive }"
          :custom="item.disabled"
        >
          <button
            :disabled="item.disabled"
            :class="[
              'w-full flex items-center gap-3.5 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 text-left group',
              item.disabled ? 'opacity-30 cursor-not-allowed' : '',
              !item.disabled && isActive
                ? 'bg-[#cf3d3d]/15 text-[#cf3d3d] shadow-sm shadow-red-900/10'
                : 'text-zinc-400 hover:bg-zinc-800/40 hover:text-zinc-200'
            ]"
          >
            <component
              :is="item.icon"
              :class="['w-5 h-5 transition-colors', !item.disabled && isActive ? 'text-[#cf3d3d]' : 'text-zinc-500 group-hover:text-zinc-400']"
            />
            <span>{{ item.label }}</span>
            <span v-if="item.disabled" class="ml-auto text-[9px] bg-zinc-800 text-zinc-500 px-1.5 py-0.5 rounded">Soon</span>
          </button>
        </router-link>
      </nav>
    </div>

    <div class="p-4 border-t border-zinc-800/60 bg-zinc-900/20 flex items-center gap-3">
      <div class="w-9 h-9 rounded-xl bg-[#cf3d3d]/20 flex items-center justify-center border border-[#cf3d3d]/30 text-[#cf3d3d] font-semibold text-xs">
        {{ initials }}
      </div>
      <div class="truncate flex-1">
        <p class="text-xs font-semibold text-zinc-200 truncate">{{ userName }}</p>
        <p class="text-[10px] text-zinc-500 font-medium">Administrator</p>
      </div>
      <button @click="logout" class="text-zinc-500 hover:text-[#cf3d3d] transition-colors" title="Logout">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
    h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z' })
  ])
})
const IconUsers = () => ({
  render: () => h('svg', { fill: 'none', stroke: 'currentColor', 'stroke-width': '2', viewBox: '0 0 24 24' }, [
    h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.244m.972-4.346h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V11.75zm0-2.25h.008v.008h-.008v-.008zm-9.75 0h.008v.008H4.5v-.008zm0 2.25h.008v.008H4.5V11.75zm0-2.25h.008v.008H4.5v-.008zm9.75 0h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V11.75z' })
  ])
})
const IconPackage = () => ({
  render: () => h('svg', { fill: 'none', stroke: 'currentColor', 'stroke-width': '2', viewBox: '0 0 24 24' }, [
    h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M21 11.25v8.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 109.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1114.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z' })
  ])
})
const IconOrder = () => ({
  render: () => h('svg', { fill: 'none', stroke: 'currentColor', 'stroke-width': '2', viewBox: '0 0 24 24' }, [
    h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z' })
  ])
})
const IconSettings = () => ({
  render: () => h('svg', { fill: 'none', stroke: 'currentColor', 'stroke-width': '2', viewBox: '0 0 24 24' }, [
    h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 010 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.241.437-.613.43-.992a7.723 7.723 0 010-.255c.007-.378-.138-.75-.43-.991l-1.004-.827a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.281z' }),
    h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M15 12a3 3 0 11-6 0 3 3 0 016 0z' })
  ])
})

const menuItems = shallowRef([
  { label: 'Dashboard', routeName: 'AdminDashboard', icon: IconDashboard },
  { label: 'Manajemen User', routeName: 'AdminUsers', icon: IconUsers, disabled: true },
  { label: 'Manajemen Paket', routeName: 'AdminPackages', icon: IconPackage, disabled: true },
  { label: 'Pesanan & Pembayaran', routeName: 'AdminOrders', icon: IconOrder, disabled: true },
  { label: 'Pengaturan', routeName: 'AdminSettings', icon: IconSettings, disabled: true },
])

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
