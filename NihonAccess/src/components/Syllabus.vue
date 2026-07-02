<template>
  <section id="alur-pembelian" class="section-flow-white relative overflow-hidden py-24" data-aos="fade-up">
    
    <div class="relative mx-auto max-w-7xl px-6 sm:px-8">
      
      <div class="red-depth relative text-white rounded-[2.5rem] px-6 py-12 sm:px-12 sm:py-16 overflow-hidden">
        
        <div class="relative z-10 text-center max-w-3xl mx-auto mb-16">
          <span class="inline-block rounded-full bg-white/15 px-4 py-1.5 text-sm font-bold text-white backdrop-blur-sm border border-white/20">
            Cara Mendaftar
          </span>
          <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-white sm:text-4xl lg:text-5xl">
            4 Langkah Pembelian Paket
          </h2>
          <p class="mt-4 text-lg text-white/80 font-medium leading-relaxed">
            Ikuti panduan praktis ini untuk mengamankan slot belajar kamu. Prosesnya cepat, aman, dan mudah diikuti.
          </p>
        </div>
        
        <div class="relative z-10 grid grid-cols-1 gap-12 sm:grid-cols-4 sm:gap-8">
          
          <div class="hidden sm:block absolute top-7 left-[10%] right-[10%] h-0.5 border-t-2 border-dashed border-white/35 -z-10"></div>

          <div 
            v-for="(step, idx) in steps" 
            :key="idx" 
            class="group relative flex flex-col items-center text-center"
          >
            
            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-white text-[#cf3d3d] font-extrabold text-lg border-4 border-white/70 shadow-[0_4px_10px_rgba(255,255,255,0.18)] group-hover:bg-slate-100 group-hover:scale-110 group-hover:shadow-[0_10px_20px_rgba(255,255,255,0.22)] transition-all duration-300">
              {{ String(idx + 1).padStart(2, '0') }}
            </div>

            <div class="mt-6 max-w-[220px] sm:max-w-none">
              <h3 class="text-base sm:text-lg font-bold text-white tracking-tight leading-snug group-hover:text-slate-100 transition-colors duration-300">
                {{ step.title }}
              </h3>
              <p class="mt-2.5 text-xs sm:text-sm text-white/75 font-medium leading-relaxed">
                {{ step.desc }}
              </p>
            </div>

          </div>
        </div>

      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue'

const steps = ref([
  {
    title: 'Pilih Paket',
    desc: 'Telusuri pilihan paket di beranda, lalu klik "Lihat Detail" untuk mengetahui fitur, modul, dan harga lengkapnya.'
  },
  {
    title: 'Isi Formulir Pendaftaran',
    desc: 'Klik "Daftar Sekarang" dan lengkapi data peserta: nama, email, serta nomor WhatsApp yang aktif.'
  },
  {
    title: 'Simpan Data & Bayar',
    desc: 'Tekan "Simpan Data & Lihat Pembayaran". Halaman pembayaran akan langsung terbuka — pilih metode favoritmu: QRIS, transfer bank, atau e-wallet.'
  },
  {
    title: 'Verifikasi & Mulai Belajar',
    desc: 'Setelah pembayaran sukses, kamu diarahkan ke halaman verifikasi. Akun dibuat otomatis dan username beserta password dikirim ke email/WhatsApp untuk langsung memulai kelas.'
  }
])
</script>