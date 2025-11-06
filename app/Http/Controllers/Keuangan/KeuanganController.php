<?php

namespace App\Http\Controllers\Keuangan;

use Storage;
use App\Models\App;
use App\Models\Akun;
use App\Models\Keuangan;
use App\Models\Rekening;
use App\Models\KategoriAkun;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\HistoryRekening;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Flasher\Laravel\Facade\Flasher;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Spatie\Activitylog\Models\Activity;

class KeuanganController extends Controller
{
    public function index(){
        $rekening = Rekening::where('auth', auth()->user()->id)->latest()->get();

        // Ambil parameter request
        $sort  = request('sort', 'desc');
        $from  = request('from');
        $to    = request('to');
        $bulan = request('bulan', date('m'));
        $tahun = request('tahun', date('Y'));

        $query = Keuangan::with(['akun', 'rekening'])
            ->where('auth', auth()->id());

        if (request('periode')) {
            [$tahun, $bulan] = explode('-', request('periode'));
        } else {
            $bulan = date('m');
            $tahun = date('Y');
        }
        // 🔹 Jika ADA filter tanggal (from & to), gunakan range ini
        if ($from && $to) {
            try {
                $fromDate = Carbon::createFromFormat('d/m/Y', $from)->format('Y-m-d');
                $toDate   = Carbon::createFromFormat('d/m/Y', $to)->format('Y-m-d');

                $query->whereRaw("STR_TO_DATE(tanggal, '%d/%m/%Y') BETWEEN ? AND ?", [$fromDate, $toDate]);
            } catch (\Exception $e) {
                // Abaikan jika format salah
            }
        } else {
            // 🔹 Jika TIDAK ADA filter tanggal, pakai filter bulan & tahun
            if ($bulan && $tahun) {
                $query->whereRaw("MONTH(STR_TO_DATE(tanggal, '%d/%m/%Y')) = ?", [$bulan])
                    ->whereRaw("YEAR(STR_TO_DATE(tanggal, '%d/%m/%Y')) = ?", [$tahun]);
            }
        }
        // 🔹 Filter tipe transaksi
        if (request('tipe')) {
            $query->where('tipe', request('tipe'));
        }

        if (request('periode')) {
            [$tahun, $bulan] = explode('-', request('periode'));
        } else {
            $bulan = date('m');
            $tahun = date('Y');
        }
        // 🔹 Urutkan dan paginasi
        $transaksi = $query->orderByRaw("STR_TO_DATE(tanggal, '%d/%m/%Y') $sort")
            ->orderBy('id', 'desc')
            ->simplePaginate(31)
            ->appends(request()->query());

        // 🔹 Ambil data akun
        $akun = Akun::with('kategori')->get();

        $akunJs = $akun->map(function ($a) {
            return [
                'id' => $a->id,
                'text' => $a->kode_akun . ' | ' . $a->nama_akun,
                'kategori' => $a->kategori->nama_kategori ?? '',
                'tipe' => $a->kategori->tipe ?? '',
            ];
        });


        $logs = Activity::where(['causer_id'=>auth()->user()->id, 'log_name' => 'ikm'])->latest()->take(10)->get();
        return view('keuangan.index',[
            'activeMenu' => 'keuangan',
            'active' => 'keuangan',
        ],compact('logs','rekening','akun','transaksi','tahun','bulan','akunJs'));
    }

    public function IndexAkun(){
      
        $akun = Akun::all();
        $kategori = KategoriAkun::all();
        $logs = Activity::where(['causer_id'=>auth()->user()->id, 'log_name' => 'ikm'])->latest()->take(10)->get();
        return view('keuangan.akun',[
            'activeMenu' => 'akun',
            'active' => 'akun',
        ],compact('logs','akun','kategori'));
    }

    public function akunCreate(request $request){
        
       $request->validate([
            'nama_akun'   => 'required|string|max:255',
            'jenis_akun'  => 'required',
            'kode_akun'   => 'required',
            'kategori_id' => 'required',
        ]);

        // Merge field baru dengan tanda "-"
        $request->merge([
            'kode_akun' => $request->id . '-' . $request->kode_akun,
        ]);

        // Simpan data
        $akun = Akun::create($request->all());

        // Log aktivitas
        activity('ikm')
            ->performedOn($akun)
            ->causedBy(auth()->user())
            ->log('Menambahkan Akun Baru ' . $request->nama_akun);

        // Redirect
        return redirect()->back()->with("success", "Data has been saved successfully!");

    }

