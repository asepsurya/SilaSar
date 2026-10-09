<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'all');
        $query = Notification::where('user_id', auth()->id())->with('transaksi.mitra');

        if ($filter === 'unread') {
            $query->where('is_read', false);
        } elseif ($filter !== 'all') {
            $query->where('type', $filter);
        }

        $notifications = $query->latest()->take(30)->get()->map(function ($n) {
            return [
                'id' => $n->id,
                'type' => $n->type,
                'title' => $n->title,
                'message' => $n->message,
                'data' => $n->data,
                'is_read' => $n->is_read,
                'created_at' => $n->created_at->diffForHumans(),
                'transaksi' => $n->transaksi ? [
                    'id' => $n->transaksi->id,
                    'kode' => $n->transaksi->kode_transaksi,
                    'mitra' => $n->transaksi->mitra->nama_mitra ?? '-',
                    'total' => $n->transaksi->total,
                ] : null,
            ];
        });

        $unreadCount = Notification::where('user_id', auth()->id())->where('is_read', false)->count();

        return response()->json([
            'status' => 'success',
            'data' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    public function getUnreadCount()
    {
        $count = Notification::where('user_id', auth()->id())->where('is_read', false)->count();
        return response()->json(['status' => 'success', 'count' => $count]);
    }

    public function markAsRead($id)
    {
        Notification::where('user_id', auth()->id())->where('id', $id)->update(['is_read' => true, 'read_at' => now()]);
        return response()->json(['status' => 'success']);
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->id())->where('is_read', false)->update(['is_read' => true, 'read_at' => now()]);
        return response()->json(['status' => 'success']);
    }

    public function getUnnotifiedOverdue()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->whereIn('type', ['payment_overdue', 'payment_due'])
            ->where('browser_notified', false)
            ->with('transaksi.mitra')
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($n) {
                return [
                    'id' => $n->id,
                    'type' => $n->type,
                    'title' => $n->title,
                    'message' => $n->message,
                    'data' => $n->data,
                    'is_read' => $n->is_read,
                    'created_at' => $n->created_at->diffForHumans(),
                    'transaksi' => $n->transaksi ? [
                        'id' => $n->transaksi->id,
                        'kode' => $n->transaksi->kode_transaksi,
                        'mitra' => $n->transaksi->mitra->nama_mitra ?? '-',
                        'total' => $n->transaksi->total,
                    ] : null,
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => $notifications,
        ]);
    }

    public function markBrowserNotified(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!is_array($ids) || empty($ids)) {
            return response()->json(['status' => 'success', 'message' => 'No IDs provided']);
        }

        Notification::where('user_id', auth()->id())
            ->whereIn('id', $ids)
            ->update(['browser_notified' => true, 'notified_at' => now()]);

        return response()->json(['status' => 'success']);
    }

    public function jatuhTempo()
    {
        $transaksis = Transaksi::with('mitra')
            ->where('auth', auth()->id())
            ->where('status_bayar', '!=', 'Sudah Bayar')
            ->whereNotNull('tanggal_transaksi')
            ->whereRaw("IFNULL(tanggal_pembayaran, DATE_ADD(tanggal_transaksi, INTERVAL 30 DAY)) <= ?", [now()->addDays(7)->toDateString()])
            ->orderByRaw("IFNULL(tanggal_pembayaran, DATE_ADD(tanggal_transaksi, INTERVAL 30 DAY)) ASC")
            ->get()
            ->map(function ($t) {
                $dueDate = $t->tanggal_pembayaran
                    ? Carbon::parse($t->tanggal_pembayaran)
                    : Carbon::parse($t->tanggal_transaksi)->addDays(30);
                $daysDiff = Carbon::now()->startOfDay()->diffInDays($dueDate->copy()->startOfDay(), false);

                return [
                    'id' => $t->id,
                    'kode_transaksi' => $t->kode_transaksi,
                    'mitra' => $t->mitra->nama_mitra ?? '-',
                    'total' => (float) $t->total,
                    'tanggal_pembayaran' => $t->tanggal_pembayaran,
                    'due_date_formatted' => $dueDate->format('d M Y'),
                    'days_diff' => $daysDiff,
                    'transaksi_url' => route('transaksi.detail', $t->id),
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => $transaksis,
            'count' => $transaksis->count(),
        ]);
    }
}
