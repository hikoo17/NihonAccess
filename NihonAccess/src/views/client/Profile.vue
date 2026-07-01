<template>
  <div class="mx-auto max-w-4xl space-y-8">
    <!-- Header halaman -->
    <div>
      <Breadcrumb :items="[{ label: 'Dashboard', to: '/client/dashboard' }, { label: 'Profil Saya' }]" />
      <h1 class="mt-3 text-2xl font-extrabold tracking-tight text-slate-800 sm:text-3xl">Profil Saya</h1>
    </div>

    <!-- ===== BAGIAN 1: HEADER & BANNER PROFIL ===== -->
    <Card class="p-5 sm:p-6">
      <!-- Banner merah gradasi bertema Jepang -->
      <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#d94545] via-[#cf3d3d] to-[#9f2b2b] px-6 py-8 sm:px-10 sm:py-10">
        <!-- Ilustrasi gunung (siluet Fuji) bertema Jepang -->
        <svg class="pointer-events-none absolute inset-x-0 bottom-0 h-24 w-full text-black/10" viewBox="0 0 1200 200" preserveAspectRatio="none" fill="currentColor" aria-hidden="true">
          <path d="M0 200 L260 80 L380 130 L600 40 L820 140 L940 90 L1200 200 Z" />
          <path d="M560 60 L600 40 L640 60 L625 70 L612 64 L600 70 L588 64 L575 70 Z" fill="white" fill-opacity="0.45" />
        </svg>
        <!-- Aksen lingkaran matahari -->
        <div class="pointer-events-none absolute -right-12 -top-12 h-44 w-44 rounded-full bg-white/10"></div>

        <!-- Konten banner -->
        <div class="relative flex flex-col items-center gap-5 text-center sm:flex-row sm:items-center sm:gap-6 sm:text-left">
          <!-- Foto profil lingkaran berbingkai putih -->
          <div class="flex h-24 w-24 shrink-0 items-center justify-center rounded-full bg-white p-1.5 shadow-xl ring-4 ring-white/25 sm:h-28 sm:w-28">
            <div class="flex h-full w-full items-center justify-center rounded-full bg-gradient-to-br from-[#cf3d3d] to-[#9f2b2b] text-3xl font-extrabold text-white">
              BS
            </div>
          </div>

          <!-- Nama + badge + metadata -->
          <div class="min-w-0">
            <div class="flex flex-col items-center gap-2 sm:flex-row sm:items-center">
              <h2 class="truncate text-2xl font-extrabold tracking-tight text-white sm:text-3xl">Budi Santoso</h2>
              <Badge variant="info" size="sm">Member</Badge>
            </div>

            <!-- Metadata kecil -->
            <div class="mt-2 flex flex-wrap items-center justify-center gap-x-4 gap-y-1 text-xs font-medium text-white/80 sm:justify-start">
              <span class="inline-flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3.5 w-3.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                </svg>
                Bergabung 12 Jan 2024
              </span>
              <span class="inline-flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3.5 w-3.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                </svg>
                Jakarta, Indonesia
              </span>
              <span class="text-base leading-none" title="Indonesia">🇮🇩</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Baris tombol aksi horizontal (masih dalam card putih utama) -->
      <div class="mt-4 flex flex-wrap items-center gap-3">
        <Button variant="default" size="sm">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
          </svg>
          Edit Profil
        </Button>
        <Button variant="outline" size="sm">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
          </svg>
          Ganti Password
        </Button>
        <Button variant="outline" size="sm">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
          </svg>
          Upload Foto
        </Button>
      </div>
    </Card>

    <!-- ===== BAGIAN 2: DUA KOLOM INFORMASI ===== -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
      <!-- Kolom Kiri: Informasi Akun -->
      <Card class="p-6">
        <div class="mb-5 border-b border-slate-100 pb-4">
          <h2 class="text-sm font-extrabold text-slate-800">Informasi Akun</h2>
          <p class="mt-0.5 text-xs text-slate-400">Data sensitif yang terhubung dengan akun kamu.</p>
        </div>

        <ul class="space-y-4">
          <!-- Email -->
          <li class="flex items-center gap-4">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#cf3d3d]/10 text-[#cf3d3d]">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
              </svg>
            </div>
            <div class="min-w-0 flex-1">
              <p class="text-xs font-medium text-slate-400">Email</p>
              <p class="truncate text-sm font-bold text-slate-800">budi@email.com</p>
            </div>
          </li>

          <!-- Username -->
          <li class="flex items-center gap-4">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#cf3d3d]/10 text-[#cf3d3d]">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
              </svg>
            </div>
            <div class="min-w-0 flex-1">
              <p class="text-xs font-medium text-slate-400">Username</p>
              <p class="truncate text-sm font-bold text-slate-800">nihon_xxxxx</p>
            </div>
          </li>

          <!-- Nomor WhatsApp -->
          <li class="flex items-center gap-4">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#cf3d3d]/10 text-[#cf3d3d]">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
              </svg>
            </div>
            <div class="min-w-0 flex-1">
              <p class="text-xs font-medium text-slate-400">Nomor WhatsApp</p>
              <p class="truncate text-sm font-bold text-slate-800">0812-3456-7890</p>
            </div>
          </li>
        </ul>
      </Card>

      <!-- Kolom Kanan: Keamanan -->
      <Card class="p-6">
        <div class="mb-5 border-b border-slate-100 pb-4">
          <h2 class="text-sm font-extrabold text-slate-800">Keamanan</h2>
          <p class="mt-0.5 text-xs text-slate-400">Pengaturan untuk menjaga akun tetap aman.</p>
        </div>

        <ul class="space-y-4">
          <!-- Password -->
          <li class="flex items-center gap-4">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#cf3d3d]/10 text-[#cf3d3d]">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
              </svg>
            </div>
            <div class="min-w-0 flex-1">
              <p class="text-xs font-medium text-slate-400">Password</p>
              <p class="truncate text-sm font-bold text-slate-800">••••••••</p>
            </div>
            <button class="shrink-0 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-600 transition-colors hover:border-[#cf3d3d]/40 hover:bg-slate-50 hover:text-[#cf3d3d]">
              Ubah
            </button>
          </li>

          <!-- Two-Factor Authentication -->
          <li class="flex items-center gap-4">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#cf3d3d]/10 text-[#cf3d3d]">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div class="min-w-0 flex-1">
              <div class="flex items-center gap-2">
                <p class="text-xs font-medium text-slate-400">Two-Factor Authentication</p>
                <Badge variant="warning" size="sm">Belum Aktif</Badge>
              </div>
              <p class="truncate text-sm font-bold text-slate-800">Tambah lapisan keamanan ekstra</p>
            </div>
            <button class="shrink-0 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-600 transition-colors hover:border-[#cf3d3d]/40 hover:bg-slate-50 hover:text-[#cf3d3d]">
              Aktifkan
            </button>
          </li>

          <!-- Terakhir Login -->
          <li class="flex items-center gap-4">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#cf3d3d]/10 text-[#cf3d3d]">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div class="min-w-0 flex-1">
              <p class="text-xs font-medium text-slate-400">Terakhir Login</p>
              <p class="truncate text-sm font-bold text-slate-800">01 Jul 2026, 08:30 WIB</p>
            </div>
            <button class="shrink-0 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-600 transition-colors hover:border-[#cf3d3d]/40 hover:bg-slate-50 hover:text-[#cf3d3d]">
              Lihat Semua
            </button>
          </li>
        </ul>
      </Card>
    </div>
  </div>
</template>

<script setup>
import Breadcrumb from '@/components/ui/Breadcrumb.vue'
import Card from '@/components/ui/Card.vue'
import Button from '@/components/ui/Button.vue'
import Badge from '@/components/ui/Badge.vue'
</script>