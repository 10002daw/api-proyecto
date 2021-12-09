<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\Community;
use App\Models\Thread;
use App\Models\Post;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@email.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'admin' => 1,
        ]);
        $comm1 = Community::factory(1)
            ->hasThreads(5, ['user_id' => $admin->id])
            ->create();
        $admin->communities()->attach($comm1, ['owner' => 1, 'admin' => 1]);
        foreach ($comm1->first()->threads as $thread) {
            Post::factory(10, [
                'thread_id' => $thread->id, 
                'user_id' => $admin->id,
            ])->create();
        }

        $user1 = User::create([
            'name' => 'user',
            'email' => 'user@email.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'admin' => 0,
        ]);
        $comm = Community::factory(1)
            ->hasThreads(5, ['user_id' => $user1->id])
            ->create();
        $user1->communities()->attach($comm, ['owner' => 1, 'admin' => 1]);
        foreach ($comm->first()->threads as $thread) {
            Post::factory(10, [
                'thread_id' => $thread->id, 
                'user_id' => $user1->id,
            ])->create();
        }
        // User::factory(50)
        //     ->hasAttached(
        //         Community::factory(),
        //         [
        //             'owner' => 1,
        //             'admin' => 1,
        //         ]
        //     )
        //     ->create();
        $users = User::factory(50)
            ->create()
            ->each(function($user) {
                $communities = Community::factory(rand(0,2))
                    ->hasThreads(5, ['user_id' => $user->id])               
                    ->create();
                $user->communities()->attach($communities, ['owner' => 1, 'admin' => 1]);
            });
        
        // foreach($users as $user) {
        //     foreach($user->communities() as $community) {

        //     }
        // }
    }
}
