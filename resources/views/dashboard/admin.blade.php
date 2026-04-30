@extends('layout.main')
@section('title', 'Dashboard Penjualan')
@section('css')
  <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('assets/css/simple-datatables.css') }}" />
@endsection
@section('container')
  @php
    date_default_timezone_set('Asia/Jakarta');
    $jam = date('H');
    if ($jam >= 5 && $jam < 12) {
      $ucapan = 'Selamat Pagi';
      $icon = '☀️'; // atau bisa SVG-nya
    } elseif ($jam >= 12 && $jam < 18) {
      $ucapan = 'Selamat Siang';
      $icon = '🌤️';
    } elseif ($jam >= 18 && $jam < 22) {
      $ucapan = 'Selamat Malam';
      $icon = '🌙';
    } else {
      $ucapan = 'Selamat Malam';
      $icon = '🌙';
    }
  @endphp

  <div
    class="flex items-center rounded bg-lightgreen-100/50 dark:bg-lightgreen-100 p-3 text-black/80 dark:text-black mb-4">
    <span class="pr-2">
      <span class="pr-2">
        <span class="ml-1">{{ $icon }}</span> <span class="font-semibold">{{ $ucapan }},</span>
        {{ auth()->user()->name }}!
        <span class="text-xs text-black/60 dark:text-white/60"></span>
      </span>
    </span>
  </div>
  <div x-data="{ showSaldo: false }">
    <div class="flex items-center justify-between mb-6">
      <div class="flex items-center gap-3">
        <h2 class="text-lg sm:text-xl font-bold">Ringkasan Penjualan</h2>
        <button @click="showSaldo = !showSaldo"
          class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none transition-colors">
          <svg x-show="!showSaldo" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
            </path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
            </path>
          </svg>
          <svg x-show="showSaldo" style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.978 9.978 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
            </path>
          </svg>
        </button>
      </div>
      <a href="/transaksi"
        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
        Lihat Transaksi
        <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
        </svg>
      </a>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-7 mb-4">
      <div class="bg-lightblue-100 rounded-2xl p-6">
        <p class="text-sm font-semibold text-black mb-2">Total Transaksi</p>
        <div class="flex items-center justify-between">
          <h2 class="text-2xl leading-9 font-semibold text-black">
            <span x-show="showSaldo">{{ $transaksi->count() }}</span>
            <span x-show="!showSaldo">{{ str_repeat('*', strlen((string) $transaksi->count())) }}</span>
          </h2>
          <div class="flex items-center gap-1">
            <p class="text-xs leading-[18px] text-black">Transaksi</p>
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" clip-rule="evenodd"
                d="M8.45488 5.60777L14 4L12.6198 9.6061L10.898 7.9532L8.12069 10.8463C8.02641 10.9445 7.89615 11 7.76 11C7.62385 11 7.49359 10.9445 7.39931 10.8463L5.36 8.72199L2.36069 11.8463C2.16946 12.0455 1.85294 12.0519 1.65373 11.8607C1.45453 11.6695 1.44807 11.3529 1.63931 11.1537L4.99931 7.65373C5.09359 7.55552 5.22385 7.5 5.36 7.5C5.49615 7.5 5.62641 7.55552 5.72069 7.65373L7.76 9.77801L10.1766 7.26067L8.45488 5.60777Z"
                fill="#1C1C1C"></path>
            </svg>
          </div>
        </div>
      </div>
      <div class="bg-lightpurple-100 rounded-2xl p-6">
        <p class="text-sm font-semibold text-black mb-2">Total Pembayaran Diterima</p>
        <div class="flex items-center justify-between">
          <h2 class="text-2xl leading-9 font-semibold text-black">
            <span x-show="showSaldo">Rp.{{ number_format($totalTransaksi, 0, ',', '.') }}</span>
            <span
              x-show="!showSaldo">Rp.{{ preg_replace('/[0-9]/', '*', number_format($totalTransaksi, 0, ',', '.')) }}</span>
          </h2>

        </div>
      </div>
      <div class="bg-lightblue-100 rounded-2xl p-6">
        <p class="text-sm font-semibold text-black mb-2">Total Barang diluar</p>
        <div class="flex items-center justify-between">
          <h2 class="text-2xl leading-9 font-semibold text-black">
            <span x-show="showSaldo">Rp.{{ number_format($totalTransaksiluar, 0, ',', '.') }}</span>
            <span
              x-show="!showSaldo">Rp.{{ preg_replace('/[0-9]/', '*', number_format($totalTransaksiluar, 0, ',', '.')) }}</span>
          </h2>

        </div>
      </div>

    </div>
  </div>
  <div class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-5">
      <!-- Keuntungan & Kerugian -->
      <div class="lg:col-span-2 bg-white dark:bg-transparent rounded">
        <h2 class="text-lg font-semibold mb-3">Keuntungan & Kerugian per Bulan</h2>
        <canvas id="chartProfitLoss" class="w-full h-64"></canvas>
      </div>

      <!-- Stok Hampir Habis -->
      <div
        class="bg-white dark:bg-transparent p-4 sm:p-6 rounded-2xl shadow-md border dark:border-white/10 flex flex-col h-full">
        <h2 class="text-lg font-semibold mb-3 text-black dark:text-white flex items-center gap-2">
          <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
            </path>
          </svg>
          Produk Stok Hampir Habis
        </h2>
        @if($produk_habis->isEmpty())
          <p class="text-sm text-gray-500 dark:text-gray-400 m-auto text-center py-4">Semua stok produk aman.</p>
        @else
          <ul class="space-y-3 flex-1 overflow-y-auto pr-1">
            @foreach($produk_habis as $p)
              <li
                class="flex items-center justify-between p-3 mb-3 rounded-xl bg-red-50/50 dark:bg-red-900/10 border border-red dark:border-white/10 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                <div class="flex items-center gap-3 overflow-hidden">
                  <div class="w-10 h-10 rounded-lg overflow-hidden flex-shrink-0 border border-black/5 dark:border-white/10">
                    <img src="{{ $p->first_image ? asset('storage/' . $p->first_image) : asset('assets/images/404.jpg') }}"
                      alt="{{ $p->nama_produk }}" class="w-full h-full object-cover"
                      onerror="this.onerror=null;this.src='{{ asset('assets/images/404.jpg') }}';" />
                  </div>
                  <div class="min-w-0">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate" title="{{ $p->nama_produk }}">
                      {{ $p->nama_produk }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $p->kode_produk }}</p>
                  </div>
                </div>
                <div class="text-right pl-2 flex-shrink-0">
                  <span
                    class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold {{ $p->stok <= 5 ? 'bg-red text-red-700 dark:bg-red-500/20 dark:text-red-400' : 'bg-orange-100 text-orange-700 dark:bg-orange-500/20 dark:text-orange-400' }}">
                    Sisa {{ $p->stok }}
                  </span>
                </div>
              </li>
            @endforeach
          </ul>
          <div class="mt-4 text-center pt-2 border-t border-gray-100 dark:border-white/10">
            <a href="{{ route('manajemenStok.index') }}"
              class="mt-2 text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 flex items-center justify-center gap-1">
              Kelola Stok
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
              </svg>
            </a>
          </div>
        @endif
      </div>
    </div>

    <!-- Chart Status & Chart Mitra (2 kolom) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="bg-lightwhite dark:bg-white/5 p-4 sm:p-6 rounded-2xl flex flex-col items-center shadow-md">
        <h2 class="text-lg font-semibold mb-3">Status Pembayaran</h2>
        <canvas id="chartStatus" class="w-full h-64"></canvas>
      </div>
      <div class="bg-lightwhite dark:bg-white/5 p-4 sm:p-6 rounded-2xl flex flex-col items-center shadow-md">
        <h2 class="text-lg font-semibold mb-3">Transaksi per Mitra</h2>
        <canvas id="chartMitra" class="w-full h-64"></canvas>
      </div>
    </div>

    <!-- Chart Transaksi (Full Width) -->
    <div class="bg-white dark:bg-transparent p-4 rounded ">
      <h2 class="text-lg font-semibold mb-3">Transaksi per Bulan</h2>
      <canvas id="chartTransaksi" class="w-full h-64"></canvas>
    </div>

  </div>


  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <script>
    // Data dari Laravel
    const bulanTransaksi = {!! json_encode(array_keys($transaksiPerBulan->toArray())) !!};
    const totalTransaksi = {!! json_encode(array_values($transaksiPerBulan->toArray())) !!};

    const statusLabel = {!! json_encode(array_keys($statusBayar->toArray())) !!};
    const statusTotal = {!! json_encode(array_values($statusBayar->toArray())) !!};

    const mitraLabel = {!! json_encode(array_keys($mitra->toArray())) !!};
    const mitraTotal = {!! json_encode(array_values($mitra->toArray())) !!};

    const bulanProfit = {!! json_encode(array_keys($keuntungan->toArray())) !!};
    const keuntungan = {!! json_encode(array_values($keuntungan->toArray())) !!};
    const kerugian = {!! json_encode(array_values($kerugian->toArray())) !!};

    // Helper: fallback data kalau kosong / semua nol
    function safeChartData(labels, data, defaultLabel = 'Tidak ada data') {
      if (!data || data.length === 0 || data.every(v => v === 0)) {
        return {
          labels: [defaultLabel],
          data: [1],
          colors: ['#9ca3af'], // abu-abu
          empty: true
        };
      }
      return {
        labels: labels,
        data: data,
        colors: null,
        empty: false
      };
    }

    // Plugin: text di tengah chart
    const centerTextPlugin = {
      id: 'centerText',
      afterDraw(chart, args, options) {
        if (options.show && options.text) {
          const { ctx, chartArea: { left, right, top, bottom } } = chart;
          ctx.save();
          ctx.font = 'bold 14px sans-serif';
          ctx.fillStyle = '#6b7280';
          ctx.textAlign = 'center';
          ctx.textBaseline = 'middle';
          ctx.fillText(options.text, (left + right) / 2, (top + bottom) / 2);
          ctx.restore();
        }
      }
    };

    // Chart Transaksi
    new Chart(document.getElementById('chartTransaksi'), {
      type: 'line',
      data: {
        labels: bulanTransaksi,
        datasets: [{
          label: 'Jumlah Transaksi',
          data: totalTransaksi,
          tension: 0.4,
          fill: true,
          backgroundColor: 'rgba(59, 130, 246, 0.15)',
          borderColor: 'rgba(59, 130, 246, 1)',
          borderWidth: 2,
          pointBackgroundColor: '#ffffff',
          pointBorderColor: 'rgba(59, 130, 246, 1)',
          pointBorderWidth: 2,
          pointRadius: 0,
          pointHoverRadius: 6
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false },
          tooltip: { mode: 'index', intersect: false, backgroundColor: 'rgba(0,0,0,0.8)', padding: 12, cornerRadius: 8, titleFont: { size: 13 }, bodyFont: { size: 13, weight: 'bold' } }
        },
        interaction: { mode: 'nearest', axis: 'x', intersect: false },
        scales: {
          x: { grid: { display: false } },
          y: { grid: { color: 'rgba(0, 0, 0, 0.05)', borderDash: [5, 5] }, beginAtZero: true, border: { display: false } }
        }
      }
    });

    // Chart Status (Doughnut) dengan fallback
    let statusSafe = safeChartData(statusLabel, statusTotal, 'Belum ada status');
    new Chart(document.getElementById('chartStatus'), {
      type: 'doughnut',
      data: {
        labels: statusSafe.labels,
        datasets: [{
          data: statusSafe.data,
          backgroundColor: statusSafe.colors || ['#22c55e', '#facc15', '#ef4444']
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
          legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8, padding: 20 } },
          tooltip: {
            enabled: !statusSafe.empty,
            backgroundColor: 'rgba(0,0,0,0.8)',
            padding: 12,
            cornerRadius: 8
          },
          centerText: {
            show: statusSafe.empty,
            text: 'Belum ada status'
          }
        }
      },
      plugins: [centerTextPlugin]
    });

    // Chart Mitra (Doughnut) dengan fallback & warna yang lebih bervariasi
    const colorPalette = [
      '#6366f1', '#ec4899', '#f97316', '#10b981', '#3b82f6',
      '#8b5cf6', '#ef4444', '#eab308', '#06b6d4', '#14b8a6',
      '#f43f5e', '#84cc16', '#a855f7', '#f59e0b', '#22c55e',
      '#0ea5e9', '#d946ef', '#64748b', '#ef4444', '#3b82f6'
    ];
    let mitraSafe = safeChartData(mitraLabel, mitraTotal, 'Belum ada mitra');
    let mitraColors = mitraSafe.colors;
    if (!mitraColors && mitraSafe.labels.length > 0) {
      mitraColors = mitraSafe.labels.map((_, i) => colorPalette[i % colorPalette.length]);
    }

    new Chart(document.getElementById('chartMitra'), {
      type: 'doughnut',
      data: {
        labels: mitraSafe.labels,
        datasets: [{
          data: mitraSafe.data,
          backgroundColor: mitraColors,
          borderWidth: 2,
          borderColor: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        cutout: '60%',
        plugins: {
          legend: { display: false },
          tooltip: {
            enabled: !mitraSafe.empty,
            backgroundColor: 'rgba(0,0,0,0.8)',
            padding: 12,
            cornerRadius: 8,
            bodyFont: { size: 13, weight: 'bold' }
          },
          centerText: {
            show: mitraSafe.empty,
            text: 'Belum ada mitra'
          }
        }
      },
      plugins: [centerTextPlugin]
    });

    // Chart Keuntungan & Kerugian
    new Chart(document.getElementById('chartProfitLoss'), {
      type: 'line',
      data: {
        labels: bulanProfit,
        datasets: [
          {
            label: 'Keuntungan',
            data: keuntungan,
            tension: 0.4,
            fill: true,
            backgroundColor: 'rgba(16, 185, 129, 0.15)',
            borderColor: 'rgba(16, 185, 129, 1)',
            borderWidth: 2,
            pointBackgroundColor: '#ffffff',
            pointBorderColor: 'rgba(16, 185, 129, 1)',
            pointBorderWidth: 2,
            pointRadius: 0,
            pointHoverRadius: 6
          },
          {
            label: 'Kerugian',
            data: kerugian,
            tension: 0.4,
            fill: true,
            backgroundColor: 'rgba(239, 68, 68, 0.15)',
            borderColor: 'rgba(239, 68, 68, 1)',
            borderWidth: 2,
            pointBackgroundColor: '#ffffff',
            pointBorderColor: 'rgba(239, 68, 68, 1)',
            pointBorderWidth: 2,
            pointRadius: 0,
            pointHoverRadius: 6
          }
        ]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { position: 'top', labels: { usePointStyle: true, boxWidth: 8, font: { weight: 'bold' } } },
          tooltip: {
            mode: 'index',
            intersect: false,
            backgroundColor: 'rgba(0,0,0,0.8)',
            padding: 12,
            cornerRadius: 8,
            titleFont: { size: 13 },
            bodyFont: { size: 13, weight: 'bold' },
            callbacks: {
              label: function (context) {
                return context.dataset.label + ': Rp ' + context.raw.toLocaleString('id-ID');
              }
            }
          }
        },
        interaction: { mode: 'nearest', axis: 'x', intersect: false },
        scales: {
          x: { grid: { display: false } },
          y: {
            grid: { color: 'rgba(0, 0, 0, 0.05)', borderDash: [5, 5] },
            beginAtZero: true,
            border: { display: false },
            ticks: {
              callback: function (value) {
                return 'Rp ' + value.toLocaleString('id-ID');
              }
            }
          }
        }
      }
    });
  </script>



@endsection