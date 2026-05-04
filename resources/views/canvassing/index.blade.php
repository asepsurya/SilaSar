@extends('layout.main')
@section('title', 'Canvasing Toko')
@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .map-container {
            height: calc(100vh - 68px);
            /* Adjust based on topbar height */
            width: 100%;
            background-color: #e5e7eb;
            overflow: hidden;
            position: relative;
        }

        @media (min-width: 1024px) {

            /* Sidebar Mini Mode for Canvassing Page */
            .is-canvassing .sidebar {
                width: 76px !important;
            }

            .is-canvassing .main-container .main-content {
                margin-left: 76px !important;
            }

            /* Hide labels, submenus (initially), SEARCH, and logo names */
            .is-canvassing .sidebar span,
            .is-canvassing .sidebar h2,
            .is-canvassing .sidebar .sub-menu,
            .is-canvassing .sidebar .text-black\/50.dark\:text-white\/20.w-4.h-4,
            .is-canvassing .sidebar .nav-item.mb-3:has(#search-container),
            /* Target search container if added */
            .is-canvassing .sidebar .ml-3.flex-1.max-w-full {
                display: none !important;
            }

            /* Hide the search menu include specifically */
            .is-canvassing .sidebar #search-menu-wrapper {
                display: none !important;
            }

            .is-canvassing .sidebar .flex.items-center.p-4 {
                justify-content: center !important;
                padding: 1.5rem 0.5rem !important;
            }

            .is-canvassing .sidebar {
                overflow: visible !important;
            }

            .is-canvassing .sidebar #menu {
                padding: 0.5rem !important;
                overflow: visible !important;
            }

            /* Centered circular icons */
            .is-canvassing .sidebar .nav-item {
                display: flex !important;
                justify-content: center !important;
                width: 100% !important;
                position: relative !important;
            }

            .is-canvassing .sidebar .nav-item>a {
                justify-content: center !important;
                display: flex !important;
                align-items: center !important;
                padding: 0 !important;
                margin: 4px auto !important;
                width: 44px !important;
                height: 44px !important;
                border-radius: 50% !important;
                transition: all 0.2s ease;
            }

            .is-canvassing .sidebar .nav-item>a.active,
            .is-canvassing .sidebar .nav-item:hover>a {
                background-color: rgba(28, 28, 28, 0.08) !important;
            }

            .dark .is-canvassing .sidebar .nav-item>a.active,
            .dark .is-canvassing .sidebar .nav-item:hover>a {
                background-color: rgba(255, 255, 255, 0.15) !important;
            }

            .is-canvassing .sidebar .pl-1,
            .is-canvassing .sidebar .pl-3,
            .is-canvassing .sidebar .pl-5 {
                padding-left: 0 !important;
            }

            .is-canvassing .sidebar .nav-item>a x-icon,
            .is-canvassing .sidebar .nav-item>a svg,
            .is-canvassing .sidebar .nav-item>a img {
                margin: 0 !important;
                width: 20px !important;
                height: 20px !important;
            }

            .is-canvassing .sidebar .nav-item>a div.flex {
                padding: 0 !important;
            }

            /* Popover Submenu Logic for both hover and clicked (open) states */
            .is-canvassing .sidebar #menu .nav-item:hover .sub-menu,
            .is-canvassing .sidebar #menu .nav-item .sub-menu[style*="display: block"] {
                display: flex !important;
                visibility: visible !important;
                opacity: 1 !important;
                flex-direction: column !important;
                position: absolute !important;
                left: 70px !important;
                top: 0 !important;
                background-color: #ffffff !important;
                background: #ffffff !important;
                border: 1px solid #cbd5e1 !important;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2) !important;
                border-radius: 12px !important;
                min-width: 220px !important;
                z-index: 100000 !important;
                padding: 12px !important;
                gap: 2px !important;
                overflow: visible !important;
                height: auto !important;
            }

            /* Hide any nested search or stray inputs in mini items */
            .is-canvassing .sidebar .nav-item:hover .sub-menu input,
            .is-canvassing .sidebar .nav-item:hover .sub-menu .relative:has(input) {
                display: none !important;
            }

            /* The invisible "bridge" to keep hover active while moving to popover */
            .is-canvassing .sidebar .nav-item:hover .sub-menu::before {
                content: "";
                position: absolute;
                left: -30px;
                top: 0;
                bottom: 0;
                width: 30px;
                background: transparent;
                z-index: -1;
            }

            .dark .is-canvassing .sidebar #menu .nav-item:hover .sub-menu,
            .dark .is-canvassing .sidebar #menu .nav-item .sub-menu[style*="display: block"] {
                background-color: #1a1c23 !important;
                background: #1a1c23 !important;
                border-color: rgba(255, 255, 255, 0.15) !important;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6) !important;
            }

            /* Re-show labels and adjust links inside popover */
            .is-canvassing .sidebar .nav-item:hover .sub-menu li {
                width: 100% !important;
            }

            .is-canvassing .sidebar .nav-item:hover .sub-menu li a {
                display: block !important;
                padding: 10px 14px !important;
                border-radius: 6px !important;
                width: 100% !important;
                height: auto !important;
                margin: 0 !important;
                text-align: left !important;
                background: transparent !important;
                justify-content: flex-start !important;
            }

            .is-canvassing .sidebar .nav-item:hover .sub-menu li a:hover {
                background: rgba(0, 0, 0, 0.05) !important;
            }

            .dark .is-canvassing .sidebar .nav-item:hover .sub-menu li a:hover {
                background: rgba(255, 255, 255, 0.1) !important;
            }
        }

        #map {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 0;
        }


        .loader {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #3b82f6;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
        }

        .leaflet-container a {
            color: #ffffff;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        #container {
            padding: 0px !important;
            height: 100%;
            margin-bottom: 0 !important;
        }

        .map-container.shadow-sm {
            box-shadow: none !important;
        }

        /* Custom scrollbar untuk list melayang */
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Styling Search Results */
        #search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            margin-top: 0.5rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            border-radius: 0px;
            border: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            max-height: 300px;
            overflow-y: auto;
            z-index: 2000;
        }

        .dark #search-results {
            background: rgba(30, 41, 59, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .search-item {
            padding: 0.75rem 1rem;
            cursor: pointer;
            font-size: 0.8rem;
            color: #4b5563;
            transition: all 0.2s;
            border-bottom: 1px solid rgba(0, 0, 0, 0.03);
        }

        .dark .search-item {
            color: #cbd5e1;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
        }

        .search-item:last-child {
            border-bottom: none;
        }

        .search-item:hover {
            background: rgba(59, 130, 246, 0.05);
            color: #2563eb;
            padding-left: 1.25rem;
        }

        .dark .search-item:hover {
            background: rgba(59, 130, 246, 0.2);
            color: #60a5fa;
        }

        /* Hide right sidebar default behaviour */
        div.right-sidebar,
        #rightSidebar {
            display: none !important;
        }

        .main-content {
            margin-right: 0 !important;
            padding-right: 0 !important;
        }

        /* Mobile Bottom Sheet Styling */
        @media (max-width: 767px) {
            .map-container {
                position: fixed !important;
                top: 0;
                left: 0;
                width: 100%;
                height: calc(100vh - 50px);
                z-index: 1;
            }

            .mobile-bottom-sheet {
                position: fixed !important;
                bottom: 0 !important;
                left: 0 !important;
                width: 100% !important;
                transform: translateY(calc(100% - 170px));
                z-index: 45 !important;
                transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
                border-radius: 20px 20px 0 0 !important;
                display: flex;
                flex-direction: column;
                box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.15);
                padding-bottom: 50px !important;
            }

            .mobile-bottom-sheet.expanded {
                transform: translateY(0) !important;
            }

            .sheet-handle {
                width: 40px;
                height: 5px;
                background: #cbd5e1;
                border-radius: 3px;
                margin: 10px auto 6px auto;
                flex-shrink: 0;
                cursor: grab;
            }

            /* Hide only zoom controls on mobile */
            .leaflet-control-zoom {
                display: none !important;
            }

            /* Keep my-location control visible and position well */
            .leaflet-bottom.leaflet-right {
                bottom: 130px;
            }

            /* Search bar full width on mobile */
            .mobile-search-wrap {
                left: 12px !important;
                right: 12px !important;
                width: auto !important;
            }

            /* Remove container padding on mobile for canvassing */
            #container {
                padding: 0 !important;
                margin-bottom: 0 !important;
            }

            /* Enable touch scrolling on store list */
            #toko-list {
                -webkit-overflow-scrolling: touch;
                overflow-y: auto !important;
                flex: 1 1 auto;
                min-height: 0;
            }
        }

        /* Ensure desktop defaults are solid */
        @media (min-width: 768px) {
            #canvassing-sidebar {
                position: absolute !important;
                top: 0 !important;
                left: 0 !important;
                height: 100% !important;
                width: 384px !important;
                transform: none !important;
                border-radius: 0 !important;
            }

            #map {
                position: absolute !important;
                top: 0 !important;
                left: 0 !important;
                width: 100% !important;
                height: 100% !important;
            }
        }
    </style>
