<?php

namespace App\Http\Controllers\Produk;

use App\Models\Akun;
use App\Models\Produk;
use App\Models\Satuan;
use App\Models\StokLog;
use App\Models\StokItem;
use App\Models\Penawaran;
use Illuminate\Http\Request;
use App\Models\StokTransaksi;
use Illuminate\Support\Carbon;
use App\Models\CategoryProduct;
use App\Models\TransaksiProduct;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;

class ProdukController extends Controller
{
    public function index()
    {
        $logs = Activity::where(['causer_id'=>auth()->user()->id, 'log_name' => 'ikm'])->latest()->take(10)->get();
       $produk = Produk::where('auth', auth()->user()->id)
            ->when(request('kategori'), function ($query) {
                $query->where('kategori', request('kategori'));
            })
            ->orderBy('created_at', 'desc')
            ->simplePaginate(25);
        $kategori = CategoryProduct::all();
        return view('produk.index',[
            'activeMenu' => 'produk',
            'active' => 'produk',
        ],compact('produk','logs','kategori'));
    }
    public function category()
    {
        $category = CategoryProduct::all();
        $logs = Activity::where(['causer_id'=>auth()->user()->id, 'log_name' => 'ikm'])->latest()->take(10)->get();
        return view('produk.category', [
            'activeMenu' => 'produk',
            'active' => 'category',
        ],compact('category','logs'));
    }
    public function createCategory(Request $request)
    {
        // Logic to create a new category
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Create the category (assuming you have a Category model)
        $ikm = CategoryProduct::create($request->all());
        activity('ikm')->performedOn($ikm)->causedBy(auth()->user())->log('Menambahkan Kategori Baru');
         return back()->with("success", "Data has been saved successfully!");
    }
    public function updateCategory(Request $request)
    {
        // Logic to update an existing category
        // Validate the request data
        $request->validate([
            'id' => 'required|exists:category_products,id',
            'name' => 'required|string|max:255',
        ]);

        // Find the category and update it
        $category = CategoryProduct::findOrFail($request->id);
        activity('ikm')->performedOn($category)->causedBy(auth()->user())->log('Mengubah Data Kategori '.$request->name);
        $category->update($request->all());
        return back()->with("success", "Data has been updated successfully!");
    }
    public function deleteCategory($id)
    {
        // Logic to delete a category
        $category = CategoryProduct::findOrFail($id);
        activity('ikm')->performedOn($category)->causedBy(auth()->user())->log('Menghapus Data Kategori '.$category->name);
        $category->delete();
        return back()->with("success", "Data has been deleted successfully!");
    }
    public function create()
    {
        $logs = Activity::where(['causer_id'=>auth()->user()->id, 'log_name' => 'ikm'])->latest()->take(10)->get();
        $category = CategoryProduct::all();
        $akun = Akun::with('kategori')->get();
        $satuans = Satuan::all();
        return view('produk.action.add_produk', [
            'activeMenu' => 'produk',
            'active' => 'add_produk',
        ],compact('category','logs','akun','satuans'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'satuan' => 'required',
            'stok' => 'required|integer',
            'deskripsi' => 'nullable|string',
            'kategori' => 'required|string',
            'gambar' => 'nullable|image|max:2048',
        ]);


        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('produk', 'public');
        }

