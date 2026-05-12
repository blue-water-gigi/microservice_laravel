<?php

use App\Models\SubscriptionProvider;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscription_events', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(SubscriptionProvider::class)->constrained()->cascadeOnDelete();
            $table->string('name', 255);
            $table->string('category', 255); // enum
            $table->integer('notification_type');
            $table->boolean('in_trial')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_events');
    }
};
