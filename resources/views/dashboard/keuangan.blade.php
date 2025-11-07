@extends('layout.main')
@section('title', 'Dashboard Keuangan')
@section('css')
<style>


  /* 📱 Responsive khusus untuk layar <= 768px */
  @media (max-width: 768px) {
    .p-7{
        padding: 10px;
    }
      /* Grid jadi 1 kolom */
      .grid {
          grid-template-columns: 1fr !important;
      }

      /* Judul lebih kecil */
      h2, h3 {
          font-size: 1rem !important;
      }

      /* Chart otomatis menyesuaikan */
      canvas {
          max-width: 100% !important;
          height: auto !important;
      }

      /* Table bisa discroll horizontal */
      table {
          display: block;
          width: 100%;
          overflow-x: auto;
          white-space: nowrap;
      }

      /* Tombol lebih full width */
      .btn, a.inline-flex {
          width: 100%;
          justify-content: center;
      }

      /* Card padding lebih rapat */
      .p-6, .sm\:p-6 {
          padding: 1rem !important;
      }
      .mobile {
          display: none;
      }

  }
</style>

@endsection
@section('container')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
    <h2 class="text-lg sm:text-xl font-bold ">Ringkasan Keuangan</h2>
    <div class="flex items-center gap-2">
        <!-- Filter Bulanan -->
        <button type="button" id="filterBulanan" class="inline-flex items-center px-3 py-2 bg-gray-100 text-gray-700 rounded-lg shadow hover:bg-gray-200 transition">
            Bulanank
            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </button>
        <!-- Filter Tahunan -->
        <button type="button" id="filterTahunan" class="inline-flex items-center px-3 py-2 bg-gray-100 text-gray-700 rounded-lg shadow hover:bg-gray-200 transition">
            Tahunan
            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
        </button>
        <a href="{{ route('index.keuangan') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
            Lihat Transaksi
            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 mb-4">
    <!-- Pemasukan -->
    <div class="bg-lightblue-100 rounded-2xl p-4 sm:p-6">
        <p class="text-sm font-semibold text-black mb-2">Pemasukan</p>
        <div class="flex items-center justify-between flex-wrap">
            <h2 class="text-xl sm:text-2xl leading-8 sm:leading-9 font-semibold text-black">
                @php
                    $totalPemasukan = $transaksi->where('tipe', 'pemasukan')->sum('total');
                @endphp
                Rp.{{ number_format($totalPemasukan, 0, ',', '.') }}
            </h2>
            <div class="flex items-center gap-1 mt-2 sm:mt-0">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M8.45488 5.60777L14 4L12.6198 9.6061L10.898 7.9532L8.12069 10.8463C8.02641 10.9445 7.89615 11 7.76 11C7.62385 11 7.49359 10.9445 7.39931 10.8463L5.36 8.72199L2.36069 11.8463C2.16946 12.0455 1.85294 12.0519 1.65373 11.8607C1.45453 11.6695 1.44807 11.3529 1.63931 11.1537L4.99931 7.65373C5.09359 7.55552 5.22385 7.5 5.36 7.5C5.49615 7.5 5.62641 7.55552 5.72069 7.65373L7.76 9.77801L10.1766 7.26067L8.45488 5.60777Z"
                        fill="#1C1C1C"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Pengeluaran -->
    <div class="bg-lightpurple-100 rounded-2xl p-4 sm:p-6">
        <p class="text-sm font-semibold text-black mb-2">Pengeluaran</p>
        <div class="flex items-center justify-between flex-wrap">
            <h2 class="text-xl sm:text-2xl leading-8 sm:leading-9 font-semibold text-black">
                @php
                    $totalPengeluaran = $transaksi->where('tipe', 'pengeluaran')->sum('total');
                @endphp
                Rp.{{ number_format($totalPengeluaran, 0, ',', '.') }}
            </h2>
            <div class="flex items-center gap-1 mt-2 sm:mt-0">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M7.54512 10.3922L2 12L3.3802 6.3939L5.10201 8.0468L7.87931 5.1537C7.97359 5.05548 8.10385 5 8.24 5C8.37615 5 8.50641 5.05548 8.60069 5.1537L10.64 7.27801L13.6393 4.1537C13.8305 3.95447 14.1471 3.94807 14.3463 4.13931C14.5455 4.33054 14.5519 4.64706 14.3607 4.8463L11.0007 8.34627C10.9064 8.44448 10.7762 8.5 10.64 8.5C10.5038 8.5 10.3736 8.44448 10.2793 8.34627L8.24 6.22199L5.82341 8.73933L7.54512 10.3922Z"
                        fill="#E53E3E"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Saldo -->
    <div class="bg-lightblue-100 rounded-2xl p-4 sm:p-6">
        <p class="text-sm font-semibold text-black mb-2">Saldo</p>
        <div class="flex items-center justify-between flex-wrap">
            <h2 class="text-xl sm:text-2xl leading-8 sm:leading-9 font-semibold text-black">
                @php
                    $totalPemasukan = $transaksi->where('tipe', 'pemasukan')->sum('total');
                    $totalPengeluaran = $transaksi->where('tipe', 'pengeluaran')->sum('total');
                    $labaBersih = $totalPemasukan - $totalPengeluaran;
                @endphp
                Rp.{{ number_format($labaBersih, 0, ',', '.') }}
            </h2>
            <div class="flex items-center gap-1 mt-2 sm:mt-0">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <circle cx="12" cy="12" r="10" stroke="#2563eb" stroke-width="2" fill="#e0f2fe"/>
                    <path d="M8 12h8M12 8v8" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>
    </div>
