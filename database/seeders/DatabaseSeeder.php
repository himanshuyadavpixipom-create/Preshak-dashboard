<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => config('admin.email')],
            [
                'name' => config('admin.name'),
                'password' => \Illuminate\Support\Facades\Hash::make(config('admin.password')),
            ]
        );
    }
}
