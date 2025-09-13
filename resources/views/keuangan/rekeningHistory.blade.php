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
   <div class="px-2 py-1 mb-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
    <h2 class="text-lg font-semibold">History Rekening</h2>
    <a href="{{ route('history.cetak-pdf',$id_rekening) }}" target="_blank" 
       class="inline-flex items-center gap-2 bg-red-600 text-white px-3 py-2 rounded-md hover:bg-red-700 transition text-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M6 2a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8.828a2 2 0 0 0-.586-1.414l-4.828-4.828A2 2 0 0 0 13.172 2H6zm7 1.414L19.586 10H15a2 2 0 0 1-2-2V3.414zM8 15h8v2H8v-2zm0-4h8v2H8v-2z"/>
        </svg>
        Cetak PDF
    </a>
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