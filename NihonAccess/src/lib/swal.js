import Swal from 'sweetalert2'

const BRAND = '#cf3d3d'

const modal = ({ icon = 'info', title, text = '', ...rest }) =>
  Swal.fire({
    icon,
    title,
    text,
    confirmButtonColor: BRAND,
    ...rest,
  })

const toast = (icon, title, rest = {}) =>
  Swal.fire({
    toast: true,
    position: 'top-end',
    icon,
    title,
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    ...rest,
  })

const confirm = (rest = {}) =>
  Swal.fire({
    showCancelButton: true,
    confirmButtonColor: BRAND,
    cancelButtonColor: '#94a3b8',
    ...rest,
  })

export { modal as fireAlert, toast as fireToast, confirm as fireConfirm }

export default Swal
