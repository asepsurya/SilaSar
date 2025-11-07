@if (Request::is('keuangan') || Request::is('catatan/keuangan'))
<nav class="w-full fixed bottom-0 inset-x-0 z-40 bg-white dark:bg-black border-t border-gray-200 dark:border-white/10 md:hidden">
    <div class="flex justify-between relative">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
           class="flex flex-col items-center justify-center w-full py-2 text-xs {{ $active === 'dashboard' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-white/60' }}">
            <x-icon name="dashboard" class="w-5 h-5 mb-1" />
            <span class="text-[10px]">Dashboard</span>
        </a>

        <!-- Transaksi -->
        <a href="{{ route('transaksi.index') }}"
           class="flex flex-col items-center justify-center w-full py-2 text-xs {{ $active === 'transaksi' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-white/60' }}">
            <x-icon name="layer" class="w-5 h-5 mb-1" />
            <span class="text-[10px]">Transaksi</span>
        </a>

        <!-- Tombol + (Floating) -->
        <div class="absolute -top-5 left-1/2 transform -translate-x-1/2"  @click="$dispatch('transaksi')">
            <a
               class="flex items-center justify-center w-12 h-12 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
            </a>
        </div>

        <!-- Nota -->
        <a href="{{ route('nota.index') }}"
           class="flex flex-col items-center justify-center w-full py-2 text-xs {{ $active === 'nota' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-white/60' }}">
            <x-icon name="document" class="w-5 h-5 mb-1" />
            <span class="text-[10px]">Nota</span>
        </a>

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

        <!-- Setelan -->
        <a href="{{ route('perusahaan.setting') }}"
           class="flex flex-col items-center justify-center w-full py-2 text-xs {{ $active === 'setelan' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-white/60' }}">
            <svg class="mb-1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 256 256">
                <path d="M226.76,69a8,8,0,0,0-12.84-2.88l-40.3,37.19-17.23-3.7-3.7-17.23,37.19-40.3A8,8,0,0,0,187,29.24,72,72,0,0,0,88,96,72.34,72.34,0,0,0,94,124.94L33.79,177c-.15.12-.29.26-.43.39a32,32,0,0,0,45.26,45.26c.13-.13.27-.28.39-.42L131.06,162A72,72,0,0,0,232,96,71.56,71.56,0,0,0,226.76,69ZM160,152a56.14,56.14,0,0,1-27.07-7,8,8,0,0,0-9.92,1.77L67.11,211.51a16,16,0,0,1-22.62-22.62L109.18,133a8,8,0,0,0,1.77-9.93,56,56,0,0,1,58.36-82.31l-31.2,33.81a8,8,0,0,0-1.94,7.1L141.83,108a8,8,0,0,0,6.14,6.14l26.35,5.66a8,8,0,0,0,7.1-1.94l33.81-31.2A56.06,56.06,0,0,1,160,152Z"></path>
            </svg>
            <span class="text-[10px]">Setelan</span>
        </a>
    </div>
</nav>
@else
<!-- Bottom Mobile Menu -->
<nav class="w-full fixed bottom-0 inset-x-0 z-40 bg-white dark:bg-black  border-t border-gray-200 dark:border-white/10  md:hidden" >
    <div class="flex justify-between">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
           class="flex flex-col items-center justify-center w-full py-2 text-xs {{ $active === 'dashboard' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-white/60' }}">
            <x-icon name="dashboard" class="w-5 h-5 mb-1" />
            <span class="text-[10px]">Dashboard</span>
        </a>

        <!-- Transaksi -->
        <a href="{{ route('transaksi.index') }}"
           class="flex flex-col items-center justify-center w-full py-2 text-xs {{ $active === 'transaksi' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-white/60' }}">
            <x-icon name="layer" class="w-5 h-5 mb-1" />
            <span class="text-[10px]">Transaksi</span>
        </a>

        <!-- Nota -->
        <a href="{{ route('nota.index') }}"
           class="flex flex-col items-center justify-center w-full py-2 text-xs {{ $active === 'nota' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-white/60' }}">
            <x-icon name="document" class="w-5 h-5 mb-1" />
            <span class="text-[10px]">Nota</span>
        </a>

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

        <!-- Pengguna -->
        <a href="{{ route('perusahaan.setting') }}"
           class="flex flex-col items-center justify-center w-full py-2 text-xs {{ $active === 'setelan' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-white/60' }}">
           <svg class="mb-1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 256 256">
            <path d="M226.76,69a8,8,0,0,0-12.84-2.88l-40.3,37.19-17.23-3.7-3.7-17.23,37.19-40.3A8,8,0,0,0,187,29.24,72,72,0,0,0,88,96,72.34,72.34,0,0,0,94,124.94L33.79,177c-.15.12-.29.26-.43.39a32,32,0,0,0,45.26,45.26c.13-.13.27-.28.39-.42L131.06,162A72,72,0,0,0,232,96,71.56,71.56,0,0,0,226.76,69ZM160,152a56.14,56.14,0,0,1-27.07-7,8,8,0,0,0-9.92,1.77L67.11,211.51a16,16,0,0,1-22.62-22.62L109.18,133a8,8,0,0,0,1.77-9.93,56,56,0,0,1,58.36-82.31l-31.2,33.81a8,8,0,0,0-1.94,7.1L141.83,108a8,8,0,0,0,6.14,6.14l26.35,5.66a8,8,0,0,0,7.1-1.94l33.81-31.2A56.06,56.06,0,0,1,160,152Z"></path>
            </svg>
            <span class="text-[10px]">Setelan</span>
        </a>
    </div>
</nav>
@endif
