<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DeploySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        //$this->call(UserTableSeeder::class);
        $this->call(AuctionCategoryTableSeeder::class);
        $this->call(AuctionConditionTableSeeder::class);
        //$this->call(AuctionTableSeeder::class);
        //$this->call(BidTableSeeder::class);
        $this->call(FeedbackTypeTableSeeder::class);
        //$this->call(FeedbackTableSeeder::class);
        //$this->call(WinnerTableSeeder::class);

        Model::reguard();
    }
}
