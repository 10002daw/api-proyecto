<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\Community;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@email.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'admin' => 1,
        ]);
        // User::factory(50)
        //     ->hasAttached(
        //         Community::factory(),
        //         [
        //             'owner' => 1,
        //             'admin' => 1,
        //         ]
        //     )
        //     ->create();
        User::factory(50)
            ->create()
            ->each(function($user) {
                $communities = Community::factory(rand(0,2))->create();
                $user->communities()->attach($communities, ['owner' => 1, 'admin' => 1]);
            });
    }
}
