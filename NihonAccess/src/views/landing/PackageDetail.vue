<template>
  <div class="min-h-screen bg-slate-50/50">
    <Navbar simple />
    
    <section class="pt-24 pb-20 text-slate-900">
      <div class="mx-auto max-w-6xl px-6 sm:px-8">
        
        <Breadcrumb class="mb-8" :items="breadcrumbItems" />

        <div class="mb-10 max-w-3xl">
          <div class="mb-4 flex flex-wrap items-center gap-3">
            <span class="rounded-full bg-[#cf3d3d]/10 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-[#cf3d3d]">{{ packageInfo.badge }}</span>
            <span class="rounded-full bg-slate-100 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-slate-500">{{ packageInfo.format }}</span>
          </div>

          <h1 class="mb-3 text-3xl font-extrabold tracking-tight text-slate-800 sm:text-4xl">
            {{ packageInfo.pageTitle }}
          </h1>
          <p class="max-w-2xl text-base font-normal leading-relaxed text-slate-500">
            {{ packageInfo.description }}
          </p>
        </div>

        <div class="grid items-start gap-8 lg:grid-cols-3">
          <div class="lg:col-span-2 space-y-8">
            <div class="grid gap-4 sm:grid-cols-3">
              <Card v-for="(stat, idx) in packageInfo.highlights" :key="idx" class="p-5">
                <div class="text-xs font-bold uppercase tracking-wider text-slate-400">{{ stat.label }}</div>
                <div class="mt-2 text-lg font-extrabold text-slate-800">{{ stat.value }}</div>
              </Card>
            </div>

            <Card class="p-6 sm:p-8">
              <h3 class="mb-4 text-lg font-bold text-slate-800">Apa yang akan Anda Dapatkan</h3>
              <ul class="space-y-3">
                <li v-for="(feature, idx) in packageInfo.features" :key="idx" class="flex items-start gap-3">
                  <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-white font-bold text-sm mt-0.5">✓</span>
                  <span class="text-sm leading-relaxed text-slate-500">{{ feature }}</span>
                </li>
              </ul>
            </Card>

            <Card class="p-6 sm:p-8">
              <h3 class="mb-4 text-lg font-bold text-slate-800">Komponen Paket</h3>
              <div class="space-y-4">
                <div v-for="(item, idx) in packageInfo.modules" :key="idx" class="border-l-4 border-[#cf3d3d] pl-4 py-2">
                  <h4 class="font-bold text-slate-800">{{ item.name }}</h4>
                  <p class="mt-0.5 text-sm text-slate-500">{{ item.description }}</p>
                </div>
              </div>
            </Card>
          </div>

          <div class="lg:col-span-1">
            <Card class="sticky top-28 p-6 sm:p-8">
              <div class="text-center mb-6">
                <div class="mb-2 text-xs font-bold uppercase tracking-wider text-slate-400">Harga Paket</div>
                <div class="text-4xl font-extrabold text-[#cf3d3d]">{{ packageInfo.price }}</div>
                <div class="mt-2 text-sm font-medium text-slate-500">{{ packageInfo.priceNote }}</div>
              </div>

              <div class="mb-6 space-y-3 rounded-2xl border border-slate-100 bg-slate-50 p-4 text-sm text-slate-500">
                <div class="flex items-center justify-between gap-4">
                  <span>Durasi</span>
                  <span class="font-bold text-slate-900">{{ packageInfo.duration }}</span>
                </div>
                <div class="flex items-center justify-between gap-4">
                  <span>Level</span>
                  <span class="font-bold text-slate-900">{{ packageInfo.level }}</span>
                </div>
              </div>
                
              <Button 
                @click="goToRegistrationPage"
                class="w-full bg-[#cf3d3d] hover:bg-[#b03333] text-white py-3 font-bold text-base transition-all"
              >
                {{ props.type === 'corporate' ? 'Hubungi Admin' : 'Daftar Sekarang' }}
              </Button>

              <p class="mt-4 text-xs text-center text-slate-500 leading-relaxed">
                Silakan isi formulir data diri pendaftaran terlebih dahulu sebelum melanjutkan ke proses pembayaran resmi.
              </p>
            </Card>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import Navbar from '../../components/Navbar.vue'
import Breadcrumb from '../../components/ui/Breadcrumb.vue'
import Button from '../../components/ui/Button.vue'
import Card from '../../components/ui/Card.vue'

const props = defineProps({
  type: { type: String, required: true }
})

const router = useRouter()

// Mengarahkan user langsung ke form pendaftaran dengan membawa parameter tipe paket
const goToRegistrationPage = () => {
  if (props.type === 'corporate') {
    // Jalur khusus jika paket corporate (bisa langsung arahkan ke WhatsApp karena harga custom)
    window.open('https://wa.me/nomorkamu?text=Halo%20Nihon%20Access,%20saya%20tertarik%20dengan%20Paket%20Private%20Corporate', '_blank')
  } else {
    // Paket reguler/online langsung dibawa ke halaman register di database
    router.push(`/daftar/${props.type}`)
  }
}

