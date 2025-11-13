<?php

namespace App\Http\Controllers\Transaksi;

use App\Models\Mitra;
use App\Models\Produk;
use App\Models\Dokumen;
use App\Models\Regency;
use App\Models\StokLog;
use App\Models\Penawaran;
use App\Models\Transaksi;
use App\Models\Itemdokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\TransaksiProduct;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Validator;

class TransaksiController extends Controller
{
    public function transaksiIndex(){
        $mitra = Mitra::where('auth', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->select('id', 'kode_mitra', 'nama_mitra')
            ->get();

        // Ambil filter dari request
        $statusPembayaran = request('status_pembayaran');
        $kodeMitra = request('kode_mitra');
        $periode = request('periode');
        $bulan = request('bulan');
        $tahunBulan = request('tahun_bulan');
        $tahunTahun = request('tahun_tahun');
        $awal = request('tanggal_awal');
        $akhir = request('tanggal_akhir');

        // Query transaksi dengan filter, pastikan tidak ada duplikat kode_transaksi
        $transaksiQuery = Transaksi::where('auth', auth()->user()->id);

        if ($statusPembayaran === 'belum_bayar') {
            $transaksiQuery->where('status_bayar', 'Belum Bayar');
        } elseif ($statusPembayaran === 'sudah_bayar') {
            $transaksiQuery->where('status_bayar', 'Sudah Bayar');
        }

        if ($kodeMitra) {
            $transaksiQuery->where('kode_mitra', $kodeMitra);
        }

        // Filter periode
        if ($periode === 'bulanan' && $bulan && $tahunBulan) {
            $transaksiQuery->whereMonth('tanggal_transaksi', $bulan)
                ->whereYear('tanggal_transaksi', $tahunBulan);
        } elseif ($periode === 'tahunan' && $tahunTahun) {
            $transaksiQuery->whereYear('tanggal_transaksi', $tahunTahun);
        } elseif ($awal && $akhir) {
            $transaksiQuery->whereBetween(DB::raw('DATE(tanggal_transaksi)'), [$awal, $akhir]);
        }

        $transaksi = $transaksiQuery
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique('kode_transaksi')
            ->map(function ($item) {
                return [
                    '<a href="' . route("transaksi.detail", $item->id) . '" class="flex items-center space-x-2 text-blue-600 hover:underline">
                    <span>' . e($item->kode_transaksi) . '</span>
                    </a>',
                    '<div class="mobile">' . ($item->tanggal_transaksi ? \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d M Y') : '<span class="text-gray-500">Tidak Diketahui</span>') . '</div>',
                    '<div class="mobile">' . ($item->mitra->nama_mitra ?? '<span class="text-gray-500">Tidak Diketahui</span>') . '</div>',
                    '<div class="mobile">' . (isset($item->total) ? 'Rp ' . number_format($item->total, 0, ',', '.') : '<span class="text-gray-500">Tidak Diketahui</span>') . '</div>',
                    '<div class="mobile">' . ($item->status_bayar ?? '<span class="text-gray-500">Tidak Diketahui</span>') . '</div>',
                ];
            })->values();

        $transaksimobile = Transaksi::where('auth', auth()->user()->id)
            ->when($statusPembayaran === 'belum_bayar', function($q) {
                $q->where('status_bayar', 'Belum Bayar');
            })
            ->when($statusPembayaran === 'sudah_bayar', function($q) {
                $q->where('status_bayar', 'Sudah Bayar');
            })
            ->when($kodeMitra, function($q) use ($kodeMitra) {
                $q->where('kode_mitra', $kodeMitra);
            })
            ->when($periode === 'bulanan' && $bulan && $tahunBulan, function($q) use ($bulan, $tahunBulan) {
                $q->whereMonth('tanggal_transaksi', $bulan)
                  ->whereYear('tanggal_transaksi', $tahunBulan);
            })
            ->when($periode === 'tahunan' && $tahunTahun, function($q) use ($tahunTahun) {
                $q->whereYear('tanggal_transaksi', $tahunTahun);
            })
            ->when($awal && $akhir, function($q) use ($awal, $akhir) {
                $q->whereBetween(DB::raw('DATE(tanggal_transaksi)'), [$awal, $akhir]);
            })
            ->get()
            ->unique('kode_transaksi');

        $totalTransaksiluar = Transaksi::where(['auth'=> auth()->user()->id,'status_bayar'=>'Belum Bayar'])
            ->when($kodeMitra, function($q) use ($kodeMitra) {
                $q->where('kode_mitra', $kodeMitra);
            })
            ->when($periode === 'bulanan' && $bulan && $tahunBulan, function($q) use ($bulan, $tahunBulan) {
                $q->whereMonth('tanggal_transaksi', $bulan)
                  ->whereYear('tanggal_transaksi', $tahunBulan);
            })
            ->when($periode === 'tahunan' && $tahunTahun, function($q) use ($tahunTahun) {
                $q->whereYear('tanggal_transaksi', $tahunTahun);
            })
            ->when($awal && $akhir, function($q) use ($awal, $akhir) {
                $q->whereBetween(DB::raw('DATE(tanggal_transaksi)'), [$awal, $akhir]);
            })
            ->sum('total');

        $totalTransaksi = Transaksi::where(['auth'=> auth()->user()->id,'status_bayar'=>'Sudah Bayar'])
            ->when($kodeMitra, function($q) use ($kodeMitra) {
                $q->where('kode_mitra', $kodeMitra);
            })
            ->when($periode === 'bulanan' && $bulan && $tahunBulan, function($q) use ($bulan, $tahunBulan) {
                $q->whereMonth('tanggal_transaksi', $bulan)
                  ->whereYear('tanggal_transaksi', $tahunBulan);
            })
            ->when($periode === 'tahunan' && $tahunTahun, function($q) use ($tahunTahun) {
                $q->whereYear('tanggal_transaksi', $tahunTahun);
            })
            ->when($awal && $akhir, function($q) use ($awal, $akhir) {
                $q->whereBetween(DB::raw('DATE(tanggal_transaksi)'), [$awal, $akhir]);
            })
            ->sum('total');

        $logs = Activity::where(['causer_id'=>auth()->user()->id, 'log_name' => 'ikm'])->latest()->take(10)->get();

        return view('transaksi.index', [
            'activeMenu' => 'transaksi',
            'active' => 'transaksi',
        ], compact('mitra','logs','transaksi','totalTransaksi','totalTransaksiluar','transaksimobile','awal','akhir','periode','bulan','tahunBulan','tahunTahun'));
    }

    public function DetailTransaki($id){
        $transaksi = Transaksi::findOrFail($id);
        $id_mitra = $transaksi->kode_mitra;

        $mitra = Mitra::where('kode_mitra', $id_mitra)->first();
        $logs = Activity::where([
            'causer_id' => auth()->user()->id,
            'log_name' => 'ikm'
        ])->latest()->take(10)->get();
        $penawaran = Penawaran::with('produk')->where('kode_mitra', $id_mitra)->with('produk')->get();
        $product = TransaksiProduct::where('kode_mitra', $id_mitra)->with(['penawaran','produk'])->get();

        return view('transaksi.detail', [
            'activeMenu' => 'transaksi',
            'active' => 'transaksi',
        ], compact('mitra', 'logs', 'penawaran','transaksi','product'));
    }

    public function transaksiCreate(request $request){
       $request->validate([
            'kode_mitra' => 'required|exists:mitras,kode_mitra',
        ]);

        // Buat transaksi baru
        $transaksi = new Transaksi();
        $transaksi->kode_transaksi     = $request->kode_transaksi;
        $transaksi->kode_mitra         = $request->kode_mitra;
        $transaksi->tanggal_transaksi  = now();
        $transaksi->auth               = auth()->user()->id;
        $transaksi->status_bayar       = 'Belum Bayar';
        $transaksi->save();

        // Ambil semua penawaran untuk mitra terkait
        $penawarans = Penawaran::where('kode_mitra', $request->kode_mitra)->get();

        if ($penawarans->count() > 0) {
            foreach ($penawarans as $item) {
                TransaksiProduct::create([
                    'tanggal'           =>now(),
                    'kode_produk'     => $item->kode_produk,
                    'kode_transaksi'  => $transaksi->kode_transaksi,
                    'kode_mitra'      => $request->kode_mitra,
                    'harga'            => $item->harga,
                    'barang_keluar'   => 0,
                    'barang_terjual'  => 0,
                    'barang_retur'    => 0,
                    'total'           => 0,
                ]);

            }
        }

        // Catat aktivitas
        activity('ikm')
            ->causedBy(auth()->user())
            ->performedOn($transaksi)
            ->log('Membuat transaksi baru');

        // Redirect dengan pesan sukses
        return redirect()->route('transaksi.index')->with('success', 'Data berhasil disimpan!');
    }

    public function transaksiUpdate(Request $request)
    {

        $request->validate([
            'kode_mitra' => 'required|exists:mitras,kode_mitra',
            'nomor_transaksi' => 'required',
            'discount' => 'nullable',
            'tanggal_bayar' => 'nullable|date',
            'status_bayar' => 'required',
            'barang_keluar' => 'array',
            'barang_terjual' => 'array',
            'barang_retur' => 'array',
            'total' => 'required',
        ]);

       $kode_mitra = $request->kode_mitra;
        $transaksi = Transaksi::where('kode_transaksi', $request->nomor_transaksi)->firstOrFail();
        $transaksi->tanggal_transaksi = $request->tanggal_transaksi ?? $transaksi->tanggal_transaksi;
        $transaksi->diskon = str_replace(['.', ','], '',  $request->discount ?? '0');
        $transaksi->ongkir = str_replace(['.', ','], '', $request->ongkir ?? '0');
        $transaksi->tanggal_pembayaran = $request->tanggal_bayar ?? $transaksi->tanggal_pembayaran;
        $transaksi->total = str_replace(['.', ','], '', $request->grand_total);
        $transaksi->status_bayar = $request->status_bayar;
        $transaksi->auth = auth()->user()->id;

       foreach ($request->kode_produk as $index => $kode_produk) {

                $barangKeluarBaru = $request->barang_keluar[$index] ?? 0;
                $barangReturBaru  = $request->barang_retur[$index] ?? 0;

                // Cek stok produk
                $produk = Produk::where('kode_produk', $kode_produk)->first();
                if (!$produk) {
                    return redirect()->back()->with("error", "Produk $kode_produk tidak ditemukan.");
                }

                // ambil data lama (jika ada)
                $existing = TransaksiProduct::where([
                    'kode_produk'    => $kode_produk,
                    'kode_transaksi' => $transaksi->kode_transaksi,
                    'kode_mitra'     => $kode_mitra,
                ])->first();

                $barangKeluarLama = $existing->barang_keluar ?? 0;
                $barangReturLama  = $existing->barang_retur ?? 0;

                $selisihKeluar = $barangKeluarBaru - $barangKeluarLama;
                $selisihRetur  = $barangReturBaru - $barangReturLama;

                // ❗️Cek stok cukup atau tidak
                if ($selisihKeluar > 0 && $produk->stok < $selisihKeluar) {
                    return redirect()->back()->with("error", "Stok produk {$produk->nama_produk} tidak mencukupi! Silahkan Cek kembali Stok yang tersedia");
                }

                // Update transaksi_product
                $harga = $request->harga_dekstop[$index] ?? $request->harga_mobile[$index] ?? '0';
                $barangTerjual = $request->barang_terjual[$index] ?? 0;
                $transaksiProduct = TransaksiProduct::updateOrCreate(
                    [
                        'kode_produk'    => $kode_produk,
                        'kode_transaksi' => $transaksi->kode_transaksi,
                        'kode_mitra'     => $kode_mitra,
                    ],
                    [   
                        'tanggal'        =>$request->tanggal_transaksi ?? $transaksi->tanggal_transaksi,
                        'harga'          => $harga,
                        'barang_keluar'  => $barangKeluarBaru,
                        'barang_terjual' => $barangTerjual,
                        'barang_retur'   => $barangReturBaru,
                        'total'          => $harga * $barangTerjual,
                    ]
                );
                Penawaran::updateOrCreate(
                    [
                        'kode_mitra'  => $kode_mitra,
                        'kode_produk' => $kode_produk,
                    ],
                    [
                        'harga'       => $harga,
                        'updated_at'  => now(),
                    ]
                );
                // Update stok
                if ($selisihKeluar != 0) {
                    Produk::where('kode_produk', $kode_produk)
                        ->decrement('stok', $selisihKeluar);

                    StokLog::create([
                        'kode_produk' => $kode_produk,
                        'tipe'        => 'keluar',
                        'jumlah'      => $selisihKeluar,
                        'sumber'      => 'transaksi',
                        'referensi'   => $transaksi->kode_transaksi,
                        'auth'        => auth()->id(),
                        'keterangan'  => 'Barang keluar untuk mitra ' . $kode_mitra
                    ]);
                }

                if ($selisihRetur != 0) {
                    Produk::where('kode_produk', $kode_produk)
                        ->increment('stok', $selisihRetur);

                    StokLog::create([
                        'kode_produk' => $kode_produk,
                        'tipe'        => 'masuk',
                        'jumlah'      => $selisihRetur,
                        'sumber'      => 'retur',
                        'referensi'   => $transaksi->kode_transaksi,
                        'auth'        => auth()->id(),
                        'keterangan'  => 'Barang retur dari mitra ' . $kode_mitra
                    ]);
                }
            }


        $transaksi->update();


        activity('ikm')
            ->causedBy(auth()->user())
            ->performedOn($transaksi)
            ->log('Memperbarui transaksi');
            $success = true;
            $message = 'Data transaksi berhasil diperbarui!';
            $info = 'Harga penawaran juga telah disesuaikan berdasarkan kode mitra dan produk.';

            // Jika request datang dari AJAX, kembalikan JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => $success,
                    'message' => $message,
                    'info'    => $info,
                ]);
            }

