<?php

namespace App\Transformers\Auctions;

use Carbon\Carbon;

class AuctionCreateTransformer extends AuctionBaseTransformer
{
    /**
     * Transforms auction data from input
     *
     * @param $auctionInput
     * @return mixed
     */
    public function transform($auctionInput)
    {
        return [
            'title' => ucfirst($auctionInput['title']),
            'description' => ucfirst($auctionInput['description']),
            'start_price' => $this->transformMoney($auctionInput['start_price']),
            'image_file_name' => $auctionInput['photo_file'],
            'end_date' => Carbon::now()->addDay($auctionInput['future_days_to_end'])->toDateTimeString(),
            'auction_category_id' => $auctionInput['category_id'],
            'auction_condition_id' => $auctionInput['condition_id'],
            'user_id' => $auctionInput['user_id'],
        ];
    }

}