<template>
  <div
    class="relative min-h-screen overflow-hidden bg-slate-50/50 font-sans text-slate-900 antialiased flex items-center justify-center px-4 py-12"
  >
    <!-- Ukuran diubah ke max-w-sm agar lebih ramping -->
    <div class="w-full max-w-sm">
      <!-- Padding disesuaikan (p-6 sm:p-8) agar proporsional dengan lebar yang lebih ramping -->
      <Card
        class="w-full p-6 shadow-[0_20px_50px_-12px_rgba(15,23,42,0.08)] sm:p-8 bg-white rounded-2xl border border-slate-100"
      >
        <!-- Logo -->
        <div class="mb-5 flex justify-center">
          <div class="h-10 transition-transform duration-300 hover:scale-105">
            <img
              src="/logo.png"
              alt="Nihon Access"
              class="h-full w-auto object-contain"
            />
          </div>
        </div>

        <!-- Judul & Deskripsi -->
        <div class="mb-6 text-center">
          <h1 class="font-extrabold tracking-tight text-slate-800 sm:text-2xl">
            Silakan Masuk
          </h1>
          <p class="mt-2 text-xs leading-relaxed text-slate-500">
            Gunakan username dan password yang dikirim ke email atau WhatsApp
            setelah pembayaran berhasil.
          </p>
        </div>

        <!-- Error Message -->
        <div
          v-if="errorMessage"
          class="mb-4 flex items-start gap-2 rounded-xl border border-red-200 bg-red-50 p-3 text-xs font-medium text-red-600"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.8"
            stroke="currentColor"
            class="mt-0.5 h-4 w-4 shrink-0"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"
            />
          </svg>
          <span>{{ errorMessage }}</span>
        </div>

        <!-- Form -->
        <form class="space-y-4" @submit.prevent="handleLogin">
          <div class="space-y-1.5">
            <Label class="text-xs font-semibold text-slate-700"
              >Username / Email</Label
            >
            <Input
              v-model="form.username"
              type="text"
              required
              placeholder="Username atau email"
              :disabled="isLoading"
              class="w-full text-sm"
            />
          </div>

          <div class="space-y-1.5">
            <Label class="text-xs font-semibold text-slate-700">Password</Label>
            <div class="relative">
              <Input
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                required
                placeholder="Password dari email"
                :disabled="isLoading"
                class="w-full text-sm pr-10"
              />
              <button
                type="button"
                @click="showPassword = !showPassword"
                tabindex="-1"
                class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 transition-colors"
                :aria-label="
                  showPassword ? 'Sembunyikan password' : 'Tampilkan password'
                "
              >
                <!-- Ikon mata (password tersembunyi) -->
                <svg
                  v-if="!showPassword"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.8"
                  stroke="currentColor"
                  class="h-4 w-4"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"
                  />
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                  />
                </svg>
                <!-- Ikon mata coret (password terlihat) -->
                <svg
                  v-else
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.8"
                  stroke="currentColor"
                  class="h-4 w-4"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 19.5 19.5M6.228 6.228 3 3"
                  />
                </svg>
              </button>
            </div>
          </div>

          <Button
            type="submit"
            class="w-full flex justify-center items-center gap-2 py-2.5 mt-2 text-sm font-medium transition-all duration-200 active:scale-[0.98]"
            :disabled="isLoading"
          >
            <svg
              v-if="isLoading"
              class="animate-spin h-4 w-4 text-white"
              fill="none"
              viewBox="0 0 24 24"
            >
              <circle
                class="opacity-25"
                cx="12"
                cy="12"
                r="10"
                stroke="currentColor"
                stroke-width="4"
              />
              <path
                class="opacity-75"
                fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
              />
            </svg>
            <span>{{ isLoading ? "Memproses..." : "Login" }}</span>
          </Button>
        </form>

        <!-- Footer Note -->
        <p class="mt-5 text-center text-[11px] leading-relaxed text-slate-400">
          Jika belum menerima akun, cek folder spam email dan pesan WhatsApp
          dari Nihon Access.
        </p>
      </Card>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import Button from "../../components/ui/Button.vue";
import Card from "../../components/ui/Card.vue";
import Input from "../../components/ui/Input.vue";
import Label from "../../components/ui/Label.vue";

const apiBaseUrl = import.meta.env.VITE_API_BASE_URL || "http://localhost:8000";
const router = useRouter();
const isLoading = ref(false);
const errorMessage = ref("");
const showPassword = ref(false);   // ← tambahkan ini


const form = ref({
  username: "",
  password: "",
});

const handleLogin = async () => {
  isLoading.value = true;
  errorMessage.value = "";

  try {
    const response = await fetch(`${apiBaseUrl}/api/auth/login`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        username: form.value.username,
        password: form.value.password,
      }),
    });

    const data = await response.json().catch(() => ({}));

    if (!response.ok || !data.success) {
      errorMessage.value = data.message || "Kredensial salah.";
      return;
    }

    const payload = data.data;

    localStorage.setItem("auth_token", payload.token);
    localStorage.setItem("user_role", payload.user.role);
    localStorage.setItem("user_data", JSON.stringify(payload.user));

    if (payload.user.role === "admin") {
      router.push({ name: "AdminDashboard" });
    } else if (payload.user.role === "teacher") {
      router.push({ name: "TeacherDashboard" });
    } else {
      router.push({ name: "client-dashboard" });
    }
  } catch (error) {
    console.error("Login Error:", error);
    errorMessage.value =
      "Tidak dapat terhubung ke server. Periksa koneksi Anda.";
  } finally {
    isLoading.value = false;
  }
};
</script>
