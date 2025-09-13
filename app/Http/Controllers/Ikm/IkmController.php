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
                <img  class="object-cover w-6 h-6 rounded-full object-cover ring-2 ring-white dark:ring-black" src="' . $foto . '" alt="Foto" loading="lazy">
                <span class="akun-nama">' . e($item->nama) . '</span>
            </a>',
            '<div ><a href="https://wa.me/' . preg_replace('/[^0-9]/', '', (substr($item->telp, 0, 1) === '0' ? '+62' . substr($item->telp, 1) : $item->telp)) . '" target="_blank" class="inline-flex items-center  py-1 rounded-full  hover:bg-green-600">' .($item->telp ?? '<span class="text-gray-500">Tidak Diketahui</span>') . '</a></div>',
            '<div class="mobile">' . ($item->email ?? '<span class="text-gray-500">Tidak Diketahui</span>') .'</div>',
            '<div>
                <a href="https://wa.me/' . preg_replace('/[^0-9]/', '', (substr($item->telp, 0, 1) === '0' ? '+62' . substr($item->telp, 1) : $item->telp)) . '" 
                    target="_blank" 
                    class="inline-flex items-center justify-center w-8 h-8 border border-green-600 text-green-600 rounded-full hover:bg-green-600 transition"
                    title="Chat WhatsApp">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-4 h-4" viewBox="0 0 24 24">
                            <path d="M20.52 3.48A11.92 11.92 0 0 0 12 .03C5.4.03.2 5.23.2 11.83a11.92 11.92 0 0 0 1.64 6.01L.03 24l6.27-1.64a11.84 11.84 0 0 0 6.01 1.64h.05c6.6 0 11.8-5.2 11.8-11.8 0-3.15-1.22-6.12-3.64-8.55Zm-8.67 17.9h-.04a9.9 9.9 0 0 1-5.04-1.39l-.36-.21-3.72.97.99-3.63-.23-.37a9.89 9.89 0 0 1-1.52-5.32C2.93 6.26 7.3 1.9 12.02 1.9c2.64 0 5.11 1.03 6.98 2.9a9.86 9.86 0 0 1-6.15 16.58ZM17 14.93c-.28-.14-1.65-.81-1.91-.9-.26-.1-.45-.14-.63.14-.19.28-.72.9-.88 1.09-.16.18-.32.2-.6.07-.28-.14-1.17-.43-2.23-1.37a8.4 8.4 0 0 1-1.55-1.93c-.16-.28-.02-.43.12-.56.13-.13.28-.32.43-.48.14-.16.19-.28.29-.46.1-.18.05-.35-.02-.49-.08-.14-.63-1.52-.86-2.08-.23-.55-.46-.48-.63-.49h-.54c-.18 0-.46.07-.7.35-.24.28-.93.91-.93 2.22 0 1.3.95 2.55 1.09 2.73.13.18 1.87 2.85 4.53 4a15.1 15.1 0 0 0 1.45.53c.61.19 1.16.17 1.6.1.49-.07 1.65-.67 1.89-1.31.23-.64.23-1.2.16-1.32-.06-.12-.25-.19-.53-.32Z"/>
                        </svg>
                </a>
            </div>',
            '<form action="' . route('ikm.updateRole', $item->id) . '" method="POST">
              ' . csrf_field() . '
              <select name="role" onchange="this.form.submit()" id="role"
                  class="form-select py-2.5 px-4 w-full text-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg placeholder:text-black/20 dark:placeholder:text-white/20 focus:border-black dark:focus:border-white/10 focus:ring-0 focus:shadow-none;">
                  <option value="admin" ' . ($item->user->role == "admin" ? "selected" : "") . '>Admin</option>
                  <option value="platinum" ' . ($item->user->role == "platinum" ? "selected" : "") . '>Platinum</option>
                  <option value="gold" ' . ($item->user->role == "gold" ? "selected" : "") . '>Gold</option>
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
      $request->validate([
          'role' => 'required|in:admin,platinum,gold'
      ]);
      $user = User::findOrFail($id);
      // Hapus role lama dan assign yang baru
      $user->syncRoles([$request->role]);
      $user->update(['role' => $request->role]);
      return redirect()->back()->with('success', 'Role berhasil diperbarui.');
  }
  public function getAktifData(Request $request)
  {
        date_default_timezone_set('Asia/Jakarta');
          // 1. Tentukan periode & durasi hari
        $periode = $request->periode ?? 'bulanan';
        $days = match($periode) {
            'harian'   => 1,
            'mingguan' => 7,
            'bulanan'  => 30,
            default    => 7,
        };

        // 2. Ambil semua user & hitung aktivitas
        $users = User::with('ikm')->get();
        $startDate = Carbon::now()->subDays($days);

        // 3. Hitung total user aktif (unik) berdasarkan aktivitas
        $aktifUserIds = UserActivity::where('created_at', '>=', $startDate)
            ->distinct('user_id')
            ->pluck('user_id');

        $totalUser = $users->count();
        $userAktifCount = $aktifUserIds->count();
        $tidakAktif = $totalUser - $userAktifCount;

        // 4. Format data untuk datatables
        $data = $users->sortByDesc("created_at")->values()->map(function ($user) use ($days, $startDate) {
            $activityCount = UserActivity::where('user_id', $user->id)
                ->where('created_at', '>=', $startDate)
                ->count();

            $percentage = min(100, ($activityCount / $days) * 100);

            $progressBar = '
                <div class="w-60 bg-gray-200 rounded-full h-3 overflow-hidden">
                    <div class="bg-green-600 h-3 rounded-full" style="width: ' . $percentage . '%;"></div>
                </div>
                <div class="text-xs mt-1">' . number_format($percentage, 0) . '%</div>
            ';

            return [
                $user->ikm->nama ?? '-',
                $progressBar,
            ];
        });

        // 5. Return response JSON
        return response()->json([
            'total_user'    => $totalUser,
            'user_aktif'    => $userAktifCount,
            'tidakaktif'    => $tidakAktif,
            'data'          => $data,
            'periode'      => $periode,
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
  public function update($id)
  {
    
    $ikm = ikm::where("id", $id)->first(); // Pakai first() langsung
    $user = User::where("phone",$ikm->telp)->first();
    $logs = Activity::where(['causer_id'=>auth()->user()->id, 'log_name' => 'ikm'])->get();

    $provinsi = Province::all();
   
    $mitra = Mitra::where('auth',$user->id)->get();
    $produk = Produk::where('auth',$user->id)->get();
    $transaksi = Transaksi::where('auth',$user->id)->with('mitra')->get();
    $keuangan = Keuangan::where('auth',$user->id)->paginate(10);
    $Ikmlogs = Activity::where(['causer_id'=>$user->id, 'log_name' => 'ikm'])->latest()->take(10)->get();
    if (!$ikm) {
      abort(404); // Data tidak ditemukan
    }

    // Ambil semua field sebagai array
    $data = $ikm->toArray();
    unset($data['sosmed'], $data['website']);
    // Hitung presentase kelengkapan data
    $totalFields = count($data);
    $emptyFields = collect($data)
      ->filter(function ($value) {
        return empty($value);
      })
      ->count();

    $filledFields = $totalFields - $emptyFields;
    $percentage = intval(($filledFields / $totalFields) * 100);

    // Kirim semua data ke view dalam satu array
    return view("ikm.action.edit", [
      "activeMenu" => "ikm_update",
      "active" => "ikm",
      "ikm" => $ikm,
      "provinsi" => $provinsi,
      "percentage" => $percentage,
      'emptyFields' => $emptyFields
    ],compact("id","logs","mitra","produk","transaksi","keuangan","Ikmlogs"));
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