        $produk = Produk::create([
            'kode_produk' => $request->kode_produk ?? 'PRD-' . uniqid(),
            'nama_produk' => $request->nama_produk,
            'harga' => $request->harga,
            'harga_jual' => $request->harga_jual,
            'stok' => $request->stok,
            'deskripsi' => $request->deskripsi,
            'berat' => $request->berat ?? 0,
            'auth' => auth()->user()->id,
            'status' => $request->status,
            'kategori' => $request->kategori,
            'satuan' => $request->satuan,
            'satuan_id' => $request->satuan_id,
            'gambar' => $gambarPath,
            'hpp_id' => $request->hpp_id,
            'pendapatan_id' => $request->pendapatan_id,
            'pendapatan_lainnya_id' => $request->pendapatan_lainnya_id,
            'persediaan_id'=> $request->persediaan_id,
            'beban_non_inventory_id'=> $request->beban_non_inventory_id,
        ]);
          if (!$produk) {
            return back()->with("error", "Produk gagal disimpan!");
        }
          StokLog::updateOrCreate(
                [
                    'kode_produk' => $produk->kode_produk,
                    'sumber' => 'manajemen_stok',
                    'referensi' => '0',
                    'auth' => auth()->id(),
                ],
                [
                    'tipe' => 'masuk',
                    'jumlah' => $request->stok, // Update dengan jumlah akhir, bukan selisih
                    'keterangan' => 'stok awal produk saat penambahan produk'
                ]
            );
        activity('ikm')->performedOn($produk)->causedBy(auth()->user())->log('Menambahkan Produk Baru '.$request->nama_produk);
        return back()->with("success", "Data has been saved successfully!");
    }
    public function update($id)
    {
        $logs = Activity::where(['causer_id'=>auth()->user()->id, 'log_name' => 'ikm'])->latest()->take(10)->get();
        $produk = Produk::where('id', $id)->get();
        $category = CategoryProduct::all();
        $akun = Akun::with('kategori')->get();
        $satuans = Satuan::all();
        return view('produk.action.update_produk', [
            'activeMenu' => 'produk',
            'active' => 'produk',
        ], compact('produk', 'category','logs','id','akun','satuans'));
    }


    public function updateaction(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'satuan' => 'required',
            'stok' => 'required|integer',
            'deskripsi' => 'nullable|string',
            'kategori' => 'required|string',
            'gambar' => 'nullable|image|max:2048',
        ]);

        // Cari data produk yang akan diupdate
        $produk = Produk::findOrFail($request->id);
        activity('ikm')->performedOn($produk)->causedBy(auth()->user())->log('Mengubah Produk '.$request->nama_produk);
        // Jika ada gambar baru diupload
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($produk->gambar && Storage::disk('public')->exists($produk->gambar)) {
                Storage::disk('public')->delete($produk->gambar);
            }

            // Simpan gambar baru
            $produk->gambar = $request->file('gambar')->store('produk', 'public');
        }

        // Update data produk
        $produk->update([
            'kode_produk' => $request->kode_produk ?? $produk->kode_produk,
            'nama_produk' => $request->nama_produk,
            'harga' => $request->harga,
            'harga_jual' => $request->harga_jual,
            'stok' => $request->stok,
            'deskripsi' => $request->deskripsi,
            'berat' => $request->berat ?? 0,
            'auth' => auth()->user()->id,
            'status' => $request->status,
            'kategori' => $request->kategori,
            'satuan' => $request->satuan,
            'satuan_id' => $request->satuan_id,
            'gambar' => $produk->gambar,
            'hpp_id' => $request->hpp_id,
            'pendapatan_id' => $request->pendapatan_id,
            'pendapatan_lainnya_id' => $request->pendapatan_lainnya_id,
            'persediaan_id'=> $request->persediaan_id,
            'beban_non_inventory_id'=> $request->beban_non_inventory_id,

        ]);

        return back()->with("success", "Data has been updated successfully!");
    }

    public function deleteaction($id)
    {
            $produk = Produk::findOrFail($id);
            // Cek di Penawaran dan TransaksiProduct
            $penawaranTerkait = Penawaran::where('kode_produk', $produk->kode_produk)->get();
            $transaksiTerkait = TransaksiProduct::where('kode_produk', $produk->kode_produk)->get();

            if ($penawaranTerkait->count() > 0 || $transaksiTerkait->count() > 0) {
                // Simpan ke session untuk ditampilkan di modal
                return redirect()->route('index.produk')->with([
                    'produk_error' => [
                        'nama' => $produk->nama_produk,
                        'penawaran' => $penawaranTerkait->map(function ($p) {
                            return [
                                'kode' => $p->kode_mitra,
                                'tanggal' => $p->created_at->format('d M Y'),
                            ];
                        }),
                        'transaksi' => $transaksiTerkait->map(function ($t) {
                            return [
                                'kode' => $t->kode_transaksi,
                                'tanggal' => $t->created_at->format('d M Y'),
                            ];
                        }),
                    ]
                ]);
            }

            // Jika aman untuk dihapus
            activity('ikm')->performedOn($produk)->causedBy(auth()->user())->log('Menghapus Produk ' . $produk->nama_produk);

            if ($produk->gambar && Storage::disk('public')->exists($produk->gambar)) {
                Storage::disk('public')->delete($produk->gambar);
            }

            $produk->delete();

            return redirect()->route('index.produk')->with('success', 'Produk berhasil dihapus!');
        }


    public function list()
    {
        return response()->json(CategoryProduct::orderBy('id', 'desc')->get());
    }

    public function manajemenStok()
    {
        $produk = Produk::where('auth', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();
            $logs = Activity::where(['causer_id'=>auth()->user()->id, 'log_name' => 'ikm'])->latest()->take(10)->get();

            // ambil bulan & tahun sekarang
            $bulanTahun = Carbon::now()->format('my'); // 0925

            // ambil transaksi terakhir untuk generate nomor urut
            $lastTransaksi = StokTransaksi::whereMonth('created_at', Carbon::now()->month)
                            ->whereYear('created_at', Carbon::now()->year)
                            ->orderBy('id', 'desc')
                            ->first();

            $nomor = 1; // default

            if($lastTransaksi) {
                // ambil 4 digit pertama sebelum slash
                $lastNo = intval(substr($lastTransaksi->no_transaksi, 0, 4));
                $nomor = $lastNo + 1;
            }

            $noTransaksi = str_pad($nomor, 4, '0', STR_PAD_LEFT) . "/BL/LTM/" . $bulanTahun;
              $satuans = Satuan::all();
        return view('persediaan.add_stok',[
            'activeMenu' => 'produk',
            'active' => 'produk',
        ],compact('logs','produk','noTransaksi','satuans'));
    }

    private function unformatRupiah($value)
    {
        return (int) preg_replace('/[^\d]/', '', $value);
    }

    public function manajemenStokcreate(Request $request)
        {
           // 🔹 Validasi utama transaksi
    $validated = $request->validate([
        'no_transaksi' => 'required|string',
        'tanggal'      => 'required|date_format:Y-m-d',
        'deskripsi'    => 'nullable|string|max:255',
        'subtotal'     => 'required',
        'potongan'     => 'nullable',
        'pajak'        => 'nullable',
        'total_akhir'  => 'required',
        'items'        => 'required|array|min:1',
    ]);
    // 🔹 Validasi tiap item
    foreach ($request->items as $index => $item) {
        $request->validate([
            "items.$index.kode_produk" => 'required|string|exists:produks,kode_produk',
            "items.$index.jumlah"      => 'required|numeric|min:1',
            "items.$index.harga"       => 'required|numeric|min:0',
            "items.$index.satuan_id"   => 'required|exists:satuans,id',
        ]);
    }

    // 🔹 Parsing angka dari format rupiah
    $subtotal   = $this->unformatRupiah($request->subtotal);
    $potongan   = $this->unformatRupiah($request->potongan);
    $pajak      = $this->unformatRupiah($request->pajak);
    $totalAkhir = $this->unformatRupiah($request->total_akhir);

    // 🔹 Buat atau update transaksi utama
    $transaksi = StokTransaksi::updateOrCreate(
        ['no_transaksi' => $request->no_transaksi],
        [
            'tanggal'     => $request->tanggal,
            'deskripsi'   => $request->deskripsi,
            'subtotal'    => $subtotal,
            'potongan'    => $potongan,
            'pajak'       => $pajak,
            'total_akhir' => $totalAkhir,
            'auth'        => auth()->id(),
        ]
    );

    // 🔹 Ambil item lama keyed by kode_produk
    $oldItems = $transaksi->items()->get()->keyBy('kode_produk');

    // 🔹 Simpan semua kode produk baru (untuk deteksi item yang dihapus)
    $newKodeProduks = collect($request->items)->pluck('kode_produk')->toArray();

    foreach ($request->items as $item) {
        $kodeProduk = $item['kode_produk'];
        $jumlahBaru = intval($item['jumlah']);
        $harga      = $this->unformatRupiah($item['harga']);
        $pot        = intval($item['pot'] ?? 0);
        $satuan     = $item['satuan_id'];

        // 🔸 Ambil produk di DB
        $produk = Produk::where('kode_produk', $kodeProduk)->firstOrFail();

        // 🔸 Cek item lama (jika ada)
        $oldItem = $oldItems[$kodeProduk] ?? null;
        $jumlahLama = $oldItem->jumlah ?? 0;
        $selisih = $jumlahBaru - $jumlahLama;

        // 🔹 Cegah stok minus
        if ($selisih < 0 && $produk->stok < abs($selisih)) {
            return back()->with('error', "Stok produk {$produk->nama_produk} tidak mencukupi!");
        }

        // 🔸 Update atau create item
        $transaksi->items()->updateOrCreate(
            ['kode_produk' => $kodeProduk],
            [
                'nama_produk' => $item['nama_produk'] ?? $produk->nama_produk,
                'jumlah'      => $jumlahBaru,
                'satuan'      => $satuan,
                'harga'       => $harga,
                'pot'         => $pot,
                'total'       => $jumlahBaru * $harga * (1 - $pot / 100),
            ]
        );

        // 🔹 Update stok produk
        $produk->stok += $selisih;
        $produk->satuan_id = $satuan;
        $produk->harga = $harga;
        $produk->save();

        // 🔹 Log aktivitas stok masuk saja (keluar akan dihandle di transaksi)
        if ($selisih > 0) {
            // Stok masuk - update atau create log
            StokLog::updateOrCreate(
                [
                    'kode_produk' => $kodeProduk,
                    'sumber' => 'manajemen_stok',
                    'referensi' => $transaksi->no_transaksi,
                    'auth' => auth()->id(),
                ],
                [
                    'tipe' => 'masuk',
                    'jumlah' => $jumlahBaru, // Update dengan jumlah akhir, bukan selisih
                    'keterangan' => 'Penambahan stok melalui manajemen stok'
                ]
            );
        } elseif ($selisih < 0) {
            // Jika stok berkurang, update log yang ada dengan jumlah baru
            $existingLog = StokLog::where([
                'kode_produk' => $kodeProduk,
                'sumber' => 'manajemen_stok',
                'referensi' => $transaksi->no_transaksi,
                'auth' => auth()->id(),
            ])->first();

            if ($existingLog) {
                $existingLog->update([
                    'jumlah' => $jumlahBaru, // Update dengan jumlah akhir
                    'keterangan' => 'Update stok melalui manajemen stok'
                ]);
            }
        } else {
            // Jika tidak ada perubahan stok, pastikan log tetap akurat
            $existingLog = StokLog::where([
                'kode_produk' => $kodeProduk,
                'sumber' => 'manajemen_stok',
                'referensi' => $transaksi->no_transaksi,
                'auth' => auth()->id(),
            ])->first();

            if ($existingLog) {
                $existingLog->update([
                    'jumlah' => $jumlahBaru, // Update dengan jumlah akhir
                    'keterangan' => 'Update data stok melalui manajemen stok'
                ]);
            }
        }

        // Selalu update keterangan pada log yang ada untuk menunjukkan update terakhir
        $logToUpdate = StokLog::where([
            'kode_produk' => $kodeProduk,
            'sumber' => 'manajemen_stok',
            'referensi' => $transaksi->no_transaksi,
            'auth' => auth()->id(),
        ])->first();

        if ($logToUpdate) {
            $logToUpdate->update([
                'keterangan' => 'Update stok melalui manajemen stok - ' . now()->format('d/m/Y H:i')
            ]);
        }

        // 🔹 Log aktivitas umum
        activity('ikm')
            ->causedBy(auth()->user())
            ->log("Update stok {$produk->nama_produk}: dari {$jumlahLama} → {$jumlahBaru} (selisih {$selisih})");
    }

    // 🔹 Hapus item lama yang tidak ada lagi di request
    $itemsToDelete = $oldItems->keys()->diff($newKodeProduks);

    foreach ($itemsToDelete as $kodeHapus) {
        $item = $oldItems[$kodeHapus];

        // Kembalikan stok
        $produk = Produk::where('kode_produk', $kodeHapus)->first();
        if ($produk) {
            $produk->stok -= $item->jumlah;
            $produk->save();
            // Stok keluar akan dilog di transaksi, bukan di sini
        }

        // Hapus item dari transaksi
        $item->delete();

        activity('ikm')
            ->causedBy(auth()->user())
            ->log("Hapus item {$produk->nama_produk} dari transaksi {$transaksi->no_transaksi}");
    }

    return redirect()
        ->route('manajemenStok.update', $transaksi->id)
        ->with('success', 'Transaksi berhasil disimpan atau diperbarui!');



        }

    public function manajemenStokIndex(){
        $stok = StokTransaksi::where('auth',auth()->id())->get();
        $logs = Activity::where(['causer_id'=>auth()->user()->id, 'log_name' => 'ikm'])->latest()->take(10)->get();

        return view('persediaan.index',[
              'activeMenu' => 'persediaan',
            'active' => 'persediaan',
        ],compact('logs','stok'));
    }
    public function manajemenStokUpdate($id){
         $produk = Produk::where('auth', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();
        $satuans = Satuan::all();
        $transaksi = StokTransaksi::with('items')->findOrFail($id);
        $logs = Activity::where(['causer_id'=>auth()->user()->id, 'log_name' => 'ikm'])->latest()->take(10)->get();

        return view('persediaan.edit_stok',[
            'activeMenu' => 'produk',
            'active' => 'persediaan',
        ],compact('logs','transaksi','produk','satuans'));
    }

    public function satuan(){
         $logs = Activity::where(['causer_id'=>auth()->user()->id, 'log_name' => 'ikm'])->latest()->take(10)->get();
        $satuans = Satuan::all();
         return view('persediaan.satuan',[
              'activeMenu' => 'produk',
            'active' => 'satuan',
        ],compact('logs','satuans'));
    }
    public function satuanAdd(request $request ){
         $request->validate([
            'nama' => 'required|unique:satuans',
        ]);

        Satuan::create($request->all());
         return back()->with("success", "Data Berhasil disimpan");
    }
    public function satuanUpdate(request $request){
        $request->validate([
            'nama' => 'required|unique:satuans',
        ]);

        Satuan::where('id',$request->id)->update(['nama'=>$request->nama]);
        return back()->with("success", "Data Berhasil diupdate");
    }
    public function satuanDelete($id){
          Satuan::with('satuan')->where('id',$id)->delete();
          return back()->with("success", "Data Berhasil dihapus");
    }
    public function manajemenStokDelete($id){
     $stok = StokItem::find($id);

        if ($stok) {
            $produk = Produk::where('kode_produk', $stok->kode_produk)->first();

            if ($produk) {
                // kurangi stok sesuai jumlah di stok_items
                if ($produk->stok >= $stok->jumlah) {
                    $produk->stok -= $stok->jumlah;
                } else {
                    $produk->stok = 0; // jaga-jaga biar tidak minus
                }
                $produk->save();
            }

            // hapus item stok
            $stok->delete();

            return back()->with("success", "Data Berhasil dihapus");
        }

        return back()->with("error", "Data tidak ditemukan!");
    }
    public function manajemenStokDeleteItem($id){
        $trans = StokTransaksi::with('items')->find($id);

        StokLog::where('referensi', $trans->no_transaksi)->delete();
        if ($trans) {
            foreach ($trans->items as $stok) {
                $produk = Produk::where('kode_produk', $stok->kode_produk)->first();

                if ($produk) {
                    $produk->stok = max(0, $produk->stok - $stok->jumlah);
                    $produk->save();
                    // Stok keluar akan dilog di transaksi, bukan di sini
                }

                $stok->delete();
            }

            $trans->delete();
            return back()->with("success", "Transaksi stok berhasil dihapus");
        }

        return back()->with("error", "Transaksi stok tidak ditemukan!");
    }
    public function logstok(Request $request){
        // Activity logs untuk sidebar
        $activityLogs = Activity::where(['causer_id'=>auth()->user()->id, 'log_name' => 'ikm'])->latest()->take(10)->get();

        // Query untuk stock logs dengan filter
        $produk = StokLog::with('produk')
            ->where('auth', auth()->user()->id);

        // Filter produk
        if ($request->produk_id) {
            $produk->where('kode_produk', $request->produk_id);
        }

        // Filter berdasarkan periode waktu
       if ($request->periode) {
            switch ($request->periode) {
                case 'bulanan':
                    if ($request->bulan && $request->tahun_bulan) {
                        $produk->whereMonth('created_at', $request->bulan)
                            ->whereYear('created_at', $request->tahun_bulan);
                    }
                    break;

                case 'tahunan':
                    if ($request->tahun_tahun) {
                        $produk->whereYear('created_at', $request->tahun_tahun);
                    }
                    break;

                case 'rentang':
                    if ($request->tanggal_awal && $request->tanggal_akhir) {
                        $tanggal_awal = Carbon::parse($request->tanggal_awal)->startOfDay()->format('Y-m-d H:i:s');
                        $tanggal_akhir = Carbon::parse($request->tanggal_akhir)->endOfDay()->format('Y-m-d H:i:s');
                        $produk->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
                    }
                    break;
            }
        } else {
            // Default: filter data hari ini
            $hariIni = Carbon::today();
            $produk->whereBetween('created_at', [
                $hariIni->copy()->startOfDay()->format('Y-m-d H:i:s'),
                $hariIni->copy()->endOfDay()->format('Y-m-d H:i:s')
            ]);
        }


        $produk->orderBy('created_at', 'desc');

        $produkList = Produk::where('auth', auth()->user()->id)->get();
        return view('persediaan.logstok', [
            'activeMenu' => 'produk',
            'active' => 'produk',
            'logs' => $produk->get(),
            'produkList' => $produkList,
            'produk_id' => $request->produk_id ?? '',
            'activityLogs' => $activityLogs,
        ]);
    }


}
