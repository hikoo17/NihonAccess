import { ref, onUnmounted } from 'vue'
import { teacherApi } from '@/services/teacherApi'

export function useAiGeneration() {
  const generating = ref(false)
  const genError = ref('')
  let timer = null

  const stopPolling = () => {
    if (timer) {
      clearInterval(timer)
      timer = null
    }
  }

  const start = async ({ type, payload, toDraft }) => {
    generating.value = true
    genError.value = ''
    try {
      const res = await teacherApi.aiGenerations.create({ type, ...payload })
      const uuid = res.data.uuid

      const result = await new Promise((resolve, reject) => {
        timer = setInterval(async () => {
          try {
            const s = (await teacherApi.aiGenerations.status(uuid)).data
            if (s.status === 'completed') {
              stopPolling()
              resolve(s.result)
            } else if (s.status === 'failed') {
              stopPolling()
              reject(new Error(s.error || 'Gagal membuat soal.'))
            }
          } catch (e) {
            stopPolling()
            reject(e)
          }
        }, 2500)
      })

      return typeof toDraft === 'function' ? toDraft(result) : result
    } catch (e) {
      genError.value = e.message || 'Terjadi kesalahan.'
      throw e
    } finally {
      generating.value = false
    }
  }

  onUnmounted(stopPolling)

  return { generating, genError, start, stopPolling }
}
