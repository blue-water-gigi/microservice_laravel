<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\WebhookHandler;
use App\Handlers\AppleWebhookHandler;
use App\Handlers\GoogleWebhookHandler;
use App\Handlers\HandlerDelegator;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Override;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[Override]
    public function register(): void
    {
        $this->app->tag([
            AppleWebhookHandler::class,
            GoogleWebhookHandler::class,
        ], WebhookHandler::class);

        $this->app->bind(HandlerDelegator::class,
            fn (Application $app) => new HandlerDelegator($app->tagged(WebhookHandler::class)));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
