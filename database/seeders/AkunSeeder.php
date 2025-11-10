<?php

namespace Database\Seeders;

use App\Models\Akun;
use App\Models\AkunTable;
use App\Models\KategoriAkun;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AkunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategori = [
            // --- ASET ---
            ['Kas & Bank', 'aset'],
            ['Akun Piutang', 'aset'],
            ['Persediaan', 'aset'],
            ['Harta Lancar Lainnya', 'aset'],
            ['Harta Tetap', 'aset'],
            ['Harta Lainnya', 'aset'],

            // --- LIABILITAS ---
            ['Akun Hutang', 'liabilitas'],
            ['Kewajiban Lancar Lainnya', 'liabilitas'],
            ['Kewajiban Jangka Panjang', 'liabilitas'],

            // --- EKUITAS ---
            ['Modal', 'ekuitas'],

            // --- PENDAPATAN (untuk Laba Rugi) ---
            ['Pendapatan', 'pendapatan'],
            ['Pendapatan Lainnya', 'pendapatan'],

            // --- BEBAN (untuk Laba Rugi) ---
            ['Harga Pokok Penjualan', 'beban'],
            ['Beban', 'beban'],
            ['Beban Lainnya', 'beban'],
            ['Depresiasi & Amortisasi', 'beban'],
        ];

        foreach ($kategori as [$nama, $tipe]) {
            KategoriAkun::create([
                'nama_kategori' => $nama,
                'tipe' => $tipe,
                'deskripsi' => null,
            ]);
        }

       $data = [
            ['1-0001', 'Kas', 'Kas & Bank', 'pemasukan'],
            ['1-0002', 'Rekening Bank', 'Kas & Bank', 'pemasukan'],
            ['1-0003', 'Bank Mandiri', 'Kas & Bank', 'pemasukan'],
            ['1-0004', 'Bank Negara Indonesia (BNI)', 'Kas & Bank', 'pemasukan'],
            ['1-0005', 'Bank Rakyat Indonesia (BRI)', 'Kas & Bank', 'pemasukan'],
            ['1-0006', 'Bank Tabungan Negara (BTN)', 'Kas & Bank', 'pemasukan'],
            ['1-0007', 'Bank Central Asia (BCA)', 'Kas & Bank', 'pemasukan'],
            ['1-0008', 'GoPay', 'Kas & Bank', 'pemasukan'],
            ['1-10009', 'OVO', 'Kas & Bank', 'pemasukan'],
            ['1-10010', 'Dana', 'Kas & Bank', 'pemasukan'],
            ['1-10011', 'Link Aja', 'Kas & Bank', 'pemasukan'],
            ['1-10012', 'Cashlez', 'Kas & Bank', 'pemasukan'],
            ['1-10100', 'Piutang Usaha', 'Akun Piutang', 'pemasukan'],
            ['1-10101', 'Piutang Belum Ditagih', 'Akun Piutang', 'pemasukan'],
            ['1-10200', 'Persediaan Barang', 'Persediaan', 'pengeluaran'],
            ['1-10300', 'Piutang Lainnya', 'Harta Lancar Lainnya', 'pengeluaran'],
            ['1-10301', 'Piutang Karyawan', 'Harta Lancar Lainnya', 'pengeluaran'],
            ['1-10400', 'Dana Belum Disetor', 'Harta Lancar Lainnya', 'pengeluaran'],
            ['1-10401', 'Aset Lancar Lainnya', 'Harta Lancar Lainnya', 'pengeluaran'],
            ['1-10402', 'Biaya Dibayar Di Muka', 'Harta Lancar Lainnya', 'pengeluaran'],
            ['1-10403', 'Uang Muka', 'Harta Lancar Lainnya', 'pengeluaran'],
            ['1-10500', 'PPN Masukan', 'Harta Lancar Lainnya', 'pengeluaran'],
            ['1-10501', 'Pajak Penghasilan Dibayar Di Muka - PPh 22', 'Harta Lancar Lainnya', 'pengeluaran'],
            ['1-10502', 'Pajak Penghasilan Dibayar Di Muka - PPh 23', 'Harta Lancar Lainnya', 'pengeluaran'],
            ['1-10503', 'Pajak Penghasilan Dibayar Di Muka - PPh 25', 'Harta Lancar Lainnya', 'pengeluaran'],
            ['1-10700', 'Aktiva Tetap - Tanah', 'Harta Tetap', 'pengeluaran'],
            ['1-10701', 'Aset Tetap - Bangunan', 'Harta Tetap', 'pengeluaran'],
            ['1-10702', 'Aset Tetap - Pengembangan Bangunan', 'Harta Tetap', 'pengeluaran'],
            ['1-10703', 'Aset Tetap - Kendaraan', 'Harta Tetap', 'pengeluaran'],
            ['1-10704', 'Aset Tetap - Mesin & Peralatan', 'Harta Tetap', 'pengeluaran'],
            ['1-10705', 'Aset Tetap - Peralatan Kantor', 'Harta Tetap', 'pengeluaran'],
            ['1-10706', 'Aset Tetap - Aset Sewaan', 'Harta Tetap', 'pengeluaran'],
            ['1-10800', 'Investasi', 'Harta Lainnya', 'pemasukan'],
            ['2-20100', 'Hutang Usaha', 'Akun Hutang', 'pengeluaran'],
            ['2-20101', 'Hutang Belum Ditagih', 'Akun Hutang', 'pengeluaran'],
            ['2-20200', 'Hutang Lainnya', 'Kewajiban Lancar Lainnya', 'pengeluaran'],
            ['2-20201', 'Hutang Gaji', 'Kewajiban Lancar Lainnya', 'pengeluaran'],
            ['2-20202', 'Hutang Deviden', 'Kewajiban Lancar Lainnya', 'pengeluaran'],
            ['2-20203', 'Pendapatan Diterima Di Muka', 'Kewajiban Lancar Lainnya', 'pengeluaran'],
            ['2-20301', 'Sarana Kantor Terhutang', 'Kewajiban Lancar Lainnya', 'pengeluaran'],
            ['2-20302', 'Bunga Terhutang', 'Kewajiban Lancar Lainnya', 'pengeluaran'],
            ['2-20399', 'Biaya Terhutang lainnya', 'Kewajiban Lancar Lainnya', 'pengeluaran'],
            ['2-20400', 'Hutang Bank', 'Kewajiban Lancar Lainnya', 'pengeluaran'],
            ['2-20500', 'PPN Keluaran', 'Kewajiban Lancar Lainnya', 'pengeluaran'],
            ['2-20501', 'Hutang Pajak - PPh 21', 'Kewajiban Lancar Lainnya', 'pengeluaran'],
            ['2-20502', 'Hutang Pajak - PPh 22', 'Kewajiban Lancar Lainnya', 'pengeluaran'],
            ['2-20503', 'Hutang Pajak - PPh 23', 'Kewajiban Lancar Lainnya', 'pengeluaran'],
            ['2-20504', 'Hutang Pajak - PPh 29', 'Kewajiban Lancar Lainnya', 'pengeluaran'],
            ['2-20599', 'Hutang Pajak Lainnya', 'Kewajiban Lancar Lainnya', 'pengeluaran'],
            ['2-20600', 'Hutang Dari Pemegang Saham', 'Kewajiban Lancar Lainnya', 'pengeluaran'],
            ['2-20601', 'Kewajiban Lancar Lainnya', 'Kewajiban Lancar Lainnya', 'pengeluaran'],
            ['2-20700', 'Kewajiban Manfaat Karyawan', 'Kewajiban Jangka Panjang', 'pengeluaran'],
            ['3-30000', 'Modal Saham', 'Modal', 'pemasukan'],
            ['3-30001', 'Modal Tambahan', 'Modal', 'pemasukan'],
            ['3-30100', 'Laba Ditahan', 'Modal', 'pemasukan'],
            ['3-30200', 'Deviden', 'Modal', 'pemasukan'],
            ['3-30300', 'Pendapatan Komprehensif Lainnya', 'Modal', 'pemasukan'],
            ['3-30999', 'Saldo Awal', 'Modal', 'pemasukan'],
            ['4-40000', 'Pendapatan', 'Pendapatan', 'pemasukan'],
            ['4-40100', 'Diskon Penjualan', 'Pendapatan', 'pemasukan'],
            ['4-40200', 'Pengembalian Penjualan', 'Pendapatan', 'pemasukan'],
            ['5-50000', 'Beban Pokok Pendapatan', 'Harga Pokok Penjualan', 'pengeluaran'],
            ['5-50100', 'Diskon Pembelian', 'Harga Pokok Penjualan', 'pengeluaran'],
            ['5-50200', 'Pengembalian Pembelian', 'Harga Pokok Penjualan', 'pengeluaran'],
            ['5-50300', 'Pengiriman / Pengangkutan', 'Harga Pokok Penjualan', 'pengeluaran'],
            ['5-50400', 'Biaya Import', 'Harga Pokok Penjualan', 'pengeluaran'],
            ['5-50500', 'Biaya Produksi', 'Harga Pokok Penjualan', 'pengeluaran'],
            ['6-60000', 'Biaya Penjualan', 'Beban', 'pengeluaran'],
            ['6-60001', 'Iklan & Promosi', 'Beban', 'pengeluaran'],
            ['6-60002', 'Komisi & Fee', 'Beban', 'pengeluaran'],
            ['6-60003', 'Bensin - Toll - dan Parkir - Penjualan', 'Beban', 'pengeluaran'],
            ['6-60004', 'Perjalanan (Travelling) - Penjualan', 'Beban', 'pengeluaran'],
            ['6-60005', 'Komunikasi - Penjualan', 'Beban', 'pengeluaran'],
            ['6-60006', 'Pemasaran lainnya', 'Beban', 'pengeluaran'],
            ['6-60100', 'Biaya Umum & Administratif', 'Beban', 'pengeluaran'],
            ['6-60101', 'Gaji', 'Beban', 'pengeluaran'],
            ['6-60102', 'Upah', 'Beban', 'pengeluaran'],
            ['6-60103', 'Konsumsi & Transport', 'Beban', 'pengeluaran'],
            ['6-60104', 'Lembur', 'Beban', 'pengeluaran'],
            ['6-60105', 'Kesehatan', 'Beban', 'pengeluaran'],
            ['6-60106', 'THR dan Bonus', 'Beban', 'pengeluaran'],
            ['6-60107', 'Jamsostek', 'Beban', 'pengeluaran'],
            ['6-60108', 'Insentif', 'Beban', 'pengeluaran'],
            ['6-60109', 'Pesangon', 'Beban', 'pengeluaran'],
            ['6-60110', 'Tunjangan Lainnya', 'Beban', 'pengeluaran'],
            ['6-60200', 'Donasi', 'Beban', 'pengeluaran'],
            ['6-60201', 'Hiburan', 'Beban', 'pengeluaran'],
            ['6-60202', 'Bensin - Toll - dan Parkir - Umum', 'Beban', 'pengeluaran'],
            ['6-60203', 'Perbaikan dan Perawatan', 'Beban', 'pengeluaran'],
            ['6-60204', 'Perjalanan (Travelling) - Umum', 'Beban', 'pengeluaran'],
            ['6-60205', 'Konsumsi', 'Beban', 'pengeluaran'],
            ['6-60206', 'Komunikasi - Umum', 'Beban', 'pengeluaran'],
            ['6-60207', 'Iuran & Berlangganan', 'Beban', 'pengeluaran'],
            ['6-60208', 'Asuransi', 'Beban', 'pengeluaran'],
            ['6-60209', 'Biaya Hukum & Professional', 'Beban', 'pengeluaran'],
            ['6-60210', 'Beban Tunjangan Karyawan', 'Beban', 'pengeluaran'],
            ['6-60211', 'Sarana Kantor', 'Beban', 'pengeluaran'],
            ['6-60212', 'Pelatihan & Pengembangan', 'Beban', 'pengeluaran'],
            ['6-60213', 'Beban Hutang Buruk', 'Beban', 'pengeluaran'],
            ['6-60214', 'Pajak & Lisensi', 'Beban', 'pengeluaran'],
            ['6-60215', 'Denda', 'Beban', 'pengeluaran'],
            ['6-60216', 'Pengeluaran Barang Rusak', 'Beban', 'pengeluaran'],
            ['6-60300', 'Beban Kantor', 'Beban', 'pengeluaran'],
            ['6-60301', 'ATK & Print', 'Beban', 'pengeluaran'],
            ['6-60302', 'Material', 'Beban', 'pengeluaran'],
            ['6-60303', 'Keamanan & Kebersihan', 'Beban', 'pengeluaran'],
            ['6-60304', 'Persediaan Material', 'Beban', 'pengeluaran'],
            ['6-60305', 'Sub Kontraktor', 'Beban', 'pengeluaran'],
            ['6-60400', 'Beban Sewa - Bangunan', 'Beban', 'pengeluaran'],
            ['6-60401', 'Beban Sewa - Kendaraan', 'Beban', 'pengeluaran'],
            ['6-60402', 'Beban Sewa - Sewa Operasional', 'Beban', 'pengeluaran'],
            ['6-60403', 'Beban Sewa - Lainnya', 'Beban', 'pengeluaran'],
            ['6-60500', 'Depresiasi - Bangunan', 'Beban', 'pengeluaran'],
            ['6-60501', 'Depresiasi - Pengembangan Bangunan', 'Beban', 'pengeluaran'],
            ['6-60502', 'Depresiasi - Kendaraan', 'Beban', 'pengeluaran'],
            ['6-60503', 'Depresiasi - Mesin & Peralatan', 'Beban', 'pengeluaran'],
            ['6-60504', 'Depresiasi - Peralatan Kantor', 'Beban', 'pengeluaran'],
            ['6-60599', 'Depresiasi - Aset Sewaan', 'Beban', 'pengeluaran'],
            ['7-70000', 'Pendapatan Bunga - Bank', 'Pendapatan Lainnya', 'pemasukan'],
            ['7-70001', 'Pendapatan Bunga - Waktu Deposit', 'Pendapatan Lainnya', 'pemasukan'],
            ['7-70099', 'Pendapatan lainnya', 'Pendapatan Lainnya', 'pemasukan'],
            ['8-80000', 'Beban Bunga', 'Beban Lainnya', 'pengeluaran'],
            ['8-80001', 'Persediaan', 'Beban Lainnya', 'pengeluaran'],
            ['8-80002', '(Keuntungan) / Kerugian Pembuangan Aset Tetap', 'Beban Lainnya', 'pengeluaran'],
            ['8-80100', 'Penyesuaian Persediaan', 'Beban Lainnya', 'pengeluaran'],
            ['8-80999', 'Biaya lainnya', 'Beban Lainnya', 'pengeluaran'],
            ['9-90000', 'Pajak Penghasilan - Saat Ini', 'Beban Lainnya', 'pengeluaran'],
            ['1-10600', 'Aset Tidak Berwujud', 'Depresiasi & Amortisasi', 'pengeluaran'],
            ['1-10601', 'Akumulasi Penyusutan - Bangunan', 'Depresiasi & Amortisasi', 'pengeluaran'],
            ['1-10602', 'Akumulasi Penyusutan - Pengembangan Bangunan', 'Depresiasi & Amortisasi', 'pengeluaran'],
            ['1-10603', 'Akumulasi Penyusutan - Kendaraan', 'Depresiasi & Amortisasi', 'pengeluaran'],
            ['1-10604', 'Akumulasi Penyusutan - Mesin & Peralatan', 'Depresiasi & Amortisasi', 'pengeluaran'],
            ['1-10605', 'Akumulasi Penyusutan - Peralatan Kantor', 'Depresiasi & Amortisasi', 'pengeluaran'],
            ['1-10606', 'Akumulasi Penyusutan - Aset Sewaan', 'Depresiasi & Amortisasi', 'pengeluaran'],
            ['1-10607', 'Akumulasi Amortisasi', 'Depresiasi & Amortisasi', 'pengeluaran'],
        ];

        foreach ($data as $akun) {
            $kategori = KategoriAkun::where('nama_kategori', $akun[2])->first();

            AkunTable::firstOrCreate([
                'kode_akun'   => $akun[0],
            ],[
                'nama_akun'   => $akun[1],
                'jenis_akun'  => $akun[3],
                'kategori_id' => $kategori->id,
            ]);
        }

        // AKun Harian
        $pemasukan = [
            'Bantuan Sosial',
            'Bonus',
            'Pemasukan Lainnya',
            'Uang dari Hutang',
            'Pembayaran Piutang',
            'Kas Anggota MML',
            'Gaji Bulanan/Usaha Pribadi',
            'Nafkah dari Suami',
        ];

        $pengeluaran = [
            'Belanja Dapur',
            'Listrik/Pulsa',
            'Pendidikan Anak',
            'Jajan Anak',
            'Zakat/ Sedekah/ Sosial',
            'Kesehatan',
            'Tabungan',
            'Hiburan',
            'Belanja / Jajan Lainnya',
            'Cicilan',
            'Modal Usaha',
            'Bayar Hutang',
            'Piutang',
            'Pengeluaran tidak terduga',
            'Perlengkapan Rumah Tangga',
            'Transportasi',
            'Upah',
            'Iuran Keluar',
            'Arisan',
        ];

        foreach ($pemasukan as $nama) {
            Akun::updateOrCreate(
                ['nama_akun' => $nama],
                ['jenis_akun' => 'pemasukan']
            );
        }

        foreach ($pengeluaran as $nama) {
            Akun::updateOrCreate(
                ['nama_akun' => $nama],
                ['jenis_akun' => 'pengeluaran']
            );
        }



    }
}
