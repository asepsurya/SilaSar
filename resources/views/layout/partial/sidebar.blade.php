<style>
      .sidebar {
  z-index: auto; /* default */
}
      .rigtcontent {
  z-index: 0; /* default */
}

@media (min-width: 360px) and (max-width: 767px) {
  .sidebar {
    z-index: 40;
  }
  .right-sidebar {
    z-index: 50;
  }

}

@media (min-width: 768px) {
  .sidebar {
    z-index: 0;
  }
  .rigtcontent {
    z-index: 0;
  }
}



</style>
<nav class="sidebar fixed top-0 bottom-0 z-40 flex-none w-[212px] border-r border-black/10 dark:border-white/10 transition-all duration-300" id="sidebar">
  <!-- sidebar content -->
    <div class="bg-white dark:bg-black h-full">
        <!-- Start Logo -->
   <div class="flex items-center p-4">
    <!-- Logo Bulat -->


    <div
    class="w-10 h-10 rounded-full overflow-hidden flex-shrink-0 bg-white dark:bg-transparent border border-gray-200 dark:border-white/20 flex items-center justify-center">

    <!-- Logo normal (light mode) -->
    <img src="{{ $perusahaan_sidebar->logo ? asset('storage/' . $perusahaan_sidebar->logo) : asset('assets/default_logo.png') }}"
         alt="logo"
         class="w-full h-full object-contain block dark:hidden" />

    <!-- Logo dark mode -->
    <img src="{{ $perusahaan_sidebar->logo ? asset('storage/' . $perusahaan_sidebar->logo) : asset('assets/default_logo.png') }}"
         alt="logo"
         class="w-full h-full object-contain hidden dark:block" />
</div>

<!-- Nama Perusahaan dengan tooltip -->
<div class="ml-3 flex-1 max-w-full group flex items-center">
    <span
        class="block font-semibold text-gray-800 dark:text-white text-sm leading-snug line-clamp-2 cursor-default"
        title="{{ $perusahaan_sidebar->nama_perusahaan }}">
        {{ $perusahaan_sidebar->nama_perusahaan }}
    </span>
</div>

