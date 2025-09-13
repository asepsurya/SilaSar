<div class="border-b border-black/10 dark:border-white/10 py-[22px] px-7 flex items-center justify-between">
    <div class="flex items-center gap-2">
     
        <button type="button" class=" text-black dark:text-white" >
            <img src="{{ asset('assets/app_logo.png') }}" alt="" srcset="" width="130" class="block dark:hidden">
            <img src="{{ asset('assets/SILASAR-LOGO-white.png') }}" alt="" srcset="" width="130" class="hidden dark:block">
        </button>


        <div class="hidden sm:block">
            <nav aria-label="breadcrumb" class="w-full py-1 px-2">
                <ol class="flex space-x-2 text-sm">
                    @php
                        $segments = Request::segments();
                        $url = '';
                    @endphp

                    <li>
                        <a href="{{ url('/dashboard') }}"
                            class="text-black/40 dark:text-white/40 hover:text-black dark:hover:text-white">
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

                        <li class="flex items-center space-x-1">
                            <span class="text-black/40 dark:text-white/40">/</span>
                            @if ($isLinkable)
                                <a href="{{ url($url) }}"
                                    class="text-black/40 dark:text-white/40 hover:text-black dark:hover:text-white">
                                    {{ $label }}
                                </a>
                            @else
                                <span class="text-black dark:text-white">{{ $label }}</span>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </nav>
        </div>
    </div>
    <div class="flex items-center gap-5">

        <div class="flex items-center gap-2">
            <div>
                <a href="javascript:;" class="text-black dark:text-white" x-cloak x-show="$store.app.mode === 'light'"
                    @click="$store.app.toggleMode('dark')">
                    <x-icon name="moon" class="" />
                </a>
                <a href="javascript:;" class="text-black dark:text-white" x-cloak x-show="$store.app.mode === 'dark'"
                    @click="$store.app.toggleMode('light')">
                    <x-icon name="sun" class="" />
                </a>
            </div>
            <button type="button" class="relative w-7 h-7 p-1 text-black dark:text-white"
                @click="$store.app.rightSidebar()" id="rightSidebar">
                <x-icon name="notif" class="" />
                <span class="flex absolute w-3 h-3 right-px top-[5px]">
                    <span
                        class="animate-ping absolute -left-[3px] -top-[3px] inline-flex h-full w-full rounded-full bg-black/50 dark:bg-white/50 opacity-75"></span>
                    <span class="relative inline-flex rounded-full w-[6px] h-[6px] bg-black dark:bg-white"></span>
                </span>
            </button>
            <div class="profile" x-data="dropdown" @click.outside="open = false">
                <button type="button" class="flex items-center gap-1.5 xl:gap-0" @click="toggle()">
                    <img class="h-7 w-7 rounded-full xl:mr-2"
                        src="{{ auth()->user()->ikm && auth()->user()->ikm->foto ? asset('storage/' . auth()->user()->ikm->foto) : asset('assets/images/byewind-avatar.png') }}"
                        alt="Header Avatar" />

                    <span class="fw-medium hidden xl:block" title="{{ auth()->user()->name }}">
                        Hallo, {{ explode(' ', auth()->user()->name)[0] }}
                    </span>

                    <x-icon name="arrow-bottom" class="" />
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
                        <div x-data="{ open: false }">
                            <!-- Trigger -->
                            <a href="javascript:;" class="flex items-center" @click="open = true">
                                <x-icon name="gear" class="" />
                                Ubah Password
                            </a>

                            <!-- Modal -->
                            <div x-show="open" x-transition @click.self="open = false"
                                class="fixed inset-0 flex items-center justify-center"
                                style="z-index: 2147483647; background-color: rgba(0, 0, 0, 0.5); position: fixed;">

                                <!-- Modal Box -->
                                <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md z-[10000]">
                                    <h2 class="text-xl font-semibold mb-4">Ubah Password</h2>
                                    <form x-data="{ showPassword: false }" action="{{ route('passChange') }}" method="POST">
                                        @csrf
                                        <div class="mb-4">
                                            <label class="block text-gray-700 font-medium mb-2">Password Baru</label>
                                            <div class="relative">
                                                <input type="hidden" name="id" value="{{ auth()->user()->id }}">
                                                <input :type="showPassword ? 'text' : 'password'"  placeholder="Masukan Password Baru" name="password"
                                                    class="w-full border px-3 py-2 rounded pr-10 focus:outline-none focus:ring focus:border-blue-300" />
                                               
                                                    <button type="button" @click="showPassword = !showPassword"
                                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500"
                                                    tabindex="-1">
                                                    <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg"
                                                        class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-.07.238-.152.47-.244.696M15 12a3 3 0 01-6 0m9.75 5.25L4.5 4.5" />
                                                    </svg>
                                                    
                                                    <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg"
                                                        class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
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
                                            <button type="submit"
                                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                                Simpan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>


                    </li>

                    <li class="h-px bg-black/5 block my-1"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="text-black dark:text-white flex items-center w-full text-left">
                                <x-icon name="sign-out" class=" mr-2" />
                                Sign Out
                            </button>
                        </form>
                    </li>

                </ul>
            </div>
        </div>
    </div>
