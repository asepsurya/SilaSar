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
                  <span class="ml-1">{{ $icon }}</span> <span class="font-semibold">{{ $ucapan }},</span> {{ auth()->user()->name }}!
                <span class="text-xs text-black/60 dark:text-white/60"></span>     
            </span>
     </span>
   </div>
   <div class="flex items-center justify-between mb-6">
    <h2 class="text-lg sm:text-xl font-bold ">Grafik Transaksi</h2>
    <a href="/transaksi" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
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
                <h2 class="text-2xl leading-9 font-semibold text-black">{{ $transaksi->count() }}</h2>
                <div class="flex items-center gap-1">
                    <p class="text-xs leading-[18px] text-black">Transaksi</p>
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
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
                    Rp.{{ number_format($totalTransaksi, 0, ',', '.') }}
                </h2>

            </div>
        </div>
        <div class="bg-lightblue-100 rounded-2xl p-6">
            <p class="text-sm font-semibold text-black mb-2">Total Barang diluar</p>
            <div class="flex items-center justify-between">
                <h2 class="text-2xl leading-9 font-semibold text-black">
                    Rp.{{ number_format($totalTransaksiluar, 0, ',', '.') }}
                </h2>

            </div>
        </div>

    </div>
  <div class="space-y-6">
  <!-- Keuntungan & Kerugian -->
  <div class="bg-white dark:bg-transparent rounded">
    <h2 class="text-lg font-semibold mb-3">Keuntungan & Kerugian per Bulan</h2>
    <canvas id="chartProfitLoss" class="w-full h-64"></canvas>
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
    type: 'bar',
    data: {
      labels: bulanTransaksi,
      datasets: [{
        label: 'Jumlah Transaksi',
        data: totalTransaksi,
        backgroundColor: 'rgba(59, 130, 246, 0.7)'
      }]
    },
    options: { responsive: true }
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
      plugins: {
        tooltip: {
          enabled: !statusSafe.empty
        },
        centerText: {
          show: statusSafe.empty,
          text: 'Belum ada status'
        }
      }
    },
    plugins: [centerTextPlugin]
  });

  // Chart Mitra (Pie) dengan fallback
  let mitraSafe = safeChartData(mitraLabel, mitraTotal, 'Belum ada mitra');
  new Chart(document.getElementById('chartMitra'), {
    type: 'pie',
    data: {
      labels: mitraSafe.labels,
      datasets: [{
        data: mitraSafe.data,
        backgroundColor: mitraSafe.colors || ['#6366f1', '#ec4899', '#f97316', '#10b981']
      }]
    },
    options: {
      responsive: true,
      plugins: {
        tooltip: {
          enabled: !mitraSafe.empty
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
    type: 'bar',
    data: {
      labels: bulanProfit,
      datasets: [
        {
          label: 'Keuntungan',
          data: keuntungan,
          backgroundColor: 'rgba(16, 185, 129, 0.7)'
        },
        {
          label: 'Kerugian',
          data: kerugian,
          backgroundColor: 'rgba(239, 68, 68, 0.7)'
        }
      ]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          ticks: {
            callback: function(value) {
              return 'Rp ' + value.toLocaleString('id-ID');
            }
          }
        }
      },
      plugins: {
        tooltip: {
          callbacks: {
            label: function(context) {
              return context.dataset.label + ': Rp ' + context.raw.toLocaleString('id-ID');
            }
          }
        }
      }
    }
  });
</script>



@endsection
