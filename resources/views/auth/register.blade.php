<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>{{ config('app.name') }} | Register</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&amp;display=swap" rel="stylesheet" />

    <!-- Site Favicon -->
    <link rel="shortcut icon" href="{{asset('assets/fav.png')}}" />

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Animasi berputar */
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .spin {
            animation: spin 1s linear infinite;
        }

        .border-red {
            border-color: #f40606;
        }

        .toggle-container {
            border-radius: 9999px; /* capsule */
            background-color: #f3f4f6; /* bg-gray-100 */
            position: relative;
            display: flex;
            width: 100%;
            max-width: 400px; /* prevent too wide on large screen */
            padding: 8px;
            cursor: pointer;
            box-sizing: border-box;
            display: none; /* default hidden */
        }

        .toggle-indicator {
            position: absolute;
            top: 4px;
            bottom: 4px;
            width: 50%;
            background-color: #2563eb; /* Tailwind Blue */
            border-radius: 9999px;
            transition: transform 0.3s ease;
        }

        .toggle-option {
            flex: 1;
            text-align: center;
            font-weight: 600;
            z-index: 10;
            padding: 8px 0;
            user-select: none;
        }

        .toggle-option.active {
            color: white;
        }

        @media (max-width: 768px) {
            .toggle-container {
                display: flex; /* show on mobile */
            }
        }
    </style>
</head>

