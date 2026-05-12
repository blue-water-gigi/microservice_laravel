<?php

declare(strict_types=1);

namespace App\Models;

use App\DTO\SubscriptionEventCategory;
use Database\Factories\SubscriptionEventFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;

/**
 * @property string $name
 * @property SubscriptionEventCategory $category
 * @property int $notification_type
 * @property bool $in_trial
 * @property int $subscription_provider_id
 */
#[Fillable(['subscription_provider_id', 'name', 'category', 'notification_type', 'in_trial'])]
class SubscriptionEvent extends Model
{
    /** @use HasFactory<SubscriptionEventFactory> */
    use HasFactory;

    // casting
    /**
     * @return BelongsTo<SubscriptionProvider, $this>
     */
    public function provider(): BelongsTo
    {
        return $this->belongsTo(SubscriptionProvider::class);
    }

    // relations

    #[Override]
    protected function casts(): array
    {
        return [
            'in_trial' => 'boolean',
            'category' => SubscriptionEventCategory::class,
        ];
    }
}
