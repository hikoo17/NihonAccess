<template>
  <div class="min-h-screen bg-slate-50/50">
    <section class="flex min-h-screen items-center justify-center px-6 py-24">
      <Card class="w-full max-w-md p-8 sm:p-10">
        <div class="mb-8 text-center">
          <div class="mb-3 inline-flex rounded-full bg-[#cf3d3d]/10 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-[#cf3d3d]">
            Login Kelas
          </div>
          <h1 class="text-3xl font-extrabold tracking-tight text-slate-800">Masuk ke Nihon Access</h1>
          <p class="mt-3 text-sm leading-relaxed text-slate-500">
            Gunakan username dan password yang dikirim ke email atau WhatsApp setelah pembayaran berhasil.
          </p>
        </div>

        <div v-if="errorMessage" class="mb-4 p-3.5 bg-red-50 border border-red-200 text-sm text-red-600 rounded-xl font-medium">
          {{ errorMessage }}
        </div>

        <form class="space-y-5" @submit.prevent="handleLogin">
          <div>
            <Label>Username / Email</Label>
            <Input v-model="form.username" type="text" required placeholder="nihon_xxxxx atau email anda" :disabled="isLoading" />
          </div>

          <div>
            <Label>Password</Label>
            <Input v-model="form.password" type="password" required placeholder="Password dari email" :disabled="isLoading" />
          </div>

          <Button type="submit" class="w-full flex justify-center items-center gap-2" :disabled="isLoading">
            <svg v-if="isLoading" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
            </svg>
            <span>{{ isLoading ? 'Memproses...' : 'Login' }}</span>
          </Button>
        </form>

        <p class="mt-6 text-center text-xs leading-relaxed text-slate-400">
          Jika belum menerima akun, cek folder spam email dan pesan WhatsApp dari Nihon Access.
        </p>
      </Card>
    </section>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import Button from '../../components/ui/Button.vue'
import Card from '../../components/ui/Card.vue'
import Input from '../../components/ui/Input.vue'
import Label from '../../components/ui/Label.vue'

const apiBaseUrl = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000'
const router = useRouter()
const isLoading = ref(false)
const errorMessage = ref('')

const form = ref({
  username: '',
  password: '',
})

const handleLogin = async () => {
  isLoading.value = true
  errorMessage.value = ''
  
  try {
    const response = await fetch(`${apiBaseUrl}/api/auth/login`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        username: form.value.username,
        password: form.value.password
      })
    })

    const data = await response.json().catch(() => ({}))

    if (!response.ok || !data.success) {
      errorMessage.value = data.message || 'Kredensial salah.'
      return
    }

    const payload = data.data

    localStorage.setItem('auth_token', payload.token)
    localStorage.setItem('user_role', payload.user.role)
    localStorage.setItem('user_data', JSON.stringify(payload.user))

    if (payload.user.role === 'admin') {
      router.push({ name: 'AdminDashboard' })
    } else if (payload.user.role === 'teacher') {
      router.push({ name: 'TeacherDashboard' })
    } else {
      router.push({ name: 'client-dashboard' })
    }
  } catch (error) {
    console.error('Login Error:', error)
    errorMessage.value = 'Tidak dapat terhubung ke server. Periksa koneksi Anda.'
  } finally {
    isLoading.value = false
  }
}
</script>