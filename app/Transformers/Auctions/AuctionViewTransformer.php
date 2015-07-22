<?php

namespace App\Transformers\Auctions;

class AuctionViewTransformer extends AuctionTransformer
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
            'auction_has_ended' => $this->auctionHasEnded($auction->auction_end_date),
            'auction_time_remaining' => $this->toHumanTimeDifference($auction->auction_end_date),
            'auction_status' => ucfirst($auction->auction_status),
            'auction_category' => $auction->auction_category,
            'auction_condition' => $auction->auction_condition,
            'auction_image' => $auction->auction_image,
            'auction_seller_username' => $auction->auction_creator_username,
            'total_bids' => $auction->total_bids,
            'highest_bid_amount' => $auction->highest_bid_amount,
            'highest_bidder_username' => $auction->highest_bidder_username,
            'seller_positive_feedback_percentage' => $this->feedbackStringToPercentage($auction->user_feedback_type_counts),
            'seller_feedback_link' => '#todo',
            'seller_positive_feedback_count' => (int) $this->feedbackStringToPositiveCount($auction->user_feedback_type_counts),
            'auction_ended_date' => $this->formatDate($auction->auction_end_date),
            'auction_created_date' => $this->formatDate($auction->auction_listed_at),
            'auction_created_date_readable' => $this->toHumanTimeDifference($auction->auction_listed_at),
            'auction_updated_date' => $this->formatDate($auction->auction_updated_at),
            'auction_updated_date_readable' => $this->toHumanTimeDifference($auction->auction_updated_at),
            'auction_description' => $auction->auction_description,
        ];
    }

    /**
     * Returns a formatted date string, like: "22 Jul, 2015   12:34:56 PM"
     *
     * @param $dateString
     * @return bool|string
     */
    protected function formatDate($dateString)
    {
        return date('d M, Y    h:i:s a', strtotime($dateString));
    }
}