const breadcrumbItems = computed(() => [
  { label: 'Beranda', to: '/' },
  { label: packageInfo.value?.pageTitle || 'Detail Paket' }
])

const packageInfo = computed(() => {
  const packages = {
    basic: {
      title: "Basic",
      pageTitle: "Paket Basic Online",
      badge: "Online Starter",
      format: "Belajar Mandiri",
      description:
        "Belajar melalui video pembelajaran berkualitas tinggi dengan akses selamanya. Cocok untuk yang mandiri.",
      price: "Rp99.000",
      priceNote: "Sekali bayar untuk akses 1 bulan",
      duration: "1 bulan",
      level: "Pemula",
      highlights: [
        { label: "Materi", value: "Huruf & kosakata" },
        { label: "Akses", value: "Web learning" },
        { label: "Latihan", value: "500+ soal" },
      ],
      features: [
        "Akses video Hiragana & Katakana lengkap",
        "Materi PDF interaktif untuk latihan",
        "Bank soal latihan 500+ pertanyaan",
        "Akses materi selama 1 bulan",
        "Grup komunitas belajar via Discord",
      ],
      modules: [
        {
          name: "Pengenalan Hiragana",
          description: "Belajar 46 huruf dasar dengan audio native speaker",
        },
        {
          name: "Pengenalan Katakana",
          description: "Pelajari 46 huruf katakana dengan contoh penggunaan",
        },
        {
          name: "Kosakata Dasar",
          description: "Kumpulan 300+ kata umum dalam bahasa Jepang",
        },
      ],
      suitableFor: [
        "Siswa yang baru mulai dari nol dan ingin mengenal huruf Jepang.",
        "Pembelajar mandiri yang butuh materi ringkas dan bisa diulang kapan saja.",
        "Peserta yang ingin mencoba kursus sebelum masuk ke program mentor.",
        "Orang sibuk yang membutuhkan jadwal belajar fleksibel.",
      ],
    },
    premium: {
      title: "Premium",
      pageTitle: "Paket Premium Online",
      badge: "Online Mentoring",
      format: "Zoom + Materi Web",
      description:
        "Program intensif dengan mentor melalui Zoom. Cocok untuk yang ingin belajar secara langsung.",
      price: "Rp199.000",
      priceNote: "Termasuk sesi live mingguan",
      duration: "3 bulan",
      level: "N5 Dasar",
      highlights: [
        { label: "Mentoring", value: "Live Zoom" },
        { label: "Target", value: "JLPT N5" },
        { label: "Akses", value: "3 bulan" },
      ],
      features: [
        "Semua fitur Online Course",
        "Kanji dasar 200+ karakter",
        "Grammar JLPT N5 lengkap",
        "Latihan soal JLPT N5 siap ujian",
        "Akses materi selama 3 bulan",
        "Sesi tanya jawab mingguan via Zoom",
      ],
      modules: [
        {
          name: "Grammar Dasar",
          description: "Pola kalimat dasar hingga lanjutan JLPT N5",
        },
        {
          name: "Kanji Pilihan",
          description: "200 kanji dasar yang sering digunakan",
        },
        {
          name: "Simulasi Ujian",
          description: "Tryout rutin untuk persiapan JLPT N5",
        },
      ],
      suitableFor: [
        "Peserta yang ingin belajar dengan arahan mentor, bukan hanya video.",
        "Siswa yang menargetkan kemampuan setara JLPT N5.",
        "Pembelajar yang membutuhkan jadwal dan progres yang lebih terstruktur.",
        "Orang yang ingin rutin bertanya langsung saat menemukan materi sulit.",
      ],
    },
    private: {
      title: "Private",
      pageTitle: "Paket Private Online",
      badge: "1-on-1 Online",
      format: "Kelas Private",
      description:
        "Kelas online personal dengan tutor untuk menyesuaikan tempo, target, dan fokus belajar peserta.",
      price: "Rp499.000",
      priceNote: "Program personal dengan jadwal fleksibel",
      duration: "6 bulan",
      level: "Pemula - N5",
      highlights: [
        { label: "Kelas", value: "Private Zoom" },
        { label: "Jadwal", value: "Fleksibel" },
        { label: "Review", value: "Personal" },
      ],
      features: [
        "Semua fitur Bootcamp",
        "Kelas private via Zoom",
        "Konsultasi belajar 1-on-1",
        "Akses materi selama 6 bulan",
        "Jadwal fleksibel sesuai keinginan",
      ],
      modules: [
        {
          name: "Konsultasi Pribadi",
          description: "Sesi belajar individu dengan tutor berpengalaman",
        },
        {
          name: "Review Materi",
          description: "Evaluasi dan perbaikan pada materi yang sulit",
        },
        {
          name: "Persiapan Ujian",
          description: "Strategi khusus untuk menghadapi JLPT",
        },
      ],
      suitableFor: [
        "Peserta yang ingin perhatian penuh dari tutor.",
        "Pembelajar dengan target khusus seperti ujian, kerja, atau wawancara.",
        "Siswa yang membutuhkan jadwal belajar lebih fleksibel.",
        "Orang yang ingin progresnya dievaluasi secara personal.",
      ],
    },
    reguler: {
      title: "Basic On-Site",
      pageTitle: "Paket Basic On-Site",
      badge: "Tatap Muka",
      format: "Kelas Kelompok",
      description:
        "Belajar interaktif langsung tatap muka di ruang kelas bersama teman kelompok.",
      price: "Rp350.000",
      priceNote: "Termasuk modul cetak dan evaluasi",
      duration: "1 bulan",
      level: "Pemula",
      highlights: [
        { label: "Pertemuan", value: "2x/minggu" },
        { label: "Modul", value: "Cetak" },
        { label: "Evaluasi", value: "Bulanan" },
      ],
      features: [
        "Kelas tatap muka (seminggu 2x)",
        "Modul cetak dan merchandise",
        "Grup diskusi offline",
        "Evaluasi bulanan",
      ],
      modules: [
        {
          name: "Kelas Tatap Muka",
          description: "Belajar langsung di tempat dengan metode interaktif",
        },
        {
          name: "Praktik Langsung",
          description: "Pelajaran praktik dengan tutor profesional",
        },
        {
          name: "Evaluasi Rutin",
          description: "Penilaian mingguan untuk melacak kemajuan",
        },
      ],
      suitableFor: [
        "Siswa yang lebih nyaman belajar langsung di kelas.",
        "Peserta yang suka diskusi bersama teman kelompok.",
        "Pembelajar yang membutuhkan rutinitas pertemuan tetap.",
        "Orang yang ingin latihan pelafalan secara langsung.",
      ],
    },
    intensive: {
      title: "Premium On-Site",
      pageTitle: "Paket Premium On-Site",
      badge: "Kelas Intensif",
      format: "Tatap Muka Harian",
      description:
        "Akselerasi materi terstruktur padat dengan bimbingan harian yang dipandu penuh.",
      price: "Rp599.000",
      priceNote: "Untuk progres cepat dan jadwal padat",
      duration: "1 bulan intensif",
      level: "N5 Terarah",
      highlights: [
        { label: "Pertemuan", value: "Setiap hari" },
        { label: "Fasilitas", value: "Ruang belajar" },
        { label: "Tryout", value: "Offline" },
      ],
      features: [
        "Semua fitur Reguler On-Site",
        "Kelas tatap muka setiap hari",
        "Free-flow coffee & ruang belajar",
        "Simulasi JLPT offline",
        "Prioritas jadwal",
      ],
      modules: [
        { name: "Belajar Harian", description: "Pertemuan rutin 5x seminggu" },
        {
          name: "Simulasi Ujian",
          description: "Tryout intensif selama program",
        },
        {
          name: "Ruang Belajar",
          description: "Fasilitas belajar gratis selama program",
        },
      ],
      suitableFor: [
        "Peserta yang ingin mengejar target dalam waktu singkat.",
        "Siswa yang siap belajar dengan ritme intensif.",
        "Pembelajar yang membutuhkan lingkungan belajar fokus.",
        "Orang yang ingin persiapan lebih serius untuk ujian dasar.",
      ],
    },
    corporate: {
      title: "Private Corporate",
      pageTitle: "Paket Private Corporate",
      badge: "Corporate Training",
      format: "Program Custom",
      description:
        "Pelatihan kurikulum khusus bisnis skala korporat yang jadwalnya fleksibel.",
      price: "Hubungi Kami",
      priceNote: "Harga menyesuaikan jumlah peserta dan kebutuhan",
      duration: "Custom",
      level: "Sesuai kebutuhan",
      highlights: [
        { label: "Kurikulum", value: "Custom" },
        { label: "Lokasi", value: "Fleksibel" },
        { label: "Sertifikat", value: "Resmi" },
      ],
      features: [
        "Jadwal fleksibel",
        "Lokasi bisa disesuaikan",
        "Kurikulum khusus bisnis",
        "Sertifikat resmi pelatihan",
      ],
      modules: [
        {
          name: "Kurikulum Custom",
          description: "Materi disesuaikan dengan kebutuhan korporat",
        },
        {
          name: "Lokasi Fleksibel",
          description: "Bisa di kantor atau kami datang ke lokasi",
        },
        {
          name: "Sertifikat Resmi",
          description: "Sertifikat pelatihan untuk peserta",
        },
      ],
      suitableFor: [
        "Perusahaan yang membutuhkan pelatihan bahasa Jepang untuk tim.",
        "Universitas atau komunitas yang ingin program belajar khusus.",
        "Tim HR yang membutuhkan jadwal dan materi fleksibel.",
        "Organisasi yang ingin pelatihan dengan laporan progres peserta.",
      ],
    },
  };
  return packages[props.type] || packages.basic;
});
</script>
