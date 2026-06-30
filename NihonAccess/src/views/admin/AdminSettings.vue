<template>
  <div class="mx-auto max-w-4xl space-y-6 animate-fadeIn">
    <div>
      <Breadcrumb
        :items="[
          { label: 'Dashboard', to: { name: 'AdminDashboard' } },
          { label: 'Pengaturan' },
        ]"
      />
      <h1 class="mt-3 text-2xl font-extrabold tracking-tight text-slate-800 sm:text-3xl">
        Pengaturan Akun
      </h1>
      <p class="mt-1 text-sm text-slate-500">
        Kelola informasi profil dan keamanan akun administrator.
      </p>
    </div>

    <div v-if="alert" class="rounded-2xl border px-4 py-3 text-sm font-medium"
      :class="alert.type === 'success' ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-[#cf3d3d]/20 bg-[#cf3d3d]/5 text-[#cf3d3d]'">
      {{ alert.message }}
    </div>

    <!-- Profile -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 sm:p-8">
      <div class="mb-6 flex items-center gap-4">
        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-[#cf3d3d] to-[#b03333] text-xl font-extrabold text-white">
          {{ initials }}
        </div>
        <div>
          <h2 class="text-lg font-extrabold text-slate-800">{{ profile.name }}</h2>
          <p class="text-sm text-slate-400">{{ profile.email }}</p>
          <span class="mt-1 inline-block rounded-full bg-[#cf3d3d]/10 px-2.5 py-0.5 text-[10px] font-bold text-[#cf3d3d]">Administrator</span>
        </div>
      </div>

      <form class="space-y-5" @submit.prevent="saveProfile">
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
          <div>
            <Label>Nama Lengkap</Label>
            <Input v-model="form.name" placeholder="Nama lengkap" />
          </div>
          <div>
            <Label>Username</Label>
            <Input :model-value="profile.username" disabled />
          </div>
          <div>
            <Label>Email</Label>
            <Input :model-value="profile.email" disabled />
          </div>
          <div>
            <Label>WhatsApp</Label>
            <Input v-model="form.whatsapp" placeholder="08xxxxxxxxxx" />
          </div>
        </div>

        <div class="flex justify-end">
          <Button type="submit" :disabled="savingProfile">
            <span v-if="savingProfile" class="inline-block h-4 w-4 animate-spin rounded-full border-2 border-white border-t-transparent"></span>
            <span>{{ savingProfile ? 'Menyimpan...' : 'Simpan Perubahan' }}</span>
          </Button>
        </div>
      </form>
    </div>

    <!-- Change Password -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 sm:p-8">
      <h2 class="mb-5 text-lg font-extrabold text-slate-800">Ubah Password</h2>

      <form class="space-y-5" @submit.prevent="savePassword">
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
          <div class="sm:col-span-2">
            <Label>Password Saat Ini</Label>
            <Input v-model="pwForm.current_password" type="password" placeholder="Password saat ini" />
          </div>
          <div>
            <Label>Password Baru</Label>
            <Input v-model="pwForm.password" type="password" placeholder="Min. 8 karakter" />
          </div>
          <div>
            <Label>Konfirmasi Password Baru</Label>
            <Input v-model="pwForm.password_confirmation" type="password" placeholder="Ulangi password baru" />
          </div>
        </div>

        <div v-if="pwError" class="rounded-xl bg-[#cf3d3d]/5 px-4 py-2.5 text-xs font-medium text-[#cf3d3d]">
          {{ pwError }}
        </div>

        <div class="flex justify-end">
          <Button type="submit" variant="outline" :disabled="savingPassword">
            <span v-if="savingPassword" class="inline-block h-4 w-4 animate-spin rounded-full border-2 border-current border-t-transparent"></span>
            <span>{{ savingPassword ? 'Memperbarui...' : 'Perbarui Password' }}</span>
          </Button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import Breadcrumb from '@/components/ui/Breadcrumb.vue'
import Button from '@/components/ui/Button.vue'
import Input from '@/components/ui/Input.vue'
import Label from '@/components/ui/Label.vue'

const apiBaseUrl = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000'

const profile = ref({ name: '', email: '', whatsapp: '', username: '' })
const form = ref({ name: '', whatsapp: '' })
const pwForm = ref({ current_password: '', password: '', password_confirmation: '' })

const savingProfile = ref(false)
const savingPassword = ref(false)
const alert = ref(null)
const pwError = ref('')

const initials = ref('AD')

const flash = (type, message) => {
  alert.value = { type, message }
  setTimeout(() => (alert.value = null), 3000)
}

const fetchProfile = async () => {
  try {
    const token = localStorage.getItem('auth_token')
    const res = await fetch(`${apiBaseUrl}/api/admin/profile`, {
      headers: { Authorization: `Bearer ${token}` },
    })
    const data = await res.json().catch(() => ({}))

    if (res.ok && data.success) {
      profile.value = data.data
      form.value.name = data.data.name
      form.value.whatsapp = data.data.whatsapp || ''
      const name = data.data.name || 'AD'
      initials.value = name.split(' ').map(w => w[0]).slice(0, 2).join('').toUpperCase()
    }
  } catch (e) {
    flash('error', 'Gagal memuat profil.')
  }
}

const saveProfile = async () => {
  savingProfile.value = true
  try {
    const token = localStorage.getItem('auth_token')
    const res = await fetch(`${apiBaseUrl}/api/admin/profile`, {
      method: 'PUT',
      headers: {
        Authorization: `Bearer ${token}`,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        name: form.value.name,
        whatsapp: form.value.whatsapp || null,
      }),
    })
    const data = await res.json().catch(() => ({}))

    if (res.ok && data.success) {
      profile.value = { ...profile.value, ...data.data }
      const stored = JSON.parse(localStorage.getItem('user_data') || '{}')
      stored.name = data.data.name
      localStorage.setItem('user_data', JSON.stringify(stored))
      flash('success', 'Profil berhasil diperbarui.')
    } else {
      flash('error', data.message || 'Gagal memperbarui profil.')
    }
  } catch (e) {
    flash('error', 'Tidak dapat terhubung ke server.')
  } finally {
    savingProfile.value = false
  }
}

const savePassword = async () => {
  pwError.value = ''
  if (pwForm.value.password !== pwForm.value.password_confirmation) {
    pwError.value = 'Konfirmasi password tidak cocok.'
    return
  }
  if (pwForm.value.password && pwForm.value.password.length < 8) {
    pwError.value = 'Password baru minimal 8 karakter.'
    return
  }

  savingPassword.value = true
  try {
    const token = localStorage.getItem('auth_token')
    const res = await fetch(`${apiBaseUrl}/api/admin/profile`, {
      method: 'PUT',
      headers: {
        Authorization: `Bearer ${token}`,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        current_password: pwForm.value.current_password,
        password: pwForm.value.password,
        password_confirmation: pwForm.value.password_confirmation,
      }),
    })
    const data = await res.json().catch(() => ({}))

    if (res.ok && data.success) {
      pwForm.value = { current_password: '', password: '', password_confirmation: '' }
      flash('success', 'Password berhasil diperbarui.')
    } else if (data.errors?.current_password) {
      pwError.value = data.errors.current_password[0]
    } else {
      pwError.value = data.message || 'Gagal memperbarui password.'
    }
  } catch (e) {
    pwError.value = 'Tidak dapat terhubung ke server.'
  } finally {
    savingPassword.value = false
  }
}

onMounted(() => fetchProfile())
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
