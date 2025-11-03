<?php

namespace App\Http\Controllers\Produk;

use App\Models\Akun;
use App\Models\Produk;
use App\Models\Satuan;
use App\Models\StokLog;
use App\Models\StokItem;
use Illuminate\Http\Request;
use App\Models\StokTransaksi;
use Illuminate\Support\Carbon;
use App\Models\CategoryProduct;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;

class ProdukController extends Controller
{
    public function index()
    {
        $logs = Activity::where(['causer_id'=>auth()->user()->id, 'log_name' => 'ikm'])->latest()->take(10)->get();
        $produk = Produk::where('auth', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('produk.index',[
            'activeMenu' => 'produk',
            'active' => 'produk', 
        ],compact('produk','logs'));
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
        activity('ikm')->performedOn($produk)->causedBy(auth()->user())->log('Menghapus Produk '.$produk->nama_produk);
        
        // Hapus gambar produk jika ada
        if ($produk->gambar && Storage::disk('public')->exists($produk->gambar)) {
            Storage::disk('public')->delete($produk->gambar);
        }
        
        $produk->delete();
        return redirect()->route('index.produk')->with("success", "Data has been deleted successfully!");
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
             // validasi utama transaksi
            $validated = $request->validate([
            
                'tanggal'      => 'required|date_format:Y-m-d',
                'deskripsi'    => 'nullable|string|max:255',
                'subtotal'     => 'required',
                'potongan'     => 'nullable',
                'pajak'        => 'nullable',
                'total_akhir'  => 'required',
                'items'        => 'required|array|min:1', // wajib ada minimal 1 item
            ], [
                'no_transaksi.required' => 'Nomor transaksi wajib diisi!',
                'no_transaksi.unique'   => 'Nomor transaksi sudah digunakan!',
                'tanggal.required'      => 'Tanggal wajib diisi!',
                'items.required'        => 'Minimal harus ada 1 produk!',
            ]);

            // validasi tiap item
            foreach ($request->items as $index => $item) {
                $request->validate([
                    "items.$index.kode_produk" => 'required|string|exists:produks,kode_produk',
                    "items.$index.jumlah"      => 'required|numeric|min:1',
                    "items.$index.harga"       => 'required|numeric|min:0',
                    "items.$index.satuan_id"      => 'required|exists:satuans,id',
                ], [
                    "items.$index.kode_produk.required" => "Kode produk pada item #".($index+1)." wajib diisi",
                    "items.$index.kode_produk.exists"   => "Kode produk pada item #".($index+1)." tidak valid",
                    "items.$index.jumlah.required"      => "Jumlah pada item #".($index+1)." wajib diisi",
                    "items.$index.jumlah.numeric"       => "Jumlah pada item #".($index+1)." harus angka",
                    "items.$index.harga.required"       => "Harga pada item #".($index+1)." wajib diisi",
                    "items.$index.harga.numeric"        => "Harga pada item #".($index+1)." harus angka",
                    "items.$index.satuan.required"      => "Satuan pada item #".($index+1)." wajib dipilih",
                    "items.$index.satuan.exists"        => "Satuan pada item #".($index+1)." tidak valid",
                ]);
            }

           // parsing angka dari format rupiah
            $subtotal   = $this->unformatRupiah($request->subtotal);
            $potongan   = $this->unformatRupiah($request->potongan);
            $pajak      = $this->unformatRupiah($request->pajak);
            $totalAkhir = $this->unformatRupiah($request->total_akhir);

            // buat / update transaksi
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

            // ambil item lama keyed by kode_produk
            $oldItems = $transaksi->items()->get()->keyBy('kode_produk');

            foreach ($request->items as $item) {
                if (empty($item['kode_produk'])) continue;

                $kodeProduk = $item['kode_produk'];
                $jumlahBaru = intval($item['jumlah'] ?? 0);
                $harga      = $item['harga'];
                $pot        = intval($item['pot'] ?? 0);

                // cek item lama
                $oldItem = $oldItems[$kodeProduk] ?? null;
                $jumlahLama = $oldItem->jumlah ?? 0;

                // hitung selisih stok
                $selisih = $jumlahBaru - $jumlahLama;

                // update atau create item stok
                if ($oldItem) {
                    $oldItem->update([
                        'nama_produk' => $item['nama_produk'] ?? $oldItem->nama_produk,
                        'jumlah'      => $jumlahBaru,
                        'satuan'      => $item['satuan'] ?? $oldItem->satuan,
                        'harga'       => $harga,
                        'pot'         => $pot,
                        'total'       => $jumlahBaru * $this->unformatRupiah($harga) * (1 - ($pot/100)),
                    ]);
                } else {
                    $transaksi->items()->create([
                        'kode_produk' => $kodeProduk,
                        'nama_produk' => $item['nama_produk'] ?? '',
                        'jumlah'      => $jumlahBaru,
                        'satuan'      => $item['satuan'] ?? '',
                        'harga'       => $harga,
                        'pot'         => $pot,
                        'total'       => $jumlahBaru * $this->unformatRupiah($harga) * (1 - ($pot/100)),
                    ]);
                }

                // update stok produk sesuai selisih
                $produk = Produk::where('kode_produk', $kodeProduk)->firstOrFail();

                if ($selisih < 0 && $produk->stok < abs($selisih)) {
                    return back()->with("error", "Stok produk {$produk->nama_produk} tidak mencukupi!");
                }

                $produk->stok += $selisih;

                // update satuan & harga jika berubah
                if (!empty($item['satuan'])) {
                    $produk->satuan_id = $item['satuan'];
                    $produk->harga_jual = $item['harga'];
                }

                $produk->save();

                activity('ikm')
                    ->causedBy(auth()->user())
                    ->log("Manajemen Stok Produk {$produk->nama_produk} Jumlah lama: {$jumlahLama}, Jumlah baru: {$jumlahBaru}, Selisih: {$selisih}");
            }
          return redirect()
            ->route('manajemenStok.update', $transaksi->id)
            ->with('success', 'Stok berhasil disimpan/diperbarui!');

         

        }

    public function manajemenStokIndex(){
        $stok = StokTransaksi::where('auth',auth()->id())->get();
        $logs = Activity::where(['causer_id'=>auth()->user()->id, 'log_name' => 'ikm'])->latest()->take(10)->get();
        
        return view('persediaan.index',[
              'activeMenu' => 'produk',
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

        if ($trans) {
            foreach ($trans->items as $stok) {
                $produk = Produk::where('kode_produk', $stok->kode_produk)->first();

                if ($produk) {
                    $produk->stok = max(0, $produk->stok - $stok->jumlah);
                    $produk->save();
                }

                $stok->delete();
            }

            $trans->delete();
            return back()->with("success", "Transaksi stok berhasil dihapus");
        }

        return back()->with("error", "Transaksi stok tidak ditemukan!");
    }
}
