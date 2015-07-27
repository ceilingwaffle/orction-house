<?php

namespace App\Transformers\Auctions;

class AuctionUpdateTransformer extends AuctionBaseTransformer
{
    /**
     * Transforms auction data from input
     *
     * @param $auctionInput
     * @return mixed
     */
    public function transform($auctionInput)
    {
        $transformations = [
            'title' => ucfirst($auctionInput['title']),
            'description' => ucfirst($auctionInput['description']),
            'image_file_name' => $auctionInput['photo_file'],
            'auction_category_id' => $auctionInput['category_id'],
            'auction_condition_id' => $auctionInput['condition_id'],
            'delete_existing_photo' => $auctionInput['delete_existing_photo'],
        ];

        if (isset($auctionInput['date_ending'])) {
            $transformations['end_date'] = $this->createDateTimeStringFromFormat($auctionInput['date_ending']);
        }

        return $transformations;
    }

}