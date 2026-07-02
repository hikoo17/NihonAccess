// src/lib/form.js
// Class input form standar teacher. Superset (ada disabled styles) aman untuk semua form.

export function formInputClass(err) {
  return [
    'w-full rounded-2xl border bg-white px-4 py-3 text-sm font-medium text-slate-800 placeholder:text-slate-400 transition focus:outline-none focus:ring-2 disabled:bg-slate-50 disabled:text-slate-400',
    err ? 'border-[#cf3d3d] focus:border-[#cf3d3d] focus:ring-[#cf3d3d]/20' : 'border-slate-200 focus:border-[#cf3d3d] focus:ring-[#cf3d3d]/20',
  ]
}