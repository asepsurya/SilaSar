@extends('layout.main')
@section('title', 'Data Akun')
@section('container')
<style>
     @media (max-width: 768px){
           .p-7{
            padding: 9px;
        }
    }
    @media (max-width: 768px) {
  .table-wrapper {
    transform: scale(0.85);   /* Zoom out */
    transform-origin: top left;
    width: 117%;             /* Supaya tetap full */
  }
}
</style>
<div class="px-2 py-1 mb-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
    <!-- Kiri: Judul -->
    <h2 class="text-lg font-semibold">History Rekening</h2>

    <!-- Kanan: Tombol -->
    <div class="flex flex-row items-center gap-2 ml-auto">
        <button type="button" style="width: 30%;" 
            @click="window.dispatchEvent(new CustomEvent('filter'))"
            class="px-3 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center gap-2">
            <span>Filter</span>
        </button>

        <a href="{{ route('history.cetakpdf.harian', $id_rekening) }}" target="_blank"
            class="inline-flex items-center gap-2 bg-red-600 text-white px-3 py-2 rounded-md hover:bg-red-700 transition text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M6 2a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8.828a2 2 0 0 0-.586-1.414l-4.828-4.828A2 2 0 0 0 13.172 2H6zm7 1.414L19.586 10H15a2 2 0 0 1-2-2V3.414zM8 15h8v2H8v-2zm0-4h8v2H8v-2z"/>
            </svg>
            Cetak PDF
        </a>
    </div>
