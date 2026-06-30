import { ref } from 'vue'

const toasts = ref([])
let counter = 0

function dismiss(id) {
  toasts.value = toasts.value.filter((t) => t.id !== id)
}

function push(message, type = 'success') {
  const id = ++counter
  toasts.value.push({ id, message, type })
  setTimeout(() => dismiss(id), 3500)
}

export function useTeacherToast() {
  return {
    toasts,
    dismiss,
    success: (m) => push(m, 'success'),
    error: (m) => push(m, 'error'),
    info: (m) => push(m, 'info'),
  }
}