<body>
    <main class="flex flex-col overflow-hidden w-full min-h-screen bg-white rounded-lg md:flex-row">

        <!-- Left Section (Animation) - Hidden on Mobile -->
        <section class="hidden md:flex md:w-1/2 items-center justify-center">
            <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
            <dotlottie-player src="https://lottie.host/8e0e4b17-e2ea-4bb2-b71e-93332419efca/T6eWi584uY.lottie"
                background="transparent" speed="1" style="width: 100%; height: auto;" loop autoplay></dotlottie-player>
        </section>

        <!-- Right Section (Form) -->
        <section class="w-full p-5 md:w-1/2 md:p-10">

            <!-- App Logo -->
            <div class="flex space-x-2 mb-10 items-center">
                <img src="{{ asset('assets/app_logo.png') }}" alt="App Logo" class="rounded" width="150" />
            </div>

            <!-- Heading -->
            <h1 class="mb-2 text-[#0f172a] text-3xl font-semibold leading-tight">
                Daftarkan Akun Anda
            </h1>
            <p class="mb-8 text-[#334155] text-sm">
                Selamat datang kembali! Silakan masukkan detail Anda.
            </p>

            <!-- Error Messages -->
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

            <!-- Toggle Login/Register (mobile only) -->
            <div id="toggle" class="toggle-container">
                <div id="indicator" class="toggle-indicator" style="transform: translateX(100%);"></div>
                <div id="login" class="toggle-option" data-link="/login">Login</div>
                <div id="register" class="toggle-option active" data-link="/register">Register</div>
            </div>

            <!-- Registration Form -->
            <form class="space-y-5" action="{{ route('register.add') }}" method="POST" onsubmit="showLoading()">
                @csrf

                <!-- Name -->
                <label class="block relative">
                    <span class="flex text-[#64748b] absolute inset-y-0 left-4 items-center">
                        <i class="fas fa-user"></i>
                    </span>
                    <input id="name" name="name"
                        class="w-full pl-12 pr-4 py-3 text-[#334155] placeholder-[#64748b] bg-[#f8fafc] rounded-lg border @error('name') border-red-500 @enderror focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                        placeholder="Nama Lengkap Anda" type="text" value="{{ old('name') }}" />
                </label>

                <!-- Phone -->
                <div class="relative">
                    <div class="flex text-[#64748b] absolute inset-y-0 left-4 items-center">
                        <i class="fas fa-phone"></i>
                    </div>
                    <input id="phone" name="phone" type="tel" placeholder="08xxxxxxxxxx" value="{{ old('phone') }}"
                        pattern="[0-9]{9,15}" title="Hanya angka, panjang 9-15 digit" inputmode="numeric" maxlength="15"
                        oninput="formatToWhatsApp(this)"
                        class="w-full pl-12 pr-4 py-3 text-[#334155] placeholder-[#64748b] bg-[#f8fafc] rounded-lg border @error('phone') border-red-500 @enderror focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" />
                </div>

                <!-- Email -->
                <label class="block relative">
                    <span class="flex text-[#64748b] absolute inset-y-0 left-4 items-center">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <input id="email" name="email"
                        class="w-full pl-12 pr-4 py-3 text-[#334155] placeholder-[#64748b] bg-[#f8fafc] rounded-lg border @error('email') border-red-500 @enderror focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                        placeholder="Email" type="email" value="{{ old('email') }}" />
                </label>

                <!-- Email Status Loading/Error -->
                <div id="email-status" class="text-sm mt-1 pl-1">
                    <p id="email-loading" class="text-blue-500 hidden">Memeriksa email...</p>
                    <p id="email-error" class="text-red-500 hidden">Email sudah terdaftar, Silahakan Login dengan email
                        tersebut</p>
                </div>

                <!-- Password Inputs -->
                @php
                $inputStyle = "w-full pl-12 pr-12 py-3 text-[#334155] placeholder-[#64748b] bg-[#f8fafc] rounded-lg border focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent";
                @endphp

                <!-- Password -->
                <div class="relative mb-4">
                    <span class="absolute inset-y-0 left-4 flex items-center text-[#64748b]">
                        <i class="fas fa-lock"></i>
                    </span>

                    <input id="password" name="password" type="password" placeholder="Password"
                        class="{{ $inputStyle }} @error('password') border-red-500 @enderror" />

                    <span class="absolute inset-y-0 right-4 flex items-center text-gray-500 cursor-pointer"
                        onclick="togglePassword('password')">
                        <i class="fas fa-eye" id="icon-password"></i>
                    </span>
                </div>

                <!-- Confirm Password -->
                <div class="relative">
                    <span class="absolute inset-y-0 left-4 flex items-center text-[#64748b]">
                        <i class="fas fa-lock"></i>
                    </span>

                    <input id="cpassword" name="cpassword" type="password" placeholder="Masukkan Kembali Password Anda"
                        class="{{ $inputStyle }} @error('cpassword') border-red-500 @enderror" />

                    <span class="absolute inset-y-0 right-4 flex items-center text-gray-500 cursor-pointer"
                        onclick="togglePassword('cpassword')">
                        <i class="fas fa-eye" id="icon-cpassword"></i>
                    </span>
                </div>

                <!-- Submit Button -->
                <button id="submit-btn" type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition-colors flex items-center justify-center">
                    <span id="btn-spinner" class="hidden mr-2">
                        <i class="fas fa-spinner fa-spin"></i>
                    </span>
                    <span id="btn-text">Mendaftar</span>
                </button>
            </form>

            <!-- Link to Login -->
            <p class="mt-10 text-center text-[#64748b] text-sm">
                Sudah memiliki akun?
                <a class="text-blue-600 font-semibold hover:underline" href="/login">
                    Masuk
                </a>
            </p>

            <!-- Footer - Hidden on Mobile -->
            <footer
                class="hidden md:flex mt-auto p-7 flex flex-wrap items-center justify-center sm:justify-between gap-3">
                <p class="text-xs text-black">&copy; {{ date('Y') }} {{ config('app.name') }}</p>
                <ul class="flex items-center text-black/40 text-xs gap-5">
                    <li><img src="{{ asset('assets/BI_Logo.png') }}" alt="BI Logo" width="120" /></li>
                </ul>
            </footer>
        </section>

    </main>

    <!-- Scripts -->

    <script>
        // Toggle password visibility
        function togglePassword(fieldId) {
            const input = document.getElementById(fieldId);
            const icon = document.getElementById('icon-' + fieldId);

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = "password";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Format phone number input to +62 standard
        function formatToWhatsApp(el) {
            let val = el.value.replace(/[^0-9]/g, ''); // Remove non-digits
            if (val.startsWith('0')) {
                val = '+62' + val.slice(1);
            } else if (val.startsWith('62')) {
                val = '+62' + val.slice(2);
            } else if (!val.startsWith('+62')) {
                val = '+62' + val;
            }
            el.value = val.slice(0, 16); // Limit to 15 digits plus '+'
        }

        // Prevent non-numeric input in phone field
        document.getElementById('phone').addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Show loading animation on submit
        function showLoading() {
            const btn = document.getElementById('submit-btn');
            const text = document.getElementById('btn-text');
            const spinner = document.getElementById('btn-spinner');

            text.textContent = 'Memproses...';
            spinner.classList.remove('hidden');

            btn.disabled = true;
            btn.classList.add('opacity-70', 'cursor-not-allowed');
        }

        // Email availability check with debounce
        const emailInput = document.getElementById('email');
        const loadingMsg = document.getElementById('email-loading');
        const errorMsg = document.getElementById('email-error');

        let timer = null;

        emailInput.addEventListener('input', function () {
            clearTimeout(timer);

            loadingMsg.classList.remove('hidden');
            errorMsg.classList.add('hidden');

            timer = setTimeout(() => {
                fetch('{{ route("check.email") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ email: this.value })
                })
                    .then(res => res.json())
                    .then(data => {
                        loadingMsg.classList.add('hidden');
                        if (data.exists) {
                            errorMsg.classList.remove('hidden');
                        } else {
                            errorMsg.classList.add('hidden');
                        }
                    })
                    .catch(() => {
                        loadingMsg.classList.add('hidden');
                        errorMsg.textContent = 'Terjadi kesalahan saat memeriksa email.';
                        errorMsg.classList.remove('hidden');
                    });
            }, 700);
        });

        // Toggle switch for Login/Register with animation and redirect
        const toggle = document.getElementById('toggle');
        const indicator = document.getElementById('indicator');
        const options = toggle.querySelectorAll('.toggle-option');

        options.forEach(option => {
            option.addEventListener('click', () => {
                options.forEach(o => o.classList.remove('active'));
                option.classList.add('active');

                if (option.id === 'login') {
                    indicator.style.transform = 'translateX(0%)';
                } else {
                    indicator.style.transform = 'translateX(100%)';
                }

                const link = option.dataset.link;
                setTimeout(() => {
                    window.location.href = link;
                }, 300);
            });
        });
    </script>
</body>

</html>
