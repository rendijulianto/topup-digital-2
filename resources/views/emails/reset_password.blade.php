<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        Reset Password
    </title>
</head>
<body>
    <table>
        <tr>
            <td>
                <h1>Reset Password</h1>
                <p>
                    Anda menerima email ini karena kami menerima permintaan reset password untuk akun anda.
                </p>
                <p> Berikut merupakan kata sandi baru anda:</p>
                <p>
                    <strong>{{ $details['newPassword'] }}</strong>
                </p>
                <p>
                    Silahkan klik tombol dibawah ini untuk melakukan perubahan password anda.
                </p>
                <a href="{{ $details['link'] }}?newPassword={{ $details['newPassword'] }}" target="_blank">Reset Password</a>
                <p>
                    Jika anda tidak merasa melakukan permintaan reset password, abaikan email ini.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>