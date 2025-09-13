<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }} | Reset Akun Anda</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px;">

    <table align="center" cellpadding="0" cellspacing="0" width="100%" 
           style="max-width: 600px; background-color: #ffffff; padding: 30px; border-radius: 8px; 
                  box-shadow: 0 2px 8px rgba(0,0,0,0.05);">

        <tr>
            <td align="center" style="padding-bottom: 20px;">
                <h2 style="color: #333333; margin: 0;">
                    Informasi Reset Akun – <span style="color: #2563EB;">{{ config('app.name') }}</span>
                </h2>
            </td>
        </tr>

        <tr>
            <td>
                <p style="font-size: 15px; color: #555555; line-height: 1.6;">
                    Kami menerima permintaan untuk mereset kata sandi akun Anda di <strong>{{ config('app.name') }}</strong>.
                    Berikut adalah informasi login terbaru Anda:
                </p>

                <table cellpadding="8" cellspacing="0" border="0" 
                       style="border-collapse: collapse; width: 100%; max-width: 400px; margin: 20px 0; font-size: 15px;">
                    <tr style="background-color: #f0f0f0;">
                        <th align="left" style="padding: 10px; border-bottom: 1px solid #ddd;">Informasi</th>
                        <th align="left" style="padding: 10px; border-bottom: 1px solid #ddd;">Detail</th>
                    </tr>
                    <tr>
                        <td style="padding: 10px;">Email</td>
                        <td style="padding: 10px;">{{ $email }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px;">Kata Sandi Baru</td>
                        <td style="padding: 10px;">{{ $password }}</td>
                    </tr>
                </table>

                <p style="font-size: 15px; color: #555555; line-height: 1.6;">
                    Demi keamanan, kami menyarankan Anda untuk segera masuk ke akun dan mengganti kata sandi ini dengan yang lebih aman.
                </p>

                <p style="font-size: 15px; color: #555555; margin-top: 20px;">
                    Jika Anda tidak merasa melakukan permintaan ini, silakan abaikan email ini atau hubungi tim kami segera.
                </p>

                <hr style="border: none; border-top: 1px solid #eeeeee; margin: 30px 0;">

                <p style="font-size: 12px; color: #999999; text-align: center;">
                    Jangan membagikan informasi ini kepada siapa pun.
                </p>

                <p style="font-size: 12px; color: #999999; text-align: center; margin-top: 20px;">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. Seluruh hak cipta dilindungi.
                </p>
            </td>
        </tr>
    </table>

</body>
</html>
