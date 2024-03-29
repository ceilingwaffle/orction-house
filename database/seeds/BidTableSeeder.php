<?php

use App\Auction;
use Illuminate\Database\Seeder;
use App\Bid;

class BidTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        $auctions = App\Auction::all();
        $users = App\User::all();

        // Total bids = n times the number of auctions
        $fakeRecordCount = $auctions->count() * 30;

        for ($i = 0; $i < $fakeRecordCount; $i++) {

            if ($i % 2 === 0) {
                // Half the auctions should have 0 bids
                continue;
            }

            $randomAuction = Auction::find($auctions->random()->id);
            $randomUser = $users->random();

            // Get the minimum bid allowed for this random auction
            $minBid = Auction::determineMinimumBid($randomAuction->start_price, $randomAuction->getHighestBid());

            // Set a bid amount equal to the minimum bid plus a random amount between $0 and $20
            $bidAmount = $faker->randomFloat(2, $minBid, $minBid + 20);

            // Insert the bid record
            Bid::create([
                'amount' => $bidAmount,
                'auction_id' => $randomAuction->id,
                'user_id' => $randomUser->id,
            ]);
        }

        // One random auction should have 0 bids
//        $auction = App\Auction::all()->random();
//        if ($auction) {
//            Bid::where('auction_id', '=', $auction->id)->delete();
//        }
    }
}
