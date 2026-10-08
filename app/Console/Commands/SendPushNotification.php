<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SendPushNotification extends Command
{
    protected $signature = 'push:send
                            {interests? : Comma-separated interests to target (default: hello)}
                            {--title=Hello : Notification title}
                            {--body=Hello, world! : Notification body}';

    protected $description = 'Send a web push notification via Pusher Beams';

    public function handle()
    {
        $instanceId = config('services.pusher_beams.instance_id');
        $secretKey = config('services.pusher_beams.secret_key');

        if (! $instanceId || ! $secretKey) {
            $this->error('Pusher Beams credentials are not configured.');
            return self::FAILURE;
        }

        $interests = array_map('trim', explode(',', $this->argument('interests') ?: 'hello'));

        $response = Http::withToken($secretKey)
            ->acceptJson()
            ->post("https://{$instanceId}.pushnotifications.pusher.com/publish_api/v1/instances/{$instanceId}/publishes", [
                'interests' => $interests,
                'web' => [
                    'notification' => [
                        'title' => $this->option('title'),
                        'body' => $this->option('body'),
                    ],
                ],
            ]);

        if ($response->successful()) {
            $this->info('Notification sent to [' . implode(', ', $interests) . ']');
            $this->info('publishId: ' . $response->json('publishId'));
            return self::SUCCESS;
        }

        $this->error('Failed (' . $response->status() . '): ' . $response->body());
        return self::FAILURE;
    }
}
