<?php

namespace Database\Seeders;

use App\Models\SubscriptionProvider;
use Illuminate\Database\Seeder;

class SubscriptionProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SubscriptionProvider::factory()->create(['name' => 'Google']);
        SubscriptionProvider::factory()->create(['name' => 'Apple']);
    }
}
