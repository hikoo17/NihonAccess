<template>
  <!-- Navbar Utama -->
  <nav
    class="fixed top-0 left-0 w-full z-50 transition-all duration-300"
    :class="[
      props.simple
        ? 'bg-white shadow-[0_4px_20px_rgba(15,23,42,0.05)] py-3'
        : '',
      !props.simple && isPastHero && !isMenuOpen
        ? 'bg-white shadow-[0_4px_20px_rgba(15,23,42,0.05)] py-3'
        : '',
      !props.simple && !isPastHero && !isMenuOpen ? 'bg-transparent py-6' : '',
      isMenuOpen ? 'bg-white shadow-none py-3 border-b border-slate-100' : '',
    ]"
  >
    <div
      class="mx-auto flex max-w-7xl items-center justify-between px-6 sm:px-8 relative z-50"
    >
      <!-- Sisi Kiri: Logo -->
      <div
        class="flex items-center group cursor-pointer"
        @click="closeMenuAndNavigate('/')"
      >
        <div class="h-8 relative flex items-center">
          <img
            src="/logo.png"
            alt="Nihon Access Logo"
            class="h-full w-auto object-contain"
          />
        </div>
      </div>

      <!-- Sisi Tengah: Teks Navigasi Desktop -->
      <div
        v-if="!props.simple"
        class="hidden md:flex items-center gap-2 text-sm font-bold transition-colors duration-300"
        :class="isPastHero ? 'text-slate-600' : 'text-slate-800'"
      >
        <a
          href="#beranda"
          class="nav-link"
          :class="{ active: activeSection === 'beranda' }"
          >Beranda</a
        >
        <a
          href="#fitur"
          class="nav-link"
          :class="{ active: activeSection === 'fitur' }"
          >Tentang Kursus</a
        >
        <a
          href="#harga"
          class="nav-link"
          :class="{ active: activeSection === 'harga' }"
          >Paket & Harga</a
        >
        <a
          href="#testimoni"
          class="nav-link"
          :class="{ active: activeSection === 'testimoni' }"
          >Testimoni</a
        >
        <a
          href="#faq"
          class="nav-link"
          :class="{ active: activeSection === 'faq' }"
          >FAQ</a
        >
      </div>

      <!-- Sisi Kanan: Action Button Desktop & Hamburger Mobile -->
      <div class="flex items-center gap-4">
        <!-- Tombol Desktop (Diubah ke Daftar Sekarang) -->
        <div class="hidden md:block" v-if="!props.simple">
          <button
            @click="router.push('/daftar/basic')"
            class="relative overflow-hidden rounded-full px-6 py-2.5 text-sm font-extrabold tracking-wide shadow-md transition-all duration-300 hover:scale-105 bg-[#cf3d3d] text-white group"
          >
            <span
              class="absolute inset-0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000 bg-gradient-to-r from-transparent via-white/20 to-transparent skew-x-12"
            ></span>
            <span class="relative z-10">Daftar Sekarang</span>
          </button>
        </div>

        <!-- Tombol Hamburger Standar Tanpa Animasi -->
        <button
          v-if="!props.simple"
          @click="isMenuOpen = !isMenuOpen"
          type="button"
          class="inline-flex md:hidden items-center justify-center p-2 rounded-xl text-slate-800 hover:bg-slate-50 focus:outline-none transition-colors"
          aria-label="Toggle Menu"
        >
          <!-- Ikon Close saat menu buka -->
          <svg
            v-if="isMenuOpen"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="2"
            stroke="currentColor"
            class="w-6 h-6"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M6 18 18 6M6 6l12 12"
            />
          </svg>
          <!-- Ikon Hamburger saat menu tutup -->
          <svg
            v-else
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="2"
            stroke="currentColor"
            class="w-6 h-6"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"
            />
          </svg>
        </button>
      </div>
    </div>

    <!-- Dropdown Menu Mobile: Full-screen ke Bawah -->
    <div
      v-if="!props.simple && isMenuOpen"
      class="md:hidden fixed inset-0 top-[57px] h-[calc(100vh-57px)] w-full bg-white z-40 overflow-y-auto"
    >
      <div
        class="flex flex-col px-6 pt-6 pb-12 space-y-5 font-bold text-slate-700 text-base"
      >
        <a
          href="#beranda"
          @click="isMenuOpen = false"
          class="py-2.5 border-b border-slate-50 transition-colors"
          :class="
            activeSection === 'beranda'
              ? 'text-[#cf3d3d]'
              : 'hover:text-[#cf3d3d]'
          "
          >Beranda</a
        >
        <a
          href="#fitur"
          @click="isMenuOpen = false"
          class="py-2.5 border-b border-slate-50 transition-colors"
          :class="
            activeSection === 'fitur'
              ? 'text-[#cf3d3d]'
              : 'hover:text-[#cf3d3d]'
          "
          >Tentang Kursus</a
        >
        <a
          href="#harga"
          @click="isMenuOpen = false"
          class="py-2.5 border-b border-slate-50 transition-colors"
          :class="
            activeSection === 'harga'
              ? 'text-[#cf3d3d]'
              : 'hover:text-[#cf3d3d]'
          "
          >Paket & Harga</a
        >
        <a
          href="#testimoni"
          @click="isMenuOpen = false"
          class="py-2.5 border-b border-slate-50 transition-colors"
          :class="
            activeSection === 'testimoni'
              ? 'text-[#cf3d3d]'
              : 'hover:text-[#cf3d3d]'
          "
          >Testimoni</a
        >
        <a
          href="#faq"
          @click="isMenuOpen = false"
          class="py-2.5 border-b border-slate-50 transition-colors"
          :class="
            activeSection === 'faq' ? 'text-[#cf3d3d]' : 'hover:text-[#cf3d3d]'
          "
          >FAQ</a
        >

        <!-- Tombol Action Utama Mobile (Diubah ke Daftar Sekarang) -->
        <div class="pt-4">
          <button
            @click="closeMenuAndNavigate('/daftar/basic')"
            class="block w-full text-center rounded-full py-3 text-sm font-extrabold tracking-wide bg-[#cf3d3d] text-white shadow-md active:scale-98 transition-all"
          >
            Daftar Sekarang
          </button>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed, watch } from "vue";
