<?php

use App\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create a consistent user
        User::create([
            'username' => 'waffle',
            'password' => bcrypt('abc123'),
            'remember_token' => null,
        ]);

        // Create 9 random users
        factory(App\User::class, 3)->create();
    }
}
