@extends('layout.main')
@section('title', 'Rekening Virtual')
@section('container')
<style>
    @media (max-width: 768px) {
        .p-7 {
            padding: 9px;
        }
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
<div class="px-2 py-1 flex items-center justify-between mb-3">
    <h2 class="text-lg font-semibold">Daftar Rekening</h2>
    <div x-data="modals">
        <button type="button" @click="toggle"
            class="px-2 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
            + Tambah
        </button>
        <div class="fixed inset-0 bg-black/60 dark:bg-white/10 z-[999] hidden overflow-y-auto"
            :class="open &amp;&amp; '!block'">
            <div class="flex items-center justify-center min-h-screen px-4" @click.self="open = false">
                <div x-show="open" x-transition="" x-transition.duration.300=""
                    class="bg-white dark:bg-black relative shadow-3xl border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8"
                    style="display: none;">
                    <div
                        class="flex bg-white dark:bg-black border-b border-black/10 dark:border-white/10 items-center justify-between px-5 py-3">
                        <h5 class="font-semibold text-lg">Tambah Rekening Baru</h5>
                        <button type="button"
                            class="text-black/40 dark:text-white/40 hover:text-black dark:hover:text-white"
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
                        @if ($errors->any())
                        <div class="mb-4">
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow animate-pulse"
                                role="alert">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 mr-2 text-red-500 animate-bounce" fill="none"
                                        stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 8v4m0 4h.01M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z" />
                                    </svg>
                                    <span class="font-bold">Oops! Ada kesalahan:</span>
                                </div>
                                <ul class="mt-2 list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                    <li class="animate-fade-in-down">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        @endif
                        <form action="{{ route('rekening.add.harian') }}" method="POST">
                            @csrf
                            <div
                                class="hidden mb-4 relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10">
                                <label class="block text-xs text-black/40 dark:text-white/40 mb-1">ID</label>
                                <input type="text" name="kode_rekening" id="kode_rekening"
                                    placeholder="Silahkan Masukan Nama Rekening" class="form-input" readonly>
                            </div>
                            <div
                                class="mb-4 relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10">
                                <label class="block text-xs text-black/40 dark:text-white/40 mb-1">Nama Rekening
                                    <span style="color: red">*</span></label>
                                <input type="text" name="nama_rekening" placeholder="Silahkan Masukan Nama Rekening"
                                    class="form-input">
                            </div>
                            <div
                                class="mb-4 relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10">
                                <label class="block text-xs text-black/40 dark:text-white/40 mb-1">Jenis
                                    Akun <span style="color: red">*</span></label>
                                <select name="jenis_akun"
                                    class="select2 form-select py-2.5 px-3 text-sm text-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg focus:border-black dark:focus:border-white focus:ring-0 focus:shadow-none w-[150px] select2-hidden-accessible"
                                    tabindex="-1" aria-hidden="true">
                                    <option value="default">Default</option>
                                    <option value="uang_tunai">Uang Tunai</option>
                                    <option value="kartu_kredit">Kartu Debit</option>
                                    <option value="kartu_kredit">Kartu Kredit <small> ( Liabilitas )</small></option>
                                    <option value="rekening_virtual">Rekening Virtual</option>
                                    <option value="investasi">Investasi</option>
                                    <option value="piutang">Piutang</option>
                                    <option value="hutang">Hutang ( Liabilitas )</option>
                                </select>
                            </div>
                            <div
                                class="mb-4 relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10">
                                <label class="block text-xs text-black/40 dark:text-white/40 mb-1">Jumlah <span
                                        style="color: red">*</span></label>
                                <div class="flex items-center">
                                    <span class="mr-2 text-gray-500">Rp.</span>
                                    <input type="text" name="jumlah" class="form-input jumlah-input"
                                        placeholder="Masukkan nominal, contoh: 10.000"
                                        oninput="this.value = this.value.replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')">
                                </div>
                            </div>
                            <div
                                class="mb-4 relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10">
                                <label class="block text-xs text-black/40 dark:text-white/40 mb-1">Keterangan </label>
                                <input type="text" name="keterangan" placeholder="Keterangan" class="form-input">
                            </div>
                            <button type="submit"
                                class=" w-full px-2 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                                Simpan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-7 mb-4">
    <div class="bg-lightblue-100 rounded-2xl p-6">
        <p class="text-sm font-semibold text-black mb-2">Kekayaan Bersih</p>
        <div class="flex items-center justify-between">
            @php
            $totalJumlah = $rekening->sum('jumlah');
            @endphp
            <h2 class="text-2xl leading-9 font-semibold text-black">Rp.{{ number_format($totalJumlah, 0, ',', '.') }}
            </h2>

        </div>
    </div>

</div>
<div x-data="{ showEditModal: false, editRekening: {} }">
    <div class="border bg-lightwhite dark:bg-white/5 dark:border-white/10 border-black/10 p-2 rounded-md">
        <div class="">
            <table class=" border-collapse text-sm table-auto" id="produkTable">
                <thead class=" lg:table-header-group">
                    <tr class="text-gray-400">
                        <th class="text-left pl-6 py-3 font-norma" width="30%">Rekening</th>
                        <th class="text-left font-normal w-1/2"></th>
                        <th class="text-left font-normal w-1/6">Saldo</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($rekening as $item)
                    <tr class="hover:bg-gray-50">
                        <!-- Produk -->
                        <td class="py-4 flex items-start gap-3">
                            <a>
                                <img src="{{ asset('assets/images/rekening.png') }}" alt="Rekening"
                                    class="hidden md:block h-12 w-12 rounded-lg object-cover flex-shrink-0">
                            </a>

                            <div class="flex flex-col">
                                <style>
                                    /* Default untuk mobile */
                                    .rekening-text {
                                        display: inline-block;
                                        max-width: 70px;
                                        white-space: nowrap;
                                        overflow: hidden;
                                        text-overflow: ellipsis;
                                    }

                                    /* Di desktop (>=768px) tampil full */
                                    @media (min-width: 768px) {
                                        .rekening-text {
                                            max-width: none;
                                            white-space: normal;
                                            overflow: visible;
                                            text-overflow: unset;
                                        }
                                    }
                                </style>
                                <span class="rekening-text">
                                    {{ $item->nama_rekening }}
                                </span>
                                <span class="text-gray-400 leading-tight truncate max-w-[100px]">
                                    {{ $item->kode_rekening ?? '-' }}
                                </span>
                                <!-- Detail mobile -->

                            </div>
                        </td>
                        <td class="py-4 ">
                            <div class="hidden md:block">
                                @if((app('settings')['default_rekening_harian'] ?? null) == $item->kode_rekening)
                                <span class="bg-violet-500 text-white text-[10px] px-1.5 py-0.5 rounded">
                                    <span class="p-0.5 rounded-full bg-white inline-block mr-1 align-middle"></span>
                                    Default
                                </span>
                                @endif

                            </div>
                        </td>
                        <td class="py-4 font-semibold mobile lg:table-cell">
                            Rp{{ number_format($item->jumlah, 0, ',', '.') }}
                        </td>

                        <td class="py-4 pr-6 text-right">
                            <div class="relative inline-block text-left" x-data="{ open: false }">
                                <button @click="open = !open" type="button"
                                    class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-3 py-1 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none"
                                    id="options-menu" aria-haspopup="true" aria-expanded="true">
                                    ⋮
                                </button>
                                <div x-show="open" @click.away="open = false"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="origin-top-right absolute right-0 mt-2 w-36 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10"
                                    style="display: none;">
                                    <div class="py-1" role="menu" aria-orientation="vertical"
                                        aria-labelledby="options-menu">
                                        @if((app('settings')['default_rekening'] ?? null) != $item->kode_rekening)
                                        <form action="{{ route('default.rekening.harian', $item->id) }}" method="GET">
                                            @csrf
                                            <button type="submit"
                                                class="block w-full text-left px-4 py-2 text-sm text-violet-700 hover:bg-gray-100"
                                                role="menuitem">
                                                Jadikan Default
                                            </button>
                                        </form>
                                        @endif

                                        <a href="{{ route('rekening.history.harian', $item->kode_rekening) }}"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                            role="menuitem">
                                            History
                                        </a>
                                        <!-- Tombol Update -->
                                        <button type="button" @click="
                                                editRekening = {
                                                    id: '{{ $item->id }}',
                                                    kode_rekening: '{{ $item->kode_rekening }}',
                                                    nama_rekening: '{{ $item->nama_rekening }}',
                                                    jenis_akun: '{{ $item->jenis_akun }}',
                                                    jumlah: '{{ number_format($item->jumlah, 0, ',', '.') }}',
                                                    keterangan: '{{ $item->keterangan }}'
                                                };
                                                showEditModal = true;
                                                open = false;
                                            "
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                            role="menuitem">
                                            Update
                                        </button>
                                    </div>
                                    <form action="{{ route('rekening.delete.harian', $item->id) }}" method="POST"
                                        @submit.prevent="Swal.fire({
                                            title: 'Yakin ingin menghapus rekening ini?',
                                            text: 'Aksi ini tidak dapat dibatalkan!',
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonColor: '#3085d6',
                                            cancelButtonColor: '#d33',
                                            confirmButtonText: 'Ya, hapus!',
                                            cancelButtonText: 'Batal',
                                            customClass: {
                                                popup: 'rounded-xl',
                                                confirmButton: 'bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg mr-2',
                                                cancelButton: 'bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg'
                                            }
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                $el.submit();
                                            }
                                        }); return false;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100"
                                            role="menuitem">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
        </div>
        </td>
        </tr>
        @endforeach
        @if($rekening->isEmpty())
        <tr>
            <td colspan="4" class="text-center py-4 text-gray-500">
                Data tidak ditemukan.
            </td>
        </tr>
        @endif
        </tbody>
        </table>


    </div>
