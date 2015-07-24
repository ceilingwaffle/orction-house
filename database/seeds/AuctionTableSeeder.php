<?php

use Illuminate\Database\Seeder;

class AuctionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->purgeImages();

        factory(App\Auction::class, 20)->create();
    }

    /**
     * Deletes all images in the auction images directory
     */
    private function purgeImages()
    {
        $path = getenv('AUCTION_IMAGE_DIRECTORY_PATH');

        // Delete the files names in the folder
        $files = glob("{$path}/*");
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
}
