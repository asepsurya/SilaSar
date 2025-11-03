<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class UpdateController extends Controller
{
     public function index()
    {
          $logs = Activity::where(['causer_id'=>auth()->user()->id, 'log_name' => 'ikm'])->latest()->take(10)->get();
        return view('auth.update',[
            'activeMenu' => 'update',
            'active' => 'update',
        ],compact('logs'));
    }
    public function run(Request $request)
    {
        $commands = [
            'git fetch --all',
            'git reset --hard origin/main',
            'composer install --no-interaction --prefer-dist --optimize-autoloader',
            'php artisan migrate --force',
            'php artisan cache:clear',
            'php artisan config:cache',
        ];

        $output = [];

        foreach ($commands as $cmd) {
            $process = Process::fromShellCommandline($cmd, base_path());
            $process->run(function ($type, $buffer) use (&$output, $cmd) {
                $output[] = "> $cmd\n" . $buffer;
            });
        }

        return response()->json([
            'status' => 'ok',
            'log' => implode("\n", $output),
        ]);
    }
}
