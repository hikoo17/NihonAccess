<template>
  <div class="mx-auto max-w-7xl space-y-6 animate-fadeIn">
    <div>
      <Breadcrumb
        :items="[
          { label: 'Dashboard', to: { name: 'AdminDashboard' } },
          { label: 'Pesanan & Pembayaran' },
        ]"
      />
      <h1
        class="mt-3 text-2xl font-extrabold tracking-tight text-slate-800 sm:text-3xl"
      >
        Pesanan & Pembayaran
      </h1>
      <p class="mt-1 text-sm text-slate-500">
        Pantau seluruh transaksi pendaftaran kursus siswa.
      </p>
    </div>

    <!-- Filter -->
    <div
      class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
    >
      <div class="relative max-w-xs flex-1">
        <Input
          v-model="search"
          placeholder="Cari order ID atau nama user..."
          @input="onSearchInput"
        />
      </div>
      <div class="flex flex-wrap items-center gap-2">
        <button
          v-for="level in statusFilters"
          :key="level.value"
          @click="setStatus(level.value)"
          class="rounded-full px-4 py-2 text-xs font-bold transition"
          :class="
            activeStatus === level.value
              ? 'bg-[#cf3d3d] text-white shadow-md shadow-[#cf3d3d]/10'
              : 'bg-white text-slate-500 border border-slate-200 hover:border-[#cf3d3d]/40'
          "
        >
          {{ level.label }}
        </button>
      </div>
    </div>

    <!-- Table -->
    <div
      class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden"
    >
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr
              class="text-xs font-bold text-slate-400 border-b border-slate-100 bg-slate-50/50"
            >
              <th class="p-4 px-6">Order ID</th>
              <th class="p-4 px-6">User</th>
              <th class="p-4 px-6">Paket</th>
              <th class="p-4 px-6">Jumlah</th>
              <th class="p-4 px-6">Status</th>
              <th class="p-4 px-6">Dibayar Pada</th>
              <th class="p-4 px-6">Waktu</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-50 text-sm">
            <tr v-if="isLoading">
              <td colspan="7" class="p-12 text-center">
                <div
                  class="mx-auto h-7 w-7 animate-spin rounded-full border-2 border-slate-200 border-t-[#cf3d3d]"
                ></div>
              </td>
            </tr>
            <tr v-else-if="orders.length === 0">
              <td colspan="7" class="p-12 text-center text-sm text-slate-400">
                Tidak ada pesanan ditemukan.
              </td>
            </tr>
            <tr
              v-for="(order, i) in orders"
              :key="i"
              class="hover:bg-slate-50 transition-colors"
            >
              <td class="p-4 px-6 font-mono text-xs text-slate-600">
                {{ order.order_number }}
              </td>
              <td class="p-4 px-6">
                <div class="font-bold text-slate-800">
                  {{ order.user_name || "-" }}
                </div>
                <div class="text-xs text-slate-400">{{ order.user_email }}</div>
              </td>
              <td class="p-4 px-6 text-slate-700">
                {{ order.package_name || "-" }}
              </td>
              <td class="p-4 px-6 font-bold text-slate-800">
                {{ order.amount_formatted }}
              </td>
              <td class="p-4 px-6">
                <span :class="statusBadge(order.status)">{{
                  order.status
                }}</span>
              </td>
              <td class="p-4 px-6 text-slate-500 text-xs">
                {{ order.paid_at || "-" }}
              </td>
              <td class="p-4 px-6 text-slate-400 text-xs">
                {{ order.created_at }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div
        v-if="!isLoading && orders.length > 0"
        class="flex flex-col gap-3 border-t border-slate-100 px-6 py-4 sm:flex-row sm:items-center sm:justify-between"
      >
        <p class="text-xs text-slate-400">
          Menampilkan {{ orders.length }} dari {{ meta.total }} pesanan
        </p>
        <div class="flex items-center gap-2">
          <button
            @click="changePage(meta.current_page - 1)"
            :disabled="meta.current_page <= 1"
            class="rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-600 transition hover:border-[#cf3d3d]/40 hover:text-[#cf3d3d] disabled:opacity-40"
          >
            Sebelumnya
          </button>
          <span
            class="rounded-xl bg-slate-50 px-3 py-1.5 text-xs font-bold text-slate-600"
          >
            {{ meta.current_page }} / {{ meta.last_page }}
          </span>
          <button
            @click="changePage(meta.current_page + 1)"
            :disabled="meta.current_page >= meta.last_page"
            class="rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-600 transition hover:border-[#cf3d3d]/40 hover:text-[#cf3d3d] disabled:opacity-40"
          >
            Berikutnya
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import Breadcrumb from "@/components/ui/Breadcrumb.vue";
import Input from "@/components/ui/Input.vue";
import { fireToast } from "@/lib/swal.js";

const apiBaseUrl = import.meta.env.VITE_API_BASE_URL || "http://localhost:8000";

const isLoading = ref(true);
const orders = ref([]);
const meta = ref({ current_page: 1, last_page: 1, per_page: 10, total: 0 });
const search = ref("");
const activeStatus = ref("all");

const statusFilters = [
  { label: "Semua", value: "all" },
  { label: "Dibayar", value: "paid" },
  { label: "Pending", value: "pending" },
  { label: "Expired", value: "expired" },
  { label: "Gagal", value: "failed" },
];

let searchTimer = null;
const onSearchInput = () => {
  clearTimeout(searchTimer);
  searchTimer = setTimeout(() => fetchOrders(1), 350);
};

const setStatus = (value) => {
  activeStatus.value = value;
  fetchOrders(1);
};

const changePage = (page) => {
  if (page < 1 || page > meta.value.last_page) return;
  fetchOrders(page);
};

const statusBadge = (status) => {
  const map = {
    paid: "px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700",
    pending:
      "px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700",
    expired:
      "px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500",
    cancelled:
      "px-3 py-1 rounded-full text-xs font-bold bg-[#cf3d3d]/10 text-[#cf3d3d]",
    failed:
      "px-3 py-1 rounded-full text-xs font-bold bg-[#cf3d3d]/10 text-[#cf3d3d]",
  };
  return (
    map[status] ||
    "px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500"
  );
};

const flash = (type, message) => fireToast(type, message);

const fetchOrders = async (page = meta.value.current_page) => {
  isLoading.value = true;
  try {
    const token = localStorage.getItem("auth_token");
    const params = new URLSearchParams({
      page: String(page),
      per_page: String(meta.value.per_page),
    });
    if (search.value.trim()) params.set("search", search.value.trim());
    if (activeStatus.value !== "all") params.set("status", activeStatus.value);

    const res = await fetch(
      `${apiBaseUrl}/api/admin/orders?${params.toString()}`,
      {
        headers: { Authorization: `Bearer ${token}` },
      },
    );
    const data = await res.json().catch(() => ({}));

if (res.ok && data.success) {
  // Ambil array user langsung dari data.data
  orders.value = data.data || []
  
  // Ambil meta dari data.meta
  meta.value = data.meta || {
    current_page: 1,
    last_page: 1,
    per_page: 10,
    total: 0
  }
} else {
  flash("error", data.message || "Gagal memuat data pesanan.");
  }
  } catch (e) {
    flash("error", "Tidak dapat terhubung ke server.");
  } finally {
    isLoading.value = false;
  }
};

onMounted(() => fetchOrders(1));
</script>

<style scoped>
.animate-fadeIn {
  animation: fadeIn 0.35s ease-out forwards;
}
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(4px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
