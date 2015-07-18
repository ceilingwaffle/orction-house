<?php

use Illuminate\Database\Seeder;

class WinnerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $auctions = App\Auction::all(['id'])->shuffle();
        $users = App\User::all(['id']);

        foreach ($auctions as $auction) {

            // Insert a random number of auction winners
            if (rand(1,3) === 3) {
                App\Winner::create([
                    'auction_id' => $auction->id,
                    'user_id' => $users->random()->id,
                ]);
            }

        }
    }
}
