@extends('layout.main')
@section('title', 'Detail Pengguna')
<!-- Add these links in the <head> section of your HTML -->
@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    
@endsection
@section('container')
    <style>
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
    <style>
        @media (max-width: 768px) {
            .mobile {
                display: none;
            }
        }

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
                margin-bottom: 50px;
            }
           .no-scrollbar{
                max-width: 500px;
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
       .scrolling-wrapper {
        overflow-x: scroll;
        overflow-y: hidden;
        white-space: nowrap;

            
        }
         .no-scrollbar::-webkit-scrollbar {
                display: none;
            }
         @media (max-width: 768px) {
            body {
                zoom: 0.9; /* Zoom-out untuk layar kecil */
            }

            table {
                font-size: 14px; /* Mengurangi ukuran font untuk tampilan kecil */
            }

            .search-icon {
                width: 24px;
                height: 24px;
            }

            /* Menyesuaikan ukuran tabel agar lebih baik di mobile */
            th, td {
                padding: 5px;
            }
        }

        /* Lebih responsif jika ukuran lebih kecil dari 500px */
        @media (max-width: 500px) {
            body {
                zoom: 0.7; /* Lebih kecil lagi di perangkat lebih kecil */
            }

            table {
                font-size: 12px; /* Ukuran font semakin kecil */
            }
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Cek apakah URL cocok dengan pola `/people/update/*`
            if (window.location.pathname.startsWith("/people/update/")) {
                // Pilih semua elemen yang mengandung class `sm:p-7`
                const elements = document.querySelectorAll(".sm\\:p-5");
                const elements2 = document.querySelectorAll(".p-7");

                // Hapus class `sm:p-7` dari elemen tersebut
                elements.forEach(el => el.classList.remove("sm:p-5"));
                elements2.forEach(el => el.classList.remove("p-7"));
            }
        });
    </script>
     @if (auth()->user()->role == 'admin' || auth()->user()->role == 'superadmin')
    <!-- Wrapper scrollable -->
    <div class="w-full overflow-x-auto no-scrollbar  bg-white dark:text-black dark:bg-black" >
        <div class="flex space-x-2 px-2  mt-1 min-w-max scrolling-wrapper no-scrollbar mb-2" >
           
            <button class="tab-button px-4 py-2  whitespace-nowrap rounded-md  border-gray-300 bg-gray-100 text-sm dark:bg-white/5 dark:border-black/10 dark:text-white  hover:bg-gray-200 transition" onclick="changeTab(0)">
                Detail IKM
            </button>
            <button class="tab-button px-4 py-2  whitespace-nowrap rounded-md border-gray-300 bg-gray-100 text-sm  dark:bg-white/5 dark:border-black/10 dark:text-white hover:bg-gray-200 transition" onclick="changeTab(1)">
                Data Mitra
            </button>
            <button class="tab-button px-4 py-2  whitespace-nowrap rounded-md border-gray-300 bg-gray-100 text-sm  dark:bg-white/5 dark:border-black/10 dark:text-white hover:bg-gray-200 transition" onclick="changeTab(2)">
                Data Produk
            </button>
            <button class="tab-button px-4 py-2  whitespace-nowrap rounded-md border-gray-300 bg-gray-100 text-sm  dark:bg-white/5 dark:border-black/10 dark:text-white hover:bg-gray-200 transition" onclick="changeTab(3)">
                Riwayat Transaksi
            </button>
            <button class="tab-button px-4 py-2  whitespace-nowrap rounded-md border-gray-300 bg-gray-100 text-sm  dark:bg-white/5 dark:border-black/10 dark:text-white hover:bg-gray-200 transition" onclick="changeTab(4)">
                Laporan Keuangan
            </button>
            <button class="tab-button px-4 py-2  whitespace-nowrap rounded-md border-gray-300 bg-gray-100 text-sm  dark:bg-white/5 dark:border-black/10 dark:text-white hover:bg-gray-200 transition" onclick="changeTab(5)">
                Log Aktivitas
            </button>
           
        </div>
    </div>
    <script>
    function changeTab(index) {
        // Hapus semua tab aktif
        document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('active-tab');
        btn.classList.add('bg-gray-100');
        btn.classList.remove('bg-white');
        });

        // Tambahkan ke tab aktif
        const activeBtn = document.querySelectorAll('.tab-button')[index];
        activeBtn.classList.add('active-tab', 'bg-white');
        activeBtn.classList.remove('bg-gray-100');

        // Simpan index tab di localStorage agar tetap aktif setelah reload
        localStorage.setItem('activeTab', index);
    }

    // Saat halaman diload, aktifkan tab yang terakhir diklik
    document.addEventListener('DOMContentLoaded', () => {
        const savedTab = localStorage.getItem('activeTab');
        if (savedTab !== null) {
        changeTab(parseInt(savedTab));
        }
    });
    </script>
     @endif
    <div class="bg-lightwhite dark:bg-white/5 dark:border-black/10 p-6">
        <div class="flex items-start justify-between gap-2 ">
            <div>
                <h2 class="text-lg font-semibold mb-3">{{ $ikm->nama }}</h2>
                <div class="flex flex-wrap gap-4 items-center mb-4">
                    <div class="flex items-center gap-1 text-xs text-black/40 dark:text-white/40">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M11.8465 2.26238C10.4873 1.6875 9 1.6875 9 1.6875C7.51265 1.6875 6.15347 2.26238 6.15347 2.26238C4.84109 2.81747 3.82928 3.82928 3.82928 3.82928C2.81748 4.84109 2.26238 6.15347 2.26238 6.15347C1.6875 7.51265 1.6875 9 1.6875 9C1.6875 10.4873 2.26238 11.8465 2.26238 11.8465C2.81747 13.1589 3.82928 14.1707 3.82928 14.1707C3.90704 14.2485 3.98657 14.3235 4.06715 14.3959C4.09662 14.4287 4.1301 14.4583 4.16709 14.4837C5.11036 15.2964 6.15347 15.7376 6.15347 15.7376C7.51265 16.3125 9 16.3125 9 16.3125C10.4873 16.3125 11.8465 15.7376 11.8465 15.7376C12.6786 15.3857 13.3899 14.8501 13.799 14.5053C13.8585 14.4704 13.9102 14.4253 13.9523 14.373C14.0928 14.2486 14.1707 14.1707 14.1707 14.1707C15.1825 13.1589 15.7376 11.8465 15.7376 11.8465C16.3125 10.4873 16.3125 9 16.3125 9C16.3125 7.51265 15.7376 6.15347 15.7376 6.15347C15.1825 4.84109 14.1707 3.82928 14.1707 3.82928C13.1589 2.81747 11.8465 2.26238 11.8465 2.26238ZM6.59172 14.7015C6.04988 14.4723 5.56846 14.151 5.21752 13.882C5.81067 12.9896 6.64596 12.4769 6.64596 12.4769C7.7291 11.8121 9 11.8125 9 11.8125C10.2709 11.8125 11.354 12.4769 11.354 12.4769C12.036 12.8955 12.5166 13.4997 12.7791 13.8899C12.0784 14.418 11.4083 14.7015 11.4083 14.7015C10.2592 15.1875 9 15.1875 9 15.1875C7.74079 15.1875 6.59172 14.7015 6.59172 14.7015ZM6.05746 11.5181C6.05746 11.5181 6.39649 11.3101 6.93432 11.1023C6.82429 11.0195 6.71668 10.9271 6.61351 10.824C6.61351 10.824 5.625 9.83547 5.625 8.4375C5.625 8.4375 5.625 7.03953 6.61351 6.05101C6.61351 6.05101 7.60203 5.0625 9 5.0625C9 5.0625 10.398 5.0625 11.3865 6.05101C11.3865 6.05101 12.375 7.03953 12.375 8.4375C12.375 8.4375 12.375 9.83547 11.3865 10.824C11.3865 10.824 11.2708 10.9397 11.0625 11.092C11.3547 11.2016 11.654 11.341 11.9425 11.5181C11.9425 11.5181 12.8853 12.0968 13.6153 13.1114C13.9039 12.7751 14.3886 12.148 14.7015 11.4083C14.7015 11.4083 15.1875 10.2592 15.1875 9C15.1875 9 15.1875 7.74079 14.7015 6.59172C14.7015 6.59172 14.2319 5.48143 13.3752 4.62478C13.3752 4.62478 12.5186 3.76813 11.4083 3.29851C11.4083 3.29851 10.2592 2.8125 9 2.8125C9 2.8125 7.74078 2.8125 6.59172 3.29851C6.59172 3.29851 5.48143 3.76813 4.62478 4.62478C4.62478 4.62478 3.76813 5.48143 3.29851 6.59172C3.29851 6.59172 2.8125 7.74078 2.8125 9C2.8125 9 2.8125 10.2592 3.29851 11.4083C3.29851 11.4083 3.68218 12.3154 4.38853 13.1224C4.73326 12.6405 5.2946 11.9864 6.05746 11.5181ZM10.591 10.0285C9.93198 10.6875 9 10.6875 9 10.6875C8.06802 10.6875 7.40901 10.0285 7.40901 10.0285C6.75 9.36948 6.75 8.4375 6.75 8.4375C6.75 7.50552 7.40901 6.84651 7.40901 6.84651C8.06802 6.1875 9 6.1875 9 6.1875C9.93198 6.1875 10.591 6.84651 10.591 6.84651C11.25 7.50552 11.25 8.4375 11.25 8.4375C11.25 9.36948 10.591 10.0285 10.591 10.0285Z"
                                fill="currentcolor"></path>
                        </svg>
                        <p>{{ $ikm->jenis_kelamin == 'L'
                            ? 'Laki - Laki'
                            : ($ikm->jenis_kelamin == 'P'
                                ? 'Perempuan'
                                : 'belum
                                                                                                                                                                                                                        disetel') }}
                        </p>
                    </div>
                    <div class="flex items-center gap-1 text-xs text-black/40 dark:text-white/40 ">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M9 4.5C9 4.5 10.165 4.5 10.9887 5.32376C10.9887 5.32376 11.8125 6.14752 11.8125 7.3125C11.8125 7.3125 11.8125 8.47748 10.9887 9.30124C10.9887 9.30124 10.165 10.125 9 10.125C9 10.125 7.83502 10.125 7.01126 9.30124C7.01126 9.30124 6.1875 8.47748 6.1875 7.3125C6.1875 7.3125 6.1875 6.14752 7.01126 5.32376C7.01126 5.32376 7.83502 4.5 9 4.5ZM9 5.625C9 5.625 8.30101 5.625 7.80676 6.11926C7.80676 6.11926 7.3125 6.61351 7.3125 7.3125C7.3125 7.3125 7.3125 8.01149 7.80676 8.50574C7.80676 8.50574 8.30101 9 9 9C9 9 9.69899 9 10.1932 8.50574C10.1932 8.50574 10.6875 8.01149 10.6875 7.3125C10.6875 7.3125 10.6875 6.61351 10.1932 6.11926C10.1932 6.11926 9.69899 5.625 9 5.625Z"
                                fill="currentcolor"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M14.7165 4.94465C14.7165 4.94465 15.1875 6.08173 15.1875 7.3125C15.1875 7.3125 15.1875 10.6869 12.237 14.08C12.237 14.08 11.3302 15.1228 10.2432 16.0468C10.2432 16.0468 9.69576 16.5121 9.32257 16.7733C9.12889 16.9089 8.87111 16.9089 8.67743 16.7733C8.67743 16.7733 8.30424 16.5121 7.75679 16.0468C7.75679 16.0468 6.66978 15.1228 5.76303 14.08C5.76303 14.08 2.8125 10.6869 2.8125 7.3125C2.8125 7.3125 2.8125 6.08173 3.2835 4.94465C3.2835 4.94465 3.75449 3.80756 4.62478 2.93728C4.62478 2.93728 5.49506 2.06699 6.63215 1.596C6.63215 1.596 7.76923 1.125 9 1.125C9 1.125 10.2308 1.125 11.3679 1.596C11.3679 1.596 12.5049 2.06699 13.3752 2.93728C13.3752 2.93728 14.2455 3.80756 14.7165 4.94465ZM14.0625 7.3125C14.0625 7.3125 14.0625 6.30551 13.6771 5.37516C13.6771 5.37516 13.2918 4.44483 12.5797 3.73277C12.5797 3.73277 11.8677 3.02072 10.9373 2.63536C10.9373 2.63536 10.007 2.25 9 2.25C9 2.25 7.99301 2.25 7.06266 2.63536C7.06266 2.63536 6.13232 3.02072 5.42027 3.73277C5.42027 3.73277 4.70822 4.44482 4.32286 5.37516C4.32286 5.37516 3.9375 6.30551 3.9375 7.3125C3.9375 7.3125 3.9375 10.2662 6.61197 13.3418C6.61197 13.3418 7.46303 14.3206 8.4854 15.1896C8.4854 15.1896 8.77076 15.4321 9 15.6113C9 15.6113 9.22924 15.4321 9.5146 15.1896C9.5146 15.1896 10.537 14.3206 11.388 13.3418C11.388 13.3418 14.0625 10.2662 14.0625 7.3125Z"
                                fill="currentcolor"></path>
                        </svg>
                        <p>
                            {{ $ikm->kota->name ?? 'Kota Tidak ditemukan' }}
                        </p>
                    </div>
                    <div class="flex items-center gap-1 text-xs text-black/40 dark:text-white/40">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M1.6875 13.5V3.9375C1.6875 3.62684 1.93934 3.375 2.25 3.375H15.75C16.0607 3.375 16.3125 3.62684 16.3125 3.9375V13.5C16.3125 13.5 16.3125 13.966 15.983 14.2955C15.983 14.2955 15.6535 14.625 15.1875 14.625H2.8125C2.8125 14.625 2.34651 14.625 2.01701 14.2955C2.01701 14.2955 1.6875 13.966 1.6875 13.5ZM2.8125 13.5H15.1875V4.5H2.8125V13.5Z"
                                fill="currentcolor"></path>
                            <path
                                d="M2.6301 3.52285C2.52635 3.42775 2.39073 3.375 2.25 3.375C2.24185 3.375 2.23371 3.37518 2.22557 3.37553C2.07652 3.38201 1.93616 3.44743 1.83535 3.5574C1.74025 3.66115 1.6875 3.79677 1.6875 3.9375C1.6875 3.94565 1.68768 3.95379 1.68803 3.96193C1.69451 4.11098 1.75993 4.25134 1.8699 4.35215L8.6199 10.5396C8.83496 10.7368 9.16504 10.7368 9.3801 10.5396L16.1297 4.35249C16.2459 4.24595 16.3125 4.09517 16.3125 3.9375L16.3125 3.93282C16.3113 3.79371 16.2587 3.65996 16.1646 3.5574C16.0638 3.44743 15.9235 3.38201 15.7744 3.37553C15.7663 3.37518 15.7581 3.375 15.75 3.375L15.7474 3.37501C15.6076 3.37565 15.473 3.42836 15.3699 3.52285L9 9.36193L2.6301 3.52285Z"
                                fill="currentcolor"></path>
                        </svg>
                        <p>{{ $ikm->email ?? 'Email Tidak ditemukan' }} </p>
                    </div>
                </div>
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 gap-8 md:gap-0 md:flex md:divide-x divide-black/10 dark:divide-white/10">
                    <div class="md:pr-7 shrink-0 w-full">
                        <p class="mb-1">Profile Completion</p>
                        <div class="w-full bg-black/5 dark:bg-white/5 rounded-lg overflow-hidden">
                            <div class="w-full bg-lightpurple-200 whitespace-nowrap text-center px-1.5 text-lg font-semibold text-black"
                                style="width: {{ $percentage }}%;">
                                {{ $percentage }}%
                            </div>
                        </div>
                        @if ($emptyFields > 0)
                            <p class="text-sm text-black/40 dark:text-white/40 mt-1">Masih ada {{ $emptyFields }} data yang belum diisi.</p>
                        @endif
                    </div>

                </div>
            </div>
            <!--image-->
            <div >
                <div x-data="modals">
                    <a @click="toggle">
                        <img class="flex-none rounded-full object-cover cursor-pointer"
                            src="{{ $ikm->foto ? asset('storage/' . $ikm->foto) : asset('assets/images/byewind-avatar.png') }}"
                            alt="Foto IKM" width="100">
                    </a>

                    {{-- MODAL --}}
                    <div class="fixed inset-0 bg-black/60 dark:bg-white/10 z-[999] hidden overflow-y-auto"
                        :class="open && '!block'"> {{-- FIXED: `& amp; & amp;` jadi `&&` --}}
                        <div class="flex items-center justify-center min-h-screen px-4" @click.self="open = false">
                            <div x-show="open" x-transition x-transition.duration.300ms
                                class="bg-white dark:bg-black relative shadow-3xl border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8">

                                {{-- HEADER MODAL --}}
                                <div
                                    class="flex bg-white dark:bg-black border-b border-black/10 dark:border-white/10 items-center justify-between px-5 py-3">
                                    <h5 class="font-semibold text-lg">Foto Profile</h5>
                                    <button type="button"
                                        class="text-black/40 dark:text-white/40 hover:text-black dark:hover:text-white"
                                        @click="toggle">
                                        {{-- ICON CLOSE --}}
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 32 32"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M24.3 6.3L6.3 24.3C6.1 24.5 6 24.7 6 25s.1.5.3.7c.2.2.5.3.7.3s.5-.1.7-.3L25.7 7.7c.2-.2.3-.5.3-.7s-.1-.5-.3-.7c-.2-.2-.5-.3-.7-.3s-.5.1-.7.3Z"
                                                fill="currentColor" />
                                            <path
                                                d="M7.7 6.3C7.5 6.1 7.2 6 7 6s-.5.1-.7.3C6.1 6.5 6 6.7 6 7s.1.5.3.7L24.3 25.7c.2.2.5.3.7.3s.5-.1.7-.3c.2-.2.3-.5.3-.7s-.1-.5-.3-.7L7.7 6.3Z"
                                                fill="currentColor" />
                                        </svg>
                                    </button>
                                </div>

                                {{-- BODY MODAL --}}
                                <div class="p-5 text-sm text-black dark:text-white">
                                    <center>
                                        <img id="preview"
                                            src="{{ $ikm->foto ? asset('storage/' . $ikm->foto) : asset('assets/images/byewind-avatar.png') }}"
                                            class="py-4 rounded-md object-cover" alt="avatar" width="300">
                                    </center>

                                    <form id="studentForm" action="{{ route('ikm.update.foto') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $ikm->id }}">
                                        <input type="hidden" name="oldImage" value="{{ $ikm->foto }}">
                                        <input type="hidden" name="croppedFoto" id="croppedFoto">

                                        {{-- INPUT FILE --}}
                                        <div
                                            class="my-4 py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 dark:bg-white/5">
                                            <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Ubah
                                                Foto</label>
                                            <input type="file" id="image-input"  class="form-input">
                                        </div>

                                        {{-- LOADING INDICATOR (Opsional) --}}
                                        <div id="loading-indicator"
                                            style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.8); z-index: 9999; text-align: center; line-height: 100vh;">
                                            <span class="spinner-border text-primary"
                                                style="width: 3rem; height: 3rem;"></span>
                                            <p class="text-white mt-4">Uploading...</p>
                                        </div>

                                        {{-- BUTTONS --}}
                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-7 mt-2">
                                            <button type="button" id="crop-btn"
                                                class="w-full px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition"
                                                style="display: none;">
                                                Crop
                                            </button>
                                            <button type="submit" id="submit-btn"
                                                class="w-full px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition"
                                                disabled>
                                                Simpan Perubahan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                {{-- END BODY --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>


    <!-- Tab Content -->
    <div id="tab-content-0" class="tab-content p-4">
        <p class="text-sm font-semibold mb-3 text-black/40 dark:text-white/40">Detail Pengguna</p>
        <form action="{{ route('ikm.update.action') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white"></h2>
                <div class="floating-buttons flex flex-col items-center space-y-2">


                    <!-- Tombol Simpan -->
                    <button type="submit" title="Simpan" class="btn-icon">
                        <i class="fas fa-save"></i>
                    </button>

                    @if (auth()->user()->role == 'admin')
                        <!-- Tombol Hapus -->
                        <button type="button" title="Hapus" class="btn-icon"
                            onclick="confirmDelete('{{ route('ikm.delete', $id) }}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    @endif
                </div>

                {{-- <div class="flex flex-col sm:flex-row gap-3">
            <button type="submit"
                class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow transition duration-150">
                Simpan
            </button>

            <button type="button" onclick="confirmDelete('{{ route('ikm.delete', $id) }}')"
                class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg shadow transition duration-150">
                Hapus
            </button>
        </div> --}}

            </div>
            <script>
                function confirmDelete(url) {
                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        text: 'Tindakan ini tidak dapat dibatalkan!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal',
                        customClass: {
                            confirmButton: 'bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg mx-2 focus:outline-none',
                            cancelButton: 'bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg mx-2 focus:outline-none'
                        },
                        buttonsStyling: false // penting agar customClass dipakai
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = url;
                        }
                    });
                }
            </script>


            @if ($errors->any())
                <div class="bg-lightyellow/50 dark:bg-lightyellow border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <strong class="font-bold">Oops!</strong>
                    <span class="block sm:inline">Ada kesalahan pada input Anda:</span>
                    <ul class="mt-2 list-disc list-inside text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 2xl:grid-cols-2 gap-2">
                <input type="text" name="id" value="{{ $ikm->id }}" hidden>
                <div class="border bg-lightwhite dark:bg-white/5 dark:border-white/10 border-black/10 p-5 rounded-md">
                    <div class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                        <label class="block mb-1 text-xs text-black/40 dark:text-white/40">NIK</label>
                        <input type="text" name="nik" placeholder="NIK" class="form-input" maxlength="16"
                            value="{{ old('nik', $ikm->nik ?? '') }}" />
                    </div>

                    <div class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                        <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Nama</label>
                        <input type="text" name="nama" placeholder="Nama" class="form-input"
                            value="{{ old('nama', $ikm->nama ?? '') }}" />
                    </div>

                    <div class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                        <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" placeholder="Tempat Lahir" class="form-input"
                            value="{{ old('tempat_lahir', $ikm->tempat_lahir ?? '') }}" />
                    </div>

                    <div class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                        <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-input"
                            value="{{ old('tanggal_lahir', $ikm->tanggal_lahir ?? '') }}" />
                    </div>

                    <div class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                        <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-input" id="jenis_kelamin">
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="L"
                                {{ old('jenis_kelamin', $ikm->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki
                            </option>
                            <option value="P"
                                {{ old('jenis_kelamin', $ikm->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan
                            </option>
                        </select>
                    </div>

                    <!-- Alamat -->
                    <div class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                        <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Alamat</label>
                        <input type="text" name="alamat" placeholder="Alamat" class="form-input"
                            value="{{ old('alamat', $ikm->alamat ?? '') }}" />
                    </div>

                    <div class="flex gap-3 mb-3">
                        <div class="flex-1 py-4 px-5 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                            <label class="block mb-1 text-xs text-black/40 dark:text-white/40">RT</label>
                            <input type="text" name="rt" placeholder="RT" class="form-input"
                                value="{{ old('rt', $ikm->rt ?? '') }}" />
                        </div>
                        <div class="flex-1 py-4 px-5 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                            <label class="block mb-1 text-xs text-black/40 dark:text-white/40">RW</label>
                            <input type="text" name="rw" placeholder="RW" class="form-input"
                                value="{{ old('rw', $ikm->rw ?? '') }}" />
                        </div>
                    </div>
                    <div class="py-4 px-5 bg-white rounded-lg border border-black/10 relative dark:bg-white/5 mb-3">
                        <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Provinsi</label>
                      @php
                            $selectedProvinsi = old('id_provinsi', $ikm->id_provinsi);
                        @endphp

                        <select id="provinsi" name="id_provinsi" class="form-select w-full">
                            <option value="">Pilih Provinsi</option>
                            @foreach ($provinsi as $ikm2)
                                <option value="{{ $ikm2->id }}"
                                    {{ $ikm2->id == $selectedProvinsi ? 'selected' : '' }}>
                                    {{ $ikm2->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @php
                        $selectedKotaId = old('id_kota', $ikm->id_kota ?? '');
                        $selectedKecamatanId = old('id_kecamatan', $ikm->id_kecamatan ?? '');
                        $selectedDesaId = old('id_desa', $ikm->id_desa ?? '');
                    @endphp

                    <div class="py-4 px-5 bg-white rounded-lg border border-black/10 relative dark:bg-white/5 mb-3">
                        <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Kota / Kabupaten</label>
                        <select id="kabupaten" name="id_kota" class="form-select w-full">
                            @if ($selectedKotaId)
                                <option value="{{ $selectedKotaId }}" selected>{{ $ikm->kota->name ?? 'Terpilih' }}</option>
                            @else
                                <option value="">Pilih Kota</option>
                            @endif
                        </select>
                    </div>

                    <div class="py-4 px-5 bg-white rounded-lg border border-black/10 relative dark:bg-white/5 mb-3">
                        <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Kecamatan</label>
                        <select id="kecamatan" name="id_kecamatan" class="form-select w-full">
                            @if ($selectedKecamatanId)
                                <option value="{{ $selectedKecamatanId }}" selected>{{ $ikm->kecamatan->name ?? 'Terpilih' }}</option>
                            @else
                                <option value="">Pilih Kecamatan</option>
                            @endif
                        </select>
                    </div>

                    <div class="py-4 px-5 bg-white rounded-lg border border-black/10 relative dark:bg-white/5 mb-3">
                        <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Desa</label>
                        <select id="desa" name="id_desa" class="form-select w-full">
                            @if ($selectedDesaId)
                                <option value="{{ $selectedDesaId }}" selected>{{ $ikm->desa->name ?? 'Terpilih' }}</option>
                            @else
                                <option value="">Pilih Desa</option>
                            @endif
                        </select>
                    </div>


                </div>
                <div class="border bg-lightwhite dark:bg-white/5 dark:border-white/10 border-black/10 p-5 rounded-md">
                    <div class="py-4 px-5 bg-white rounded-lg border border-black/10 relative dark:bg-white/5 mb-3">
                        <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Agama</label>
                        @php
                            $selectedAgama = old('agama', $ikm->agama);
                        @endphp

                        <select id="agama" name="agama" class="form-select w-full">
                            <option value="islam" {{ $selectedAgama == 'islam' ? 'selected' : '' }}>Islam</option>
                            <option value="kristen" {{ $selectedAgama == 'kristen' ? 'selected' : '' }}>Kristen</option>
                            <option value="katolik" {{ $selectedAgama == 'katolik' ? 'selected' : '' }}>Katolik</option>
                            <option value="hindu" {{ $selectedAgama == 'hindu' ? 'selected' : '' }}>Hindu</option>
                            <option value="buddha" {{ $selectedAgama == 'buddha' ? 'selected' : '' }}>Buddha</option>
                            <option value="konghucu" {{ $selectedAgama == 'konghucu' ? 'selected' : '' }}>Konghucu</option>
                            <option value="kepercayaan" {{ $selectedAgama == 'kepercayaan' ? 'selected' : '' }}>
                                Kepercayaan (Agama Lokal)
                            </option>
                        </select>
                    </div>

                    @php
                        $selectedStatus = old('status_perkawinan', $ikm->status_perkawinan);
                    @endphp

                    <div class="py-4 px-5 bg-white rounded-lg border border-black/10 relative dark:bg-white/5 mb-3">
                        <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Status </label>
                        <select id="status_perkawinan" name="status_perkawinan" class="form-select w-full">
                            <option value="" {{ $selectedStatus == '' ? 'selected' : '' }}>status perkawinan</option>
                            <option value="belum_menikah" {{ $selectedStatus == 'belum_menikah' ? 'selected' : '' }}>Belum Menikah</option>
                            <option value="menikah" {{ $selectedStatus == 'menikah' ? 'selected' : '' }}>Menikah</option>
                            <option value="cerai_hidup" {{ $selectedStatus == 'cerai_hidup' ? 'selected' : '' }}>Cerai Hidup</option>
                            <option value="cerai_mati" {{ $selectedStatus == 'cerai_mati' ? 'selected' : '' }}>Cerai Mati</option>
                        </select>
                    </div>


                    <div class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                        <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Pekerjaan</label>
                        <input type="text" name="pekerjaan" placeholder="Pekerjaan" class="form-input"
                            value="{{ old('pekerjaan', $ikm->pekerjaan ?? '') }}" />
                    </div>

                    <div class="py-4 px-5 bg-white rounded-lg border border-black/10 relative dark:bg-white/5 mb-3">
                        <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Kewarganegaraan</label>
                        @php
                            $selectedKewarganegaraan = old('kewarganegaraan', $ikm->kewarganegaraan);
                        @endphp
                        <select id="kewarganegaraan" name="kewarganegaraan" class="form-select w-full">
                            <option value="" {{ $selectedKewarganegaraan == '' ? 'selected' : '' }}>Kewarganegaraan</option>
                            <option value="wni" {{ $selectedKewarganegaraan == 'wni' ? 'selected' : '' }}>
                                Warga Negara Indonesia (WNI)
                            </option>
                            <option value="wna" {{ $selectedKewarganegaraan == 'wna' ? 'selected' : '' }}>
                                Warga Negara Asing (WNA)
                            </option>
                        </select>
                    </div>

                    <div class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                        <label class="block mb-1 text-xs text-black/40 dark:text-white/40">No. Telepon</label>
                        <input type="text" name="telp" placeholder="No. Telepon" class="form-input"
                            value="{{ old('telp', $ikm->telp ?? '') }}" />
                    </div>

                    <div class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5" hidden>
                        <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Sosial Media</label>
                        <input type="text" name="sosmed" placeholder="Sosial Media" class="form-input"
                            value="{{ old('sosmed', $ikm->sosmed ?? '') }}" />
                    </div>

                    <div class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5" hidden>
                        <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Website</label>
                        <input type="text" name="website" placeholder="Website" class="form-input"
                            value="{{ old('website', $ikm->website ?? '') }}" />
                    </div>

                    <div class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                        <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Email</label>
                        <input type="email" name="email" placeholder="Email" class="form-input"
                            value="{{ old('email', $ikm->email ?? '') }}" />
                    </div>
                </div>
            </div>
        </form>

    </div>
    <div id="tab-content-1" class="tab-content hidden p-4">
        <p class="text-sm font-semibold mb-3 text-black/40 dark:text-white/40">Daftar Mitra</p>
        <div class="border bg-lightwhite dark:bg-white/5 dark:border-white/10 border-black/10 p-5 rounded-md">
            <div class="mb-4">
                <p class="text-sm font-semibold">Daftar Mitra dan Toko</p>
                <p class="text-xs text-black/60 dark:text-white/60">Berikut adalah daftar mitra dan toko yang telah
                    terdaftar di sistem.</p>
            </div>
            <div class="overflow-x-auto w-full">
                <table class=" border-collapse text-sm">
                    <thead class=" text-gray-700">
                        <tr>
                            <th class="text-left px-4 py-2">Kode Mitra</th>
                            <th class="text-left px-4 py-2">Nama Mitra</th>
                            <th class="text-left px-4 py-2 mobile">Alamat</th>
                            <th class="text-left px-4 py-2 mobile">Kota</th>
                            <th class="text-left px-4 py-2 mobile">Telp</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mitra as $item)
                            <tr class="hover:bg-gray-50 border-b dark:border-white/10">
                                <td class="whitespace-nowrap px-4 py-2">
                                    <a href="/mitra/detail/{{ $item->id }}" class="text-blue-600 hover:underline">
                                        {{ $item->kode_mitra }}
                                    </a>
                                </td>
                                <td class="px-4 py-2">{{ $item->nama_mitra }}</td>
                                <td class="px-4 py-2 mobile"
                                    style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                                    title="{{ $item->alamat_mitra }}">
                                    {{ $item->alamat_mitra }}
                                </td>
                                <td class="px-4 py-2 mobile">{{ $item->id_kota }}</td>
                                <td class="px-4 py-2 mobile">{{ $item->no_telp_mitra }}</td>
                            </tr>
                        @endforeach
                       @if ($mitra->isEmpty())
                        <tr>
                            <td colspan="5" class="py-10">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <!-- Ikon catatan kosong -->
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="w-14 h-14 mb-2 text-gray-300"
                                        fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2
                                                2 0 012-2h7l5 5v14a2 2 0 01-2 2z" />
                                    </svg>

                                    <p class="text-sm text-gray-500">Tidak ada data mitra ditemukan</p>
                                </div>
                            </td>
                        </tr>
                    @endif


                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <div id="tab-content-2" class="tab-content hidden p-4">
        <p class="text-sm font-semibold mb-3 text-black/40 dark:text-white/40">Daftar Produk</p>
        <div class="border bg-lightwhite dark:bg-white/5 dark:border-white/10 border-black/10 p-2 rounded-md">
            <div class="table-responsive overflow-x-auto w-full">
                <table class="w-full border-collapse text-sm table-auto " id="produkTable">
                    <thead class="hidden lg:table-header-group">
                        <tr class="text-gray-400">
                            <th class="text-left pl-6 py-3 font-normal w-1/2">Product</th>
                            <th class="text-left font-normal w-1/6">Created at</th>
                            <th class="text-left font-normal w-1/6">Status</th>
                            <th class="text-left font-normal w-1/6 pr-6">Amount</th>
                            <th class="w-6"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-white/10">
                        @foreach ($produk as $item)
                            <tr class="hover:bg-gray-50">
                                <!-- Produk -->
                                <td class="py-4 pl-6 flex items-start gap-3">

                                    <img src="/storage/{{ $item->gambar }}" alt="Produk"
                                        class="h-12 w-12 rounded-lg object-cover flex-shrink-0"
                                        onerror="this.onerror=null;this.src='{{ asset('assets/images/404.jpg') }}';">


                                    <div class="flex flex-col">
                                        <span class="font-semibold leading-tight">
                                            <a href="/produk/update/{{ $item->id }}">{{ $item->nama_produk }}</a>
                                        </span>
                                        <span class="text-gray-400 leading-tight truncate max-w-[50px]">
                                            {{ $item->deskripsi }}
                                        </span>

                                        <!-- Detail mobile -->
                                        <div class="lg:hidden mt-2 text-xs text-gray-500 space-y-1">
                                            <div><strong>Created at:</strong>
                                                {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}
                                            </div>
                                            <div><strong>Status:</strong>{{ $item->status }}({{ $item->stok }} pcs)
                                            </div>
                                            <div><strong>Amount:</strong>Rp{{ number_format($item->harga, 0, ',', '.') }}
                                            </div>

                                        </div>
                                    </div>
                                </td>

                                <!-- Kolom desktop (disembunyikan di mobile) -->
                                <td class="py-4  font-normal mobile lg:table-cell">{{ $item->kode_produk }}</td>
                                <td class="py-4 mobile lg:table-cell">
                                    <span
                                        class="inline-block rounded px-2 py-0.5 text-xs font-semibold">{{ $item->status }}({{ $item->stok }}
                                        pcs)</span>
                                </td>
                                <td class="py-4 font-semibold mobile lg:table-cell">Rp
                                    Rp{{ number_format($item->harga, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        @if ($produk->isEmpty())
                            <tr>
                                <td colspan="5" class="py-10">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <!-- Ikon catatan kosong -->
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="w-14 h-14 mb-2 text-gray-300"
                                            fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2
                                                    2 0 012-2h7l5 5v14a2 2 0 01-2 2z" />
                                        </svg>

                                        <p class="text-sm text-gray-500">Data tidak ditemukan</p>
                                    </div>
                                </td>
                            </tr>

                        @endif
                    </tbody>
                </table>


            </div>
        </div>
    </div>
    <div id="tab-content-3" class="tab-content hidden p-4">
        <p class="text-sm font-semibold mb-3 text-black/40 dark:text-white/40">Daftar Riwayat Transaksi</p>
        <div class="border bg-lightwhite dark:bg-white/5 dark:border-white/10 border-black/10 p-5 rounded-md">
            <div class="mb-4">
                <p class="text-sm font-semibold">Daftar Riwayat Transaksi </p>
                <p class="text-xs text-black/60 dark:text-white/60">Berikut adalah daftar Transaksi dengan Mitra yang telah
                    terdaftar di sistem.</p>
            </div>

            
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Kode Transaksi</th>
                            <th class="hidden md:block">Tanggal Transaksi</th>
                            <th>Nama Toko</th>
                            <th class="hidden md:block">Nilai Pesanan</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                        @foreach ($transaksi2 as $item)
                            <tr>
                                <td class="whitespace-nowrap"><a
                                        href="/transaksi/{{ $item->id }}">{{ $item->kode_transaksi }}</a></td>
                                <td class="hidden md:block">{{ \Carbon\Carbon::make($item->tanggal_transaksi)->translatedFormat('d F Y') }}</td>
                                <td>{{ $item->mitra->nama_mitra }}</td>
                                <td class="hidden md:block">Rp{{ number_format($item->total, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        @if ($transaksi2->isEmpty())
                             <tr>
                                <td colspan="5" class="py-10">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <!-- Ikon catatan kosong -->
                                        <svg xmlns="http://www.w3.org/2000/svg" 
                                            class="w-16 h-16 mb-3 text-gray-300" 
                                            fill="none" viewBox="0 0 24 24" 
                                            stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 
                                                    2 0 012-2h7l5 5v14a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="text-sm">Tidak ada riwayat transaksi ditemukan</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

        </div>
    </div>
<div id="tab-content-4" class="tab-content hidden p-4">
    <p class="text-sm font-semibold mb-3 text-black/40 dark:text-white/40">Catatan Keuangan</p>
    @php
    // Ambil parameter sort dan filter tanggal dari request
    $sort = request('sort', 'desc');
    $from = request('from');
    $to = request('to');

    // Filter transaksi berdasarkan rentang tanggal jika ada
    $filteredTransaksi = $transaksi;
    if ($from && $to) {
    try {
        $fromDate = \Carbon\Carbon::createFromFormat('d/m/Y', $from)->startOfDay();
        $toDate = \Carbon\Carbon::createFromFormat('d/m/Y', $to)->endOfDay();
        $filteredTransaksi = $filteredTransaksi->filter(function ($item) use ($fromDate, $toDate) {
        $itemDate = \Carbon\Carbon::createFromFormat('d/m/Y', $item->tanggal);
    return $itemDate->between($fromDate, $toDate);
    });
    } catch (\Exception $e) {
    // Jika format salah, tampilkan semua
    }
    }

    // Urutkan transaksi berdasarkan tanggal sesuai sort
    $filteredTransaksi =
    $sort === 'asc' ? $filteredTransaksi->sortBy(function ($item) {
        return \Carbon\Carbon::createFromFormat('d/m/Y', $item->tanggal)->format('Y-m-d');
    }) : $filteredTransaksi->sortByDesc(function ($item) {
        return \Carbon\Carbon::createFromFormat('d/m/Y', $item->tanggal)->format('Y-m-d');
    });
    @endphp



    <div class="border bg-lightwhite dark:bg-white/5 dark:border-white/10 border-black/10 p-2 rounded-md">
         <div class="flex flex-wrap md:flex-row gap-2 items-start w-full md:w-auto">
   
            <!-- Filter -->
            <div x-data="{ openFilter: false }" class="relative shrink-0 mb-3">
                <button type="button"
                        @click="openFilter = !openFilter"
                        class="p-3 rounded-lg bg-gray-100 hover:bg-blue-100 dark:bg-black border border-gray-200 dark:border-white/10 flex items-center justify-center md:justify-start gap-1 w-auto">
                    <i class="fas fa-filter"></i>
                    <span class="hidden sm:inline">Filter</span>
                </button>

                <!-- Dropdown Filter -->
                <div x-show="openFilter"
                    @click.away="openFilter = false"
                    x-transition
                    class="absolute z-50 mt-2 left-0 bg-white dark:bg-black border border-gray-200 dark:border-white/10 rounded-lg shadow-lg p-4 min-w-[320px] max-w-[90vw]">
                    <form method="GET" id="filterForm" class="flex flex-col gap-3">
                        <h3 class="font-semibold text-sm">Filter Transaksi</h3>

                        <div class="flex items-center gap-2">
                            <label class="text-sm w-16">Dari:</label>
                            <input type="text" name="from" id="from_date"
                                value="{{ $from }}"
                                class="form-input py-1 px-2 rounded border border-black/10 dark:border-white/10 w-full"
                                placeholder="dd/mm/yyyy" autocomplete="off">
                        </div>

                        <div class="flex items-center gap-2">
                            <label class="text-sm w-16">Sampai:</label>
                            <input type="text" name="to" id="to_date"
                                value="{{ $to }}"
                                class="form-input py-1 px-2 rounded border border-black/10 dark:border-white/10 w-full"
                                placeholder="dd/mm/yyyy" autocomplete="off">

                        </div>
                        <input type="text" name="ip" value="{{ $id }}" hidden>      
                        <div class="flex gap-2 mt-3">
                            <button type="submit"
                                    class="submitBtn flex items-center justify-center gap-2 bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition w-full">
                                <span class="btn-text">Terapkan</span>
                                <span class="btn-spinner hidden animate-spin border-2 border-white border-t-transparent rounded-full w-4 h-4"></span>
                            </button>

                            <a href="{{ route('index.keuangan') }}"
                            class="bg-gray-200 text-gray-700 px-3 py-1 rounded hover:bg-gray-300 transition w-full text-center">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Pilih Bulan -->
            <form method="GET" class="shrink-0 w-auto">
                <input type="month"
                    name="periode"
                    value="{{ $tahun }}-{{ str_pad($bulan, 2, '0', STR_PAD_LEFT) }}"
                    onchange="this.form.submit()"
                    class="bg-white dark:bg-black form-input py-2.5 px-4 w-auto text-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-100 dark:focus:border-white/20 dark:focus:ring-white/5">
                    <input type="text" name="ip" value="{{ $id }}" hidden>
                    <select name="tipe"
                id="tipe"
                onchange="this.form.submit()"
                class="bg-white dark:bg-black form-input py-2.5 px-4 text-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-100 dark:focus:border-white/20 dark:focus:ring-white/5 w-auto"
                style="min-width: 140px;">
                <option value="" {{ request('tipe') == '' ? 'selected' : '' }}>Semua Tipe</option>
                <option value="pemasukan" {{ request('tipe') == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                <option value="pengeluaran" {{ request('tipe') == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
            </select>
                </form>
                <a  href="{{ route('keuangan.pdf.harian', request()->query()) }}" target="_blank" type="button"
                        @click="openFilter = !openFilter"
                        class="p-3 rounded-lg bg-gray-100 hover:bg-blue-100 dark:bg-black border border-gray-200 dark:border-white/10 flex items-center justify-center md:justify-start gap-1 w-auto">
                    <i class="fas fa-file-pdf"></i>
                    <span class="hidden sm:inline">Filter</span>
            </a>
        </div>
        
        <div class="table-responsive">
            @if($keuangan->isEmpty())
                <div class="flex flex-col items-center justify-center py-10 text-gray-400">
                    <!-- Ikon catatan keuangan kosong -->
                    <svg xmlns="http://www.w3.org/2000/svg" 
                        class="w-16 h-16 mb-3 text-gray-300" 
                        fill="none" 
                        viewBox="0 0 24 24" 
                        stroke="currentColor" 
                        stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 
                                2 0 012-2h7l5 5v14a2 2 0 01-2 2z" />
                    </svg>

                    <div class="text-center text-sm">
                        Tidak ada data keuangan yang tersedia.
                    </div>
                </div>

            @else
            <table class="w-full border-collapse text-sm table-auto" id="produkTable">
                @php
                    // Group transaksi by tanggal (format: Y-m-d)
                    $groupedTransaksi = $keuangan->groupBy(function ($item) {
                        return \Carbon\Carbon::createFromFormat('d/m/Y', $item->tanggal)->format('Y-m-d');
                    });
                @endphp

                @foreach ($groupedTransaksi as $tanggal => $items)
                    @php
                        // Format tanggal untuk header
                        $carbonTanggal = \Carbon\Carbon::createFromFormat('Y-m-d', $tanggal);
                        $tanggalFormatted = $carbonTanggal->translatedFormat('d F Y - l');
                        // Hitung total pemasukan & pengeluaran untuk tanggal ini
                        $pemasukan = $items->where('tipe', 'pemasukan')->sum('total');
                        $pengeluaran = $items->where('tipe', 'pengeluaran')->sum('total');
                    @endphp
                    <thead class="lg:table-header-group">
                        <tr class="text-gray-400">
                            <th width="70%" class="text-left font-normal">{{ $tanggalFormatted }}</th>
                            <th class="text-left">
                                <div class="flex flex-col lg:flex-row gap-4 text-gray-600">
                                    <div>Pemasukan: <span class="text-green-600">Rp {{ number_format($pemasukan, 0, ',', '.') }}</span></div>
                                    <div>Pengeluaran: <span class="text-red-600">Rp {{ number_format($pengeluaran, 0, ',', '.') }}</span></div>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr class="hover:bg-gray-50" x-data="{ openDetail: false }">
                                <!-- Produk / Transaksi -->
                                <td class="py-4 pl-6 flex items-start gap-3">
                                    <div class="flex flex-col">
                                        <span class="font-semibold leading-tight">
                                            <a>{{ $item->deskripsi ?? '-' }}</a>
                                        </span>
                                        <span class="text-gray-400 leading-tight truncate max-w-[120px]">
                                            {{ $item->akun->nama_akun ?? '-' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="py-4 font-semibold mobile lg:table-cell">
                                    <span class="{{ $item->tipe == 'pemasukan' ? 'text-green-600' : 'text-red-600' }}">
                                        Rp. {{ number_format($item->total, 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                @endforeach
            </table>
            @endif
        </div>
    </div>
</div>

    <div id="tab-content-5" class="tab-content hidden p-4">
        <p class="text-sm font-semibold mb-3 text-black/40 dark:text-white/40">Log Aktivitas Pengguna</p>
        <div class="border bg-lightwhite dark:bg-white/5 dark:border-white/10 border-black/10 p-2 rounded-md">
            <div class="bg-white dark:bg-black p-5 rounded-md border border-black/10 dark:border-white/10 max-h-[400px] overflow-y-auto">
            @foreach ($Ikmlogs as $log)
                <div class="flex gap-3 items-start  text-sm text-gray-700 dark:text-gray-300 mb-3 border-b dark:border-white/10 border-black/10 pb-3">
                    <div class="h-6 w-6 flex-none p-1 text-black bg-lightblue-100 rounded-lg">
                        <x-icon name="users" class="text-gray-600" />
                    </div>
                    <div class="flex-1">
                        <p class=" text-sm text-gray-900 dark:text-white">
                            {{ $log->description }} oleh <strong>{{ $log->causer->name ?? 'Sistem' }}</strong>
                        </p>
                        <p class="text-xs dark:text-gray-400 text-gray-500 mb-3">
                            {{ $log->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
            @endforeach
            <div class="text-center dark:text-gray-400 text-sm">
               @if ($Ikmlogs->count() === 0)
                    <div class="flex flex-col items-center justify-center py-10 text-gray-400">
                        <!-- Ikon aktivitas kosong -->
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-14 h-14 mb-2 text-gray-300"
                            fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 13a4 4 0 014-4h3l3-3v12l-3-3H7a4 4 0 01-4-4z" />
                        </svg>

                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Tidak ada aktivitas terbaru.
                        </p>
                    </div>
                @endif

            </div>
            </div>
        </div>
    </div>
    <!-- Modal for cropping -->
    <div class="hidden fixed top-0 left-0 w-full h-full bg-gray-800 bg-opacity-50  justify-center items-center z-50">
        <!-- Modal Background (Overlay) -->
        <div class="absolute inset-0 bg-gray-800 bg-opacity-50 z-40"></div>

        <!-- Modal Container -->
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-4xl relative z-50">
            <h3 class="text-2xl font-semibold mb-6 text-center">Crop Foto</h3>

        </div>
    </div>
    <!-- Modal Cropper -->
    <div id="cropperModal" class="fixed inset-0 bg-black/60 dark:bg-white/10 z-[999] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4" @click.self="toggle">
            <div
                class="bg-white dark:bg-black relative shadow-3xl border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8">
                <div
                    class="flex bg-white dark:bg-black border-b border-black/10 dark:border-white/10 items-center justify-between px-5 py-3">
                    <h5 class="font-semibold text-lg">Tambah Mitra</h5>
                    <button type="button" class="text-black/40 dark:text-white/40 hover:text-black dark:hover:text-white"
                        @click="toggle">
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
                <div class="p-5">
                    <div class="text-sm text-black dark:text-white">
                        <div class="relative">
                            <img id="image" src="" alt="Image"
                                class="h-auto max-h-96 object-contain w-60">
                        </div>
                        <div class="mt-6 flex justify-end space-x-4">
                            <button id="cancelCrop"
                                class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600">Batal</button>
                            <button id="saveCrop"
                                class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
     <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
    <script>
        // Inisialisasi Flatpickr dengan lokal Indonesia
        flatpickr("#from_date", {
            locale: "id",
            dateFormat: "d/m/Y",
        });

        flatpickr("#to_date", {
            locale: "id",
            dateFormat: "d/m/Y",
        });
    </script>
    <script>
        const imageInput = document.getElementById('image-input');
        const preview = document.getElementById('preview');
        const cropBtn = document.getElementById('crop-btn');
        const submitBtn = document.getElementById('submit-btn');
        const croppedFotoInput = document.getElementById('croppedFoto');
        let cropper;

        // Handle image input change
        imageInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = () => {
                    preview.src = reader.result;
                    preview.style.display = 'block';
                    cropBtn.style.display = 'inline';
                    submitBtn.disabled = true;

                    // Initialize cropper
                    if (cropper) cropper.destroy();
                    cropper = new Cropper(preview, {
                        aspectRatio: 463 / 451, // Set aspect ratio for the desired dimensions
                        viewMode: 1, // Restrict crop box within the canvas
                        autoCropArea: 1 // Maximize the crop box within the canvas
                    });
                };
                reader.readAsDataURL(file);
            }
        });

        // Handle cropping
        cropBtn.addEventListener('click', () => {
            const canvas = cropper.getCroppedCanvas({
                width: 463, // Set width to 463px
                height: 451, // Set height to 451px
            });

            // Convert to Blob and Base64
            canvas.toBlob((blob) => {
                const reader = new FileReader();
                reader.onloadend = () => {
                    croppedFotoInput.value = reader.result; // Base64 data for form
                    preview.src = reader.result;
                    cropper.destroy();
                    cropBtn.style.display = 'none';
                    submitBtn.disabled = false;
                };
                reader.readAsDataURL(blob);
            }, 'image/jpeg', 1.0); // Set image quality to 100%
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#jenis_kelamin').select2({
                placeholder: "Pilih Jenis Kelamin",
                width: '100%'
            });
            $('#agama').select2({
                placeholder: "Agama",
                width: '100%'
            });
            $('#status_perkawinan').select2({
                placeholder: "Status Perkawinan",
                width: '100%'
            });
            $('#kewarganegaraan').select2({
                placeholder: "kewarganegaraan",
                width: '100%'
            });
            $('#provinsi').select2({
                placeholder: "Pilih Provinsi",
                width: '100%'
            });
            $('#kabupaten').select2({
                placeholder: "Pilih Kabupaten / Kota",
                width: '100%'
            });
            $('#kecamatan').select2({
                placeholder: "Pilih Kecamatan",
                width: '100%'
            });
            $('#desa').select2({
                placeholder: "Pilih Desa",
                width: '100%'
            });
        });
    </script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('modals', () => ({
                open: false,
                cropper: null,

                init() {
                    this.$nextTick(() => {
                        // Inisialisasi Cropper setelah modal muncul
                        const image = document.getElementById('image');
                        this.cropper = new Cropper(image, {
                            aspectRatio: 1, // Ubah sesuai kebutuhan
                            viewMode: 1,
                            autoCropArea: 0.8,
                            responsive: true,
                            modal: true,
                        });
                    });
                },

                toggle() {
                    this.open = !this.open;
                },

                close() {
                    this.open = false;
                    // Hapus cropper dan reset gambar jika modal ditutup
                    if (this.cropper) {
                        this.cropper.destroy();
                    }
                },

                saveCrop() {
                    const croppedCanvas = this.cropper.getCroppedCanvas(); // Ambil gambar hasil crop

                    // Ubah ke base64
                    const croppedImage = croppedCanvas.toDataURL();

                    // Kirim hasil gambar ke controller atau API
                    this.uploadCroppedImage(croppedImage);
                },

                uploadCroppedImage(croppedImage) {
                    // Contoh upload menggunakan fetch (AJAX)
                    const formData = new FormData();
                    formData.append('foto', croppedImage);

                    fetch("{{ route('ikm.store') }}", {
                            method: "POST",
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}", // CSRF Token untuk keamanan
                            },
                            body: formData,
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Foto berhasil disimpan!');
                                this.close(); // Tutup modal setelah berhasil
                            } else {
                                alert('Terjadi kesalahan saat menyimpan foto.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat mengirim data.');
                        });
                }
            }));
        });
    </script>

    <script>
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $(function() {
                $('#provinsi').on('change', function() {
                    let id_provinsi = $('#provinsi').val();

                    $.ajax({
                        type: 'POST',
                        url: "{{ route('getkabupaten') }}",
                        data: {
                            id_provinsi: id_provinsi
                        },
                        cache: false,

                        success: function(msg) {
                            $('#kabupaten').removeAttr('disabled');
                            $('#kabupaten').html(msg);
                            $('#kecamatan').html('');
                            $('#desa').html('');

                        },
                        error: function(data) {
                            console.log('error:', data)
                        },
                    })
                })


                $('#kabupaten').on('change', function() {
                    let id_kabupaten = $('#kabupaten').val();

                    $.ajax({
                        type: 'POST',
                        url: "{{ route('getkecamatan') }}",
                        data: {
                            id_kabupaten: id_kabupaten
                        },
                        cache: false,

                        success: function(msg) {
                            $('#kecamatan').removeAttr('disabled');
                            $('#kecamatan').html(msg);
                            $('#desa').html('');


                        },
                        error: function(data) {
                            console.log('error:', data)
                        },
                    })
                })

                $('#kecamatan').on('change', function() {
                    let id_kecamatan = $('#kecamatan').val();

                    $.ajax({
                        type: 'POST',
                        url: "{{ route('getdesa') }}",
                        data: {
                            id_kecamatan: id_kecamatan
                        },
                        cache: false,

                        success: function(msg) {
                            $('#desa').removeAttr('disabled');
                            $('#desa').html(msg);


                        },
                        error: function(data) {
                            console.log('error:', data)
                        },
                    })
                })
            })
        });
    </script>
    <script>
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content'); // pastikan class ini di semua konten tab

        function changeTab(index) {
            tabButtons.forEach((btn, i) => {
                if (i === index) {
                    btn.style.color = '#2563EB'; // blue-600
                    btn.style.borderColor = '#2563EB';
                } else {
                    btn.style.color = '#4B5563'; // gray-600
                    btn.style.borderColor = 'transparent';
                }

            });

            tabContents.forEach((content, i) => {
                content.classList.toggle('hidden', i !== index);
            });

            localStorage.setItem('activeTab', index);
        }

        // Aktifkan tab terakhir saat halaman dimuat
        // window.addEventListener('DOMContentLoaded', () => {
        //     const savedIndex = parseInt(localStorage.getItem('activeTab')) || 0;
        //     changeTab(savedIndex);
        // });
    </script>
  <script>
document.addEventListener('DOMContentLoaded', function () {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');
    const activeTabIndex = localStorage.getItem('activeTabIndex') || 0;

    // Fungsi ubah tab
    function changeTab(index) {
        tabButtons.forEach((btn, i) => {
            btn.classList.toggle('bg-blue-600', i === index);
            btn.classList.toggle('text-white', i === index);
        });
        tabContents.forEach((content, i) => {
            content.classList.toggle('hidden', i !== index);
        });

        localStorage.setItem('activeTabIndex', index);
    }

    // Pasang event klik ke semua tombol tab
    tabButtons.forEach((btn, i) => {
        btn.addEventListener('click', () => changeTab(i));
    });

    // Saat halaman pertama kali dimuat → buka tab terakhir yang disimpan
    changeTab(parseInt(activeTabIndex));
});
</script>

@endsection
