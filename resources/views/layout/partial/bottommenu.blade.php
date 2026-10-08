@if (Request::is('keuangan') || Request::is('catatan/keuangan'))
    <nav class="w-full fixed bottom-0 inset-x-0 z-40 bg-white dark:bg-black backdrop-blur-md border-t border-black/10 dark:border-white/10 shadow-[0_-4px_20px_rgba(0,0,0,0.06)] md:hidden pb-[env(safe-area-inset-bottom)]"
        x-data="{ openMenu: null }">
        <div class="flex items-center justify-around relative py-1.5 px-1">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
                class="flex flex-col items-center justify-center flex-1 py-1 transition-all duration-200 {{ $active === 'dashboard' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-white/60' }}">
                <div class="w-5 h-5 flex items-center justify-center mb-0.5">
                    <x-icon name="dashboard" />
                </div>
                <span class="text-[10px] tracking-wide">Dashboard</span>
            </a>

            <!-- Transaksi -->
            <a href="{{ route('transaksi.index') }}"
                class="flex flex-col items-center justify-center flex-1 py-1 transition-all duration-200 {{ $active === 'transaksi' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-white/60' }}">
                <div class="w-5 h-5 flex items-center justify-center mb-0.5">
                    <x-icon name="layer" />
                </div>
                <span class="text-[10px] tracking-wide">Transaksi</span>
            </a>

            <!-- Notifikasi -->
            {{-- <button @click="$store.app.rightSidebar()"
                class="flex flex-col items-center justify-center flex-1 py-1 transition-all duration-200 text-gray-500 dark:text-white/60 relative">
                <div class="w-5 h-5 flex items-center justify-center mb-0.5">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                </div>
                <span class="text-[10px] tracking-wide">Notif</span>
            </button> --}}

            <!-- Tombol + (Floating) -->
            <div class="absolute -top-5 left-1/2 transform -translate-x-1/2 z-50">
                <button @click="$dispatch('transaksi')"
                    class="flex items-center justify-center w-12 h-12 bg-gradient-to-tr from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-full shadow-[0_6px_16px_rgba(37,99,235,0.35)] transition-transform active:scale-95 border-2 border-white dark:border-black">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                </button>
            </div>

            <!-- Nota -->
            <a href="{{ route('nota.index') }}"
                class="flex flex-col items-center justify-center flex-1 py-1 transition-all duration-200 {{ $active === 'nota' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-white/60' }}">
                <div class="w-5 h-5 flex items-center justify-center mb-0.5">
                    <x-icon name="document" />
                </div>
                <span class="text-[10px] tracking-wide">Nota</span>
            </a>

            <!-- Keuangan -->
            <a href="{{ route('index.keuangan') }}"
                class="flex flex-col items-center justify-center flex-1 py-1 transition-all duration-200 {{ $active === 'keuangan' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-white/60' }}">
                <div class="w-5 h-5 flex items-center justify-center mb-0.5">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <rect x="3" y="7" width="18" height="10" rx="2" stroke="currentColor" stroke-width="1.5" />
                        <circle cx="12" cy="12" r="2.5" stroke="currentColor" stroke-width="1.5" />
                    </svg>
                </div>
                <span class="text-[10px] tracking-wide">Keuangan</span>
            </a>

            <!-- Setelan -->
            <a href="{{ route('perusahaan.setting') }}"
                class="flex flex-col items-center justify-center flex-1 py-1 transition-all duration-200 {{ $active === 'setelan' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-white/60' }}">
                <div class="w-5 h-5 flex items-center justify-center mb-0.5">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 256 256">
                        <path
                            d="M226.76,69a8,8,0,0,0-12.84-2.88l-40.3,37.19-17.23-3.7-3.7-17.23,37.19-40.3A8,8,0,0,0,187,29.24,72,72,0,0,0,88,96,72.34,72.34,0,0,0,94,124.94L33.79,177c-.15.12-.29.26-.43.39a32,32,0,0,0,45.26,45.26c.13-.13.27-.28.39-.42L131.06,162A72,72,0,0,0,232,96,71.56,71.56,0,0,0,226.76,69ZM160,152a56.14,56.14,0,0,1-27.07-7,8,8,0,0,0-9.92,1.77L67.11,211.51a16,16,0,0,1-22.62-22.62L109.18,133a8,8,0,0,0,1.77-9.93,56,56,0,0,1,58.36-82.31l-31.2,33.81a8,8,0,0,0-1.94,7.1L141.83,108a8,8,0,0,0,6.14,6.14l26.35,5.66a8,8,0,0,0,7.1-1.94l33.81-31.2A56.06,56.06,0,0,1,160,152Z">
                        </path>
                    </svg>
                </div>
                <span class="text-[10px] tracking-wide">Setelan</span>
            </a>
        </div>
    </nav>
