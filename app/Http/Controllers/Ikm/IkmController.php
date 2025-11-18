<?php

namespace App\Http\Controllers\Ikm;

use App\Models\ikm;
use App\Models\User;
use App\Models\Mitra;
use App\Models\Produk;
use App\Models\Keuangan;
use App\Models\Province;
use App\Models\Transaksi;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;

class IkmController extends Controller
{
  public function index()
  {
    date_default_timezone_set('Asia/Jakarta');
    $logs = Activity::where(['causer_id'=>auth()->user()->id, 'log_name' => 'ikm'])->latest()->take(10)->get();
    $ikm = ikm::all()->sortByDesc("created_at")->map(function ($item) {
        $foto = $item->foto ? asset('storage/' . $item->foto) : asset('assets/images/byewind-avatar.png');
        return [
             '<a href="' . route("ikm.update", $item->id) . '" class="flex items-center space-x-2 text-blue-600 hover:underline">
                <img  class="object-cover w-6 h-6 rounded-full  ring-2 ring-white dark:ring-black" src="' . $foto . '" alt="Foto" loading="lazy">
                <span class="akun-nama">' . e($item->nama) . '</span>
            </a>',
            '<div ><a href="https://wa.me/' . preg_replace('/[^0-9]/', '', (substr($item->telp, 0, 1) === '0' ? '+62' . substr($item->telp, 1) : $item->telp)) . '" target="_blank" class="inline-flex items-center  py-1 rounded-full  hover:bg-green-600">' .($item->telp ?? '<span class="text-gray-500">Tidak Diketahui</span>') . '</a></div>',
            '<div class="mobile">' . ($item->email ?? '<span class="text-gray-500">Tidak Diketahui</span>') .'</div>',
           
           '<form action="' . route('ikm.updateRole', $item->user->id) . '" method="POST">
        ' . csrf_field() . '
       
        <select name="role" onchange="this.form.submit()" id="role"
            class="form-select py-2.5 px-4 w-full text-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg placeholder:text-black/20 dark:placeholder:text-white/20 focus:border-black dark:focus:border-white/10 focus:ring-0 focus:shadow-none;">
            <option value="admin" ' . ($item->user?->role == "admin" ? "selected" : "") . '>Admin</option>
            <option value="platinum" ' . ($item->user?->role == "platinum" ? "selected" : "") . '>Platinum</option>
            <option value="gold" ' . ($item->user?->role == "gold" ? "selected" : "") . '>Gold</option>
        </select>
    </form>'
        ];

      })->values();

        // Hitung jumlah berdasarkan jenis kelamin
     $jumlah = ikm::select('jenis_kelamin', DB::raw('count(*) as total'))->whereIn('jenis_kelamin', ['L', 'P'])
        ->groupBy('jenis_kelamin')
        ->pluck('total', 'jenis_kelamin');

      return view("ikm.index",[
        "activeMenu" => "ikm",
        "active" => "ikm",
      ],compact("ikm", "jumlah","logs"));
  }
  public function updateRole(Request $request, $id)
  {
    
    $user = User::findOrFail($id);
 
    // Hapus role lama dan assign role baru
    $user->syncRoles([$request->role]);
    $user->role = $request->role;
    $user->save();

    return redirect()->back()->with('success', 'Role berhasil diperbarui.');
  }

