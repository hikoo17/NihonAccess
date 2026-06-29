<template>
  <footer class="relative overflow-hidden bg-slate-950 text-slate-300 text-sm">
    <div class="relative mx-auto max-w-7xl px-6 py-20 sm:px-8 grid gap-10 lg:grid-cols-4">
      <div class="space-y-4">
        <div class="flex items-center gap-3 text-white">
          <img 
            src="/logo.png" 
            alt="Nihon Access Logo" 
            class="h-8 w-auto object-contain"
          />
        </div>
        <p class="text-slate-400 leading-relaxed">Platform kursus Bahasa Jepang online terpercaya untuk pemula. Belajar seru, terstruktur, dan efisien.</p>
        <div class="flex gap-3 pt-2">
          <a href="#" aria-label="Instagram" class="flex h-9 w-9 items-center justify-center rounded-full bg-white/5 text-slate-300 hover:bg-[#cf3d3d] hover:text-white transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
              <rect x="3" y="3" width="18" height="18" rx="5" ry="5" />
              <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37Z" />
              <path d="M17.5 6.5h.01" />
            </svg>
          </a>
          <a href="#" aria-label="TikTok" class="flex h-9 w-9 items-center justify-center rounded-full bg-white/5 text-slate-300 hover:bg-[#cf3d3d] hover:text-white transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
              <path d="M9 18a4 4 0 1 1 4-4V4c1.2 2.5 3 4 6 4" />
              <path d="M13 14V4" />
            </svg>
          </a>
          <a href="#" aria-label="WhatsApp" class="flex h-9 w-9 items-center justify-center rounded-full bg-white/5 text-slate-300 hover:bg-[#cf3d3d] hover:text-white transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
              <path d="M20 11.5a8 8 0 0 1-11.8 7L4 20l1.5-4.1A8 8 0 1 1 20 11.5Z" />
              <path d="M9 9.5c.5 2 2 3.5 4 4l1.2-1.2 2 1c-.3 1.1-1.1 1.7-2.1 1.7-3.4 0-6.1-2.7-6.1-6.1 0-1 .6-1.8 1.7-2.1l1 2L9 9.5Z" />
            </svg>
          </a>
        </div>
      </div>
      <div>
        <h4 class="text-white font-semibold mb-5 text-base">Menu</h4>
        <ul class="space-y-3">
          <li><a href="#beranda" class="hover:text-red-400 transition-colors text-slate-400 hover:pl-1 duration-200">Beranda</a></li>
          <li><a href="#fitur" class="hover:text-red-400 transition-colors text-slate-400 hover:pl-1 duration-200">Tentang Kursus</a></li>
          <li><a href="#harga" class="hover:text-red-400 transition-colors text-slate-400 hover:pl-1 duration-200">Paket & Harga</a></li>
          <li><a href="#testimoni" class="hover:text-red-400 transition-colors text-slate-400 hover:pl-1 duration-200">Testimoni</a></li>
          <li><a href="#faq" class="hover:text-red-400 transition-colors text-slate-400 hover:pl-1 duration-200">FAQ</a></li>
        </ul>
      </div>
      <div>
        <h4 class="text-white font-semibold mb-5 text-base">Kontak Kami</h4>
        <ul class="space-y-3 text-slate-400">
          <li class="flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-[#cf3d3d]"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.12.9.33 1.77.63 2.61a2 2 0 0 1-.45 2.11L8 9.73a16 16 0 0 0 6 6l1.29-1.29a2 2 0 0 1 2.11-.45c.84.3 1.71.51 2.61.63A2 2 0 0 1 22 16.92Z" /></svg>
            <span>0812-3456-7890</span>
          </li>
          <li class="flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-[#cf3d3d]"><path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7" /><rect x="2" y="4" width="20" height="16" rx="2" /></svg>
            <span>info@japanesecourse.id</span>
          </li>
          <li class="flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-[#cf3d3d]"><path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 1 1 16 0Z" /><circle cx="12" cy="10" r="3" /></svg>
            <span>Jakarta, Indonesia</span>
          </li>
        </ul>
      </div>
      <div>
        <h4 class="text-white font-semibold mb-5 text-base">Newsletter</h4>
        <p class="text-slate-400 mb-4">Dapatkan info terbaru seputar kursus Jepang langsung ke emailmu.</p>
        <div class="flex gap-2">
          <input type="email" placeholder="email@kamu.com" class="flex-1 rounded-full bg-white/5 border border-white/10 px-4 py-2 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-red-500/50 transition-colors" />
          <button class="rounded-full bg-[#cf3d3d] px-4 py-2 text-white font-semibold hover:bg-[#b83232] transition-colors">Kirim</button>
        </div>
      </div>
    </div>
    <div class="relative border-t border-slate-800 py-6 text-center text-xs text-slate-500">
      &copy; 2026 Japanese Course for Beginner. All rights reserved.
    </div>
  </footer>
</template>
