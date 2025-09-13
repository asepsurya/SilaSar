@extends('layout.main')
@section('title', 'Data IKM')
@section('css')
<link rel="stylesheet" type="text/css" media="screen" href="{{ asset('assets/css/simple-datatables.css') }}" />
<style>
.akun-nama {
            white-space: normal; /* default desktop: teks normal */
        }
        
        @media (max-width: 768px) { /* hanya mobile */
            .akun-nama {
                display: inline-block;
                max-width: 140px; /* sesuaikan lebar maksimal */
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
        }
    @media (max-width: 768px) {
        .mobile {
            display: none;
        }
    }

    @media (max-width: 768px) {

        #myTable th:nth-child(4),
        #myTable td:nth-child(4),
        #myTable th:nth-child(5),
        #myTable td:nth-child(5),
        #myTable th:nth-child(6),
        #myTable td:nth-child(6) {
            display: none !important;
        }
    }
    #TableKeaktifan td:nth-child(3),
    #TableKeaktifan th:nth-child(3) {
        min-width: 300px;
    }
    #TableKeaktifan td:nth-child(1),
    #TableKeaktifan th:nth-child(1) {
        width: 2px;
    }
    .active-button {
        border-bottom: 3px solid #075ade; /* border-b-2 */
    }

</style>
@endsection
@section('container')

<div class="p-5 flex items-center justify-between">
    <h2 class="text-lg font-semibold">Peoples</h2>
    <a href="{{ route('ikm.create') }}" class="px-2 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
        + Tambah Pengguna Baru
    </a>
</div>
<div x-data="{ tab: 'harian' }" class=" ">

    <!-- Tab Buttons -->
    <div class="flex space-x-2 bg-gray-100 dark:bg-white/5 p-3 w-max m-5 ">
        <!-- Data Pengguna -->
        <button :class="tab === 'harian' ? 'bg-blue-600 text-white' : 'bg-white/5 text-gray-800'" class="flex items-center space-x-2 px-4 py-2 text-sm font-semibold rounded-full focus:outline-none focus:ring-2 focus:ring-blue-400" @click="tab = 'harian'">
            <!-- Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A7 7 0 0112 15a7 7 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span>Data Pengguna</span>
        </button>

        <!-- Keaktifan Pengguna -->
        <button :class="tab === 'mingguan' ? 'bg-blue-600 text-white' : 'bg-white/5 text-gray-800'" class="flex items-center space-x-2 px-4 py-2 text-sm font-semibold rounded-full focus:outline-none focus:ring-2 focus:ring-blue-400" @click="tab = 'mingguan'">
            <!-- Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 3v18h18M4 16l4-4 4 4 8-8" />
            </svg>

            <span>Keaktifan Pengguna</span>
        </button>
