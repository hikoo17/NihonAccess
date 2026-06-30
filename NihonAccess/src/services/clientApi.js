const BASE = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000'
const token = () => localStorage.getItem('auth_token')

async function request(path, { method = 'GET', body, query } = {}) {
  const url = new URL(`${BASE}/api/client${path}`)
  if (query) {
    for (const [k, v] of Object.entries(query)) {
      if (v !== null && v !== '' && v !== undefined) url.searchParams.set(k, v)
    }
  }

  const res = await fetch(url, {
    method,
    headers: {
      'Accept': 'application/json', // biar backend balikin JSON kalau error (bukan redirect 302)
      'Authorization': `Bearer ${token()}`,
      ...(body ? { 'Content-Type': 'application/json' } : {}),
    },
    body: body ? JSON.stringify(body) : undefined,
  })

  const data = await res.json().catch(() => ({}))

  if (res.status === 422) {
    const e = new Error(data.message || 'Validasi gagal')
    e.errors = data.errors || {}
    throw e
  }

  if (res.status === 401) {
    localStorage.removeItem('auth_token')
    localStorage.removeItem('user_role')
    localStorage.removeItem('user_data')
    window.location.href = '/login'
    throw new Error('Sesi berakhir, silakan login kembali.')
  }

  if (!res.ok || !data.success) {
    throw new Error(data.message || `Permintaan gagal (${res.status})`)
  }

  return data
}

export const clientApi = {
  dashboard: () => request('/dashboard'),
  myCourses: () => request('/my-courses'),
  courseLessons: (enrollmentId) => request(`/my-courses/${enrollmentId}/lessons`),
  completeLesson: (lessonId) => request(`/lessons/${lessonId}/complete`, { method: 'POST' }),

  // Endpoint lain bisa ditambahkan di sini nanti, contoh:
  // courses: {
  //   list: (query) => request('/courses', { query }),
  //   get: (id) => request(`/courses/${id}`),
  // },
  // progress: {
  //   complete: (lessonId) => request('/progress', { method: 'POST', body: { lesson_id: lessonId } }),
  // },
    characters: {
    list: (query) => request('/character-exercises', { query }),
    get: (id) => request(`/character-exercises/${id}`),
    submit: (body) => request('/character-exercises/submit', { method: 'POST', body }),
  },
}