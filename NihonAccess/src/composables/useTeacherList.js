// src/composables/useTeacherList.js
// Pola list teacher: fetch + pagination + konfirmasi hapus.
// Cocok untuk view dengan api.{list, remove} standar (Characters/Quiz/Listening/Writing).

import { ref } from 'vue'
import { useTeacherToast } from './useTeacherToast'

export function useTeacherList({ api, label, labelKey = 'title', queryFor }) {
  const { success, error } = useTeacherToast()

  const loading = ref(true)
  const items = ref([])
  const meta = ref(null)
  const page = ref(1)

  const confirmId = ref(null)
  const confirmLabel = ref('')
  const deleting = ref(false)

  const togglingId = ref(null)

  const toggleActive = async (row) => {
    if (togglingId.value || !api.toggleActive) return
    togglingId.value = row.id
    const newState = !row.is_active
    row.is_active = newState
    try {
      await api.toggleActive(row.id, newState)
      success(`${label} ${newState ? 'diaktifkan' : 'dinonaktifkan'}.`)
    } catch (e) {
      row.is_active = !newState
      error(e.message || `Gagal mengubah status ${label.toLowerCase()}.`)
    } finally {
      togglingId.value = null
    }
  }

  const fetchItems = async () => {
    loading.value = true
    try {
      const extra = queryFor ? queryFor() : {}
      const res = await api.list({ page: page.value, per_page: 10, ...extra })
      items.value = res.data
      meta.value = res.meta
    } catch (e) {
      error(e.message || `Gagal memuat ${label.toLowerCase()}.`)
    } finally {
      loading.value = false
    }
  }

  const onPage = (p) => {
    page.value = p
    fetchItems()
  }

  const askDelete = (row) => {
    confirmId.value = row.id
    confirmLabel.value = row[labelKey]
  }
  const cancelDelete = () => { confirmId.value = null }

  const confirmDelete = async () => {
    deleting.value = true
    try {
      await api.remove(confirmId.value)
      success(`${label} berhasil dihapus.`)
      confirmId.value = null
      await fetchItems()
    } catch (e) {
      error(e.message || `Gagal menghapus ${label.toLowerCase()}.`)
    } finally {
      deleting.value = false
    }
  }

  return {
    success, error,
    loading, items, meta, page,
    confirmId, confirmLabel, deleting,
    togglingId, toggleActive,
    fetchItems, onPage, askDelete, cancelDelete, confirmDelete,
  }
}