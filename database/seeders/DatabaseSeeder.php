<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Client;
use App\Models\Table;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        User::factory()->create([
            'name' => 'Test2 User',
            'email' => 'test2@example.com',
        ]);

        User::factory()->create([
            'name' => 'Test3 User',
            'email' => 'test3@example.com',
        ]);

        Client::factory(10)->create();
        Address::factory(10)->create();
        Table::factory(40)->create();
    }
}
