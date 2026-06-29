<template>
  <div class="mx-auto max-w-4xl space-y-8">
    <!-- Header -->
    <div>
      <Breadcrumb :items="[{ label: 'Dashboard', to: '/client/dashboard' }, { label: 'Profil Saya' }]" />
      <h1 class="mt-3 text-2xl font-extrabold tracking-tight text-slate-800 sm:text-3xl">Profil Saya</h1>
      <p class="mt-1 text-sm text-slate-500">Kelola informasi akun dan keamanan kamu.</p>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
      <!-- Left: avatar -->
      <Card class="h-fit p-6 text-center lg:col-span-1">
        <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-gradient-to-br from-[#cf3d3d] to-[#b03333] text-3xl font-extrabold text-white">
          BS
        </div>
        <h2 class="mt-4 text-base font-extrabold text-slate-800">Budi Santoso</h2>
        <p class="text-xs text-slate-400">budi@email.com</p>
        <Badge variant="info" size="sm" class="mt-3">Member</Badge>
        <Button variant="outline" size="sm" class="mt-5 w-full">Ganti Foto</Button>
      </Card>

      <!-- Right: forms -->
      <div class="space-y-6 lg:col-span-2">
        <!-- Data diri -->
        <Card class="p-6">
          <div class="mb-5 border-b border-slate-100 pb-4">
            <h2 class="text-sm font-extrabold text-slate-800">Data Diri</h2>
            <p class="mt-0.5 text-xs text-slate-400">Perbarui informasi pribadi kamu.</p>
          </div>
          <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
              <Label>Nama Lengkap</Label>
              <Input modelValue="Budi Santoso" />
            </div>
            <div>
              <Label>Email</Label>
              <Input modelValue="budi@email.com" />
            </div>
            <div>
              <Label>Nomor WhatsApp</Label>
              <Input modelValue="081234567890" />
            </div>
            <div>
              <Label>Username</Label>
              <Input modelValue="nihon_xxxxx" />
            </div>
          </div>
          <Button class="mt-5">Simpan Perubahan</Button>
        </Card>

        <!-- Keamanan -->
        <Card class="p-6">
          <div class="mb-5 border-b border-slate-100 pb-4">
            <h2 class="text-sm font-extrabold text-slate-800">Keamanan</h2>
            <p class="mt-0.5 text-xs text-slate-400">Ubah password akun kamu.</p>
          </div>
          <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="sm:col-span-2">
              <Label>Password Saat Ini</Label>
              <Input type="password" placeholder="••••••••" />
            </div>
            <div>
              <Label>Password Baru</Label>
              <Input type="password" placeholder="••••••••" />
            </div>
            <div>
              <Label>Konfirmasi Password</Label>
              <Input type="password" placeholder="••••••••" />
            </div>
          </div>
          <Button variant="outline" class="mt-5">Ubah Password</Button>
        </Card>
      </div>
    </div>
  </div>
</template>

<script setup>
import Breadcrumb from '@/components/ui/Breadcrumb.vue'
import Card from '@/components/ui/Card.vue'
import Button from '@/components/ui/Button.vue'
import Input from '@/components/ui/Input.vue'
import Label from '@/components/ui/Label.vue'
import Badge from '@/components/ui/Badge.vue'
</script>