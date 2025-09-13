<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota {{ Request('type') }} | {{ $id }}</title>
     <!-- Site favicon -->
    <link rel="shortcut icon" href="{{asset('assets/fav.png')}}" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        @media print {
          .header {
            display: none !important;
          }
            #floatingButton{
                 display: none !important;
            }
              @page {
    size: A4 portrait; /* atau landscape */
    margin: 0;         /* hilangkan margin bawaan */
  }

  body {
    margin: 0 !important;
    padding: 0 !important;
  }

  #print-area {
    width: 100%;
    height: auto;
    transform: scale(1);          /* pastikan tidak mengecil */
    transform-origin: top left;   /* scaling mulai dari kiri atas */
  }


  html, body {
  width: 100% !important;
  height: 100% !important;
  overflow: visible !important;
}
        }
        @media screen and (max-width: 768px) {
              #include-content{
                  transform: scale(0.7); /* perkecil biar muat layar */ transform-origin: top center;
              }
      }
    }
    </style>
</head>

<body class="bg-gray-900 text-white h-screen flex flex-col">

    <!-- Topbar -->
    <div class="flex items-center justify-between bg-gray-800 px-4 h-14 header">
        <div class="flex items-center space-x-3">
            <button id="menuToggle" class="md:hidden">
                <i data-lucide="menu" class="w-5 h-5 text-white"></i>
            </button>
            <span class="text-sm truncate max-w-[140px] text-gray-300">Nota {{ Request('type') }}{{ $id }}</span>
            <a href="/transaksi"  class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-sm font-medium text-gray-800 rounded shadow">
                ← Kembali
            </a>
           
            {{-- <button class="bg-blue-600 text-xs px-2 py-1 rounded">+ Create</button> --}}
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
        <div class="flex-1 bg-gray-900 overflow-auto flex items-start justify-center p-4" id="print-area" style="border: none;">
            <div class="w-full h-[297mm] bg-white shadow-lg border border-gray-600 text-white p-5 "
                style="width: 210mm;"id="include-content">
                @if(Request::is('transaksi/cetak/konsinyasi*'))
                    @include('transaksi.dokumen.laporan.konsinyasi')
                @elseif(Request::is('transaksi/cetak/kwitansi*'))
                    @include('transaksi.dokumen.laporan.kwitansi')
                @elseif(Request::is('transaksi/cetak/invoice*'))
                    @include('transaksi.dokumen.laporan.kwitansi')
                @endif
                {{-- <iframe src="/transaksi/dok/konsinyasi/{{  $id }}" class="w-full h-full" frameborder="0"></iframe> --}}
            </div>
        </div>
    </div>
    <!-- Floating Button -->
    <div class="fixed bottom-10 right-10 flex flex-col items-end space-y-3">
          <button id="floatingButton" class="bg-gray-700 p-3 rounded-full shadow-lg hover:bg-gray-600 relative">
            <i data-lucide="settings" class="w-6 h-6 text-white"></i>
        </button>
        <div id="floatingMenu"
            class="hidden absolute bottom-full right-0 mb-2 bg-gray-800 p-4 rounded-lg shadow-lg space-y-4">
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
    @if(request('type') !== 'konsinyasi')
    <button class="bg-gray-700 p-3 rounded-full shadow-lg hover:bg-gray-600"  id="submitButton">
        <i data-lucide="save" class="w-6 h-6 text-white"></i>
    </button>
    @endif
    <script>
        // Mendapatkan tombol dan form
        const submitButton = document.getElementById('submitButton');
        const form = document.getElementById('myForm');

        // Menambahkan event listener ke tombol
        submitButton.addEventListener('click', function() {
            form.submit(); // Menyubmit form
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
    </div>

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
