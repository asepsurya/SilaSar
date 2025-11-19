@extends('layout.main')
@section('title', 'Keaktifan Pengguna')
@section('container')

<link rel="stylesheet" href="{{ asset('assets/css/simple-datatables.css') }}" />

<style>
    [x-cloak] {
        display: none !important;
    }

    .active-button {
        border-color: #2563eb !important;
        color: #2563eb !important;
    }

    .p-7 {
        padding: 0px;
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
        border-bottom: 3px solid #075ade;
        /* border-b-2 */
    }

</style>

<div x-data="{ tab: 'harian' }">

    <!-- Tab Buttons -->
    <div class="flex space-x-2 bg-gray-100 dark:bg-white/5 p-3 w-max">
        <!-- Data Pengguna -->
        <a href="/people" class="flex items-center space-x-2 px-4 py-2 text-sm font-semibold rounded-full focus:outline-none focus:ring-2 focus:ring-blue-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A7 7 0 0112 15a7 7 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span>Data Pengguna</span>
        </a>

        <!-- Keaktifan Pengguna -->
        <button @click="tab = 'harian'" :class="tab === 'harian' ? 'bg-blue-600 text-white' : 'bg-white/5 text-gray-800 dark:text-white/70'" class="flex items-center space-x-2 px-4 py-2 text-sm font-semibold rounded-full focus:outline-none focus:ring-2 focus:ring-blue-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18M4 16l4-4 4 4 8-8" />
            </svg>
            <span>Keaktifan Pengguna</span>
        </button>
    </div>

    <!-- ============== TAB KEAKTIFAN ============== -->
    <div x-show="tab === 'harian'" class="p-4 rounded-lg" x-data="dataKeaktifan" x-init="init()">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between  border-gray-200 dark:border-white/10 px-5 mb-2">
            <!-- Judul kiri -->
            <h2 class="text-lg font-semibold mb-3 md:mb-0">Keaktifan Pengguna</h2>

            <!-- Bagian kanan: tombol tab + filter bulan/tahun -->
            <div class="flex flex-wrap items-center gap-3">

                <!-- Tombol tab -->
                <div class="flex flex-wrap items-center gap-3">

                    <!-- Tombol tab -->
                    <div class="flex space-x-2">
                        <button @click="loadData('harian')" :class="periodeAktif === 'harian' ? 'active-button border-b-2 border-blue-500 text-blue-600' : 'border-transparent text-gray-500'" class="px-4 py-2 text-sm font-semibold border-b-2 -mb-px transition">
                            Harian
                        </button>
                        <button @click="loadData('mingguan')" :class="periodeAktif === 'mingguan' ? 'active-button border-b-2 border-blue-500 text-blue-600' : 'border-transparent text-gray-500'" class="px-4 py-2 text-sm font-semibold border-b-2 -mb-px transition">
                            Mingguan
                        </button>
                        <button @click="loadData('bulanan')" :class="periodeAktif === 'bulanan' ? 'active-button border-b-2 border-blue-500 text-blue-600' : 'border-transparent text-gray-500'" class="px-4 py-2 text-sm font-semibold border-b-2 -mb-px transition">
                            Bulanan
                        </button>
                    </div>

                    <!-- Filter Bulan dan Tahun -->
                    <form id="filterForm" method="GET" class="flex gap-0 items-center">
                        <select name="bulan" id="bulanSelect" style="width: 150px;" class="form-select border dark:border-white/10 border-gray-300 rounded-l-md px-3 py-2 focus:ring-2 focus:ring-blue-400 text-sm" onchange="this.form.submit()">
                            @foreach(range(1, 12) as $b)
                            <option value="{{ $b }}" {{ $b == $bulan ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::createFromDate(null, (int)$b, 1)->translatedFormat('F') }}
                            </option>
                            @endforeach
                        </select>

                        <input type="number" id="tahunInput" name="tahun" value="{{ $tahun }}" placeholder="Tahun" class="form-input w-20 border dark:border-white/10 border-gray-300 border-l-0 rounded-r-md px-3 py-2 focus:ring-2 focus:ring-blue-400 text-sm" onchange="this.form.submit()">
                    </form>

                    </button>
                </div>
            </div>
        </div>

        <!-- Konten utama -->
        <div class="border bg-lightwhite dark:bg-white/5 dark:border-white/10 border-black/10 p-2 rounded-md">
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
                    <div class="border bg-white dark:bg-black border-black/10 dark:border-white/10 rounded-md p-3 overflow-x-auto">
                        <table id="TableKeaktifan" class="whitespace-nowrap table-hover table-bordered w-full"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Scripts -->
<script src="{{ asset('assets/js/simple-datatables.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("alpine:init", () => {
    Alpine.data('dataKeaktifan', () => ({
        table: null,
        periodeAktif: '{{ request("periode") ?: "harian" }}', // default
        pieChartInstance: null,
        listenerAdded: false, // <-- flag agar listener tidak double
        init() {
            // Load data saat init
            this.loadData(this.periodeAktif);

            // Event filter: saat select periode berubah
            window.addEventListener('filter', () => {
                const select = document.getElementById('periodeFilter');
                this.loadData(select.value);
            });
        },

        loadData(periode) {
            this.periodeAktif = periode;

            // Ambil bulan & tahun dari request Blade
            const bulan = {{ $bulan ?? now()->month }};
            const tahun = {{ $tahun ?? now()->year }};

            fetch(`/keaktifan?periode=${periode}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ periode, bulan, tahun })
            })
            .then(response => response.json())
            .then(data => {
                // ===========================
                // Render DataTable
                // ===========================
                if (this.table) {
                    this.table.destroy();
                    this.table = null;
                }

                const numberedData = data.data.map((row, index) => {
                    const userId = row[2];
                    const userName = row[0];
                    const stat = row[1];
                    const link = `<a href="/people/update/${userId}" class="text-blue-600 hover:underline">${userName}</a>`;
                    return [index + 1, link, stat];
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

                // ===========================
                // Render Pie Chart
                // ===========================
                const canvas = document.getElementById('userPieChart');
                const ctx = canvas.getContext('2d');

                if (this.pieChartInstance) {
                    this.pieChartInstance.destroy();
                }

                const { total_user, user_aktif, tidakaktif } = data;

                this.pieChartInstance = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: ['Aktif', 'Tidak Aktif'],
                        datasets: [{
                            label: 'Keaktifan User',
                            data: [user_aktif, tidakaktif],
                            backgroundColor: ['#16A34A', '#E5E7EB'],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'bottom' },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const value = context.parsed;
                                        const percentage = (value / total_user * 100).toFixed(1);
                                        return `${context.label}: ${value} user (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            })
            .catch(err => console.error('Error loading data:', err));
        }
    }));
});
</script>

@endsection