    public function akunUpdate(request $request){
        $request->validate([
            'nama_akun'   => 'required|string|max:255',
            'jenis_akun'  => 'required',
            'kode_akun'   => 'required',
            'kategori_id' => 'required',
            'id'          => 'required|numeric'
        ]);

        // Gabungkan id dan kode_akun menjadi satu
        $gabunganKode = $request->id . '-' . $request->kode_akun;

        // Cari data akun yang mau diupdate
        $akun = Akun::findOrFail($request->akun_id);

        // Update data
        $akun->update([
            'nama_akun'   => $request->nama_akun,
            'jenis_akun'  => $request->jenis_akun,
            'kode_akun'   => $gabunganKode,
            'kategori_id' => $request->kategori_id,
        ]);

        // Log aktivitas
        activity('ikm')
            ->performedOn($akun)
            ->causedBy(auth()->user())
            ->log('Mengubah Akun ' . $request->nama_akun);

        return redirect()->back()->with('success', 'Data has been updated successfully!');
    }

    public function akunDelete($id){
        $akun = Akun::findOrFail($id);
        $akun->delete();

        activity('ikm')
            ->performedOn($akun)
            ->causedBy(auth()->user())
            ->log('Menghapus Akun ' . $akun->nama_akun);

        return redirect()->back()->with("success", "Data akun berhasil dihapus!");
    }
    public function rekeningIndex(){
        $logs = Activity::where(['causer_id'=>auth()->user()->id, 'log_name' => 'ikm'])->latest()->take(10)->get();
        $rekening = Rekening::where('auth', auth()->user()->id)->latest()->get();
        return view('keuangan.rekening',[
            'activeMenu' => 'rekening',
            'active' => 'rekening',
        ],compact('logs','rekening'));
    }

    public function rekeningAdd(request $request){
        $validated = $request->validate([
            'kode_rekening' => 'required|string|unique:rekenings',
            'nama_rekening' => 'required|string',
            'jenis_akun' => 'required|in:default,uang_tunai,kartu_kredit,rekening_virtual,investasi,piutang,hutang',
            'jumlah' => 'required|numeric',
            'keterangan' => 'nullable|string',
            'auth' => 'nullable|string',
        ]);
        $validated['auth'] = auth()->user()->id;
        Rekening::create($validated);
        HistoryRekening::create([
            'id_rekening' => $validated['kode_rekening'],
            'tanggal' => now()->format('d/m/Y'),
            'keterangan' => 'Saldo Awal',
            'debit' => $validated['jumlah'],
            'kredit' => 0,
            'saldo' => $validated['jumlah'],
        ]);

        Keuangan::create([
            'tanggal' => now()->format('d/m/Y'),
            'deskripsi' => 'Modal Awal',
            'id_akun' => 5,
            'tipe' => 'pemasukan',
            'total' => $validated['jumlah'],
            'id_rekening' => Rekening::where('kode_rekening', $validated['kode_rekening'])->value('id'),
            'auth' => auth()->user()->id,
            'foto' => null,
        ]);

        return redirect()->back()->with("success", "Data akun berhasil ditambahkan!");
    }

