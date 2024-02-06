<?php

namespace Database\Seeders;

use App\Models\ResultStatus;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(1)->create();

        foreach (config('result-status.status') as $status) {
            ResultStatus::create([
                'status' => $status
            ]);
        }
    }
}
