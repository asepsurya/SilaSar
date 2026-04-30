<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        @if (Request::is('transaksi/nota/create/*'))
            Nota Konsinyasi
        @elseif (Request::is('transaksi/invoice/create/*'))
            Invoice
        @else
            Nota Pembayaran
        @endif
    </title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="shortcut icon" href="{{asset('assets/fav.png')}}" />

    <style>
        body {
            background-color: #0f0f0f;
            color: white;
            font-family: 'Inter', sans-serif;
        }

        /* Paper Styles */
        .workspace-bg {
            background-image: radial-gradient(#2a2a2a 1px, transparent 1px);
            background-size: 20px 20px;
        }

        .paper {
            background-color: white;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            transition: all 0.3s ease;
        }

        /* Hide scrollbar but allow scrolling */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        @media screen and (max-width: 768px) {
            #include-content {
                transform: scale(0.65);
                transform-origin: top center;
            }
        }

        /* Animations */
        @keyframes slideInUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .animate-slide-up {
            animation: slideInUp 0.5s ease-out forwards;
        }

        /* Print Styles */
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
            }

            .workspace-bg {
                background: none !important;
            }

            .paper {
                box-shadow: none !important;
                margin: 0 !important;
                width: 100% !important;
            }

            @page {
                size: A4;
                margin: 0;
            }
        }
    </style>
</head>

