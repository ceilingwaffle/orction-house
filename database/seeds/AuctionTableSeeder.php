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

        factory(App\Auction::class, 30)->create();
    }

    /**
     * Deletes all images in the auction images directory
     */
    private function purgeImages()
    {
        $path = getenv('AUCTION_IMAGE_DIRECTORY_PATH');

        $files = glob("{$path}/*"); // get all file names
        foreach($files as $file){ // iterate files
            if(is_file($file))
                unlink($file); // delete file
        }
    }
}
