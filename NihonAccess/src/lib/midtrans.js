const apiBaseUrl = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000'
const clientKey = import.meta.env.VITE_MIDTRANS_CLIENT_KEY
const isProduction = import.meta.env.VITE_MIDTRANS_ENV === 'production'

const snapUrl = isProduction
  ? 'https://app.midtrans.com/snap/snap.js'
  : 'https://app.sandbox.midtrans.com/snap/snap.js'

let snapPromise

export const loadMidtransSnap = () => {
  if (window.snap) {
    return Promise.resolve(window.snap)
  }

  if (!clientKey) {
    return Promise.reject(new Error('VITE_MIDTRANS_CLIENT_KEY belum diatur.'))
  }

  if (!snapPromise) {
    snapPromise = new Promise((resolve, reject) => {
      const existingScript = document.querySelector(`script[src="${snapUrl}"]`)

      if (existingScript) {
        existingScript.addEventListener('load', () => resolve(window.snap), { once: true })
        existingScript.addEventListener('error', () => reject(new Error('Gagal memuat Midtrans Snap.')), { once: true })
        return
      }

      const script = document.createElement('script')
      script.src = snapUrl
      script.setAttribute('data-client-key', clientKey)
      script.onload = () => resolve(window.snap)
      script.onerror = () => reject(new Error('Gagal memuat Midtrans Snap.'))
      document.body.appendChild(script)
    })
  }

  return snapPromise
}

export const createMidtransTransaction = async (payload) => {
  const response = await fetch(`${apiBaseUrl}/api/register-course`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(payload)
  })

  const data = await response.json().catch(() => ({}))

  if (!response.ok) {
    throw new Error(data.message || 'Gagal membuat transaksi Midtrans.')
  }

  return data
}

export const checkRegistrationStatus = async (orderId) => {
  const response = await fetch(`${apiBaseUrl}/api/registration/check-status/${orderId}`)
  const data = await response.json().catch(() => ({}))

  if (!response.ok && response.status !== 404) {
    throw new Error(data.message || 'Gagal mengecek status pendaftaran.')
  }

  return data
}

export const syncRegistrationPayment = async (orderId) => {
  const response = await fetch(`${apiBaseUrl}/api/registration/sync-payment/${orderId}`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    }
  })

  const data = await response.json().catch(() => ({}))

  if (!response.ok) {
    throw new Error(data.message || 'Gagal menyinkronkan status pembayaran.')
  }

  return data
}

export const confirmSnapPayment = async (snapResult, fallbackOrderId) => {
  const payload = {
    order_id: String(snapResult?.order_id || fallbackOrderId || ''),
    status_code: String(snapResult?.status_code || ''),
    gross_amount: String(snapResult?.gross_amount || ''),
    signature_key: String(snapResult?.signature_key || ''),
    transaction_status: String(snapResult?.transaction_status || ''),
    fraud_status: snapResult?.fraud_status || null,
  }

  const response = await fetch(`${apiBaseUrl}/api/registration/confirm-snap`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(payload)
  })

  const data = await response.json().catch(() => ({}))

  if (!response.ok) {
    throw new Error(data.message || 'Gagal mengonfirmasi hasil pembayaran Snap.')
  }

  return data
}
