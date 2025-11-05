@extends('layout.main')
@section('css')
@section('title', 'Setelan Perusahaan')
@section('container')
    <style>
    #signature-pad {
    border: 2px solid #333;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    cursor: crosshair;

    /* ✅ Biar responsive */
    width: 100%;
    max-width: 100%;
    height: auto;

    /* ✅ Penting supaya layar tidak geser waktu tanda tangan di HP */
    touch-action: none;
}

@media (max-width: 768px) {
    #signature-pad {
        height: 250px; /* default tinggi di HP */
    }
}
        @media (min-width: 640px) {
            .sm\:p-7 {
                padding: 0;
            }
        }

        @media (min-width: 768px) {
            .sm\:p-7 {
                padding: 0;
            }

            .mobile {
                visibility: none;
            }
        }

        .space-x-3>:not([hidden])~:not([hidden]) {
            --tw-space-x-reverse: none;

        }

        .select2-container--default .select2-selection--single {
            margin-left: -10px;
            border: none;
        }

        .dark .select2-container--default .select2-selection--single {
            background-color: rgba(0, 0, 0, 0);
            margin-left: -10px;
            border: none;
        }
    </style>
    <script>
        document.getElementById('rightSidebar').addEventListener('click', function() {
            const sidebar = document.getElementById('rigtcontent');
            const floatingButtons = document.querySelector('.floating-buttons');

            // Kalau sidebar sedang tampil (display !== 'none')
            if (window.getComputedStyle(sidebar).display !== 'none') {
                // Sembunyikan sidebar
                sidebar.style.display = 'none';
                // Geser floating button ke 0
                floatingButtons.style.marginRight = '0';
            } else {
                // Tampilkan sidebar
                sidebar.style.display = 'block';
                // Geser floating button ke 280px
                floatingButtons.style.marginRight = '280px';
            }
        });
    </script>
    <style>
        .floating-buttons {
            position: fixed;
            right: 24px;
            bottom: 24px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            z-index: 9999;
            margin-right: 280px;
            transition: margin-right 0.3s ease;
        }

        /* Untuk HP & tablet kecil */
        @media (max-width: 768px) {
            .floating-buttons {
                margin-right: 0;
                margin-bottom:50px;
            }

            #mycontent {
                margin-bottom: 100px;
            }
        }

        /* Untuk tablet besar & laptop kecil sampai 1030px */
        @media (max-width: 1030px) {
            .floating-buttons {
                margin-right: 0;
            }
        }

        /* Untuk layar menengah ke atas sampai 1502px */
        @media (max-width: 1502px) {
            .floating-buttons {
                margin-right: 0;
            }
        }

        /* Tombol bulat floating */
        .btn-icon {
            background-color: #2563eb;
            border: none;
            border-radius: 50%;
            width: 48px;
            height: 48px;
            color: white;
            font-size: 20px;
            cursor: pointer;
            box-shadow: 0 4px 6px rgb(0 0 0 / 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s ease;
        }

        .btn-icon:hover {
            background-color: #1e40af;
        }
    </style>
    
        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('superadmin') || auth()->user()->hasRole('platinum'))
<!-- Bottom Navigation Menu - Mobile Only -->
<nav class="bg-white dark:bg-black border-b-2 border-gray-200 dark:border-white/10 md:hidden">
    <div class="flex h-16">
        <!-- Profil -->
        <button onclick="openTab('tab-profil')"
            class="flex-1 flex flex-col items-center justify-center text-xs text-center text-gray-700 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 py-2">
            <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 2a6 6 0 100 12 6 6 0 000-12zM2 18a8 8 0 1116 0H2z" />
            </svg>
            <span class="text-xs">Profil Perusahaan</span>
        </button>

        <!-- Legalitas -->
        <button onclick="openTab('tab-legalitas')"
            class="flex-1 flex flex-col items-center justify-center text-xs text-center text-gray-700 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 py-2">
            <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                <path d="M5 4h10v2H5V4zM4 8h12v2H4V8zm1 4h10v2H5v-2z" />
            </svg>
            <span class="text-xs">Legalitas</span>
        </button>

        @if(auth()->user()->role != 'gold')
        <!-- Stempel -->
        <button onclick="openTab('tab-stamp')"
            class="flex-1 flex flex-col items-center justify-center text-xs text-center text-gray-700 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 py-2">
            <svg class="w-6 h-6 mb-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M4 21v-18l2 2 2-2 2 2 2-2 2 2 2-2v18z" />
                <line x1="8" y1="10" x2="16" y2="10" />
                <line x1="8" y1="14" x2="16" y2="14" />
                <line x1="8" y1="18" x2="12" y2="18" />
            </svg>
            <span class="text-xs">Nota dan Kwitansi</span>
        </button>
        @endif
    </div>
</nav>

