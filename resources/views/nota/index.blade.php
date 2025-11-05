@extends('layout.main')
@section('title', 'Nota dan Kwitansi')
@section('container')
<style>

    @media screen and (max-width: 767px) {
        .mobile{
            display: none;
        }
        .p-7{
            padding: 9px;
        }
    }
</style>
<div class="flex justify-between items-center mb-4  shadow-md rounded-lg">

    <!-- Title (Nota & Kwitansi) -->
    <div class="text-2xl font-bold text-gray-800">
        <span>Nota</span> & <span>Kwitansi</span>
    </div>

    <!-- Tombol di sebelah kanan -->
    <div class="flex space-x-2">
        @php
        function generateTransactionCode() {
        // Menghasilkan angka acak 7 digit
        $randomNumber = rand(1000000, 9999999);
        // Membuat kode transaksi dengan format B + angka acak
        $transactionCode = 'B' . $randomNumber;
        return $transactionCode;
        }

        // Menggunakan fungsi untuk menghasilkan kode transaksi
        $transactionCode = generateTransactionCode();
        @endphp
       <!-- Dropdown for Mobile -->
<div class="relative md:hidden">
    <!-- Dropdown Button -->
    <button id="dropdownButton" class="btn px-4 py-2 text-sm bg-gray-800  rounded-lg w-full text-left hover:bg-gray-700 transition">
        + Buat  Nota
    </button>

    <!-- Dropdown Menu -->
    <div id="dropdownMenu" class="shadow absolute left-0 w-full bg-white dark:bg-black text-gray-800 dark:text-white shadow-lg rounded-lg mt-2 hidden">
        <a href="{{ route('transaksi.nota.manual', $transactionCode) }}" 
            class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 transition">
            KONSINYASI
        </a>
        <a href="{{ route('transaksi.invoice.manual', $transactionCode) }}" 
            class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 transition">
            INVOICE
        </a>
        <a href="{{ route('transaksi.kwitansi.manual', $transactionCode) }}" 
            class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 transition">
            KWITANSI
        </a>
    </div>
</div>

<!-- Desktop Buttons (Visible on Larger Screens) -->
<div class="hidden md:flex space-x-4">
    <!-- Buat Nota Konsinyasi Button -->
    <a href="{{ route('transaksi.nota.manual', $transactionCode) }}" 
        class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-500 transition">
        Buat Nota Konsinyasi
    </a>

    <!-- Buat INVOICE Button -->
    <a href="{{ route('transaksi.invoice.manual', $transactionCode) }}" 
        class="px-4 py-2 text-sm bg-green-600 text-white rounded-lg hover:bg-green-500 transition">
        Buat INVOICE
    </a>

    <!-- Buat Kwitansi Button -->
    <a href="{{ route('transaksi.kwitansi.manual', $transactionCode) }}" 
        class="px-4 py-2 text-sm bg-red-600 text-white rounded-lg hover:bg-red-500 transition">
        Buat Kwitansi
    </a>
</div>


<script>
    // Toggle dropdown visibility when the button is clicked
    document.getElementById('dropdownButton').addEventListener('click', function() {
        const dropdownMenu = document.getElementById('dropdownMenu');
        dropdownMenu.classList.toggle('hidden');
    });
</script>

    </div>
</div>
<div class="border bg-lightwhite dark:bg-white/5 dark:border-white/10 border-black/10 rounded-md">
    <div class="">
        <div class="table-responsive">
            <table class="table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th class="mobile" >Tanggal</th>
                        <th class="mobile">Kode Transaksi</th>
                        <th >Jenis Dokumen</th>
                        <th class="mobile">Nama Toko / Mitra</th>
                        <th class="mobile">Total Transaksi</th>
                        <th ></th>
                    </tr>
                </thead>
                <tbody>
                    @php $no=1 @endphp
                    @foreach($data as $item)
                    @php
                        if ($item->type == 'nota_konsinyasi') {
                            $link = route('transaksi.nota.manual', $item->kode_transaksi);
                        } elseif ($item->type == 'invoice') {
                            $link = route('transaksi.invoice.manual', $item->kode_transaksi);
                        } elseif ($item->type == 'nota_pembayaran') {
                            $link = route('transaksi.kwitansi.manual', $item->kode_transaksi);
                        } else {
                            $link = '#'; // fallback kalau type tidak ada
                        }
                    @endphp
                    <tr onclick="window.location='{{ $link }}'" class="border bg-white dark:bg-black p-5 rounded-md cursor-pointer hover:bg-gray-100 ">
                        <td>{{ $no++ }}</td>
                        <td class="whitespace-nowrap mobile"><span class="inline-flex items-center rounded-full text-xs justify-center px-2 py-1 bg-lightblue-200 text-black">
                         <svg class="w-5 h-5 text-black mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10m2-5H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2z" />
                        </svg> {{ $item->tanggal }}
                        </span></td>
                        <td class="mobile"><b>{{ $item->kode_transaksi }}</b></td>
                        <td >{{ $item->judul }}</td>
                        <td class="mobile" >{{ $item->kepada }}</td>
                        <td class="mobile">Rp {{ number_format($item->grandtotal, 0, ',', '.') }}</td>
                        <td>
                            
                             
                            
                               <a href="/nota/delete/{{$item->id}}" type="submit" aria-label="Hapus"
                                    onclick="event.stopPropagation();"
                                    class="inline-flex items-center justify-center rounded-full p-2 
                                           text-red-600 hover:text-white hover:bg-red-600 
                                           focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2
                                           transition">
                                    <!-- Ikon Trash -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M6 7h12m-9 0V5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2
                                                 m-7 0v12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V7
                                                 M10 11v6m4-6v6"/>
                                    </svg>
                                </a>
                          
                           
                        </td>
                    </tr>
                
                    @endforeach

                    @if($data->isEmpty())
                    <tr>
                        <td colspan="6" class="text-center py-5"> Belum ada Nota dibuat</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
