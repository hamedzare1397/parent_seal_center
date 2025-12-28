<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Person;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::factory()->count(3)->create();

// کاربران
        User::factory()
            ->count(10)
            ->withRoles(2)
            ->create();

// ادمین سیستم
        User::factory()
            ->admin()
            ->create([
                'username' => 'admin',
            ]);

    }
}
