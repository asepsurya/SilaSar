<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiController extends Controller
{
    /**
     * Proxy request to Ollama API to avoid CORS/OPTIONS issues.
     */
    public function generate(Request $request)
    {
        $url = config('services.ollama.url');

        // Use a stream response to pass-through the Ollama stream
        return response()->stream(function () use ($url, $request) {
            $ch = curl_init($url);

            // Prepare the request body
            $payload = json_encode($request->all());

            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Accept: application/json',
            ]);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

            // Set timeout to handle long AI generations
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);

            // This function is called for every chunk of data received
            curl_setopt($ch, CURLOPT_WRITEFUNCTION, function ($ch, $data) {
                echo $data;
                // Flush the output buffer to send data to the client immediately
                if (ob_get_level() > 0) {
                    ob_flush();
                }
                flush();
                return strlen($data);
            });

            $result = curl_exec($ch);
            
            if ($result === false) {
                echo json_encode(['error' => curl_error($ch)]);
            }
            
            curl_close($ch);
        }, 200, [
            'Content-Type' => 'application/json',
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no', // Disable buffering in Nginx if present
        ]);
    }
}
