<?php

namespace App\Http\Controllers\Perusahaan;

use DB;
use App\Models\Province;
use App\Models\Legalitas;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;

class PerusahaanController extends Controller
{
  public function index()
  {
    $provinsi = Province::all();
    return view("perusahaan.setup",compact('provinsi'));
  }
  public function create(request $request)
  {
    $usaha = Perusahaan::where("auth", auth()->user()->id)->get();
    if ($usaha->count() > 0) {
      toastr()->warning("Anda Sudah Registrasi Perusahaan Sebelumnya");
      return redirect()->back();
    } else {
      Perusahaan::create([
        "nama_perusahaan" => $request->name,
        "telp_perusahaan" => $request->phone,
        "alamat" => $request->alamat,
        "email" => $request->email,
        "id_provinsi" => $request->id_provinsi,
        "id_kota" => $request->id_kota,
        "id_kecamatan" => $request->id_kecamatan,
        "id_desa" => $request->id_desa,
        "auth" => auth()->user()->id,
      ]);
     return redirect()->route('dashboard.keuangan')->with('success', 'Berhasil Login, Selamat Datang');
    }
  }

  public function PerusahaanSetting()
  {
     $provinsi = Province::all();
    $perusahaan = Perusahaan::where("auth", auth()->user()->id)->first();
    $logs = Activity::where([
      "causer_id" => auth()->user()->id,
      "log_name" => "ikm",
    ])
      ->latest()
      ->take(10)
      ->get();
    return view(
      "perusahaan.index",
      [
        "activeMenu" => "setelan",
        "active" => "setelan",
      ],
      compact("logs", "perusahaan","provinsi")
    );
  }

  public function uploadLogo(Request $request)
  {
    try {
        $request->validate([
            'id' => 'required|exists:perusahaans,id',
            'logo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Dapatkan perusahaan berdasarkan ID
        $perusahaan = Perusahaan::findOrFail($request->id);

        // Hapus logo lama jika ada
        if ($perusahaan->logo && Storage::disk('public')->exists($perusahaan->logo)) {
            Storage::disk('public')->delete($perusahaan->logo);
        }

        // Simpan file baru
        $path = $request->file('logo')->store('perusahaan/logos', 'public');
        
        // Update data perusahaan
        $perusahaan->update([
            'logo' => $path
        ]);
        
        return response()->json([
            'success' => true,
            'path' => asset("storage/$path"),
            'message' => 'Logo perusahaan berhasil diperbarui'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal mengupdate logo: ' . $e->getMessage()
        ], 500);
    }
  }
  public function updateProfil(Request $request)
  {
    $validated = $request->validate([
      "nama_perusahaan" => "required|string|max:255",
      "email" => "required|email|max:255",
      "alamat" => "required|string",
      "telp_perusahaan" => "required|string",
      "id_provinsi" => "string",
      "id_kota" => "string",
      "id_kecamatan" => "string",
      "id_desa" => "string",
    ]);

    Perusahaan::where("id", $request->id)->update($validated);
    return back()->with("success", "Profil perusahaan berhasil diperbarui");
  }

  public function updateLegalitas(Request $request)
  {
    $idPerusahaan = $request->input('id_perusahaan');

    // Legalitas existing (nib, npwp, dst)
    $legalitasInputs = $request->input('legalitas_existing', []);
    foreach ($legalitasInputs as $key => $data) {
        if (!empty($data['nomor'])) {
            $lampiranFile = $request->file("legalitas_existing.$key.lampiran");
            $lampiranPath = null;

            if ($lampiranFile && $lampiranFile->isValid()) {
                $lampiranPath = $lampiranFile->store('lampiran_legalitas', 'public');
            }

            $existing = DB::table('legalitas')
                ->where('id_perusahaan', $idPerusahaan)
                ->where('legalitas', $key)
                ->first();

            if ($existing) {
                DB::table('legalitas')
                    ->where('id', $existing->id)
                    ->update([
                        'nomor' => $data['nomor'],
                        'lampiran' => $lampiranPath ?? $existing->lampiran,
                        'updated_at' => now(),
                    ]);
            } else {
                DB::table('legalitas')->insert([
                    'id_perusahaan' => $idPerusahaan,
                    'legalitas' => $key,
                    'nomor' => $data['nomor'],
                    'lampiran' => $lampiranPath,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    // Legalitas baru
    $newInputs = $request->input('legalitas_new', []);
    foreach ($newInputs as $i => $data) {
        if (!empty($data['nomor']) && !empty($data['type'])) {
            $lampiranFile = $request->file("legalitas_new.$i.lampiran");
            $lampiranPath = null;

            if ($lampiranFile && $lampiranFile->isValid()) {
                $lampiranPath = $lampiranFile->store('lampiran_legalitas', 'public');
            }

            DB::table('legalitas')->insert([
                'id_perusahaan' => $idPerusahaan,
                'legalitas' => $data['type'],
                'nomor' => $data['nomor'],
                'lampiran' => $lampiranPath,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    return redirect()->back()->with('success', 'Data legalitas berhasil disimpan');
  }

  public function updateStamp(Request $request)
  {
    // $request->validate([
    //     'stempel' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
    //     'ttd_file' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
    //     'ttd_base64' => 'nullable|string',
    //     'keterangan_pembayaran' => 'nullable|string'
    // ]);
    
    $perusahaan = Perusahaan::findOrFail($request->id_perusahaan);

       
    // Upload stempel file
    if ($request->hasFile('stempel')) {
        $stempelName = 'stempel_' . uniqid() . '.' . $request->stempel->extension();
        $stamp = $request->file('stempel')->store('perusahaan/stampel', 'public');
        $perusahaan->stamp =  $stamp ;
    }
    

    // Upload file tanda tangan manual (jika diupload via input file)
    if ($request->hasFile('ttd_file')) {
        $ttdName = 'ttd_' . uniqid() . '.' . $request->ttd_file->extension();
        $ttd = $request->file('ttd_file')->store('perusahaan/ttd', 'public');
        $perusahaan->ttd = $ttd;
    }

 
    // Upload dari base64 canvas
    if ($request->filled('ttd_base64')) {
        $imageData = str_replace('data:image/png;base64,', '', $request->ttd_base64);
        $imageData = str_replace(' ', '+', $imageData);
        $ttdName = 'ttd_canvas_' . uniqid() . '.png';
        Storage::disk('public')->put('tanda_tangan/' . $ttdName, base64_decode($imageData));
        $perusahaan->ttd = 'tanda_tangan/' . $ttdName;
    }

    // Simpan keterangan pembayaran
    $perusahaan->keterangan_pembayaran = $request->keterangan_pembayaran;
    $perusahaan->save();

    return redirect()->back()->with('success', 'Stempel, tanda tangan, dan keterangan berhasil diperbarui.');
  }

  public function HapusLegalitas($id){
    Legalitas::where('id',$id)->delete();
    return back()->with("success", "Stempel dan template berhasil dihapus");
  }
}
