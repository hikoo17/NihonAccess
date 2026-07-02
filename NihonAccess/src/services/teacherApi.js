const BASE = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000'
const token = () => localStorage.getItem('auth_token')

async function request(path, { method = 'GET', body, query, form } = {}) {
  const url = new URL(`${BASE}/api/teacher${path}`)
  if (query) {
    for (const [k, v] of Object.entries(query)) {
      if (v !== null && v !== '' && v !== undefined) url.searchParams.set(k, v)
    }
  }

  let finalBody
  let headers = {
    'Accept': 'application/json',
    'Authorization': `Bearer ${token()}`,
  }
  if (form) {
    finalBody = form
    // jangan set Content-Type, browser otomatis tambah boundary multipart/form-data
  } else if (body) {
    headers['Content-Type'] = 'application/json'
    finalBody = JSON.stringify(body)
  }

  const res = await fetch(url, {
    method,
    headers,
    body: finalBody,
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

export const teacherApi = {
  dashboard: () => request('/dashboard'),

  courses: {
    list: (query) => request('/courses', { query }),
    get: (id) => request(`/courses/${id}`),
    create: (form) => request('/courses', { method: 'POST', form }),
    update: (id, form) => request(`/courses/${id}`, { method: 'POST', form }),
    toggleActive: (id, active) => {
      const form = new FormData()
      form.append('_method', 'PUT')
      form.append('is_active', active ? 1 : 0)
      return request(`/courses/${id}`, { method: 'POST', form })
    },
    remove: (id) => request(`/courses/${id}`, { method: 'DELETE' }),
  },

  lessons: {
    create: (body) => request('/lessons', { method: 'POST', body }),
    get: (id) => request(`/lessons/${id}`),
    update: (id, body) => request(`/lessons/${id}`, { method: 'PUT', body }),
    remove: (id) => request(`/lessons/${id}`, { method: 'DELETE' }),
  },

  quizzes: {
    list: (query) => request('/quizzes', { query }),
    get: (id) => request(`/quizzes/${id}`),
    create: (body) => request('/quizzes', { method: 'POST', body }),
    update: (id, body) => request(`/quizzes/${id}`, { method: 'PUT', body }),
    toggleActive: (id, active) => request(`/quizzes/${id}`, { method: 'PUT', body: { is_active: active } }),
    remove: (id) => request(`/quizzes/${id}`, { method: 'DELETE' }),
  },

  characters: {
    list: (query) => request('/character-exercises', { query }),
    get: (id) => request(`/character-exercises/${id}`),
    create: (body) => request('/character-exercises', { method: 'POST', body }),
    update: (id, body) => request(`/character-exercises/${id}`, { method: 'PUT', body }),
    remove: (id) => request(`/character-exercises/${id}`, { method: 'DELETE' }),
  },

  listening: {
    list: (query) => request('/listening-exercises', { query }),
    get: (id) => request(`/listening-exercises/${id}`),
    create: (body) => request('/listening-exercises', { method: 'POST', body }),
    update: (id, body) => request(`/listening-exercises/${id}`, { method: 'PUT', body }),
    toggleActive: (id, active) => request(`/listening-exercises/${id}`, { method: 'PUT', body: { is_active: active } }),
    remove: (id) => request(`/listening-exercises/${id}`, { method: 'DELETE' }),
  },

  writing: {
    list: (query) => request('/writing-exercises', { query }),
    get: (id) => request(`/writing-exercises/${id}`),
    create: (body) => request('/writing-exercises', { method: 'POST', body }),
    update: (id, body) => request(`/writing-exercises/${id}`, { method: 'PUT', body }),
    remove: (id) => request(`/writing-exercises/${id}`, { method: 'DELETE' }),
  },

  aiGenerations: {
    create: (body) => request('/ai-generations', { method: 'POST', body }),
    status: (uuid) => request(`/ai-generations/${uuid}`),
  },
}
