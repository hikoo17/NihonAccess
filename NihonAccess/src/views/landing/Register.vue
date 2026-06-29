<template>
  <div class="min-h-screen bg-slate-50/50">
    <Navbar simple />

    <section class="pt-24 pb-20">
      <div class="mx-auto max-w-6xl px-6 sm:px-8">
        <Breadcrumb class="mb-8" :items="breadcrumbItems" />

        <div class="mb-10 max-w-3xl">
          <div
            class="mb-4 inline-flex rounded-full bg-[#cf3d3d]/10 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-[#cf3d3d]"
          >
            Pendaftaran Kursus
          </div>
          <h1
            class="text-3xl font-extrabold tracking-tight text-slate-800 sm:text-4xl"
          >
            Formulir Pendaftaran
          </h1>
          <p
            class="mt-3 max-w-2xl text-base font-normal leading-relaxed text-slate-500"
          >
            Lengkapi data peserta untuk melanjutkan pemesanan paket kursus Anda.
          </p>
        </div>

        <div class="max-w-3xl">
          <Card class="p-6 sm:p-8">
            <div class="mb-6 border-b border-slate-100 pb-5">
              <h2 class="text-lg font-bold text-slate-800">Data Peserta</h2>
              <p class="mt-0.5 text-xs text-slate-400">
                Isi informasi utama untuk proses pendaftaran ke database server
              </p>
            </div>

            <form @submit.prevent="handleSubmit" class="space-y-6">
              <div class="grid gap-6 sm:grid-cols-2">
                <div>
                  <Label>Nama Lengkap</Label>
                  <Input
                    v-model="form.name"
                    type="text"
                    required
                    placeholder="Masukkan nama Anda"
                  />
                </div>

                <div>
                  <Label>Email</Label>
                  <Input
                    v-model="form.email"
                    type="email"
                    required
                    placeholder="nama@email.com"
                  />
                </div>
              </div>

              <div>
                <Label>Nomor WhatsApp</Label>
                <Input
                  v-model="form.whatsapp"
                  type="tel"
                  required
                  placeholder="08xx xxxx xxxx"
                />
              </div>

              <div>
                <Label>Paket Kursus</Label>
                <div 
                  :class="[
                    isPackageLocked 
                      ? 'pointer-events-none opacity-70 cursor-not-allowed [&_select]:bg-slate-100' 
                      : ''
                  ]"
                >
                  <Select
                    v-model="form.packageType"
                    :disabled="isPackageLocked"
                    required
                  >
                    <option value="">-- Pilih Paket Kursus --</option>
                    <option value="basic">Online Course (Basic) - Rp99.000</option>
                    <option value="premium">Bootcamp (Premium) - Rp199.000</option>
                    <option value="private">Partnership (Private) - Rp499.000</option>
                    <option value="reguler">Reguler On-Site - Rp350.000</option>
                    <option value="intensive">Intensive On-Site - Rp599.000</option>
                    <option value="corporate">Exclusive Corporate - Hubungi Kami</option>
                  </Select>
                </div>
                <p v-if="isPackageLocked" class="mt-1.5 text-xs text-slate-400">
                  Paket terkunci berdasarkan pilihan Anda sebelumnya dari halaman detail.
                </p>
              </div>

              <div class="pt-2">
                <Button
                  type="submit"
                  class="w-full bg-[#cf3d3d] hover:bg-[#b03333] text-white font-bold py-3"
                  :disabled="isSubmitting"
                >
                  {{ isSubmitting ? 'Menyimpan Data...' : 'Simpan Data & Lihat Pembayaran' }}
                </Button>
              </div>
            </form>
          </Card>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useRouter, useRoute } from "vue-router";
import Navbar from "../components/Navbar.vue";
import Breadcrumb from "../components/ui/Breadcrumb.vue";
import Button from "../components/ui/Button.vue";
import Card from "../components/ui/Card.vue";
import Input from "../components/ui/Input.vue";
import Label from "../components/ui/Label.vue";
import Select from "../components/ui/Select.vue"; // Di-import kembali
import { confirmSnapPayment, createMidtransTransaction, loadMidtransSnap, syncRegistrationPayment } from "../lib/midtrans.js";

const props = defineProps({
  type: { type: String, required: false, default: "" }, // Ubah ke optional untuk rute Navbar umum
});

