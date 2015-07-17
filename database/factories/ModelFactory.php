<?php

use App\Common\BaseModel;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

// User
$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'username' => $faker->userName,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

// Auction
$factory->define(App\Auction::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->realText(20),
        'description' => $faker->sentence(30, $variableNumWords = true),
        'start_price' => $faker->randomFloat(2, 0, 99),
        'end_date' => $faker->dateTimeBetween('now', '+30 days'),
        'image_file_name' => $faker->image(
            getenv('AUCTION_IMAGE_DIRECTORY_PATH'),
            getenv('AUCTION_IMAGE_WIDTH'),
            getenv('AUCTION_IMAGE_HEIGHT'),
            'cats',
            $fullPath = false
        ),
        'user_id' => BaseModel::getRandomId(App\User::class),
        'auction_category_id' => BaseModel::getRandomId(App\AuctionCategory::class),
        'auction_condition_id' => BaseModel::getRandomId(App\AuctionCondition::class),
    ];
});

// Bid
//$factory->define(App\Bid::class, function (Faker\Generator $faker) {
//
//    $randomAuction = BaseModel::getRandomRecord(App\Auction::class);
//    $randomUser = BaseModel::getRandomRecord(App\User::class);
//
//    var_dump('id = ' . $randomAuction->id);
//
//    // Get the highest bid for this random auction
//    $highestBid = App\Bid::where('auction_id', '=', $randomAuction->id)
//                            ->orderBy('amount', 'desc')
//                            ->first();
//
//    if (is_null($highestBid)) {
//        var_dump('is null');
//        // Set a bid amount equal to any amount higher than the auction start price
//        $bidAmount = $faker->randomFloat(2, $randomAuction->start_price, 99);
//    } else {
//        var_dump('is NOT null');
//
//        // Set a bid amount equal to the highest bid plus a random amount between $0.50 and $20.00
//        $bidAmount = $faker->randomFloat(2, $highestBid->amount + 0.50, 20);
//    }
//
//    return [
//        'amount' => $bidAmount,
//        'auction_id' => $randomAuction->id,
//        'user_id' => $randomUser->id,
//    ];
//});