@else
    <nav class="w-full fixed bottom-0 inset-x-0 z-40 bg-white dark:bg-black backdrop-blur-md border-t border-black/10 dark:border-white/10 shadow-[0_-4px_20px_rgba(0,0,0,0.06)] md:hidden pb-[env(safe-area-inset-bottom)]"
        x-data="{ openMenu: null }">
        <div class="flex items-center justify-around relative py-1.5 px-1">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
                class="flex flex-col items-center justify-center flex-1 py-1 transition-all duration-200 {{ $active === 'dashboard' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-white/60' }}">
                <div class="w-5 h-5 flex items-center justify-center mb-0.5">
                    <x-icon name="dashboard" />
                </div>
                <span class="text-[10px] tracking-wide">Dashboard</span>
            </a>

            <!-- Transaksi -->
            <a href="{{ route('transaksi.index') }}"
                class="flex flex-col items-center justify-center flex-1 py-1 transition-all duration-200 {{ $active === 'transaksi' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-white/60' }}">
                <div class="w-5 h-5 flex items-center justify-center mb-0.5">
                    <x-icon name="layer" />
                </div>
                <span class="text-[10px] tracking-wide">Transaksi</span>
            </a>

            <!-- Notifikasi -->
            {{-- <button @click="$store.app.rightSidebar()"
                class="flex flex-col items-center justify-center flex-1 py-1 transition-all duration-200 text-gray-500 dark:text-white/60 relative">
                <div class="w-5 h-5 flex items-center justify-center mb-0.5">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                </div>
                <span class="text-[10px] tracking-wide">Notif</span>
            </button> --}}

            <!-- Nota -->
            <a href="{{ route('nota.index') }}"
                class="flex flex-col items-center justify-center flex-1 py-1 transition-all duration-200 {{ $active === 'nota' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-white/60' }}">
                <div class="w-5 h-5 flex items-center justify-center mb-0.5">
                    <x-icon name="document" />
                </div>
                <span class="text-[10px] tracking-wide">Nota</span>
            </a>

            <!-- Keuangan -->
            <a href="{{ route('index.keuangan') }}"
                class="flex flex-col items-center justify-center flex-1 py-1 transition-all duration-200 {{ $active === 'keuangan' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-white/60' }}">
                <div class="w-5 h-5 flex items-center justify-center mb-0.5">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <rect x="3" y="7" width="18" height="10" rx="2" stroke="currentColor" stroke-width="1.5" />
                        <circle cx="12" cy="12" r="2.5" stroke="currentColor" stroke-width="1.5" />
                    </svg>
                </div>
                <span class="text-[10px] tracking-wide">Keuangan</span>
            </a>

            <!-- Setelan -->
            <a href="{{ route('perusahaan.setting') }}"
                class="flex flex-col items-center justify-center flex-1 py-1 transition-all duration-200 {{ $active === 'setelan' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-white/60' }}">
                <div class="w-5 h-5 flex items-center justify-center mb-0.5">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 256 256">
                        <path
                            d="M226.76,69a8,8,0,0,0-12.84-2.88l-40.3,37.19-17.23-3.7-3.7-17.23,37.19-40.3A8,8,0,0,0,187,29.24,72,72,0,0,0,88,96,72.34,72.34,0,0,0,94,124.94L33.79,177c-.15.12-.29.26-.43.39a32,32,0,0,0,45.26,45.26c.13-.13.27-.28.39-.42L131.06,162A72,72,0,0,0,232,96,71.56,71.56,0,0,0,226.76,69ZM160,152a56.14,56.14,0,0,1-27.07-7,8,8,0,0,0-9.92,1.77L67.11,211.51a16,16,0,0,1-22.62-22.62L109.18,133a8,8,0,0,0,1.77-9.93,56,56,0,0,1,58.36-82.31l-31.2,33.81a8,8,0,0,0-1.94,7.1L141.83,108a8,8,0,0,0,6.14,6.14l26.35,5.66a8,8,0,0,0,7.1-1.94l33.81-31.2A56.06,56.06,0,0,1,160,152Z">
                        </path>
                    </svg>
                </div>
                <span class="text-[10px] tracking-wide">Setelan</span>
            </a>
        </div>
    </nav>
@endif