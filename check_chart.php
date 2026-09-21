<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$rows = DB::table('transaksis')->where('kode_transaksi', 'B2025126')->first();
echo "B2025126: ";
print_r($rows);

$allAuths = DB::table('transaksis')->distinct()->pluck('auth');
echo "\nAll auth IDs: ";
print_r($allAuths->toArray());
