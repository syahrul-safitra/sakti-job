<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        \App\Models\Admin::create([
            'name' => 'Admin Sakti Job', 
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password')
        ]);

        // \App\Models\User::create([
        //     'full_name' => 'Ucup Markucup', 
        //     'phone' => '082387120435',
        //     'email' => 'ucup@gmail.com',
        //     'password' => bcrypt('password')
        // ]);
    }
}
