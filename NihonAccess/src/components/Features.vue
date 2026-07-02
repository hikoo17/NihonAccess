<template>
  <section class="section-flow-white relative overflow-hidden py-24" id="fitur" data-aos="fade-up">
    <div class="relative mx-auto max-w-7xl px-6 sm:px-8">
      
      <!-- Header Section -->
      <div class="text-left max-w-3xl mb-16">
        <span class="inline-flex items-center gap-3 text-xs font-extrabold uppercase tracking-[0.22em] text-[#cf3d3d]">
          <span class="h-px w-10 bg-[#cf3d3d]"></span>
          Mengapa Memilih Kami?
        </span>
        <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl lg:text-5xl">
          Belajar Jadi Lebih Menyenangkan
        </h2>
        <p class="mt-4 text-lg text-slate-600 font-medium leading-relaxed">
          Kami menyediakan pengalaman belajar terbaik dengan materi terstruktur dan pendekatan yang interaktif.
        </p>
      </div>
      
      <!-- Grid Layout -->
      <div class="grid lg:grid-cols-12 gap-12 lg:gap-16 items-center">
        
        <!-- Kolom Kiri: Ilustrasi Gambar (Bagian yang sebelumnya terpotong) -->
        <div class="lg:col-span-5 flex justify-center" data-aos="fade-right">
            <div class="relative p-4 border-2 border-dashed border-slate-200 rounded-[2.5rem] bg-slate-50/50 max-w-sm sm:max-w-md w-full aspect-square flex items-center justify-center transition-all duration-500 ease-out hover:-rotate-3 hover:scale-[1.02] hover:shadow-[0_20px_40px_rgba(207,61,61,0.12)] group">
            
            <div class="w-full h-full rounded-[2rem] overflow-hidden bg-slate-100 border border-slate-100 shadow-sm">
              <img 
                src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=800&q=80" 
                alt="Learning Concept" 
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out"
              />
            </div>

            <!-- Badge Bintang Melayang -->
            <div class="absolute -top-3 -right-3 bg-[#cf3d3d] shadow-md shadow-[#cf3d3d]/20 rounded-2xl p-3 flex items-center justify-center border border-white/60 transition-transform duration-500 group-hover:rotate-12">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-white">
                <path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/>
              </svg>
            </div>
          </div>
        </div>

        <!-- Kolom Kanan: List Fitur Berulang -->
        <div class="lg:col-span-7 space-y-6" data-aos="fade-left">
          <div 
            v-for="(feature, idx) in features" 
            :key="idx" 
            class="group relative rounded-2xl bg-white p-4 sm:p-5 border border-slate-100/70 hover:shadow-[0_10px_30px_rgba(15,23,42,0.04)] transition-all duration-300 flex items-start gap-4"
          >
            <!-- Wadah Ikon -->
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#cf3d3d] text-white group-hover:bg-slate-100 group-hover:text-[#cf3d3d] transition-all duration-300 shadow-sm shadow-[#cf3d3d]/15">
              <svg 
                xmlns="http://www.w3.org/2000/svg" 
                viewBox="0 0 24 24" 
                fill="none" 
                stroke="currentColor" 
                stroke-width="2" 
                stroke-linecap="round" 
                stroke-linejoin="round" 
                class="w-5 h-5"
                v-html="feature.iconRaw"
              ></svg>
            </div>
            
            <!-- Konten Teks Fitur -->
            <div class="space-y-1">
              <h3 class="font-bold text-slate-900 text-base sm:text-lg group-hover:text-[#cf3d3d] transition-colors duration-300">
                {{ feature.title }}
              </h3>
              <p class="text-sm text-slate-500 font-medium leading-relaxed">
                {{ feature.desc }}
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

const features = ref([
  { 
    title: 'Materi Lengkap', 
    desc: 'Mulai dari huruf dasar Jepang (Hiragana, Katakana, Kanji) sampai tata bahasa dan percakapan sehari-hari.',
    iconRaw: '<path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M6 6h10M6 10h10"/>'
  },
  { 
    title: 'Video Pembelajaran', 
    desc: 'Akses video berkualitas dengan penjelasan yang sangat mudah dipahami.',
    iconRaw: '<path d="m22 8-6 4 6 4V8Z"/><rect width="14" height="12" x="2" y="6" rx="2" ry="2"/>'
  },
  { 
    title: 'Latihan & Quiz', 
    desc: 'Latihan interaktif di setiap modul untuk mengukur pemahamanmu.',
    iconRaw: '<path d="M12 20h9M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/>'
  },
  { 
    title: 'Sertifikat', 
    desc: 'Dapatkan sertifikat resmi kelulusan setelah menyelesaikan kursus.',
    iconRaw: '<path d="M12 14a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/>'
  },
  { 
    title: 'Dukungan Belajar', 
    desc: 'Konsultasi & tanya jawab kapan saja langsung bersama tim pengajar.',
    iconRaw: '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>'
  }
])
</script>
