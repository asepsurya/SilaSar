<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>{{ config('app.name') }} | Register</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Fonts & Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&amp;display=swap" rel="stylesheet" />

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/fav.png') }}" />

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .spin {
            animation: spin 1s linear infinite;
        }

        .border-red {
            border-color: #f40606;
        }

        /* Default sembunyikan mobile login */
        .mobile-login {
            display: none;
        }

        /* Mobile rules */
        @media (max-width: 768px) {
            .dekstop {
                display: none !important;
            }

            .mobile-login {
                display: block;
            }
        }

        /* Mobile login styling */
        .header {
            background: #1e319d;
            text-align: center;
            padding: 50px 20px 80px;
            border-bottom-left-radius: 40px;
            border-bottom-right-radius: 40px;
        }

        .header h1 {
            margin: 0;
            font-size: 36px;
            font-weight: bold;
            color: #ffffff;
        }

        .header p {
            margin: 5px 0 0;
            font-size: 18px;
            color: #ffffff;
        }

        .login-container {
            max-width: 320px;
            background: #fff;
            margin: -60px auto 0;
            padding: 30px 25px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .login-container h2 {
            text-align: center;
            font-size: 22px;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .login-container p {
            text-align: center;
            font-size: 12px;
            color: #777;
            margin-bottom: 25px;
        }

        .input-group {
            margin-bottom: 20px;
            position: relative;
        }

        .input-group input {
            width: 100%;
            padding: 12px 40px 12px 15px;
            border-radius: 30px;
            border: 1px solid #ddd;
            font-size: 14px;
            outline: none;
        }

        .input-group i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 16px;
            color: #555;
        }

        .options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            margin-bottom: 20px;
        }

        .btn-login {
            display: block;
            width: 100%;
            padding: 12px;
            background: #1e319d;
            border: none;
            border-radius: 30px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            color: #ffffff;
        }

        .btn-login:hover {
            background: #e1a417;
        }

        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        /* Spinner */
        .spinner {
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top: 3px solid #000;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            animation: spin 0.8s linear infinite;
            margin-left: 8px;
        }

    </style>
</head>