</div>
<div class="hidden sm:block">
    <style>
        .border-bottom {
            border-color: #0846c2; /* Tailwind's gray-200 */
        }
    </style>
<ul class="flex space-x-2 border-b border-black/10 dark:border-white/10 pb-2 g-2 pl-5">
    <a href="/keuangan">
        <li class="tab-link cursor-pointer px-4 py-2  hover:text-blue-500 font-semibold flex items-center @if(request()->is('keuangan')) border-b-2 border-bottom @endif">
            <!-- Ikon Kiri -->
            <x-icon name="forms" class=" w-6 h-6 mr-2" />
            <span class="pl-2">Keuangan</span>
        </li>
    </a>

    <a href="/akun">
        <li class="tab-link cursor-pointer px-4 py-2  hover:text-blue-500 font-semibold flex items-center @if(request()->is('akun')) border-b-2 border-bottom @endif">
            <!-- Ikon Kiri -->
            <x-icon name="forms" class=" w-6 h-6 mr-2" />
            <span class="pl-2">Akun</span>
        </li>
    </a>

    <a href="/rekening">
        <li class="tab-link cursor-pointer px-4 py-2  hover:text-blue-500 font-semibold flex items-center @if(request()->is('rekening')) border-b-2 border-bottom @endif">
            <!-- Ikon Kiri -->
            <x-icon name="layer" class=" w-6 h-6 mr-2" />
            <span class="pl-2">Rekening</span>
        </li>
    </a>

    <a href="{{ route('dashboard.keuangan') }}">
        <li class="tab-link cursor-pointer px-4 py-2  hover:text-blue-500 font-semibold flex items-center @if(request()->routeIs('dashboard.keuangan')) border-b-2 border-bottom @endif" onclick="openTab(event, 'tab1')">
            <!-- Ikon Kiri -->
            <x-icon name="dashboard" class=" w-6 h-6 mr-2" />
            <span class="pl-2">Grafik</span>
        </li>
    </a>

    <a href="/setelan">
        <li class="tab-link cursor-pointer px-4 py-2  hover:text-blue-500 font-semibold flex items-center @if(request()->is('setelan')) border-b-2 border-bottom @endif">
            <!-- Ikon Kiri -->
            <x-icon name="gear" class=" w-6 h-6 mr-2" />
            <span class="pl-2">Perusahaan Saya</span>
        </li>
    </a>
</ul>

</div>

<nav class="w-full fixed bottom-0 inset-x-0 z-40 bg-white dark:bg-black  border-t border-gray-200 dark:border-white/10  md:hidden" >
    <div class="flex justify-between">
        
        <!-- Keuangan -->
        <a href="{{ route('index.keuangan') }}"
           class="flex flex-col items-center justify-center w-full py-2 text-xs {{ $active === 'keuangan' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-white/60' }}">
            <svg class="w-5 h-5 mb-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
                <rect x="3" y="7" width="18" height="10" rx="2" stroke="currentColor" stroke-width="1.5"/>
                <circle cx="12" cy="12" r="2.5" stroke="currentColor" stroke-width="1.5"/>
            </svg>
            <span class="text-[10px]">Keuangan</span>
        </a>

        <!-- Dashboard -->
        <a href="{{ route('dashboard.keuangan') }}"
           class="flex flex-col items-center justify-center w-full py-2 text-xs {{ $active === 'keuangan' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-white/60' }}">
            <x-icon name="dashboard" class="w-5 h-5 mb-1" />
            <span class="text-[10px]">Grafik</span>
        </a>
        <!-- Dashboard -->
        <a href="/akun"
           class="flex flex-col items-center justify-center w-full py-2 text-xs {{ $active === 'akun' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-white/60' }}">
             <!-- Ikon Kiri -->
            <x-icon name="forms" class=" w-6 h-6 mr-2" />
            <span class="text-[10px]">Akun</span>
        </a>
        <!-- Dashboard -->
        <a href="/rekening"
           class="flex flex-col items-center justify-center w-full py-2 text-xs {{ $active === 'rekening' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-white/60' }}">
             <x-icon name="layer" class=" w-6 h-6 mr-2" />
            <span class="text-[10px]">Rekening</span>
        </a>


        <!-- Keuangan -->
        <a href="/setelan"
           class="flex flex-col items-center justify-center w-full py-2 text-xs {{ $active === 'setelan' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-white/60' }}">
            <!-- Ikon User (Heroicons) -->
               <x-icon name="user-1" class=" w-6 h-6 mr-2" />
            <span class="text-[10px]">Usaha Saya</span>
        </a>

    </div>
</nav>