@endsection
@section('container')
    <div class="relative w-full map-container shadow-sm overflow-hidden">
        <!-- Peta Dasar Full Resolusi -->
        <div id="map" class="absolute inset-0"></div>

        <!-- Floating Sidebar Kiri / Mobile Bottom Sheet -->
        <div id="canvassing-sidebar"
            class="absolute top-0 left-0 h-full z-[9999] w-[384px] min-w-[384px] dark:bg-black bg-white dark:bg-slate-900 shadow-xl flex flex-col overflow-hidden border border-gray-100/50 dark:border-white/10 rounded-none mobile-bottom-sheet">
            <!-- Handle for mobile -->
            <div class="sheet-handle md:hidden"></div>
            <!-- Header & Controls -->
            <div id="sidebar-header"
                class="border-b border-gray-50 dark:border-white/5 flex-shrink-0 transition-all duration-300">
                <div class="p-5">
                    <div class="flex justify-between items-center mb-5">
                        <b>Daftar Toko</b>

                        <button onclick="toggleAddMode()" id="btn-add-mode"
                            class="btn btn-primary font-bold py-2 px-4 rounded-none text-xs transition-all shadow-md hover:shadow-lg flex items-center gap-1.5 active:scale-95">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Toko
                        </button>
                    </div>

                    <!-- Form Tambah (Diganti Modal) -->
                    <div id="add-mode-indicator"
                        class="hidden mb-5 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800 rounded-none">
                        <h3 class="font-bold text-sm text-emerald-900 dark:text-emerald-300 mb-2 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                            </svg>
                            Mode Tambah Toko
                        </h3>
                        <p class="text-[11px] text-emerald-700/80 dark:text-emerald-400 mb-3 italic leading-relaxed"
                            id="add-instruction">Silakan klik lokasi di peta untuk <br>menambahkan titik toko baru.</p>
                        <button type="button" onclick="useCurrentLocationForStore()"
                            class="w-full btn bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-bold py-2 rounded-none transition-colors mb-2 flex items-center justify-center gap-1 shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Gunakan Lokasi Saat Ini
                        </button>
                        <button type="button" onclick="cancelAddMode()"
                            class="w-full btn bg-red-100/50 hover:bg-red-100 text-red-700 text-xs font-bold py-2 rounded-none transition-colors shadow-sm">Batal</button>
                    </div>

                    <!-- Search & Month Controls -->
                    <div id="main-controls" class="flex flex-col gap-3">
                        <div class="flex gap-2">
                            <div class="flex-1 relative group">
                                <input type="month" id="filter-month" onchange="window.handleMonthChange()"
                                    class="form-input py-2.5 pl-4 pr-20 w-full text-black dark:text-white border border-black/10 dark:border-white/10 rounded-none placeholder:text-black/20 dark:placeholder:text-white/20 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-0 focus:shadow-none">
                            </div>
                        </div>

                        <div class="relative group">
                            <input type="text" id="searchToko" oninput="window.syncAndSearch(this.value, 'sidebar')"
                                placeholder="Cari toko, alamat, atau lokasi..."
                                class="form-input py-2.5 pl-4 pr-20 w-full text-black dark:text-white border border-black/10 dark:border-white/10 rounded-none placeholder:text-black/20 dark:placeholder:text-white/20 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-0 focus:shadow-none">
                        </div>

                        <div class="relative">
                            <button onclick="window.toggleFilter()"
                                class="w-full flex justify-between items-center text-xs font-bold text-gray-600 dark:text-gray-300 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600 px-4 py-2.5 rounded-none dark:bg-black border border-gray-200 dark:border-white/10 transition-all shadow-sm active:scale-[0.98]">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                                        </path>
                                    </svg>
                                    Opsi & Filter
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="status-summary" class="mx-4 mb-2 grid grid-cols-2 gap-3 text-[11px] px-4 mt-2">
                <div
                    class=" bg-lightblue-100 rounded-2xl p-4 sm:p-6  dark:border-green-900/40 bg-green-50 dark:bg-green-900/20 rounded-none p-3 shadow-sm">
                    <div class="text-green-700 dark:text-black font-bold uppercase tracking-wide text-black">Sudah Cek</div>
                    <div id="checked-count" class="mt-1 text-2xl font-black text-green-800 dark:text-black">0</div>
                </div>
                <div
                    class="bg-lightpurple-100 rounded-2xl p-4 sm:p-6 border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-slate-800 rounded-none p-3 shadow-sm">
                    <div class="text-gray-600 dark:text-gray-300 font-bold uppercase tracking-wide">Belum Cek</div>
                    <div id="unchecked-count" class="mt-1 text-2xl font-black text-gray-800 dark:text-black">0</div>
                </div>
            </div>

            <!-- Info Section -->
            <div id="info-search-area"
                class="hidden m-4 p-4 bg-blue-50 dark:bg-blue-900/20 text-blue-800 dark:text-blue-300 rounded-none text-[11px] border border-blue-100 dark:border-blue-800 shadow-sm">
                <div class="flex flex-col gap-2">
                    <div class="font-bold flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Area Terpilih: <span id="search-center-name" class="ml-1"></span>
                    </div>
                    <div class="flex gap-2">
                        <button id="btn-search-here"
                            class="flex-1 bg-blue-600 text-white px-3 py-1.5 rounded-none font-bold text-[10px] hover:bg-blue-700 transition">Lihat
                            Toko Terdekat dari Sini</button>
                        <button onclick="window.shareAreaWA()"
                            class="bg-green-600 text-white px-3 py-1.5 rounded-none font-bold text-[10px] hover:bg-emerald-700 transition flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12.031 6.172c-2.31 0-4.184 1.874-4.184 4.184 0 2.31 1.874 4.184 4.184 4.184 2.31 0 4.184-1.874 4.184-4.184 0-2.31-1.874-4.184-4.184-4.184zm8.681 4.184c0 4.793-3.882 8.681-8.681 8.681-4.793 0-8.681-3.888-8.681-8.681C3.35 5.56 7.238 1.672 12.031 1.672c4.799 0 8.681 3.888 8.681 8.684zm-4.48 4.477c-.198-.102-1.173-.58-1.354-.645-.181-.065-.313-.098-.445.098-.132.196-.511.645-.626.776-.115.131-.231.148-.429.046-.198-.102-.835-.308-1.591-.983-.589-.525-.987-1.173-1.102-1.371-.115-.198-.012-.304.087-.404.089-.09.198-.231.297-.346.1-.115.132-.196.198-.328.065-.132.033-.247-.016-.346-.05-.102-.445-1.074-.61-1.472-.16-.39-.323-.337-.445-.343-.115-.006-.247-.006-.379-.006s-.346.049-.526.247c-.181.196-.693.677-.693 1.652s.71 1.916.81 2.047c.1.131 1.396 2.131 3.383 2.986.471.203.839.324 1.125.415.474.15.905.129 1.246.078.38-.056 1.173-.48 1.338-.943.165-.462.165-.858.116-.941-.049-.083-.182-.132-.38-.234z" />
                            </svg>
                            Share
                        </button>
                        <button onclick="window.clearSearchCenter()"
                            class="bg-white text-gray-500 px-3 py-1.5 rounded-none border border-gray-200 font-bold text-[10px] hover:bg-gray-50 transition">X</button>
                    </div>
                </div>
            </div>

            <div id="loading" class="flex justify-center items-center py-4 hidden bg-white dark:bg-slate-900">
                <div class="loader"></div>
                <span class="ml-2 text-xs font-medium text-gray-500 dark:text-gray-400">Sinkronisasi data...</span>
            </div>

            <div id="route-info"
                class="hidden m-4 p-4 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-800 dark:text-emerald-300 rounded-none text-[11px] border border-emerald-100 dark:border-emerald-800 shadow-sm animate-pulse-subtle">
                <div class="flex justify-between items-start mb-3">
                    <div class="font-bold flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        Estimasi Navigasi
                    </div>
                    <div class="flex gap-3">

                        <button onclick="window.exitRoute()"
                            class="text-emerald-700 dark:text-emerald-400 font-bold hover:underline flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali
                        </button>
                    </div>
                </div>
                <div
                    class="flex justify-between border-b border-emerald-100 dark:border-emerald-800 pb-1.5 mb-1.5 opacity-80">
                    <span>Total Jarak:</span> <span id="route-distance" class="font-bold">-</span>
                </div>
                <div class="flex justify-between opacity-80">
                    <span>Estimasi Waktu:</span> <span id="route-time" class="font-bold">-</span>
                </div>
            </div>

            <!-- List Content -->
            <div id="toko-list" class="flex-1 overflow-y-auto px-4 pb-4 custom-scrollbar"></div>
        </div>

        <!-- Floating Element Map Search -->
        <div class="absolute top-4 right-4 z-[1000] w-[calc(100%-2rem)] md:w-80 left-4 md:left-auto mobile-search-wrap">
            <div class="relative group">
                <input type="text" id="map_search_input"
                    class="w-full h-11 px-4 py-2 bg-white/95 dark:bg-slate-800/95 backdrop-blur-sm shadow-lg border border-gray-200 dark:border-white/10 rounded-xl md:rounded-none focus:outline-none focus:ring-2 focus:ring-blue-500/50 text-gray-700 dark:text-white text-sm font-medium transition-all"
                    placeholder="Cari toko, alamat, atau lokasi..." oninput="window.syncAndSearch(this.value, 'map')">
                <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <div id="search-results" class="hidden dark:bg-slate-800/95 dark:border-white/10 dark:text-white"></div>
            </div>
        </div>

    </div>

