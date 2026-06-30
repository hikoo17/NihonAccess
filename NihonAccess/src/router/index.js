import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/landing/Home.vue'
import PackageDetail from '../views/landing/PackageDetail.vue'
import Register from '../views/landing/Register.vue'
import CheckEmailVerification from '../views/landing/CheckEmailVerification.vue'
import Login from '../views/auth/Login.vue'
import TeacherDashboard from '../views/teacher/TeacherDashboard.vue'
import TeacherLayout from '../views/teacher/TeacherLayout.vue'
import AdminLayout from '../views/admin/AdminLayout.vue'
import AdminDashboard from '../views/admin/AdminDashboard.vue'
import AdminUsers from '../views/admin/AdminUsers.vue'
import AdminPackages from '../views/admin/AdminPackages.vue'
import AdminOrders from '../views/admin/AdminOrders.vue'
// import AdminSettings from '../views/admin/AdminSettings.vue'
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
      { path: 'courses', name: 'TeacherCourses', component: () => import('@/views/teacher/TeacherCourses.vue'), meta: { title: 'Course | Teacher' } },
      { path: 'courses/:courseId/lessons', name: 'TeacherLessons', component: () => import('@/views/teacher/TeacherLessons.vue'), props: true, meta: { title: 'Lesson | Teacher' } },
      { path: 'courses/:courseId/lessons/create', name: 'TeacherLessonCreate', component: () => import('@/views/teacher/TeacherLessonForm.vue'), props: true, meta: { title: 'Tambah Lesson | Teacher' } },
      { path: 'courses/:courseId/lessons/:id/edit', name: 'TeacherLessonEdit', component: () => import('@/views/teacher/TeacherLessonForm.vue'), props: true, meta: { title: 'Edit Lesson | Teacher' } },
      { path: 'quiz', name: 'TeacherQuiz', component: () => import('@/views/teacher/TeacherQuiz.vue'), meta: { title: 'Quiz | Teacher' } },
      { path: 'quiz/create', name: 'TeacherQuizCreate', component: () => import('@/views/teacher/TeacherQuizForm.vue'), meta: { title: 'Tambah Quiz | Teacher' } },
      { path: 'quiz/:id/edit', name: 'TeacherQuizEdit', component: () => import('@/views/teacher/TeacherQuizForm.vue'), props: true, meta: { title: 'Edit Quiz | Teacher' } },
      { path: 'characters', name: 'TeacherCharacters', component: () => import('@/views/teacher/TeacherCharacters.vue'), meta: { title: 'Karakter | Teacher' } },
      { path: 'characters/create', name: 'TeacherCharacterCreate', component: () => import('@/views/teacher/TeacherCharacterForm.vue'), meta: { title: 'Tambah Karakter | Teacher' } },
      { path: 'characters/:id/edit', name: 'TeacherCharacterEdit', component: () => import('@/views/teacher/TeacherCharacterForm.vue'), props: true, meta: { title: 'Edit Karakter | Teacher' } },
      { path: 'listening', name: 'TeacherListening', component: () => import('@/views/teacher/TeacherListening.vue'), meta: { title: 'Listening | Teacher' } },
      { path: 'listening/create', name: 'TeacherListeningCreate', component: () => import('@/views/teacher/TeacherListeningForm.vue'), meta: { title: 'Tambah Listening | Teacher' } },
      { path: 'listening/:id/edit', name: 'TeacherListeningEdit', component: () => import('@/views/teacher/TeacherListeningForm.vue'), props: true, meta: { title: 'Edit Listening | Teacher' } },
    ]
  },
  {
    path: '/admin',
    component: AdminLayout,
    meta: { requiresAuth: true, role: 'admin' },
    children: [
      { path: '', redirect: { name: 'AdminDashboard' } },
      { path: 'dashboard', name: 'AdminDashboard', component: AdminDashboard, meta: { title: 'Dashboard | Admin' } },
      { path: 'users', name: 'AdminUsers', component: AdminUsers, meta: { title: 'Manajemen User | Admin' } },
      { path: 'packages', name: 'AdminPackages', component: AdminPackages, meta: { title: 'Manajemen Paket | Admin' } },
      { path: 'orders', name: 'AdminOrders', component: AdminOrders, meta: { title: 'Pesanan & Pembayaran | Admin' } },
      // { path: 'settings', name: 'AdminSettings', component: AdminSettings, meta: { title: 'Pengaturan | Admin' } },
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
      { path: 'my-courses/:id/quiz', name: 'client-quiz', component: () => import('@/views/client/QuizView.vue'), props: true, meta: { title: 'Quiz | Client' } },
      { path: 'my-courses/:id/quiz/result', name: 'client-quiz-result', component: () => import('@/views/client/QuizResult.vue'), props: true, meta: { title: 'Hasil Quiz | Client' } },
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