import { useRouter } from "vue-router";

const props = defineProps({
  simple: { type: Boolean, default: false },
});

const router = useRouter();
const isPastHero = ref(false);
const isMenuOpen = ref(false);
const activeSection = ref("beranda");
let heroElement = null;

const sectionIds = [
  "beranda",
  "kepercayaan",
  "fitur",
  "harga",
  "testimoni",
  "faq",
];

// Cegah window agar tidak bisa di-scroll saat menu mobile full-screen terbuka
watch(isMenuOpen, (newVal) => {
  if (newVal) {
    document.body.style.overflow = "hidden";
  } else {
    document.body.style.overflow = "";
  }
});

const handleScroll = () => {
  if (heroElement) {
    const heroRect = heroElement.getBoundingClientRect();
    isPastHero.value = heroRect.top < -20;
  }

  let current = "beranda";
  for (const id of sectionIds) {
    const el = document.getElementById(id);
    if (el) {
      const rect = el.getBoundingClientRect();
      if (rect.top <= 150) {
        current = id;
      }
    }
  }
  activeSection.value = current;
};

const closeMenuAndNavigate = (path) => {
  isMenuOpen.value = false;
  router.push(path);
};

onMounted(() => {
  heroElement = document.getElementById("beranda");
  window.addEventListener("scroll", handleScroll, { passive: true });
  handleScroll();
});

onUnmounted(() => {
  window.removeEventListener("scroll", handleScroll);
  document.body.style.overflow = "";
});
</script>

<style scoped>
.nav-link {
  position: relative;
  padding: 0.5rem 0.85rem;
  transition: color 0.3s ease;
}

.nav-link::after {
  content: "";
  position: absolute;
  bottom: 0px;
  left: 50%;
  width: 0;
  height: 2px;
  background-color: #cf3d3d;
  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
  transform: translateX(-50%);
  border-radius: 99px;
}

.nav-link:hover::after,
.nav-link.active::after {
  width: 16px;
}

.nav-link:hover,
.nav-link.active {
  color: #cf3d3d;
}
</style>