    public function keuanganAdd(Request $request){
        $request->validate([
            'tanggal' => 'required',
            'deskripsi' => 'required|string',
            'waktu' => 'required|string',
            'id_akun' => 'required|exists:akuns,id',
            'id_akun_second' => 'required|exists:akuns,id',
            'tipe' => 'required',
            'jenis_transaksi' => 'nullable|string',
            'total' => 'required|numeric',
            'id_rekening' => 'nullable|exists:rekenings,id',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        $data = $request->only(['tanggal','waktu', 'deskripsi', 'id_akun','id_akun_second', 'tipe','jenis_transaksi','total']);
        $data['auth'] = auth()->user()->id;

        // Handle rekening
        if (empty($request->id_rekening)) {
            // Cek apakah sudah ada default rekening di App
            $defaultRekening = App::where(['key' => 'default_rekening','auth'=> auth()->user()->id])->first();
            if ($defaultRekening) {

                // Gunakan rekening default yang sudah ada
                $rekening = Rekening::where('kode_rekening', $defaultRekening->value)->first();
                if ($rekening) {
                    $data['id_rekening'] = $rekening->id;
                    // Update saldo rekening sesuai tipe transaksi
                    if ($request->tipe === 'pengeluaran') {
                        $rekening->jumlah -= $request->total;
                    } elseif ($request->tipe === 'pemasukan') {
                        $rekening->jumlah += $request->total;
                    }
                    $rekening->save();

                    // Catat history rekening
                    HistoryRekening::create([
                        'id_rekening' => $rekening->kode_rekening,
                        'tanggal' => $request->tanggal,
                        'keterangan' => $request->deskripsi,
                        'debit' => $request->tipe === 'pemasukan' ? $request->total : 0,
                        'kredit' => $request->tipe === 'pengeluaran' ? $request->total : 0,
                        'saldo' => $rekening->jumlah,
                    ]);
                } else {
                    $rekeningid = 'RKN' . str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);

                    // Jika kode rekening di App tidak ditemukan di tabel rekening, buat baru
                    $rekeningBaru = Rekening::create([
                        'kode_rekening' => $rekeningid,
                        'nama_rekening' => 'Rekening Otomatis',
                        'jenis_akun' => 'default',
                        'jumlah' => $request->total,
                        'keterangan' => 'Dibuat otomatis saat transaksi',
                        'auth' => auth()->user()->id,
                    ]);
                    $data['id_rekening'] = $rekeningBaru->id;
                    HistoryRekening::create([
                        'id_rekening' => $rekeningBaru->kode_rekening,
                        'tanggal' => $request->tanggal,
                        'keterangan' => $request->deskripsi,
                        'debit' => $request->tipe === 'pemasukan' ? $request->total : 0,
                        'kredit' => $request->tipe === 'pengeluaran' ? $request->total : 0,
                        'saldo' => $rekeningBaru->jumlah,
                    ]);
                    // Tambahkan data default rekening ke tabel App (key-value)
                    App::where(['key' => 'default_rekening','auth'=> auth()->user()->id])->update([
                        'value' => $rekeningid
                    ]);

                    Artisan::call('optimize:clear');
                }
            } else {
                // Buat rekening baru dan simpan sebagai default
                $kodeRekeningBaru = 'RK-' . strtoupper(uniqid());
                $rekeningBaru = Rekening::create([
                    'kode_rekening' => $kodeRekeningBaru,
                    'nama_rekening' => 'Rekening Otomatis',
                    'jenis_akun' => 'default',
                    'jumlah' => $request->total,
                    'keterangan' => 'Dibuat otomatis saat transaksi',
                    'auth' => auth()->user()->id,
                ]);
                $data['id_rekening'] = $rekeningBaru->id;

                // Catat history rekening
                HistoryRekening::create([
                    'id_rekening' => $rekeningBaru->kode_rekening,
                    'tanggal' => $request->tanggal,
                    'keterangan' => $request->deskripsi,
                    'debit' => $request->tipe === 'pemasukan' ? $request->total : 0,
                    'kredit' => $request->tipe === 'pengeluaran' ? $request->total : 0,
                    'saldo' => $rekeningBaru->jumlah,
                ]);
                // Tambahkan data default rekening ke tabel App (key-value)
                App::where(['key' => 'default_rekening','auth'=> auth()->user()->id])->update([
                    'value' => $rekeningBaru->kode_rekening,
                ]);
                  Artisan::call('optimize:clear');
            }
        } else {
            $data['id_rekening'] = $request->id_rekening;
            $rekening = Rekening::find($request->id_rekening);
            if ($rekening) {
                if ($request->tipe === 'pengeluaran') {
                    $rekening->jumlah -= $request->total;
                } elseif ($request->tipe === 'pemasukan') {
                    $rekening->jumlah += $request->total;
                }
                $rekening->save();

                // Tambahkan ke HistoryRekening
                HistoryRekening::create([
                    'id_rekening' => $rekening->kode_rekening,
                    'tanggal' => $request->tanggal,
                    'keterangan' => $request->deskripsi,
                    'debit' => $request->tipe === 'pemasukan' ? $request->total : 0,
                    'kredit' => $request->tipe === 'pengeluaran' ? $request->total : 0,
                    'saldo' => $rekening->jumlah,
                ]);
            }
        }

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('keuangan_foto', 'public');
            $data['foto'] = $fotoPath;
        } else {
            $data['foto'] = null;
        }

        $keuangan = Keuangan::create($data);

        activity('ikm')
            ->performedOn($keuangan)
            ->causedBy(auth()->user())
            ->log('Menambahkan data keuangan baru');
         UserActivity::create(['user_id'=> auth()->id()]);
         return redirect()->back()->with("success", "Berhasil menyimpan data");
    }

