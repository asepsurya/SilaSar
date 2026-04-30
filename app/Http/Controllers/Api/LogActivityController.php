<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class LogActivityController extends Controller
{
    public function getLogs(Request $request)
    {
        $filter = $request->query('filter', 'semua');

        $query = Activity::where('causer_id', auth()->id())
            ->where('log_name', 'ikm')
            ->with('causer');

        // Tampilkan semua jika filter adalah 'semua'
        if ($filter === 'semua') {
            // Tidak ada query tambahan untuk 'semua' agar menampilkan seluruhnya
        } elseif ($filter === 'login') {
            $query->where(function($q) {
                $q->where('description', 'like', '%login%')
                  ->orWhere('description', 'like', '%masuk%');
            });
        } elseif ($filter === 'transaksi') {
            $query->where(function($q) {
                $q->where('description', 'like', '%transaksi%')
                  ->orWhere('description', 'like', '%nota%')
                  ->orWhere('description', 'like', '%bayar%')
                  ->orWhere('description', 'like', '%jual%');
            });
        } elseif ($filter === 'sistem') {
            $query->where('description', 'not like', '%login%')
                  ->where('description', 'not like', '%transaksi%')
                  ->where('description', 'not like', '%nota%');
        }

        $logs = $query->latest()->take(20)->get();

        $formattedLogs = $logs->map(function ($log) {
            $icon = 'info';
            $bg = 'bg-gray-100 text-gray-500 dark:bg-white/5 dark:text-gray-400';

            $desc = strtolower($log->description);
            if (str_contains($desc, 'login') || str_contains($desc, 'masuk')) {
                $icon = 'user-circle-plus';
                $bg = 'bg-emerald-100 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-400';
            } elseif (str_contains($desc, 'tambah') || str_contains($desc, 'baru')) {
                $icon = 'plus-square';
                $bg = 'bg-blue-100 text-blue-600 dark:bg-blue-500/20 dark:text-blue-400';
            } elseif (str_contains($desc, 'ubah') || str_contains($desc, 'update')) {
                $icon = 'pencil-line';
                $bg = 'bg-amber-100 text-amber-600 dark:bg-amber-500/20 dark:text-amber-400';
            } elseif (str_contains($desc, 'hapus') || str_contains($desc, 'delete')) {
                $icon = 'trash-simple';
                $bg = 'bg-red-100 text-red-600 dark:bg-red-500/20 dark:text-red-400';
            } elseif (str_contains($desc, 'transaksi') || str_contains($desc, 'nota') || str_contains($desc, 'jual')) {
                $icon = 'receipt';
                $bg = 'bg-purple-100 text-purple-600 dark:bg-purple-500/20 dark:text-purple-400';
            } elseif (str_contains($desc, 'stok') || str_contains($desc, 'produk')) {
                $icon = 'package';
                $bg = 'bg-cyan-100 text-cyan-600 dark:bg-cyan-500/20 dark:text-cyan-400';
            }

            return [
                'id' => $log->id,
                'description' => $log->description,
                'causer_name' => $log->causer->name ?? 'Sistem',
                'created_at' => $log->created_at->diffForHumans(),
                'icon' => $icon,
                'bg_class' => $bg
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $formattedLogs
        ]);
    }
}
