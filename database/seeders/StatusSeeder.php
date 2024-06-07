<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    private const STATUSES = ['todo', 'in progress', 'done'];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::STATUSES as $status) {
            Status::updateOrCreate(['name' => $status]);
        }
    }
}