</div>

    <!-- Tab Content -->
    <div class="">
        <div x-show="tab === 'harian'" class="p-4 rounded-lg ">
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-7 mb-4">
                <div class="bg-lightblue-100 rounded-2xl p-6">
                    <p class="text-sm font-semibold text-black mb-2">Jumlah UMKM</p>
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl leading-9 font-semibold text-black">{{ $ikm->count() }}</h2>
                        <div class="flex items-center gap-1">
                            <p class="text-xs leading-[18px] text-black">Pengguna</p>
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M8.45488 5.60777L14 4L12.6198 9.6061L10.898 7.9532L8.12069 10.8463C8.02641 10.9445 7.89615 11 7.76 11C7.62385 11 7.49359 10.9445 7.39931 10.8463L5.36 8.72199L2.36069 11.8463C2.16946 12.0455 1.85294 12.0519 1.65373 11.8607C1.45453 11.6695 1.44807 11.3529 1.63931 11.1537L4.99931 7.65373C5.09359 7.55552 5.22385 7.5 5.36 7.5C5.49615 7.5 5.62641 7.55552 5.72069 7.65373L7.76 9.77801L10.1766 7.26067L8.45488 5.60777Z" fill="#1C1C1C"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-lightpurple-100 rounded-2xl p-6">
                    <p class="text-sm font-semibold text-black mb-2">Jumlah Laki-Laki</p>
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl leading-9 font-semibold text-black">{{ $jumlah['L'] ?? 0; }}</h2>
                        <div class="flex items-center gap-1">
                            <p class="text-xs leading-[18px] text-black">Pengguna</p>
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M8.45488 5.60777L14 4L12.6198 9.6061L10.898 7.9532L8.12069 10.8463C8.02641 10.9445 7.89615 11 7.76 11C7.62385 11 7.49359 10.9445 7.39931 10.8463L5.36 8.72199L2.36069 11.8463C2.16946 12.0455 1.85294 12.0519 1.65373 11.8607C1.45453 11.6695 1.44807 11.3529 1.63931 11.1537L4.99931 7.65373C5.09359 7.55552 5.22385 7.5 5.36 7.5C5.49615 7.5 5.62641 7.55552 5.72069 7.65373L7.76 9.77801L10.1766 7.26067L8.45488 5.60777Z" fill="#1C1C1C"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-lightblue-100 rounded-2xl p-6">
                    <p class="text-sm font-semibold text-black mb-2">Jumlah Perempuan</p>
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl leading-9 font-semibold text-black">{{ $jumlah['P'] ?? 0; }}</h2>
                        <div class="flex items-center gap-1">
                            <p class="text-xs leading-[18px] text-black">Pengguna</p>
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M8.45488 5.60777L14 4L12.6198 9.6061L10.898 7.9532L8.12069 10.8463C8.02641 10.9445 7.89615 11 7.76 11C7.62385 11 7.49359 10.9445 7.39931 10.8463L5.36 8.72199L2.36069 11.8463C2.16946 12.0455 1.85294 12.0519 1.65373 11.8607C1.45453 11.6695 1.44807 11.3529 1.63931 11.1537L4.99931 7.65373C5.09359 7.55552 5.22385 7.5 5.36 7.5C5.49615 7.5 5.62641 7.55552 5.72069 7.65373L7.76 9.77801L10.1766 7.26067L8.45488 5.60777Z" fill="#1C1C1C"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div  x-data="dataPengguna" x-init="init()" class="border bg-lightwhite dark:bg-white/5 dark:border-white/10 border-black/10 p-2 rounded-md">
                <div class="mb-1">
                    <p class="text-sm font-semibold mb-3">Data Pengguna</p>
                </div>
                <div class="">
                    <div class="">
                        <table id="myTable" class="whitespace-nowrap table-hover table-bordered "></table>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="tab === 'mingguan'" class="p-4 rounded-lg ">
            <div class="w-full" x-data="dataKeaktifan" x-init="init()">

                <!-- Tab Button Group -->
              <div class="flex border-b border-gray-200 dark:border-white/10 justify-end mb-4">
                    <button 
                        @click="loadData('harian')" 
                        :class="periodeAktif === 'harian' ? 'active-button' : 'border-transparent'" 
                        class="px-4 py-2 text-sm font-semibold border-b-2 -mb-px transition">
                        Harian
                    </button>
                    <button 
                        @click="loadData('mingguan')" 
                        :class="periodeAktif === 'mingguan' ? 'active-button' : 'border-transparent'" 
                        class="px-4 py-2 text-sm font-semibold border-b-2 -mb-px transition">
                        Mingguan
                    </button>
                    <button 
                        @click="loadData('bulanan')" 
                        :class="periodeAktif === 'bulanan' ? 'active-button' : 'border-transparent'" 
                        class="px-4 py-2 text-sm font-semibold border-b-2 -mb-px transition">
                        Bulanan
                    </button>
                </div>


              <div class="border bg-lightwhite dark:bg-white/5 dark:border-white/10 border-black/10 p-5 rounded-md">
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
        
        <!-- Chart -->
        <div class="col-span-12 md:col-span-4 flex flex-col items-center">
             <p class="text-sm font-semibold mb-4">Keaktifan Pengguna</p>
            <div class="w-48 md:w-64">
                <canvas id="userPieChart"></canvas>
            </div>
           
        </div>

        <!-- Table -->
        <div class="col-span-12 md:col-span-8">
            <div class="">
                <div class="">
                    <table id="TableKeaktifan" class="whitespace-nowrap table-hover table-bordered w-full"></table>
                </div>
            </div>
        </div>

    </div>
</div>

            </div>
        </div>
    </div>
</div>