@endif
    <div class="flex">
        @if(auth()->user()->role != "gold")
        <!-- Sidebar Tab -->
        <div class="hidden md:block p-4 w-[30%] h-screen">
            <ul class="space-y-2">
                <li>
                    <button onclick="openTab('tab-profil')"
                        class="w-full text-left px-4 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-800 font-medium"
                        id="btn-tab-profil">
                        Profil Usaha
                    </button>
                </li>
                <li>
                    <button onclick="openTab('tab-legalitas')"
                        class="w-full text-left px-4 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-800 font-medium"
                        id="btn-tab-legalitas">
                        Legalitas Usaha
                    </button>
                </li>
                <li>
                    <button onclick="openTab('tab-stamp')"
                        class="w-full text-left px-4 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-800 font-medium"
                        id="btn-tab-stamp">
                        Setelan Nota dan Kwitansi
                    </button>
                </li>
            </ul>
        </div>
        @endif
        <!-- Content Area -->
        <div id="mycontent" class=" w-full pl-6 p-5 border-l border-gray-200 dark:border-white/10">
            <div id="tab-profil" class="tab-content">
                <form action="{{ route('perusahaan.update.profil') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="text" name="id" value="{{ $perusahaan->id }}" hidden>
                    <div class="grid grid-flow-row gap-7">
                        <div>
                            <h2 class="text-lg font-semibold mb-4">Perusahaan Saya</h2>
                            <div class="relative inline-block">
                                <!-- Image Preview -->
                                <img id="logo-preview"
                                    class="w-[120px] h-[120px] flex-none rounded-full overflow-hidden object-cover mb-2 cursor-pointer"
                                    src="{{ $perusahaan->logo ? asset('storage/' . $perusahaan->logo) : asset('assets/default_logo.png') }}"
                                    alt="Logo Perusahaan" onclick="document.getElementById('logo-upload').click()">

                                <!-- File Input (hidden) -->
                                <input type="file" id="logo-upload" class="hidden" 
                                    onchange="uploadLogo(this)">

                                <!-- Loading Overlay -->
                                <div id="logo-loading"
                                    class="absolute inset-0 flex items-center justify-center bg-black/30 rounded-full hidden">
                                    <div class="animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-white"></div>
                                </div>
                            </div>
                            <p class="text-xs text-black/40 dark:text-white/40">Allowed file types: png, jpg, jpeg.</p>
                        </div>
                        <div
                            class="border bg-lightwhite dark:bg-white/5 dark:border-white/10 border-black/10 p-5 rounded-md">
                            <div class="mb-3">
                                <p class="text-sm font-semibold">Data Perusahaan</p>
                            </div>
                            <div class="relative mb-3">
                                <input type="text" id="cold-design" name="nama_perusahaan"
                                    class="block rounded-lg px-5 pb-4 pt-[38px] w-full text-black dark:text-white bg-white dark:bg-white/5 border border-black/10 dark:border-white/10 appearance-none focus:outline-none focus:ring-0 focus:border-black/10 dark:focus:border-black/10 peer"
                                    placeholder=" " value="{{ $perusahaan->nama_perusahaan }}">
                                <label for="cold-design"
                                    class="absolute text-sm text-black/40 dark:text-white/40 duration-300 transform -translate-y-2 scale-90 top-6 z-10 origin-[0] left-5 peer-focus:text-black/40 dark:peer-focus:text-white/40 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-90 peer-focus:-translate-y-2">
                                    Nama Perusahaan <span style="color:red">*</span>
                                </label>
                            </div>
                            <div class="relative mb-3">
                                <input type="text" value="{{ $perusahaan->telp_perusahaan }}" name="telp_perusahaan"
                                    class="block rounded-lg px-5 pb-4 pt-[38px] w-full text-black dark:text-white bg-white dark:bg-white/5 border border-black/10 dark:border-white/10 appearance-none focus:outline-none focus:ring-0 focus:border-black/10 dark:focus:border-black/10 peer"
                                    placeholder=" ">
                                <label for="relationship"
                                    class="absolute text-sm text-black/40 dark:text-white/40 duration-300 transform -translate-y-2 scale-90 top-6 z-10 origin-[0] left-5 peer-focus:text-black/40 dark:peer-focus:text-white/40 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-90 peer-focus:-translate-y-2">
                                    Telepon Perusahaan <span style="color:red">*</span>
                                </label>
                            </div>
                            <div class="relative mb-3">
                                <input type="text" value="{{ $perusahaan->email }}" id="relationship" name="email"
                                    class="block rounded-lg px-5 pb-4 pt-[38px] w-full text-black dark:text-white bg-white dark:bg-white/5 border border-black/10 dark:border-white/10 appearance-none focus:outline-none focus:ring-0 focus:border-black/10 dark:focus:border-black/10 peer"
                                    placeholder=" ">
                                <label for="relationship"
                                    class="absolute text-sm text-black/40 dark:text-white/40 duration-300 transform -translate-y-2 scale-90 top-6 z-10 origin-[0] left-5 peer-focus:text-black/40 dark:peer-focus:text-white/40 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-90 peer-focus:-translate-y-2">
                                    Email <span style="color:red">*</span>
                                </label>
                            </div>
                            <div class="relative mb-3">
                                <textarea id="description" name="alamat"
                                    class="block rounded-lg px-5 pb-4 pt-[38px] w-full text-black dark:text-white bg-white dark:bg-white/5 border border-black/10 dark:border-white/10 appearance-none focus:outline-none focus:ring-0 focus:border-black/10 dark:focus:border-black/10 peer"
                                    placeholder=" ">{{ $perusahaan->alamat }}</textarea>
                                <label for="description"
                                    class="absolute text-sm text-black/40 dark:text-white/40 duration-300 transform -translate-y-2 scale-90 top-6 z-10 origin-[0] left-5 peer-focus:text-black/40 dark:peer-focus:text-white/40 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-90 peer-focus:-translate-y-2">
                                    Alamat Usaha <span style="color:red">*</span>
                                </label>
                            </div>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-7 ">
                                {{-- Provinsi --}}
                                <div
                                    class=" px-5 w-full pl-12 pr-2 py-3 text-[#334155] placeholder-[#64748b] bg-white  dark:bg-white/5 dark:border-white/10 rounded-lg border @error('alamat') border-red-500 @enderror focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                                    <label for="provinsi"
                                        class="text-sm font-medium text-slate-700 mb-1 block">Provinsi <span style="color:red">*</span></label>
                                    <div class="relative">

                                        <select id="provinsi" name="id_provinsi"
                                            class="select2 pl-10 pr-4 py-2 w-full border rounded-lg text-slate-700 focus:ring-2 focus:ring-blue-600" required>
                                            <option value="">Pilih Provinsi </option>
                                            @foreach ($provinsi as $ikm2)
                                                <option value="{{ $ikm2->id }}"
                                                    {{ $ikm2->id == $perusahaan->id_provinsi ? 'selected' : '' }}>
                                                    {{ $ikm2->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('id_provinsi')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Kabupaten --}}
                                <div
                                    class="px-5 w-full pl-12 pr-2 py-3 text-[#334155] placeholder-[#64748b] bg-white  dark:bg-white/5 dark:border-white/10 rounded-lg border @error('alamat') border-red-500 @enderror focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                                    <label for="kabupaten" class="text-sm font-medium text-slate-700 mb-1 block">Kota /
                                        Kabupaten <span style="color:red">*</span></label>
                                    <div class="relative">
                                        <select id="kabupaten" name="id_kota"
                                            class="select2 pl-10 pr-4 py-2 w-full border rounded-lg text-slate-700 focus:ring-2 focus:ring-blue-600" required>
                                            <option value="">Pilih Kota/Kabupaten</option>
                                            <option value="{{ $perusahaan->id_kota ?? '' }}" selected>
                                                {{ $perusahaan->kota->name ?? '' }}</option>
                                        </select>
                                    </div>
                                    @error('id_kota')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Kecamatan --}}
                                <div
                                    class="px-5 w-full pl-12 pr-2 py-3 text-[#334155] placeholder-[#64748b] bg-white  dark:bg-white/5 dark:border-white/10 rounded-lg border @error('alamat') border-red-500 @enderror focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                                    <label for="kecamatan"
                                        class="text-sm font-medium text-slate-700 mb-1 block">Kecamatan <span style="color:red">*</span></label>
                                    <div class="relative">

                                        <select id="kecamatan" name="id_kecamatan" required
                                            class="select2 pl-10 pr-4 py-2 w-full border rounded-lg text-slate-700 focus:ring-2 focus:ring-blue-600">
                                            <option value="">Pilih Kecamatan</option>
                                            <option value="{{ $perusahaan->id_kecamatan ?? '' }}" selected>
                                                {{ $perusahaan->kecamatan->name ?? '' }}</option>
                                        </select>
                                    </div>
                                    @error('id_kecamatan')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Desa --}}
                                <div
                                    class="px-5 w-full pl-12 pr-2 py-3 text-[#334155] placeholder-[#64748b] bg-white  dark:bg-white/5 dark:border-white/10 rounded-lg border @error('alamat') border-red-500 @enderror focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                                    <label for="desa" class="text-sm font-medium text-slate-700 mb-1 block">Desa /
                                        Kelurahan <span style="color:red">*</span> </label>
                                    <div class="relative">

                                        <select id="desa" name="id_desa" required
                                            class="select2 pl-10 pr-4 py-2 w-full border rounded-lg text-slate-700 focus:ring-2 focus:ring-blue-600">
                                            <option value="">Pilih Desa</option>
                                            <option value="{{ $perusahaan->id_desa ?? '' }}" selected>
                                                {{ $perusahaan->desa->name ?? '' }}</option>
                                        </select>
                                    </div>
                                    @error('id_desa')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div
                            class="border bg-lightwhite dark:bg-white/5 dark:border-white/10 border-black/10 p-5 rounded-md">
                            <div class="mb-3">
                                <p class="text-sm font-semibold">Identitas Pemilik Usaha</p>
                            </div>
                            <div class="relative mb-3">
                                <input type="text" value="{{ auth()->user()->ikm->nik ?? '' }}" id="relationship"
                                    class="block rounded-lg px-5 pb-4 pt-[38px] w-full text-black dark:text-white bg-white  dark:bg-white/5 border border-black/10 dark:border-white/10 appearance-none focus:outline-none focus:ring-0 focus:border-black/10 dark:focus:border-black/10 peer"
                                    placeholder="Nomor Induk Kependudukan" disabled>
                                <label for="relationship"
                                    class="absolute text-sm text-black/40 dark:text-white/40 duration-300 transform -translate-y-2 scale-90 top-6 z-10 origin-[0] left-5 peer-focus:text-black/40 dark:peer-focus:text-white/40 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-90 peer-focus:-translate-y-2">
                                    NIK
                                </label>
                            </div>
                            <div class="relative">
                                <input type="text" value="{{ auth()->user()->ikm->nama ?? '' }}" id="relationship"
                                    class="block rounded-lg px-5 pb-4 pt-[38px] w-full text-black dark:text-white bg-white dark:bg-white/5 border border-black/10 dark:border-white/10 appearance-none focus:outline-none focus:ring-0 focus:border-black/10 dark:focus:border-black/10 peer"
                                    placeholder=" " readonly>
                                <label for="relationship"
                                    class="absolute text-sm text-black/40 dark:text-white/40 duration-300 transform -translate-y-2 scale-90 top-6 z-10 origin-[0] left-5 peer-focus:text-black/40 dark:peer-focus:text-white/40 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-90 peer-focus:-translate-y-2">
                                    Nama Lengkap
                                </label>
                            </div>
                        </div>
                        <div class="floating-buttons ">

                            <button type="submit" title="Simpan" class="btn-icon">
                                <i class="fas fa-save"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div id="tab-legalitas" class="tab-content hidden">
                <form action="{{ route('perusahaan.update.legalitas') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id_perusahaan" value="{{ $perusahaan->id }}">
                    {{-- Konten form Legalitas Usaha --}}
                    <h2 class="text-lg font-semibold mb-4">Legalitas Usaha</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6" id="legalitas-container">

                        {{-- Legalitas Default (NIB, NPWP, Akta, SK) --}}
                        @php
                            $legalitasList = [
                                'nib' => 'Nomor Induk NIB',
                                'npwp' => 'Nomor NPWP',
                                'akta' => 'Akta Badan Usaha',
                                'sk' => 'SK Kemenkumham',
                            ];
                        @endphp

                        @php
                            $defaultKeys = array_keys($legalitasList);

                            // Map legalitas dari database berdasar nama legalitas
                            $legalitasMap = $perusahaan->legalitas->keyBy('legalitas');

                            // Filter legalitas yang tidak ada di default list
                            $legalitasLain = $perusahaan->legalitas->filter(function ($item) use ($defaultKeys) {
                                return !in_array($item->legalitas, $defaultKeys);
                            });
                        @endphp

                        {{-- 1. Legalitas Default --}}
                        @foreach ($legalitasList as $key => $label)
                            @php
                                $existing = $legalitasMap[$key] ?? null;
                            @endphp
                            <div class="border border-black/10 dark:border-white/10 rounded-lg mb-2">
                                <button type="button"
                                    class="w-full flex justify-between items-center px-4 py-3 bg-gray-100 dark:bg-white/10 rounded-t-lg focus:outline-none"
                                    onclick="toggleAccordion('accordion-{{ $key }}')">
                                    <span class="font-semibold text-sm">{{ $label }}</span>
                                    <svg class="w-4 h-4 transition-transform rotate-180" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div id="accordion-{{ $key }}"
                                    class="px-4 py-3 bg-white dark:bg-white/5 rounded-b-lg">
                                    <div
                                        class="relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10 mb-4">
                                        <label
                                            class="block text-xs text-black/40 dark:text-white/40 mb-1">{{ $label }}</label>
                                        <div class="flex items-center space-x-2">
                                            <input type="text" placeholder="{{ $label }}"
                                                class="form-input w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200/50 transition"
                                                name="legalitas_existing[{{ $key }}][nomor]"
                                                value="{{ $existing->nomor ?? '' }}" />

                                            @if ($existing && $existing->lampiran)
                                                <a href="{{ asset('storage/' . $existing->lampiran) }}" target="_blank"
                                                    class="text-blue-600 hover:underline text-xs whitespace-nowrap">
                                                    Download Lampiran
                                                </a>
                                            @endif
                                        </div>
                                        <input type="hidden" name="legalitas_existing[{{ $key }}][type]"
                                            value="{{ $key }}">
                                    </div>

                                    <div
                                        class="relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10 mb-4">
                                        <label class="block text-xs text-black/40 dark:text-white/40 mb-1">Upload
                                            Dokumen</label>
                                        <input type="file" name="legalitas_existing[{{ $key }}][lampiran]"
                                            class="form-input w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200/50 transition" />
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        {{-- 2. Legalitas Lainnya --}}
                        @if ($legalitasLain->count())
                            @foreach ($legalitasLain as $idx => $item)
                                <div class="border border-black/10 dark:border-white/10 rounded-lg mb-2">

                                    <div
                                        class="w-full flex justify-between items-center px-4 py-3 bg-gray-100 dark:bg-white/10 rounded-t-lg">
                                        <div class="flex items-center space-x-2">
                                            <!-- Tombol hapus di kiri -->
                                            <a href="{{ route('perusahaan.hapus.legalitas', $item->id) }}" type="button"
                                                class="text-red-600 hover:text-red-800 text-xs font-semibold"
                                                title="Hapus Legalitas" onclick="event.stopPropagation()">
                                                <!-- supaya tidak toggle accordion -->
                                                <i class="fas fa-trash"></i>
                                            </a>

                                            <!-- Judul legalitas (klik buat toggle accordion) -->
                                            <button type="button"
                                                class="font-semibold text-sm bg-transparent focus:outline-none"
                                                onclick="toggleAccordion('accordion-lain-{{ $idx }}')">
                                                {{ $item->legalitas }}
                                            </button>
                                        </div>

                                        <!-- Ikon toggle di kanan -->
                                        <button type="button" class="bg-transparent focus:outline-none"
                                            onclick="toggleAccordion('accordion-lain-{{ $idx }}')">
                                            <svg class="w-4 h-4 transition-transform rotate-180" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                    </div>

                                    <div id="accordion-lain-{{ $idx }}"
                                        class="hidden  px-4 py-3 bg-white dark:bg-white/5 rounded-b-lg">
                                        <div
                                            class="relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10 mb-4">
                                            <label class="block text-xs text-black/40 dark:text-white/40 mb-1">Nama
                                                Legalitas</label>
                                            <input type="text"
                                                name="legalitas_existing_other[{{ $idx }}][type]"
                                                value="{{ $item->legalitas }}"
                                                class="form-input w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200/50 transition"
                                                required />

                                            <label
                                                class="block text-xs text-black/40 dark:text-white/40 mb-1 mt-3">Nomor</label>
                                            <input type="text"
                                                name="legalitas_existing_other[{{ $idx }}][nomor]"
                                                value="{{ $item->nomor }}"
                                                class="form-input w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200/50 transition"
                                                required />

                                            <div class="flex items-center gap-2">
                                                @if ($item->lampiran)
                                                    <a href="{{ asset('storage/' . $item->lampiran) }}" target="_blank"
                                                        class="text-blue-600 hover:underline text-xs">Download Lampiran</a>
                                                @endif

                                            </div>
                                        </div>

                                        <div
                                            class="relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10 mb-4">
                                            <label class="block text-xs text-black/40 dark:text-white/40 mb-1">Upload
                                                Dokumen</label>
                                            <input type="file"
                                                name="legalitas_existing_other[{{ $idx }}][lampiran]"
                                                class="form-input w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200/50 transition" />
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif


                    </div>
                    <div id="container-legalitas-baru" class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6"></div>

                    <div class="floating-buttons ">
                        <button type="button" onclick="addLegalitas()" title="Tambah Legalitas Baru" class="btn-icon">
                            <i class="fas fa-plus"></i>
                        </button>
                        <button type="submit" title="Simpan" class="btn-icon">
                            <i class="fas fa-save"></i>
                        </button>
                    </div>

                    <script>
                        let legalitasCount = 0;

                        function addLegalitas() {
                            legalitasCount++;
                            const containerBaru = document.getElementById('container-legalitas-baru');

                            const div = document.createElement('div');
                            div.className = 'border border-black/10 dark:border-white/10 rounded-lg mb-2';

                            div.innerHTML = `
                            <button type="button"
                                class="w-full flex justify-between items-center px-4 py-3 bg-gray-100 dark:bg-white/10 rounded-t-lg focus:outline-none"
                                onclick="toggleAccordion('accordion-baru-${legalitasCount}')">
                                <span class="font-semibold text-sm">Legalitas Baru #${legalitasCount}</span>
                                <svg class="w-4 h-4 transition-transform rotate-180" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div id="accordion-baru-${legalitasCount}" class="px-4 py-3 bg-white dark:bg-white/5 rounded-b-lg">
                                <div class="relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10 mb-4">
                                    <label class="block text-xs text-black/40 dark:text-white/40 mb-1">Nama Legalitas</label>
                                    <input type="text" name="legalitas_new[${legalitasCount}][type]" placeholder="Contoh: Surat Izin Lingkungan"
                                        class="form-input w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200/50 transition" required />
                                </div>
                                <div class="relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10 mb-4">
                                    <label class="block text-xs text-black/40 dark:text-white/40 mb-1">Nomor</label>
                                    <input type="text" name="legalitas_new[${legalitasCount}][nomor]" placeholder="Nomor legalitas"
                                        class="form-input w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200/50 transition" required />
                                </div>
                                <div class="relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10">
                                    <label class="block text-xs text-black/40 dark:text-white/40 mb-1">Upload Dokumen</label>
                                    <input type="file" name="legalitas_new[${legalitasCount}][lampiran]"
                                        class="form-input w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200/50 transition" />
                                </div>
                            </div>
                        `;

                            containerBaru.appendChild(div);
                        }

                        // Fungsi toggle accordion sederhana
                        function toggleAccordion(id) {
                            const el = document.getElementById(id);
                            if (!el) return;

                            el.classList.toggle('hidden');
                            // Bisa tambahkan toggle rotate icon juga jika mau
                        }
                    </script>


                </form>
            </div>
            <div id="tab-stamp" class="tab-content hidden">
                <form action="{{ route('perusahaan.update.stamp') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <h2 class="text-lg font-semibold mb-4">Stemple dan Cap</h2>
                    <input type="hidden" name="id_perusahaan" value="{{ $perusahaan->id }}">
                    <div
                        class="mb-3 border bg-lightwhite dark:bg-white/5 dark:border-white/10 border-black/10 p-5 rounded-md">

                        <div class="flex flex-col mb-3">
                            <label class="block text-sm font-semibold mb-1">Stample (PNG)</label>
                            @if ($perusahaan->stamp)
                                <div class="mb-2">
                                    <img src="{{ optional($perusahaan)->stamp ? asset('storage/' . $perusahaan->stamp) : asset('assets/stamp-default.png') }}"
                                        alt="Stamp" class="w-[80px] h-[80px] object-contain border rounded"
                                        width="100">
                                </div>
                            @endif
                            <input type="file" name="stempel" accept="image/png"
                                class="block rounded-lg px-3 py-2 form-input text-black dark:text-white bg-white dark:bg-white/5 border border-black/10 dark:border-white/10 appearance-none focus:outline-none focus:ring-0 focus:border-black/10 dark:focus:border-black/10">
                            <p class="mt-2 text-xs text-black/40 dark:text-white/40 mb-3">Diizinkan File Berformat
                                PNG</p>
                        </div>

                        <div class="flex flex-col mb-3">
                            <label class="block text-sm font-semibold mb-1">Tanda Tangan</label>
                            @if ($perusahaan->ttd)
                                <div class="mb-2">
                                    <img src="{{ optional($perusahaan)->ttd ? asset('storage/' . $perusahaan->ttd) : asset('assets/ttd-default.png') }}"
                                        alt="Stamp" class="w-[80px] h-[80px] object-contain border rounded"
                                        width="200">

                                </div>
                            @endif
                            <input type="file" name="ttd_file" accept="image/png"
                                class="block rounded-lg px-3 py-2 w-full text-black dark:text-white bg-white dark:bg-white/5 border border-black/10 dark:border-white/10 appearance-none focus:outline-none focus:ring-0 focus:border-black/10 dark:focus:border-black/10">
                            <p class="mt-2 text-xs text-black/40 dark:text-white/40 mb-3">Diizinkan File Berformat
                                PNG</p>
                        </div>

                        <style>
                            #signature-pad {
                                border: 2px solid #333;
                                background: #fff;
                                border-radius: 8px;
                                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                                cursor: crosshair;
                            }

                            #signature-container {
                                display: inline-block;
                                border: 2px solid #333;
                                border-radius: 8px;
                                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                                overflow: hidden;
                            }

                            canvas {
                                background: transparent;
                                display: block;
                                width: auto;
                               
                                /* tampilan layar */
                                height: 400px;
                                cursor: crosshair;
                            }
                        </style>
                        <h2>Atau Silakan Tanda Tangan di Bawah Ini</h2>
                        <canvas id="signature-pad" ></canvas>
                        <br>
                        <label for="lineWidth">Ketebalan Garis:</label>
                        <input type="range" id="lineWidth" min="10" max="50" value="2">
                        <span id="lineWidthValue">10</span> px
                        <button type="button" class="btn" onclick="clearSignature()">Clear</button>
                        <button type="button" class="btn" onclick="saveSignature()">Ok</button>
                        <input type="text" name="ttd_base64" id="ttd_base64" hidden><br>
                        <small class="mt-2 text-xs text-black/40 dark:text-white/40 mb-3">Klik tombol Ok jika Tanda Tangan
                            sudah di draw atau di gambar, kemudian klik tombol Simpan</small>
                    </div>
                    
                    <div x-data="{ showModal: false }"
                        class="mb-3 flex items-center rounded bg-indigo-300/50 dark:bg-indigo-300 p-3 text-black/80 dark:text-black space-x-3">
                        <svg class="w-5 h-5" width="32" height="32" viewBox="0 0 32 32" fill="none"
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

                        <span class="pr-2 flex-1">Tempelate Keterangan Pembayaran</span>

                        <button @click="showModal = true" type="button"
                            class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            Show
                        </button>

                        <!-- Modal / Lightbox -->
                        <div x-show="showModal" style="background: rgba(0,0,0,0.6);z-index:2147483647;width:100%;"
                            class="fixed inset-0 flex items-center justify-center " @click.away="showModal = false"
                            @keydown.escape.window="showModal = false">
                            <div class="relative bg-white dark:bg-gray-800 rounded shadow-lg max-w-3xl max-h-[90vh] p-4">
                                <button @click="showModal = false" type="button"
                                    class="absolute top-2 right-2 text-gray-600 hover:text-gray-900 dark:hover:text-white text-xl font-bold"
                                    aria-label="Close modal">&times;</button>

                                <img src="{{ asset('assets/tempelate/keterangan-pembayaran.png') }}"
                                    alt="Keterangan Pembayaran" class="max-w-full max-h-[80vh] object-contain rounded" />
                            </div>
                        </div>
                    </div>

                    <div class="relative">
                        <textarea id="description" name="keterangan_pembayaran" cols="10" rows="10"
                            class="block rounded-lg px-5 pb-4 pt-[38px] w-full text-black dark:text-white bg-white dark:bg-white/5 border border-black/10 dark:border-white/10 appearance-none focus:outline-none focus:ring-0 focus:border-black/10 dark:focus:border-black/10 peer"
                            placeholder="">{{ $perusahaan->keterangan_pembayaran ?? 'Pembayaran dilakukan melalui transfer ke no. Rekeneing xxxxxxxx atas nama xxxxxxxxx setelah diterima informasi penjualan.'}}</textarea>

                        <label for="description"
                            class="absolute text-sm text-black/40 dark:text-white/40 duration-300 transform -translate-y-2 scale-90 top-6 z-10 origin-[0] left-5 peer-focus:text-black/40 dark:peer-focus:text-white/40 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-90 peer-focus:-translate-y-2">
                            Tempelate Pembayaran
                        </label>
                    </div>
                    <div class="floating-buttons ">
                        <button type="submit" title="Simpan" class="btn-icon">
                            <i class="fas fa-save"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
   

     <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Cek apakah URL cocok dengan pola `/people/update/*`
            if (window.location.pathname.startsWith("/setelan")) {
                // Pilih semua elemen yang mengandung class `sm:p-7`
                const elements = document.querySelectorAll(".sm\\:p-7");
                const elements2 = document.querySelectorAll(".p-7");

                // Hapus class `sm:p-7` dari elemen tersebut
                elements.forEach(el => el.classList.remove("sm:p-7"));
                elements2.forEach(el => el.classList.remove("p-7"));
            }
        });
    </script>
    <script>
        function addLegalitas() {
            legalitasCount++;
            const containerBaru = document.getElementById('container-legalitas-baru');

            const div = document.createElement('div');
            div.className = 'border border-black/10 dark:border-white/10 rounded-lg mb-2 relative';
            div.id = `legalitas-baru-${legalitasCount}`;

            div.innerHTML = `
        <button type="button"
            class="w-full flex justify-between items-center px-4 py-3 bg-gray-100 dark:bg-white/10 rounded-t-lg focus:outline-none"
            onclick="toggleAccordion('accordion-baru-${legalitasCount}')">
            <span class="font-semibold text-sm">Legalitas Baru #${legalitasCount}</span>
            
        </button>
        <button type="button" onclick="hapusLegalitasBaru(${legalitasCount})" 
            class="absolute top-2 right-2 text-red-600 hover:text-red-800" title="Hapus Legalitas Baru">
            <i class="fas fa-trash"></i>
        </button>
        <div id="accordion-baru-${legalitasCount}" class="px-4 py-3 bg-white dark:bg-white/5 rounded-b-lg">
            <div class="relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10 mb-4">
                <label class="block text-xs text-black/40 dark:text-white/40 mb-1">Nama Legalitas</label>
                <input type="text" name="legalitas_new[${legalitasCount}][type]" placeholder="Contoh: Surat Izin Lingkungan"
                    class="form-input w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200/50 transition" required />
            </div>
            <div class="relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10 mb-4">
                <label class="block text-xs text-black/40 dark:text-white/40 mb-1">Nomor</label>
                <input type="text" name="legalitas_new[${legalitasCount}][nomor]" placeholder="Nomor legalitas"
                    class="form-input w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200/50 transition" required />
            </div>
            <div class="relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10">
                <label class="block text-xs text-black/40 dark:text-white/40 mb-1">Upload Dokumen</label>
                <input type="file" name="legalitas_new[${legalitasCount}][lampiran]"
                    class="form-input w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200/50 transition" />
            </div>
        </div>
    `;

            containerBaru.appendChild(div);
        }

        function hapusLegalitasBaru(id) {
            const el = document.getElementById(`legalitas-baru-${id}`);
            if (el) el.remove();
        }
    </script>

    <script>
        function openTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(function(tab) {
                tab.classList.add('hidden');
            });

            document.querySelectorAll('[id^="btn-tab-"]').forEach(btn => {
                btn.classList.remove('bg-gray-200', 'dark:bg-gray-800');
            });

            document.getElementById(tabId).classList.remove('hidden');
            document.getElementById('btn-' + tabId).classList.add('bg-gray-200', 'dark:bg-gray-800');
        }

        // Open first tab by default
        document.addEventListener("DOMContentLoaded", () => {
            openTab('tab-profil');
        });
    </script>
    <script>
        async function uploadLogo(input) {
            const file = input.files[0];
            if (!file) return;

            // File validation
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            const maxSize = 2 * 1024 * 1024; // 2MB

            if (!validTypes.includes(file.type)) {
                showAlert('error', 'Hanya file JPG, JPEG, atau PNG yang diizinkan');
                resetFileInput(input);
                return;
            }

            if (file.size > maxSize) {
                showAlert('error', 'Ukuran file maksimal 2MB');
                resetFileInput(input);
                return;
            }

            const preview = document.getElementById('logo-preview');
            const loading = document.getElementById('logo-loading');

            // Show loading
            loading.classList.remove('hidden');

            // Preview image
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);

            try {
                // Prepare form data
                const formData = new FormData();
                formData.append('logo', file);
                formData.append('id', '{{ $perusahaan->id }}');
                formData.append('_token', '{{ csrf_token() }}');

                // Upload with progress tracking (optional)
                const xhr = new XMLHttpRequest();

                // Progress event
                xhr.upload.addEventListener('progress', (event) => {
                    if (event.lengthComputable) {
                        const percent = Math.round((event.loaded / event.total) * 100);
                        console.log(`Upload progress: ${percent}%`);
                        // You can update a progress bar here if needed
                    }
                });

                // Using fetch API instead (modern approach)
                const response = await fetch('/perusahaan/upload-logo', {
                    method: 'POST',
                    body: formData,
                    // headers are automatically set by FormData
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                loading.classList.add('hidden');

                if (data.success) {
                    showAlert('success', 'Logo berhasil diupload!');
                    // Update preview with server-side processed image
                    if (data.path) {
                        preview.src = data.path;
                    }
                } else {
                    showAlert('error', data.message || 'Gagal mengupload logo');
                    resetFileInput(input);
                }

            } catch (error) {
                loading.classList.add('hidden');
                console.error('Upload error:', error);
                showAlert('error', 'Terjadi kesalahan saat upload: ' + error.message);
                resetFileInput(input);
            }
        }

        // Helper functions
        function showAlert(type, message) {
            // Replace with your preferred notification system
            const alertBox = document.createElement('div');
            alertBox.className = `fixed top-4 right-4 p-4 rounded-md shadow-lg ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    } text-white`;
            alertBox.textContent = message;
            document.body.appendChild(alertBox);

            setTimeout(() => {
                alertBox.remove();
            }, 5000);
        }

        function resetFileInput(input) {
            input.value = '';
            // Optional: Reset preview to original image
            const preview = document.getElementById('logo-preview');
            preview.src =
                '{{ $perusahaan->logo ? asset('storage/' . $perusahaan->logo) : asset('assets/images/inopak.jpg') }}';
        }
    </script>

    <style>
        .animate-spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                width: '100%',
                placeholder: 'Pilih data...', // Ganti sesuai konteks misalnya 'Pilih Provinsi'

            });
        });
    </script>
    <script>
        const canvas = document.getElementById('signature-pad');
        const ctx = canvas.getContext('2d');

        // Buat canvas 2x resolusi untuk HD
        const width = 2000;
        const height = 2000;
        canvas.width = width;
        canvas.height = height;

        // ✅ LOAD IMAGE DARI DATABASE KE CANVAS (jika ada)
        document.addEventListener('DOMContentLoaded', () => {
            @if ($perusahaan->ttd)
                const image = new Image();
                image.onload = function() {
                    ctx.drawImage(image, 0, 0, canvas.width, canvas.height);
                };
                image.src = "{{ asset('storage/' . $perusahaan->ttd) }}";
            @endif
        });

        let isDrawing = false;

        const lineWidthInput = document.getElementById('lineWidth');
        const lineWidthValue = document.getElementById('lineWidthValue');

        // Update tampilan angka saat slider digeser
        lineWidthInput.addEventListener('input', () => {
            lineWidthValue.textContent = lineWidthInput.value;
        });

        canvas.addEventListener('mousedown', (e) => {
            isDrawing = true;
            const scaleX = canvas.width / canvas.offsetWidth;
            const scaleY = canvas.height / canvas.offsetHeight;
            ctx.beginPath();
            ctx.moveTo(e.offsetX * scaleX, e.offsetY * scaleY);
        });

        canvas.addEventListener('mousemove', (e) => {
            if (isDrawing) {
                const scaleX = canvas.width / canvas.offsetWidth;
                const scaleY = canvas.height / canvas.offsetHeight;
                ctx.lineTo(e.offsetX * scaleX, e.offsetY * scaleY);
                ctx.strokeStyle = '#000';
                ctx.lineWidth = lineWidthInput.value;
                ctx.lineCap = 'round';
                ctx.lineJoin = 'round';
                ctx.stroke();
            }
        });

        canvas.addEventListener('mouseup', () => isDrawing = false);
        canvas.addEventListener('mouseleave', () => isDrawing = false);

        function clearSignature() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }

        function saveSignature() {
            const imageData = canvas.toDataURL('image/png');
            document.getElementById('ttd_base64').value = imageData;
            alert('Berhasil disetel');
        }

        /* =====================================================
           ✅ Tambahan untuk support mobile (touch events) + scaling
        ===================================================== */
        function getTouchPos(e) {
            const rect = canvas.getBoundingClientRect();
            const scaleX = canvas.width / rect.width;
            const scaleY = canvas.height / rect.height;
            return {
                x: (e.touches[0].clientX - rect.left) * scaleX,
                y: (e.touches[0].clientY - rect.top) * scaleY
            };
        }

        canvas.addEventListener('touchstart', (e) => {
            isDrawing = true;
            const pos = getTouchPos(e);
            ctx.beginPath();
            ctx.moveTo(pos.x, pos.y);
            e.preventDefault(); // biar layar nggak geser waktu tanda tangan
        });

        canvas.addEventListener('touchmove', (e) => {
            if (isDrawing) {
                const pos = getTouchPos(e);
                ctx.lineTo(pos.x, pos.y);
                ctx.strokeStyle = '#000';
                ctx.lineWidth = lineWidthInput.value;
                ctx.lineCap = 'round';
                ctx.lineJoin = 'round';
                ctx.stroke();
            }
            e.preventDefault();
        });

        canvas.addEventListener('touchend', () => isDrawing = false);
