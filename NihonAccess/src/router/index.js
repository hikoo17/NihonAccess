import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/landing/Home.vue'
import PackageDetail from '../views/landing/PackageDetail.vue'
import Register from '../views/landing/Register.vue'
import CheckEmailVerification from '../views/landing/CheckEmailVerification.vue'
import Login from '../views/auth/Login.vue'
import TeacherLayout from '../views/teacher/TeacherLayout.vue'
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
  { path: '/login', name: 'Login', component: Login, meta: { title: 'Login | NihonAccess' } },
  {    path: '/teacher',
    component: TeacherLayout,
    meta: { requiresAuth: true, role: 'teacher' },
    children: [
      { path: '', redirect: { name: 'TeacherDashboard' } },
      { path: 'dashboard', name: 'TeacherDashboard', component: TeacherDashboard, meta: { title: 'Dashboard | Teacher' } },
      // { path: 'courses', name: 'TeacherCourses', component: TeacherCourses, meta: { title: 'Kursus | Teacher' } },
      // { path: 'lessons', name: 'TeacherLessons', component: TeacherLessons, meta: { title: 'Pelajaran | Teacher' } },
      // { path: 'quiz', name: 'TeacherQuiz', component: TeacherQuiz, meta: { title: 'Quiz | Teacher' } },
      // { path: 'characters', name: 'TeacherCharacters', component: TeacherCharacters, meta: { title: 'Karakter | Teacher' } },
      // { path: 'listening', name: 'TeacherListening', component: TeacherListening, meta: { title: 'Listening | Teacher' } },
      // { path: 'writing', name: 'TeacherWriting', component: TeacherWriting, meta: { title: 'Writing | Teacher' } },
    ]
  }, 
  {
    path: '/client',
    component: () => import('@/components/Client/ClientShell.vue'),
    meta: { requiresAuth: true },
    children: [
      { path: '', redirect: '/client/dashboard' },
      { path: 'dashboard', name: 'client-dashboard', component: () => import('@/views/client/Dashboard.vue'), meta: { title: 'Dashboard | Client' } },
      { path: 'my-courses', name: 'client-my-courses', component: () => import('@/views/client/MyCourses.vue'), meta: { title: 'Kursus Saya | Client' } },
      { path: 'my-courses/:id/learn', name: 'client-learn', component: () => import('@/views/client/LearnView.vue'), props: true, meta: { title: 'Belajar | Client' } },
      { path: 'packages', name: 'client-packages', component: () => import('@/views/client/PackageList.vue'), meta: { title: 'Beli Paket | Client' } },
      { path: 'packages/:slug', name: 'client-package-detail', component: () => import('@/views/client/PackageDetail.vue'), props: true, meta: { title: 'Detail Paket | Client' } },
      { path: 'checkout/:packageId', name: 'client-checkout', component: () => import('@/views/client/Checkout.vue'), props: true, meta: { title: 'Checkout | Client' } },
      { path: 'payment-status/:orderId', name: 'client-payment-status', component: () => import('@/views/client/PaymentStatus.vue'), props: true, meta: { title: 'Status Pembayaran | Client' } },
      { path: 'orders', name: 'client-orders', component: () => import('@/views/client/OrderHistory.vue'), meta: { title: 'Riwayat Pesanan | Client' } },
      { path: 'profile', name: 'client-profile', component: () => import('@/views/client/Profile.vue'), meta: { title: 'Profil Saya | Client' } },
    ],
  }

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