</div>




        <!-- End Logo -->
        <!-- Start Menu -->
        <ul class="relative h-[calc(100vh-58px)] flex flex-col gap-1 overflow-y-auto overflow-x-hidden p-4 py-0"
            x-data="{ activeMenu: '{{ $activeMenu }}' }" id="menu">
            <li class="menu nav-item mb-3">
                @include('layout.partial.seachmenu')
            </li>

            {{-- Dashboard --}}

          <li class="menu nav-item" x-data="{ open: {{ in_array($active ?? '', ['dashboard', 'akun', 'rekening']) ? 'true' : 'false' }} }">

              <!-- Trigger utama -->
              <a href="javascript:;" class="nav-link group text-black dark:text-white" @click="open = !open" :class="{ 'active': open }">

                  <!-- Panah -->
                  <div class="text-black/50 dark:text-white/20 w-4 h-4 flex items-center justify-center transition-transform duration-300" :class="{ '!rotate-90': open }">
                      <svg width="6" height="10" viewBox="0 0 6 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path fill-rule="evenodd" clip-rule="evenodd" d="M0.659675 9.35355C0.446775 9.15829 0.446775 8.84171 0.659675 8.64645L4.25 5.35355C4.4629 5.15829 4.4629 4.84171 4.25 4.64645L0.659675 1.35355C0.446776 1.15829 0.446776 0.841709 0.659675 0.646446C0.872575 0.451184 1.21775 0.451185 1.43065 0.646446L5.02098 3.93934C5.65967 4.52513 5.65968 5.47487 5.02098 6.06066L1.43065 9.35355C1.21775 9.54882 0.872574 9.54882 0.659675 9.35355Z" fill="currentcolor"></path>
                      </svg>
                  </div>

                  <!-- Label -->
                  <div class="flex items-center">
                      <x-icon name="dashboard" class="text-gray-600" />
                      <span class="pl-1">Dashboard</span>
                  </div>
              </a>

              <!-- Submenu -->
              <ul x-show="open" x-collapse class="sub-menu flex flex-col gap-1 text-black dark:text-white/80">
                  <li>
                      <a href="{{ route('dashboard') }}" class="{{ $active === 'dashboard' ? 'active' : '' }}">Penjualan</a>
                  </li>
                  <li>
                      <a href="{{ route('dashboard.keuangan') }}" class="{{ $active === 'dahboardkeuangan' ? 'active' : '' }}">Keuangan</a>
                  </li>
                  <li>
                      <a href="{{ route('dashboard.peta') }}" class="{{ $active === 'peta' ? 'active' : '' }}">Peta</a>
                  </li>
              </ul>
          </li>

          <h2 class="pl-3 my-2 text-black/60 dark:text-white/40 text-sm"><span>Keuangan</span></h2>
          {{-- Data Keuangan --}}
          <li class="menu nav-item" x-data="{ open: {{ in_array($active ?? '', ['keuangan', 'akun', 'rekening']) ? 'true' : 'false' }} }">
              <a href="javascript:;" class="nav-link group text-black dark:text-white" :class="{ 'active': open }" @click="open = !open">
                  <div class="text-black/50 dark:text-white/20 w-4 h-4 flex items-center justify-center transition-transform duration-300" :class="{ '!rotate-90': open }">
                      <svg width="6" height="10" viewBox="0 0 6 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path fill-rule="evenodd" clip-rule="evenodd" d="M0.659675 9.35355C0.446775 9.15829 0.446775 8.84171 0.659675 8.64645L4.25 5.35355C4.4629 5.15829 4.4629 4.84171 4.25 4.64645L0.659675 1.35355C0.446776 1.15829 0.446776 0.841709 0.659675 0.646446C0.872575 0.451184 1.21775 0.451185 1.43065 0.646446L5.02098 3.93934C5.65967 4.52513 5.65968 5.47487 5.02098 6.06066L1.43065 9.35355C1.21775 9.54882 0.872574 9.54882 0.659675 9.35355Z" fill="currentcolor"></path>
                      </svg>
                  </div>
                  <div class="flex items-center">
                      <x-icon name="grafik" class="text-gray-600" />
                      <span class="pl-1">Keuangan</span>
                  </div>
              </a>
              <ul x-show="open" x-collapse class="sub-menu flex flex-col gap-1 text-black dark:text-white/80">
                  <li><a href="{{ route('index.keuangan') }}" class="{{ $active === 'keuangan' ? 'active' : '' }}">Buku Kas</a></li>
                  <li><a href="{{ route('index.akun') }}" class="{{ $active === 'akun' ? 'active' : '' }}">Akun</a>
                  </li>
                  <li><a href="{{ route('akun.rekening') }}" class="{{ $active === 'rekening' ? 'active' : '' }}">Rekening</a></li>
              </ul>
          </li>

          
            

            <li class="menu nav-item"   x-data="{ open: {{ in_array($active ?? '', ['laporan_transaksi', 'neraca', 'neracasaldo','labarugi']) ? 'true' : 'false' }} }">
                <a href="javascript:;"
                   class="nav-link group text-black dark:text-white active"
                   :class="{ 'active': activeMenu === 'laporan' }"
                   @click="activeMenu === 'laporan' ? activeMenu = null : activeMenu = 'laporan'">

                    <!-- Panah -->
                    <div class="text-black/50 dark:text-white/20 w-4 h-4 flex items-center justify-center transition-transform duration-300"
                         :class="{ 'rotate-90': activeMenu === 'laporan' }">
                        <svg width="6" height="10" viewBox="0 0 6 10" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                  d="M0.659675 9.35355C0.446775 9.15829 0.446775 8.84171 0.659675 8.64645L4.25 5.35355C4.4629 5.15829 4.4629 4.84171 4.25 4.64645L0.659675 1.35355C0.446776 1.15829 0.446776 0.841709 0.659675 0.646446C0.872575 0.451184 1.21775 0.451185 1.43065 0.646446L5.02098 3.93934C5.65967 4.52513 5.65968 5.47487 5.02098 6.06066L1.43065 9.35355C1.21775 9.54882 0.872574 9.54882 0.659675 9.35355Z"
                                  fill="currentcolor"></path>
                        </svg>
                    </div>

                    <!-- Label -->
                    <div class="flex items-center">
                        <x-icon name="laporan" class="text-gray-600" />
                        <span class="pl-1">Laporan </span>
                    </div>
                </a>

                <!-- Submenu -->
                <ul x-show="activeMenu === 'laporan'"
                    x-collapse
                    x-transition
                    class="sub-menu flex flex-col gap-1 text-black dark:text-white/80">

                   @php
                        $bulan = date('m');
                        $tahun = date('Y');
                    @endphp

                    <li>
                        <a href="{{ route('laporan.transaksi') }}?bulan={{ $bulan }}&tahun={{ $tahun }}"
                        class="{{ $active === 'laporan_transaksi' ? 'active' : '' }}">
                        Transaksi
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('laporan.neraca') }}?bulan={{ $bulan }}&tahun={{ $tahun }}"
                           class="{{ $active === 'neraca' ? 'active' : '' }}">
                           Neraca
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('laporan.neraca_saldo') }}"
                           class="{{ $active === 'neracasaldo' ? 'active' : '' }}">
                           Neraca Saldo
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('laporan.labarugi') }}"
                           class="{{ $active === 'labarugi' ? 'active' : '' }}">
                           Laba Rugi
                        </a>
                    </li>

                </ul>
            </li>


            <h2 class="pl-3 my-2 text-black/60 dark:text-white/40 text-sm"><span>Administrasi</span></h2>

            <li class="menu nav-item" x-data="{ open: {{ in_array($active ?? '', ['add_produk', 'produk', 'category','satuan']) ? 'true' : 'false' }} }">
                <a href="javascript:;" class="nav-link group text-black dark:text-white" :class="{ 'active': open }"
                    @click="open = !open">
                    <div class="text-black/50 dark:text-white/20 w-4 h-4 flex items-center justify-center transition-transform duration-300"
                        :class="{ '!rotate-90': open }">
                        <svg width="6" height="10" viewBox="0 0 6 10" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M0.659675 9.35355C0.446775 9.15829 0.446775 8.84171 0.659675 8.64645L4.25 5.35355C4.4629 5.15829 4.4629 4.84171 4.25 4.64645L0.659675 1.35355C0.446776 1.15829 0.446776 0.841709 0.659675 0.646446C0.872575 0.451184 1.21775 0.451185 1.43065 0.646446L5.02098 3.93934C5.65967 4.52513 5.65968 5.47487 5.02098 6.06066L1.43065 9.35355C1.21775 9.54882 0.872574 9.54882 0.659675 9.35355Z"
                                fill="currentcolor"></path>
                        </svg>
                    </div>
                    <div class="flex items-center">
                        <x-icon name="forms" class="text-gray-600" />
                        <span class="pl-1">Data Produk</span>
                    </div>
                </a>
                <ul x-show="open" x-collapse class="sub-menu flex flex-col gap-1 text-black dark:text-white/80">
                    <li><a href="{{ route('index.create.produk') }}"
                            class="{{ $active === 'add_produk' ? 'active' : '' }}">Tambah Data</a></li>
                    <li><a href="{{ route('index.produk') }}"
                            class="{{ $active === 'produk' ? 'active' : '' }}">Data Produk</a></li>
                    <li><a href="{{ route('produk.category') }}"
                            class="{{ $active === 'category' ? 'active' : '' }}">Kategori</a></li>
                   
                    <li><a href="{{ route('satuan.index') }}"
                            class="{{ $active === 'satuan' ? 'active' : '' }}">Satuan</a></li>
                </ul>
            </li>

            {{-- Data Mitra --}}
            <li class="menu nav-item">
                <a href="{{ route('manajemenStok.index') }}" class="{{ $active === 'persediaan' ? 'active' : '' }}">
                    <div class="flex pl-5 items-center">
                        <x-icon name="stok" class="text-gray-600" />
                        <span class="pl-1 text-black dark:text-white">Manajemen Stok</span>
                    </div>
                </a>
            </li>
            <li class="menu nav-item">
                <a href="{{ route('index.mitra') }}" class="{{ $active === 'mitra' ? 'active' : '' }}">
                    <div class="flex pl-5 items-center">
                        <x-icon name="supplier" class="text-gray-600" />
                        <span class="pl-1 text-black dark:text-white">Data Mitra</span>
                    </div>
                </a>
            </li>
            <h2 class="pl-3 my-2 text-black/60 dark:text-white/40 text-sm"><span>Transaksi</span></h2>
            {{-- Penjualan --}}
            <li class="menu nav-item">
                <a class="nav-link group {{ $active === 'transaksi' ? 'active' : '' }}"
                    href="{{ route('transaksi.index') }}">
                    <div class="flex pl-5 items-center">
                        <x-icon name="layer" class="text-gray-600" />
                        <span class="pl-1 text-black dark:text-white">Transaksi</span>
                    </div>
                </a>
            </li>

            {{-- Nota dan Kwitansi --}}
            <li class="menu nav-item">
                <a class="nav-link group {{ $active === 'nota' ? 'active' : '' }}" href="{{ route('nota.index') }}">
                    <div class="flex pl-5 items-center">
                        <x-icon name="document" class="text-gray-600" />
                        <span class="pl-1 text-black dark:text-white">Nota dan Kwitansi</span>
                    </div>
                </a>
            </li>
            <li class="menu nav-item">
                <a class="nav-link group {{ $active === 'laporan_penjualan' ? 'active' : '' }}" href="{{ route('laporan.penjualan') }}">
                    <div class="flex pl-5 items-center">
                        <x-icon name="penjualan" class="text-gray-600" />
                        <span class="pl-1 text-black dark:text-white">Laporan Penjualan</span>
                    </div>
                </a>
            </li>

            <h2 class="pl-3 my-2 text-black/60 dark:text-white/40 text-sm"><span>Setelan</span></h2>

            {{-- Data IKM --}}
            @if (auth()->check() && auth()->user()->role === 'admin' || auth()->user()->role === 'superadmin')
               {{-- <h2 class="pl-3 my-2 text-black/60 dark:text-white/40 text-sm"><span>Master Data</span></h2> --}}
                <li class="menu nav-item" x-data="{ open: {{ in_array($active ?? '', ['ikm', 'ikm_create', 'ikm_update']) ? 'true' : 'false' }} }">
                    <a href="javascript:;" class="nav-link group text-black dark:text-white"
                        :class="{ 'active': open }" @click="open = !open">
                        <div class="text-black/50 dark:text-white/20 w-4 h-4 flex items-center justify-center transition-transform duration-300"
                            :class="{ '!rotate-90': open }">
                            <svg width="6" height="10" viewBox="0 0 6 10" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M0.659675 9.35355C0.446775 9.15829 0.446775 8.84171 0.659675 8.64645L4.25 5.35355C4.4629 5.15829 4.4629 4.84171 4.25 4.64645L0.659675 1.35355C0.446776 1.15829 0.446776 0.841709 0.659675 0.646446C0.872575 0.451184 1.21775 0.451185 1.43065 0.646446L5.02098 3.93934C5.65967 4.52513 5.65968 5.47487 5.02098 6.06066L1.43065 9.35355C1.21775 9.54882 0.872574 9.54882 0.659675 9.35355Z"
                                    fill="currentcolor"></path>
                            </svg>
                        </div>
                        <div class="flex items-center">
                            <x-icon name="user" class="text-gray-600" />
                            <span class="pl-1">Data Pengguna</span>
                        </div>
                    </a>
                    <ul x-show="open" x-collapse class="sub-menu flex flex-col gap-1 text-black dark:text-white/80">
                        <li><a href="{{ route('ikm.create') }}"
                                class="{{ $active === 'ikm_create' ? 'active' : '' }}">Tambah Data</a></li>
                        <li><a href="{{ route('index.ikm') }}" class="{{ $active === 'ikm' ? 'active' : '' }}">Data
                                Pengguna</a></li>
                    </ul>
                </li>
            @endif
             
            <li class="menu nav-item">
                <a class="nav-link group" href="{{ route('perusahaan.setting') }}">
                    <div class="flex pl-5 items-center">
                       <x-icon name="usaha" class="text-gray-600" />
                        <span class="pl-1 text-black dark:text-white">Perusahaan Saya</span>
                    </div>
                </a>
            </li>

        <!-- End Menu -->
    </div>
</nav>

@include('layout.partial.bottommenu')
