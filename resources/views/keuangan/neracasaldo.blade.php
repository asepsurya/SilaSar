@extends('layout.main')
@section('title', 'Laporan Neraca Saldo')
@section('container')
<style>
/* 🔹 Efek scale tabel hanya di layar kecil */

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


<div class="flex justify-between items-center  dark:border-white/10 py-4">
      <h2 class="text-2xl font-semibold text-gray-800 ">
    Neraca Saldo
    <span class="text-gray-500 text-base font-normal">(Dalam Rupiah)</span>
</h2>

  <form id="filterForm" method="GET" class="flex gap-2 items-center">
     <button type="button" @click="window.dispatchEvent(new CustomEvent('filter'))"
            class="flex items-center gap-x-2 px-3 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
            <span>Filter</span>
        </button>

    <a id="pdfLink" href="{{ route('keuangan.neracaSaldoPdf', ['bulan' => $bulan, 'tahun' => $tahun, 'periode' => request('periode'), 'tahun_bulan' => request('tahun_bulan'), 'tahun_tahun' => request('tahun_tahun'), 'tanggal_awal' => request('tanggal_awal'), 'tanggal_akhir' => request('tanggal_akhir')]) }}"
            class="px-3 py-2 text-sm bg-green-600 hover:bg-green-700 text-white rounded-lg transition" target="_Blank">
            Cetak PDF
        </a>
        <!-- Modal -->
    <div x-data="{ open: false }" @filter.window="open = true" @close-modal.window="open = false">
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
                        <h5 class="font-semibold text-lg">Filter</h5>
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
                        <form method="GET" class="flex flex-col gap-5">


                            <!-- Filter Waktu -->
                            <div>
                                <label class="block text-xs font-semibold text-black/60 dark:text-white/60 mb-1">Periode
                                    Waktu</label>
                                <div class="flex flex-col gap-2">
                                    <select name="periode" id="periodeFilter"
                                        class="form-select py-2 px-4 rounded-lg border border-black/10 dark:border-white/10 text-sm w-full bg-transparent dark:bg-transparent text-black dark:text-white">
                                        <option value="">Semua Waktu</option>
                                        <option value="bulanan" {{ request('periode')=='bulanan' ? 'selected' : '' }}>
                                            Bulanan</option>
                                        <option value="tahunan" {{ request('periode')=='tahunan' ? 'selected' : '' }}>
                                            Tahunan</option>
                                        <option value="rentang" {{ request('periode')=='rentang' ? 'selected' : '' }}>
                                            Rentang Tanggal</option>
                                    </select>
                                    <div id="filterBulanan"
                                        class="{{ request('periode') == 'bulanan' ? '' : 'hidden' }}">
                                        <div class="flex gap-2">
                                            <select name="bulan"
                                                class="form-select py-2 px-3 rounded-lg border border-black/10 dark:border-white/10 text-sm w-full bg-transparent dark:bg-transparent text-black dark:text-white">
                                                <option value="">Pilih Bulan</option>
                                                @for($m=1;$m<=12;$m++) <option value="{{ $m }}" {{ request('bulan')==$m
                                                    ? 'selected' : '' }}>
                                                    {{ \Carbon\Carbon::create()->month($m)->locale('id')->monthName }}
                                                    </option>
                                                    @endfor
                                            </select>
                                            <select name="tahun_bulan"
                                                class="form-select py-2 px-3 rounded-lg border border-black/10 dark:border-white/10 text-sm w-full bg-transparent dark:bg-transparent text-black dark:text-white">
                                                <option value="">Pilih Tahun</option>
                                                @for($y = date('Y')-5; $y <= date('Y'); $y++) <option value="{{ $y }}"
                                                    {{ request('tahun_bulan')==$y ? 'selected' : '' }}>{{ $y }}</option>
                                                    @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div id="filterTahunan"
                                        class="{{ request('periode') == 'tahunan' ? '' : 'hidden' }}">
                                        <select name="tahun_tahun"
                                            class="form-select py-2 px-3 rounded-lg border border-black/10 dark:border-white/10 text-sm w-full bg-transparent dark:bg-transparent text-black dark:text-white">
                                            <option value="">Pilih Tahun</option>
                                            @for($y = date('Y')-5; $y <= date('Y'); $y++) <option value="{{ $y }}" {{
                                                request('tahun_tahun')==$y ? 'selected' : '' }}>{{ $y }}</option>
                                                @endfor
                                        </select>
                                    </div>
                                    <div id="filterRentang"
                                        class="{{ request('periode') == 'rentang' ? '' : 'hidden' }}">
                                        <div class="flex gap-2">
                                            <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}"
                                                class="form-input py-2 px-3 rounded-lg border border-black/10 dark:border-white/10 text-sm w-full bg-transparent dark:bg-transparent text-black dark:text-white"
                                                placeholder="Tanggal Awal">
                                            <input type="date" name="tanggal_akhir"
                                                value="{{ request('tanggal_akhir') }}"
                                                class="form-input py-2 px-3 rounded-lg border border-black/10 dark:border-white/10 text-sm w-full bg-transparent dark:bg-transparent text-black dark:text-white"
                                                placeholder="Tanggal Akhir">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end mt-4">
                                <button type="submit"
                                    class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-300">
                                    Terapkan Filter
                                </button>
                            </div>
                        </form>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                            function showFilterFields() {
                                                var periode = document.getElementById('periodeFilter').value;
                                                document.getElementById('filterBulanan').classList.toggle('hidden', periode !== 'bulanan');
                                                document.getElementById('filterTahunan').classList.toggle('hidden', periode !== 'tahunan');
                                                document.getElementById('filterRentang').classList.toggle('hidden', periode !== 'rentang');
                                            }
                                            document.getElementById('periodeFilter').addEventListener('change', showFilterFields);
                                            showFilterFields();
                                            $('#mitraFilter').select2({
                                                width: '100%',
                                                placeholder: "Cari Mitra...",
                                                allowClear: true
                                            });
                                        });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
    </div>


     @php
        $totalDebit = $data->sum('saldo_debit');
        $totalKredit = $data->sum('saldo_kredit');
    @endphp

