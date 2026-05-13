<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\SubscriptionEvent;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SubscriptionEventRepository
{
    /**
     * Retrieve a SubscriptionEvent record by its notification type and trial status.
     *
     * @param  int|string  $notificationType  The notification type to search for.
     * @param  bool  $inTrial  Indicates whether to include trial records in the search.
     * @return SubscriptionEvent Returns the found SubscriptionEvent.
     *
     * @throws ModelNotFoundException or throws a ModelNotFoundException.
     */
    public function findByNotificationType(
        int|string $notificationType,
        bool $inTrial = false): SubscriptionEvent
    {
        return SubscriptionEvent::query()
            ->where('notification_type', '=', $notificationType)
            ->where('in_trial', '=', $inTrial)
            ->firstOrFail();
    }
}
