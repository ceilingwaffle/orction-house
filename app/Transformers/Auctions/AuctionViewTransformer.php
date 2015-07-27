<?php

namespace App\Transformers\Auctions;

use App\Auction;

class AuctionViewTransformer extends AuctionBaseTransformer
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
            'auction_has_ended' => Auction::auctionHasEnded($auction->auction_status),
            'auction_time_remaining' => $this->toHumanTimeDifference($auction->auction_end_date),
            'auction_status' => ucfirst($auction->auction_status),
            'auction_category' => $auction->auction_category,
            'auction_condition' => $auction->auction_condition,
            'auction_image' => $auction->auction_image,
            'auction_seller_username' => $auction->auction_creator_username,
            'total_bids' => $auction->total_bids,
            'highest_bidder_username' => $auction->highest_bidder_username,
            'seller_positive_feedback_percentage' => $this->feedbackStringToPercentage($auction->user_feedback_type_counts),
            'seller_feedback_link' => '#todo',
            'seller_positive_feedback_count' => $this->feedbackStringToPositiveCount($auction->user_feedback_type_counts),
            'auction_ended_date' => $this->formatAsPrettyDateAndTime($auction->auction_end_date),
            'auction_created_date' => $this->formatAsPrettyDateAndTime($auction->auction_listed_at),
            'auction_created_date_readable' => $this->toHumanTimeDifference($auction->auction_listed_at),
            'auction_updated_date' => $this->formatAsPrettyDateAndTime($auction->auction_updated_at),
            'auction_updated_date_readable' => $this->toHumanTimeDifference($auction->auction_updated_at),
            'auction_description' => $auction->auction_description,
            'highest_bid_amount' => $this->transformToCurrencyString(Auction::determineCurrentAuctionPrice($auction->auction_start_price, $auction->highest_bid_amount)),
            'minimum_bid' => $this->transformToCurrencyString(Auction::determineMinimumBid($auction->auction_start_price, $auction->highest_bid_amount)),
            'start_price' => $auction->auction_start_price,
        ];
    }

//    /**
//     * Removes the $ from the beginning of the bid amount if it exists
//     *
//     * @param $bid
//     * @return string
//     */
//    public function transformBid($bid)
//    {
//        return $this->transformCurrencyStringToFloat($bid);
//    }

}