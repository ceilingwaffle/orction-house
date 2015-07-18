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

    $randomUser = App\User::all()->random();
    $randomAuctionCategory = App\AuctionCategory::all()->random();
    $randomAuctionCondition = App\AuctionCondition::all()->random();

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
        'user_id' => $randomUser->id,
        'auction_category_id' => $randomAuctionCategory->id,
        'auction_condition_id' => $randomAuctionCondition->id,
    ];
});
