<template>
  <header 
    id="beranda"
    class="relative overflow-hidden px-6 py-20 lg:py-28 min-h-[650px] flex items-center"
  >
    <div class="absolute inset-0 bg-gradient-to-br from-[#fdf6f3] via-[#fef5f0] to-[#fff0e8]"></div>
    
    <div 
      class="absolute inset-0 opacity-90 will-change-transform" 
      :style="{ 
        backgroundImage: `url('/bg-hero.png')`, 
        backgroundSize: 'cover', 
        backgroundPosition: 'center',
        transform: `translate3d(0, ${scrollY * 0.4}px, 0)`
      }"
    ></div>

    <div 
      class="absolute inset-0 pointer-events-none overflow-hidden z-20 will-change-transform"
      :style="{ transform: `translate3d(0, ${scrollY * 0.15}px, 0)` }"
    >
      <div v-for="n in 6" :key="n" class="sakura-petal" :style="{ left: `${15 + (n * 13)}%`, animationDelay: `${(n - 1) * 1.5}s`, animationDuration: `${6 + (n % 3)}s` }"></div>
    </div>

    <div class="absolute inset-0 bg-gradient-to-r from-white/85 via-white/50 to-transparent lg:from-white/80 lg:via-white/30 lg:to-transparent pointer-events-none z-10"></div>

    <div class="absolute top-20 right-10 w-96 h-96 bg-[#cf3d3d]/15 rounded-full blur-3xl pointer-events-none will-change-transform" :style="{ transform: `translate3d(0, ${scrollY * 0.2}px, 0)` }"></div>
    <div class="absolute bottom-10 right-32 w-72 h-72 bg-slate-300/25 rounded-full blur-3xl pointer-events-none will-change-transform" :style="{ transform: `translate3d(0, ${scrollY * 0.3}px, 0)` }"></div>
    <div class="absolute top-1/3 -left-8 w-80 h-80 bg-slate-200/25 rounded-full blur-3xl pointer-events-none will-change-transform" :style="{ transform: `translate3d(0, ${scrollY * 0.1}px, 0)` }"></div>

    <div class="relative mx-auto max-w-7xl w-full z-10">
      <div class="grid lg:grid-cols-12 gap-8 lg:gap-16 items-center">
        
        <div class="lg:col-span-7 space-y-5 lg:space-y-6 text-center lg:text-left lg:pl-8">
          <span data-aos="fade-up" data-aos-delay="100" class="inline-block rounded-full bg-[#cf3d3d]/10 px-4 py-1.5 text-sm font-semibold text-[#cf3d3d] shadow-sm backdrop-blur-sm">
            一緒に日本語を学びましょう！
          </span>
          
          <h1 data-aos="fade-up" data-aos-delay="200" class="text-4xl font-extrabold tracking-tight text-slate-900 sm:text-5xl lg:text-6xl leading-tight">
            Belajar Bahasa Jepang <br />
            <span class="text-[#cf3d3d] relative inline-block">
              untuk Pemula
            </span>
          </h1>
          
          <p data-aos="fade-up" data-aos-delay="300" class="mx-auto max-w-xl text-lg text-slate-700 font-medium leading-relaxed lg:mx-0">
            Kursus lengkap mulai dari nol hingga kamu bisa membaca, menulis, dan ngobrol pakai bahasa Jepang.
          </p>

          <div data-aos="fade-up" data-aos-delay="400" class="flex flex-wrap justify-center gap-4 lg:justify-start">
            <a 
              href="#harga"
              class="inline-flex items-center gap-2 rounded-full bg-[#cf3d3d] px-8 py-3.5 font-bold text-white shadow-lg shadow-[#cf3d3d]/20 hover:bg-[#b83232] hover:scale-105 transition-all duration-300 group"
            >
              Lihat Paket
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 transition-transform group-hover:translate-x-1">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
              </svg>
            </a>
            <a 
              href="https://wa.me/089524398977" 
              target="_blank"
              class="inline-flex items-center gap-2 rounded-full border-2 border-slate-200 bg-white px-8 py-3.5 font-bold text-slate-700 hover:border-slate-300 hover:bg-slate-50 hover:scale-105 transition-all duration-300"
            >
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-whatsapp w-4 h-4 text-slate-700" viewBox="0 0 16 16">
                <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.948h.003c4.368 0 7.928-3.559 7.928-7.929a7.89 7.89 0 0 0-2.324-5.593m-5.607 11.366a6.52 6.52 0 0 1-3.303-.905l-.237-.14-2.454.644.655-2.39-.153-.244a6.52 6.52 0 0 1-1.006-3.411c0-3.595 2.926-6.521 6.521-6.521 1.74 0 3.376.679 4.607 1.912a6.52 6.52 0 0 1 1.912 4.613c0 3.595-2.926 6.521-6.521 6.521m3.614-4.81c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
              </svg>
              Konsultasi Gratis
            </a>
          </div>

          <div 
            data-aos="fade-up" 
            data-aos-delay="450" 
            data-aos-anchor="#beranda"
            class="mt-4 pt-3 border-t border-slate-200/60 max-w-md mx-auto lg:mx-0"
          ></div>

          <div 
            data-aos="fade-up" 
            data-aos-delay="500" 
            data-aos-anchor="#beranda"
            class="flex items-center justify-center lg:justify-start gap-8"
          >
            <div>
              <p class="text-[11px] font-bold text-slate-400 tracking-widest uppercase">Kursus Tersedia</p>
              <p class="text-xl lg:text-2xl font-extrabold text-slate-900 mt-0.5">6 Paket</p>
            </div>
            
            <div class="h-8 w-[1px] bg-slate-200 self-end mb-1"></div>
            
            <div>
              <p class="text-[11px] font-bold text-slate-400 tracking-widest uppercase">Siswa Terdaftar</p>
              <p class="text-xl lg:text-2xl font-extrabold text-slate-900 mt-0.5">1.200+</p>
            </div>
          </div>
        </div>
        
        <div class="lg:col-span-5 relative w-full min-h-[150px] lg:min-h-[450px]"></div>

      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import AOS from 'aos'
import 'aos/dist/aos.css'

const scrollY = ref(0)
let ticking = false

const handleScroll = () => {
  if (!ticking) {
    window.requestAnimationFrame(() => {
      scrollY.value = window.scrollY
      ticking = false
    })
    ticking = true
  }
}

onMounted(() => {
  AOS.init({
    duration: 800,
    once: true,
    easing: 'ease-out-cubic'
  })

  window.addEventListener('scroll', handleScroll, { passive: true })
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
})
</script>

<style scoped>
.will-change-transform {
  will-change: transform;
}

.sakura-petal {
  position: absolute;
  top: -20px;
  width: 14px;
  height: 10px;
  background: linear-gradient(to bottom right, #ffb7c5, #ffa3b1);
  border-radius: 50% 0 50% 50%;
  opacity: 0;
  animation: fall-and-sway linear infinite;
}

@keyframes fall-and-sway {
  0% {
    top: -20px;
    opacity: 0;
    transform: translate3d(0, 0, 0) rotate(0deg) scale(0.8);
  }
  10% { opacity: 0.85; }
  100% {
    top: 105%;
    opacity: 0;
    transform: translate3d(120px, 0, 0) rotate(540deg) scale(1);
  }
}
</style>