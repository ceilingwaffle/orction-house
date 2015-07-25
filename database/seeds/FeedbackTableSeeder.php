<?php

use App\Feedback;
use Illuminate\Database\Seeder;

class FeedbackTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        $auctions = App\Auction::all()->shuffle();
        $feedbackTypes = App\FeedbackType::all(['id']);

        foreach ($auctions as $auction) {

            // No feedback if the auction has not ended yet
            if (Carbon\Carbon::now()->lte(new Carbon\Carbon($auction->end_date))) {
                continue;
            }

            $auctionWithBids = $auction->where('id', '=', $auction->id)
                ->with([
                    'bids' => function ($query) {
                        $query->orderBy('amount', 'desc')->take(1);
                    }
                ])->first()->toArray();

            // No feedback if no bids were placed
            if (!isset($auctionWithBids['bids'][0])) {
                continue;
            }

            $highestBid = $auctionWithBids['bids'][0];

            // Insert a random number of feedback records
            if (rand(1, 2) == 2) {
                Feedback::create([
                    'auction_id' => $auction->id,
                    'left_by_user_id' => $highestBid['user_id'],
                    'message' => $faker->sentence(12, $variableNbWords = true),
                    'feedback_type_id' => $feedbackTypes->random()->id,
                ]);
            }
        }
    }
}
