<?php

namespace App\Http\Controllers\Keuangan;

use Storage;
use App\Models\App;
use App\Models\Akun;
use App\Models\Keuangan;
use App\Models\Rekening;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\HistoryRekening;
use Barryvdh\DomPDF\Facade\Pdf;
use Flasher\Laravel\Facade\Flasher;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\DB;
class KeuanganHarianController extends Controller
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
        return view('keuangan.catatan_keuangan.index',[
            'activeMenu' => 'keuangan_harian',
            'active' => 'keuangan_harian',
        ],compact('logs','rekening','akun','transaksi','tahun','bulan','akunJs'));
    }
    public function IndexAkun(){
        $akun = Akun::all();
        $logs = Activity::where(['causer_id'=>auth()->user()->id, 'log_name' => 'ikm'])->latest()->take(10)->get();
        return view('keuangan.catatan_keuangan.akun',[
            'activeMenu' => 'akun_harian',
            'active' => 'akun_harian',
        ],compact('logs','akun'));
    }

    public function akunCreate(request $request){
        $request->validate([
            'nama_akun' => 'required|string|max:255',
            'jenis_akun' => 'required|in:pemasukan,pengeluaran',
        ]);

        $akun = Akun::create($request->all());
        activity('ikm')->performedOn($akun)->causedBy(auth()->user())->log('Menambahkan Akun Baru ' . $request->akun);
        return redirect()->back()->with("success", "Data has been saved successfully!");
    }

    public function akunUpdate(request $request){
        $request->validate([
            'nama_akun' => 'required|string|max:255',
            'jenis_akun' => 'required|in:pemasukan,pengeluaran',
        ]);

        $akun = Akun::findOrFail($request->id);
        $akun->update([
            'nama_akun' => $request->nama_akun,
            'jenis_akun' => $request->jenis_akun,
        ]);

        activity('ikm')
            ->performedOn($akun)
            ->causedBy(auth()->user())
            ->log('Mengupdate Akun ' . $request->nama_akun);

        return redirect()->back()->with("success", "Data has been saved successfully!");
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
        return view('keuangan.catatan_keuangan.rekening',[
            'activeMenu' => 'rekening_harian',
            'active' => 'rekening_harian',
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
            'id_akun' => 'required|exists:akuns,id',
            'tipe' => 'required|in:pemasukan,pengeluaran',
            'total' => 'required|numeric',
            'id_rekening' => 'nullable|exists:rekenings,id',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        $data = $request->only(['tanggal', 'deskripsi', 'id_akun', 'tipe', 'total']);
        $data['auth'] = auth()->user()->id;

        // Handle rekening
        if (empty($request->id_rekening)) {
            // Cek apakah sudah ada default rekening harian di App
            $defaultRekening = App::where(['key' => 'default_rekening_harian','auth'=> auth()->user()->id])->first();
            if (!$defaultRekening) {
                // Jika tidak ada, buat entry baru
                App::create([
                    'key' => 'default_rekening_harian',
                    'value' => '',
                    'auth' => auth()->user()->id
                ]);
                $defaultRekening = null; // Set ke null agar masuk ke else
            }
            if ($defaultRekening && !empty($defaultRekening->value)) {

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
                        'nama_rekening' => 'Rekening Harian',
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
                    App::where(['key' => 'default_rekening_harian','auth'=> auth()->user()->id])->update([
                        'value' => $rekeningid
                    ]);

                    Artisan::call('optimize:clear');
                }
            } else {
                // Cek apakah sudah ada rekening default untuk user ini
                $existingDefault = Rekening::where('auth', auth()->user()->id)->where('jenis_akun', 'default')->first();
                if ($existingDefault) {
                    // Gunakan rekening default yang sudah ada
                    $data['id_rekening'] = $existingDefault->id;
                    // Update saldo rekening sesuai tipe transaksi
                    if ($request->tipe === 'pengeluaran') {
                        $existingDefault->jumlah -= $request->total;
                    } elseif ($request->tipe === 'pemasukan') {
                        $existingDefault->jumlah += $request->total;
                    }
                    $existingDefault->save();

                    // Catat history rekening
                    HistoryRekening::create([
                        'id_rekening' => $existingDefault->kode_rekening,
                        'tanggal' => $request->tanggal,
                        'keterangan' => $request->deskripsi,
                        'debit' => $request->tipe === 'pemasukan' ? $request->total : 0,
                        'kredit' => $request->tipe === 'pengeluaran' ? $request->total : 0,
                        'saldo' => $existingDefault->jumlah,
                    ]);
                    // Update data default rekening ke tabel App (key-value)
                    App::where(['key' => 'default_rekening_harian','auth'=> auth()->user()->id])->update([
                        'value' => $existingDefault->kode_rekening,
                    ]);
                    Artisan::call('optimize:clear');
                } else {
                    // Buat rekening baru dan simpan sebagai default
                    $kodeRekeningBaru = 'RK-' . strtoupper(uniqid());
                    $rekeningBaru = Rekening::create([
                        'kode_rekening' => $kodeRekeningBaru,
                        'nama_rekening' => 'Rekening Harian',
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
                    App::where(['key' => 'default_rekening_harian','auth'=> auth()->user()->id])->update([
                        'value' => $rekeningBaru->kode_rekening,
                    ]);
                    Artisan::call('optimize:clear');
                }
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
            'id' => 'required|exists:keuangans,id',
            'tanggal' => 'required',
            'deskripsi' => 'required|string',
            'id_akun' => 'required|exists:akuns,id',
            'tipe' => 'required|in:pemasukan,pengeluaran',
            'total' => 'required|numeric',
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

        $data = $request->only(['tanggal', 'deskripsi', 'id_akun', 'tipe', 'total', 'id_rekening']);
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
    // Base query
    $keuangan = Keuangan::where('auth', auth()->user()->id);

   if ($request->filled('from') && $request->filled('to')) {
    try {
        $from = Carbon::createFromFormat('d/m/Y', $request->from)->format('Y-m-d');
        $to   = Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d');

        $keuangan->whereRaw("
            STR_TO_DATE(tanggal, '%d/%m/%Y') BETWEEN ? AND ?
        ", [$from, $to]);


        $periode = "{$request->from} s.d. {$request->to}";
    } catch (\Exception $e) {
        $periode = 'Semua Data';
    }
} else {
    $periode = 'Semua Data';
}


    // Ambil data keuangan
    $data = [
        'keuangan' => $keuangan->with('akun')->orderBy('tanggal', 'asc')->get(),
        'periode' => $periode
    ];

    // Buat dan kirim PDF
    $pdf = Pdf::loadView('keuangan.catatan_keuangan.pdf', $data)->setPaper('a4', 'portrait');

    $periode = $request->filled('from') && $request->filled('to')
    ? str_replace('/', '-', $request->from) . '_sd_' . str_replace('/', '-', $request->to)
    : 'semua-periode';

    $filename = "laporan-keuangan-{$periode}.pdf";

    return $pdf->download($filename);
}
 public function kelenderIndex(){
        $data = Keuangan::where('auth',auth()->user()->id)->get();
         $logs = Activity::where(['causer_id'=>auth()->user()->id, 'log_name' => 'ikm'])->latest()->take(10)->get();
        return view('keuangan.catatan_keuangan.bulan',[
            'activeMenu' => 'keuangan_harian',
            'active' => 'keuangan_harian',
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

    $pdf = Pdf::loadView('keuangan.catatan_keuangan.pdfHistory', [
        'histories' => $histories,
        'name' => $rekening
    ])->setPaper('a4', 'portrait');

    $safeName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $rekening->nama_rekening); // sanitasi nama file

    $filename = "CetakHistory-keuangan-dari-{$safeName}.pdf";

    return $pdf->download($filename);
}


}
