<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\Transaksi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SendPaymentDueNotifications extends Command
{
    protected $signature = 'push:payment-due-notifications
                            {--days=7 : Number of days before due date to notify}
                            {--dry-run : Run without sending push or saving notifications}';

    protected $description = 'Check transactions approaching due date and send web push notifications';

    public function handle()
    {
        $instanceId = config('services.pusher_beams.instance_id');
        $secretKey = config('services.pusher_beams.secret_key');

        if (! $instanceId || ! $secretKey) {
            $this->error('Pusher Beams credentials are not configured.');
            return self::FAILURE;
        }

        $daysBefore = (int) $this->option('days');
        $dryRun = $this->option('dry-run');

        $transaksis = Transaksi::with('mitra')
            ->whereNotNull('tanggal_pembayaran')
            ->where('status_bayar', '!=', 'Sudah Bayar')
            ->whereDate('tanggal_pembayaran', '>=', now()->startOfDay())
            ->whereDate('tanggal_pembayaran', '<=', now()->addDays($daysBefore)->endOfDay())
            ->get();

        if ($transaksis->isEmpty()) {
            $this->info('No transactions approaching due date.');
            return self::SUCCESS;
        }

        $sent = 0;
        $errors = 0;

        foreach ($transaksis as $transaksi) {
            $dueDate = $transaksi->tanggal_pembayaran;
            $daysDiff = now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($dueDate)->startOfDay(), false);
            $kodeTransaksi = $transaksi->kode_transaksi;

            $type = $daysDiff < 0 ? 'payment_overdue' : 'payment_due';
            $title = $type === 'payment_overdue' ? 'Pembayaran Jatuh Tempo' : 'Pembayaran Akan Jatuh Tempo';
            $message = "Transaksi {$kodeTransaksi} untuk {$transaksi->mitra->nama_mitra} "
                . ($type === 'payment_overdue'
                    ? 'sudah lewat ' . abs($daysDiff) . ' hari'
                    : "akan jatuh tempo dalam {$daysDiff} hari");

            $interest = "user_{$transaksi->auth}";

            if (! $dryRun) {
                Notification::updateOrCreate([
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

                $response = Http::withToken($secretKey)
                    ->acceptJson()
                    ->post("https://{$instanceId}.pushnotifications.pusher.com/publish_api/v1/instances/{$instanceId}/publishes", [
                        'interests' => [$interest],
                        'web' => [
                            'notification' => [
                                'title' => $title,
                                'body' => $message,
                            ],
                        ],
                    ]);

                if ($response->successful()) {
                    $sent++;
                    $this->info("Sent to {$interest}: {$kodeTransaksi}");
                } else {
                    $errors++;
                    $this->error("Failed for {$kodeTransaksi}: " . $response->body());
                }
            } else {
                $this->line("[DRY RUN] Would send to {$interest}: {$kodeTransaksi}");
                $sent++;
            }
        }

        $this->info("Done. Sent: {$sent}, Errors: {$errors}");
        return $errors > 0 ? self::FAILURE : self::SUCCESS;
    }
}