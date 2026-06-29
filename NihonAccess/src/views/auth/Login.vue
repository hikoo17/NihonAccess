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

        <form class="space-y-5" @submit.prevent="handleLogin">
          <div>
            <Label>Username</Label>
            <Input v-model="form.username" type="text" required placeholder="nihon_xxxxx" />
          </div>

          <div>
            <Label>Password</Label>
            <Input v-model="form.password" type="password" required placeholder="Password dari email" />
          </div>

          <Button type="submit" class="w-full">
            Login
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
import Navbar from '../../components/Navbar.vue'
import Button from '../../components/ui/Button.vue'
import Card from '../../components/ui/Card.vue'
import Input from '../../components/ui/Input.vue'
import Label from '../../components/ui/Label.vue'

const form = ref({
  username: '',
  password: '',
})

const handleLogin = () => {
  alert('Endpoint login kelas belum dihubungkan. Gunakan form ini setelah API login akun kursus tersedia.')
}
</script>
