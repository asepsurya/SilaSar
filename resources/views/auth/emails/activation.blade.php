<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Aktivasi Akun Anda</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin:0; padding:20px;">

    <table align="center" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
        <tr>
            <td align="center" style="padding-bottom: 20px;">
                <h2 style="color: #333333; margin: 0;">Selamat Datang di <span style="color:#2563EB;">{{ config('app.name') }}</span></h2>
            </td>
        </tr>

        <tr>
            <td>
                <p style="font-size: 16px; color: #555555; margin-bottom: 24px;">
                    Yth. {{ $user->name }},
                </p>

                <p style="font-size: 14px; color: #666666; margin-bottom: 24px;">
                    Terima kasih telah melakukan pendaftaran di <strong>{{ config('app.name') }}</strong>.  
                    Untuk menyelesaikan proses registrasi dan mengaktifkan akun Anda, silakan klik tombol di bawah ini:
                </p>

                <p style="text-align: center; margin: 30px 0;">
                    <a href="{{ url('account/activate/'.$token) }}" 
                       style="background-color: #2563EB; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block; font-weight: bold;">
                       Aktivasi Akun
                    </a>
                </p>

                <p style="font-size: 14px; color: #666666; margin-bottom: 24px;">
                    Apabila tombol di atas tidak dapat diakses, Anda juga dapat menyalin dan membuka tautan berikut melalui browser:
                </p>

                <p style="font-size: 13px; color: #2563EB; word-break: break-all;">
                    {{ url('account/activate/'.$token) }}
                </p>

                <hr style="border:none; border-top: 1px solid #eeeeee; margin: 30px 0;">

                <p style="font-size: 12px; color: #aaaaaa; text-align: center;">
                    Apabila Anda merasa tidak pernah melakukan pendaftaran, Anda dapat mengabaikan email ini.  
                    Email ini dikirim secara otomatis, mohon untuk tidak membalas.
                </p>

                <p style="font-size: 12px; color: #aaaaaa; text-align: center; margin-top: 20px;">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. Seluruh hak cipta dilindungi undang-undang.
                </p>
            </td>
        </tr>
    </table>

</body>
</html>
