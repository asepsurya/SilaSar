@extends('layout.main')
@section('css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #location-cards {
            scroll-behavior: smooth;
        }

        #map {
            height: 500px;
                  position: relative;
    z-index: 0; /* pastikan map di belakang */
        }
        @media (max-width: 768px) {
    #map {
        z-index: 0;
    }
}
    </style>
@endsection

@section('title', 'Peta Pemasaran')

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
    <div class="px-2 py-1 mb-4">
        <h2 class="text-lg font-semibold">Peta Pemasaran Saya</h2>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-7 mb-5">
        <div class="bg-lightblue-100 rounded-2xl p-6">
            <p class="text-sm font-semibold text-black mb-2">Jumlah Titik Lokasi</p>
            <div class="flex items-center justify-between">
                <h2 class="text-2xl leading-9 font-semibold text-black">{{ $mitras->count() }}</h2>
                <div class="flex items-center gap-1">
                    <p class="text-xs leading-[18px] text-black">Titik Lokasi</p>
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
            <p class="text-sm font-semibold text-black mb-2">Jumlah Mitra</p>
            <div class="flex items-center justify-between">
                <h2 class="text-2xl leading-9 font-semibold text-black">{{ $mitras->count() }}</h2>
                <div class="flex items-center gap-1">
                    <p class="text-xs leading-[18px] text-black">Mitra / Toko</p>
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M8.45488 5.60777L14 4L12.6198 9.6061L10.898 7.9532L8.12069 10.8463C8.02641 10.9445 7.89615 11 7.76 11C7.62385 11 7.49359 10.9445 7.39931 10.8463L5.36 8.72199L2.36069 11.8463C2.16946 12.0455 1.85294 12.0519 1.65373 11.8607C1.45453 11.6695 1.44807 11.3529 1.63931 11.1537L4.99931 7.65373C5.09359 7.55552 5.22385 7.5 5.36 7.5C5.49615 7.5 5.62641 7.55552 5.72069 7.65373L7.76 9.77801L10.1766 7.26067L8.45488 5.60777Z"
                            fill="#1C1C1C"></path>
                    </svg>
                </div>
            </div>
        </div>

    </div>
    <!-- Peta -->

    <div id="map" class="rounded w-full"></div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-7">
        <div class="">
            <p class="text-sm font-semibold mt-3">Titik Lokasi</p>
            <!-- Kartu Lokasi -->
            <div id="location-cards"
                class="mt-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-2 gap-4 pb-2 px-1">
                
                <!-- Diisi via JS -->
            </div>

            <!-- Pagination -->
            <div class="flex justify-center items-center gap-2 mt-4" id="pagination-controls">
                <button id="prev-btn" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">Prev</button>
                <span id="page-info" class="text-sm text-gray-600">Page 1</span>
                <button id="next-btn" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">Next</button>
            </div>
        </div>

        <div class="pt-5">
            <p class="text-sm font-semibold mb-3">Titik Berdasarkan Kota</p>
            <div class=" border bg-lightwhite dark:bg-white/5 dark:border-white/10 border-black/10 p-5 rounded-md">
                <table>
                    <thead>
                        <tr>
                            <th width="1%">#</th>
                            <th>Kota</th>
                            <th>Jumlah Titik</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no=1; @endphp
                        @forelse ($jumlahPerKota as $data)
                            <tr>
                                <td>{{ $no++ }}.</td>
                                <td>{{ strtoupper($data->id_kota) }}</td>
                                <td>{{ $data->total }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">Data tidak tersedia</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // Define map layers
        const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        });

        const satellite = L.tileLayer(
            'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Tiles © Esri'
            });

        const dark = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '© CartoDB'
        });

        // Inisialisasi map
        const map = L.map('map', {
            center: [-6.200000, 106.816666],
            zoom: 10,
            layers: [osm]
        });

        const baseMaps = {
            "OpenStreetMap": osm,
            "Satelit": satellite,
            "Dark Mode": dark
        };

        L.control.layers(baseMaps).addTo(map);

        // Data lokasi
        const titikAwal = @json($mitras);

        const locationCardsContainer = document.getElementById('location-cards');
        const pageInfo = document.getElementById('page-info');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');

        const perPage = 12;
        let currentPage = 1;
        const totalPages = Math.ceil(titikAwal.length / perPage);

       function renderPage(page) {
    locationCardsContainer.innerHTML = '';

    // Hapus semua marker sebelumnya
    map.eachLayer(function(layer) {
        if (layer instanceof L.Marker) {
            map.removeLayer(layer);
        }
    });

    if (titikAwal.length === 0) {
        locationCardsContainer.innerHTML = `
          <div class="col-span-full flex items-center justify-center border border-gray-300 rounded p-6 text-gray-500 h-48">
            Data lokasi tidak tersedia
            </div>

        `;
        pageInfo.textContent = 'No data available';
        prevBtn.disabled = true;
        nextBtn.disabled = true;

        // Reset view ke default (optional)
        map.setView([-6.200000, 106.816666], 10);
        return;
    }

    const start = (page - 1) * perPage;
    const end = start + perPage;
    const pageData = titikAwal.slice(start, end);

    // Buat bounds kosong
    const bounds = L.latLngBounds();

    pageData.forEach((titik) => {
        const marker = L.marker([titik.lat, titik.lng]).addTo(map).bindPopup(titik.label);
        bounds.extend(marker.getLatLng());

        const lat = parseFloat(titik.lat);
        const lng = parseFloat(titik.lng);
        const label = titik.label || "Lokasi tanpa nama";
        const gmapsLink = `https://www.google.com/maps?q=${lat},${lng}(${encodeURIComponent(label)})`;

        const card = document.createElement('div');
        card.className =
            "border border-black/10 dark:border-white/10 p-5 rounded-md shadow-sm hover:shadow-md cursor-pointer transition-all duration-300";
        card.innerHTML = `
            <div class="font-semibold text-gray-800">
                <a href="${gmapsLink}" target="_blank" class="text-blue-600 underline">
                    ${label}
                </a>
            </div>
            <div class="text-sm text-gray-500">
                ${isNaN(lat) ? titik.lat : lat.toFixed(3)}, ${isNaN(lng) ? titik.lng : lng.toFixed(3)}
            </div>
        `;

        card.addEventListener('click', () => {
            map.setView([lat, lng], 14);
            marker.openPopup();
        });

        locationCardsContainer.appendChild(card);
    });

    // Fit map ke bounds semua marker jika ada marker
    if (pageData.length > 0) {
        map.fitBounds(bounds, {
            padding: [50, 50]
        });
    }

    pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
    prevBtn.disabled = currentPage === 1;
    nextBtn.disabled = currentPage === totalPages;
}


        prevBtn.addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                renderPage(currentPage);
            }
        });

        nextBtn.addEventListener('click', () => {
            if (currentPage < totalPages) {
                currentPage++;
                renderPage(currentPage);
            }
        });

        renderPage(currentPage);
    </script>
@endsection
