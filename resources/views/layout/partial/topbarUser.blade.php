<div class="sticky top-0 z-30 border-b border-black/10 bg-white/80 px-4 py-3 backdrop-blur-sm dark:border-white/10 dark:bg-slate-950/60 sm:px-6 lg:px-7">
    <div class="flex items-center justify-between gap-3">
        <div class="flex min-w-0 items-center gap-3">

            <button type="button" class="shrink-0 rounded-lg p-1 transition hover:bg-black/5 dark:hover:bg-white/10">
                <img src="{{ asset('assets/app_logo.png') }}" alt="" srcset="" width="130" class="block dark:hidden">
                <img src="{{ asset('assets/SILASAR-LOGO-white.png') }}" alt="" srcset="" width="130"
                    class="hidden dark:block">
            </button>


            <div class="hidden min-w-0 sm:block">
                <nav aria-label="breadcrumb" class="w-full">
                    <ol class="flex min-w-0 items-center gap-1.5 overflow-hidden text-sm">
                        @php
                            $segments = Request::segments();
                            $url = '';
                        @endphp

                        <li class="shrink-0">
                            <a href="{{ url('/') }}"
                                class="inline-flex items-center rounded-md px-2 py-1 text-black/45 transition hover:bg-black/5 hover:text-black dark:text-white/45 dark:hover:bg-white/10 dark:hover:text-white">
                                Home
                            </a>
                        </li>

                        @foreach ($segments as $index => $segment)
                            @php
                                $url .= '/' . $segment;
                                $isLast = $loop->last;

                                // Label khusus jika segmen terakhir di halaman update
                                if ($isLast && Request::is('people/update/*')) {
                                    $label = $ikm->nama;
                                } elseif ($isLast && Request::is('mitra/detail/*')) {
                                    $label = $mitra->nama_mitra;
                                } elseif ($isLast && Request::is('transaksi/*')) {
                                    $label = $mitra->nama_mitra;
                                } else {
                                    $label = ucwords(str_replace('-', ' ', $segment));
                                }

                                $isLinkable = !$isLast && $segment !== ['update', 'detail'];
                            @endphp

                            <li class="flex min-w-0 items-center gap-1.5">
                                <span class="text-black/30 dark:text-white/30">/</span>
                                @if ($isLinkable)
                                    <span class="max-w-[120px] truncate rounded-md px-2 py-1 text-black/45 transition hover:bg-black/5 hover:text-black dark:text-white/45 dark:hover:bg-white/10 dark:hover:text-white">
                                        {{ $label }}
                                    </span>
                                @else
                                    <span class="max-w-[160px] truncate rounded-md bg-black/[0.03] px-2 py-1 font-medium text-black dark:bg-white/5 dark:text-white">{{ $label }}</span>
                                @endif
                            </li>
                        @endforeach
                    </ol>
                </nav>
            </div>
        </div>
        <div class="flex items-center gap-4 lg:gap-5">

            <div class="flex items-center gap-2 sm:gap-3">
                <div class="flex items-center gap-1.5 rounded-full border border-black/5 bg-black/[0.02] p-1 dark:border-white/10 dark:bg-white/5">
                    <a href="javascript:;" class="flex h-9 w-9 items-center justify-center rounded-full text-black transition hover:bg-black/5 hover:text-black dark:text-white dark:hover:bg-white/10 dark:hover:text-white"
                        x-cloak x-show="$store.app.mode === 'light'" @click="$store.app.toggleMode('dark')" aria-label="Ganti ke mode gelap">
                        <x-icon name="moon" class="h-4 w-4" />
                    </a>
                    <a href="javascript:;" class="flex h-9 w-9 items-center justify-center rounded-full text-black transition hover:bg-black/5 hover:text-black dark:text-white dark:hover:bg-white/10 dark:hover:text-white"
                        x-cloak x-show="$store.app.mode === 'dark'" @click="$store.app.toggleMode('light')" aria-label="Ganti ke mode terang">
                        <x-icon name="sun" class="h-4 w-4" />
                    </a>
                </div>

                <button type="button" class="relative flex h-9 w-9 items-center justify-center rounded-full text-black transition hover:bg-black/5 hover:text-black dark:text-white dark:hover:bg-white/10 dark:hover:text-white"
                    @click="$store.app.rightSidebar()" id="rightSidebar" aria-label="Notifikasi">
                    <x-icon name="notif" class="h-4 w-4" />
                    <span class="absolute right-[9px] top-[8px] flex h-2.5 w-2.5 items-center justify-center">
                        <span
                            class="absolute inline-flex h-full w-full animate-ping rounded-full bg-black/50 opacity-75 dark:bg-white/70"></span>
                        <span class="relative inline-flex h-2 w-2 rounded-full bg-black dark:bg-white"></span>
                    </span>
                </button>

                @if (auth()->user()->role === 'gold')
                    <div class="inline-flex items-center rounded-full px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wide bg-yellow-100 text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-300">
                        Gold</div>
                @elseif (auth()->user()->role === 'platinum')
                    <div class="inline-flex items-center rounded-full px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wide bg-blue-100 text-blue-700 dark:bg-blue-500/15 dark:text-blue-300">
                        Platinum</div>
                @elseif (auth()->user()->role === 'admin')
                    <div class="inline-flex items-center rounded-full px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wide bg-indigo-100 text-indigo-700 dark:bg-indigo-500/15 dark:text-indigo-300">
                        Admin</div>
                @elseif (auth()->user()->role === 'superadmin')
                    <div class="inline-flex items-center rounded-full px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wide bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300">
                        Superadmin</div>
                @endif

                <div class="profile" x-data="dropdown" @click.outside="open = false">
                    <button type="button" class="flex items-center gap-2 rounded-full border border-black/5 bg-black/[0.02] px-2 py-1.5 transition hover:bg-black/5 dark:border-white/10 dark:bg-white/5 dark:hover:bg-white/10" @click="toggle()">
                        <img class="h-8 w-8 rounded-full object-cover ring-2 ring-white dark:ring-black/30"
                            src="{{ auth()->user()->ikm && auth()->user()->ikm->foto ? asset('storage/' . auth()->user()->ikm->foto) : asset('assets/images/byewind-avatar.png') }}"
                            alt="Header Avatar" />

                        <span class="hidden text-sm font-medium text-black dark:text-white xl:block" title="{{ auth()->user()->name }}">
                            Hallo, {{ explode(' ', auth()->user()->name)[0] }}
                        </span>

                        <x-icon name="arrow-bottom" class="h-3.5 w-3.5 text-black/60 dark:text-white/70" />
                    </button>
                    <ul x-cloak x-show="open" x-transition x-transition.duration.300ms>
                        <li>
                            <div class="flex items-center !p-1">
                                <div class="flex-none">
                                    <img class="h-7 w-7 rounded-full "
                                        src="{{ auth()->user()->ikm && auth()->user()->ikm->foto ? asset('storage/' . auth()->user()->ikm->foto) : asset('assets/images/byewind-avatar.png') }}"
                                        alt="Header Avatar" />
                                </div>
                                <div class="pl-2">
                                    <h4 class="text-sm text-black dark:text-white font-medium leading-none">
                                        {{ auth()->user()->name }}
                                    </h4>
                                    @php
                                        $email = auth()->user()->email;
                                        $maxLength = 18;
                                        $displayEmail =
                                            strlen($email) > $maxLength ? substr($email, 0, $maxLength) . '...' : $email;
                                    @endphp

                                    <a href="javascript:;"
                                        class="block max-w-[160px] truncate text-black/40 dark:text-white/40 hover:text-black dark:hover:text-white text-xs"
                                        title="{{ $email }}">
                                        {{ $displayEmail }}
                                    </a>
                                </div>
                            </div>
                        </li>

                        <li class="h-px bg-black/5 block my-1"></li>


                        <li>
                            <a href="{{ route('ikm.update', auth()->user()->ikm->id) }}" class="flex items-center">
                                <x-icon name="user-rounded" class="" />
                                Profile
                            </a>
                        </li>
                        <li>


                            <a href="javascript:;" class="flex items-center"
                                @click="window.dispatchEvent(new CustomEvent('pass'))">
                                <x-icon name="gear" class="text-gray-600" />
                                Ubah Password
                            </a>
                        </li>

                        <li class="h-px bg-black/5 block my-1"></li>
                        <li>
                            <form id="logout-form-user" method="POST" action="{{ route('logout') }}" class="hidden">
                                @csrf
                            </form>
                            <button type="button" onclick="confirmLogout('logout-form-user')"
                                class="text-black dark:text-white flex items-center w-full text-left">
                                <x-icon name="sign-out" class="mr-2" />
                                Sign Out
                            </button>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ubah Password -->
