<?php

namespace App\Http\Controllers\Mitra;

use App\Models\Mitra;
use App\Models\Produk;
use App\Models\Regency;
use App\Models\District;
use App\Models\Penawaran;
use App\Models\Transaksi;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Spatie\Activitylog\Models\Activity;

class MitraController extends Controller
{
    public function index()
    {
        $mitra = Mitra::where('auth', auth()->user()->id)->orderBy('created_at', 'desc')->get()->map(function ($item) {
            return [
                '<a href="' . route("detail.mitra", $item->id) . '" class="flex items
                -center space-x-2 text-blue-600 hover:underline">
                    <span>' . e($item->kode_mitra) . '</span>
                </a>',
                '<div class="mobile">' . ($item->nama_mitra ?? '<span class="text-gray-500">Tidak Diketahui</span>') . '</div>',
                '<div class="mobile truncate">' . (Str::limit($item->alamat_mitra, 20) ?? '<span class="text-gray-500">Tidak Diketahui</span>') . '</div>',
                '<div class="mobile">' . ($item->id_kota ?? '<span class="text-gray-500">Tidak Diketahui</span>') . '</div>',
                '<div class="mobile">' . ($item->no_telp_mitra ?? '<span class="text-gray-500">Tidak Diketahui</span>') . '</div>',
            ];
        })->values();
        // Hitung jumlah berdasarkan Kota
        $totalKota = Mitra::whereNotNull('id_kota')->where('auth',auth()->user()->id)->count();
        $mitraData = Mitra::where('auth', auth()->user()->id)->get();
        $logs = Activity::where(['causer_id'=>auth()->user()->id, 'log_name' => 'ikm'])->latest()->take(10)->get();
        return view('mitra.index', [
            'activeMenu' => 'mitra',
            'active' => 'mitra',
        ], compact('mitra','logs','totalKota','mitraData'));
    }

    public function create()
    {
        $kota = Regency::pluck('name');
        $produk = Produk::where('auth', auth()->user()->id)->get();
        $logs = Activity::where(['causer_id'=>auth()->user()->id, 'log_name' => 'ikm'])->latest()->take(10)->get();
        return view('mitra.action.add', [
            'activeMenu' => 'mitra',
            'active' => 'add_mitra',
        ],compact('kota','logs','produk'));
    }
    public function createAction(Request $request)
    {
        $request->validate([
            'kode_mitra' => 'required|string|max:255',
            'nama_mitra' => 'required|string|max:255',
        ]);

        // Simpan data mitra
        $mitra = Mitra::create([
            'kode_mitra' => $request->kode_mitra,
            'nama_mitra' => $request->nama_mitra,
            'auth'=>auth()->user()->id
        ]);
        // Log aktivitas
        activity('ikm')->performedOn($mitra)->causedBy(auth()->user())->log('Menambahkan Mitra Baru ' . $request->nama_mitra);
        
        return redirect()->route('detail.mitra', $mitra->id)->with('reload', true);
    }
    public function mitraDetail($id)
    {
        $mitra = Mitra::findOrFail($id);
        $produk = Produk::where('auth', auth()->user()->id)->get();
        $kota = Regency::all();
        $logs = Activity::where(['causer_id'=>auth()->user()->id, 'log_name' => 'ikm'])->latest()->take(10)->get();
        $kode_mitra = $mitra->kode_mitra;
        $penawaran = Penawaran::where('kode_mitra', $kode_mitra)->get();
        return view('mitra.action.add', [
            'activeMenu' => 'mitra',
            'active' => 'mitra',
        ], compact('mitra', 'produk', 'logs','penawaran','kota'));
    }

