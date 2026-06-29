import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'
import PackageDetail from '../views/PackageDetail.vue'
import Register from '../views/Register.vue'
import CheckEmailVerification from '../views/CheckEmailVerification.vue'
import Login from '../views/Login.vue'

const packageTitles = {
  basic: 'Paket Basic Online',
  premium: 'Paket Premium Online',
  private: 'Paket Private Online',
  reguler: 'Paket Basic On-Site',
  intensive: 'Paket Premium On-Site',
  corporate: 'Paket Private Corporate'
}

const routes = [
  { path: '/', name: 'Home', component: Home, meta: { title: 'NihonAccess' } },
  { path: '/paket/:type', name: 'PackageDetail', component: PackageDetail, props: true, meta: { titlePrefix: 'Detail' } },
  { path: '/daftar/:type', name: 'Register', component: Register, props: true, meta: { titlePrefix: 'Pendaftaran' } },
  { path: '/cek-email-verifikasi', name: 'CheckEmailVerification', component: CheckEmailVerification, meta: { title: 'Cek Email Verifikasi | NihonAccess' } },
  { path: '/login', name: 'Login', component: Login, meta: { title: 'Login | NihonAccess' } }
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior() {
    return { top: 0 }
  }
})

// --- PERBAIKAN LOGIKA DI SINI ---
router.afterEach((to) => {
  if (to.meta.title) {
    // Jika route punya meta.title langsung (seperti Home), pakai itu
    document.title = to.meta.title
  } else {
    // Jika route dinamis (Detail / Pendaftaran)
    const packageTitle = packageTitles[to.params.type] || 'Paket Kursus'
    const prefix = to.meta.titlePrefix ? `${to.meta.titlePrefix} ` : ''
    document.title = `${prefix}${packageTitle} | NihonAccess`
  }
})

export default router
