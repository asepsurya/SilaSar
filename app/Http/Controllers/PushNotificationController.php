<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PushNotificationController extends Controller
{
    public function send(Request $request)
    {
        $instanceId = config('services.pusher_beams.instance_id');
        $secretKey = config('services.pusher_beams.secret_key');

        $payload = [
            'interests' => $request->input('interests', ['hello']),
            'web' => [
                'notification' => [
                    'title' => $request->input('title', 'Hello'),
                    'body' => $request->input('body', 'Hello, world!'),
                ],
            ],
        ];

        $response = Http::withToken($secretKey)
            ->acceptJson()
            ->post("https://{$instanceId}.pushnotifications.pusher.com/publish_api/v1/instances/{$instanceId}/publishes", $payload);

        return response()->json([
            'success' => $response->successful(),
            'data' => $response->json(),
        ], $response->status());
    }
}
