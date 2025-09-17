<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>{{ config('app.name') }} | Login</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
    <link rel="shortcut icon" href="{{ asset('assets/fav.png') }}" />

    <style>
        body {
            font-family: 'Inter', sans-serif;
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
            max-width: 400px;
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

        .options label {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #333;
        }

        .options a {
            color: #555;
            text-decoration: none;
        }

        .options a:hover {
            text-decoration: underline;
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

        .create-account {
            text-align: center;
            margin-top: 15px;
        }

        .create-account a {
            color: #000;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
        }

        .create-account a:hover {
            text-decoration: underline;
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

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

    </style>
</head>

<body class="bg-white min-h-screen">

    <!-- DESKTOP LOGIN -->
    <main class="dekstop flex flex-col md:flex-row min-h-screen overflow-hidden bg-cover bg-center bg-[url('{{ asset('assets/bg2.png') }}')] md:bg-none">
        <!-- Right Form Section -->
        <section class="flex items-center min-h-screen w-full md:w-1/2 px-6 py-12 md:px-16">
            <div class="flex-grow">
                <!-- Logo -->
                <div class="mb-10">
                    <img src="{{ asset('assets/app_logo.png') }}" alt="App Logo" class="rounded" width="150">
                </div>

                <!-- Heading -->
                <h1 class="text-[#0f172a] text-3xl font-semibold mb-2">Masuk ke Akun Anda</h1>
                <p class="text-[#334155] mb-8 text-base">Selamat datang! Silakan masukkan detail Anda.</p>

                <!-- Error Alert -->
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

                <!-- Login Form -->
                <form method="POST" action="/login" class="space-y-5" onsubmit="showLoading('desktop')">
                    @csrf

                    <!-- Email -->
                    <label class="relative block">
                        <span class="absolute inset-y-0 left-4 flex items-center text-[#64748b]">
                            <i class="fas fa-user"></i>
                        </span>
                        <input name="email" type="text" placeholder="Username, Telepon, Email" class="w-full pl-12 pr-4 py-3 rounded-lg border bg-[#f8fafc] text-[#334155] placeholder-[#64748b] focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" />
                    </label>

                    <!-- Password -->
                    <label class="relative block">
                        <span class="absolute inset-y-0 left-4 flex items-center text-[#64748b]">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input name="password" id="password" type="password" placeholder="Password" class="w-full pl-12 pr-12 py-3 rounded-lg border bg-[#f8fafc] text-[#334155] placeholder-[#64748b] focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" />
                        <span onclick="togglePasswordVisibility('password')" class="absolute inset-y-0 right-4 flex items-center text-[#64748b] cursor-pointer">
                            <i id="eye-icon-password" class="fas fa-eye"></i>
                        </span>
                    </label>

                    <!-- Forgot Password -->
                    <div class="flex justify-between mb-8 text-sm text-[#475569]">
                        <a href="{{ route('passReset') }}" class="text-blue-600 hover:underline">Lupa Password..?</a>
                    </div>

                    <!-- Submit Button -->
                    <button id="submit-btn-desktop" type="submit"  class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition flex items-center justify-center">
                        <span id="btn-spinner-desktop" class="hidden mr-2"><i class="fas fa-spinner fa-spin"></i></span>
                        <span id="btn-text-desktop">Masuk</span>
                    </button>
                </form>

                <!-- Register -->
                <p class="text-center text-[#64748b] text-sm mt-10">
                    Belum memiliki akun?
                    <a href="/register" class="text-blue-600 font-semibold hover:underline">Mendaftar</a>
                </p>
                <footer class="mt-auto p-7 flex flex-wrap items-center justify-center sm:justify-between gap-3 ">
                    <p class="text-xs text-black">&copy; {{ date('Y') }} {{ config('app.name') }}</p>
                    <ul class="flex items-center text-black/40 text-xs gap-5"> {{-- <li><img src="{{ asset('assets/app_logo.png') }}" alt="App Logo" class="rounded" width="110"></li> --}} <li><img src="{{ asset('assets/BI_Logo.png') }}" alt="BI Logo" width="120"></li>
                    </ul>
                </footer>
            </div>

        </section>

        <!-- Left Illustration Section -->
        <section class="hidden md:flex md:w-1/2 items-center justify-center">
            <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
            <dotlottie-player src="https://lottie.host/fe44e5b8-3c61-43ec-824d-7902326385d5/9slXZGbndP.lottie" background="transparent" speed="1" style="width: 100%; height: auto;" loop autoplay> </dotlottie-player>
        </section>

    </main>

    <!-- MOBILE LOGIN -->
    <div class="mobile-login">
        <div class="header" style="text-align: center;">
            <center>
                <img src="{{ asset('assets/SILASAR-LOGO-white.png') }}" alt="Logo" width="200" style="margin-bottom: 15px;">
            </center>
        </div>


        <div class="login-container">
            <h2>Masuk ke akun Anda</h2>
            <p>Selamat datang, silakan masukkan detail akun Anda untuk melanjutkan.</p>


            <form method="POST" action="/login" onsubmit="showLoading('mobile')">
                @csrf
                @if ($errors->any())
    <div id="toast-error"
        class="fixed top-4 right-4 z-50 bg-red-500 text-white px-3 py-2 rounded-md shadow-md text-sm max-w-xs animate-fade-in-down">
        <div class="flex items-start space-x-2">
            <div>
                <strong class="font-semibold">Error!</strong>

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

            </div>
            <button onclick="document.getElementById('toast-error').remove()"
                class="ml-2 text-white hover:text-gray-200 text-xs">
                ✖
            </button>
        </div>
    </div>

    <script>
        // Auto hide setelah 4 detik
        setTimeout(() => {
            document.getElementById('toast-error')?.remove();
        }, 4000);
    </script>
@endif

                <div class="input-group">
                    <input type="email" name="email" placeholder="Your Email Address" required>
                    <i class="fas fa-user"></i>
                </div>

                <div class="input-group">
                    <input type="password" name="password" placeholder="Password" required>
                    <i class="fas fa-lock"></i>
                </div>

                <div class="options">
                    <label>
                        <input type="checkbox" id="rememberMe"> Simpan login
                    </label>
                    <a href="{{ route('passReset') }}">Lupa Password?</a>
                </div>

                <button id="submit-btn-mobile" type="submit"  class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition flex items-center justify-center">
                    <span id="btn-spinner-mobile" class="hidden mr-2"><i class="fas fa-spinner fa-spin"></i></span>
                    <span id="btn-text-mobile">Masuk</span>
                </button>

                <div class="create-account">
                    <a href="/register">Mendaftar</a>
                </div>

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
       function showLoading(formType = 'desktop') {
            const btn = document.getElementById(`submit-btn-${formType}`);
            const text = document.getElementById(`btn-text-${formType}`);
            const spinner = document.getElementById(`btn-spinner-${formType}`);

            text.textContent = 'Memproses...';
            spinner.classList.remove('hidden');
            btn.disabled = true;
            btn.classList.add('opacity-70', 'cursor-not-allowed');
        }


        function togglePasswordVisibility(id) {
            const input = document.getElementById(id);
            const icon = document.getElementById('eye-icon-' + id);
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            icon.classList.toggle('fa-eye', !isPassword);
            icon.classList.toggle('fa-eye-slash', isPassword);
        }

    </script>

</body>
</html>
