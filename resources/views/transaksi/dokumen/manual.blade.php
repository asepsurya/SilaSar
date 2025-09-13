<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
    @if (Request::is('transaksi/nota/create/*'))
       Nota Konsinyasi 
    @elseif (Request::is('transaksi/invoice/create/*'))
       INVOICE
    @else
       Nota Pembayaran
    @endif
    </title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
     <!-- Site favicon -->
    <link rel="shortcut icon" href="{{asset('assets/fav.png')}}" />
    <!-- Load ProseMirror dependencies (wajib) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.6.1/tinymce.min.js"
        integrity="sha512-bib7srucEhHYYWglYvGY+EQb0JAAW0qSOXpkPTMgCgW8eLtswHA/K4TKyD4+FiXcRHcy8z7boYxk0HTACCTFMQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <style>
          #print-area {
    width: 100%;
    height: auto;
    transform: scale(1);          /* pastikan tidak mengecil */
    transform-origin: top center;   /* scaling mulai dari kiri atas */
  }
         @media screen and (max-width: 768px) {
              #include-content{
                  transform: scale(0.7); /* perkecil biar muat layar */ transform-origin: top center;
              }
      }
            /* Gaya default untuk halaman */
            body {
                background-color: rgb(17, 24, 39); /* Warna latar belakang default */
                color: white;
                font-family: Arial, sans-serif;
            }

            /* Gaya khusus saat mencetak */
            @media print {
                 #floatingButton{
                     display: none !important;
                }
                 .header{
                     display: none !important;
                }
                body {
                    background-color: white; /* Latar belakang menjadi putih saat mencetak */
                    color: black; /* Teks menjadi hitam */
                }

                /* Menentukan ukuran halaman menjadi A4 */
                @page {
                    size: A4; /* Ukuran A4 */
                    margin: 10mm; /* Margin halaman A4 */
                }

                /* Area print yang memuat konten */
                #print-area {
                    width: 100%; /* Lebar penuh untuk area print */
                 
                    overflow: visible;
                }

                /* Konten utama yang akan mengalir ke bawah */
                .print-page {
                    width: 100%; /* Lebar penuh konten */
                    height: auto; /* Tinggi otomatis agar mengalir */
                    min-height: 297mm; /* Menetapkan ukuran minimum halaman A4 */
                    page-break-after: always; /* Halaman baru setelah setiap konten besar */
                }

                /* Menghindari pemisahan elemen besar seperti tabel atau gambar */
                .content {
                    page-break-inside: avoid; /* Hindari pemisahan konten besar */
                }

                /* Sembunyikan elemen-elemen tertentu saat print */
                .no-print {
                    display: none;
                }
            }
        </style>



</head>

