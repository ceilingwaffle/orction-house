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
        //$currentVisibleBid = $this->getCurrentVisibleBidForAuthUser($auction->auction_id);
        //$minimumBid = $this->calculateCurrentBidVisibleForUser($auction->auction_id, $auction->total_bids);

        return [
            'auction_id' => $auction->auction_id,
            'auction_title' => $auction->auction_title,
            'auction_has_ended' => $this->auctionHasEnded($auction->auction_id),
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
            'auction_ended_date' => $this->formatDate($auction->auction_end_date),
            'auction_created_date' => $this->formatDate($auction->auction_listed_at),
            'auction_created_date_readable' => $this->toHumanTimeDifference($auction->auction_listed_at),
            'auction_updated_date' => $this->formatDate($auction->auction_updated_at),
            'auction_updated_date_readable' => $this->toHumanTimeDifference($auction->auction_updated_at),
            'auction_description' => $auction->auction_description,
            //'current_visible_bid' => $this->toDecimalPlacesFormat($currentVisibleBid),
            //'user_minimum_bid' => $this->toDecimalPlacesFormat($minimumBid),
            'highest_bid_amount' => $this->transformMoney(Auction::determineCurrentAuctionPrice($auction->auction_start_price, $auction->highest_bid_amount)),
            'minimum_bid' => $this->transformMoney(Auction::determineMinimumBid($auction->auction_start_price, $auction->highest_bid_amount)),
            'start_price' => $auction->auction_start_price,
        ];
    }

    /**
     * Removes the $ from the beginning of the bid amount if it exists
     *
     * @param $bid
     * @return string
     */
    public function transformBid($bid)
    {
        return $this->transformMoney($bid);
    }

}