    public function keuanganUpdate(Request $request){

        $request->validate([
            'tanggal' => 'required',
            'deskripsi' => 'required|string',
            'waktu' => 'required|string',
            'id_akun' => 'required|exists:akuns,id',
            'id_akun_second' => 'required|exists:akuns,id',
            'tipe' => 'required|in:pemasukan,pengeluaran',
            'total' => 'required|numeric',
            'jenis_transaksi' => 'nullable|string',
            'id_rekening' => 'nullable|exists:rekenings,id',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);


        $keuangan = Keuangan::findOrFail($request->id);
        $oldTotal = $keuangan->total;
        $oldTipe = $keuangan->tipe;
        $oldRekeningId = $keuangan->id_rekening;

        // Kembalikan saldo rekening lama
        if ($oldRekeningId && $oldRekeningId !== '00') {
            $oldRekening = Rekening::find($oldRekeningId);
            if ($oldRekening) {
                if ($oldTipe === 'pengeluaran') {
                    $oldRekening->jumlah += $oldTotal;
                } elseif ($oldTipe === 'pemasukan') {
                    $oldRekening->jumlah -= $oldTotal;
                }
                $oldRekening->save();

                HistoryRekening::create([
                    'id_rekening' => $oldRekening->kode_rekening,
                    'tanggal' => now()->format('d/m/Y'),
                    'keterangan' => 'Update transaksi: saldo dikembalikan',
                    'debit' => $oldTipe === 'pengeluaran' ? $oldTotal : 0,
                    'kredit' => $oldTipe === 'pemasukan' ? $oldTotal : 0,
                    'saldo' => $oldRekening->jumlah,
                ]);
            }
        }

        // Update rekening baru
        $newRekeningId = $request->id_rekening;
        $newTipe = $request->tipe;
        $newTotal = $request->total;

        if ($newRekeningId && $newRekeningId !== '00') {
            $newRekening = Rekening::find($newRekeningId);
            if ($newRekening) {
                if ($newTipe === 'pengeluaran') {
                    $newRekening->jumlah -= $newTotal;
                } elseif ($newTipe === 'pemasukan') {
                    $newRekening->jumlah += $newTotal;
                }
                $newRekening->save();

                HistoryRekening::create([
                    'id_rekening' => $newRekening->kode_rekening,
                    'tanggal' => $request->tanggal,
                    'keterangan' => 'Update transaksi: saldo diperbarui',
                    'debit' => $newTipe === 'pemasukan' ? $newTotal : 0,
                    'kredit' => $newTipe === 'pengeluaran' ? $newTotal : 0,
                    'saldo' => $newRekening->jumlah,
                ]);
            }
        }

        $data = $request->only(['tanggal','waktu', 'deskripsi', 'id_akun','id_akun_second','jenis_transaksi', 'tipe', 'total']);
        $data['auth'] = auth()->user()->id;

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($keuangan->foto) {
                Storage::disk('public')->delete($keuangan->foto);
            }
            $fotoPath = $request->file('foto')->store('keuangan_foto', 'public');
            $data['foto'] = $fotoPath;
        }

        $keuangan->update($data);

        activity('ikm')
            ->performedOn($keuangan)
            ->causedBy(auth()->user())
            ->log('Mengupdate data keuangan');

        return redirect()->back()->with("success", "Berhasil menyimpan data");
    }

    public function keuanganDelete($id){
        $keuangan = Keuangan::findOrFail($id);
        $oldTotal = $keuangan->total;
        $oldTipe = $keuangan->tipe;
        $oldRekeningId = $keuangan->id_rekening;

        // Update saldo rekening jika ada
        if ($oldRekeningId && $oldRekeningId !== '00') {
            $rekening = Rekening::find($oldRekeningId);
            if ($rekening) {
            if ($oldTipe === 'pengeluaran') {
                $rekening->jumlah += $oldTotal;
            } elseif ($oldTipe === 'pemasukan') {
                $rekening->jumlah -= $oldTotal;
            }
            $rekening->save();

            // Tambahkan ke HistoryRekening
            HistoryRekening::create([
                'id_rekening' => $rekening->kode_rekening,
                'tanggal' => now()->format('d/m/Y'),
                'keterangan' => 'Hapus transaksi: saldo dikembalikan',
                'debit' => $oldTipe === 'pengeluaran' ? $oldTotal : 0,
                'kredit' => $oldTipe === 'pemasukan' ? $oldTotal : 0,
                'saldo' => $rekening->jumlah,
            ]);
            }
        }

        $keuangan->delete();
        if ($keuangan->foto) {
            Storage::disk('public')->delete($keuangan->foto);
        }
        activity('ikm')
            ->performedOn($keuangan)
            ->causedBy(auth()->user())
            ->log('Menghapus data keuangan');

        return redirect()->back()->with("success", "Berhasil menghapus data");
    }

    public function rekeningUpdate(request $request){
        $validated = $request->validate([
            'id' => 'required|exists:rekenings,id',
            'kode_rekening' => 'required|string',
            'nama_rekening' => 'required|string',
            'jenis_akun' => 'required|in:default,uang_tunai,kartu_kredit,rekening_virtual,investasi,piutang,hutang',
            'jumlah' => 'required|numeric',
            'keterangan' => 'nullable|string',
        ]);

        $rekening = Rekening::findOrFail($validated['id']);
        $oldJumlah = $rekening->jumlah;

        $rekening->update([
            'kode_rekening' => $validated['kode_rekening'],
            'nama_rekening' => $validated['nama_rekening'],
            'jenis_akun' => $validated['jenis_akun'],
            'jumlah' => $validated['jumlah'],
            'keterangan' => $validated['keterangan'],
            'auth' => auth()->user()->id,
        ]);

        // Catat perubahan saldo ke history jika jumlah berubah
        if ($oldJumlah != $validated['jumlah']) {
            HistoryRekening::create([
            'id_rekening' => $validated['kode_rekening'],
            'tanggal' => now()->format('d/m/Y'),
            'keterangan' => 'Update Saldo Rekening',
            'debit' => $validated['jumlah'] > $oldJumlah ? $validated['jumlah'] - $oldJumlah : 0,
            'kredit' => $validated['jumlah'] < $oldJumlah ? $oldJumlah - $validated['jumlah'] : 0,
            'saldo' => $validated['jumlah'],
            ]);

            Keuangan::create([
            'tanggal' => now()->format('d/m/Y'),
            'deskripsi' => 'Update Saldo Rekening',
            'id_akun' => 5, // sesuaikan id akun jika perlu
            'tipe' => $validated['jumlah'] > $oldJumlah ? 'pemasukan' : 'pengeluaran',
            'total' => abs($validated['jumlah'] - $oldJumlah),
            'id_rekening' => $rekening->id,
            'auth' => auth()->user()->id,
            'foto' => null,
            ]);
        }

        activity('ikm')
            ->performedOn($rekening)
            ->causedBy(auth()->user())
            ->log('Mengupdate data rekening');

        return redirect()->back()->with("success", "Berhasil mengupdate data");
    }

