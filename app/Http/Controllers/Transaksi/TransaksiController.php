<?php

namespace App\Http\Controllers\Transaksi;

use App\Models\Mitra;
use App\Models\Produk;
use App\Models\Dokumen;
use App\Models\Regency;
use App\Models\Penawaran;
use App\Models\Transaksi;
use App\Models\Itemdokumen;
use Illuminate\Http\Request;
use App\Models\TransaksiProduct;
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
        // Hitung jumlah berdasarkan Kota

        $transaksi = Transaksi::where('auth', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
            return [
                // Kode Transaksi
                '<a href="' . route("transaksi.detail", $item->id) . '" class="flex items-center space-x-2 text-blue-600 hover:underline">
                <span>' . e($item->kode_transaksi) . '</span>
                </a>',
                // Tanggal Transaksi
                '<div class="mobile">' . ($item->tanggal_transaksi ? \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d M Y') : '<span class="text-gray-500">Tidak Diketahui</span>') . '</div>',
                // Nama Toko (ambil dari relasi mitra, jika ada)
                '<div class="mobile">' . ($item->mitra->nama_mitra ?? '<span class="text-gray-500">Tidak Diketahui</span>') . '</div>',
                // Nilai Pesanan (misal: total_harga, jika ada field ini)
                '<div class="mobile">' . (isset($item->total) ? 'Rp ' . number_format($item->total, 0, ',', '.') : '<span class="text-gray-500">Tidak Diketahui</span>') . '</div>',
                // Status Pembayaran (misal: status_pembayaran, jika ada field ini)
                '<div class="mobile">' . ($item->status_bayar ?? '<span class="text-gray-500">Tidak Diketahui</span>') . '</div>',
            ];
            })->values();
        $totalTransaksiluar = Transaksi::where(['auth'=> auth()->user()->id,'status_bayar'=>'Belum Bayar'])->sum('total');
        $totalTransaksi = Transaksi::where(['auth'=> auth()->user()->id,'status_bayar'=>'Sudah Bayar'])->sum('total');
        $logs = Activity::where(['causer_id'=>auth()->user()->id, 'log_name' => 'ikm'])->latest()->take(10)->get();
        return view('transaksi.index', [
            'activeMenu' => 'transaksi',
            'active' => 'transaksi',
        ], compact('mitra','logs','transaksi','totalTransaksi','totalTransaksiluar'));
    }

    public function DetailTransaki($id){
        $transaksi = Transaksi::findOrFail($id);
        $id_mitra = $transaksi->kode_mitra;

        $mitra = Mitra::where('kode_mitra', $id_mitra)->first();
        $logs = Activity::where([
            'causer_id' => auth()->user()->id,
            'log_name' => 'ikm'
        ])->latest()->take(10)->get();
        $penawaran = Penawaran::where('kode_mitra', $id_mitra)->with('produk')->get();
        $product = TransaksiProduct::where('kode_mitra', $id_mitra)->with('produk')->get();

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
        $transaksi->save();

        // Ambil semua penawaran untuk mitra terkait
        $penawarans = Penawaran::where('kode_mitra', $request->kode_mitra)->get();

        if ($penawarans->count() > 0) {
            foreach ($penawarans as $item) {
                TransaksiProduct::create([
                    'kode_produk'     => $item->kode_produk,
                    'kode_transaksi'  => $transaksi->kode_transaksi,
                    'kode_mitra'      => $request->kode_mitra,
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

        $transaksi->diskon = str_replace(['.', ','], '',  $request->discount ?? '0') ;
        $transaksi->ongkir = str_replace(['.', ','], '', $request->ongkir ?? '0') ;
        $transaksi->tanggal_pembayaran = $request->tanggal_bayar ?? $transaksi->tanggal_pembayaran;
        $transaksi->total = str_replace(['.', ','], '', $request->grand_total); // Remove dots and commas before saving
        $transaksi->status_bayar = $request->status_bayar;
        $transaksi->auth = auth()->user()->id;

        // foreach ($request->kode_produk as $index => $kode_produk) {
        //     $penawaran = Penawaran::where('kode_mitra', $kode_mitra)
        //     ->where('kode_produk', $kode_produk)
        //     ->first();

        //     if ($penawaran) {
        //     $penawaran->barang_keluar = $request->barang_keluar[$index] ?? $penawaran->barang_keluar;
        //     $penawaran->barang_terjual = $request->barang_terjual[$index] ?? $penawaran->barang_terjual;
        //     $penawaran->barang_retur = $request->barang_retur[$index] ?? $penawaran->barang_retur;
        //     $penawaran->total = str_replace(['.', ','], '', $request->harga[$index] ?? $penawaran->total); // Remove dots and commas before saving
        //     $penawaran->update();
        //     }
        // }
        foreach ($request->kode_produk as $index => $kode_produk) {
            TransaksiProduct::updateOrCreate(
                [
                    'kode_produk'    => $kode_produk,
                    'kode_transaksi' => $transaksi->kode_transaksi,
                    'kode_mitra'     => $kode_mitra,
                ],
                [
                    'barang_keluar'  => $request->barang_keluar[$index] ?? 0,
                    'barang_terjual' => $request->barang_terjual[$index] ?? 0,
                    'barang_retur'   => $request->barang_retur[$index] ?? 0,
                    'total'          => str_replace(['.', ','], '', $request->harga[$index] ?? 0),
                ]
            );
        }

        $transaksi->update();

        activity('ikm')
            ->causedBy(auth()->user())
            ->performedOn($transaksi)
            ->log('Memperbarui transaksi');

        return redirect()->route('transaksi.detail', ['id' => $transaksi->id])->with("success", "Data has been updated successfully!");
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


}