    public function mitraupdate(Request $request)
    {
        DB::beginTransaction();

        try {
            // 1. Simpan atau update data mitra
            $mitra = Mitra::updateOrCreate(
                ['kode_mitra' => $request->kode_mitra],
                [
                    'nama_mitra' => $request->nama_mitra,
                    'alamat_mitra' => $request->alamat_mitra,
                    'no_telp_mitra' => $request->no_telp_mitra,
                    'id_kota' => $request->id_kota,
                    'longitude' => $request->longitude,
                    'latitude' => $request->latitude,
                  
                ]
            );

            // Log aktivitas
            activity('ikm')->performedOn($mitra)->causedBy(auth()->user())->log('Mengubah atau Menambahkan Penawaran Baru di mitra ' . $request->nama_mitra);

            // 2. Hapus penawaran lama mitra ini (jika update)
            Penawaran::where('kode_mitra', $request->kode_mitra)->delete();

            // 3. Simpan penawaran baru
            $kode_produk = $request->input('kode_produk'); // array
            $harga = $request->input('harga'); // array

           // Cek jika ada penawaran produk yang dikirim
            if ($request->has('kode_produk') && is_array($request->kode_produk)) {
                // Bersihkan dulu penawaran lama jika perlu (opsional)
                Penawaran::where('kode_mitra', $request->kode_mitra)->delete();

                // Simpan penawaran baru
                foreach ($request->kode_produk as $index => $kode) {
                    if ($kode) {
                        Penawaran::create([
                            'kode_mitra' => $request->kode_mitra,
                            'kode_produk' => $kode,
                            'harga' => (int) str_replace('.', '', $request->harga[$index]),
                        ]);

                        
                    }
                }
            }

            DB::commit();
        
            return redirect()->route('detail.mitra',$request->id)->with('reload', true);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with("error", 'Gagal menyimpan penawaran: ' . $e->getMessage());
        }
    }

    public function mitaProdukDelete($id)
    {
        $produk = Penawaran::findOrFail($id);
        $produk->delete();
        activity('ikm')->performedOn($produk)->causedBy(auth()->user())->log('Menghapus Produk Mitra ' . $produk->nama_produk);
        return response()->json(['success' => true]);
    }

    public function mitraDelete($id){
        $mitra = Mitra::findOrFail($id);

        // Simpan nama dan kode mitra sebelum hapus
        $namaMitra = $mitra->nama_mitra;
        $kodeMitra = $mitra->kode_mitra;
        
        // Hapus semua penawaran yang punya kode_mitra ini
        Penawaran::where('kode_mitra', $kodeMitra)->delete();
        // Hapus semua transaksi yang terkait dengan kode_mitra ini
        Transaksi::where('kode_mitra', $kodeMitra)->delete();
        // Logging aktivitas
        activity('ikm')
            ->causedBy(auth()->user())
            ->log("Menghapus Mitra $namaMitra");
        
        // Hapus mitra
        $mitra->delete();

        return redirect()->route('index.mitra')->with("success", "Data mitra dan penawaran berhasil dihapus!");        
        
    }
    public function resolve(request $request)
    {
            $url = $request->input('url');

        try {
            // Kalau link diawali dengan maps.app.goo.gl → follow redirect
            if (str_contains($url, 'maps.app.goo.gl')) {
                $response = Http::withOptions(['allow_redirects' => true])->get($url);
                $finalUrl = $response->effectiveUri();
            } else {
                // Kalau bukan shortlink, langsung pakai URL-nya
                $finalUrl = $url;
            }

            // Coba ambil koordinat dari !3dLAT!4dLNG
            if (preg_match('/!3d(-?\d+\.\d+)!4d(-?\d+\.\d+)/', $finalUrl, $matches)) {
                return response()->json([
                    'latitude' => $matches[1],
                    'longitude' => $matches[2],
                    'full_url' => (string) $finalUrl
                ]);
            }

            // Atau dari @LAT,LNG
            if (preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $finalUrl, $matches)) {
                return response()->json([
                    'latitude' => $matches[1],
                    'longitude' => $matches[2],
                    'full_url' => (string) $finalUrl
                ]);
            }

            return response()->json(['error' => 'Koordinat tidak ditemukan di URL.'], 404);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal resolve link: ' . $e->getMessage()], 500);
        }

    }
}