<body class=" text-white h-screen flex flex-col" >

    <!-- Topbar -->
    <div class="flex items-center justify-between bg-gray-800 px-4 h-14 header">
        <div class="flex items-center space-x-3">
            <button id="menuToggle" class="md:hidden">
                <i data-lucide="menu" class="w-5 h-5 text-white"></i>
            </button>
            <span class="text-sm truncate max-w-[140px] text-gray-300">Buat Dokument Baru</span>
             <a href="{{ route('nota.index') }}"  class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-sm font-medium text-gray-800 rounded shadow">
                ← Kembali
            </a>
       
        </div>
        <div class="flex items-center space-x-3">
           
            <button onclick="printArea()"><i data-lucide="printer" class="w-5 h-5 text-white"></i></button>

            <script>
                function printArea() {
                    const printContents = document.getElementById("print-area").innerHTML;
                    const originalContents = document.body.innerHTML;

                    document.body.innerHTML = printContents;
                    window.print();
                    document.body.innerHTML = originalContents;
                }
            </script>

        </div>
    </div>

    <div class="flex flex-1 overflow-hidden">
        <!-- Workspace -->
        <div class="flex-1 overflow-auto flex items-start justify-center p-5"
            style="border: none; " id="print-area">
            <div class="w-full shadow-lg border border-gray-600 text-white p-5" style="background-color:white; width: 210mm; height: 297mm;  min-height: 297mm; /* Default height */
  height: auto; " class="print-page" id="include-content" >
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
    <!-- Floating Button -->

    <div class="fixed bottom-10 right-10 flex flex-col items-end space-y-3" id="floatingButton">
        <!-- Tombol Plus -->
    <button class="bg-gray-700 p-3 rounded-full shadow-lg hover:bg-gray-600"  id="submitButton">
        <i data-lucide="save" class="w-6 h-6 text-white"></i>
    </button>
    <script>
        // Mendapatkan tombol dan form
        const submitButton = document.getElementById('submitButton');
        const form = document.getElementById('myForm');

        // Menambahkan event listener ke tombol
        submitButton.addEventListener('click', function() {
            form.submit(); // Menyubmit form
        });
    </script>
    <!-- Tombol Plus -->
    <button onclick="addRow()" class="bg-gray-700 p-3 rounded-full shadow-lg hover:bg-gray-600">
        <i data-lucide="plus" class="w-6 h-6 text-white"></i>
    </button>

    <!-- Tombol Settings dan Menu -->
    <div class="relative">
        <button id="floatingButton" class="bg-gray-700 p-3 rounded-full shadow-lg hover:bg-gray-600">
            <i data-lucide="settings" class="w-6 h-6 text-white"></i>
        </button>
        <div id="floatingMenu"
            class="hidden absolute bottom-full right-0 mb-2 bg-gray-800 p-4 rounded-lg shadow-lg space-y-3">
            <label class="flex items-center space-x-2">
                <input type="checkbox" class="form-checkbox text-blue-500" id="toggleSignature"
                    onchange="toggleVisibility('signature')">
                <span class="text-white text-sm">Signature</span>
            </label>
            <label class="flex items-center space-x-2">
                <input type="checkbox" class="form-checkbox text-blue-500" id="toggleStamp"
                    onchange="toggleVisibility('stamp')">
                <span class="text-white text-sm">Stamp</span>
            </label>
        </div>
    </div>
</div>
<script>
  // Cek jika input belum ada value, baru set defaultDate
document.querySelectorAll('.flatpickr-input').forEach(function (el) {
    flatpickr(el, {
        dateFormat: "d M Y",
        defaultDate: el.value || "today"
    });
});

</script>
        <script>
            function toggleVisibility(elementId) {
                const element = document.getElementById(elementId);
                if (element) {
                    element.style.display = element.style.display === 'none' ? 'block' : 'none';
                }
            }

            // Default hide elements
            document.addEventListener('DOMContentLoaded', () => {
                const stampElement = document.getElementById('stamp');
                const signatureElement = document.getElementById('signature');
                if (stampElement) stampElement.style.display = 'none';
                if (signatureElement) signatureElement.style.display = 'none';
            });

            // Show image when checkbox is checked
            document.getElementById('toggleStamp').addEventListener('change', (event) => {
                const stampElement = document.getElementById('stamp');
                if (stampElement) {
                    stampElement.style.display = event.target.checked ? 'block' : 'none';
                }
            });

            document.getElementById('toggleSignature').addEventListener('change', (event) => {
                const signatureElement = document.getElementById('signature');
                if (signatureElement) {
                    signatureElement.style.display = event.target.checked ? 'block' : 'none';
                }
            });
        </script>


    <script>
        const floatingButton = document.getElementById('floatingButton');
        const floatingMenu = document.getElementById('floatingMenu');

        floatingButton.addEventListener('click', () => {
            floatingMenu.classList.toggle('hidden');
        });
    </script>
    <script>
        function printDokumen() {
            const iframe = document.querySelector('iframe');
            iframe.contentWindow.focus();
            iframe.contentWindow.print();
        }
    </script>


    <script>
        lucide.createIcons()

        // Toggle sidebar di mobile
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');

        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });
    </script>

    <style>
        @media (max-width: 768px) {
            #sidebar {
                position: fixed;
                top: 56px;
                left: 0;
                height: calc(100% - 56px);
                z-index: 50;
                transform: translateX(-100%);
                width: 56px;
            }
        }
    </style>
</body>

</html>
