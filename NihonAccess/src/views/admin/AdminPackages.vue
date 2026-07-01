<template>
  <div class="mx-auto max-w-7xl space-y-6 animate-fadeIn">
    <div>
      <Breadcrumb
        :items="[
          { label: 'Dashboard', to: { name: 'AdminDashboard' } },
          { label: 'Manajemen Paket' },
        ]"
      />
      <h1 class="mt-3 text-2xl font-extrabold tracking-tight text-slate-800 sm:text-3xl">
        Manajemen Paket
      </h1>
      <p class="mt-1 text-sm text-slate-500">
        Aktifkan atau nonaktifkan paket kursus yang ditampilkan ke siswa.
      </p>
    </div>

    <!-- Search -->
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <div class="relative max-w-xs flex-1">
        <Input v-model="search" placeholder="Cari paket..." @input="onSearchInput" />
      </div>
      <p class="text-xs text-slate-400">{{ packages.length }} paket ditemukan</p>
    </div>

    <!-- Loading -->
    <div v-if="isLoading" class="flex items-center justify-center py-24">
      <div class="h-8 w-8 animate-spin rounded-full border-2 border-slate-200 border-t-[#cf3d3d]"></div>
    </div>

    <!-- Empty -->
    <div v-else-if="packages.length === 0" class="rounded-3xl border border-slate-100 bg-white p-16 text-center text-sm text-slate-400">
      Tidak ada paket ditemukan.
    </div>

    <!-- Grid -->
    <div v-else class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
      <div v-for="pkg in packages" :key="pkg.id"
        class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm transition hover:shadow-md">
        <div class="flex items-start justify-between gap-3">
          <div class="flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-[#cf3d3d] to-[#b03333] text-sm font-extrabold text-white">
              {{ pkg.name?.charAt(0).toUpperCase() }}
            </div>
            <div>
              <h3 class="font-bold text-slate-800 leading-tight">{{ pkg.name }}</h3>
              <p class="text-xs text-slate-400 capitalize">{{ typeLabel(pkg.type) }}{{ pkg.level ? ' · ' + pkg.level : '' }}</p>
            </div>
          </div>
          <span :class="pkg.is_active ? 'px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700' : 'px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500'">
            {{ pkg.is_active ? 'Aktif' : 'Nonaktif' }}
          </span>
        </div>

        <div class="mt-5 grid grid-cols-2 gap-3 text-center">
          <div class="rounded-2xl bg-slate-50 py-3">
            <p class="text-lg font-extrabold text-slate-800">{{ pkg.enrollments_count }}</p>
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Enrollment</p>
          </div>
          <div class="rounded-2xl bg-slate-50 py-3">
            <p class="text-lg font-extrabold text-slate-800">{{ pkg.orders_count }}</p>
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Pesanan</p>
          </div>
        </div>

        <div class="mt-4 flex items-end justify-between border-t border-slate-100 pt-4">
          <div>
            <p class="text-xs text-slate-400">{{ pkg.duration_label }}</p>
            <p class="text-lg font-extrabold text-[#cf3d3d]">{{ pkg.price_formatted }}</p>
          </div>
          <button
            @click="togglePackage(pkg)"
            :disabled="togglingId === pkg.id"
            class="rounded-xl border px-4 py-2 text-xs font-bold transition disabled:opacity-50"
            :class="pkg.is_active
              ? 'border-slate-200 text-slate-500 hover:border-[#cf3d3d]/40 hover:text-[#cf3d3d]'
              : 'border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100'"
          >
            <span v-if="togglingId === pkg.id" class="inline-block h-3 w-3 animate-spin rounded-full border border-current border-t-transparent align-middle"></span>
            <span v-else>{{ pkg.is_active ? 'Nonaktifkan' : 'Aktifkan' }}</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import Breadcrumb from '@/components/ui/Breadcrumb.vue'
import Input from '@/components/ui/Input.vue'
import { fireToast } from '@/lib/swal.js'

const apiBaseUrl = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000'

const isLoading = ref(true)
const packages = ref([])
const search = ref('')
const togglingId = ref(null)

let searchTimer = null
const onSearchInput = () => {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => fetchPackages(), 350)
}

const typeLabel = (type) => {
  const map = { online: 'Online', onsite: 'On-Site' }
  return map[type] || (type || '-')
}

const flash = (type, message) => fireToast(type, message)

const fetchPackages = async () => {
  isLoading.value = true
  try {
    const token = localStorage.getItem('auth_token')
    const params = new URLSearchParams()
    if (search.value.trim()) params.set('search', search.value.trim())

    const res = await fetch(`${apiBaseUrl}/api/admin/packages?${params.toString()}`, {
      headers: { Authorization: `Bearer ${token}` },
    })
    const data = await res.json().catch(() => ({}))

    if (res.ok && data.success) {
      packages.value = data.data
    } else {
      flash('error', data.message || 'Gagal memuat data paket.')
    }
  } catch (e) {
    flash('error', 'Tidak dapat terhubung ke server.')
  } finally {
    isLoading.value = false
  }
}

const togglePackage = async (pkg) => {
  togglingId.value = pkg.id
  try {
    const token = localStorage.getItem('auth_token')
    const res = await fetch(`${apiBaseUrl}/api/admin/packages/${pkg.id}/toggle`, {
      method: 'PATCH',
      headers: { Authorization: `Bearer ${token}` },
    })
    const data = await res.json().catch(() => ({}))

    if (res.ok && data.success) {
      pkg.is_active = data.data.is_active
      flash('success', `${pkg.name} ${pkg.is_active ? 'diaktifkan' : 'dinonaktifkan'}.`)
    } else {
      flash('error', data.message || 'Gagal memperbarui status paket.')
    }
  } catch (e) {
    flash('error', 'Tidak dapat terhubung ke server.')
  } finally {
    togglingId.value = null
  }
}

onMounted(() => fetchPackages())
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
