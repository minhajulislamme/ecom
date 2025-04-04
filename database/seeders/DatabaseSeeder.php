<?php

namespace Database\Seeders;

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
        // Call Users Table Seeder for initial admin users
        $this->call([
            UserSeeder::class

        ]);

        // Create additional fake users for testing
        User::factory(5)->create(); // Regular users
        User::factory(2)->admin()->create(); // Admin users
        User::factory(3)->inactive()->create(); // Inactive users
    }
}
