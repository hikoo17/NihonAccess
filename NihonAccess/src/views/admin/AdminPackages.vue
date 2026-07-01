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
      <div class="flex items-center gap-3">
        <p class="hidden text-xs text-slate-400 sm:block">{{ packages.length }} paket ditemukan</p>
        <button
          @click="openCreateModal"
          class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#cf3d3d] px-4 py-2.5 text-xs font-bold text-white shadow-md shadow-[#cf3d3d]/10 transition hover:bg-[#b83232]"
        >
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
            <path d="M12 5v14M5 12h14" />
          </svg>
          Tambah Paket
        </button>
      </div>
    </div>

    <!-- Loading skeleton -->
    <div v-if="isLoading" class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
      <div v-for="n in 6" :key="`sk-${n}`" class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
        <div class="flex items-start justify-between gap-3">
          <div class="flex items-center gap-3">
            <Skeleton width="3rem" height="3rem" rounded="2xl" />
            <div class="space-y-1.5">
              <Skeleton width="8rem" />
              <Skeleton width="5rem" height="0.75rem" />
            </div>
          </div>
          <Skeleton width="3rem" height="1.25rem" rounded="full" />
        </div>
        <div class="mt-5 grid grid-cols-2 gap-3">
          <Skeleton width="100%" height="3rem" rounded="xl" />
          <Skeleton width="100%" height="3rem" rounded="xl" />
        </div>
        <div class="mt-5 flex justify-end">
          <Skeleton width="6rem" height="2rem" rounded="xl" />
        </div>
      </div>
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
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#cf3d3d] text-white shadow-sm shadow-[#cf3d3d]/15">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <path :d="resolvePackageIcon(pkg.icon)" />
              </svg>
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

        <ul v-if="pkg.features && pkg.features.length" class="mt-4 space-y-2.5 text-sm">
          <li
            v-for="feat in pkg.features"
            :key="feat.id"
            class="flex items-center gap-3 font-medium text-slate-600"
          >
            <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-white text-xs font-bold">✓</span>
            <span>{{ feat.name }}</span>
          </li>
        </ul>

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

    <!-- Create Modal -->
    <Teleport to="body">
      <div v-if="showCreateModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/40 backdrop-blur-sm" @click.self="closeCreateModal">
        <div class="relative flex min-h-full items-center justify-center p-4 py-8 sm:p-6">
          <div class="relative w-full max-w-lg rounded-3xl bg-white p-6 shadow-2xl sm:p-7">
          <div class="flex items-start justify-between gap-4">
            <div>
              <h3 class="text-lg font-extrabold text-slate-800">Tambah Paket Baru</h3>
              <p class="mt-1 text-xs text-slate-400">Lengkapi detail paket kursus di bawah ini.</p>
            </div>
            <button @click="closeCreateModal" class="rounded-xl p-1.5 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <path d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <form @submit.prevent="submitCreate" class="mt-5 space-y-4">
            <div>
              <Label>Nama Paket</Label>
              <Input v-model="form.name" placeholder="Contoh: Paket Intensive Online" />
              <p v-if="errors.name" class="mt-1 text-xs text-[#cf3d3d]">{{ errors.name }}</p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
              <div>
                <Label>Tipe</Label>
                <Select v-model="form.type">
                  <option value="online">Online</option>
                  <option value="onsite">On-Site</option>
                </Select>
                <p v-if="errors.type" class="mt-1 text-xs text-[#cf3d3d]">{{ errors.type }}</p>
              </div>
              <div>
                <Label>Level</Label>
                <Input v-model="form.level" placeholder="Contoh: Pemula" />
              </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
              <div>
                <Label>Harga (Rp)</Label>
                <Input v-model="form.price" type="number" min="0" placeholder="0" />
                <p v-if="errors.price" class="mt-1 text-xs text-[#cf3d3d]">{{ errors.price }}</p>
              </div>
              <div>
                <Label>Durasi (hari)</Label>
                <Input v-model="form.duration_days" type="number" min="0" placeholder="30" />
                <p v-if="errors.duration_days" class="mt-1 text-xs text-[#cf3d3d]">{{ errors.duration_days }}</p>
              </div>
            </div>

            <div>
              <Label>Deskripsi</Label>
              <textarea
                v-model="form.description"
                rows="3"
                placeholder="Deskripsi singkat paket..."
                class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 transition placeholder:text-slate-400 focus:border-[#cf3d3d]/40 focus:outline-none focus:ring-2 focus:ring-[#cf3d3d]/20"
              ></textarea>
            </div>

            <div>
              <div class="mb-2 flex items-center justify-between">
                <Label class="mb-0">Fitur Paket</Label>
                <button type="button" @click="addFeature" class="inline-flex items-center gap-1 text-xs font-bold text-[#cf3d3d] hover:text-[#b83232]">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="h-3.5 w-3.5">
                    <path d="M12 5v14M5 12h14" />
                  </svg>
                  Tambah
                </button>
              </div>
              <div v-if="!form.features.length" class="rounded-2xl border border-dashed border-slate-200 px-4 py-3 text-xs text-slate-400">
                Belum ada fitur. Klik "Tambah" untuk menambahkan daftar fitur.
              </div>
              <div v-else class="space-y-2">
                <div v-for="(feat, idx) in form.features" :key="idx" class="flex items-center gap-2">
                  <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-white text-xs font-bold">✓</span>
                  <input
                    v-model="feat.name"
                    type="text"
                    :placeholder="`Fitur ${idx + 1}`"
                    class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 transition placeholder:text-slate-400 focus:border-[#cf3d3d]/40 focus:outline-none focus:ring-2 focus:ring-[#cf3d3d]/20"
                  />
                  <button type="button" @click="removeFeature(idx)" class="rounded-lg p-1.5 text-slate-400 transition hover:bg-[#cf3d3d]/10 hover:text-[#cf3d3d]" title="Hapus fitur">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                      <path d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </div>
              </div>
            </div>

            <div>
              <Label>Ikon Paket</Label>
              <div class="grid grid-cols-5 gap-2 sm:grid-cols-10">
                <button
                  v-for="ic in PACKAGE_ICONS"
                  :key="ic.name"
                  type="button"
                  @click="form.icon = ic.path"
                  class="flex h-10 w-10 items-center justify-center rounded-xl border transition"
                  :class="form.icon === ic.path
                    ? 'border-[#cf3d3d] bg-[#cf3d3d]/10 text-[#cf3d3d]'
                    : 'border-slate-200 text-slate-500 hover:border-[#cf3d3d]/40 hover:text-[#cf3d3d]'"
                  :title="ic.label"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path :d="ic.path" />
                  </svg>
                </button>
              </div>
            </div>

            <label class="flex cursor-pointer items-center gap-3">
              <input type="checkbox" v-model="form.is_active" class="h-4 w-4 rounded border-slate-300 text-[#cf3d3d] focus:ring-[#cf3d3d]/40" />
              <span class="text-sm font-medium text-slate-600">Aktifkan paket segera</span>
            </label>

            <div class="flex justify-end gap-3 pt-2">
              <button type="button" @click="closeCreateModal" class="rounded-xl border border-slate-200 px-4 py-2.5 text-xs font-bold text-slate-500 transition hover:bg-slate-50">
                Batal
              </button>
              <button
                type="submit"
                :disabled="isSubmitting"
                class="inline-flex items-center gap-2 rounded-xl bg-[#cf3d3d] px-5 py-2.5 text-xs font-bold text-white shadow-md shadow-[#cf3d3d]/10 transition hover:bg-[#b83232] disabled:opacity-60"
              >
                <span v-if="isSubmitting" class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-white border-t-transparent"></span>
                {{ isSubmitting ? 'Menyimpan...' : 'Simpan Paket' }}
              </button>
            </div>
          </form>
          </div>
        </div>
      </div>
    </Teleport>
  </div>
</template>

<script setup>
import { ref, reactive, watch, onMounted, onBeforeUnmount } from 'vue'
import Breadcrumb from '@/components/ui/Breadcrumb.vue'
import Input from '@/components/ui/Input.vue'
import Select from '@/components/ui/Select.vue'
import Label from '@/components/ui/Label.vue'
import Skeleton from '@/components/ui/Skeleton.vue'
import { fireToast } from '@/lib/swal.js'
import { PACKAGE_ICONS, resolvePackageIcon } from '@/lib/packageIcons.js'

const apiBaseUrl = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000'

const isLoading = ref(true)
const packages = ref([])
const search = ref('')
const togglingId = ref(null)

const showCreateModal = ref(false)
const isSubmitting = ref(false)
const form = reactive({
  name: '',
  type: 'online',
  icon: PACKAGE_ICONS[0].path,
  level: '',
  description: '',
  price: '',
  duration_days: '30',
  is_active: true,
  features: [],
})
const errors = reactive({})

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

const addFeature = () => {
  form.features.push({ name: '' })
}

const removeFeature = (idx) => {
  form.features.splice(idx, 1)
}

const resetForm = () => {
  form.name = ''
  form.type = 'online'
  form.icon = PACKAGE_ICONS[0].path
  form.level = ''
  form.description = ''
  form.price = ''
  form.duration_days = '30'
  form.is_active = true
  form.features = []
  Object.keys(errors).forEach((k) => delete errors[k])
}

const openCreateModal = () => {
  resetForm()
  showCreateModal.value = true
}

const closeCreateModal = () => {
  if (isSubmitting.value) return
  showCreateModal.value = false
  resetForm()
}

watch(showCreateModal, (open) => {
  if (typeof document === 'undefined') return
  document.body.style.overflow = open ? 'hidden' : ''
})

onBeforeUnmount(() => {
  if (typeof document !== 'undefined') document.body.style.overflow = ''
})

const submitCreate = async () => {
  Object.keys(errors).forEach((k) => delete errors[k])
  isSubmitting.value = true
  try {
    const token = localStorage.getItem('auth_token')
    const res = await fetch(`${apiBaseUrl}/api/admin/packages`, {
      method: 'POST',
      headers: {
        Authorization: `Bearer ${token}`,
        'Content-Type': 'application/json',
        Accept: 'application/json',
      },
      body: JSON.stringify({
        ...form,
        features: form.features.map((f) => f.name).filter((n) => n.trim() !== ''),
      }),
    })
    const data = await res.json().catch(() => ({}))

    if (res.ok && data.success) {
      flash('success', data.message || 'Paket berhasil ditambahkan.')
      showCreateModal.value = false
      resetForm()
      await fetchPackages()
    } else if (res.status === 422 && data.errors) {
      Object.entries(data.errors).forEach(([field, msgs]) => {
        errors[field] = Array.isArray(msgs) ? msgs[0] : msgs
      })
    } else {
      flash('error', data.message || 'Gagal menambahkan paket.')
    }
  } catch (e) {
    flash('error', 'Tidak dapat terhubung ke server.')
  } finally {
    isSubmitting.value = false
  }
}

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
