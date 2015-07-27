<?php

namespace App\Transformers\Auctions;

class AuctionStoreTransformer extends AuctionBaseTransformer
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
            'user_id' => $auctionInput['user_id'],
            'start_price' => $this->transformCurrencyStringToFloat($auctionInput['start_price']),
            'end_date' => $this->createDateTimeStringFromFormat($auctionInput['date_ending']),
        ];

        return $transformations;
    }

}