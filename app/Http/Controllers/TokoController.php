<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;
use App\Models\CanvassingVisit;
use Carbon\Carbon;

class TokoController extends Controller
{
    public function index()
    {
        $logs = Activity::where(['causer_id' => auth()->user()->id, 'log_name' => 'ikm'])->latest()->take(10)->get();

        return view('canvassing.index', [
            "activeMenu" => "canvassing",
            "active" => "canvassing",
            "logs" => $logs
        ]);
    }

    public function getTokos(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $tokos = \App\Models\Mitra::where('auth', auth()->user()->id)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('latitude', '!=', '')
            ->where('longitude', '!=', '')
            ->get()->map(function($item) use ($month, $year) {
                // Check if visited in the target month/year
                $isChecked = CanvassingVisit::where('mitra_id', $item->id)
                    ->whereYear('visited_at', $year)
                    ->whereMonth('visited_at', $month)
                    ->exists();

                return [
                    'id' => $item->id,
                    'nama' => $item->nama_mitra,
                    'alamat' => $item->alamat_mitra,
                    'latitude' => $item->latitude,
                    'longitude' => $item->longitude,
                    'no_telp_mitra' => $item->no_telp_mitra,
                    'is_checked' => $isChecked,
                    'foto' => $item->foto,
                    'kode_mitra' => $item->kode_mitra,
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => $tokos
        ]);
    }

    public function rute(Request $request)
    {
        $validated = $request->validate([
            'user_lat' => 'required|numeric',
            'user_lng' => 'required|numeric',
            'toko_ids' => 'required|array',
        ]);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Rute berhasil direkam.',
            'data' => $validated
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'no_telp_mitra' => 'nullable|string|max:255',
            'foto' => 'nullable|string',
        ]);

        $kode_mitra = 'M-' . strtoupper(Str::random(6)) . time();

        $toko = \App\Models\Mitra::create([
            'kode_mitra' => $kode_mitra,
            'nama_mitra' => $validated['nama'],
            'alamat_mitra' => $validated['alamat'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'no_telp_mitra' => $validated['no_telp_mitra'] ?? null,
            'foto' => $validated['foto'] ?? null,
            'auth' => auth()->user() ? auth()->user()->id : null
        ]);

        $responseData = [
            'id' => $toko->id,
            'nama' => $toko->nama_mitra,
            'alamat' => $toko->alamat_mitra,
            'latitude' => $toko->latitude,
            'longitude' => $toko->longitude,
            'no_telp_mitra' => $toko->no_telp_mitra,
            'is_checked' => $toko->is_checked,
            'foto' => $toko->foto,
            'kode_mitra' => $toko->kode_mitra,
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Titik toko berhasil ditambahkan.',
            'data' => $responseData
        ]);
    }

    public function toggleStatus(Request $request, $id)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $visit = CanvassingVisit::where('mitra_id', $id)
            ->whereYear('visited_at', $year)
            ->whereMonth('visited_at', $month)
            ->first();

        if ($visit) {
            $visit->delete();
            $isChecked = false;
        } else {
            // Set visited_at to 1st of the month if it's a past/future month, or today if it's current month
            $visitedAt = now();
            if ($month != now()->month || $year != now()->year) {
                $visitedAt = Carbon::create($year, $month, 1);
            }

            CanvassingVisit::create([
                'mitra_id' => $id,
                'user_id' => auth()->id(),
                'visited_at' => $visitedAt
            ]);
            $isChecked = true;
        }

        return response()->json([
            'status' => 'success',
            'is_checked' => $isChecked
        ]);
    }

    public function getProposedItems($kode_mitra)
    {
        $items = \App\Models\Penawaran::where('kode_mitra', $kode_mitra)
            ->with('produk')
            ->get();
            
        $transactions = \App\Models\Transaksi::where('kode_mitra', $kode_mitra)
            ->latest()
            ->take(10)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $items,
            'transactions' => $transactions
        ]);
    }
}