    public function rekeningDelete($id){
        $rekening = Rekening::findOrFail($id);

        // Hapus semua transaksi keuangan terkait rekening ini
        $transaksis = Keuangan::where('id_rekening', $rekening->id)->get();
        foreach ($transaksis as $transaksi) {
            // Hapus foto jika ada
            if ($transaksi->foto) {
                Storage::disk('public')->delete($transaksi->foto);
            }
            $transaksi->delete();
        }

        // Hapus semua history rekening terkait
        HistoryRekening::where('id_rekening', $rekening->kode_rekening)->delete();

        // Hapus rekening
        $rekening->delete();

        activity('ikm')
            ->performedOn($rekening)
            ->causedBy(auth()->user())
            ->log('Menghapus rekening beserta seluruh transaksi terkait');

       return redirect()->back()->with("success", "Rekening dan seluruh transaksi terkait berhasil dihapus!");
    }

    public function rekeningDefault($id){
        $rekening = Rekening::findOrFail($id);

        // Update atau buat entry default rekening di tabel App
        App::updateOrCreate(
            ['key' => 'default_rekening'],
            ['value' => $rekening->kode_rekening]
        );

        activity('ikm')
            ->performedOn($rekening)
            ->causedBy(auth()->user())
            ->log('Mengatur rekening default: ' . $rekening->kode_rekening);

            \Artisan::call('optimize:clear');
        return redirect()->back()->with("success", "Rekening default berhasil diatur!");

    }


