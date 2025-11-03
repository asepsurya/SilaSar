<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>{{ config('app.name') }}  | Reset Akun</title>

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
            background-color: #f3f1f1;
            /* background-image: url('{{ asset('assets/bg3.jpg') }}');
            background-size: cover;
            background-position: center; */
        }
        /* Untuk HP & tablet kecil */
        /* @media (max-width: 768px) {
            #content {
                background-color: #ffffff;

            }

        } */

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

<body class="flex items-center justify-center min-h-screen" style="">
<main class="w-full max-w-5xl mx-auto bg-white rounded-lg overflow-hidden flex flex-col md:flex-row ">

    <!-- Side Animation - Only Desktop -->
    <section class="hidden md:flex w-1/2 bg-[#f0f5ff] items-center justify-center">
        <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
        <dotlottie-player 
            src="https://lottie.host/3b67f67b-d617-4a7c-9528-e4640e808132/6XAxleY8Vu.lottie"
            background="transparent"
            speed="1"
            style="width: 100%; height: auto;"
            loop autoplay>
        </dotlottie-player>
    </section>

    <!-- Content Section -->
    <section class="w-full md:w-1/2 p-6 sm:p-8 md:p-12 flex flex-col justify-center" id="content">

        <!-- Logo -->
        <div class="flex items-center space-x-2 mb-6">
            <img src="{{ asset('assets/app_logo.png') }}" alt="App Logo" class="rounded" width="150">
        </div>

        <!-- Title -->
        <h2 class="text-2xl font-semibold text-[#111827] mb-4">Reset Password</h2>

        <!-- Alert - Error -->
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <strong class="font-bold">Terjadi kesalahan:</strong>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Alert - Success -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Form Start -->
        <form action="{{ route('passResetAction') }}" method="POST" class="space-y-5" onsubmit="showLoading()">
            @csrf

            <!-- Email Input -->
            <div>
                <label class="relative block">
                    <span class="absolute inset-y-0 left-4 flex items-center text-[#64748b]">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <input
                        type="text"
                        name="email"
                        value="{{ old('email') }}"
                        class="w-full pl-12 pr-4 py-3 rounded-lg border bg-[#f8fafc] text-[#334155] placeholder-[#64748b] focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                        placeholder="Masukkan Email"
                    >
                </label>
            </div>

            <!-- Captcha -->
            <div class="border border-gray-300 rounded-lg p-4">
                <div class="flex justify-between items-center mb-3">
                    <h2 id="captcha-word"
                        class="text-[#9AEBA3] font-serif font-bold text-3xl leading-none select-none"
                        style="text-shadow: 0 0 3px #9AEBA3;">
                        {{ $captcha }}
                    </h2>
                    <button
                        type="button"
                        onclick="generateCaptcha()"
                        class="flex items-center text-blue-600 font-semibold text-sm"
                    >
                        <i class="fas fa-sync-alt mr-1"></i> Ganti Nama
                    </button>
                </div>

                <input
                    type="text"
                    id="captcha-input"
                    name="chapta"
                    placeholder="Ketik nama di atas..."
                    value="{{ old('chapta') }}"
                    class="w-full pl-5 pr-4 py-3 rounded-lg border bg-[#f8fafc] text-[#334155] placeholder-[#64748b] focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                >
            </div>

            <!-- Submit Button -->
            <button
                id="submit-btn"
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition-colors flex items-center justify-center"
            >
                <span id="btn-spinner" class="hidden mr-2">
                    <i class="fas fa-spinner fa-spin"></i>
                </span>
                <span id="btn-text">Reset Password</span>
            </button>
        </form>

        <!-- Javascript - Loading Spinner -->
        <script>
            function showLoading() {
                const btn = document.getElementById('submit-btn');
                const text = document.getElementById('btn-text');
                const spinner = document.getElementById('btn-spinner');

                text.textContent = 'Memproses...';
                spinner.classList.remove('hidden');

                btn.disabled = true;
                btn.classList.add('opacity-70', 'cursor-not-allowed');
            }
        </script>

        <!-- Login Redirect -->
        <div class="mt-6 text-center">
            <small>Sudah punya akun? 
                <a href="/login" class="text-blue-600 hover:underline">Login</a>
            </small>
        </div>
    </section>
</main>

.
    <script>
        function generateCaptcha() {
            fetch("{{ route('refreshCaptcha') }}")
                .then(response => response.json())
                .then(data => {
                    document.getElementById('captcha-word').innerText = data.captcha;
                });
        }
    </script>
    <script>
         const emailInput = document.querySelector('input[name="email"]');
        const captchaInput = document.getElementById('captcha-input');
         emailInput.addEventListener('input', () => {
            const valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value);
            emailInput.classList.toggle('border-green-500', valid);
            emailInput.classList.toggle('border-red-500', !valid && emailInput.value.length > 3);
        });
    </script>
</body>

</html>