  public function keaktifan()
  { 
    $bulan = request('bulan', Carbon::now()->month);
    $tahun = request('tahun', Carbon::now()->year);
    
     $logs = Activity::where(['causer_id'=>auth()->user()->id, 'log_name' => 'ikm'])->latest()->take(10)->get();
      return view("ikm.action.keaktifan",[
          "activeMenu" => "ikm",
          "active" => "ikm",
      ],compact("logs","bulan","tahun"));
  }
  public function getAktifData(Request $request)
  {
       date_default_timezone_set('Asia/Jakarta');

        $periode = $request->input('periode', 'harian');
        $bulan   = (int) $request->input('bulan', Carbon::now()->month);
        $tahun   = (int) $request->input('tahun', Carbon::now()->year);
        $hari    = (int) $request->input('hari', Carbon::now()->day);

        $days = match ($periode) {
            'harian'   => 1,
            'mingguan' => 7,
            'bulanan'  => 30,
            default    => 7,
        };

        $users = User::with('ikm')->get();

        // Filter UserActivity sesuai periode
        $queryAktif = UserActivity::query();

        if ($periode === 'harian') {
            $queryAktif->whereDate('created_at', Carbon::createFromDate($tahun, $bulan, $hari));
        } else {
            $queryAktif->whereMonth('created_at', $bulan)
                    ->whereYear('created_at', $tahun);
        }

        $aktifUserIds = $queryAktif->distinct('user_id')->pluck('user_id');

        $totalUser      = $users->count();
        $userAktifCount = $aktifUserIds->count();
        $tidakAktif     = $totalUser - $userAktifCount;

        $data = $users->map(function ($user) use ($periode, $bulan, $tahun, $hari, $days) {
            $query = UserActivity::where('user_id', $user->id);

            if ($periode === 'harian') {
                $query->whereDate('created_at', Carbon::createFromDate($tahun, $bulan, $hari));
            } else {
                $query->whereMonth('created_at', $bulan)
                    ->whereYear('created_at', $tahun);
            }

            $activityCount = $query->count();
            $percentage    = $days > 0 ? min(100, ($activityCount / $days) * 100) : 0;

            $progressBar = '
                <div class="w-60 bg-gray-200 rounded-full h-3 overflow-hidden">
                    <div class="bg-green-600 h-3 rounded-full" style="width: ' . $percentage . '%;"></div>
                </div>
                <div class="text-xs mt-1">' . number_format($percentage, 0) . '%</div>
            ';

            return [
                $user->ikm->nama ?? '-',
                $progressBar,
                $user->ikm->id,
            ];
        })->values();

        return response()->json([
            'total_user' => $totalUser,
            'user_aktif' => $userAktifCount,
            'tidakaktif' => $tidakAktif,
            'data'       => $data,
            'periode'    => $periode,
            'bulan'      => $bulan,
            'tahun'      => $tahun,
            'hari'       => $hari,
        ]);
  }

  public function create()
  {
    $logs = Activity::where(['causer_id'=>auth()->user()->id, 'log_name' => 'ikm'])->latest()->take(10)->get();
    $provinsi = Province::all();
    return view("ikm.action.add",[
        "activeMenu" => "ikm_create",
        "active" => "ikm_create",
      ],compact("provinsi","logs")
    );
  }

