const apiBaseUrl = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000'

export const fetchPackages = async () => {
  const response = await fetch(`${apiBaseUrl}/api/packages`)
  const data = await response.json().catch(() => ({}))

  if (!response.ok) {
    throw new Error(data.message || 'Gagal memuat daftar paket.')
  }

  return data.data || []
}

export const fetchPackage = async (slug) => {
  const response = await fetch(`${apiBaseUrl}/api/packages/${slug}`)
  const data = await response.json().catch(() => ({}))

  if (!response.ok) {
    throw new Error(data.message || 'Gagal memuat detail paket.')
  }

  return data.data || null
}