</div>

<!-- Modal Edit Rekening (global, outside loop) -->
<div x-show="showEditModal" class="fixed inset-0 bg-black/60 dark:bg-white/10 z-[999] flex items-center justify-center"
    style="display: none;">
    <div class="bg-white dark:bg-black relative shadow-3xl border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8"
        @click.away="showEditModal = false" x-transition>
        <div
            class="flex bg-white dark:bg-black border-b border-black/10 dark:border-white/10 items-center justify-between px-5 py-3">
            <h5 class="font-semibold text-lg">Edit Rekening</h5>
            <button type="button" class="text-black/40 dark:text-white/40 hover:text-black dark:hover:text-white"
                @click="showEditModal = false">
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
            <form action="{{ route('rekening.update.harian') }} " method="POST">
                @csrf
                <input type="hidden" name="id" x-model="editRekening.id">
                <div class="mb-4 relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10">
                    <label class="block text-xs text-black/40 dark:text-white/40 mb-1">ID</label>
                    <input type="text" name="kode_rekening" x-model="editRekening.kode_rekening" class="form-input"
                        readonly>
                </div>
                <div class="mb-4 relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10">
                    <label class="block text-xs text-black/40 dark:text-white/40 mb-1">Nama Rekening <span
                            style="color: red">*</span></label>
                    <input type="text" name="nama_rekening" x-model="editRekening.nama_rekening" class="form-input">
                </div>
                <div class="mb-4 relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10">
                    <label class="block text-xs text-black/40 dark:text-white/40 mb-1">Jenis Akun <span
                            style="color: red">*</span></label>
                    <select name="jenis_akun" x-model="editRekening.jenis_akun"
                        class="select2 form-select py-2.5 px-3 text-sm text-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg focus:border-black dark:focus:border-white focus:ring-0 focus:shadow-none w-[150px]">
                        <option value="default">Default</option>
                        <option value="uang_tunai">Uang Tunai</option>
                        <option value="kartu_kredit">Kartu Debit</option>
                        <option value="kartu_kredit">Kartu Kredit <small> ( Liabilitas )</small></option>
                        <option value="rekening_virtual">Rekening Virtual</option>
                        <option value="investasi">Investasi</option>
                        <option value="piutang">Piutang</option>
                        <option value="hutang">Hutang ( Liabilitas )</option>
                    </select>
                </div>
                <div class="mb-4 relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10">
                    <label class="block text-xs text-black/40 dark:text-white/40 mb-1">Jumlah <span
                            style="color: red">*</span></label>
                    <div class="flex items-center" @readonly(true)>
                        <span class="mr-2 text-gray-500">Rp.</span>
                        <input type="text" name="jumlah" x-model="editRekening.jumlah" class="form-input"
                            placeholder="Masukkan nominal, contoh: 100.000"
                            oninput="this.value = this.value.replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')">

                    </div>
                </div>
                <script>
                    document.addEventListener('alpine:init', () => {
                            Alpine.data('modals', () => ({
                                open: false,
                                toggle() { this.open = !this.open }
                            }));
                        });
                </script>
                <script>
                    // Format input jumlah pada submit agar ke database tanpa titik
                        document.addEventListener('DOMContentLoaded', function() {
                            document.querySelectorAll('form').forEach(form => {
                                form.addEventListener('submit', function(e) {
                                    let jumlahInput = form.querySelector('input[name="jumlah"]');
                                    if (jumlahInput) {
                                        jumlahInput.value = jumlahInput.value.replace(/\./g, '');
                                    }
                                });
                            });
                        });
                </script>


                <div class="mb-4 relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10">
                    <label class="block text-xs text-black/40 dark:text-white/40 mb-1">Keterangan </label>
                    <input type="text" name="keterangan" x-model="editRekening.keterangan" class="form-input">
                </div>
                <button type="submit"
                    class="w-full px-2 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
