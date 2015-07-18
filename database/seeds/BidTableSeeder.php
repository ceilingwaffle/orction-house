<?php

use App\Common\BaseModel;
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

        $fakeRecordCount = 120;

        for ($i = 0; $i < $fakeRecordCount; $i++) {

            $randomAuction = BaseModel::getRandomRecord(App\Auction::class);
            $randomUser = BaseModel::getRandomRecord(App\User::class);

            // Get the minimum bid allowed for this random auction
            $minBid = $randomAuction->calculateMinimumBidForUser($randomUser);

            // Set a bid amount equal to the minimum bid plus a random amount between $0 and $20
            $bidAmount = $faker->randomFloat(2, $minBid, $minBid + 20);

            // Insert the bid record
            Bid::create([
                'amount' => $bidAmount,
                'auction_id' => $randomAuction->id,
                'user_id' => $randomUser->id,
            ]);
        }
    }
}
