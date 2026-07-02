<template>
  <nav
    class="fixed inset-x-0 bottom-0 z-40 border-t border-slate-200 bg-white/95 backdrop-blur-md lg:hidden"
    style="padding-bottom: max(0.375rem, env(safe-area-inset-bottom))"
    aria-label="Navigasi utama"
  >
    <div class="mx-auto flex max-w-md items-center justify-around px-2 pt-1.5">
      <RouterLink
        v-for="item in items"
        :key="item.name"
        :to="item.to"
        class="nav-bottom-item"
      >
        <span class="nav-bottom-icon" v-html="item.icon" />
        <span class="mt-0.5 text-[10px] font-bold">{{ item.label }}</span>
      </RouterLink>
    </div>
  </nav>
</template>

<script setup>
const iconDashboard = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.949c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.5a.75.75 0 00.75.75h4.5a.75.75 0 00.75-.75V15a.75.75 0 01.75-.75h3a.75.75 0 01.75.75v5.25a.75.75 0 00.75.75h4.5a.75.75 0 00.75-.75V9.75M8.25 21h8.25" /></svg>`
const iconCourses = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>`
const iconAccount = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.648 0-5.195-.429-7.499-1.182z" /></svg>`

const items = [
  { name: 'dashboard', to: '/client/dashboard', label: 'Dashboard', icon: iconDashboard },
  { name: 'courses', to: '/client/my-courses', label: 'Kursus', icon: iconCourses },
  { name: 'account', to: '/client/profile', label: 'Akun', icon: iconAccount },
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