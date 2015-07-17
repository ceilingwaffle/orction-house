<?php

use Illuminate\Database\Seeder;

class AuctionCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            'Relics',
            'Fashion',
            'Jewelery',
            'Books',
            'Furniture',
            'Appliances',
            'Electronics',
            'Computers',
        ];

        foreach ($categories as $category) {
            App\AuctionCategory::create([
                'category_name' => $category,
            ]);
        }

    }
}