<div x-data="{ open: false }" @pass.window="open = true" @close-modal.window="open = false">
    <!-- Overlay -->
    <div class="fixed inset-0 bg-black/60 dark:bg-white/10 z-[999] hidden overflow-y-auto"
        :class="{ 'block': open, 'hidden': !open }">
        <div class="flex items-center justify-center min-h-screen px-4" @click.self="open = false">
            <!-- Modal Box -->
            <div x-show="open" x-transition x-transition.duration.300
                class="bg-white dark:bg-black relative shadow-3xl border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8"
                style="display: none;">
                <!-- Header -->
                <div
                    class="flex bg-white dark:bg-black border-b border-black/10 dark:border-white/10 items-center justify-between px-5 py-3">
                    <h5 class="font-semibold text-lg">Ubah Password</h5>
                    <button type="button"
                        class="text-black/40 dark:text-white/40 hover:text-black dark:hover:text-white"
                        @click="open = false">
                        <svg class="w-5 h-5" width="32" height="32" viewBox="0 0 32 32" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M24.2929 6.29289L6.29289 24.2929C6.10536 24.4804 6 24.7348 6 25C6 25.2652 6.10536 25.5196 6.29289 25.7071C6.48043 25.8946 6.73478 26 7 26C7.26522 26 7.51957 25.8946 7.70711 25.7071L25.7071 7.70711C25.8946 7.51957 26 7.26522 26 7C26 6.73478 25.8946 6.48043 25.7071 6.29289C25.5196 6.10536 25.2652 6 25 6C24.7348 6 24.4804 6.10536 24.2929 6.29289Z"
                                fill="currentcolor" />
                            <path
                                d="M7.70711 6.29289C7.51957 6.10536 7.26522 6 7 6C6.73478 6 6.48043 6.10536 6.29289 6.29289C6.10536 6.48043 6 6.73478 6 7C6 7.26522 6.10536 7.51957 6.29289 7.70711L24.2929 25.7071C24.4804 25.8946 24.7348 26 25 26C25.2652 26 25.5196 25.8946 25.7071 25.7071C25.8946 25.5196 26 25.2652 26 25C26 24.7348 25.8946 24.4804 25.7071 24.2929L7.70711 6.29289Z"
                                fill="currentcolor" />
                        </svg>
                    </button>
                </div>
                <div class="p-5">
                    <form x-data="{ showPassword: false }" action="{{ route('passChange') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block font-medium mb-2">Password Baru</label>
                            <div class="relative">
                                <input type="hidden" name="id" value="{{ auth()->user()->id }}">
                                <input :type="showPassword ? 'text' : 'password'" placeholder="Masukan Password Baru"
                                    name="password"
                                    class="w-full border px-3 py-2 dark:bg-black rounded pr-10 focus:outline-none focus:ring focus:border-blue-300" />

                                <button type="button" @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500"
                                    tabindex="-1">
                                    <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-.07.238-.152.47-.244.696M15 12a3 3 0 01-6 0m9.75 5.25L4.5 4.5" />
                                    </svg>

                                    <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 012.24-3.67M9.88 9.88a3 3 0 104.24 4.24M6.12 6.12l11.76 11.76" />
                                    </svg>
                                </button>

                            </div>
                            <small>*) Pastikan Password yang anda masukan sudah benar.</small>
                        </div>
                        <div class="flex justify-end space-x-2">
                            <button type="button" @click="open = false"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="hidden sm:block">
    <ul class="flex space-x-2 border-b border-black/10 dark:border-white/10 pb-2 g-2 pl-5">
        <a href="{{ route('index.keuangan.harian') }}">
            <li
                class="tab-link cursor-pointer px-4 py-2  hover:text-blue-500 font-semibold flex items-center @if(request()->is('keuangan')) border-b-2 border-blue-600 @endif">
                <!-- Ikon Kiri -->
                <x-icon name="forms" class=" w-6 h-6 mr-2" />
                <span class="pl-2">Keuangan</span>
            </li>
        </a>

        <a href="{{ route('index.akun.harian') }}">
            <li
                class="tab-link cursor-pointer px-4 py-2  hover:text-blue-500 font-semibold flex items-center @if(request()->is('akun')) border-b-2 border-blue-600 @endif">
                <!-- Ikon Kiri -->
                <x-icon name="forms" class=" w-6 h-6 mr-2" />
                <span class="pl-2">Akun</span>
            </li>
        </a>

        <a href="{{ route('akun.rekening.harian') }}">
            <li
                class="tab-link cursor-pointer px-4 py-2  hover:text-blue-500 font-semibold flex items-center @if(request()->is('rekening')) border-b-2 border-blue-600 @endif">
                <!-- Ikon Kiri -->
                <x-icon name="layer" class=" w-6 h-6 mr-2" />
                <span class="pl-2">Rekening</span>
            </li>
        </a>

        <a href="{{ route('dashboard.keuangan.harian') }}">
            <li class="tab-link cursor-pointer px-4 py-2  hover:text-blue-500 font-semibold flex items-center @if(request()->routeIs('dashboard.keuangan')) border-b-2 border-blue-600 @endif"
                onclick="openTab(event, 'tab1')">
                <!-- Ikon Kiri -->
                <x-icon name="dashboard" class=" w-6 h-6 mr-2" />
                <span class="pl-2">Grafik</span>
            </li>
        </a>

        <a href="/setelan">
            <li
                class="tab-link cursor-pointer px-4 py-2  hover:text-blue-500 font-semibold flex items-center @if(request()->is('setelan')) border-b-2 border-blue-600 @endif">
                <!-- Ikon Kiri -->
                <x-icon name="gear" class=" w-6 h-6 mr-2" />
                <span class="pl-2">Perusahaan Saya</span>
            </li>
        </a>
    </ul>