</div>


<div class="w-full mt-6 sm:mt-8 mb-6 sm:mb-8">
    <div class="dark:border-white/10 rounded-2xl shadow-md border border-gray-100 p-4 sm:p-6">
        <!-- Judul -->
        <h3 class="text-lg sm:text-xl font-semibold mb-4  text-center">
            Grafik Pemasukan & Pengeluaran
        </h3>

        <!-- Wrapper Canvas -->
        <div class="w-full overflow-x-auto">
            <canvas id="lineChart" class="min-w-[300px] sm:min-w-0" height="180"></canvas>
        </div>

        <!-- Keterangan -->
        <p class="text-xs sm:text-sm  mt-3 text-center">
            Data ditampilkan berdasarkan transaksi bulan berjalan
        </p>
    </div>
</div>


<div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-7">
    <!-- Left: Donut Chart & Account Info -->
    <div class=" p-2 sm:p-0 rounded-2xl shadow-md  sm:mt-8"
     x-data="{ showModal:false, detail:{} }">

    <!-- Judul -->
    <h3 class="text-base sm:text-lg font-semibold mb-3 sm:mb-4 text-center">
        Pengeluaran per Akun
    </h3>

    <!-- ✅ Donut Chart -->
    <div class="w-full flex justify-center mb-6">
        <canvas id="donutChart" width="240" height="240" class="max-w-[240px] w-full h-auto"></canvas>
    </div>

    <!-- ✅ TABLE UNTUK DESKTOP -->
    <div class="overflow-x-auto hidden md:block">
        <table class="w-full min-w-[300px] text-sm sm:text-base">
            <thead>
                <tr>
                    <th class="text-left py-2 text-gray-700 dark:text-gray-200">Akun</th>
                    <th class="text-left py-2 text-gray-700 dark:text-gray-200">Warna</th>
                    <th class="text-left py-2 text-gray-700 dark:text-gray-200">Total Transaksi</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $colors = ['#2563eb', '#a78bfa', '#fbbf24', '#ef4444', '#10b981', '#f472b6', '#f59e42', '#6366f1'];
                @endphp
                @foreach($akun as $i => $a)
                    @php
                        $totalAkun = $transaksi->where('id_akun', $a->id)
                                                ->where('tipe', 'pengeluaran')
                                                ->sum('total');
                        $color = $colors[$i % count($colors)];
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                        <td class="py-2 text-gray-800 dark:text-gray-100 whitespace-nowrap">{{ $a->nama_akun }}</td>
                        <td class="py-2 whitespace-nowrap">
                            <span class="inline-block w-4 h-4 rounded-full" style="background: {{ $color }}"></span>
                        </td>
                        <td class="py-2 text-gray-800 dark:text-gray-100 whitespace-nowrap">
                            Rp.{{ number_format($totalAkun, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- ✅ LIST UNTUK MOBILE -->
    <div class="space-y-2 block md:hidden">
        @foreach($akun as $i => $a)
            @php
                $totalAkun = $transaksi->where('id_akun', $a->id)
                                        ->where('tipe', 'pengeluaran')
                                        ->sum('total');
                $color = $colors[$i % count($colors)];
            @endphp
            <div class="p-3 rounded-lg border dark:border-white/10  dark:bg-gray-900 flex justify-between items-center shadow cursor-pointer"
                 @click="showModal=true; detail={nama:'{{ $a->nama_akun }}', warna:'{{ $color }}', total:'Rp.{{ number_format($totalAkun, 0, ',', '.') }}'}">
                <span class="text-gray-800 dark:text-gray-100 font-medium">{{ $a->nama_akun }}</span>
                <span class="inline-block w-4 h-4 rounded-full" style="background: {{ $color }}"></span>
            </div>
        @endforeach
    </div>

    <!-- ✅ MODAL DETAIL MOBILE -->
    <div x-show="showModal"
         class="fixed inset-0 flex items-center justify-center z-50" style="background: rgba(0,0,0,0.5)"
         x-cloak>
        <div class="bg-white dark:bg-black  rounded-xl p-6 w-11/12 max-w-sm shadow-lg relative">
            <h4 class="text-lg font-semibold mb-4">Detail Akun</h4>
            <p><strong>Akun:</strong> <span x-text="detail.nama"></span></p>
            <p class="mt-2"><strong>Warna:</strong>
                <span class="inline-block w-4 h-4 rounded-full align-middle"
                      :style="`background:${detail.warna}`"></span>
            </p>
            <p class="mt-2"><strong>Total:</strong> <span x-text="detail.total"></span></p>

            <button class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg w-full"
                    @click="showModal=false">Tutup</button>
        </div>
    </div>
</div>

    <!-- Right: Progress Table -->
   <div class="bg-lightwhite dark:bg-white/5 p-4 sm:p-6 rounded-2xl shadow-md overflow-x-auto mobile ">
    <h3 class="text-base sm:text-lg font-semibold mb-3 sm:mb-4 ">Persentase Penggunaan Akun</h3>
    @php
        $totalPengeluaran = $transaksi->where('tipe', 'pengeluaran')->sum('total');
    @endphp
    <table class="w-full min-w-[320px] h ">
        <thead>
            <tr>
                <th class="text-left py-2 text-gray-700 dark:text-gray-200">Akun</th>
                <th class="text-left py-2 text-gray-700 dark:text-gray-200">Persentase</th>
                <th class="text-left py-2 text-gray-700 dark:text-gray-200 hidden sm:table-cell">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($akun as $a)
                @php
                    $totalAkun = $transaksi->where('id_akun', $a->id)->where('tipe', 'pengeluaran')->sum('total');
                    $percent = $totalPengeluaran > 0 ? ($totalAkun / $totalPengeluaran) * 100 : 0;
                @endphp
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition cursor-pointer"
                    onclick="showAkunDetailModal({{ $a->id }})">
                    <!-- Nama akun -->
                    <td class="py-2 text-gray-800 dark:text-gray-100">{{ $a->nama_akun }}</td>

                    <!-- Persentase progress bar -->
                    <td class="py-2 w-2/5">
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                            <div class="h-3 rounded-full transition-all duration-300"
                                 style="width: {{ $percent }}%; background-color: #2563eb;"></div>
                        </div>
                        <span class="text-xs text-gray-600 dark:text-gray-400">
                            {{ number_format($percent, 1) }}%
                        </span>
                    </td>

                    <!-- Total hanya muncul di desktop -->
                    <td class="py-2 text-gray-800 dark:text-gray-100 hidden sm:table-cell">
                        Rp.{{ number_format($totalAkun, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

</div>
<div class="w-full mt-6 sm:mt-8 mb-6 sm:mb-8">
    <div class=" rounded-2xl shadow-md border border-gray-100 p-4 sm:p-6 dark:border-white/10">
        <!-- Judul -->
        <h3 class="text-lg sm:text-xl font-semibold mb-4  text-center">
            Grafik Boundaries Penggunaan Rekening
        </h3>

        <!-- Chart -->
        <div class="w-full overflow-x-auto">
            <canvas id="boundariesLineChart" class="min-w-[320px] sm:min-w-0" height="200"></canvas>
        </div>

        <!-- Keterangan -->
        <p class="text-xs sm:text-sm  mt-3 text-center">
            Menampilkan total transaksi per rekening
        </p>
    </div>
</div>

<script>
window.addEventListener('load', function () {
    const boundariesChartCanvas = document.getElementById('boundariesLineChart');
    if (boundariesChartCanvas && window.Chart) {
        // Data dari backend
        const akunLabels = @json($akun->pluck('nama_akun'));
        const akunTotals = @json(
            $akun->map(function($a) use ($transaksi) {
                return $transaksi->where('id_akun', $a->id)->sum('total');
            })
        );

        // Deskripsi akun (ambil dari field deskripsi akun, kalau ada)
        const akunDescriptions = @json($akun->pluck('deskripsi', 'nama_akun'));

        // Warna untuk titik data
        const colors = ['#2563eb', '#a78bfa', '#fbbf24', '#ef4444', '#10b981', '#f472b6', '#f59e42', '#6366f1'];

        const chart = new Chart(boundariesChartCanvas.getContext('2d'), {
            type: 'line',
            data: {
                labels: akunLabels,
                datasets: [{
                    label: 'Total Penggunaan Rekening',
                    data: akunTotals,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37,99,235,0.1)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: colors,
                    pointBorderColor: colors,
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // biar fleksibel di mobile
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: { size: 12 }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const akun = context.label;
                                const value = context.raw;
                                const desc = akunDescriptions[akun] || '';
                                return `${akun}: Rp ${value.toLocaleString('id-ID')} ${desc ? '('+desc+')' : ''}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    },
                    x: {
                        ticks: {
                            maxRotation: 60,
                            minRotation: 30
                        }
                    }
                }
            }
        });

        // Tambah height otomatis di mobile
        function adjustChartHeight() {
            if (window.innerWidth < 768) {
                boundariesChartCanvas.parentElement.style.height = "400px"; // tinggi di HP
            } else {
                boundariesChartCanvas.parentElement.style.height = "300px"; // tinggi normal di desktop
            }
        }
        adjustChartHeight();
        window.addEventListener('resize', adjustChartHeight);
    }
});
</script>



<script>
window.addEventListener('load', function () {
    // Donut Chart (Pengeluaran per Akun)
    const chartCanvas = document.getElementById('donutChart');

    if (chartCanvas && window.Chart) {
        const ctx = chartCanvas.getContext('2d');
        const akunLabels = @json($akun->pluck('nama_akun'));
        const akunTotals = @json(
            $akun->map(function($a) use ($transaksi) {
                return $transaksi->where('id_akun', $a->id)->where('tipe', 'pengeluaran')->sum('total');
            })
        );
        const colors = ['#2563eb', '#a78bfa', '#fbbf24', '#ef4444', '#10b981', '#f472b6', '#f59e42', '#6366f1'];

        // cek apakah kosong
        const isEmpty = akunTotals.length === 0 || akunTotals.every(v => v === 0);

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: isEmpty ? ['Tidak ada data'] : akunLabels,
                datasets: [{
                    data: isEmpty ? [1] : akunTotals,
                    backgroundColor: isEmpty ? ['#e5e7eb'] : colors,
                    borderWidth: 1
                }]
            },
            options: {
                cutout: '50%',
                plugins: {
                    legend: {
                        display: false,
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return isEmpty ? 'Tidak ada data' : context.label + ': ' + context.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            },
            plugins: [{
                // Tambahin teks di tengah kalau kosong
                id: 'emptyText',
                afterDraw(chart) {
                    if (isEmpty) {
                        const { ctx, chartArea } = chart;
                        ctx.save();
                        ctx.font = '14px sans-serif';
                        ctx.fillStyle = '#6b7280';
                        ctx.textAlign = 'center';
                        ctx.fillText('Tidak ada data', (chartArea.left + chartArea.right) / 2, (chartArea.top + chartArea.bottom) / 2);
                    }
                }
            }]
        });
    }

    // Line Chart (Pemasukan & Pengeluaran per bulan)
    const lineChartCanvas = document.getElementById('lineChart');
    if (lineChartCanvas && window.Chart) {
        const bulanLabels = @json($bulanLabels);
        const pemasukanData = @json($pemasukanPerBulan);
        const pengeluaranData = @json($pengeluaranPerBulan);

        new Chart(lineChartCanvas.getContext('2d'), {
            type: 'line',
            data: {
                labels: bulanLabels,
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: pemasukanData,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16,185,129,0.1)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Pengeluaran',
                        data: pengeluaranData,
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239,68,68,0.1)',
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp.' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>


@endsection
