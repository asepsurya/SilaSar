<?php

namespace App\Providers;

use App\Models\App;
use App\Models\User;
use App\Policies\AdminPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
         if (config('app.env') === 'production') { URL::forceScheme('https'); }

        Gate::policy(User::class, AdminPolicy::class);
         // App Settings
      $this->app->singleton('settings', function () {
            $userId = auth()->user()->id;
            if (!$userId) return [];

            return Cache::rememberForever("settings_{$userId}", function () use ($userId) {
                return App::where('auth', $userId)
                    ->pluck('value', 'key') // Format: ['key' => 'value']
                    ->toArray();
            });
        });
        

    }
}