<body class="h-screen flex flex-col overflow-hidden">
    <!-- Topbar -->
    <header
        class="no-print h-16 bg-black/80 backdrop-blur-xl border-b border-white/10 flex items-center justify-between px-6 z-50">
        <div class="flex items-center gap-4">
            <a href="{{ route('nota.index') }}" class="p-2 hover:bg-white/10 rounded-xl transition-colors group">
                <i class="ph ph-arrow-left text-xl text-gray-400 group-hover:text-white"></i>
            </a>
            <div class="h-6 w-[1px] bg-white/10"></div>
            <div>
                <h2 class="text-sm font-bold text-white">
                    @if (Request::is('transaksi/nota/create/*')) Editor Nota Konsinyasi
                    @elseif (Request::is('transaksi/invoice/create/*')) Editor Invoice
                    @else Editor Dokumen @endif
                </h2>
                <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold">Workspace Mode</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button onclick="printArea()"
                class="flex items-center gap-2 px-4 py-2 hover:bg-white/5 rounded-xl transition-all text-gray-300 hover:text-white border border-transparent hover:border-white/10">
                <i class="ph ph-printer text-xl"></i>
                <span class="text-xs font-bold">Print</span>
            </button>
            <button onclick="downloadPDF()"
                class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-xl transition-all shadow-lg shadow-blue-600/20">
                <i class="ph ph-file-pdf text-xl"></i>
                <span class="text-xs font-bold">Export PDF</span>
            </button>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 flex overflow-hidden workspace-bg">
        <!-- Editor Area -->
        <div class="flex-1 overflow-auto p-8 flex justify-center no-scrollbar">
            <div id="print-area" class="paper p-12 min-h-[297mm] w-[210mm] animate-slide-up origin-top relative">
                <div id="include-content">
                    @if (Request::is('transaksi/nota/*'))
                        @include('transaksi.dokumen.laporan.konsinyasiMain')
                    @elseif (Request::is('transaksi/invoice/*'))
                        @include('transaksi.dokumen.laporan.KwitansiMain')
                    @else
                        @include('transaksi.dokumen.laporan.KwitansiMain')
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar Actions (Desktop) -->
        <aside
            class="no-print w-20 bg-black/40 border-l border-white/5 flex flex-col items-center py-8 gap-6 backdrop-blur-sm">
            <button id="submitButton" title="Simpan Dokumen"
                class="p-4 bg-emerald-500 hover:bg-emerald-600 rounded-2xl transition-all shadow-lg shadow-emerald-500/20 group">
                <i class="ph ph-floppy-disk text-2xl text-white"></i>
            </button>

            <button onclick="addRow()" title="Tambah Baris"
                class="p-4 bg-white/5 hover:bg-white/10 border border-white/10 rounded-2xl transition-all">
                <i class="ph ph-plus text-2xl text-white"></i>
            </button>

            <div class="h-[1px] w-8 bg-white/10"></div>

            <button id="settingsButton" title="Pengaturan Tampilan"
                class="p-4 hover:bg-white/10 rounded-2xl transition-all text-gray-400 hover:text-white">
                <i class="ph ph-gear-six text-2xl"></i>
            </button>
        </aside>
    </main>

    <!-- Settings Overlay -->
    <div id="settingsMenu"
        class="hidden fixed top-20 right-24 bg-black border border-white/10 p-6 rounded-3xl shadow-2xl z-[100] w-72 animate-in slide-in-from-right duration-300">
        <h3 class="text-sm font-bold mb-4 flex items-center gap-2">
            <i class="ph ph-eye"></i> Visibilitas & Layering
        </h3>
        <div class="space-y-4">
            <div class="space-y-2">
                <label
                    class="flex items-center justify-between p-3 bg-white/5 rounded-xl cursor-pointer hover:bg-white/10 transition-colors">
                    <span class="text-xs font-medium">Tanda Tangan</span>
                    <input type="checkbox"
                        class="form-checkbox h-4 w-4 rounded border-gray-700 bg-gray-800 text-blue-600 focus:ring-blue-500"
                        id="toggleSignature">
                </label>
                <div class="flex items-center gap-2 px-2">
                    <span class="text-[10px] text-gray-500">Layer:</span>
                    <button onclick="changeZIndex('signature', 1)"
                        class="px-2 py-1 bg-white/5 rounded text-[10px] hover:bg-white/10">Naik</button>
                    <button onclick="changeZIndex('signature', -1)"
                        class="px-2 py-1 bg-white/5 rounded text-[10px] hover:bg-white/10">Turun</button>
                </div>
            </div>

            <div class="space-y-2">
                <label
                    class="flex items-center justify-between p-3 bg-white/5 rounded-xl cursor-pointer hover:bg-white/10 transition-colors">
                    <span class="text-xs font-medium">Stempel</span>
                    <input type="checkbox"
                        class="form-checkbox h-4 w-4 rounded border-gray-700 bg-gray-800 text-blue-600 focus:ring-blue-500"
                        id="toggleStamp">
                </label>
                <div class="flex items-center gap-2 px-2">
                    <span class="text-[10px] text-gray-500">Layer:</span>
                    <button onclick="changeZIndex('stamp', 1)"
                        class="px-2 py-1 bg-white/5 rounded text-[10px] hover:bg-white/10">Naik</button>
                    <button onclick="changeZIndex('stamp', -1)"
                        class="px-2 py-1 bg-white/5 rounded text-[10px] hover:bg-white/10">Turun</button>
                </div>
            </div>

            <p class="text-[10px] text-gray-500 italic p-2 border-t border-white/5 mt-2">
                * Klik dan seret tanda tangan/stempel untuk mengubah posisi.
            </p>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        // Drag and Drop Logic
        function makeDraggable(elementId) {
            const el = document.getElementById(elementId);
            if (!el) return;

            let pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
            el.onmousedown = dragMouseDown;

            function dragMouseDown(e) {
                e = e || window.event;
                e.preventDefault();
                pos3 = e.clientX;
                pos4 = e.clientY;
                document.onmouseup = closeDragElement;
                document.onmousemove = elementDrag;
                el.style.cursor = 'grabbing';
            }

            function elementDrag(e) {
                e = e || window.event;
                e.preventDefault();
                pos1 = pos3 - e.clientX;
                pos2 = pos4 - e.clientY;
                pos3 = e.clientX;
                pos4 = e.clientY;
                el.style.top = (el.offsetTop - pos2) + "px";
                el.style.left = (el.offsetLeft - pos1) + "px";
            }

            function closeDragElement() {
                document.onmouseup = null;
                document.onmousemove = null;
                el.style.cursor = 'grab';
            }
        }

        function changeZIndex(id, delta) {
            const el = document.getElementById(id);
            if (!el) return;
            let currentZ = parseInt(window.getComputedStyle(el).zIndex) || 10;
            el.style.zIndex = currentZ + delta;
        }

        // Print Logic
        function printArea() {
            window.print();
        }

        // PDF Logic
        function downloadPDF() {
            const element = document.getElementById("print-area");
            const nomorNota = document.getElementById("nomor-nota")?.innerText || "DOKUMEN";
            const opt = {
                margin: 0,
                filename: `Nota_${nomorNota}.pdf`,
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 3, useCORS: true, backgroundColor: '#ffffff' },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };
            html2pdf().set(opt).from(element).save();
        }

        // Form Submission
        document.getElementById('submitButton').addEventListener('click', () => {
            document.getElementById('myForm').submit();
        });

        // Settings Menu
        const settingsButton = document.getElementById('settingsButton');
        const settingsMenu = document.getElementById('settingsMenu');
        settingsButton.addEventListener('click', (e) => {
            e.stopPropagation();
            settingsMenu.classList.toggle('hidden');
        });
        document.addEventListener('click', () => settingsMenu.classList.add('hidden'));
        settingsMenu.addEventListener('click', (e) => e.stopPropagation());

        // Visibility Toggles
        function handleVisibility(id, checked) {
            const el = document.getElementById(id);
            if (el) {
                el.style.display = checked ? 'block' : 'none';
                if (checked) {
                    el.style.position = 'absolute';
                    el.style.cursor = 'grab';
                    makeDraggable(id);
                }
            }
        }

        document.getElementById('toggleStamp').addEventListener('change', (e) => handleVisibility('stamp', e.target.checked));
        document.getElementById('toggleSignature').addEventListener('change', (e) => handleVisibility('signature', e.target.checked));

        // Flatpickr Initialization
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.flatpickr-input').forEach(el => {
                flatpickr(el, { dateFormat: "d M Y", defaultDate: el.value || "today" });
            });
            handleVisibility('stamp', false);
            handleVisibility('signature', false);
        });
    </script>
</body>

</html>