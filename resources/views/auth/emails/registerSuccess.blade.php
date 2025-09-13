<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>{{ config('app.name') }} | Aktivasi Email</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Site favicon -->
    <link rel="shortcut icon" href="{{asset('assets/fav.png')}}" />
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
        }
        .icon svg {
            width: 80px;
            height: 80px;
            fill: #2563eb;
        }
        .btn {
            display: inline-block;
            padding: 10px 24px;
            background-color: #2563eb;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
        }
        .btn:hover {
            background-color: #1e40af;
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen">

    <main class="bg-white w-full rounded-lg flex flex-col md:flex-row overflow-hidden max-w-5xl shadow-lg">
        
        <!-- Side Image / Animation -->
        <section class="hidden md:flex md:w-1/2 bg-[#f0f5ff] items-center justify-center relative">
            <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
            <dotlottie-player src="https://lottie.host/c5dd81a2-a013-410a-81c8-9b1fb9afb345/Gko5fyHQ2d.lottie"
                background="transparent" speed="1" style="width: 100%; height: auto;" loop autoplay>
            </dotlottie-player>
        </section>

        <!-- Content Section -->
        <section class="w-full md:w-1/2 p-8 md:p-16 flex flex-col justify-center items-center text-center">
            
            <!-- Logo & App Name -->
            <div class="flex items-center space-x-2 mb-8">
                 <img src="{{ asset('assets/app_logo.png') }}" alt="App Logo" class="rounded" width="150">
            </div>

            <!-- Message Container -->
            <div class="max-w-sm w-full">
                <h2 class="text-2xl font-semibold text-[#111827] mb-4">Aktivasi Email Dikirim</h2>

                <p class="text-[#4b5563] text-sm leading-relaxed mb-6">
                    Kami telah mengirimkan kode aktivasi ke alamat email <strong>{{ $user->email }}</strong><br>
                    Silakan cek inbox atau folder spam Anda.
                </p>

                <button type="button" id="resendBtn" class="btn">Kirim Ulang </button>
                <a href="{{ url('/') }}" class="btn">Kembali ke Beranda</a>

            </div>

        </section>

    </main>
<script>
    document.getElementById('resendBtn').addEventListener('click', function () {
        let btn = this;
        btn.disabled = true;
        btn.textContent = 'Mengirim...';

        fetch("{{ route('resend') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ token: "{{ $token }}" })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.success);
                startTimer(btn);
            } else {
                alert(data.error);
                btn.disabled = false;
                btn.textContent = 'Kirim Ulang';
            }
        })
        .catch(error => {
            console.error(error);
            alert('Gagal mengirim ulang token.');
            btn.disabled = false;
            btn.textContent = 'Kirim Ulang';
        });
    });

    function startTimer(button) {
        let timeLeft = 60;
        let originalText = button.textContent;
        let timer = setInterval(function () {
            if (timeLeft <= 0) {
                clearInterval(timer);
                button.disabled = false;
                button.textContent = originalText;
            } else {
                button.textContent = 'Tunggu ' + timeLeft + ' detik';
                timeLeft -= 1;
            }
        }, 1000);
    }
</script>

</body>
</html>
