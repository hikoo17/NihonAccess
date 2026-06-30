<template>
  <div class="mx-auto max-w-7xl space-y-6 animate-fadeIn">
    <div>
      <Breadcrumb
        :items="[
          { label: 'Dashboard', to: { name: 'AdminDashboard' } },
          { label: 'Manajemen User' },
        ]"
      />
      <h1 class="mt-3 text-2xl font-extrabold tracking-tight text-slate-800 sm:text-3xl">
        Manajemen User
      </h1>
      <p class="mt-1 text-sm text-slate-500">
        Kelola seluruh akun pengguna: siswa, pengajar, dan admin.
      </p>
    </div>

    <div v-if="alert" class="rounded-2xl border px-4 py-3 text-sm font-medium"
      :class="alert.type === 'success' ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-[#cf3d3d]/20 bg-[#cf3d3d]/5 text-[#cf3d3d]'">
      {{ alert.message }}
    </div>

    <!-- Filter -->
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <div class="relative max-w-xs flex-1">
        <Input v-model="search" placeholder="Cari nama, email, atau username..." @input="onSearchInput" />
      </div>
      <div class="flex items-center gap-2">
        <button
          v-for="level in roleFilters"
          :key="level.value"
          @click="setRole(level.value)"
          class="rounded-full px-4 py-2 text-xs font-bold transition"
          :class="
            activeRole === level.value
              ? 'bg-[#cf3d3d] text-white shadow-md shadow-[#cf3d3d]/10'
              : 'bg-white text-slate-500 border border-slate-200 hover:border-[#cf3d3d]/40'
          "
        >
          {{ level.label }}
        </button>
      </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="text-xs font-bold text-slate-400 border-b border-slate-100 bg-slate-50/50">
              <th class="p-4 px-6">Nama</th>
              <th class="p-4 px-6">Email</th>
              <th class="p-4 px-6">Username</th>
              <th class="p-4 px-6">Role</th>
              <th class="p-4 px-6">Status</th>
              <th class="p-4 px-6">Bergabung</th>
              <th class="p-4 px-6 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-50 text-sm">
            <tr v-if="isLoading">
              <td colspan="7" class="p-12 text-center">
                <div class="mx-auto h-7 w-7 animate-spin rounded-full border-2 border-slate-200 border-t-[#cf3d3d]"></div>
              </td>
            </tr>
            <tr v-else-if="users.length === 0">
              <td colspan="7" class="p-12 text-center text-sm text-slate-400">Tidak ada user ditemukan.</td>
            </tr>
            <tr v-for="user in users" :key="user.id" class="hover:bg-slate-50 transition-colors">
              <td class="p-4 px-6">
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 rounded-xl bg-[#cf3d3d]/10 flex items-center justify-center text-sm font-bold text-[#cf3d3d]">
                    {{ user.name?.charAt(0).toUpperCase() }}
                  </div>
                  <span class="font-bold text-slate-800">{{ user.name }}</span>
                </div>
              </td>
              <td class="p-4 px-6 text-slate-500">{{ user.email }}</td>
              <td class="p-4 px-6 font-mono text-xs text-slate-500">{{ user.username || '-' }}</td>
              <td class="p-4 px-6">
                <span :class="roleBadge(user.role)">{{ user.role }}</span>
              </td>
              <td class="p-4 px-6">
                <span :class="user.is_active ? 'px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700' : 'px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500'">
                  {{ user.is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
              </td>
              <td class="p-4 px-6 text-slate-400 text-xs">{{ user.created_at }}</td>
              <td class="p-4 px-6 text-right">
                <button
                  @click="toggleActive(user)"
                  :disabled="togglingId === user.id"
                  class="rounded-xl border px-3 py-1.5 text-xs font-bold transition disabled:opacity-50"
                  :class="user.is_active
                    ? 'border-slate-200 text-slate-500 hover:border-[#cf3d3d]/40 hover:text-[#cf3d3d]'
                    : 'border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100'"
                >
                  <span v-if="togglingId === user.id" class="inline-block h-3 w-3 animate-spin rounded-full border border-current border-t-transparent align-middle"></span>
                  <span v-else>{{ user.is_active ? 'Nonaktifkan' : 'Aktifkan' }}</span>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div v-if="!isLoading && users.length > 0" class="flex flex-col gap-3 border-t border-slate-100 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-xs text-slate-400">
          Menampilkan {{ users.length }} dari {{ meta.total }} user
        </p>
        <div class="flex items-center gap-2">
          <button
            @click="changePage(meta.current_page - 1)"
            :disabled="meta.current_page <= 1"
            class="rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-600 transition hover:border-[#cf3d3d]/40 hover:text-[#cf3d3d] disabled:opacity-40"
          >Sebelumnya</button>
          <span class="rounded-xl bg-slate-50 px-3 py-1.5 text-xs font-bold text-slate-600">
            {{ meta.current_page }} / {{ meta.last_page }}
          </span>
          <button
            @click="changePage(meta.current_page + 1)"
            :disabled="meta.current_page >= meta.last_page"
            class="rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-600 transition hover:border-[#cf3d3d]/40 hover:text-[#cf3d3d] disabled:opacity-40"
          >Berikutnya</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import Breadcrumb from '@/components/ui/Breadcrumb.vue'
import Input from '@/components/ui/Input.vue'

const apiBaseUrl = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000'

const isLoading = ref(true)
const users = ref([])
const meta = ref({ current_page: 1, last_page: 1, per_page: 10, total: 0 })
const search = ref('')
const activeRole = ref('all')
const togglingId = ref(null)
const alert = ref(null)

const roleFilters = [
  { label: 'Semua', value: 'all' },
  { label: 'Client', value: 'client' },
  { label: 'Teacher', value: 'teacher' },
  { label: 'Admin', value: 'admin' },
]

let searchTimer = null
const onSearchInput = () => {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => fetchUsers(1), 350)
}

const setRole = (value) => {
  activeRole.value = value
  fetchUsers(1)
}

const changePage = (page) => {
  if (page < 1 || page > meta.value.last_page) return
  fetchUsers(page)
}

const roleBadge = (role) => {
  const map = {
    admin: 'px-3 py-1 rounded-full text-xs font-bold bg-[#cf3d3d]/10 text-[#cf3d3d]',
    teacher: 'px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700',
    client: 'px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600',
  }
  return map[role] || 'px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500'
}

const flash = (type, message) => {
  alert.value = { type, message }
  setTimeout(() => (alert.value = null), 3000)
}

const fetchUsers = async (page = meta.value.current_page) => {
  isLoading.value = true
  try {
    const token = localStorage.getItem('auth_token')
    const params = new URLSearchParams({
      page: String(page),
      per_page: String(meta.value.per_page),
    })
    if (search.value.trim()) params.set('search', search.value.trim())
    if (activeRole.value !== 'all') params.set('role', activeRole.value)

    const res = await fetch(`${apiBaseUrl}/api/admin/users?${params.toString()}`, {
      headers: { Authorization: `Bearer ${token}` },
    })
    const data = await res.json().catch(() => ({}))

if (res.ok && data.success) {
  // Ambil array user langsung dari data.data
  users.value = data.data || []
  
  // Ambil meta dari data.meta
  meta.value = data.meta || {
    current_page: 1,
    last_page: 1,
    per_page: 10,
    total: 0
  }
} else {
  flash('error', data.message || 'Gagal memuat data user.')
}
  } catch (e) {
    flash('error', 'Tidak dapat terhubung ke server.')
  } finally {
    isLoading.value = false
  }
}

const toggleActive = async (user) => {
  togglingId.value = user.id
  try {
    const token = localStorage.getItem('auth_token')
    const res = await fetch(`${apiBaseUrl}/api/admin/users/${user.id}`, {
      method: 'PATCH',
      headers: {
        Authorization: `Bearer ${token}`,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ is_active: !user.is_active }),
    })
    const data = await res.json().catch(() => ({}))

    if (res.ok && data.success) {
      user.is_active = data.data.is_active
      flash('success', `${user.name} ${user.is_active ? 'diaktifkan' : 'dinonaktifkan'}.`)
    } else {
      flash('error', data.message || 'Gagal memperbarui status user.')
    }
  } catch (e) {
    flash('error', 'Tidak dapat terhubung ke server.')
  } finally {
    togglingId.value = null
  }
}

onMounted(() => fetchUsers(1))
</script>

<style scoped>
.animate-fadeIn {
  animation: fadeIn 0.35s ease-out forwards;
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(4px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
