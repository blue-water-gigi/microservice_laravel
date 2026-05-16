<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\ErrorHandler;
use App\Contracts\GoogleSubscriptionForwarder;
use App\Contracts\WebhookHandler;
use App\DTO\Google\SubscriptionBuilder;
use App\Error\AppErrorHandler;
use App\Error\DebugErrorHandler;
use App\Forwarders\Google\SubscriptionChangeForwarder;
use App\Forwarders\Google\SubscriptionNoChangeForwarder;
use App\Forwarders\Google\SubscriptionRenewForwarder;
use App\Forwarders\Google\SubscriptionStartForwarder;
use App\Forwarders\Google\SubscriptionStopForwarder;
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
        // Platform webhook handlers
        $this->app->tag([
            AppleWebhookHandler::class,
            GoogleWebhookHandler::class,
        ], WebhookHandler::class);

        $this->app->bind(HandlerDelegator::class,
            fn(Application $app) => new HandlerDelegator($app->tagged(WebhookHandler::class)));

        // subscription category handlers
        $this->app->tag([
            SubscriptionStartForwarder::class,
            SubscriptionChangeForwarder::class,
            SubscriptionNoChangeForwarder::class,
            SubscriptionRenewForwarder::class,
            SubscriptionStopForwarder::class,
        ], GoogleSubscriptionForwarder::class);

        $this->app->bind(GoogleWebhookHandler::class,
            fn(Application $app) => new GoogleWebhookHandler(
                $app->make(SubscriptionBuilder::class),
                $app->tagged(GoogleSubscriptionForwarder::class))
        );

        $this->app->singleton(
            ErrorHandler::class,
            fn(Application $app) => app()->environment('production')
                ? $app->make(AppErrorHandler::class)
                : $app->make(DebugErrorHandler::class));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
