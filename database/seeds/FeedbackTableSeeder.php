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

        $auctions = App\Auction::all(['id'])->shuffle();
        $feedbackTypes = App\FeedbackType::all(['id']);

        foreach ($auctions as $auction) {

            // Insert a random number of feedback records
            if (rand(1,2) == 2) {
                Feedback::create([
                    'auction_id' => $auction->id,
                    'message' => $faker->sentence(12, $variableNbWords = true),
                    'feedback_type_id' => $feedbackTypes->random()->id,
                ]);
            }

        }

    }
}
