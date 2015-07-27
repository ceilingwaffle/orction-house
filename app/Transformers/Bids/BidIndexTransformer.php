<?php

namespace App\Transformers\Bids;

use App\Transformers\Auctions\AuctionBaseTransformer;

class BidIndexTransformer extends AuctionBaseTransformer
{
    /**
     * Transforms a single auction result
     *
     * @param $bid
     * @return mixed
     */
    public function transform($bid)
    {
        return [
            'bidder_username' => $bid->bidder_username,
            'bid_amount' => $this->transformToCurrencyString($bid->bid_amount),
            'bid_date_human' => $this->toHumanTimeDifference($bid->bid_created_at),
            'bid_date_full' => $this->formatAsPrettyDateAndTime($bid->bid_created_at),
            'bidder_positive_feedback_count' => $this->feedbackStringToPositiveCount($bid->feedback_type_counts),
//            'bidder_positive_feedback_percentage' => $this->feedbackStringToPercentage($bid->feedback_type_counts),
        ];
    }

}