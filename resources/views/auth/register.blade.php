<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>{{ config('app.name') }} | Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&amp;display=swap" rel="stylesheet" />
     <!-- Site favicon -->
    <link rel="shortcut icon" href="{{asset('assets/fav.png')}}" />
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
            /* Warna merah terang */
        }
    </style>
</head>

<body>
    <main class="flex flex-col overflow-hidden w-full min-h-screen bg-white rounded-lg md:flex-row">

        <section class="hidden bg-[#f0f5ff] items-center justify-center relative md:flex md:w-1/2">
            <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module">
            </script>
            <dotlottie-player src="https://lottie.host/8e0e4b17-e2ea-4bb2-b71e-93332419efca/T6eWi584uY.lottie"
                background="transparent" speed="1" style="width: 100%; height: auto;" loop autoplay></dotlottie-player>
        </section>
        <section class="w-full p-8 md:w-1/2 md:p-16  bg-[url('{{ asset('assets/bg2.png') }}')] md:bg-none bg-cover bg-center">
            <div class="flex space-x-2 mb-10 items-center">
                 <img src="{{ asset('assets/app_logo.png') }}" alt="App Logo" class="rounded" width="150">
            </div>
            <h1 class="mb-2 text-[#0f172a] text-3xl font-semibold leading-tight">
                Daftarkan Akun Anda
            </h1>
            <p class="mb-8 text-[#334155] text-base">
                Selamat datang kembali! Silakan masukkan detail Anda.
            </p>
            
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


            <form class="space-y-5" action="{{ route('register.add') }}" method="POST" onsubmit="showLoading()">
                @csrf
                <form action="{{ route('register') }}" method="POST">
                    @csrf
               <label class="block relative">
                            <span class="flex text-[#64748b] absolute inset-y-0 left-4 items-center">
                                <i class="fas fa-user"></i>
                            </span>
                            <input id="name" name="name"
                                class="w-full pl-12 pr-4 py-3 text-[#334155] placeholder-[#64748b] bg-[#f8fafc] rounded-lg border @error('name') border-red-500 @enderror focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                                placeholder="Nama Lengkap Anda" type="text" value="{{ old('name') }}" />

                        </label>

                    <div class="relative">
                        <div class="flex text-[#64748b] absolute inset-y-0 left-4 items-center">
                            <i class="fas fa-phone"></i>
                        </div>

                       <input id="phone" name="phone" type="tel" placeholder="08xxxxxxxxxx" value="{{ old('phone') }}"
                            pattern="[0-9]{9,15}" title="Hanya angka, panjang 9-15 digit"
                            inputmode="numeric" maxlength="15"
                            oninput="formatToWhatsApp(this)"
                            class="w-full pl-12 pr-4 py-3 text-[#334155] placeholder-[#64748b] bg-[#f8fafc] rounded-lg border @error('phone') border-red-500 @enderror focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" />

                        <script>
                        function formatToWhatsApp(el) {
                            let val = el.value.replace(/[^0-9]/g, ''); // hapus selain angka
                            if (val.startsWith('0')) {
                                val = '+62' + val.slice(1); // ubah 08xxx jadi +628xxx
                            } else if (val.startsWith('62')) {
                                val = '+62' + val.slice(2);
                            } else if (!val.startsWith('+62')) {
                                val = '+62' + val;
                            }
                            el.value = val.slice(0, 15 + 1); // max 15 digit (tanpa +)
                        }
                        </script>
                     
                    </div>


                    <label class="block relative">
                        <span class="flex text-[#64748b] absolute inset-y-0 left-4 items-center">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input id="email" name="email"
                            class="w-full pl-12 pr-4 py-3 text-[#334155] placeholder-[#64748b] bg-[#f8fafc] rounded-lg border @error('email') border-red-500 @enderror focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                            placeholder="Email" type="email" value="{{ old('email') }}" />

                    </label>

                    {{-- Loading dan pesan error --}}
                    <div id="email-status" class="text-sm mt-1 pl-1">
                        <p id="email-loading" class="text-blue-500 hidden">Memeriksa email...</p>
                        <p id="email-error" class="text-red-500 hidden">Email sudah terdaftar, Silahakan Login dengan
                            email tersebut</p>
                    </div>
              
                    @php
                    $inputStyle = "w-full pl-12 pr-12 py-3 text-[#334155] placeholder-[#64748b] bg-[#f8fafc] rounded-lg
                    border focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent";
                    @endphp

                    <!-- Password -->
                    <div class="relative mb-4">
                    
                        <span class="absolute inset-y-0 left-4 flex items-center text-[#64748b]">
                            <i class="fas fa-lock"></i>
                        </span>

                        <input id="password" name="password" type="password" placeholder="Password"
                            class="{{ $inputStyle }} @error('password') border-red-500 @enderror" />

                        <!-- Toggle -->
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

                        <input id="cpassword" name="cpassword" type="password"
                            placeholder="Masukkan Kembali Password Anda"
                            class="{{ $inputStyle }} @error('cpassword') border-red-500 @enderror" />

                        <!-- Toggle -->
                        <span class="absolute inset-y-0 right-4 flex items-center text-gray-500 cursor-pointer"
                            onclick="togglePassword('cpassword')">
                            <i class="fas fa-eye" id="icon-cpassword"></i>
                        </span>

                     
                    </div>

                    <label class="inline-flex space-x-2 mb-8 text-[#475569] text-sm items-start">
                        <input class="mt-1" type="checkbox" checked />
                        <span>
                            Dengan membuat akun, berarti Anda setuju dengan <span class="text-blue-600">Syarat & Ketentuan</span>
                            dan <span class="text-blue-600">Kebijakan Privasi</span> kami.
                        </span>
                    </label>
                    <button id="submit-btn" type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition-colors flex items-center justify-center">
                        <span id="btn-spinner" class="hidden mr-2">
                            <i class="fas fa-spinner fa-spin"></i>
                        </span> 
                        <span id="btn-text">Mendaftar</span>
                    </button>
                </form>

           
                  <script>
            function showLoading() {
                const btn = document.getElementById('submit-btn');
                const text = document.getElementById('btn-text');
                const spinner = document.getElementById('btn-spinner');

                text.textContent = 'Memproses...';
                spinner.classList.remove('hidden');

                // Nonaktifkan tombol untuk cegah submit dobel
                btn.disabled = true;
                btn.classList.add('opacity-70', 'cursor-not-allowed');
            }
        </script>
                <p class="mt-10 text-center text-[#64748b] text-sm">
                    Sudah memiliki akun?
                    <a class="text-blue-600 font-semibold hover:underline" href="/login">
                        Masuk
                    </a>
                </p>
                      <!-- Footer -->
            <footer class="mt-auto p-7 flex flex-wrap items-center justify-center sm:justify-between gap-3 ">
                <p class="text-xs text-black">&copy; {{ date('Y') }} {{ config('app.name') }}</p>
                <ul class="flex items-center text-black/40 text-xs gap-5">
                    {{-- <li><img src="{{ asset('assets/app_logo.png') }}" alt="App Logo" class="rounded" width="110"></li> --}}
                    <li><img src="{{ asset('assets/BI_Logo.png') }}" alt="BI Logo" width="120"></li>
                </ul>
            </footer>
        </section>
    </main>
    <script>
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
    </script>


    <script>
        document.getElementById('phone').addEventListener('input', function(event) {
            // Membatasi hanya angka yang dapat dimasukkan
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>
    <script>
        function togglePasswordVisibility(inputId) {
            const passwordField = document.getElementById(inputId);
            const eyeIcon = document.getElementById('eye-icon-' + inputId);

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
    <script>
        const emailInput = document.getElementById('email');
    const loadingMsg = document.getElementById('email-loading');
    const errorMsg = document.getElementById('email-error');

    let timer = null;

    emailInput.addEventListener('input', function () {
        // Clear timeout jika user masih mengetik
        clearTimeout(timer);

        // Mulai loading
        loadingMsg.classList.remove('hidden');
        errorMsg.classList.add('hidden');

        // Tunggu 700ms setelah terakhir diketik
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
    </script>



</body>

</html>