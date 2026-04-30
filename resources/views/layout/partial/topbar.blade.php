<div class="border-b border-black/10 dark:border-white/10 py-[22px] px-7 flex items-center justify-between">
    <div class="flex items-center gap-2">
        <button class="flex items-start  md:hidden" @click="$store.app.toggleSidebar()">
            <div title="{{ $perusahaan_sidebar->nama_perusahaan }}"
                class=" cursor-default w-8 h-8 rounded-full overflow-hidden flex-shrink-0 bg-white dark:bg-transparent border border-gray-200 dark:border-white/20 flex items-center justify-center">
                <img src="{{ $perusahaan_sidebar->logo ? asset('storage/' . $perusahaan_sidebar->logo) : asset('assets/default_logo.png') }}"
                    alt="logo" class="w-full h-full object-contain block " />

            </div>

        </button>
        <button type="button" class="hidden md:block text-black dark:text-white" @click="$store.app.toggleSidebar()">
            <x-icon name="sidebar" class="text-gray-600" />
        </button>


        <div class="hidden sm:block">
            <nav aria-label="breadcrumb" class="w-full py-1 px-2">
                <ol class="flex space-x-2 text-sm">
                    @php
                        $segments = Request::segments();
                        $url = '';
                    @endphp

                    <li>
                        <a href="{{ url('/') }}"
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

                            $isLinkable = !$isLast && !in_array($segment, ['update', 'detail']);
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
                    <x-icon name="moon" class="text-gray-600" />
                </a>
                <a href="javascript:;" class="text-black dark:text-white" x-cloak x-show="$store.app.mode === 'dark'"
                    @click="$store.app.toggleMode('light')">
                    <x-icon name="sun" class="text-gray-600" />
                </a>
            </div>
            <button type="button" class="relative w-7 h-7 p-1 text-black dark:text-white"
                @click="$store.app.rightSidebar()" id="rightSidebar">
                <x-icon name="notif" class="text-gray-600" />
                <span class="flex absolute w-3 h-3 right-px top-[5px]">
                    <span
                        class="animate-ping absolute -left-[3px] -top-[3px] inline-flex h-full w-full rounded-full bg-black/50 dark:bg-white/50 opacity-75"></span>
                    <span class="relative inline-flex rounded-full w-[6px] h-[6px] bg-black dark:bg-white"></span>
                </span>
            </button>

            @if (auth()->user()->role === 'gold')
                <div
                    class="inline-flex items-center gap-1 rounded text-xs justify-center px-2 py-1 bg-lightyellow text-black">
                    <i class="ph ph-crown-simple text-sm"></i>
                    Gold
                </div>

            @elseif (auth()->user()->role === 'platinum')
                <div
                    class="inline-flex items-center gap-1 rounded text-xs justify-center px-2 py-1 bg-lightblue-200 text-black">
                    <i class="ph ph-crown-simple text-sm"></i>
                    Platinum
                </div>

            @elseif (auth()->user()->role === 'admin')
                <div
                    class="inline-flex items-center gap-1 rounded text-xs justify-center px-2 py-1 bg-indigo-300 text-black">
                    <i class="ph ph-crown-simple text-sm"></i>
                    Admin
                </div>

            @elseif (auth()->user()->role === 'superadmin')
                <div
                    class="inline-flex items-center gap-1 rounded text-xs justify-center px-2 py-1 bg-lightgreen-100 text-black">
                    <i class="ph ph-crown-simple text-sm"></i>
                    Superadmin
                </div>
            @endif


            <div class="profile" x-data="dropdown" @click.outside="open = false">
                <button type="button" class="flex items-center gap-1.5 xl:gap-0" @click="toggle()">
                    <img class="h-7 w-7 rounded-full xl:mr-2"
                        src="{{ auth()->user()->ikm && auth()->user()->ikm->foto ? asset('storage/' . auth()->user()->ikm->foto) : asset('assets/images/byewind-avatar.png') }}"
                        alt="Header Avatar" />

                    <span class="fw-medium hidden xl:block" title="{{ auth()->user()->name }}">
                        Hallo, {{ explode(' ', auth()->user()->name)[0] }}
                    </span>

                    <x-icon name="arrow-bottom" class="text-gray-600" />
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
                            <x-icon name="user-rounded" class="text-gray-600" />
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
                    <li class="p-3">
                        <form method="POST" action="{{ route('logout') }}" class="flex items-center">
                            @csrf
                            <button type="submit" class="text-black dark:text-white flex items-center w-full text-left">
                                <x-icon name="sign-out" class="text-gray-600 mr-2" />
                                Sign Out
                            </button>
                        </form>
                    </li>

                </ul>
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
                    <div
                        class="mb-3 flex items-center rounded bg-lightblue-200/50 dark:bg-lightblue-200 p-3 text-black/80 dark:text-black">
                        <svg class="w-5 h-5 mr-2" width="32" height="32" viewBox="0 0 32 32" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M16 3C16 3 18.6442 3 21.0605 4.02201C21.0605 4.02201 23.3936 5.00884 25.1924 6.80761C25.1924 6.80761 26.9912 8.60638 27.978 10.9395C27.978 10.9395 29 13.3558 29 16C29 16 29 18.6442 27.978 21.0605C27.978 21.0605 26.9912 23.3936 25.1924 25.1924C25.1924 25.1924 23.3936 26.9912 21.0605 27.978C21.0605 27.978 18.6442 29 16 29C16 29 13.3558 29 10.9395 27.978C10.9395 27.978 8.60638 26.9912 6.80761 25.1924C6.80761 25.1924 5.00884 23.3936 4.02202 21.0605C4.02202 21.0605 3 18.6442 3 16C3 16 3 13.3558 4.02202 10.9395C4.02202 10.9395 5.00885 8.60638 6.80761 6.80761C6.80761 6.80761 8.60638 5.00884 10.9395 4.02201C10.9395 4.02201 13.3558 3 16 3ZM16 5C16 5 13.7614 5 11.7186 5.86402C11.7186 5.86402 9.74476 6.69889 8.22183 8.22182C8.22183 8.22182 6.6989 9.74476 5.86402 11.7186C5.86402 11.7186 5 13.7614 5 16C5 16 5 18.2386 5.86402 20.2814C5.86402 20.2814 6.69889 22.2552 8.22183 23.7782C8.22183 23.7782 9.74476 25.3011 11.7186 26.136C11.7186 26.136 13.7614 27 16 27C16 27 18.2386 27 20.2814 26.136C20.2814 26.136 22.2552 25.3011 23.7782 23.7782C23.7782 23.7782 25.3011 22.2552 26.136 20.2814C26.136 20.2814 27 18.2386 27 16C27 16 27 13.7614 26.136 11.7186C26.136 11.7186 25.3011 9.74476 23.7782 8.22183C23.7782 8.22183 22.2552 6.69889 20.2814 5.86402C20.2814 5.86402 18.2386 5 16 5Z"
                                fill="currentColor"></path>
                            <path
                                d="M16 23H17C17.5523 23 18 22.5523 18 22C18 21.4477 17.5523 21 17 21V15C17 14.4477 16.5523 14 16 14H15C14.4477 14 14 14.4477 14 15C14 15.5523 14.4477 16 15 16V22C15 22.5523 15.4477 23 16 23Z"
                                fill="currentColor"></path>
                            <path
                                d="M17.25 10.5C17.25 11.3284 16.5784 12 15.75 12C14.9216 12 14.25 11.3284 14.25 10.5C14.25 9.67157 14.9216 9 15.75 9C16.5784 9 17.25 9.67157 17.25 10.5Z"
                                fill="currentColor"></path>
                        </svg>
                        <span class="pr-2">Pastikan password, anda yang masukan benar.</span>
                        <button type="button"
                            class="ml-auto hover:opacity-50 rotate-0 hover:rotate-180 transition-all duration-300">
                            <svg class="w-5 h-5" width="32" height="32" viewBox="0 0 32 32" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M24.2929 6.29289L6.29289 24.2929C6.10536 24.4804 6 24.7348 6 25C6 25.2652 6.10536 25.5196 6.29289 25.7071C6.48043 25.8946 6.73478 26 7 26C7.26522 26 7.51957 25.8946 7.70711 25.7071L25.7071 7.70711C25.8946 7.51957 26 7.26522 26 7C26 6.73478 25.8946 6.48043 25.7071 6.29289C25.5196 6.10536 25.2652 6 25 6C24.7348 6 24.4804 6.10536 24.2929 6.29289Z"
                                    fill="currentcolor"></path>
                                <path
                                    d="M7.70711 6.29289C7.51957 6.10536 7.26522 6 7 6C6.73478 6 6.48043 6.10536 6.29289 6.29289C6.10536 6.48043 6 6.73478 6 7C6 7.26522 6.10536 7.51957 6.29289 7.70711L24.2929 25.7071C24.4804 25.8946 24.7348 26 25 26C25.2652 26 25.5196 25.8946 25.7071 25.7071C25.8946 25.5196 26 25.2652 26 25C26 24.7348 25.8946 24.4804 25.7071 24.2929L7.70711 6.29289Z"
                                    fill="currentcolor"></path>
                            </svg>
                        </button>
                    </div>
                    <form x-data="{ showPassword: false }" action="{{ route('passChange') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block  font-medium mb-2">Password Baru</label>
                            <div class="relative">
                                <input type="hidden" name="id" value="{{ auth()->user()->id }}">
                                <input :type="showPassword ? 'text' : 'password'" placeholder="Masukan Password Baru"
                                    name="password"
                                    class="form-select py-2 px-4 rounded-lg border border-black/10 dark:border-white/10 text-sm w-full bg-transparent dark:bg-transparent text-black dark:text-white w-full border dark:bg-black px-3 py-2 rounded pr-10 focus:outline-none focus:ring focus:border-blue-300" />

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