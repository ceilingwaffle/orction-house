<?php

namespace App\Transformers;

class AuctionsIndexTransformer extends BaseTransformer
{
    /**
     * Transforms a single auction result
     *
     * @param $auction
     * @return mixed
     */
    public function transform($auction)
    {
        return [
            'auction_id' => $auction->auction_id,
            'auction_title' => $auction->auction_title,
            'auction_time_remaining' => $auction->auction_end_date,
            'auction_status' => ucfirst($auction->auction_status),
            'auction_category' => $auction->auction_category,
            'auction_condition' => $auction->auction_condition,
            'auction_image' => $auction->auction_image,
            'auction_seller_username' => $auction->auction_creator_username,
            'total_bids' => $auction->total_bids,
            'highest_bid_amount' => $auction->highest_bid_amount,
            'highest_bidder_username' => $auction->highest_bidder_username,
            'seller_positive_feedback_percentage' => $auction->user_feedback_type_counts,
            'seller_feedback_link' => '#todo',
        ];
    }
}