const router = useRouter();
const route = useRoute();
const storageKey = "nihonaccess-registration-form";
const isSubmitting = ref(false);

// Cek apakah data paket harus dikunci (dikunci jika user datang membawa props type atau params dari detail)
const isPackageLocked = computed(() => {
  return !!props.type || !!route.params.type;
});

const getSavedForm = () => {
  try {
    const saved = localStorage.getItem(storageKey);
    return saved ? JSON.parse(saved) : {};
  } catch {
    return {};
  }
};

const savedData = getSavedForm();

const form = ref({
  name: savedData.name || "",
  email: savedData.email || "",
  whatsapp: savedData.whatsapp || "",
  packageType: props.type || route.params.type || savedData.packageType || "",
});

// Beranda > Pendaftaran
const breadcrumbItems = computed(() => [
  { label: "Beranda", to: "/" },
  { label: "Pendaftaran" },
]);

const handleSubmit = async () => {
  if (isSubmitting.value) return;
  isSubmitting.value = true;

  try {
    // 1. Kirim data formulir ke backend Laravel kamu
    const transaction = await createMidtransTransaction({
      name: form.value.name,
      email: form.value.email,
      whatsapp: form.value.whatsapp,
      package_type: form.value.packageType
    });

    // 2. Ambil token Midtrans yang dikirim balik oleh backend Laravel
    const midtransToken = transaction.token;
    const orderId = transaction.registration?.order_id;

    if (!midtransToken) {
      throw new Error("Snap Token Midtrans tidak diterima dari server.");
    }

    if (!orderId) {
      throw new Error("Order ID pendaftaran tidak diterima dari server.");
    }

    // 3. Simpan data lengkap + token ke localStorage (untuk backup jika web tertutup)
    localStorage.setItem(storageKey, JSON.stringify({
      ...form.value,
      order_id: orderId,
      payment_token: midtransToken
    }));

    alert("Data pendaftaran berhasil disimpan! Membuka gerbang pembayaran...");

    // 4. Muat library Midtrans Snap secara dinamis
    await loadMidtransSnap();

    // 5. TRIGGER POP-UP MIDTRANS SNAP LANGSUNG DI TEMPAT
    window.snap.pay(midtransToken, {
onSuccess: async (result) => {
        try {
          // Snap pop-up terkadang tidak memberikan signature_key di object result frontend
          // Jadi kita andalkan syncRegistrationPayment yang menembak ke status Midtrans resmi via backend
          let syncResult;
          
          if (result && result.signature_key) {
            syncResult = await confirmSnapPayment(result, orderId);
          } else {
            // Ini akan memicu method syncPaymentStatus di Laravel kamu
            syncResult = await syncRegistrationPayment(orderId);
          }

          // Pastikan backend merespons dengan status sukses
          if (syncResult && (syncResult.payment_status === 'success' || syncResult.status === 'success')) {
            localStorage.removeItem(storageKey);
            alert("Pembayaran berhasil diverifikasi!");
          } else {
            alert("Pembayaran berhasil, tetapi sistem sedang memverifikasi data Anda.");
          }
        } catch (error) {
          console.error("Gagal sinkronisasi data:", error);
          alert("Gagal mencocokkan status pembayaran. Silakan cek email Anda secara berkala.");
        } finally {
          isSubmitting.value = false;
          // Pindahkan ke halaman cek email setelah proses try-catch selesai dieksekusi
          router.push({ path: '/cek-email-verifikasi', query: { order_id: orderId } });
        }
      },
      onPending: (result) => {
        isSubmitting.value = false;
        alert('Pembayaran menunggu transfer. Anda bisa menutup halaman ini dan mengecek status nanti.');
        // Biarkan localStorage tetap ada agar banner pengingat nanti muncul
        router.push({ path: '/', hash: '#harga' });
      },
      onError: (result) => {
        isSubmitting.value = false;
        alert('Terjadi kesalahan pada proses pembayaran.');
      },
      onClose: () => {
        isSubmitting.value = false;
        alert('Anda menutup halaman pembayaran. Anda dapat melanjutkannya nanti melalui beranda.');
        // Redirect user ke Beranda utama
        router.push({ path: '/', hash: '#harga' });
      }
    });

  } catch (error) {
    isSubmitting.value = false;
    alert(error.message || "Gagal menginisialisasi pendaftaran dan pembayaran.");
  }
};
</script>
