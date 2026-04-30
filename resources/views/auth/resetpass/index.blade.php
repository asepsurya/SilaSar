<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>{{ config('app.name') }} | Reset Akun</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="shortcut icon" href="{{ asset('assets/app_logo_new.png') }}" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .auth-shell {
            background:
                radial-gradient(circle at top left, rgba(59, 130, 246, 0.18), transparent 32%),
                radial-gradient(circle at bottom right, rgba(16, 185, 129, 0.16), transparent 28%),
                linear-gradient(135deg, #f8fbff 0%, #eef5ff 45%, #ffffff 100%);
        }

        .app-visual {
            position: relative;
            width: 100%;
            max-width: 620px;
            height: 360px;
        }

        .app-orb {
            position: absolute;
            border-radius: 9999px;
            filter: blur(8px);
            opacity: 0.9;
            animation: float 7s ease-in-out infinite;
        }

        .app-orb.one {
            top: 28px;
            left: 24px;
            width: 110px;
            height: 110px;
            background: rgba(59, 130, 246, 0.32);
        }

        .app-orb.two {
            right: 38px;
            top: 54px;
            width: 82px;
            height: 82px;
            background: rgba(16, 185, 129, 0.28);
            animation-delay: -2s;
        }

        .app-orb.three {
            left: 90px;
            bottom: 24px;
            width: 72px;
            height: 72px;
            background: rgba(251, 191, 36, 0.2);
            animation-delay: -4s;
        }

        .dashboard-stage {
            position: absolute;
            inset: 22px 26px;
            border-radius: 32px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.94), rgba(15, 23, 42, 0.75));
            box-shadow: 0 22px 60px rgba(2, 6, 23, 0.35);
            overflow: hidden;
        }

        .dashboard-grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.04) 1px, transparent 1px);
            background-size: 24px 24px;
            mask-image: linear-gradient(180deg, rgba(0, 0, 0, 1), transparent);
        }

        .dashboard-window {
            position: absolute;
            inset: 24px;
            border-radius: 24px;
            border: 1px solid rgba(148, 163, 184, 0.2);
            background: linear-gradient(180deg, rgba(30, 41, 59, 0.82), rgba(15, 23, 42, 0.92));
            overflow: hidden;
        }

        .mini-card {
            position: absolute;
            border-radius: 22px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            background: rgba(15, 23, 42, 0.76);
            backdrop-filter: blur(12px);
            box-shadow: 0 14px 30px rgba(2, 6, 23, 0.28);
        }

        .mini-card.balance {
            top: 22px;
            left: 22px;
            width: 200px;
            padding: 18px;
            animation: float 6s ease-in-out infinite;
        }

        .mini-card.list {
            right: 22px;
            bottom: 22px;
            width: 190px;
            padding: 16px;
            animation: float 6s ease-in-out infinite;
            animation-delay: -2.5s;
        }

        .chart-panel {
            position: absolute;
            left: 24px;
            right: 24px;
            bottom: 24px;
            height: 150px;
            border-radius: 24px;
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.35), rgba(15, 23, 42, 0.72));
            border: 1px solid rgba(255, 255, 255, 0.08);
            overflow: hidden;
        }

        .chart-area {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(37, 99, 235, 0.18), rgba(37, 99, 235, 0));
            clip-path: polygon(0 78%, 12% 68%, 26% 72%, 38% 50%, 51% 56%, 63% 34%, 78% 42%, 100% 14%, 100% 100%, 0 100%);
        }

        .chart-line {
            position: absolute;
            inset: 18px 16px 18px 16px;
        }

        .chart-line svg {
            width: 100%;
            height: 100%;
        }

        .chart-line path {
            stroke-dasharray: 340;
            stroke-dashoffset: 340;
            animation: drawLine 2.8s ease forwards;
        }

        .float-chip {
            position: absolute;
            display: flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0.75rem 1rem;
            border-radius: 9999px;
            background: rgba(255, 255, 255, 0.94);
            color: #0f172a;
            box-shadow: 0 16px 32px rgba(15, 23, 42, 0.22);
            animation: float 5.5s ease-in-out infinite;
        }

        .float-chip.one {
            right: -6px;
            top: 98px;
        }

        .float-chip.two {
            left: -4px;
            bottom: 52px;
            animation-delay: -2.2s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes drawLine {
            to {
                stroke-dashoffset: 0;
            }
        }
    </style>
</head>

<body class="min-h-screen auth-shell text-slate-900">
    <main class="flex min-h-screen w-full">
        <div class="flex min-h-screen w-full overflow-hidden">
            <section class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-slate-950 text-white">
                <div
                    class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(59,130,246,0.45),transparent_35%),radial-gradient(circle_at_bottom,rgba(16,185,129,0.3),transparent_30%)]">
                </div>
                <div class="absolute inset-0 opacity-30"
                    style="background-image: linear-gradient(rgba(255,255,255,0.08) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.08) 1px, transparent 1px); background-size: 32px 32px;">
                </div>

                <div class="relative z-10 flex h-full w-full flex-col p-10 xl:p-12">
                    <div class="max-w-md">
                        <div
                            class="inline-flex items-center gap-3 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-sm font-semibold text-blue-100 backdrop-blur">
                            <span class="h-2.5 w-2.5 rounded-full bg-emerald-400"></span>
                            Sistem usaha dalam satu dashboard
                        </div>

                        <h1 class="mt-8 text-4xl font-extrabold leading-tight xl:text-5xl">
                            Pulihkan akses akun Anda tanpa keluar dari alur aplikasi.
                        </h1>
                        <p class="mt-5 max-w-lg text-base leading-7 text-slate-300">
                            Masukkan email akun Anda dan selesaikan verifikasi captcha untuk memproses reset password di
                            {{ config('app.name') }}.
                        </p>
                    </div>

                    <div class="flex flex-1 items-center justify-center py-8">
                        <div class="app-visual" aria-hidden="true">
                            <div class="app-orb one"></div>
                            <div class="app-orb two"></div>
                            <div class="app-orb three"></div>

                            <div class="dashboard-stage">
                                <div class="dashboard-grid"></div>
                                <div class="dashboard-window">
                                    <div class="absolute left-6 top-6 flex items-center gap-3">
                                        <div
                                            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-500/15 ring-1 ring-blue-400/30">
                                            <img src="{{ asset('assets/app_logo_new.png') }}" alt=""
                                                class="h-8 w-8 object-contain">
                                        </div>
                                        <div>
                                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">
                                                SilaSar Security</p>
                                            <p class="mt-1 text-lg font-bold text-white">Pemulihan Akses</p>
                                        </div>
                                    </div>

                                    <div class="mini-card balance">
                                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">
                                            Verifikasi</p>
                                        <p class="mt-3 text-2xl font-extrabold text-white">Aman</p>
                                        <div class="mt-4 flex items-center gap-2 text-sm text-emerald-300">
                                            <i class="fas fa-shield-alt"></i>
                                            <span>Langkah reset tervalidasi</span>
                                        </div>
                                    </div>

                                    <div class="chart-panel">
                                        <div class="chart-area"></div>
                                        <div class="chart-line">
                                            <svg viewBox="0 0 320 120" preserveAspectRatio="none" fill="none">
                                                <path
                                                    d="M0 90 C30 82, 48 78, 68 74 S110 56, 132 52 S172 30, 198 34 S244 28, 270 20 S302 12, 320 10"
                                                    stroke="#38bdf8" stroke-width="4" stroke-linecap="round" />
                                            </svg>
                                        </div>
                                        <div
                                            class="absolute inset-x-0 bottom-4 flex justify-between px-5 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">
                                            <span>Input</span>
                                            <span>Cek</span>
                                            <span>Validasi</span>
                                            <span>Kirim</span>
                                        </div>
                                    </div>

                                    <div class="mini-card list">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-bold text-white">Tahapan</p>
                                            <span
                                                class="rounded-full bg-emerald-500/15 px-2 py-1 text-[11px] font-bold text-emerald-300">Ready</span>
                                        </div>
                                        <div class="mt-4 space-y-3 text-sm text-slate-300">
                                            <div class="flex items-center gap-3">
                                                <span class="h-2.5 w-2.5 rounded-full bg-emerald-400"></span>
                                                <span>Identitas akun dicek</span>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <span class="h-2.5 w-2.5 rounded-full bg-sky-400"></span>
                                                <span>Captcha diverifikasi</span>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <span class="h-2.5 w-2.5 rounded-full bg-amber-300"></span>
                                                <span>Instruksi reset dikirim</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="float-chip one">
                                <span
                                    class="flex h-9 w-9 items-center justify-center rounded-full bg-emerald-500 text-white">
                                    <i class="fas fa-lock-open text-sm"></i>
                                </span>
                                <div>
                                    <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Akses
                                    </p>
                                    <p class="text-sm font-bold text-slate-900">Dipulihkan</p>
                                </div>
                            </div>

                            <div class="float-chip two">
                                <span
                                    class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-600 text-white">
                                    <i class="fas fa-envelope-open-text text-sm"></i>
                                </span>
                                <div>
                                    <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Email
                                    </p>
                                    <p class="text-sm font-bold text-slate-900">Terverifikasi</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-3">
                        <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                            <p class="text-sm text-slate-300">Reset</p>
                            <p class="mt-2 text-xl font-bold text-white">Aman</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                            <p class="text-sm text-slate-300">Proses</p>
                            <p class="mt-2 text-xl font-bold text-white">Jelas</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                            <p class="text-sm text-slate-300">Akses</p>
                            <p class="mt-2 text-xl font-bold text-white">Cepat</p>
                        </div>
                    </div>
                </div>
            </section>

            <section
                class="flex min-h-screen w-full items-center justify-center px-5 py-8 sm:px-8 lg:w-1/2 lg:px-14 xl:px-20">
                <div class="w-full max-w-2xl p-2 sm:p-4">
                    <div class="flex items-center justify-between gap-4">
                        <img src="{{ asset('assets/app_logo_new.png') }}" alt="App Logo"
                            class="h-12 w-auto object-contain">
                        <a href="/login"
                            class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                            <i class="fas fa-arrow-left"></i>
                            <span>Kembali ke Login</span>
                        </a>
                    </div>

                    <div class="mt-8">
                        <span
                            class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-bold uppercase tracking-[0.2em] text-blue-700">
                            Reset access
                        </span>
                        <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-950">
                            Reset password akun
                        </h2>
                        <p class="mt-3 text-sm leading-6 text-slate-500 sm:text-base">
                            Masukkan email yang terdaftar, lalu ketik captcha untuk melanjutkan proses pemulihan akun.
                        </p>
                    </div>

                    @if ($errors->any())
                        <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-4 text-sm text-red-700">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-exclamation-circle mt-0.5 text-red-500"></i>
                                <div>
                                    <p class="font-semibold text-red-800">Terjadi kesalahan saat reset password.</p>
                                    <ul class="mt-2 list-disc space-y-1 pl-5">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (session('success'))
                        <div
                            class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-4 text-sm text-emerald-700">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-check-circle mt-0.5 text-emerald-500"></i>
                                <div>
                                    <p class="font-semibold text-emerald-800">Permintaan reset berhasil diproses.</p>
                                    <p class="mt-1">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('passResetAction') }}" method="POST" class="mt-8 space-y-5"
                        onsubmit="showLoading()">
                        @csrf

                        <div>
                            <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                            <div class="group relative">
                                <span
                                    class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 transition group-focus-within:text-blue-600">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input id="email" type="text" name="email" value="{{ old('email') }}"
                                    placeholder="Masukkan email terdaftar"
                                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3.5 pl-11 pr-4 text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100" />
                            </div>
                        </div>

                        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Captcha</p>
                                    <h3 id="captcha-word"
                                        class="mt-2 text-3xl font-extrabold tracking-[0.12em] text-emerald-500">
                                        {{ $captcha }}
                                    </h3>
                                </div>
                                <button type="button" onclick="generateCaptcha()"
                                    class="inline-flex items-center gap-2 rounded-full border border-blue-200 bg-white px-4 py-2 text-sm font-semibold text-blue-600 transition hover:border-blue-300 hover:bg-blue-50">
                                    <i class="fas fa-sync-alt"></i>
                                    <span>Ganti Captcha</span>
                                </button>
                            </div>

                            <div class="mt-5">
                                <label for="captcha-input"
                                    class="mb-2 block text-sm font-semibold text-slate-700">Masukkan Captcha</label>
                                <input id="captcha-input" type="text" name="chapta" value="{{ old('chapta') }}"
                                    placeholder="Ketik captcha di atas"
                                    class="w-full rounded-2xl border border-slate-200 bg-white py-3.5 px-4 text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100" />
                            </div>
                        </div>

                        <button id="submit-btn" type="submit"
                            class="flex w-full items-center justify-center rounded-2xl bg-slate-950 px-4 py-3.5 text-sm font-bold text-white shadow-[0_16px_32px_rgba(15,23,42,0.18)] transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-70">
                            <span id="btn-spinner" class="mr-2 hidden"><i class="fas fa-spinner fa-spin"></i></span>
                            <span id="btn-text">Reset Password</span>
                        </button>
                    </form>

                    <div class="mt-8 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4 text-sm text-slate-600">
                        Sudah ingat password Anda?
                        <a href="/login"
                            class="font-bold text-blue-600 transition hover:text-blue-700 hover:underline">Login
                            sekarang</a>
                    </div>

                    <div
                        class="mt-8 flex flex-col gap-4 border-t border-slate-200 pt-6 text-xs text-slate-400 sm:flex-row sm:items-center sm:justify-between">
                        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                        <img src="{{ asset('assets/logo/logo_light.png') }}" alt="BI Logo"
                            class="h-7 w-auto object-contain opacity-60 transition hover:opacity-100" />
                    </div>
                </div>
            </section>
        </div>
    </main>

    <script>
        function showLoading() {
            const btn = document.getElementById('submit-btn');
            const text = document.getElementById('btn-text');
            const spinner = document.getElementById('btn-spinner');

            if (!btn || !text || !spinner) {
                return;
            }

            text.textContent = 'Memproses...';
            spinner.classList.remove('hidden');
            btn.disabled = true;
        }

        function generateCaptcha() {
            fetch("{{ route('refreshCaptcha') }}")
                .then(response => response.json())
                .then(data => {
                    const captchaWord = document.getElementById('captcha-word');

                    if (captchaWord) {
                        captchaWord.innerText = data.captcha;
                    }
                });
        }

        const emailInput = document.getElementById('email');

        if (emailInput) {
            emailInput.addEventListener('input', () => {
                const valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value);
                emailInput.classList.toggle('border-green-500', valid);
                emailInput.classList.toggle('border-red-500', !valid && emailInput.value.length > 3);
                emailInput.classList.toggle('border-slate-200', emailInput.value.length <= 3);
            });
        }
    </script>
</body>

</html>