@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        let map;
        let routingLayer = null;
        let userPos = null;
        let tokos = [];
        let markers = [];
        let tempMarker = null;
        let isAddingMode = false;
        let searchCenter = null;
        let currentRouteData = null;
        let selectedTokoId = null;

        // Inisialisasi Map (Leaflet)
        window.onload = function () {
            initLeafletMap();
        };

        function initLeafletMap() {
            const defaultPos = [-6.200000, 106.816666];
            map = L.map('map', {
                zoomControl: true,
                attributionControl: false
            }).setView(defaultPos, 12);

            L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
                maxZoom: 19,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
            }).addTo(map);

            // Add custom "My Location" control inside the map (Google Maps style)
            L.Control.MyLocation = L.Control.extend({
                onAdd: function () {
                    const btn = L.DomUtil.create('button', '');
                    btn.innerHTML = '<svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm8.94 3A8.994 8.994 0 0 0 13 3.06V1h-2v2.06A8.994 8.994 0 0 0 3.06 11H1v2h2.06A8.994 8.994 0 0 0 11 20.94V23h2v-2.06A8.994 8.994 0 0 0 20.94 13H23v-2h-2.06zM12 19c-3.87 0-7-3.13-7-7s3.13-7 7-7 7 3.13 7 7-3.13 7-7 7z"/></svg>';
                    btn.style.cssText = 'width:40px;height:40px;background:white;border:none;border-radius:4px;display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 1px 5px rgba(0,0,0,0.3);color:#666;transition:all 0.2s;';
                    btn.title = 'Ke lokasi saya';
                    btn.onmouseover = () => { btn.style.background = '#f5f5f5'; btn.style.color = '#333'; };
                    btn.onmouseout = () => { btn.style.background = 'white'; btn.style.color = '#666'; };
                    btn.onclick = (e) => { e.stopPropagation(); goToMyLocation(); };
                    L.DomEvent.disableClickPropagation(btn);
                    return btn;
                }
            });
            new L.Control.MyLocation({ position: 'bottomright' }).addTo(map);

            setTimeout(() => {
                if (map) map.invalidateSize();
            }, 500);

            map.on('click', (e) => {
                if (isAddingMode) handleMapClick(e);
            });

            getUserLocation();
            fetchTokos(); // Load data immediately, don't wait for GPS
        }

        // Pencarian Lokasi (Nominatim OSM)
        let searchTimeout;
        async function handleMapSearch(query) {
            const resultsEl = document.getElementById('search-results');
            if (!query || query.length < 3) {
                resultsEl.innerHTML = '';
                resultsEl.classList.add('hidden');
                return;
            }

            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(async () => {
                try {
                    const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=id&limit=5`);
                    const data = await res.json();

                    if (data.length > 0) {
                        resultsEl.innerHTML = data.map(item => `
                                                                                                                                                                                                                                                                                                <div class="search-item" onclick="selectSearchLocation(${item.lat}, ${item.lon}, '${item.display_name.replace(/'/g, "\\'")}')">
                                                                                                                                                                                                                                                                                                    ${item.display_name}
                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                            `).join('');
                        resultsEl.classList.remove('hidden');
                    } else {
                        resultsEl.innerHTML = '<div class="p-3 text-xs text-gray-400">Lokasi tidak ditemukan</div>';
                        resultsEl.classList.remove('hidden');
                    }
                } catch (err) { console.error(err); }
            }, 800); // Slightly longer debounce for network request
        }

        // Search Unification & Optimization
        let renderTimeout;
        window.syncAndSearch = function (value, source) {
            // Sync values between inputs
            const sidebarSearch = document.getElementById('searchToko');
            const mapSearch = document.getElementById('map_search_input');

            if (source === 'sidebar' && mapSearch) mapSearch.value = value;
            if (source === 'map' && sidebarSearch) sidebarSearch.value = value;

            // Trigger local store search (optimized)
            clearTimeout(renderTimeout);
            renderTimeout = setTimeout(() => {
                renderList();
            }, 100); // Short debounce for local filtering

            // Trigger map location search
            handleMapSearch(value);
        }

        function selectSearchLocation(lat, lon, displayName) {
            const pos = [lat, lon];
            map.flyTo(pos, 16, {
                duration: 1.5,
                easeLinearity: 0.25
            });
            document.getElementById('map_search_input').value = displayName;
            window.collapseBottomSheet();

            // Show "Search Here" banner in sidebar
            const banner = document.getElementById('info-search-area');
            const nameNode = document.getElementById('search-center-name');
            const btnSearchHere = document.getElementById('btn-search-here');

            banner.classList.remove('hidden');
            nameNode.innerText = displayName;
            btnSearchHere.onclick = () => window.setSearchCenter(lat, lon, displayName);

            if (isAddingMode) {
                handleMapClick({ latlng: { lat, lng: lon } }, { name: displayName });
            } else {
                if (tempMarker) map.removeLayer(tempMarker);
                tempMarker = L.marker(pos).addTo(map).bindPopup('Lokasi Terpilih').openPopup();

                Swal.fire({
                    title: 'Lokasi Ditemukan',
                    text: `Titik "${displayName}" ditemukan. Anda ingin mendaftarkannya sebagai Toko Canvasing baru?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Daftarkan',
                    cancelButtonText: 'Hanya Lihat',
                    confirmButtonColor: '#2563eb',
                    customClass: {
                        popup: 'rounded-none',
                        confirmButton: 'bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-none text-xs uppercase tracking-wider',
                        cancelButton: 'bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded-none text-xs uppercase tracking-wider'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        toggleAddMode();
                        handleMapClick({ latlng: { lat, lng: lon } }, { name: displayName });
                    }
                });
            }
        }

        function getUserLocation() {
            const statusEl = document.getElementById('user-location-status');
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        userPos = { lat: position.coords.latitude, lng: position.coords.longitude };
                        if (statusEl) statusEl.innerHTML = `<span class="text-green-600 font-bold">✅ Lokasi Anda Ditemukan</span>`;

                        L.circle([userPos.lat, userPos.lng], {
                            color: '#3b82f6', fillColor: '#3b82f6', fillOpacity: 0.2, radius: 100
                        }).addTo(map);

                        L.marker([userPos.lat, userPos.lng], {
                            icon: L.divIcon({
                                className: 'custom-div-icon',
                                html: `<div style='background-color:#3b82f6; width:12px; height:12px; border-radius:50%; border:2px solid white; box-shadow:0 0 5px rgba(0,0,0,0.3)'></div>`,
                                iconSize: [12, 12], iconAnchor: [6, 6]
                            }),
                            title: "Anda"
                        }).addTo(map);

                        const now = new Date();
                        const monthVal = (now.getMonth() + 1).toString().padStart(2, '0');
                        const picker = document.getElementById('filter-month');
                        if (picker) picker.value = `${now.getFullYear()}-${monthVal}`;

                        map.setView([userPos.lat, userPos.lng], 13);
                        renderList();
                    },
                    (error) => {
                        if (statusEl) statusEl.innerHTML = `<span class="text-amber-600 font-bold">⚠️ GPS ditolak.</span>`;
                    }
                );
            } else {
                if (statusEl) statusEl.innerHTML = `<span class="text-red-600">Geolocation tidak disupport.</span>`;
            }
        }

        async function fetchTokos() {
            document.getElementById('loading').classList.remove('hidden');

            const monthVal = document.getElementById('filter-month').value;
            const [year, month] = monthVal ? monthVal.split('-') : [new Date().getFullYear(), new Date().getMonth() + 1];

            try {
                const response = await fetch(`/api/toko?month=${month}&year=${year}`);
                const result = await response.json();
                tokos = result.data;
                if (userPos) {
                    tokos.forEach(toko => {
                        toko.dist_val = calculateHaversine(userPos.lat, userPos.lng, toko.latitude, toko.longitude);
                        toko.dist_text = (toko.dist_val / 1000).toFixed(2) + " km";
                    });
                }
                renderMarkers();
                renderList();
            } catch (error) { console.error(error); }
            finally { document.getElementById('loading').classList.add('hidden'); }
        }

        function renderMarkers() {
            markers.forEach(m => map.removeLayer(m));
            markers = [];

            tokos.forEach(toko => {
                let status;
                if (toko.is_new) {
                    status = { label: 'Toko Baru', color: '#1e40af', bg: '#bfdbfe' }; // blue
                } else {
                    status = toko.is_checked ?
                        { label: 'Sudah Cek', color: '#166534', bg: '#9AD872' } :
                        { label: 'Belum Cek', color: '#374151', bg: '#FF3737' };
                }

                const defaultFoto = window.location.origin + '/assets/1.png';
                const fotoUrl = toko.foto || defaultFoto;

                let markerColor;
                if (toko.is_new) {
                    markerColor = '#3b82f6'; // blue
                } else {
                    markerColor = toko.is_checked ? '#22c55e' : '#ef4444';
                }

                const marker = L.marker([toko.latitude, toko.longitude], {
                    icon: L.divIcon({
                        className: 'custom-marker',
                        html: `
                                                                                                                                                                                                                                                                                                <div class="marker-pin-wrapper" style="position: relative; width: 32px; height: 32px;">
                                                                                                                                                                                                                                                                                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 32px; height: 32px; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));">
                                                                                                                                                                                                                                                                                                        <path d="M12 21C16 17.5 19 14.4087 19 10.5C19 6.63401 15.866 3.5 12 3.5C8.13401 3.5 5 6.63401 5 10.5C5 14.4087 8 17.5 12 21Z" fill="${markerColor}" stroke="white" stroke-width="1.5"/>
                                                                                                                                                                                                                                                                                                        <circle cx="12" cy="10.5" r="3" fill="white"/>
                                                                                                                                                                                                                                                                                                    </svg>
                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                            `,
                        iconSize: [32, 32], iconAnchor: [16, 32], popupAnchor: [0, -32]
                    })
                }).addTo(map);

                const gmapsLink = `https://www.google.com/maps?q=${toko.latitude},${toko.longitude}`;
                const btnLabel = toko.is_checked ? 'Batal Cek (Bulan Ini)' : 'Tandai Selesai (Bulan Ini)';
                const btnClass = toko.is_checked ? 'bg-amber-500 hover:bg-amber-600' : 'bg-green-600 hover:bg-green-700';

                const contentString = `
                                                                                                                                                                                                                                                                                        <div class="p-2 font-sans max-w-[220px]">
                                                                                                                                                                                                                                                                                            <div class="flex justify-between items-start mb-1 gap-2">
                                                                                                                                                                                                                                                                                                <h3 class="font-bold text-sm m-0 leading-tight">${toko.nama}</h3>
                                                                                                                                                                                                                                                                                                <span class="text-[9px] px-2 py-0.5 rounded-none font-bold whitespace-nowrap" style="background:${status.bg}; color:${status.color}; border:1px solid rgba(0,0,0,0.05)">${status.label}</span>
                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                            <p class="text-[11px] text-gray-500 mb-3 leading-relaxed">${toko.alamat}</p>
                                                                                                                                                                                                                                                                                            <img src="${fotoUrl}" class="w-full h-24 object-cover rounded-none mb-3 shadow-sm border border-gray-100">
                                                                                                                                                                                                                                                                                            <div class="flex flex-col gap-2">
                                                                                                                                                                                                                                                                                                <button onclick="window.toggleStatus(${toko.id})" class="block w-full text-center bg-red-600 text-white text-[11px] font-bold py-2 rounded-none transition-colors shadow-md shadow-blue-100 ${toko.is_new ? 'hidden' : ''}">${btnLabel}</button>

                                                                                                                                                                                                                                                                                                <a href="${gmapsLink}" target="_blank"
                                                                                                                                                                                                                                                                                                   class="custom-gmaps-link block w-full text-center text-white bg-blue-600 hover:bg-blue-700 text-[11px] py-2 rounded-none shadow-md shadow-blue-100">
                                                                                                                                                                                                                                                                                                   Buka di Google Maps
                                                                                                                                                                                                                                                                                                </a>
                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                    `;

                marker.bindPopup(contentString);
                marker.tokoId = toko.id;

                markers.push(marker);
            });
        }

        function renderList() {
            const container = document.getElementById('toko-list');
            const searchValue = document.getElementById('searchToko').value.toLowerCase();

            let filteredTokos = tokos.filter(toko => {
                const nama = (toko.nama || '').toLowerCase();
                const alamat = (toko.alamat || '').toLowerCase();
                return nama.includes(searchValue) || alamat.includes(searchValue);
            });

            // Filter by radius if searchCenter is active (City-based filtering)
            if (searchCenter) {
                filteredTokos = filteredTokos.filter(toko =>
                    calculateHaversine(searchCenter.lat, searchCenter.lng, toko.latitude, toko.longitude) <= 20000
                );
            }

            updateStatusSummary(filteredTokos);

            if (filteredTokos.length === 0) {
                container.innerHTML = '<div class="text-sm text-gray-500 text-center py-4">Tidak ada toko yang cocok.</div>';
                return;
            }

            // Build HTML string first for performance
            let listHtml = '';
            filteredTokos.forEach(toko => {
                const isSelected = toko.id === selectedTokoId;
                let status;
                if (toko.is_new) {
                    status = { label: 'Toko Baru (< 1 Bln)', bg: 'inline-flex items-center rounded-full text-xs justify-center px-2 py-1 bg-blue-100 text-blue-800', text: 'text-blue-800 dark:text-blue-400' };
                } else {
                    status = toko.is_checked ?
                        { label: 'Sudah Cek', bg: 'inline-flex items-center rounded-full text-xs justify-center px-2 py-1 bg-lightgreen-100 ', text: 'text-black dark:text-green-400' } :
                        { label: 'Belum Cek', bg: 'inline-flex items-center rounded text-xs justify-center px-2 py-1 bg-lightred text-black', text: 'text-white dark:text-gray-300' };
                }

                const defaultFoto = window.location.origin + '/assets/1.png';
                const fotoUrl = toko.foto || defaultFoto;

                const distRef = searchCenter || userPos;
                const distLabel = searchCenter ? `dari ${searchCenter.name}` : `dari lokasi Anda`;
                const distHtml = distRef && toko.dist_val ? `<span class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-2 py-0.5 rounded text-[10px] font-semibold dark:bg-black border border-gray-200 dark:border-white/10">${(toko.dist_val / 1000).toFixed(2)} km ${distLabel}</span>` : '';
                const phoneHtml = toko.no_telp_mitra ? `<div class="text-xs text-gray-500 dark:text-gray-400 font-medium flex items-center mt-2"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg> ${toko.no_telp_mitra}</div>` : '';
                const imgHtml = `<img src="${fotoUrl}" class="w-full h-24 object-cover rounded-none border border-gray-100 dark:border-white/10 mt-2 shadow-sm">`;

                const selectedClasses = isSelected ? 'border-blue-500 dark:border-blue-400 ring-1 ring-blue-500 dark:ring-blue-400 shadow-lg' : 'border-gray-200 dark:border-white/10 shadow-sm';

                listHtml += `
                                                                                                                                                                                                            <div id="card-${toko.id}" class="border ${selectedClasses} dark:bg-black rounded-none p-4 hover:shadow-md bg-white dark:bg-slate-800 flex flex-col gap-1 cursor-pointer transition-all mb-3 relative" onclick="window.handleCardClick(${toko.latitude}, ${toko.longitude}, ${toko.id})">
                                                                                                                                                                                                                <div class="flex justify-between items-start w-full gap-2">
                                                                                                                                                                                                                    <h3 class="font-bold text-gray-900 dark:text-white text-[13px] leading-snug break-words">${toko.nama}</h3>
                                                                                                                                                                                                                    <span class="px-2.5 py-1 rounded-none text-[10px] font-bold ${status.bg} ${status.text} shrink-0">${status.label}</span>
                                                                                                                                                                                                                </div>

                                                                                                                                                                                                                <div class="flex items-start text-gray-500 dark:text-gray-400 text-[11px] mt-1">
                                                                                                                                                                                                                    <svg class="w-3.5 h-3.5 mr-1 shrink-0 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                                                                                                                                                                                    <span class="break-words leading-relaxed">${toko.alamat}</span>
                                                                                                                                                                                                                </div>

                                                                                                                                                                                                                ${imgHtml}

                                                                                                                                                                                                                <div class="flex flex-col mt-3 gap-2">
                                                                                                                                                                                                                    <div class="flex justify-between items-center">
                                                                                                                                                                                                                        <div class="flex flex-col">
                                                                                                                                                                                                                            ${phoneHtml ? phoneHtml.replace('mt-2', 'mt-0') : ''}
                                                                                                                                                                                                                            ${distHtml ? `<div class="mt-1">${distHtml}</div>` : ''}
                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                        <button onclick="event.stopPropagation(); window.showDirectRoute(${toko.latitude}, ${toko.longitude}, '${toko.nama.replace(/'/g, "\\'")}')" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-none text-[10px] font-bold transition-colors flex items-center gap-1 shrink-0 shadow-md">
                                                                                                                                                                                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A2 2 0 013 15.382V5.448a2 2 0 011.106-1.79L9 2l5 2.5 5.447-2.724A2 2 0 0121 3.552v9.934a2 2 0 01-1.106 1.791L15 18l-6 2z"></path></svg>
                                                                                                                                                                                                                            Rute
                                                                                                                                                                                                                        </button>
                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                    <button onclick="event.stopPropagation(); window.showProposedItems(${toko.id})" class="w-full btn  hover:bg-blue-700  py-2 rounded-none text-[10px] font-bold transition-colors uppercase tracking-widest shadow-sm">
                                                                                                                                                                                                                        Daftar Barang Diajukan
                                                                                                                                                                                                                    </button>
                                                                                                                                                                                                                </div>

                                                                                                                                                                                                                <input type="checkbox" class="hidden" id="cb-${toko.id}" value="${toko.id}">

                                                                                                                                                                                                                <div id="check-icon-${toko.id}" class="absolute -top-2 -right-2 bg-blue-500 text-white rounded-full p-1 hidden shadow border-2 border-white">
                                                                                                                                                                                                                     <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                                                                                                                                                                                </div>
                                                                                                                                                                                                            </div>
                                                                                                                                                                                                        `;
            });

            container.innerHTML = listHtml;
        }

        function updateStatusSummary(filteredTokos) {
            const checkedCountEl = document.getElementById('checked-count');
            const uncheckedCountEl = document.getElementById('unchecked-count');

            if (!checkedCountEl || !uncheckedCountEl) return;

            const eligibleTokos = filteredTokos.filter(toko => !toko.is_new);
            const checkedCount = eligibleTokos.filter(toko => toko.is_checked).length;
            const uncheckedCount = eligibleTokos.length - checkedCount;

            checkedCountEl.textContent = checkedCount;
            uncheckedCountEl.textContent = uncheckedCount;
        }

        window.toggleCheckbox = function (id) {
            const cb = document.getElementById(`cb-${id}`);
            const icon = document.getElementById(`check-icon-${id}`);
            const card = document.getElementById(`card-${id}`);
            if (cb) {
                cb.checked = !cb.checked;
                if (cb.checked) {
                    icon.classList.remove('hidden');
                    card.classList.add('border-blue-500', 'ring-1', 'ring-blue-500');
                    card.classList.remove('border-gray-200');
                } else {
                    icon.classList.add('hidden');
                    card.classList.remove('border-blue-500', 'ring-1', 'ring-blue-500');
                    card.classList.add('border-gray-200');
                }
            }
        }

        window.handleCardClick = function (lat, lng, id) {
            window.panToToko(lat, lng, id);
        }

        window.toggleStatus = async function (id) {
            const monthVal = document.getElementById('filter-month').value;
            const [year, month] = monthVal ? monthVal.split('-') : [new Date().getFullYear(), new Date().getMonth() + 1];

            try {
                const res = await fetch(`/api/toko/${id}/toggle-status?month=${month}&year=${year}`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                const data = await res.json();
                if (data.status === 'success') {
                    const toko = tokos.find(t => t.id === id);
                    if (toko) {
                        toko.is_checked = data.is_checked;
                        renderMarkers();
                        renderList();
                    }
                }
            } catch (e) {
                console.error(e);
            }
        }

        window.handleMonthChange = function () {
            fetchTokos();
        }

        window.formatDuration = function (minutes) {
            if (minutes < 60) return `${minutes} Menit`;
            const h = Math.floor(minutes / 60);
            const m = minutes % 60;
            return m > 0 ? `${h} Jam ${m} Menit` : `${h} Jam`;
        }

        window.shareRouteWA = function () {
            if (!currentRouteData) return;
            const d = currentRouteData;
            let gmapsUrl = "";
            if (d.waypoints) {
                const origin = `${userPos.lat},${userPos.lng}`;
                const dest = d.waypoints[d.waypoints.length - 1].join(',');
                const wp = d.waypoints.slice(0, -1).map(p => `${p[0]},${p[1]}`).join('|');
                gmapsUrl = `https://www.google.com/maps/dir/?api=1&origin=${origin}&destination=${dest}&waypoints=${wp}&travelmode=driving`;
            } else {
                gmapsUrl = `https://www.google.com/maps/dir/?api=1&origin=${userPos.lat},${userPos.lng}&destination=${d.lat},${d.lng}&travelmode=driving`;
            }
            const text = `*Rencana Kunjungan Canvassing*\n\nTujuan: ${d.destName}\nJarak: ${d.dist} km\nEstimasi: ${d.time}\n\nNavigasi: ${gmapsUrl}`;
            window.open(`https://wa.me/?text=${encodeURIComponent(text)}`, '_blank');
        }

        window.shareAreaWA = function () {
            if (!searchCenter) return;
            const filtered = tokos.filter(t => calculateHaversine(searchCenter.lat, searchCenter.lng, t.latitude, t.longitude) <= 20000);
            let text = `*Daftar Toko di Area ${searchCenter.name}:*\n\n`;
            filtered.forEach((t, i) => {
                const gmapsUrl = `https://www.google.com/maps?q=${t.latitude},${t.longitude}`;
                text += `${i + 1}. *${t.nama}*\n   Alamat: ${t.alamat}\n   Peta: ${gmapsUrl}\n\n`;
            });
            window.open(`https://wa.me/?text=${encodeURIComponent(text)}`, '_blank');
        }

        window.toggleFilter = function () {
            Swal.fire({
                title: 'Opsi & Filter',
                html: `
                                                                                                                                                                                                                                                                                        <div class="space-y-4 text-left p-2">
                                                                                                                                                                                                                                                                                            <div id="user-location-info" class="text-[11px] bg-gray-50 p-3 border border-gray-100 mb-4">
                                                                                                                                                                                                                                                                                                ${userPos ? '<span class="text-green-600 font-bold">✅ Lokasi GPS Aktif</span>' : '<span class="text-amber-600 font-bold">⚠️ GPS Tidak Aktif</span>'}
                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Urutan List</p>
                                                                                                                                                                                                                                                                                            <div class="grid grid-cols-2 gap-2">
                                                                                                                                                                                                                                                                                                <button onclick="Swal.clickConfirm(); sortToko('asc')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-none text-[10px] transition shadow-md uppercase tracking-wider">Terdekat</button>
                                                                                                                                                                                                                                                                                                <button onclick="Swal.clickConfirm(); sortToko('desc')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-none text-[10px] transition shadow-md uppercase tracking-wider">Terjauh</button>
                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                            <hr class="border-gray-100">
                                                                                                                                                                                                                                                                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Alat Jalan</p>
                                                                                                                                                                                                                                                                                            <button onclick="Swal.clickConfirm(); calculateRoute()" class="w-full btn btn-primary py-3 rounded-none font-bold text-xs transition shadow-md uppercase tracking-widest flex items-center justify-center gap-2">
                                                                                                                                                                                                                                                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A2 2 0 013 15.382V5.448a2 2 0 011.106-1.79L9 2l5 2.5 5.447-2.724A2 2 0 0121 3.552v9.934a2 2 0 01-1.106 1.791L15 18l-6 2z"></path></svg>
                                                                                                                                                                                                                                                                                                Mulai Rute
                                                                                                                                                                                                                                                                                            </button>
                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                    `,
                showConfirmButton: false,
                showCloseButton: true,
                customClass: { popup: 'rounded-none' }
            });
        }

        window.setSearchCenter = function (lat, lng, name) {
            searchCenter = { lat, lng, name };
            document.getElementById('main-controls').classList.add('hidden');
            tokos.forEach(toko => {
                toko.dist_val = calculateHaversine(lat, lng, toko.latitude, toko.longitude);
            });
            tokos.sort((a, b) => a.dist_val - b.dist_val);
            renderMarkers();
            renderList();
            window.collapseBottomSheet();
        }

        window.clearSearchCenter = function () {
            searchCenter = null;
            document.getElementById('info-search-area').classList.add('hidden');
            document.getElementById('main-controls').classList.remove('hidden');
            document.getElementById('map_search_input').value = '';
            if (userPos) {
                tokos.forEach(toko => {
                    toko.dist_val = calculateHaversine(userPos.lat, userPos.lng, toko.latitude, toko.longitude);
                });
                tokos.sort((a, b) => a.dist_val - b.dist_val);
            }
            renderMarkers();
            renderList();
        }

        window.exitRoute = function () {
            if (routingLayer) map.removeLayer(routingLayer);
            document.getElementById('route-info').classList.add('hidden');
            document.getElementById('sidebar-header').classList.remove('hidden');
            if (searchCenter) {
                document.getElementById('main-controls').classList.add('hidden');
            }
        }

        window.panToToko = function (lat, lng, id) {
            selectedTokoId = id;
            renderList();
            map.flyTo([lat, lng], 16, {
                duration: 1.2,
                easeLinearity: 0.25
            });
            const marker = markers.find(m => m.tokoId === id);
            window.collapseBottomSheet();
        }

        window.showProposedItems = async function (tokoId) {
            const toko = tokos.find(t => String(t.id) === String(tokoId));
            const namaToko = toko ? toko.nama : 'Detail Mitra';
            const kodeMitra = toko ? toko.kode_mitra : '-';

            Swal.fire({
                title: 'Memuat Data...',
                didOpen: () => { Swal.showLoading(); },
                allowOutsideClick: false,
                customClass: { popup: 'rounded-3xl' }
            });

            try {
                const res = await fetch(`/api/toko/${tokoId}/items-by-id`, {
                    headers: { 'Accept': 'application/json' }
                });

                if (!res.ok) throw new Error(`Gagal memuat data (${res.status})`);
                const json = await res.json();

                if (json.status === 'success') {
                    const items = json.data || [];
                    const transactions = json.transactions || [];
                    const totalTransVal = transactions.reduce((sum, tr) => sum + (Number(tr.total) || 0), 0);

                    let itemsHtml = '';
                    if (items.length > 0) {
                        items.forEach(item => {
                            const namaProduk = item.produk ? item.produk.nama_produk : 'Produk Tidak Diketahui';
                            const stokProduk = item.produk && item.produk.stok !== undefined && item.produk.stok !== null ? Number(item.produk.stok) : null;
                            const satuanProduk = item.produk && item.produk.satuan && item.produk.satuan.nama ? item.produk.satuan.nama : '';

                            itemsHtml += `
                                        <div class="group p-4 border-b border-slate-50 dark:border-white/5 hover:bg-slate-50/50 dark:hover:bg-white/5 transition-all">
                                            <div class="flex items-center justify-between gap-4">
                                                <div class="flex items-center gap-4 flex-1 min-w-0">
                                                    <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:rotate-6 transition-transform flex-shrink-0">
                                                        <i class="ph ph-package text-2xl"></i>
                                                    </div>
                                                    <div class="min-w-0">
                                                        <p class="text-[14px] font-black text-slate-900 dark:text-white truncate mb-0.5 leading-tight">${namaProduk}</p>
                                                        <div class="flex items-center gap-2">
                                                            <span class="text-[9px] px-2 py-0.5 bg-slate-100 dark:bg-white/10 rounded font-bold text-slate-500 dark:text-slate-400 tracking-wider uppercase">${item.kode_produk}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-right flex-shrink-0">
                                                    <div class="flex items-baseline justify-end gap-1 mb-0.5">
                                                        <span class="text-[10px] font-bold text-slate-400">Rp</span>
                                                        <p class="text-[16px] font-black text-blue-600 dark:text-blue-400 leading-none tracking-tight">${new Intl.NumberFormat('id-ID').format(item.harga || 0)}</p>
                                                    </div>
                                                    <div class="flex items-center justify-end gap-1.5">
                                                        <div class="w-1.5 h-1.5 rounded-full ${stokProduk > 0 ? 'bg-emerald-500' : 'bg-slate-300'}"></div>
                                                        <p class="text-[11px] font-bold ${stokProduk > 0 ? 'text-emerald-600' : 'text-slate-400'} tracking-tighter">
                                                            ${stokProduk !== null ? new Intl.NumberFormat('id-ID').format(stokProduk) : '0'} ${satuanProduk}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                        });
                    } else {
                        itemsHtml = `
                                    <div class="flex flex-col items-center justify-center py-20 text-slate-300">
                                        <div class="w-16 h-16 rounded-3xl bg-slate-50 dark:bg-white/5 flex items-center justify-center mb-4">
                                            <i class="ph ph-package-open text-4xl opacity-20"></i>
                                        </div>
                                        <p class="text-xs font-bold uppercase tracking-widest opacity-50">Belum ada barang diajukan</p>
                                    </div>
                                `;
                    }

                    let transHtml = '';
                    if (transactions.length > 0) {
                        transactions.forEach(tr => {
                            const date = new Date(tr.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
                            transHtml += `
                                        <a href="/transaksi/${tr.id}" target="_blank" class="block p-4 rounded-3xl border border-slate-100 dark:border-white/5 bg-white dark:bg-slate-900/50 mb-3 hover:border-blue-400/30 hover:shadow-xl hover:shadow-blue-500/5 transition-all group relative overflow-hidden">
                                            <div class="flex justify-between items-center relative z-10">
                                                <div class="flex items-center gap-3 min-w-0">
                                                    <div class="w-11 h-11 rounded-2xl bg-slate-50 dark:bg-white/5 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all shadow-sm">
                                                        <i class="ph ph-receipt text-xl"></i>
                                                    </div>
                                                    <div class="min-w-0">
                                                        <p class="text-[13px] font-black text-slate-900 dark:text-white group-hover:text-blue-600 transition-colors truncate mb-0.5 leading-tight">${tr.kode_transaksi}</p>
                                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">${date}</p>
                                                    </div>
                                                </div>
                                                <div class="text-right flex-shrink-0">
                                                    <div class="flex items-baseline justify-end gap-1 mb-0.5">
                                                        <span class="text-[9px] font-bold text-slate-400">Rp</span>
                                                        <p class="text-[15px] font-black text-emerald-600 dark:text-emerald-400 leading-none tracking-tight">${new Intl.NumberFormat('id-ID').format(tr.total || 0)}</p>
                                                    </div>
                                                    <p class="text-[10px] font-bold text-blue-500 uppercase tracking-widest flex items-center justify-end gap-1">
                                                        Detail <i class="ph ph-arrow-right text-xs"></i>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="absolute inset-0 bg-gradient-to-r from-blue-600/0 to-blue-600/0 group-hover:to-blue-600/[0.03] transition-all"></div>
                                        </a>
                                    `;
                        });
                    } else {
                        transHtml = `
                                    <div class="flex flex-col items-center justify-center py-20 text-slate-300">
                                        <div class="w-16 h-16 rounded-3xl bg-slate-50 dark:bg-white/5 flex items-center justify-center mb-4">
                                            <i class="ph ph-receipt text-4xl opacity-20"></i>
                                        </div>
                                        <p class="text-xs font-bold uppercase tracking-widest opacity-50">Belum ada riwayat transaksi</p>
                                    </div>
                                `;
                    }

                    const dashboardHtml = `
                                <div class="flex flex-col h-full bg-white dark:bg-black font-sans text-slate-900 dark:text-white overflow-hidden">
                                    <!-- Premium Header -->
                                    <div class="p-8 md:p-10 border-b border-slate-100 dark:border-white/10 bg-white dark:bg-black">
                                        <div class="flex flex-col md:flex-row justify-between items-center gap-8">
                                            <!-- Left: Profile -->
                                            <div class="flex items-center gap-5 w-full md:w-auto">
                                                <div class="w-16 h-16 rounded-[2rem] bg-blue-600 flex items-center justify-center text-white shadow-2xl shadow-blue-500/20">
                                                    <i class="ph ph-storefront text-3xl"></i>
                                                </div>
                                                <div>
                                                    <div class="flex items-center gap-3 mb-1.5">
                                                        <span class="px-2.5 py-0.5 bg-blue-600 text-white text-[10px] font-black rounded uppercase tracking-[0.2em]">Partner</span>
                                                        <div class="flex items-center gap-1.5 px-2 py-0.5 bg-emerald-50 dark:bg-emerald-500/10 rounded">
                                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                                            <span class="text-[10px] font-black text-emerald-600 uppercase tracking-tighter">Active</span>
                                                        </div>
                                                    </div>
                                                    <h3 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white leading-none">${namaToko}</h3>
                                                    <div class="flex items-center gap-2 text-slate-400 text-xs mt-2">
                                                        <i class="ph ph-fingerprint text-blue-500"></i>
                                                        <span class="font-bold tracking-[0.15em] uppercase">${kodeMitra}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Right: Summary Stats -->

                                        </div>
                                    </div>

                                    <!-- Dashboard Content -->
                                    <div class="flex flex-col md:flex-row gap-8 p-8 md:p-10 bg-slate-50/30 dark:bg-black/40 overflow-y-auto md:overflow-hidden" style="max-height: 65vh;">
                                        <!-- Left: Proposed Items -->
                                        <div class="w-full md:w-1/2 flex flex-col bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-white/5 rounded-[2.5rem] overflow-hidden shadow-2xl shadow-slate-200/20 dark:shadow-none">
                                            <div class="px-6 py-5 border-b border-slate-100 dark:border-white/5 flex justify-between items-center bg-white dark:bg-slate-900/50 backdrop-blur-md">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/30">
                                                        <i class="ph ph-shopping-bag-open text-xl"></i>
                                                    </div>
                                                    <h4 class="text-[13px] font-black uppercase tracking-widest text-slate-900 dark:text-white">Daftar Pengajuan</h4>
                                                </div>
                                                <span class="text-[10px] font-black px-3 py-1 bg-blue-50 dark:bg-blue-600/10 text-blue-600 dark:text-blue-400 rounded-full uppercase tracking-tighter border border-blue-100 dark:border-blue-500/10">
                                                    ${items.length} SKU
                                                </span>
                                            </div>
                                            <div class="flex-1 overflow-y-auto custom-scrollbar p-2">
                                                ${itemsHtml}
                                            </div>
                                        </div>

                                        <!-- Right: Transaction History -->
                                        <div class="w-full md:w-1/2 flex flex-col bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-white/5 rounded-[2.5rem] overflow-hidden shadow-2xl shadow-slate-200/20 dark:shadow-none">
                                            <div class="px-6 py-5 border-b border-slate-100 dark:border-white/5 flex justify-between items-center bg-white dark:bg-slate-900/50 backdrop-blur-md">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-9 h-9 rounded-xl bg-emerald-600 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30">
                                                        <i class="ph ph-clock-counter-clockwise text-xl"></i>
                                                    </div>
                                                    <h4 class="text-[13px] font-black uppercase tracking-widest text-slate-900 dark:text-white">Riwayat Sesi</h4>
                                                </div>
                                                <span class="text-[10px] font-black px-3 py-1 bg-emerald-50 dark:bg-emerald-600/10 text-emerald-600 dark:text-emerald-400 rounded-full uppercase tracking-tighter border border-emerald-100 dark:border-emerald-500/10">
                                                    ${transactions.length} Sesi
                                                </span>
                                            </div>
                                            <div class="flex-1 overflow-y-auto custom-scrollbar p-6">
                                                ${transHtml}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Footer -->
                                    <div class="p-8 md:p-10 pt-0 flex justify-end bg-slate-50/30 dark:bg-black/40">
                                        <button onclick="Swal.close()" class="group w-full btn  md:w-auto px-12 py-5  dark:bg-white text-white dark:text-slate-900 rounded-[2rem] font-black text-xs uppercase tracking-[0.2em] shadow-2xl hover:bg-blue-600 dark:hover:bg-blue-600 hover:text-white dark:hover:text-white hover:scale-105 active:scale-95 transition-all flex items-center justify-center gap-3">
                                            <span>Tutup Dashboard</span>
                                            <i class="ph ph-arrow-right text-lg transition-transform group-hover:translate-x-1"></i>
                                        </button>
                                    </div>
                                </div>
                            `;

                    Swal.fire({
                        html: dashboardHtml,
                        showConfirmButton: false,
                        width: 'min(98vw, 1200px)',
                        padding: '0',
                        background: document.documentElement.classList.contains('dark') ? '#000000' : '#ffffff',
                        customClass: {
                            popup: 'rounded-[3rem] overflow-hidden border border-slate-200 dark:border-white/10 shadow-2xl shadow-blue-500/10',
                            htmlContainer: 'p-0 m-0'
                        },
                        allowOutsideClick: true
                    });
                } else {
                    throw new Error(json.message || 'Gagal memuat dashboard mitra.');
                }
            } catch (e) {
                console.error(e);
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan',
                    text: e.message || 'Gagal memuat dashboard mitra.',
                    customClass: { popup: 'rounded-3xl' }
                });
            }
        }

        window.showDirectRoute = async function (lat, lng, nama) {
            if (!userPos) return alert("Mohon aktifkan lokasi GPS Anda.");

            if (routingLayer) map.removeLayer(routingLayer);

            try {
                const url = `https://router.project-osrm.org/route/v1/driving/${userPos.lng},${userPos.lat};${lng},${lat}?overview=full&geometries=geojson`;
                const response = await fetch(url);
                const data = await response.json();

                if (data.code === 'Ok' && data.routes.length > 0) {
                    const route = data.routes[0];
                    routingLayer = L.geoJSON(route.geometry, {
                        style: { color: '#3b82f6', weight: 6, opacity: 0.8 }
                    }).addTo(map);

                    map.fitBounds(routingLayer.getBounds(), { padding: [50, 50] });

                    const distanceKm = (route.distance / 1000).toFixed(2);
                    const durationText = formatDuration(Math.round(route.duration / 60));

                    document.getElementById('route-info').classList.remove('hidden');
                    document.getElementById('sidebar-header').classList.add('hidden');
                    document.getElementById('route-distance').innerText = `${distanceKm} km (Jalan Raya)`;
                    document.getElementById('route-time').innerText = durationText;

                    // Set data for sharing
                    window.currentRouteData = {
                        destName: nama,
                        dist: distanceKm,
                        time: durationText,
                        lat: lat,
                        lng: lng
                    };
                    window.collapseBottomSheet();
                } else {
                    // Fallback to straight line
                    const routePoints = [[userPos.lat, userPos.lng], [lat, lng]];
                    routingLayer = L.polyline(routePoints, { color: '#3b82f6', weight: 5, dashArray: '10, 10' }).addTo(map);
                    alert("Routing jalan tidak tersedia, menampilkan garis rute udara.");
                }
            } catch (e) {
                console.error(e);
                alert("Terjadi kesalahan saat mengambil rute jalan.");
            }
        }

        function sortToko(order) {
            if (!userPos) return alert("Lokasi Anda belum divalidasi GPS.");

            tokos.forEach(toko => {
                toko.dist_val = calculateHaversine(userPos.lat, userPos.lng, toko.latitude, toko.longitude);
                toko.dist_text = (toko.dist_val / 1000).toFixed(2) + " km";
            });

            if (order === 'asc') tokos.sort((a, b) => a.dist_val - b.dist_val);
            else tokos.sort((a, b) => b.dist_val - a.dist_val);
            renderList();
        }

        async function calculateRoute() {
            if (!userPos) return alert("Aktifkan izin lokasi browser.");

            let selectedTokos;
            let selectedIds = [];
            const checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');

            if (checkboxes.length === 0) {
                selectedTokos = tokos;
                selectedIds = tokos.map(t => t.id);
                if (selectedTokos.length === 0) return alert("Pilih minimal satu toko atau pastikan ada toko di daftar.");
            } else {
                selectedIds = Array.from(checkboxes).map(cb => parseInt(cb.value));
                selectedTokos = selectedIds.map(id => tokos.find(t => t.id === id));
            }

            if (routingLayer) map.removeLayer(routingLayer);

            // Build coordinate string for OSRM
            const coords = [`${userPos.lng},${userPos.lat}`, ...selectedTokos.map(t => `${t.longitude},${t.latitude}`)].join(';');

            try {
                const url = `https://router.project-osrm.org/route/v1/driving/${coords}?overview=full&geometries=geojson`;
                const response = await fetch(url);
                const data = await response.json();

                if (data.code === 'Ok' && data.routes.length > 0) {
                    const route = data.routes[0];
                    routingLayer = L.geoJSON(route.geometry, {
                        style: { color: '#059669', weight: 6, opacity: 0.8 }
                    }).addTo(map);
                    map.fitBounds(routingLayer.getBounds(), { padding: [50, 50] });

                    const distanceKm = (route.distance / 1000).toFixed(2);
                    const durationText = formatDuration(Math.round(route.duration / 60));

                    document.getElementById('route-info').classList.remove('hidden');
                    document.getElementById('sidebar-header').classList.add('hidden');
                    document.getElementById('route-distance').innerText = `${distanceKm} km (Total)`;
                    document.getElementById('route-time').innerText = durationText;

                    // Set data for sharing (multiple)
                    window.currentRouteData = {
                        destName: selectedTokos[selectedTokos.length - 1].nama + " (Multi-Stop)",
                        dist: distanceKm,
                        time: durationText,
                        waypoints: selectedTokos.map(t => [t.latitude, t.longitude])
                    };
                    window.collapseBottomSheet();

                    // Save route to DB
                    fetch('/api/rute', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ user_lat: userPos.lat, user_lng: userPos.lng, toko_ids: selectedIds })
                    });

                    if (confirm("Gunakan Google Maps untuk navigasi jalan yang akurat?")) {
                        const dest = selectedTokos[selectedTokos.length - 1];
                        const waypoints = selectedTokos.slice(0, -1).map(t => `${t.latitude},${t.longitude}`).join('|');
                        const urlGmaps = `https://www.google.com/maps/dir/?api=1&origin=${userPos.lat},${userPos.lng}&destination=${dest.latitude},${dest.longitude}&waypoints=${waypoints}&travelmode=driving`;
                        window.open(urlGmaps, '_blank');
                    }
                } else {
                    alert("Gagal menghitung rute jalan.");
                }
            } catch (e) {
                console.error(e);
                alert("Terjadi kesalahan saat menghubungi API rute.");
            }
        }

        function toggleAddMode() {
            isAddingMode = true;
            document.getElementById('add-mode-indicator').classList.remove('hidden');
            document.getElementById('btn-add-mode').classList.add('hidden');
            document.getElementById('main-controls').classList.add('hidden');
            document.getElementById('map').style.cursor = 'crosshair';
            window.collapseBottomSheet();
        }

        function cancelAddMode() {
            isAddingMode = false;
            document.getElementById('add-mode-indicator').classList.add('hidden');
            document.getElementById('btn-add-mode').classList.remove('hidden');
            document.getElementById('main-controls').classList.remove('hidden');
            document.getElementById('map').style.cursor = '';
            if (tempMarker) map.removeLayer(tempMarker);
            tempMarker = null;
        }

        function goToMyLocation() {
            if (userPos) {
                map.flyTo([userPos.lat, userPos.lng], 16, { duration: 0.8 });
            } else {
                navigator.geolocation.getCurrentPosition(
                    (pos) => {
                        userPos = { lat: pos.coords.latitude, lng: pos.coords.longitude };
                        map.flyTo([userPos.lat, userPos.lng], 16, { duration: 0.8 });
                    },
                    () => {
                        Swal.fire({ icon: 'warning', title: 'GPS Tidak Tersedia', text: 'Izinkan akses lokasi di browser Anda.', customClass: { popup: 'rounded-xl dark:bg-slate-900 dark:text-white' } });
                    },
                    { enableHighAccuracy: true }
                );
            }
        }

        function useCurrentLocationForStore() {
            if (!userPos) {
                Swal.fire({
                    icon: 'warning',
                    title: 'GPS Tidak Tersedia',
                    text: 'Pastikan pencarian lokasi/GPS browser Anda sudah diizinkan dan perangkat berhasil mendapatkan titik lokasi.',
                    customClass: { popup: 'rounded-none shadow-2xl dark:bg-slate-900 border dark:border-white/10 dark:text-white', confirmButton: 'text-xs font-bold px-6 py-2.5 rounded-md uppercase tracking-wider' }
                });
                return;
            }

            map.setView([userPos.lat, userPos.lng], 16);
            handleMapClick({ latlng: { lat: userPos.lat, lng: userPos.lng } }, { name: 'Lokasi Anda Saat Ini' });
        }

        function handleMapClick(e, placeObj = null) {
            if (!isAddingMode) return;

            const lat = e.latlng.lat;
            const lng = e.latlng.lng;

            if (tempMarker) map.removeLayer(tempMarker);
            tempMarker = L.marker([lat, lng], {
                icon: L.divIcon({
                    className: 'temp-marker',
                    html: `<div class='dark:bg-black' style='background-color:#ef4444; width:12px; height:12px; border-radius:50%; border:2px solid white; box-shadow:0 0 5px rgba(0,0,0,0.3)'></div>`,
                    iconSize: [12, 12], iconAnchor: [6, 6]
                })
            }).addTo(map);

            const defaultNama = placeObj ? placeObj.name : '';
            const defaultAlamat = placeObj ? placeObj.name : '';

            Swal.fire({
                title: 'Tambah Mitra Baru',
                html: `
                                                                                                                                                                                                                                                                                        <div class="text-left space-y-4 font-sans mt-4">
                                                                                                                                                                                                                                                                                            <div class="mb-3">
                                                                                                                                                                                                                                                                                                <label class="block text-[11px] font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">Titik Koordinat (Auto)</label>
                                                                                                                                                                                                                                                                                                <input type="text" value="${lat}, ${lng}" class="w-full border border-gray-200 dark:border-white/10 bg-gray-100 dark:bg-black dark:text-gray-400 rounded-md px-3 py-2 text-sm focus:outline-none cursor-not-allowed" disabled>
                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                            <div class="mb-3">
                                                                                                                                                                                                                                                                                                <label class="block text-[11px] font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">Nama Mitra *</label>
                                                                                                                                                                                                                                                                                                <input type="text" id="swal_new_nama" class="w-full border border-gray-300 dark:border-white/20 bg-white dark:bg-slate-900 dark:text-white rounded-md px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all placeholder-gray-400" value="${defaultNama}" placeholder="Contoh: Toko Maju Jaya">
                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                            <div class="mb-3">
                                                                                                                                                                                                                                                                                                <label class="block text-[11px] font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">No. Telp (Opsional)</label>
                                                                                                                                                                                                                                                                                                <input type="text" id="swal_new_no_telp" class="w-full border border-gray-300 dark:border-white/20 bg-white dark:bg-slate-900 dark:text-white rounded-md px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all placeholder-gray-400" placeholder="Contoh: 081234567890">
                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                            <div class="mb-3">
                                                                                                                                                                                                                                                                                                <label class="block text-[11px] font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">Alamat Lengkap *</label>
                                                                                                                                                                                                                                                                                                <textarea id="swal_new_alamat" class="w-full border border-gray-300 dark:border-white/20 bg-white dark:bg-slate-900 dark:text-white rounded-md px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all placeholder-gray-400 resize-none" rows="3" placeholder="Masukkan alamat lengkap dengan jelas...">${defaultAlamat}</textarea>
                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                    `,
                showCancelButton: true,
                confirmButtonText: 'Simpan Mitra',
                cancelButtonText: 'Batal',
                buttonsStyling: false,
                customClass: {
                    popup: 'rounded-xl shadow-2xl dark:bg-slate-900 border border-gray-100 dark:border-white/10',
                    title: 'text-xl font-black uppercase tracking-tight text-gray-900 dark:text-white',
                    confirmButton: 'bg-blue-600 text-white text-xs font-bold px-6 py-2.5 rounded-md uppercase tracking-wider shadow-sm hover:bg-blue-700 transition-all mx-2',
                    cancelButton: 'bg-red-500 text-white text-xs font-bold px-6 py-2.5 rounded-md uppercase tracking-wider shadow-sm hover:bg-red-600 transition-all mx-2'
                },
                preConfirm: () => {

                    const nama = document.getElementById('swal_new_nama').value;
                    const alamat = document.getElementById('swal_new_alamat').value;
                    const no_telp = document.getElementById('swal_new_no_telp').value;

                    if (!nama || !alamat) {
                        Swal.showValidationMessage('Nama Mitra dan Alamat Lengkap wajib diisi!');
                        return false;
                    }

                    return { nama, alamat, latitude: lat, longitude: lng, no_telp_mitra: no_telp };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    saveNewTokoModal(result.value);
                } else {
                    if (tempMarker) { map.removeLayer(tempMarker); tempMarker = null; }
                }
            });
        }

        async function saveNewTokoModal(payload) {
            Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, didOpen: () => Swal.showLoading(), customClass: { popup: 'rounded-none dark:bg-slate-900 dark:text-white' } });

            try {
                const res = await fetch('/canvassing/store', {
                    method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                if (data.status === 'success') {
                    tokos.push(data.data);
                    if (userPos) {
                        data.data.dist_val = calculateHaversine(userPos.lat, userPos.lng, data.data.latitude, data.data.longitude);
                        data.data.dist_text = (data.data.dist_val / 1000).toFixed(2) + " km";
                    }
                    renderMarkers();
                    renderList();
                    cancelAddMode();
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Mitra baru berhasil ditambahkan', timer: 1500, showConfirmButton: false, customClass: { popup: 'rounded-none dark:bg-slate-900 dark:text-white' } });
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: 'Gagal menyimpan data ke server.', customClass: { popup: 'rounded-none dark:bg-slate-900 dark:text-white' } });
                }
            } catch (e) {
                console.error(e);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan sistem.', customClass: { popup: 'rounded-none dark:bg-slate-900 dark:text-white' } });
            }
        }

        function calculateHaversine(lat1, lon1, lat2, lon2) {
            const R = 6371e3; const radLat1 = lat1 * Math.PI / 180; const radLat2 = lat2 * Math.PI / 180;
            const deltaLat = (lat2 - lat1) * Math.PI / 180; const deltaLon = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(deltaLat / 2) * Math.sin(deltaLat / 2) + Math.cos(radLat1) * Math.cos(radLat2) * Math.sin(deltaLon / 2) * Math.sin(deltaLon / 2);
            return R * (2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a)));
        }

        window.collapseBottomSheet = function () {
            if (window.innerWidth < 768) {
                const sheet = document.getElementById('canvassing-sidebar');
                if (sheet) {
                    sheet.classList.remove('expanded');
                    sheet.style.transform = '';
                }
            }
        }

        // Mobile Bottom Sheet Dragging logic
        const sheet = document.getElementById('canvassing-sidebar');
        const headerArea = document.getElementById('sidebar-header');
        const handle = document.querySelector('.sheet-handle');
        let startY, currentY;

        if (headerArea) {
            // Touch events for swiping the header
            headerArea.addEventListener('touchstart', (e) => {
                // Ignore touch if it's on an input or button
                if (e.target.tagName === 'INPUT' || e.target.tagName === 'BUTTON' || e.target.closest('button')) return;
                startY = e.touches[0].clientY;
                sheet.style.transition = 'none';
            }, { passive: true });

            headerArea.addEventListener('touchmove', (e) => {
                if (!startY) return;
                currentY = e.touches[0].clientY;
                const deltaY = startY - currentY;
                
                // Allow dragging down if expanded, or dragging up if collapsed
                if (deltaY > 20) { // Dragging up
                    sheet.classList.add('expanded');
                    sheet.style.transform = 'translateY(0)';
                } else if (deltaY < -20) { // Dragging down
                    sheet.classList.remove('expanded');
                    sheet.style.transform = '';
                }
            }, { passive: true });

            headerArea.addEventListener('touchend', () => {
                startY = null;
                sheet.style.transition = '';
            });

            // Click to toggle (only on header background, not inputs/buttons)
            headerArea.addEventListener('click', (e) => {
                if (e.target.tagName === 'INPUT' || e.target.tagName === 'BUTTON' || e.target.closest('button')) return;
                // Don't toggle if they just finished a drag
                if (currentY && Math.abs(startY - currentY) > 20) return;
                
                sheet.classList.toggle('expanded');
                if (sheet.classList.contains('expanded')) {
                    sheet.style.transform = 'translateY(0)';
                } else {
                    sheet.style.transform = '';
                }
            });
        }
        
        if (handle) {
             // Handle can also be tapped
             handle.addEventListener('click', () => {
                sheet.classList.toggle('expanded');
                if (sheet.classList.contains('expanded')) {
                    sheet.style.transform = 'translateY(0)';
                } else {
                    sheet.style.transform = '';
                }
             });
        }
    </script>
@endsection