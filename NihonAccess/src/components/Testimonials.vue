<template>
  <section id="testimoni" class="section-flow-white relative overflow-hidden py-24" data-aos="fade-up">
    <div class="relative mx-auto max-w-7xl px-6 sm:px-8">
      <div class="red-depth relative overflow-hidden rounded-[2.5rem] px-6 py-12 text-white sm:px-12 sm:py-16">
        <div class="relative z-10 mx-auto max-w-3xl text-center">
          <span class="inline-block rounded-full bg-white/15 px-4 py-1.5 text-sm font-semibold text-white border border-white/20 backdrop-blur-sm">
            Testimoni
          </span>
          <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-white sm:text-4xl lg:text-5xl">
            Apa Kata Mereka yang Sudah Bergabung?
          </h2>
          <p class="mx-auto mt-4 max-w-2xl text-white/80 text-base sm:text-lg font-medium leading-relaxed">
            Ribuan siswa sudah merasakan perubahan nyata dan peningkatan kemampuan sejak belajar bersama kami.
          </p>
        </div>

        <div class="relative z-10 mx-auto mt-14 grid max-w-5xl gap-5 sm:grid-cols-2 lg:grid-cols-3">
        <div 
          v-for="(testi, idx) in testimonials" 
          :key="idx" 
          class="group rounded-2xl bg-white p-5 text-left text-slate-900 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
        >
          <div class="mb-4 flex items-center justify-between gap-3">
            <div class="flex items-center gap-1 text-amber-400">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4" v-for="star in 5" :key="star">
                <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd" />
              </svg>
            </div>
          </div>

          <p class="text-sm text-slate-600 font-medium leading-relaxed">
            "{{ testi.quote }}"
          </p>

          <div class="mt-5 flex items-center gap-3 border-t border-slate-100 pt-4">
            <div class="flex items-center gap-3">
              <img 
                :src="testi.avatar" 
                :alt="testi.name" 
                class="h-10 w-10 shrink-0 rounded-full object-cover ring-2 ring-slate-100"
              />
              <div>
                <h3 class="font-bold text-slate-900 text-sm sm:text-base group-hover:text-[#cf3d3d] transition-colors duration-300">
                  {{ testi.name }}
                </h3>
                <p class="text-[11px] text-slate-400 font-bold tracking-wide uppercase">
                  {{ testi.status }}
                </p>
              </div>
            </div>

          </div>
        </div>
      </div>
      </div>

    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue'

const testimonials = ref([
  { 
    metricLabel: 'Penguasaan Huruf', 
    quote: 'Penjelasannya mudah dipahami dan materinya sangat lengkap. Sekarang saya sudah lancar membaca Hiragana & Katakana!', 
    name: 'Siti Aminah', 
    status: 'SISWA PAKET BASIC',
    avatar: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=160&q=80'
  },
  { 
    metricLabel: 'Peningkatan Skor Tryout', 
    quote: 'Berkat kursus ini saya jadi jauh lebih siap menghadapi ujian JLPT N5. Bank soal latihannya ter-update dan sangat membantu!', 
    name: 'Dimas Kurniawan', 
    status: 'SISWA PAKET PREMIUM',
    avatar: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=160&q=80'
  },
  { 
    metricLabel: 'Waktu Belajar Efektif', 
    quote: 'Kelas private sangat membantu saya yang punya jadwal padat. Sensei-nya ramah dan metode penjelasannya sangat mendalam.', 
    name: 'Rina Mardiana', 
    status: 'SISWA PAKET PRIVATE',
    avatar: 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&w=160&q=80'
  },
  { 
    metricLabel: 'Durasi Praktek Kaiwa', 
    quote: 'Sesi interaktif langsung dengan native & mentor berpengalaman bikin saya tidak takut lagi buat ngomong pakai bahasa Jepang.', 
    name: 'Fahmi Idris', 
    status: 'ALUMNI N4 HUNTER',
    avatar: 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=160&q=80'
  },
  { 
    metricLabel: 'Pemahaman Bunpou', 
    quote: 'Kurikulumnya seru dan tidak membosankan. Pola kalimat (Grammar) yang rumit disederhanakan lewat analogi kehidupan sehari-hari.', 
    name: 'Aulia Rahma', 
    status: 'SISWA PAKET PREMIUM',
    avatar: 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=160&q=80'
  },
  { 
    metricLabel: 'Tingkat Kelulusan Ujian', 
    quote: 'Alur belajarnya bener-bener terarah dengan rapi. Saya berhasil lulus JLPT N5 dalam sekali coba berkat simulasi intensif di sini.', 
    name: 'Kevin Sanjaya', 
    status: 'ALUMNI JLPT N5',
    avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=160&q=80'
  }
])
</script>