</script>



    <script>
        function saveSignature2() {
            const image = canvas.toDataURL('image/png');
            const link = document.createElement('a');
            link.download = 'signature_hd.png';
            link.href = image;
            link.click();

        }

        function clearSignature2() {
            const canvas = document.getElementById('signature-pad');
            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }
    </script>
    <script>
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Saat Provinsi dipilih
            $('#provinsi').on('change', function() {
                let id_provinsi = $(this).val();

                // Tampilkan loading
                $('#kabupaten').html('<option>Loading data kabupaten...</option>');
                $('#kecamatan').html('<option value="">Pilih Kecamatan</option>');
                $('#desa').html('<option value="">Pilih Desa</option>');

                $.ajax({
                    type: 'POST',
                    url: "{{ route('getkabupaten') }}",
                    data: {
                        id_provinsi: id_provinsi
                    },
                    cache: false,

                    success: function(msg) {
                        $('#kabupaten').html(msg).prop('disabled', false);
                    },
                    error: function(data) {
                        console.log('error:', data)
                    },
                });
            });

            // Saat Kabupaten dipilih
            $('#kabupaten').on('change', function() {
                let id_kabupaten = $(this).val();

                $('#kecamatan').html('<option>Loading data kecamatan...</option>');
                $('#desa').html('<option value="">Pilih Desa</option>');

                $.ajax({
                    type: 'POST',
                    url: "{{ route('getkecamatan') }}",
                    data: {
                        id_kabupaten: id_kabupaten
                    },
                    cache: false,

                    success: function(msg) {
                        $('#kecamatan').html(msg).prop('disabled', false);
                    },
                    error: function(data) {
                        console.log('error:', data)
                    },
                });
            });

            // Saat Kecamatan dipilih
            $('#kecamatan').on('change', function() {
                let id_kecamatan = $(this).val();

                $('#desa').html('<option>Loading data desa...</option>');

                $.ajax({
                    type: 'POST',
                    url: "{{ route('getdesa') }}",
                    data: {
                        id_kecamatan: id_kecamatan
                    },
                    cache: false,

                    success: function(msg) {
                        $('#desa').html(msg).prop('disabled', false);
                    },
                    error: function(data) {
                        console.log('error:', data)
                    },
                });
            });
        });
    </script>
    <script>
        function autoResizeTextarea(id) {
            const textarea = document.getElementById(id);
            if (!textarea) return;

            textarea.style.height = 'auto'; // reset dulu
            textarea.style.height = (textarea.scrollHeight) + 'px';
        }

        // Pas load pertama
        window.addEventListener('load', () => {
            autoResizeTextarea('description');
        });

        // Saat diketik
        document.getElementById('description').addEventListener('input', function() {
            autoResizeTextarea('description');
        });
    </script>
@endsection
