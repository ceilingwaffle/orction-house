<?php

use Illuminate\Database\Seeder;

class FeedbackTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            'Positive',
            'Neutral',
            'Negative',
        ];

        foreach ($types as $type) {
            App\FeedbackType::create([
                'type' => $type,
            ]);
        }
    }
}
