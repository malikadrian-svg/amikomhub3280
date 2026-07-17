<?php

namespace App\Providers;

use App\Contracts\WhatsAppProviderInterface;
use App\Services\WhatsApp\FonnteProvider;
// use App\Services\WhatsApp\WablasProvider; // Can be added later
use Illuminate\Support\ServiceProvider;

class WhatsAppServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(WhatsAppProviderInterface::class, function ($app) {
            $provider = config('services.whatsapp.default', 'fonnte');

            switch ($provider) {
                // case 'wablas':
                //     return new WablasProvider();
                case 'fonnte':
                default:
                    return new FonnteProvider();
            }
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