            // Jika request biasa (non-AJAX), redirect dengan flash message
            return back()->with([
                'success' => $message,
                'info'    => $info,
            ]);



    }

    public function konsinyasi($id){
        $transaksi = Transaksi::where('kode_transaksi', $id)->first();
        return view('transaksi.dokumen.index',compact('id','transaksi'));
    }
    public function konsinyasidok($id){
        $transaksi = Transaksi::where('kode_transaksi', $id)->first();
        return view('transaksi.dokumen.laporan.konsinyasi',compact('transaksi'));
    }

    public function manualNota($id){
        $nota = Dokumen::where('kode_transaksi',$id)->first();
        return view('transaksi.dokumen.manual',compact('nota','id'));
    }
    public function manualadd(Request $request)
    {
        // Step 1: Validate request
        $validator = Validator::make($request->all(), [
            'nama_barang' => 'required|array',
            'qty' => 'required|array',
            'unit' => 'required|array',
            'harga' => 'required|array',
            'total' => 'required|array',
            'judul' => 'required|string',
            'kode_transaksi' => 'required|string',
            'tanggal' => 'required|date',
            'alamat_company' => 'required|string',
            'telp_company' => 'required|string',
            'kota' => 'required|string',
            'telp' => 'required|string',
            'kepada' => 'required|string',
            'keterangan' => 'required|string',
            'email_company' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        $validated = $validator->validated();

        // Step 2: Create or update the document
        $dokumen = Dokumen::updateOrCreate(
            ['kode_transaksi' => $validated['kode_transaksi']],
            [
                'judul' => $validated['judul'],
                'tanggal' => $validated['tanggal'],
                'alamat_company' => $validated['alamat_company'],
                'telp_company' => $validated['telp_company'],
                'kota' => $validated['kota'],
                'telp' => $validated['telp'],
                'kepada' => $validated['kepada'],
                'keterangan' => $validated['keterangan'],
                'email_company' => $validated['email_company'],
                'grandtotal' => (int) preg_replace('/\D/', '', $request->grandtotal),
                'auth' => auth()->user()->id,
                'notes' => $request->notes ?? '',
                'type' => $request->type ?? '',
            ]
        );

        // Step 3: Create or update the item rows
        foreach ($validated['nama_barang'] as $index => $namaBarang) {
            if (empty($namaBarang)) {
                return redirect()->back()->withErrors("Nama barang tidak boleh kosong.");
            }

            $itemId = $request->id_item[$index] ?? null;

            if ($itemId === null) {
                $item = Itemdokumen::where('nama_barang', $namaBarang)
                                   ->where('kode_transaksi', $validated['kode_transaksi'])
                                   ->first();
                $itemId = $item?->id;
            }

            Itemdokumen::updateOrCreate(
                ['kode_transaksi' => $validated['kode_transaksi'], 'id' => $itemId],
                [
                    'nama_barang' => $namaBarang,
                    'qty' => $validated['qty'][$index] ?? 0,
                    'unit' => $validated['unit'][$index] ?? '',
                    'harga' => (int) preg_replace('/\D/', '', $validated['harga'][$index] ?? 0),
                    'total' => (int) preg_replace('/\D/', '', $validated['total'][$index] ?? 0),
                    'auth' => auth()->user()->id,
                ]
            );
        }

       return back()->with("success", "Data has been updated successfully!");
    }

    public function itemDelete($id){
        Itemdokumen::where('id',$id)->delete();
        return redirect()->back();
    }

    public function kwitansi($id){
        $transaksi = Transaksi::where('kode_transaksi', $id)->first();
        return view('transaksi.dokumen.index',compact('id','transaksi'));
    }

    public function notes(request $request){
        Transaksi::where('kode_transaksi', $request->id)->update(['notes' => $request->notes]);
        return back()->with("success", "Data has been updated successfully!");

    }

    public function updateKodeTransaksi(request $request)
    {
        $kodeTransaksi = $request->kode_transaksi;
        $kodeProduks   = $request->kode_produk;
        $kodeMitra     = $request->kode_mitra;

        if (!$kodeProduks || empty($kodeProduks)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada produk terpilih.']);
        }

        $produkSudahAda = [];

        foreach ($kodeProduks as $kodeProduk) {
            $exists = TransaksiProduct::where('kode_transaksi', $kodeTransaksi)
                ->where('kode_produk', $kodeProduk)
                ->exists();

            if ($exists) {
                $produkSudahAda[] = $kodeProduk;
            } else {
                TransaksiProduct::create([
                    'kode_produk'    => $kodeProduk,
                    'kode_transaksi' => $kodeTransaksi,
                    'kode_mitra'     => $kodeMitra,
                ]);
            }
        }

        return response()->json([
            'success'        => true,
            'produk_sudah_ada' => $produkSudahAda
        ]);
    }

    public function hapusProduk(Request $request)
    {
        $request->validate([
            'kode_transaksi' => 'required',
            'kode_produk' => 'required'
        ]);

        $deleted = TransaksiProduct::where('kode_transaksi', $request->kode_transaksi)
                    ->where('kode_produk', $request->kode_produk)
                    ->delete();

        if ($deleted) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan atau sudah dihapus.']);
        }
    }

    public function hapusTransksi($id){
        Transaksi::where('kode_transaksi',$id)->delete();
        TransaksiProduct::where('kode_transaksi',$id)->delete();
        return redirect()->route('transaksi.index')->with("success", "Data has been deleted successfully!");
    }

    public function laporanTransaksi(){
        $awal = request('awal');   // contoh: 2025-11-01
        $akhir = request('akhir'); // contoh: 2025-11-30

        $periode = request('periode'); // 'bulanan' / 'tahunan' / null
        $bulan = request('bulan');     // 1–12
        $tahun_bulan = request('tahun_bulan'); // contoh: 2025
        $tahun_tahun = request('tahun_tahun'); // contoh: 2025

        $laporan = DB::table('transaksis as t')
            ->leftJoin('transaksi_products as tp', 't.kode_transaksi', '=', 'tp.kode_transaksi')
            ->leftJoin('produks as p', 'tp.kode_produk', '=', 'p.kode_produk')
            ->leftJoin('satuans as s', 'p.satuan_id', '=', 's.id')
            ->leftJoin('mitras as m', 't.kode_mitra', '=', 'm.kode_mitra')
            ->select(
                't.kode_transaksi',
                't.tanggal_transaksi',
                't.kode_mitra',
                'm.nama_mitra as nama_pelanggan',
                'm.alamat_mitra as alamat',
                'tp.kode_produk',
                'p.nama_produk',
                'tp.barang_keluar',
                'tp.barang_retur',
                'tp.barang_terjual as jumlah',
                's.nama as satuan',
                'tp.harga',
                DB::raw('(tp.barang_terjual * tp.harga) as total')
            )

            // 🔹 Filter berdasarkan periode
            ->when($periode === 'bulanan' && $bulan && $tahun_bulan, function ($query) use ($bulan, $tahun_bulan) {
                $query->whereMonth('t.tanggal_transaksi', $bulan)
                    ->whereYear('t.tanggal_transaksi', $tahun_bulan);
            })
            ->when($periode === 'tahunan' && $tahun_tahun, function ($query) use ($tahun_tahun) {
                $query->whereYear('t.tanggal_transaksi', $tahun_tahun);
            })

            // 🔹 Filter berdasarkan tanggal manual (prioritas jika diisi)
            ->when($awal && $akhir, function ($query) use ($awal, $akhir) {
                $query->whereBetween(DB::raw('DATE(t.tanggal_transaksi)'), [$awal, $akhir]);
            })

            ->get();


        // 🔸 Perhitungan laba (optional)
        $labaKotor = ($pendapatan ?? 0) - ($hpp ?? 0);
        $labaBersih = $labaKotor - ($bebanNonInventory ?? 0);

        // 🔸 Ambil log aktivitas
        $logs = Activity::where([
            'causer_id' => auth()->user()->id,
            'log_name' => 'ikm'
        ])->latest()->take(10)->get();

        // 🔸 Kirim ke view
        return view('report.laporanTransaksi', [
            'activeMenu' => 'laporan_penjualan',
            'active' => 'laporan_penjualan',
        ], compact('laporan', 'logs', 'awal', 'akhir', 'periode', 'bulan', 'tahun_bulan', 'tahun_tahun'));

    }

       public function exportPDF()
    {
$periode = request('periode');
    $bulan = request('bulan');
    $tahunBulan = request('tahun_bulan');
    $tahunTahun = request('tahun_tahun');
    $tanggalAwal = request('tanggal_awal');
    $tanggalAkhir = request('tanggal_akhir');
    $awal = request('awal');
    $akhir = request('akhir');

    // Tentukan range tanggal berdasarkan filter
    if ($periode === 'bulanan' && $bulan && $tahunBulan) {
        $awal = Carbon::createFromDate($tahunBulan, $bulan, 1)->startOfMonth()->format('Y-m-d');
        $akhir = Carbon::createFromDate($tahunBulan, $bulan, 1)->endOfMonth()->format('Y-m-d');
    } elseif ($periode === 'tahunan' && $tahunTahun) {
        $awal = Carbon::createFromDate($tahunTahun, 1, 1)->startOfYear()->format('Y-m-d');
        $akhir = Carbon::createFromDate($tahunTahun, 12, 31)->endOfYear()->format('Y-m-d');
    } elseif ($periode === 'custom' && $tanggalAwal && $tanggalAkhir) {
        $awal = $tanggalAwal;
        $akhir = $tanggalAkhir;
    }

    // Query utama
    $laporan = DB::table('transaksis as t')
        ->leftJoin('transaksi_products as tp', 't.kode_transaksi', '=', 'tp.kode_transaksi')
        ->leftJoin('produks as p', 'tp.kode_produk', '=', 'p.kode_produk')
        ->leftJoin('satuans as s', 'p.satuan_id', '=', 's.id')
        ->leftJoin('mitras as m', 't.kode_mitra', '=', 'm.kode_mitra')
        ->select(
            't.kode_transaksi',
            't.tanggal_transaksi',
            't.kode_mitra',
            'm.nama_mitra as nama_pelanggan',
            'm.alamat_mitra as alamat',
            'tp.kode_produk',
            'p.nama_produk',
            'tp.barang_keluar',
            'tp.barang_retur',
            'tp.barang_terjual as jumlah',
            's.nama as satuan',
            'tp.harga',
            DB::raw('(tp.barang_terjual * tp.harga) as total')
        )
        ->when($awal && $akhir, function ($query) use ($awal, $akhir) {
            $query->whereBetween(DB::raw('DATE(t.tanggal_transaksi)'), [$awal, $akhir]);
        })
        ->get();

    // Laba (kalau ada variabelnya)
    $pendapatan = 0;
    $hpp = 0;
    $bebanNonInventory = 0;
    $labaKotor = $pendapatan - $hpp;
    $labaBersih = $labaKotor - $bebanNonInventory;

    // Buat PDF
    $pdf = Pdf::loadView('report.transaksi', [
        'laporan' => $laporan,
        'awal' => $awal,
        'akhir' => $akhir,
        'periode' => $periode,
        'bulan' => $bulan,
        'tahunBulan' => $tahunBulan,
        'tahunTahun' => $tahunTahun,
        'tanggalAwal' => $tanggalAwal,
        'tanggalAkhir' => $tanggalAkhir,
    ])->setPaper('a4', 'portrait');

    $random = rand(1000, 9999);
    $namaFile = 'laporan-penjualan-detail-' . Carbon::now()->format('Ymd_His') . '-' . $random . '.pdf';

    return $pdf->download($namaFile);
    }

    public function savePdf(Request $request) {
        if ($request->hasFile('pdf')) {
            $file = $request->file('pdf');
            $fileName = $file->getClientOriginalName();
            $path = $file->storeAs('public/pdfs', $fileName);
            return response()->json(['url' => asset('storage/pdfs/'.$fileName)]);
        }
        return response()->json(['error' => 'No file'], 400);
    }



}
