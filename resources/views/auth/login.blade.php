<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>{{ config('app.name') }} | Login</title>

    <!-- Tailwind CSS & Fonts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/fav.png') }}" />

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-white min-h-screen">
    <main class="flex flex-col md:flex-row min-h-screen overflow-hidden bg-cover bg-center bg-[url('{{ asset('assets/bg2.png') }}')] md:bg-none">
          <!-- Right Form Section -->
        <section class="flex flex-col  min-h-screen w-full md:w-1/2 p-8 md:p-16 bg-[url('{{ asset('assets/bg2.png') }}')] md:bg-none bg-cover bg-center pt-[130px]">
            <div class="flex-grow items-center justify-center">
                <!-- Logo -->
                <div class="mb-10">
                    <img src="{{ asset('assets/app_logo.png') }}" alt="App Logo" class="rounded" width="150">
                </div>

                <!-- Heading -->
                <h1 class="text-[#0f172a] text-3xl font-semibold mb-2">Masuk ke Akun Anda</h1>
                <p class="text-[#334155] mb-8 text-base">Selamat datang! Silakan masukkan detail Anda.</p>

                <!-- Error Alert -->
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                        <strong class="font-bold">Terjadi kesalahan:</strong>
                        <ul class="mt-2 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="/login" class="space-y-5" onsubmit="showLoading()">
                    @csrf

                    <!-- Email -->
                   
                    <label class="relative block">
                        <span class="absolute inset-y-0 left-4 flex items-center text-[#64748b]">
                            <i class="fas fa-user"></i>
                        </span>
                        <input 
                            name="email" 
                            type="text" 
                            placeholder="Username, Telepon, Email"
                            class="w-full pl-12 pr-4 py-3 rounded-lg border bg-[#f8fafc] text-[#334155] placeholder-[#64748b] focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent @error('email') border-red-500 @enderror" />
                    </label>

                    <!-- Password -->
                    <label class="relative block">
                        <span class="absolute inset-y-0 left-4 flex items-center text-[#64748b]">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input 
                            name="password" 
                            id="password" 
                            type="password" 
                            placeholder="Password"
                            class="w-full pl-12 pr-12 py-3 rounded-lg border bg-[#f8fafc] text-[#334155] placeholder-[#64748b] focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent @error('password') border-red-500 @enderror" />
                        <span onclick="togglePasswordVisibility('password')" class="absolute inset-y-0 right-4 flex items-center text-[#64748b] cursor-pointer">
                            <i id="eye-icon-password" class="fas fa-eye"></i>
                        </span>
                    </label>
                        
                    <!-- Forgot Password -->
                    <div class="flex justify-between mb-8 text-sm text-[#475569]">
                        <a href="#" onclick="openModal()" class="text-blue-600 hover:underline">Unduh versi Mobile</a>
                                                <!-- Modal -->
                          <div id="downloadModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                            <div class="bg-white w-96 rounded-2xl shadow-lg p-6 text-center">
                              <h2 class="text-lg font-bold text-gray-800 mb-4">Sedang mendownload...</h2>
                              
                              <!-- Progress Bar -->
                              <div class="w-full bg-gray-200 rounded-full h-4 mb-4">
                                <div id="progressBar" class="bg-green-500 h-4 rounded-full transition-all duration-300" style="width: 0%"></div>
                              </div>
                              
                              <p id="progressText" class="text-gray-600">0%</p>
                              
                              <!-- Pesan setelah selesai -->
                              <p id="finishMessage" class="text-green-700 font-semibold mt-4 hidden">
                                ✅ Terima kasih sudah mendownload.<br>Silakan instal aplikasi Anda.
                              </p>
                              
                              <!-- Tombol tutup -->
                              <button type="button" onclick="closeModal()" 
                                      class="mt-6 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">
                                Tutup
                              </button>
                            </div>
                          </div>
                        
                          <script>
                            function openModal() {
                              document.getElementById("downloadModal").classList.remove("hidden");
                        
                              let progress = 0;
                              let bar = document.getElementById("progressBar");
                              let text = document.getElementById("progressText");
                              let finishMsg = document.getElementById("finishMessage");
                        
                              let interval = setInterval(() => {
                                progress += 10;
                                bar.style.width = progress + "%";
                                text.innerText = progress + "%";
                        
                                if (progress >= 100) {
                                  clearInterval(interval);
                                  text.classList.add("hidden");
                                  finishMsg.classList.remove("hidden");
                        
                                  // Setelah progress selesai, trigger download APK otomatis
                                  setTimeout(() => {
                                    window.location.href = "https://silasar.inopakinstitute.or.id/SilaSar.v1.1.apk"; // Ganti link APK
                                  }, 1000);
                                }
                              }, 300);
                            }
                        
                            function closeModal() {
                              document.getElementById("downloadModal").classList.add("hidden");
                            }
                          </script>
                        <a href="{{ route('passReset') }}" class="text-blue-600 hover:underline">Lupa Password..?</a>
                    </div>

                    <!-- Submit Button -->
                    <button id="submit-btn" type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition flex items-center justify-center">
                        <span id="btn-spinner" class="hidden mr-2">
                            <i class="fas fa-spinner fa-spin"></i>
                        </span>
                        <span id="btn-text">Masuk</span>
                    </button>
                </form>
                

                <!-- Register -->
                <p class="text-center text-[#64748b] text-sm mt-10">
                    Belum memiliki akun?
                    <a href="/register" class="text-blue-600 font-semibold hover:underline">Mendaftar</a>
                </p>
               
            </div>

            <!-- Footer -->
            <footer class="mt-auto p-7 flex flex-wrap items-center justify-center sm:justify-between gap-3 ">
                <p class="text-xs text-black">&copy; {{ date('Y') }} {{ config('app.name') }}</p>
                <ul class="flex items-center text-black/40 text-xs gap-5">
                    {{-- <li><img src="{{ asset('assets/app_logo.png') }}" alt="App Logo" class="rounded" width="110"></li> --}}
                    <li><img src="{{ asset('assets/BI_Logo.png') }}" alt="BI Logo" width="120"></li>
                </ul>
            </footer>
        </section>
        <!-- Left Illustration Section -->
        <section class="hidden md:flex md:w-1/2 items-center justify-center">
            <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
            <dotlottie-player 
                src="https://lottie.host/fe44e5b8-3c61-43ec-824d-7902326385d5/9slXZGbndP.lottie"
                background="transparent" 
                speed="1" 
                style="width: 100%; height: auto;" 
                loop 
                autoplay>
            </dotlottie-player>
        </section>

      
    </main>

    <!-- Scripts -->
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
