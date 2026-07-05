<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if clients already exist so we don't accidentally duplicate
        // demo data if the seeder is run multiple times manually.
        if (\App\Models\Client::count() === 0) {
            \App\Models\Client::factory(30)->create();
            $this->command->info('Created 30 demo clients successfully.');
        } else {
            $this->command->info('Clients already exist. Skipping seed to prevent duplicates.');
        }
    }
}