@endsection
@section('js')
<script src="{{ asset('assets/js/simple-datatables.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

 <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Cek apakah URL cocok dengan pola `/people/update/*`
            if (window.location.pathname.startsWith("/people")) {
                // Pilih semua elemen yang mengandung class `sm:p-7`
                const elements = document.querySelectorAll(".sm\\:p-5");
                const elements2 = document.querySelectorAll(".p-7");

                // Hapus class `sm:p-7` dari elemen tersebut
                elements.forEach(el => el.classList.remove("sm:p-5"));
                elements2.forEach(el => el.classList.remove("p-7"));
            }
        });
    </script>

<script>
    let pieChartInstance = null; // <-- simpan di luar Alpine jika global
    document.addEventListener("alpine:init", () => {
    Alpine.data('dataPengguna', () => ({
        init() {
            this.loadData();
        },
        loadData() {
            const rawData = @json($ikm);
            const numberedData = rawData.map((row, index) => {
                return [index + 1, ...row];
            });
            new simpleDatatables.DataTable("#myTable", {
                data: {
                    headings: ["No", "Nama", "No. Telepon", "Email","","Tingkatan Pengguna"],
                    data: numberedData
                },
                sortable: false,
                searchable: true,
                perPage: 10,
                perPageSelect: [10, 20, 50, 100],
                firstLast: false,
                firstText: svgFirst,
                lastText: svgLast,
                prevText: svgPrev,
                nextText: svgNext,
                labels: { perPage: '{select}' },
                layout: { top: '{select}{search}', bottom: '{info}{pager}' }
            });
        }
    }));

 

   Alpine.data('dataKeaktifan', () => ({
    table: null,
    periodeAktif: 'bulanan',
    pieChartInstance: null,

    init() {
        this.loadData('bulanan'); // load data dan render pie chart default bulanan
      },

    loadData(periode) {
        this.periodeAktif = periode;
        fetch(`/keaktifan?periode=${periode}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ periode: periode })
        })
        .then(response => response.json())
        .then(data => {
            // ===================== TABEL =====================
            if (this.table) this.table.destroy();

            const numberedData = data.data.map((row, index) => {
                return [index + 1, ...row];
            });

            this.table = new simpleDatatables.DataTable("#TableKeaktifan", {
                data: {
                    headings: ["No", "Nama", "Statistik Keaktifan"],
                    data: numberedData
                },
                sortable: false,
                searchable: true,
                perPage: 10,
                perPageSelect: [10, 20, 50, 100],
                firstLast: false,
                labels: { perPage: '{select}' },
                layout: { top: '{select}{search}', bottom: '{info}{pager}' }
            });

            // ===================== PIE CHART =====================
            const ctx = document.getElementById('userPieChart').getContext('2d');
            const totalUser = data.total_user;
            const aktif = data.user_aktif;
            const tidakAktif = data.tidakaktif;

            const chartData = {
                labels: ['Aktif', 'Tidak Aktif'],
                datasets: [{
                    label: 'Keaktifan User',
                    data: [aktif, tidakAktif],
                    backgroundColor: ['#16A34A', '#E5E7EB'],
                    borderWidth: 1
                }]
            };

            const chartOptions = {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.parsed;
                                const percentage = (value / totalUser * 100).toFixed(1);
                                return `${context.label}: ${value} user (${percentage}%)`;
                            }
                        }
                    }
                }
            };

            if (this.pieChartInstance) {
                this.pieChartInstance.destroy();
            }

            this.pieChartInstance = new Chart(ctx, {
                type: 'pie',
                data: chartData,
                options: chartOptions
            });
        });
    }
}));
});

// Icon SVG Text (biar gak ulang nulis)
const svgFirst = ` <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
              xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5">
              <path d="M13 19L7 12L13 5" stroke="currentColor" stroke-width="1.5"
                stroke-linecap="round" stroke-linejoin="round" />
              <path opacity="0.5" d="M17 19L11 12L17 5" stroke="currentColor"
                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>`;
const svgLast  = `<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
              xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5">
              <path d="M11 19L17 12L11 5" stroke="currentColor" stroke-width="1.5"
                stroke-linecap="round" stroke-linejoin="round" />
              <path opacity="0.5" d="M7 19L13 12L7 5" stroke="currentColor"
                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>`;
const svgPrev  = ` <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
              xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5">
              <path d="M15 5L9 12L15 19" stroke="currentColor" stroke-width="1.5"
                stroke-linecap="round" stroke-linejoin="round" />
            </svg>`;
const svgNext  = `<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
              xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5">
              <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                stroke-linecap="round" stroke-linejoin="round" />
            </svg>`;

</script>

@endsection