<div class="w-full overflow-x-auto">
<div class="responsive-table-scale">
    <table class="min-w-[600px] w-full border dark:border-white/10 text-xs">
        <thead>
            <tr class="bg-gray-100 dark:bg-white/5">
                <th class="border dark:border-white/10 px-2 py-1 text-left w-[20%]">Kode</th>
                <th class="border dark:border-white/10 px-2 py-1 text-left w-[40%]">Akun</th>
                <th class="border dark:border-white/10 px-2 py-1 text-right w-[20%]">Saldo Debit</th>
                <th class="border dark:border-white/10 px-2 py-1 text-right w-[20%]">Saldo Kredit</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $akun)
            <tr>
                <td class="border dark:border-white/10 px-2 py-1">{{ $akun->kode_akun }}</td>
                <td class="border dark:border-white/10 px-2 py-1 truncate">{{ $akun->nama_akun }}</td>
                <td class="border dark:border-white/10 px-2 py-1 text-right">{{ number_format($akun->saldo_debit,0,',','.') }}</td>
                <td class="border dark:border-white/10 px-2 py-1 text-right">{{ number_format($akun->saldo_kredit,0,',','.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center py-4">Tidak ada transaksi bulan ini</td>
            </tr>
            @endforelse

            {{-- Total --}}
            <tr class="bg-gray-100 font-semibold dark:bg-white/5">
                <td colspan="2" class="border dark:border-white/10 px-2 py-2 text-right">Total</td>
                <td class="border dark:border-white/10 px-2 py-2 text-right">{{ number_format($totalDebit,0,',','.') }}</td>
                <td class="border dark:border-white/10 px-2 py-2 text-right">{{ number_format($totalKredit,0,',','.') }}</td>
            </tr>

            {{-- Balance --}}
            <tr class="bg-blue-50 font-bold dark:bg-white/5">
                <td colspan="2" class="border dark:border-white/10 px-2 py-2 text-right">Balance</td>
                <td colspan="2" class="border dark:border-white/10 px-2 py-2 text-center">
                    @if($totalDebit == $totalKredit)
                        ✅ Seimbang
                    @else
                        ⚠️ Tidak Seimbang (Selisih: {{ number_format($totalDebit - $totalKredit,0,',','.') }})
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
</div>
</div>





@endsection
