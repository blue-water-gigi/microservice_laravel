<?php

declare(strict_types=1);

namespace App\Contracts;

use App\DTO\Google\Subscription;

interface GoogleSubscriptionForwarder
{
    /**
     * Determines if the forwarder supports forwarding the given subscription.
     */
    public function supports(Subscription $subscription): bool;

    /**
     * Forwards the given subscription to the appropriate service.
     */
    public function forward(Subscription $subscription): void;
}
