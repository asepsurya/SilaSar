<?php

namespace App\Http\Controllers\Keuangan;

use Storage;
use App\Models\App;
use App\Models\AkunTable;
use App\Models\KeuanganTable;
use App\Models\RekeningTable;
use App\Models\KategoriAkun;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\HistoryRekeningTable;
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
        $rekening = RekeningTable::where('auth', auth()->user()->id)->latest()->get();

        // Ambil parameter request
        $sort  = request('sort', 'desc');
        $from  = request('from');
        $to    = request('to');
        $bulan = request('bulan', date('m'));
        $tahun = request('tahun', date('Y'));

        $query = KeuanganTable::with(['akun', 'rekening'])
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
        $akun = AkunTable::with('kategori')->get();

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

        $akun = AkunTable::all();
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
        $akun = AkunTable::create($request->all());

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
        $akun = AkunTable::findOrFail($request->akun_id);

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
       
        $akun = AkunTable::findOrFail($id);
        $akun->delete();
        
        activity('ikm')
            ->performedOn($akun)
            ->causedBy(auth()->user())
            ->log('Menghapus Akun ' . $akun->nama_akun);

        return redirect()->back()->with("success", "Data akun berhasil dihapus!");
    }
    public function rekeningIndex(){
        $logs = Activity::where(['causer_id'=>auth()->user()->id, 'log_name' => 'ikm'])->latest()->take(10)->get();
        $rekening = RekeningTable::where('auth', auth()->user()->id)->latest()->get();
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
        RekeningTable::create($validated);
        HistoryRekeningTable::create([
            'id_rekening' => $validated['kode_rekening'],
            'tanggal' => now()->format('d/m/Y'),
            'keterangan' => 'Saldo Awal',
            'debit' => $validated['jumlah'],
            'kredit' => 0,
            'saldo' => $validated['jumlah'],
        ]);

        KeuanganTable::create([
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
            'id_akun' => 'required|exists:akun_tables,id',
            'id_akun_second' => 'required|exists:akun_tables,id',
            'tipe' => 'required',
            'jenis_transaksi' => 'nullable|string',
            'total' => 'required|numeric',
            'id_rekening' => 'nullable|exists:rekening_tables,id',
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
                $rekening = RekeningTable::where('kode_rekening', $defaultRekening->value)->first();
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
                    HistoryRekeningTable::create([
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
                    $rekeningBaru = RekeningTable::create([
                        'kode_rekening' => $rekeningid,
                        'nama_rekening' => 'Rekening Otomatis',
                        'jenis_akun' => 'default',
                        'jumlah' => $request->total,
                        'keterangan' => 'Dibuat otomatis saat transaksi',
                        'auth' => auth()->user()->id,
                    ]);
                    $data['id_rekening'] = $rekeningBaru->id;
                    HistoryRekeningTable::create([
                        'id_rekening' => $rekeningBaru->kode_rekening,
                        'tanggal' => $request->tanggal,
                        'keterangan' => $request->deskripsi,
                        'debit' => $request->tipe === 'pemasukan' ? $request->total : 0,
                        'kredit' => $request->tipe === 'pengeluaran' ? $request->total : 0,
                        'saldo' => $rekeningBaru->jumlah,
                    ]);
                    // Tambahkan data default rekening ke tabel App (key-value)
                    App::updateOrCreate(
                        ['key' => 'default_rekening', 'auth' => auth()->user()->id],
                        ['value' => $rekeningid]
                    );

                    Artisan::call('optimize:clear');
                }
            } else {
                // Buat rekening baru dan simpan sebagai default
                $kodeRekeningBaru = 'RK-' . strtoupper(uniqid());
                $rekeningBaru = RekeningTable::create([
                    'kode_rekening' => $kodeRekeningBaru,
                    'nama_rekening' => 'Rekening Otomatis',
                    'jenis_akun' => 'default',
                    'jumlah' => $request->total,
                    'keterangan' => 'Dibuat otomatis saat transaksi',
                    'auth' => auth()->user()->id,
                ]);
                $data['id_rekening'] = $rekeningBaru->id;

                // Catat history rekening
                HistoryRekeningTable::create([
                    'id_rekening' => $rekeningBaru->kode_rekening,
                    'tanggal' => $request->tanggal,
                    'keterangan' => $request->deskripsi,
                    'debit' => $request->tipe === 'pemasukan' ? $request->total : 0,
                    'kredit' => $request->tipe === 'pengeluaran' ? $request->total : 0,
                    'saldo' => $rekeningBaru->jumlah,
                ]);
                // Tambahkan data default rekening ke tabel App (key-value)
                App::updateOrCreate(
                    ['key' => 'default_rekening', 'auth' => auth()->user()->id],
                    ['value' => $rekeningBaru->kode_rekening]
                );
                  Artisan::call('optimize:clear');
            }
        } else {
            $data['id_rekening'] = $request->id_rekening;
            $rekening = RekeningTable::find($request->id_rekening);
            if ($rekening) {
                if ($request->tipe === 'pengeluaran') {
                    $rekening->jumlah -= $request->total;
                } elseif ($request->tipe === 'pemasukan') {
                    $rekening->jumlah += $request->total;
                }
                $rekening->save();

                // Tambahkan ke HistoryRekening
                HistoryRekeningTable::create([
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

        $keuangan = KeuanganTable::create($data);

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
            'id_akun' => 'required|exists:akun_tables,id',
            'id_akun_second' => 'required|exists:akun_tables,id',
            'tipe' => 'required|in:pemasukan,pengeluaran',
            'total' => 'required|numeric',
            'jenis_transaksi' => 'nullable|string',
            'id_rekening' => 'nullable|exists:rekening_tables,id',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);


        $keuangan = KeuanganTable::findOrFail($request->id);
        $oldTotal = $keuangan->total;
        $oldTipe = $keuangan->tipe;
        $oldRekeningId = $keuangan->id_rekening;

        // Kembalikan saldo rekening lama
        if ($oldRekeningId && $oldRekeningId !== '00') {
            $oldRekening = RekeningTable::find($oldRekeningId);
            if ($oldRekening) {
                if ($oldTipe === 'pengeluaran') {
                    $oldRekening->jumlah += $oldTotal;
                } elseif ($oldTipe === 'pemasukan') {
                    $oldRekening->jumlah -= $oldTotal;
                }
                $oldRekening->save();

                HistoryRekeningTable::create([
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
            $newRekening = RekeningTable::find($newRekeningId);
            if ($newRekening) {
                if ($newTipe === 'pengeluaran') {
                    $newRekening->jumlah -= $newTotal;
                } elseif ($newTipe === 'pemasukan') {
                    $newRekening->jumlah += $newTotal;
                }
                $newRekening->save();

                HistoryRekeningTable::create([
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
        $keuangan = KeuanganTable::findOrFail($id);
        $oldTotal = $keuangan->total;
        $oldTipe = $keuangan->tipe;
        $oldRekeningId = $keuangan->id_rekening;

        // Update saldo rekening jika ada
        if ($oldRekeningId && $oldRekeningId !== '00') {
            $rekening = RekeningTable::find($oldRekeningId);
            if ($rekening) {
            if ($oldTipe === 'pengeluaran') {
                $rekening->jumlah += $oldTotal;
            } elseif ($oldTipe === 'pemasukan') {
                $rekening->jumlah -= $oldTotal;
            }
            $rekening->save();

            // Tambahkan ke HistoryRekening
            HistoryRekeningTable::create([
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

        $rekening = RekeningTable::findOrFail($validated['id']);
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
            HistoryRekeningTable::create([
            'id_rekening' => $validated['kode_rekening'],
            'tanggal' => now()->format('d/m/Y'),
            'keterangan' => 'Update Saldo Rekening',
            'debit' => $validated['jumlah'] > $oldJumlah ? $validated['jumlah'] - $oldJumlah : 0,
            'kredit' => $validated['jumlah'] < $oldJumlah ? $oldJumlah - $validated['jumlah'] : 0,
            'saldo' => $validated['jumlah'],
            ]);

            KeuanganTable::create([
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
        $rekening = RekeningTable::findOrFail($id);

        // Hapus semua transaksi keuangan terkait rekening ini
        $transaksis = KeuanganTable::where('id_rekening', $rekening->id)->get();
        foreach ($transaksis as $transaksi) {
            // Hapus foto jika ada
            if ($transaksi->foto) {
                Storage::disk('public')->delete($transaksi->foto);
            }
            $transaksi->delete();
        }

        // Hapus semua history rekening terkait
        HistoryRekeningTable::where('id_rekening', $rekening->kode_rekening)->delete();

        // Hapus rekening
        $rekening->delete();

        activity('ikm')
            ->performedOn($rekening)
            ->causedBy(auth()->user())
            ->log('Menghapus rekening beserta seluruh transaksi terkait');

       return redirect()->back()->with("success", "Rekening dan seluruh transaksi terkait berhasil dihapus!");
    }

    public function rekeningDefault($id){
        $rekening = RekeningTable::findOrFail($id);

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
    $query = KeuanganTable::with(['akun', 'rekening'])
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
        $data = KeuanganTable::where('auth',auth()->user()->id)->get();
         $logs = Activity::where(['causer_id'=>auth()->user()->id, 'log_name' => 'ikm'])->latest()->take(10)->get();
        return view('keuangan.bulan',[
            'activeMenu' => 'keuangan',
            'active' => 'keuangan',
        ],compact('logs','data'));
    }

 public function cetakHistoryPDF($id_rekening)
{
    $histories = HistoryRekeningTable::where('id_rekening', $id_rekening)
        ->orderBy('tanggal', 'desc')
        ->get();

    $rekening = RekeningTable::where('kode_rekening',$id_rekening)->first();

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
    // Ambil parameter filter
    $periode = request('periode');
    $bulan = (int) request('bulan', Carbon::now()->month);
    $tahun = (int) request('tahun', Carbon::now()->year);
    $tahun_tahun = (int) request('tahun_tahun', Carbon::now()->year);

    // Jika periode tahunan, gunakan tahun_tahun dan set bulan ke null untuk filter tahunan
    if ($periode === 'tahunan') {
        $tahun = $tahun_tahun;
        $bulan = null; // Tidak filter bulan untuk tahunan
    }

    $saldoakun_tables = AkunTable::select(
        'akun_tables.id',
        'akun_tables.kode_akun',
        'akun_tables.nama_akun',
        'kategori_akuns.nama_kategori',
        'kategori_akuns.tipe',
        DB::raw('
            SUM(CASE WHEN keuangan_tables.id_akun = akun_tables.id THEN keuangan_tables.total ELSE 0 END) AS total_debit
        '),
        DB::raw('
            SUM(CASE WHEN keuangan_tables.id_akun_second = akun_tables.id THEN keuangan_tables.total ELSE 0 END) AS total_kredit
        ')
    )
    ->join('kategori_akuns','kategori_akuns.id','=','akun_tables.kategori_id')
    ->leftJoin('keuangan_tables', function($join) use ($bulan, $tahun) {
        $join->on(function($q) {
                $q->on('keuangan_tables.id_akun','=','akun_tables.id')
                  ->orOn('keuangan_tables.id_akun_second','=','akun_tables.id');
            })
            ->whereRaw('YEAR(STR_TO_DATE(keuangan_tables.tanggal, "%d/%m/%Y")) = ?', [$tahun])
            ->when($bulan, function($query) use ($bulan) {
                return $query->whereRaw('MONTH(STR_TO_DATE(keuangan_tables.tanggal, "%d/%m/%Y")) = ?', [$bulan]);
            })
            ->where('keuangan_tables.auth', auth()->id());
    })
    ->groupBy('akun_tables.id','akun_tables.kode_akun','akun_tables.nama_akun','kategori_akuns.nama_kategori','kategori_akuns.tipe')
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
    $neraca = $saldoakun_tables->whereIn('tipe', ['aset','liabilitas','ekuitas'])
                        ->groupBy('tipe');

    $labaRugi = $saldoakun_tables->whereIn('tipe', ['pendapatan','beban'])
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
        'periode' => $periode,
    ]);
    }
    public function neracapdf(){
        // Ambil parameter filter
        $periode = request('periode');
        $bulan = request('bulan', Carbon::now()->month);
        $tahun = request('tahun', Carbon::now()->year);
        $tahun_bulan = request('tahun_bulan');
        $tahun_tahun = request('tahun_tahun');
        $tanggal_awal = request('tanggal_awal');
        $tanggal_akhir = request('tanggal_akhir');

        // Jika ada filter periode bulanan, gunakan bulan dan tahun_bulan
        if ($periode === 'bulanan' && $tahun_bulan) {
            $tahun = (int) $tahun_bulan;
        }

        // Jika ada filter periode tahunan, gunakan tahun_tahun dan set bulan ke null
        if ($periode === 'tahunan' && $tahun_tahun) {
            $tahun = (int) $tahun_tahun;
            $bulan = null;
        }

        $saldoakun_tables = AkunTable::select(
            'akun_tables.id',
            'akun_tables.kode_akun',
            'akun_tables.nama_akun',
            'kategori_akuns.nama_kategori',
            'kategori_akuns.tipe',
            DB::raw('
                SUM(CASE WHEN keuangan_tables.id_akun = akun_tables.id THEN keuangan_tables.total ELSE 0 END) AS total_debit
            '),
            DB::raw('
                SUM(CASE WHEN keuangan_tables.id_akun_second = akun_tables.id THEN keuangan_tables.total ELSE 0 END) AS total_kredit
            ')
        )
        ->join('kategori_akuns','kategori_akuns.id','=','akun_tables.kategori_id')
        ->leftJoin('keuangan_tables', function($join) use ($bulan, $tahun, $tanggal_awal, $tanggal_akhir) {
            $join->on(function($q) {
                    $q->on('keuangan_tables.id_akun','=','akun_tables.id')
                    ->orOn('keuangan_tables.id_akun_second','=','akun_tables.id');
                })
                ->where('keuangan_tables.auth', auth()->id());

            // Filter berdasarkan tanggal_awal dan tanggal_akhir jika ada
            if ($tanggal_awal && $tanggal_akhir) {
                try {
                    $fromDate = Carbon::createFromFormat('d/m/Y', $tanggal_awal)->format('Y-m-d');
                    $toDate = Carbon::createFromFormat('d/m/Y', $tanggal_akhir)->format('Y-m-d');
                    $join->whereRaw("STR_TO_DATE(keuangan_tables.tanggal, '%d/%m/%Y') BETWEEN ? AND ?", [$fromDate, $toDate]);
                } catch (\Exception $e) {
                    // Abaikan jika format salah
                }
            } else {
                // Filter berdasarkan tahun
                $join->whereRaw('YEAR(STR_TO_DATE(keuangan_tables.tanggal, "%d/%m/%Y")) = ?', [$tahun]);

                // Filter berdasarkan bulan jika ada
                if ($bulan) {
                    $join->whereRaw('MONTH(STR_TO_DATE(keuangan_tables.tanggal, "%d/%m/%Y")) = ?', [$bulan]);
                }
            }
        })
        ->groupBy('akun_tables.id','akun_tables.kode_akun','akun_tables.nama_akun','kategori_akuns.nama_kategori','kategori_akuns.tipe')
        ->get()
        ->map(function($row){
            if (in_array($row->tipe, ['aset','beban'])) {
                $row->saldo = $row->total_debit - $row->total_kredit;
            } else {
                $row->saldo = $row->total_kredit - $row->total_debit;
            }
            return $row;
        });

        $neraca = $saldoakun_tables->whereIn('tipe', ['aset','liabilitas','ekuitas'])->groupBy('tipe');
        $labaRugi = $saldoakun_tables->whereIn('tipe', ['pendapatan','beban'])->groupBy('tipe');


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
    public function neracaSaldo2(request $request)
    {
        $logs = Activity::where([
                'causer_id'=>auth()->user()->id,
                'log_name' => 'ikm'
            ])
            ->latest()
            ->take(10)
            ->get();

        // Ambil parameter filter
        $periode = $request->input('periode');
        $bulan = $request->input('bulan', now()->format('m'));
        $tahun = $request->input('tahun', now()->format('Y'));
        $tahun_bulan = $request->input('tahun_bulan');
        $tahun_tahun = $request->input('tahun_tahun');
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');

        // Jika ada filter periode bulanan, gunakan bulan dan tahun_bulan
        if ($periode === 'bulanan' && $tahun_bulan) {
            $tahun = (int) $tahun_bulan;
        }

        // Jika ada filter periode tahunan, gunakan tahun_tahun dan set bulan ke null
        if ($periode === 'tahunan' && $tahun_tahun) {
            $tahun = (int) $tahun_tahun;
            $bulan = null;
        }

        $auth = auth()->user()->id;

        $query = DB::table('keuangan_tables')
            ->select(
                'akun_tables.id',
                'akun_tables.kode_akun',
                'akun_tables.nama_akun',
                DB::raw("SUM(CASE WHEN keuangan_tables.id_akun = akun_tables.id THEN keuangan_tables.total ELSE 0 END) AS saldo_debit"),
                DB::raw("SUM(CASE WHEN keuangan_tables.id_akun_second = akun_tables.id THEN keuangan_tables.total ELSE 0 END) AS saldo_kredit")
            )
            ->join('akun_tables', function ($join) {
                $join->on('keuangan_tables.id_akun', '=', 'akun_tables.id')
                    ->orOn('keuangan_tables.id_akun_second', '=', 'akun_tables.id');
            })
            ->whereRaw("STR_TO_DATE(keuangan_tables.tanggal, '%d/%m/%Y') IS NOT NULL")
            ->where("keuangan_tables.auth", $auth);

        // Filter berdasarkan tanggal_awal dan tanggal_akhir jika ada
        if ($tanggal_awal && $tanggal_akhir) {
            try {
                $fromDate = Carbon::createFromFormat('d/m/Y', $tanggal_awal)->format('Y-m-d');
                $toDate = Carbon::createFromFormat('d/m/Y', $tanggal_akhir)->format('Y-m-d');
                $query->whereRaw("STR_TO_DATE(keuangan_tables.tanggal, '%d/%m/%Y') BETWEEN ? AND ?", [$fromDate, $toDate]);
            } catch (\Exception $e) {
                // Abaikan jika format salah
            }
        } else {
            // Filter berdasarkan tahun
            $query->whereRaw("YEAR(STR_TO_DATE(keuangan_tables.tanggal, '%d/%m/%Y')) = ?", [$tahun]);

            // Filter berdasarkan bulan jika ada
            if ($bulan) {
                $query->whereRaw("MONTH(STR_TO_DATE(keuangan_tables.tanggal, '%d/%m/%Y')) = ?", [$bulan]);
            }
        }

        $data = $query->groupBy('akun_tables.id', 'akun_tables.kode_akun', 'akun_tables.nama_akun')
            ->get();

        return view('keuangan.neracasaldo',[
            'activeMenu' => 'laporan',
            'active' => 'neracasaldo',
            'logs' => $logs
        ],compact('data','bulan','tahun'));

    }

    public function neracaSaldoPdf(Request $request)
    {
        // Ambil parameter filter
        $periode = $request->input('periode');
        $bulan = $request->input('bulan', now()->format('m'));
        $tahun = $request->input('tahun', now()->format('Y'));
        $tahun_bulan = $request->input('tahun_bulan');
        $tahun_tahun = $request->input('tahun_tahun');
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');

        // Jika ada filter periode bulanan, gunakan bulan dan tahun_bulan
        if ($periode === 'bulanan' && $tahun_bulan) {
            $tahun = (int) $tahun_bulan;
        }

        // Jika ada filter periode tahunan, gunakan tahun_tahun dan set bulan ke null
        if ($periode === 'tahunan' && $tahun_tahun) {
            $tahun = (int) $tahun_tahun;
            $bulan = null;
        }

        $auth = auth()->user()->id;

        $query = DB::table('keuangan_tables')
            ->select(
                'akun_tables.id',
                'akun_tables.kode_akun',
                'akun_tables.nama_akun',
                DB::raw("SUM(CASE WHEN keuangan_tables.id_akun = akun_tables.id THEN keuangan_tables.total ELSE 0 END) AS saldo_debit"),
                DB::raw("SUM(CASE WHEN keuangan_tables.id_akun_second = akun_tables.id THEN keuangan_tables.total ELSE 0 END) AS saldo_kredit")
            )
            ->join('akun_tables', function ($join) {
                $join->on('keuangan_tables.id_akun', '=', 'akun_tables.id')
                    ->orOn('keuangan_tables.id_akun_second', '=', 'akun_tables.id');
            })
            ->whereRaw("STR_TO_DATE(keuangan_tables.tanggal, '%d/%m/%Y') IS NOT NULL")
            ->where("keuangan_tables.auth", $auth);

        // Filter berdasarkan tanggal_awal dan tanggal_akhir jika ada
        if ($tanggal_awal && $tanggal_akhir) {
            try {
                $fromDate = Carbon::createFromFormat('d/m/Y', $tanggal_awal)->format('Y-m-d');
                $toDate = Carbon::createFromFormat('d/m/Y', $tanggal_akhir)->format('Y-m-d');
                $query->whereRaw("STR_TO_DATE(keuangan_tables.tanggal, '%d/%m/%Y') BETWEEN ? AND ?", [$fromDate, $toDate]);
            } catch (\Exception $e) {
                // Abaikan jika format salah
            }
        } else {
            // Filter berdasarkan tahun
            $query->whereRaw("YEAR(STR_TO_DATE(keuangan_tables.tanggal, '%d/%m/%Y')) = ?", [$tahun]);

            // Filter berdasarkan bulan jika ada
            if ($bulan) {
                $query->whereRaw("MONTH(STR_TO_DATE(keuangan_tables.tanggal, '%d/%m/%Y')) = ?", [$bulan]);
            }
        }

        $data = $query->groupBy('akun_tables.id', 'akun_tables.kode_akun', 'akun_tables.nama_akun')
            ->get();

        $perusahaan = auth()->user()->perusahaanUser;

        $logo = null;
        if ($perusahaan->logo && file_exists(storage_path('app/public/' . $perusahaan->logo))) {
            $logoPath = storage_path('app/public/' . $perusahaan->logo);
            $logo = 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($logoPath));
        } else {
            // fallback ke logo default di public/assets
            $defaultPath = public_path('assets/default_logo.png');
            if (file_exists($defaultPath)) {
                $logo = 'data:image/png;base64,' . base64_encode(file_get_contents($defaultPath));
            }
        }

        // Generate PDF
        $pdf = Pdf::loadView('keuangan.pdf.neracaSaldo', [
            'data' => $data,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'perusahaan' => $perusahaan,
            'logo' => $logo,
            'periode' => $periode,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
        ])->setPaper('A4', 'portrait');

        return $pdf->stream("LAPORAN-NERACA-SALDO-{$bulan}-{$tahun}.pdf");
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

        // Ambil parameter filter
        $periode = $request->input('periode');
        $bulan = $request->input('bulan', now()->format('m'));
        $tahun = $request->input('tahun', now()->format('Y'));
        $tahun_bulan = $request->input('tahun_bulan');
        $tahun_tahun = $request->input('tahun_tahun');
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');

        // Jika ada filter periode bulanan, gunakan bulan dan tahun_bulan
        if ($periode === 'bulanan' && $tahun_bulan) {
            $tahun = (int) $tahun_bulan;
        }

        // Jika ada filter periode tahunan, gunakan tahun_tahun dan set bulan ke null
        if ($periode === 'tahunan' && $tahun_tahun) {
            $tahun = (int) $tahun_tahun;
            $bulan = null;
        }

        $auth = auth()->user()->id;

        $query = DB::table('keuangan_tables')
            ->select(
                'akun_tables.id',
                'akun_tables.kode_akun',
                'akun_tables.nama_akun',
                DB::raw("SUM(CASE WHEN keuangan_tables.id_akun = akun_tables.id THEN keuangan_tables.total ELSE 0 END) AS saldo_debit"),
                DB::raw("SUM(CASE WHEN keuangan_tables.id_akun_second = akun_tables.id THEN keuangan_tables.total ELSE 0 END) AS saldo_kredit")
            )
            ->join('akun_tables', function ($join) {
                $join->on('keuangan_tables.id_akun', '=', 'akun_tables.id')
                    ->orOn('keuangan_tables.id_akun_second', '=', 'akun_tables.id');
            })
            ->whereRaw("STR_TO_DATE(keuangan_tables.tanggal, '%d/%m/%Y') IS NOT NULL")
            ->where("keuangan_tables.auth", $auth);

        // Filter berdasarkan tanggal_awal dan tanggal_akhir jika ada
        if ($tanggal_awal && $tanggal_akhir) {
            try {
                $fromDate = Carbon::createFromFormat('d/m/Y', $tanggal_awal)->format('Y-m-d');
                $toDate = Carbon::createFromFormat('d/m/Y', $tanggal_akhir)->format('Y-m-d');
                $query->whereRaw("STR_TO_DATE(keuangan_tables.tanggal, '%d/%m/%Y') BETWEEN ? AND ?", [$fromDate, $toDate]);
            } catch (\Exception $e) {
                // Abaikan jika format salah
            }
        } else {
            // Filter berdasarkan tahun
            $query->whereRaw("YEAR(STR_TO_DATE(keuangan_tables.tanggal, '%d/%m/%Y')) = ?", [$tahun]);

            // Filter berdasarkan bulan jika ada
            if ($bulan) {
                $query->whereRaw("MONTH(STR_TO_DATE(keuangan_tables.tanggal, '%d/%m/%Y')) = ?", [$bulan]);
            }
        }

        $data = $query->groupBy('akun_tables.id', 'akun_tables.kode_akun', 'akun_tables.nama_akun')
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

        // Ambil parameter filter
        $periode = $request->input('periode');
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        $tahun_bulan = $request->input('tahun_bulan');
        $tahun_tahun = $request->input('tahun_tahun');
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');

        // Jika ada filter periode bulanan, gunakan bulan dan tahun_bulan
        if ($periode === 'bulanan' && $tahun_bulan) {
            $tahun = (int) $tahun_bulan;
        }

        // Jika ada filter periode tahunan, gunakan tahun_tahun dan set bulan ke null
        if ($periode === 'tahunan' && $tahun_tahun) {
            $tahun = (int) $tahun_tahun;
            $bulan = null;
        }

        $auth = auth()->id(); // lebih ringkas daripada auth()->user()->id

        $query = KeuanganTable::with([
            'akun.kategori',
            'akunSecond.kategori'
        ])
        ->whereRaw("STR_TO_DATE(tanggal, '%d/%m/%Y') IS NOT NULL")
        ->where('auth', $auth);

        // Filter berdasarkan tanggal_awal dan tanggal_akhir jika ada
        if ($tanggal_awal && $tanggal_akhir) {
            try {
                $fromDate = Carbon::createFromFormat('d/m/Y', $tanggal_awal)->format('Y-m-d');
                $toDate = Carbon::createFromFormat('d/m/Y', $tanggal_akhir)->format('Y-m-d');
                $query->whereRaw("STR_TO_DATE(tanggal, '%d/%m/%Y') BETWEEN ? AND ?", [$fromDate, $toDate]);
            } catch (\Exception $e) {
                // Abaikan jika format salah
            }
        } else {
            // Filter berdasarkan tahun
            $query->whereRaw("YEAR(STR_TO_DATE(tanggal, '%d/%m/%Y')) = ?", [$tahun]);

            // Filter berdasarkan bulan jika ada
            if ($bulan) {
                $query->whereRaw("MONTH(STR_TO_DATE(tanggal, '%d/%m/%Y')) = ?", [$bulan]);
            }
        }

        $items = $query->orderByRaw("STR_TO_DATE(tanggal, '%d/%m/%Y') ASC")
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
            $akun = AkunTable::with('kategori')->find($akunId);
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
        $transaksi = KeuanganTable::with(['akun', 'akunsecond', 'rekening'])
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
