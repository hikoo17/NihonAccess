<template>
  <nav
    class="fixed inset-x-0 bottom-0 z-40 border-t border-slate-200 bg-white/95 backdrop-blur-md lg:hidden"
    style="padding-bottom: max(0.375rem, env(safe-area-inset-bottom))"
    aria-label="Navigasi admin"
  >
    <div class="mx-auto flex max-w-md items-center justify-around px-2 pt-1.5">
      <RouterLink
        v-for="item in items"
        :key="item.routeName"
        :to="{ name: item.routeName }"
        class="nav-bottom-item"
      >
        <span class="nav-bottom-icon" v-html="item.icon" />
        <span class="mt-0.5 text-[10px] font-bold">{{ item.label }}</span>
      </RouterLink>
    </div>
  </nav>
</template>

<script setup>
const iconDashboard = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>`
const iconUsers = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4" stroke-linecap="round" stroke-linejoin="round"/><path stroke-linecap="round" stroke-linejoin="round" d="M22 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>`
const iconPackage = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 109.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1114.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>`
const iconOrder = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" /></svg>`

const items = [
  { routeName: 'AdminDashboard', label: 'Dashboard', icon: iconDashboard },
  { routeName: 'AdminUsers', label: 'User', icon: iconUsers },
  { routeName: 'AdminPackages', label: 'Paket', icon: iconPackage },
  { routeName: 'AdminOrders', label: 'Pesanan', icon: iconOrder },
]
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
.nav-bottom-item:hover {
  color: #cf3d3d;
}
.nav-bottom-item.router-link-active {
  color: #cf3d3d;
}
.nav-bottom-item.router-link-active .nav-bottom-icon {
  background-color: rgba(207, 61, 61, 0.1);
}
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