    public function keuanganPDF(Request $request)
    {

           // 🔹 Tentukan user (bisa dari ip parameter atau auth)
    $id_user = $request->filled('ip') ? $request->ip : auth()->id();

    // 🔹 Mulai query
    $query = Keuangan::with(['akun', 'rekening'])
        ->where('auth', $id_user);

    // 🔹 Filter tipe (pemasukan/pengeluaran)
    $tipe = $request->filled('tipe') ? $request->tipe : null;
    if ($tipe) {
        $query->where('tipe', $tipe);
    }

    $periodeText = 'Semua Periode';
    $periodeSlug = 'semua-periode';

    // === Filter by from-to ===
    if ($request->filled('from') && $request->filled('to')) {
        try {
            $from = Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d');
            $to   = Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d');

            // Filter tanggal string dd/mm/YYYY
            $query->whereRaw("STR_TO_DATE(tanggal, '%d/%m/%Y') BETWEEN ? AND ?", [$from, $to]);

            $periodeText = "{$request->from} s.d. {$request->to}";
            $periodeSlug = str_replace(['/', '\\'], '-', $request->from) . '_sd_' . str_replace(['/', '\\'], '-', $request->to);
        } catch (\Exception $e) {
            $periodeSlug = 'semua-periode';
        }
    }

    // === Filter by ?periode=YYYY-MM ===
    elseif ($request->filled('periode')) {
        try {
            [$tahun, $bulan] = explode('-', $request->periode);
            $tahun = (int) $tahun;
            $bulan = (int) $bulan;

            $query->whereRaw("
                YEAR(STR_TO_DATE(tanggal, '%d/%m/%Y')) = ?
                AND MONTH(STR_TO_DATE(tanggal, '%d/%m/%Y')) = ?
            ", [$tahun, $bulan]);

            $periodeText = Carbon::create($tahun, $bulan, 1)->translatedFormat('F Y');
            $periodeSlug = "{$bulan}-{$tahun}";
        } catch (\Exception $e) {
            $periodeSlug = 'semua-periode';
        }
    }

    // === Filter by bulan & tahun ===
    elseif ($request->filled('bulan') && $request->filled('tahun')) {
        $bulan = (int) $request->bulan;
        $tahun = (int) $request->tahun;

        $query->whereRaw("YEAR(STR_TO_DATE(tanggal, '%d/%m/%Y')) = ?", [$tahun])
              ->whereRaw("MONTH(STR_TO_DATE(tanggal, '%d/%m/%Y')) = ?", [$bulan]);

        $periodeText = Carbon::createFromDate($tahun, $bulan)->translatedFormat('F Y');
        $periodeSlug = "{$bulan}-{$tahun}";
    }

    // 🔹 Ambil data
    $keuangan = $query->orderByRaw("STR_TO_DATE(tanggal, '%d/%m/%Y') ASC")->get();
   
    // 🔹 Jika tidak ada filter, tampilkan semua periode
    if (
        !$request->filled('from') &&
        !$request->filled('to') &&
        !$request->filled('periode') &&
        !$request->filled('bulan') &&
        !$request->filled('tahun')
    ) {
        $periodeText = 'Semua Periode';
    }

    $data = [
        'keuangan' => $keuangan,
        'periode'  => $periodeText,
    ];

    // 🔹 Generate PDF
    $pdf = Pdf::loadView('keuangan.pdf', $data)->setPaper('a4', 'portrait');

    // 🔹 Nama file aman
    $safeFile = preg_replace('/[\/\\\\]/', '-', $periodeSlug);
    $tipeSlug = $tipe ? $tipe : 'semua-tipe';
    $filename = "laporan-keuangan-{$tipeSlug}-{$safeFile}.pdf";

    return $pdf->download($filename);
    }
 public function kelenderIndex(){
        $data = Keuangan::where('auth',auth()->user()->id)->get();
         $logs = Activity::where(['causer_id'=>auth()->user()->id, 'log_name' => 'ikm'])->latest()->take(10)->get();
        return view('keuangan.bulan',[
            'activeMenu' => 'keuangan',
            'active' => 'keuangan',
        ],compact('logs','data'));
    }

 public function cetakHistoryPDF($id_rekening)
{
    $histories = HistoryRekening::where('id_rekening', $id_rekening)
        ->orderBy('tanggal', 'desc')
        ->get();

    $rekening = Rekening::where('kode_rekening',$id_rekening)->first();

    if (!$rekening) {
        abort(404, 'Rekening tidak ditemukan');
    }

    $pdf = Pdf::loadView('keuangan.pdfHistory', [
        'histories' => $histories,
        'name' => $rekening
    ])->setPaper('a4', 'portrait');

    $safeName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $rekening->nama_rekening); // sanitasi nama file

    $filename = "CetakHistory-keuangan-dari-{$safeName}.pdf";

    return $pdf->download($filename);
}

public function neraca()
    {

    // Ambil bulan & tahun dari request, default ke bulan & tahun sekarang
   $bulan = (int) request('bulan', Carbon::now()->month);
    $tahun = (int) request('tahun', Carbon::now()->year);

    $saldoAkuns = Akun::select(
        'akuns.id',
        'akuns.kode_akun',
        'akuns.nama_akun',
        'kategori_akuns.nama_kategori',
        'kategori_akuns.tipe',
        DB::raw('
            SUM(CASE WHEN keuangans.id_akun = akuns.id THEN keuangans.total ELSE 0 END) AS total_debit
        '),
        DB::raw('
            SUM(CASE WHEN keuangans.id_akun_second = akuns.id THEN keuangans.total ELSE 0 END) AS total_kredit
        ')
    )
    ->join('kategori_akuns','kategori_akuns.id','=','akuns.kategori_id')
    ->leftJoin('keuangans', function($join) use ($bulan, $tahun) {
        $join->on(function($q) {
                $q->on('keuangans.id_akun','=','akuns.id')
                  ->orOn('keuangans.id_akun_second','=','akuns.id');
            })
            ->whereRaw('MONTH(STR_TO_DATE(keuangans.tanggal, "%d/%m/%Y")) = ?', [$bulan])
            ->whereRaw('YEAR(STR_TO_DATE(keuangans.tanggal, "%d/%m/%Y")) = ?', [$tahun])
            ->where('keuangans.auth', auth()->id()); // ✅ pindahkan ke sini
    })
    ->groupBy('akuns.id','akuns.kode_akun','akuns.nama_akun','kategori_akuns.nama_kategori','kategori_akuns.tipe')
    ->get()
    ->map(function($row){
        if (in_array($row->tipe, ['aset','beban'])) {
            $row->saldo = $row->total_debit - $row->total_kredit;
        } else {
            $row->saldo = $row->total_kredit - $row->total_debit;
        }
        return $row;
    });

    // Pisahkan Neraca dan Laba Rugi
    $neraca = $saldoAkuns->whereIn('tipe', ['aset','liabilitas','ekuitas'])
                        ->groupBy('tipe');

    $labaRugi = $saldoAkuns->whereIn('tipe', ['pendapatan','beban'])
                        ->groupBy('tipe');

    // Ambil log aktivitas terbaru
    $logs = Activity::where([
                'causer_id'=>auth()->id(),
                'log_name' => 'ikm'
            ])
            ->latest()
            ->take(10)
            ->get();

    return view('keuangan.neraca', [
        'activeMenu' => 'laporan',
        'active' => 'neraca',
        'neraca' => $neraca,
        'labaRugi' => $labaRugi,
        'logs' => $logs,
        'bulan' => $bulan,
        'tahun' => $tahun,
    ]);
    }
    public function neracapdf(){
       // Ambil bulan & tahun dari request, default ke sekarang
        $bulan = request('bulan', Carbon::now()->month);
        $tahun = request('tahun', Carbon::now()->year);

            $saldoAkuns = Akun::select(
            'akuns.id',
            'akuns.kode_akun',
            'akuns.nama_akun',
            'kategori_akuns.nama_kategori',
            'kategori_akuns.tipe',
            DB::raw('
                SUM(CASE WHEN keuangans.id_akun = akuns.id THEN keuangans.total ELSE 0 END) AS total_debit
            '),
            DB::raw('
                SUM(CASE WHEN keuangans.id_akun_second = akuns.id THEN keuangans.total ELSE 0 END) AS total_kredit
            ')
        )
        ->join('kategori_akuns','kategori_akuns.id','=','akuns.kategori_id')
        ->leftJoin('keuangans', function($join) use ($bulan, $tahun) {
            $join->on(function($q) {
                    $q->on('keuangans.id_akun','=','akuns.id')
                    ->orOn('keuangans.id_akun_second','=','akuns.id');
                })
                ->whereRaw('MONTH(STR_TO_DATE(keuangans.tanggal, "%d/%m/%Y")) = ?', [$bulan])
                ->whereRaw('YEAR(STR_TO_DATE(keuangans.tanggal, "%d/%m/%Y")) = ?', [$tahun])
                ->where('keuangans.auth', auth()->id()); // ✅ pindahkan ke sini
        })
        ->groupBy('akuns.id','akuns.kode_akun','akuns.nama_akun','kategori_akuns.nama_kategori','kategori_akuns.tipe')
        ->get()
        ->map(function($row){
            if (in_array($row->tipe, ['aset','beban'])) {
                $row->saldo = $row->total_debit - $row->total_kredit;
            } else {
                $row->saldo = $row->total_kredit - $row->total_debit;
            }
            return $row;
        });

        $neraca = $saldoAkuns->whereIn('tipe', ['aset','liabilitas','ekuitas'])->groupBy('tipe');
        $labaRugi = $saldoAkuns->whereIn('tipe', ['pendapatan','beban'])->groupBy('tipe');


            // Ambil log aktivitas terbaru (opsional untuk catatan bawah PDF)
            $logs = Activity::where([
                        'causer_id' => auth()->id(),
                        'log_name'  => 'ikm'
                    ])
                    ->latest()
                    ->take(10)
                    ->get();

            // Buat PDF
            $pdf = Pdf::loadView('keuangan.pdf.neracaNew', [
                'neraca' => $neraca,
                'labaRugi' => $labaRugi,
                'logs' => $logs,
                'bulan' => $bulan,
                'tahun' => $tahun,
            ])->setPaper('a4', 'portrait');

            // Output PDF langsung di browser
            return $pdf->stream("Laporan_Neraca_{$bulan}_{$tahun}.pdf");
    }
    public function neracaSaldo(request $request)
    {
        $logs = Activity::where([
                'causer_id'=>auth()->user()->id,
                'log_name' => 'ikm'
            ])
            ->latest()
            ->take(10)
            ->get();
       $bulan = $request->input('bulan', now()->format('m'));
    $tahun = $request->input('tahun', now()->format('Y'));
    $auth = auth()->user()->id;
    $data = DB::table('keuangans')
        ->select(
            'akuns.id',
            'akuns.kode_akun',
            'akuns.nama_akun',
            DB::raw("SUM(CASE WHEN keuangans.id_akun = akuns.id THEN keuangans.total ELSE 0 END) AS saldo_debit"),
            DB::raw("SUM(CASE WHEN keuangans.id_akun_second = akuns.id THEN keuangans.total ELSE 0 END) AS saldo_kredit")
        )
        ->join('akuns', function ($join) {
            $join->on('keuangans.id_akun', '=', 'akuns.id')
                ->orOn('keuangans.id_akun_second', '=', 'akuns.id');
        })
        ->whereRaw("STR_TO_DATE(keuangans.tanggal, '%d/%m/%Y') IS NOT NULL")
        ->whereRaw("MONTH(STR_TO_DATE(keuangans.tanggal, '%d/%m/%Y')) = ?", [$bulan])
        ->whereRaw("YEAR(STR_TO_DATE(keuangans.tanggal, '%d/%m/%Y')) = ?", [$tahun])
        ->where("keuangans.auth", $auth)
        ->groupBy('akuns.id', 'akuns.kode_akun', 'akuns.nama_akun')
        ->get();

    return view('keuangan.neracasaldo',[
        'activeMenu' => 'laporan',
        'active' => 'neracasaldo',
        'logs' => $logs
    ],compact('data','bulan','tahun'));

    }

    public function labarugi(Request $request)
    {
        // Ambil logs aktivitas terakhir
        $logs = Activity::where([
            'causer_id' => auth()->user()->id,
            'log_name' => 'ikm'
        ])->latest()->take(10)->get();

        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
      $auth = auth()->id(); // lebih ringkas daripada auth()->user()->id

    $items = Keuangan::with([
            'akun.kategori',
            'akunSecond.kategori'
        ])
        ->whereRaw("STR_TO_DATE(tanggal, '%d/%m/%Y') IS NOT NULL")
        ->whereRaw("MONTH(STR_TO_DATE(tanggal, '%d/%m/%Y')) = ?", [$bulan])
        ->whereRaw("YEAR(STR_TO_DATE(tanggal, '%d/%m/%Y')) = ?", [$tahun])
        ->where('auth', $auth)
        ->orderByRaw("STR_TO_DATE(tanggal, '%d/%m/%Y') ASC")
        ->get();

        // Inisialisasi array Laba Rugi
        $labaRugi = [
            'pendapatan' => collect(),
            'hpp' => collect(),
            'beban_operasional' => collect(),
            'pendapatan_lainnya' => collect(),
            'beban_lainnya' => collect(),
        ];

        $addedAkun = []; // array untuk menjumlahkan total per akun

        // Loop semua transaksi untuk kumpulkan total per akun
        foreach ($items as $item) {
            $akunList = [$item->akun, $item->akunSecond];
            foreach ($akunList as $akun) {
                if (!$akun || !$akun->kategori) continue;

                $key = $akun->id;
                if (!isset($addedAkun[$key])) {
                    $addedAkun[$key] = 0;
                }
                $addedAkun[$key] += $item->total;
            }
        }

        // Mapping nama kategori ke tipe Laba Rugi
        $kategoriMap = [
            'Pendapatan' => 'pendapatan',
            'Pendapatan Lainnya' => 'pendapatan_lainnya',
            'Harga Pokok Penjualan' => 'hpp',
            'Beban' => 'beban_operasional',
            'Beban Lainnya' => 'beban_lainnya',
            'Depresiasi & Amortisasi' => 'beban_lainnya',
        ];

        // Fungsi helper push ke Laba Rugi
        $pushToLabaRugi = function($akun, $total) use (&$labaRugi, $kategoriMap) {
            $namaKategori = $akun->kategori->nama_kategori;
            $tipeKategori = $kategoriMap[$namaKategori] ?? null;

            if (!$tipeKategori) return; // abaikan jika bukan Laba Rugi

            $saldoItem = (object)[
                'nama_akun' => $akun->nama_akun,
                'saldo' => $total,
            ];

            $labaRugi[$tipeKategori]->push($saldoItem);
        };

        // Loop akun yang sudah dijumlahkan totalnya
        foreach ($addedAkun as $akunId => $total) {
            $akun = Akun::with('kategori')->find($akunId);
            if ($akun) {
                $pushToLabaRugi($akun, $total);
            }
        }

        // Hitung total Laba Rugi
        $totalPendapatan = $labaRugi['pendapatan']->sum('saldo');
        $totalHpp = $labaRugi['hpp']->sum('saldo');
        $totalBebanOperasional = $labaRugi['beban_operasional']->sum('saldo');
        $totalPendapatanLainnya = $labaRugi['pendapatan_lainnya']->sum('saldo');
        $totalBebanLainnya = $labaRugi['beban_lainnya']->sum('saldo');

        $labaRugi['laba_kotor'] = $totalPendapatan - $totalHpp;
        $labaRugi['laba_operasional'] = $labaRugi['laba_kotor'] - $totalBebanOperasional;
        $labaRugi['laba_bersih'] = $labaRugi['laba_operasional'] + $totalPendapatanLainnya - $totalBebanLainnya;

        return view('keuangan.labarugi', [
            'activeMenu' => 'laporan',
            'active' => 'labarugi',
            'logs' => $logs,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'labaRugi' => $labaRugi
        ]);
    }
    
    public function laptransaksi(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        $auth = auth()->user()->id;
        // Ambil transaksi bulan & tahun tersebut
        $transaksi = Keuangan::with(['akun', 'akunSecond', 'rekening'])
            ->whereRaw("MONTH(STR_TO_DATE(tanggal, '%d/%m/%Y')) = ?", [$bulan])
            ->whereRaw("YEAR(STR_TO_DATE(tanggal, '%d/%m/%Y')) = ?", [$tahun])
            ->where("auth", $auth)
            ->orderByRaw("STR_TO_DATE(tanggal, '%d/%m/%Y') ASC")
            ->get();

        return view('keuangan.transaksi',[
            'activeMenu' => 'laporan',
            'active' => 'laporan_transaksi',
            'logs' => Activity::where(['causer_id'=>auth()->user()->id, 'log_name' => 'ikm'])->latest()->take(10)->get()
        ],compact('transaksi','bulan','tahun'));
    }

}
