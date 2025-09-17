<?php

namespace App\Http\Controllers\Produk;

use App\Models\Produk;
use Illuminate\Http\Request;
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
        return view('produk.action.add_produk', [
            'activeMenu' => 'produk',
            'active' => 'add_produk',
        ],compact('category','logs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|numeric',
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
            'stok' => $request->stok,
            'deskripsi' => $request->deskripsi,
            'berat' => $request->berat ?? 0,
            'auth' => auth()->user()->id,
            'status' => $request->status,
            'kategori' => $request->kategori,
            'satuan' => $request->satuan,
            'gambar' => $gambarPath,
        ]);
        activity('ikm')->performedOn($produk)->causedBy(auth()->user())->log('Menambahkan Produk Baru '.$request->nama_produk);
        return back()->with("success", "Data has been saved successfully!");
    }
    public function update($id)
    {
        $logs = Activity::where(['causer_id'=>auth()->user()->id, 'log_name' => 'ikm'])->latest()->take(10)->get();
        $produk = Produk::where('id', $id)->get();
        $category = CategoryProduct::all();
        return view('produk.action.update_produk', [
            'activeMenu' => 'produk',
            'active' => 'produk',
        ], compact('produk', 'category','logs','id'));
    }

    
    public function updateaction(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|numeric',
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
            'nama_produk' => $request->nama_produk,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'deskripsi' => $request->deskripsi,
            'berat' => $request->berat ?? $produk->berat,
            'auth' => auth()->user()->id,
            'status' => $request->status ?? $produk->status,
            'kategori' => $request->kategori,
            'gambar' => $produk->gambar, // gunakan yang sudah diset (baru atau lama)
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
}
