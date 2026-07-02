// src/composables/useTeacherUser.js
// Parse user_data dari localStorage sekali, dipakai Sidebar/Topbar/Dashboard.

import { computed } from 'vue'

export function useTeacherUser() {
  const userName = computed(() => {
    try {
      const data = JSON.parse(localStorage.getItem('user_data') || '{}')
      return data.name || 'Guru'
    } catch {
      return 'Guru'
    }
  })

  const firstName = computed(() => userName.value.split(' ')[0])

  const initials = computed(() =>
    userName.value.split(' ').map((w) => w[0]).slice(0, 2).join('').toUpperCase()
  )

  return { userName, firstName, initials }
}