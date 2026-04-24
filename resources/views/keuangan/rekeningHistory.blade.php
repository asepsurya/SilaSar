@extends('layout.main')
@section('title', 'Riwayat Rekening')
@section('container')

  <div x-data="historyManager()" class="pb-10">
    <!-- Header Section -->
    <div class="px-2 py-1 mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <div class="flex items-center gap-2">
          <a href="{{ route('akun.rekening') }}"
            class="w-8 h-8 rounded-lg border border-black/10 dark:border-white/10 flex items-center justify-center hover:bg-gray-100 dark:hover:bg-white/5 transition-colors">
            <i class="fas fa-arrow-left text-xs text-gray-400"></i>
          </a>
          <h2 class="text-2xl font-black text-black dark:text-white tracking-tight">Riwayat Rekening</h2>
        </div>
        <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mt-1 ml-10">
          Data Mutasi: <span class="text-blue-600 dark:text-blue-400">{{ $id_rekening }}</span>
        </p>
      </div>

      <div class="flex items-center gap-3">
        <button @click="showFilter = true"
          class="px-4 py-2.5 text-xs font-bold text-gray-600 dark:text-gray-300 bg-white dark:bg-white/5 border border-black/10 dark:border-white/10 rounded-xl hover:bg-gray-50 dark:hover:bg-white/10 transition flex items-center gap-2">
          <i class="fas fa-filter"></i>
          <span>Filter Data</span>
        </button>
        <a href="{{ route('history.cetak-pdf', $id_rekening) . '?' . http_build_query(request()->query()) }}"
          target="_blank"
          class="px-5 py-2.5 text-xs font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-lg shadow-red-500/20 transition duration-150 border-b-4 border-red-800 active:border-b-0 active:translate-y-1 flex items-center gap-2">
          <i class="fas fa-file-pdf"></i>
          <span>Cetak laporan</span>
        </a>
      </div>
    </div>

    <!-- Stats Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
      <div class="bg-white dark:bg-white/5 border border-black/5 dark:border-white/10 rounded-2xl p-6 shadow-sm">
        <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-1">Saldo Akhir</p>
        <h3 class="text-xl font-black text-black dark:text-white tracking-tight">
          Rp{{ number_format($histories->last()->saldo ?? 0, 0, ',', '.') }}
        </h3>
      </div>
      <div class="bg-white dark:bg-white/5 border border-black/5 dark:border-white/10 rounded-2xl p-6 shadow-sm">
        <p class="text-[10px] font-black uppercase text-green-500 tracking-widest mb-1">Total Debit (+)</p>
        <h3 class="text-xl font-black text-green-600 dark:text-green-400 tracking-tight">
          Rp{{ number_format($histories->sum('debit'), 0, ',', '.') }}
        </h3>
      </div>
      <div class="bg-white dark:bg-white/5 border border-black/5 dark:border-white/10 rounded-2xl p-6 shadow-sm">
        <p class="text-[10px] font-black uppercase text-red-500 tracking-widest mb-1">Total Kredit (-)</p>
        <h3 class="text-xl font-black text-red-600 dark:text-red-400 tracking-tight">
          Rp{{ number_format($histories->sum('kredit'), 0, ',', '.') }}
        </h3>
      </div>
      <div class="bg-white dark:bg-white/5 border border-black/5 dark:border-white/10 rounded-2xl p-6 shadow-sm">
        <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-1">Total Mutasi</p>
        <h3 class="text-xl font-black text-black dark:text-white tracking-tight">
          {{ $histories->count() }} <span class="text-[10px] text-gray-400 uppercase ml-1">Baris</span>
        </h3>
      </div>
    </div>

    <!-- Multi-Column List View -->
    <div
      class="bg-white dark:bg-white/5 border border-black/5 dark:border-white/10 rounded-2xl shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left">
          <thead>
            <tr class="bg-gray-50/50 dark:bg-white/5 border-b border-black/5 dark:border-white/5 text-gray-400 uppercase">
              <th class="px-6 py-4 text-[10px] font-black tracking-widest w-16">No</th>
              <th class="px-6 py-4 text-[10px] font-black tracking-widest">Waktu & Transaksi</th>
              <th class="px-6 py-4 text-[10px] font-black tracking-widest text-right">Debit (+)</th>
              <th class="px-6 py-4 text-[10px] font-black tracking-widest text-right">Kredit (-)</th>
              <th class="px-6 py-4 text-[10px] font-black tracking-widest text-right">Saldo</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-black/5 dark:divide-white/5">
            @forelse($histories as $index => $history)
              <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group cursor-pointer"
                @click="showDetail('{{ $history->keterangan }}', '{{ $history->tanggal }}', 'Rp{{ number_format($history->debit, 0, ',', '.') }}', 'Rp{{ number_format($history->kredit, 0, ',', '.') }}', 'Rp{{ number_format($history->saldo, 0, ',', '.') }}')">
                <td class="px-6 py-4 text-xs font-bold text-gray-400">{{ $index + 1 }}</td>
                <td class="px-6 py-4">
                  <div class="flex flex-col">
                    <span
                      class="text-[10px] text-blue-600 dark:text-blue-400 font-black uppercase">{{ $history->tanggal }}</span>
                    <span
                      class="font-bold text-black dark:text-white text-sm leading-tight mt-0.5 line-clamp-1">{{ $history->keterangan }}</span>
                  </div>
                </td>
                <td class="px-6 py-4 text-right">
                  <span
                    class="text-sm font-bold {{ $history->debit > 0 ? 'text-green-600 dark:text-green-400' : 'text-gray-300 dark:text-gray-700' }}">
                    {{ $history->debit > 0 ? '+Rp' . number_format($history->debit, 0, ',', '.') : '-' }}
                  </span>
                </td>
                <td class="px-6 py-4 text-right">
                  <span
                    class="text-sm font-bold {{ $history->kredit > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-300 dark:text-gray-700' }}">
                    {{ $history->kredit > 0 ? '-Rp' . number_format($history->kredit, 0, ',', '.') : '-' }}
                  </span>
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="flex flex-col items-end">
                    <span class="text-sm font-black text-black dark:text-white tracking-tight">
                      Rp{{ number_format($history->saldo, 0, ',', '.') }}
                    </span>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="py-20 text-center">
                  <div class="flex flex-col items-center">
                    <div class="w-16 h-16 bg-gray-50 dark:bg-white/5 rounded-full flex items-center justify-center mb-4">
                      <i class="fas fa-history fa-2x text-gray-200"></i>
                    </div>
                    <h4 class="text-sm font-bold text-black dark:text-white tracking-tight uppercase">Belum Ada Mutasi</h4>
                    <p class="text-[10px] text-gray-400 mt-1 uppercase font-bold tracking-widest">Rekening ini belum
                      memiliki catatan transaksi.</p>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- Filter Modal -->
    <div x-show="showFilter" x-cloak
      class="fixed inset-0 bg-black/60 dark:bg-black/80 z-[1000] flex items-center justify-center p-4">
      <div
        class="bg-white dark:bg-zinc-900 w-full max-w-md rounded-2xl shadow-2xl overflow-hidden transform transition-all"
        @click.away="showFilter = false">
        <div
          class="px-6 py-4 border-b border-gray-100 dark:border-white/5 flex items-center justify-between bg-gray-50/50 dark:bg-white/5">
          <div>
            <h3 class="text-lg font-black text-black dark:text-white tracking-tight italic">Saring Data Riwayat</h3>
            <p class="text-[9px] text-gray-400 uppercase tracking-widest font-black mt-0.5">Filter berdasarkan periode
              waktu</p>
          </div>
          <button type="button" @click="showFilter = false"
            class="text-gray-400 hover:text-red-600 transition-colors p-2">
            <i class="fas fa-times fa-lg"></i>
          </button>
        </div>

        <form method="GET" class="p-6 space-y-5">
          <div class="py-4 px-5 bg-white dark:bg-white/5 rounded-2xl border border-black/10 relative">
            <label
              class="block mb-1 text-[10px] text-black/40 dark:text-white/40 uppercase tracking-widest font-black italic">Mode
              Periode</label>
            <select name="periode" x-model="filterMode"
              class="w-full bg-transparent font-bold text-sm focus:outline-none dark:text-white">
              <option value="">Semua Waktu</option>
              <option value="bulanan">📅 Per Bulan</option>
              <option value="tahunan">🗓️ Per Tahun</option>
              <option value="rentang">📏 Rentang Khusus</option>
            </select>
          </div>

          <div x-show="filterMode == 'bulanan'" class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
              <div class="py-4 px-5 bg-white dark:bg-white/5 rounded-2xl border border-black/10">
                <label
                  class="block mb-1 text-[10px] text-black/40 dark:text-white/40 uppercase tracking-widest font-black">Bulan</label>
                <select name="bulan" class="w-full bg-transparent font-bold text-sm focus:outline-none dark:text-white">
                  @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                      {{ \Carbon\Carbon::create()->month($m)->locale('id')->monthName }}
                    </option>
                  @endfor
                </select>
              </div>
              <div class="py-4 px-5 bg-white dark:bg-white/5 rounded-2xl border border-black/10">
                <label
                  class="block mb-1 text-[10px] text-black/40 dark:text-white/40 uppercase tracking-widest font-black">Tahun</label>
                <select name="tahun_bulan"
                  class="w-full bg-transparent font-bold text-sm focus:outline-none dark:text-white">
                  @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                    <option value="{{ $y }}" {{ request('tahun_bulan') == $y ? 'selected' : '' }}>{{ $y }}</option>
                  @endfor
                </select>
              </div>
            </div>
          </div>

          <div x-show="filterMode == 'tahunan'"
            class="py-4 px-5 bg-white dark:bg-white/5 rounded-2xl border border-black/10">
            <label
              class="block mb-1 text-[10px] text-black/40 dark:text-white/40 uppercase tracking-widest font-black">Pilih
              Tahun</label>
            <select name="tahun_tahun" class="w-full bg-transparent font-bold text-sm focus:outline-none dark:text-white">
              @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                <option value="{{ $y }}" {{ request('tahun_tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
              @endfor
            </select>
          </div>

          <div x-show="filterMode == 'rentang'" class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
              <div class="py-4 px-5 bg-white dark:bg-white/5 rounded-2xl border border-black/10">
                <label
                  class="block mb-1 text-[10px] text-black/40 dark:text-white/40 uppercase tracking-widest font-black">Tgl
                  Mulai</label>
                <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}"
                  class="w-full bg-transparent font-bold text-sm focus:outline-none dark:text-white" />
              </div>
              <div class="py-4 px-5 bg-white dark:bg-white/5 rounded-2xl border border-black/10">
                <label
                  class="block mb-1 text-[10px] text-black/40 dark:text-white/40 uppercase tracking-widest font-black">Tgl
                  Akhir</label>
                <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}"
                  class="w-full bg-transparent font-bold text-sm focus:outline-none dark:text-white" />
              </div>
            </div>
          </div>

          <div class="flex gap-4 pt-2">
            <button type="button" @click="showFilter = false"
              class="flex-1 px-6 py-3 text-[10px] font-black text-gray-500 uppercase tracking-widest hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-colors">Tutup</button>
            <button type="submit"
              class="flex-[2] px-8 py-3 text-[10px] font-black text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-lg shadow-blue-500/20 transition duration-150 border-b-4 border-blue-800 active:border-b-0 active:translate-y-1 uppercase tracking-widest">Terapkan
              Filter</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Detail Row Modal -->
    <div x-show="showRowDetail" x-cloak
      class="fixed inset-0 bg-black/60 dark:bg-black/80 z-[1001] flex items-center justify-center p-4">
      <div class="bg-white dark:bg-zinc-900 w-full max-w-sm rounded-2xl shadow-2xl overflow-hidden p-6"
        @click.away="showRowDetail = false">
        <h4
          class="text-sm font-black text-black dark:text-white uppercase tracking-widest border-b border-black/5 dark:border-white/5 pb-3 mb-4">
          Rincian Mutasi</h4>
        <div class="space-y-4">
          <div class="flex justify-between">
            <span class="text-[10px] text-gray-400 font-bold uppercase">Tanggal</span>
            <span class="text-xs font-black text-black dark:text-white" x-text="detailData.date"></span>
          </div>
          <div class="flex flex-col gap-1">
            <span class="text-[10px] text-gray-400 font-bold uppercase">Keterangan</span>
            <span class="text-xs font-medium text-black dark:text-white leading-relaxed" x-text="detailData.desc"></span>
          </div>
          <div class="grid grid-cols-2 gap-4 pt-2">
            <div class="bg-green-50 dark:bg-green-900/10 p-3 rounded-xl border border-green-100 dark:border-green-800/20">
              <span class="block text-[8px] font-black text-green-500 uppercase tracking-widest mb-1">Debit (+)</span>
              <span class="text-xs font-black text-green-600 dark:text-green-400" x-text="detailData.debit"></span>
            </div>
            <div class="bg-red-50 dark:bg-red-900/10 p-3 rounded-xl border border-red-100 dark:border-red-800/20">
              <span class="block text-[8px] font-black text-red-500 uppercase tracking-widest mb-1">Kredit (-)</span>
              <span class="text-xs font-black text-red-600 dark:text-red-400" x-text="detailData.kredit"></span>
            </div>
          </div>
          <div class="bg-gray-50 dark:bg-white/5 p-4 rounded-xl border border-black/5 dark:border-white/10 mt-2">
            <span class="block text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Saldo Setelah
              Transaksi</span>
            <span class="text-lg font-black text-black dark:text-white italic tracking-tighter"
              x-text="detailData.balance"></span>
          </div>
        </div>
        <button @click="showRowDetail = false"
          class="w-full mt-6 py-3 bg-black dark:bg-white dark:text-black text-white text-[10px] font-black uppercase tracking-[2px] rounded-xl hover:opacity-90 transition-opacity">Tutup
          Panel</button>
      </div>
    </div>
  </div>

  <script>
    function historyManager() {
      return {
        showFilter: false,
        filterMode: "{{ request('periode') }}",
        showRowDetail: false,
        detailData: {
          desc: '',
          date: '',
          debit: '',
          kredit: '',
          balance: ''
        },

        showDetail(desc, date, debit, kredit, balance) {
          this.detailData = { desc, date, debit, kredit, balance };
          this.showRowDetail = true;
        }
      }
    }
  </script>

@endsection