  public function store(Request $request)
  {
    // Validasi input
    $validatedData = $request->validate([
      "nik" => "required|string|max:20|unique:ikms,nik",
      "nama" => "required|string|max:255",
      "tempat_lahir" => "required|string|max:255",
      "tanggal_lahir" => "required|date",
      "jenis_kelamin" => "required|in:L,P",
      "alamat" => "required|string|max:255",
      "rt" => "nullable|string|max:10",
      "rw" => "nullable|string|max:10",
      "id_provinsi" => "nullable|integer",
      "id_kota" => "nullable|integer",
      "id_kecamatan" => "nullable|integer",
      "id_desa" => "nullable|integer",
      "agama" => "nullable|string|max:50",
      "status_perkawinan" => "nullable|string|max:50",
      "pekerjaan" => "nullable|string|max:100",
      "kewarganegaraan" => "nullable|string|max:50",
      "telp" => "required|string|max:20",
      "sosmed" => "nullable|string|max:100",
      "website" => "nullable|url|max:255",
      "email" => "required|email|max:255",

    ]);


    // Simpan data ke database
    $ikm = ikm::create($validatedData);
    // Log aktivitas
    activity('ikm')->performedOn($ikm)->causedBy(auth()->user())->log('Menambahkan Data Pengguna');
    // Simpan user terkait
    User::create([
      'name' => $validatedData['nama'],
      'phone'=>$validatedData['telp'],
      'email' => $validatedData['email'], // Gunakan NIK sebagai email default jika tidak ada
      'password' => $validatedData['telp'],
    ]);

    return redirect()->route("index.ikm")->with("success", "Data has been saved successfully!");
  }
  public function update( request $request,$id)
  {
      // 🔹 Ambil data IKM
    $ikm = Ikm::find($id);
    if (!$ikm) {
        abort(404);
    }
    
    // 🔹 Ambil user berdasarkan nomor telepon IKM
    $user = User::where("phone", $ikm->telp)->first();
    if (!$user) {
        abort(404, 'User tidak ditemukan untuk IKM ini.');
    }
       $daftarTransaksi = Transaksi::where('auth', $user->id)->with('mitra')->get();
    // 🔹 Ambil filter dari request
    $bulan = $request->input('bulan', date('m'));
    $tahun = $request->input('tahun', date('Y'));

    $from = $request->input('from');
    $to = $request->input('to');

    // Jika ada filter "periode" dalam format yyyy-mm
    if ($request->filled('periode')) {
        [$tahun, $bulan] = explode('-', $request->periode);
    }
     
      if ($from && $to) {
          try {
              $fromDate = Carbon::createFromFormat('d/m/Y', $from)->startOfDay();
              $toDate   = Carbon::createFromFormat('d/m/Y', $to)->endOfDay();
              // filter query di sini
          } catch (\Exception $e) {
              // abaikan kalau format salah
          }
      } elseif ($from && !$to) {
          // hanya dari tanggal, set ke hari yang sama
          try {
              $fromDate = Carbon::createFromFormat('d/m/Y', $from)->startOfDay();
              $toDate   = $fromDate->copy()->endOfDay();
          } catch (\Exception $e) {}
      } else {
          // fallback ke bulan/tahun
      }
    // 🔹 Data umum
    $provinsi = Province::all();
    $mitra = Mitra::where('auth', $user->id)->get();
    $produk = Produk::where('auth', $user->id)->get();

    // 🔹 Query dasar
    $transaksiQuery = Transaksi::where('auth', $user->id)->with('mitra');
    $keuanganQuery = Keuangan::where('auth', $user->id);

  if (request('tipe')) {
            $keuanganQuery->where('tipe', request('tipe'));
        }
    // ==============================================================
    // 🔹 FILTER DATA BERDASARKAN RANGE ATAU BULAN/TAHUN
    // ==============================================================
    // ==============================================================
// 🔹 FILTER DATA BERDASARKAN RANGE ATAU BULAN/TAHUN
// ==============================================================
    if ($from && $to) {
        try {
            // 🧩 Deteksi otomatis format tanggal (d/m/Y atau Y-m-d atau d-m-Y)
            $fromDate = null;
            $toDate = null;

            foreach (['d/m/Y', 'Y-m-d', 'd-m-Y'] as $fmt) {
                try {
                    $fromDate = Carbon::createFromFormat($fmt, $from);
                    $toDate = Carbon::createFromFormat($fmt, $to);
                    break; // Berhenti kalau berhasil parse
                } catch (\Exception $e) {
                    continue;
                }
            }

            if (!$fromDate || !$toDate) {
                $fromDate = Carbon::parse($from);
                $toDate = Carbon::parse($to);
            }

            $fromDate = $fromDate->startOfDay();
            $toDate = $toDate->endOfDay();

            // ✅ Keuangan → format tanggal: d/m/Y
            $keuanganQuery->whereRaw("
                STR_TO_DATE(tanggal, '%d/%m/%Y') BETWEEN ? AND ?
            ", [
                $fromDate->format('Y-m-d'),
                $toDate->format('Y-m-d')
            ]);

            // ✅ Transaksi → format tanggal_transaksi: d-m-Y
            $transaksiQuery->whereRaw("
                STR_TO_DATE(tanggal_transaksi, '%Y-%m-%d') BETWEEN ? AND ?
            ", [
                $fromDate->format('Y-m-d'),
                $toDate->format('Y-m-d')
            ]);

        } catch (\Exception $e) {
            // Abaikan error format
        }
    } else {
        // 🔹 Jika tidak ada filter tanggal, gunakan bulan & tahun
        $keuanganQuery
            ->whereRaw("MONTH(STR_TO_DATE(tanggal, '%d/%m/%Y')) = ?", [$bulan])
            ->whereRaw("YEAR(STR_TO_DATE(tanggal, '%d/%m/%Y')) = ?", [$tahun]);

        $transaksiQuery
            ->whereRaw("MONTH(STR_TO_DATE(tanggal_transaksi, '%Yd-%m-%d')) = ?", [$bulan])
            ->whereRaw("YEAR(STR_TO_DATE(tanggal_transaksi, '%Y-%m-%d')) = ?", [$tahun]);
    }

    // ==============================================================
    // 🔹 Eksekusi Query
    // ==============================================================
    $transaksi = $transaksiQuery->get();
    $keuangan = $keuanganQuery->paginate(10);

    // 🔹 Activity logs
    $Ikmlogs = Activity::where(['causer_id' => $user->id, 'log_name' => 'ikm'])
        ->latest()
        ->take(10)
        ->get();

    $logs = Activity::where(['causer_id' => auth()->user()->id, 'log_name' => 'ikm'])->get();

    // ==============================================================
    // 🔹 Hitung kelengkapan data IKM
    // ==============================================================
    $data = $ikm->toArray();
    unset($data['sosmed'], $data['website']);

    $totalFields = count($data);
    $emptyFields = collect($data)->filter(fn($value) => empty($value))->count();
    $filledFields = $totalFields - $emptyFields;
    $percentage = intval(($filledFields / $totalFields) * 100);

    // ==============================================================
    // 🔹 Return ke view
    // ==============================================================
    return view("ikm.action.edit", [
        "activeMenu" => "ikm_update",
        "active" => "ikm",
        "ikm" => $ikm,
        "provinsi" => $provinsi,
        "percentage" => $percentage,
        "emptyFields" => $emptyFields,
        "id" => $user->id,
        "logs" => $logs,
        "mitra" => $mitra,
        "produk" => $produk,
        "transaksi" => $transaksi,
        "transaksi2" => $daftarTransaksi,
        "keuangan" => $keuangan,
        "Ikmlogs" => $Ikmlogs,
        "tahun" => $tahun,
        "bulan" => $bulan,
        "from" => $from,
        "to" => $to,
    ]);
  }

    public function updateFoto(Request $request)
    {
        // Validate inputs
        $request->validate([
            'id' => 'required|exists:ikms,id',
            'croppedFoto' => 'required|string', // Base64 string
        ]);

        try {
            // Extract the Base64 string
            $base64Image = $request->input('croppedFoto');

            // Decode and process the image
            $imageParts = explode(';base64,', $base64Image);
            $imageType = explode('image/', $imageParts[0])[1]; // Get extension (e.g., jpeg, png)
            $imageBase64 = base64_decode($imageParts[1]);

            // Generate unique file name
            $fileName = 'ikm-foto/' . uniqid() . '.' . $imageType;

            // Save to storage
            Storage::disk('public')->put($fileName, $imageBase64);


            // Delete old image if it exists
            if ($request->oldImage) {
                Storage::delete($request->oldImage); // Deletes from `storage/app`
            }

            // Update database with new image path
            ikm::where('id', $request->id)->update(['foto' => $fileName]);
            $ikm = ikm::find($request->id);
            // Log the activity
            if($ikm){
                activity('ikm')->performedOn($ikm)->causedBy(auth()->user())->log('Memperbarui Foto Profil Pengguna');
            }

            return redirect()->back()->with("success", "Profile photo updated successfully.");

        } catch (\Exception $e) {
            return redirect()->back()->with("error", 'An error occurred: ' . $e->getMessage());
        }
    }

    public function updateIkm(Request $request)
    {
     // Validasi input
        $validatedData = $request->validate([
            "nik" => "required|string|max:20",
            "nama" => "required|string|max:255",
            "tempat_lahir" => "required|string|max:255",
            "tanggal_lahir" => "required|date",
            "jenis_kelamin" => "required|in:L,P",
            "alamat" => "required|string|max:255",
            "rt" => "nullable|string|max:10",
            "rw" => "nullable|string|max:10",
            "id_provinsi" => "nullable|integer",
            "id_kota" => "nullable|integer",
            "id_kecamatan" => "nullable|integer",
            "id_desa" => "nullable|integer",
            "agama" => "nullable|string|max:50",
            "status_perkawinan" => "nullable|string|max:50",
            "pekerjaan" => "nullable|string|max:100",
            "kewarganegaraan" => "nullable|string|max:50",
            "telp" => "nullable|string|max:20",
            "sosmed" => "nullable|string|max:100",
            "website" => "nullable|url|max:255",
            "email" => "nullable|email|max:255",

        ]);

        // Update data di database
        ikm::where('id',$request->id)->update($validatedData);
        User::where('email',$validatedData['email'])->update([
            'name' => $validatedData['nama'],
            'phone'=>$validatedData['telp'],
        ]);
          $ikm = ikm::find($request->id);
            // Log the activity
            if($ikm){
                activity('ikm')->performedOn($ikm)->causedBy(auth()->user())->log('Memperbarui Data Pengguna');
            }

         return redirect()->route('ikm.update',$request->id)->with("success", "Profile photo updated successfully.");
     }

    public function delete($id)
    {

        $ikm = ikm::find($id);
        if ($ikm) {
            activity('ikm')
                ->performedOn($ikm)
                ->causedBy(auth()->user())
                ->log('Menghapus Data Pengguna');

            if ($ikm->foto) {
                Storage::disk('public')->delete($ikm->foto);
            }

            $ikm->delete();

            // Hapus user terkait
            $user = User::where('email', $ikm->email ?? '')->first();
            if ($user) {
                $user->delete();
            }
        }

        return redirect()->route("index.ikm")->with("success", "Profile photo updated successfully.");
    }
}