</div>

 <div x-data="{ open: false }" @filter.window="open = true" @close-modal.window="open = false">
            <!-- Overlay -->
            <div
                class="fixed inset-0 bg-black/60 dark:bg-white/10 z-[999] hidden overflow-y-auto"
                :class="{ 'block': open, 'hidden': !open }"
            >
                <div class="flex items-center justify-center min-h-screen px-4" @click.self="open = false">
                    <!-- Modal Box -->
                    <div
                        x-show="open"
                        x-transition
                        x-transition.duration.300
                        class="bg-white dark:bg-black relative shadow-3xl border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8"
                        style="display: none;"
                    >
                        <!-- Header -->
                        <div class="flex bg-white dark:bg-black border-b border-black/10 dark:border-white/10 items-center justify-between px-5 py-3">
                            <h5 class="font-semibold text-lg">Filter</h5>
                            <button
                                type="button"
                                class="text-black/40 dark:text-white/40 hover:text-black dark:hover:text-white"
                                @click="open = false"
                            >
                                <svg class="w-5 h-5" width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M24.2929 6.29289L6.29289 24.2929C6.10536 24.4804 6 24.7348 6 25C6 25.2652 6.10536 25.5196 6.29289 25.7071C6.48043 25.8946 6.73478 26 7 26C7.26522 26 7.51957 25.8946 7.70711 25.7071L25.7071 7.70711C25.8946 7.51957 26 7.26522 26 7C26 6.73478 25.8946 6.48043 25.7071 6.29289C25.5196 6.10536 25.2652 6 25 6C24.7348 6 24.4804 6.10536 24.2929 6.29289Z" fill="currentcolor" />
                                    <path d="M7.70711 6.29289C7.51957 6.10536 7.26522 6 7 6C6.73478 6 6.48043 6.10536 6.29289 6.29289C6.10536 6.48043 6 6.73478 6 7C6 7.26522 6.10536 7.51957 6.29289 7.70711L24.2929 25.7071C24.4804 25.8946 24.7348 26 25 26C25.2652 26 25.5196 25.8946 25.7071 25.7071C25.8946 25.5196 26 25.2652 26 25C26 24.7348 25.8946 24.4804 25.7071 24.2929L7.70711 6.29289Z" fill="currentcolor" />
                                </svg>
                            </button>
                        </div>
                        <div class="p-5">
                              <form method="GET"  class="flex flex-col gap-5">


                                        <!-- Filter Waktu -->
                                        <div>
                                            <label class="block text-xs font-semibold text-black/60 dark:text-white/60 mb-1">Periode Waktu</label>
                                            <div class="flex flex-col gap-2">
                                                <select name="periode" id="periodeFilter"
                                                    class="form-select py-2 px-4 rounded-lg border border-black/10 dark:border-white/10 text-sm w-full bg-transparent dark:bg-transparent text-black dark:text-white">
                                                    <option value="">Semua Waktu</option>
                                                    <option value="bulanan" {{ request('periode') == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                                                    <option value="tahunan" {{ request('periode') == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                                                    <option value="rentang" {{ request('periode') == 'rentang' ? 'selected' : '' }}>Rentang Tanggal</option>
                                                </select>
                                                <div id="filterBulanan" class="{{ request('periode') == 'bulanan' ? '' : 'hidden' }}">
                                                    <div class="flex gap-2">
                                                        <select name="bulan" class="form-select py-2 px-3 rounded-lg border border-black/10 dark:border-white/10 text-sm w-full bg-transparent dark:bg-transparent text-black dark:text-white">
                                                            <option value="">Pilih Bulan</option>
                                                            @for($m=1;$m<=12;$m++)
                                                                <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                                                                    {{ \Carbon\Carbon::create()->month($m)->locale('id')->monthName }}
                                                                </option>
                                                            @endfor
                                                        </select>
                                                        <select name="tahun_bulan" class="form-select py-2 px-3 rounded-lg border border-black/10 dark:border-white/10 text-sm w-full bg-transparent dark:bg-transparent text-black dark:text-white">
                                                            <option value="">Pilih Tahun</option>
                                                            @for($y = date('Y')-5; $y <= date('Y'); $y++)
                                                                <option value="{{ $y }}" {{ request('tahun_bulan') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                                <div id="filterTahunan" class="{{ request('periode') == 'tahunan' ? '' : 'hidden' }}">
                                                    <select name="tahun_tahun" class="form-select py-2 px-3 rounded-lg border border-black/10 dark:border-white/10 text-sm w-full bg-transparent dark:bg-transparent text-black dark:text-white">
                                                        <option value="">Pilih Tahun</option>
                                                        @for($y = date('Y')-5; $y <= date('Y'); $y++)
                                                            <option value="{{ $y }}" {{ request('tahun_tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <div id="filterRentang" class="{{ request('periode') == 'rentang' ? '' : 'hidden' }}">
                                                    <div class="flex gap-2">
                                                        <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}"
                                                            class="form-input py-2 px-3 rounded-lg border border-black/10 dark:border-white/10 text-sm w-full bg-transparent dark:bg-transparent text-black dark:text-white"
                                                            placeholder="Tanggal Awal">
                                                        <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}"
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
<div class="border bg-white dark:bg-white/5 dark:border-white/10 border-black/10 p-4 rounded-md">
    <p class="text-sm font-semibold text-gray-500 mb-3">Riwayat Transaksi</p>

    <div id="saldo-akhir" class="mt-3 flex justify-between items-center font-bold mb-5">
        <span>Saldo Akhir:</span>
        <span id="saldo-value"></span>
    </div>


<script>
  document.addEventListener("DOMContentLoaded", () => {
    const rows = document.querySelectorAll("table tbody tr");
    if (rows.length > 0) {
      const lastRow = rows[rows.length - 1];
      const saldoCell = lastRow.cells[5]; // kolom ke-6 = saldo
      document.getElementById("saldo-value").innerText = 'Rp.' + saldoCell.innerText;
    }
  });
</script>


  <div class="w-full overflow-x-auto">
  <div class="table-wrapper">
    <table class="border text-sm md:text-base w-full">
      <thead class="bg-gray-100">
        <tr>
          <th class="px-2 py-1 border">No</th>
          <th class="px-2 py-1 border">Tanggal</th>
          <th class="px-2 py-1 border">Keterangan</th>
          <th class="px-2 py-1 border">Debit</th>
          <th class="px-2 py-1 border">Kredit</th>
          <th class="px-2 py-1 border">Saldo</th>
        </tr>
      </thead>
      <tbody>
        @foreach($histories as $index => $history)
        <tr>
          <td class="px-2 py-1 border text-center">{{ $index+1 }}</td>
          <td class="px-2 py-1 border whitespace-nowrap">{{ $history->tanggal }}</td>
          <td class="px-2 py-1 border">{{ $history->keterangan }}</td>
          <td class="px-2 py-1 border text-right">{{ number_format($history->debit,0,',','.') }}</td>
          <td class="px-2 py-1 border text-right">{{ number_format($history->kredit,0,',','.') }}</td>
          <td class="px-2 py-1 border text-right font-semibold">{{ number_format($history->saldo,0,',','.') }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
<style>
  /* Sembunyikan kolom lain di layar kecil */
  @media (max-width: 768px) {
    table thead th:nth-child(1),
    table thead th:nth-child(4),
    table thead th:nth-child(5),
    table thead th:nth-child(6),
    table tbody td:nth-child(1),
    table tbody td:nth-child(4),
    table tbody td:nth-child(5),
    table tbody td:nth-child(6) {
      display: none;
    }

    /* Biar keterangan panjang jadi ... */
    table tbody td:nth-child(3) {
      max-width: 120px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
      cursor: pointer;
    }
  }
</style>

<!-- Modal -->
<div id="detail-modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-lg p-5 w-80">
    <h3 class="text-lg font-semibold mb-3">Detail Transaksi</h3>
    <div id="modal-content"></div>
    <button class="mt-4 bg-red-600 text-white px-4 py-2 rounded w-full"
            onclick="closeModal()">Tutup</button>
  </div>
</div>

<script>
  document.querySelectorAll("table tbody tr").forEach(row => {
    const keteranganCell = row.cells[2]; // kolom ke-3 = keterangan
    keteranganCell?.addEventListener("click", () => {
      if (window.innerWidth <= 768) { // hanya aktif di mobile
        const cells = row.cells;
        document.getElementById("modal-content").innerHTML = `
          <p><strong>No:</strong> ${cells[0].innerText}</p>
          <p><strong>Tanggal:</strong> ${cells[1].innerText}</p>
          <p><strong>Keterangan:</strong> ${cells[2].innerText}</p>
          <p><strong>Debit:</strong> ${cells[3].innerText}</p>
          <p><strong>Kredit:</strong> ${cells[4].innerText}</p>
          <p><strong>Saldo:</strong> ${cells[5].innerText}</p>
        `;
        document.getElementById("detail-modal").classList.remove("hidden");
      }
    });
  });

  function closeModal() {
    document.getElementById("detail-modal").classList.add("hidden");
  }
</script>

</div>

@endsection
