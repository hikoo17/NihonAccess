
<template>
  <aside class="w-64 bg-[#161616] border-r border-zinc-800/80 flex flex-col justify-between shrink-0 min-h-screen">
    <div class="p-6">
      <div class="mb-8">
        <h1 class="text-xl font-semibold tracking-tight text-zinc-200">Panel guru</h1>
        <p class="text-xs text-zinc-500 font-medium">Nihongo Academy</p>
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
                ? 'bg-blue-600/10 text-blue-500 shadow-sm shadow-blue-900/5' 
                : 'text-zinc-400 hover:bg-zinc-800/40 hover:text-zinc-200'
            ]"
          >
            <component 
              :is="item.icon" 
              :class="['w-5 h-5 transition-colors', !item.disabled && isActive ? 'text-blue-500' : 'text-zinc-500 group-hover:text-zinc-400']" 
            />
            <span>{{ item.label }}</span>
            <span v-if="item.disabled" class="ml-auto text-[9px] bg-zinc-800 text-zinc-500 px-1.5 py-0.5 rounded">N/A</span>
          </button>
        </router-link>
      </nav>
    </div>

    <div class="p-4 border-t border-zinc-800/60 bg-zinc-900/20 flex items-center gap-3">
      <div class="w-9 h-9 rounded-xl bg-blue-600/20 flex items-center justify-center border border-blue-500/30 text-blue-400 font-semibold text-xs">
        BU
      </div>
      <div class="truncate">
        <p class="text-xs font-semibold text-zinc-200 truncate">Budi Utomo</p>
        <p class="text-[10px] text-zinc-500 font-medium">Teacher</p>
      </div>
    </div>
  </aside>
</template>

<script setup>
import { shallowRef, h } from 'vue'

const IconDashboard = () => ({
  render: () => h('svg', { fill: 'none', stroke: 'currentColor', 'stroke-width': '2', viewBox: '0 0 24 24' }, [
    h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z' })
  ])
})
const IconCourse = () => ({
  render: () => h('svg', { fill: 'none', stroke: 'currentColor', 'stroke-width': '2', viewBox: '0 0 24 24' }, [
    h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25' })
  ])
})
const IconQuiz = () => ({
  render: () => h('svg', { fill: 'none', stroke: 'currentColor', 'stroke-width': '2', viewBox: '0 0 24 24' }, [
    h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z' })
  ])
})
const IconCharacter = () => ({
  render: () => h('svg', { fill: 'none', stroke: 'currentColor', 'stroke-width': '2', viewBox: '0 0 24 24' }, [
    h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10' })
  ])
})
const IconListening = () => ({
  render: () => h('svg', { fill: 'none', stroke: 'currentColor', 'stroke-width': '2', viewBox: '0 0 24 24' }, [
    h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z' })
  ])
})

const menuItems = shallowRef([
  { label: 'Dashboard', routeName: 'TeacherDashboard', icon: IconDashboard },
  { label: 'Course & lesson', routeName: 'TeacherCourses', icon: IconCourse },
  { label: 'Quiz & soal', routeName: 'TeacherQuiz', icon: IconQuiz },
  { label: 'Karakter & Huruf', routeName: 'TeacherCharacters', icon: IconCharacter },
  { label: 'Listening', routeName: 'TeacherListening', icon: IconListening },
  { label: 'Writing (Tebak Huruf)', routeName: 'TeacherWriting', icon: IconCharacter, disabled: true }
])
</script>