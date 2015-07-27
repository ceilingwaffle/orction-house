<?php

namespace App\Services;

use App;
use App\Repositories\AuctionRepository;
use App\Transformers\Auctions\AuctionStoreTransformer;
use App\Transformers\Auctions\AuctionUpdateTransformer;
use Exception;
use File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ListAuctionService
{
    /**
     * @var AuctionRepository
     */
    private $auctionRepo;

    public function __construct(AuctionRepository $auctionRepo)
    {
        $this->auctionRepo = $auctionRepo;
    }

    /**
     * Prepares a photo for storage and returns the name of the file
     *
     * @param UploadedFile $file
     * @return string
     */
    public function preparePhoto(UploadedFile $file)
    {
        // Prepare the photo storage directory
        $storageDir = $this->getAuctionImageDirectory();
        $this->createDirectory($storageDir);

        // Prepare the file name
        $fileName = $this->makeRandomFileName($file);
        $fileName = $this->makeUniqueFileName($file, $storageDir, $fileName);

        // Move the file to the storage directory
        $savedFile = $file->move($storageDir, $fileName);

        // Return the name of the file
        return $savedFile->getFilename();
    }

    /**
     * Transforms data from input and passes it to the repository for creating
     *
     * @param array $auctionData
     * @return mixed
     */
    public function createAuction(array $auctionData)
    {
        // Transform the data
        $transformer = App::make(AuctionStoreTransformer::class);
        $auctionData = $transformer->transform($auctionData);

        // Save the auction
        return $this->auctionRepo->createAuction($auctionData);
    }

    /**
     * Transforms data from input and passes it to the repository for updating
     *
     * @param $id
     * @param array $auctionData
     * @return mixed
     */
    public function updateAuction($id, array $auctionData)
    {
        // Transform the data
        $transformer = App::make(AuctionUpdateTransformer::class);
        $auctionData = $transformer->transform($auctionData);

        // Save the auction
        return $this->auctionRepo->updateAuction($id, $auctionData);
    }

    /**
     * Generates a random string
     *
     * @return string
     */
    protected function generateRandomString()
    {
        return md5(uniqid(rand(), true));
    }

    /**
     * Returns the path of the auction image directory
     *
     * @return string
     */
    protected function getAuctionImageDirectory()
    {
        return base_path() . '/' . getenv('AUCTION_IMAGE_DIRECTORY_PATH');
    }

    /**
     * Returns a random file name (with file extension)
     *
     * @param UploadedFile $file
     * @return string
     */
    protected function makeRandomFileName(UploadedFile $file)
    {
        return $this->generateRandomString() . '.' . $file->getClientOriginalExtension();
    }

    /**
     * Creates a directory if it does not already exist
     *
     * @param $storageDir
     */
    protected function createDirectory($storageDir)
    {
        if (!file_exists($storageDir)) {
            mkdir($storageDir, 0777, true);
        }
    }

    /**
     * @param UploadedFile $file
     * @param $storageDir
     * @param $fileName
     * @return string
     * @throws Exception
     */
    protected function makeUniqueFileName(UploadedFile $file, $storageDir, $fileName)
    {
        // Generate a new file name if this file already exists
        $i = 0;
        while (File::exists($storageDir . '/' . $fileName)) {

            if ($i === 100) {
                throw new Exception("Fatal error. Looped over 100 times attempting to create the unique auction image file name!");
            }

            $fileName = $this->makeRandomFileName($file);

            $i++;
        }

        return $fileName;
    }
}