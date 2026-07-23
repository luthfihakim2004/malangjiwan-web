<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\URL;
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
        \Carbon\Carbon::setLocale('id');

        Relation::morphMap([
            'wisata' => \App\Models\Wisata::class,
            'umkm'   => \App\Models\Umkm::class,
            'post'   => \App\Models\Post::class,
            'profil' => \App\Models\Profile::class,
        ]);

        // Force HTTPS when accessed via tunnel
        if (request()->isSecure() || str_contains(request()->getHost(), 'trycloudflare.com')) {
            URL::forceScheme('https');
        }
    }
}
