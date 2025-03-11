<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()
            ->afterCreating(function (User $user) {
                $user->permission()->update([
                    'can_vote' => false,
                    'is_supervisor' => true,
                    'is_admin' => true,
                ]);
            })
            ->create([
                'run' => '12046474-4',
                'name' => 'Nelson Hereveri',
                'email' => 'nelson@hereveri.cl',
                'password' => bcrypt('pasopaso'),
            ]);

        // Create regular users with default permissions
        User::factory(10)->create();
    }
}
