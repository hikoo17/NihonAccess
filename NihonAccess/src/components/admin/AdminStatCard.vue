<!-- components/admin/AdminStatCard.vue -->
<template>
  <div 
    :class="[
      'p-6 rounded-[2rem] shadow-sm flex flex-col justify-between relative min-h-[150px] transition-all border',
      isPrimary 
        ? 'bg-[#cf3d3d] text-white border-transparent shadow-[#cf3d3d]/20 shadow-lg' 
        : 'bg-white border-slate-100 text-slate-800'
    ]"
  >
    <!-- Bagian Atas: Icon & Badge Tren -->
    <div class="flex items-center justify-between w-full mb-3">
      <div 
        :class="[
          'p-2.5 rounded-2xl flex items-center justify-center text-lg',
          isPrimary ? 'bg-white/20 text-white' : 'bg-[#cf3d3d]/10 text-[#cf3d3d]'
        ]"
      >
        <slot name="icon" />
      </div>

      <!-- Badge Tren Persentase -->
      <div 
        v-if="trend" 
        :class="[
          'px-2.5 py-1 rounded-full text-[11px] font-extrabold tracking-tight',
          trendType === 'up' 
            ? 'bg-emerald-100 text-emerald-700' 
            : 'bg-rose-100 text-rose-700'
        ]"
      >
        {{ trendType === 'up' ? '+' : '' }}{{ trend }}%
      </div>
    </div>

    <!-- Bagian Bawah: Label, Value, & Subteks Samping -->
    <div class="space-y-0.5">
      <p :class="['text-xs font-bold tracking-wide', isPrimary ? 'text-white/80' : 'text-slate-400']">
        {{ label }}
      </p>
      
      <div class="flex items-baseline gap-2 flex-wrap">
        <span class="text-2xl font-extrabold tracking-tight">
          {{ value }}
        </span>
        <span 
          v-if="subtext" 
          :class="['text-[11px] font-medium leading-tight max-w-[110px]', isPrimary ? 'text-white/60' : 'text-slate-400']"
        >
          {{ subtext }}
        </span>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  label: String,
  value: [String, Number],
  isPrimary: { type: Boolean, default: false },
  trend: String,
  trendType: { type: String, default: 'up' },
  subtext: String
})
</script>