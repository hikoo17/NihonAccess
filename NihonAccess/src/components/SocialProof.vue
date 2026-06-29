<template>
  <section class="red-depth relative z-20 py-24 text-white">
    <div class="mx-auto max-w-7xl px-6 sm:px-8">
      
      <!-- Bagian Atas: Angka Statistik Keren -->
      <!-- Border diubah menggunakan putih transparan agar serasi dengan background kontras -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center pb-12 border-b border-white/20">
        <div data-aos="fade-up" data-aos-delay="100">
          <!-- Angka menggunakan putih solid -->
          <div class="text-3xl lg:text-4xl font-black text-white">1,200+</div>
          <div class="text-xs lg:text-sm font-bold text-white/80 tracking-wide uppercase mt-1">Siswa Aktif</div>
        </div>
        <div data-aos="fade-up" data-aos-delay="200">
          <div class="text-3xl lg:text-4xl font-black text-white">94.8%</div>
          <div class="text-xs lg:text-sm font-bold text-white/80 tracking-wide uppercase mt-1">Kelulusan N5</div>
        </div>
        <div data-aos="fade-up" data-aos-delay="300">
          <div class="text-3xl lg:text-4xl font-black text-white">10+</div>
          <div class="text-xs lg:text-sm font-bold text-white/80 tracking-wide uppercase mt-1">Native & N2 Tutor</div>
        </div>
        <div data-aos="fade-up" data-aos-delay="400">
          <div class="text-3xl lg:text-4xl font-black text-white">50+</div>
          <div class="text-xs lg:text-sm font-bold text-white/80 tracking-wide uppercase mt-1">Alumni di Jepang</div>
        </div>
      </div>

      <!-- Bagian Bawah: Logo Mitra -->
      <div class="pt-10">
        <p class="text-center text-xs font-bold uppercase tracking-widest text-white/90 mb-8">
          Telah Dipercaya & Mencetak Alumni untuk Perusahaan & Kampus Top
        </p>
        
        <!-- Opacity disesuaikan dan efek hover diubah ke putih solid agar menyala di atas bg gelap -->
        <div 
          data-aos="fade-up" 
          data-aos-delay="200" 
          class="flex flex-wrap items-center justify-center gap-8 md:gap-16 opacity-60 grayscale brightness-200 hover:grayscale-0 hover:opacity-100 hover:brightness-100 transition-all duration-500"
        >
          <span class="text-sm font-black tracking-widest text-white">KAMPUS MITRA</span>
          <span class="text-sm font-black tracking-widest text-white">PT JEPANG INDO</span>
          <span class="text-sm font-black tracking-widest text-white">ALUMNI CORP</span>
          <span class="text-sm font-black tracking-widest text-white">LKP JAPANESE</span>
        </div>
      </div>

    </div>
  </section>
</template>
