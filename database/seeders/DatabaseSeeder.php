<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'run' => '12046474-4',
            'name' => 'Nelson Hereveri',
            'email' => 'nelson@hereveri.cl',
            'password' => bcrypt('pasopaso')
        ]);

        User::factory(10)->create();
    }
}
