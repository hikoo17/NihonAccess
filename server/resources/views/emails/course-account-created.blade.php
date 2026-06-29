<p>Halo {{ $user->name }},</p>

<p>Pembayaran kursus Nihon Access Anda telah berhasil. Berikut kuitansi singkat dan detail akun belajar Anda:</p>

<table cellpadding="6" cellspacing="0" border="0">
    <tr>
        <td>Order ID</td>
        <td><strong>{{ $user->id }}</strong></td>
    </tr>
    <tr>
        <td>Paket</td>
        <td><strong>{{ $user->package_type }}</strong></td>
    </tr>
    <tr>
        <td>Status</td>
        <td><strong>{{ $user->payment_status }}</strong></td>
    </tr>
    <tr>
        <td>Username</td>
        <td><strong>{{ $user->username }}</strong></td>
    </tr>
    <tr>
        <td>Password</td>
        <td><strong>{{ $plainPassword }}</strong></td>
    </tr>
</table>

<p>Silakan login dan segera ubah password Anda setelah berhasil masuk. Jangan membagikan detail akun ini kepada orang lain.</p>

<p>Terima kasih,<br>Nihon Access</p>