</div>
</div>


<script>
    $(document).ready(function() {
            $(".select2").select2({
                width: '100%'
            });
        });
</script>
<script>
    // Generate kode produk otomatis
        document.addEventListener('DOMContentLoaded', function() {
            const kodeInput = document.getElementById('kode_rekening');
            if (kodeInput) {
                const now = new Date();
                const month = String(now.getMonth() + 1).padStart(2, '0'); // Bulan (01-12)
                const year = String(now.getFullYear()).slice(-2); // 2 digit terakhir tahun
                const random = Math.floor(1000 + Math.random() * 9000); // 4 digit acak

                kodeInput.value = `RKN${month}${year}${random}`;
            }
        });
</script>
<script>
    $(document).ready(function() {
        $(".select2").select2({width: '100%'
        });
    });

    // Alpine.js modal dat,a (only once globally)
    document.addEventListener('alpine:init', () => {
        Alpine.data('modals', () => ({
            open: false,
            toggle() { this.open = !this.open }
        }));
    });

    // Format input jumlah pada submit agar ke database tanpa titik
    document.addEventListener('DOMContentLoaded', function() {
        // Format on input for all .jumlah-input fields
        document.querySelectorAll('.jumlah-input').forEach(input => {
            input.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                e.target.value = value;
            });
            input.addEventListener('blur', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                e.target.value = value;
            });
        });
        // Remove dots before submit for all forms
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                let jumlahInput = form.querySelector('input[name="jumlah"]');
                if (jumlahInput) {
                    jumlahInput.value = jumlahInput.value.replace(/\./g, '');
                }
            });
        });

        // Generate kode produk otomatis
        const kodeInput = document.getElementById('kode_rekening');
        if (kodeInput) {
            const now = new Date();
            const month = String(now.getMonth() + 1).padStart(2, '0'); // Bulan (01-12)
            const year = String(now.getFullYear()).slice(-2); // 2 digit terakhir tahun
            const random = Math.floor(1000 + Math.random() * 9000); // 4 digit acak
            kodeInput.value = `RKN${month}${year}${random}`;
        }
    });
</script>
@endsection
