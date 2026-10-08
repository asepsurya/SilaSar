<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Transaksi;
use Carbon\Carbon;

class PaymentNotificationService
{
    public static function createPaymentDue(Transaksi $transaksi, int $daysBefore = 7): ?Notification
    {
        $dueDate = $transaksi->tanggal_pembayaran;
        if (!$dueDate) return null;

        $dueDateCarbon = Carbon::parse($dueDate)->endOfMonth()->startOfDay();
        $nowCarbon = Carbon::now()->startOfDay();
        $daysDiff = $nowCarbon->diffInDays($dueDateCarbon, false);
        
        if ($daysDiff > $daysBefore || $daysDiff < 0) return null;

        $type = $daysDiff < 0 ? 'payment_overdue' : 'payment_due';
        $title = $type === 'payment_overdue' ? 'Pembayaran Jatuh Tempo' : 'Pembayaran Akan Jatuh Tempo';
        $message = "Transaksi {$transaksi->kode_transaksi} untuk {$transaksi->mitra->nama_mitra} "
            . ($type === 'payment_overdue' 
                ? 'sudah lewat ' . abs($daysDiff) . ' hari' 
                : "akan jatuh tempo dalam {$daysDiff} hari");

        return Notification::firstOrCreate([
            'user_id' => $transaksi->auth,
            'transaksi_id' => $transaksi->id,
            'type' => $type,
        ], [
            'title' => $title,
            'message' => $message,
            'data' => [
                'amount' => $transaksi->total,
                'due_date' => $dueDate,
                'days_diff' => $daysDiff,
            ],
        ]);
    }

    public static function createPaymentReceived(Transaksi $transaksi): Notification
    {
        return Notification::create([
            'user_id' => $transaksi->auth,
            'transaksi_id' => $transaksi->id,
            'type' => 'payment_received',
            'title' => 'Pembayaran Diterima',
            'message' => "Pembayaran transaksi {$transaksi->kode_transaksi} dari {$transaksi->mitra->nama_mitra} sebesar " . number_format($transaksi->total, 0, ',', '.') . " telah diterima.",
            'data' => [
                'amount' => $transaksi->total,
                'received_at' => now(),
            ],
        ]);
    }

    public static function createPaymentPartial(Transaksi $transaksi, $amount): Notification
    {
        return Notification::create([
            'user_id' => $transaksi->auth,
            'transaksi_id' => $transaksi->id,
            'type' => 'payment_partial',
            'title' => 'Pembayaran Sebagian Diterima',
            'message' => "Pembayaran sebagian transaksi {$transaksi->kode_transaksi} dari {$transaksi->mitra->nama_mitra} sebesar " . number_format($amount, 0, ',', '.') . " telah diterima.",
            'data' => [
                'amount' => $amount,
                'total_amount' => $transaksi->total,
                'received_at' => now(),
            ],
        ]);
    }

    public static function checkAndCreateDueNotifications(): int
    {
        $transaksis = Transaksi::with('mitra')
            ->whereNotNull('tanggal_pembayaran')
            ->where('status_bayar', '!=', 'sudah_bayar')
            ->get();

        $count = 0;
        foreach ($transaksis as $transaksi) {
            if (self::createPaymentDue($transaksi)) {
                $count++;
            }
        }

        return $count;
    }
}