<body>
    <main class="flex flex-col overflow-hidden w-full min-h-screen bg-white rounded-lg md:flex-row dekstop">

        <!-- Left Side (Desktop Only) -->
        <section class="hidden bg-[#f0f5ff] items-center justify-center relative md:flex md:w-1/2">
            <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
            <dotlottie-player src="https://lottie.host/8e0e4b17-e2ea-4bb2-b71e-93332419efca/T6eWi584uY.lottie" background="transparent" speed="1" style="width: 100%; height: auto;" loop autoplay></dotlottie-player>
        </section>

        <!-- Right Side (Desktop Form) -->
        <section class="w-full p-8 md:w-1/2 ">
            <div class="flex space-x-2 mb-10 items-center">
                <img src="{{ asset('assets/app_logo.png') }}" alt="App Logo" class="rounded" width="150">
            </div>
            <h1 class="mb-2 text-[#0f172a] text-3xl font-semibold leading-tight">Daftarkan Akun Anda</h1>
            <p class="mb-8 text-[#334155] text-base">Selamat datang kembali! Silakan masukkan detail Anda.</p>

            @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Terjadi kesalahan:</strong>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @php
            $inputStyle = "w-full pl-12 pr-12 py-3 text-[#334155] placeholder-[#64748b] bg-[#f8fafc] rounded-lg
            border focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent";
            @endphp

            <form class="space-y-5" action="{{ route('register.add') }}" method="POST" onsubmit="showLoading()">
                @csrf

                <!-- Name -->
                <label class="block relative">
                    <span class="flex text-[#64748b] absolute inset-y-0 left-4 items-center">
                        <i class="fas fa-user"></i>
                    </span>
                    <input id="name" name="name" type="text" placeholder="Nama Lengkap Anda" value="{{ old('name') }}" class="{{ $inputStyle }} @error('name') border-red-500 @enderror" />
                </label>

                <!-- Phone -->
                <div class="relative">
                    <span class="absolute inset-y-0 left-4 flex items-center text-[#64748b]">
                        <i class="fas fa-phone"></i>
                    </span>
                    <input id="phone" name="phone" type="tel" placeholder="08xxxxxxxxxx" value="{{ old('phone') }}" pattern="[0-9]{9,15}" inputmode="numeric" maxlength="15" oninput="formatToWhatsApp(this)" class="{{ $inputStyle }} @error('phone') border-red-500 @enderror" />
                </div>

                <!-- Email -->
                <label class="block relative">
                    <span class="flex text-[#64748b] absolute inset-y-0 left-4 items-center">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <input id="email" name="email" type="email" placeholder="Email" value="{{ old('email') }}" class="{{ $inputStyle }} @error('email') border-red-500 @enderror" />
                </label>

                <div id="email-status" class="text-sm mt-1 pl-1">
                    <p id="email-loading" class="text-blue-500 hidden">Memeriksa email...</p>
                    <p id="email-error" class="text-red-500 hidden">Email sudah terdaftar, silakan login.</p>
                </div>

                <!-- Password -->
                <div class="relative">
                    <span class="absolute inset-y-0 left-4 flex items-center text-[#64748b]">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input id="password" name="password" type="password" placeholder="Password" class="{{ $inputStyle }} @error('password') border-red-500 @enderror" />
                    <span class="absolute inset-y-0 right-4 flex items-center text-gray-500 cursor-pointer" onclick="togglePassword('password')">
                        <i class="fas fa-eye" id="icon-password"></i>
                    </span>
                </div>

                <!-- Confirm Password -->
                <div class="relative">
                    <span class="absolute inset-y-0 left-4 flex items-center text-[#64748b]">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input id="cpassword" name="cpassword" type="password" placeholder="Masukkan Kembali Password Anda" class="{{ $inputStyle }} @error('cpassword') border-red-500 @enderror" />
                    <span class="absolute inset-y-0 right-4 flex items-center text-gray-500 cursor-pointer" onclick="togglePassword('cpassword')">
                        <i class="fas fa-eye" id="icon-cpassword"></i>
                    </span>
                </div>

                <!-- Terms -->
                <label class="inline-flex space-x-2 mb-8 text-[#475569] text-sm items-start">
                    <input class="mt-1" type="checkbox" checked />
                    <span>
                        Dengan membuat akun, berarti Anda setuju dengan
                        <span class="text-blue-600">Syarat & Ketentuan</span>
                        dan <span class="text-blue-600">Kebijakan Privasi</span> kami.
                    </span>
                </label>

                <!-- Submit -->
                <button id="submit-btn" type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition-colors flex items-center justify-center">
                    <span id="btn-spinner" class="hidden mr-2">
                        <i class="fas fa-spinner fa-spin"></i>
                    </span>
                    <span id="btn-text">Mendaftar</span>
                </button>
            </form>

            <p class="mt-10 text-center text-[#64748b] text-sm">
                Sudah memiliki akun?
                <a class="text-blue-600 font-semibold hover:underline" href="/login">Masuk</a>
            </p>

            <footer class="mt-auto p-7 flex flex-wrap items-center justify-center sm:justify-between gap-3">
                <p class="text-xs text-black">&copy; {{ date('Y') }} {{ config('app.name') }}</p>
                <ul class="flex items-center text-black/40 text-xs gap-5">
                    <li><img src="{{ asset('assets/BI_Logo.png') }}" alt="BI Logo" width="120"></li>
                </ul>
            </footer>
        </section>
    </main>

    <!-- Mobile Form -->
    <div class="mobile-login">
        <div class="header">
            <center>
                <img src="{{ asset('assets/SILASAR-LOGO-white.png') }}" alt="Logo" width="200" style="margin-bottom: 15px;">
            </center>
        </div>
        <div class="login-container">
            <h2>Daftarkan Akun Anda</h2>
            <p>Silakan isi detail untuk membuat akun baru.</p>

            <form class="space-y-5" action="{{ route('register.add') }}" method="POST" onsubmit="showLoading()">
                @csrf

                <!-- Name -->
                <label class="block relative">
                    <span class="flex text-[#64748b] absolute inset-y-0 left-4 items-center">
                        <i class="fas fa-user"></i>
                    </span>
                    <input id="register-name" name="name" type="text" placeholder="Nama Lengkap Anda" value="{{ old('name') }}" class="{{ $inputStyle }} @error('name') border-red-500 @enderror" />
                </label>

                <!-- Phone -->
                <div class="relative">
                    <span class="absolute inset-y-0 left-4 flex items-center text-[#64748b]">
                        <i class="fas fa-phone"></i>
                    </span>
                    <input id="register-phone" name="phone" type="tel" placeholder="08xxxxxxxxxx" value="{{ old('phone') }}" pattern="[0-9]{9,15}" inputmode="numeric" maxlength="15" oninput="formatToWhatsApp(this)" class="{{ $inputStyle }} @error('phone') border-red-500 @enderror" />
                </div>

                <!-- Email -->
                <label class="block relative">
                    <span class="flex text-[#64748b] absolute inset-y-0 left-4 items-center">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <input id="register-email" name="email" type="email" placeholder="Email" value="{{ old('email') }}" class="{{ $inputStyle }} @error('email') border-red-500 @enderror" />
                </label>

                <div id="register-email-status" class="text-sm mt-1 text-left">
                    <p id="register-email-loading" class="text-blue-500 hidden">Memeriksa email...</p>
                    <p id="register-email-error" class="text-red-500 hidden">Email sudah terdaftar, silakan login.</p>
                </div>


                <!-- Password -->
                <div class="relative">
                    <span class="absolute inset-y-0 left-4 flex items-center text-[#64748b]">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input id="register-password" name="password" type="password" placeholder="Password" class="{{ $inputStyle }} @error('password') border-red-500 @enderror" />
                    <span class="absolute inset-y-0 right-4 flex items-center text-gray-500 cursor-pointer" onclick="togglePassword('register-password')">
                        <i class="fas fa-eye" id="icon-register-password"></i>
                    </span>
                </div>

                <!-- Confirm Password -->
                <div class="relative">
                    <span class="absolute inset-y-0 left-4 flex items-center text-[#64748b]">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input id="register-cpassword" name="cpassword" type="password" placeholder="Masukkan Kembali Password Anda" class="{{ $inputStyle }} @error('cpassword') border-red-500 @enderror" />
                    <span class="absolute inset-y-0 right-4 flex items-center text-gray-500 cursor-pointer" onclick="togglePassword('register-cpassword')">
                        <i class="fas fa-eye" id="icon-register-cpassword"></i>
                    </span>
                </div>
                <!-- Terms -->
                <label class="inline-flex space-x-2 mb-8 text-[#475569] text-sm items-start">
                    <input class="mt-1" type="checkbox" checked />
                    <span>
                        Dengan membuat akun, berarti Anda setuju dengan
                        <span class="text-blue-600">Syarat & Ketentuan</span>
                        dan <span class="text-blue-600">Kebijakan Privasi</span> kami.
                    </span>
                </label>

                <!-- Submit -->
                <button id="submit-btn" type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition-colors flex items-center justify-center">
                    <span id="btn-spinner" class="hidden mr-2">
                        <i class="fas fa-spinner fa-spin"></i>
                    </span>
                    <span id="btn-text">Mendaftar</span>
                </button>

                <p class="mt-10 text-center text-[#64748b] text-sm">
                    Sudah memiliki akun?
                    <a class="text-blue-600 font-semibold hover:underline" href="/login">Masuk</a>
                </p>
            </form>


        </div>

        <footer class="mt-auto p-7 flex items-center justify-between">
            <!-- Kiri -->
            <p class="text-xs text-black">
                &copy; {{ date('Y') }} {{ config('app.name') }}
            </p>

            <!-- Kanan -->
            <ul class="flex items-center gap-5">
                {{-- <li><img src="{{ asset('assets/app_logo.png') }}" alt="App Logo" class="rounded" width="110"></li> --}}
                <li>
                    <img src="{{ asset('assets/BI_Logo.png') }}" alt="BI Logo" width="120">
                </li>
            </ul>
        </footer>
    </div>

    <!-- Scripts -->
    <script>
        function togglePassword(fieldId) {
            const input = document.getElementById(fieldId);
            const icon = document.getElementById('icon-' + fieldId);

            if (input.type === "password") {
                input.type = "text";
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = "password";
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        function showLoading() {
            const btn = document.getElementById('submit-btn');
            const text = document.getElementById('btn-text');
            const spinner = document.getElementById('btn-spinner');
            text.textContent = 'Memproses...';
            spinner.classList.remove('hidden');
            btn.disabled = true;
        }

        function formatToWhatsApp(el) {
            let val = el.value.replace(/[^0-9]/g, '');
            if (val.startsWith('0')) val = '+62' + val.slice(1);
            else if (val.startsWith('62')) val = '+62' + val.slice(2);
            else if (!val.startsWith('+62')) val = '+62' + val;
            el.value = val.slice(0, 16);
        }

        // Check email availability
        const emailInput = document.getElementById('email');
        const loadingMsg = document.getElementById('email-loading');
        const errorMsg = document.getElementById('email-error');
        let timer = null;

        emailInput.addEventListener('input', function() {
            clearTimeout(timer);
            loadingMsg.classList.remove('hidden');
            errorMsg.classList.add('hidden');
            timer = setTimeout(() => {
                fetch('{{ route("check.email") }}', {
                        method: 'POST'
                        , headers: {
                            'Content-Type': 'application/json'
                            , 'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                        , body: JSON.stringify({
                            email: this.value
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        loadingMsg.classList.add('hidden');
                        if (data.exists) errorMsg.classList.remove('hidden');
                    })
                    .catch(() => {
                        loadingMsg.classList.add('hidden');
                        errorMsg.textContent = 'Terjadi kesalahan saat memeriksa email.';
                        errorMsg.classList.remove('hidden');
                    });
            }, 700);
        });
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('register-email');
            const loadingMsg = document.getElementById('register-email-loading');
            const errorMsg = document.getElementById('register-email-error');
            let timer;

            emailInput.addEventListener('input', function() {
                clearTimeout(timer);
                loadingMsg.classList.remove('hidden');
                errorMsg.classList.add('hidden');

                timer = setTimeout(() => {
                    fetch('{{ route("check.email") }}', {
                            method: 'POST'
                            , headers: {
                                'Content-Type': 'application/json'
                                , 'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                            , body: JSON.stringify({
                                email: this.value
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            loadingMsg.classList.add('hidden');
                            if (data.exists) {
                                errorMsg.textContent = 'Email sudah terdaftar, silakan login.';
                                errorMsg.classList.remove('hidden');
                            }
                        })
                        .catch(() => {
                            loadingMsg.classList.add('hidden');
                            errorMsg.textContent = 'Terjadi kesalahan saat memeriksa email.';
                            errorMsg.classList.remove('hidden');
                        });
                }, 700);
            });
        });

    </script>
</body>
</html>