</div>

<nav
    class="w-full fixed bottom-0 inset-x-0 z-40 bg-white dark:bg-black border-t border-gray-200 dark:border-white/10 md:hidden">
    <div class="relative flex justify-between items-center">

        <!-- Keuangan -->
        <a href="{{ route('index.keuangan.harian') }}"
            class="flex flex-col items-center justify-center w-full py-2 text-xs {{ $active === 'keuangan' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-white/60' }}">
            <svg class="w-5 h-5 mb-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <rect x="3" y="7" width="18" height="10" rx="2" stroke="currentColor" stroke-width="1.5" />
                <circle cx="12" cy="12" r="2.5" stroke="currentColor" stroke-width="1.5" />
            </svg>
            <span class="text-[10px]">Keuangan</span>
        </a>

        <!-- Grafik -->
        <a href="{{ route('dashboard.keuangan.harian') }}"
            class="flex flex-col items-center justify-center w-full py-2 text-xs {{ $active === 'grafik' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-white/60' }}">
            <x-icon name="dashboard" class="w-5 h-5 mb-1" />
            <span class="text-[10px]">Grafik</span>
        </a>

        @if (Request::is('keuangan') || Request::is('catatan/keuangan'))
            <!-- Tombol + (Floating di tengah) -->
            <div class="flex flex-col items-center justify-center w-full py-2 text-xs  ">
                <button @click="$dispatch('transaksi')"
                    class="flex items-center justify-center w-12 h-12 bg-blue-600 hover:bg-blue-700 text-white rounded-full  shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                </button>
            </div>
        @else
            <!-- Akun -->
            <a href="{{ route('index.akun.harian') }}"
                class="flex flex-col items-center justify-center w-full py-2 text-xs {{ $active === 'akun' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-white/60' }}">
                <x-icon name="forms" class="w-6 h-6 mb-1" />
                <span class="text-[10px]">Akun</span>
            </a>
        @endif

        <!-- Rekening -->
        <a href="{{ route('akun.rekening.harian') }}"
            class="flex flex-col items-center justify-center w-full py-2 text-xs {{ $active === 'rekening' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-white/60' }}">
            <x-icon name="layer" class="w-6 h-6 mb-1" />
            <span class="text-[10px]">Rekening</span>
        </a>

        <!-- Usaha Saya -->
        <a href="/setelan"
            class="flex flex-col items-center justify-center w-full py-2 text-xs {{ $active === 'setelan' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-white/60' }}">
            <x-icon name="user-1" class="w-6 h-6 mb-1" />
            <span class="text-[10px]">Usaha Saya</span>
        </a>
    </div>
</nav>
