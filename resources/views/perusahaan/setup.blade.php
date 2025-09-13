<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&amp;display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- CSS Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
     <!-- Site favicon -->
    <link rel="shortcut icon" href="{{asset('assets/fav.png')}}" />
    <!-- jQuery (wajib untuk Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- JS Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Animasi berputar */
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .spin {
            animation: spin 1s linear infinite;
        }

        .border-red {
            border-color: #f40606;
            /* Warna merah terang */
        }
    </style>
    <style>
        .select2-container--default .select2-selection--single {
            margin-left: -10px;
            border: none;
        }

        .dark .select2-container--default .select2-selection--single {
            background-color: rgba(0, 0, 0, 0);
            margin-left: -12px;
            border: none;
        }

        .select2-container .select2-selection--single {
            background-color: transparent !important;

            height: 40px;
            /* atau sesuaikan dengan desain */

            color: #334155;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            background-color: transparent !important;
            color: #334155 !important;
            line-height: 38px;
            /* disesuaikan dengan tinggi */
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px;
        }

        .select2-dropdown {
            background-color: #ffffff;
            /* dropdown tetap putih */
        }
    </style>
</head>

<body>
    <main class="flex flex-col overflow-hidden w-full min-h-screen bg-white rounded-lg md:flex-row">
        <section class="hidden bg-[#f0f5ff] items-center justify-center relative md:flex md:w-1/2">
            <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module">
            </script>
            <dotlottie-player src="https://lottie.host/4d4bc1fb-5aef-4126-be78-a362daaecde4/UonYqk3Qcr.lottie"
                background="transparent" speed="1" style="width: 100%; height: auto;" loop autoplay></dotlottie-player>
        </section>
        <section class="w-full p-8 md:w-1/2 md:p-16">
            <div class="flex space-x-2 mb-10 items-center">
                <img src="{{ asset('assets/app_logo.png') }}" alt="App Logo" class="rounded" width="150">
            </div>
            <h1 class="mb-2 text-[#0f172a] text-3xl font-semibold leading-tight">
                Sedikit Lagi Hampir Siap..!!
            </h1>
            <p class="mb-8 text-[#334155] text-base">
                Masukan identitas perusahaan yang anda kelola
            </p>
            <form class="space-y-5" action="{{ route('perusahaan.create') }}" method="POST">
                @if (session('Perhatian'))
                    <div class="mb-4 p-4 bg-yellow-100 border border-yellow-400 text-yellow-800 rounded">
                        {{ session('Perhatian') }}
                    </div>
                @endif
                @csrf
                <label class="block relative">
                    <span class="flex text-[#64748b] absolute inset-y-0 left-4 items-center">
                        <i class="fas fa-user"></i>
                    </span>
                    <input id="name" name="name"
                        class="w-full pl-12 pr-4 py-3 text-[#334155] placeholder-[#64748b] bg-[#f8fafc] rounded-lg border @error('name') border-red-500 @enderror focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                        placeholder="Nama Perusahaan" type="text" value="{{ old('name') }}" required />

                </label>
                @error('name')
                <div class="text-red-500  text-sm">{{ $message }}</div>
                @enderror
                <label class="block relative">
                    <span class="flex text-[#64748b] absolute inset-y-0 left-4 items-center">
                        <i class="fas fa-phone"></i>
                    </span>
                    <input id="phone" name="phone"
                        class="w-full pl-12 pr-4 py-3 text-[#334155] placeholder-[#64748b] bg-[#f8fafc] rounded-lg border @error('phone') border-red-500 @enderror focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                        placeholder="Nomor Telepon Perusahaan" type="tel"
                        value="{{ auth()->user()->phone ?? old('phone') }}" pattern="[0-9]{10,15}"
                        title="Hanya angka yang diizinkan dan panjang nomor 10-15 digit" required/>
                </label>

                @error('phone')
                <div class="text-red-500  text-sm">{{ $message }}</div>
                @enderror
                <label class="block relative">
                    <span class="flex text-[#64748b] absolute inset-y-0 left-4 items-center">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <input id="email" name="email"
                        class="w-full pl-12 pr-4 py-3 text-[#334155] placeholder-[#64748b] bg-[#f8fafc] rounded-lg border @error('email') border-red-500 @enderror focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                        placeholder="Email Perusahaan" type="email"
                        value="{{auth()->user()->email ??  old('email') }}" required/>

                </label>
                @error('email')
                <div class="text-red-500  text-sm">{{ $message }}</div>
                @enderror
                <label class="block relative">
                    <span class="flex text-[#64748b] absolute inset-y-0 left-4 items-center">
                        <i class="fas fa-map-marker-alt"></i>
                    </span>
                    <input id="alamat" name="alamat"
                        class="w-full pl-12 pr-12 py-3 text-[#334155] placeholder-[#64748b] bg-[#f8fafc] rounded-lg border @error('alamat') border-red-500 @enderror focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                        placeholder="Alamat Perusahaan" type="text" required />
                </label>
                @error('alamat')
                <div class="text-red-500 text-sm">{{ $message }}</div>
                @enderror
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-7">
                    {{-- Provinsi --}}

                    <div
                        class="w-full pl-12 pr-12 py-3 text-[#334155] placeholder-[#64748b] bg-[#f8fafc] rounded-lg border @error('alamat') border-red-500 @enderror focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                        <label for="provinsi" class="text-sm font-medium text-slate-700 mb-1 block">Provinsi</label>
                        <div class="relative">

                            <select id="provinsi" name="id_provinsi"
                                class="select2 pl-10 pr-4 py-2 w-full border rounded-lg text-slate-700 focus:ring-2 focus:ring-blue-600">
                                <option value="">Pilih Provinsi</option>
                                @foreach ($provinsi as $ikm2)
                                <option value="{{ $ikm2->id }}">{{ $ikm2->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('id_provinsi')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Kabupaten --}}
                    <div
                        class="w-full pl-12 pr-12 py-3 text-[#334155] placeholder-[#64748b] bg-[#f8fafc] rounded-lg border @error('alamat') border-red-500 @enderror focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                        <label for="kabupaten" class="text-sm font-medium text-slate-700 mb-1 block">Kota /
                            Kabupaten</label>
                        <div class="relative">
                            <select id="kabupaten" name="id_kota"
                                class="select2 pl-10 pr-4 py-2 w-full border rounded-lg text-slate-700 focus:ring-2 focus:ring-blue-600">
                                <option value="">Pilih Kota/Kabupaten</option>
                            </select>
                        </div>
                        @error('id_kota')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Kecamatan --}}
                    <div
                        class="w-full pl-12 pr-12 py-3 text-[#334155] placeholder-[#64748b] bg-[#f8fafc] rounded-lg border @error('alamat') border-red-500 @enderror focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                        <label for="kecamatan" class="text-sm font-medium text-slate-700 mb-1 block">Kecamatan</label>
                        <div class="relative">

                            <select id="kecamatan" name="id_kecamatan"
                                class="select2 pl-10 pr-4 py-2 w-full border rounded-lg text-slate-700 focus:ring-2 focus:ring-blue-600">
                                <option value="">Pilih Kecamatan</option>
                            </select>
                        </div>
                        @error('id_kecamatan')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Desa --}}
                    <div
                        class="w-full pl-12 pr-12 py-3 text-[#334155] placeholder-[#64748b] bg-[#f8fafc] rounded-lg border @error('alamat') border-red-500 @enderror focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                        <label for="desa" class="text-sm font-medium text-slate-700 mb-1 block">Desa / Kelurahan</label>
                        <div class="relative">

                            <select id="desa" name="id_desa"
                                class="select2 pl-10 pr-4 py-2 w-full border rounded-lg text-slate-700 focus:ring-2 focus:ring-blue-600">
                                <option value="">Pilih Desa</option>
                            </select>
                        </div>
                        @error('id_desa')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button
                    class="relative w-full py-3 text-white font-semibold bg-blue-600 rounded-lg transition-colors hover:bg-blue-700"
                    type="submit" id="signup-button">

                    <i class="fas fa-user absolute left-4 top-1/2 transform -translate-y-1/2" id="signup-icon"></i>

                    <span id="button-text">Ayo Mulai!</span>

                    <svg id="loading-spinner" class="hidden absolute left-4 top-1/2 w-5 h-5 text-white animate-spin"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                </button>

            </form>

        </section>
    </main>
    
    <script>
        $(document).ready(function() {
        $('.select2').select2({
            width: '100%',
            placeholder: 'Pilih data...',  // Ganti sesuai konteks misalnya 'Pilih Provinsi'
            allowClear: true  // Menampilkan tombol hapus (x)
        });
    });
    </script>



    <script>
        document.getElementById('phone').addEventListener('input', function(event) {
            // Membatasi hanya angka yang dapat dimasukkan
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>

    <script>
        $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Saat Provinsi dipilih
        $('#provinsi').on('change', function () {
            let id_provinsi = $(this).val();

            // Tampilkan loading
            $('#kabupaten').html('<option>Loading data kabupaten...</option>');
            $('#kecamatan').html('<option value="">Pilih Kecamatan</option>');
            $('#desa').html('<option value="">Pilih Desa</option>');

            $.ajax({
                type: 'POST',
                url: "{{ route('getkabupaten') }}",
                data: { id_provinsi: id_provinsi },
                cache: false,

                success: function (msg) {
                    $('#kabupaten').html(msg).prop('disabled', false);
                },
                error: function (data) {
                    console.log('error:', data)
                },
            });
        });

        // Saat Kabupaten dipilih
        $('#kabupaten').on('change', function () {
            let id_kabupaten = $(this).val();

            $('#kecamatan').html('<option>Loading data kecamatan...</option>');
            $('#desa').html('<option value="">Pilih Desa</option>');

            $.ajax({
                type: 'POST',
                url: "{{ route('getkecamatan') }}",
                data: { id_kabupaten: id_kabupaten },
                cache: false,

                success: function (msg) {
                    $('#kecamatan').html(msg).prop('disabled', false);
                },
                error: function (data) {
                    console.log('error:', data)
                },
            });
        });

        // Saat Kecamatan dipilih
        $('#kecamatan').on('change', function () {
            let id_kecamatan = $(this).val();

            $('#desa').html('<option>Loading data desa...</option>');

            $.ajax({
                type: 'POST',
                url: "{{ route('getdesa') }}",
                data: { id_kecamatan: id_kecamatan },
                cache: false,

                success: function (msg) {
                    $('#desa').html(msg).prop('disabled', false);
                },
                error: function (data) {
                    console.log('error:', data)
                },
            });
        });
    });
    </script>

</body>

</html>