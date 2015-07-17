<?php

use Illuminate\Database\Seeder;

class AuctionConditionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $conditions = ['Excellent', 'Very Good', 'Good', 'Poor', 'Very Poor', 'Awful'];

        foreach ($conditions as $condition) {
            App\AuctionCondition::create([
                'condition_name' => $condition,
            ]);
        }

    }
}
