<?php

namespace App\Transformers;

use App\Exceptions\UnexpectedFeedbackTypeStringFormatException;
use App\FeedbackType;
use Carbon\Carbon;

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
        ];
    }

    /**
     * Converts a feedback string from the database query (like 1,2,3:1,1,2)
     * to its positive-feedback percentage value (e.g. pos,neutral,neg:1,1,2 --> 1 out of 4 positive --> 25%)
     *
     * @param $feedbackString
     * @return mixed
     * @throws UnexpectedFeedbackTypeStringFormatException
     */
    private function feedbackStringToPercentage($feedbackString)
    {
        // Split the string into array values
        $feedbackTypesAndCounts = explode(':', $feedbackString);
        $feedbackTypes = explode(',', $feedbackTypesAndCounts[0]);
        $feedbackCounts = explode(',', $feedbackTypesAndCounts[1]);

        // We expect an equal number of feedback types to counts, otherwise
        // something went wrong with the database query result.
        if (count($feedbackTypes) != count($feedbackCounts)) {
            throw new UnexpectedFeedbackTypeStringFormatException();
        }

        // Group the feedback values into their feedback types
        $amounts = [];
        for ($i = 0; $i < count($feedbackTypes); $i++) {
            $amounts[$feedbackTypes[$i]] = $feedbackCounts[$i];
        }

        // Set the feedback count values (0 if not the feedback type is not set)
        $positive = (array_key_exists(FeedbackType::POSITIVE, $amounts) ? $amounts[FeedbackType::POSITIVE] : 0);
        $negative = (array_key_exists(FeedbackType::NEGATIVE, $amounts) ? $amounts[FeedbackType::NEGATIVE] : 0);

        // Calculate the percentage of "positive to negative" feedback counts (we ignore neutral feedback in this calculation)
        $total = $positive + $negative;

        if ($total === 0) {
            return null;
        }

        $percentage = round($positive / $total * 100);

        $pcString = $percentage . '%';

        return $pcString;
    }

    /**
     * Returns true if an auction has ended
     *
     * @param $auctionEndDateString
     * @return bool
     */
    private function auctionHasEnded($auctionEndDateString)
    {
        $dt = Carbon::createFromTimestamp(strtotime($auctionEndDateString));

        return $dt->isPast();
    }

    /**
     * Returns a human readable string like "2 minutes ago"
     *
     * @param $auctionEndDateString
     * @return string
     */
    private function toHumanTimeDifference($auctionEndDateString)
    {
        $dt = Carbon::createFromTimestamp(strtotime($auctionEndDateString));

        return $dt->diffForHumans();
    }
}