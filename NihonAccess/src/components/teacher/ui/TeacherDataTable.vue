<template>
  <!-- DESKTOP: tabel (md ke atas) -->
  <div class="hidden overflow-x-auto md:block">
    <table class="w-full text-left border-collapse">
      <thead>
        <tr class="border-b border-slate-200">
          <th
            v-for="col in columns"
            :key="col.key"
            :class="[
              'whitespace-nowrap px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-400',
              col.align === 'right' ? 'text-right' : 'text-left',
            ]"
          >
            {{ col.label }}
          </th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100 text-sm">
        <tr
          v-for="(item, idx) in items"
          :key="item.id ?? idx"
          class="transition-colors hover:bg-slate-50/70"
        >
          <td
            v-for="col in columns"
            :key="col.key"
            :class="[
              'px-6 py-4 align-middle',
              col.align === 'right' ? 'text-right' : 'text-left',
              col.cellClass || '',
            ]"
          >
            <slot :name="`cell-${col.key}`" :row="item" :index="idx">{{ item[col.key] }}</slot>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- MOBILE: kartu (di bawah md) -->
  <div v-if="$slots.mobile" class="divide-y divide-slate-100 md:hidden">
    <slot name="mobile" :items="items" />
  </div>
</template>

<script setup>
defineProps({
  items: { type: Array, default: () => [] },
  columns: { type: Array, required: true },
})
</script>