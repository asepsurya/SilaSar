<?php
$url = 'https://myollama.inopakinstitute.or.id/api/generate';
$payload = json_encode([
    'model' => 'gpt-oss:120b-cloud',
    'prompt' => 'hi',
    'stream' => true
]);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json',
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$result = curl_exec($ch);

if ($result === false) {
    echo "cURL Error: " . curl_error($ch) . "\n";
} else {
    $info = curl_getinfo($ch);
    echo "HTTP Status: " . $info['http_code'] . "\n";
    echo "Response: " . substr($result, 0, 500) . "\n";
}

curl_close($ch);
