<?php

namespace App\Transformers\Auctions;

class AuctionEditTransformer extends AuctionBaseTransformer
{
    /**
     * Transforms auction data to be displayed in the auction edit form
     *
     * @param $auction
     * @return mixed
     */
    public function transform($auction)
    {
        return [
            'title' => $auction->auction_title,
            'description' => $auction->auction_description,
            'category_id' => $auction->auction_category_id,
            'condition_id' => $auction->auction_condition_id,
            'start_price' => $this->transformToCurrencyString($auction->auction_start_price),
            'end_date' => $this->convertDateTimeStringToDate($auction->auction_end_date),
            'image_file_name' => $auction->auction_photo_file_name,
            'ending_today' => $this->dateIsToday($auction->auction_end_date),
        ];
    }
}